<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Service extends MX_Controller {

    function __construct() {

        date_default_timezone_set("Asia/Jakarta");
        parent::__construct();

        // Load credentials from config — never hardcode in controller
        $this->load->config('api_service');
        $this->username     = $this->config->item('api_username');
        $this->password     = $this->config->item('api_password');
        $this->kode_faskses = $this->config->item('api_kode_faskes');
        $this->token_secret = $this->config->item('api_token_secret');

        // CORS — apply after BASEPATH check, based on config
        $allowed_origins = $this->config->item('api_allowed_origins');
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        if (in_array('*', $allowed_origins)) {
            header('Access-Control-Allow-Origin: *');
        } elseif (!empty($origin) && in_array($origin, $allowed_origins)) {
            header('Access-Control-Allow-Origin: ' . $origin);
        }

        // All responses from this controller are JSON
        header('Content-Type: application/json');

        // load models
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        $this->load->model('ws/AntrianOnlineModel', 'AntrianOnline');
        $this->load->model('booking/Regon_booking_model', 'Regon_booking');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        $this->load->module('templates/References');
        $this->load->library('Daftar_pasien');
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos');

    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    /**
     * Generate HMAC-SHA256 token.
     * Replaces the old MD5(username/password) approach.
     */
    private function _makeToken() {
        return hash_hmac('sha256', $this->username . '/' . $this->password, $this->token_secret);
    }

    /**
     * Generate a time-limited signed embed token for a specific no_mr.
     * Format: base64(nomr|expire_unix) + '.' + hmac_sha256(nomr|expire_unix, secret)
     *
     * @param string $nomr  No. Rekam Medis
     * @param int    $ttl   Time-to-live in seconds (default 1800 = 30 min)
     * @return string
     */
    private function _generateEmbedToken($nomr, $ttl = 1800) {
        $expire  = time() + $ttl;
        $raw     = $nomr . '|' . $expire;
        $payload = base64_encode($raw);
        $sig     = hash_hmac('sha256', $raw, $this->token_secret);
        return $payload . '.' . $sig;
    }

    /**
     * Emit a JSON response and exit.
     *
     * @param int    $code    Application code (200 = success, 201+ = error)
     * @param string $message Human-readable message
     * @param mixed  $data    Optional response payload
     */
    private function _respond($code, $message, $data = null) {
        $response = [
            'metadata' => [
                'code'    => $code,
                'message' => $message,
            ],
        ];
        if ($data !== null) {
            $response['response'] = $data;
        }
        echo json_encode($response);
        exit;
    }

    /**
     * Parse and validate the raw JSON request body.
     * Exits with a 400 response if the body is not valid JSON.
     *
     * @return stdClass
     */
    private function _parseBody() {
        $content = file_get_contents('php://input');
        $post    = json_decode($content);
        if ($post === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->_respond(400, 'Request body harus berformat JSON yang valid.');
        }
        return $post ?: new stdClass();
    }

    // -------------------------------------------------------------------------
    // AUTH
    // -------------------------------------------------------------------------

    public function getToken() {

        $headers  = $this->input->request_headers();
        $username = isset($headers['x-username']) ? $headers['x-username'] : '';
        $password = isset($headers['x-password']) ? $headers['x-password'] : '';

        $post           = new stdClass();
        $post->username = $username;
        $post->password = $password;

        try {
            $token = $this->checkAccount($post);
            $this->_respond(200, 'Sukses', ['token' => $token]);
        } catch (Exception $err) {
            // Generic message — do not reveal which credential is wrong
            $this->_respond(401, 'Username atau password yang anda masukan salah.');
        }
    }

    public function checkAccount($post) {

        // hash_equals: constant-time comparison to prevent timing attacks
        $usernameMatch = hash_equals($this->username, (string) $post->username);
        $passwordMatch = hash_equals($this->password, (string) $post->password);

        if (!$usernameMatch || !$passwordMatch) {
            throw new Exception('Incorrect credentials');
        }

        return $this->_makeToken();
    }

    public function checkToken($header) {

        $tokenSent    = isset($header['x-token'])    ? (string) $header['x-token']    : '';
        $usernameSent = isset($header['x-username']) ? (string) $header['x-username'] : '';

        // Validate username and token in constant time (prevents timing attacks + user enumeration)
        $usernameValid = hash_equals($this->username, $usernameSent);
        $tokenValid    = hash_equals($this->_makeToken(), $tokenSent);

        if (!$usernameValid || !$tokenValid) {
            // Single generic message — do not differentiate username vs token error
            $this->_respond(401, 'Autentikasi gagal. Token atau kredensial tidak valid.');
        }

        return $tokenSent;
    }

    // -------------------------------------------------------------------------
    // ENDPOINTS
    // -------------------------------------------------------------------------

    public function getPatient($field, $key) {

        $this->checkToken($this->input->request_headers());

        $allowed_fields = ['nomr', 'nik', 'name', 'nobpjs'];
        if (!in_array($field, $allowed_fields, true)) {
            $this->_respond(202, 'Parameter tidak valid, gunakan: ' . implode(', ', $allowed_fields));
        }

        switch ($field) {
            case 'nomr':   $this->db->where('no_mr',          $key); break;
            case 'nik':    $this->db->where('no_ktp',         $key); break;
            case 'name':   $this->db->like('nama_pasien',     $key); break;
            case 'nobpjs': $this->db->where('no_kartu_bpjs',  $key); break;
        }

        $this->db->from('mt_master_pasien');
        $result = $this->db->get();

        if ($result->num_rows() === 0) {
            $this->_respond(202, 'Data pasien tidak ditemukan');
        }

        $data_pasien = [];
        foreach ($result->result() as $row) {
            $data_pasien[] = [
                'no_mr'         => $row->no_mr,
                'nik'           => $row->no_ktp,
                'title'         => $row->title,
                'nama_pasien'   => $row->nama_pasien,
                'tmt_lhr'       => $row->tempat_lahir,
                'tgl_lhr'       => $this->tanggal->formatDateTimeToSqlDate($row->tgl_lhr),
                'jk'            => $row->jen_kelamin,
                'alamat'        => $row->almt_ttp_pasien,
                'no_tlp'        => $row->tlp_almt_ttp,
                'no_hp'         => $row->no_hp,
                'no_kartu_bpjs' => $row->no_kartu_bpjs,
            ];
        }

        $this->_respond(200, 'Sukses', [
            'code'     => 200,
            'message'  => 'Sukses',
            'metadata' => $data_pasien,
        ]);
    }

    public function getMedicalRecord() {

        $post = $this->_parseBody();
        $this->checkToken($this->input->request_headers());

        if (empty($post->nomr)) {
            $this->_respond(202, 'Parameter nomr wajib diisi.');
        }

        $no_mr = (string) $post->nomr;
        // Clamp limit: minimum 1, maximum 100
        $limit = isset($post->limit) ? max(1, min(100, (int) $post->limit)) : 20;
        $year  = (int) date('Y') - 3;

        // Riwayat medis
        $this->db->from('view_cppt');
        $this->db->where('no_mr', $no_mr);
        $this->db->where('jenis_form', 0);
        $this->db->where('YEAR(tanggal) >=', $year);
        $this->db->limit($limit);
        $riwayat_medis = $this->db->get()->result();

        // File EMR pasien
        $emr = $this->db
            ->select('csm_dokumen_export.*, tc_kunjungan.no_mr, tc_kunjungan.no_kunjungan')
            ->join('tc_kunjungan', 'tc_kunjungan.no_registrasi=csm_dokumen_export.no_registrasi', 'left')
            ->get_where('csm_dokumen_export', ['tc_kunjungan.no_mr' => $no_mr])
            ->result();

        $getDataFile = [];
        foreach ($emr as $val_file) {
            $getDataFile[$val_file->no_kunjungan][] = [
                'filename' => $val_file->csm_dex_nama_dok,
                'fileurl'  => $val_file->base_url_dok . $val_file->csm_dex_fullpath,
            ];
        }

        // Eresep
        $eresep          = $this->db->get_where('fr_tc_pesan_resep_detail', ['no_mr' => $no_mr])->result();
        $getChildRacikan = [];
        foreach ($eresep as $val_resep) {
            if ($val_resep->tipe_obat === 'racikan') {
                $getChildRacikan[$val_resep->parent][] = [
                    'nama_obat' => $val_resep->nama_brg,
                    'dosis'     => $val_resep->jml_pesan,
                    'satuan'    => $val_resep->satuan_obat,
                ];
            }
        }

        $getResep = [];
        foreach ($eresep as $val_resep) {
            if ($val_resep->parent == 0) {
                $child_racikan = [];
                if ($val_resep->tipe_obat === 'racikan' && isset($getChildRacikan[$val_resep->kode_brg])) {
                    $child_racikan = $getChildRacikan[$val_resep->kode_brg];
                }
                $getResep[$val_resep->no_kunjungan][] = [
                    'nama_obat'         => $val_resep->nama_brg,
                    'dosis'             => $val_resep->jml_dosis . 'x' . $val_resep->jml_dosis_obat . ' ' . $val_resep->aturan_pakai,
                    'qty'               => $val_resep->jml_pesan,
                    'satuan'            => $val_resep->satuan_obat,
                    'keterangan'        => $val_resep->keterangan,
                    'komposisi_racikan' => $child_racikan,
                ];
            }
        }

        $getDataRm = [];
        foreach ($riwayat_medis as $row_rm) {
            $getDataRm[] = [
                'no_registrasi'     => $row_rm->no_registrasi,
                'no_kunjungan'      => $row_rm->no_kunjungan,
                'no_mr'             => $row_rm->no_mr,
                'tanggal'           => $row_rm->tanggal,
                'tipe_layan'        => $row_rm->tipe,
                'jenis_mr'          => $row_rm->jenis_pengkajian,
                'flag_mr'           => $row_rm->flag,
                'ppa'               => $row_rm->ppa,
                'nama_ppa'          => $row_rm->nama_ppa,
                'spesialis'         => $row_rm->spesialis,
                'subjective'        => $row_rm->subjective,
                'objective'         => $row_rm->objective,
                'assesment'         => $row_rm->assesment,
                'diagnosa_sekunder' => $row_rm->diagnosa_sekunder,
                'planning'          => $row_rm->planning,
                'td'                => $row_rm->tekanan_darah,
                'tb'                => $row_rm->tinggi_badan,
                'bb'                => $row_rm->berat_badan,
                'suhu'              => $row_rm->suhu,
                'nadi'              => $row_rm->nadi,
                'resep_obat'        => isset($getResep[$row_rm->no_kunjungan])  ? $getResep[$row_rm->no_kunjungan]  : [],
                'files'             => isset($getDataFile[$row_rm->no_kunjungan]) ? $getDataFile[$row_rm->no_kunjungan] : [],
            ];
        }

        if (empty($getDataRm)) {
            $this->_respond(202, 'Data Medical Record Pasien tidak ditemukan');
        }

        $this->_respond(200, 'Sukses', [
            'code'     => 200,
            'message'  => 'Sukses',
            'metadata' => $getDataRm,
        ]);
    }

    public function getMedicalExam() {

        $post = $this->_parseBody();
        $this->checkToken($this->input->request_headers());

        if (empty($post->nomr)) {
            $this->_respond(202, 'Parameter nomr wajib diisi.');
        }

        $no_mr = (string) $post->nomr;

        // File EMR pasien (lampiran)
        $emr = $this->db
            ->select('csm_dokumen_export.*, tc_kunjungan.no_mr, tc_kunjungan.no_kunjungan')
            ->join('pm_tc_penunjang', 'pm_tc_penunjang.kode_penunjang=csm_dokumen_export.kode_penunjang', 'left')
            ->join('tc_kunjungan',    'tc_kunjungan.no_kunjungan=pm_tc_penunjang.no_kunjungan', 'left')
            ->get_where('csm_dokumen_export', ['tc_kunjungan.no_mr' => $no_mr])
            ->result();

        $getDataFile = [];
        foreach ($emr as $val_file) {
            $getDataFile[$val_file->kode_penunjang][] = [
                'filename' => $val_file->csm_dex_nama_dok,
                'fileurl'  => $val_file->base_url_dok . $val_file->csm_dex_fullpath,
            ];
        }

        // Penunjang medis (with cache)
        if ( ! $penunjang = $this->cache->get('rm_penunjang_medis_' . $no_mr . '_' . date('Y-m-d'))) {
            $this->db->select('tc_kunjungan.no_kunjungan, tc_kunjungan.no_mr, tc_kunjungan.no_registrasi, mt_karyawan.nama_pegawai as dokter, asal.nama_bagian as asal_bagian, tujuan.nama_bagian as tujuan_bagian, mt_master_pasien.nama_pasien, tc_kunjungan.tgl_masuk, tc_kunjungan.tgl_keluar, status_isihasil, kode_penunjang, pm_tc_penunjang.flag_mcu, status_daftar, kode_bagian_tujuan, flag_mcu');
            $this->db->select('tgl_daftar, tgl_isihasil, tgl_periksa');
            $this->db->select("CAST((
                SELECT '|' + nama_tindakan
                FROM tc_trans_pelayanan
                LEFT JOIN pm_tc_penunjang n ON n.no_kunjungan=tc_trans_pelayanan.no_kunjungan
                LEFT JOIN tc_kunjungan s ON s.no_kunjungan=n.no_kunjungan
                WHERE s.no_kunjungan = tc_kunjungan.no_kunjungan AND n.kode_penunjang = pm_tc_penunjang.kode_penunjang
                FOR XML PATH('')) as varchar(max)) as nama_tarif");
            $this->db->from('tc_kunjungan');
            $this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_kunjungan.no_mr', 'left');
            $this->db->join('mt_karyawan',      'mt_karyawan.kode_dokter=tc_kunjungan.kode_dokter', 'left');
            $this->db->join('mt_bagian as asal',   'asal.kode_bagian=tc_kunjungan.kode_bagian_asal', 'left');
            $this->db->join('mt_bagian as tujuan', 'tujuan.kode_bagian=tc_kunjungan.kode_bagian_tujuan', 'left');
            $this->db->join('pm_tc_penunjang', 'pm_tc_penunjang.no_kunjungan=tc_kunjungan.no_kunjungan', 'left');
            $this->db->where('tc_kunjungan.no_mr', $no_mr);
            $this->db->where('tgl_isihasil is not null');
            $this->db->where('DATEDIFF(year,tgl_masuk,GETDATE()) < 2 ');
            $this->db->where('SUBSTRING(kode_bagian_tujuan, 1, 2) =', '05');
            $this->db->order_by('tgl_masuk', 'DESC');
            $penunjang = $this->db->get()->result();
            $this->cache->save('rm_penunjang_medis_' . $no_mr . '_' . date('Y-m-d'), $penunjang, 3600);
        }

        $getDataPenunjang = [];
        foreach ($penunjang as $value) {
            $exp         = explode('|', $value->nama_tarif);
            $pemeriksaan = array_values(array_diff(array_unique($exp), ['']));
            $getDataPenunjang[] = [
                'no_registrasi'    => $value->no_registrasi,
                'no_kunjungan'     => $value->no_kunjungan,
                'kode_penunjang'   => $value->kode_penunjang,
                'kode_unit'        => $value->kode_bagian_tujuan,
                'no_mr'            => $value->no_mr,
                'tanggal_daftar'   => $value->tgl_daftar,
                'jenis_penunjang'  => $value->tujuan_bagian,
                'jenis_pemeriksaan'=> $pemeriksaan,
                'flag_mcu'         => $value->flag_mcu,
                'lampiran_hasil'   => isset($getDataFile[$value->kode_penunjang]) ? $getDataFile[$value->kode_penunjang] : [],
            ];
        }

        if (empty($getDataPenunjang)) {
            $this->_respond(202, 'Data Pemeriksaan Penunjang Medis Pasien tidak ditemukan');
        }

        $this->_respond(200, 'Sukses', [
            'code'     => 200,
            'message'  => 'Sukses',
            'metadata' => $getDataPenunjang,
        ]);
    }

    public function getMedicalExamResult() {

        $post = $this->_parseBody();
        $this->checkToken($this->input->request_headers());

        // Validate required fields
        $required = ['no_registrasi', 'no_kunjungan', 'kode_penunjang', 'kode_unit', 'flag_mcu'];
        foreach ($required as $field) {
            if (!isset($post->$field)) {
                $this->_respond(202, 'Parameter ' . $field . ' wajib diisi.');
            }
        }

        $no_registrasi  = (string) $post->no_registrasi;
        $no_kunjungan   = (string) $post->no_kunjungan;
        // Escape user input to prevent SQL injection in raw WHERE strings
        $kode_penunjang = $this->db->escape($post->kode_penunjang);
        $kode_bagian_pm = (string) $post->kode_unit;
        $flag_mcu       = (string) $post->flag_mcu;

        if ($flag_mcu === '') {
            $table = 'pm_hasilpasien_v as a';
            $where = 'a.kode_trans_pelayanan IN (SELECT kode_trans_pelayanan FROM tc_trans_pelayanan WHERE kode_penunjang=' . $kode_penunjang . ')';
        } else {
            $table = 'mcu_hasilpasien_pm_v as a';
            $where = 'a.kode_trans_pelayanan IN (SELECT kode_trans_pelayanan_paket_mcu FROM tc_trans_pelayanan_paket_mcu WHERE kode_penunjang=' . $kode_penunjang . ')';
        }

        $this->db->select("a.kode_trans_pelayanan, a.kode_tarif, a.nama_pemeriksaan, REPLACE(a.nama_tindakan, 'BPJS', '') as nama_tindakan, a.hasil, a.standar_hasil_pria, a.standar_hasil_wanita, a.satuan, a.keterangan, a.detail_item_1, a.detail_item_2, b.referensi, d.urutan");
        $this->db->from($table);
        $this->db->join('mt_master_tarif b',   'a.kode_tarif=b.kode_tarif', 'left');
        $this->db->join('pm_mt_standarhasil d', 'a.kode_mt_hasilpm=d.kode_mt_hasilpm', 'left');
        $this->db->where($where);
        $this->db->where("a.hasil != ''");
        $this->db->group_by('a.kode_tarif, a.nama_pemeriksaan, a.nama_tindakan, a.hasil, a.standar_hasil_pria, a.standar_hasil_wanita, a.satuan, a.keterangan, a.detail_item_1, a.detail_item_2, b.referensi, d.urutan, a.kode_trans_pelayanan');
        $this->db->order_by('d.urutan', 'ASC');
        $result_pm = $this->db->get()->result();

        $getTindakan = [];
        foreach ($result_pm as $row_pm) {
            $getTindakan[$row_pm->kode_trans_pelayanan][$row_pm->nama_tindakan][] = [
                'urutan'              => $row_pm->urutan,
                'jenis_pemeriksaan'   => $row_pm->nama_pemeriksaan,
                'standar_hasil_pria'  => $row_pm->standar_hasil_pria,
                'standar_hasil_wanita'=> $row_pm->standar_hasil_wanita,
                'satuan'              => $row_pm->satuan,
                'hasil'               => $row_pm->hasil,
                'keterangan'          => $row_pm->keterangan,
            ];
        }

        if (empty($getTindakan)) {
            $this->_respond(202, 'Data Hasil Pemeriksaan Penunjang Medis Pasien tidak ditemukan');
        }

        $this->_respond(200, 'Sukses', [
            'code'     => 200,
            'message'  => 'Sukses',
            'metadata' => $getTindakan,
        ]);
    }

    /**
     * Generate a signed, time-limited embed URL for the SOAP viewer.
     *
     * POST /api/service/getSoapLink
     * Headers : x-username, x-token
     * Body    : { "nomr": "00313889", "ttl": 1800 }
     *
     * Response: { "url": "...", "nomr": "...", "expires_in": 1800, "expires_at": "..." }
     */
    public function getSoapLink() {

        $post = $this->_parseBody();
        $this->checkToken($this->input->request_headers());

        if (empty($post->nomr)) {
            $this->_respond(202, 'Parameter nomr wajib diisi.');
        }

        $nomr = trim((string) $post->nomr);

        // Validate patient exists
        $pasien = $this->db->get_where('mt_master_pasien', ['no_mr' => $nomr])->row();
        if (!$pasien) {
            $this->_respond(202, 'Pasien dengan No. MR ' . $nomr . ' tidak ditemukan.');
        }

        // TTL: min 5 menit, max 8 jam, default 30 menit
        $ttl = isset($post->ttl) ? max(300, min(28800, (int) $post->ttl)) : 1800;

        $embed_token = $this->_generateEmbedToken($nomr, $ttl);
        $url = base_url() . 'rekam_medis/Rm_soap_embed/view?nomr=' . urlencode($nomr)
             . '&st=' . urlencode($embed_token);

        $this->_respond(200, 'Sukses', [
            'url'        => $url,
            'nomr'       => $nomr,
            'nama_pasien'=> $pasien->nama_pasien,
            'expires_in' => $ttl,
            'expires_at' => date('Y-m-d H:i:s', time() + $ttl),
        ]);
    }

}

/* End of file service.php */
/* Location: ./application/modules/api/controllers/service.php */
