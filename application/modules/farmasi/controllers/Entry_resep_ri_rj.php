<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Entry_resep_ri_rj extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Entry_resep_ri_rj');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Entry_resep_ri_rj_model', 'Entry_resep_ri_rj');
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
        $this->load->view('Entry_resep_ri_rj/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'Entry_resep_ri_rj/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['kode_pesan_resep'] = $id;
        $data['value'] = $this->Entry_resep_ri_rj->get_by_id($id);
        $data['trans_farmasi'] = $this->db->get_where('fr_tc_far', array('kode_pesan_resep' => $id) )->row();
        // echo '<pre>';print_r($data);die;
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
        $this->load->view('Entry_resep_ri_rj/form', $data);
    }

    public function form_create()
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'Entry_resep_ri_rj/'.strtolower(get_class($this)).'/'.__FUNCTION__);
        
        /*initialize flag for form*/
        $data['tipe_layanan'] = $_GET['tipe_layanan'];
        $data['str_tipe_layanan'] = ($_GET['tipe_layanan']=='RJ')?'Rajal':'Ranap';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Entry_resep_ri_rj/form_create', $data);
    }

    public function form_resep_rj()
    {
        $this->load->view('Entry_resep_ri_rj/form_resep_rj');
    }
    
    public function form_resep_luar()
    {
        $this->load->view('Entry_resep_ri_rj/form_resep_luar');
    }

    public function form_resep_karyawan()
    {
        
        $this->load->view('Entry_resep_ri_rj/form_resep_karyawan');
    }


    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Entry_resep_ri_rj/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Entry_resep_ri_rj->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Entry_resep_ri_rj/form', $data);
    }

    public function getDetail($kode_brg, $id){
        
        $data = $this->Entry_resep_ri_rj->get_detail_by_kode_brg($kode_brg);
        $resep = $this->Entry_resep_ri_rj->get_detail_by_kode_tr_resep($id);
        // echo'<pre>';print_r($data);die;
        
        $html = '';
        if(count($data) > 0){
            $html .= '<div style="border-bottom: 1px #333 solid"><b><h4>'.strtoupper($data->nama_brg).'</h4></b></div><br>';
            $html .= '<table class="table table-striped" style="width: 75%">';
            $html .= '<tr>';
                // $html .= '<th>Pabrikan</th>';
                // $html .= '<th>Jenis Obat</th>';
                $html .= '<th>Jumlah PRB/ ditangguhkan</th>';
                $html .= '<th>Satuan Besar/Kecil</th>';
                $html .= '<th>Rasio</th>';
                $html .= '<th>Signa</th>';
                $html .= '<th>Catatan</th>';
            $html .= '</tr>'; 
                $html .= '<tr>';
                    // $html .= '<td>'.$data->nama_pabrik.'</td>';
                    // $html .= '<td>'.$data->nama_jenis.'</td>';
                    $penangguhan = ($resep->prb_ditangguhkan == 1)?'Y':'N';
                    $html .= '<td align="center">'.$resep->jumlah_obat_23.'/'.$penangguhan.'</td>';
                    $html .= '<td>'.$data->satuan_besar.' / '.$data->satuan_kecil.'</td>';
                    $html .= '<td>1 : '.$data->content.'</td>';
                    $html .= '<td>'.$resep->dosis_obat.' x '.$resep->dosis_per_hari.' '.$resep->satuan_obat.' ('.$resep->anjuran_pakai.') </td>';
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
        $list = $this->Entry_resep_ri_rj->get_datatables();
        if(isset($_GET['search']) AND $_GET['search']==TRUE){
            $this->find_data(); exit;
        }
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
                                <li><a href="#" onclick="getMenu('."'farmasi/Entry_resep_ri_rj/form/".$row_list->kode_pesan_resep."?mr=".$row_list->no_mr."&tipe_layanan=".$_GET['flag']."'".')">Entry Resep</a></li>
                                <li><a href="#">Kirim ke Gudang</a></li>
                                <li><a href="#" onclick="rollback('.$row_list->kode_pesan_resep.')">Rollback</a></li>
                            </ul>
                        </div></div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/Entry_resep_ri_rj/form/".$row_list->kode_pesan_resep."?mr=".$row_list->no_mr."&tipe_layanan=".$_GET['flag']."'".')">'.$row_list->kode_pesan_resep.'</a></div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_pesan);
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->nama_pegawai;
            $row[] = ucwords($row_list->nama_bagian);
            $status_tebus = ($row_list->status_tebus ==  1)?'<label class="label label-xs label-success">Selesai</label>':'<label class="label label-xs label-warning">Belum diproses</label>';
            $row[] = '<div class="center">'.$status_tebus.'</div>';
            $row[] = '<div class="center">'.$row_list->jumlah_r.'</div>';
            $row[] = $row_list->nama_lokasi;
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Entry_resep_ri_rj->count_all(),
                        "recordsFiltered" => $this->Entry_resep_ri_rj->count_filtered(),
                        "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_temp_pesanan_obat()
    {
        /*get data from model*/
        $list = $this->Entry_resep_ri_rj->get_detail_resep_data(); 
        // echo '<pre>';print_r($list); die;
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
            
            if($row_list->status_tebus != 1){
                $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-success" '.$onclick.'><i class="fa fa-edit"></i></a> <a href="#" class="btn btn-xs btn-danger" onclick="delete_resep('.$row_list->relation_id.', '."'".$row_list->flag_resep."'".')"><i class="fa fa-trash"></i></a> </div>';
            }else{
                $row[] = '<div class="center"><i class="fa fa-check-circle bigger-150 green"></i></div>';
            }

            $row[] = $this->tanggal->formatDateTime($row_list->tgl_input);
            $row[] = $row_list->kode_brg;
            $row[] = strtoupper($row_list->nama_brg);
            $row[] = '<div class="center">'.(int)$row_list->jumlah_tebus.' '.ucfirst($row_list->satuan_kecil).'</div>';
            $row[] = '<div align="right">'.number_format($row_list->harga_jual_satuan, 2).'</div>';
            $row[] = '<div align="right">'.number_format($row_list->sub_total, 2).'</div>';
            $row[] = '<div align="right">'.number_format($row_list->jasa_r, 2).'</div>';
            $row[] = '<div align="right">'.number_format($row_list->total, 2).'</div>';
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

    public function cek_resep_karyawan_today(){

        $trans_far = $this->db->where("convert(varchar,tgl_trans,23) = '".date('Y-m-d')."' ")->get_where('fr_tc_far', array('no_mr' => $_GET['no_mr']) );
        $return = ( $trans_far->num_rows() > 0 ) ? $trans_far->row() : array('kode_trans_far' => 0) ;
        echo json_encode($return);
    }
}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
