<?php
date_default_timezone_set("Asia/Jakarta");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Farmasi_pesan_resep extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Farmasi_pesan_resep');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Farmasi_pesan_resep_model', 'Farmasi_pesan_resep');

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
        $this->load->view('Farmasi_pesan_resep/index', $data);
    }

    public function pesan_resep($no_kunj='',$kode_klas='',$kode_profit='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Farmasi_pesan_resep/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$no_kunj);
        /*get value by no_kunj*/
        $data['value'] = $this->Farmasi_pesan_resep->get_detail_data_kunjungan($no_kunj);
        $data['kode_klas'] = $kode_klas;
        $data['kode_profit'] = $kode_profit;
        $data['kode_bagian_asal'] = isset($_GET['kode_bag'])?$_GET['kode_bag']:'';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Farmasi_pesan_resep/form_pesan_resep', $data);
    }

    public function get_data_by_id()
    {
        /*get data from model*/
        $list = $this->Farmasi_pesan_resep->get_by_no_kunj($this->input->get('q'));
        //print_r($this->db->last_query());
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '';
            $row[] = '<div class="center">
                        <a href="#" id="btn_edit_data" class="btn btn-xs btn-success" onclick="showModalEdit('.$row_list->kode_pesan_resep.')"><i class="ace-icon fa fa-edit bigger-50"></i></a>
                        <a href="#" id="btn_delete_data" class="btn btn-xs btn-danger" onclick="delete_pesan_resep('.$row_list->kode_pesan_resep.')"><i class="ace-icon fa fa-times bigger-50"></i></a>
                  </div>'; 
            $row[] = '<div class="center">'.$this->tanggal->formatDateTime($row_list->tgl_pesan).'</div>';
            $row[] = ucwords($row_list->nama_bagian);
            $row[] = $row_list->kode_pesan_resep;
            $row[] = $row_list->nama_pegawai;
            $row[] = ($row_list->lokasi_tebus==1)?'Dalam RS':'Luar RS';
            $row[] = '<div class="center">'.$row_list->jumlah_r.'</div>';
            $status_tebus = ($row_list->status_tebus==null)?'<label class="label label-danger">Dalam Proses</label>':'<label class="label label-success">Selesai</label>';
            $row[] = '<div class="center">'.$status_tebus.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Farmasi_pesan_resep->count_all_id($this->input->get('q')),
                        "recordsFiltered" => $this->Farmasi_pesan_resep->count_all_id($this->input->get('q')),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function delete($id='')
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        if($this->Farmasi_pesan_resep->delete_by_id($id)){
            echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function process()
    {
        //print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('tgl_pesan', 'Tanggal Pesan', 'trim|required');
        $val->set_rules('jumlah_r', 'Jumlah R', 'trim|required');
        $val->set_rules('kode_dokter', 'Dokter', 'trim');
        $val->set_rules('lokasi_tebus', 'Lokasi Tebus', 'trim|required');
        
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       

            $this->db->trans_begin();

            $dataexc = array(
                'kode_bagian' => $this->regex->_genRegex('060101', 'RGXQSL'),
                'no_registrasi' => $this->input->post('no_registrasi'),
                'no_kunjungan' => $this->input->post('no_kunjungan'),
                'no_mr' => $this->input->post('no_mr'),
                'kode_perusahaan' => ($this->input->post('kode_perusahaan'))?$this->input->post('kode_perusahaan'):0,
                'kode_kelompok' => $this->input->post('kode_kelompok'),
                'kode_klas' => $this->input->post('kode_klas'),
                'kode_profit' => $this->input->post('kode_profit'),
                'kode_bagian_asal' => ($this->input->post('kode_bagian_asal'))?$this->input->post('kode_bagian_asal'):$this->input->post('kode_bagian_tujuan'),
            );

            //print_r($dataexc);die;
            $id = ($this->input->post('kode_pesan_resep'))?$this->regex->_genRegex($this->input->post('kode_pesan_resep'),'RGXINT'):0;

            if($id==0){
                $kode_pesan_resep = $this->master->get_max_number('fr_tc_pesan_resep', 'kode_pesan_resep');

                $dataexc['kode_pesan_resep'] = $kode_pesan_resep;
                $dataexc['tgl_pesan'] = date("Y-m-d h:i:s");
                $dataexc['kode_dokter'] = $this->regex->_genRegex($val->set_value('kode_dokter'), 'RGXQSL');
                $dataexc['jumlah_r'] = $this->regex->_genRegex($this->input->post('jumlah_r'),'RGXINT');
                $dataexc['lokasi_tebus'] = $this->regex->_genRegex($this->input->post('lokasi_tebus'),'RGXINT');

                /*save post data*/
                $newId = $this->Farmasi_pesan_resep->save('fr_tc_pesan_resep',$dataexc);

                /*save logs*/
                //$this->logs->save('tc_pesanan', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'kode_pesan_resep');
                
            }else{

                $dataexc['tgl_pesan'] = $this->input->post('tgl_pesan_edit');
                $dataexc['kode_dokter'] = $this->regex->_genRegex($this->input->post('kode_dokter_edit'),'RGXQSL');
                $dataexc['jumlah_r'] = $this->regex->_genRegex($this->input->post('jumlah_r_edit'),'RGXINT');
                $dataexc['lokasi_tebus'] = $this->regex->_genRegex($this->input->post('lokasi_tebus_edit'),'RGXINT');
                /*update record*/
                $this->Farmasi_pesan_resep->update('fr_tc_pesan_resep',$dataexc,array('kode_pesan_resep' => $id));
                $newId = $id;
                /*save logs*/
                //$this->logs->save('tc_pesanan', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'kode_pesan_resep');
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $dataexc['no_mr'] ));
            }

        }

    }

    public function get_pesan_resep_by_id()
    {
        # code...
        $id = $this->input->post('id');

        $data =  $this->Farmasi_pesan_resep->get_by_id($id);

        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $data ));
    }


    public function getDetail($id){
        
        $data = $this->Farmasi_pesan_resep->get_detail_by_id($id);

        //print_r($data);die;
        
        $html = '';
        if(count($data) > 0){
            $html .= '<div style="border-bottom:1px solid #333;"><b><h3>Detail Obat</h3></b></div><br>';
            $html .= '<div><b><p>No Transaksi : '.$data[0]->kode_trans_far.'</p><b></div>';
            $html .= '<div><b><p>No Resep : '.$data[0]->no_resep.'</p><b></div>';
            $html .= '<table class="table table-striped">';
            $html .= '<tr>';
                $html .= '<th>Tanggal Transaksi</th>';
                $html .= '<th>Nama Barang</th>';
                $html .= '<th>Status Kasir</th>';
                $html .= '<th class="center">Jumlah</th>';
                $html .= '<th class="center">Total</th>';
            $html .= '</tr>'; 
            $total=0;
            $total_jumlah=0;
            foreach ($data as $value_data) {
                $html .= '<tr>';
                    $html .= '<td>'.$this->tanggal->formatDateTime($value_data->tgl_trans).'</td>';
                    $html .= '<td>'.$value_data->nama_brg.'</td>';
                    $status_trans = ($value_data->kode_tc_trans_kasir==null)?'<label class="label label-yellow">Belum bayar</label>':'<label class="label label-primary">Lunas</label>';
                    $html .= '<td>'.$status_trans.'</td>';
                    $html .= '<td class="center">'.number_format($value_data->jumlah_tebus).'</td>';
                    $harga = $value_data->biaya_tebus + $value_data->harga_r;
                    $html .= '<td class="center">'.number_format($harga).'</td>';
                $html .= '</tr>';
                $total_jumlah += $value_data->jumlah_tebus;
                $total += $harga;
            }
            $html .= '<tr>';
                $html .= '<td colspan="3" style="text-align:center">Grand Total</td>';
                $html .= '<td class="center">'.number_format($total_jumlah).'</td>';
                $html .= '<td class="center">'.number_format($total).'</td>';
            $html .= '</tr>'; 
            $html .= '</table>'; 
        }else{
            $html .= '<div style="border-bottom:1px solid #333;"><b>Belum diproses</b></div><br>';
        }
        


        echo json_encode(array('html' => $html));
    }



}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
