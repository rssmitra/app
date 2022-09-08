<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses_resep_prb_model extends CI_Model {

	var $table = 'fr_tc_far';
	var $column = array('fr_tc_far.kode_trans_far','nama_pasien', 'dokter_pengirim', 'no_resep', 'no_kunjungan', 'no_mr');
	var $select = 'fr_tc_far.kode_trans_far,fr_tc_far.nama_pasien,dokter_pengirim,no_resep,no_kunjungan,fr_tc_far.no_mr, kode_pesan_resep, nama_pelayanan, tgl_trans, no_sep, verifikasi_prb, proses_mutasi_prb, almt_ttp_pasien, tlp_almt_ttp';

	var $order = array('tgl_trans' => 'DESC', 'fr_tc_far.kode_trans_far' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('(SELECT SUM(jumlah_mutasi_obat) FROM fr_tc_log_mutasi_obat WHERE kode_trans_far=fr_tc_far.kode_trans_far) as jumlah_mutasi,
		(select SUM(ttl_hutang) from view_fr_hutang_obat_pasien where kode_trans_far=fr_tc_far.kode_trans_far) as ttl_hutang');
		$this->db->from($this->table);
		$this->db->join('fr_mt_profit_margin','fr_tc_far.kode_profit = fr_mt_profit_margin.kode_profit','left');
		$this->db->join('tc_registrasi','fr_tc_far.no_registrasi = tc_registrasi.no_registrasi','left');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr = fr_tc_far.no_mr','left');
		
		$this->db->group_by($this->select);

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		$this->db->where('kode_trans_far IN ( select kode_trans_far from view_fr_hutang_obat_pasien where sisa_hutang > 0 group by kode_trans_far)');
		$this->db->like('no_resep','RJ');

		if(isset($_GET['search_by']) AND $_GET['search_by'] != '' AND isset($_GET['keyword']) AND $_GET['keyword'] != '' ){
			if($_GET['search_by'] == 'nama_pasien'){
				$this->db->like('fr_tc_far.'.$_GET['search_by'].'', $_GET['keyword']);
			}else{
				$this->db->where('fr_tc_far.'.$_GET['search_by'].'', $_GET['keyword']);
			}
		}
		else{
        	$this->db->where('DATEDIFF(Day, tgl_trans, getdate()) <= 30');
        }

		if( isset($_GET['bagian']) AND $_GET['bagian'] != 0 ){
			$this->db->where('fr_tc_far.kode_bagian_asal', $_GET['bagian']);
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' or isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
            $this->db->where("CAST(fr_tc_far.tgl_trans as DATE) BETWEEN '".$_GET['from_tgl']."' AND '".$_GET['to_tgl']."' " );
        }
		

		$i = 0;
	
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
		$this->db->where('fr_tc_far.verifikasi_prb', 1);
		// $this->db->having('(select SUM(ttl_hutang) from view_fr_hutang_obat_pasien where kode_trans_far=fr_tc_far.kode_trans_far) > (SELECT SUM(jumlah_mutasi_obat) FROM fr_tc_log_mutasi_obat WHERE kode_trans_far=fr_tc_far.kode_trans_far)');

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
		return $this->db->select("fr_tc_far_detail_log.id_fr_tc_far_detail_log,fr_tc_far_detail_log.tgl_input,fr_tc_far_detail_log.kode_brg,fr_tc_far_detail_log.nama_brg,fr_tc_far_detail_log.satuan_kecil,fr_tc_far_detail_log.jumlah_pesan,fr_tc_far_detail_log.jumlah_tebus,fr_tc_far_detail_log.harga_jual_satuan,fr_tc_far_detail_log.sub_total,fr_tc_far_detail_log.jasa_r,fr_tc_far_detail_log.jasa_produksi,fr_tc_far_detail_log.diskon,fr_tc_far_detail_log.total,fr_tc_far_detail_log.urgensi,fr_tc_far_detail_log.flag_resep,fr_tc_far_detail_log.dosis_obat,fr_tc_far_detail_log.relation_id,fr_tc_far_detail_log.satuan_obat,fr_tc_far_detail_log.anjuran_pakai,fr_tc_far_detail_log.kode_pesan_resep,fr_tc_far_detail_log.status_input,fr_tc_far_detail_log.kode_trans_far,fr_tc_far_detail_log.jumlah_obat_23,fr_tc_far_detail_log.status_tebus,fr_tc_far_detail_log.jumlah_retur,fr_tc_far_detail_log.tgl_retur,resep_ditangguhkan,
		prb_ditangguhkan,")->select("(select top 1 jml_sat_kcl from mt_depo_stok where kode_brg=fr_tc_far_detail_log.kode_brg AND kode_bagian='060101') as stok_akhir_depo, fr_tc_far_detail_log_prb.jumlah, fr_tc_far_detail_log_prb.kd_tr_resep, fr_tc_far_detail_log_prb.id_fr_tc_far_detail_log_prb, fr_tc_log_mutasi_obat.jumlah_mutasi_obat")->join('fr_tc_far', 'fr_tc_far.kode_trans_far=fr_tc_far_detail_log_prb.kode_trans_far', 'left')->join('fr_tc_far_detail_log', '(fr_tc_far_detail_log.kode_trans_far=fr_tc_far_detail_log_prb.kode_trans_far AND fr_tc_far_detail_log.relation_id=fr_tc_far_detail_log_prb.kd_tr_resep)', 'left')->join('fr_tc_log_mutasi_obat','fr_tc_log_mutasi_obat.kd_tr_resep=fr_tc_far_detail_log_prb.kd_tr_resep','left')->order_by('fr_tc_far_detail_log_prb.kd_tr_resep', 'ASC')->group_by("fr_tc_far_detail_log.id_fr_tc_far_detail_log,fr_tc_far_detail_log.tgl_input,fr_tc_far_detail_log.kode_brg,fr_tc_far_detail_log.nama_brg,fr_tc_far_detail_log.satuan_kecil,fr_tc_far_detail_log.jumlah_pesan,fr_tc_far_detail_log.jumlah_tebus,fr_tc_far_detail_log.harga_jual_satuan,fr_tc_far_detail_log.sub_total,fr_tc_far_detail_log.jasa_r,fr_tc_far_detail_log.jasa_produksi,fr_tc_far_detail_log.diskon,fr_tc_far_detail_log.total,fr_tc_far_detail_log.urgensi,fr_tc_far_detail_log.flag_resep,fr_tc_far_detail_log.dosis_obat,fr_tc_far_detail_log.relation_id,fr_tc_far_detail_log.satuan_obat,fr_tc_far_detail_log.anjuran_pakai,fr_tc_far_detail_log.kode_pesan_resep,fr_tc_far_detail_log.status_input,fr_tc_far_detail_log.kode_trans_far,fr_tc_far_detail_log.jumlah_obat_23,fr_tc_far_detail_log.status_tebus,fr_tc_far_detail_log.jumlah_retur,fr_tc_far_detail_log.tgl_retur,
		prb_ditangguhkan,fr_tc_far_detail_log_prb.jumlah, fr_tc_far_detail_log_prb.kd_tr_resep, fr_tc_far_detail_log_prb.id_fr_tc_far_detail_log_prb, fr_tc_log_mutasi_obat.jumlah_mutasi_obat, resep_ditangguhkan")->get_where('å', array('fr_tc_far_detail_log_prb.kode_trans_far' => $kode_trans_far))->result();		
	}

	public function get_log_mutasi($kode_trans_far){
		$this->db->select('fr_tc_log_mutasi_obat.kode_log_mutasi_obat, fr_tc_log_mutasi_obat.created_date, fr_tc_log_mutasi_obat.created_by, jumlah_mutasi_obat, fr_tc_far_detail_log_prb.nama_brg, fr_tc_far_detail_log_prb.satuan_kecil, harga_satuan, mt_barang.nama_brg as nama_brg_update');
		$this->db->group_by('fr_tc_log_mutasi_obat.kode_log_mutasi_obat, jumlah_mutasi_obat, fr_tc_far_detail_log_prb.nama_brg, fr_tc_far_detail_log_prb.satuan_kecil, harga_satuan, mt_barang.nama_brg, fr_tc_log_mutasi_obat.created_date, fr_tc_log_mutasi_obat.created_by');
		$this->db->join('fr_tc_far_detail_log_prb','fr_tc_far_detail_log_prb.kd_tr_resep=fr_tc_log_mutasi_obat.kd_tr_resep','left');
		$this->db->join('mt_barang','mt_barang.kode_brg=fr_tc_log_mutasi_obat.kode_brg','left');
		$this->db->order_by('kode_log_mutasi_obat','ASC');
		if(isset($_GET['kode_log_mutasi']) AND $_GET['kode_log_mutasi'] != ''){
			$this->db->where('kode_log_mutasi_obat', $_GET['kode_log_mutasi']);
		}
		$query = $this->db->get_where('fr_tc_log_mutasi_obat', array('fr_tc_log_mutasi_obat.kode_trans_far' => $kode_trans_far))->result();
		$getData = [];
		foreach ($query as $key => $value) {
			$getData[$value->kode_log_mutasi_obat][] = $value;
		}
		return $getData;
	}


}
