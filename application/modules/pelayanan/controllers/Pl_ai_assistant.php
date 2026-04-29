<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_ai_assistant extends MX_Controller {

    function __construct() {
        parent::__construct();

        // Session check
        if ($this->session->userdata('logged') != TRUE) {
            $this->output->set_status_header(401);
            echo json_encode(array('status' => 401, 'message' => 'Session expired'));
            exit;
        }
    }

    /**
     * POST /pelayanan/Pl_ai_assistant/structure_handover
     *
     * Menerima transkripsi suara dan mengembalikan semua field SBAR Hand Over
     * yang sudah dipetakan oleh Claude AI.
     *
     * POST params:
     *   transcription (string) - teks transkripsi suara petugas
     *
     * Response JSON:
     *   { status: 200, data: { diagnosa_masuk, nama_pasien, no_rm, umur,
     *     ruangan, keluhan, riwayat_penyakit, alergi, terapi_dpjp,
     *     kesadaran, td, nadi, nafas, suhu, fisik, lab, iv_lines, xray,
     *     tindakan, instruksi_dokter } }
     */
    public function structure_handover() {
        if ($this->input->method() !== 'post') {
            echo json_encode(array('status' => 405, 'message' => 'Method not allowed'));
            return;
        }

        $transcription = trim($this->input->post('transcription'));

        if (empty($transcription)) {
            echo json_encode(array('status' => 400, 'message' => 'Teks transkripsi tidak boleh kosong'));
            return;
        }

        $this->config->load('ai', TRUE);
        $api_key = $this->config->item('anthropic_api_key', 'ai');
        $model   = $this->config->item('anthropic_model', 'ai');
        $api_url = $this->config->item('anthropic_api_url', 'ai');
        $timeout = $this->config->item('anthropic_timeout', 'ai');

        if (empty($api_key) || $api_key === 'YOUR_ANTHROPIC_API_KEY_HERE') {
            echo json_encode(array('status' => 500, 'message' => 'API key belum dikonfigurasi. Hubungi administrator.'));
            return;
        }

        $prompt =
            "Kamu adalah asisten medis untuk rumah sakit. " .
            "Dari transkripsi suara petugas berikut, ekstrak setiap informasi dan petakan ke field formulir Hand Over Pasien format SBAR.\n\n" .
            "Transkripsi:\n" . $transcription . "\n\n" .
            "Kembalikan HANYA JSON valid tanpa komentar, tanpa markdown, tanpa teks lain:\n" .
            "{\n" .
            "  \"diagnosa_masuk\": \"\",\n" .
            "  \"nama_pasien\": \"\",\n" .
            "  \"no_rm\": \"\",\n" .
            "  \"umur\": \"\",\n" .
            "  \"ruangan\": \"\",\n" .
            "  \"keluhan\": \"\",\n" .
            "  \"riwayat_penyakit\": \"\",\n" .
            "  \"alergi\": \"\",\n" .
            "  \"terapi_dpjp\": \"\",\n" .
            "  \"kesadaran\": \"\",\n" .
            "  \"td\": \"\",\n" .
            "  \"nadi\": \"\",\n" .
            "  \"nafas\": \"\",\n" .
            "  \"suhu\": \"\",\n" .
            "  \"fisik\": \"\",\n" .
            "  \"lab\": \"\",\n" .
            "  \"iv_lines\": \"\",\n" .
            "  \"xray\": \"\",\n" .
            "  \"tindakan\": \"\",\n" .
            "  \"instruksi_dokter\": \"\"\n" .
            "}\n\n" .
            "Panduan pengisian:\n" .
            "- Petakan setiap informasi ke field yang paling sesuai berdasarkan konteks medis\n" .
            "- Petugas membacakan sesuai urutan label; gunakan itu sebagai petunjuk pemetaan\n" .
            "- kesadaran harus salah satu dari: Compos Mentis, Apatis, Somnolen, Sopor, Koma\n" .
            "- td dalam format mmHg (contoh: 120/80), nadi dan nafas dalam x/menit, suhu dalam °C\n" .
            "- umur hanya angka tahun\n" .
            "- Kosongkan field (string kosong \"\") jika tidak ada informasi\n" .
            "- Gunakan bahasa Indonesia medis formal";

        $payload = json_encode(array(
            'model'      => $model,
            'max_tokens' => 2048,
            'messages'   => array(
                array('role' => 'user', 'content' => $prompt)
            )
        ));

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL            => $api_url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POST           => TRUE,
            CURLOPT_HTTPHEADER     => array(
                'Content-Type: application/json',
                'x-api-key: ' . $api_key,
                'anthropic-version: 2023-06-01'
            ),
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => (int)$timeout,
            CURLOPT_SSL_VERIFYPEER => FALSE,
        ));

        $response  = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_err  = curl_error($ch);
        curl_close($ch);

        if ($curl_err) {
            echo json_encode(array('status' => 500, 'message' => 'Gagal menghubungi API: ' . $curl_err));
            return;
        }

        if ($http_code !== 200) {
            $err_body = json_decode($response, TRUE);
            $err_msg  = isset($err_body['error']['message']) ? $err_body['error']['message'] : 'HTTP ' . $http_code;
            echo json_encode(array('status' => 500, 'message' => 'Claude API error: ' . $err_msg));
            return;
        }

        $claude_resp = json_decode($response, TRUE);
        $raw_content = isset($claude_resp['content'][0]['text']) ? trim($claude_resp['content'][0]['text']) : '';

        if (empty($raw_content)) {
            echo json_encode(array('status' => 500, 'message' => 'Respon AI kosong'));
            return;
        }

        $json_start = strpos($raw_content, '{');
        $json_end   = strrpos($raw_content, '}');
        if ($json_start !== FALSE && $json_end !== FALSE) {
            $raw_content = substr($raw_content, $json_start, $json_end - $json_start + 1);
        }

        $fields = json_decode($raw_content, TRUE);
        if (!is_array($fields)) {
            echo json_encode(array('status' => 500, 'message' => 'Format respon AI tidak valid. Coba lagi.'));
            return;
        }

        $allowed = array(
            'diagnosa_masuk', 'nama_pasien', 'no_rm', 'umur', 'ruangan',
            'keluhan', 'riwayat_penyakit', 'alergi', 'terapi_dpjp',
            'kesadaran', 'td', 'nadi', 'nafas', 'suhu', 'fisik', 'lab', 'iv_lines', 'xray',
            'tindakan', 'instruksi_dokter'
        );

        $result = array();
        foreach ($allowed as $k) {
            $result[$k] = isset($fields[$k]) ? $fields[$k] : '';
        }

        echo json_encode(array('status' => 200, 'data' => $result));
    }

    /**
     * POST /pelayanan/Pl_ai_assistant/structure_soap
     *
     * Menerima teks transkripsi dan mengembalikan struktur SOAP via Claude AI.
     * Juga menyertakan riwayat SOAP terakhir pasien dengan dokter yang sama sebagai
     * konteks klinis ke AI dan sebagai data perbandingan di modal.
     *
     * POST params:
     *   transcription  (string) - teks transkripsi percakapan/catatan dokter
     *   context        (string) - opsional, konteks tambahan
     *   no_mr          (string) - nomor rekam medis pasien
     *   kode_dokter    (string) - kode dokter pemeriksa
     *   no_kunjungan   (string) - no kunjungan saat ini (untuk exclude dari query riwayat)
     *
     * Response JSON:
     *   { status: 200, data: { subjektif, objektif, assessment, planning }, soap_sebelumnya: {...}|null }
     */
    public function structure_soap() {
        // Only accept POST
        if ($this->input->method() !== 'post') {
            echo json_encode(array('status' => 405, 'message' => 'Method not allowed'));
            return;
        }

        $transcription  = trim($this->input->post('transcription'));
        $context        = trim($this->input->post('context'));
        $no_mr          = trim($this->input->post('no_mr'));
        $kode_dokter    = trim($this->input->post('kode_dokter'));
        $no_kunjungan   = trim($this->input->post('no_kunjungan'));

        if (empty($transcription)) {
            echo json_encode(array('status' => 400, 'message' => 'Teks transkripsi tidak boleh kosong'));
            return;
        }

        // -----------------------------------------------------------------
        // Query SOAP kunjungan terakhir: pasien + dokter yang sama, sebelum
        // kunjungan saat ini
        // -----------------------------------------------------------------
        $soap_prev = NULL;
        if (!empty($no_mr)) {
            $q = $this->db
                ->select('rp.anamnesa, rp.pemeriksaan, rp.diagnosa_akhir, rp.pengobatan, k.tgl_masuk')
                ->from('th_riwayat_pasien rp')
                ->join('tc_kunjungan k', 'k.no_kunjungan = rp.no_kunjungan', 'inner')
                ->where('rp.no_mr', $no_mr);

            if (!empty($kode_dokter)) {
                $q->where('k.kode_dokter', $kode_dokter);
            }
            if (!empty($no_kunjungan)) {
                $q->where('rp.no_kunjungan !=', $no_kunjungan);
            }

            $prev_row = $q->order_by('k.tgl_masuk', 'DESC')->limit(1)->get()->row_array();

            if (!empty($prev_row)) {
                $soap_prev = array(
                    'subjektif'     => $prev_row['anamnesa']       ? $prev_row['anamnesa']       : '',
                    'objektif'      => $prev_row['pemeriksaan']    ? $prev_row['pemeriksaan']    : '',
                    'assessment'    => $prev_row['diagnosa_akhir'] ? $prev_row['diagnosa_akhir'] : '',
                    'planning'      => $prev_row['pengobatan']     ? $prev_row['pengobatan']     : '',
                    'tgl_kunjungan' => $prev_row['tgl_masuk']      ? $prev_row['tgl_masuk']      : '',
                );
            }
        }

        // Load AI config
        $this->config->load('ai', TRUE);
        $api_key  = $this->config->item('anthropic_api_key', 'ai');
        $model    = $this->config->item('anthropic_model', 'ai');
        $api_url  = $this->config->item('anthropic_api_url', 'ai');
        $max_tok  = $this->config->item('anthropic_max_tokens', 'ai');
        $timeout  = $this->config->item('anthropic_timeout', 'ai');

        if (empty($api_key) || $api_key === 'YOUR_ANTHROPIC_API_KEY_HERE') {
            echo json_encode(array('status' => 500, 'message' => 'API key belum dikonfigurasi. Hubungi administrator.'));
            return;
        }

        // -----------------------------------------------------------------
        // Build prompt — sertakan SOAP sebelumnya sebagai konteks klinis
        // -----------------------------------------------------------------
        $context_block = '';
        if (!empty($context)) {
            $context_block = "\nKonteks tambahan: " . $context . "\n";
        }

        $soap_prev_block = '';
        if (!empty($soap_prev)) {
            $tgl_label = !empty($soap_prev['tgl_kunjungan'])
                ? date('d/m/Y', strtotime($soap_prev['tgl_kunjungan']))
                : 'kunjungan sebelumnya';
            $soap_prev_block =
                "\n\nRiwayat SOAP kunjungan terakhir pasien dengan dokter yang sama (" . $tgl_label . "):\n" .
                "- Subjektif  : " . ($soap_prev['subjektif']  ?: '-') . "\n" .
                "- Objektif   : " . ($soap_prev['objektif']   ?: '-') . "\n" .
                "- Assessment : " . ($soap_prev['assessment'] ?: '-') . "\n" .
                "- Planning   : " . ($soap_prev['planning']   ?: '-') . "\n" .
                "Gunakan riwayat ini sebagai referensi klinis dalam menyusun SOAP yang baru.\n";
        }

        $prompt = "Kamu adalah asisten medis untuk rumah sakit Setia Mitra. " .
                  "Berdasarkan catatan atau transkripsi berikut, susun informasi SOAP (Subjektif, Objektif, Assessment, Planning) medis.\n" .
                  $context_block .
                  $soap_prev_block .
                  "\nInput transkripsi:\n" . $transcription . "\n\n" .
                  "Kembalikan HANYA JSON valid tanpa komentar, tanpa markdown, tanpa teks lain:\n" .
                  "{\"subjektif\":\"...\",\"objektif\":\"...\",\"assessment\":\"...\",\"planning\":\"...\"}\n\n" .
                  "Panduan pengisian:\n" .
                  "- subjektif: keluhan utama, riwayat penyakit sekarang, yang disampaikan pasien\n" .
                  "- objektif: temuan pemeriksaan fisik, tanda vital, hasil lab/penunjang\n" .
                  "- assessment: diagnosis klinis atau kesan dokter\n" .
                  "- planning: tatalaksana, terapi, rencana pemeriksaan lanjutan\n" .
                  "Gunakan bahasa Indonesia medis formal. Kosongkan field (string kosong \"\") jika tidak ada informasi.";

        $payload = json_encode(array(
            'model'      => $model,
            'max_tokens' => (int)$max_tok,
            'messages'   => array(
                array('role' => 'user', 'content' => $prompt)
            )
        ));

        // Call Claude API via cURL
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL            => $api_url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POST           => TRUE,
            CURLOPT_HTTPHEADER     => array(
                'Content-Type: application/json',
                'x-api-key: ' . $api_key,
                'anthropic-version: 2023-06-01'
            ),
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => (int)$timeout,
            CURLOPT_SSL_VERIFYPEER => FALSE,
        ));

        $response   = curl_exec($ch);
        $http_code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            echo json_encode(array('status' => 500, 'message' => 'Gagal menghubungi API: ' . $curl_error));
            return;
        }

        if ($http_code !== 200) {
            $err_body = json_decode($response, TRUE);
            $err_msg  = isset($err_body['error']['message']) ? $err_body['error']['message'] : 'HTTP ' . $http_code;
            echo json_encode(array('status' => 500, 'message' => 'Claude API error: ' . $err_msg));
            return;
        }

        // Parse Claude response
        $claude_resp = json_decode($response, TRUE);
        $raw_content = isset($claude_resp['content'][0]['text']) ? trim($claude_resp['content'][0]['text']) : '';

        if (empty($raw_content)) {
            echo json_encode(array('status' => 500, 'message' => 'Respon AI kosong'));
            return;
        }

        // Extract JSON from response (in case AI adds extra text)
        $json_start = strpos($raw_content, '{');
        $json_end   = strrpos($raw_content, '}');
        if ($json_start !== FALSE && $json_end !== FALSE) {
            $raw_content = substr($raw_content, $json_start, $json_end - $json_start + 1);
        }

        $soap = json_decode($raw_content, TRUE);
        if (!is_array($soap)) {
            echo json_encode(array('status' => 500, 'message' => 'Format respon AI tidak valid. Coba lagi.'));
            return;
        }

        $result = array(
            'subjektif'  => isset($soap['subjektif'])  ? $soap['subjektif']  : '',
            'objektif'   => isset($soap['objektif'])   ? $soap['objektif']   : '',
            'assessment' => isset($soap['assessment']) ? $soap['assessment'] : '',
            'planning'   => isset($soap['planning'])   ? $soap['planning']   : '',
        );

        echo json_encode(array(
            'status'          => 200,
            'data'            => $result,
            'soap_sebelumnya' => $soap_prev,
        ));
    }
}
