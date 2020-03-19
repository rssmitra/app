<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Process_entry_resep_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}


	public function format_no_resep($flag, $kode_profit)
	{
		
		/*get max total resep by day*/
		$this->db->where('YEAR(tgl_trans)='.date('Y').' AND MONTH(tgl_trans)='.date('m').' AND DAY(tgl_trans)='.date('d').' AND status_transaksi=1 AND kode_profit='.$kode_profit.'');
		$max = $this->db->get('fr_tc_far')->num_rows();
		/*plus 1*/
		$max_row = $max + 1;
		/*format*/
		$format = $flag.'/'.$max_row;

		return $format;
		
	}

	public function format_aturan_pakai($string, $bentuk_resep, $anjuran_pakai){
		 /*format aturan pakai*/
        $aturan = explode('*', $string);
        $jml_pakai = isset($aturan[0])?$aturan[0]:'  ';
        $day = isset($aturan[1])?$aturan[1]:'  ';
        $jml_obat = isset($aturan[2])?$aturan[2]:'  ';
        $format_aturan_pakai = $jml_pakai.' x '.$day. ' hari '.$jml_obat.' '. $bentuk_resep.' ('.$anjuran_pakai.') ';

        return $format_aturan_pakai;
	}

	public function cek_existing_data($table, $where){

		$result = $this->db->get_where($table, $where);

		return ( $result->num_rows() > 0 ) ? $result->row() : false ;

	}

	public function delete_by_id($id, $flag='')
	{
		if( $flag == 'biasa' ){
			$this->db->where_in('fr_tc_far_detail.kd_tr_resep', $id);
			$this->db->delete('fr_tc_far_detail');			
		}else{
			$this->db->where_in('tc_far_racikan_detail.id_tc_far_racikan', $id);
			$this->db->delete('tc_far_racikan_detail');

			$this->db->where_in('tc_far_racikan.id_tc_far_racikan', $id);
			$this->db->delete('tc_far_racikan');

			$this->db->where_in('fr_tc_far_detail.id_tc_far_racikan', $id);
			$this->db->delete('fr_tc_far_detail');	

		}

		$this->db->where_in('fr_tc_far_detail_log.relation_id', $id);
		$this->db->delete('fr_tc_far_detail_log');

		return true;
		
	}

	public function sum_total_biaya_far($no_resep){
		$qry = "select SUM(total)as total
					from fr_tc_far_detail_log where kode_pesan_resep=".$no_resep."";
		$result = $this->db->query($qry)->row();
		$total = $result->total;
		return $total;

	}

	public function save_log_detail($params, $relation_id){

         /*data detail farmasi*/

        /*sub total*/
        $sub_total = ceil($params['jumlah_pesan'] * $params['harga_jual']);
        /*total biaya*/
        $total_biaya = ($sub_total + $params['harga_r']);
        $data_farmasi_detail = array(
            'kode_pesan_resep' => $this->regex->_genRegex($params['kode_pesan_resep'], 'RGXINT'),
            'tgl_input' => date('Y-m-d H:i:s'),
            'kode_brg' => $this->regex->_genRegex($params['kode_brg'], 'RGXQSL'),
            'nama_brg' => $this->regex->_genRegex($params['nama_brg'], 'RGXQSL'),
            'satuan_kecil' => $this->regex->_genRegex($params['satuan_kecil'], 'RGXQSL'),
            'jumlah_pesan' => $this->regex->_genRegex($params['jumlah_pesan'], 'RGXINT'),
            'jumlah_tebus' => $this->regex->_genRegex($params['jumlah_pesan'], 'RGXINT'),
            'sisa' => $this->regex->_genRegex(0, 'RGXINT'),
            'harga_jual_satuan' => $this->regex->_genRegex($params['harga_jual'], 'RGXINT'),
            'sub_total' => $this->regex->_genRegex($sub_total, 'RGXINT'),
            'jasa_r' => $this->regex->_genRegex($params['harga_r'], 'RGXINT'),
            'total' => $this->regex->_genRegex($total_biaya, 'RGXINT'),
            'aturan_pakai_format' => $this->regex->_genRegex($params['aturan_pakai_format'], 'RGXQSL'),
            'aturan_pakai' => $this->regex->_genRegex($params['aturan_pakai'], 'RGXQSL'),
            'catatan_lainnya' => $this->regex->_genRegex($params['catatan'], 'RGXQSL'),
            'urgensi' => $this->regex->_genRegex($params['urgensi'], 'RGXQSL'),
            'flag_resep' => $this->regex->_genRegex($params['flag_resep'], 'RGXQSL'),
            'relation_id' => $this->regex->_genRegex($relation_id, 'RGXQSL'),
            'bentuk_resep' => $this->regex->_genRegex($params['bentuk_resep'], 'RGXQSL'),
            'anjuran_pakai' => $this->regex->_genRegex($params['anjuran_pakai'], 'RGXQSL'),
        );

        //print_r($data_farmasi_detail);die;
        /*cek terlebih dahulu data fr_tc_far*/
        /*jika sudah ada data sebelumnya maka langsung insert ke detail*/
        $cek_existing = $this->Process_entry_resep->cek_existing_data('fr_tc_far_detail_log', array('relation_id' => $relation_id) );

        if( $cek_existing == false ){
            /*save existing*/
            $data_farmasi_detail['created_date'] = date('Y-m-d H:i:s');
            $data_farmasi_detail['created_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
            /*insert new data detail*/
            $this->db->insert('fr_tc_far_detail_log', $data_farmasi_detail);
            /*save history*/
            
        }else{

            /*update existing*/
            $data_farmasi_detail['updated_date'] = date('Y-m-d H:i:s');
            $data_farmasi_detail['updated_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
            /*insert new data detail*/
            $this->db->update('fr_tc_far_detail_log', $data_farmasi_detail, array('relation_id' => $relation_id));

        }

        return $data_farmasi_detail;

    }


}
