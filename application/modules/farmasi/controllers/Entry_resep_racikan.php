<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Entry_resep_racikan extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Entry_resep_racikan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Entry_resep_racikan_model', 'Entry_resep_racikan');
        $this->load->model('Entry_resep_ri_rj_model', 'Entry_resep_ri_rj');
        $this->load->model('Process_entry_resep_model', 'Process_entry_resep');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'Entry_resep_racikan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/

        $data['kode_pesan_resep'] = $id;
        $data['value_header'] = $this->Entry_resep_ri_rj->get_by_id($id);

        $data['value'] = $this->db->get_where('tc_far_racikan', array('kode_pesan_resep' => $id))->result();
        $data['kode_kelompok'] = $_GET['kelompok'];
        $data['kode_pesan_resep'] = $id;
        /*get fr_tc_far*/
        $tc_far = $this->db->get_where('fr_tc_far', array('kode_pesan_resep' => $id) );
        $data['kode_trans_far'] = ($tc_far->num_rows() > 0) ? $tc_far->row()->kode_trans_far : 0 ;
        // echo '<pre>';print_r($data);die;

        /*initialize flag for form*/
        $data['tipe_layanan'] = $_GET['tipe_layanan'];
        $data['str_tipe_layanan'] = ($_GET['tipe_layanan']=='RJ')?'Rajal':'Ranap';
        
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Entry_resep_racikan/form', $data);
    }

    public function getDetail($id){
        
        $data = $this->Entry_resep_racikan->get_detail_by_id($id);

        // print_r($data);die;
        
        $html = '';
        if(count($data) > 0){
            $html .= '<div style="border-bottom:1px solid #333;"><b><h4>'.strtoupper($data[0]->nama_racikan).' ('.$data[0]->id_tc_far_racikan.') </h4></b></div><br>';

            $html .= '<table class="table table-striped">';
            $html .= '<tr>';
                $html .= '<th>Kode</th>';
                $html .= '<th>Nama Obat</th>';
                $html .= '<th class="center">Jumlah</th>';
                $html .= '<th>Satuan</th>';
                $html .= '<th>Harga Satuan</th>';
                $html .= '<th class="center">Total</th>';
            $html .= '</tr>'; 
            $total=0;
            $total_jumlah=0;
            foreach ($data as $value_data) {
                $html .= '<tr>';
                    $html .= '<td>'.$value_data->kode_brg.'</td>';
                    $html .= '<td>'.$value_data->nama_brg.'</td>';
                    $html .= '<td align="center">'.number_format($value_data->jumlah).'</td>';
                    $html .= '<td>'.$value_data->satuan.'</td>';
                    $html .= '<td align="right">'.number_format($value_data->harga_jual).'</td>';
                    $html .= '<td align="right">'.number_format($value_data->jumlah_total).'</td>';
                $html .= '</tr>';
                $total_jumlah += $value_data->jumlah_total;
            }

            /*total harga*/
            $harga_total = $total_jumlah;
            $jasa_r = $data[0]->jasa_r + $data[0]->jasa_produksi;
            $total = $harga_total + $jasa_r;
            $html .= '<tr>';
                $html .= '<td colspan="5" align="right">Sub Total</td>';
                $html .= '<td align="right">'.number_format($harga_total).'</td>';
            $html .= '</tr>'; 
            $html .= '<tr>';
                $html .= '<td colspan="5" align="right">Jasa R</td>';
                $html .= '<td align="right">'.number_format($jasa_r).'</td>';
            $html .= '</tr>'; 
            $html .= '<tr>';
                $html .= '<td colspan="5" align="right"><b>Total Harga</b></td>';
                $html .= '<td align="right"><b>'.number_format($total).'</b></td>';
            $html .= '</tr>'; 
            
            $html .= '</table>'; 
        }else{
            $html .= '<div style="border-bottom:1px solid #333;"><b>Belum diproses</b></div><br>';
        }

        echo json_encode(array('html' => $html));

    }

    public function get_data()
    {
        /*get data from model*/
        $data = array();
        $no = $_POST['start'];
        
        if( $_GET['id'] != 0 ){
            $list = $this->Entry_resep_racikan->get_datatables();
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center">
                                <label class="pos-rel">
                                    <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_far_racikan_detail.'"/>
                                    <span class="lbl"></span>
                                </label>
                              </div>';
                    $row[] = '<div class="center">
                                <a href="#" onclick="delete_item_obat_racikan('.$row_list->id_tc_far_racikan_detail.', '.$row_list->id_tc_far_racikan.')" title="delete"><i class="fa fa-times-circle red bigger-150"></i></a>
                              </div>';
                $row[] = $row_list->id_tc_far_racikan_detail;
                $row[] = $row_list->kode_brg;
                $row[] = strtoupper($row_list->nama_brg);
                $row[] = '<div align="right">'.$row_list->jumlah.'</div>';
                $row[] = '<div align="left">'.$row_list->satuan.'</div>';
                $row[] = '<div align="right">'.number_format($row_list->harga_jual).'</div>';
                $total = $row_list->harga_jual * $row_list->jumlah;
                $row[] = '<div align="right">'.number_format($total).'</div>';
                
                $data[] = $row;
            }

            $recordsTotal = $this->Entry_resep_racikan->count_all();
            $recordsFiltered = $this->Entry_resep_racikan->count_filtered();

        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => isset($recordsTotal)?$recordsTotal:0,
                        "recordsFiltered" => isset($recordsFiltered)?$recordsFiltered:0,
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        // form validation
        $this->form_validation->set_rules('nama_racikan', 'Nama Racikan', 'trim|required');
        $this->form_validation->set_rules('satuan_racikan', 'Satuan', 'trim|required');
        $this->form_validation->set_rules('jml_racikan', 'Jumlah Pesan', 'trim|required');
        $this->form_validation->set_rules('nama_tindakan', 'Nama Obat', 'trim');
        $this->form_validation->set_rules('jumlah_pesan_racikan', 'Jumlah', 'trim');

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

                /*entry tc_far_racikan*/

                if($_POST['submit'] == 'header'){

                    $data_racikan = array(
                        'nama_racikan' => $this->regex->_genRegex(strtoupper($this->form_validation->set_value('nama_racikan')), 'RGXQSL'),
                        'jml_content' => $this->regex->_genRegex($this->form_validation->set_value('jml_racikan'), 'RGXINT'),
                        'satuan_kecil' => $this->regex->_genRegex($this->form_validation->set_value('satuan_racikan'), 'RGXQSL'),
                        'jasa_r' => $this->regex->_genRegex($_POST['jasa_r_racikan'], 'RGXINT'),
                        'jasa_produksi' => $this->regex->_genRegex($_POST['jasa_prod_racikan'], 'RGXINT'),
                        'tgl_input' => date('Y-m-d H:i:s'),
                        'user_id' => $this->session->userdata('user')->user_id,
                        'kode_pesan_resep' => $this->regex->_genRegex($_POST['kode_pesan_resep'], 'RGXINT'),
                    );
                    
                    // jika belum ada transaksi
                    if( $_POST['kode_trans_far'] == 0 ){

                        $data_farmasi = array(
                            'kode_pesan_resep' => isset($_POST['no_resep'])?$this->regex->_genRegex($_POST['no_resep'], 'RGXINT'):0,
                            'no_resep' => $this->Process_entry_resep->format_no_resep($_POST['flag_trans'], $_POST['kode_profit']),
                            'kode_profit' => $this->regex->_genRegex($_POST['kode_profit'], 'RGXINT'),
                            'kode_bagian' => $this->regex->_genRegex($_POST['kode_bagian'], 'RGXQSL'),
                            'tgl_trans' => date('Y-m-d H:i:s'),
                            'kode_bagian_asal' => $this->regex->_genRegex($_POST['kode_bagian_asal'], 'RGXQSL'),
                            'no_mr' => $this->regex->_genRegex($_POST['no_mr'], 'RGXQSL'),
                            'no_registrasi' => $this->regex->_genRegex($_POST['no_registrasi'], 'RGXINT'),
                            'no_kunjungan' => $this->regex->_genRegex($_POST['no_kunjungan'], 'RGXINT'),
                            'kode_dokter' => $this->regex->_genRegex($_POST['kode_dokter'], 'RGXINT'),
                            'dokter_pengirim' => $this->regex->_genRegex($_POST['dokter_pengirim'], 'RGXQSL'),
                            'nama_pasien' => $this->regex->_genRegex(substr($_POST['nama_pasien'], 1,20), 'RGXQSL'),
                            'flag_trans' => $this->regex->_genRegex($_POST['flag_trans'], 'RGXAZ'),
                        );
                        $kode_trans_far = $this->master->get_max_number('fr_tc_far', 'kode_trans_far', array('kode_bagian' => $_POST['kode_bagian'] ));
                        /*update existing*/
                        $data_farmasi['kode_trans_far'] = $kode_trans_far;
                        $data_farmasi['created_date'] = date('Y-m-d H:i:s');
                        $data_farmasi['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                        // print_r($data_farmasi);die;
                        $this->db->insert( 'fr_tc_far', $data_farmasi );
                        /*save log*/
                        $this->logs->save('fr_tc_far', $kode_trans_far, 'insert new record on entry resep module', json_encode($data_farmasi), 'kode_trans_far');

                        $data_racikan['kode_trans_far'] = $kode_trans_far;
                    }else{
                        $data_racikan['kode_trans_far'] = $_POST['kode_trans_far'];
                    }

                    if($_POST['id_tc_far_racikan']==0){
                        $data_racikan['created_date'] = date('Y-m-d H:i:s');
                        $data_racikan['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));

                        $this->db->insert('tc_far_racikan', $data_racikan);
                        $new_id_tc_far_racikan = $this->db->insert_id();
                         /*save log*/
                        $this->logs->save('tc_far_racikan', $new_id_tc_far_racikan, 'insert new record on entry resep racikan module', json_encode($data_racikan),'id_tc_far_racikan');                        

                    }else{
                        $data_racikan['updated_date'] = date('Y-m-d H:i:s');
                        $data_racikan['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                        $new_id_tc_far_racikan = $_POST['id_tc_far_racikan'];
                        
                        $this->db->update('tc_far_racikan', $data_racikan, array('id_tc_far_racikan' => $_POST['id_tc_far_racikan']) );
                         /*save log*/
                        $this->logs->save('tc_far_racikan', $new_id_tc_far_racikan, 'update record on entry resep racikan module', json_encode($data_racikan),'id_tc_far_racikan');
                        
                    }

                    $id_tc_far_racikan = $new_id_tc_far_racikan;

                }

                if($_POST['submit'] == 'detail'){
                    /*entry detail obat*/
                    $id_tc_far_racikan = $_POST['id_tc_far_racikan'];
                    $jumlah_total = $_POST['jumlah_pesan_racikan'] * $_POST['pl_harga_satuan'] ;
                    $obat_detail = array(
                        'id_tc_far_racikan' => $this->regex->_genRegex($id_tc_far_racikan, 'RGXINT'),
                        'id_obat' => $this->regex->_genRegex(0, 'RGXQSL'),
                        'kode_brg' => $this->regex->_genRegex($_POST['kode_brg'], 'RGXQSL'),
                        'nama_brg' => $this->regex->_genRegex($_POST['nama_tindakan'], 'RGXQSL'),
                        'jumlah' => $this->regex->_genRegex($_POST['jumlah_pesan_racikan'], 'RGXINT'),
                        'satuan' => $this->regex->_genRegex($_POST['pl_satuan_kecil'], 'RGXQSL'),
                        'harga_beli' => $this->regex->_genRegex($_POST['pl_harga_beli'], 'RGXINT'),
                        'jumlah_total' => $this->regex->_genRegex($jumlah_total, 'RGXINT'),
                        'harga_jual' => $this->regex->_genRegex($_POST['pl_harga_satuan'], 'RGXINT'),
                        );

                    if($_POST['id_tc_far_racikan_detail']==0){
                        $obat_detail['created_date'] = date('Y-m-d H:i:s');
                        $obat_detail['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                        //print_r($obat_detail);die;
                        $this->db->insert('tc_far_racikan_detail', $obat_detail);
                        $new_id = $this->db->insert_id();
                         /*save log*/
                        $this->logs->save('tc_far_racikan_detail', $new_id, 'insert new record on entry resep racikan detail module', json_encode($obat_detail),'id_tc_far_racikan_detail');
                        

                    }else{
                        $obat_detail['updated_date'] = date('Y-m-d H:i:s');
                        $obat_detail['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));

                        $this->db->update('tc_far_racikan_detail', $obat_detail, array('id_tc_far_racikan_detail' => $_POST['id_tc_far_racikan_detail']) );
                        $new_id = $_POST['id_tc_far_racikan_detail'];
                         /*save log*/
                        $this->logs->save('tc_far_racikan_detail', $new_id, 'update record on entry resep racikan detail module', json_encode($obat_detail),'id_tc_far_racikan');           
                    }

                }
                
                /*update billing tc_far_racikan*/
                $result_racikan = $this->Entry_resep_racikan->update_bill_tc_far_racikan($id_tc_far_racikan);

                /*save to fr_tc_far*/
                $post_data = array(
                    'jumlah_tebus' => $result_racikan->jml_content,
                    'jumlah_pesan' => $result_racikan->jml_content,
                    'sisa' => 0,
                    'kode_brg' => $result_racikan->id_tc_far_racikan,
                    'harga_beli' => $result_racikan->harga_beli,
                    'harga_jual' => $result_racikan->harga_jual,
                    'harga_r' => $result_racikan->jasa_r,
                    'biaya_tebus' => $result_racikan->sub_total,
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'urgensi' => '',
                    'kode_trans_far' => $result_racikan->kode_trans_far,
                    'id_tc_far_racikan' => $result_racikan->id_tc_far_racikan,
                );

                $this->Entry_resep_racikan->save_fr_tc_far($post_data); 

                /*save log*/
                /*total biaya*/
                $data_racikan_log = array(
                    'kode_trans_far' => $result_racikan->kode_trans_far,
                    'kode_pesan_resep' => $result_racikan->kode_pesan_resep,
                    'kode_brg' => $id_tc_far_racikan,
                    'nama_brg' => $result_racikan->nama_racikan,
                    'satuan_kecil' => $result_racikan->satuan_kecil,
                    'jumlah_pesan' => $result_racikan->jml_content,
                    'jumlah_tebus' => $result_racikan->jml_content,
                    'harga_jual' => $result_racikan->harga_jual,
                    'sub_total' => $result_racikan->sub_total,
                    'harga_r' => $result_racikan->jasa_r + $result_racikan->jasa_produksi,
                    'urgensi' =>'biasa',
                    'flag_resep' => 'racikan',
                    'relation_id' => $id_tc_far_racikan,
                );

                $this->Process_entry_resep->save_log_detail($data_racikan_log, $id_tc_far_racikan);                

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                $val_data_racikan = $this->db->get_where('tc_far_racikan', array('id_tc_far_racikan' => $id_tc_far_racikan) )->row();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $val_data_racikan, 'id_tc_far_racikan' => $id_tc_far_racikan ));
            }
        
        }

    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_resep_racikan_by_id($id_tc_far_racikan){
        $result = $this->db->get_where('tc_far_racikan', array('id_tc_far_racikan' => $id_tc_far_racikan))->row();
        echo json_encode($result);
    }

    public function get_item_racikan($kode_pesan_resep)
    {
        $query = "select id_tc_far_racikan, nama_racikan
                    from tc_far_racikan where kode_pesan_resep=".$kode_pesan_resep." order by nama_racikan ASC";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
    }

    public function delete_obat()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Entry_resep_racikan->delete_item_obat($toArray)){
                
                $result_racikan = $this->Entry_resep_racikan->update_bill_tc_far_racikan($_POST['id_tc_far_racikan']);
                /*save log*/
                /*total biaya*/
                $data_racikan_log = array(
                    'kode_trans_far' => $result_racikan->kode_trans_far,
                    'kode_pesan_resep' => $result_racikan->kode_pesan_resep,
                    'kode_brg' => $_POST['id_tc_far_racikan'],
                    'nama_brg' => $result_racikan->nama_racikan,
                    'satuan_kecil' => $result_racikan->satuan_kecil,
                    'jumlah_pesan' => $result_racikan->jml_content,
                    'jumlah_tebus' => $result_racikan->jml_content,
                    'harga_jual' => $result_racikan->harga_jual,
                    'sub_total' => $result_racikan->sub_total,
                    'harga_r' => $result_racikan->jasa_r + $result_racikan->jasa_produksi,
                    'urgensi' =>'biasa',
                    'flag_resep' => 'racikan',
                    'relation_id' => $_POST['id_tc_far_racikan'],
                );

                $this->Process_entry_resep->save_log_detail($data_racikan_log, $_POST['id_tc_far_racikan']); 

                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function process_selesai_racikan()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->Entry_resep_racikan->process_selesai_racikan($id)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }



}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
