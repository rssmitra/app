<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Global_report_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_data()
	{
		$flag = isset($_POST['flag'])?$_POST['flag']:$_GET['flag'];
		$query 		= $this->$flag();
		$execute 	= $this->db->query( $query );
		// echo '<pre>'; print_r($query);die;
		/*field data*/
		$result = array(
			'fields' 	=> $execute->field_data(),
			'data' 		=> $execute->result(),
		);
		/*return data*/
		return $result;

	}

	// master tarif
	public function master_tarif(){
		$where = (!empty($_POST['bagian']))? " AND b.kode_bagian = '".$_POST['bagian']."' " : '';
		if(!empty($_POST['klas'])){
			$implode = implode(",", $_POST['klas']);
		}
		$where .= (!empty($_POST['klas']))? ' AND a.kode_klas IN ('.$implode.')' : '';

		$query = "SELECT
					a.kode_tarif,
					nama_tarif,
					nama_bagian,
					nama_klas,
					a.kode_klas,
					a.revisi_ke,
					CAST(bill_dr1 as INT) as bill_dr1,
				CAST(bill_dr2 as INT) as bill_dr2,
				CAST(kamar_tindakan as INT) as kamar_tindakan,
				CAST(bhp as INT) as bhp,
				CAST(alat_rs as INT) as alat_rs,
				CAST(adm as INT) as adm,
				CAST(pendapatan_rs as INT) as pendapatan_rs 
				FROM
					mt_master_tarif_detail a 
					left join mt_master_tarif b on b.kode_tarif = a.kode_tarif
					left join mt_klas c on c.kode_klas = a.kode_klas
					left join mt_bagian d on d.kode_bagian= b.kode_bagian
				WHERE
					a.is_active = 'Y' 
					AND a.kode_tarif != '0' 
					".$where."
				ORDER BY
					a.revisi_ke DESC,
					a.kode_tarif ASC";
		return $query;
	}

	// ================= AKUNTING DAN KEUANGAN =================== //
	public function akunting_mod_1(){
		// jenis
		$where = ($_POST['seri_kuitansi'] != '') ? 'AND seri_kuitansi = '."'".$_POST['seri_kuitansi']."'".' ' : '';
		
		$query = "select no_kuitansi, seri_kuitansi, CAST(tgl_jam as DATE)as tgl_transaksi, nama_pasien, pembayar, CAST(tunai as INT) as tunai, CAST(debet as INT) as debet, CAST(kredit as INT) as kredit, CAST(nk_perusahaan as INT) as piutang, CAST(bill as INT)as billing, nama_pegawai  
		from tc_trans_kasir 
		left join mt_karyawan on mt_karyawan.no_induk=tc_trans_kasir.no_induk
		where CAST(tgl_jam as DATE) = '".$_POST['tgl_transaksi']."' ".$where." order by tgl_jam DESC";
		return $query;
	}

	public function akunting_mod_2(){
		// $query = "select tc_registrasi.no_registrasi, seri_kuitansi, tc_registrasi.no_mr, nama_pasien, 
		// 			nama_bagian, no_sep, tgl_jam_masuk, CAST(billing.total as int) as total
		// 			from tc_registrasi
		// 			left join mt_bagian on mt_bagian.kode_bagian=tc_registrasi.kode_bagian_masuk
		// 			left join mt_master_pasien on mt_master_pasien.no_mr=tc_registrasi.no_mr
		// 			left join (
		// 				select no_registrasi, SUM(bill)as total, seri_kuitansi from tc_trans_kasir where no_registrasi in (
		// 				select no_registrasi from tc_registrasi
		// 				where YEAR(tgl_jam_masuk) = ".$_POST['year']."
		// 				and MONTH(tgl_jam_keluar) between ".$_POST['from_month']." and ".$_POST['to_month']."
		// 				and tgl_jam_keluar is not null
		// 				and tc_registrasi.kode_perusahaan=120 and status_batal is null
		// 				)
		// 				group by no_registrasi, seri_kuitansi
		// 			) as billing on billing.no_registrasi=tc_registrasi.no_registrasi
		// 			where tgl_jam_keluar is not null
		// 			and tc_registrasi.kode_perusahaan=120 and status_batal is null and seri_kuitansi='".$_POST['keterangan']."'
		// 			ORDER BY nama_bagian,tgl_jam_masuk ASC";
		
		if (isset($_POST['jenis_kunjungan']) AND $_POST['jenis_kunjungan'] == 'rj') {
			$where = "b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '03' AND MONTH(tgl_masuk) BETWEEN '".$_POST['from_month']."' AND '".$_POST['to_month']."' AND YEAR(tgl_masuk) = ".$_POST['year']." AND a.status_batal is null)";
		}

		if (isset($_POST['jenis_kunjungan']) AND $_POST['jenis_kunjungan'] == 'ri') {
			$where = "b.no_kunjungan IN ( SELECT no_kunjungan
			FROM ri_tc_rawatinap a
			where MONTH(tgl_masuk) BETWEEN '".$_POST['from_month']."' AND '".$_POST['to_month']."' AND YEAR(tgl_masuk) = ".$_POST['year']."   )";
		}
		
		$query = "SELECT
					b.no_registrasi,
					f.no_sep,
					d.nama_bagian,
					CAST( ( SUM ( bill_rs ) + SUM ( bill_dr1 ) + SUM ( bill_dr2 ) + SUM ( bill_dr3 ) ) as INT) AS total,
					b.no_mr,
					e.nama_pasien,
					CAST(c.tgl_masuk as DATE) as tgl_masuk
				FROM
					tc_trans_pelayanan b
					LEFT JOIN tc_registrasi f ON f.no_registrasi = b.no_registrasi
					LEFT JOIN tc_kunjungan c ON c.no_kunjungan = b.no_kunjungan
					LEFT JOIN mt_bagian d ON d.kode_bagian = f.kode_bagian_masuk
					LEFT JOIN mt_master_pasien e ON e.no_mr = b.no_mr 
				WHERE
					b.kode_perusahaan = 120 
					AND ".$where."
				GROUP BY
					b.no_registrasi,
					f.no_sep,
					d.nama_bagian,
					e.nama_pasien,
					b.no_mr,
					CAST(c.tgl_masuk as DATE) 
				ORDER BY
					f.no_sep ASC";
		
		return $query;
	}

	public function akunting_mod_7(){
		
		if (isset($_POST['jenis_kunjungan']) AND $_POST['jenis_kunjungan'] == 'rj') {
			$where = "b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '03' AND MONTH(tgl_masuk) BETWEEN '".$_POST['from_month']."' AND '".$_POST['to_month']."' AND YEAR(tgl_masuk) = ".$_POST['year']." AND a.status_batal is null)";
		}

		if (isset($_POST['jenis_kunjungan']) AND $_POST['jenis_kunjungan'] == 'ri') {
			$where = "b.no_kunjungan IN ( SELECT no_kunjungan
			FROM ri_tc_rawatinap a
			where MONTH(tgl_masuk) BETWEEN '".$_POST['from_month']."' AND '".$_POST['to_month']."' AND YEAR(tgl_masuk) = ".$_POST['year']."   )";
		}
		
		$query = "SELECT
					b.no_registrasi,
					f.no_sep,
					d.nama_bagian,
					CAST( ( SUM ( bill_rs ) + SUM ( bill_dr1 ) + SUM ( bill_dr2 ) + SUM ( bill_dr3 ) ) as INT) AS total,
					b.no_mr,
					e.nama_pasien,
					CAST(c.tgl_masuk as DATE) as tgl_masuk
				FROM
					tc_trans_pelayanan b
					LEFT JOIN tc_registrasi f ON f.no_registrasi = b.no_registrasi
					LEFT JOIN tc_kunjungan c ON c.no_kunjungan = b.no_kunjungan
					LEFT JOIN mt_bagian d ON d.kode_bagian = f.kode_bagian_masuk
					LEFT JOIN mt_master_pasien e ON e.no_mr = b.no_mr 
				WHERE
					b.kode_perusahaan != 120 
					AND ".$where."
				GROUP BY
					b.no_registrasi,
					f.no_sep,
					d.nama_bagian,
					e.nama_pasien,
					b.no_mr,
					CAST(c.tgl_masuk as DATE) 
				ORDER BY
					f.no_sep ASC";
		
		return $query;
	}
    
    public function akunting_mod_3(){

		$kode_bagian = isset($_POST['bagian'])?$_POST['bagian']:$_GET['kode_bagian'];

		$this->db->select('a.kode_brg, b.nama_brg, b.content, CAST(AVG(b.harga_beli) as INT) as harga_beli');
		$this->db->from('mt_depo_stok a');
		$this->db->join('mt_barang b', 'b.kode_brg=a.kode_brg', 'INNER');
		$this->db->join('mt_rekap_stok c', 'a.kode_brg=c.kode_brg', 'LEFT');
		if(!empty($kode_bagian)){
			$this->db->where('a.kode_bagian', $kode_bagian);
		}
		$this->db->where('a.kode_brg IS NOT NULL');
		$this->db->where('a.is_active', 1);
		// $this->db->where('a.kode_brg ', 'D43B0103');
		$this->db->group_by('a.kode_brg, b.nama_brg, b.content');
		$this->db->order_by('b.nama_brg', 'ASC');
		$this->db->get();
			
		return $this->db->last_query();
	}

	public function akunting_mod_3a(){
		$query = "SELECT a.kode_brg, b.nama_brg, CAST(AVG(c.harga_beli) as INT) as harga_beli
		FROM  mt_depo_stok as a LEFT JOIN mt_barang b on b.kode_brg=a.kode_brg 
		LEFT JOIN mt_rekap_stok c on a.kode_brg=c.kode_brg
		WHERE a.kode_brg IS NOT NULL
		group by a.kode_brg, b.nama_brg ORDER BY b.nama_brg ASC";
			
		return $query;
    }
    
	public function akunting_mod_4(){
		// $month=$_POST['from_month'] - 1;
		$query = 'SELECT a.kode_brg, b.nama_brg, AVG(a.harga_beli) as harga_beli, AVG(c.harga_jual) as hargajual 
		FROM mt_rekap_stok as a INNER JOIN mt_barang b on b.kode_brg=a.kode_brg 
		inner JOIN fr_tc_far_detail c ON c.kode_brg=a.kode_brg 
		WHERE a.kode_bagian_gudang IN (060101,060201) 
		group by a.kode_brg, b.nama_brg ORDER BY b.nama_brg ASC';
			
		return $query;
	}

	public function akunting_mod_5(){

		$t_kartu_stok = ($_POST['bagian'] == 'non_medis') ? 'tc_kartu_stok_nm' : 'tc_kartu_stok' ;
		$tgl_stok = isset($_POST['tgl_stok'])?$_POST['tgl_stok']:date('Y-m-d');
		// // Gudang Non Medis
		// if($_POST['bagian']=='070101'){

		// 	$this->db->select('mt_depo_stok_nm_v.kode_brg, mt_depo_stok_nm_v.nama_brg, mt_depo_stok_nm_v.satuan_besar, mt_depo_stok_nm_v.satuan_kecil, mt_depo_stok_nm_v.nama_golongan, mt_depo_stok_nm_v.nama_sub_golongan, mt_bagian.nama_bagian, mt_depo_stok_nm_v.is_active, kartu_stok.stok_akhir');
		// 	$this->db->from('mt_depo_stok_nm_v');
		// 	$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=mt_depo_stok_nm_v.kode_bagian','left');
		// 	$this->db->join('( SELECT * FROM tc_kartu_stok_nm WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok_nm WHERE tgl_input <= '."'".$tgl_stok."'".' AND tgl_input is not null GROUP BY kode_brg) AND kode_bagian='."'".$_POST['bagian']."'".' ) AS kartu_stok', 'kartu_stok.kode_brg=mt_depo_stok_nm_v.kode_brg','left');

		// 	$this->db->where('mt_depo_stok_nm_v.kode_bagian', $_POST['bagian']);
		// 	$this->db->where('nama_brg LIKE '."'%'".'');

		// 	if( isset($_POST['kode_golongan']) AND $_POST['kode_golongan'] != '' ){
		// 		$this->db->where('kode_golongan', $_POST['kode_golongan']);
		// 	}

		// 	if( isset($_POST['kode_sub_gol']) AND $_POST['kode_sub_gol'] != '' ){
		// 		$this->db->where('kode_sub_golongan', $_POST['kode_sub_gol']);
		// 	}

		// 	// $this->db->where('is_active', 1);
		// 	$this->db->group_by( 'mt_depo_stok_nm_v.kode_brg, mt_depo_stok_nm_v.nama_brg, mt_depo_stok_nm_v.satuan_besar, mt_depo_stok_nm_v.satuan_kecil, mt_depo_stok_nm_v.nama_golongan, mt_depo_stok_nm_v.nama_sub_golongan, mt_bagian.nama_bagian, mt_depo_stok_nm_v.is_active, kartu_stok.stok_akhir' );
		// 	$this->db->order_by( 'nama_brg','ASC' );
		// 	$this->db->order_by( 'nama_sub_golongan','ASC' );
		// 	$this->db->order_by( 'nama_golongan','ASC' );
		// 	$query = $this->db->get();
		// 	// print_r($this->db->last_query());die;

		// // Gudang Medis
		// }else{
			
			$this->db->select('mt_depo_stok_v.kode_brg, nama_brg, satuan_besar, satuan_kecil, nama_sub_golongan as nama_golongan, nama_bagian, nama_kategori, nama_layanan, nama_jenis, kartu_stok.stok_akhir, AVG(mt_rekap_stok.harga_beli) as harga_beli, AVG(harga_jual) as hargajual, is_active');
			$this->db->from('mt_depo_stok_v');
			$this->db->join('mt_rekap_stok', 'mt_rekap_stok.kode_brg=mt_depo_stok_v.kode_brg','left');
			$this->db->join('fr_tc_far_detail', 'fr_tc_far_detail.kode_brg=mt_depo_stok_v.kode_brg','left');
			$this->db->join('mt_golongan', 'mt_golongan.kode_golongan=mt_depo_stok_v.kode_golongan','left');
			$this->db->join('mt_sub_golongan', 'mt_sub_golongan.kode_sub_gol=mt_depo_stok_v.kode_sub_golongan','left');
			$this->db->join('( SELECT * FROM tc_kartu_stok WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok WHERE tgl_input <= '."'".$tgl_stok."'".' AND tgl_input is not null GROUP BY kode_brg) ) AS kartu_stok', 'kartu_stok.kode_brg=mt_depo_stok_v.kode_brg','left');

			
			// $this->db->where('status_aktif', 1);
			$this->db->group_by( 'mt_depo_stok_v.kode_brg, nama_brg, satuan_besar, satuan_kecil, nama_sub_golongan, nama_bagian, nama_kategori, nama_layanan, nama_jenis, kartu_stok.stok_akhir, mt_rekap_stok.harga_beli, harga_jual, is_active' );
			$this->db->order_by( 'nama_bagian','ASC' );
			$this->db->order_by( 'nama_brg','ASC' );
			$query = $this->db->get();
			// print_r($this->db->last_query());die;
		// }

		return $this->db->last_query();
	}

	public function akunting_mod_6(){

		$tgl_stok = isset($_POST['tgl_stok'])?$_POST['tgl_stok']:date('Y-m-d');
		
			
			$this->db->select('mt_depo_stok_nm_v.kode_brg, mt_depo_stok_nm_v.nama_brg, mt_depo_stok_nm_v.satuan_besar, mt_depo_stok_nm_v.satuan_kecil, mt_depo_stok_nm_v.nama_golongan, mt_depo_stok_nm_v.nama_sub_golongan, mt_bagian.nama_bagian, kartu_stok.stok_akhir, AVG(mt_rekap_stok_nm.harga_beli) as harga_beli, AVG(harga_jual) as hargajual, is_active');
			$this->db->from('mt_depo_stok_nm_v');
			$this->db->join('mt_rekap_stok_nm', 'mt_rekap_stok_nm.kode_brg=mt_depo_stok_nm_v.kode_brg','left');
			$this->db->join('fr_tc_far_detail', 'fr_tc_far_detail.kode_brg=mt_depo_stok_nm_v.kode_brg','left');
			$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=mt_depo_stok_nm_v.kode_bagian','left');
			$this->db->join('( SELECT * FROM tc_kartu_stok_nm WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok_nm WHERE tgl_input <= '."'".$tgl_stok."'".' AND tgl_input is not null GROUP BY kode_brg)) AS kartu_stok', 'kartu_stok.kode_brg=mt_depo_stok_nm_v.kode_brg','left');

			

			// $this->db->where('is_active', 1);
			$this->db->group_by( 'mt_depo_stok_nm_v.kode_brg, mt_depo_stok_nm_v.nama_brg, mt_depo_stok_nm_v.satuan_besar, mt_depo_stok_nm_v.satuan_kecil, mt_depo_stok_nm_v.nama_golongan, mt_depo_stok_nm_v.nama_sub_golongan, mt_bagian.nama_bagian, kartu_stok.stok_akhir , mt_rekap_stok_nm.harga_beli, fr_tc_far_detail.harga_jual, is_active');
			$this->db->order_by( 'nama_bagian','ASC' );
			$this->db->order_by( 'nama_brg','ASC' );
			$query = $this->db->get();
			// print_r($this->db->last_query());die;
		// }

		return $this->db->last_query();
	}

	public function akunting_mod_8(){

		$kode_bagian = '070101';
		$this->db->select('a.kode_brg, b.nama_brg, CAST(AVG(c.harga_beli) as INT) as harga_beli');
		$this->db->from('mt_depo_stok_nm a');
		$this->db->join('mt_barang_nm b', 'b.kode_brg=a.kode_brg', 'INNER');
		$this->db->join('mt_rekap_stok_nm c', 'a.kode_brg=c.kode_brg', 'LEFT');
		$this->db->where('a.kode_bagian', $kode_bagian);
		$this->db->where('a.kode_brg IS NOT NULL');
		$this->db->where('a.is_active', 1);
		$this->db->group_by('a.kode_brg, b.nama_brg');
		$this->db->order_by('b.nama_brg', 'ASC');
		$this->db->get();
		return $this->db->last_query();
	}

    // ================= END MOD AKUNTING DAN KEUANGAN =================== //

	public function penjualan_obat_bpjs(){
		
		$query = 'select kode_brg, kode_perusahaan, SUM(jumlah_tebus) as jumlah_tebus, AVG(harga_beli) as harga_beli, AVG(harga_jual) as harga_jual 
		from fr_hisbebasluar_v where MONTH(tgl_trans)= '."'".$_POST['from_month']."'".' and YEAR(tgl_trans) = '."'".$_POST['year']."'".' group by kode_brg, kode_perusahaan';
			
		return $this->db->query($query)->result_array();
    }
    
	public function penjualan_obat_umum(){
		
		$query = 'select kode_brg, kode_perusahaan, kode_kelompok, SUM(jumlah_tebus) as jumlah_tebus, AVG(harga_beli) as harga_beli, AVG(harga_jual) as harga_jual 
		from fr_hisbebasluar_v where MONTH(tgl_trans)= '."'".$_POST['from_month']."'".' and YEAR(tgl_trans) = '."'".$_POST['year']."'".' group by kode_brg, kode_perusahaan, kode_kelompok';
			
		return $this->db->query($query)->result_array();
    }
    
	public function penjualan_obat_internal(){
		
		$query = 'select kode_brg, kode_perusahaan, kode_kelompok, SUM(jumlah_tebus) as jumlah_tebus, AVG(harga_beli) as harga_beli, AVG(harga_jual) as harga_jual 
		from fr_hisbebasluar_v where MONTH(tgl_trans)= '."'".$_POST['from_month']."'".' and YEAR(tgl_trans) = '."'".$_POST['year']."'".' AND kode_kelompok NOT IN(1,2,3,5,6) group by kode_brg, kode_perusahaan, kode_kelompok';
			
		return $this->db->query($query)->result_array();
	}

	public function penerimaan_penjualan(){
		$query = "SELECT b.kode_brg, b.content, SUM(b.jumlah_kirim) as jumlah_kirim, AVG(b.harga) as harga
			FROM tc_penerimaan_barang_detail as b 
			LEFT JOIN tc_penerimaan_barang a ON a.kode_penerimaan=b.kode_penerimaan
			WHERE MONTH(a.tgl_penerimaan)= ".$_POST['from_month']." and YEAR(a.tgl_penerimaan) = ".$_POST['year']." AND a.tgl_penerimaan is not null 
			GROUP BY b.kode_brg, b.content";
			
		return $this->db->query($query)->result_array();
    }
    
	public function distribusi_unit(){
		$query = "SELECT a.kode_brg, SUM(jumlah_permintaan) as jumlah_permintaan, SUM(jumlah_penerimaan) as jumlah_penerimaan, f.harga_beli
				  FROM tc_permintaan_inst_det a
				  LEFT JOIN mt_barang c ON c.kode_brg=a.kode_brg 
				  LEFT JOIN tc_permintaan_inst e ON e.id_tc_permintaan_inst=a.id_tc_permintaan_inst 
				  LEFT JOIN mt_bagian g ON g.kode_bagian=e.kode_bagian_minta 
				  LEFT JOIN mt_rekap_stok f ON f.kode_brg=a.kode_brg 
				  WHERE MONTH(e.tgl_permintaan)= ".$_POST['from_month']." and YEAR(e.tgl_permintaan) = ".$_POST['year']." AND e.tgl_permintaan is not null and status_selesai in (4,5)
				  GROUP BY a.kode_brg, f.harga_beli";
			
		return $this->db->query($query)->result_array();
	}

	public function get_saldo_b(){
		$year=$_POST['year'] - 1;
		$query = 'select kode_brg, tgl_input, stok_awal, stok_akhir, pemasukan, pengeluaran, kode_bagian, keterangan, petugas, id_kartu, kode_brg
		from tc_kartu_stok where id_kartu IN 
					(SELECT MAX(id_kartu) AS id_kartu from tc_kartu_stok where YEAR(tgl_input) = '."'".$year."'".' group by kode_brg)';
			
		return $this->db->query($query)->result_array();
	}

	public function penjualan_obat_bpjs_b(){
		
		$query = 'select kode_brg, kode_perusahaan, SUM(jumlah_tebus) as jumlah_tebus, AVG(harga_beli) as harga_beli, AVG(harga_jual) as harga_jual 
		from fr_hisbebasluar_v where YEAR(tgl_trans) = '."'".$_POST['year']."'".' group by kode_brg, kode_perusahaan';
			
		return $this->db->query($query)->result_array();
    }
    
	public function penjualan_obat_umum_b(){
		
		$query = 'select kode_brg, kode_perusahaan, kode_kelompok, SUM(jumlah_tebus) as jumlah_tebus, AVG(harga_beli) as harga_beli, AVG(harga_jual) as harga_jual 
		from fr_hisbebasluar_v where YEAR(tgl_trans) = '."'".$_POST['year']."'".' group by kode_brg, kode_perusahaan, kode_kelompok';
			
		return $this->db->query($query)->result_array();
    }
    
	public function penjualan_obat_internal_b(){
		
		$query = 'select kode_brg, kode_perusahaan, kode_kelompok, SUM(jumlah_tebus) as jumlah_tebus, AVG(harga_beli) as harga_beli, AVG(harga_jual) as harga_jual 
		from fr_hisbebasluar_v where YEAR(tgl_trans) = '."'".$_POST['year']."'".' AND kode_kelompok NOT IN(1,2,3,5,6) group by kode_brg, kode_perusahaan, kode_kelompok';
			
		return $this->db->query($query)->result_array();
	}

	public function penerimaan_penjualan_b(){
		$query = "SELECT b.kode_brg, b.content, SUM(b.jumlah_kirim) as jumlah_kirim, AVG(b.harga) as harga
			FROM tc_penerimaan_barang as a 
			LEFT JOIN tc_penerimaan_barang_detail b ON a.kode_penerimaan=b.kode_penerimaan
			WHERE YEAR(a.tgl_penerimaan) = ".$_POST['year']." AND a.tgl_penerimaan is not null 
			GROUP BY b.kode_brg, b.content";
			
		return $this->db->query($query)->result_array();
    }
    
	public function distribusi_unit_b(){
		$query = "SELECT a.kode_brg, SUM(jumlah_permintaan) as jumlah_permintaan, SUM(jumlah_penerimaan) as jumlah_penerimaan, f.harga_beli
				  FROM tc_permintaan_inst_det a
				  LEFT JOIN mt_barang c ON c.kode_brg=a.kode_brg 
				  LEFT JOIN tc_permintaan_inst e ON e.id_tc_permintaan_inst=a.id_tc_permintaan_inst 
				  LEFT JOIN mt_bagian g ON g.kode_bagian=e.kode_bagian_minta 
				  LEFT JOIN mt_rekap_stok f ON f.kode_brg=a.kode_brg 
				  WHERE YEAR(e.tgl_permintaan) = ".$_POST['year']." AND e.tgl_permintaan is not null
				  GROUP BY a.kode_brg, f.harga_beli";
			
		return $this->db->query($query)->result_array();
	}

	public function get_saldo(){
		$month=$_POST['from_month'] - 1;
		$query = 'select kode_brg, tgl_input, stok_awal, stok_akhir, pemasukan, pengeluaran, kode_bagian, keterangan, petugas, id_kartu, kode_brg
		from tc_kartu_stok where id_kartu IN 
					(SELECT MAX(id_kartu) AS id_kartu from tc_kartu_stok where MONTH(tgl_input)= '."'".$month."'".' and YEAR(tgl_input) = '."'".$_POST['year']."'".' group by kode_brg)';
			
		return $this->db->query($query)->result_array();
	}

	public function get_saldo_awal_nm(){
		$month = isset($_POST['from_month'])?$_POST['from_month']: $_GET['month'];
		$last_month = ( ($month - 1) == 0 ) ? 12 : ($month - 1);
		$year = isset($_POST['year'])?$_POST['year'] : $_GET['year'];
		$last_year = ( ($month - 1) == 0 ) ? $year -1 : $year;
		$bagian = '070101';
		$this->db->select('a.kode_brg, nama_brg, tgl_input, stok_awal, stok_akhir, pemasukan, pengeluaran, a.kode_bagian, keterangan, petugas, id_kartu, nama_bagian');
		$this->db->from('tc_kartu_stok_nm a');
		$this->db->join('mt_barang_nm b','b.kode_brg=a.kode_brg','left');
		$this->db->join('mt_bagian c','c.kode_bagian = a.kode_bagian','left');
		$this->db->where('id_kartu IN (SELECT MAX(id_kartu) AS id_kartu from tc_kartu_stok_nm d where MONTH(tgl_input) <= '."'".$last_month."'".' and YEAR(tgl_input) = '."'".$last_year."'".' group by d.kode_brg, d.kode_bagian)');
		$this->db->where('nama_brg is not null');
		// $this->db->where('stok_akhir > 0');
		$this->db->where('a.kode_bagian', $bagian);
		$query = $this->db->get()->result_array();
		
		// echo $this->db->last_query();die;
		return $query;
	}

	public function get_saldo_awal(){
		$from_tgl = isset($_POST['from_tgl'])?$_POST['from_tgl']: date('Y-m-d');
		$to_tgl = isset($_POST['to_tgl'])?$_POST['to_tgl']: date('Y-m-d');
		$bagian = isset($_POST['bagian'])?$_POST['bagian'] : $_GET['kode_bagian'];
		if(!empty($bagian)){
			$this->db->select('tc_kartu_stok.kode_brg, nama_brg, tgl_input, stok_awal, stok_akhir, pemasukan, pengeluaran, tc_kartu_stok.kode_bagian, keterangan, petugas, id_kartu, nama_bagian');
			$this->db->from('tc_kartu_stok');
			$this->db->join('mt_barang','mt_barang.kode_brg=tc_kartu_stok.kode_brg','left');
			$this->db->join('mt_bagian','mt_bagian.kode_bagian = tc_kartu_stok.kode_bagian','left');
			$this->db->where('id_kartu IN (SELECT MAX(id_kartu) AS id_kartu from tc_kartu_stok where CAST(tgl_input as DATE) <= '."'".$from_tgl."'".' group by tc_kartu_stok.kode_brg, tc_kartu_stok.kode_bagian)');
			$this->db->where('nama_brg is not null');
			$this->db->where('stok_akhir > 0');
			$this->db->where('tc_kartu_stok.kode_bagian', $bagian);
			$query = $this->db->get()->result_array();
		}else{
			$this->db->select('tc_kartu_stok.kode_brg, SUM(stok_akhir) as stok_akhir');
			$this->db->from('tc_kartu_stok');
			$this->db->join('mt_barang','mt_barang.kode_brg=tc_kartu_stok.kode_brg','left');
			$this->db->join('mt_bagian','mt_bagian.kode_bagian = tc_kartu_stok.kode_bagian','left');
			$this->db->where('id_kartu IN (SELECT MAX(id_kartu) AS id_kartu from tc_kartu_stok where CAST(tgl_input as DATE) <= '."'".$from_tgl."'".' group by tc_kartu_stok.kode_brg, tc_kartu_stok.kode_bagian)');
			$this->db->where('nama_brg is not null');
			$this->db->where('stok_akhir > 0');
			$this->db->group_by('tc_kartu_stok.kode_brg');
			$query = $this->db->get()->result_array();
		}
		
		// echo $this->db->last_query();die;
		return $query;
	}

	public function get_saldo_akhir(){
		$month = isset($_POST['from_month'])?$_POST['from_month']: $_GET['month'];
		$year = isset($_POST['year'])?$_POST['year'] : $_GET['year'];
		$this->db->select('tc_kartu_stok.kode_brg, nama_brg, tgl_input, stok_awal, stok_akhir, pemasukan, pengeluaran, tc_kartu_stok.kode_bagian, keterangan, petugas, id_kartu, nama_bagian');
		$this->db->from('tc_kartu_stok');
		$this->db->join('mt_barang','mt_barang.kode_brg=tc_kartu_stok.kode_brg','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian = tc_kartu_stok.kode_bagian','left');
		$this->db->where('id_kartu IN (SELECT MAX(id_kartu) AS id_kartu from tc_kartu_stok where MONTH(tgl_input) <= '."'".$month."'".' and YEAR(tgl_input) = '."'".$year."'".' group by tc_kartu_stok.kode_brg, tc_kartu_stok.kode_bagian)');
		$this->db->where('nama_brg is not null');
		$this->db->where('stok_akhir > 0');
		$this->db->where('mt_bagian.status_aktif', 1);
		$this->db->order_by('mt_bagian.nama_bagian', 'ASC');
		return $this->db->get()->result_array();
	}

	public function permintaan_brg_medis_unit(){

		$from_tgl = isset($_POST['from_tgl'])?$_POST['from_tgl'] : date('Y-m-d');
		$to_tgl = isset($_POST['to_tgl'])?$_POST['to_tgl'] : date('Y-m-d');
		$bagian = isset($_POST['bagian'])?$_POST['bagian'] : $_GET['kode_bagian'];
		if(empty($bagian)){
			return $this->penerimaan_brg_gudang();
		}else{
			$this->db->select('b.kode_brg, SUM(b.jumlah_penerimaan) as jumlah_penerimaan');
			$this->db->from('tc_permintaan_inst a');
			$this->db->join('tc_permintaan_inst_det b', 'a.id_tc_permintaan_inst=b.id_tc_permintaan_inst', 'left');
			if(!empty($bagian)){
				$this->db->where('a.kode_bagian_minta', $bagian);
			}
			$this->db->where("CAST(a.tgl_input_terima as DATE) BETWEEN '".$from_tgl."' AND '".$to_tgl."' ");
			$this->db->where('a.tgl_input_terima is not null');
			$this->db->group_by('b.kode_brg');
			$query = $this->db->get()->result_array();
			// echo $this->db->last_query();die;
			return $query;
		}
		
		
	}

	public function penerimaan_brg_gudang(){

		$from_tgl = isset($_POST['from_tgl'])?$_POST['from_tgl'] : date('Y-m-d');
		$to_tgl = isset($_POST['to_tgl'])?$_POST['to_tgl'] : date('Y-m-d');
		$bagian = isset($_POST['bagian'])?$_POST['bagian'] : $_GET['kode_bagian'];

		$query = "select b.kode_brg, SUM(b.jumlah_kirim_decimal * b.content) as jumlah_penerimaan, SUM(b.jumlah_kirim_decimal * b.harga_net) as biaya, AVG(CAST((b.harga_net/b.content) AS INT)) as harga_beli from tc_penerimaan_barang_detail b
		left join tc_penerimaan_barang a on a.id_penerimaan=b.id_penerimaan
		where CAST(a.tgl_penerimaan as DATE) BETWEEN "."'".$from_tgl."'"." AND "."'".$to_tgl."'"."
		GROUP BY b.kode_brg";
		// echo $query;
		return $this->db->query($query)->result_array();
	}

	public function penerimaan_brg_gudang_nm(){

		$month = isset($_POST['from_month'])?$_POST['from_month'] : $_GET['month'];
		$year = isset($_POST['year'])?$_POST['year'] : $_GET['year'];
		$bagian = '070101';

		$query = "select b.kode_brg, SUM(b.jumlah_kirim_decimal * b.content) as jumlah_penerimaan, SUM(b.jumlah_kirim_decimal * b.harga_net) as biaya, AVG(CAST((b.harga_net/b.content) AS INT)) as harga_beli from tc_penerimaan_barang_nm_detail b
		left join tc_penerimaan_barang_nm a on a.id_penerimaan=b.id_penerimaan
		where MONTH(a.tgl_penerimaan) = ".$month." AND YEAR(a.tgl_penerimaan) = ".$year." 
		GROUP BY b.kode_brg, MONTH(a.tgl_penerimaan), YEAR(a.tgl_penerimaan)";
		// echo $query;
		return $this->db->query($query)->result_array();
	}

	public function penjualan_obat(){
		
		$from_tgl = isset($_POST['from_tgl'])?$_POST['from_tgl'] : date('Y-m-d');
		$to_tgl = isset($_POST['to_tgl'])?$_POST['to_tgl'] : date('Y-m-d');
		$bagian = isset($_POST['bagian'])?$_POST['bagian'] : $_GET['kode_bagian'];

		// laporan penjualan obat berdasarkan data transaksi
		// ditambah dengan data obat yang ditangguhkan dan sudah dilakukan mutasi
		// untuk obat yang ditangguhkan dan belum dimutasikan, maka belum menjadi penjualan

		$filter_bagian = (empty($bagian)) ? "" : "AND ( a.kode_bagian= '060101' OR a.kode_bagian_asal= '060101' )";

		$query = 'SELECT (SUM(penjualan)-SUM(retur)) as jml_terjual, SUM(retur) as retur, AVG(harga_beli) as harga_rata_satuan, "kode_brg", "nama_brg", "satuan_kecil"
		FROM "view_lap_mutasi_obat"
		WHERE "tanggal" BETWEEN '."'".$from_tgl."'".' AND '."'".$to_tgl."'".' AND kode_bagian = '."'".$bagian."'".'
		GROUP BY "kode_brg", "nama_brg", "satuan_kecil" 
		HAVING (SUM(penjualan)-SUM(retur)) >0
		ORDER BY (SUM(penjualan)-SUM(retur)) DESC, "nama_brg" ASC';

		// echo $query;

		return $this->db->query($query)->result_array();
	}

	public function penjualan_obat_umum_bmhp(){
		
		$query = 'select kode_barang, kode_perusahaan, SUM(jumlah) as jumlah, AVG(bill_rs) as bill_rs
		from tc_trans_pelayanan where kode_bagian = '."'".$_POST['bagian']."'".' AND MONTH(tgl_transaksi)= '."'".$_POST['from_month']."'".' and YEAR(tgl_transaksi) = '."'".$_POST['year']."'".' and jenis_tindakan=9 group by kode_barang, kode_perusahaan';
			
		return $this->db->query($query)->result_array();
	}

	public function penjualan_obat_internal_bmhp(){
		
		$from_tgl = isset($_POST['from_tgl'])?$_POST['from_tgl'] : date('Y-m-d');
		$to_tgl = isset($_POST['to_tgl'])?$_POST['to_tgl'] : date('Y-m-d');
		$bagian = isset($_POST['bagian'])?$_POST['bagian'] : $_GET['kode_bagian'];
		$this->db->select('kode_brg, kode_bagian, SUM(pengeluaran) as jumlah');
		$this->db->from('tc_kartu_stok');
		if(!empty($bagian)){
			$this->db->where('kode_bagian', $bagian);
		}
		$this->db->where('jenis_transaksi IN (7)');
		$this->db->where('CAST(tgl_input as DATE) BETWEEN '."'".$from_tgl."'".' AND '."'".$to_tgl."'".' ');
		$this->db->group_by('kode_brg, kode_bagian');
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function distribusi_barang_unit(){
		
		$from_tgl = isset($_POST['from_tgl'])?$_POST['from_tgl']: date('Y-m-d');
		$to_tgl = isset($_POST['to_tgl'])?$_POST['to_tgl']: date('Y-m-d');
		$bagian = isset($_POST['bagian'])?$_POST['bagian'] : $_GET['kode_bagian'];

		$query = "select a.kode_brg, b.kode_bagian_kirim, SUM(a.jumlah_penerimaan) as jumlah from tc_permintaan_inst_det a 
		left join tc_permintaan_inst b on b.id_tc_permintaan_inst = a.id_tc_permintaan_inst
		where CAST(a.tgl_kirim as DATE) BETWEEN  "."'".$from_tgl."'"."  AND "."'".$to_tgl."'"."  AND b.kode_bagian_kirim = '".$bagian."' GROUP BY a.kode_brg, b.kode_bagian_kirim";
		// echo $query;
		return $this->db->query($query)->result_array();
	}

	public function distribusi_barang_unit_nm(){
		
		$month = isset($_POST['from_month'])?$_POST['from_month']: $_GET['month'];
		$year = isset($_POST['year'])?$_POST['year'] : $_GET['year'];
		$bagian = '070101';

		$query = "select a.kode_brg, b.kode_bagian_kirim, SUM(a.jumlah_penerimaan) as jumlah from tc_permintaan_inst_nm_det a 
		left join tc_permintaan_inst_nm b on b.id_tc_permintaan_inst = a.id_tc_permintaan_inst
		where MONTH(a.tgl_kirim) = ".$month." AND YEAR(a.tgl_kirim)=".$year." AND b.kode_bagian_kirim = '".$bagian."' GROUP BY a.kode_brg, b.kode_bagian_kirim, MONTH(a.tgl_kirim), YEAR(a.tgl_kirim)";
		// echo $query;
		return $this->db->query($query)->result_array();
	}

	// ================= PENGADAAN DAN LOGISTIK =================== //
    public function pengadaan_mod_12(){

		$c_month = ($_POST['from_month'] > 0) ? "AND MONTH(c.tgl_input)= ".$_POST['from_month']."" : "";
		$d_month = ($_POST['from_month'] > 0) ? "AND MONTH(d.tgl_input)= ".$_POST['from_month']."" : "";
		$f_month = ($_POST['from_month'] > 0) ? "AND MONTH(f.tgl_input)= ".$_POST['from_month']."" : "";
		$a_month = ($_POST['from_month'] > 0) ? "AND MONTH(a.tgl_input)= ".$_POST['from_month']."" : "";

		$query = "select a.kode_brg, b.nama_brg, a.kode_bagian,
					(select SUM(pengeluaran) from tc_kartu_stok c where c.jenis_transaksi in (14,16) AND 
						(c.kode_brg=a.kode_brg AND c.kode_bagian=a.kode_bagian and c.kode_bagian='".$_POST['bagian']."' 
						AND YEAR(c.tgl_input)=".$_POST['year']." ".$c_month.")
						group by kode_brg, kode_bagian ) as pengeluaran,
					(select SUM(pemasukan) from tc_kartu_stok d where d.jenis_transaksi in (8,17) AND 
						(d.kode_brg=a.kode_brg AND d.kode_bagian=a.kode_bagian and d.kode_bagian='".$_POST['bagian']."' 
						AND YEAR(d.tgl_input)=".$_POST['year']." ".$d_month.")
						group by kode_brg, kode_bagian ) as retur, 
					(select stok_akhir from tc_kartu_stok g where g.id_kartu in (
						select MAX(id_kartu) from tc_kartu_stok f 
						where f.kode_bagian='".$_POST['bagian']."' AND YEAR(f.tgl_input)=".$_POST['year']." ".$f_month."
						group by f.kode_brg, f.kode_bagian) 
						AND (g.kode_brg=a.kode_brg AND g.kode_bagian=a.kode_bagian)) as stok_akhir
				from tc_kartu_stok a 
				left join mt_barang b on b.kode_brg=a.kode_brg
				where a.kode_bagian='".$_POST['bagian']."' AND YEAR(a.tgl_input)=".$_POST['year']." ".$a_month."
				GROUP BY b.nama_brg, a.kode_brg, a.kode_bagian order by b.nama_brg ASC";
		// echo $query; exit;
		return $query;

    }

	public function pengadaan_mod_6(){

        $tc_penerimaan_barang_detail = ($_POST['jenis'] == 'medis')?'tc_penerimaan_barang_detail':'tc_penerimaan_barang_nm_detail';
        $mt_barang = ($_POST['jenis'] == 'medis')?'mt_barang':'mt_barang_nm';
        $tc_penerimaan_barang = ($_POST['jenis'] == 'medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
        $tc_po = ($_POST['jenis'] == 'medis')?'tc_po':'tc_po_nm';

		$query = "select e.kodesupplier, e.namasupplier as supplier, SUM((a.harga_net * a.jumlah_kirim_decimal)) as total_format_money
						from ".$tc_penerimaan_barang_detail." a 
						left join ".$mt_barang." c on c.kode_brg=a.kode_brg
						left join ".$tc_penerimaan_barang." b on b.id_penerimaan=a.id_penerimaan
						left join ".$tc_po." d on d.id_tc_po=b.id_tc_po
						left join mt_supplier e on e.kodesupplier=d.kodesupplier
						where YEAR(b.tgl_penerimaan)=".$_POST['year']." and MONTH(b.tgl_penerimaan)=".$_POST['from_month']." 
						GROUP BY e.kodesupplier, e.namasupplier
						ORDER BY SUM((a.harga_net * a.jumlah_kirim_decimal)) DESC";
		// echo $query; exit;
		return $query;

    }
    
    public function pengadaan_mod_6_detail_transaksi(){

        $tc_penerimaan_barang_detail = ($_GET['jenis'] == 'medis')?'tc_penerimaan_barang_detail':'tc_penerimaan_barang_nm_detail';
        $mt_barang = ($_GET['jenis'] == 'medis')?'mt_barang':'mt_barang_nm';
        $tc_penerimaan_barang = ($_GET['jenis'] == 'medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
        $tc_po = ($_GET['jenis'] == 'medis')?'tc_po':'tc_po_nm';

		$query = "select e.kodesupplier,e.namasupplier as supplier, CAST(b.tgl_penerimaan as DATE) as tgl_terima, b.no_faktur, f.nama_brg, f.satuan_besar,
        a.jumlah_kirim_decimal as jml_kirim, a.harga_net as harga, (a.jumlah_kirim_decimal * a.harga_net) as total_biaya, a.disc
        from ".$tc_penerimaan_barang_detail." a left join mt_barang c on c.kode_brg=a.kode_brg 
        left join ".$mt_barang." f on f.kode_brg=a.kode_brg
        left join ".$tc_penerimaan_barang." b on b.id_penerimaan=a.id_penerimaan 
        left join ".$tc_po." d on d.id_tc_po=b.id_tc_po 
        left join mt_supplier e on e.kodesupplier=d.kodesupplier 
        where YEAR(b.tgl_penerimaan)=".$_GET['year']." and MONTH(b.tgl_penerimaan)=".$_GET['month']." and e.kodesupplier=".$_GET['kode_supplier']."";
		// echo $query; exit;
		return $query;

    }
    
    public function pengadaan_mod_6_shw_w_d_trx(){

        $tc_penerimaan_barang_detail = ($_GET['jenis'] == 'medis')?'tc_penerimaan_barang_detail':'tc_penerimaan_barang_nm_detail';
        $mt_barang = ($_GET['jenis'] == 'medis')?'mt_barang':'mt_barang_nm';
        $tc_penerimaan_barang = ($_GET['jenis'] == 'medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
        $tc_po = ($_GET['jenis'] == 'medis')?'tc_po':'tc_po_nm';

		$query = "select e.kodesupplier,e.namasupplier as supplier, CAST(b.tgl_penerimaan as DATE) as tgl_terima, b.no_faktur, f.nama_brg, f.satuan_besar,
        a.jumlah_kirim_decimal as jml_kirim, a.harga_net as harga, (a.jumlah_kirim_decimal * a.harga_net) as total_biaya, a.disc
        from ".$tc_penerimaan_barang_detail." a left join mt_barang c on c.kode_brg=a.kode_brg 
        left join ".$mt_barang." f on f.kode_brg=a.kode_brg
        left join ".$tc_penerimaan_barang." b on b.id_penerimaan=a.id_penerimaan 
        left join ".$tc_po." d on d.id_tc_po=b.id_tc_po 
        left join mt_supplier e on e.kodesupplier=d.kodesupplier 
        where YEAR(b.tgl_penerimaan)=".$_GET['year']." and MONTH(b.tgl_penerimaan)=".$_GET['month']." ORDER BY DAY(b.tgl_penerimaan) ASC";
		// echo $query; exit;
		return $query;

	}
























    // ================= DIBAWAH INI ADALAH QUERY YANG BELUM DI SORTIR =================== //

	public function kunjungan_mod_2(){

		$query = 'SELECT a.tgl_jam_masuk, a.no_mr, a.no_registrasi, c.nama_pasien, e.nama_bagian, b.nama_pegawai, d.nama_perusahaan, a.no_sep 
					FROM [tc_registrasi] a 
					left join [mt_karyawan] b ON a.kode_dokter=b.kode_dokter 
					left join [mt_master_pasien] c ON a.no_mr=c.no_mr 
					left join [mt_perusahaan] d ON a.kode_perusahaan=d.kode_perusahaan 
					left join [mt_bagian] e ON a.kode_bagian_masuk=e.kode_bagian 
					where a.tgl_jam_masuk >='."'".$_POST['tgl']."'".' 
					AND no_registrasi IN (SELECT no_registrasi  FROM [tc_kunjungan] where tgl_masuk >='."'".$_POST['tgl']."'".' group by no_registrasi) 
					and a.status_batal is NULL
					order by b.nama_pegawai,a.no_mr ASC';

		return $query;

	}
	public function kunjungan_mod_3(){

		$query = 'SELECT a.no_registrasi,a.no_mr,c.nama_pasien,c.no_hp,d.nama_perusahaan,
					a.tgl_jam_masuk,e.nama_bagian, b.nama_pegawai, a.no_sep 
					FROM [tc_registrasi] a left join [mt_karyawan] b ON a.kode_dokter=b.kode_dokter 
					left join [mt_master_pasien] c ON a.no_mr=c.no_mr 
					left join [mt_perusahaan] d ON a.kode_perusahaan=d.kode_perusahaan 
					left join [mt_bagian] e ON a.kode_bagian_masuk=e.kode_bagian  
					where convert(varchar,a.tgl_jam_masuk,23) between '."'".$_POST['from_tgl']."'".' and '."'".$_POST['to_tgl']."'".'
					order by a.no_registrasi DESC';

		return $query;

	}

	public function kunjungan_mod_4(){
		$kd='010901';

		$query = 'SELECT tc_kunjungan.no_kunjungan,tc_kunjungan.no_mr,tc_kunjungan.no_registrasi,mt_karyawan.nama_pegawai as dokter, asal.nama_bagian as asal_bagian, tujuan.nama_bagian as tujuan_bagian, mt_master_pasien.nama_pasien, tc_kunjungan.tgl_masuk, pl_tc_poli.id_pl_tc_poli,pl_tc_poli.no_antrian, pl_tc_poli.status_batal, pl_tc_poli.status_periksa, pl_tc_poli.kode_gcu, tc_kunjungan.tgl_keluar
					FROM [tc_kunjungan] left join mt_master_pasien ON mt_master_pasien.no_mr=tc_kunjungan.no_mr
		left join mt_karyawan ON mt_karyawan.kode_dokter=tc_kunjungan.kode_dokter
		left join mt_bagian as asal ON asal.kode_bagian=tc_kunjungan.kode_bagian_asal
		left join mt_bagian as tujuan ON tujuan.kode_bagian=tc_kunjungan.kode_bagian_tujuan
		left join pl_tc_poli ON pl_tc_poli.no_kunjungan=tc_kunjungan.no_kunjungan
					where tc_kunjungan.kode_bagian_tujuan='."'".$kd."'".' and convert(varchar,tc_kunjungan.tgl_masuk,23) between '."'".$_POST['from_tgl']."'".' and '."'".$_POST['to_tgl']."'".'
					order by tc_kunjungan.no_kunjungan ASC ';
					// echo '<pre>';print_r($query);
		return $query;

	}

	public function kunjungan_mod_5(){
		
		$where = "no_antrian = 1 and c.nama_pegawai is not null";
		if(isset($_POST['kode_bagian'])){
			$where .= " AND a.kode_bagian = '".$_POST['kode_bagian']."'";
			$where .= " AND a.kode_dokter = '".$_POST['kode_dokter']."'";
		}

		if(isset($_POST['from_tgl']) AND isset($_POST['to_tgl'])){
			$where .= " AND CAST(a.tgl_keluar_poli as DATE) BETWEEN '".$_POST['from_tgl']."' AND '".$_POST['to_tgl']."'";
		}

		$query = 'select c.nama_pegawai, b.nama_bagian, tgl_keluar_poli from pl_tc_poli a 
					left join mt_bagian b on b.kode_bagian = a.kode_bagian
					left join mt_dokter_v c on c.kode_dokter= a.kode_dokter
					where '.$where.'
					GROUP BY c.nama_pegawai, b.nama_bagian, tgl_keluar_poli, a.kode_bagian, a.kode_dokter
					ORDER BY tgl_keluar_poli ASC';
		return $query;

	}

	public function pengadaan_mod_1(){

		$query = 'SELECT tc_kartu_stok_nm_v.kode_brg, tc_kartu_stok_nm_v.nama_brg, harga_barang.harga_satuan_po, 
							harga_barang.satuan_besar, content, stok_akhir, satuan_kecil, 
							nama_golongan, nama_kategori , nama_sub_golongan, tgl_input 
					FROM tc_kartu_stok_nm_v 
					LEFT JOIN (
								SELECT c.kode_brg, d.nama_brg, CAST(AVG(c.harga_satuan) AS INT) as harga_satuan_po, d.satuan_besar
								FROM tc_po_nm_det c, mt_barang_nm d
								WHERE c.kode_brg=d.kode_brg AND c.kode_brg in (select a.kode_brg from mt_barang_nm a) 
								GROUP BY c.kode_brg, d.nama_brg, d.satuan_besar
							  ) AS harga_barang ON harga_barang.kode_brg=tc_kartu_stok_nm_v.kode_brg
					
					WHERE id_kartu IN 
					(SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok_nm WHERE kode_bagian='."'070101'".' GROUP BY kode_brg) 
					AND tgl_input <= '."'".$_POST['tgl']."'".' AND kode_bagian='."'070101'".'
					ORDER BY nama_kategori, nama_golongan, nama_sub_golongan ASC';
		// echo $query;
		return $query;

	}
	public function pengadaan_mod_2(){
		$tc_permintaan = ( $_POST['status'] == '1' ) ? 'tc_permintaan_det_v' : 'tc_permintaan_nm_v' ;
		$mt_rekap_stok = ( $_POST['status'] == '1' ) ? 'mt_rekap_stok' : 'mt_rekap_stok_nm' ;
		$mt_golongan = ( $_POST['status'] == '1' ) ? 'mt_golongan' : 'mt_golongan_nm' ;
		$mt_barang = ( $_POST['status'] == '1' ) ? 'mt_barang' : 'mt_barang_nm' ;

		$filter_bagian = ($_POST['bagian'] != '') ? 'AND a.kode_bagian_minta ='."'".$_POST['bagian']."'".'' : '';
		
		$query = 'select a.kode_brg, a.nama_brg, a.nama_bagian_minta, SUM(a.jumlah_penerimaan)as total, a.satuan_kecil, CAST(AVG(c.harga_beli) as INT) as harga_beli, e.nama_golongan
		from '.$tc_permintaan.' a
		LEFT JOIN '.$mt_rekap_stok.' c on a.kode_brg=c.kode_brg
		LEFT JOIN '.$mt_barang.' d ON d.kode_brg=a.kode_brg
		LEFT JOIN '.$mt_golongan.' e ON e.kode_golongan=d.kode_golongan
		where CAST(a.tgl_kirim as DATE) BETWEEN '."'".$_POST['from_tgl']."'".'  and '."'".$_POST['to_tgl']."'".' '.$filter_bagian.'
		group by a.kode_brg, a.nama_brg, a.nama_bagian_minta, a.satuan_kecil, e.nama_golongan
		order by e.nama_golongan, a.nama_brg ASC';
		// echo $query; exit;
		return $query;

	}
	public function pengadaan_mod_3(){

		$tc_permintaan = ( $_POST['status'] == '1' ) ? 'tc_permintaan_det_v' : 'tc_permintaan_nm_v' ;
		$mt_rekap_stok = ( $_POST['status'] == '1' ) ? 'mt_rekap_stok' : 'mt_rekap_stok_nm' ;

		$query = 'select a.kode_brg, a.nama_brg, a.nama_bagian_minta, SUM(a.jumlah_penerimaan)as total, a.satuan_kecil,  
						CAST(AVG(c.harga_beli) as INT) as harga_beli
						from '.$tc_permintaan.' a
						LEFT JOIN '.$mt_rekap_stok.' c on a.kode_brg=c.kode_brg
						where MONTH(a.tgl_kirim) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".' AND YEAR(tgl_kirim)='."'".$_POST['year']."'".'
						group by a.kode_brg, a.nama_brg, a.nama_bagian_minta, a.satuan_kecil
						order by a.nama_brg ASC';

		return $query;

	}
	public function pengadaan_mod_4(){

		if($_POST['keterangan']=='medis'){
			if($_POST['status']=='1'){
					$query = 'SELECT c.nama_brg, b.kode_brg, b.satuan_besar, b.rasio, a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan, jml_besar, jml_besar_acc, jml_acc_penyetuju 
					FROM tc_permohonan_det b 
					LEFT JOIN tc_permohonan a ON b.id_tc_permohonan=a.id_tc_permohonan 
					LEFT JOIN mt_barang c ON c.kode_brg=b.kode_brg 
					WHERE YEAR(a.tgl_permohonan)='."'".$_POST['year']."'".' AND MONTH(a.tgl_permohonan)='."'".$_POST['from_month']."'".' AND (jml_acc_penyetuju is null or jml_acc_penyetuju = 0) ORDER BY a.tgl_permohonan DESC';
				}
				else{
					$query = 'SELECT c.nama_brg, b.kode_brg, b.satuan_besar, b.rasio, a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan, jml_besar, jml_besar_acc, jml_acc_penyetuju 
					FROM tc_permohonan_det b 
					LEFT JOIN tc_permohonan a ON b.id_tc_permohonan=a.id_tc_permohonan 
					LEFT JOIN mt_barang c ON c.kode_brg=b.kode_brg 
					WHERE YEAR(a.tgl_permohonan)='."'".$_POST['year']."'".' AND MONTH(a.tgl_permohonan)='."'".$_POST['from_month']."'".' AND (jml_acc_penyetuju is not null or jml_acc_penyetuju > 0) ORDER BY a.tgl_permohonan DESC';
				}
		}
		else{
			if($_POST['status']=='0'){
					$query = 'SELECT a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan, COUNT(id_tc_permohonan_det) jml_brg, a.status_batal, a.tgl_acc, a.no_acc FROM tc_permohonan_nm a INNER JOIN tc_permohonan_nm_det b ON b.id_tc_permohonan=a.id_tc_permohonan WHERE a.status_kirim=1 AND a.status_batal=0 AND YEAR(a.tgl_permohonan)='."'".$_POST['year']."'".' AND MONTH(a.tgl_permohonan)='."'".$_POST['from_month']."'".' GROUP BY a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan, a.status_batal, a.tgl_acc, a.no_acc ORDER BY a.tgl_permohonan DESC';
				}
				else{
					$query = 'SELECT a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan, COUNT(id_tc_permohonan_det) jml_brg, a.status_batal, a.tgl_acc, a.no_acc FROM tc_permohonan_nm a INNER JOIN tc_permohonan_nm_det b ON b.id_tc_permohonan=a.id_tc_permohonan WHERE a.status_kirim=1 AND a.status_batal=1 AND YEAR(a.tgl_permohonan)='."'".$_POST['year']."'".' AND MONTH(a.tgl_permohonan)='."'".$_POST['from_month']."'".' GROUP BY a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan, a.status_batal, a.tgl_acc, a.no_acc ORDER BY a.tgl_permohonan DESC';
				}
		}
		
	
		return $query;

	}
	public function pengadaan_mod_5(){

		// print_r($_POST);die;
		switch ($_POST['search_by']) {
			case 'usulan':
				# code...
				$filter = 'e.tgl_permohonan';
				break;
			case 'penerbitan_po':
				# code...
				$filter = 'a.tgl_po';
				break;
			case 'penerimaan':
				# code...
				$filter = 'f.tgl_penerimaan';
				break;
		}

		if($_POST['keterangan']=='medis'){
			$this->db->select('e.tgl_permohonan,
			e.tgl_acc,
			a.no_po,
			CAST ( a.tgl_po AS DATE ) AS tgl_po,
			b.kode_brg,
			h.jumlah_besar as jumlah_usulan,
			CAST(h.jml_acc_penyetuju as int) as jumlah_diacc,
			b.jumlah_besar as jml_order,
			h.satuan_besar,
			b.harga_satuan_netto,
			b.jumlah_harga_netto,
			c.nama_brg,
			d.namasupplier, i.nama_pabrik,
			e.kode_permohonan,
			f.tgl_penerimaan,
			f.no_faktur,
			CAST(a.updated_date as date) as revisi,
				jumlah_kirim_decimal AS jml_diterima');

			if($_POST['search_by'] == 'usulan'){
				$this->db->from('tc_permohonan_det h');
				$this->db->join('tc_permohonan e', 'e.id_tc_permohonan = h.id_tc_permohonan', 'LEFT');
				$this->db->join('tc_po_det b', 'b.id_tc_permohonan_det= h.id_tc_permohonan_det', 'LEFT');
				$this->db->join('tc_po a', 'b.id_tc_po= a.id_tc_po', 'LEFT');
				$this->db->join('mt_barang c', 'c.kode_brg= h.kode_brg', 'LEFT');
				$this->db->join('mt_pabrik i', 'i.kode_pabrik= c.kode_pabrik', 'LEFT');
				$this->db->join('mt_supplier d', 'd.kodesupplier= a.kodesupplier', 'LEFT');
				$this->db->join('tc_penerimaan_barang_detail g', 'g.id_tc_po_det = b.id_tc_po_det', 'LEFT');
				$this->db->join('tc_penerimaan_barang f', 'f.id_penerimaan = g.id_penerimaan ', 'LEFT');
				$this->db->where('YEAR(e.tgl_permohonan) = ', $_POST['year']);
				$this->db->where('MONTH(e.tgl_permohonan) = ', $_POST['from_month']);
				$this->db->order_by('e.tgl_permohonan', 'ASC');
			}

			if($_POST['search_by'] == 'penerbitan_po'){
				$this->db->from('tc_po_det b');
				$this->db->join('tc_po a', 'b.id_tc_po= a.id_tc_po', 'LEFT');
				$this->db->join('tc_permohonan_det h', 'b.id_tc_permohonan_det= h.id_tc_permohonan_det', 'LEFT');
				$this->db->join('tc_permohonan e', 'e.id_tc_permohonan = h.id_tc_permohonan', 'LEFT');
				$this->db->join('mt_barang c', 'c.kode_brg= b.kode_brg', 'LEFT');
				$this->db->join('mt_pabrik i', 'i.kode_pabrik= c.kode_pabrik', 'LEFT');
				$this->db->join('mt_supplier d', 'd.kodesupplier= a.kodesupplier', 'LEFT');
				$this->db->join('tc_penerimaan_barang_detail g', 'g.id_tc_po_det = b.id_tc_po_det', 'LEFT');
				$this->db->join('tc_penerimaan_barang f', 'f.id_penerimaan = g.id_penerimaan ', 'LEFT');
				$this->db->where('YEAR(a.tgl_po) = ', $_POST['year']);
				$this->db->where('MONTH(a.tgl_po) = ', $_POST['from_month']);
				$this->db->order_by('a.tgl_po', 'ASC');
			}

			if($_POST['search_by'] == 'penerimaan'){
				$this->db->from('tc_penerimaan_barang_detail g ');
				$this->db->join('tc_po_det b', 'b.id_tc_po_det= g.id_tc_po_det', 'LEFT');
				$this->db->join('tc_po a', 'b.id_tc_po= a.id_tc_po', 'LEFT');
				$this->db->join('tc_permohonan_det h', 'h.id_tc_permohonan_det= b.id_tc_permohonan_det', 'LEFT');
				$this->db->join('tc_permohonan e', 'e.id_tc_permohonan = h.id_tc_permohonan', 'LEFT');
				$this->db->join('mt_barang c', 'c.kode_brg= g.kode_brg', 'LEFT');
				$this->db->join('mt_pabrik i', 'i.kode_pabrik= c.kode_pabrik', 'LEFT');
				$this->db->join('mt_supplier d', 'd.kodesupplier= a.kodesupplier', 'LEFT');
				$this->db->join('tc_penerimaan_barang f', 'f.id_penerimaan = g.id_penerimaan ', 'LEFT');
				$this->db->where('YEAR(f.tgl_penerimaan) = ', $_POST['year']);
				$this->db->where('MONTH(f.tgl_penerimaan) = ', $_POST['from_month']);
				$this->db->order_by('f.tgl_penerimaan', 'ASC');
			}
			$this->db->get();

			$query = $this->db->last_query();
			// echo $query; die;
				
		}
		else{
			
			$query = 'SELECT a.no_po, a.tgl_po, b.kode_brg, b.jumlah_besar, b.harga_satuan_netto, b.jumlah_harga_netto, c.nama_brg, d.namasupplier FROM tc_po_nm a JOIN tc_po_nm_det b ON b.id_tc_po=a.id_tc_po JOIN mt_barang_nm c ON c.kode_brg=b.kode_brg JOIN mt_supplier d ON d.kodesupplier=a.kodesupplier WHERE YEAR(a.tgl_po)='."'".$_POST['year']."'".' AND MONTH(a.tgl_po)='."'".$_POST['from_month']."'".' AND (b.status_batal<>1 OR b.status_batal IS NULL) ORDER BY a.tgl_po, d.namasupplier, c.nama_brg';
				
		}
			
		return $query;

	}

	// public function pengadaan_mod_8(){

	// 	$query = 'SELECT tc_kartu_stok_nm_vv.kode_brg, tc_kartu_stok_nm_vv.nama_brg, tc_kartu_stok_nm_vv.harga_beli, tc_kartu_stok_nm_vv.harga_update, 
	// 						satuan_besar, content, stok_akhir, satuan_kecil, 
	// 						nama_golongan, nama_kategori , nama_sub_golongan, tgl_input 
	// 				FROM tc_kartu_stok_nm_vv 
					
	// 				WHERE id_kartu IN 
	// 				(SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok_nm GROUP BY kode_brg) 
	// 				AND tgl_input <= '."'".$_POST['tgl']."'".'
	// 				ORDER BY nama_kategori, nama_golongan, nama_sub_golongan ASC';

	public function pengadaan_mod_7(){
		$tc_permintaan = ( $_POST['status'] == '1' ) ? 'tc_permintaan_det_v' : 'tc_permintaan_nm_v' ;
		$mt_rekap_stok = ( $_POST['status'] == '1' ) ? 'mt_rekap_stok' : 'mt_rekap_stok_nm' ;

		$query = 'select a.kode_brg, a.nama_brg, SUM(a.jumlah_penerimaan)as total, a.satuan_kecil,  
					CAST(AVG(c.harga_beli) as INT) as harga_beli, a.nama_bagian_minta
					from '.$tc_permintaan.' a
					LEFT JOIN '.$mt_rekap_stok.' c on a.kode_brg=c.kode_brg
					where CAST(a.tgl_kirim as DATE) BETWEEN '."'".$_POST['from_tgl']."'".'  and '."'".$_POST['to_tgl']."'".' group by a.kode_brg, a.nama_brg, a.satuan_kecil, a.nama_bagian_minta';
		// echo $query; die;
		return $query;

	}
public function pengadaan_mod_8(){
		if($_POST['status']==1){
			$query = 'SELECT tc_kartu_stok_vv.kode_brg, tc_kartu_stok_vv.nama_brg, tc_kartu_stok_vv.harga_beli, tc_kartu_stok_vv.harga_update, 
									satuan_besar, content, stok_akhir, satuan_kecil, 
									nama_golongan, nama_kategori , nama_sub_golongan, tgl_input 
							FROM tc_kartu_stok_vv 
							
							WHERE id_kartu IN 
							(SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok GROUP BY kode_brg) 
							AND tgl_input <= '."'".$_POST['tgl']."'".'
							ORDER BY nama_kategori, nama_golongan, nama_sub_golongan ASC';
		}
		else{
		$query = 'SELECT tc_kartu_stok_nm_vv.kode_brg, tc_kartu_stok_nm_vv.nama_brg, tc_kartu_stok_nm_vv.harga_beli, tc_kartu_stok_nm_vv.harga_update, 
							satuan_besar, content, stok_akhir, satuan_kecil, 
							nama_golongan, nama_kategori , nama_sub_golongan, tgl_input 
					FROM tc_kartu_stok_nm_vv 
					
					WHERE id_kartu IN 
					(SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok_nm GROUP BY kode_brg) 
					AND tgl_input <= '."'".$_POST['tgl']."'".'
					ORDER BY nama_kategori, nama_golongan, nama_sub_golongan ASC';
				}
		return $query;

	}
	public function pengadaan_mod_9(){
		$query = 'SELECT tgl_input, tc_kartu_stok_nm.kode_brg, nama_brg, harga_beli,
					stok_akhir,
							SUM(pemasukan) as total_brg_masuk, SUM(pengeluaran) as total_brg_keluar
					FROM tc_kartu_stok_nm 
					LEFT JOIN mt_barang_nm ON mt_barang_nm.kode_brg=tc_kartu_stok_nm.kode_brg
					WHERE tgl_input BETWEEN '."'".$_POST['tgl1']."'".'  and '."'".$_POST['tgl2']."'".'
					AND tc_kartu_stok_nm.kode_bagian='."'".$_POST['bagian']."'".' AND nama_brg IS NOT NULL
					GROUP BY tgl_input, tc_kartu_stok_nm.kode_brg, nama_brg, harga_beli, stok_akhir, satuan_kecil
					ORDER BY kode_brg, nama_brg ASC';

		return $query;

	}

	public function pengadaan_mod_10(){
		$t_penerimaan = ($_POST['jenis']=='Non Medis')?'tc_penerimaan_barang_nm':'tc_penerimaan_barang';
		$t_penerimaan_dtl = ($_POST['jenis']=='Non Medis')?'tc_penerimaan_barang_nm_detail':'tc_penerimaan_barang_detail';
		$t_po = ($_POST['jenis']=='Non Medis')?'tc_po_nm':'tc_po';
		$t_po_d = ($_POST['jenis']=='Non Medis')?'tc_po_nm_det':'tc_po_det';
		$m_barang = ($_POST['jenis']=='Non Medis')?'mt_barang_nm':'mt_barang';
		
		if($_POST['frekuensi']=='harian'){
			$query = 'SELECT a.kode_penerimaan, a.no_po, a.tgl_penerimaan, c.namasupplier, a.no_faktur, b.kode_brg, d.nama_brg, b.jumlah_pesan, b.jumlah_kirim, d.satuan_besar, CAST(b.content AS int) AS content FROM '.$t_penerimaan.' a JOIN '.$t_penerimaan_dtl.' b ON b.kode_penerimaan=a.kode_penerimaan 
				JOIN mt_supplier c ON c.kodesupplier=a.kodesupplier
				JOIN '.$m_barang.' d ON b.kode_brg=d.kode_brg
				WHERE a.tgl_penerimaan='."'".$_POST['tgl']."'".' 
				GROUP BY a.kode_penerimaan, a.no_po, a.tgl_penerimaan, c.namasupplier, a.no_faktur, b.kode_brg, d.nama_brg, b.jumlah_pesan, b.jumlah_kirim, d.satuan_besar, b.content ORDER BY a.tgl_penerimaan desc';
		}
		elseif($_POST['frekuensi']=='bulanan'){
			$query = 'SELECT a.kode_penerimaan, a.no_po, a.tgl_penerimaan, c.namasupplier, a.no_faktur, b.kode_brg, d.nama_brg, b.jumlah_pesan, b.jumlah_kirim, d.satuan_besar, CAST(b.content AS int) AS content FROM '.$t_penerimaan.' a 
				JOIN '.$t_penerimaan_dtl.' b ON b.kode_penerimaan=a.kode_penerimaan 
				JOIN mt_supplier c ON c.kodesupplier=a.kodesupplier 
				JOIN '.$m_barang.' d ON b.kode_brg=d.kode_brg
				WHERE MONTH(a.tgl_penerimaan)='."'".$_POST['from_month']."'".' AND YEAR(a.tgl_penerimaan)='."'".$_POST['year']."'".' GROUP BY a.kode_penerimaan, a.no_po, a.tgl_penerimaan, c.namasupplier, a.no_faktur, b.kode_brg, d.nama_brg, b.jumlah_pesan, b.jumlah_kirim, d.satuan_besar, b.content ORDER BY a.tgl_penerimaan desc';
		}
		else{
			$query = 'SELECT a.kode_penerimaan, a.no_po, a.tgl_penerimaan, c.namasupplier, a.no_faktur, b.kode_brg, d.nama_brg, b.jumlah_pesan, b.jumlah_kirim, d.satuan_besar, CAST(b.content AS int) AS content FROM '.$t_penerimaan.' a 
				JOIN '.$t_penerimaan_dtl.' b ON b.kode_penerimaan=a.kode_penerimaan 
				JOIN mt_supplier c ON c.kodesupplier=a.kodesupplier 
				JOIN '.$m_barang.' d ON b.kode_brg=d.kode_brg
				WHERE YEAR(a.tgl_penerimaan)='."'".$_POST['year']."'".' GROUP BY a.kode_penerimaan, a.no_po, a.tgl_penerimaan, c.namasupplier, a.no_faktur, b.kode_brg, d.nama_brg, b.jumlah_pesan, b.jumlah_kirim, d.satuan_besar, b.content ORDER BY a.tgl_penerimaan desc';
		}
			// echo '<pre>';print_r($query);
		return $query;

	}

	public function pengadaan_mod_11(){
		$t_po = ($_POST['keterangan']=='nmmedis')?'tc_po_nm':'tc_po';
		$t_po_d = ($_POST['keterangan']=='nmmedis')?'tc_po_nm_det':'tc_po_det';
		$m_barang = ($_POST['keterangan']=='nmmedis')?'mt_barang_nm':'mt_barang';
		$join = ($_POST['keterangan']=='nmmedis')?'mt_rekap_stok_nm':'mt_rekap_stok';
		
		$query = 'SELECT b.id_tc_po_det, b.id_tc_po, b.id_tc_permohonan_det, 
					b.id_tc_permohonan, b.kode_brg, b.jumlah_besar, 
					b.jumlah_besar_acc, b.content, b.harga_satuan as harga_satuan, 
					b.harga_satuan_netto as harga_satuan_netto, 
					b.jumlah_harga_netto as jumlah_harga_netto,b.jumlah_harga as jumlah_harga, 
					b.discount, b.discount_rp as discount_rp, c.nama_brg, c.satuan_besar, a.no_po, a.tgl_po, 
					a.ppn as ppn, a.total_sbl_ppn as total_sbl_ppn, a.total_stl_ppn as total_stl_ppn, a.discount_harga as total_diskon, 
					a.term_of_pay, a.diajukan_oleh, a.tgl_kirim as estimasi_kirim, e.namasupplier, e.alamat, e.kota, e.telpon1, a.no_urut_periodik
					 FROM '.$t_po.' a 
					  left join '.$t_po_d.' b ON b.id_tc_po=a.id_tc_po 
					  left JOIN '.$m_barang.' c ON c.kode_brg=b.kode_brg
					  left join '.$join.' d ON d.kode_brg=b.kode_brg
					  left join mt_supplier e ON e.kodesupplier=a.kodesupplier
					  where a.kodesupplier=43 AND MONTH(a.tgl_po) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".'
						AND YEAR(a.tgl_po)='."'".$_POST['year']."'".'
					  order by a.no_po, c.nama_brg ASC';

		return $query;

	}
	public function farmasi_mod_5(){
		$query = 'SELECT tgl_input, tc_kartu_stok.kode_brg, nama_brg, harga_beli,
					CASE WHEN mt_barang.flag_medis=1 THEN '."'Alkes'".' ELSE '."'Obat'".' END as jenis,
					stok_akhir,
							SUM(pemasukan) as total_brg_masuk, SUM(pengeluaran) as total_brg_keluar
					FROM tc_kartu_stok 
					LEFT JOIN mt_barang ON mt_barang.kode_brg=tc_kartu_stok.kode_brg
					WHERE tgl_input BETWEEN '."'".$_POST['tgl1']."'".'  and '."'".$_POST['tgl2']."'".'
					AND tc_kartu_stok.kode_bagian='."'".$_POST['kode_bagian']."'".' AND nama_brg IS NOT NULL
					GROUP BY tgl_input, tc_kartu_stok.kode_brg, nama_brg, harga_beli, flag_medis, stok_akhir, satuan_kecil
					ORDER BY flag_medis, nama_brg ASC';

		return $query;

	}
	public function farmasi_mod_10(){
		$query = 'SELECT a.id_tc_permintaan_inst_det, jumlah_permintaan, 
				jumlah_penerimaan, a.kode_brg, c.nama_brg, CAST(f.harga_beli as INT) as harga_beli, c.content as rasio, 
				c.satuan_kecil, c.satuan_besar, e.nomor_permintaan, e.jenis_permintaan, 
				e.tgl_permintaan, f.jml_sat_kcl as jumlah_stok_sebelumnya, g.kode_bagian, g.nama_bagian
				 FROM tc_permintaan_inst_det a LEFT JOIN mt_barang c ON c.kode_brg=a.kode_brg
				  LEFT JOIN tc_permintaan_inst e ON e.id_tc_permintaan_inst=a.id_tc_permintaan_inst 
				  LEFT JOIN mt_bagian g ON g.kode_bagian=e.kode_bagian_minta 
				  LEFT JOIN mt_rekap_stok f ON f.kode_brg=a.kode_brg 
				  WHERE g.kode_bagian='."'".$_POST['bagian']."'".' and tgl_permintaan BETWEEN '."'".$_POST['tgl1']."'".'  and '."'".$_POST['tgl2']."'".' AND jumlah_penerimaan > 0 GROUP BY a.id_tc_permintaan_inst_det,
				   jumlah_permintaan, jumlah_penerimaan, a.kode_brg, 
				   c.nama_brg, f.harga_beli, c.content, c.satuan_kecil, c.satuan_besar, e.nomor_permintaan, e.jenis_permintaan, 
					e.tgl_permintaan, f.jml_sat_kcl, g.kode_bagian, g.nama_bagian ORDER BY e.tgl_permintaan ASC';

		return $query;

	}

	public function farmasi_mod_11(){
		if($_POST['status']==1){
			$query = 'SELECT a.kode_brg, b.nama_brg, a.pengeluaran 
				 FROM tc_kartu_stok a LEFT JOIN mt_barang b ON b.kode_brg=a.kode_brg
				  WHERE a.kode_bagian='."'".$_POST['bagian']."'".' and month(a.tgl_input)='."'".$_POST['from_month']."'".'  and
				  year(a.tgl_input)='."'".$_POST['year']."'".' and a.jenis_transaksi=7 GROUP BY a.kode_brg, b.nama_brg';
			} else {
			$query = 'SELECT a.kode_brg, c.nama_brg, b.SUM(jumlah_permintaan) AS jumlah 
				 FROM tc_permintaan_inst_nm a
				 LEFT JOIN tc_permintaan_inst_nm_det b ON a.id_tc_permintaan_inst=b.id_tc_permintaan_inst
				 LEFT JOIN mt_barang c ON c.kode_brg=b.kode_brg
				  WHERE a.kode_bagian_minta='."'".$_POST['bagian']."'".' and month(a.tgl_input)='."'".$_POST['from_month']."'".'  and
				  year(a.tgl_input)='."'".$_POST['year']."'".' and a.status_selesai IN (4,5) GROUP BY a.kode_brg, b.nama_brg';

		}
		
		
		return $query;

	}


	public function farmasi_mod_4(){

			$monthdr=$_POST['year']."-".$_POST['from_month']."-01 00:00:	00";
			$monthto=$_POST['year']."-".$_POST['to_month']."-31 23:59:59";
					
		if($_POST['keterangan']=='karyawan'){
			$query = 'SELECT c.nama_pasien as penjamin,c.no_induk,c.kode_intern,a.kode_tc_trans_kasir,a.kode_trans_far,
			d.tunai,d.debet,d.kredit,e.nama_kelompok,f.tgl_trans,e.fak_kali_obat,SUM(a.bill_rs) AS bill_rs, SUM(a.lain_lain) AS lain_lain 
			FROM tc_trans_pelayanan a inner join mt_master_pasien b on a.no_mr=b.no_mr 
			inner join mt_master_pasien c on b.no_induk=c.no_mr 
			inner join tc_trans_kasir d
			on a.kode_tc_trans_kasir=d.kode_tc_trans_kasir 
			left join mt_nasabah e on e.kode_kelompok=c.kode_kelompok 
			left join fr_tc_far f ON f.kode_trans_far=a.kode_trans_far
			WHERE a.kode_bagian = 060101 and a.status_selesai>1 
			and a.kode_trans_far is not null and c.kode_intern in (4,7,12)
			and a.no_registrasi=0 and a.no_mr is not null and (b.penanggung is not null or b.no_induk is not null)
			AND MONTH(a.tgl_transaksi) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".'
			AND YEAR(a.tgl_transaksi)='."'".$_POST['year']."'".' GROUP BY c.nama_pasien,c.no_induk,c.kode_intern,a.kode_tc_trans_kasir,a.kode_trans_far,
			d.tunai,e.nama_kelompok,f.tgl_trans,e.fak_kali_obat,d.debet,d.kredit having(d.tunai+d.debet+d.kredit)=0
			ORDER BY c.nama_pasien,a.kode_trans_far DESC';
		}
		elseif($_POST['keterangan']=='dokter'){
			$query = 'SELECT c.nama_pasien as penjamin,c.no_induk,c.kode_intern,a.kode_tc_trans_kasir,a.kode_trans_far,
			d.tunai,d.debet,d.kredit,e.nama_kelompok,f.tgl_trans,e.fak_kali_obat,SUM(a.bill_rs) AS bill_rs, SUM(a.lain_lain) AS lain_lain 
			FROM tc_trans_pelayanan a inner join mt_master_pasien b on a.no_mr=b.no_mr 
			inner join mt_master_pasien c on b.no_induk=c.no_mr 
			inner join tc_trans_kasir d
			on a.kode_tc_trans_kasir=d.kode_tc_trans_kasir 
			left join mt_nasabah e on e.kode_kelompok=c.kode_kelompok 
			left join fr_tc_far f ON f.kode_trans_far=a.kode_trans_far
			WHERE a.kode_bagian = 060101 and a.status_selesai>1 
			and a.kode_trans_far is not null and c.kode_intern in (15)
			and a.no_registrasi=0 and a.no_mr is not null and (b.penanggung is not null or b.no_induk is not null)
			AND MONTH(a.tgl_transaksi) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".'
			AND YEAR(a.tgl_transaksi)='."'".$_POST['year']."'".' GROUP BY c.nama_pasien,c.no_induk,c.kode_intern,a.kode_tc_trans_kasir,a.kode_trans_far,
			d.tunai,e.nama_kelompok,f.tgl_trans,e.fak_kali_obat,d.debet,d.kredit having(d.tunai+d.debet+d.kredit)=0
			ORDER BY c.nama_pasien,a.kode_trans_far DESC';
		}
		elseif($_POST['keterangan']=='pimpinan'){
			$query = 'SELECT c.nama_pasien as penjamin,c.no_induk,c.kode_intern,a.kode_tc_trans_kasir,a.kode_trans_far,
			d.tunai,d.debet,d.kredit,e.nama_kelompok,f.tgl_trans,e.fak_kali_obat,SUM(a.bill_rs) AS bill_rs, SUM(a.lain_lain) AS lain_lain 
			FROM tc_trans_pelayanan a inner join mt_master_pasien b on a.no_mr=b.no_mr 
			inner join mt_master_pasien c on b.no_induk=c.no_mr 
			inner join tc_trans_kasir d
			on a.kode_tc_trans_kasir=d.kode_tc_trans_kasir 
			left join mt_nasabah e on e.kode_kelompok=c.kode_kelompok 
			left join fr_tc_far f ON f.kode_trans_far=a.kode_trans_far
			WHERE a.kode_bagian = 060101 and a.status_selesai>1 
			and a.kode_trans_far is not null and c.kode_intern in (16)
			and a.no_registrasi=0 and a.no_mr is not null and (b.penanggung is not null or b.no_induk is not null)
			AND MONTH(a.tgl_transaksi) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".'
			AND YEAR(a.tgl_transaksi)='."'".$_POST['year']."'".' GROUP BY c.nama_pasien,c.no_induk,c.kode_intern,a.kode_tc_trans_kasir,a.kode_trans_far,
			d.tunai,e.nama_kelompok,f.tgl_trans,e.fak_kali_obat,d.debet,d.kredit having(d.tunai+d.debet+d.kredit)=0
			ORDER BY c.nama_pasien,a.kode_trans_far DESC';
		}
		elseif($_POST['keterangan']=='saham'){
			$query = 'SELECT c.nama_pasien as penjamin,c.no_induk,c.kode_intern,a.kode_tc_trans_kasir,a.kode_trans_far,
			d.tunai,d.debet,d.kredit,e.nama_kelompok,f.tgl_trans,e.fak_kali_obat,SUM(a.bill_rs) AS bill_rs, SUM(a.lain_lain) AS lain_lain 
			FROM tc_trans_pelayanan a inner join mt_master_pasien b on a.no_mr=b.no_mr 
			inner join mt_master_pasien c on b.no_induk=c.no_mr 
			inner join tc_trans_kasir d
			on a.kode_tc_trans_kasir=d.kode_tc_trans_kasir 
			left join mt_nasabah e on e.kode_kelompok=c.kode_kelompok 
			left join fr_tc_far f ON f.kode_trans_far=a.kode_trans_far
			WHERE a.kode_bagian = 060101 and a.status_selesai>1 
			and a.kode_trans_far is not null and c.kode_intern in (9,11,13,14)
			and a.no_registrasi=0 and a.no_mr is not null and (b.penanggung is not null or b.no_induk is not null)
			AND MONTH(a.tgl_transaksi) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".'
			AND YEAR(a.tgl_transaksi)='."'".$_POST['year']."'".' GROUP BY c.nama_pasien,c.no_induk,c.kode_intern,a.kode_tc_trans_kasir,a.kode_trans_far,
			d.tunai,e.nama_kelompok,f.tgl_trans,e.fak_kali_obat,d.debet,d.kredit having(d.tunai+d.debet+d.kredit)=0
			ORDER BY c.nama_pasien,a.kode_trans_far DESC';
		}

		else{
			$query = 'SELECT c.nama_pasien as penjamin,c.no_induk,c.kode_intern,a.kode_tc_trans_kasir,a.kode_trans_far,
			d.tunai,d.debet,d.kredit,e.nama_kelompok,f.tgl_trans,e.fak_kali_obat,SUM(a.bill_rs) AS bill_rs, SUM(a.lain_lain) AS lain_lain 
			FROM tc_trans_pelayanan a inner join mt_master_pasien b on a.no_mr=b.no_mr 
			inner join mt_master_pasien c on b.no_induk=c.no_mr 
			inner join tc_trans_kasir d
			on a.kode_tc_trans_kasir=d.kode_tc_trans_kasir 
			left join mt_nasabah e on e.kode_kelompok=c.kode_kelompok 
			left join fr_tc_far f ON f.kode_trans_far=a.kode_trans_far
			WHERE a.kode_bagian = 060101 and a.status_selesai>1 
			and a.kode_trans_far is not null and c.kode_intern in (8)
			and a.no_registrasi=0 and a.no_mr is not null and (b.penanggung is not null or b.no_induk is not null)
			AND MONTH(a.tgl_transaksi) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".'
			AND YEAR(a.tgl_transaksi)='."'".$_POST['year']."'".' GROUP BY c.nama_pasien,c.no_induk,c.kode_intern,a.kode_tc_trans_kasir,a.kode_trans_far,
			d.tunai,e.nama_kelompok,f.tgl_trans,e.fak_kali_obat,d.debet,d.kredit having(d.tunai+d.debet+d.kredit)=0
			ORDER BY c.nama_pasien,a.kode_trans_far DESC';
		}
		
		return $query;

	}
	public function farmasi_mod_3(){
		$t_penerimaan = ($_POST['jenis']=='Non Medis')?'tc_penerimaan_barang_nm':'tc_penerimaan_barang';
		$t_penerimaan_dtl = ($_POST['jenis']=='Non Medis')?'tc_penerimaan_barang_nm_detail':'tc_penerimaan_barang_detail';

		if($_POST['frekuensi']=='harian'){
			$query = 'SELECT a.kode_penerimaan, a.no_po, a.tgl_penerimaan, c.namasupplier, a.no_faktur, COUNT(b.kode_detail_penerimaan_barang) jml_brg, SUM(b.jumlah_kirim) jml_krm FROM '.$t_penerimaan.' a JOIN '.$t_penerimaan_dtl.' b ON b.kode_penerimaan=a.kode_penerimaan JOIN mt_supplier c ON c.kodesupplier=a.kodesupplier WHERE a.tgl_penerimaan='."'".$_POST['tgl']."'".' GROUP BY a.kode_penerimaan, a.no_po, a.tgl_penerimaan, c.namasupplier, a.no_faktur ORDER BY a.tgl_penerimaan desc';
		}
		elseif($_POST['frekuensi']=='bulanan'){
			$query = 'SELECT a.kode_penerimaan, a.no_po, a.tgl_penerimaan, c.namasupplier, a.no_faktur, COUNT(b.kode_detail_penerimaan_barang) jml_brg, SUM(b.jumlah_kirim) jml_krm FROM '.$t_penerimaan.' a JOIN '.$t_penerimaan_dtl.' b ON b.kode_penerimaan=a.kode_penerimaan JOIN mt_supplier c ON c.kodesupplier=a.kodesupplier WHERE MONTH(a.tgl_penerimaan)='."'".$_POST['from_month']."'".' AND YEAR(a.tgl_penerimaan)='."'".$_POST['year']."'".' GROUP BY a.kode_penerimaan, a.no_po, a.tgl_penerimaan, c.namasupplier, a.no_faktur ORDER BY a.tgl_penerimaan desc';
		}
		else{
			$query = 'SELECT a.kode_penerimaan, a.no_po, a.tgl_penerimaan, c.namasupplier, a.no_faktur, COUNT(b.kode_detail_penerimaan_barang) jml_brg, SUM(b.jumlah_kirim) jml_krm FROM tc_penerimaan_barang a JOIN '.$t_penerimaan_dtl.' b ON b.kode_penerimaan=a.kode_penerimaan JOIN mt_supplier c ON c.kodesupplier=a.kodesupplier WHERE YEAR(a.tgl_penerimaan)='."'".$_POST['year']."'".' GROUP BY a.kode_penerimaan, a.no_po, a.tgl_penerimaan, c.namasupplier, a.no_faktur ORDER BY a.tgl_penerimaan desc';
		}

		return $query;

	}

	public function farmasi_mod_2(){

		$query = 'select b.kode_brg, b.nama_brg, b.content, kartu_stok.stok_akhir, b.satuan_kecil, 
					cast(c.harga_beli as int)as harga_beli, (kartu_stok.stok_akhir * cast(c.harga_beli as int)) as total, kartu_stok.tgl_input, kartu_stok.keterangan from mt_depo_stok a
					left join mt_barang b on b.kode_brg=a.kode_brg
					left join mt_rekap_stok c on c.id_obat=b.id_obat
					left join (
						select * from tc_kartu_stok
						WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu 
											FROM tc_kartu_stok 
											WHERE CAST(tgl_input as DATE) <= '."'".$_POST['tgl']."'".' AND kode_bagian='."'".$_POST['kode_bagian']."'".'
											GROUP BY kode_brg) 
						and kode_bagian='."'".$_POST['kode_bagian']."'".'
					)as kartu_stok on kartu_stok.kode_brg=a.kode_brg
					where a.kode_bagian='."'".$_POST['kode_bagian']."'".' and b.kode_brg is not null
					order by b.nama_brg ASC';
		// echo $query;
		return $query;

	}

	public function farmasi_mod_1(){

		$query = 'SELECT tc_kartu_stok.kode_brg, nama_brg, 
					CASE WHEN mt_barang.flag_medis=1 THEN '."'Alkes'".' ELSE '."'Obat'".' END as jenis,
							SUM(pemasukan) as total_brg_masuk, SUM(pengeluaran) as total_brg_keluar, 
							(SUM(pemasukan) - SUM(pengeluaran)) as selisih, satuan_kecil as satuan
					FROM tc_kartu_stok 
					LEFT JOIN mt_barang ON mt_barang.kode_brg=tc_kartu_stok.kode_brg
					WHERE YEAR(tgl_input)='.$_POST['tahun'].' AND tc_kartu_stok.kode_bagian='."'".$_POST['kode_bagian']."'".' AND nama_brg IS NOT NULL
					GROUP BY tc_kartu_stok.kode_brg, nama_brg, flag_medis, satuan_kecil
					ORDER BY flag_medis, nama_brg ASC';

		return $query;

	}
	public function farmasi_mod_6(){
		$txt_tanggal_ajax1=$_POST['tgl1']." 00:00:00";
		$txt_tanggal_ajax2=$_POST['tgl2']." 23:59:59";
		$query = 'SELECT year(tgl_trans) as tahun_trans,month(tgl_trans) as bln_trans,day(tgl_trans) as tgl_tran,id_tc_far_racikan FROM fr_tc_far as a, fr_tc_far_detail as b 
				  where a.kode_trans_far=b.kode_trans_far and tgl_trans between '."'".$txt_tanggal_ajax1."'".' 
				  and '."'".$txt_tanggal_ajax2."'".' and status_transaksi=1 
			order by year(tgl_trans),month(tgl_trans),day(tgl_trans) ,id_tc_far_racikan ';

		return $query;

	}
	public function farmasi_mod_7(){
		$txt_tanggal_ajax1=$_POST['tgl1']." 00:00:00";
		$txt_tanggal_ajax2=$_POST['tgl2']." 23:59:59";
		$query = 'SELECT  b.nama_brg,b.satuan_kecil,a.harga_beli,a.total_harga,a.tempat_pembelian,a.tgl_pembelian,a.harga_jual,a.jumlah_kcl, (a.harga_jual * a.jumlah_kcl) as tot_harga_jual FROM fr_pengadaan_cito as a inner join mt_barang as b on a.kode_brg=b.kode_brg  WHERE (a.tgl_pembelian BETWEEN '."'".$txt_tanggal_ajax1."'".' and '."'".$txt_tanggal_ajax2."'".') and a.status_transaksi=1';

		return $query;

	}

	public function farmasi_mod_8(){
		$txt_tanggal_ajax1=$_POST['tgl1']." 00:00:00";
		$txt_tanggal_ajax2=$_POST['tgl2']." 23:59:59";
		if($_POST['bagian']==''){
			$query = 'SELECT a.*,b.nama_pegawai,c.nama_bagian FROM fr_tc_pesan_resep as a inner join mt_karyawan as b on a.kode_dokter=b.kode_dokter inner join mt_bagian as c on a.kode_bagian_asal=c.kode_bagian WHERE lokasi_tebus='."'".$_POST['lokasi']."'".' and tgl_pesan BETWEEN '."'".$txt_tanggal_ajax1."'".' and '."'".$txt_tanggal_ajax2."'".' order by kode_pesan_resep';
		}
		else{
			$query = 'SELECT a.*,b.nama_pegawai,c.nama_bagian FROM fr_tc_pesan_resep as a inner join mt_karyawan as b on a.kode_dokter=b.kode_dokter inner join mt_bagian as c on a.kode_bagian_asal=c.kode_bagian WHERE lokasi_tebus='."'".$_POST['lokasi']."'".' and tgl_pesan BETWEEN '."'".$txt_tanggal_ajax1."'".' and '."'".$txt_tanggal_ajax2."'".' and kode_bagian_asal='."'".$_POST['bagian']."'".' order by kode_pesan_resep';
		}
		return $query;

	}

	public function farmasi_mod_9(){
		$txt_tanggal_ajax1=$_POST['tgl1']." 00:00:00";
		$txt_tanggal_ajax2=$_POST['tgl2']." 23:59:59";
		
			$query = 'select a.kode_brg, a.nama_brg, a.satuan_kecil, SUM(b.jumlah_tebus) as jml_tebus,SUM(b.jumlah_retur) as jml_retur,
			(SUM(b.jumlah_tebus)-SUM(b.jumlah_retur)) as net_qty,SUM(b.net_rp) as net_rp from mt_barang a right join (SELECT kode_brg, nama_brg, satuan_kecil,harga_jual , SUM(jumlah_tebus) AS jumlah_tebus, SUM(jumlah_retur) 
			AS jumlah_retur,SUM(harga_r) AS harga_r, SUM(harga_r_retur) AS harga_r_retur,
			((SUM(jumlah_tebus)-SUM(jumlah_retur))*harga_jual) as net_rp FROM fr_hisbebasluar_v where nama_brg is not null 
			and tgl_trans between '."'".$txt_tanggal_ajax1."'".' and '."'".$txt_tanggal_ajax2."'".' 
			and status_transaksi=1 GROUP BY kode_brg, nama_brg, satuan_kecil,harga_jual) b
			on a.kode_brg=b.kode_brg group by a.kode_brg, a.nama_brg, a.satuan_kecil order by a.nama_brg';
		 // echo '<pre>';print_r($query);
		return $query;

	}

	public function farmasi_mod_13(){

		$month = $_POST['from_month'];
		$to_month = $_POST['to_month'];
		$year  = $_POST['year'];
		
		$query = 'SELECT bulan, kode_brg, nama_brg, CAST(SUM(jml_pesan) as INT) as hutang, CAST(SUM(jml_mutasi) AS INT) as mutasi, CAST((SUM(jml_pesan) - SUM(jml_mutasi)) as INT) as sisa_hutang, AVG(rata_harga_jual) as rata_harga_jual
		FROM (
		SELECT
			fr_tc_far_detail_log.kode_brg,
			nama_brg,
			(CAST(SUM ( jumlah_pesan ) as int) + CAST(SUM ( jumlah_obat_23 ) as int)) AS jml_pesan,
			SUM ( jumlah_mutasi_obat ) AS jml_mutasi, 
			CAST(avg (harga_jual_satuan) as INT) as rata_harga_jual, MONTH(fr_tc_far.tgl_trans) as bulan
		FROM
			fr_tc_far_detail_log
			LEFT JOIN fr_tc_far ON fr_tc_far.kode_trans_far = fr_tc_far_detail_log.kode_trans_far
			LEFT JOIN fr_tc_log_mutasi_obat ON ( fr_tc_log_mutasi_obat.kode_trans_far = fr_tc_far_detail_log.kode_trans_far AND fr_tc_log_mutasi_obat.kode_brg = fr_tc_far_detail_log.kode_brg ) 
		WHERE
			(resep_ditangguhkan = 1 or prb_ditangguhkan = 1)
			AND YEAR ( fr_tc_far.tgl_trans ) = '.$year.' 
			AND MONTH ( fr_tc_far.tgl_trans ) BETWEEN '.$month.' AND '.$to_month.'
		GROUP BY
			fr_tc_far_detail_log.kode_brg,
			nama_brg, MONTH(fr_tc_far.tgl_trans) 
		HAVING
			SUM ( jumlah_pesan ) > 0 
		
		) as tbl_mutasi_x
			GROUP BY kode_brg, nama_brg, bulan
			ORDER BY nama_brg ASC';
		//  echo '<pre>';print_r($query);exit;
		return $query;

	}

	public function so_mod_1(){

		$t_kartu_stok = ($_POST['bagian'] == 'non_medis') ? 'tc_kartu_stok_nm' : 'tc_kartu_stok' ;
		$tgl_stok = isset($_POST['tgl_stok'])?$_POST['tgl_stok']:date('Y-m-d');
		// Gudang Non Medis
		if($_POST['bagian']=='070101'){

			$this->db->select('mt_depo_stok_nm_v.kode_brg, mt_depo_stok_nm_v.nama_brg, mt_depo_stok_nm_v.satuan_besar, mt_depo_stok_nm_v.satuan_kecil, mt_depo_stok_nm_v.nama_golongan, mt_depo_stok_nm_v.nama_sub_golongan, mt_bagian.nama_bagian, mt_depo_stok_nm_v.is_active, kartu_stok.stok_akhir');
			$this->db->from('mt_depo_stok_nm_v');
			$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=mt_depo_stok_nm_v.kode_bagian','left');
			$this->db->join('( SELECT * FROM tc_kartu_stok_nm WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok_nm WHERE tgl_input <= '."'".$tgl_stok."'".' AND tgl_input is not null GROUP BY kode_brg) AND kode_bagian='."'".$_POST['bagian']."'".' ) AS kartu_stok', 'kartu_stok.kode_brg=mt_depo_stok_nm_v.kode_brg','left');

			$this->db->where('mt_depo_stok_nm_v.kode_bagian', $_POST['bagian']);
			$this->db->where('nama_brg LIKE '."'%'".'');

			if( isset($_POST['kode_golongan']) AND $_POST['kode_golongan'] != '' ){
				$this->db->where('kode_golongan', $_POST['kode_golongan']);
			}

			if( isset($_POST['kode_sub_gol']) AND $_POST['kode_sub_gol'] != '' ){
				$this->db->where('kode_sub_golongan', $_POST['kode_sub_gol']);
			}

			
			// $this->db->where('is_active', 1);
			$this->db->group_by( 'mt_depo_stok_nm_v.kode_brg, mt_depo_stok_nm_v.nama_brg, mt_depo_stok_nm_v.satuan_besar, mt_depo_stok_nm_v.satuan_kecil, mt_depo_stok_nm_v.nama_golongan, mt_depo_stok_nm_v.nama_sub_golongan, mt_bagian.nama_bagian, mt_depo_stok_nm_v.is_active, kartu_stok.stok_akhir' );
			$this->db->order_by( 'nama_brg','ASC' );
			$this->db->order_by( 'nama_sub_golongan','ASC' );
			$this->db->order_by( 'nama_golongan','ASC' );
			// print_r($this->db->last_query());die;
			$query = $this->db->get();

		// Gudang Medis
		}else{
			
			$qry = 'SELECT kode_brg, nama_brg, satuan_besar, satuan_kecil, jenis_golongan, nama_golongan, nama_kategori, nama_bagian, ';
			$qry .= 'CASE 
							WHEN jenis_golongan = nama_golongan THEN jenis_golongan
						ELSE jenis_golongan +' ."'/'". '+nama_golongan 
						END AS jenis_golongan_concat, stok_akhir, is_active, rak, tgl_input ';
			$qry .= 'FROM view_depo_stok_so ';
			// $qry .= 'WHERE kode_bagian = '."'".$_POST['bagian']."'".' AND CAST("tgl_input" as DATE) <= '."'".$tgl_stok."'".' AND "tgl_input" is not null ';

			$qry .= 'WHERE kode_bagian = '."'".$_POST['bagian']."'".' ';
			

			if( isset($_POST['kode_kategori']) AND $_POST['kode_kategori'] != '' ){
				$qry .= 'AND kode_kategori = '."'".$_POST['kode_kategori']."'".' ';
			}

			if( isset($_POST['kode_layanan']) AND $_POST['kode_layanan'] != '' ){
				$qry .= 'AND kode_layanan = '."'".$_POST['kode_layanan']."'".' ';
			}

			if( isset($_POST['jenis_obat']) AND $_POST['jenis_obat'] != '' ){
				$qry .= 'AND jenis_obat = '."'".$_POST['jenis_obat']."'".' ';
			}

			if( isset($_POST['rak']) AND $_POST['rak'] != '' ){
				$qry .= 'AND rak = '."'".$_POST['rak']."'".' ';
			}

			$qry .= 'GROUP BY kode_brg, nama_brg, satuan_besar, satuan_kecil, jenis_golongan, nama_golongan, nama_kategori, nama_bagian, jenis_golongan, stok_akhir, is_active, rak, tgl_input ';
			$qry .= 'ORDER BY is_active DESC, nama_brg ASC';
			$query = $this->db->query($qry);
			//  print_r($this->db->last_query());die;
		}

		return $this->db->last_query();
	}

	public function so_mod_2(){
		if($_POST['bagian']=='070101'){
		$query = " select b.kode_bagian, b.nama_bagian, d.nama_golongan, a.kode_brg, c.nama_brg, a.stok_sebelum, a.stok_sekarang, a.tgl_stok_opname, a.nama_petugas, a.harga_pembelian_terakhir, a.set_status_aktif, c.satuan_besar,
			c.satuan_kecil, c.content from tc_stok_opname_nm a
			left join mt_bagian b ON a.kode_bagian=b.kode_bagian
			left join mt_barang_nm c ON c.kode_brg=a.kode_brg
			left join mt_golongan_nm d ON d.kode_golongan=c.kode_golongan
			WHERE a.agenda_so_id=".$_POST['agenda_so']." AND a.kode_bagian=".$_POST['bagian']." 
			order by d.nama_golongan, c.nama_brg ASC";
		}
		else{
			$query = " select a.id_tc_stok_opname
		      ,a.tgl_stok_opname
		      ,a.kode_bagian
			  ,b.nama_bagian
		      ,a.kode_brg
		      ,c.nama_brg
		      ,a.stok_sebelum
		      ,a.stok_sekarang
		      ,a.agenda_so_id
		      ,a.set_status_aktif
			  ,a.harga_pembelian_terakhir
		      ,a.nama_petugas
			  ,d.nama_golongan
			  ,c.satuan_besar
			  ,c.satuan_kecil
			  ,c.content
			  FROM [tc_stok_opname] a 
		  			left join [mt_bagian] b on a.kode_bagian=b.kode_bagian 
					left join mt_barang c ON c.kode_brg=a.kode_brg
					left join mt_golongan d ON d.kode_golongan=c.kode_golongan
					WHERE a.agenda_so_id=".$_POST['agenda_so']." AND a.kode_bagian=".$_POST['bagian']."

					order by d.nama_golongan, c.nama_brg ASC";
				}
			return $query;
		
	}
	public function so_mod_3(){
		if($_POST['bagian']=='070101'){
			if($_POST['status']==''){
				$query = " select b.kode_bagian, b.nama_bagian, d.nama_golongan, a.kode_brg, c.nama_brg, a.stok_sebelum, a.stok_sekarang, a.tgl_stok_opname, a.nama_petugas, a.harga_pembelian_terakhir, a.set_status_aktif, c.satuan_besar,
					c.satuan_kecil, c.content from tc_stok_opname_nm a
					left join mt_bagian b ON a.kode_bagian=b.kode_bagian
					left join mt_barang_nm c ON c.kode_brg=a.kode_brg
					left join mt_golongan_nm d ON d.kode_golongan=c.kode_golongan
					WHERE a.agenda_so_id=".$_POST['agenda_so']." AND a.kode_bagian=".$_POST['bagian']." 
					order by d.nama_golongan, c.nama_brg ASC";
				}
				else{
					$query = " select b.kode_bagian, b.nama_bagian, d.nama_golongan, a.kode_brg, c.nama_brg, a.stok_sebelum, a.stok_sekarang, a.tgl_stok_opname, a.nama_petugas, a.harga_pembelian_terakhir, a.set_status_aktif, c.satuan_besar,
					c.satuan_kecil, c.content from tc_stok_opname_nm a
					left join mt_bagian b ON a.kode_bagian=b.kode_bagian
					left join mt_barang_nm c ON c.kode_brg=a.kode_brg
					left join mt_golongan_nm d ON d.kode_golongan=c.kode_golongan
					WHERE a.agenda_so_id=".$_POST['agenda_so']." AND a.kode_bagian=".$_POST['bagian']." 
					and a.set_status_aktif=".$_POST['status']." 
					order by d.nama_golongan, c.nama_brg ASC";
				}
		}
		else{
			if($_POST['status']==''){
				$query = " select a.id_tc_stok_opname
				      ,a.tgl_stok_opname
				      ,a.kode_bagian
					  ,b.nama_bagian
				      ,a.kode_brg
				      ,c.nama_brg
				      ,a.stok_sebelum
				      ,a.stok_sekarang
				      ,a.agenda_so_id
				      ,a.set_status_aktif
					  ,a.harga_pembelian_terakhir
				      ,a.nama_petugas
					  ,d.nama_golongan
					  ,c.satuan_besar
					  ,c.satuan_kecil
					  ,c.content
					  FROM [tc_stok_opname] a 
				  			left join [mt_bagian] b on a.kode_bagian=b.kode_bagian 
							left join mt_barang c ON c.kode_brg=a.kode_brg
							left join mt_golongan d ON d.kode_golongan=c.kode_golongan
							WHERE a.agenda_so_id=".$_POST['agenda_so']." AND a.kode_bagian=".$_POST['bagian']." 
							order by d.nama_golongan, c.nama_brg ASC";
						}
			else{
				$query = " select a.id_tc_stok_opname
				      ,a.tgl_stok_opname
				      ,a.kode_bagian
					  ,b.nama_bagian
				      ,a.kode_brg
				      ,c.nama_brg
				      ,a.stok_sebelum
				      ,a.stok_sekarang
				      ,a.agenda_so_id
				      ,a.set_status_aktif
					  ,a.harga_pembelian_terakhir
				      ,a.nama_petugas
					  ,d.nama_golongan
					  ,c.satuan_besar
					  ,c.satuan_kecil
					  ,c.content
					  FROM [tc_stok_opname] a 
				  			left join [mt_bagian] b on a.kode_bagian=b.kode_bagian 
							left join mt_barang c ON c.kode_brg=a.kode_brg
							left join mt_golongan d ON d.kode_golongan=c.kode_golongan
							WHERE a.agenda_so_id=".$_POST['agenda_so']." AND a.kode_bagian=".$_POST['bagian']." 
							and a.set_status_aktif=".$_POST['status']." 
							order by d.nama_golongan, c.nama_brg ASC";	
				}
		}
			return $query;
		
	}
	public function lainnyabillingdokter_mod_1(){

		$query = "select kode_tc_trans_kasir, no_kunjungan, tc_trans_pelayanan.no_registrasi, b.no_sep, tc_trans_pelayanan.no_mr, nama_pasien_layan, nama_tindakan,
			convert(varchar,tgl_transaksi,23) as tgl_transaksi, CAST(bill_rs as int) as bill_rs , CAST(bill_dr1 as int) as bill_dr1, CAST(bill_dr2 as int) as bill_dr2,
			CAST( (SUM( bill_rs + bill_dr1+bill_dr2)) as int)  as total, tc_trans_pelayanan.log_id
			from tc_trans_pelayanan
			left join tc_registrasi b on b.no_registrasi=tc_trans_pelayanan.no_registrasi
			where no_kunjungan in (
				select no_kunjungan from pm_tc_penunjang where no_kunjungan in (
				select no_kunjungan 
				from tc_kunjungan 
				where kode_dokter= ".$_POST['dokter']." and YEAR(tgl_masuk) = ".$_POST['year']." and MONTH(tgl_masuk)  between ".$_POST['from_month']." and ".$_POST['to_month']."
				and tgl_keluar is not null and status_masuk=1
			)
			) and kode_tc_trans_kasir IS NULL and bill_rs > 0
			group by kode_tc_trans_kasir, tc_trans_pelayanan.no_registrasi, tc_trans_pelayanan.no_mr, 
			nama_pasien_layan, tgl_transaksi, no_kunjungan, nama_tindakan, bill_rs , 
			bill_dr1, bill_dr2, b.no_sep, tc_trans_pelayanan.log_id
			order by tgl_transaksi DESC";

		return $query;
	}

	public function totalpengadaan_mod_3(){

					$query = 'SELECT sum(hargabeli) as total
			      	  FROM [view_permintaan_inst_nm] 
			      	  where MONTH(tgl_pengiriman) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".' AND YEAR(tgl_pengiriman)='."'".$_POST['year']."'".' ';

		return $query;

	}

	public function rl_mod_1(){

		$query = 'SELECT * FROM [dd_konfigurasi]';

		return $query;

	}
	public function v_rl_mod_1(){

		$query = 'SELECT * FROM [dd_konfigurasi]';

		return $this->db->query($query)->result();

	}

	public function jumlah_spesialis(){
		$query = "select count(id_mt_karyawan) as total, mt_spesialisasi_dokter.kode_spesialisasi, mt_spesialisasi_dokter.nama_spesialisasi
			from mt_karyawan 
			LEFT JOIN mt_spesialisasi_dokter ON mt_karyawan.kode_spesialisasi=mt_spesialisasi_dokter.kode_spesialisasi 
			group by mt_spesialisasi_dokter.kode_spesialisasi, mt_spesialisasi_dokter.nama_spesialisasi";
		return $this->db->query($query)->result();
	}

	public function jumlah_spesialispwt(){
		$query = "select count(flag_tenaga_medis) as total
			from mt_karyawan  WHERE flag_tenaga_medis=1 and kode_dokter is null";
		return $this->db->query($query)->result();
	}
	public function jumlah_spesialisbdn(){
		$query = "select count(flag_tenaga_medis) as total
			from mt_karyawan  WHERE flag_tenaga_medis=2 and kode_dokter is null";
		return $this->db->query($query)->result();
	}
	public function jumlah_spesialisfrm(){
		$query = "select count(flag_tenaga_medis) as total
			from mt_karyawan  WHERE flag_tenaga_medis=3 and kode_dokter is null";
		return $this->db->query($query)->result();
	}
	public function jumlah_spesialistk(){
		$query = "select count(flag_tenaga_medis) as total
			from mt_karyawan  WHERE flag_tenaga_medis=4 and kode_dokter is null";
		return $this->db->query($query)->result();
	}
	public function jumlah_spesialisntk(){
		$query = "select count(flag_tenaga_medis) as total
			from mt_karyawan  WHERE flag_tenaga_medis=0 and kode_dokter is null";
		return $this->db->query($query)->result();
	}

	public function rl_mod_12_1(){
		$year_curr = $_POST['year'];
		$year_before = $year_curr - 2;
		for ($i=$year_before; $i <= $year_curr; $i++) { 
			$arr_year[] = $i;
		}

		$this->db->where_in('tahun', $arr_year);
		$this->db->order_by('tahun', 'ASC');
		$dt = $this->db->get('v_tc_bor')->result_array();
		foreach ($dt as $key => $value) {
			$getData[$value['tahun']][] = $value;
		}
		return $getData;

	}

	public function rl_mod_13(){
		$year_curr = $_POST['year'];
		$dt = $this->db->get('v_tc_bor')->result_array();
		$year_before = $year_curr - 2;
		for ($i=$year_before; $i <= $year_curr; $i++) { 
			$arr_year[] = $i;
		}

		$this->db->where_in('tahun', $arr_year);
		$this->db->order_by('tahun', 'ASC');
		$dt = $this->db->get('v_tc_bor')->result_array();
		foreach ($dt as $key => $value) {
			$getData[$value['tahun']][] = $value;
		}
		return $getData;

	}

	public function rl_mod_2(){
	$tahun1 =$_POST['year'];
	
	$query = 'select *	from tc_trans_rl2_v  WHERE tahun_lap='."'".$tahun1."'".' ';
	return $this->db->query($query)->result();
	}

	public function rl_mod_2_1(){
	
	$query = 'select *	from mt_rl2';
	return $this->db->query($query)->result();
	}

	// public function rl_mod_3_1(){
	// $tahun1 =$_POST['year'];
	
	// $query = 'select *	from v_ri_rekap_sensus_beta_msk WHERE thn='."'".$tahun1."'".' ';
	// return $this->db->query($query)->result();
	// }

	public function rl_mod_3_1(){
	$year_curr = $_POST['year'];
	$bln=1;
	$dt = $this->db->get('v_ri_rekap_sensus_beta_msk')->result_array();
	
	$this->db->where('thn', $year_curr);
	$this->db->where ('bln', $bln);
	// $this->db->where_in ('kode_bagian', $dt['kode_bagian']);
	$this->db->order_by('thn', 'ASC');
	$dt = $this->db->get('v_ri_rekap_sensus_beta_msk')->result_array();
	 // echo '<pre>';print_r($dt);
	foreach ($dt as $key => $value) {
		// $getData[$value['thn']][] = $value;
		$getData[$value['kode_bagian']][] = $value;
	}
	return $getData;
	}


	public function rl_mod_3_1_1(){
	// $tahun1 =$_POST['year'];
	$year_curr = $_POST['year'];
	$bln=1;
	$dt = $this->db->get('v_ri_rekap_sensus_beta_ttl')->result_array();
	
	$this->db->where_in('thn', $year_curr);
	$this->db->where ('bln', $bln);
	$this->db->order_by('thn', 'ASC');
	$dt = $this->db->get('v_ri_rekap_sensus_beta_ttl')->result_array();
	foreach ($dt as $key => $value) {
		$getData[$value['thn']][] = $value;
		$getData[$value['kode_bagian']][] = $value;
	}
	return $getData;

	// $query = 'select *	from v_ri_rekap_sensus_beta_ttl  WHERE thn='."'".$tahun1."'".' AND bln=1 ';
	// return $this->db->query($query)->result();
	}
	public function rl_mod_3_1_2(){
	$tahun1 =$_POST['year'];
	
	$query = 'select *	from v_ri_rekap_sensus_beta_ttl  WHERE thn='."'".$tahun1."'".' AND bln=12 ';
	return $this->db->query($query)->result();
	}


	public function lappembelian_1_mod_1(){
		$obat="D";
		$alkes="E";
		if($_POST['jenis']=='Obat'){
		$query = 'select b.kode_brg,c.nama_brg,c.satuan_kecil,c.content,c.satuan_besar,d.nama_pabrik, SUM(b.jumlah_kirim) as jml, sum((b.harga-((b.harga*disc)/100))*b.jumlah_kirim) as jumlah from tc_penerimaan_barang a inner join tc_penerimaan_barang_detail b on a.kode_penerimaan=b.kode_penerimaan inner join mt_barang c on b.kode_brg=c.kode_brg 
			left join mt_pabrik d on c.id_pabrik=d.id_pabrik 
			WHERE b.flag_prod_obat<>1 and YEAR(a.tgl_penerimaan)='."'".$_POST['year']."'".' AND MONTH(a.tgl_penerimaan)='."'".$_POST['to_month']."'".' and SUBSTRING(b.kode_brg,1,1)='."'".$obat."'".'
		    group by b.kode_brg,c.nama_brg,c.satuan_kecil,c.content,c.satuan_besar,d.nama_pabrik ORDER BY c.nama_brg';
		}
		elseif($_POST['jenis']=='Alkes'){
		$query = 'select b.kode_brg,c.nama_brg,c.satuan_kecil,c.content,c.satuan_besar,d.nama_pabrik,SUM(b.jumlah_kirim) as jml, sum((b.harga-((b.harga*disc)/100))*b.jumlah_kirim) as jumlah from tc_penerimaan_barang a inner join tc_penerimaan_barang_detail b on a.kode_penerimaan=b.kode_penerimaan inner join mt_barang c on b.kode_brg=c.kode_brg 
			left join mt_pabrik d on c.id_pabrik=d.id_pabrik WHERE b.flag_prod_obat<>1 and YEAR(a.tgl_penerimaan)='."'".$_POST['year']."'".' AND MONTH(a.tgl_penerimaan)='."'".$_POST['to_month']."'".' and SUBSTRING(b.kode_brg,1,1)='."'".$alkes."'".' group by b.kode_brg,c.nama_brg,c.satuan_kecil,c.content,c.satuan_besar,d.nama_pabrik ORDER BY c.nama_brg';
		}
		else{
		$query = 'select b.kode_brg,c.nama_brg,c.satuan_kecil,c.content,c.satuan_besar,SUM(b.jumlah_kirim) as jml, sum((b.harga-((b.harga*disc)/100))*b.jumlah_kirim) as jumlah from tc_penerimaan_barang a inner join tc_penerimaan_barang_detail b on a.kode_penerimaan=b.kode_penerimaan inner join mt_barang c on b.kode_brg=c.kode_brg 
			left join mt_pabrik d on c.id_pabrik=d.id_pabrik WHERE b.flag_prod_obat<>1 and YEAR(a.tgl_penerimaan)='."'".$_POST['year']."'".' AND MONTH(a.tgl_penerimaan)='."'".$_POST['to_month']."'".' group by b.kode_brg,c.nama_brg,c.satuan_kecil,c.content,c.satuan_besar,d.nama_pabrik ORDER BY c.nama_brg';
		}
		// return $query;
		return $this->db->query($query)->result();
	}

	public function lappembelian_1_mod_2(){
		$obat="D";
		$alkes="E";
		if($_POST['jenis']=='Obat'){
		$query = 'select b.kode_brg,c.nama_brg,c.satuan_kecil,c.content,c.satuan_besar,d.nama_pabrik, SUM(b.jumlah_kirim) as jml, sum((b.harga-((b.harga*disc)/100))*b.jumlah_kirim) as jumlah from tc_penerimaan_barang a inner join tc_penerimaan_barang_detail b on a.kode_penerimaan=b.kode_penerimaan inner join mt_barang c on b.kode_brg=c.kode_brg 
			left join mt_pabrik d on c.id_pabrik=d.id_pabrik 
			WHERE b.flag_prod_obat<>1 and YEAR(a.tgl_penerimaan)='."'".$_POST['year']."'".' and SUBSTRING(b.kode_brg,1,1)='."'".$obat."'".'
		    group by b.kode_brg,c.nama_brg,c.satuan_kecil,c.content,c.satuan_besar,d.nama_pabrik ORDER BY c.nama_brg';
		}
		elseif($_POST['jenis']=='Alkes'){
		$query = 'select b.kode_brg,c.nama_brg,c.satuan_kecil,c.content,c.satuan_besar,d.nama_pabrik,SUM(b.jumlah_kirim) as jml, sum((b.harga-((b.harga*disc)/100))*b.jumlah_kirim) as jumlah from tc_penerimaan_barang a inner join tc_penerimaan_barang_detail b on a.kode_penerimaan=b.kode_penerimaan inner join mt_barang c on b.kode_brg=c.kode_brg 
			left join mt_pabrik d on c.id_pabrik=d.id_pabrik WHERE b.flag_prod_obat<>1 and YEAR(a.tgl_penerimaan)='."'".$_POST['year']."'".' and SUBSTRING(b.kode_brg,1,1)='."'".$alkes."'".' group by b.kode_brg,c.nama_brg,c.satuan_kecil,c.content,c.satuan_besar,d.nama_pabrik ORDER BY c.nama_brg';
		}
		else{
		$query = 'select b.kode_brg,c.nama_brg,c.satuan_kecil,c.content,c.satuan_besar,SUM(b.jumlah_kirim) as jml, sum((b.harga-((b.harga*disc)/100))*b.jumlah_kirim) as jumlah from tc_penerimaan_barang a inner join tc_penerimaan_barang_detail b on a.kode_penerimaan=b.kode_penerimaan inner join mt_barang c on b.kode_brg=c.kode_brg 
			left join mt_pabrik d on c.id_pabrik=d.id_pabrik WHERE b.flag_prod_obat<>1 and YEAR(a.tgl_penerimaan)='."'".$_POST['year']."'".' group by b.kode_brg,c.nama_brg,c.satuan_kecil,c.content,c.satuan_besar,d.nama_pabrik ORDER BY c.nama_brg';
		}
		// return $query;
		return $this->db->query($query)->result();
	}

	public function vsql_ugd(){
		if($_POST['penunjang']=='Lab'){
		$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where (b.kode_bagian_asal=012601 or b.kode_bagian_asal like '020%') and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and MONTH(b.tgl_masuk)=".$_POST['from_month']." and b.kode_bagian_tujuan = '050101' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
			}
			else if($_POST['penunjang']=='Rad'){
			$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where (b.kode_bagian_asal=012601 or b.kode_bagian_asal like '020%') and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and MONTH(b.tgl_masuk)=".$_POST['from_month']." and b.kode_bagian_tujuan = '050201' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
			}
			else{
				$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where (b.kode_bagian_asal=012601 or b.kode_bagian_asal like '020%') and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and MONTH(b.tgl_masuk)=".$_POST['from_month']." and b.kode_bagian_tujuan = '050301' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
			
			}
		
		return $this->db->query($query)->result();
	}
	public function vsql_ugdthn(){
		if($_POST['penunjang']=='Lab'){
		$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where (b.kode_bagian_asal=012601 or b.kode_bagian_asal like '020%') and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and  b.kode_bagian_tujuan = '050101' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
			}
			else if($_POST['penunjang']=='Rad'){
			$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where (b.kode_bagian_asal=012601 or b.kode_bagian_asal like '020%') and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and  b.kode_bagian_tujuan = '050201' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
			}
			else{
				$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where (b.kode_bagian_asal=012601 or b.kode_bagian_asal like '020%') and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and  b.kode_bagian_tujuan = '050301' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
			
			}
		
		return $this->db->query($query)->result();
	}

	public function vsql_spesialis(){
		if($_POST['penunjang']=='Lab'){
		$query = "select COUNT(b.id_tc_kunjungan) as total from mt_bagian a,tc_kunjungan b,
						tc_registrasi c  
				where a.nama_bagian like '%spesialis%' and a.kode_bagian = b.kode_bagian_asal and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and MONTH(b.tgl_masuk)=".$_POST['from_month']." and b.kode_bagian_tujuan = '050101' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
			}
		elseif($_POST['penunjang']=='Rad'){
		$query = "select COUNT(b.id_tc_kunjungan) as total from mt_bagian a,tc_kunjungan b,
						tc_registrasi c  
				where a.nama_bagian like '%spesialis%' and a.kode_bagian = b.kode_bagian_asal and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and MONTH(b.tgl_masuk)=".$_POST['from_month']." and b.kode_bagian_tujuan = '050201' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
			}
		else{
			$query = "select COUNT(b.id_tc_kunjungan) as total from mt_bagian a,tc_kunjungan b,
						tc_registrasi c  
				where a.nama_bagian like '%spesialis%' and a.kode_bagian = b.kode_bagian_asal and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and MONTH(b.tgl_masuk)=".$_POST['from_month']." and b.kode_bagian_tujuan = '050301' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
			}	
		return $this->db->query($query)->result();
	}
	public function vsql_spesialisthn(){
		if($_POST['penunjang']=='Lab'){
		$query = "select COUNT(b.id_tc_kunjungan) as total from mt_bagian a,tc_kunjungan b,
						tc_registrasi c  
				where a.nama_bagian like '%spesialis%' and a.kode_bagian = b.kode_bagian_asal and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and b.kode_bagian_tujuan = '050101' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
			}
		elseif($_POST['penunjang']=='Rad'){
		$query = "select COUNT(b.id_tc_kunjungan) as total from mt_bagian a,tc_kunjungan b,
						tc_registrasi c  
				where a.nama_bagian like '%spesialis%' and a.kode_bagian = b.kode_bagian_asal and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and b.kode_bagian_tujuan = '050201' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
			}
		else{
			$query = "select COUNT(b.id_tc_kunjungan) as total from mt_bagian a,tc_kunjungan b,
						tc_registrasi c  
				where a.nama_bagian like '%spesialis%' and a.kode_bagian = b.kode_bagian_asal and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and b.kode_bagian_tujuan = '050301' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
			}	
		return $this->db->query($query)->result();
	}

	public function vsql_luar(){
		if($_POST['penunjang']=='Lab'){

		$query = "select count(*) as total, no_kunjungan, b.kode_bagian_asal, b.kode_bagian_tujuan 
					from tc_registrasi a, tc_kunjungan b where a.no_registrasi = b.no_registrasi and YEAR(b.tgl_masuk)=".$_POST['year']." and MONTH(b.tgl_masuk) = ".$_POST['from_month']." 
					and (b.kode_bagian_asal = b.kode_bagian_tujuan and b.kode_bagian_tujuan = '050101')
					group by b.no_registrasi, no_kunjungan, b.kode_bagian_asal, b.kode_bagian_tujuan having count(*) = 1";

		}
		else if($_POST['penunjang']=='Rad'){
		$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where YEAR(b.tgl_masuk)=".$_POST['year']." 
				and MONTH(b.tgl_masuk)=".$_POST['from_month']." and b.kode_bagian_tujuan = '050201' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'baru'";
		}
		else{
			$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where YEAR(b.tgl_masuk)=".$_POST['year']." 
				and MONTH(b.tgl_masuk)=".$_POST['from_month']." and b.kode_bagian_tujuan = '050301' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'baru'";
		}
		return $this->db->query($query)->result();
	}

	public function vsql_luarthn(){
		if($_POST['penunjang']=='Lab'){
		$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where YEAR(b.tgl_masuk)=".$_POST['year']." 
				and b.kode_bagian_tujuan = '050101' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'baru'";
		}
		else if($_POST['penunjang']=='Rad'){
		$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where YEAR(b.tgl_masuk)=".$_POST['year']." 
				and b.kode_bagian_tujuan = '050201' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'baru'";
		}
		else{
			$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where YEAR(b.tgl_masuk)=".$_POST['year']." 
				and b.kode_bagian_tujuan = '050301' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'baru'";
		}
		return $this->db->query($query)->result();
	}


	public function vsql_inap(){
		if($_POST['penunjang']=='Lab'){
		$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where (b.kode_bagian_asal like '03%') and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and MONTH(b.tgl_masuk)=".$_POST['from_month']." and b.kode_bagian_tujuan = '050101' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
		}
		else if($_POST['penunjang']=='Rad'){
		$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where (b.kode_bagian_asal like '03%') and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and MONTH(b.tgl_masuk)=".$_POST['from_month']." and b.kode_bagian_tujuan = '050201' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
		}
		else{
			$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where (b.kode_bagian_asal like '03%') and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and MONTH(b.tgl_masuk)=".$_POST['from_month']." and b.kode_bagian_tujuan = '050301' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
		
		}
		return $this->db->query($query)->result();
	}

	public function vsql_inapthn(){
		if($_POST['penunjang']=='Lab'){
		$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where (b.kode_bagian_asal like '03%') and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and b.kode_bagian_tujuan = '050101' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
		}
		else if($_POST['penunjang']=='Rad'){
		$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where (b.kode_bagian_asal like '03%') and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and b.kode_bagian_tujuan = '050201' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
		}
		else{
			$query = "select COUNT(b.id_tc_kunjungan) as total from tc_kunjungan b,
						tc_registrasi c  
				where (b.kode_bagian_asal like '03%') and YEAR(b.tgl_masuk)=".$_POST['year']." 
				and b.kode_bagian_tujuan = '050301' and c.no_registrasi = b.no_registrasi 
				and c.stat_pasien = 'lama'";
		
		}
		return $this->db->query($query)->result();
	}

	public function lapkinerja(){
		if($_POST['penunjang']=='Lab'){
		
			$query = "select nama_tindakan,count(kode_tarif) as jumlah ,sum(bill_rs+bill_dr1) as biaya from pm_sie_kinerjatindakan_v where status_selesai>=2 and kode_bagian = '050101' and month(tgl_transaksi)=".$_POST['from_month']." and year(tgl_transaksi)=".$_POST['year']." GROUP BY nama_tindakan,kode_tarif";
		}
		else if($_POST['penunjang']=='Rad'){
		$query = "select nama_tindakan,count(kode_tarif) as jumlah ,sum(bill_rs+bill_dr1) as biaya from pm_sie_kinerjatindakan_v where status_selesai>=2 and kode_bagian = '050201' and month(tgl_transaksi)=".$_POST['from_month']." and year(tgl_transaksi)=".$_POST['year']." GROUP BY nama_tindakan,kode_tarif";
		}
		else{
			$query = "select nama_tindakan,count(kode_tarif) as jumlah ,sum(bill_rs+bill_dr1) as biaya from pm_sie_kinerjatindakan_v where status_selesai>=2 and kode_bagian = '050301' and month(tgl_transaksi)=".$_POST['from_month']." and year(tgl_transaksi)=".$_POST['year']." GROUP BY nama_tindakan,kode_tarif";
		
		}
		return $this->db->query($query)->result();
	}

	public function lapkinerjathn(){
		if($_POST['penunjang']=='Lab'){
		
			$query = "select nama_tindakan,count(kode_tarif) as jumlah ,sum(bill_rs+bill_dr1) as biaya from pm_sie_kinerjatindakan_v where status_selesai>=2 and kode_bagian = '050101' and year(tgl_transaksi)=".$_POST['year']." GROUP BY nama_tindakan,kode_tarif";
		}
		else if($_POST['penunjang']=='Rad'){
		$query = "select nama_tindakan,count(kode_tarif) as jumlah ,sum(bill_rs+bill_dr1) as biaya from pm_sie_kinerjatindakan_v where status_selesai>=2 and kode_bagian = '050201' and year(tgl_transaksi)=".$_POST['year']." GROUP BY nama_tindakan,kode_tarif";
		}
		else{
			$query = "select nama_tindakan,count(kode_tarif) as jumlah ,sum(bill_rs+bill_dr1) as biaya from pm_sie_kinerjatindakan_v where status_selesai>=2 and kode_bagian = '050301' and year(tgl_transaksi)=".$_POST['year']." GROUP BY nama_tindakan,kode_tarif";
		
		}
		return $this->db->query($query)->result();
	}


	//farmasi per-bulan
	public function get_saldo_bagian(){
		$year=$_POST['year'] - 1;
		$query = 'select kode_brg, kode_bagian, tgl_input, stok_awal, stok_akhir, pemasukan, pengeluaran, kode_bagian, keterangan, petugas, id_kartu, kode_brg
		from tc_kartu_stok where id_kartu IN 
					(SELECT MAX(id_kartu) AS id_kartu from tc_kartu_stok where kode_bagian='."'".$_POST['bagian']."'".' AND YEAR(tgl_input) = '."'".$year."'".' group by kode_brg,kode_bagian)';
			
		return $this->db->query($query)->result_array();
	}
	public function get_saldo_awal_bagian(){
		$month = $_POST['from_month'] - 1;

		$query = 'select tc_kartu_stok.kode_brg, tc_kartu_stok.kode_bagian, nama_brg, tgl_input, stok_awal, stok_akhir, pemasukan, pengeluaran, kode_bagian, keterangan, petugas, id_kartu
		from tc_kartu_stok left join mt_barang on mt_barang.kode_brg=tc_kartu_stok.kode_brg where id_kartu IN 
					(SELECT MAX(id_kartu) AS id_kartu from tc_kartu_stok where kode_bagian= '."'".$_POST['bagian']."'".' AND MONTH(tgl_input)= '."'".$month."'".' and YEAR(tgl_input) = '."'".$_POST['year']."'".' AND nama_brg is not null group by tc_kartu_stok.kode_brg,tc_kartu_stok.kode_bagian)';

		
		return $this->db->query($query)->result_array();
	}

	public function penjualan_obat_bpjs_bagian(){
		
		$query = 'select kode_brg, kode_bagian, kode_perusahaan, SUM(jumlah_tebus) as jumlah_tebus, AVG(harga_beli) as harga_beli, AVG(harga_jual) as harga_jual 
		from fr_hisbebasluar_v where kode_bagian='."'".$_POST['bagian']."'".' AND YEAR(tgl_trans) = '."'".$_POST['year']."'".' group by kode_brg, kode_bagian, kode_perusahaan';
			
		return $this->db->query($query)->result_array();
	}
	public function penjualan_obat_umum_bagian(){
		
		$query = 'select kode_brg, kode_bagian, kode_perusahaan, kode_kelompok, SUM(jumlah_tebus) as jumlah_tebus, AVG(harga_beli) as harga_beli, AVG(harga_jual) as harga_jual 
		from fr_hisbebasluar_v where kode_bagian='."'".$_POST['bagian']."'".' AND YEAR(tgl_trans) = '."'".$_POST['year']."'".' group by kode_brg, kode_bagian, kode_perusahaan, kode_kelompok';
			
		return $this->db->query($query)->result_array();
	}
	public function penjualan_obat_internal_bagian(){
		
		$query = 'select kode_brg, kode_bagian, kode_perusahaan, kode_kelompok, SUM(jumlah_tebus) as jumlah_tebus, AVG(harga_beli) as harga_beli, AVG(harga_jual) as harga_jual 
		from fr_hisbebasluar_v where kode_bagian='."'".$_POST['bagian']."'".' AND  YEAR(tgl_trans) = '."'".$_POST['year']."'".' AND kode_kelompok NOT IN(1,2,3,5,6) group by kode_brg, kode_bagian, kode_perusahaan, kode_kelompok';
			
		return $this->db->query($query)->result_array();
	}

	public function penerimaan_penjualan_bagian(){
		$kdbagian=$_POST['bagian'];
		if($kdbagian==060101){
		$query = "SELECT b.kode_brg, b.content, SUM(b.jumlah_kirim) as jumlah_kirim, AVG(b.harga) as harga
			FROM tc_penerimaan_barang as a 
			LEFT JOIN tc_penerimaan_barang_detail b ON a.kode_penerimaan=b.kode_penerimaan
			WHERE YEAR(a.tgl_penerimaan) = ".$_POST['year']." AND a.tgl_penerimaan is not null 
			GROUP BY b.kode_brg, b.content";
			
		return $this->db->query($query)->result_array();
		}
	else{

		}
	}
	
	public function distribusi_unit_bagian(){
		$query = "SELECT a.kode_brg, e.kode_bagian_minta, g.nama_bagian, SUM(jumlah_permintaan) as jumlah_permintaan, SUM(jumlah_penerimaan) as jumlah_penerimaan, f.harga_beli
				  FROM tc_permintaan_inst_det a
				  LEFT JOIN mt_barang c ON c.kode_brg=a.kode_brg 
				  LEFT JOIN tc_permintaan_inst e ON e.id_tc_permintaan_inst=a.id_tc_permintaan_inst 
				  LEFT JOIN mt_bagian g ON g.kode_bagian=e.kode_bagian_minta 
				  LEFT JOIN mt_rekap_stok f ON f.kode_brg=a.kode_brg 
				  WHERE e.kode_bagian_minta=".$_POST['bagian']." AND YEAR(e.tgl_permintaan) = ".$_POST['year']." AND e.tgl_permintaan is not null
				  GROUP BY a.kode_brg, e.kode_bagian_minta, g.nama_bagian, f.harga_beli";
			
		return $this->db->query($query)->result_array();
	}
}
