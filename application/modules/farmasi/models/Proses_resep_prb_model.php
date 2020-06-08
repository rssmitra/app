<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses_resep_prb_model extends CI_Model {

	var $table = 'fr_tc_far';
	var $column = array('kode_trans_far','nama_pasien', 'dokter_pengirim', 'no_resep', 'no_kunjungan', 'no_mr');
	var $select = 'kode_trans_far,nama_pasien,dokter_pengirim,no_resep,no_kunjungan,fr_tc_far.no_mr, kode_pesan_resep, nama_pelayanan, tgl_trans, no_sep';

	var $order = array('tgl_trans' => 'DESC', 'kode_trans_far' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('fr_mt_profit_margin','fr_tc_far.kode_profit = fr_mt_profit_margin.kode_profit','left');
		$this->db->join('tc_registrasi','fr_tc_far.no_registrasi = tc_registrasi.no_registrasi','left');
		$this->db->where('kode_trans_far in (select kode_trans_far from fr_tc_far_detail_log where jumlah_obat_23 > 0 group by kode_trans_far)');
		$this->db->where('tc_registrasi.kode_perusahaan', 120);
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

	public function get_detail_resep_data($kode_trans_far){
		$this->db->select('b.*, e.nama_pasien, e.dokter_pengirim, e.tgl_trans, e.no_mr, e.created_by, e.no_resep, f.nama_bagian, flag_resep');
		$this->db->from('fr_tc_far_detail_log b');
		$this->db->join('fr_tc_far e','e.kode_trans_far=b.kode_trans_far','left');
		$this->db->join('mt_bagian f','f.kode_bagian=e.kode_bagian_asal','left');
		$this->db->where('b.kode_trans_far', $kode_trans_far);
		$this->db->order_by('b.id_fr_tc_far_detail_log', 'DESC');
		return $this->db->get();
		// print_r($this->db->last_query());die;
	}

	public function get_etiket_data(){
		$data = ($_GET)?$_GET:$_POST;
		$this->db->select('b.kode_brg, b.nama_brg, dosis_obat, aturan_pakai, satuan_obat, jumlah_obat, catatan_lainnya, anjuran_pakai, dosis_per_hari, a.nama_pasien, a.no_mr, a.tgl_trans');
		$this->db->from('fr_tc_far_detail_log b');
		$this->db->join('fr_tc_far a', 'a.kode_trans_far=b.kode_trans_far','left');
		$this->db->where_in('relation_id', $data);
		return $this->db->get();
	}

	
}
