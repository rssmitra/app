<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tmp_mst_menu_model extends CI_Model {

	var $table = 'tmp_mst_menu';
	var $column = array('tmp_mst_menu.name','tmp_mst_menu.class','tmp_mst_menu.link','tmp_mst_menu.level','tmp_mst_menu.counter','tmp_mst_menu.is_active','tmp_mst_menu.updated_date','tmp_mst_menu.created_date', 'tmp_mst_modul.name');
	var $select = 'tmp_mst_menu.menu_id, tmp_mst_menu.name, tmp_mst_menu.class, tmp_mst_menu.link, tmp_mst_menu.level, tmp_mst_menu.parent, tmp_mst_menu.icon, tmp_mst_menu.set_shortcut, tmp_mst_menu.counter, tmp_mst_menu.is_active, tmp_mst_menu.is_deleted, tmp_mst_menu.created_date, tmp_mst_menu.created_by, tmp_mst_menu.updated_date, tmp_mst_menu.modul_id, tmp_mst_menu.description, tmp_mst_menu.updated_by, tmp_mst_modul.name as modul_name';

	var $order = array('tmp_mst_menu.menu_id' => 'DESC', 'tmp_mst_menu.updated_date' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('tmp_mst_modul',$this->table.'.modul_id=tmp_mst_modul.modul_id','left');
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
			$this->db->where_in(''.$this->table.'.menu_id',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.menu_id',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
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
		$this->db->where_in(''.$this->table.'.menu_id', $id);
		return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
	}


}
