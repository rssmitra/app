<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Entry_resep_rj extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Entry_resep_rj');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Entry_resep_rj_model', 'Entry_resep_rj');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() 
    { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Entry_resep_rj/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'Entry_resep_rj/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['kode_pesan_resep'] = $id;
        $data['value'] = $this->Entry_resep_rj->get_by_id($id);
        //echo '<pre>';print_r($data);die;
        /*no mr default*/
        $data['no_mr'] = $_GET['mr'];
        /*initialize flag for form*/
        $data['flag'] = "update";
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Entry_resep_rj/form', $data);
    }

    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Entry_resep_rj/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Entry_resep_rj->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Entry_resep_rj/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Entry_resep_rj->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                            <label class="pos-rel">
                                <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->kode_pesan_resep.'"/>
                                <span class="lbl"></span>
                            </label>
                          </div>';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#">Edit</a></li>
                            <li><a href="#" onclick="cetak_surat_kontrol('.$row_list->kode_pesan_resep.')">Hapus</a></li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/Entry_resep_rj/form/".$row_list->kode_pesan_resep."?mr=".$row_list->no_mr."'".')">'.$row_list->kode_pesan_resep.'</a></div>';
            $row[] = $row_list->tgl_pesan;
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->nama_pegawai;
            $row[] = $row_list->nama_bagian;
            $row[] = $row_list->jumlah_r;
            $row[] = $row_list->nama_lokasi;
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Entry_resep_rj->count_all(),
                        "recordsFiltered" => $this->Entry_resep_rj->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_temp_pesanan_obat($kode_pesan_resep)
    {
        /*get data from model*/
        $list = $this->Entry_resep_rj->get_data_temp_pesanan_obat($kode_pesan_resep);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center"><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-inverse">
                                <li><a href="#">Edit</a></li>
                                <li><a href="#">Hapus</a></li>
                            </ul>
                        </div></div>';
            $row[] = $row_list->kode_brg;
            $row[] = $row_list->nama_brg;
            $row[] = $row_list->satuan_kecil;
            $row[] = '<div class="center">'.$row_list->jumlah_pesan.'</div>';
            $row[] = '<div class="center">'.$row_list->jumlah_tebus.'</div>';
            $row[] = '<div align="right">'.number_format($row_list->harga_jual).'</div>';
            $row[] = '<div align="right">'.number_format($row_list->biaya_tebus).'</div>';
            $row[] = '<div align="right">'.number_format($row_list->harga_r).'</div>';
            $total = $row_list->biaya_tebus + $row_list->harga_r;
            $row[] = '<div align="right">'.number_format($total).'</div>';
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function process()
    {

        print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('tgl_registrasi', 'Tanggal Registrasi', 'trim|required');

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

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['noMrHidden'], 'no_registrasi' => $dataexc['no_registrasi'], 'is_new' => $this->input->post('is_new') ));
            }
        
        }

    }


    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
