<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Etiket_obat_model extends CI_Model {

	var $table = 'fr_hisbebasluar_v';
	var $column = array('kode_trans_far','nama_pasien', 'dokter_pengirim', 'no_resep', 'no_kunjungan', 'no_mr');
	var $select = 'kode_trans_far,nama_pasien,dokter_pengirim,no_resep,no_kunjungan,no_mr, kode_pesan_resep, nama_pelayanan, tgl_trans';

	var $order = array('tgl_trans' => 'DESC', 'kode_pesan_resep' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->where('status_transaksi', 1);
		$this->db->group_by($this->select);

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if(isset($_GET['no_mr']) AND $_GET['no_mr']!=0 ){
			if($_GET['no_mr']!='' or $_GET['no_mr']!=0){
				$this->db->where('fr_hisbebasluar_v.no_mr', $_GET['no_mr']);
			}
		}

		if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
			$this->db->like('fr_hisbebasluar_v.'.$_GET['search_by'].'', $_GET['keyword']);
		}

		if(isset($_GET['dokter']) AND $_GET['dokter']!=0 ){
			if($_GET['dokter']!='' or $_GET['dokter']!=0){
				$this->db->where('fr_hisbebasluar_v.kode_dokter', $_GET['dokter']);
			}
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' or isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
            $this->db->where("fr_hisbebasluar_v.tgl_trans >= '".$this->tanggal->selisih($_GET['from_tgl'],'-0')."'" );
            $this->db->where("fr_hisbebasluar_v.tgl_trans <= '".$this->tanggal->selisih($_GET['to_tgl'],'+1')."'" );
        }else{
        	$this->db->where('DATEDIFF(Hour, tgl_trans, getdate())<=12');
        }

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
		//print_r($this->db->last_query());die;
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
		$this->_get_datatables_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.kode_pesan_resep',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_pesan_resep',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		return $this->db->insert_id();;
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

	public function get_detail_by_kode_brg($kode_brg)
	{
		return $this->db->join('mt_jenis_obat','mt_jenis_obat.kode_jenis=mt_barang.kode_jenis','left')
						->join('mt_pabrik','mt_pabrik.id_pabrik=mt_barang.id_pabrik','left')
						->get_where('mt_barang', array('kode_brg' => $kode_brg))->row();		
	}

	public function get_detail_by_kode_tr_resep($id)
	{
		$this->db->from('fr_tc_far_detail_log a');	
		$this->db->where('relation_id', $id);	
		return $this->db->get()->row();	
	}

	private function _main_query_detail(){
		$this->db->select('*');
		$this->db->from('fr_tc_far_detail_log');
	}

	private function _get_datatables_query_detail()
	{
		
		$this->_main_query_detail();
		$this->db->where('fr_tc_far_detail_log.kode_pesan_resep', $_GET['relationId']);
		$this->db->where('fr_tc_far_detail_log.flag_resep', $_GET['flag']);

	}
	
	function get_detail_resep_data()
	{
		$this->_get_datatables_query_detail();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_detail()
	{
		$this->_get_datatables_query_detail();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_detail()
	{
		$this->_main_query_detail();
		return $this->db->count_all_results();
	}



}
