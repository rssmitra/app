<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entry_resep_racikan_model extends CI_Model {

	var $table = 'tc_far_racikan_detail';
	var $column = array('tc_far_racikan.id_tc_far_racikan');
	var $select = 'id_tc_far_racikan_detail,tc_far_racikan.id_tc_far_racikan, tc_far_racikan_detail.kode_brg, tc_far_racikan_detail.nama_brg, jumlah, satuan, tc_far_racikan_detail.harga_beli, jumlah_total, tc_far_racikan_detail.harga_jual, nama_racikan, tc_far_racikan.jasa_r, tc_far_racikan.jasa_produksi, b.dosis_obat, b.dosis_per_hari, b.anjuran_pakai, b.catatan_lainnya, b.satuan_kecil as satuan_racikan, tc_far_racikan_detail.jumlah_obat_23,tc_far_racikan_detail.prb_ditangguhkan ';

	var $order = array('id_tc_far_racikan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('tc_far_racikan','tc_far_racikan.id_tc_far_racikan=tc_far_racikan_detail.id_tc_far_racikan','left');
		$this->db->join('fr_tc_far_detail_log as b','b.relation_id=tc_far_racikan_detail.id_tc_far_racikan','left');
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		$this->db->where('tc_far_racikan_detail.id_tc_far_racikan', $_GET['id']);	

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
		$this->_main_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in('tc_far_racikan.kode_pesan_resep',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('tc_far_racikan.kode_pesan_resep',$id);
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

	public function get_detail_by_id($id)
	{
		$this->_main_query();
		$this->db->where_in('tc_far_racikan.id_tc_far_racikan',$id);
		$query = $this->db->get();
		return $query->result();
		
	}

	public function get_sum_total_racikan_detail($id){

		$query = "select SUM(harga_beli) as harga_beli, SUM(harga_jual) as harga_jual, 
					SUM(jumlah_total) as jumlah_total, AVG(jumlah_total) as average
					 from tc_far_racikan_detail where id_tc_far_racikan=".$id."";
		return $this->db->query( $query )->row();
	}

	public function delete_item_obat($id)
	{
		
		$this->db->where_in('id_tc_far_racikan_detail', $id);
		$this->db->delete('tc_far_racikan_detail');

		return true;
		
	}

	public function process_selesai_racikan($id)
	{

		$this->db->update('tc_far_racikan', array('status_input' => 1), array('id_tc_far_racikan' => $id) );
		$this->db->update('fr_tc_far_detail_log', array('status_input' => 1), array('relation_id' => $id) );

		return true;
		
	}

	public function update_bill_tc_far_racikan($id_tc_far_racikan){

		/*update total fr_tc_far_racikan*/
        $racikan = $this->db->get_where('tc_far_racikan', array('id_tc_far_racikan' => $id_tc_far_racikan))->row();
		$sum_result = $this->Entry_resep_racikan->get_sum_total_racikan_detail($id_tc_far_racikan);
		
		$jml_content = $racikan->jml_content;
        $harga_beli = $sum_result->harga_beli / $jml_content ;
        $harga_jual_satuan = $sum_result->jumlah_total / $jml_content ;
        $sub_total = $harga_jual_satuan * $jml_content ;
		
		$update_header = array(
            'harga_beli' => $harga_beli,
            'harga_jual' => $harga_jual_satuan,
            'sub_total' => $sub_total,
            'updated_date' => date('Y-m-d H:i:s'),
            'updated_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
            );
		// print_r($update_header);die;
        $this->db->update('tc_far_racikan', $update_header, array('id_tc_far_racikan' => $id_tc_far_racikan) );
		
        return $this->db->get_where('tc_far_racikan', array('id_tc_far_racikan' => $id_tc_far_racikan))->row();

	}

	public function save_fr_tc_far($params){

		/*cek fr_tc_far from racikan*/
		$fr_tc_far_dt = $this->db->get_where('fr_tc_far_detail', array('id_tc_far_racikan' => $params['id_tc_far_racikan']) )->row();
		$data_farmasi_detail = array(
                'id_tc_far_racikan' => $this->regex->_genRegex($params['id_tc_far_racikan'], 'RGXINT'),
                'jumlah_pesan' => $this->regex->_genRegex($params['jumlah_pesan'], 'RGXINT'),
                'jumlah_tebus' => $this->regex->_genRegex($params['jumlah_tebus'], 'RGXINT'),
                'sisa' => $this->regex->_genRegex(0, 'RGXINT'),
                'kode_brg' => $this->regex->_genRegex($params['kode_brg'], 'RGXQSL'),
                'harga_beli' => $this->regex->_genRegex($params['harga_beli'], 'RGXINT'),
                'harga_jual' => $this->regex->_genRegex($params['harga_jual'], 'RGXINT'),
                'harga_r' => $this->regex->_genRegex($params['harga_r'], 'RGXINT'),
                'biaya_tebus' => $this->regex->_genRegex($params['biaya_tebus'], 'RGXINT'),
                'tgl_input' => date('Y-m-d H:i:s'),
                'urgensi' => $this->regex->_genRegex($params['urgensi'], 'RGXQSL'),
            );

            //print_r($data_farmasi_detail);die;

            if( empty($fr_tc_far_dt->kd_tr_resep) ){
                $data_farmasi_detail['kd_tr_resep'] = $this->master->get_max_number('fr_tc_far_detail', 'kd_tr_resep');
                $data_farmasi_detail['kode_trans_far'] = $params['kode_trans_far'];
                $data_farmasi_detail['created_date'] = date('Y-m-d H:i:s');
                $data_farmasi_detail['created_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*insert new data detail*/
                $this->db->insert('fr_tc_far_detail', $data_farmasi_detail);
            }else{
                $data_farmasi_detail['kd_tr_resep'] = $fr_tc_far_dt->kd_tr_resep;
                $data_farmasi_detail['kode_trans_far'] = $params['kode_trans_far'];
                $data_farmasi_detail['updated_date'] = date('Y-m-d H:i:s');
                $data_farmasi_detail['updated_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*insert new data detail*/
                $this->db->update('fr_tc_far_detail', $data_farmasi_detail, array('kd_tr_resep' => $fr_tc_far_dt->kd_tr_resep) );
            }

	}





}
