<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Process_entry_resep_model extends CI_Model {

	public function __construct()
	{
        parent::__construct();
        $this->kode_farmasi = '060101';
	}


	public function format_no_resep($flag, $kode_profit)
	{
		
		/*get max total resep by day*/
		$this->db->where('YEAR(tgl_trans)='.date('Y').' AND MONTH(tgl_trans)='.date('m').' AND DAY(tgl_trans)='.date('d').' AND kode_profit='.$kode_profit.'');
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

	public function sum_total_biaya_far($kode_trans_far){
		$qry = "select SUM(total)as total
					from fr_tc_far_detail_log where kode_trans_far=".$kode_trans_far."";
		$result = $this->db->query($qry)->row();
		$total = $result->total;
		return $total;

	}

	public function save_log_detail($params, $relation_id){

         /*data detail farmasi*/

        /*sub total*/
        $sub_total = (float)$params['jumlah_tebus'] * (float)$params['harga_jual'];
        $sisa = $params['jumlah_pesan'] - $params['jumlah_tebus'];
        /*total biaya*/
        $jasa_produksi = isset($params['jasa_produksi']) ? $params['jasa_produksi'] : 0 ;
        $total_biaya = ($sub_total + $params['harga_r'] + $jasa_produksi);
        $data_farmasi_detail = array(
            'kode_trans_far' => $this->regex->_genRegex($params['kode_trans_far'], 'RGXINT'),
            'kode_pesan_resep' => $this->regex->_genRegex($params['kode_pesan_resep'], 'RGXINT'),
            'tgl_input' => date('Y-m-d H:i:s'),
            'kode_brg' => $this->regex->_genRegex($params['kode_brg'], 'RGXQSL'),
            'nama_brg' => $this->regex->_genRegex($params['nama_brg'], 'RGXQSL'),
            'satuan_kecil' => $this->regex->_genRegex($params['satuan_kecil'], 'RGXQSL'),
            'jumlah_pesan' => $params['jumlah_pesan'],
            'jumlah_tebus' => $params['jumlah_tebus'],
            'sisa' => $sisa,
            'harga_jual_satuan' => $params['harga_jual'],
            'sub_total' => $sub_total,
            'jasa_r' => $params['harga_r'],
            'total' => $total_biaya,
            'flag_resep' => $this->regex->_genRegex($params['flag_resep'], 'RGXQSL'),
            'relation_id' => $this->regex->_genRegex($relation_id, 'RGXQSL'),

        );

        // jumlah obat 23/resep prb
        isset($params['jumlah_obat_23']) ? 
            $data_farmasi_detail['jumlah_obat_23'] = $this->regex->_genRegex($params['jumlah_obat_23'], 'RGXINT'):'';
        
        // apakah jumlah obat 23/resep prb ditangguhkan
        isset($params['prb_ditangguhkan']) ? 
        $data_farmasi_detail['prb_ditangguhkan'] = $this->regex->_genRegex($params['prb_ditangguhkan'], 'RGXINT'):'';

        // apakah jumlah obat 7/resep prb ditangguhkan
        isset($params['resep_ditangguhkan']) ? 
        $data_farmasi_detail['resep_ditangguhkan'] = $this->regex->_genRegex($params['resep_ditangguhkan'], 'RGXINT'):'';
            
        // jasa produksi
        isset($params['jasa_produksi']) ? 
        	$data_farmasi_detail['jasa_produksi'] = $this->regex->_genRegex($params['jasa_produksi'], 'RGXINT'):'';
        // signa
        isset($params['dosis_obat']) ? 
        	$data_farmasi_detail['dosis_obat'] = $this->regex->_genRegex($params['dosis_obat'], 'RGXQSL'):'';
        isset($params['dosis_per_hari']) ? 
        	$data_farmasi_detail['dosis_per_hari'] = $this->regex->_genRegex($params['dosis_per_hari'], 'RGXQSL'):'';
        isset($params['satuan_obat']) ? 
        	$data_farmasi_detail['satuan_obat'] = $this->regex->_genRegex($params['satuan_obat'], 'RGXQSL'):'';
        isset($params['anjuran_pakai']) ? 
        	$data_farmasi_detail['anjuran_pakai'] = $this->regex->_genRegex($params['anjuran_pakai'], 'RGXQSL'):'';
        isset($params['catatan_lainnya']) ? 
        	$data_farmasi_detail['catatan_lainnya'] = $this->regex->_genRegex($params['catatan_lainnya'], 'RGXQSL'):'';

        //print_r($data_farmasi_detail);die;
        /*cek terlebih dahulu data fr_tc_far*/
        /*jika sudah ada data sebelumnya maka langsung insert ke detail*/
        $cek_existing = $this->Process_entry_resep->cek_existing_data('fr_tc_far_detail_log', array('relation_id' => $relation_id, 'flag_resep' => $params['flag_resep']) );

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

    public function rollback($kode_pesan_resep){

        // data resep
        $dt = $this->db->get_where('fr_tc_far', array('kode_pesan_resep' => $kode_pesan_resep) )->row();
        // update status transaksi
        $this->db->where('kode_pesan_resep', $kode_pesan_resep);
        $this->db->update('fr_tc_far', array('status_transaksi' => null) );

        /*log pesan resep*/
        $this->db->update('fr_tc_pesan_resep', array('status_tebus' => null), array('kode_pesan_resep' => $kode_pesan_resep) );
        
        if( $dt->kode_trans_far > 0 ){
            $this->db->where('kode_trans_far', $dt->kode_trans_far);
            $this->db->delete('tc_trans_pelayanan');
        }

        /*log trasaksi detail*/
        $this->db->update('fr_tc_far_detail_log', array('status_tebus' => null), array('kode_trans_far' => $dt->kode_trans_far) );

        $dt_existing = $this->db->get_where('fr_tc_far_detail', array('kode_trans_far' => $dt->kode_trans_far) )->result();

        // retur barang
        foreach ($dt_existing as $key => $value) {
            # code...
            if($value->id_tc_far_racikan != 0){
                $sisa_di_retur = $value->jumlah_tebus;
                // retur stok ke farmasi
                if($value->urgensi == 'cito'){
                    // retur stok ke farmasi
                    $this->stok_barang->stock_process_cito($value->kode_brg, (int)$sisa_di_retur, $this->kode_farmasi , 16 ," - (Rollback) Kode. ".$value->kode_trans_far." ", 'restore'); 
                }else{
                    // retur stok ke farmasi
                    $this->stok_barang->stock_process($value->kode_brg, (int)$sisa_di_retur, $this->kode_farmasi , 8 ," (Rollback) Kode. ".$value->kode_trans_far." ", 'restore'); 
                }

                               
            }else{
                // get detail racikan
                $dt_racikan = $this->db->get_where('tc_far_racikan_detail', array('id_tc_far_racikan' => $value->id_tc_far_racikan) )->result();
                foreach ($dt_racikan as $key_dt => $row_dt) {
                    // retur stok ke farmasi
                    $this->stok_barang->stock_process($row_dt->kode_brg, (int)$row_dt->jumlah, $this->kode_farmasi, 8 ," (Rollback) Racikan Kode : ".$row_dt->id_tc_far_racikan." ", 'restore');
                }
            }
        }
        
        return true;

    }

    public function rollback_by_kode_trans_far($kode_trans_far){

        $dt_trans = $this->db->get_where('fr_tc_far', array('kode_trans_far' => $kode_trans_far) )->row();
        
        if(!empty($dt_trans)){
            /*untuk rawat jalan update log pesan resep*/
            $this->db->update('fr_tc_pesan_resep', array('status_tebus' => null), array('kode_pesan_resep' => $dt_trans->kode_pesan_resep) );   
            
            // update status transaksi
            $this->db->where('kode_trans_far', $dt_trans->kode_trans_far);
            $this->db->update('fr_tc_far', array('status_transaksi' => null) );

            // delete transaksi
            if( $dt_trans->kode_trans_far > 0 ){
                $this->db->where('kode_trans_far', $kode_trans_far);
                $this->db->delete('tc_trans_pelayanan');
            }
            

            /*log trasaksi detail*/
            $this->db->update('fr_tc_far_detail_log', array('status_tebus' => null), array('kode_trans_far' => $kode_trans_far) );

            $dt_existing = $this->db->get_where('fr_tc_far_detail', array('kode_trans_far' => $kode_trans_far, 'status_input' => 1) )->result();
            
            // retur barang
            foreach ($dt_existing as $key => $value) {
                # code...

                if($value->id_tc_far_racikan == 0){
                    $this->db->update('fr_tc_far_detail', array('status_input' => null), array('kd_tr_resep' => $value->kd_tr_resep) );
                    $jml_kronis = ( $value->prb_ditangguhkan != 1 ) ? (int)$value->jumlah_obat_23 : 0 ;
                    $jml_tebus = ( $value->resep_ditangguhkan != 1 ) ? (int)$value->jumlah_tebus : 0 ;
                    $sisa_di_retur = $jml_kronis + $jml_tebus;
                    if($value->urgensi == 'cito'){
                        // retur stok ke farmasi
                        $this->stok_barang->stock_process_cito($value->kode_brg, (int)$sisa_di_retur, $this->kode_farmasi , 16 ," - (Rollback) Kode. ".$value->kode_trans_far." ", 'restore'); 
                    }else{
                        // retur stok ke farmasi
                        $this->stok_barang->stock_process($value->kode_brg, (int)$sisa_di_retur, $this->kode_farmasi , 8 ," (Rollback) Kode. ".$value->kode_trans_far." ", 'restore'); 
                    }
                                   
                }else{
                    // get detail racikan
                    $dt_racikan = $this->db->join('tc_far_racikan', 'tc_far_racikan.id_tc_far_racikan=tc_far_racikan_detail.id_tc_far_racikan', 'left')->get_where('tc_far_racikan_detail', array('tc_far_racikan.id_tc_far_racikan' => $value->id_tc_far_racikan, 'status_input' => null) )->result();
                    // update status
                    $this->db->update('tc_far_racikan', array('status_input' => null), array('id_tc_far_racikan' => $value->id_tc_far_racikan) );
                    foreach ($dt_racikan as $key_dt => $row_dt) {
                        // retur stok ke farmasi
                        $this->stok_barang->stock_process($row_dt->kode_brg, (int)$row_dt->jumlah, $this->kode_farmasi, 8 ," (Rollback) Racikan Kode : ".$row_dt->id_tc_far_racikan." ", 'restore');

                    }
                }
            }
            
        }

        return true;

    }
}
