<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Counter_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function update($table,$data, $where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function get_antrian($data)
	{
		$query ="select TOP 1 ant_no from tr_antrian WHERE ant_status in(0,2) and ant_panggil=0 and ant_type = '".$data['tipe']."' AND ant_loket IS NULL order by ant_no asc ";
		//print_r($query);die;
		$exc = $this->db->query($query);
        return $exc->row();
	
	}

	public function get_antrian_current($data)
	{
		$query ="select TOP 1 ant_no from tr_antrian WHERE ant_status = 0 and ant_panggil=1 and ant_loket='".$data['loket']."' and ant_type = '".$data['tipe']."' order by ant_no asc ";
		$exc = $this->db->query($query);
        return $exc->row();
	
	}

	public function get_antrian_for_first($data)
	{
		$query ="select TOP 1 ant_no from tr_antrian WHERE ant_status = 0 and ant_panggil=0 and ant_type = '".$data['tipe']."' order by ant_no asc ";
		$exc = $this->db->query($query);
        return $exc->row();
	
	}

	public function get_antrian_current_2($data)
	{
		$query ="select TOP 1 ant_no from tr_antrian WHERE ant_status = 0 and ant_panggil=1 and ant_loket='".$data['loket']."' and ant_type = '".$data['tipe']."' order by ant_no asc ";
		$exc = $this->db->query($query);
        return $exc->row();
	
	}

	public function get_current_counter_number()
	{
		/*cek session*/
		/*data from session*/
			$data = [];
			$data['loket'] = isset($_POST['loket'])?$_POST['loket']:'';
			$data['tipe'] = isset($_POST['tipe'])?$_POST['tipe']:'';
			
			/*cek status aktif dulu*/
			$aktif_counter_num = $this->get_num_active_loket($data['loket']);

			if(count($aktif_counter_num)==1){
				$curr_num = $aktif_counter_num->ant_no;
			}else{
				/*get max_number*/
				$curr_num = $this->max_counter_number($data);
				/*update loket for first loket*/
				$this->db->update('tr_antrian', array('ant_loket' => $data['loket']), array('ant_no' => $curr_num, 'ant_type' => $data['tipe']) );
			}
			
			$format =$this->format_counter_number($data['tipe'], $curr_num);

        return $format;
	
	}

	function get_num_active_loket($loket){
		return $this->db->where('ant_status != 1')->get_where('tr_antrian', array('ant_loket' => $loket, 'ant_aktif' => 1) )->row();
	}

	function max_counter_number($data){

		/*get curr data*/
		$curr_data = $this->get_antrian_current($data);
		$cek = count($curr_data);
        if($cek==0){
            /*get antrian */
            $no_ = $this->get_antrian($data);
            if( count($no_)==0){
            	$no = 1;
            }else{

            	$no = $no_->ant_no;
            	
            }
        }else{
            $no = $curr_data->ant_no;
        }

        return $no;

	}

	function format_counter_number($tipe, $num){

		$tipe_loket = $this->txt_tipe_loket($tipe);

		$format = $tipe_loket.$this->pad($num, 3);

		return $format;

	}

	function pad ($string, $max) {
	    $length = strlen($string);
	    return $length < $max ? $this->pad("0".$string, $max) : $string;
	}

	function txt_tipe_loket($tipe){

		switch ($tipe) {
			case 'bpjs':
				$txt_tipe = 'A';
				break;
			case 'umum':
				$txt_tipe = 'B';
				break;
			case 'online':
				$txt_tipe = 'C';
				break;
			default:
				$txt_tipe = 'A';
				break;
		}

		return $txt_tipe;

	}

	function get_counter_info( $data ){
		/*get total antrian by tipe loket*/
		$total = 0;
		$exs_num = 0;
		$curr_num=0;
		
		/*get total antrian*/
		$total = $this->db->get_where('tr_antrian', array('ant_type' => strtolower($data['tipe'])) )->num_rows();

		/*cek status aktif dulu*/
		$aktif_counter_num = $this->get_num_active_loket($data['loket']);

		if(count($aktif_counter_num)==1){
			$curr_num = $aktif_counter_num->ant_no;
		}else{
			/*get max_number*/
			$curr_num = $this->max_counter_number($data);
		}

		$bpjs = $this->db->get_where('tr_antrian', array('ant_type' => 'bpjs') )->num_rows();
		$non_bpjs = $this->db->get_where('tr_antrian', array('ant_type' => 'umum') )->num_rows();
		return array('total' => $total, 'curr_num' => $curr_num, 'bpjs' => $bpjs, 'non_bpjs' => $non_bpjs);

	}

	function get_counter_total_tipe_loket(){

		$bpjs = $this->db->get_where('tr_antrian', array('ant_type' => 'bpjs') )->num_rows();
		$count_bpjs =  $this->db->get_where('tr_antrian', array('ant_type' => 'bpjs', 'ant_status' => 1) )->num_rows();
		$sisa_bpjs = (int)$bpjs - (int)$count_bpjs;

		$non_bpjs = $this->db->get_where('tr_antrian', array('ant_type' => 'umum') )->num_rows();
		$count_non_bpjs = $this->db->get_where('tr_antrian', array('ant_type' => 'umum', 'ant_status' => 1) )->num_rows();
		$sisa_non_bpjs = (int)$non_bpjs - (int)$count_non_bpjs;

		$online = $this->db->get_where('tr_antrian', array('ant_type' => 'online') )->num_rows();
		$count_online = $this->db->get_where('tr_antrian', array('ant_type' => 'online', 'ant_status' => 1) )->num_rows();
		$sisa_online = (int)$online - (int)$count_online;

		return array('bpjs' => $bpjs, 'sisa_bpjs' => $sisa_bpjs, 'non_bpjs' => $non_bpjs, 'sisa_non_bpjs' => $sisa_non_bpjs, 'online' => $online, 'sisa_online' => $sisa_online);

	}



	/*next counter number*/
	public function get_next_counter($data)
	{
		$query ="select TOP 1 ant_no from tr_antrian 
				WHERE ant_status in(0,2) and ant_panggil=0 
				and ant_type = '".$data['tipe']."' and ant_no > ".$data['curr_num']." and ant_loket IS NULL 
				order by ant_no asc ";
				//print_r($query);die;
		$exc = $this->db->query($query);
        return $exc->row();
	
	}

	public function get_next_counter_number( $data )
	{
		/*get antrian current*/
		$next = $this->get_next_counter($data);

		/*get prev_number*/
		$next_num = $next->ant_no;
		$format =$this->format_counter_number($data['tipe'], $next_num);

		/*update for next number*/
		$this->db->where('ant_type', $data['tipe'])
				 ->where('ant_no', $next_num)
				 ->where('ant_aktif != 1')
				 ->update('tr_antrian', array('ant_loket' => $data['loket'], 'ant_aktif' => 0, 'ant_panggil' => 1) );

		/*update null for previous num*/
		$this->db->where('ant_no < '.$next_num.'')
				 ->where('ant_type', $data['tipe'])
				 ->where('ant_loket', $data['loket'])->or_where('ant_loket', 0)
				 ->update('tr_antrian', array('ant_aktif' => 2, 'ant_status' => 1, 'ant_loket' => $data['loket']) );

        return $format;
	
	}

	function next_counter_number($data){

		/*get curr data*/
		$next_data = $this->get_next_counter($data);
		return $next_data->ant_no;

	}

	/*end next counter number*/

	/*previous counter number*/

	public function get_prev_counter($data)
	{
		$query ="select TOP 1 ant_no from tr_antrian 
				WHERE ant_status in(0,2) and ant_panggil=0 
				and ant_type = '".$data['tipe']."' and ant_no < ".$data[2]."  order by ant_no desc ";
				//print_r($query);die;
		$exc = $this->db->query($query);
        return $exc->row();
	
	}

	public function get_previous_counter_number( $data )
	{
		/*get antrian current*/
		$prev = $this->get_prev_counter($data);

		/*get prev_number*/
		$prev_num = $prev->ant_no;
		$format =$this->format_counter_number($data['tipe'], $prev_num);

		/*update loket current*/
		$this->db->where('ant_type', $data['tipe'])
				 ->where('ant_no', $prev_num)
				 ->where('ant_aktif != 1')
				 ->update('tr_antrian', array('ant_loket' => $data['loket'], 'ant_aktif' => 0) );

		/*update null for previous num*/
		$this->db->where('ant_loket', $data['loket'])
				 ->where('ant_aktif != 1')
				 ->where('ant_status != 1')
				 ->where('ant_no > '.$prev_num.'')
				 ->update('tr_antrian', array('ant_aktif' => 0, 'ant_loket' => 0) );

        return $format;
	
	}

	function prev_counter_number($data){

		/*get curr data*/
		$prev_data = $this->get_prev_counter($data);
		return $prev_data->ant_no;

	}

	function update_last_status_aktif_loket($data, $num_active){

		/*second update status aktif 1 for current number*/
		$this->db->where('ant_loket', $data['loket'])
				 ->where('ant_no', $num_active)
		    	 ->update('tr_antrian', array('ant_aktif' => 1, 'ant_loket' => $data['loket']) );
		    	 //print_r($this->db->last_query());die;
		$this->update_status_aktif_null($data, $num_active);

		return true;
	}

	function update_status_aktif_null($data, $num_active){

		/*update all status aktif 0*/
		return $this->db->where('ant_loket = '.$data['loket'].'')
				 ->where('ant_no != '.$num_active.'')
				 ->where('ant_status != 1')
				 ->update('tr_antrian', array('ant_aktif' => 0, 'ant_loket' => 0));

	}

	function update_current_num_for_first($data, $num_active){

		$this->db->where('ant_type', $data['tipe'])
				 ->where('ant_no', $num_active)
		    	 ->update('tr_antrian', array('ant_aktif' => 1, 'ant_loket' => $data['loket'], 'ant_panggil' => 1) );

		return true;
	}


}
