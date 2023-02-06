<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class App_setup_ttd extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/persetujuan_pemb/App_setup_ttd');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('persetujuan_pemb/App_setup_ttd/index', $data);
    }


    public function process()
    {
        // echo '<pre>'; print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        foreach( $_POST['value'] as $key=>$row ){
            $val->set_rules('value['.$key.']', 'Nama '.$key.'', 'trim|required');
            $val->set_rules('label['.$key.']', 'Text Jabatan ('.$key.')', 'trim|required');
            $val->set_rules('reff_id['.$key.']', 'Pilih user ID ('.$key.')', 'trim|required');
        }
                
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            
            foreach( $_POST['value'] as $key=>$row ){
                $dataexc = array(
                    'value' => $this->regex->_genRegex($val->set_value('value['.$key.']'),'RGXQSL'),
                    'label' => $this->regex->_genRegex($val->set_value('label['.$key.']'),'RGXQSL'),
                    'reff_id' => $this->regex->_genRegex($val->set_value('reff_id['.$key.']'),'RGXQSL'),
                    'flag' => $this->regex->_genRegex($key,'RGXQSL'),
                );
                $this->db->update('global_parameter', $dataexc, array('auto_id' => $_POST['auto_id'][$key]) );
            }
            
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


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
