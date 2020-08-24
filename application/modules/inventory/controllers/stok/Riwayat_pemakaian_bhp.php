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

    public function reset_stok_depo($kode_bagian, $agenda_so_id){
        // select kartu stok
        $kartu_stok = $this->db->where('id_kartu IN (select MAX(id_kartu) as id_kartu from tc_kartu_stok where kode_bagian='."'".$kode_bagian."'".' group by kode_brg) ')->from('tc_kartu_stok')->get()->result();
        
        foreach($kartu_stok as $row){
            $mutasi = array(
                'id_kartu' => $this->master->get_max_number('tc_kartu_stok','id_kartu'),
                'tgl_input' => date('Y-m-d H:i:s'),
                'kode_brg' => $row->kode_brg,
                'stok_awal' => $row->stok_awal,
                'pemasukan' => 0,
                'pengeluaran' => $row->stok_awal,
                'stok_akhir' => 0,
                'jenis_transaksi' => 10,
                'kode_bagian' => $kode_bagian,
                'keterangan' => 'Reset stok depo untuk stok opname',
                'petugas' => 0,
                'nama_petugas' => 'Administrator Sistem',
                'agenda_so_id' => $agenda_so_id,
            );
            // reset 0 kartu stok
        // $this->db->insert('tc_kartu_stok', $mutasi);
        }

        // echo '<pre>';print_r($mutasi);die;

        

        // update mt_rekap stok
        $this->db->update('mt_depo_stok', array('jml_sat_kcl' => 0), array('kode_bagian' => $kode_bagian) );

        echo 'Sukses';

    }

}


/* End of file Pendidikan.php */
/* Location: ./application/modules/Riwayat_pemakaian_bhp/controllers/Riwayat_pemakaian_bhp.php */
