<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csm_resume_billing_ri_model extends CI_Model {


	var $table = 'csm_reg_pasien';
	var $column = array('csm_reg_pasien.no_registrasi');
	var $select = 'csm_reg_pasien.csm_rp_no_sep, csm_reg_pasien.csm_rp_no_mr, csm_reg_pasien.csm_rp_tgl_keluar, csm_reg_pasien.csm_rp_nama_pasien, csm_reg_pasien.no_registrasi, csm_rp_kode_bagian, csm_rp_tgl_masuk, csm_rp_nama_dokter' ;
	var $order = array('csm_reg_pasien.csm_rp_no_sep' => 'ASC', 'csm_reg_pasien.csm_rp_tgl_keluar' => 'ASC');
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database('default', TRUE);
	}

	private function _main_query(){
		
		$curr_month = date('m')-1;
		
		$this->db->select($this->select);
		$this->db->from($this->table);

		if (isset($_GET['frmdt']) AND $_GET['frmdt'] != '' || isset($_GET['todt']) AND $_GET['todt'] != '') {
			$this->db->where($this->table.".".$_GET['field']." BETWEEN '".$_GET['frmdt']."' AND '".$_GET['todt']."' " );
		}else{
			$this->db->where(" MONTH(csm_reg_pasien.created_date) > ".$curr_month." " );
			$this->db->where(" YEAR(csm_reg_pasien.created_date) = ".date('Y')." " );
		}

		$this->db->where('csm_reg_pasien.kode_perusahaan', 120);
		$this->db->where('csm_reg_pasien.csm_rp_tipe', 'RI');
		$this->db->where('csm_reg_pasien.is_submitted', 'Y');
		$this->db->group_by($this->select);

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

	function get_data()
	{
		$this->_main_query();
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		//print_r($query);die;
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
			$this->db->where_in(''.$this->table.'.no_registrasi',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.no_registrasi',$id);
			$query = $this->db->get();
			//echo '<pre>';print_r($this->db->last_query());die;
			return $query->row();
		}
		
	}

	public function getByNoRegistrasi($no_registrasi)
	{
		$this->db->from('csm_resume_billing_pasien_ri');
		$this->db->where('no_registrasi', $no_registrasi);
		return $this->db->get();
		
	}

	public function getResumeBillingHtml($data,$no_registrasi){
    	/*html data untuk tampilan*/
    	/*rincian billing*/
    	$html = '';
        $html .= '<div class="col-sm-4">';
        $html .= '<br>';
        $html .= '<div class="center"><p><b>RINCIAN BIAYA KESELURUHAN PASIEN RAWAT INAP</b></p></div>';
        $html .= '<table class="table table-striped">';
        $html .= '<tr>';
            $html .= '<th width="30px" class="center">No</th>';
            $html .= '<th>Uraian</th>';
            $html .= '<th width="100px" class="center">Subtotal (Rp.)</th>';
        $html .= '</tr>'; 
        $no=1;
        foreach ($data->result() as $k => $val) {
        	/*total*/
        	if((int)$val->csm_rbp_ri_total > 0){
        		$sum_subtotal[] = $val->csm_rbp_ri_total;
	            $html .= '<tr>';
	            $html .= '<td width="30px" class="center">'.$no.'</td>';
	            $html .= '<td width="100px">'.$val->csm_rbp_ri_title.'</td>';
	            $html .= '<td width="100px" align="right">'.number_format($val->csm_rbp_ri_total).'</td>';
	            $html .= '</tr>';
	            $no++;
        	}
                 
        }
	        /*biaya materai*/
	         $html .= '<tr>';
		            $html .= '<td width="30px" class="center">'.$no.'</td>';
		            $html .= '<td width="100px">Materai</td>';
		            $html .= '<td width="100px" align="right">6,000,-</td>';
		            $html .= '</tr>';
	        $html .= '<tr>';
		    /*total plus materai*/
		    $total_plus_materai = array_sum($sum_subtotal) + 6000;
            $html .= '<td align="right"><b>Total</b></td>';
            $html .= '<td colspan="2" width="100px" align="right"><b>Rp. '.number_format($total_plus_materai).',-</b></td>';
        $html .= '</tr>';   
        $html .= '</table>';
        $html .= '<br>';
            
        $html .= '</div>';

        return $html;
    }

	
}
