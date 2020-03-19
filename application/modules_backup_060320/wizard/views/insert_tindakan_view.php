<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Insert_data extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        $this->load->model('Insert_data_model');
    }

    public function insert_tindakan(){
        $this->load->view('insert_tindakan_view');
    }


}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
