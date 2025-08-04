<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengambilan_resep_iter_model extends CI_Model {

	var $table = 'fr_tc_resep_iter';
	var $column = array('fr_tc_far.kode_trans_far','nama_pasien', 'dokter_pengirim', 'no_resep', 'fr_tc_far.no_mr');
	var $select = 'fr_tc_resep_iter.id_iter, fr_tc_far.no_registrasi, fr_tc_far.kode_trans_far,nama_pasien,dokter_pengirim,no_resep,fr_tc_far.no_kunjungan,fr_tc_far.no_mr, fr_tc_resep_iter.kode_pesan_resep, tgl_trans, alamat_pasien, telpon_pasien, fr_tc_far.status_transaksi, tc_registrasi.no_sep, mt_perusahaan.nama_perusahaan, tc_registrasi.kode_perusahaan, mt_bagian.nama_bagian, fr_tc_far.kode_bagian_asal, iter, fr_tc_far.kode_dokter, status_iter, tgl_pengambilan_resep';

	var $order = array('tgl_trans' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('fr_tc_far','fr_tc_far.kode_trans_far=fr_tc_resep_iter.kode_trans_far','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=fr_tc_far.no_registrasi','left');
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=fr_tc_far.kode_bagian_asal','left');
		$this->db->group_by($this->select);

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if(isset($_GET['search_by']) AND $_GET['search_by'] != '' AND isset($_GET['keyword']) AND $_GET['keyword'] != '' ){
			$this->db->like('fr_tc_far.'.$_GET['search_by'].'', $_GET['keyword']);
		}

		if(isset($_GET['search_by']) AND $_GET['search_by'] != '' AND isset($_GET['keyword']) AND $_GET['keyword'] != '' ){
			if (in_array($_GET['search_by'], array('no_mr', 'nama_pasien') )) {
				// no action
				$this->db->like('fr_tc_far.'.$_GET['search_by'].'', $_GET['keyword']);
			}else{
				$this->db->like('fr_tc_far.'.$_GET['search_by'].'', $_GET['keyword']);
			}
		}else{
			$this->db->where('DATEDIFF(Day, tgl_trans, getdate())<=7');
			
		}

		if( isset($_GET['bagian']) AND $_GET['bagian'] != 0 ){
			$this->db->where('fr_tc_far.kode_bagian_asal', $_GET['bagian']);
		}


		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' or isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("CAST(fr_tc_far.tgl_trans) BETWEEN '".$_GET['from_tgl']."' AND '".$_GET['to_tgl']."'");
        }

		if( isset($_GET['no_mr']) AND $_GET['no_mr'] != 0 ){
			$this->db->where('fr_tc_far.no_mr', $_GET['no_mr']);
		}

		if( isset($_GET['flag']) AND $_GET['flag'] != 'All' ){
			$this->db->like('fr_tc_far.no_resep', $_GET['flag']);
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

	public function get_detail($kode_trans_far)
	{
		$query = $this->db->select("fr_tc_far_detail_log.id_fr_tc_far_detail_log,fr_tc_far_detail_log.tgl_input,fr_tc_far_detail_log.kode_brg,fr_tc_far_detail_log.nama_brg,fr_tc_far_detail_log.satuan_kecil,fr_tc_far_detail_log.jumlah_pesan,fr_tc_far_detail_log.jumlah_tebus,fr_tc_far_detail_log.harga_jual_satuan,fr_tc_far_detail_log.sub_total,fr_tc_far_detail_log.jasa_r,fr_tc_far_detail_log.jasa_produksi,fr_tc_far_detail_log.diskon,fr_tc_far_detail_log.total,fr_tc_far_detail_log.urgensi,fr_tc_far_detail_log.flag_resep,fr_tc_far_detail_log.dosis_obat,fr_tc_far_detail_log.relation_id,fr_tc_far_detail_log.satuan_obat,fr_tc_far_detail_log.anjuran_pakai,fr_tc_far_detail_log.kode_pesan_resep,fr_tc_far_detail_log.status_input,fr_tc_far_detail_log.kode_trans_far,fr_tc_far_detail_log.jumlah_obat_23,fr_tc_far_detail_log.status_tebus,fr_tc_far_detail_log.jumlah_retur,fr_tc_far_detail_log.tgl_retur,resep_ditangguhkan,
		prb_ditangguhkan,dosis_per_hari")
		->select("(select top 1 jml_sat_kcl from mt_depo_stok where kode_brg=fr_tc_far_detail_log.kode_brg AND kode_bagian='060101') as stok_akhir_depo, fr_tc_far_detail_log.relation_id as kd_tr_resep,  fr_tc_log_mutasi_obat.jumlah_mutasi_obat")
		->join('fr_tc_far', 'fr_tc_far.kode_trans_far=fr_tc_far_detail_log.kode_trans_far', 'left')
		->join('fr_tc_log_mutasi_obat','fr_tc_log_mutasi_obat.kd_tr_resep=fr_tc_far_detail_log.relation_id','left')
		->order_by('fr_tc_far_detail_log.relation_id', 'ASC')
		->group_by("fr_tc_far_detail_log.id_fr_tc_far_detail_log,fr_tc_far_detail_log.tgl_input,fr_tc_far_detail_log.kode_brg,fr_tc_far_detail_log.nama_brg,fr_tc_far_detail_log.satuan_kecil,fr_tc_far_detail_log.jumlah_pesan,fr_tc_far_detail_log.jumlah_tebus,fr_tc_far_detail_log.harga_jual_satuan,fr_tc_far_detail_log.sub_total,fr_tc_far_detail_log.jasa_r,fr_tc_far_detail_log.jasa_produksi,fr_tc_far_detail_log.diskon,fr_tc_far_detail_log.total,fr_tc_far_detail_log.urgensi,fr_tc_far_detail_log.flag_resep,fr_tc_far_detail_log.dosis_obat,fr_tc_far_detail_log.relation_id,fr_tc_far_detail_log.satuan_obat,fr_tc_far_detail_log.anjuran_pakai,fr_tc_far_detail_log.kode_pesan_resep,fr_tc_far_detail_log.status_input,fr_tc_far_detail_log.kode_trans_far,fr_tc_far_detail_log.jumlah_obat_23,fr_tc_far_detail_log.status_tebus,fr_tc_far_detail_log.jumlah_retur,fr_tc_far_detail_log.tgl_retur,dosis_per_hari,
		prb_ditangguhkan, fr_tc_log_mutasi_obat.jumlah_mutasi_obat, resep_ditangguhkan")
		->get_where('fr_tc_far_detail_log', array('fr_tc_far_detail_log.kode_trans_far' => $kode_trans_far))->result();		

		return $query;
	}

	public function get_detail_group_by_id($kode_trans_far)
	{

		$query = $this->get_detail($kode_trans_far);
		foreach ($query as $key => $value) {
			$getData[$value->id_fr_tc_far_detail_log] = $value;
		}
		

		return $getData;
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

	public function get_riwayat_iter($kode_trans_far){
		$this->_main_query();
		$this->db->where('referensi', $kode_trans_far);
		return $this->db->get()->result();
	}
	
}
