<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_pembayaran_pengajuan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/pembayaran_hutang/Adm_pembayaran_pengajuan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/pembayaran_hutang/Adm_pembayaran_pengajuan_model', 'Adm_pembayaran_pengajuan');
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
        $this->load->view('pembayaran_hutang/Adm_pembayaran_pengajuan/index', $data);
    }

    public function form($id='')
    {

        $qry_url = http_build_query($_GET);
        /*if id is not null then will show form edit*/
            /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Pembayaran Hutang Supplier '.strtolower($this->title).'', 'Adm_pembayaran_pengajuan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id.'?'.$qry_url);
        /*get value by id*/
        $data['value'] = $this->Adm_pembayaran_pengajuan->get_by_id($id); 
        $data['detail_faktur'] = $this->Adm_pembayaran_pengajuan->get_log_data($id);
        /*initialize flag for form*/
        $data['flag'] = "update";
    
        /*title header*/
        $data['qry_url'] = $qry_url;
        $data['title'] = $this->title;
        // echo '<pre>'; print_r($data);die;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('pembayaran_hutang/Adm_pembayaran_pengajuan/form', $data);
    }


    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Adm_pembayaran_pengajuan->get_datatables();
        $qry_url = ($_GET) ? http_build_query($_GET) : '' ;
        // print_r($list);die;
        $data = array();
        $arr_total = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $arr_total[] = $row_list->total_harga;
            $row[] = '<div class="center"></div>';
            $row[] = $row_list->id_tc_hutang_supplier_inv;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->no_terima_faktur;
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($row_list->tgl_faktur).'</div>';
            // get label keterlambatan
            $txt_color = $this->tanggal->date_range( $row_list->tgl_rencana_bayar, date('Y-m-d'));
            $icon = ($txt_color['count'] > 1) ? '('.$txt_color['count'].')' : '';
            // print_r($txt_color);die;
            $row[] = '<div class="center '.$txt_color['color'].'"><b>'.$this->tanggal->formatDateDmy($row_list->tgl_rencana_bayar).' '.$icon.'</b></div>';
            $row[] = $row_list->namasupplier;
            $row[] = '<div class="pull-right">'.number_format($row_list->total_harga).'</div>';
            $row[] = ($row_list->flag_bayar == 1) ? '<div class="center green"><b>Lunas</b></div>' : '<div class="center orange"><b>Dalam proses pengajuan</b></div>';
            $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'purchasing/tukar_faktur/Tf_riwayat_tukar_faktur/preview_ttf?ID=".$row_list->id_tc_hutang_supplier_inv."'".','."'BUKTI TANDA TERIMA FAKTUR'".',900,650);"><i class="fa fa-print green bigger-150"></i></a></div>';
            $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'adm_pasien/pembayaran_hutang/Adm_pembayaran_pengajuan/preview_kuitansi?nm=".$row_list->namasupplier."&inv=".$row_list->no_terima_faktur."&tgl=".$row_list->tgl_faktur."&jml=".$row_list->total_harga."'".','."'Preview Kuitansi'".',900,650);"><i class="fa fa-print blue bigger-150"></i></a></div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'adm_pasien/pembayaran_hutang/Adm_pembayaran_pengajuan/form/".$row_list->id_tc_hutang_supplier_inv."'".');" class="label label-xs label-primary">Pembayaran</a></div>';
            $data[] = $row;
              
        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Adm_pembayaran_pengajuan->count_all(),
                        "recordsFiltered" => $this->Adm_pembayaran_pengajuan->count_filtered(),
                        "data" => $data,
                        "total_billing" => array_sum($arr_total),
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // print_r($_POST);die;
        
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('kodesupplier', 'Kode Supplier', 'trim|required');
        $val->set_rules('tgl_faktur', 'Tanggal Faktur', 'trim|required');
        $val->set_rules('tgl_rencana_bayar', 'Tanggal Jth Tempo', 'trim|required');
        $val->set_rules('no_ttf', 'Nomor Tanda Terima Faktur', 'trim|required');
        $val->set_rules('no_seri_pajak', 'Disetujui Oleh', 'trim|required');
        $val->set_rules('diskon', 'KARS', 'trim|required');
        $val->set_rules('ppn', 'SIK AA', 'trim|required');
        $val->set_rules('biaya_materai', 'Biaya Materai', 'trim|required');
        $val->set_rules('keterangan', 'Keterangan', 'trim');
        
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
                'total_harga' => $this->regex->_genRegex($_POST['total_harga'],'RGXINT'),
                'total_sbl_ppn' => $this->regex->_genRegex($_POST['total_sbl_ppn'],'RGXINT'),
                'total_ppn' => $this->regex->_genRegex($_POST['total_ppn'],'RGXINT'),
                'diskon' => $this->regex->_genRegex($_POST['total_diskon'],'RGXINT'),
                'no_seri_pajak' => $this->regex->_genRegex($_POST['no_seri_pajak'],'RGXQSL'),
                'biaya_materai' => $this->regex->_genRegex($_POST['total_biaya_materai'],'RGXINT'),
                'kodesupplier' => $this->regex->_genRegex($_POST['kodesupplier'],'RGXINT'),
                'tgl_faktur' => $this->regex->_genRegex($_POST['tgl_faktur'],'RGXQSL'),
                'no_terima_faktur' => $this->regex->_genRegex($_POST['no_ttf'],'RGXQSL'),
                'tgl_rencana_bayar' => $this->regex->_genRegex($_POST['tgl_rencana_bayar'],'RGXQSL'),
                'flag' => $this->regex->_genRegex($_POST['flag'],'RGXQSL'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $newId = $this->Tf_tukar_faktur->save('tc_hutang_supplier_inv', $dataexc);
                /*save logs*/
                $this->logs->save('tc_hutang_supplier_inv', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_hutang_supplier_inv');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*print_r($dataexc);die;*/
                /*update record*/
                $this->Tf_tukar_faktur->update('tc_hutang_supplier_inv', array('id_tc_hutang_supplier_inv' => $id), $dataexc);
                $newId = $id;
                $this->logs->save('tc_hutang_supplier_inv', $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_tc_hutang_supplier_inv');
            }
            
            foreach( $_POST['id_penerimaan'] as $key=>$row_checked ){

                $insertBatch[] = array(
                    "id_tc_hutang_supplier_inv" => $newId,
                    "id_penerimaan" => $row_checked,
                    "total_hutang" => $_POST['subtotal'][$key],
                    "kode_penerimaan" => $_POST['kode_penerimaan'][$key],
                    "no_faktur" => $_POST['no_faktur'][$key],
                );
                $arr_id[] = $row_checked;
            }
            // insert batch
            $this->db->insert_batch('tc_hutang_supplier_inv_det', $insertBatch);
            // update status tukar faktur
            $tc_penerimaan_brg = ($_POST['flag'] == 'medis') ? 'tc_penerimaan_barang' : 'tc_penerimaan_barang_nm' ;
            $this->db->where_in('id_penerimaan', $arr_id)->update($tc_penerimaan_brg, array('status_tukar_faktur' => 1) );

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $_POST['flag'], 'id' => $newId));
            }
        }
    }

    public function get_log_data($id_tc_hutang_supplier_inv)
    {
        /*get data from model*/
        $list = $this->Adm_pembayaran_pengajuan->get_log_data($id_tc_hutang_supplier_inv);
        // echo '<pre>';print_r($list);die;
        $data = array(
            'id_tc_hutang_supplier_inv' => $id_tc_hutang_supplier_inv,
            'result' => $list,
        ); 
        $html = $this->load->view('pembayaran_hutang/Adm_pembayaran_pengajuan/detail_table', $data, true);

        echo json_encode(array('html' => $html, 'data' => $list));
    }

    public function get_invoice_detail($id_tc_tagih)
    {
        /*get data from model*/
        $list = $this->Adm_pembayaran_pengajuan->get_invoice_detail($id_tc_tagih);

        $no_invoice = $list[0]->no_invoice_tagih;
        echo json_encode(array('data' => $list, 'no_invoice' => $no_invoice));
    }

    public function get_penerimaan_detail($id_penerimaan)
    {
        /*get data from model*/
        $list = $this->Adm_pembayaran_pengajuan->get_penerimaan_detail($id_penerimaan, $_GET['flag']);
        $no = 0;
        foreach ($list as $key => $value) {
            $no++;
            $arr_subtotal[] = $value->dpp;
            $getData[] = array(
                'count_num' => $no,
                'nama_brg' => $value->nama_brg,
                'jml_kirim' => $value->jumlah_kirim_decimal,
                'satuan' => $value->satuan_besar,
                'harga_satuan' => $value->harga_net,
                'subtotal' => $value->dpp,
            );
        }
        echo json_encode(array('data' => $getData, 'kode_penerimaan' => $list[0]->kode_penerimaan, 'tgl_penerimaan' => $this->tanggal->formatDateDmy($list[0]->tgl_penerimaan), 'total' => array_sum($arr_subtotal)));
    }

    public function preview_invoice(){
        $data = array();
        $list = $this->Adm_pembayaran_pengajuan->get_invoice_detail($_GET['ID']);
        $data['result'] = $list;
        
        $this->load->view('pembayaran_hutang/Adm_pembayaran_pengajuan/preview_invoice', $data);
    }

    public function preview_kuitansi(){
 
        
        $data = array(
            'inv' => $_GET['inv'],
            'name' => $_GET['nm'],
            'tgl' => $_GET['tgl'],
            'total' => $_GET['jml'],
        );
        $this->load->view('pembayaran_hutang/Adm_pembayaran_pengajuan/preview_kuitansi', $data, false);
         
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
