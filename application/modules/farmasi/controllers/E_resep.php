<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class E_resep extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/E_resep');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('E_resep_model', 'E_resep');
        $this->load->model('registration/Reg_klinik_model', 'Reg_klinik');
        $this->load->model('farmasi/Farmasi_pesan_resep_model', 'Farmasi_pesan_resep');
        $this->load->library('Form_validation');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function form($no_registrasi='', $kode_pesan_resep='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'E_resep/'.strtolower(get_class($this)).'/'.__FUNCTION__);
        /*get value by id*/
        $data['value'] = $this->Reg_klinik->get_by_id($no_registrasi); 
        
        /*title header*/
        $data['title'] = $this->title;
        $data['no_mr'] = $_GET['no_mr'];
        // cek existing
        $pesan_resep = $this->db->get_where('fr_tc_pesan_resep', array('kode_pesan_resep' => $kode_pesan_resep))->row();
        $data['pesan_resep'] = isset($pesan_resep) ? $pesan_resep : '';
        // echo "<pre>"; print_r($data);die;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('E_resep/form', $data);
    }

    public function getDetail($id)
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->E_resep->get_cart_detail_by_template_id($id);
        // echo "<pre>"; print_r($list);die;
        $html = '<p style="font-size: 12px; font-weight: bold;"><u>Template Resep</u></p>';
        foreach ($list as $row_list) {

            if($row_list->parent == '0'){
                if($row_list->tipe_obat == 'non_racikan'){
                    $config = array(
                        'nama_obat' => $row_list->nama_brg,
                        'dd' => $row_list->jml_dosis,
                        'qty' => $row_list->jml_dosis_obat,
                        'unit' => $row_list->satuan_obat,
                        'use' => $row_list->aturan_pakai,
                        'jumlah' => $row_list->jml_pesan,
                    );
                    $format_signa = $this->master->formatSignaFull($config);
                    $html .= '<div class="left">'.$format_signa.'<br>Ket : <br>'.$row_list->keterangan.'</div>';
                    $html .= '<a href="#" onclick="clickedititemtemplate('.$row_list->id.')">edit</a> | <a href="#" onclick="deleterowitemtemplate('.$row_list->id.')">delete</a><br><br>';
                }else{
    
                    $format_signa_racikan = '<span class="monotype_style">R/</span><br>';

                    $config = array(
                        'nama_obat' => $row_list->nama_brg,
                        'dd' => $row_list->jml_dosis,
                        'qty' => $row_list->jml_dosis_obat,
                        'unit' => $row_list->satuan_obat,
                        'use' => $row_list->aturan_pakai,
                        'jumlah' => $row_list->jml_pesan,
                    );
                    // komposisi obat racikan
                    $unit_code = $this->master->get_string_data('reff_id', 'global_parameter', array('flag' => 'satuan_obat', 'value' => ucfirst($row_list->satuan_obat)) );

                    $format_signa_racikan .= '<div style="padding-left: 15px">';
                    $format_signa_racikan .= $this->master->get_child_racikan_template($list, $row_list->kode_brg);
                    $format_signa_racikan .= '<i>m.f '.$unit_code.' dtd no. '.$this->master->formatRomawi((int)$row_list->jml_pesan).' da in '.$unit_code.'</i> <br>';
                    $format_signa_racikan .= ''.$this->master->formatSigna($config);
                    $format_signa_racikan .= '</div>';
    
                    $html .= '<div class="left">'.$format_signa_racikan.'<br>Ket : <br>'.$row_list->keterangan.'</div>';
                    $html .= '<a href="#" onclick="clickeditracikanitemtemplate('.$row_list->id.')">edit</a> | <a href="#" onclick="deleterowtemplate('.$row_list->id.')">delete</a><br><br>';
    
                }
            }

        }

        //output to json format
        echo json_encode(array('html' => $html));
    }

    public function get_cart_resep($no_kunjungan)
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->E_resep->get_cart_resep($no_kunjungan);
        $data = array();
        $no = isset($_POST['start'])?$_POST['start']:0;

        foreach ($list as $row_list) {
            $no++;
            $row = array();

            if($row_list->parent == '0'){
                if($row_list->tipe_obat == 'non_racikan'){
                    $config = array(
                        'nama_obat' => $row_list->nama_brg,
                        'dd' => $row_list->jml_dosis,
                        'qty' => $row_list->jml_dosis_obat,
                        'unit' => $row_list->satuan_obat,
                        'use' => $row_list->aturan_pakai,
                        'jumlah' => $row_list->jml_pesan,
                    );
                    $format_signa = $this->master->formatSignaFull($config);
                    $row[] = '<div class="left">'.$format_signa.'<br>Ket : <br>'.$row_list->keterangan.'</div>';
                    $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-warning" onclick="clickedit('.$row_list->id.')"><i class="fa fa-pencil"></i></a><a href="#" class="btn btn-xs btn-danger" onclick="deleterow('.$row_list->id.')"><i class="fa fa-trash"></i></a></div>';
                }else{
                    $format_signa_racikan = '<span class="monotype_style">R/</span><br>';
                    $config = array(
                        'nama_obat' => $row_list->nama_brg,
                        'dd' => $row_list->jml_dosis,
                        'qty' => $row_list->jml_dosis_obat,
                        'unit' => $row_list->satuan_obat,
                        'use' => $row_list->aturan_pakai,
                        'jumlah' => $row_list->jml_pesan,
                    );
                    // komposisi obat racikan
                    $unit_code = $this->master->get_string_data('reff_id', 'global_parameter', array('flag' => 'satuan_obat', 'value' => ucfirst($row_list->satuan_obat)) );

                    $format_signa_racikan .= '<div style="padding-left: 15px">';
                    $format_signa_racikan .= $this->master->get_child_racikan($list, $row_list->kode_brg);
                    $dtd = ($row_list->tipe_racik == 'dtd') ? ' dtd ' : ' ';
                    $format_signa_racikan .= '<i>m.f '.$unit_code.''.$dtd.'no. '.$this->master->formatRomawi((int)$row_list->jml_pesan).' da in '.$unit_code.'</i> <br>';
                    $format_signa_racikan .= ''.$this->master->formatSigna($config);
                    $format_signa_racikan .= '</div>';
    
                    $row[] = '<div class="left">'.$format_signa_racikan.'<br>Ket : <br>'.$row_list->keterangan.'</div>';
                    $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-warning" onclick="clickeditracikan('.$row_list->id.')"><i class="fa fa-pencil"></i></a><a href="#" class="btn btn-xs btn-danger" onclick="deleterow('.$row_list->id.')"><i class="fa fa-trash"></i></a></div>';
    
                }
                $data[] = $row;
            }

        }

        $output = array(
                        "draw" => isset($_POST['draw'])?$_POST['draw']:0,
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_cart_resep_value($kode_pesan_resep)
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->E_resep->get_cart_resep($kode_pesan_resep);
        $data = array();

        foreach ($list as $row_list) {
            $row = array();

            if($row_list->parent == '0'){
                if($row_list->tipe_obat == 'non_racikan'){
                    $config = array(
                        'nama_obat' => $row_list->nama_brg,
                        'dd' => $row_list->jml_dosis,
                        'qty' => $row_list->jml_dosis_obat,
                        'unit' => $row_list->satuan_obat,
                        'use' => $row_list->aturan_pakai,
                        'jumlah' => $row_list->jml_pesan,
                    );
                    $format_signa = $this->master->formatSignaFull($config);
                    $row[] = '<div class="left">'.$format_signa.'<br>Ket : <br>'.$row_list->keterangan.'</div>';
                }else{
                    $format_signa_racikan = '<span class="monotype_style">R/</span><br>';
                    $config = array(
                        'nama_obat' => $row_list->nama_brg,
                        'dd' => $row_list->jml_dosis,
                        'qty' => $row_list->jml_dosis_obat,
                        'unit' => $row_list->satuan_obat,
                        'use' => $row_list->aturan_pakai,
                        'jumlah' => $row_list->jml_pesan,
                    );
                    // komposisi obat racikan
                    $unit_code = $this->master->get_string_data('reff_id', 'global_parameter', array('flag' => 'satuan_obat', 'value' => ucfirst($row_list->satuan_obat)) );

                    $format_signa_racikan .= '<div style="padding-left: 15px">';
                    $format_signa_racikan .= $this->master->get_child_racikan($list, $row_list->kode_brg);
                    $format_signa_racikan .= '<i>m.f '.$unit_code.' dtd no. '.$this->master->formatRomawi((int)$row_list->jml_pesan).' da in '.$unit_code.'</i> <br>';
                    $format_signa_racikan .= ''.$this->master->formatSigna($config);
                    $format_signa_racikan .= '</div>';
    
                    $row[] = '<div class="left">'.$format_signa_racikan.'<br>Ket : <br>'.$row_list->keterangan.'</div>';
    
                }
                
                $child_racikan = $this->master->get_child_racikan_data($kode_pesan_resep, $row_list->kode_brg);
                $html_racikan = ($child_racikan != '') ? '<br><div style="padding:10px"><span style="font-size:11px; font-style: italic">bahan racik :</span><br>'.$child_racikan.'</div>' : '' ;

                $row['nama_brg'] = strtoupper($row_list->nama_brg).''.$html_racikan;
                $row['signa'] = $row_list->jml_dosis.' x '.$row_list->jml_dosis_obat.' '.$row_list->satuan_obat.' '.$row_list->aturan_pakai;
                $row['qty'] = '<div class="center">'. $row_list->jml_pesan.' '.$row_list->satuan_obat.'</div>';
                $row['keterangan'] = $row_list->keterangan;



                $row['kode_brg'] = $row_list->kode_brg;
                $row['id'] = $row_list->id;
                $row['tipe_obat'] = $row_list->tipe_obat;
                $data[] = $row;
            }

        }

        //output to json format
        return $data;
    }


    public function get_cart_resep_data($kode_pesan_resep)
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->E_resep->get_cart_resep($kode_pesan_resep);
        // echo "<pre>";print_r($list);die;
        $data = array();
        $no = isset($_POST['start'])?$_POST['start']:0;

        foreach ($list as $row_list) {
            $no++;
            $row = array();
            // get child racikan
            $child_racikan = $this->master->get_child_racikan_data($kode_pesan_resep, $row_list->kode_brg);
            $html_racikan = ($child_racikan != '') ? '<br><div style="padding:10px"><span style="font-size:11px; font-style: italic">bahan racik :</span><br>'.$child_racikan.'</div>' : '' ;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = strtoupper($row_list->nama_brg).''.$html_racikan;
            $row[] = $row_list->jml_dosis.' x '.$row_list->jml_dosis_obat.' '.$row_list->satuan_obat.' '.$row_list->aturan_pakai;
            $row[] = '<div class="center">'. $row_list->jml_pesan.' '.$row_list->satuan_obat.'</div>';
            $row[] = $row_list->keterangan;
            if($row_list->tipe_obat == 'non_racikan'){
                $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-warning" onclick="clickedit('.$row_list->id.')"><i class="fa fa-pencil"></i></a><a href="#" class="btn btn-xs btn-danger" onclick="deleterow('.$row_list->id.')"><i class="fa fa-trash"></i></a></div>';
            }else{
                $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-warning" onclick="clickeditracikan('.$row_list->id.')"><i class="fa fa-pencil"></i></a><a href="#" class="btn btn-xs btn-danger" onclick="deleterow('.$row_list->id.')"><i class="fa fa-trash"></i></a></div>';

            }
            $data[] = $row;
        }

        $output = array(
                        "draw" => isset($_POST['draw'])?$_POST['draw']:0,
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_template_resep($kode_dokter='')
    {
        /*get data from model*/
        $list = $this->E_resep->get_template_resep($kode_dokter);
        // echo "<pre>"; print_r($list);die;
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $row_list) {
            $no++;
            $row = array();

            $row[] = '<div class="left"></div>';
            $row[] = $row_list->id;
            $row[] = '<div class="left">'.strtoupper($row_list->nama_resep).'</div>';
            $row[] = '<div class="left">'.$row_list->keterangan.'</div>';
            $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-primary" onclick="click_resepkan_template('.$row_list->id.')">Resepkan</a></div>';
            $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-success" onclick="click_edit_template('.$row_list->id.')"><i class="fa fa-pencil"></i></a> <a href="#" class="btn btn-xs btn-danger" onclick="deleterowtemplate('.$row_list->id.')"><i class="fa fa-trash"></i></a></div>';
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    
    public function add_resep_obat(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('kode_pesan_resep', 'kode_pesan_resep', 'trim|required');
        $this->form_validation->set_rules('no_registrasi', 'no_registrasi', 'trim|required');
        $this->form_validation->set_rules('no_kunjungan', 'no_kunjungan', 'trim|required');
        $this->form_validation->set_rules('kode_brg', 'kode_brg', 'trim');
        $this->form_validation->set_rules('nama_brg', 'nama_brg', 'trim|required');
        $this->form_validation->set_rules('jml_dosis', 'jml_dosis', 'trim|required');
        $this->form_validation->set_rules('jml_dosis_obat', 'jml_dosis_obat', 'trim|required');
        $this->form_validation->set_rules('satuan_obat', 'satuan_obat', 'trim|required');
        $this->form_validation->set_rules('aturan_pakai', 'aturan_pakai', 'trim');
        $this->form_validation->set_rules('no_mr', 'no_mr', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim');
        // $this->form_validation->set_rules('jml_hari', 'jml_hari', 'trim|required');
        $this->form_validation->set_rules('jml_pesan', 'jml_pesan', 'trim|required');
        $this->form_validation->set_rules('tipe_obat', 'Tipe Obat', 'trim|required');
        $this->form_validation->set_rules('parent', 'Parent', 'trim|required');
        

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();           
            
            $table = ($_POST['id_template'] > 0)? 'fr_tc_template_resep_detail' : 'fr_tc_pesan_resep_detail';
            $id = ($this->input->post('id_pesan_resep_detail')) ? $this->input->post('id_pesan_resep_detail') : "0";
            
            // print_r($kode_brg);die;

            $dataexc = array(
                'kode_pesan_resep' => $this->regex->_genRegex($this->form_validation->set_value('kode_pesan_resep'), 'RGXINT'),
                'no_registrasi' => $this->regex->_genRegex($this->form_validation->set_value('no_registrasi'), 'RGXINT'),
                'no_kunjungan' => $this->regex->_genRegex($this->form_validation->set_value('no_kunjungan'), 'RGXINT'),
                'nama_brg' => $this->regex->_genRegex($this->form_validation->set_value('nama_brg'), 'RGXQSL'),
                'jml_dosis' => $this->regex->_genRegex($this->form_validation->set_value('jml_dosis'), 'RGXQSL'),
                'jml_dosis_obat' => $this->regex->_genRegex($this->form_validation->set_value('jml_dosis_obat'), 'RGXQSL'),
                'satuan_obat' => $this->regex->_genRegex($this->form_validation->set_value('satuan_obat'), 'RGXQSL'),
                'aturan_pakai' => $this->regex->_genRegex($this->form_validation->set_value('aturan_pakai'), 'RGXQSL'),
                'no_mr' => $this->regex->_genRegex($this->form_validation->set_value('no_mr'), 'RGXQSL'),
                'keterangan' => $this->regex->_genRegex($this->form_validation->set_value('keterangan'), 'RGXQSL'),
                'jml_pesan' => $this->regex->_genRegex($this->form_validation->set_value('jml_pesan'), 'RGXQSL'),
                // 'jml_hari' => $this->regex->_genRegex($this->form_validation->set_value('jml_hari'), 'RGXQSL'),
                'tipe_obat' => $this->regex->_genRegex($this->form_validation->set_value('tipe_obat'), 'RGXQSL'),
                'parent' => $this->regex->_genRegex($this->form_validation->set_value('parent'), 'RGXQSL'),
                'tipe_racik' => isset($_POST['tipe_racik']) ? $this->regex->_genRegex($_POST['tipe_racik'], 'RGXQSL') : '0',
            );

            if( $id == 0 ){
                $kode_brg = ($_POST['submit'] == 'header' && $_POST['tipe_obat'] == 'racikan') ? 'R'.rand(0,999999) : $this->form_validation->set_value('kode_brg'); 
                $dataexc['kode_brg'] = $this->regex->_genRegex($kode_brg, 'RGXQSL');
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $this->db->insert($table, $dataexc);
                $newId = $this->db->insert_id();
                $kode_brg = $kode_brg;
                $flag = 'insert';
            }else{
                $dataexc['kode_brg'] = $this->regex->_genRegex($this->form_validation->set_value('kode_brg'), 'RGXQSL');
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $this->db->where('id', $id)->update($table, $dataexc);
                $newId = $id;
                $kode_brg = $dataexc['kode_brg'];
                $flag = 'update';
            }


            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'newId' => $newId, 'type' => $table, 'parent' => $kode_brg, 'flag' => $flag));
            }

        
        }

    }

    public function getrowresep(){
        $data = $this->db->select('fr_tc_pesan_resep_detail.*, mt_barang.nama_brg as nama_obat')->join('mt_barang', 'mt_barang.kode_brg=fr_tc_pesan_resep_detail.kode_brg', 'LEFT')->get_where('fr_tc_pesan_resep_detail', array('id' => $_GET['ID']))->row();
        echo json_encode($data);
    }

    public function getResepByNoKunjungan($no_kunjungan, $kode_pesan_resep=''){
        $this->db->select('fr_tc_pesan_resep_detail.*, mt_barang.nama_brg as nama_obat');
        $this->db->join('mt_barang', 'mt_barang.kode_brg=fr_tc_pesan_resep_detail.kode_brg', 'LEFT');
        $this->db->from('fr_tc_pesan_resep_detail');
        $this->db->where(array('no_kunjungan' => $no_kunjungan));
        if($kode_pesan_resep != ''){
            $this->db->where(array('kode_pesan_resep' => $kode_pesan_resep));
        }

        $data = $this->db->get()->result();
        $getData = [];
        foreach($data as $row){
            $getData[$row->tipe_obat][] = $row;
            if($row->tipe_obat == 'racikan' && $row->parent == '0'){
                $getData['header_racikan'] = $row;
            }
        }
        return $getData;
    }

    public function getrowitemtemplate(){
        $data = $this->db->get_where('fr_tc_template_resep_detail', array('id' => $_GET['ID']))->row();
        echo json_encode($data);
    }

    public function get_template(){
        $data = $this->db->get_where('fr_tc_template_resep', array('id' => $_GET['ID']))->row();
        echo json_encode($data);
    }

    public function deleterowresep(){

        // get row data
        $row = $this->db->select('fr_tc_pesan_resep_detail.*, mt_barang.nama_brg as nama_obat')->join('mt_barang', 'mt_barang.kode_brg=fr_tc_pesan_resep_detail.kode_brg', 'LEFT')->get_where('fr_tc_pesan_resep_detail', array('id' => $_POST['ID']))->row();
        $this->db->where('parent', $row->kode_brg)->delete('fr_tc_pesan_resep_detail');
        $this->db->where('id', $_POST['ID'])->delete('fr_tc_pesan_resep_detail');
        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
    }

    public function deleterowtemplate(){
        $this->db->where('id_template', $_POST['ID'])->delete('fr_tc_template_resep_detail');
        $this->db->where('id', $_POST['ID'])->delete('fr_tc_template_resep');
        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
    }

    public function deleterowitemtemplate(){
        $this->db->where('id', $_POST['ID'])->delete('fr_tc_template_resep_detail');
        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
    }

    public function form_template_resep($kode_dokter, $kode_pesan_resep){
        $data = array(
            'kode_dokter' => $kode_dokter,
            'kode_pesan_resep' => $kode_pesan_resep,
            'value' => isset($_GET['ID']) ? $this->E_resep->get_row_template($_GET['ID']) : [],
        );
        $this->load->view('E_resep/form_template_resep', $data);
    }

    public function proses_template_resep(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('kode_pesan_resep', 'Kode Pesan Resep', 'trim|required');
        $this->form_validation->set_rules('no_kunjungan', 'Nomor Kunjungan', 'trim|required');
        $this->form_validation->set_rules('kode_dokter', 'Kode Dokter', 'trim|required');
        $this->form_validation->set_rules('nama_resep', 'Nama Resep', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();           
            
            $id = ($this->input->post('id_template')) ? $this->input->post('id_template') : "0";
            $no_kunjungan = ($this->input->post('no_kunjungan')) ? $this->input->post('no_kunjungan') : "0";

            $dataexc = array(
                'kode_dokter' => $this->regex->_genRegex($this->form_validation->set_value('kode_dokter'), 'RGXINT'),
                'nama_resep' => $this->regex->_genRegex($this->form_validation->set_value('nama_resep'), 'RGXQSL'),
                'keterangan' => $this->regex->_genRegex($this->form_validation->set_value('keterangan'), 'RGXQSL'),
                'is_active' => $this->regex->_genRegex('Y', 'RGXQSL'),
            );

            if( $id == 0 ){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $this->db->insert('fr_tc_template_resep', $dataexc);
                $newId = $this->db->insert_id();

                // get list resep
                $list = $this->E_resep->get_cart_resep_for_template($_POST['kode_pesan_resep']);
                // print_r($list);die;

                foreach ($list as $key => $value) {

                    $list_resep[] = array(
                        'id' => $value->id,
                        'kode_pesan_resep' => $value->kode_pesan_resep,
                        'no_registrasi' => $value->no_registrasi,
                        'no_kunjungan' => $value->no_kunjungan,
                        'kode_brg' => $value->kode_brg,
                        'nama_brg' => $value->nama_brg,
                        'jml_dosis' => $value->jml_dosis,
                        'jml_dosis_obat' => $value->jml_dosis_obat,
                        'satuan_obat' => $value->satuan_obat,
                        'aturan_pakai' => $value->aturan_pakai,
                        'no_mr' => $value->no_mr,
                        'keterangan' => $value->keterangan,
                        'jml_pesan' => $value->jml_pesan,
                        'jml_hari' => $value->jml_hari,
                        'tipe_obat' => $value->tipe_obat,
                        'parent' => $value->parent,
                        'id_template' => $newId,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_by' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'),
                        'updated_date' => date('Y-m-d H:i:s'),
                        'updated_by' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'),
                    );

                }

                $this->db->insert_batch('fr_tc_template_resep_detail', $list_resep);
                
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $this->db->where('id', $id)->update('fr_tc_template_resep', $dataexc);
                $newId = $id;
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'newId' => $newId));
            }

        
        }

    }

    public function proses_resepkan_template(){

        // form validation
        $this->form_validation->set_rules('ID', 'ID Template Resep', 'trim|required');
        $this->form_validation->set_rules('no_kunjungan', 'Nomor Kunjungan', 'trim|required');
        $this->form_validation->set_rules('kode_pesan_resep', 'Kode Pesan Resep', 'trim|required');
        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();           
            
            $id_template = ($this->input->post('ID')) ? $this->input->post('ID') : "0";
            $no_kunjungan = ($this->input->post('no_kunjungan')) ? $this->input->post('no_kunjungan') : "0";
            $no_registrasi = ($this->input->post('no_registrasi')) ? $this->input->post('no_registrasi') : "0";
            $kode_pesan_resep = ($this->input->post('kode_pesan_resep')) ? $this->input->post('kode_pesan_resep') : "0";

            // get data template
            $template = $this->E_resep->get_cart_detail_by_template_id($id_template);
            // print_r($_POST);die;
            // print_r($this->db->last_query());die;
            // print_r($template);die;

            foreach ($template as $key => $value) {
                # code...
                $dataexc = array(
                    'kode_pesan_resep' => $this->regex->_genRegex($kode_pesan_resep, 'RGXINT'),
                    'no_registrasi' => $this->regex->_genRegex($no_registrasi, 'RGXINT'),
                    'no_kunjungan' => $this->regex->_genRegex($no_kunjungan, 'RGXINT'),
                    'kode_brg' => $this->regex->_genRegex($value->kode_brg, 'RGXQSL'),
                    'nama_brg' => $this->regex->_genRegex($value->nama_brg, 'RGXQSL'),
                    'jml_dosis' => $this->regex->_genRegex($value->jml_dosis, 'RGXQSL'),
                    'jml_dosis_obat' => $this->regex->_genRegex($value->jml_dosis_obat, 'RGXQSL'),
                    'satuan_obat' => $this->regex->_genRegex($value->satuan_obat, 'RGXQSL'),
                    'aturan_pakai' => $this->regex->_genRegex($value->aturan_pakai, 'RGXQSL'),
                    'no_mr' => $this->regex->_genRegex($_POST['no_mr'], 'RGXQSL'),
                    'keterangan' => $this->regex->_genRegex($value->keterangan, 'RGXQSL'),
                    'jml_pesan' => $this->regex->_genRegex($value->jml_pesan, 'RGXQSL'),
                    'jml_hari' => $this->regex->_genRegex($value->jml_hari, 'RGXQSL'),
                    'tipe_obat' => $this->regex->_genRegex($value->tipe_obat, 'RGXQSL'),
                );

                $dataexc['parent'] = $value->parent;    
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $this->db->insert('fr_tc_pesan_resep_detail', $dataexc);
                $newId = $this->db->insert_id();
            }

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


    public function proses_resepkan_ulang(){

        // echo "<pre>";print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('no_registrasi', 'Nomor Registrasi', 'trim|required');
        $this->form_validation->set_rules('no_kunjungan', 'Nomor Kunjungan', 'trim|required');
        $this->form_validation->set_rules('kode_pesan_resep', 'Kode Pesan Resep', 'trim|required');
        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();           
            $no_kunjungan = ($this->input->post('no_kunjungan')) ? $this->input->post('no_kunjungan') : "0";
            $no_registrasi = ($this->input->post('no_registrasi')) ? $this->input->post('no_registrasi') : "0";
            $no_mr = ($this->input->post('no_mr')) ? $this->input->post('no_mr') : "0";
            $kode_pesan_resep_old = ($this->input->post('kode_pesan_resep')) ? $this->input->post('kode_pesan_resep') : "0";
            $kode_pesan_resep = $this->master->get_max_number('fr_tc_pesan_resep', 'kode_pesan_resep');
            // insert pesan resep baru
            $dataexc = array(
                'kode_bagian' => $this->regex->_genRegex('060101', 'RGXQSL'),
                'no_registrasi' => $no_registrasi,
                'no_kunjungan' => $no_kunjungan,
                'no_mr' => $no_mr,
                'kode_perusahaan' => ($this->input->post('kode_perusahaan'))?$this->input->post('kode_perusahaan'):0,
                'kode_kelompok' => $this->input->post('kode_kelompok'),
                'kode_klas' => $this->input->post('kode_klas'),
                'kode_profit' => $this->input->post('kode_profit')?$this->input->post('kode_profit'):2000,
                'kode_bagian_asal' => ($this->input->post('kode_bagian_asal'))?$this->input->post('kode_bagian_asal'):'',
                'kode_pesan_resep' => $kode_pesan_resep,
                'kode_dokter' => $this->regex->_genRegex($this->input->post('kode_dokter'), 'RGXQSL'),
                'jumlah_r' => $this->regex->_genRegex(1,'RGXINT'),
                'lokasi_tebus' => $this->regex->_genRegex(1,'RGXINT'),
                'tgl_pesan' => date("Y-m-d h:i:s"),
                'created_date' => date('Y-m-d H:i:s'),
                'created_by' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'),
                'e_resep' => 1,
            );
            // print_r($dataexc);die;
            /*save post data*/
            $this->Farmasi_pesan_resep->save('fr_tc_pesan_resep',$dataexc);
            $this->db->trans_commit();

            // get data resep old
            $listresep = $this->E_resep->get_cart_resep_for_template($kode_pesan_resep_old);
            // echo "<pre>"; print_r($listresep);die;

            foreach ($listresep as $key => $value) {
                # code...
                $dataloop = array(
                    'kode_pesan_resep' => $this->regex->_genRegex($kode_pesan_resep, 'RGXINT'),
                    'no_registrasi' => $this->regex->_genRegex($no_registrasi, 'RGXINT'),
                    'no_kunjungan' => $this->regex->_genRegex($no_kunjungan, 'RGXINT'),
                    'kode_brg' => $this->regex->_genRegex($value->kode_brg, 'RGXQSL'),
                    'nama_brg' => $this->regex->_genRegex($value->nama_brg, 'RGXQSL'),
                    'jml_dosis' => $this->regex->_genRegex($value->jml_dosis, 'RGXQSL'),
                    'jml_dosis_obat' => $this->regex->_genRegex($value->jml_dosis_obat, 'RGXQSL'),
                    'satuan_obat' => $this->regex->_genRegex($value->satuan_obat, 'RGXQSL'),
                    'aturan_pakai' => $this->regex->_genRegex($value->aturan_pakai, 'RGXQSL'),
                    'no_mr' => $this->regex->_genRegex($no_mr, 'RGXQSL'),
                    'keterangan' => $this->regex->_genRegex($value->keterangan, 'RGXQSL'),
                    'jml_pesan' => $this->regex->_genRegex($value->jml_pesan, 'RGXQSL'),
                    'jml_hari' => $this->regex->_genRegex($value->jml_hari, 'RGXQSL'),
                    'tipe_obat' => $this->regex->_genRegex($value->tipe_obat, 'RGXQSL'),
                    'parent' => $value->parent,
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'),
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'),
                );
                $this->db->insert('fr_tc_pesan_resep_detail', $dataloop);
                $this->db->trans_commit();

            }


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


    public function proses_resep()
    {
        // print_r($_POST);die;

        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('kode_dokter_poli', 'Dokter', 'trim');
        
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
                'kode_profit' => $this->input->post('kode_profit')?$this->input->post('kode_profit'):2000,
                'kode_bagian_asal' => ($this->input->post('kode_bagian_asal'))?$this->input->post('kode_bagian_asal'):$this->input->post('kode_bagian_tujuan'),
                'e_resep' => 1,
            );

            
            $id = ($this->input->post('kode_pesan_resep_e'))?$this->regex->_genRegex($this->input->post('kode_pesan_resep_e'),'RGXINT'):0;

            if($id==0){
                // log
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                
                $kode_pesan_resep = $this->master->get_max_number('fr_tc_pesan_resep', 'kode_pesan_resep');

                $dataexc['kode_pesan_resep'] = $kode_pesan_resep;
                $dataexc['tgl_pesan'] = date("Y-m-d h:i:s");
                $dataexc['kode_dokter'] = $this->regex->_genRegex($val->set_value('kode_dokter'), 'RGXQSL');
                $dataexc['jumlah_r'] = $this->regex->_genRegex(1,'RGXINT');
                $dataexc['lokasi_tebus'] = $this->regex->_genRegex('RS','RGXQSL');
                // print_r($dataexc);die;
                /*save post data*/
                $this->Farmasi_pesan_resep->save('fr_tc_pesan_resep',$dataexc);
                $newId = $kode_pesan_resep;
                
            }else{

                // log
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Farmasi_pesan_resep->update('fr_tc_pesan_resep',$dataexc,array('kode_pesan_resep' => $id));
                $newId = $id;
            }

            // update eresep
            // $this->db->where('no_kunjungan', $this->input->post('no_kunjungan'))->update('fr_tc_pesan_resep_detail', ['kode_pesan_resep' => $newId]);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $dataexc['no_mr'], 'redirect' => 'farmasi/Entry_resep_ri_rj/form/'.$newId.'?mr='.$dataexc['no_mr'].'&tipe_layanan=RJ', 'kode_pesan_resep' => $newId ));
            }

        }

    }

}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
