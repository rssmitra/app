<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

final Class Master {


    function get_tahun($nid='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$year = array();
		$now = date('Y');
		for ($i=$now-5; $i < $now+2 ; $i++) { 
			$year[] = $i;
		}
		$data = $year;

		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.=$fieldset.'
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="0" '.$selected.'> - Tahun - </option>';

				foreach($data as $row){
					$sel = $nid==$row?'selected':'';
					$field.='<option value="'.$row.'" '.$sel.' >'.strtoupper($row).'</option>';
				}	
			
		$field.='
		</select>
		'.$fieldsetend;
		
		return $field;
		
    }

    function custom_selection_radio($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		if(isset($custom['where_in'])){
			$db->where_in($custom['where_in']['col'],$custom['where_in']['val']);
			$data = $db->get($custom['table'])->result_array();

		}else if(isset($custom['like'])&&isset($custom['where'])){
			$db->like($custom['like']['col'],$custom['like']['val']);
			$db->where($custom['where']);
			$data = $db->get($custom['table'])->result_array();
		}else{
			$data = $db->where($custom['where'])->get($custom['table'])->result_array();
		}

		$field='';

		$field.='<div class="checkbox">';
		foreach($data as $row){
			$sel = $nid==$row[$custom['id']]?'checked':'';
			$field.='<label>';
			$field.='<input type="checkbox" name="'.$name.'" class="ace" value="'.$row[$custom['id']].'" '.$sel.'>';
			$field.='<span class="lbl"> '.$row[$custom['name']].' </span>';
			$field.='</label>';
		}	
		$field.='</div>';
			
		
		return $field;
		
    }
    
    function get_bulan($nid='',$name,$id,$class='',$required='',$inline='') {
		//print_r($nid);die;
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$year = array();
		for ($i=1; $i < 13 ; $i++) { 
			$list = array(
				'key' => $i,
				'value' => $CI->tanggal->getBulan($i),
				);
			$year[] = $list;
		}
		$data = $year;

		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.=$fieldset.'
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="0" '.$selected.'> - Pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row['key']?'selected':'';
					$field.='<option value="'.$row['key'].'" '.$sel.' >'.strtoupper($row['value']).'</option>';
				}	
			
		$field.='
		</select>
		'.$fieldsetend;
		
		return $field;
		
    }

    function custom_selection($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='',$readonly='',$adjustment_option=array()) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		if($custom['id']==$custom['name']){
			$db->select( $custom['id'].' as ID' );
			$db->select( $custom['name'] );
			$db->group_by( array($custom['name']) );
		}else{
			$db->select( array($custom['id'], $custom['name']) );
			$db->group_by( array($custom['id'], $custom['name']) );
		}
		
		$db->order_by($custom['name'], 'ASC');

		if(isset($custom['where_in'])){
			$db->where_in($custom['where_in']['col'],$custom['where_in']['val']);
			$data = $db->get($custom['table'])->result_array();

		}else if(isset($custom['where'])&&isset($custom['where_in'])){
			$db->where_in($custom['where_in']['col'],$custom['where_in']['val']);
			$db->where($custom['where']);
			$data = $db->get($custom['table'])->result_array();
		}else if(isset($custom['like'])&&isset($custom['where'])){
			$db->like($custom['like']['col'],$custom['like']['val']);
			$db->where($custom['where']);
			$data = $db->get($custom['table'])->result_array();
		}else{
			$data = $db->where($custom['where'])->get($custom['table'])->result_array();
		}
		
        //$data = $db->where($custom['where'])->get($custom['table'])->result_array();
		
		$selected = $nid?'':'selected';
		//$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		

		
		$field.='<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' '.$inline.'>';
			$field.='<option value="" '.$selected.'> - Pilih - </option>';

			if(isset($adjustment_option)){
				foreach ($adjustment_option as $key => $value) {
					// echo '<pre>';print_r($value);die;
					$field.='<option value="'.$key.'">'.$value.'</option>';
				}
			}
			$field_id = ($custom['id']==$custom['name']) ? 'ID' : $custom['id'] ;
			foreach($data as $row){
				$sel = trim($nid) == trim($row[$field_id])?'selected':'';
				$field.='<option value="'.trim($row[$field_id]).'" '.$sel.' >'.strtoupper(trim($row[$custom['name']])).'</option>';
			}	
		
		$field.='</select>';
		
		return $field;
		
	}
	
	function custom_selection_with_same_field($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='',$readonly='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		$db->select( array($custom['name']) );
		$db->group_by( array($custom['name']) );
		$db->order_by($custom['name'], 'ASC');

		if(isset($custom['where_in'])){
			$db->where_in($custom['where_in']['col'],$custom['where_in']['val']);
			$data = $db->get($custom['table'])->result_array();

		}else if(isset($custom['where'])&&isset($custom['where_in'])){
			$db->where_in($custom['where_in']['col'],$custom['where_in']['val']);
			$db->where($custom['where']);
			$data = $db->get($custom['table'])->result_array();
		}else if(isset($custom['like'])&&isset($custom['where'])){
			$db->like($custom['like']['col'],$custom['like']['val']);
			$db->where($custom['where']);
			$data = $db->get($custom['table'])->result_array();
		}else{
			$data = $db->where($custom['where'])->get($custom['table'])->result_array();
		}
		
        //$data = $db->where($custom['where'])->get($custom['table'])->result_array();
		
		$selected = $nid?'':'selected';
		//$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.='
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' '.$inline.'>
			<option value="" '.$selected.'> - Pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row[$custom['id']]?'selected':'';
					$field.='<option value="'.$row[$custom['id']].'" '.$sel.' >'.strtoupper($row[$custom['name']]).'</option>';
				}	
			
		$field.='
		</select>
		';
		
		return $field;
		
    }

    function custom_selection_with_label($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='',$readonly='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		if(isset($custom['where_in'])){
			$db->where_in($custom['where_in']['col'],$custom['where_in']['val']);
			$data = $db->get($custom['table'])->result_array();

		}else if(isset($custom['where'])&&isset($custom['where_in'])){
			$db->where_in($custom['where_in']['col'],$custom['where_in']['val']);
			$db->where($custom['where']);
			$data = $db->get($custom['table'])->result_array();
		}else if(isset($custom['like'])&&isset($custom['where'])){
			$db->like($custom['like']['col'],$custom['like']['val']);
			$db->where($custom['where']);
			$data = $db->get($custom['table'])->result_array();
		}else{
			$data = $db->where($custom['where'])->get($custom['table'])->result_array();
		}
        //$data = $db->where($custom['where'])->get($custom['table'])->result_array();
		
		$selected = $nid?'':'selected';
		//$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.='
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="" '.$selected.'> - Pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row[$custom['id']]?'selected':'';
					$add_label = isset($custom['label']) ? $row[$custom['label']] : $row[$custom['id']];
					$field.='<option value="'.$row[$custom['id']].'" '.$sel.' >'.$add_label.' - '.strtoupper($row[$custom['name']]).'</option>';
				}	
			
		$field.='
		</select>
		';
		
		return $field;
		
    }

    function custom_selection_with_join($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		$select = array( $custom['id'], $custom['name'] );
		$db->select( $select );
		foreach ($custom['join'] as $k => $v) {
			$db->join($v['relation_table'],$custom['table'].'.'.$v['relation_ref_id'].'='.$v['relation_table'].'.'.$v['relation_id'],$v['join_type']);
		}
        $db->where($custom['where']);
        foreach ($select as $rw) {
        	$db->group_by($rw, 'ASC');
        }
		$data = $db->get($custom['table'])->result_array();

		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.='
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="" '.$selected.'> - Pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row[$custom['id']]?'selected':'';
					$field.='<option value="'.$row[$custom['id']].'" '.$sel.' >'.strtoupper($row[$custom['name']]).'</option>';
				}	
			
		$field.='
		</select>
		';
		
		return $field;
		
    }


    function on_change_custom_selection($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		if($nid != ''){
        	$data = $db->where($custom['id'], $nid)
        			   ->where($custom['where'])
        			   ->get($custom['table'])->result_array();
		}else{
			$data = array();
		}
		
		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.='
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="" '.$selected.'> - Pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row[$custom['id']]?'selected':'';
					$field.='<option value="'.$row[$custom['id']].'" '.$sel.' >'.strtoupper($row[$custom['name']]).'</option>';
				}	
			
		$field.='
		</select>
		';
		
		return $field;
		
    }

    function get_change($params=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
        
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        if($nid!=''){
            $data = $db->where($params['id'], $nid)->get($params['table'])->result_array();
        }else{
            $data = array();
        }

        $selected = $nid?'':'selected';
        $readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
        
        $starsign = $required?'*':'';
        
        $fieldset = $inline?'':'<fieldset>';
        $fieldsetend = $inline?'':'</fieldset>';
        
        $field='';
        $field.=$fieldset.'
        <select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
            <option value="0" '.$selected.'> - Pilih - </option>';
                foreach($data as $row){
                    $sel = $nid==$row[$params['id']]?'selected':'';
                    $field.='<option value="'.$row[$params['id']].'" '.$sel.' >'.strtoupper($row[$params['name']]).'</option>';
                }
                
            
        $field.='
        </select>
        '.$fieldsetend;
        return $field;
        
    }

	function custom_selection_with_data($arr_data=array(), $nid='',$name,$id,$class='',$required='',$inline='',$readonly='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		$selected = $nid?'':'selected';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		
		$field.='<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' '.$inline.'>';
		$field.='<option value="" '.$selected.'> - Pilih - </option>';

		foreach($arr_data['data'] as $row){
			$val = isset($row[$arr_data['value']]) ? $row[$arr_data['value']] : 0;
			$label = isset($row[$arr_data['label']]) ? $row[$arr_data['label']] : 0;
			$sel = trim($nid) == trim($val)?'selected':'';
			$field.='<option value="'.$val.'">'.strtoupper($label).'</option>';
		}	
		
		$field.='</select>';
		
		return $field;
		
	}
    

    function get_custom_data($table, $select, $where, $return) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$db->select($select);
		$db->from($table);
		$db->where($where);
		$qry = $db->get()->$return();
		return $qry;
		
    }

    function get_max_number($table, $field, $where='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$db->select_max($field);
		$db->from($table);
		if( is_array($where) ){
			$db->where($where);
		}
		$qry = $db->get()->row();
		/*plus 1*/
		$max_num = $qry->$field + 1 ;
		return $max_num;
		
	}
	
    function get_no_antrian_poli($kode_bagian, $kode_dokter, $tipe_pasien='', $tgl_registrasi = '') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		$tgl = ($tgl_registrasi != '')?$tgl_registrasi:date('Y-m-d');

		$db->select_max('no_antrian');
		$db->from('pl_tc_poli');
		if($tipe_pasien != ''){
			$db->where('flag_antrian', $tipe_pasien);
			$txt_type = '(umum)';
		}else{
			$txt_type = '(bpjs)';
		}
		$db->where( "kode_bagian='".$kode_bagian."' and kode_dokter=".$kode_dokter." and CAST(tgl_jam_poli as DATE) = '".$tgl."' " );
		$qry = $db->get()->row();
		// echo $db->last_query();exit;
		/*plus 1*/
		$max_num = $qry->no_antrian + 1 ;
		return $max_num;
		
	}
	
	function get_no_antrian_pm($kode_bagian) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$db->select_max('no_antrian');
		$db->from('pm_tc_penunjang');
		$db->where( "kode_bagian='".$kode_bagian."' and CAST(tgl_daftar as DATE)='".date('Y-m-d')."' " );
		$qry = $db->get()->row();
		/*plus 1*/
		$max_num = $qry->no_antrian + 1 ;
		return $max_num;
		
    }

    function get_string_data($select, $table, $where) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$db->select($select);
		$db->from($table);
		$db->where($where);
		$result = $db->get()->row();

		return isset($result->$select)?$result->$select:'-No data-';		
    }

    function get_qr_code($data) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$qr_code = $data->regon_booking_kode.'-'.$data->regon_booking_no_mr.''.$data->regon_booking_klinik.''.$data->regon_booking_kode_dokter.''.$data->regon_booking_instalasi;

		return $qr_code;
		
    }

    function check_pasien_lama_baru( $no_mr ){

    	$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		$qry_lama_baru = $db->get_where('tc_registrasi', array('no_mr' => $no_mr) );
        $cek_riwayat_pasien =  $qry_lama_baru->num_rows();
        $stat_pasien = ($cek_riwayat_pasien==0)?'Baru':'Lama';

        return $stat_pasien;

    }

    function get_kode_perjanjian( $date ){

    	$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		$jam_pesanan = date_format($date, 'Y-m-d H:i:s');
        /*get_unique_code*/
        $kode_faskes = '0112R034';
        $unique_code_max = $this->get_max_number('tc_pesanan', 'unique_code_counter');
        $length = strlen((string)$unique_code_max);
        $less = 6-$length;
        $null = '';
        for ($i=0; $i < $less; $i++) { 
            $null .= '0';
        }
        $kode_perjanjian = $kode_faskes.date('my').$null.$unique_code_max;

        return $kode_perjanjian;

    }

    function show_detail_row_table( $fields, $data ){

   		$CI =&get_instance();
   		$CI->load->library('session');

   		// print_r($data);die;
   		$html = '<br>';
		$html .= '<div class="row"><div class="col-md-12">';
        $html .= '<pre style="width:100%; font-size:10px; white-space: pre-wrap">';
		$html .= '<b><i>Query Result :</i></b><br>';
		$html .= '<table>';
   		foreach ($fields as $key => $value) {
			$val_data = isset($data->$value)?$data->$value:'-';
			$html .= '<tr>';    
			$html .= '<td width="150px" valign="top">'.ucfirst($value).'</td><td style="text-align: justify" valign="top">: '.$val_data.'</td>';    
			$html .= '</tr>'; 
            // $html .= $value->name.' : '.$row_data[$value->name].' | '; 
		}
		$html .= '</table>';
        $html .= '</pre>';
	    
	    $html .= '</div></div">';

   		return $html;

	}
	   
		
	public function format_kode_permohonan( $tipe ){
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		$table = ($tipe=='medis')?'tc_permohonan':'tc_permohonan_nm';
		$format = ($tipe=='medis')?'PP':'PPNM';
		$db->where('MONTH(tgl_permohonan)', date('m'));
		$db->where('YEAR(tgl_permohonan)', date('Y'));
		$row_num = $db->get($table)->num_rows();
		$max_kode_permohonan = $row_num + 1;
		return $max_kode_permohonan.'/'.$format.'/'.date('m').'/'.date('Y');

	}

	public function format_nomor_permintaan( $tipe ){
			
		$table = ($tipe=='medis')?'tc_permintaan_inst':'tc_permintaan_inst_nm';
		$format = ($tipe=='medis')?'RSSM':'RSSM-NM';
		$max_nomor_permintaan = $this->get_max_number($table, 'id_tc_permintaan_inst');
		return $max_nomor_permintaan.'/'.$format.'/'.date('m').'/'.date('Y');

	}

	public function format_nomor_retur(){
			
		$max_no_retur = $this->get_max_number_per_month('tc_retur_unit', 'id_tc_retur_unit', 'tgl_retur');

		return 'RB/'.$max_no_retur['format'];

	}

	public function format_nomor_penerimaan_brg( $tipe ){
			
		$table = ($tipe=='medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
		$max = $this->get_max_number($table, 'id_penerimaan');
		$format = ($tipe=='medis')?'PB':'PB-NM';
		$rand_unique = rand(999,3);
		$max_number = $this->get_max_number_per_month($table, 'id_penerimaan', 'tgl_penerimaan');
		return $format.'/'.date('my').'/'.$max.'/'.$rand_unique;

	}

	public function format_no_invoice( $jenis_pelayanan ){
			
		$max_num = $this->get_max_number('tc_tagih', 'id_tc_tagih');
		return array('max_num' => $max_num, 'format' => $jenis_pelayanan.'-'.$max_num.'/INV-RSSM/'.date('m').'/'.date('Y'));

	}

	public function format_ttf( $jenis_pelayanan ){
			
		$max_num = $this->get_max_number('tc_hutang_supplier_inv', 'id_tc_hutang_supplier_inv');
		$flag = ($jenis_pelayanan == 'medis')?'M':'NM';
		return array('max_num' => $max_num, 'format' => 'TTF-'.$flag.$max_num.'/'.date('m').'/'.date('Y'));

	}

	function get_max_number_per_month($table, $field, $field_date) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$db->from($table);
		$db->where('YEAR('.$field_date.')', date('Y'));
		$db->where('MONTH('.$field_date.')', date('m'));
		$qry = $db->get()->num_rows();
		/*plus 1*/
		$max_num = $qry + 1 ;
		$format = $this->get_length_num(3, $max_num).'/'.date('m').'/'.date('Y');
		return array('max_num' => $max_num, 'format' => $format);
		
	}

	function get_length_num($length, $num){
		$null = '';
		for( $i = 0; $i < $length; $i++ ){
			$null .= '0';
		}
		$length_null = $null.$num;
		$strlen = strlen($length_null);
		if( $strlen > $length ){
			$offset = $strlen - $length;
			$num = substr($length_null,$offset,$length);
		}else{
			$num = $length_null;
		}
		
		return $num;
	}

	   
	function get_ttd($flag) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$data = $db->get_where('global_parameter', array('flag' => $flag))->row();
		$ttd_format = $data->label.'<br>( '.$data->value.' )';

		return $ttd_format;
		
	}

	function get_ttd_data($flag, $field) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$data = $db->get_where('global_parameter', array('flag' => $flag))->row();

		return $data->$field;
		
	}

	public function list_fields( $table ){
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		return $db->list_fields( $table );
	}

	public function rumus_harga( $params ){
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		// keterangan
		// hna = harga satuan sebelum diskon dan ppn
		// harga_satuan = harga setelah dipotong diskon
		// total_harga_satuan = harga_satuan * jumlah qty barang
		// harga_satuan_netto = harga_satuan ditambah ppn
		// total_harga_hetto = harga_satuan_netto * jumlah qty barang

		// get maximum harga jual akhir
		$existing = $db->select('max(harga_beli) as harga_beli')->get_where('mt_rekap_stok', array('kode_brg' => $params['kode_brg']) )->row();


		// potonga diskon satuan barang
		$potongan_disk_satuan = $params['hna'] * ($params['disc']/100);

		// harga satuan setelah dipotong diskon dan ppn
		$harga_satuan = $params['hna'] - $potongan_disk_satuan;

		// total harga satuan 
		$total_harga_satuan = $harga_satuan * $params['qty'];

		// harga ppn
		$harga_ppn = $harga_satuan * ($params['ppn'] / 100);
		
		// harga satuan netto (setelah ditambah ppn)
		$harga_satuan_netto = $harga_satuan + $harga_ppn;

		// jumlah harga netto
		$total_harga_netto = $harga_satuan_netto * $params['qty'];
		
		// harga persediaan
		$harga_persediaan = $params['hna'] * $params['qty'];
		$harga_satuan_persediaan = $harga_persediaan / $params['rasio'];

		// dpp
		$dpp = $harga_satuan_netto * $params['qty'];
		$total_ppn_val = $dpp * ($params['ppn'] / 100);

		// harga satuan kecil / dibagi rasio
		$harga_satuan_kecil = $params['hna'] / $params['rasio']; 
		$harga_satuan_kecil_netto = $harga_satuan_netto / $params['rasio']; 
		
		// harga jual satuan 
		$harga_jual_ppn = $harga_satuan_kecil * ($params['ppn'] / 100);
		// cari harga jual tertinggi
		$harga_jual_baru = $harga_satuan_kecil + $harga_jual_ppn;
		$harga_jual = ($existing->harga_beli > $harga_jual_baru) ? $existing->harga_beli : $harga_jual_baru;

		$result = array(
			'hna' => $params['hna'],
			'disc' => $params['disc'],
			'potongan_disc' => $potongan_disk_satuan,
			'ppn' => $params['ppn'],
			'dpp' => $dpp,
			'harga_total_ppn' => $total_ppn_val,
			'harga_ppn' => $harga_ppn,
			'harga_satuan' => $harga_satuan,
			'harga_satuan_kecil' => $harga_satuan_kecil,
			'total_harga_satuan' => $total_harga_satuan,
			'harga_satuan_netto' => $harga_satuan_netto,
			'harga_satuan_kecil_netto' => $harga_satuan_kecil_netto,
			'total_harga_netto' => $total_harga_netto,
			'harga_persediaan' => $harga_persediaan,
			'harga_satuan_persediaan' => $harga_satuan_persediaan,
			'harga_jual_ppn' => $harga_jual_ppn,
			'harga_jual' => $harga_jual,
		);

		return $result;

	}
	
	public function sumArrayByKey($myArray, $key){
		
		$result = array_sum(array_column($myArray,$key));
		return $result;
	}

	public function sumArrayByColumn($array, $column) {
		$sum = array();
	   	foreach ($array as $key => $val) {
	   		if( $val[key($column[0])] == $column[0][key($column[0])] 
	   				AND $val[key($column[1])] ==  $column[1][key($column[1])] 
	   				AND $val[key($column[2])] ==  $column[2][key($column[2])]
	   		  )
	   		{
	   			$sum[] = $val['bill_rs'] + $val['bill_dr1'] + $val['bill_dr2'] + $val['lain_lain'];
	   			$getData[] = $val;
	   		}
	   	}
		// echo '<pre>';print_r(array_sum($sum));die;
	   	return array_sum($sum);
	}

	public function get_tgl_keluar($no_registrasi){
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$get_dt = $db->order_by('tgl_keluar', 'DESC')->get_where('tc_kunjungan', array('no_registrasi' => $no_registrasi) )->row();
		$tanggal = ( !empty($get_dt->tgl_keluar) ) ? $get_dt->tgl_keluar : $get_dt->tgl_masuk;
		return $tanggal;
	}

	public function no_seri_kuitansi($no_registrasi){

		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		// cek rawat inap
		$query = "select * from ri_tc_rawatinap where no_kunjungan in (select no_kunjungan from tc_kunjungan where no_registrasi=".$no_registrasi." and substring(kode_bagian_tujuan, 1, 2) = '03')";
		$dt_ri = $db->query($query)->num_rows();

		$kode_bagian_keluar = $db->where('(kode_bagian in (SELECT kode_bagian_keluar FROM tc_registrasi WHERE no_registrasi='.$no_registrasi.') or kode_bagian in (SELECT kode_bagian_masuk FROM tc_registrasi WHERE no_registrasi='.$no_registrasi.'))')->get('mt_bagian')->row();
		// print_r($db->last_query());die;

		if ($dt_ri > 0){
			$seri_kuitansi = 'RI';	
		} else {
			$seri_kuitansi = 'RJ';	
		}

		$no_seri = $this->get_max_number('tc_trans_kasir', 'no_kuitansi', array('seri_kuitansi' => $seri_kuitansi));

		return array('seri_kuitansi' => $seri_kuitansi, 'no_kuitansi' => $no_seri);
	}

	public function no_seri_kuitansi_apt($kode_trans_far){

		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		$seri_kuitansi = 'PB';

		$no_seri = $this->get_max_number('tc_trans_kasir', 'no_kuitansi', array('seri_kuitansi' => $seri_kuitansi));

		return array('seri_kuitansi' => $seri_kuitansi, 'no_kuitansi' => $no_seri);
	}

	public function get_kode_cuti($kepeg_id){

		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		// get data pegawai
		$dt_pegawai = $db->get_where('view_dt_pegawai', array('kepeg_id' => $kepeg_id) )->row();

		$query = "SELECT b.kepeg_nip, COUNT(a.pengajuan_cuti_id) as total_cuti
					FROM kepeg_pengajuan_cuti a
					LEFT JOIN view_dt_pegawai b on b.kepeg_id=a.kepeg_id
					WHERE a.kepeg_id = ".$kepeg_id."
					GROUP BY b.kepeg_nip";
		// exc query
		$result = $db->query($query)->row();

		// format kode cuti
		$total_cuti = isset($result->total_cuti)?$result->total_cuti + 1 : 1;
		$format = 'CT-'.$dt_pegawai->kepeg_nip.'-'.date('my').$total_cuti.'';
		return $format;
	}

	public function get_kode_lembur($kepeg_id){

		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		// get data pegawai
		$dt_pegawai = $db->get_where('view_dt_pegawai', array('kepeg_id' => $kepeg_id) )->row();

		$query = "SELECT b.kepeg_nip, COUNT(a.pengajuan_lembur_id) as total_lembur
					FROM kepeg_pengajuan_lembur a
					LEFT JOIN view_dt_pegawai b on b.kepeg_id=a.kepeg_id
					WHERE a.kepeg_id = ".$kepeg_id."
					GROUP BY b.kepeg_nip";
		// exc query
		$result = $db->query($query)->row();

		// format kode lembur
		$total_lembur = isset($result->total_lembur)?$result->total_lembur + 1 : 1;
		$format = 'LR-'.$dt_pegawai->kepeg_nip.'-'.date('my').$total_lembur.'';
		return $format;
	}

	public function kepeg_acc_flow($kepeg_id, $pengajuan_cuti_id, $type){

		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		// get data pegawai
		$dt_pegawai = $db->get_where('view_dt_pegawai', array('kepeg_id' => $kepeg_id) )->row();

		// get unit 
		$unit_pegawai = $db->get_where('kepeg_mt_unit', array('kepeg_unit_id' => $dt_pegawai->kepeg_unit) )->row();		
		// get spv 
		$dt_atasan = $db->join("(SELECT * FROM kepeg_log_acc_pengajuan WHERE ref_id=".$pengajuan_cuti_id." AND type="."'".$type."'".") as log_acc", 'log_acc.acc_by_kepeg_id=view_dt_pegawai.kepeg_id','LEFT')->get_where('view_dt_pegawai', array('kepeg_unit' => $unit_pegawai->kepeg_unit_parent, 'kepeg_level' => ($unit_pegawai->kepeg_unit_level - 1)
			) )->row();		

		$getData = [];
		if(!empty($dt_atasan))
			$getData = $this->kepeg_acc_flow($dt_atasan->kepeg_id, $pengajuan_cuti_id, $type);
		
		if(!empty($dt_atasan))
			$getData[] = array('kepeg_id' => $dt_atasan->kepeg_id, 'nama_pegawai' => $dt_atasan->nama_pegawai, 'unit' => $dt_atasan->nama_unit, 'level' => $dt_atasan->nama_level, 'acc_date' => $dt_atasan->acc_date);
		return $getData;

	}

	function searcharray($value, $key, $array) {
		$k=0;
		foreach ($array as $k => $val) {
			if ($val[$key] == $value) {
				return $k;
			}
		}
		return $k;
	}

	function formatRomawi($angka){
		$hsl = "";
		if( $angka < 1 || $angka > 3999 ){
			// $hsl = "Batas Angka 1 s/d 3999";
			$hsl = 0;
		}else{
			 while($angka>=1000){
				 $hsl .= "M";
				 $angka -= 1000;
			 }
			 if($angka>=500){
				 if($angka>500){
					 if($angka>=900){
						 $hsl .= "M";
						 $angka-=900;
					 }else{
						 $hsl .= "D";
						 $angka-=500;
					 }
				 }
			 }
			 while($angka>=100){
				 if($angka>=400){
					 $hsl .= "CD";
					 $angka-=400;
				 }else{
					 $angka-=100;
				 }
			 }
			 if($angka>=50){
				 if($angka>=90){
					 $hsl .= "XC";
					  $angka-=90;
				 }else{
					$hsl .= "L";
					$angka-=50;
				 }
			 }
			 while($angka>=10){
				 if($angka>=40){
					$hsl .= "XL";
					$angka-=40;
				 }else{
					$hsl .= "X";
					$angka-=10;
				 }
			 }
			 if($angka>=5){
				 if($angka==9){
					 $hsl .= "IX";
					 $angka-=9;
				 }else{
					$hsl .= "V";
					$angka-=5;
				 }
			 }
			 while($angka>=1){
				 if($angka==4){
					$hsl .= "IV";
					$angka-=4;
				 }else{
					$hsl .= "I";
					$angka-=1;
				 }
			 }
		}
		return ($hsl);
	}

	function formatSigna($params){
		// dd
		$dd = $this->getdd($params['dd']);
		$use = $this->getuse($params['use']);
		// unit
		$unit = $this->getunit($params['unit']);
		$format = 'S. '.$dd.' '.$unit.' '.$this->formatRomawi((int)$params['qty']).' '.$use.'';
		return $format;
		
	}

	function getunit($for_unit){
		$code = $this->get_string_data('reff_id', 'global_parameter', array('flag' => 'satuan_obat', 'value' => ucfirst($for_unit)) );
		return $code;
	}

	function getdd($for_dd){
		switch ($for_dd) {
			case '1':
				$dd = 'sdd';
				break;
			case '2':
				$dd = 'bdd';
				break;
			case '3':
				$dd = 'tdd';
				break;
			case '4':
				$dd = 'qdd';
				break;
			
			default:
				$dd = 'dd';
				break;
		}

		return $dd;
	}

	function getuse($for_use){
		switch (strtolower($for_use)) {
			case 'sesudah makan':
				$use = 'p.c';
				break;
			case 'sebelum makan':
				$use = 'a.c';
				break;
			case 'bersamaan':
				$use = 'd.c';
				break;
			
			default:
				$use = 'p.c';
				break;
		}

		return $use;
	}

	function getNewKodeAkun($lvl, $ref=''){
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		// get last kode akun
        $max_kode_akun = $db->select_max('acc_no_rs')->where(array('level_coa' => $lvl, 'acc_ref' => $ref))->get('mt_account')->row();
        // explode to array
        $explode = explode('.', $max_kode_akun->acc_no_rs);
        // change lvl to array key
        $lvl_prev = $lvl - 1;
        // get max num
        $max_num = (int)$explode[$lvl_prev] + 1;
        // get new kode coa 
        foreach ($explode as $key => $value) {
        	$new_num[] = ($key == $lvl_prev) ? '0'.$max_num : $value;
        }
        $new_kode_akun = implode('.', $new_num);
	}

	function createIDPegawai(){
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$no_urut = $this->get_max_number('mt_karyawan','urutan_karyawan', '');
		$rand = rand(1,99);
		$no_induk = '0'.$rand.''.$no_urut.'';
		return array('no_urut' => $no_urut, 'no_induk' => $no_induk);
	}

	function br2nl($string)
	{
		return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
	}

	function get_depo_aktif($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='',$readonly='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		$data = $db->select(''.$custom['table'].'.'.$custom['id'].', '.$custom['name'].'')->join('mt_bagian', 'mt_bagian.depo_group='.$custom['table'].'.kode_bagian','left')->group_by(''.$custom['table'].'.'.$custom['id'].', '.$custom['name'].'')->where($custom['where'])->get($custom['table'])->result_array();
		
        //$data = $db->where($custom['where'])->get($custom['table'])->result_array();
		
		$selected = $nid?'':'selected';
		//$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.='
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' '.$inline.'>
			<option value="" '.$selected.'> - Pilih - </option>';
				$field_id = ($custom['id']==$custom['name']) ? 'ID' : $custom['id'] ;
				foreach($data as $row){
					$sel = trim($nid) == trim($row[$field_id])?'selected':'';
					$field.='<option value="'.trim($row[$field_id]).'" '.$sel.' >'.strtoupper(trim($row[$custom['name']])).'</option>';
				}	
			
		$field.='
		</select>
		';
		
		return $field;
		
	}

	function generateRandomString($string, $length) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return strtoupper(trim($randomString));
	}

	function color_parameter( $value ){

        $color = '';
        if( $value >= 0 && $value < 49 ){
            $color = '#FF0000';
        }
        if( $value >= 50 && $value < 74 ){
            $color = '#FF8C00';
        }
        if( $value >= 75 && $value < 100 ){
            // $color = '#ffeb00';
            $color = '#58a0e0';
        }

        if( $value == 100 ){
            // $color = '#00FF00';
            $color = '#19d263';
        }
        if( $value > 100 ){
            $color = '#0000FF';
        }

        return $color;

    }

	function stats_between_value( $curr, $last ){

		$a = ($curr > 0 AND $last > 0 ) ? round(($curr/$last) * 100) : 0; 
		$skor_a = ($curr < $last) ? 100 - $a : $a - 100 ;
		$skor = ($skor_a > 0) ? $skor_a : 0;
		if($curr < $last){
			$result = '<i class="fa fa-arrow-down red"></i> <br>'.$skor.'%';
		}else{
			$result = '<i class="fa fa-arrow-up green"></i> <br>'.$skor.'%';
		}
		return $result;
    }

	function getDataKeterlambatanDokter($config){
		$CI =&get_instance();
		/*get day from date*/
		$day = $CI->tanggal->getHariFromDateSql($config['tgl_mulai']);
		$tgl_kunjungan = $CI->tanggal->formatDateTimeToSqlDate($config['tgl_mulai']);
		$time = $CI->tanggal->formatDateTimeToTime($config['tgl_mulai']);

		// get jadwal dokter
		$jadwal = $CI->db->get_where('tr_jadwal_dokter', array('jd_kode_dokter' => $config['kode_dokter'], 'jd_kode_spesialis' => $config['kode_bagian'], 'jd_hari' => $day))->row();

		
        $jam_praktek    = $CI->tanggal->formatDateTimeToTime($tgl_kunjungan.' '.$jadwal->jd_jam_mulai); // bisa juga waktu sekarang now()
		$waktu_awal = strtotime($tgl_kunjungan.' '.$jadwal->jd_jam_mulai);
        $waktu_akhir    = strtotime($tgl_kunjungan.' '.$time); // bisa juga waktu sekarang now()
        
        //menghitung selisih dengan hasil detik
        $diff    =$waktu_akhir - $waktu_awal;
        
        //membagi detik menjadi jam
        $jam    =floor($diff / (60 * 60));
        
        //membagi sisa detik setelah dikurangi $jam menjadi menit
        $menit    =$diff - $jam * (60 * 60);

		$return = array(
			'jam_mulai' => $time,
			'jam_praktek' => $jam_praktek,
			'jam' => $jam,
			'menit' => floor( $menit / 60 ),
			'detik' => number_format($diff,0,",","."),
		);
		return $return;

	}

	public function getLabelObatExpired($tgl_expired){
		$ex_mth = date('m');
		$exp_mth = date("m",strtotime($tgl_expired));
		$count_mth_exp = $exp_mth - $ex_mth;
		$return = ($count_mth_exp <= 0) ? 'expired : '.$tgl_expired.'' : '';
		switch ($count_mth_exp) {
			case 0:
				$return = '<span class="blink_me" style="color: white; background: red; font-size: 10px; padding: 2px; font-weight: bold">'.$tgl_expired.'</span>';
				break;
			case 1:
				$return = '<span style="color: white; background: orange; font-size: 10px; padding: 2px; font-weight: bold">'.$tgl_expired.'</span>';
				break;
			case 2:
				$return = '<span style="color: white; background: green; font-size: 10px; padding: 2px; font-weight: bold">'.$tgl_expired.'</span>';
				break;
			
			default:
				# code...
				$return = '<span style="color: white; background: green; font-size: 10px; padding: 2px; font-weight: bold">'.$tgl_expired.'</span>';
				break;
		}
		return $return;
	}
	

}

?> 