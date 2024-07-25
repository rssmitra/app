<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_pm_model extends CI_Model {

	var $table = 'mt_master_pasien';

	var $column = array('');

	var $select = '';

	var $order = array('mt_master_pasien.nama_pasien' => 'ASC');

	public function __construct()
	
	{
		
		parent::__construct();
	
	}

	private function _main_query($params=''){
		
		$this->db->select($this->select);
		
		$this->db->from($this->table);

		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=mt_master_pasien.kode_perusahaan','left');
		$this->db->join('mt_nasabah','mt_nasabah.kode_kelompok=mt_master_pasien.kode_kelompok','left');
		
		/*check level user*/
		$this->authuser->filtering_data_by_level_user($this->table, $this->session->userdata('user')->user_id);
	
	}

	private function _get_datatables_query($params='')
	
	{
		
		$this->_main_query();

		$i = 0;

		foreach ($this->column as $item) 
		
		{
			
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item
					, $_POST['search']['value']);
			
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

	function get_datatables($params='')
	
	{
		
		$this->_get_datatables_query();
		
		if($_POST['length'] != -1)
		
		$this->db->limit($_POST['length'], $_POST['start']);
		
		$query = $this->db->get();
		
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
			
			$this->db->where_in(''.$this->table.'.id_mt_master_pasien',$id);
			
			$query = $this->db->get();
			
			return $query->result();
		
		}else{
			
			$this->db->where(''.$this->table.'.id_mt_master_pasien',$id);
			
			$query = $this->db->get();
			
			return $query->row();
		
		}
	
	}

	public function get_by_mr($mr)
	
	{
		
		$this->_main_query();
		
		$this->db->where(''.$this->table.'.no_mr', $mr);
			
		$query = $this->db->get();
		
		return $query->row();
	
	}


	public function save($data)
	
	{
		
		$this->db->insert($this->table, $data);
		
		return $this->db->insert_id();
	
	}

	public function update($where, $data)
	
	{
		
		$this->db->update($this->table, $data, $where);
		
		return $this->db->affected_rows();
	
	}

	public function delete_by_id($id)
	
	{
		
		$get_data = $this->get_by_id($id);
		
		$this->db->where_in(''.$this->table.'.id_mt_master_pasien', $id);
		
		return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
	
	}

	public function get_hasil_pm($no_registrasi, $no_kunjungan, $kode_bagian='', $flag_mcu='' ){

		//pm_tc_penunjang//
		/*select * from pm_tc_penunjang where kode_penunjang=327018*/
		if($kode_bagian==''){
			$kode_bagian='050101';
		}

		if($flag_mcu==1){
			$query_1 = "select * from tc_trans_pelayanan_paket_mcu where no_kunjungan=".$no_kunjungan."";
			
			//pm_mt_standarhasil pm_tc_hasilpenunjang//
			$query = 'SELECT a.kode_trans_pelayanan,b.detail_item_1, b.nama_pemeriksaan, b.standar_hasil_wanita, b.standar_hasil_pria, b.satuan , a.keterangan, a.hasil from pm_tc_hasilpenunjang a, pm_mt_standarhasil b 
			where a.kode_mt_hasilpm=b.kode_mt_hasilpm AND a.kode_trans_pelayanan in (
			select kode_trans_pelayanan_paket_mcu from tc_trans_pelayanan_paket_mcu where no_kunjungan='.$no_kunjungan.') AND b.kode_bagian='."'".$kode_bagian."'".'
			order by b.nama_pemeriksaan ASC';

		}else{
			//tc_trans_pelayanan
			$query_1 = "select * from tc_trans_pelayanan where no_kunjungan=".$no_kunjungan."";
			
			//pm_mt_standarhasil pm_tc_hasilpenunjang//
			$query = 'select a.kode_trans_pelayanan, b.detail_item_1, b.nama_pemeriksaan, b.standar_hasil_wanita, b.standar_hasil_pria, b.satuan , 
			a.keterangan, a.hasil from pm_tc_hasilpenunjang a, pm_mt_standarhasil b 
			where a.kode_mt_hasilpm=b.kode_mt_hasilpm AND a.kode_trans_pelayanan in (
			select kode_trans_pelayanan from tc_trans_pelayanan where no_kunjungan='.$no_kunjungan.') AND b.kode_bagian='."'".$kode_bagian."'".' group by a.kode_trans_pelayanan, b.detail_item_1, b.nama_pemeriksaan, b.standar_hasil_wanita, b.standar_hasil_pria, b.satuan , a.keterangan, a.hasil 
			order by b.nama_pemeriksaan ASC';
			
		}

		$data_1 = $this->db->query($query_1)->result();
		$data = $this->db->query($query)->result();

		// print_r($this->db->last_query());die;
		$get_data = [];
		foreach ($data as $key => $value) {
			$get_data[$value->kode_trans_pelayanan][] = $value;
		}
		
		foreach ($data_1 as $key_2 => $value_2) {
			$getDataAll[] = array(
				'nama_pemeriksaan' => $value_2->nama_tindakan, 
				'detail' => ($flag_mcu=='')?$get_data[$value_2->kode_trans_pelayanan]:$get_data[$value_2->kode_trans_pelayanan_paket_mcu],
			);
		}
		// echo '<pre>';print_r($getDataAll);die;
		$html = '';
		$html .= '<table width="100%">';
		$html .= '<tr>';
		$html .= '<th>Jenis Test</th>';
		$html .= '<th class="center">Hasil</th>';
		$html .= '<th class="center">Nilai Standar</th>';
		$html .= '<th class="center">Satuan</th>';
		$html .= '<th>Keterangan</th>';
		$html .= '</tr>';
		$no = 0;
		foreach ($getDataAll as $key_title => $val_title) { $no++;
			$html .= '<tr>';
			$html .= '<td colspan="4"><b>'.$no.'. '.$val_title['nama_pemeriksaan'].'</b></td>';
			$html .= '</tr>';
			foreach ($val_title['detail'] as $key => $val_dt) {
				$detail_item = ($val_dt->detail_item_1!='' || $val_dt->detail_item_1!= NULL)?'('.$val_dt->detail_item_1.')':'';
				$html .= '<tr>';
				$html .= '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$val_dt->nama_pemeriksaan.' '.$detail_item.'</td>';
				$html .= '<td align="center">'.$val_dt->hasil.'</td>';
				$html .= '<td align="center">'.$val_dt->standar_hasil_pria.'</td>';
				$html .= '<td align="center">'.$val_dt->satuan.'</td>';
				$html .= '<td>'.$val_dt->keterangan.'</td>';
				$html .= '</tr>';
			}
		}
		
		$html .= '</table>';

		echo '<pre>';print_r($data);die;

		return array('html' => $html, 'data' => $data);

	}


}
