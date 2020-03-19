<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendaftaran_pasien_model extends CI_Model {

	var $table = 'tc_registrasi';
	var $column = array('tc_registrasi.no_registrasi','tc_registrasi.no_mr','mt_master_pasien.nama_pasien','mt_perusahaan.nama_perusahaan','tc_registrasi.tgl_jam_masuk','mt_bagian.nama_bagian', 'mt_karyawan.nama_pegawai');
	var $select = 'tc_registrasi.no_registrasi,tc_registrasi.no_mr,mt_master_pasien.nama_pasien,mt_perusahaan.nama_perusahaan,tc_registrasi.tgl_jam_masuk,mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, print_tracer, stat_pasien';

	var $order = array('tc_registrasi.no_registrasi' => 'DESC', 'tc_registrasi.updated_date' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_master_pasien',''.$this->table.'.no_mr=mt_master_pasien.no_mr','left');
		$this->db->join('mt_perusahaan',''.$this->table.'.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_bagian',''.$this->table.'.kode_bagian_masuk=mt_bagian.kode_bagian','left');
		$this->db->join('mt_karyawan',''.$this->table.'.kode_dokter=mt_karyawan.kode_dokter','left');
		
		/*if isset parameter*/
		if( $_GET ) {
			
			if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
				$this->db->like('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
			}

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$this->db->where("convert(varchar,tc_registrasi.tgl_jam_masuk,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");	
				
	        }else{
				$this->db->where('CONVERT(VARCHAR(11),tc_registrasi.tgl_jam_masuk,102) >= CONVERT(VARCHAR(11),getdate(),102) ');
			}

	        if (isset($_GET['bagian']) AND $_GET['bagian'] != '') {
	            $this->db->where('tc_registrasi.kode_bagian_masuk', $_GET['bagian']);	
	        }

	        if (isset($_GET['dokterHidden']) AND $_GET['dokterHidden'] != 0) {
	            $this->db->where('tc_registrasi.kode_dokter', $_GET['dokterHidden']);	
	        }
		
		}else{
			$this->db->where(array('YEAR(tc_registrasi.tgl_jam_masuk)' => date('Y'), 'MONTH(tc_registrasi.tgl_jam_masuk)' => date('m'), 'DAY(tc_registrasi.tgl_jam_masuk)' => date('d')));
		} 
        /*end parameter*/
		/*check level user*/
		//$this->authuser->filtering_data_by_level_user($this->table, $this->session->userdata('user')->user_id);

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		$i = 0;
	
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	
	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function get_exported_data($data)
	{
		//print_r($data);die;
		if($data['export_by'] == 'detail'){
			$this->db->select($this->select);
		} else {
			$this->db->select('tc_registrasi.kode_bagian_masuk,mt_bagian.nama_bagian,count(tc_registrasi.no_registrasi) as number, per_a.bpjs as bpjs, per_b.jaminan as jaminan, per_c.umum as umum');
		}
		$this->db->from($this->table);
		$this->db->join('mt_master_pasien',''.$this->table.'.no_mr=mt_master_pasien.no_mr','left');
		$this->db->join('mt_perusahaan',''.$this->table.'.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_bagian',''.$this->table.'.kode_bagian_masuk=mt_bagian.kode_bagian','left');
		$this->db->join('mt_karyawan',''.$this->table.'.kode_dokter=mt_karyawan.kode_dokter','left');
		
		if($data['export_by'] == 'general'){
			if( $data ){
				if(isset($data['search_by']) AND isset($data['keyword'])){
					$sql1 = "AND mt_master_pasien.".$data['search_by']." like '%".$data['keyword']."%'";
				}else{
					$sql1 = "";
				}

				if (isset($data['from_tgl']) AND $data['from_tgl'] != '' || isset($data['to_tgl']) AND $data['to_tgl'] != '') {	
					$sql2 = "AND convert(varchar,tc_registrasi.tgl_jam_masuk,23) between'".$data['from_tgl']."' and '".$data['to_tgl']."'";
				}else{
					$sql2 = "AND CONVERT(VARCHAR(11),tc_registrasi.tgl_jam_masuk,102) >= CONVERT(VARCHAR(11),getdate(),102)";
				}

				if (isset($data['bagian']) AND $data['bagian'] != '') {
					$sql3 = "AND tc_registrasi.kode_bagian_masuk = ".$data['bagian']."";	
				}else{
					$sql3 ="";
				}

				if (isset($data['dokterHidden']) AND $data['dokterHidden'] != 0) {
					$sql4 = "AND tc_registrasi.kode_dokter = ".$data['dokterHidden']."";
				}else{
					$sql4 ="";
				}
			}
			
			$this->db->join("
							(SELECT tc_registrasi.kode_bagian_masuk,mt_bagian.nama_bagian,count(tc_registrasi.no_registrasi) as bpjs FROM tc_registrasi 
							LEFT JOIN mt_master_pasien ON tc_registrasi.no_mr=mt_master_pasien.no_mr 
							LEFT JOIN mt_perusahaan ON tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan 
							LEFT JOIN mt_bagian ON tc_registrasi.kode_bagian_masuk=mt_bagian.kode_bagian 
							LEFT JOIN mt_karyawan ON tc_registrasi.kode_dokter=mt_karyawan.kode_dokter
							WHERE tc_registrasi.kode_perusahaan=120 ".$sql1." ".$sql2." ".$sql3." ".$sql4." GROUP BY tc_registrasi.kode_bagian_masuk,mt_bagian.nama_bagian) as per_a
							",''.$this->table.'.kode_bagian_masuk=per_a.kode_bagian_masuk','left'
						);

			$this->db->join("
						(SELECT tc_registrasi.kode_bagian_masuk,mt_bagian.nama_bagian,count(tc_registrasi.no_registrasi) as jaminan FROM tc_registrasi 
						LEFT JOIN mt_master_pasien ON tc_registrasi.no_mr=mt_master_pasien.no_mr 
						LEFT JOIN mt_perusahaan ON tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan 
						LEFT JOIN mt_bagian ON tc_registrasi.kode_bagian_masuk=mt_bagian.kode_bagian 
						LEFT JOIN mt_karyawan ON tc_registrasi.kode_dokter=mt_karyawan.kode_dokter
						WHERE tc_registrasi.kode_kelompok=3 AND tc_registrasi.kode_perusahaan<>120 ".$sql1." ".$sql2." ".$sql3." ".$sql4." GROUP BY tc_registrasi.kode_bagian_masuk,mt_bagian.nama_bagian) as per_b
						",''.$this->table.'.kode_bagian_masuk=per_b.kode_bagian_masuk','left'
					);

			$this->db->join("
						(SELECT tc_registrasi.kode_bagian_masuk,mt_bagian.nama_bagian,count(tc_registrasi.no_registrasi) as umum FROM tc_registrasi 
						LEFT JOIN mt_master_pasien ON tc_registrasi.no_mr=mt_master_pasien.no_mr 
						LEFT JOIN mt_perusahaan ON tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan 
						LEFT JOIN mt_bagian ON tc_registrasi.kode_bagian_masuk=mt_bagian.kode_bagian 
						LEFT JOIN mt_karyawan ON tc_registrasi.kode_dokter=mt_karyawan.kode_dokter
						WHERE tc_registrasi.kode_kelompok<>3 ".$sql1." ".$sql2." ".$sql3." ".$sql4." GROUP BY tc_registrasi.kode_bagian_masuk,mt_bagian.nama_bagian) as per_c
						",''.$this->table.'.kode_bagian_masuk=per_c.kode_bagian_masuk','left'
					);
		} 

		/*if isset parameter*/
		if( $data ) {
			
			if(isset($data['search_by']) AND isset($data['keyword'])){
				$this->db->like('mt_master_pasien.'.$data['search_by'].'', $data['keyword']);
			}

			if (isset($data['from_tgl']) AND $data['from_tgl'] != '' || isset($data['to_tgl']) AND $data['to_tgl'] != '') {
				$this->db->where("convert(varchar,tc_registrasi.tgl_jam_masuk,23) between '".$data['from_tgl']."' and '".$data['to_tgl']."'");	
				
	        }else{
				$this->db->where('CONVERT(VARCHAR(11),tc_registrasi.tgl_jam_masuk,102) >= CONVERT(VARCHAR(11),getdate(),102) ');
			}

	        if (isset($data['bagian']) AND $data['bagian'] != '') {
	            $this->db->where('tc_registrasi.kode_bagian_masuk', $data['bagian']);	
	        }

	        if (isset($data['dokterHidden']) AND $data['dokterHidden'] != 0) {
	            $this->db->where('tc_registrasi.kode_dokter', $data['dokterHidden']);	
	        }
		
		}else{
			$this->db->where(array('YEAR(tc_registrasi.tgl_jam_masuk)' => date('Y'), 'MONTH(tc_registrasi.tgl_jam_masuk)' => date('m'), 'DAY(tc_registrasi.tgl_jam_masuk)' => date('d')));
		} 

		if($data['export_by'] == 'general'){
			$this->db->group_by('tc_registrasi.kode_bagian_masuk,mt_bagian.nama_bagian,per_a.bpjs,per_b.jaminan, per_c.umum');
		} else {
			$this->db->order_by('tc_registrasi.tgl_jam_masuk','asc');
		}

		
		$query = $this->db->get();
		//print_r($this->db->last_query());
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->_main_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.no_registrasi',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.no_registrasi',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}


	public function update($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$get_data = $this->get_by_id($id);
		$this->db->where_in(''.$this->table.'.no_registrasi', $id);
		return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
	}

	public function save_pm($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}


}
