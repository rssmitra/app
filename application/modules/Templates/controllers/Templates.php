<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Templates extends MX_Controller {

    /**
     *
     * This is the Modular Template controller. Pass a data object here and it loads the data into view templates.
     * This controller is called from the templates.php library.
     *
     * It can also be loaded as a module using:
     * $this->load->module('templates');
     * making the method and its functions available:
     * $this->templates->index($data);
     * *note: requires index function explicitly
     *
     * It can also be run as a module using:
     * echo Modules::run('templates', $data);
     * *note: requires data['body'] be defined.
     */

    function __construct() {
        parent::__construct();
        $this->load->model('templates_model', 'templates_model');
        $this->load->model('billing/Billing_model', 'Billing');
        $this->load->model('pelayanan/Pl_pelayanan_pm_model', 'Pl_pelayanan_pm');

        // load module
        $this->load->module('registration/Reg_pasien.php');
    }


    public function index($data, $template_name = null)
    {
        $this->load->library('master');
        $this->load->library('lib_menus');
        //echo '<pre>';print_r($this->session->all_userdata());die;
        /*
        |
        | If $data['body'] is null then we will get the content from the
        | module's default view file, which is <module_name>_view.php
        | within the application/modules/<module_name>/views directory
        |
        */

        if ( ! array_key_exists('body', $data) )
        {       
      // We get the name of the class that called this method so we
      // can get its view file.
            $caller = debug_backtrace();
            $caller_module = $caller[1]['class'];

            // Get the default view file for the module and return as a string.
        $data['body'] = $this->load->view(ucfirst($caller_module).'/'.strtolower($caller_module).'_view', $data, TRUE);
        }
        
        if ( ! isset($template_name) )
        {
      // If there is no template name parameter passed, we just use the default.
            $template_name = 'default';
        }
        
        // With the $data['body'] we now can load the template views.
        // Note that currently there is no value included to specify any
        // header or footer file other than default.

        /*get menu by session role user*/
        
        $data['menu'] = $this->lib_menus->get_menus($this->session->userdata('user')->user_id, $_GET['mod']);
        $data['shortcut'] = $this->lib_menus->get_menus_shortcut($this->session->userdata('user')->user_id, $_GET['mod']);
        $data['app'] = $this->db->get_where('tmp_profile_app', array('id' => 1))->row();
        $data['module'] = $this->db->get_where('tmp_mst_modul', array('modul_id' => $_GET['mod']))->row();

        //echo '<pre>';print_r($data);die;

        /*here specially for mod 9 or module booking will suggest profile form for the first use*/
        if($_GET['mod']==9){
            /*check existing profile*/
            $profile = $this->db->get_where('tmp_user_profile', array('user_id' => $this->session->userdata('user')->user_id) )->num_rows();
            if($profile > 0){
                $this->load->view('templates/content_view', $data);
            }else{
                $this->load->view('templates/form_profile_view', $data);
            }
        }else{
            $this->load->view('templates/content_view', $data);
        }
        

    }

    public function getGraphModule(){
        
        if ($_GET['mod']==1) {

            $data[0] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-line-1',
                'style' => 'line',
                'col_size' => 12,
                'url' => 'templates/Templates/graph?prefix=1&TypeChart=line&style=1&mod='.$_GET['mod'].'',
                );
            $data[1] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-pie-1',
                'style' => 'pie',
                'col_size' => 6,
                'url' => 'templates/Templates/graph?prefix=2&TypeChart=pie&style=1&mod='.$_GET['mod'].'',
                );
            $data[2] = array(
            'mod' => $_GET['mod'],
            'nameid' => 'graph-table-1',
            'style' => 'table',
            'col_size' => 6,
            'url' => 'templates/Templates/graph?prefix=3&TypeChart=table&style=1&mod='.$_GET['mod'].'',
            );

        }
        
        if ( in_array($_GET['mod'], array(11,22) ) ) {
            $data[0] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-line-1',
                'style' => 'line',
                'col_size' => 12,
                'url' => 'templates/Templates/graph?prefix=111&TypeChart=line&style=1&mod='.$_GET['mod'].'',
                );
            $data[1] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-pie-1',
                'style' => 'pie',
                'col_size' => 6,
                'url' => 'templates/Templates/graph?prefix=112&TypeChart=pie&style=1&mod='.$_GET['mod'].'',
                );
            $data[2] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-table-1',
                'style' => 'table',
                'col_size' => 6,
                'url' => 'templates/Templates/graph?prefix=113&TypeChart=table&style=1&mod='.$_GET['mod'].'',
                );
        }

        if ( in_array($_GET['mod'], array(5) ) ) {
            $data[0] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-line-1',
                'style' => 'line',
                'col_size' => 12,
                'url' => 'templates/Templates/graph?prefix=51&TypeChart=line&style=1&mod='.$_GET['mod'].'',
                );
            $data[1] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-pie-1',
                'style' => 'pie',
                'col_size' => 12,
                'url' => 'templates/Templates/graph?prefix=52&TypeChart=pie&style=1&mod='.$_GET['mod'].'',
                );
            $data[2] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-table-1',
                'style' => 'table',
                'col_size' => 6,
                'url' => 'templates/Templates/graph?prefix=53&TypeChart=table&style=1&mod='.$_GET['mod'].'',
                );
            $data[3] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-table-2',
                'style' => 'table',
                'col_size' => 6,
                'url' => 'templates/Templates/graph?prefix=54&TypeChart=table&style=1&mod='.$_GET['mod'].'',
                );

        }

        if ( in_array($_GET['mod'], array(25) ) ) {
                
            $data[0] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-line-1',
                'style' => 'line',
                'col_size' => 12,
                'url' => 'templates/Templates/graph?prefix=251&TypeChart=line&style=1&mod='.$_GET['mod'].'',
                );
            $data[1] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-pie-1',
                'style' => 'pie',
                'col_size' => 12,
                'url' => 'templates/Templates/graph?prefix=252&TypeChart=pie&style=1&mod='.$_GET['mod'].'',
                );

        }

        // modul purchasing
        if ($_GET['mod']==32) {

            $data[0] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-line-1',
                'style' => 'line',
                'col_size' => 12,
                'url' => 'templates/Templates/graph?prefix=321&TypeChart=line&style=1&mod='.$_GET['mod'].'',
            );
            $data[1] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-table-1',
                'style' => 'table',
                'col_size' => 4,
                'url' => 'templates/Templates/graph?prefix=323&TypeChart=table&style=1&mod='.$_GET['mod'].'',
            );
            $data[2] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-pie-1',
                'style' => 'pie',
                'col_size' => 8,
                'url' => 'templates/Templates/graph?prefix=322&TypeChart=pie&style=1&mod='.$_GET['mod'].'',
            );
            
        }

        // modul adm pasien
        if ($_GET['mod']==20) {

            $data[0] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-line-1',
                'style' => 'line',
                'col_size' => 12,
                'url' => 'templates/Templates/graph?prefix=201&TypeChart=line&style=1&mod='.$_GET['mod'].'',
            );
            $data[1] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-table-1',
                'style' => 'table',
                'col_size' => 4,
                'url' => 'templates/Templates/graph?prefix=203&TypeChart=table&style=1&mod='.$_GET['mod'].'',
            );
            $data[2] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-pie-1',
                'style' => 'pie',
                'col_size' => 12,
                'url' => 'templates/Templates/graph?prefix=202&TypeChart=pie&style=1&mod='.$_GET['mod'].'',
            );
            
        }

        // modul casemix
        if ($_GET['mod']==34) {

            $data[0] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-line-1',
                'style' => 'line',
                'col_size' => 12,
                'url' => 'templates/Templates/graph?prefix=341&TypeChart=line&style=2&mod='.$_GET['mod'].'',
            );
            $data[1] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-table-1',
                'style' => 'table',
                'col_size' => 4,
                'url' => 'templates/Templates/graph?prefix=343&TypeChart=table&style=1&mod='.$_GET['mod'].'',
            );
            $data[2] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-table-2',
                'style' => 'table',
                'col_size' => 4,
                'url' => 'templates/Templates/graph?prefix=344&TypeChart=table&style=1&mod='.$_GET['mod'].'',
            );
            $data[3] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-table-3',
                'style' => 'table',
                'col_size' => 4,
                'url' => 'templates/Templates/graph?prefix=345&TypeChart=table&style=1&mod='.$_GET['mod'].'',
            );
            $data[4] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-pie-1',
                'style' => 'pie',
                'col_size' => 12,
                'url' => 'templates/Templates/graph?prefix=342&TypeChart=pie&style=1&mod='.$_GET['mod'].'',
            );
            
        }

        // modul farmasi
        if ($_GET['mod']==24) {

            $data[0] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-line-1',
                'style' => 'line',
                'col_size' => 8,
                'url' => 'templates/Templates/graph?prefix=241&TypeChart=line&style=4&mod='.$_GET['mod'].'',
            );

            $data[1] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-pie-1',
                'style' => 'pie',
                'col_size' => 4,
                'url' => 'templates/Templates/graph?prefix=242&TypeChart=pie&style=1&mod='.$_GET['mod'].'',
            );
            $data[2] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-table-1',
                'style' => 'table',
                'col_size' => 4,
                'url' => 'templates/Templates/graph?prefix=243&TypeChart=table&style=1&mod='.$_GET['mod'].'',
            );
            $data[3] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-table-2',
                'style' => 'table',
                'col_size' => 4,
                'url' => 'templates/Templates/graph?prefix=244&TypeChart=table&style=1&mod='.$_GET['mod'].'',
            );
            $data[4] = array(
                'mod' => $_GET['mod'],
                'nameid' => 'graph-table-3',
                'style' => 'table',
                'col_size' => 4,
                'url' => 'templates/Templates/graph?prefix=245&TypeChart=table&style=1&mod='.$_GET['mod'].'',
            );
            
            
        }
        
        echo json_encode($data);
    }

    public function graph(){
        echo json_encode($this->graph_master->get_graph($_GET['mod'],$_GET), JSON_NUMERIC_CHECK);
    }
    
    public function setGlobalHeaderTemplate(){
        $html = '';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:36px">
                    <tr><td align ="left" colspan="2"><b>'.COMP_LONG.'</b>&nbsp;</td></tr>
                    <tr><td align ="left" colspan="2">'.COMP_ADDRESS_SORT.'</td></tr>
                    <tr><td align ="left" colspan="2">Telp:&nbsp;'.COMP_TELP.'&nbsp;(Hunting)&nbsp;Fax:&nbsp;'.COMP_FAX.'&nbsp;</td></tr>
                    <tr><td align ="left" colspan="2">&nbsp;</td></tr>
                  </table>';
        return $html;
    }

    public function setGlobalProfilePasienTemplate($data, $flag='', $pm=''){
        $html = '';
        $html .= '<table class="table table-striped" width="100%" cellpadding="0" cellspacing="0" border="0">
                     
                    <tr>
                        <td colspan="2" align="center" width="300px"><b>RINCIAN BIAYA PASIEN</b><br></td>
                    </tr> 
                    <tr>
                        <td width="100px">Tanggal</td>
                        <td align="left" width="200px">: '.$this->tanggal->formatDate($data->reg_data->tgl_jam_masuk).'</td>
                    </tr>
                    <tr>
                        <td width="100px">No. RM</td>
                        <td width="200px">: '.$data->reg_data->no_mr.'</td>
                    </tr>
                    <tr>
                        <td width="100px" align="left">Nama Pasien</td>
                        <td width="200px">: '.$data->reg_data->nama_pasien.'</td>
                    </tr>
                    <tr>
                        <td width="100px">Nama Dokter</td>
                        <td width="200px">: '.$data->reg_data->nama_pegawai.'</td>
                    </tr> 
                   
                  </table>';
        return $html;
    }
    
    public function setGlobalProfileRekamMedis($data, $flag='', $pm=''){
        $html = '';
        $html .= '<table align="left" cellpadding="0" cellspacing="0" border="0" style="font-size:36px">
                <tr>
                    <td width="100px">No. RM</td>
                    <td width="180px">: '.$data->reg_data->no_mr.'</td>
                    <td width="100px">Poli/Klinik</td>
                    <td width="350px">: '.ucwords($data->reg_data->nama_bagian).'</td>
                </tr>
                <tr>
                    <td width="100px" align="left">Nama Pasien</td>
                    <td width="180px">: '.ucwords(strtolower($data->reg_data->nama_pasien)).'</td>
                    <td width="100px">Dokter</td>
                    <td width="350px">: '.$data->reg_data->nama_pegawai.'</td>
                </tr>
                <tr>
                    <td width="100px">Umur</td>
                    <td width="180px">: '.$data->reg_data->umur.' Tahun</td>
                    <td width="100px">Tanggal Periksa</td>
                    <td align="left" width="350px">: '.$this->tanggal->formatDateTime($data->reg_data->tgl_jam_masuk).'</td>
                    
                </tr>

                <tr>
                    <td width="100px">Jenis Kelamin</td>
                    <td width="180px">: '.$data->reg_data->jk.'</td>
                    <td width="100px">No SEP</td>
                    <td width="250px">: '.$data->reg_data->no_sep.'</td>
                </tr>                    
            </table>';
            
        $html .= '<hr>';

        return $html;
    }

    public function setGlobalProfilePasienTemplateRI($data, $flag='', $pm=''){
        $html = '';
        $jk = ($data->reg_data->jk == 'L')?'Pria':'Wanita';
        /*data ri*/
        $ri = $this->Billing->getDataRI($data->reg_data->no_registrasi);
        $html .= '<table align="left" cellpadding="0" cellspacing="0" border="0">
                     <tr>
                        <td width="100px">No. RM</td>
                        <td width="300px">: '.$data->reg_data->no_mr.'</td>
                        <td width="120px">No. Registrasi</td>
                        <td width="300px">: '.$data->reg_data->no_registrasi.'</td>
                    </tr>
                    <tr>
                        <td width="100px" align="left">Nama Pasien</td>
                        <td width="300px">: '.$data->reg_data->nama_pasien.'</td>
                        <td width="120px">Dokter Pengirim</td>
                        <td width="300px">: '.$data->reg_data->nama_pegawai.'</td>
                    </tr>
                    <tr>
                        <td width="100px">Umur</td>
                        <td width="300px">: '.$data->reg_data->umur.' Tahun</td>
                        <td width="120px">Tanggal Masuk</td>
                        <td align="left" width="300px">: '.$this->tanggal->formatDate($data->reg_data->tgl_jam_masuk).'</td>
                        
                    </tr>

                    <tr>
                        <td width="100px">Jenis Kelamin</td>
                        <td width="300px">: '.$jk.'</td>
                        <td width="120px">Tanggal Keluar</td>
                        <td width="300px">: '.$this->tanggal->formatDate($data->reg_data->tgl_jam_keluar).'</td>
                    </tr>
                    <tr>
                        <td width="100px">Ruangan</td>
                        <td width="300px">: '.$ri->nama_bagian.'</td>
                        <td width="120px">Kelas</td>
                        <td width="300px">: '.$ri->nama_klas.'</td>
                    </tr>                    
                  </table>';
        return $html;
    }

    public function setGlobalContentBilling($html){
        return $html;
    }

    public function TemplateBillingRJ($no_registrasi, $tipe, $data){
        /*html data untuk tampilan*/
        $html = '';
        $html .= '<table class="table table-striped">';
        $html .= '<tr>';
            $html .= '<th coslpan="2" align="center">&nbsp;</th>';
        $html .= '</tr>'; 
        $html .= '<tr>';
            $html .= '<th width="200px" align="center"><b>URAIAN</b></th>';
            $html .= '<th width="100px" align="right"><b>SUBTOTAL (Rp.)</b></th>';
        $html .= '</tr>'; 
        $html .= '<tr>';
            $html .= '<th coslpan="2" align="center"><hr></th>';
            $html .= '<th coslpan="2" align="center"><hr></th>';
        $html .= '</tr>'; 

        $no=1;
        foreach ($data->group as $k => $val) {
            $html .= '<tr>';
            $html .= '<td><b>'.$k.'</b></td>';
            $html .= '<td align="right"></td>';
            $html .= '</tr>';
            $no++; 
            foreach ($val as $value_data) {
                if( $value_data->status_nk == 1){
                    $subtotal = (double)$value_data->bill_rs + (double)$value_data->bill_dr1 + (double)$value_data->bill_dr2 + (double)$value_data->lain_lain;
                    $html .= '<tr>';
                    $html .= '<td>'.$value_data->nama_tindakan.'</td>';
                    $html .= '<td align="right">Rp. '.number_format($subtotal).',-</td>';
                    $html .= '</tr>';
                    /*total*/
                    $sum_subtotal[] = $subtotal;
                    $sum_subtotal_peritems[$k][] = $subtotal;
                    /*resume billing*/
                    $resume_billing[] = $this->Billing->resumeBillingRJ($value_data->jenis_tindakan, $value_data->kode_bagian, $subtotal);
                }
            }    
            $arr_subtotal_peritems = isset($sum_subtotal_peritems[$k])?$sum_subtotal_peritems[$k]:[]; 
            $html .= '<tr>';
            $html .= '<td align="right"><b>Subtotal</b></td>';
            $html .= '<td align="right"><b>Rp. '.number_format(array_sum($arr_subtotal_peritems)).',-</b></td>';
            $html .= '</tr>';

        }
        $html .= '<tr>';
            $html .= '<td colspan="1" align="right"><b>TOTAL</b></td>';
            $html .= '<td width="100px" align="right"><b>Rp. '.number_format(array_sum($sum_subtotal)).',-</b></td>';
        $html .= '</tr>';   
        $html .= '</table>';


        return $html;
    }

    public function TemplateResumeMedis($no_registrasi, $tipe, $data){
        /*html data untuk tampilan*/    
        $this->load->model('registration/Reg_pasien_model','Reg_pasien');
        // get riwayat pasien
        $riwayat_pasien = $this->db->get_where('th_riwayat_pasien', array('no_registrasi' => $no_registrasi) )->row();
        $result = $this->Reg_pasien->get_detail_resume_medis($no_registrasi);

        $userDob = $result['registrasi']->tgl_lhr;
 
        //Create a DateTime object using the user's date of birth.
        $dob = new DateTime($userDob);
     
        //We need to compare the user's date of birth with today's date.
        $now = new DateTime();

        //Calculate the time difference between the two dates.
        $difference = $now->diff($dob);

        //Get the difference in years, as we are looking for the user's age.
        $umur = $difference->format('%y');
        $html = '';
        $html .= '<center><h2>RESUME MEDIS PASIEN</h2></center>';
        $html .= '<table align="left" cellpadding="2" cellspacing="2" border="0" width="100%" style="font-size:36px;">';

        $html .= '<tr>';
            $html .= '<td colspan="2"><b>Pernyataan Pasien</b><br>Dengan ini saya selaku pasien, memberikan ijin kepada dokter untuk memberikan keterangan mengenai penyakit saya, guna kepentingan pengajuan klaim saya.</td>';
        $html .= '</tr>';

        $html .= '<tr>';
            $html .= '<td colspan="2"><b>Pernyataan Dokter</b><br>Saya, dokter yang merawat, dengan ini menyatakan bahwa keterangan tersebut dibawah ini lengkap dan benar.</td>';
        $html .= '</tr>';

        $html .= '<tr>';
            $html .= '<td width="100%">
                        <ol>
                            <li><b>Anamnesa</b><br>'.$riwayat_pasien->anamnesa.'</li>
                            <li><b>Diagnosa Penyakit</b>
                                <br>Diagnosa awal, '.$riwayat_pasien->diagnosa_awal.'
                                <br>Diagnosa akhir, '.$riwayat_pasien->diagnosa_akhir.'
                            </li>
                            <li><b>Pemeriksaan yang dilakukan</b><br>'.$riwayat_pasien->pemeriksaan.'</li>
                            <li><b>Anjuran Dokter</b><br>'.$riwayat_pasien->pengobatan.'</li>
                        </ol>
                      </td>';
        $html .= '</tr>';

        // $html .= '<tr>';
        //     $html .= '<td colspan="2">
        //                 <b>Tindakan kepada Pasien</b><br>
        //                 Berikut adalah tindakan yang dilakukan oleh dokter kepada pasien sebagai dasar tagihan kepada pasien.<br>
        //                 <table class="table table-striped" cellpadding="2" cellspacing="2" style="font-size:36px;">
        //                     <tr>
        //                         <th align="center" width="30px" style="border-collapse: collapse; border-bottom: 1px solid black;">No</th>
        //                         <th style="border-collapse: collapse; border-bottom: 1px solid black;">Deskripsi</th>
        //                         <th style="border-collapse: collapse; border-bottom: 1px solid black;" width="120px">Jenis Tindakan</th>
        //                     </tr>';
        //                     $no_tindakan = 0;
        //                     foreach($result['tindakan'] as $row_tindakan) : 
        //                         if(in_array($row_tindakan->kode_jenis_tindakan, array(3,10,12) )) :
        //                             $no_tindakan++;
        //                             $html .= '
        //                             <tr>
        //                                 <td align="center">'.$no_tindakan.'</td>
        //                                 <td>'.$row_tindakan->nama_tindakan.'</td>
        //                                 <td>'.$row_tindakan->jenis_tindakan.'</td>
        //                             </tr>';
        //                         endif;
        //                     endforeach;
        //                 $html .= '</table>
        //               </td>';
        // $html .= '</tr>';

        // $html .= '<tr>';
        //     $html .= '<td colspan="2">
        //                 <b>Obat yang diberikan</b><br>
        //                 Berikut adalah obat yang diberikan kepada pasien sebagai resep dokter.<br>
        //                 <table class="table table-striped" cellpadding="2" cellspacing="2"  width="100%" style="font-size:36px;">
        //                     <tr>
        //                         <th style="border-collapse: collapse; border-bottom: 1px solid black;" align="center" width="30px">No</th>
        //                         <th style="border-collapse: collapse; border-bottom: 1px solid black;">Nama Obat</th>
        //                         <th style="border-collapse: collapse; border-bottom: 1px solid black;" align="center" width="100px">Jumlah</th>
        //                         <th style="border-collapse: collapse; border-bottom: 1px solid black;" width="100px">Keterangan</th>
        //                     </tr>';
        //                     $no_obt = 0;
        //                     foreach($result['tindakan'] as $row_obt) : 
        //                         if(in_array($row_obt->kode_jenis_tindakan, array(11) )) :
        //                             $no_obt++;
        //                             $html .= '
        //                             <tr>
        //                                 <td align="center">'.$no_obt.'</td>
        //                                 <td>'.$row_obt->nama_tindakan.'</td>
        //                                 <td align="center">'.$row_obt->jumlah_tebus.'</td>
        //                                 <td>'.$row_obt->jenis_tindakan.'</td>
        //                             </tr>';
        //                         endif;
        //                     endforeach;
        //                 $html .= '</table>
        //               </td>';
        // $html .= '</tr>';

        $html .= '</table>';


        return $html;
    }
    
    public function TemplateRincianRI($noreg, $tipe, $field){
        $title_name = $this->Billing->getTitleNameBilling($field);
        $rincian_detail_billing = $this->Billing->getDetailData($noreg, $tipe, $field);
        $data = json_decode($rincian_detail_billing);
        $needle = array('bill_tindakan_inap','bill_tindakan_oksigen','bill_tindakan_bedah','bill_tindakan_vk','bill_obat','bill_dokter','bill_apotik','bill_lain_lain','bill_ugd','bill_rad','bill_lab','bill_fisio','bill_klinik','bill_pemakaian_alat',
            );
        if(in_array($field, $needle)){
            $html_dokter = '<th width="20%">Dokter</th>';
            $colspan = 4;
            $percent = 30;
        }else{
            $html_dokter = '';
            $colspan = 3;
            $percent = 50;
        }

        $html = '';
        $html .= '<br><div align="center" width="100%"><p><b>RINCIAN BIAYA '.strtoupper($title_name).'</b></p></div>';
        $html .= '<table class="table table-striped" width="100%">';
        $html .= '<tr>';
            $html .= '<th width="5%" align="center">No</th>';
            $html .= '<th width="20%">Tanggal</th>';
            $html .= '<th width="'.$percent.'%">Keterangan</th>';
            $html .= $html_dokter;
            $html .= '<th width="25%" class="center">Biaya (Rp.)</th>';
        $html .= '</tr>'; 
        $no = 0;
        $arr_subtotal = array();
        foreach ($data->group as $k => $val) {
            foreach ($val as $value_data) {
                
                /*check resume*/
                $resume = $this->Billing->getKodeTransPelayanan($val, $field);
                /*array search kode tc_trans_pelayanan*/
                $array_search = $this->Billing->arraySearchResume($resume, $field);
                //echo '<pre>';print_r($array_search);die;
                if(in_array($value_data->kode_trans_pelayanan, $array_search)){
                    $no++;
                    $subtotal = (double)$value_data->bill_rs + (double)$value_data->bill_dr1 + (double)$value_data->bill_dr2 + (double)$value_data->lain_lain;
                    $html .= '<tr>';
                    $html .= '<td width="5%" align="center">'.$no.'</td>';
                    $html .= '<td width="20%">'.$this->tanggal->formatDate($value_data->tgl_transaksi).'</td>';
                    if($value_data->jenis_tindakan==3){
                        $html .= '<td width="'.$percent.'%"><a href="#" onclick="show_modal_medium('."'Templates/Templates/getDetailFromItem/".$value_data->kode_trans_pelayanan."'".','."'".$value_data->nama_tindakan."'".')">'.$value_data->nama_tindakan.'</a></td>';
                    }else{
                        $html .= '<td width="'.$percent.'%">'.$value_data->nama_tindakan.'</td>';
                    }
                    if(in_array($field, $needle)){
                        $html .= '<td width="20%">'.$value_data->nama_dokter.'</td>';
                    }
                    $html .= '<td width="20%" align="right">'.number_format($subtotal).',-</td>';
                    $html .= '</tr>';
                    $arr_subtotal[] = $subtotal;
                }
                /*jika ada dokumen penunjang add row here*/
            }        
        }
                    $html .= '<tr>';
                    $html .= '<td colspan="'.$colspan.'" align="right"><b>Total Biaya (Rp.)</b></td>';
                    $html .= '<td align="right"><b>Rp. '.number_format(array_sum($arr_subtotal)).',-</b></td>';
                    $html .= '</tr>';
        $html .= '</table>'; 

       
        return $html;
    }

    public function getDetailFromItem($kode){
        $data = $this->db->select('a.*, b.nama_pegawai as dokter1, c.nama_pegawai as dokter2, d.nama_pegawai as dokter3')->join('mt_dokter_v b', 'b.kode_dokter=a.kode_dokter1','left')->join('mt_dokter_v c', 'c.kode_dokter=a.kode_dokter2','left')->join('mt_dokter_v d', 'd.kode_dokter=a.kode_dokter3','left')->get_where('tc_trans_pelayanan a', array('a.kode_trans_pelayanan' => $kode))->row();
        $html = '';
        $html .= '<table class="table">';

        $html .= '<tr>';
        $html .= '<th width="30px">No</th>';
        $html .= '<th>Komponen Tarif</th>';
        $html .= '<th width="120px">Harga (Rp)</th>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>1</td>';
        $html .= '<td>Obat</td>';
        $html .= '<td align="right">'.number_format($data->obat).'</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>2</td>';
        $html .= '<td>Alkes</td>';
        $html .= '<td align="right">'.number_format($data->alkes).'</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>3</td>';
        $html .= '<td>Alat RS</td>';
        $html .= '<td align="right">'.number_format($data->alat_rs).'</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>4</td>';
        $html .= '<td>BHP</td>';
        $html .= '<td align="right">'.number_format($data->bhp).'</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>5</td>';
        $html .= '<td>Pendapatan RS</td>';
        $html .= '<td align="right">'.number_format($data->pendapatan_rs).'</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>6</td>';
        $html .= '<td>Kamar Tindakan</td>';
        $html .= '<td align="right">'.number_format($data->kamar_tindakan).'</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>7</td>';
        $html .= '<td>'.$data->dokter1.'</td>';
        $html .= '<td align="right">'.number_format($data->bill_dr1).'</td>';
        $html .= '</tr>';

        if( $data->bill_dr2 > 0 ){
            $nama_dr2 = ($data->dokter2=='')?'-Belum ditentukan-':$data->dokter2;
            $html .= '<tr>';
            $html .= '<td>8</td>';
            $html .= '<td>'.$nama_dr2.'</td>';
            $html .= '<td align="right">'.number_format($data->bill_dr2).'</td>';
            $html .= '</tr>';
        }

        if( $data->bill_dr3 > 0 ){
            $nama_dr3 = ($data->dokter3=='')?'-Belum ditentukan-':$data->dokter3;
            $html .= '<tr>';
            $html .= '<td>8</td>';
            $html .= '<td>'.$nama_dr3.'</td>';
            $html .= '<td align="right">'.number_format($data->bill_dr3).'</td>';
            $html .= '</tr>';
        }

        // total
        $total = $data->bill_rs + $data->bill_dr1 + $data->bill_dr2;
        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td align="right"><i><b>Total</b></i></td>';
        $html .= '<td align="right"><b>'.number_format($total).'</b></td>';
        $html .= '</tr>';
        
        $html .= '</table>';

        echo $html;
    }

    public function TemplateRincianRIData($noreg, $tipe, $field){
        $title_name = $this->Billing->getTitleNameBilling($field);
        $rincian_detail_billing = $this->Billing->getDetailData($noreg, $tipe, $field);
        $data = json_decode($rincian_detail_billing);
        $needle = array('bill_tindakan_inap','bill_tindakan_oksigen','bill_tindakan_bedah','bill_tindakan_vk','bill_obat','bill_dokter','bill_apotik','bill_lain_lain','bill_ugd','bill_rad','bill_lab','bill_fisio','bill_klinik','bill_pemakaian_alat',
            );
        if(in_array($field, $needle)){
            $html_dokter = '<th width="20%">Dokter</th>';
            $colspan = 4;
            $percent = 30;
        }else{
            $html_dokter = '';
            $colspan = 3;
            $percent = 50;
        }

        $html = '';
        $no = 0;
        $arr_subtotal = array();
        foreach ($data->group as $k => $val) {
            foreach ($val as $value_data) {
                
                /*check resume*/
                $resume = $this->Billing->getKodeTransPelayanan($val, $field);
                /*array search kode tc_trans_pelayanan*/
                $array_search = $this->Billing->arraySearchResume($resume, $field);
                //echo '<pre>';print_r($array_search);die;
                if(in_array($value_data->kode_trans_pelayanan, $array_search)){
                    $no++;
                    $subtotal = (double)$value_data->bill_rs + (double)$value_data->bill_dr1 + (double)$value_data->bill_dr2 + (double)$value_data->lain_lain;
                    $getData[] = array('tanggal' => $this->tanggal->formatDatedmY($value_data->tgl_transaksi), 'tindakan' => $value_data->nama_tindakan, 'dokter' => $value_data->nama_dokter, 'subtotal' => $subtotal);
                }
            }        
        }
                
       
        return $getData;
    }

    public function TemplateBillingRI( $no_registrasi, $tipe, $data, $rb='' ){
        $csm_bp = new Billing_model;
        /*html data untuk tampilan*/
        $dataRI = $this->Billing->getDataRI($no_registrasi);
        
        $no=1;
        // echo '<pre>';print_r($data->group);die;
        foreach ($data->group as $k => $val) {
            foreach ($val as $value_data) {
                $subtotal = (double)$value_data->bill_rs + (double)$value_data->bill_dr1 + (double)$value_data->bill_dr2 + (double)$value_data->lain_lain;
                $resume_billing[] = $this->Billing->resumeBillingRI($value_data);
                // echo '<pre>';print_r($resume_billing);die;
            }        
        }
        /*split resume billing*/
        $split_billing = $this->Billing->splitResumeBillingRI($resume_billing);

        $html = '';
        if( $rb == '') :
        
        /*html data untuk tampilan*/
        $html .= '<div align="center"><b>RINCIAN BIAYA KESELURUHAN PASIEN RAWAT INAP</b></div>';
        $html .= '<table class="table table-striped" border="0">';
        $html .= '<hr>';
        $html .= '<tr>';
            $html .= '<th width="7%" align="center"><b>NO</b></th>';
            $html .= '<th width="78%"><b>URAIAN</b></th>';
            $html .= '<th width="15%" align="center"><b>SUBTOTAL (Rp.)</b></th>';
        $html .= '</tr>'; 
        // echo '<pre>';print_r($split_billing);die;
        foreach ($split_billing as $k => $val) {
            /*total*/
            if((int)$val['subtotal'] > 0){
                $sum_subtotal[] = $val['subtotal'];
                $html .= '<tr>';
                $html .= '<td width="7%" align="center"><b>'.$no.'</b></td>';
                $html .= '<td width="93%"><b>'.strtoupper($val['title']).'</b></td>';
                $html .= '</tr>';
                $no++;
                /*rincian biaya*/
                $rincian_billing_ri =  $this->TemplateRincianRIData($no_registrasi, $tipe, $val['field']);
                // echo '<pre>';print_r($rincian_billing_ri);die;
                foreach ($rincian_billing_ri as $key_rincian_billing_ri => $value_rincian_billing_ri) {
                    $html .= '<tr>';
                    $html .= '<td width="7%" align="center"></td>';
                    $html .= '<td width="33%">'.$value_rincian_billing_ri['tindakan'].'</td>';
                    $html .= '<td width="10%">'.$value_rincian_billing_ri['tanggal'].'</td>';
                    $html .= '<td width="30%">'.$value_rincian_billing_ri['dokter'].'</td>';
                    $html .= '<td width="20%" align="right">'.number_format($value_rincian_billing_ri['subtotal']).'</td>';
                    $html .= '</tr>';
                    $subtotal_rincian[$val['field']][] = $value_rincian_billing_ri['subtotal'];
                }
                /*subtotal rincian*/
                    $html .= '<tr>';
                    $html .= '<td width="100%" align="right"><b><i>Subtotal</i>&nbsp;&nbsp;&nbsp;'.number_format(array_sum($subtotal_rincian[$val['field']])).' </b></td>';
                    $html .= '</tr>';
            }
        }
        /*biaya materai*/
            $html .= '<tr>';
                    $html .= '<td width="7%" align="center"><b>'.$no.'</b></td>';
                    $html .= '<td width="73%"><b>MATERAI</b></td>';
                    $html .= '<td width="20%" align="right"><b><i>Subtotal</i>&nbsp;&nbsp;&nbsp; 6,000 </b></td>';
                    $html .= '</tr>';
        //$html .= '<hr>';

        $total_plus_materai = array_sum($sum_subtotal) + 6000;
        $html .= '<tr>';
                    $html .= '<td width="100%" align="right"><b>TOTAL Rp. '.number_format($total_plus_materai).'</b></td>';
                    $html .= '</tr>';

        $html .= '</table>';

        else :
            $content_rincian = $csm_bp->getRincianBillingData($no_registrasi, $tipe, $rb);
            $decode = json_decode($content_rincian);
            $content_html = $decode->html;
            $html .= $content_html;
        endif;
        //print_r($html);die;
        return $html;
    }

    public function TemplateHasilPMOri($no_registrasi, $tipe, $data, $pm, $flag_mcu='',$data_pm=''){
        /*html data untuk tampilan*/
        /*get data hasil penunjang medis*/
        $pm_data = $this->Billing->getHasilLab($data->reg_data, $pm, $flag_mcu);
        // echo '<pre>';print_r($pm_data);die;
        $html = '';
        if($tipe=='RAD'){
            // $html .= '<br><table class="table table-striped table-bordered" cellpadding="2" cellspacing="2" border="0">
            //         <tr>
            //             <td colspan="4" align="center"><b>HASIL PEMERIKSAAN RADIOLOGI</b></td>
            //         </tr> 
            //         <hr>
            //         <tr>
            //             <th width="30px" align="center"><b>NO</b></th>
            //             <th><b>JENIS PEMERIKSAAN</b></th>
            //             <th><b>HASIL</b></th>
            //             <th><b>KESAN</b></th>
            //             <th><b>KETERANGAN</b></th>
            //         </tr>
            //         <hr>';
            //         $no=0;
            //         foreach ($pm_data as $key => $value) {
            //             $name = ($value->nama_pemeriksaan)?$value->nama_pemeriksaan:$value->nama_tindakan;
            //             $no++;
            //             $html .= '<tr>
            //                         <td width="30px" align="center">'.$no.'</td>
            //                         <td>'.$name.'</td>
            //                         <td><p style="text-align:justify">'.nl2br($value->hasil).'</p></td>
            //                         <td><p style="text-align:justify">'.nl2br($value->keterangan).'</p></td>
            //                         <td></td>
            //                       </tr>';
            //         }
            
            // $html .= '</table><br><br>';

            // $html .= '<b>Catatan : </b><br><br>';

            $html .= '<br><table  cellpadding="2" cellspacing="2" border="0" width="100%" style="font-size:36px">
                    <tr>
                        <td colspan="2" align="center"><b>HASIL PEMERIKSAAN RADIOLOGI</b></td>
                    </tr> 
                    ';

            foreach ($pm_data as $key => $value) {
                $name = ($value->nama_pemeriksaan)?$value->nama_pemeriksaan:$value->nama_tindakan;
                //$no++;
                $html .= '
                        <hr>        
                        <tr>
                            <th width="100px"><b>Pemeriksaan</b> </th>
                            <td width="10px">:</td>
                            <th>'.$name.'</th>
                        </tr>
                        <hr>
                        <tr>
                            <td valign="top" width="100px"><b>Hasil</b> </td>
                            <td width="10px">:</td>
                            <td>'.nl2br($value->hasil).'</td>
                        </tr>
                        <tr>
                            <td valign="top" width="100px"><b>Kesan</b> </td>
                            <td width="10px">:</td>
                            <td>'.nl2br($value->keterangan).'</td>
                        </tr>
                        ';
            }

            $html .= '</table><br><br>';

            $html .= '<b style="font-size:36px">Catatan : </b><br> '.trim($data_pm->catatan_hasil).'<br>';

        }elseif ($tipe=='LAB') {
           
            // echo '<pre>';print_r($data);die;
            $referensi = $this->Billing->getRefLab($data->reg_data, $pm,$flag_mcu);
            
            // echo '<pre>';print_r($referensi);die;
            $getRef = array();
            // referensi
            // foreach($referensi as $key=>$row){
            //     $getRef[$row->referensi][] = $row;
            // }
            
            // echo '<pre>';print_r($pm_data);die;
            $html .= '<br><table cellpadding="0" cellspacing="0" border="0" style="font-size:38px">
                    <tr>
                        <td colspan="5" align="center"><b>HASIL PEMERIKSAAN LABORATORIUM</b></td>
                    </tr> 
                    <hr>
                    <tr>                        
                        <th align="center" width="160px"><b>JENIS TEST</b></th>
                        <th align="center" width="100px"><b>HASIL</b></th>
                        <th align="center" width="150px"><b>NILAI STANDAR</b></th>
                        <th align="center" width="80px"><b>SATUAN</b></th>
                        <th width="100px"><b>KETERANGAN</b></th>
                    </tr>
                    <hr>';
            $no=0;
            if(count($pm_data) > 0){
                $referen = '';
                $nama_tindakan = '';
                $nama_pemeriksaan = '';
                $detail_item_1 = '';
                $detail_item_2 = '';
                
                for($i=0;$i<count($referensi);$i++) {

                    if(($referensi[$i]->referensi!=$referen)){
                        $html .= '<tr>
                                <td colspan="5"><b>'.$referensi[$i]->referensi.'</b></td>
                            </tr>';
                            $referen = $referensi[$i]->referensi;
                    }
                                      
                    foreach ($pm_data as $key => $value) {
                        $standar = ($data->reg_data->jk == 'L') ? $value->standar_hasil_pria : $value->standar_hasil_wanita;
                        if(trim($value->nama_pemeriksaan)==trim($referensi[$i]->nama_pemeriksaan)){
                         
                            if($value->detail_item_1 != ' ' AND $value->detail_item_1 != NULL){
                                
                                if((trim($value->nama_tindakan)!=$nama_tindakan)){
                                    
                                    $html .= '<tr>
                                        <td colspan="5">&nbsp;'.strtoupper($value->nama_tindakan).'</td>
                                    </tr>';

                                    $nama_tindakan = trim($value->nama_tindakan);

                                }

                                if((trim($value->nama_pemeriksaan)!=$nama_pemeriksaan)){
                                    $html .= '<tr>
                                        <td colspan="5">&nbsp;&nbsp;&nbsp;'.$value->nama_pemeriksaan.'</td>
                                    </tr>';
                                    $nama_pemeriksaan = trim($value->nama_pemeriksaan);
                                }

                                if($value->detail_item_2 != ' ' AND $value->detail_item_2 != NULL){
                                    
                                    if((trim($value->detail_item_1)!=$detail_item_1)){
                                        $html .= '<tr>
                                            <td colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&#x203A; '.$value->detail_item_1.'</td>
                                        </tr>';
                                        $detail_item_1 = trim($value->detail_item_1);
                                    }

                                    if((trim($value->detail_item_2)!=$detail_item_2)){
                                        $html .= '<tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#xbb; '.$value->detail_item_2.'</td>
                                                <td align="center">'.stripslashes($value->hasil).'</td>
                                                <td align="center">'.str_replace(array('>','<'), array('&rsaquo;','&lsaquo;'), $standar).'</td>
                                                <td align="center">'.$value->satuan.'</td>
                                                <td><br>'.$value->keterangan.'</td>
                                            </tr>';
                                        $detail_item_2 = trim($value->detail_item_2);
                                    }

                                }else{

                                    if((trim($value->detail_item_1)!=$detail_item_1)){                                    
                                        $html .= '<tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&#x203A; '.$value->detail_item_1.'</td>
                                            <td align="center">'.stripslashes($value->hasil).'</td>
                                            <td align="center">'.str_replace(array('>','<'), array('&rsaquo;','&lsaquo;'), $standar).'</td>
                                            <td align="center">'.$value->satuan.'</td>
                                            <td><br>'.$value->keterangan.'</td>
                                        </tr>';
                                        $detail_item_1 = trim($value->detail_item_1);
                                    }
                                    
                        
                                }

                            }else{
                                $html .= '<tr>
                                             <td>&nbsp;&nbsp;&nbsp;'.$value->nama_pemeriksaan.'</td>
                                             <td align="center">'.stripslashes($value->hasil).'</td>
                                             <td align="center">'.str_replace(array('>','<'), array('&rsaquo;','&lsaquo;'), $standar).'</td>
                                             <td align="center">'.$value->satuan.'</td>
                                             <td><br>'.$value->keterangan.'</td>
                                         </tr>';
                            }
                           
                        }
                        
                    }
                }

            }
            
            
            $html .= '</table><br><br><hr>';
            $html .= '<br><br><b><p style="font-size:38px">Catatan : </p></b><br><br>
                '.trim($data_pm->catatan_hasil).'
            <br><div style="border-bottom:0.6px dotted black"></div>';
        }
        
        //echo '<pre>';
        //print_r($html);die;

        return $html;
    }

    public function TemplateHasilPM($no_registrasi, $tipe, $data, $pm, $flag_mcu='',$data_pm=''){
        /*html data untuk tampilan*/
        /*get data hasil penunjang medis*/
        $pm_data = $this->Billing->getHasilLab($data->reg_data, $pm, $flag_mcu);
        // echo '<pre>';print_r($pm_data);die;
        $html = '';
        if($tipe=='RAD'){
            $html .= '<br><table  cellpadding="2" cellspacing="2" border="0" width="100%" style="font-size:36px">
                    <tr>
                        <td colspan="2" align="center"><b>HASIL PEMERIKSAAN RADIOLOGI</b></td>
                    </tr> 
                    ';

            foreach ($pm_data as $key => $value) {
                $name = ($value->nama_pemeriksaan)?$value->nama_pemeriksaan:$value->nama_tindakan;
                //$no++;
                $html .= '
                        <hr>        
                        <tr>
                            <th width="100px"><b>Pemeriksaan</b> </th>
                            <td width="10px">:</td>
                            <th>'.$name.'</th>
                        </tr>
                        <hr>
                        <tr>
                            <td valign="top" width="100px"><b>Hasil</b> </td>
                            <td width="10px">:</td>
                            <td>'.nl2br($value->hasil).'</td>
                        </tr>
                        <tr>
                            <td valign="top" width="100px"><b>Kesan</b> </td>
                            <td width="10px">:</td>
                            <td>'.nl2br($value->keterangan).'</td>
                        </tr>
                        ';
            }

            $html .= '</table><br><br>';

            $html .= '<b style="font-size:36px">Catatan : </b><br> '.trim($data_pm->catatan_hasil).'<br>';

        }elseif ($tipe=='LAB') {
           
            // echo '<pre>';print_r($data);die;
            $referensi = $this->Billing->getRefLab($data->reg_data, $pm,$flag_mcu);
            foreach ($referensi as $key => $value) {
                $getReferensiDt[$value->referensi][] = array('nama_pemeriksaan' => $value->nama_pemeriksaan, 'nama_tarif' => $value->nama_tarif);
            }
            // echo '<pre>';print_r($getReferensiDt);die;
            $getRef = array();
            // echo '<pre>';print_r($pm_data);die;
            $html .= '<br><table cellpadding="0" cellspacing="0" border="0" style="font-size:38px">
                    <tr>
                        <td colspan="5" align="center"><b>HASIL PEMERIKSAAN LABORATORIUM</b></td>
                    </tr> 
                    <hr>
                    <tr>                        
                        <th align="center" width="160px"><b>JENIS TEST</b></th>
                        <th align="center" width="100px"><b>HASIL</b></th>
                        <th align="center" width="150px"><b>NILAI STANDAR</b></th>
                        <th align="center" width="80px"><b>SATUAN</b></th>
                        <th width="100px"><b>KETERANGAN</b></th>
                    </tr>
                    <hr>';
            $no=0;
            if(count($pm_data) > 0){
                $referen = '';
                $nama_tindakan = '';
                $nama_pemeriksaan = '';
                $detail_item_1 = '';
                $detail_item_2 = '';
                
                for($i=0;$i<count($referensi);$i++) {

                    if(($referensi[$i]->referensi!=$referen)){
                        $html .= '<tr>
                                <td colspan="5"><b>'.$referensi[$i]->referensi.'</b></td>
                            </tr>';
                            $referen = $referensi[$i]->referensi;
                    }
                                      
                    foreach ($pm_data as $key => $value) {
                        $standar = ($data->reg_data->jk == 'L') ? $value->standar_hasil_pria : $value->standar_hasil_wanita;
                        if(trim($value->nama_pemeriksaan)==trim($referensi[$i]->nama_pemeriksaan)){
                         
                            if($value->detail_item_1 != ' ' AND $value->detail_item_1 != NULL){
                                
                                if((trim($value->nama_tindakan)!=$nama_tindakan)){
                                    
                                    $html .= '<tr>
                                        <td colspan="5">&nbsp;'.strtoupper($value->nama_tindakan).'</td>
                                    </tr>';

                                    $nama_tindakan = trim($value->nama_tindakan);

                                }

                                if((trim($value->nama_pemeriksaan)!=$nama_pemeriksaan)){
                                    $html .= '<tr>
                                        <td colspan="5">&nbsp;&nbsp;&nbsp;'.$value->nama_pemeriksaan.'</td>
                                    </tr>';
                                    $nama_pemeriksaan = trim($value->nama_pemeriksaan);
                                }

                                if($value->detail_item_2 != ' ' AND $value->detail_item_2 != NULL){
                                    
                                    if((trim($value->detail_item_1)!=$detail_item_1)){
                                        $html .= '<tr>
                                            <td colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&#x203A; '.$value->detail_item_1.'</td>
                                        </tr>';
                                        $detail_item_1 = trim($value->detail_item_1);
                                    }

                                    if((trim($value->detail_item_2)!=$detail_item_2)){
                                        $html .= '<tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#xbb; '.$value->detail_item_2.'</td>
                                                <td align="center">'.stripslashes($value->hasil).'</td>
                                                <td align="center">'.str_replace(array('>','<'), array('&rsaquo;','&lsaquo;'), $standar).'</td>
                                                <td align="center">'.$value->satuan.'</td>
                                                <td><br>'.$value->keterangan.'</td>
                                            </tr>';
                                        $detail_item_2 = trim($value->detail_item_2);
                                    }

                                }else{

                                    if((trim($value->detail_item_1)!=$detail_item_1)){                                    
                                        $html .= '<tr>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&#x203A; '.$value->detail_item_1.'</td>
                                            <td align="center">'.stripslashes($value->hasil).'</td>
                                            <td align="center">'.str_replace(array('>','<'), array('&rsaquo;','&lsaquo;'), $standar).'</td>
                                            <td align="center">'.$value->satuan.'</td>
                                            <td><br>'.$value->keterangan.'</td>
                                        </tr>';
                                        $detail_item_1 = trim($value->detail_item_1);
                                    }
                                    
                        
                                }

                            }else{
                                $html .= '<tr>
                                             <td>&nbsp;&nbsp;&nbsp;'.$value->nama_pemeriksaan.'</td>
                                             <td align="center">'.stripslashes($value->hasil).'</td>
                                             <td align="center">'.str_replace(array('>','<'), array('&rsaquo;','&lsaquo;'), $standar).'</td>
                                             <td align="center">'.$value->satuan.'</td>
                                             <td><br>'.$value->keterangan.'</td>
                                         </tr>';
                            }
                           
                        }
                        
                    }
                }

            }
            
            
            $html .= '</table><br><br><hr>';
            $html .= '<br><br><b><p style="font-size:38px">Catatan : </p></b><br><br>
                '.trim($data_pm->catatan_hasil).'
            <br><div style="border-bottom:0.6px dotted black"></div>';
        }
        
        //echo '<pre>';
        //print_r($html);die;

        return $html;
    }

    public function setGlobalFooterBillingPM($nama_dokter, $flag='', $pm=''){
        $html = '';
        if($flag=='RAD'){
            $html .= '<table border="0" cellspacing="0" cellpadding="0" style="font-size:36px">
                    <tr> 
                        <td align="right" width="70%"></td>
                        <td align="center" width="30%">
                        <br><br>
                        Dokter Radiologi<br>
                        '.COMP_FULL.'
                        <br/><br/><br/> 
                        <br/> 
                        ( '.$this->Billing->getNamaDokter($flag, $pm).' )<br>
                        Generated by '.APPS_NAME_SORT.' ('.date('d/M/Y').')
                        </td>   
                    </tr>
                </table>';
            }elseif ($flag=='LAB') {
                $html .= '<table border="0" cellspacing="0" cellpadding="0" style="font-size:36px">
                    <tr> 
                        <td align="center" width="30%">
                        <br><br>
                        Penanggung Jawab<br>
                        <br/><br/><br/> 
                        <br/> 
                        ( '.$this->Billing->getNamaDokter_($flag, $pm).' )
                        </td>
                        <td align="center" width="40%">&nbsp;</td>
                        <td align="center" width="30%">
                        <br><br>
                        Petugas Laboratorium<br>
                        '.COMP_FULL.'
                        <br/><br/><br/> 
                        <br/> 
                        ___________________________<br><br>
                        Generated by '.APPS_NAME_SORT.' ('.date('d/M/Y').')
                        </td>   
                    </tr>
                </table>';
            }
        
        return $html;
    }   

    public function setGlobalFooterBilling($data){
        $html = '';
        $html .= '<table width="100%" border="1" cellspacing="0" cellpadding="0" border="0">
                    <tr> 
                        <td align="right" width="300px">
                        <br><br>
                        Jakarta,&nbsp;'.$this->tanggal->formatDate($data->reg_data->tgl_jam_masuk).'<br>
                        '.COMP_FULL.'
                        <br/><br/><br/><br/> 
                        <br/> 
                        ( _____________________ )<br>
                        Generated by '.APPS_NAME_SORT.' ('.date('d/M/Y').')
                        
                        </td>   
                    </tr>
                </table>';
        return $html;
    }

    public function setGlobalFooterRm($data){
        $html = '';
        $html .= '<table width="100%" border="1" cellspacing="0" cellpadding="0" border="0">
                    <tr> 
                        <td width="50%"></td>
                        <td align="right" width="50%">
                        <br><br>
                        Jakarta,&nbsp;'.$this->tanggal->formatDate($data->reg_data->tgl_jam_masuk).'<br>
                        '.COMP_FULL.'
                        <br/><br/><br/><br/> 
                        <br/> 
                        ( _____________________ )<br>
                        '.$data->reg_data->nama_pegawai.'
                        
                        </td>   
                    </tr>
                </table>';
        return $html;
    }

    public function setGlobalFooterBillingRI($data){
        $html = '';
        $html .= '<br><br><br><table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr> 
                        <td align="center" width="30%">&nbsp;</td>
                        <td align="center" width="30%">&nbsp;</td>
                        <td align="center" width="40%">
                        <br><br>
                        Jakarta,&nbsp;'.$this->tanggal->formatDate($data->reg_data->tgl_jam_masuk).'<br>
                        '.COMP_FULL.'
                        <br/><br/><br/><br/> 
                        <br/> 
                        ( ______________________________ )<br>
                        Generated by '.APPS_NAME_SORT.' ('.date('d/M/Y').')
                        
                        </td>   
                    </tr>
                </table>';
        return $html;
    }

    public function setGlobalProfilePasienTemplatePM($data, $flag, $pm, $data_pm=''){
        $html = '';
        $jk = ($data->reg_data->jk == 'L')?'Pria':'Wanita';
        if($flag=='RAD'){
            $html .= '<table align="left" cellpadding="0" cellspacing="0" border="0" style="font-size:36px">
                        <tr>
                            <td width="100px">No. RM</td>
                            <td width="250px">: '.$data->reg_data->no_mr.'</td>
                            <td width="150px">No. Penunjang</td>
                            <td width="250px">: '.$pm.'</td>
                        </tr>
                        <tr>
                            <td width="100px" align="left">Nama Pasien</td>
                            <td width="250px">: '.ucwords(strtolower($data->reg_data->nama_pasien)).'</td>
                            <td width="150px">Dokter Pengirim</td>
                            <td width="250px">: '.$data->reg_data->nama_pegawai.'</td>
                        </tr>
                        <tr>
                            <td width="100px">Umur</td>
                            <td width="250px">: '.$data->reg_data->umur.' Tahun</td>
                            <td width="150px">Tanggal Pemeriksaan</td>
                            <td align="left" width="200px">: '.$this->tanggal->formatDateTime($data_pm->tgl_transaksi).'</td>
                            
                        </tr>

                        <tr>
                            <td width="100px">Jenis Kelamin</td>
                            <td width="250px">: '.$jk.'</td>
                            <td width="150px">Laporan Pemeriksaan</td>
                            <td width="250px">: '.$this->tanggal->formatDateTime(date('Y-m-d H:i:s')).'</td>
                        </tr>                    
                    </table>';
        }else{
            $html .= '<table align="left" cellpadding="0" cellspacing="0" border="0" style="font-size:36px">
                        <tr>
                            <td width="150px">No. Penunjang</td>
                            <td width="250px">: '.$pm.'</td>
                            <td width="100px">No. RM</td>
                            <td width="250px">: '.$data->reg_data->no_mr.'</td>
                        </tr>
                        <tr>
                            <td width="150px">Dokter Pengirim</td>
                            <td width="250px">: '.$data->reg_data->nama_pegawai.'</td>
                            <td width="100px" align="left">Nama Pasien</td>
                            <td width="250px">: '.ucwords(strtolower($data->reg_data->nama_pasien)).'</td>
                        </tr>
                        <tr>
                            <td width="150px">Tanggal Pendaftaran</td>
                            <td align="left" width="250px">: '.$this->tanggal->formatDateTime($data->reg_data->tgl_jam_masuk).'</td>
                            <td width="100px">Umur</td>
                            <td width="250px">: '.$data->reg_data->umur.' Tahun</td>     
                        </tr>

                        <tr>
                            <td width="150px">Tanggal Pemeriksaan</td>
                            <td width="250px">: '.$this->tanggal->formatDateTime($data_pm->tgl_periksa).'</td>
                            <td width="100px">Jenis Kelamin</td>
                            <td width="250px">: '.$jk.'</td>
                        </tr>    
                        
                        <tr>
                        <td width="150px">Ruangan / Kelas</td>
                        <td width="250px">: '.$data_pm->nama_bagian.' / '.$data_pm->nama_klas.'</td>
                      
                        </tr>    
                    </table>';
        }
        
        return $html;
    }

    public function export_tp_pdf($exp_no_registrasi, $tipe, $unique_code, $act_code){
        
        $this->Export_data->getContentPDF($exp_no_registrasi, $tipe, $unique_code, $act_code);
        return true;
    }


}

/* End of file templates.php */
/* Location: ./application/modules/templates/controllers/templates.php */