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
        $this->load->model('E_resep_model', 'E_resep');
        $this->load->model('ws/AntrianOnlineModel', 'AntrianOnline');

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
        $data['no_kunjungan'] = $no_kunj;
        $data['kode_klas'] = $kode_klas;
        $data['kode_profit'] = $kode_profit;
        $data['kode_bagian_asal'] = isset($_GET['kode_bag'])?$_GET['kode_bag']:'';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo "<pre>";print_r($data);die;
        /*load form view*/
        $this->load->view('Farmasi_pesan_resep/form_pesan_resep', $data);
    }

    public function get_data_by_id()
    {
        /*get data from model*/
        $list = $this->Farmasi_pesan_resep->get_by_no_kunj($this->input->get('q'));
        // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '';
            $btn_delete = ($row_list->status_tebus==null)?'<li><a href="#" id="btn_delete_data" onclick="delete_pesan_resep('.$row_list->kode_pesan_resep.')">Hapus</a></li>':'';

            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                             <li><a href="#" id="btn_edit_data" onclick="showModalEdit('.$row_list->kode_pesan_resep.')">Ubah</a></li>
                            '.$btn_delete.'
                        </ul>
                      </div></div>';

            $jenis_resep = ($row_list->jenis_resep == 'prb') ? '<span class="red">[PRB]</span><br>' : '<span class="green">[NON PRB]</span><br>';
            $row[] = '<div class="center"><b>'.$jenis_resep.'</b>&nbsp;'.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_pesan).'</div>';
            $row[] = ucwords($row_list->nama_bagian).'<br>'.$row_list->nama_pegawai;
            $row[] = $row_list->kode_pesan_resep;
            $row[] = $row_list->no_registrasi;
            $row[] = $row_list->keterangan;
            // $row[] = ($row_list->lokasi_tebus==1)?'Dalam RS':'Luar RS';
            // $row[] = '<div class="center">'.$row_list->jumlah_r.'</div>';
            $status_tebus = ($row_list->status_tebus==null)?'<label class="label label-danger">Dalam Proses</label>':'<label class="label label-success">Selesai diproses</label><br><span>'.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_trans).'</span>';
            $row[] = '<div class="center">'.$status_tebus.'</div>';

            if($row_list->status_tebus == null){
                $lbl_eresep = ($row_list->e_resep == 1) ? '<a href="#" class="label label-xs label-success" onclick="form_eresep('.$row_list->kode_pesan_resep.')"><i class="fa fa-edit"></i> Update Resep</a>' : '<a href="#" class="label label-xs label-primary" onclick="form_eresep('.$row_list->kode_pesan_resep.')"><i class="fa fa-pencil"></i> Input Resep</a>';
            }else{
                $lbl_eresep = '<i class="fa fa-check bigger-180 green"></i>';

            }

            $row[] = '<div class="center">'.$lbl_eresep.'</div>';
           
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

    public function get_data_by_mr()
    {
        /*get data from model*/
        $list = $this->Farmasi_pesan_resep->get_by_no_mr($this->input->get('no_mr'));
        // print_r($this->db->last_query());
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '';
            $btn_delete = ($row_list->status_tebus==null)?'<li><a href="#" id="btn_delete_data" onclick="delete_pesan_resep('.$row_list->kode_pesan_resep.')">Hapus</a></li>':'';

            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                             <li><a href="#" id="btn_edit_data" onclick="showModalEdit('.$row_list->kode_pesan_resep.')">Ubah</a></li>
                            '.$btn_delete.'
                        </ul>
                      </div></div>';

            $jenis_resep = ($row_list->jenis_resep == 'prb') ? '<span class="red">[PRB]</span><br>' : '<span class="green">[NON PRB]</span><br>';
            $row[] = '<div class="center"><b>'.$jenis_resep.'</b>&nbsp;'.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_pesan).'</div>';
            $row[] = ucwords($row_list->nama_bagian).'<br>'.$row_list->nama_pegawai;
            $row[] = $row_list->kode_pesan_resep;
            $row[] = $row_list->keterangan;
            // $row[] = ($row_list->lokasi_tebus==1)?'Dalam RS':'Luar RS';
            // $row[] = '<div class="center">'.$row_list->jumlah_r.'</div>';
            $status_tebus = ($row_list->status_tebus==null)?'<label class="label label-danger">Dalam Proses</label>':'<label class="label label-success">Selesai diproses</label><br><span>'.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_trans).'</span>';
            $row[] = '<div class="center">'.$status_tebus.'</div>';

            if($row_list->status_tebus == null){
                $lbl_eresep = ($row_list->e_resep == 1) ? '<a href="#" class="label label-xs label-success" onclick="form_eresep('.$row_list->kode_pesan_resep.')"><i class="fa fa-edit"></i> Update Resep</a>' : '<a href="#" class="label label-xs label-primary" onclick="form_eresep('.$row_list->kode_pesan_resep.')"><i class="fa fa-pencil"></i> Input Resep</a>';
            }else{
                $lbl_eresep = '<i class="fa fa-check bigger-180 green"></i>';

            }

            $row[] = '<div class="center">'.$lbl_eresep.'</div>';
           
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
        // print_r($_POST);die;

        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('tgl_pesan', 'Tanggal Pesan', 'trim');
        $val->set_rules('jumlah_r', 'Jumlah R', 'trim|required');
        $val->set_rules('kode_dokter', 'Dokter', 'trim');
        $val->set_rules('lokasi_tebus', 'Lokasi Tebus', 'trim|required');
        $val->set_rules('jenis_resep', 'Jenis Resep', 'trim');
        
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
                'jenis_resep' => $this->input->post('jenis_resep'),
                'kode_bagian_asal' => ($this->input->post('kode_bagian_asal'))?$this->input->post('kode_bagian_asal'):$this->input->post('kode_bagian_tujuan'),
                'keterangan' => ($this->input->post('keterangan_pesan_resep'))?$this->input->post('keterangan_pesan_resep'):'',
            );

            
            $id = ($this->input->post('kode_pesan_resep'))?$this->regex->_genRegex($this->input->post('kode_pesan_resep'),'RGXINT'):0;

            if($id==0){
                // log
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                
                $kode_pesan_resep = $this->master->get_max_number('fr_tc_pesan_resep', 'kode_pesan_resep');

                $dataexc['kode_pesan_resep'] = $kode_pesan_resep;
                $dataexc['tgl_pesan'] = date("Y-m-d h:i:s");
                $dataexc['kode_dokter'] = $this->regex->_genRegex($val->set_value('kode_dokter'), 'RGXQSL');
                $dataexc['jumlah_r'] = $this->regex->_genRegex($this->input->post('jumlah_r'),'RGXINT');
                $dataexc['lokasi_tebus'] = $this->regex->_genRegex($this->input->post('lokasi_tebus'),'RGXINT');
                // print_r($dataexc);die;
                /*save post data*/
                $this->Farmasi_pesan_resep->save('fr_tc_pesan_resep',$dataexc);
                $newId = $kode_pesan_resep;

                /*save logs*/
                $this->logs->save('fr_tc_pesan_resep', $kode_pesan_resep, 'update record on '.$this->title.' module', json_encode($dataexc),'kode_pesan_resep');
                
                
            }else{

                // log
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Farmasi_pesan_resep->update('fr_tc_pesan_resep',$dataexc,array('kode_pesan_resep' => $id));
                $newId = $id;
                /*save logs*/
                $this->logs->save('fr_tc_pesan_resep', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'kode_pesan_resep');
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $dataexc['no_mr'], 'redirect' => 'farmasi/Entry_resep_ri_rj/form/'.$newId.'?mr='.$dataexc['no_mr'].'&tipe_layanan=RJ', 'type_pelayanan' => 'eresep' ));
            }

        }

    }

    public function get_pesan_resep_by_id()
    {
        # code...
        $id = $this->input->post('id');

        $data =  $this->Farmasi_pesan_resep->get_by_id($id);
        // print_r($data);die;
        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $data ));
    }


    public function getDetail($kode_pesan_resep, $no_registrasi=''){
        
        $data = $this->Farmasi_pesan_resep->get_detail_by_id($kode_pesan_resep);
        $list = $this->E_resep->get_cart_resep($kode_pesan_resep, $no_registrasi);

        // echo "<pre>";print_r($list);die;
        
        $html = '';
        
        $html .= '<hr>';
        $html .= '<div style="padding: 10px">';
        if(count($list) > 0){
            $html .= '<div style="border-bottom:1px solid #333; font-size: 16px"><b><span>Data e-Resep ['.$kode_pesan_resep.']</span></b></div><br>';
            $html .= '<table class="table" id="dt_add_resep_obat">
            <thead>
            <tr>
                <th width="30px">No</th>
                <th>Nama Obat</th>
                <th width="180px">Signa</th>
                <th width="60px">Qty</th>
                <th width="200px">Keterangan</th>
                <th width="200px">Dibuat oleh/Waktu</th>
                <th width="80px" style="text-align: center">Status Tebus</th>
                <th width="80px">Verifikasi</th>
                <th width="80px">Catatan Verifikasi</th>
            </tr>
            </thead>
            <tbody>';
            $no = 0;
            foreach ($list as $row_list) {
                $no++;
                // get child racikan
                $child_racikan = $this->master->get_child_racikan_data($kode_pesan_resep, $row_list->kode_brg);
                $html_racikan = ($child_racikan != '') ? '<br><div style="padding:10px"><span style="font-size:11px; font-style: italic">bahan racik :</span><br>'.$child_racikan.'</div>' : '' ;
                $html .= '<tr>';
                $html .= '<td align="center">'.$no.'</td>';
                $html .= '<td>'.strtoupper($row_list->nama_brg).''.$html_racikan.'</td>';
                $html .= '<td>'.$row_list->jml_dosis.' x '.$row_list->jml_dosis_obat.' '.$row_list->satuan_obat.' '.$row_list->aturan_pakai.'</td>';
                $html .= '<td>'.$row_list->jml_pesan.' '.$row_list->satuan_obat.'</td>';
                $html .= '<td>'.$row_list->keterangan.'</td>';
                $html .= '<td><span style="font-size: 11px">'.$row_list->updated_by.'<br>'.$this->tanggal->formatDateTimeFormDmy($row_list->updated_date).'</td>';
                if($row_list->kode_brg == $row_list->kode_brg_fr){
                    $status_tebus = '<i class="fa fa-check green bigger-120"></i>';
                }else{
                    $status_tebus = '<i class="fa fa-times red bigger-120"></i>';
                }
                $html .= '<td class="center">'.$status_tebus.'</td>';
                $verifikasi_apotik_online = ($row_list->verifikasi_apotik_online ==  1)?'checked':'';
                $html .= '<td>
                            <div class="center">
                                <label>
                                    <input name="switch-field-1" class="ace ace-switch" id="status_verif_'.$row_list->id.'" onchange="udpateStatusVerifperItem('.$row_list->id.')" type="checkbox" value="1" '.$verifikasi_apotik_online.'>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                        </td>';
                        $html .= '<td>
                            <div class="center">
                                <input name="catatan_verif_'.$row_list->id.'" class="form-control" onchange="saveCatatanVerif('.$row_list->id.')" id="catatan_verif_'.$row_list->id.'" type="text" value="'.$row_list->catatan_verifikasi.'">
                            </div>
                        </td>';
                $html .= '</tr>';
            }

            $html .= '</tbody></table>';
            
        }else{
            $html .= '<div style="border-bottom:1px solid #333; color: red"><b>Belum ada resep yang diinput oleh dokter</b></div><br>';
        }
        $html .= '</div>';

        $html .= '<div style="padding: 10px">';
        if(count($data) > 0){
            $html .= '<div style="border-bottom:1px solid #333; font-size: 16px"><b><span>Obat yang diberikan farmasi/ditebus ['.$data[0]->kode_trans_far.']</span></b></div><br>';
            $html .= '<div><b><p>No Resep : '.$data[0]->no_resep.'</p><b></div>';
            $html .= '<table class="table table-striped">';
            $html .= '<tr style="background-color:rgb(202, 201, 201)">';
                $html .= '<th class="center" style="vertical-align: middle" rowspan="2" width="150px">Tanggal Transaksi</th>';
                $html .= '<th class="center" style="vertical-align: middle" rowspan="2">Nama Barang</th>';
                $html .= '<th class="center" style="vertical-align: middle" rowspan="2" width="80px">Status Kasir</th>';
                $html .= '<th width="80px" colspan="2" class="center">Jumlah Obat</th>';
                $html .= '<th rowspan="2" class="center" style="vertical-align: middle" width="100px">Total Obat</th>';
                $html .= '<th rowspan="2" class="center" style="vertical-align: middle" width="100px">Harga Satuan</th>';
                $html .= '<th rowspan="2" class="center" style="vertical-align: middle" width="100px">Total (Rp)</th>';
                $html .= '</tr>';
            $html .= '<tr style="background-color:rgb(202, 201, 201)">';
                $html .= '<th class="center" width="80px">Non Kronis</th>';
                $html .= '<th class="center" width="100px">Kronis</th>';
            $html .= '</tr>'; 
            $total=0;
            $total_jumlah=0;
            foreach ($data as $value_data) {
                $html .= '<tr>';
                    $html .= '<td>'.$this->tanggal->formatDateTimeFormDmy($value_data->tgl_trans).'</td>';
                    $html .= '<td>'.$value_data->nama_brg.'</td>';
                    $status_trans = ($value_data->kode_tc_trans_kasir==null)?'<label class="label label-yellow">Belum bayar</label>':'<label class="label label-primary">Lunas</label>';
                    $html .= '<td>'.$status_trans.'</td>';
                    $html .= '<td class="center">'.number_format($value_data->jumlah_tebus).'</td>';
                    $html .= '<td class="center">'.number_format($value_data->jumlah_obat_23).'</td>';
                    $jml_obat = $value_data->jumlah_tebus + $value_data->jumlah_obat_23;
                    $html .= '<td align="center">'.number_format($jml_obat).'</td>';
                    $harga_jual = $value_data->harga_jual;  
                    $html .= '<td align="right">'.number_format($harga_jual).'</td>';
                    $harga = ($harga_jual * $jml_obat) + $value_data->harga_r;
                    $html .= '<td align="right">'.number_format($harga).'</td>';
                $html .= '</tr>';
                $total += $harga;
            }
            $html .= '<tr>';
                $html .= '<td colspan="7" style="text-align:right; font-weight: bold">GRAND TOTAL</td>';
                $html .= '<td align="right" style="font-weight: bold">'.number_format($total).'</td>';
            $html .= '</tr>'; 
            $html .= '</table>'; 
        }else{
            $html .= '<div style="border-bottom:1px solid #333; color: red"><b>Resep belum diproses oleh apotik</b></div><br>';
        }
        $html .= '</div>';
        


        echo json_encode(array('html' => $html));
    }



}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
