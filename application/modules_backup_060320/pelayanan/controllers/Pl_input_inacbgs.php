<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_input_inacbgs extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'pelayanan/Pl_input_inacbgs');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pl_pelayanan_ri_model', 'Pl_input_inacbgs');
        /*load library*/
        $this->load->library('Form_validation');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        /*define variable data*/
        $is_icu = (isset($_GET['is_icu']))?$_GET['is_icu']:'N';
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'is_icu' => $is_icu,
        );

        $this->load->view('Pl_input_inacbgs/index', $data);
    }
    
    public function get_data()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_input_inacbgs->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $link = 'billing/Billing';
            $str_type = 'RI';
            $cek_trans = $this->Pl_input_inacbgs->cek_trans_pelayanan($row_list->no_registrasi);

            $rollback_btn = ($cek_trans>0 AND $row_list->status_pulang!= 0 || NULL)?'<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>':'';

            /*color of type Ruangan RI*/
            /*LB*/
            if ( in_array($row_list->bag_pas, array('030101','031401','031301','030801','030401','031601') ) ) {
                $color = 'red';
            /*LA*/
            }elseif( in_array($row_list->bag_pas,  array('030701','030301','030601','030201') )){
                $color = 'green';
            /*VK Ruang Bersalin*/
            }elseif( in_array($row_list->bag_pas,  array('031201','031701','030501') )){
                $color = 'blue';
            }else{
                $color = 'black';
            }
            if($cek_trans==0){
                $status_pulang = '<label class="label label-primary"><i class="fa fa-money"></i> Lunas </label>';
            }else{
                $status_pulang = ($row_list->status_pulang== 0 || NULL)?($row_list->pasien_titipan== 1)?'<label class="label label-yellow">Pasien Titipan</label>':'<label class="label label-danger">Masih dirawat</label>':'<label class="label label-success">Pulang</label>';
            }

            $row[] = $row_list->no_registrasi;
            $row[] = $str_type;
            $row[] = '';
            // $row[] = '<div class="center"><div class="btn-group">
            //             <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
            //                 <span class="ace-icon fa fa-caret-down icon-on-right"></span>
            //             </button>
            //             <ul class="dropdown-menu dropdown-inverse">
            //                 '.$rollback_btn.' 
            //                 <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
            //             </ul>
            //         </div></div>';
            // $row[] = '<div class="center"><a href="#" onclick="getMenu('."'pelayanan/Pl_input_inacbgs/form/".$row_list->kode_ri."/".$row_list->no_kunjungan."'".')">'.$row_list->no_kunjungan.'</a></div>';
            // $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = '<a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">'.$row_list->no_mr.'<br><span style="color:'.$color.'"><b>'.strtoupper($row_list->nama_pasien).'</b></span><br><small>'.$this->tanggal->formatDateTime($row_list->tgl_masuk).'</small>'.'</a>';
            $row[] = $row_list->nama_bagian.'<br>'.$row_list->klas;
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            // $row[] = $row_list->klas;
            $row[] = ($row_list->klas_titip)?$row_list->klas_titip:'-';
            // $row[] = number_format($row_list->tarif_inacbgs);
            // $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            $row[] = $row_list->nama_pegawai;
            $row[] = '<div class="center">'.$status_pulang.'</div>';

            $row[] = '<div class="center"><input type="text" name="" style="width: 150px; id="inacbgs_'.$row_list->no_registrasi.'" onchange="udpateRow('.$row_list->no_registrasi.')" value="'.$row_list->ina_cbgs.'"></div>';
            $row[] = '<div class="center"><input type="text" name="" id="tarif_inacbgs_'.$row_list->no_registrasi.'" style="width: 100px; font-weight: bold; text-align: right" value="'.number_format($row_list->tarif_inacbgs).'" onchange="udpateRow('.$row_list->no_registrasi.')"></div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_input_inacbgs->count_all(),
                        "recordsFiltered" => $this->Pl_input_inacbgs->count_filtered(),
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

    public function process(){

        // form validation
        $this->form_validation->set_rules('no_registrasi', 'No Registrasi', 'trim|required');
        $this->form_validation->set_rules('ina_cbgs', 'Inacbgs', 'trim');
        $this->form_validation->set_rules('tarif_inacbgs', 'Tarif Inacbgs', 'trim');
               

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

            $dataexc = array(
                'ina_cbgs' => $this->regex->_genRegex($this->input->post('ina_cbgs'),'RGXQSL'), 
                'tarif_inacbgs' => $this->regex->_genRegex($this->input->post('tarif_inacbgs'),'RGXINT'),
            );
            $this->db->update('tc_registrasi', $dataexc, array('no_registrasi' => $_POST['no_registrasi']) );
                        
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Diagnosa'));
            }
        
        }

    }



}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
