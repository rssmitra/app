<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rm_soap extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->breadcrumbs->push('Index', 'rekam_medis/Rm_soap');
        if ($this->session->userdata('logged') != TRUE) {
            redirect(base_url() . 'login'); exit;
        }
        $this->load->model('Rm_soap_model', 'Rm_soap');
        $this->output->enable_profiler(false);
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))
            ? $this->lib_menus->get_menu_by_class(get_class($this))->name
            : 'Data SOAP Pasien';
    }

    public function index()
    {
        $data = array(
            'title'       => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        $this->load->view('Rm_soap/index', $data);
    }

    public function get_data()
    {
        $list = $this->Rm_soap->get_datatables();
        $data = array();
        $no   = $_POST['start'];
        // Batch-fetch related data for all records on this page
        $kunjungan_ids  = array();
        $registrasi_ids = array();
        foreach ($list as $_item) {
            if (!empty($_item->no_kunjungan))  $kunjungan_ids[$_item->no_kunjungan]   = $_item->no_kunjungan;
            if (!empty($_item->no_registrasi)) $registrasi_ids[$_item->no_registrasi] = $_item->no_registrasi;
        }
        $kunjungan_ids  = array_values($kunjungan_ids);
        $registrasi_ids = array_values($registrasi_ids);
        
        $map_eresep     = $this->Rm_soap->get_eresep_by_kunjungan($kunjungan_ids);
        $map_emr        = $this->Rm_soap->get_emr_files_by_kunjungan($registrasi_ids);
        $map_penunjang  = $this->Rm_soap->get_penunjang_by_kunjungan($registrasi_ids);

        foreach ($list as $row) {
            $no++;
            $kunjungan_key  = $row->no_kunjungan;
            $registrasi_key = $row->no_registrasi;

            $badge_tipe = ($row->tipe == 'RJ')
                ? '<span class="label label-success">RJ</span>'
                : '<span class="label label-primary">RI</span>';

            // Objective: prepend vital signs if any
            $ttv = array();
            if ($row->tinggi_badan)  $ttv[] = 'TB: ' . $row->tinggi_badan . ' cm';
            if ($row->berat_badan)   $ttv[] = 'BB: ' . $row->berat_badan  . ' kg';
            if ($row->tekanan_darah) $ttv[] = 'TD: ' . $row->tekanan_darah . ' mmHg';
            if ($row->nadi)          $ttv[] = 'Nadi: ' . $row->nadi . ' bpm';
            if ($row->suhu)          $ttv[] = 'Suhu: ' . $row->suhu . ' °C';
            $obj_html = '';
            if ($ttv) {
                $obj_html .= '<span style="color:#475569;font-size:11px;">' . implode(' | ', $ttv) . '</span><br>';
            }
            $obj_html .= $row->objective ? nl2br(htmlspecialchars($row->objective)) : '<span style="color:#94a3b8;">—</span>';

            // Assessment: diagnosa primer + sekunder + tindakan
            $asm_html = '';

            // Diagnosa Primer
            $asm_html .= '<div style="margin-bottom:4px;">'
                . '<span style="font-size:10px;font-weight:bold;color:#d97706;text-transform:uppercase;">Diagnosa Primer</span><br>';
            if ($row->kode_icd_diagnosa) {
                $asm_html .= '<strong>' . htmlspecialchars($row->kode_icd_diagnosa) . '</strong>';
                if ($row->assesment) $asm_html .= ' &mdash; ' . nl2br(htmlspecialchars($row->assesment));
            } else {
                $asm_html .= $row->assesment
                    ? nl2br(htmlspecialchars($row->assesment))
                    : '<span style="color:#94a3b8;">—</span>';
            }
            $asm_html .= '</div>';

            // Diagnosa Sekunder
            $ds_list = array_filter(array_map('trim', explode('|', (string)$row->diagnosa_sekunder)));
            if ($ds_list) {
                $asm_html .= '<div style="margin-bottom:4px;">'
                    . '<span style="font-size:10px;font-weight:bold;color:#64748b;text-transform:uppercase;">Diagnosa Sekunder</span><br>';
                foreach ($ds_list as $ds) {
                    $asm_html .= '<span style="display:inline-block;margin-right:4px;">'
                        . htmlspecialchars($ds) . '</span>';
                }
                $asm_html .= '</div>';
            }

            // Prosedur / Tindakan (ICD-9)
            if ($row->kode_icd9 || $row->text_icd9) {
                $asm_html .= '<div>'
                    . '<span style="font-size:10px;font-weight:bold;color:#7c3aed;text-transform:uppercase;">Tindakan</span><br>';
                $icd9_items = array_filter(array_map('trim', explode('|', (string)$row->kode_icd9)));
                $icd9_texts = array_values(array_filter(array_map('trim', explode('|', (string)$row->text_icd9))));
                if ($icd9_items) {
                    foreach ($icd9_items as $idx => $kode) {
                        $nama = isset($icd9_texts[$idx]) ? $icd9_texts[$idx] : '';
                        $asm_html .= '<span style="display:block;">'
                            . '<strong>' . htmlspecialchars($kode) . '</strong>'
                            . ($nama ? ' &mdash; ' . htmlspecialchars($nama) : '')
                            . '</span>';
                    }
                } elseif ($row->text_icd9) {
                    $asm_html .= nl2br(htmlspecialchars($row->text_icd9));
                }
                $asm_html .= '</div>';
            }

            $pasien_html = '<small style="color:#64748b;">' . $this->tanggal->formatDateTime($row->tanggal) . '</small><br>'
                . '<span style="font-size:11px;color:#475569;">' . $row->no_mr . '</span><br>'
                . '<strong>' . strtoupper($row->nama_pasien) . '</strong>';

            $ppa_html = $badge_tipe . ' <small>' . strtoupper($row->ppa) . '</small><br>'
                . $row->nama_ppa;

            // Subjective: main text + riwayat section
            $subj_html = $row->subjective
                ? nl2br(htmlspecialchars($row->subjective))
                : '<span style="color:#94a3b8;">—</span>';

            $riwayat_rows = array();

            // Helper: build Ya/Tidak badge + ket — tampil untuk Ya maupun Tidak selama ada data
            $fn_riwayat = function($status, $ket, $label_ya = '#16a34a') use (&$riwayat_rows) {
                if ($status === null && !$ket) return;
                if ($status == 'ada') {
                    $val = '<span style="color:' . $label_ya . ';">Ya</span>';
                } else {
                    $val = '<span style="color:#94a3b8;">Tidak</span>';
                }
                if ($ket) $val .= ': ' . htmlspecialchars($ket);
                return $val;
            };

            // Riwayat Penyakit Dahulu
            $v = $fn_riwayat($row->riwayat_penyakit_dahulu, $row->riwayat_penyakit_dahulu_ket);
            if ($v !== null) $riwayat_rows[] = array('Peny. Dahulu', $v);

            // Riwayat Operasi
            $v = $fn_riwayat($row->riwayat_operasi, $row->riwayat_operasi_ket);
            if ($v !== null) $riwayat_rows[] = array('Operasi', $v);

            // Riwayat Alergi (merah jika Ya)
            $v = $fn_riwayat($row->riwayat_alergi, $row->riwayat_alergi_ket, '#dc2626');
            if ($v !== null) $riwayat_rows[] = array('Alergi', $v);

            // Catatan Assessment
            if ($row->catatan_assesmen) {
                $riwayat_rows[] = array('Catatan', nl2br(htmlspecialchars($row->catatan_assesmen)));
            }
            // Resep Iter
            if ($row->resep_iter) {
                $iter_val = htmlspecialchars($row->resep_iter);
                if ($row->jumlah_iter) $iter_val .= ' &times; ' . htmlspecialchars($row->jumlah_iter);
                $riwayat_rows[] = array('Resep Iter', $iter_val);
            }

            if ($riwayat_rows) {
                $subj_html .= '<div style="margin-top:6px;padding-top:5px;border-top:1px dashed #cbd5e1;">'
                    . '<span style="font-size:10px;font-weight:bold;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Riwayat</span>'
                    . '<table style="font-size:11px;margin-top:3px;width:100%;border-collapse:collapse;">';
                foreach ($riwayat_rows as $r) {
                    $subj_html .= '<tr>'
                        . '<td style="color:#64748b;white-space:nowrap;padding-right:6px;vertical-align:top;">' . $r[0] . '</td>'
                        . '<td>' . $r[1] . '</td>'
                        . '</tr>';
                }
                $subj_html .= '</table></div>';
            }

            // ── Eresep Dokter ───────────────────────────────────────────────
            $eresep_html = '<span style="color:#94a3b8;">—</span>';
            if (!empty($map_eresep[$kunjungan_key])) {
                // Group by kode_trans_far
                $er_by_trans = array();
                foreach ($map_eresep[$kunjungan_key] as $er) {
                    $er_by_trans[$er->kode_trans_far ?: 'LAINNYA'][] = $er;
                }

                $eresep_html = '';
                foreach ($er_by_trans as $trans_key => $ers) {
                    $tgl_trans = !empty($ers[0]->tgl_trans)
                        ? $this->tanggal->formatDate($ers[0]->tgl_trans)
                        : '';
                    $eresep_html .= '<div style="margin-bottom:6px;">'
                        . '<span style="font-size:10px;font-weight:bold;color:#0ea5e9;">'
                        . htmlspecialchars($trans_key) . '</span>'
                        . ($tgl_trans ? '<small style="color:#64748b;"> &mdash; ' . $tgl_trans . '</small>' : '')
                        . '<ol style="margin:2px 0 0 0;padding-left:16px;font-size:11px;">';
                    foreach ($ers as $er) {
                        $dose_info = '';
                        if ($er->jml_dosis && $er->jml_dosis_obat) {
                            $dose_info = $er->jml_dosis . ' &times; ' . $er->jml_dosis_obat . ' ' . htmlspecialchars($er->satuan_obat);
                        }
                        $eresep_html .= '<li style="margin-bottom:3px;">'
                            . '<strong>' . strtoupper(htmlspecialchars($er->nama_brg)) . '</strong><br>'
                            . '<span style="color:#475569;">'
                            . ($dose_info ? $dose_info . ' &mdash; ' : '')
                            . htmlspecialchars($er->aturan_pakai)
                            . ($er->jml_pesan ? ' (Qty: ' . $er->jml_pesan . ' ' . htmlspecialchars($er->satuan_obat) . ')' : '')
                            . '</span>'
                            . ($er->keterangan ? '<br><small style="color:#94a3b8;">' . htmlspecialchars($er->keterangan) . '</small>' : '')
                            . '</li>';
                    }
                    $eresep_html .= '</ol></div>';
                }
            }

            // ── Pemeriksaan Penunjang ────────────────────────────────────────
            $penunjang_html = '<span style="color:#94a3b8;">—</span>';
            if (!empty($map_penunjang[$registrasi_key])) {
                // Group by tujuan_bagian
                $pnj_by_dept = array();
                foreach ($map_penunjang[$registrasi_key] as $pnj) {
                    $dept = $pnj->tujuan_bagian ? strtoupper($pnj->tujuan_bagian) : 'LAINNYA';
                    $pnj_by_dept[$dept][] = $pnj;
                }

                $penunjang_html = '';
                foreach ($pnj_by_dept as $dept => $items) {
                    $penunjang_html .= '<div style="margin-bottom:5px;">'
                        . '<span style="font-size:10px;font-weight:bold;color:#7c3aed;">'
                        . htmlspecialchars($dept) . '</span>'
                        . '<ul style="margin:2px 0 0 0;padding-left:14px;font-size:11px;">';
                    foreach ($items as $pnj) {
                        $tarif_parts = array_filter(array_map('trim', explode('|', (string)$pnj->nama_tarif)));
                        $tarif_text = $tarif_parts
                            ? htmlspecialchars(implode(', ', $tarif_parts))
                            : htmlspecialchars($pnj->kode_penunjang);

                        $status_color = ($pnj->status_isihasil == 'selesai') ? '#16a34a' : '#d97706';
                        $tgl_text = $pnj->tgl_isihasil
                            ? $this->tanggal->formatDate($pnj->tgl_isihasil)
                            : '';

                        $penunjang_html .= '<li style="margin-bottom:3px;">'
                            . $tarif_text
                            . '<br><small style="color:#64748b;">'
                            . $tgl_text
                            . ($pnj->status_isihasil
                                ? ' &mdash; <span style="color:' . $status_color . ';">'
                                  . htmlspecialchars($pnj->status_isihasil) . '</span>'
                                : '')
                            . ($pnj->dokter ? ' &mdash; ' . htmlspecialchars($pnj->dokter) : '')
                            . '</small></li>';
                    }
                    $penunjang_html .= '</ul></div>';
                }
            }

            // ── File EMR ────────────────────────────────────────────────────
            $emr_html = '<span style="color:#94a3b8;">—</span>';
            if (!empty($map_emr[$registrasi_key])) {
                $emr_html = '<ol style="margin:0;padding-left:16px;font-size:11px;">';
                foreach ($map_emr[$registrasi_key] as $emr) {
                    // print_r($emr);die;
                    $label = $emr->csm_dex_nama_dok;
                    // Ambil bagian sebelum '-' sebagai nama singkat
                    if (strpos($label, '-') !== false) {
                        $label = trim(explode('-', $label)[0]);
                    }
                    $url = rtrim($emr->base_url_dok, '/') . '/' . ltrim($emr->csm_dex_fullpath, '/');
                    $emr_html .= '<li style="margin-bottom:2px;">'
                        . '<a href="' . htmlspecialchars($url) . '" target="_blank" '
                        . 'style="color:#0ea5e9;text-decoration:none;">'
                        . htmlspecialchars(strtoupper($label))
                        . '</a></li>';
                }
                $emr_html .= '</ol>';
            }

            

            $data[] = array(
                $no,
                $pasien_html,
                $ppa_html,
                $subj_html,
                $obj_html,
                $asm_html,
                $row->planning ? nl2br(htmlspecialchars(str_replace("null", "", $row->planning))) : '<span style="color:#94a3b8;">—</span>',
                $eresep_html,
                $penunjang_html,
                $emr_html,
            );
        }

        $output = array(
            'draw'            => $_POST['draw'],
            'recordsTotal'    => $this->Rm_soap->count_all(),
            'recordsFiltered' => $this->Rm_soap->count_filtered(),
            'data'            => $data,
        );
        echo json_encode($output);
    }

    public function find_data()
    {
        $output = array('data' => http_build_query($_POST) . "\n");
        echo json_encode($output);
    }

    public function export_excel()
    {
        $list = $this->Rm_soap->get_for_export();

        $getData = array();
        foreach ($list as $row) {
            $getData[] = array(
                'no_mr'           => $row->no_mr,
                'nama_pasien'     => strtoupper($row->nama_pasien),
                'tanggal'         => $this->tanggal->formatDateTime($row->tanggal),
                'tipe'            => $row->tipe,
                'ppa'             => strtoupper($row->ppa),
                'nama_ppa'        => $row->nama_ppa,
                'subjective'      => strip_tags($row->subjective),
                'objective'       => strip_tags($row->objective),
                'assesment'       => strip_tags($row->assesment),
                'kode_icd'        => $row->kode_icd_diagnosa,
                'diagnosa_sekunder'=> $row->diagnosa_sekunder,
                'planning'        => strip_tags($row->planning),
                'tinggi_badan'    => $row->tinggi_badan,
                'berat_badan'     => $row->berat_badan,
                'tekanan_darah'   => $row->tekanan_darah,
                'nadi'            => $row->nadi,
                'suhu'            => $row->suhu,
                'tgl_kontrol'     => ($row->tgl_kontrol_kembali ? $this->tanggal->formatDate($row->tgl_kontrol_kembali) : ''),
            );
        }

        $fields = array(
            'no_mr', 'nama_pasien', 'tanggal', 'tipe', 'ppa', 'nama_ppa',
            'subjective', 'objective', 'assesment', 'kode_icd', 'diagnosa_sekunder',
            'planning', 'tinggi_badan', 'berat_badan', 'tekanan_darah', 'nadi', 'suhu',
            'tgl_kontrol',
        );

        $labels = array(
            'no_mr'            => 'No. MR',
            'nama_pasien'      => 'Nama Pasien',
            'tanggal'          => 'Tanggal Kunjungan',
            'tipe'             => 'Tipe',
            'ppa'              => 'PPA',
            'nama_ppa'         => 'Nama PPA',
            'subjective'       => 'S - Subjective',
            'objective'        => 'O - Objective',
            'assesment'        => 'A - Assessment',
            'kode_icd'         => 'Kode ICD-10',
            'diagnosa_sekunder' => 'Diagnosa Sekunder',
            'planning'         => 'P - Planning',
            'tinggi_badan'     => 'TB (cm)',
            'berat_badan'      => 'BB (kg)',
            'tekanan_darah'    => 'TD (mmHg)',
            'nadi'             => 'Nadi (bpm)',
            'suhu'             => 'Suhu (°C)',
            'tgl_kontrol'      => 'Tgl Kontrol',
        );

        $data = array(
            'title'   => 'Data SOAP Pasien',
            'fields'  => $fields,
            'labels'  => $labels,
            'getData' => $getData,
            'filters' => $_GET,
        );

        $this->load->view('Rm_soap/excel_view', $data);
    }

    private function _build_soap_card($row)
    {
        $html  = '<div style="font-size:12px;line-height:1.6;">';

        // S
        $html .= '<div style="margin-bottom:6px;">';
        $html .= '<strong style="color:#0ea5e9;">S &mdash; Subjective</strong><br>';
        $html .= $row->subjective ? nl2br(htmlspecialchars($row->subjective)) : '<span style="color:#94a3b8;">—</span>';
        $html .= '</div>';

        // O – Vital Signs + Pemeriksaan Fisik
        $html .= '<div style="margin-bottom:6px;">';
        $html .= '<strong style="color:#16a34a;">O &mdash; Objective</strong><br>';
        $ttv   = array();
        if ($row->tinggi_badan) $ttv[] = 'TB: ' . $row->tinggi_badan . ' cm';
        if ($row->berat_badan)  $ttv[] = 'BB: ' . $row->berat_badan  . ' kg';
        if ($row->tekanan_darah) $ttv[] = 'TD: ' . $row->tekanan_darah . ' mmHg';
        if ($row->nadi)         $ttv[] = 'Nadi: ' . $row->nadi . ' bpm';
        if ($row->suhu)         $ttv[] = 'Suhu: ' . $row->suhu . ' °C';
        if ($ttv) {
            $html .= '<span style="color:#475569;font-size:11px;">' . implode(' &nbsp;|&nbsp; ', $ttv) . '</span><br>';
        }
        $html .= $row->objective ? nl2br(htmlspecialchars($row->objective)) : '<span style="color:#94a3b8;">—</span>';
        $html .= '</div>';

        // A
        $html .= '<div style="margin-bottom:6px;">';
        $html .= '<strong style="color:#d97706;">A &mdash; Assessment</strong><br>';
        $icd   = $row->kode_icd_diagnosa;
        $html .= $icd
            ? '<strong>' . htmlspecialchars($icd) . '</strong> &mdash; ' . htmlspecialchars($row->assesment)
            : ($row->assesment ? nl2br(htmlspecialchars($row->assesment)) : '<span style="color:#94a3b8;">—</span>');
        // Diagnosa Sekunder
        $ds = array_filter(array_map('trim', explode('|', (string)$row->diagnosa_sekunder)));
        if ($ds) {
            $html .= '<br><span style="font-size:11px;color:#64748b;">Sek: ' . implode(', ', array_map('htmlspecialchars', $ds)) . '</span>';
        }
        $html .= '</div>';

        // P
        $html .= '<div>';
        $html .= '<strong style="color:#7c3aed;">P &mdash; Planning</strong><br>';
        $html .= $row->planning ? nl2br(htmlspecialchars($row->planning)) : '<span style="color:#94a3b8;">—</span>';
        if ($row->tgl_kontrol_kembali) {
            $html .= '<br><small style="color:#64748b;">Kontrol: ' . $this->tanggal->formatDate($row->tgl_kontrol_kembali) . '</small>';
        }
        $html .= '</div>';

        $html .= '</div>';
        return $html;
    }
}
