<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verifikasi_resep_prb_model extends CI_Model {

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
		$this->db->join('(select COUNT(jumlah_obat_23) as jumlah_obat_23, kode_trans_far from fr_tc_far_detail where jumlah_obat_23 > 0 group by kode_trans_far) as detail','detail.kode_trans_far = fr_tc_far.kode_trans_far','left');
		$this->db->where('fr_tc_far.kode_trans_far in (select kode_trans_far from fr_tc_far_detail_log where jumlah_obat_23 > 0 group by kode_trans_far)');
		$this->db->where('tc_registrasi.kode_perusahaan', 120);
		// $this->db->where('detail.jumlah_obat_23 > 0');
		$this->db->group_by($this->select);

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		$this->db->where('fr_tc_far.verifikasi_prb IS NULL');

		if(isset($_GET['search_by']) AND $_GET['search_by'] != '' AND isset($_GET['keyword']) AND $_GET['keyword'] != '' ){
			if($_GET['search_by'] == 'no_sep'){
				$this->db->where('tc_registrasi.'.$_GET['search_by'].'', $_GET['keyword']);
			}else{
				$this->db->like('fr_tc_far.'.$_GET['search_by'].'', $_GET['keyword']);
			}
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' or isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
            $this->db->where("CAST(fr_tc_far.tgl_trans as DATE) BETWEEN '".$_GET['from_tgl']."' AND '".$_GET['to_tgl']."' " );
        }else{
        	$this->db->where('DATEDIFF(Day, tgl_trans, getdate())<=30');
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

	function get_result_data($date='')
	{
		$this->_main_query();
		if($date != ''){
			$this->db->where("CAST(fr_tc_far.tgl_trans as DATE) = '".$date."' " );
		}else{
			$this->db->where('DATEDIFF(Day, tgl_trans, getdate())<=7');
		}
		$this->db->where("scheduler_running_time is null");
		$this->db->order_by('tgl_trans', 'DESC');
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
		$this->db->select('c.ttd as ttd_pasien, c.jen_kelamin, CAST(c.tgl_lhr as DATE) as tgl_lhr');
		$this->db->select('a.*, b.*, e.*, d.nama_bagian, d.kode_poli_bpjs, c.no_hp');
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
		return $this->db->select('fr_tc_far_detail_log_prb.*, fr_tc_far.*, fr_tc_far_detail_log.jumlah_tebus as jumlah_7, fr_tc_far_detail_log.jumlah_obat_23')->join('fr_tc_far', 'fr_tc_far.kode_trans_far=fr_tc_far_detail_log_prb.kode_trans_far', 'left')->join('fr_tc_far_detail_log','fr_tc_far_detail_log.relation_id=fr_tc_far_detail_log_prb.kd_tr_resep','left')->get_where('fr_tc_far_detail_log_prb', array('fr_tc_far_detail_log_prb.kode_trans_far' => $kode_trans_far))->result();		
	}

	public function insert_verify($kode_trans_far)
	{
		// get data resep
		$resep_log = $this->Etiket_obat->get_detail_resep_data($kode_trans_far)->result_array();
        foreach($resep_log as $row){
            $racikan = ($row['flag_resep']=='racikan') ? $this->Entry_resep_racikan->get_detail_by_id($row['relation_id']) : [] ;
            $row['racikan'][] = $racikan;
            $getData[] = $row;
        }

		// echo '<pre>';print_r($getData);die;

		foreach( $getData as $key => $row ){

			if($row['jumlah_obat_23'] > 0){

                $kode_brg = $row['kode_brg'];
                
                // data master barang
                $dt_brg = $this->db->get_where('mt_barang', array('kode_brg' => $kode_brg) )->row();
                // data existing
                $dt_existing = $this->db->get_where('fr_tc_far_detail_log_prb', array('kode_brg' => $kode_brg, 'kode_trans_far' => $row['kode_trans_far'], 'kd_tr_resep' => $row['kd_tr_resep']) )->row();
                // print_r($dt_existing);die;
                $id_tc_far_racikan = isset($row['id_tc_far_racikan'])?$this->regex->_genRegex($row['id_tc_far_racikan'], 'RQXINT'):0;

                $sub_total = $row['harga_beli_master'] * $row['jumlah_obat_23'];
                $data_farmasi = array(
                    'id_tc_far_racikan' => $id_tc_far_racikan,
                    'kd_tr_resep' => isset($row['kd_tr_resep'])?$this->regex->_genRegex($row['kd_tr_resep'], 'RQXINT'):0,
                    'no_sep' => isset($_POST['no_sep'])?$this->regex->_genRegex($_POST['no_sep'], 'RQXINT'):0,
                    'kode_trans_far' => isset($row['kode_trans_far'])?$this->regex->_genRegex($row['kode_trans_far'], 'RQXINT'):0,
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'kode_brg' => isset($kode_brg)?$this->regex->_genRegex($kode_brg, 'RGXQSL'):0,
                    'nama_brg' => $dt_brg->nama_brg,
                    'satuan_kecil' => $dt_brg->satuan_kecil,
                    'jumlah' =>  isset($row['jumlah_obat_23'])?$this->regex->_genRegex($row['jumlah_obat_23'], 'RQXINT'):0,
                    'harga_satuan' =>  isset($row['harga_beli_master'])?$this->regex->_genRegex($row['harga_beli_master'], 'RQXINT'):0,
                    'sub_total' =>  isset($sub_total)?$this->regex->_genRegex($sub_total, 'RQXINT'):0,
                );

                if( count($dt_existing) > 0 ){
                    /*update existing*/
                    $data_farmasi['updated_date'] = date('Y-m-d H:i:s');
                    $data_farmasi['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    $this->db->update('fr_tc_far_detail_log_prb', $data_farmasi, array('id_fr_tc_far_detail_log_prb' => $dt_existing->id_fr_tc_far_detail_log_prb) );
                    /*save log*/
                    $this->logs->save('fr_tc_far_detail_log_prb', $dt_existing->id_fr_tc_far_detail_log_prb, 'update record on verifikasi obat prb module', json_encode($data_farmasi),'id_fr_tc_far_detail_log_prb');
                
                }else{    
                    $data_farmasi['created_date'] = date('Y-m-d H:i:s');
                    $data_farmasi['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    // print_r($data_farmasi);die;
    
                    $this->db->insert( 'fr_tc_far_detail_log_prb', $data_farmasi );
                    $newId = $this->db->insert_id();
                    /*save log*/
                    $this->logs->save('fr_tc_far_detail_log_prb', $newId, 'insert new record on verifikasi obat prb module', json_encode($data_farmasi),'id_fr_tc_far_detail_log_prb');
    
                }
			} 
                
        }

		return true;		
	}

	public function checkIfDokExist($kode_trans_far, $file_name){
        $qry = $this->db->get_where('fr_tc_far_dokumen_klaim_prb', array('kode_trans_far'=>$kode_trans_far,'dok_prb_file_name' => $file_name));
        return ($qry->num_rows() > 0) ? TRUE : FALSE;
    }

    public function getDocumentPDF($kode_trans_far){
        return $this->db->join('fr_tc_far b','b.kode_trans_far=a.kode_trans_far','left')->order_by('dok_prb_id', 'ASC')->get_where('fr_tc_far_dokumen_klaim_prb a', array('a.kode_trans_far'=>$kode_trans_far))->result();
    }

}
