<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Farmasi_pesan_resep_model extends CI_Model {

	var $table = 'fr_tc_pesan_resep';
	var $column = array('fr_tc_pesan_resep.kode_pesan_resep','fr_tc_pesan_resep.tgl_pesan');
	var $select = 'fr_tc_pesan_resep.*, mt_karyawan.nama_pegawai, mt_bagian.nama_bagian';

	var $order = array('fr_tc_pesan_resep.kode_pesan_resep' => 'DESC');

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
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=fr_tc_pesan_resep.kode_dokter','left');
		$this->db->join('mt_bagian',''.$this->table.'.kode_bagian_asal=mt_bagian.kode_bagian','left');
		$this->db->join('mt_master_pasien',''.$this->table.'.no_mr=mt_master_pasien.no_mr','left');
		//$this->db->where('fr_tc_pesan_resep.status_tebus IS NULL');

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
		$this->_main_query();
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

	public function get_by_no_kunj($id)
	{
		$this->_main_query();
		// $this->db->select('fr_tc_far.kode_trans_far, (select count(kode_trans_far) from fr_tc_far_his where kode_trans_far=fr_tc_far.kode_trans_far) as total_retur');
		$this->db->join('fr_tc_far',''.$this->table.'.kode_pesan_resep=fr_tc_far.kode_pesan_resep','left');
		$this->db->where(''.$this->table.'.no_kunjungan',$id);
		$this->db->order_by(''.$this->table.'.kode_pesan_resep','ASC');		
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
		
		
	}

	public function count_all_id($id)
	{
		$this->get_by_no_kunj($id);
		return $this->db->count_all_results();
	}

	public function update($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$get_data = $this->get_by_id($id);
		// delete resep detail
		$this->db->where('kode_pesan_resep', $id)->delete('fr_tc_pesan_resep_detail');
		
		$this->db->where_in(''.$this->table.'.kode_pesan_resep', $id);
		return $this->db->delete($this->table, array('kode_pesan_resep' => $id));
	}

	public function save_pm($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	public function get_detail_data_kunjungan($id)
	{
		$this->db->select('tc_kunjungan.no_registrasi, tc_kunjungan.no_kunjungan, tc_kunjungan.kode_dokter, nama_pegawai, nama_perusahaan, mt_bagian.nama_bagian, th_riwayat_pasien.diagnosa_awal, th_riwayat_pasien.diagnosa_akhir, th_riwayat_pasien.anamnesa, th_riwayat_pasien.kategori_tindakan, tc_registrasi.tgl_jam_masuk, tc_kunjungan.tgl_masuk, th_riwayat_pasien.pemeriksaan, mt_master_pasien.nama_pasien, mt_master_pasien.no_mr, kode_bagian_tujuan, tujuan_poli.nama_bagian as poli_tujuan_kunjungan, asal_poli.nama_bagian as poli_asal_kunjungan, kode_bagian_asal, tc_registrasi.kode_perusahaan, tc_registrasi.kode_kelompok, tc_registrasi.kode_perusahaan');
		$this->db->from('tc_kunjungan');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_registrasi.no_mr','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_registrasi.kode_bagian_masuk','left');
		$this->db->join('mt_bagian as tujuan_poli','tujuan_poli.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left');
		$this->db->join('mt_bagian as asal_poli','asal_poli.kode_bagian=tc_kunjungan.kode_bagian_asal','left');
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_kunjungan.kode_dokter','left');
		$this->db->join('th_riwayat_pasien','th_riwayat_pasien.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->where('tc_kunjungan.no_kunjungan', $id);
		$this->db->order_by('tc_kunjungan.tgl_masuk', 'DESC');

		return $this->db->get()->row();
		
		
	}

	public function get_detail_by_id($id)
	{
		# code...
		$this->db->select('a.kode_trans_far,a.no_resep,a.kode_pesan_resep,a.tgl_trans,a.status_transaksi,d.nama_brg,c.jumlah_tebus,c.biaya_tebus,c.harga_r,b.kode_tc_trans_kasir, c.kode_brg, c.kd_tr_resep, c.jumlah_retur');
		$this->db->from('fr_tc_far_detail c');
		$this->db->join('fr_tc_far a','a.kode_trans_far=c.kode_trans_far','left');
		$this->db->join('tc_trans_pelayanan b','a.kode_trans_far=b.kode_trans_far','left');
		$this->db->join('mt_barang d','c.kode_brg=d.kode_brg','left');
		$this->db->where('a.kode_pesan_resep', $id);
		$this->db->group_by('a.kode_trans_far,a.no_resep,a.kode_pesan_resep,a.tgl_trans,a.status_transaksi,d.nama_brg,c.jumlah_tebus,c.biaya_tebus,c.harga_r,b.kode_tc_trans_kasir, c.kode_brg, c.kd_tr_resep, c.jumlah_retur');
		$this->db->order_by('c.kd_tr_resep', 'ASC');

		return $this->db->get()->result();
	}


}
