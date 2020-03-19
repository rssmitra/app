<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hd_pelayanan_pasien extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'hemodialisa/Hd_pelayanan_pasien');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Hd_pelayanan_pasien_model', 'Hd_pelayanan_pasien');
        /*load library*/
        $this->load->library('Form_validation');
        $this->load->library('stok_barang');
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
        $this->load->view('Hd_pelayanan_pasien/index', $data);
    }

    public function form($id='', $no_kunjungan)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Hd_pelayanan_pasien/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Hd_pelayanan_pasien->get_by_id($id);
        /*variable*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $id;
        $data['no_kunjungan'] = $no_kunjungan;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Hd_pelayanan_pasien/form_hd', $data);
    }

    public function tindakan($id='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Hd_pelayanan_pasien/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Hd_pelayanan_pasien->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $data['value']->no_kunjungan;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Hd_pelayanan_pasien/form_hd_tindakan', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Hd_pelayanan_pasien->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_kunjungan.'"/>
                            <span class="lbl"></span>
                        </label>
                    </div>';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#">Selengkapnya</a></li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'hemodialisa/Hd_pelayanan_pasien/form/".$row_list->id_pl_tc_poli."/".$row_list->no_kunjungan."'".')">'.$row_list->no_kunjungan.'</a></div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_jam_poli);
            $row[] = $row_list->nama_pegawai;
            $row[] = '<div class="center">'.$row_list->no_antrian.'</div>';
            $status_periksa = ($row_list->status_periksa!=1)?'<label class="label label-danger">Belum dipieriksa</label>':'<label class="label label-success">Selesai</label>';
            $row[] = '<div class="center">'.$status_periksa.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Hd_pelayanan_pasien->count_all(),
                        "recordsFiltered" => $this->Hd_pelayanan_pasien->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_tindakan()
    {
        /*get data from model*/
        $list = $this->Hd_pelayanan_pasien->get_datatables_tindakan();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->kode_trans_pelayanan.'"/>
                            <span class="lbl"></span>
                        </label>
                    </div>';
            $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-danger" onclick="delete_transaksi('.$row_list->kode_trans_pelayanan.')"><i class="fa fa-times-circle"></i></a></div>';
            $row[] = $row_list->kode_trans_pelayanan;
            $row[] = strtoupper($row_list->nama_tindakan);
            $row[] = $row_list->nama_pegawai;

            $bill_total = $row_list->bill_rs + $row_list->bill_dr1 + $row_list->bill_dr2;
            $row[] = '<div align="right">Rp. '.number_format($bill_total).',-</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Hd_pelayanan_pasien->count_all_tindakan(),
                        "recordsFiltered" => $this->Hd_pelayanan_pasien->count_filtered_tindakan(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_obat()
    {
        /*get data from model*/
        $list = $this->Hd_pelayanan_pasien->get_datatables_tindakan();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->kode_trans_pelayanan.'"/>
                            <span class="lbl"></span>
                        </label>
                    </div>';
            $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-danger" onclick="delete_transaksi('.$row_list->kode_trans_pelayanan.')"><i class="fa fa-times-circle"></i></a></div>';
            $row[] = $row_list->kode_trans_pelayanan;
            $row[] = strtoupper($row_list->nama_tindakan);
            $bill_total = $row_list->bill_rs + $row_list->bill_dr1 + $row_list->bill_dr2;
            $row[] = '<div align="right">Rp. '.number_format($bill_total).',-</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Hd_pelayanan_pasien->count_all_tindakan(),
                        "recordsFiltered" => $this->Hd_pelayanan_pasien->count_filtered_tindakan(),
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

    public function process_add_tindakan(){


        // form validation
        $this->form_validation->set_rules('hd_kode_tindakan_hidden', 'Tindakan', 'trim|required');
        $this->form_validation->set_rules('hd_kode_dokter_hidden1', 'Dokter', 'trim|required');
        

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();           

            $kode_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');

            $dataexc = array(
                'kode_trans_pelayanan' => $kode_trans_pelayanan,
                /*form hidden input default*/
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'kode_kelompok' => $this->regex->_genRegex($this->input->post('kode_kelompok'),'RGXINT'),
                'kode_perusahaan' => $this->regex->_genRegex($this->input->post('kode_perusahaan'),'RGXINT'),
                'no_mr' => $this->regex->_genRegex($this->input->post('no_mr'),'RGXQSL'),
                'nama_pasien_layan' => $this->regex->_genRegex($this->input->post('nama_pasien_layan'),'RGXQSL'),
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                /*end form hidden input default*/

                /*form hidden after select tindakan*/
                'kode_tarif' => $this->regex->_genRegex($this->input->post('kode_tarif'),'RGXINT'),
                'jenis_tindakan' => ($this->regex->_genRegex($this->input->post('jenis_tindakan'),'RGXINT')!=0)?$this->regex->_genRegex($this->input->post('jenis_tindakan'),'RGXINT'):3,
                'nama_tindakan' => $this->regex->_genRegex($this->input->post('nama_tindakan'),'RGXQSL'),
                'kode_bagian' => $this->regex->_genRegex($this->input->post('kode_bagian'),'RGXQSL'),
                'kode_master_tarif_detail' => $this->regex->_genRegex($this->input->post('kode_master_tarif_detail'),'RGXQSL'),
                'kode_klas' => $this->regex->_genRegex($this->input->post('kode_klas'),'RGXINT'),
                'bill_dr1' => $this->regex->_genRegex($this->input->post('bill_dr1'),'RGXINT'),
                'bill_rs' => $this->regex->_genRegex($this->input->post('bill_rs'),'RGXINT'),
                'kode_dokter1' => $this->regex->_genRegex($this->input->post('hd_kode_dokter_hidden1'),'RGXINT'),
                //'kode_dokter2' => $this->regex->_genRegex($this->input->post('hd_kode_dokter_hidden2'),'RGXINT'),
                /*end form hidden after select tindakan*/

                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                'tgl_transaksi' => date('Y-m-d'),                
                'jumlah' => 1,                
                
            );

            /*save tc_trans_pelayanan*/
            $this->Hd_pelayanan_pasien->save('tc_trans_pelayanan', $dataexc);

            /*save logs*/
            $this->logs->save('tc_trans_pelayanan', $kode_trans_pelayanan, 'insert new record on '.$this->title.' module', json_encode($dataexc),'kode_trans_pelayanan');

            //print_r($dataexc);die;

            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }

        
        }

    }

    public function process_add_obat(){

        
        // form validation
        $this->form_validation->set_rules('hd_kode_brg_hidden', 'Obat', 'trim|required');
        

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();           

            $kode_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');

            /*get bill_rs*/
            $bill_rs = $this->input->post('harga_satuan') * $this->input->post('hd_jumlah_obat');

            $dataexc = array(
                'kode_trans_pelayanan' => $kode_trans_pelayanan,
                /*form hidden input default*/
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'kode_kelompok' => $this->regex->_genRegex($this->input->post('kode_kelompok'),'RGXINT'),
                'kode_perusahaan' => $this->regex->_genRegex($this->input->post('kode_perusahaan'),'RGXINT'),
                'no_mr' => $this->regex->_genRegex($this->input->post('no_mr'),'RGXQSL'),
                'nama_pasien_layan' => $this->regex->_genRegex($this->input->post('nama_pasien_layan'),'RGXQSL'),
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                /*end form hidden input default*/

                /*form hidden after select tindakan*/
                'kode_barang' => $this->regex->_genRegex($this->input->post('hd_kode_brg_hidden'),'RGXQSL'),
                'jenis_tindakan' => 9,
                'nama_tindakan' => $this->regex->_genRegex($this->input->post('nama_tindakan'),'RGXQSL'),
                'kode_bagian' => $this->regex->_genRegex($this->input->post('kode_bagian'),'RGXQSL'),
                'kode_klas' => $this->regex->_genRegex(16,'RGXINT'),
                
                'bill_rs' => $this->regex->_genRegex($bill_rs,'RGXINT'),
                'kode_profit' => $this->regex->_genRegex(2000,'RGXINT'),
                /*end form hidden after select obat*/
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                'tgl_transaksi' => date('Y-m-d'),                
                'jumlah' => $this->regex->_genRegex($this->input->post('hd_jumlah_obat'),'RGXINT'),
                
            );
            //print_r($dataexc);die;

            /*save tc_trans_pelayanan*/
            $this->Hd_pelayanan_pasien->save('tc_trans_pelayanan', $dataexc);

            $this->stok_barang->stock_process($dataexc['kode_barang'], $dataexc['jumlah'], $dataexc['kode_bagian'],6, '', 'reduce');


            /*save logs*/
            $this->logs->save('tc_trans_pelayanan', $kode_trans_pelayanan, 'insert new record on '.$this->title.' module', json_encode($dataexc),'kode_trans_pelayanan');

            //print_r($dataexc);die;

            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }

        
        }

    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->Hd_pelayanan_pasien->delete_trans_pelayanan($id)){
                $this->logs->save('tc_trans_pelayanan', $id, 'delete record', '', 'kode_trans_pelayanan');
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
/* Location: ./application/functiones/example/controllers/example.php */
