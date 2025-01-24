<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mst_tarif extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'tarif/Mst_tarif');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Mst_tarif_model', 'Mst_tarif');
        $this->load->library('tarif');
        // driver
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
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
            'klas' =>$klas = $this->db->order_by('no_urut', 'ASC')->where('is_active', 1)->get('mt_klas')->result()
        );
        /*load view index*/
        $this->load->view('Mst_tarif/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Mst_tarif/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Mst_tarif->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Mst_tarif/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $view = ($data['flag'] == 'create')?'form':'form_update';
        $this->load->view('Mst_tarif/'.$view.'', $data);
    }
    /*function for view data only*/
    public function edit_klas_tarif($kode_master_tarif_detail)
    {
        // define
        $tarif = $this->Mst_tarif->get_detail_by_kode_tarif_detail($kode_master_tarif_detail);
        /*breadcrumbs for view*/
        /*define data variabel*/
        $data['value'] = $tarif;
        $data['title'] = $tarif->nama_tarif;
        $data['flag'] = "read";
        /*load form view*/
        $this->load->view('Mst_tarif/form_edit_klas_tarif', $data);
    }

    public function add_klas_tarif($kode_tarif)
    {
        // define
        $tarif = $this->Mst_tarif->get_by_id($kode_tarif);
        // print_r($tarif); die;
        /*breadcrumbs for view*/
        /*define data variabel*/
        $data['value'] = $tarif;
        $data['title'] = $tarif->nama_tarif;
        $data['flag'] = "read";
        /*load form view*/
        $this->load->view('Mst_tarif/form_add_klas_tarif', $data);
    }

    public function getDetail($kode_tarif){
        
        $data = $this->Mst_tarif->get_detail_by_kode_tarif($kode_tarif);
        // print_r($data); die;
        $html = '';
        if(count($data) > 0){
            $html .= '<div style="border-bottom: 1px #333 solid"><b><h4>'.$data['nama_tarif'].'</h4></b></div><br>';
            $html .= '<div style="padding-left: 39px; padding-bottom: 3px"><a href="#" class="btn btn-xs btn-success" onclick="getMenu('."'tarif/Mst_tarif/add_klas_tarif/".$kode_tarif."'".')"><i class="fa fa-plus"></i> Tambah Klas Tarif</a></div>';
            $html .= '<table class="table table-striped" style="width: 90%; margin-left: 38px !important">';
            $html .= '<tr>';
                $html .= '<th>Nama Klas </th>';
                $html .= '<th>Bill Dr1</th>';
                $html .= '<th>Bill Dr2</th>';
                $html .= '<th>Bill Dr3</th>';
                $html .= '<th>Kamar Tindakan</th>';
                $html .= '<th>Obat</th>';
                $html .= '<th>Alkes</th>';
                $html .= '<th>Alat RS</th>';
                $html .= '<th>BHP</th>';
                $html .= '<th>Adm</th>';
                $html .= '<th>Pendapatan RS</th>';
                $html .= '<th>Biaya Lainnya</th>';
                $html .= '<th>Total</th>';
                $html .= '<th>Status</th>';
                $html .= '<th>Action</th>';
            $html .= '</tr>'; 
            foreach ($data['result'] as $key => $value) {
                $nama_klas = ($key != '0')?$key:'Tarif Global';
                $html .='<tr><td colspan="13"><b><i class="fa fa-angle-double-down bigger-120"></i> '.strtoupper($nama_klas).'</b></td></tr>';
                foreach ($value as $key2 => $val) {
                    # code...
                    // echo "<pre>";print_r($val); die;
                    $label_active = (trim($val->is_active) == 'Y')?'<span style="color: green; font-weight: bold">Active</span>':'<span style="color: red; font-weight: bold">Inactive</span>';
                    $html .= '<tr>';
                        $html .= '<td>Revisi ke- '.$val->revisi_ke.'</td>';
                        $html .= '<td align="right">'.number_format($val->bill_dr1).'</td>';
                        $html .= '<td align="right">'.number_format($val->bill_dr2).'</td>';
                        $html .= '<td align="right">'.number_format($val->bill_dr3).'</td>';
                        $html .= '<td align="right">'.number_format($val->kamar_tindakan).'</td>';
                        $html .= '<td align="right">'.number_format($val->obat).'</td>';
                        $html .= '<td align="right">'.number_format($val->alkes).'</td>';
                        $html .= '<td align="right">'.number_format($val->alat_rs).'</td>';
                        $html .= '<td align="right">'.number_format($val->bhp).'</td>';
                        $html .= '<td align="right">'.number_format($val->adm).'</td>';
                        $html .= '<td align="right">'.number_format($val->pendapatan_rs).'</td>';
                        $html .= '<td align="right">'.number_format($val->biaya_lain).'</td>';
                        $html .= '<td align="right">'.number_format($val->total).'</td>';
                        $html .= '<td align="center">'.$label_active.'</td>';
                        $html .= '<td align="center">
                                <a href="#" onclick="getMenu('."'tarif/Mst_tarif/edit_klas_tarif/".$val->kode_master_tarif_detail."'".')"><span class="badge badge-success"><i class="fa fa-pencil"></i></span></a>
                                <a href="#" onclick="delete_tarif_klas('.$val->kode_master_tarif_detail.')"><span class="badge badge-danger"><i class="fa fa-trash"></i></span></a>
                        </td>';
                    $html .= '</tr>';
                }
                
            }
                

            $html .= '</table>'; 
        }else{
            $html .= '<div style="border-bottom:1px solid #333;"><b>Tidak ada barang ditemukan</b></div><br>';
        }

        echo json_encode(array('html' => $html, 'data' => $data));

    }

    public function get_data()
    {

        // Save into the cache for 5 minutes
		$this->cache->save('cache', $_GET, 300);

        // get klas
        $klas = $this->db->order_by('no_urut', 'ASC')->where('is_active', 1)->get('mt_klas')->result();

        /*get data from model*/
        $list = [];
        // $list = $this->Mst_tarif->get_datatables();
        $list = isset($_GET['checked_nama_tarif']) ? $this->Mst_tarif->get_datatables() : [];
        // echo '<pre>';print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $key=>$row_list) {
            $no++;
            $row = array();
            $row[] = '';
            $row[] = '';
            $row[] = $key;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $key;
            $row[] = ucwords($row_list['nama_tarif']);
            $row[] = ucwords($row_list['nama_bagian']);
            $row[] = ucwords($row_list['nama_jenis_tindakan']);
            foreach ($klas as $key_klas => $row_klas) {
                $row[] = isset($row_list['klas'][$row_klas->kode_klas]) ? '<div class="pull-right">'.number_format($row_list['klas'][$row_klas->kode_klas]->total).'</div>' : '<div class="pull-right">0</div>';
            }
            $row[] = (rtrim($row_list['is_active']) == 'Y') ? '<div class="center"><span style="color: green; font-weight: bold">Aktif</span></div>' : '<div class="center"><span style="color: red; font-weight: bold">Non Aktif</span></div>' ;
            $row[] = '<div class="center">
                '.$this->authuser->show_button('tarif/Mst_tarif','U',$key,2).'
                '.$this->authuser->show_button('tarif/Mst_tarif','D',$key,2).'
                </div>';

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mst_tarif->count_all(),
                        "recordsFiltered" => $this->Mst_tarif->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // echo print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('nama_tarif', 'Nama Tarif', 'trim|required');
        $val->set_rules('kode_bagian', 'Kode Bagian', 'trim');
        $val->set_rules('kode_klas', 'Klas Tarif', 'trim');
        $val->set_rules('jenis_tindakan', 'Jenis Tindakan', 'trim|required');

        if($_POST['submit'] != 'update_tarif'){
            $val->set_rules('bill_dr1', 'Bill Dr1', 'trim');
            $val->set_rules('bill_dr2', 'Bill Dr2', 'trim');
            $val->set_rules('bill_dr3', 'Bill Dr3', 'trim');
            $val->set_rules('kamar_tindakan', 'Kamar Tindakan', 'trim');
            $val->set_rules('bhp', 'BHP', 'trim');
            $val->set_rules('obat', 'Obat', 'trim');
            $val->set_rules('alat_rs', 'Alat RS', 'trim');
            $val->set_rules('alkes', 'Alkes', 'trim');
            $val->set_rules('adm', 'Administrasi', 'trim');
            $val->set_rules('pendapatan_rs', 'Pendapatan RS', 'trim');
            $val->set_rules('total', 'Total', 'trim|required');
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
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;
            
            $dataexc = array(
                'nama_tarif' => $this->regex->_genRegex($val->set_value('nama_tarif'), 'RGXQSL'),
                'kode_bagian' => $val->set_value('kode_bagian')?$val->set_value('kode_bagian'):'0',
                'jenis_tindakan' => $this->regex->_genRegex($val->set_value('jenis_tindakan'), 'RGXQSL'),
                'tingkatan' => 5,
                'is_active' => 'Y',
            );
            // print_r($dataexc);die;
            if($id==0){
                
                // kode tarif
                $kode_tarif = $this->generate_kode_tarif($val->set_value('kode_bagian'), $_POST['jenis_tindakan']);
                // echo print_r($kode_tarif);die;
                $dataexc['kode_tarif'] = $kode_tarif;
                
                // kode tindakan
                $new_kode_tindakan = $kode_tarif;
                $dataexc['kode_tindakan'] = 'NT'.$new_kode_tindakan;
                
                $dataexc['revisi_ke'] = 1;
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                // print_r($dataexc);die;
                $this->db->insert('mt_master_tarif', $dataexc);
                $newId = $kode_tarif;
            }else{
                // get existing
                $exist = $this->db->get_where('mt_master_tarif', array('kode_tarif' => $id) )->row();
                $dataexc['revisi_ke'] = $exist->revisi_ke + 1;

                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->db->update('mt_master_tarif', $dataexc, array('kode_tarif' => $id));
                $newId = $id;
            }

            if(in_array($_POST['submit'], array('create_tarif', 'update_rincian', 'add_klas_tarif') )){
                // insert detail tarif
                $bill_rs = $_POST['kamar_tindakan'] + $_POST['bhp'] + $_POST['obat'] + $_POST['alat_rs'] + $_POST['alkes'] + $_POST['adm'] + $_POST['pendapatan_rs'];
                $data_tarif = array(
                    'kode_tarif' => $this->regex->_genRegex($newId, 'RGXQSL'),
                    'kode_klas' => $this->regex->_genRegex($_POST['kode_klas'], 'RGXQSL'),
                    'bill_rs' => $this->regex->_genRegex((int)$bill_rs, 'RGXQSL'),
                    'bill_dr1' => $this->regex->_genRegex((int)$_POST['bill_dr1'], 'RGXQSL'),
                    'bill_dr2' => $this->regex->_genRegex((int)$_POST['bill_dr2'], 'RGXQSL'),
                    'bill_dr3' => $this->regex->_genRegex((int)$_POST['bill_dr3'], 'RGXQSL'),
                    'kamar_tindakan' => $this->regex->_genRegex((int)$_POST['kamar_tindakan'], 'RGXQSL'),
                    'bhp' => $this->regex->_genRegex((int)$_POST['bhp'], 'RGXQSL'),
                    'obat' => $this->regex->_genRegex((int)$_POST['obat'], 'RGXQSL'),
                    'alat_rs' => $this->regex->_genRegex((int)$_POST['alat_rs'], 'RGXQSL'),
                    'alkes' => $this->regex->_genRegex((int)$_POST['alkes'], 'RGXQSL'),
                    'adm' => $this->regex->_genRegex((int)$_POST['adm'], 'RGXQSL'),
                    'pendapatan_rs' => $this->regex->_genRegex((int)$_POST['pendapatan_rs'], 'RGXQSL'),
                    'total' => $this->regex->_genRegex((int)$_POST['total'], 'RGXQSL'),
                    'is_active' => $this->input->post('is_active'),
                );

                // existing
                $tarif_det_exist = $this->db->order_by('revisi_ke', 'DESC')->get_where('mt_master_tarif_detail', array('kode_tarif' => $newId, 'kode_klas' => $_POST['kode_klas']) )->row();

                if(empty($tarif_det_exist)){
                    $data_tarif['revisi_ke'] = 1;
                    $data_tarif['created_date'] = date('Y-m-d H:i:s');
                    $data_tarif['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    /*save post data*/
                    $this->db->insert('mt_master_tarif_detail', $data_tarif);
                    $kode_master_tarif_detail = $this->db->insert_id();
                }else{
                    $data_tarif['revisi_ke'] = $tarif_det_exist->revisi_ke + 1;
                    $data_tarif['updated_date'] = date('Y-m-d H:i:s');
                    $data_tarif['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    /*update record*/
                    $this->db->update('mt_master_tarif_detail', $data_tarif, array('kode_master_tarif_detail' => $tarif_det_exist->kode_master_tarif_detail));
                    $kode_master_tarif_detail = $tarif_det_exist->kode_master_tarif_detail;
                }
            }

            $kd_trf = isset($newId)?$newId:'';
            $kd_trf_dtl = isset($kode_master_tarif_detail)?$kode_master_tarif_detail:'';

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'kode_tarif' => $kd_trf, 'kode_master_tarif_detail' => $kd_trf_dtl));
            }
        }
    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        
        if($this->Mst_tarif->delete_by_id($id)){
            echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

        }else{
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
        }
        
    }

    public function delete_tarif_klas()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        
        if($this->Mst_tarif->delete_tarif_klas($id)){
            echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

        }else{
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
        }
        
    }
    
    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function generate_kode_tarif($kode_bagian='', $jenis_tindakan=''){
        /*get max kode tarif by kode_bagian*/
        if($kode_bagian == ''){
            $max_kode = $this->db->query("select COUNT(*)as max_tarif from mt_master_tarif where kode_bagian='0' and jenis_tindakan=".$jenis_tindakan."")->row();
        }else{
            $max_kode = $this->db->query("select MAX(kode_tarif)as max_tarif from mt_master_tarif where kode_bagian=".$kode_bagian."")->row();
        }
        $new_kode_plus_one = ($kode_bagian == '') ? $jenis_tindakan.'0'.$max_kode->max_tarif + 1 : $max_kode->max_tarif + 1;

        $new_kode = $this->recursive_kode_tarif($new_kode_plus_one);
        return $new_kode;
    }

    public function recursive_kode_tarif($kode_tarif){
      $kode = $this->db->get_where('mt_master_tarif', array('kode_tarif' => $kode_tarif));
      if($kode->num_rows() == 0){
        return $kode_tarif;
      }else{
        $kode = $kode_tarif + 1;
        return $this->recursive_kode_tarif($kode);
      }
    }

    public function export_excel()
    {   
        // box data
        $data = array();
        $list = [];
        if(isset($_GET['unit'])){
            $list = ($_GET['unit'] != '') ? $this->Mst_tarif->get_datatables() : [];
        }
        $data['data'] = $list;
        $data['klas'] = $this->db->order_by('no_urut', 'ASC')->get('mt_klas')->result();
        $this->load->view('Mst_tarif/export_excel', $data);
    }

}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
