<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adm_tagihan_pelunasan_model extends CI_Model {

	var $table = 'tc_tagih';
	var $column = array('nama_tertagih');
	var $select = 'a.id_tc_tagih, no_invoice_tagih, nama_tertagih, a.jenis_tagih, tgl_tagih, diskon, id_tertagih, tgl_jt_tempo, a.tr_yg_diskon, a.jumlah_tagih, b.statusLunas, c.id_tc_bayar_tagih, c.no_kuitansi_bayar, c.tgl_bayar';
	var $order = array('tgl_tagih' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('SUM(CAST(b.jumlah_billing as INT)) as jumlah_tagihan, SUM(CAST(jumlah_bayar as INT)) as jumlah_bayar');
		$this->db->from($this->table.' a');
		$this->db->join('tc_tagih_det b', 'b.id_tc_tagih=a.id_tc_tagih', 'left');
		$this->db->join('tc_bayar_tagih c', 'c.id_tc_tagih=a.id_tc_tagih', 'left');
		$this->db->where('(a.id_tertagih NOT IN (120, 221, 0, 299))');

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

	public function save($table, $data)
	{
		$this->db->insert($table, $data);
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
			$this->db->where_in('a.id_tc_tagih',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('a.id_tc_tagih',$id);
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
		$this->db->select('a.*, CAST(a.jumlah_tagih as INT) as jumlah_tagih_int, CAST(a.penyesuaian as INT) as beban_pasien_int, b.no_invoice_tagih, c.tgl_jam, b.tgl_tagih, b.tgl_jt_tempo, b.nama_tertagih, d.alamat, d.telpon1');
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

	public function get_invoice_pelunasan_detail($id_tagih){
		$this->db->select('e.id_tc_bayar_tagih,e.id_tc_tagih,e.no_kuitansi_bayar,e.tgl_bayar,e.jumlah_bayar,e.tgl_input,e.tr_yg_diskon,e.metode_pembayaran,e.bank,e.subtotal, f.jumlah_bayar as jumlah_bayar_det, f.keterangan');
		$this->db->select('a.no_mr,a.nama_pasien,a.kode_perusahaan,a.StatusLunas, d.nama_perusahaan, d.alamat, d.telpon1');
		$this->db->from('tc_bayar_tagih e');
		$this->db->join('tc_bayar_tagih_det f', 'f.id_tc_bayar_tagih=e.id_tc_bayar_tagih','left');
		$this->db->join('tc_tagih_det a', 'a.id_tc_tagih_det=f.id_tc_tagih_det','left');
		$this->db->join('tc_tagih b', 'b.id_tc_tagih=a.id_tc_tagih','left');
		$this->db->join('tc_trans_kasir c', 'c.kode_tc_trans_kasir=a.kode_tc_trans_kasir','left');
		$this->db->join('mt_perusahaan d','d.kode_perusahaan=b.id_tertagih','left');
		$this->db->where('e.id_tc_bayar_tagih', $id_tagih);
		$query = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		return $query;
	}

	public function get_inv_lunas_detail($id_tc_bayar_tagih, $id_tc_tagih){
		// tc_tagih_det , tc_bayar_tagih, tc_bayar_tagih_det
		$this->db->select('a.id_tc_tagih, a.no_invoice_tagih, a.tgl_tagih, b.id_tc_bayar_tagih, b.no_kuitansi_bayar, b.tgl_bayar, b.tr_yg_diskon, b.metode_pembayaran, b.bank, c.id_tc_bayar_tagih_det, c.jumlah_bayar, d.no_mr, d.nama_pasien, d.kode_perusahaan, e.nama_perusahaan, e.alamat, e.telpon1');
		$this->db->from('tc_tagih a');
		// $this->db->from('tc_bayar_tagih b');
		$this->db->join('tc_bayar_tagih b', 'b.id_tc_tagih=a.id_tc_tagih','left');
		$this->db->join('tc_bayar_tagih_det c', 'c.id_tc_bayar_tagih=b.id_tc_bayar_tagih','left');
		$this->db->join('tc_tagih_det d', 'd.id_tc_tagih=a.id_tc_tagih','left');
		$this->db->join('mt_perusahaan e', 'e.kode_perusahaan=d.kode_perusahaan','left');
		$this->db->where('a.id_tc_tagih', $id_tc_tagih);
		// $this->db->where('b.id_tc_bayar_tagih', $id_tc_bayar_tagih);
		$this->db->order_by('c.id_tc_bayar_tagih_det', 'DESC');
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
		// print_r($this->db->last_query());die;
		return $query;
	}

	public function get_detail_pasien($id_tc_tagih){

		$this->db->select('a.*');
		$this->db->select('CAST(jumlah_billing as INT) as jumlah_tagih_int, CAST(penyesuaian as INT) as penyesuaian_int');
		$this->db->from('tc_tagih_det a');
		$this->db->where('a.id_tc_tagih', $id_tc_tagih);			
		$query = $this->db->get()->result();
		return $query;
	}

}
