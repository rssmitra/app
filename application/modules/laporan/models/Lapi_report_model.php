<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lapi_report_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query($params){
		
		$bulan = $params['bulan'];
		$tahun = $params['tahun'];
		
		$query = "DECLARE @startDate DATE = '$tahun-$bulan-01';
					DECLARE @endDate   DATE = DATEADD(MONTH, 1, @startDate);

					WITH last_discount AS (
						SELECT
							p.kode_brg,
							p.discount
						FROM tc_po_det p
						INNER JOIN (
							SELECT
								kode_brg,
								MAX(id_tc_po_det) AS max_id
							FROM tc_po_det
							GROUP BY kode_brg
						) x ON x.kode_brg = p.kode_brg
						AND x.max_id   = p.id_tc_po_det
					),

					trx AS (

						/* ================= OBAT NON RACIK ================= */
						SELECT
							CAST(f.tgl_trans AS DATE) AS tanggal,
							d.kode_brg,
							d.nama_brg,
							d.satuan_kecil,
							f.dokter_pengirim,

							CASE 
								WHEN d.prb_ditangguhkan != 1
									THEN SUM(d.jumlah_pesan + d.jumlah_obat_23)
								ELSE SUM(d.jumlah_pesan)
							END AS qty,

							SUM(
								CASE 
									WHEN d.prb_ditangguhkan != 1
										THEN (d.jumlah_pesan + d.jumlah_obat_23) * CAST(d.harga_jual_satuan AS INT)
									ELSE d.jumlah_pesan * CAST(d.harga_jual_satuan AS INT)
								END
							) AS total_jual

						FROM fr_tc_far_detail_log d
						JOIN fr_tc_far f ON f.kode_trans_far = d.kode_trans_far
						WHERE
							f.tgl_trans >= @startDate
							AND f.tgl_trans <  @endDate
							AND d.flag_resep = 'biasa'
						GROUP BY
							CAST(f.tgl_trans AS DATE),
							d.prb_ditangguhkan,
							d.kode_brg,
							d.nama_brg,
							d.satuan_kecil,
							f.dokter_pengirim

						UNION ALL

						/* ================= OBAT RACIK ================= */
						SELECT
							CAST(r.tgl_input AS DATE) AS tanggal,
							rd.kode_brg,
							b.nama_brg,
							rd.satuan AS satuan_kecil,
							f.dokter_pengirim,

							CASE 
								WHEN rd.prb_ditangguhkan != 1
									THEN SUM(rd.jumlah + rd.jumlah_obat_23)
								ELSE SUM(rd.jumlah)
							END AS qty,

							SUM(
								CASE 
									WHEN rd.prb_ditangguhkan != 1
										THEN (rd.jumlah + rd.jumlah_obat_23) * CAST(rd.harga_jual AS INT)
									ELSE rd.jumlah * CAST(rd.harga_jual AS INT)
								END
							) AS total_jual

						FROM tc_far_racikan_detail rd
						JOIN tc_far_racikan r ON r.id_tc_far_racikan = rd.id_tc_far_racikan
						JOIN fr_tc_far f ON f.kode_trans_far = r.kode_trans_far
						JOIN mt_barang b ON b.kode_brg = rd.kode_brg
						WHERE
							f.tgl_trans >= @startDate
							AND f.tgl_trans <  @endDate
						GROUP BY
							CAST(r.tgl_input AS DATE),
							rd.prb_ditangguhkan,
							rd.kode_brg,
							b.nama_brg,
							rd.satuan,
							f.dokter_pengirim

						UNION ALL

						/* ================= PRB ================= */
						SELECT
							CAST(p.tgl_input AS DATE) AS tanggal,
							p.kode_brg,
							b.nama_brg,
							b.satuan_kecil,
							f.dokter_pengirim,

							SUM(p.log_jml_mutasi) AS qty,

							SUM(p.log_jml_mutasi * CAST(p.harga_satuan AS INT)) AS total_jual

						FROM fr_tc_far_detail_log_prb p
						JOIN fr_tc_far f ON f.kode_trans_far = p.kode_trans_far
						JOIN mt_barang b ON b.kode_brg = p.kode_brg
						WHERE
							p.tgl_input >= @startDate
							AND p.tgl_input <  @endDate
						GROUP BY
							CAST(p.tgl_input AS DATE),
							p.kode_brg,
							b.nama_brg,
							b.satuan_kecil,
							f.dokter_pengirim
						HAVING SUM(p.log_jml_mutasi) > 0
					)

					SELECT
						YEAR(t.tanggal)  AS tahun,
						MONTH(t.tanggal) AS bulan,

						t.kode_brg,
						t.nama_brg,
						lo.nama_layanan,
						t.dokter_pengirim,
						t.satuan_kecil,

						SUM(t.qty) AS jumlah_item,

						CAST(SUM(t.total_jual) / NULLIF(SUM(t.qty),0) AS DECIMAL(18,2)) AS rata_jual,

						ISNULL(ld.discount,0) AS discount  

					FROM trx t
					JOIN mt_barang b ON b.kode_brg = t.kode_brg
					LEFT JOIN mt_layanan_obat lo ON lo.kode_layanan = b.kode_layanan
					LEFT JOIN last_discount ld ON ld.kode_brg = t.kode_brg

					GROUP BY
						YEAR(t.tanggal),
						MONTH(t.tanggal),
						t.kode_brg,
						t.nama_brg,
						lo.nama_layanan,
						t.satuan_kecil,
						t.dokter_pengirim,
						ld.discount

					ORDER BY
						t.nama_brg ASC;
					";

			// echo '<pre>'; print_r($query);die;
			return $query;
	}


	public function get_data($params)
	{
		$query 		= $this->_main_query($params);
		$execute 	= $this->db->query( $query );
		// echo '<pre>'; print_r($this->db->last_query());die;
		/*field data*/
		$result = array(
			'fields' 	=> $execute->field_data(),
			'data' 		=> $execute->result(),
		);
		/*return data*/
		return $result;
	}
}
