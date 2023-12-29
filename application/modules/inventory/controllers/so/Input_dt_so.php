<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Input_dt_so extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'inventory/so/Input_dt_so');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('inventory/so/Input_dt_so_model', 'Input_dt_so');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';
        $this->agenda_so_id = ($this->session->userdata('session_input_so')['agenda_so_id'])?$this->session->userdata('session_input_so')['agenda_so_id']:'';

    }

    public function index() { 

        /*define variable data*/
        //echo '<pre>';print_r($this->session->userdata('session_input_so'));die;
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        if( $this->session->userdata('session_input_so')){
            echo '<script>getMenu('."'".base_url().'inventory/so/Input_dt_so/form_input_dt_so/'.$this->session->userdata('session_input_so')['bagian'].''."'".')</script>';
        }
        /*load view index*/
        $this->load->view('so/Input_dt_so/index', $data);
    }

    public function form_input_dt_so($kode_bag='')
    {
        // echo '<pre>';print_r($this->session->userdata('session_input_so'));
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Create_agenda_so/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_bag);
        /*get value by id*/
        $data['value'] = $this->Input_dt_so->get_agenda_by_id($this->session->userdata('session_input_so')['agenda_so_id']);
        //echo '<pre>';print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('so/Input_dt_so/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        if($_GET['bag']=='070101'){
            $list = $this->Input_dt_so->get_datatables_nm();
            $recordsTotal = $this->Input_dt_so->count_all_nm();
            $recordsFiltered = $this->Input_dt_so->count_filtered_nm();
        }else{
            $list = $this->Input_dt_so->get_datatables();
            $recordsTotal = $this->Input_dt_so->count_all();
            $recordsFiltered = $this->Input_dt_so->count_filtered();
        }

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $link_image = ( $row_list->path_image != NULL ) ? PATH_IMG_MST_BRG.$row_list->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ;
            $row[] = '<div class="center"><a href="'.base_url().$link_image.'" target="_blank"><img src="'.base_url().$link_image.'" width="100px"></a></div>';
            $row[] = '<b>'.$row_list->kode_brg.'</b><br>'.ucwords($row_list->nama_brg);
            $satuan = ($row_list->satuan_kecil==$row_list->satuan_besar)?$row_list->satuan_kecil:$row_list->satuan_kecil.'/'.$row_list->satuan_besar;
            $cutt_off_stock = ($row_list->stok_sebelum)?$row_list->stok_sebelum:$row_list->jml_sat_kcl;
            $row[] = '<div class="center">'.$row_list->satuan_kecil.'</div>';
            $row[] = '<div class="center" style="color: blue; font-size: 14px; font-weight: bold">'.$cutt_off_stock.'</div>';
            $row[] = '<div class="center" style="color: green; font-size: 14px; font-weight: bold">'.$row_list->jml_sat_kcl.'</div>';
            $row[] = '<div class="center"><input type="text" name="stok_kartu" id="row_'.$row_list->kode_brg.'_'.$row_list->kode_brg.'_'.$this->agenda_so_id.'" style="width:80px !important; text-align: center; background-color: #1f1fff61 ; font-weight: bold; font-color: #000000c4;" value="'.$row_list->stok_sekarang.'" onchange="updateRow('."'".$row_list->kode_brg."'".', '."'".$row_list->kode_bagian."'".','.$this->agenda_so_id.')"></div>';

            $row[] = '<div class="center"><input type="text" name="stok_will_exp" id="row_will_exp_'.$row_list->kode_brg.'_'.$row_list->kode_brg.'_'.$this->agenda_so_id.'" style="width:80px !important; text-align: center; background-color: #ffe00069 ; font-weight: bold; font-color: #000000c4;" value="'.$row_list->will_stok_exp.'" onchange="updateRow('."'".$row_list->kode_brg."'".', '."'".$row_list->kode_bagian."'".','.$this->agenda_so_id.')"></div>';
            
            $row[] = '<div class="center"><input type="text" name="stok_exo" id="row_exp_'.$row_list->kode_brg.'_'.$row_list->kode_brg.'_'.$this->agenda_so_id.'" style="width:80px !important; text-align: center; background-color: #ff00004f ; font-weight: bold; font-color: #000000c4;" value="'.$row_list->stok_exp.'" onchange="updateRow('."'".$row_list->kode_brg."'".', '."'".$row_list->kode_bagian."'".','.$this->agenda_so_id.')"></div>';
            
            $value_brg_aktif = $row_list->status_aktif;
            $status_brg_aktif = ($value_brg_aktif==0)?'':'checked';
            $row[] = '<div class="center">
                        <label>
                            <input name="status_brg_aktif" id="stat_on_off_'.$row_list->kode_brg.'_'.$row_list->kode_brg.'_'.$this->agenda_so_id.'" onclick="setStatusAktifBrg('."'".$row_list->kode_brg."'".', '."'".$row_list->kode_bagian."'".','.$this->agenda_so_id.')" class="ace ace-switch ace-switch-3" type="checkbox" '.$status_brg_aktif.' value="'.$value_brg_aktif.'">
                            <span class="lbl"></span>
                            </label>
                    </div>';
            $row[] = '<div class="center">'.$row_list->nama_petugas.'<br>'.$this->tanggal->formatDateTime($row_list->tgl_stok_opname).'</div>';
            // $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-danger" onclick="deleteRow('.$row_list->kode_depo_stok.')"><i class="fa fa-times bigger-120"></i></a></div>';
            $data[] = $row;
        }

        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
       
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('agenda_so_id', 'Agenda SO', 'trim|required');
        $val->set_rules('tanggal_input', 'Tanggal', 'trim|required');
        $val->set_rules('waktu_input', 'Waktu/Jam', 'trim|required');
        $val->set_rules('bagian', 'Bagian/Unit', 'trim|required');
        $val->set_rules('kode_petugas', 'Petugas', 'trim|xss_clean');

        $val->set_message('required', "Silahkan isi field \"%s\"");
        $val->set_message('integer', "Field \"%s\" harus diisi dengann angka");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;
            $dt_bag = $this->db->select('nama_bagian')->get_where('mt_bagian', array('kode_bagian' => $val->set_value('bagian')))->row();
            $nm_peg = $this->db->select('nama_pegawai')->get_where('mt_karyawan', array('no_induk' => $val->set_value('kode_petugas')))->row();
            $dataexc = array(
                'agenda_so_id' => $this->regex->_genRegex($val->set_value('agenda_so_id'),'RGXQSL'),
                'tanggal_input' => $this->regex->_genRegex($val->set_value('tanggal_input'),'RGXQSL'),
                'waktu_input' => $this->regex->_genRegex($val->set_value('waktu_input'),'RGXQSL'),
                'bagian' => $this->regex->_genRegex($val->set_value('bagian'),'RGXQSL'),
                'nama_bagian' => $dt_bag->nama_bagian,
                'no_induk_pegawai' => $this->regex->_genRegex($val->set_value('kode_petugas'),'RGXQSL'),
                'nama_pegawai' => $nm_peg->nama_pegawai,
            );
            
            $this->session->set_userdata('session_input_so', $dataexc);

            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'redirect_page' => 'inventory/so/Input_dt_so/form_input_dt_so/'.$dataexc['bagian'].'' ));

        }
    }

    public function destroy_session_input_so()
    {
        $this->session->unset_userdata('session_input_so');
        echo json_encode(array('status' => 200, 'message' => 'Silahkan masukan data petugas SO kembali'));

    }

    public function process_input_so()
    {
        
        /*proses input so*/
        if($_POST['kode_bagian']=='070101'){
            $this->Input_dt_so->save_dt_so_nm();
        }else{
            $this->Input_dt_so->save_dt_so();
        }

        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));

    }

    public function set_status_brg()
    {
        // print_r($_POST);die;
        /*proses input so*/
        $value = ($_POST['value'] == 0) ? 1 : 0;
        // $value = $_POST['value'];
        if($_POST['kode_bagian']=='070101'){
            $this->Input_dt_so->update_status_brg('mt_barang_nm', array('is_active' => $value), array('kode_brg' => $_POST['kode_brg']) );
            $this->Input_dt_so->update_status_brg('tc_stok_opname_nm', array('set_status_aktif' => $value), array('kode_brg' => $_POST['kode_brg'], 'agenda_so_id' => $_POST['agenda_so_id'], 'kode_bagian' => $_POST['kode_bagian']) );
            $this->Input_dt_so->update_status_brg('mt_depo_stok_nm', array('is_active' => $value), array('kode_brg' => $_POST['kode_brg'], 'kode_bagian' => $_POST['kode_bagian']) );
            // update
            $this->Input_dt_so->save_dt_so_nm();
        }else{
            if($_POST['kode_bagian']=='060201'){
                $this->Input_dt_so->update_status_brg('mt_barang', array('is_active' => $value), array('kode_brg' => $_POST['kode_brg']) );
            }
            $this->Input_dt_so->update_status_brg('mt_depo_stok', array('is_active' => $value), array('kode_brg' => $_POST['kode_brg'], 'kode_bagian' => $_POST['kode_bagian']) );

            $this->Input_dt_so->update_status_brg('tc_stok_opname', array('set_status_aktif' => $value), array('kode_brg' => $_POST['kode_brg'], 'agenda_so_id' => $_POST['agenda_so_id'], 'kode_bagian' => $_POST['kode_bagian']) );

            $this->Input_dt_so->save_dt_so();
            
        }

        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));

    }

    public function delete_row()
    {
        /*proses input so*/
        if($_POST['kode_bagian']=='070101'){
            // delete depo stok
            $this->db->where('kode_depo_stok', $_POST['ID'])->delete('mt_depo_stok_nm');
        }else{
            $this->db->where('kode_depo_stok', $_POST['ID'])->delete('mt_depo_stok');
            
        }

        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));

    }


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
