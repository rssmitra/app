
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pindah_ruangan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Pindah_ruangan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pindah_ruangan_model', 'Pindah_ruangan');

        $this->load->model('Reg_pasien_model', 'Reg_pasien');

        $this->load->library('Form_validation');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Pindah_ruangan/index', $data);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Pindah_ruangan->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->kode_ri.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#" onclick="showModalPindahRuangan('."'".$row_list->kode_ri."'".','."'".$row_list->no_mr."'".')">Pindah Ruangan</a></li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = '<a href="#">'.strtoupper($row_list->nama_pasien).'</>';
            $row[] = ($row_list->nama_perusahaan==NULL)?'<div class="left">PRIBADI/UMUM</div>':'<div class="left">'.$row_list->nama_perusahaan.'</div>';
            $row[] = $this->tanggal->formatDate($row_list->tgl_masuk);
            $row[] = '<div class="left">'.$row_list->nama_bagian.'</div>';
            $row[] = '<a href="#">'.strtoupper($row_list->nama_klas).'</>';
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pindah_ruangan->count_all(),
                        "recordsFiltered" => $this->Pindah_ruangan->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function form_pindah_ruangan($id='')
    
    {
        
        $data = array();
        
        /*if id is not null then will show form edit*/
        
        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $id, array('no_mr') );

        $data['value'] = $data_pasien[0];

        $kode_ri = ($this->input->get('ID'))?$this->input->get('ID'):0;
        

        if($kode_ri!=0){

            $data_ri = $this->Pindah_ruangan->get_by_id($kode_ri);
            $data['rawatinap'] = $data_ri;

        }
        
        /*load form view*/
        
        $this->load->view('Pindah_ruangan/form_pindah_ruangan', $data);
    
    }

    public function process(){

        // form validation

        $this->form_validation->set_rules('ri_ruangan', 'Nama Ruangan', 'trim|required');
        $this->form_validation->set_rules('ri_klas_ruangan', 'Kelas', 'trim|required');
        $this->form_validation->set_rules('ri_kamar', 'Pilih bed', 'trim|required');
        $this->form_validation->set_rules('ri_dokter_ruangan', 'Dokter yang merawat', 'trim|required');
    
        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                 
            
            /*execution*/
            $this->db->trans_begin();
            //print_r($this->input->post('kelas_pas').' != '.$this->form_validation->set_value('ri_klas_ruangan').' && '.$this->input->post('kode_bagian') .'!='. $this->form_validation->set_value('ri_ruangan') );

            /*cek existing*/
            $existing = $this->Pindah_ruangan->get_by_id($_POST['kode_ri']);
            //print_r($existing); die;
            //if ( ($this->input->post('kelas_pas') == $this->form_validation->set_value('ri_klas_ruangan')) AND ($this->input->post('kode_bagian') == $this->form_validation->set_value('ri_ruangan')) ){

            if ( $existing->kode_ruangan == $_POST['ri_kamar'] ){
                
                echo json_encode(array('status' => 301, 'message' => 'Ruangan yang dipilih sama dengan ruangan sebelumnya'));

            }else{
                
                /*edit mt_ruangan lama */
                $data_mt_ruangan_asal["status"] = NULL;

                $this->Pindah_ruangan->update('mt_ruangan',  $data_mt_ruangan_asal, array('kode_ruangan' => $this->input->post('kode_ruangan')));

                /*edit pindah tc_riwayat_kelas ruangan sebelum */

                $data_riw_kelas_awal = array(
                    'tgl_pindah' => date('Y-m-d h:i:s'),
                    'ket_keluar' => 2
                );

                $this->Pindah_ruangan->update('ri_tc_riwayat_kelas',  $data_riw_kelas_awal, array('kode_ri' => $this->input->post('kode_ri'), 'tgl_pindah !=' => NULL));

                /*---------------------------------------------------------*/

                /*edit status keluar tc_kunjungan sebelum */

                $data_tc_kunj_awal = array(
                    'tgl_keluar' => date('Y-m-d h:i:s'),
                    'status_keluar' => 1
                );

                $this->Pindah_ruangan->update('tc_kunjungan',  $data_tc_kunj_awal, array('no_kunjungan' => $this->input->post('no_kunjungan')));

                /*---------------------------------------------------------*/

                /*insert kunjungan tujuan*/

                $no_kunjungan_tuju = $this->master->get_max_number('tc_kunjungan', 'no_kunjungan');
                $datakunjungan_tuju = array(
                    'no_kunjungan' => $no_kunjungan_tuju,
                    'no_registrasi' => $this->input->post('no_registrasi'),
                    'no_mr' => $this->input->post('no_mr'),
                    'kode_dokter' => $this->form_validation->set_value('ri_dokter_ruangan'),
                    'kode_bagian_tujuan' =>  $this->form_validation->set_value('ri_ruangan'),
                    'kode_bagian_asal' => $this->input->post('kode_bagian'),
                    'tgl_masuk' =>  $data_riw_kelas_awal['tgl_pindah'],
                    'status_masuk' => 0
                );

                $idKunjunganTuju = $this->Pindah_ruangan->save('tc_kunjungan', $datakunjungan_tuju);

                /*---------------------------------------------------------*/

                /*insert ri tc riwayat kelas*/

                $data_ruangan_tuju = $this->db->get_where('mt_ruangan', array('kode_ruangan' => $this->form_validation->set_value('ri_kamar')))->row();

                $data_ruangan_asal = $this->db->get_where('mt_ruangan', array('kode_ruangan' => $this->input->post('kode_ruangan')))->row();

                $data_tc_riwayat_kelas_ = array(
                    'kode_ri' => $this->input->post('kode_ri'),
                    'kode_kunjungan' => $this->input->post('no_kunjungan'),
                    'no_registrasi' => $this->input->post('no_registrasi'),
                    'no_mr' => $this->input->post('no_mr'),
                    'kode_kelompok' => $this->input->post('kode_kelompok'),
                    'kode_perusahaan' => $this->input->post('kode_perusahaan'),
                    'kode_dokter' => $this->form_validation->set_value('ri_dokter_ruangan'),
                    'kode_ruangan' => $this->form_validation->set_value('ri_kamar'),
                    'bagian_tujuan' =>  $this->form_validation->set_value('ri_ruangan'),
                    'kelas_tujuan' =>  $this->form_validation->set_value('ri_klas_ruangan'),
                    'no_kamar_tujuan' =>  $data_ruangan_tuju->no_kamar,
                    'no_bed_tujuan' =>  $data_ruangan_tuju->no_bed,
                    'bagian_asal' => $this->input->post('kode_bagian'),
                    'kelas_asal' =>  $this->input->post('kelas_pas'),
                    'no_kamar_asal' =>  $data_ruangan_asal->no_kamar,
                    'no_bed_asal' =>  $data_ruangan_asal->no_bed,
                    'tgl_masuk' =>  $data_riw_kelas_awal['tgl_pindah'],
                    'ket_masuk' =>  1,
                );

                $this->Pindah_ruangan->save('ri_tc_riwayat_kelas', $data_tc_riwayat_kelas_);

                /*---------------------------------------------------------*/

                /*edit ruangan*/

                $data_mt_ruangan_tuju["status"] = "ISI";

                $this->Pindah_ruangan->update('mt_ruangan',  $data_mt_ruangan_tuju, array('kode_ruangan' => $this->form_validation->set_value('ri_kamar')));

                /*---------------------------------------------------------*/

                 /*edit tc_rawat_inap*/

                $data_tc_rawat_inap = array(
                    'kode_ruangan' => $this->form_validation->set_value('ri_kamar'),
                    'bag_pas' => $this->form_validation->set_value('ri_ruangan'),
                    'kelas_pas' => $this->form_validation->set_value('ri_klas_ruangan'),
                    'status_pindah' => 0
                );

                 $this->Pindah_ruangan->update('ri_tc_rawatinap',  $data_tc_rawat_inap, array('kode_ri' => $this->input->post('kode_ri')));
 
                 /*---------------------------------------------------------*/


                /*trans pelayanan */

                $tarif_ = $this->db->get_where('mt_master_tarif_ruangan',array('kode_bagian=' => $this->form_validation->set_value('ri_ruangan'), 'kode_klas' => $this->form_validation->set_value('ri_klas_ruangan')))->row();
                $nama_bagian = $this->db->get_where('mt_bagian',array('kode_bagian=' => $this->form_validation->set_value('ri_ruangan')))->row();

                if ($this->input->post('kode_kelompok')==3 && $this->input->post('kode_perusahaan')==120) {
                    $tarif = $tarif_->harga_bpjs;
                    if ($tarif < 1) {
                        $tarif = $tarif_->harga_r;
                    }	
                }else{
                    $tarif = $tarif_->harga_r;
                }

                $trans_p = $this->db->query("select * from tc_trans_pelayanan where no_registrasi=".$this->input->post('no_registrasi')." and status_selesai < 3 and jenis_tindakan=1 and kode_bagian like '3%' and day(tgl_transaksi) = ".date('d')." and month(tgl_transaksi) = ".date('m')." and year(tgl_transaksi) = ".date('Y')."")->row();

                $tarif_lama = $trans_p['bill_rs'];
                $kode_trans_p = $trans_p['kode_trans_pelayanan'];
                if($tarif_lama < $tarif){
                    $data_tc_trans_pelayanan["nama_tindakan"] = "Ruangan ".$nama_bagian->nama_bagian." (Pindahan)";
                    $data_tc_trans_pelayanan["bill_rs"] = $tarif;
                    $data_tc_trans_pelayanan["kode_bagian"] = $this->form_validation->set_value('ri_ruangan');
                    // echo "asdfasdf";
                }else{
                    $data_tc_trans_pelayanan["kode_bagian"] = $this->form_validation->set_value('ri_ruangan');
                }
                
                $data_tc_trans_pelayanan["kode_klas"] = $this->form_validation->set_value('ri_klas_ruangan');
                $data_tc_trans_pelayanan["no_kamar"] =  $data_ruangan_tuju->no_kamar;
                $data_tc_trans_pelayanan["no_bed"] =  $data_ruangan_tuju->no_bed;
                $data_tc_trans_pelayanan["kode_bagian_asal"] = $this->input->post('kode_bagian');

                $this->Pindah_ruangan->update('tc_trans_pelayanan',  $data_tc_trans_pelayanan, array('kode_trans_pelayanan' => $kode_trans_p));

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
                }
                else
                {
                    $this->db->trans_commit();
                    echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
                }

            }
        
        }

    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
