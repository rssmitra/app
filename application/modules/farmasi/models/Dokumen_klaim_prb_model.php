<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dokumen_klaim_prb_model extends CI_Model {

	var $table = 'fr_tc_far';
	var $column = array('fr_tc_far.kode_trans_far','nama_pasien', 'dokter_pengirim', 'no_resep', 'no_kunjungan', 'no_mr');
	var $select = 'fr_tc_far.kode_trans_far,nama_pasien,dokter_pengirim,no_resep,no_kunjungan,fr_tc_far.no_mr, kode_pesan_resep, nama_pelayanan, tgl_trans, no_sep, verifikasi_prb';

	var $order = array('tgl_trans' => 'DESC', 'fr_tc_far.kode_trans_far' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('fr_mt_profit_margin','fr_tc_far.kode_profit = fr_mt_profit_margin.kode_profit','left');
		$this->db->join('tc_registrasi','fr_tc_far.no_registrasi = tc_registrasi.no_registrasi','left');
		// $this->db->join('(select kode_trans_far, SUM(sub_total) as total from fr_tc_far_detail_log_prb group by fr_tc_far_detail_log_prb.kode_trans_far) as log_detail','log_detail.kode_trans_far = fr_tc_far.kode_trans_far','left');
		$this->db->where('fr_tc_far.kode_trans_far in (select kode_trans_far from fr_tc_far_detail_log_prb group by fr_tc_far_detail_log_prb.kode_trans_far)');
		$this->db->where('fr_tc_far.verifikasi_prb', 1);
		$this->db->group_by($this->select);

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if(isset($_GET['search_by']) AND $_GET['search_by'] != '' AND isset($_GET['keyword']) AND $_GET['keyword'] != '' ){
			if($_GET['search_by'] == 'no_sep'){
				$this->db->where('tc_registrasi.'.$_GET['search_by'].'', $_GET['keyword']);
			}else{
				$this->db->like('fr_tc_far.'.$_GET['search_by'].'', $_GET['keyword']);
			}
		}

		if( isset($_GET['bagian']) AND $_GET['bagian'] != 0 ){
			$this->db->where('fr_tc_far.kode_bagian_asal', $_GET['bagian']);
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' or isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
            $this->db->where("CAST(fr_tc_far.tgl_trans as DATE) BETWEEN '".$_GET['from_tgl']."' AND '".$_GET['to_tgl']."' " );
        }else{
        	$this->db->where('DATEDIFF(Day, tgl_trans, getdate())<=90');
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
		// print_r($this->db->last_query());die;
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
			$this->db->where_in(''.$this->table.'.kode_trans_far',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_trans_far',$id);
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

	public function get_header_data($kode_trans_far){
		$this->db->from('fr_tc_far a');
		$this->db->join('tc_registrasi b', 'b.no_registrasi=a.no_registrasi', 'left');
		$this->db->join('th_riwayat_pasien e', 'e.no_registrasi=a.no_registrasi', 'left');
		$this->db->join('mt_master_pasien c', 'c.no_mr=a.no_mr', 'left');
		$this->db->join('mt_bagian d', 'd.kode_bagian=a.kode_bagian_asal', 'left');
		$this->db->where('a.kode_trans_far', $kode_trans_far);
		return $this->db->get()->row();
	}
	
	public function get_detail($kode_trans_far)
	{
		return $this->db->join('fr_tc_far', 'fr_tc_far.kode_trans_far=fr_tc_far_detail_log_prb.kode_trans_far', 'left')->get_where('fr_tc_far_detail_log_prb', array('fr_tc_far_detail_log_prb.kode_trans_far' => $kode_trans_far))->result();		
	}

	public function checkIfDokExist($kode_trans_far, $file_name){
        $qry = $this->db->get_where('fr_tc_far_dokumen_klaim_prb', array('kode_trans_far'=>$kode_trans_far,'dok_prb_file_name' => $file_name));
        return ($qry->num_rows() > 0) ? TRUE : FALSE;
    }

    public function getDocumentPDF($kode_trans_far){
        return $this->db->join('fr_tc_far b','b.kode_trans_far=a.kode_trans_far','left')->order_by('dok_prb_id', 'ASC')->get_where('fr_tc_far_dokumen_klaim_prb a', array('a.kode_trans_far'=>$kode_trans_far))->result();
    }

}
