<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lapi_report_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query($params){
		$query = "select YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, tblx.kode_brg, tblx.nama_brg, nama_layanan, dokter_pengirim, tblx.satuan_kecil, SUM(jumlah_pesan) as jumlah_item, AVG(harga_jual_satuan) as rata_jual
			FROM (
			SELECT
			CAST(fr_tc_far.tgl_trans as DATE) as tanggal,
			fr_tc_far_detail_log.kode_brg,
			fr_tc_far_detail_log.nama_brg,
			fr_tc_far_detail_log.satuan_kecil,dokter_pengirim,
			CASE 
			WHEN prb_ditangguhkan != 1 THEN CAST( (SUM ( fr_tc_far_detail_log.jumlah_pesan ) + SUM ( fr_tc_far_detail_log.jumlah_obat_23 )) as INT)
				ELSE CAST( SUM ( fr_tc_far_detail_log.jumlah_pesan ) as INT)
			END
			AS jumlah_pesan,
			AVG (CAST(harga_jual_satuan AS INT)) AS harga_jual_satuan
		FROM
			fr_tc_far_detail_log 
			LEFT JOIN fr_tc_far on fr_tc_far.kode_trans_far=fr_tc_far_detail_log.kode_trans_far
		WHERE YEAR(tgl_trans)=".$params['tahun']." and MONTH(tgl_trans) = ".$params['bulan']." and fr_tc_far_detail_log.flag_resep = 'biasa'
		GROUP BY
		CAST(fr_tc_far.tgl_trans as DATE),prb_ditangguhkan,
			fr_tc_far_detail_log.kode_brg,
			fr_tc_far_detail_log.nama_brg,
			fr_tc_far_detail_log.satuan_kecil, dokter_pengirim
			
			UNION ALL
			
		SELECT
		CAST(tc_far_racikan.tgl_input as DATE) as tanggal,
			tc_far_racikan_detail.kode_brg,
			mt_barang.nama_brg,dokter_pengirim,
			satuan AS satuan_kecil,
			CASE 
			WHEN tc_far_racikan_detail.prb_ditangguhkan != 1 THEN CAST ( ( SUM ( jumlah ) + SUM(tc_far_racikan_detail.jumlah_obat_23) ) AS INT)
				ELSE CAST( SUM ( jumlah ) as INT)
			END
			AS jumlah_pesan,
			AVG ( CAST(tc_far_racikan_detail.harga_jual AS INT) ) AS harga_jual_satuan
		FROM
			tc_far_racikan_detail 
			LEFT JOIN tc_far_racikan on tc_far_racikan.id_tc_far_racikan=tc_far_racikan_detail.id_tc_far_racikan
			LEFT JOIN fr_tc_far on fr_tc_far.kode_trans_far=tc_far_racikan.kode_trans_far
			LEFT JOIN mt_barang on mt_barang.kode_brg = tc_far_racikan_detail.kode_brg
			WHERE YEAR(tgl_trans)= ".$params['tahun']." and MONTH(tgl_trans) = ".$params['bulan']." 
		GROUP BY
		CAST(tc_far_racikan.tgl_input as DATE),dokter_pengirim, tc_far_racikan_detail.prb_ditangguhkan,
			tc_far_racikan_detail.kode_brg,
			mt_barang.nama_brg,
			satuan
			
			UNION ALL
			
			SELECT
				CAST(tgl_input as DATE) as tanggal,
				mt_barang.kode_brg,
				mt_barang.nama_brg,
				mt_barang.satuan_kecil,dokter_pengirim,
				CAST (SUM ( log_jml_mutasi ) AS INT) AS jumlah_pesan,
				AVG ( CAST(harga_satuan AS INT) ) AS harga_jual_satuan
			FROM
				fr_tc_far_detail_log_prb 
			LEFT JOIN fr_tc_far on fr_tc_far.kode_trans_far=fr_tc_far_detail_log_prb.kode_trans_far
			LEFT JOIN mt_barang on mt_barang.kode_brg = fr_tc_far_detail_log_prb.kode_brg
			WHERE YEAR(tgl_input) = ".$params['tahun']." and MONTH(tgl_input) = ".$params['bulan']." 
			GROUP BY
				CAST(tgl_input as DATE),dokter_pengirim,
				mt_barang.kode_brg,
				mt_barang.nama_brg,
				mt_barang.satuan_kecil
				HAVING CAST (SUM ( log_jml_mutasi ) AS INT) > 0
			
			) as tblx

			LEFT JOIN mt_barang on mt_barang.kode_brg = tblx.kode_brg
			LEFT JOIN mt_layanan_obat on mt_layanan_obat.kode_layanan = mt_barang.kode_layanan
			GROUP BY MONTH(tanggal), YEAR(tanggal), tblx.kode_brg, tblx.nama_brg, nama_layanan, tblx.satuan_kecil, tblx.dokter_pengirim
			ORDER BY tblx.nama_brg ASC";

			return $query;
	}


	public function get_data($params)
	{
		$query 		= $this->_main_query($params);
		$execute 	= $this->db->query( $query );
		/*field data*/
		$result = array(
			'fields' 	=> $execute->field_data(),
			'data' 		=> $execute->result(),
		);
		// echo '<pre>'; print_r($result);die;
		/*return data*/
		return $result;
	}
}
