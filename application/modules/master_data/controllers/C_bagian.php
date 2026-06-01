<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_bagian extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'master_data/C_bagian');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('master_data/M_bagian_model', 'M_bagian');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() {
        $group_list = $this->db
            ->select('kode_bagian, nama_bagian')
            ->from('mt_bagian')
            ->where('group_bag', 'Group')
            ->where('is_active', 'Y')
            ->order_by('kode_bagian', 'ASC')
            ->get()->result();

        $data = array(
            'title'       => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'group_list'  => $group_list,
        );
        $this->load->view('Bagian/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'master_data/C_bagian/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->M_bagian->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'master_data/C_bagian/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        // echo "<pre>"; print_r($data);die;
        
        $this->load->view('Bagian/form', $data);
    }

    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'master_data/C_bagian/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id.'?'.http_build_query($_GET));
        /*define data variabel*/
        $data['value'] = $this->M_bagian->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['flag_string'] = $_GET['flag'];
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Bagian/form', $data);
    }

    public function get_detail( $id )
    {
        $fields = $this->M_bagian->list_fields();
        $data = $this->M_bagian->get_by_id( $id );
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }

    // ── Inline toggle renderer ────────────────────────────────────────────────
    private function _toggle($id, $field, $cur_val, $options)
    {
        $cur_str = (string)$cur_val;
        $html    = '<div class="tbl-field-toggle"'
                 . ' data-id="' . (int)$id . '"'
                 . ' data-field="' . htmlspecialchars($field, ENT_QUOTES) . '"'
                 . ' data-current="' . htmlspecialchars($cur_str, ENT_QUOTES) . '"'
                 . ' style="display:inline-flex;border:1px solid #ccc;border-radius:3px;overflow:hidden;font-size:11px">';
        foreach ($options as $opt) {
            $is_act = ($cur_str === (string)$opt['val']);
            if ($is_act) {
                $s = 'cursor:default;padding:3px 8px;background:' . $opt['color'] . ';color:#fff;font-weight:bold';
            } else {
                $s = 'cursor:pointer;padding:3px 8px;background:#f0f0f0;color:#aaa';
            }
            $html .= '<span class="tbl-toggle-opt" data-val="' . htmlspecialchars((string)$opt['val'], ENT_QUOTES) . '" style="' . $s . '">'
                   . htmlspecialchars($opt['label'], ENT_QUOTES) . '</span>';
        }
        $html .= '</div>';
        return $html;
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->M_bagian->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        // Load Group options once — used for the inline parent select in every Detail row
        $group_opts = $this->db
            ->select('kode_bagian, nama_bagian')
            ->from('mt_bagian')
            ->where('group_bag', 'Group')
            ->where('is_active', 'Y')
            ->order_by('kode_bagian', 'ASC')
            ->get()->result();
        foreach ($list as $row_list) {
            $no++;
            $row = array();

            // ── 0: Checkbox ───────────────────────────────────────────────────
            $row[] = '<div class="center"><label class="pos-rel">
                        <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_mt_bagian.'"/>
                        <span class="lbl"></span>
                    </label></div>';

            // ── 1: Expand (DataTable detail row) ──────────────────────────────
            $row[] = '';

            // ── 2: Hidden ID (used by detail loader) ──────────────────────────
            $row[] = $row_list->id_mt_bagian;

            // ── 3: Actions ────────────────────────────────────────────────────
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li>'.$this->authuser->show_button('master_data/C_bagian','R',$row_list->id_mt_bagian,67).'</li>
                            <li>'.$this->authuser->show_button('master_data/C_bagian','U',$row_list->id_mt_bagian,67).'</li>
                            <li>'.$this->authuser->show_button('master_data/C_bagian','D',$row_list->id_mt_bagian,6).'</li>
                        </ul>
                      </div></div>';

            // ── 4: No ─────────────────────────────────────────────────────────
            $row[] = '<div class="center">'.$no.'</div>';

            // ── 5: Kode ───────────────────────────────────────────────────────
            $row[] = '<div class="center"><b>'.$row_list->kode_bagian.'</b></div>';

            // ── 6: Nama Bagian ────────────────────────────────────────────────
            $row[] = strtoupper($row_list->nama_bagian);

            // ── 7: Nama Singkat ───────────────────────────────────────────────
            $short = isset($row_list->short_name) && $row_list->short_name ? $row_list->short_name : '<span style="color:#aaa">-</span>';
            $row[] = strtoupper($short);

            // ── 8: Nama Group ─────────────────────────────────────────────────
            // Group rows → static badge (they ARE the parent, no parent select needed)
            // Detail rows → inline select to assign / change parent Group
            if ($row_list->group_bag === 'Group') {
                $row[] = '<div class="center"><span class="label label-sm label-info">Group</span></div>';
            } else {
                $cur_parent = isset($row_list->depo_group) ? $row_list->depo_group : '';
                $sel = '<select class="form-control input-sm tbl-parent-select"'
                     . ' style="min-width:140px" data-id="' . $row_list->id_mt_bagian . '">'
                     . '<option value="">-- Pilih Group --</option>';
                foreach ($group_opts as $g) {
                    $selected = ($cur_parent === $g->kode_bagian) ? 'selected' : '';
                    $sel .= '<option value="' . $g->kode_bagian . '" ' . $selected . '>'
                          . $g->kode_bagian . ' &ndash; ' . $g->nama_bagian . '</option>';
                }
                $sel .= '</select>';
                $row[] = $sel;
            }

            // ── 9: Depo? ──────────────────────────────────────────────────────
            $is_depo = isset($row_list->is_depo) ? (string)$row_list->is_depo : 'N';
            $row[] = '<div class="center">' . $this->_toggle($row_list->id_mt_bagian, 'is_depo', $is_depo, array(
                array('val' => 'Y', 'label' => 'On',    'color' => '#8e44ad'),
                array('val' => 'N', 'label' => 'Off', 'color' => '#95a5a6'),
            )) . '</div>';

            // ── 10: Depo Group ────────────────────────────────────────────────
            $depo_grp       = isset($row_list->depo_group)       && $row_list->depo_group       ? $row_list->depo_group       : '';
            $nama_depo_grp  = isset($row_list->nama_depo_group)  && $row_list->nama_depo_group  ? $row_list->nama_depo_group  : '';
            if ($depo_grp) {
                $row[] = '<div class="center"><b>' . $depo_grp . '</b>'
                       . ($nama_depo_grp ? '<br><small style="color:#555">' . $nama_depo_grp . '</small>' : '')
                       . '</div>';
            } else {
                $row[] = '<div class="center"><span style="color:#aaa">-</span></div>';
            }

            // ── 11: Publik? ───────────────────────────────────────────────────
            $is_pub = isset($row_list->is_public) ? (string)$row_list->is_public : 'N';
            $row[] = '<div class="center">' . $this->_toggle($row_list->id_mt_bagian, 'is_public', $is_pub, array(
                array('val' => '1', 'label' => 'On',  'color' => '#27ae60'),
                array('val' => '0', 'label' => 'Off', 'color' => '#95a5a6'),
            )) . '</div>';

            // ── 12: Pelayanan / Backoffice ────────────────────────────────────
            $pel = isset($row_list->pelayanan) ? (string)$row_list->pelayanan : '0';
            $row[] = '<div class="center">' . $this->_toggle($row_list->id_mt_bagian, 'pelayanan', $pel, array(
                array('val' => '1', 'label' => 'On',  'color' => '#2980b9'),
                array('val' => '0', 'label' => 'Off', 'color' => '#e67e22'),
            )) . '</div>';

            // ── 13: Status Aktif ──────────────────────────────────────────────
            $is_act = isset($row_list->is_active) ? (string)$row_list->is_active : 'N';
            $row[] = '<div class="center">' . $this->_toggle($row_list->id_mt_bagian, 'is_active', $is_act, array(
                array('val' => 'Y', 'label' => 'On',    'color' => '#27ae60'),
                array('val' => 'N', 'label' => 'Off', 'color' => '#e74c3c'),
            )) . '</div>';

            // ── 14: Last Update ───────────────────────────────────────────────
            $row[] = $this->logs->show_logs_record_datatable($row_list);

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->M_bagian->count_all(),
                        "recordsFiltered" => $this->M_bagian->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('nama_bagian', 'Nama Bagian', 'trim|required');
        $val->set_rules('group_bag', 'Group Bag', 'trim|required');
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $id = ($this->input->post('id'))?$this->input->post('id'):0;

            $dataexc = array(
                'kode_bagian'      => $this->regex->_genRegex( $this->input->post('kode_bagian')      , 'RGXQSL'),
                'nama_bagian'      => $this->regex->_genRegex( $val->set_value('nama_bagian')          , 'RGXQSL'),
                'short_name'       => $this->regex->_genRegex( $this->input->post('short_name')        , 'RGXQSL'),
                'group_bag'        => $this->regex->_genRegex( $this->input->post('group_bag')         , 'RGXQSL'),
                'validasi'         => $this->regex->_genRegex( $this->input->post('validasi')          , 'RGXQSL'),
                'is_depo'          => $this->regex->_genRegex( $this->input->post('is_depo')           , 'RGXQSL'),
                'depo_group'       => $this->regex->_genRegex( $this->input->post('depo_group')        , 'RGXQSL'),
                'has_observe_room' => $this->regex->_genRegex( $this->input->post('has_observe_room')  , 'RGXQSL'),
                'pelayanan'        => $this->regex->_genRegex( $this->input->post('pelayanan')         , 'RGXINT'),
                'status_aktif'     => $this->regex->_genRegex( $this->input->post('status_aktif')      , 'RGXINT'),
                'kode_poli_bpjs'   => $this->regex->_genRegex( $this->input->post('kode_poli_bpjs')    , 'RGXQSL'),
                'is_public'        => $this->regex->_genRegex( $this->input->post('is_public')         , 'RGXINT'),
                'id_satu_sehat'    => $this->regex->_genRegex( $this->input->post('id_satu_sehat')     , 'RGXQSL'),
                'location_id'      => $this->regex->_genRegex( $this->input->post('location_id')       , 'RGXQSL'),
                'is_active'        => $this->regex->_genRegex( $this->input->post('is_active')         , 'RGXQSL'),
            );

            // DEBUG: Log the data being saved
            error_log('[BAGIAN_PROCESS] ID: ' . $id . ' | GROUP: ' . $dataexc['group_bag'] . ' | KODE: ' . $dataexc['kode_bagian'] . ' | DEPO: ' . $dataexc['depo_group']);

            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $this->M_bagian->save($dataexc);
                $newId = $this->db->insert_id();
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->M_bagian->update(array('id_mt_bagian' => $id), $dataexc);
                $newId = $id;
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $error = $this->db->error();
                $error_msg = isset($error['message']) ? $error['message'] : json_encode($error);
                error_log('[BAGIAN_DB_ERROR] ' . $error_msg . ' | Last Query: ' . $this->db->last_query());
                echo json_encode(array('status' => 301, 'message' => 'DB Error: ' . $error_msg));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }
        }
    }

    // ── Inline parent update (from table select) ─────────────────────────────

    public function update_parent()
    {
        $id         = (int)$this->input->post('id');
        $kode_group = $this->input->post('kode_group');

        if (!$id) {
            echo json_encode(array('status' => 301, 'message' => 'ID tidak valid'));
            return;
        }

        $fld = array(
            'depo_group'   => ($kode_group !== '' ? $kode_group : null),
            'updated_date' => date('Y-m-d H:i:s'),
            'updated_by'   => json_encode(array(
                'user_id'  => $this->session->userdata('user')->user_id,
                'fullname' => $this->session->userdata('user')->fullname,
            )),
        );

        $this->M_bagian->update(array('id_mt_bagian' => $id), $fld);

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(array('status' => 301, 'message' => 'Gagal menyimpan parent group'));
        } else {
            echo json_encode(array('status' => 200, 'message' => 'Parent group berhasil diperbarui'));
        }
    }

    // ── Inline field toggle update ────────────────────────────────────────────

    public function update_field()
    {
        $id    = (int)$this->input->post('id');
        $field = $this->input->post('field');
        $value = $this->input->post('value');

        $allowed = array('is_depo', 'is_public', 'pelayanan', 'is_active');
        if (!in_array($field, $allowed, true) || !$id) {
            echo json_encode(array('status' => 301, 'message' => 'Parameter tidak valid'));
            return;
        }

        $save_value = ($field === 'pelayanan') ? (int)$value : (string)$value;

        $fld = array(
            $field         => $save_value,
            'updated_date' => date('Y-m-d H:i:s'),
            'updated_by'   => json_encode(array(
                'user_id'  => $this->session->userdata('user')->user_id,
                'fullname' => $this->session->userdata('user')->fullname,
            )),
        );

        $this->M_bagian->update(array('id_mt_bagian' => $id), $fld);

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(array('status' => 301, 'message' => 'Gagal menyimpan perubahan'));
        } else {
            echo json_encode(array('status' => 200, 'message' => 'Berhasil diperbarui'));
        }
    }

    public function get_bagian_dropdown()
    {
        $this->db->select('id_mt_bagian as id, kode_bagian, nama_bagian, group_bag');
        $this->db->from('mt_bagian');
        $this->db->where('group_bag', 'Group');
        $this->db->where('is_active', 'Y');
        $this->db->order_by('kode_bagian', 'ASC');
        $query = $this->db->get();
        
        error_log('[GET_BAGIAN_DROPDOWN] Query: ' . $this->db->last_query() . ' | Result count: ' . $query->num_rows());
        
        $result = $query->result();
        echo json_encode($result);
    }


    public function get_next_kode_group()
    {
        $result = $this->db->query(
            "SELECT MAX(CAST(kode_bagian AS INT)) as last_kode FROM mt_bagian WHERE group_bag = 'Group'"
        )->row();

        $next_kode = ($result && $result->last_kode) ? ($result->last_kode + 1) : 10000;

        echo json_encode(array('next_kode' => (string)$next_kode));
    }

    public function get_next_kode_bagian_by_validasi()
    {
        $selected_kode = $this->input->get('kode_bagian');
        
        // Validasi = first 4 digits of selected kode_bagian (parent)
        // e.g., if parent is 10000, validasi = 1000
        $validasi = substr($selected_kode, 0, 4);
        
        // Build a pattern: for parent kode 10000, we want detail kodes like 1000001, 1000002, etc.
        // So we search for kodes that start with the parent kode (as prefix)
        $pattern = $selected_kode . '%';
        
        // Find the MAX numeric kode_bagian that starts with selected_kode, then add 1
        $query = "SELECT MAX(CAST(kode_bagian AS INT)) as last_kode 
                  FROM mt_bagian 
                  WHERE validasi = ? AND is_active = 'Y'";
                //   echo '[GET_NEXT_KODE] Query: ' . $query . ' | Validasi: ' . $validasi;
        $result = $this->db->query($query, array($validasi))->row();
        
        // If max exists, increment by 1; otherwise start with parent + 001
        if ($result && $result->last_kode) {
            $next_kode = $result->last_kode + 1;
        } else {
            // First detail under this parent: parent_kode + 001
            $next_kode = intval($selected_kode . '001');
        }
        
        // Pad to ensure it's a valid width (7 digits for kode_bagian)
        $next_kode_str = str_pad((string)$next_kode, 6, '0', STR_PAD_LEFT);
        
        echo json_encode(array(
            'next_kode' => $next_kode_str,
            'validasi' => $validasi
        ));
    }

    public function export_excel()
    {
        $filter_is_active  = $this->input->get('filter_is_active');
        $filter_depo_group = $this->input->get('filter_depo_group');

        $this->db->select('mt_bagian.*, grp.nama_bagian as nama_depo_group');
        $this->db->from('mt_bagian');
        $this->db->join('mt_bagian grp', 'grp.kode_bagian = mt_bagian.depo_group', 'left');

        if ($filter_is_active !== '' && $filter_is_active !== null) {
            $this->db->where('mt_bagian.is_active', $filter_is_active);
        }
        if ($filter_depo_group !== '' && $filter_depo_group !== null) {
            $this->db->where('mt_bagian.depo_group', $filter_depo_group);
        }

        $this->db->order_by('mt_bagian.group_bag', 'ASC');
        $this->db->order_by('mt_bagian.kode_bagian', 'ASC');

        $list = $this->db->get()->result();

        $data = array(
            'title' => $this->title,
            'list'  => $list,
        );

        $this->load->view('Bagian/excel_view', $data);
    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->M_bagian->delete_by_id($toArray)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

}


/* End of file Gender.php */
/* Location: ./application/modules/product_type/controllers/product_type.php */
