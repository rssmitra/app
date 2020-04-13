<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Input_perjanjian_pm extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Input_perjanjian_pm');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Input_perjanjian_pm_model', 'Input_perjanjian_pm');
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
        $this->load->view('Input_perjanjian_pm/index', $data);
    }

    public function form($id='')    
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'registration/Input_perjanjian_pm/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Input_perjanjian_pm->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'registration/Input_perjanjian_pm/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }

        $data['no_mr'] = isset($_GET['no_mr']) ? $_GET['no_mr'] : null;

        /*breadcrumbs for view*/
        
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['title'] = $this->title;
        // referensi no kunjungan
        $data['no_kunjungan'] = isset($_GET['no_kunjungan'])?$_GET['no_kunjungan']: null;
        // echo '<pre>'; print_r($data);die;
        /*load form view*/
        
        $this->load->view('Input_perjanjian_pm/form', $data);
    
    }

    public function konfirmasi_kunjungan()    
    {
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'registration/Input_perjanjian_pm/'.strtolower(get_class($this)).'/konfirmasi_kunjungan');
        /*define data variabel*/
        
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['title'] = $this->title;
        $data['ids'] = implode(',', $_GET['ID']);
        $data['value'] = $this->Input_perjanjian_pm->get_by_id($_GET['ID']);
        /*load form view*/
        // echo '<pre>'; print_r($data);die;

        $this->load->view('Input_perjanjian_pm/form_konfirmasi', $data);
    
    }

    public function get_data()
    {
        /*get data from model*/
        if(isset($_GET['search']) AND $_GET['search']==TRUE ){
            $this->find_data();
        }else{
            $list = $this->Input_perjanjian_pm->get_datatables(); 
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center">
                            <label class="pos-rel">
                                <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_pesanan.'"/>
                                <span class="lbl"></span>
                            </label>
                          </div>';
                $row[] = '<div class="center"><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-inverse">
                                <li>'.$this->authuser->show_button('registration/Input_perjanjian_pm','U',$row_list->id_tc_pesanan,6).'</li>
                                <li>'.$this->authuser->show_button('registration/Input_perjanjian_pm','D',$row_list->id_tc_pesanan,6).'</li>
                                <li><a href="#" onclick="cetak_surat_kontrol('.$row_list->id_tc_pesanan.')">Cetak Surat Kontrol</a></li>
                            </ul>
                        </div></div>';
                if( !isset($_GET['no_mr']) ){
                    $no_mr = ($row_list->no_mr == NULL)?'<i class="fa fa-user green bigger-150"></i> - ':$row_list->no_mr.' - ';
                    $row[] = $no_mr.'<a href="#">'.strtoupper($row_list->nama).'</>';
                }
                $row[] = ($row_list->nama_perusahaan==NULL)?'<div class="left">PRIBADI/UMUM</div>':'<div class="left">'.$row_list->nama_perusahaan.'</div>';
                // $row[] = '<div class="left">'.$row_list->nama_bagian.'</div>';
                $row[] = '<div class="left">'.$row_list->nama_pegawai.'</div>';
                $row[] = '<div class="left">'.$row_list->nama_tarif.'</div>';
                $row[] = $this->tanggal->getBulan($row_list->bulan_kunjungan);
                $row[] = $row_list->no_telp."/".$row_list->no_hp;
                $row[] = $row_list->keterangan;
                $row[] = ($row_list->status_konfirmasi_kedatangan == NULL) ? '<div class="center"><i class="fa fa-times-circle bigger-120 red"></i></div>' : '<div class="center"><span class="label label-sm label-success"><i class="fa fa-check"></i></span></div>';


                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->Input_perjanjian_pm->count_all(),
                            "recordsFiltered" => $this->Input_perjanjian_pm->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }
        
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n", "status" => 200 );
        echo json_encode($output);
    }

    public function export_excel(){
        $list = $this->Input_perjanjian_pm->get_data(); 
        $data = array(
			'title' 	=> 'Perjanjian Rawat Jalan',
            'fields' 	=> $list->field_data(),
            // 'fields' 	=> array('no_mr','nama', 'nama_bagian','nama_pegawai','nama_perusahaan','tgl_pesanan','no_hp_pasien','no_hp','no_telp','telp_almt_ttp','unique_code_counter','kode_perjanjian'),
			'data' 		=> $list->result(),
		);
        // echo '<pre>';print_r($data);die;
        $this->load->view('Input_perjanjian_pm/excel_view', $data);

    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Input_perjanjian_pm->delete_by_id($toArray)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
