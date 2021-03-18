<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Keu_trans_umum extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'keuangan/Keu_trans_umum');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('keuangan/Keu_trans_umum_model', 'Keu_trans_umum');
        $this->load->model('billing/Billing_model', 'Billing');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('Keu_trans_umum/index', $data);
    }

    public function form($id='')
    {

        $qry_url = http_build_query($_GET);
        /*if id is not null then will show form edit*/
            /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Buat Transaksi'.strtolower($this->title).'', 'Keu_trans_umum/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id.'?'.$qry_url);
        if($id == ''){
            /*initialize flag for form*/
            $data['flag'] = "create";
        }else{
            /*initialize flag for form*/
            $data['flag'] = "update";
            /*get value by id*/
            $data['value'] = $this->Keu_trans_umum->get_by_id($id); 
        }
        
        /*title header*/
        $data['qry_url'] = $qry_url;
        $data['title'] = $this->title;
        // echo '<pre>'; print_r($data);die;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Keu_trans_umum/form', $data);
    }


    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_detail( $id )
    {
        $fields = $this->db->list_fields( 'bd_tc_trans' );
        $data = $this->Keu_trans_umum->get_by_id( $id );
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Keu_trans_umum->get_datatables();
        $qry_url = ($_GET) ? http_build_query($_GET) : '' ;
        // print_r($list);die;
        $data = array();
        $arr_pengeluaran = array();
        $arr_pemasukan = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            if ($row_list->jenis_transaksi == 'out') {
                $arr_pengeluaran[] = $row_list->jumlah;
            }

            if ($row_list->jenis_transaksi == 'in') {
                $arr_pemasukan[] = $row_list->jumlah;
            }
            $arr_total[] = $row_list->jumlah;
            $row[] = '<div class="center"></div>';
            $row[] = $row_list->id_bd_tc_trans;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->no_bukti;
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($row_list->tgl_transaksi).'</div>';
            $row[] = $row_list->uraian;
            $row[] = ($row_list->jenis_transaksi == 'out') ? '<span class="red">Pengeluaran</span>' : '<span class="green">Pemasukan</span>';
            $row[] = '<div class="pull-right">'.number_format($row_list->jumlah).'</div>';
            $row[] = ($row_list->metode_pembayaran == 'kas') ? 'Tunai' : $row_list->nama_bank;
            $row[] = $row_list->penerima;
            $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'keuangan/Keu_trans_umum/preview_kuitansi/".$row_list->id_bd_tc_trans."'".','."'Preview Kuitansi'".',900,650);"><i class="fa fa-print blue bigger-150"></i></a></div>';
            $row[] = '<div class="center">
                        '.$this->authuser->show_button('keuangan/Keu_trans_umum','R',$row_list->id_bd_tc_trans,2).'
                        '.$this->authuser->show_button('keuangan/Keu_trans_umum','U',$row_list->id_bd_tc_trans,2).'
                        '.$this->authuser->show_button('keuangan/Keu_trans_umum','D',$row_list->id_bd_tc_trans,2).'
                      </div>'; 
            $data[] = $row;
              
        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Keu_trans_umum->count_all(),
                        "recordsFiltered" => $this->Keu_trans_umum->count_filtered(),
                        "data" => $data,
                        "total_pengeluaran" => array_sum($arr_pengeluaran),
                        "total_pemasukan" => array_sum($arr_pemasukan),
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('tgl_transaksi', 'Tgl Transaksi', 'trim|required');
        $val->set_rules('no_bukti', 'No Kuitansi', 'trim|required');
        $val->set_rules('jenis_transaksi', 'Jenis Transaksi', 'trim|required');
        $val->set_rules('metode_pembayaran', 'Metode Pembayaran', 'trim|required');
        $val->set_rules('kode_bank', 'bank', 'trim');
        $val->set_rules('kode_bagian', 'Unit/Bagian', 'trim');
        $val->set_rules('uraian', 'Uraian transaksi', 'trim|required');
        $val->set_rules('jumlah', 'Jumlah', 'trim|required');
        $val->set_rules('penerima', 'Penerima', 'trim|required');
        $val->set_rules('catatan', 'Catatan', 'trim');
        
        
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;

            // data header po
            $dataexc = array(
                'tgl_transaksi' => $val->set_value('tgl_transaksi'),
                'no_bukti' => $val->set_value('no_bukti'),
                'jenis_transaksi' => $val->set_value('jenis_transaksi'),
                'metode_pembayaran' => $val->set_value('metode_pembayaran'),
                'kode_bank' => $val->set_value('kode_bank'),
                'kode_bagian' => $val->set_value('kode_bagian'),
                'uraian' => $val->set_value('uraian'),
                'jumlah' => $val->set_value('jumlah'),
                'penerima' => $val->set_value('penerima'),
                'catatan' => $val->set_value('catatan'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $newId = $this->Keu_trans_umum->save($dataexc);
                /*save logs*/
                $this->logs->save('bd_tc_trans', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_bd_tc_trans');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*print_r($dataexc);die;*/
                /*update record*/
                $this->Keu_trans_umum->update(array('id_bd_tc_trans' => $id), $dataexc);
                $newId = $id;
                $this->logs->save('bd_tc_trans', $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_bd_tc_trans');
            }
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'id' => $newId));
            }
        }
    }


    public function preview_kuitansi($id){
 
        $data = $this->Keu_trans_umum->get_by_id( $id );
        $data = array(
            'value' => $data,
        );
        $this->load->view('Keu_trans_umum/preview_kuitansi', $data, false);
         
    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->Keu_trans_umum->delete_by_id($id)){
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
/* Location: ./application/modules/example/controllers/example.php */
