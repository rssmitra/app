<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Etiket_obat extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Etiket_obat');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Etiket_obat_model', 'Etiket_obat');
        // load library
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos'); 
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() 
    { 
        /*define variable data*/
        $data = array(
            'title' => ($_GET['flag']=='RJ') ? 'Resep Rawat Jalan' : 'Resep Rawat Inap' ,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag']
        );
        /*load view index*/
        $this->load->view('Etiket_obat/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'Etiket_obat/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['kode_pesan_resep'] = $id;
        $data['value'] = $this->Etiket_obat->get_by_id($id);
        //echo '<pre>';print_r($data);die;
        /*no mr default*/
        $data['no_mr'] = $_GET['mr'];
        /*initialize flag for form*/
        $data['tipe_layanan'] = $_GET['tipe_layanan'];
        $data['str_tipe_layanan'] = ($_GET['tipe_layanan']=='RJ')?'Rajal':'Ranap';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Etiket_obat/form', $data);
    }

    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Etiket_obat/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Etiket_obat->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Etiket_obat/form', $data);
    }

    public function getDetail($kode_brg, $id){
        
        $data = $this->Etiket_obat->get_detail_by_kode_brg($kode_brg);
        $resep = $this->Etiket_obat->get_detail_by_kode_tr_resep($id);
        //print_r($data);die;
        
        $html = '';
        if(count($data) > 0){
            $html .= '<div style="border-bottom: 1px #333 solid"><b><h4>'.strtoupper($data->nama_brg).'</h4></b></div><br>';
            $html .= '<table class="table table-striped">';
            $html .= '<tr>';
                $html .= '<th>Kode</th>';
                $html .= '<th>Nama Obat</th>';
                $html .= '<th>Jenis</th>';
                $html .= '<th>Pabrikan</th>';
                $html .= '<th>Satuan Besar/Kecil</th>';
                $html .= '<th>Rasio</th>';
                $html .= '<th>Aturan Pakai</th>';
                $html .= '<th>Catatan</th>';
            $html .= '</tr>'; 
                $html .= '<tr>';
                    $html .= '<td>'.$data->kode_brg.'</td>';
                    $html .= '<td>'.$data->nama_brg.'</td>';
                    $html .= '<td>'.$data->nama_jenis.'</td>';
                    $html .= '<td>'.$data->nama_pabrik.'</td>';
                    $html .= '<td>'.$data->satuan_besar.' / '.$data->satuan_kecil.'</td>';
                    $html .= '<td>1 : '.$data->content.'</td>';
                    $html .= '<td>'.$resep->aturan_pakai.'</td>';
                    $html .= '<td>'.ucfirst($resep->catatan_lainnya).'</td>';
                $html .= '</tr>';

            $html .= '</table>'; 
        }else{
            $html .= '<div style="border-bottom:1px solid #333;"><b>Tidak ada barang ditemukan</b></div><br>';
        }

        echo json_encode(array('html' => $html, 'resep_data' => $resep));

    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Etiket_obat->get_datatables();
        if(isset($_GET['search']) AND $_GET['search']==TRUE){
            $this->find_data(); exit;
        }
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/Etiket_obat/form/".$row_list->kode_pesan_resep."?mr=".$row_list->no_mr."&tipe_layanan=".$_GET['flag']."'".')">'.$row_list->kode_trans_far.'</a></div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_trans);
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->dokter_pengirim;
            $row[] = $row_list->nama_pelayanan;
            $row[] = '<div class="center">
                        <a href="#" onclick="getMenu('."'farmasi/Etiket_obat/form/".$row_list->kode_pesan_resep."?mr=".$row_list->no_mr."&tipe_layanan=".$_GET['flag']."'".')" class="btn btn-xs btn-inverse">
                            <i class="fa fa-copy"></i> Copy
                        </a>
                      </div>';
            $row[] = '<div class="center">
                      <a href="#" onclick="PopupCenter('."'farmasi/Etiket_obat/print_resep_gudang/".$row_list->kode_trans_far."'".', '."'Print Etiket Obat'".',750,550)" class="btn btn-xs btn-info">
                          <i class="fa fa-ticket inverse"></i> Etiket
                      </a>
                    </div>';
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Etiket_obat->count_all(),
                        "recordsFiltered" => $this->Etiket_obat->count_filtered(),
                        "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_temp_pesanan_obat()
    {
        /*get data from model*/
        $list = $this->Etiket_obat->get_detail_resep_data(); 
        //echo '<pre>';print_r($list); die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '';
            $row[] = $row_list->relation_id;
            $row[] = $row_list->flag_resep;
            if($row_list->flag_resep=='racikan'){
                $onclick = 'onclick="show_modal('."'farmasi/Entry_resep_racikan/form/".$row_list->kode_pesan_resep."?kelompok=12&id_tc_far_racikan=".$row_list->relation_id."&tipe_layanan=".$_GET['tipe_layanan']."'".', '."'RESEP RACIKAN'".')"';
                $btn_selesai_racikan = '<li><a href="#" onclick="process_selesai('.$row_list->relation_id.')">Resep Selesai</a></li>';
            }else{
                $onclick = 'onclick="edit_obat_resep('."'".$row_list->kode_brg."','".$row_list->relation_id."'".')"';
                $btn_selesai_racikan = '';
            }
            /*$row[] = '<div class="center"><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-inverse">
                            '.$btn_selesai_racikan.'
                                <li><a href="#" '.$onclick.' >Edit</a></li>
                                <li><a href="#" onclick="delete_resep('.$row_list->relation_id.', '."'".$row_list->flag_resep."'".')">Hapus</a></li>
                            </ul>
                        </div></div>';*/
            $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-success" '.$onclick.'><i class="fa fa-edit"></i></a> <a href="#" class="btn btn-xs btn-danger" onclick="delete_resep('.$row_list->relation_id.', '."'".$row_list->flag_resep."'".')"><i class="fa fa-trash"></i></a> </div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_input);
            $row[] = $row_list->kode_brg;
            $row[] = strtoupper($row_list->nama_brg);
            $row[] = '<div class="center">'.$row_list->jumlah_pesan.' '.ucfirst($row_list->satuan_kecil).'</div>';
            $row[] = '<div align="right">'.number_format($row_list->harga_jual_satuan).'</div>';
            $row[] = '<div align="right">'.number_format($row_list->sub_total).'</div>';
            $row[] = '<div align="right">'.number_format($row_list->jasa_r).'</div>';
            $row[] = '<div align="right">'.number_format($row_list->total).'</div>';
            $status_input = ($row_list->status_input==NULL)?'<label class="label label-warning">Dalam Proses</label>':'<label class="label label-success">Selesai</label>';
            $row[] = '<div align="center">'.$status_input.'</div>';
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
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

    public function print_resep_gudang()
    {   
        $this->print_escpos->print_testing();
    }

}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
