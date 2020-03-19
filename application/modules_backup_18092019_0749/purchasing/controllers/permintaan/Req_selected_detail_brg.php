<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Req_selected_detail_brg extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/permintaan/Req_selected_detail_brg');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/permintaan/Req_selected_detail_brg_model', 'Req_selected_detail_brg');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Req_selected_detail_brg->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<img src="'.base_url().'assets/images/no_pict.jpg" width="100px">';
            $row[] = '<div style="font-size:14px"><b>'.$row_list->kode_brg.'</b><br>'.$row_list->nama_brg.'</div>';
            $row[] = '<div align="right">'.number_format((int)$row_list->harga_beli_terakhir).'</div>';
            /*coloring style*/
            $color = ($row_list->jml_sat_kcl <= $row_list->stok_minimum)?'red':($row_list->jml_sat_kcl > $row_list->stok_minimum) ? 'green' : 'blue' ;

            $row[] = '<div class="center" style="color:'.$color.'; font-weight: bold">'.$row_list->jml_sat_kcl.'</div>';
            $row[] = '<div class="center">'.$row_list->satuan_kecil.'</div>';
            $row[] = '<div class="center">'.$row_list->content.'</div>';
            $row[] = '<div class="center">'.$row_list->satuan_besar.'</div>';
            $row[] = '<div class="center"><input type="number" style="width:70px; height:40px !important; text-align:center;font-size:14px;font-weight:bold"></div>';
            $row[] = '<a href="#" class="btn btn-sm btn-yellow"> <i class="fa fa-plus"></i> </a>';
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Req_selected_detail_brg->count_all(),
                        "recordsFiltered" => $this->Req_selected_detail_brg->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
