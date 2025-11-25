<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Riwayat_pemakaian_bhp extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'inventory/stok/Riwayat_pemakaian_bhp');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('stok/Riwayat_pemakaian_bhp_model', 'Riwayat_pemakaian_bhp');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() {
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('stok/Riwayat_pemakaian_bhp/index', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Riwayat_pemakaian_bhp->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_input);
            $row[] = '<div class="left">'.strtoupper($row_list->nama_brg).'</div>';
            $row[] = '<div class="center">'.number_format($row_list->pengeluaran).'</div>';
            $row[] = $row_list->keterangan;
            if($row_list->is_rollback == null){
            $row[] = '<div class="center">
                <a href="#" class="btn btn-xs btn-danger"
                   onclick="rollback_stok_bhp('
                   .$row_list->id_kartu.', '
                   ."'".$row_list->kode_bagian."', "
                   ."'".$row_list->kode_brg."'"
                   .')">Rollback</a>
              </div>';
            }else{
                $row[] = '<div class="center" style="color: red; font-weight: bold">n/a</div>';
            }
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Riwayat_pemakaian_bhp->count_all(),
                        "recordsFiltered" => $this->Riwayat_pemakaian_bhp->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_bhp_unit()
    {
        /*get data from model*/
        $list = $this->Riwayat_pemakaian_bhp->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_input);
            $row[] = '<div class="left">'.strtoupper($row_list->nama_brg).'</div>';
            $row[] = '<div class="center">'.number_format($row_list->pengeluaran).'</div>';
            $row[] = '<div class="center">'.number_format($row_list->harga_beli).'</div>';
            $total = $row_list->pengeluaran * $row_list->harga_beli;
            $row[] = '<div class="center">'.number_format($total).'</div>';
            if($row_list->is_retur == null){
                $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-danger" onclick="rollback_stok_bhp('.$row_list->id_kartu.')">Rollback</a></div>';
            }else{
                $row[] = '<div class="center"><span style="font-weight: bold; color: red">Retur</span><br><small>'.$row_list->retur_by.'<br>'.$this->tanggal->formatDateTimeFormDmy($row_list->retur_date).'</small></div>';
            }
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Riwayat_pemakaian_bhp->count_all(),
                        "recordsFiltered" => $this->Riwayat_pemakaian_bhp->count_filtered(),
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

    public function print_label(){

        $result = $this->db->get_where('mt_barang', array('kode_brg' => $_GET['kode_brg']) )->result();
        $data = array(
            'barang' => $result,
            'count' => count($result),
            );
        $this->load->view('inventory/stok/Riwayat_pemakaian_bhp/print_label', $data);
    }

    public function cetak_kartu_stok(){

        $result = $this->Riwayat_pemakaian_bhp->get_by_params(); 
        // print_r($this->db->last_query());die;
        $data = array(
            'unit' => $this->db->get_where('mt_bagian', array('kode_bagian' => $_GET['kode_bagian']) )->row(),
            'header' => $this->Riwayat_pemakaian_bhp->get_mutasi_stok($_GET['kode_brg'], $_GET['kode_bagian']),
            'value' => $result,
            'count' => count($result),
            );
            // echo '<pre>';print_r($data);die;
        $this->load->view('inventory/stok/Riwayat_pemakaian_bhp/kartu_stok', $data);
    }

    public function rollback_stok_bhp(){
        
        // select kartu stok
        $row = $this->db->where('id_kartu', $_POST['ID'])->from('tc_kartu_stok')->get()->row();

        // query get last stok
        //$row = $this->db->where('kode_brg', $_POST['kode_brg'])->from('tc_kartu_stok')->get()->row();

        //$row = select * from tc_kartu_stok where kode_brg = $_POST['kode_brg'] AND kode_bagian = $_POST['kode_bagian'] ORDER BY id_kartu DESC

        $row2 = $this->db->where('kode_brg', $_POST['kode_brg'])
                 ->where('kode_bagian', $_POST['kode_bagian'])
                 ->order_by('id_kartu', 'DESC')
                 ->limit(1)
                 ->get('tc_kartu_stok')
                 ->row();
                 //echo $this->db->last_query(); die;

        // restore stok
        //$stok_akhir = $row->stok_akhir + $row->pengeluaran;
        $stok_akhir = $row2->stok_akhir + $row->pengeluaran;

        $mutasi = array(
            'id_kartu' => $this->master->get_max_number('tc_kartu_stok','id_kartu'),
            'tgl_input' => date('Y-m-d H:i:s'),
            'kode_brg' => $row->kode_brg,
            'stok_awal' => $row2->stok_akhir,
            'pemasukan' => $row->pengeluaran,
            'pengeluaran' => 0,
            'stok_akhir' => $stok_akhir,
            'jenis_transaksi' => 23,
            'kode_bagian' => $row->kode_bagian,
            'is_rollback' => 1,
            'keterangan' => 'Retur Pemakaian BHP',
            'petugas' => 0,
            'nama_petugas' => ($this->session->userdata('user')->fullname) ? $this->session->userdata('user')->fullname : 'Administrator Sistem',
        );

        // echo '<pre>';print_r($mutasi);die;
        $this->db->insert('tc_kartu_stok', $mutasi);

        // update kartu stok existing stok
        $this->db->update('tc_kartu_stok', array('is_retur' => 1, 'retur_date' => date('Y-m-d H:i:s'), 'retur_by' => $mutasi['nama_petugas']), array('id_kartu' => $_POST['ID']) );
        // update mt_depo_stok
        $this->db->update('mt_depo_stok', array('jml_sat_kcl' => $stok_akhir), array('kode_bagian' => $row->kode_bagian, 'kode_brg' => $row->kode_brg) );
        // 
        echo json_encode(array('status' => 200, 'message' => 'Proses berhasil dilakukan'));

    }

}


/* End of file Pendidikan.php */
/* Location: ./application/modules/Riwayat_pemakaian_bhp/controllers/Riwayat_pemakaian_bhp.php */
