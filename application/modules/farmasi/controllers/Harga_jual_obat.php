<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Harga_jual_obat extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Harga_jual_obat');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Harga_jual_obat_model', 'Harga_jual_obat');
        $this->load->model('Lap_penjualan_obat_model', 'Lap_penjualan_obat');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ( $_GET['flag'] == 'medis' ) ? 'Barang Medis' : 'Barang Non Medis';
        $this->kode_gudang_nm = '070101';
        $this->kode_gudang = '060201';

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag_string' => $_GET['flag']
        );
        /*load view index*/
        $this->load->view('Harga_jual_obat/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Harga_jual_obat/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Harga_jual_obat->get_by_id($id); 
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Harga_jual_obat/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        // echo '<pre>'; print_r($data['value']);
        $data['flag_string'] = $_GET['flag'];
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Harga_jual_obat/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Harga_jual_obat/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Harga_jual_obat->get_by_id($id);
        $data['history_po'] = $this->Lap_penjualan_obat->get_history_po($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['flag_string'] = $_GET['flag'];
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Harga_jual_obat/form_show', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Harga_jual_obat->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            if( $_GET['flag'] == 'medis' ){
                $txt_keterangan = ($row_list->nama_generik)?$row_list->nama_generik.' <br>':'';
                $txt_keterangan .= ($row_list->nama_layanan)?$row_list->nama_layanan.' <br>':'';
                $txt_keterangan .= ($row_list->nama_pabrik)?$row_list->nama_pabrik.' <br>':'';
                $txt_keterangan .= ($row_list->nama_jenis)?$row_list->nama_jenis.' <br>':'';
                $txt_keterangan .= ($row_list->jenis_barang)?$row_list->jenis_barang:'';
                $txt_gol = $row_list->nama_golongan.'<br>('.strtolower($row_list->nama_sub_golongan).')';
            }
            if( $_GET['flag'] == 'non_medis' ){
                $txt_keterangan = ($row_list->nama_pabrik)?$row_list->nama_pabrik.' <br>':'';
                $txt_gol = $row_list->nama_golongan.'<br>('.strtolower($row_list->nama_sub_golongan).')';
            }
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->kode_brg.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->kode_brg;
            $row[] = '<div class="center">
                        <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li>'.$this->authuser->show_button('farmasi/Harga_jual_obat?flag='.$_GET['flag'].'','R',$row_list->kode_brg,67).'</li>
                            <li>'.$this->authuser->show_button('farmasi/Harga_jual_obat?flag='.$_GET['flag'].'','U',$row_list->kode_brg,67).'</li>
                            <li>'.$this->authuser->show_button('farmasi/Harga_jual_obat?flag='.$_GET['flag'].'','D',$row_list->kode_brg,6).'</li>
                        </ul>
                        </div>
                    </div>';
            
            $link_image = ( $row_list->path_image != NULL ) ? PATH_IMG_MST_BRG.$row_list->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ;
            $row[] = '<div class="center"><a href="'.base_url().$link_image.'" target="_blank"><img src="'.base_url().$link_image.'" width="100px"></a></div>';
            $row[] = 'Kategori : '.ucfirst($row_list->nama_kategori).'<br><b>'.$row_list->kode_brg.'</b><br>'.$row_list->nama_brg;
            $row[] = ucfirst($txt_gol).'<br><span style="color: green">Rak : '.$row_list->rak.'</span>';
            $row[] = '<div class="center">'.strtoupper($row_list->satuan_besar).'/'.strtoupper($row_list->satuan_kecil).'</div>';
            $row[] = '<div class="center">'.$row_list->content.'</div>';
            $row[] = '<div align="right">'.number_format($row_list->harga_beli).'</div>';
            // harga jual obat flat kurang lebih
            $harga_jual = $row_list->harga_beli + ($row_list->harga_beli * (33.3/100));
            $row[] = '<div align="right">'.number_format($harga_jual).'</div>';
            
            // $row[] = '<div>'.$row_list->spesifikasi.'<br>'.$this->logs->show_logs_record_datatable($row_list).'</div>';
            $status_aktif = ($row_list->is_active == 1) ? '<span class="label label-sm label-success">Active</span>' : '<span class="label label-sm label-danger">Not active</span>';
            $row[] = '<div class="center">'.$status_aktif.'</div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Harga_jual_obat->count_all(),
                        "recordsFiltered" => $this->Harga_jual_obat->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function show_detail( $id )
    {   
        $table = ($_GET['flag'] == 'non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
        $fields = $this->master->list_fields( $table );
        // print_r($fields);die;
        $data = $this->Harga_jual_obat->get_by_id($id);
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function export_excel()
    {
        /*get data from model*/
        $list = $this->Harga_jual_obat->get_all_data();
        // echo "<pre>"; print_r($list); die;
        $data = array("data" => $list);
        //output to json format
        $this->load->view('farmasi/Harga_jual_obat/index_excel', $data);
    }



}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
