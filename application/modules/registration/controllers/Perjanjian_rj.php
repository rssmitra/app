<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Perjanjian_rj extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Perjanjian_rj');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Perjanjian_rj_model', 'Perjanjian_rj');
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
        $this->load->view('Perjanjian_rj/index', $data);
    }

    public function get_by_mr($no_mr) { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_mr' => $no_mr
        );
        /*load view index*/
        $this->load->view('Perjanjian_rj/index_by_mr', $data);
    }
    
    public function get_data()
    {
        /*get data from model*/
        if(isset($_GET['search']) AND $_GET['search']==TRUE ){
            $this->find_data();
        }else{
            $list = $this->Perjanjian_rj->get_datatables(); 
            // echo '<pre />'; print_r($list); die;
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $html = '';

                if( isset($_GET['no_mr']) AND $_GET['no_mr'] != '' ){
                    // $html .= '<li><a href="#" onclick="changeModulRjFromPerjanjian('.$row_list->id_tc_pesanan.','.$row_list->kode_dokter.','."'".$row_list->no_poli."'".','."'".$row_list->kode_perjanjian."'".')">Daftarkan Pasien</a></li>';
                }else{
                    $html .= '<li><a href="#" onclick="getMenu('."'registration/Reg_klinik?idp=".$row_list->id_tc_pesanan."&kode_dokter=".$row_list->kode_dokter."&poli=".$row_list->no_poli."&kode_perjanjian=".$row_list->kode_perjanjian."&no_mr=".$row_list->no_mr."'".')">Daftarkan Pasien</a></li>';
                }
                
                $row[] = '<div class="center">
                            <label class="pos-rel">
                                <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_pesanan.'"/>
                                <span class="lbl"></span>
                            </label>
                          </div>';
                if(isset($_GET['no_mr'])){
                    $print_surat_kontrol = ($row_list->jd_id != '') ? '<li><a href="#" onclick="getMenuTabs('."'registration/Reg_pasien/surat_control?id_tc_pesanan=".$row_list->id_tc_pesanan."&jd_id=".$row_list->jd_id."'".', '."'tabs_form_pelayanan'".')">Cetak Surat Kontrol</a></li>' : '';
                }else{
                    $print_surat_kontrol = ($row_list->jd_id != '') ? '<li><a href="#" onclick="cetak_surat_kontrol('.$row_list->id_tc_pesanan.', '.$row_list->jd_id.')">Cetak Surat Kontrol</a></li> <li><a href="#" onclick="cetak_surat_kontrol_popup('.$row_list->id_tc_pesanan.', '.$row_list->jd_id.')">Cetak Surat Kontrol [testing]</a></li>' : '';
                }

                $is_bridging = ($row_list->is_bridging == 1) ? '<span style="background: green; padding: 2px; color: white"><i class="fa fa-check"></i> Bridging</span>' : '' ;

                $row[] = '<div class="center"><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-inverse">
                                '.$html.'
                                '.$print_surat_kontrol.'
                                <li><a href="#" onclick="delete_perjanjian('.$row_list->id_tc_pesanan.')" >Hapus Perjanjian</a></li>
                            </ul>
                        </div></div>';
                if( !isset($_GET['no_mr']) ){
                    $no_mr = ($row_list->no_mr == NULL)?'<i class="fa fa-user green bigger-150"></i>':$row_list->no_mr;
                    // $row[] = '<div class="center">'.$no_mr.'</div>';
                    $row[] = '<b>'.$no_mr.'</b><br>'.strtoupper($row_list->nama).'<br>'.$is_bridging;
                }
                // $row[] = ($row_list->nama_perusahaan==NULL)?'<div class="left">PRIBADI/UMUM</div>':'<div class="left">'.$row_list->nama_perusahaan.'</div>';
                // $row[] = '<div class="left">'.ucwords($row_list->nama_bagian).'</div>';
                
                $row[] = '<div class="left"><b>'.$row_list->nama_pegawai.'</b><br><small>'.ucwords($row_list->nama_bagian).'</small></div>';
                if( isset($_GET['flag']) AND $_GET['flag']=='HD' ){
                    $row[] = $row_list->selected_day;
                }else{
                    $row[] = 'Kode Booking : <span style="font-weight: bold; color: BLUE;">'.$row_list->unique_code_counter."</span><br />".$this->tanggal->formatDate($row_list->tgl_pesanan);
                }
                $row[] = $row_list->tlp_almt_ttp."<br>".$row_list->no_telp."<br>".$row_list->no_hp_pasien;
                if( !isset($_GET['no_mr']) ){
                    $row[] = $row_list->no_sep;
                }
                $row[] = $row_list->no_kartu_bpjs;
                $row[] = '<div class="center"><input type="text" class="form-control" style="border: 1px solid white !important" name="kode_perjanjian" value="'.$row_list->kode_perjanjian.'" id="surat_kontrol_'.$row_list->id_tc_pesanan.'" onchange="saveRow('.$row_list->id_tc_pesanan.')"></div>';
                $row[] = $this->tanggal->formatDateTime($row_list->input_tgl).'<br>'.$row_list->petugas.'<br>'.$row_list->keterangan;
                $row[] = ($row_list->tgl_masuk == NULL) ? '<div class="center"><span class="label label-sm label-danger"><i class="fa fa-times-circle"></i></span></div>' : '<div class="center"><span class="label label-sm label-success"><i class="fa fa-check"></i></span></div>';


                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->Perjanjian_rj->count_all(),
                            "recordsFiltered" => $this->Perjanjian_rj->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }
        
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function export_excel(){
        $list = $this->Perjanjian_rj->get_data(); 
        $data = array(
			'title' 	=> 'Perjanjian Rawat Jalan',
            'fields' 	=> $list->field_data(),
            // 'fields' 	=> array('no_mr','nama', 'nama_bagian','nama_pegawai','nama_perusahaan','tgl_pesanan','no_hp_pasien','no_hp','no_telp','telp_almt_ttp','unique_code_counter','kode_perjanjian'),
			'data' 		=> $list->result(),
		);
        // echo '<pre>';print_r($data);die;
        $this->load->view('Perjanjian_rj/excel_view', $data);

    }

    public function saveNoSuratKontrol(){
        $this->db->where('id_tc_pesanan', $_POST['ID'])->update('tc_pesanan', array('kode_perjanjian' => $_POST['no_surat_kontrol']));
        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
    }

}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
