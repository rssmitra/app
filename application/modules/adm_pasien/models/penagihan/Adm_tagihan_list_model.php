<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adm_tagihan_list_model extends CI_Model {

	var $table = 'tc_tagih';
	var $column = array('nama_tertagih');
	var $select = 'a.id_tc_tagih, no_invoice_tagih, nama_tertagih, a.jenis_tagih, tgl_tagih, diskon, id_tertagih, tgl_jt_tempo, a.tr_yg_diskon';
	var $order = array('id_tc_tagih' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('SUM(CAST(b.jumlah_dijamin as INT)) as jumlah_tagihan, SUM(CAST(jumlah_bayar as INT)) as jumlah_bayar');
		$this->db->from($this->table.' a');
		$this->db->join('tc_tagih_det b', 'b.id_tc_tagih=a.id_tc_tagih', 'left');
		$this->db->join('tc_bayar_tagih c', 'c.id_tc_tagih=a.id_tc_tagih', 'left');
		// $this->db->where('(a.id_tertagih NOT IN (120, 221, 0, 299))');

		$this->db->group_by($this->select);

		if(isset($_GET['no_invoice']) AND $_GET['no_invoice'] != ''){
			$this->db->like('a.no_invoice_tagih', $_GET['no_invoice']);		
		}

		if(isset($_GET['jenis_pelayanan']) AND $_GET['jenis_pelayanan'] != ''){
			$this->db->where('a.id_tc_tagih IN (select kd_inv_persh_tx as id_tc_tagih from tc_trans_kasir where seri_kuitansi='."'".$_GET['jenis_pelayanan']."'".' group by kd_inv_persh_tx)');		
		}
		
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,tgl_tagih,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
		}else{
			$this->db->where("MONTH(tgl_tagih)", date('m'));
			$this->db->where("YEAR(tgl_tagih)", date('Y'));
		}
		
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
		$query = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		return $query;
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

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in('a.kode_perusahaan',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('a.kode_perusahaan',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function get_hist_inv($kode_perusahaan){
		$this->db->from('tc_tagih a');
		$this->db->where('a.id_tertagih', $kode_perusahaan);
		$this->db->where('MONTH(a.tgl_tagih)', date('m'));
		$this->db->order_by('tgl_tagih', 'DESC');

		return $this->db->get()->result();
	}

	public function get_invoice_detail($id_tagih){
		$this->db->select('a.*, CAST(a.jumlah_dijamin as INT) as jumlah_tagih_int, CAST(a.jumlah_tagih as INT) as beban_pasien_int, b.no_invoice_tagih, c.tgl_jam, b.tgl_tagih, b.tgl_jt_tempo, b.nama_tertagih, d.alamat, d.telpon1, b.tr_yg_diskon as rp_diskon, b.diskon');
		$this->db->from('tc_tagih_det a');
		$this->db->join('tc_tagih b', 'b.id_tc_tagih=a.id_tc_tagih','left');
		$this->db->join('tc_trans_kasir c', 'c.kode_tc_trans_kasir=a.kode_tc_trans_kasir','left');
		$this->db->join('mt_perusahaan d','d.kode_perusahaan=b.id_tertagih','left');
		$this->db->where('a.id_tc_tagih', $id_tagih);
		$this->db->order_by('kode_tc_trans_kasir', 'ASC');
		$query = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		return $query;
	}

	public function get_billing_detail($kode_tc_trans_kasir){
		$this->db->select('a.*, CAST((bill_rs + bill_dr1 + bill_dr2 + bill_dr3) as INT) as subtotal');
		$this->db->from('tc_trans_pelayanan a');
		$this->db->where('a.kode_tc_trans_kasir', $kode_tc_trans_kasir);
		$this->db->order_by('tgl_transaksi', 'ASC');
		$query = $this->db->get()->result();
		print_r($this->db->last_query());die;
		return $query;
	}

	public function get_detail_pasien($kode_perusahaan){

		$this->db->select('a.*');
		$this->db->select('CAST(bill as INT) as bill_int, (CAST(tunai as INT) + CAST(debet as INT) + CAST(kredit as INT)) as beban_pasien, CAST(nk_perusahaan as INT) as nk_perusahaan_int');
		$this->db->from($this->table.' a');
		$this->db->join('mt_perusahaan b','b.kode_perusahaan=a.kode_perusahaan','left');
		$this->db->where('(a.nk_perusahaan > 0 AND a.kd_inv_persh_tx IS NULL AND a.kode_perusahaan NOT IN (120, 221, 0, 299))');
		$this->db->where('a.kode_perusahaan', $kode_perusahaan);		
		$this->db->where('a.seri_kuitansi', $_GET['jenis_pelayanan']);		
		$this->db->where("convert(varchar,tgl_jam,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");	
		$query = $this->db->get()->result();
		return $query;
	}

	// Delete tc_tagih, tc_tagih_det by id_tc_tagih, and update tc_trans_kasir.kd_inv_persh_tx to null
	public function delete_by_id($id){
		// delete from tc_tagih
		$this->db->where('tc_tagih.id_tc_tagih', $id);
		$this->db->delete('tc_tagih');

		// delete from tc_tagih_det
		$this->db->where('tc_tagih_det.id_tc_tagih', $id);
		$this->db->delete('tc_tagih_det');

		// update tc_trans_kasir.kd_inv_persh_tx back to null
		$this->db->update('tc_trans_kasir', ['kd_inv_persh_tx'=> NULL] , ['kd_inv_persh_tx' => $id] );

		return true;
	} 

}
