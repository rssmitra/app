<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Global_report_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_data()
	{
		$query 		= $this->$_POST['flag']();
		$execute 	= $this->db->query( $query );
		/*field data*/
		$result = array(
			'fields' 	=> $execute->field_data(),
			'data' 		=> $execute->result(),
		);
		//echo '<pre>'; print_r($this->db->last_query());die;
		/*return data*/
		return $result;

	}

	public function kunjungan_mod_2(){

		$query = 'SELECT a.tgl_jam_masuk, a.no_mr, a.no_registrasi, c.nama_pasien, e.nama_bagian, b.nama_pegawai, d.nama_perusahaan, a.no_sep 
					FROM [rls_rssm_sirs].[dbo].[tc_registrasi] a 
					left join [rls_rssm_sirs].[dbo].[mt_karyawan] b ON a.kode_dokter=b.kode_dokter 
					left join [rls_rssm_sirs].[dbo].[mt_master_pasien] c ON a.no_mr=c.no_mr 
					left join [rls_rssm_sirs].[dbo].[mt_perusahaan] d ON a.kode_perusahaan=d.kode_perusahaan 
					left join [rls_rssm_sirs].[dbo].[mt_bagian] e ON a.kode_bagian_masuk=e.kode_bagian 
					where a.tgl_jam_masuk >='."'".$_POST['tgl']."'".' 
					AND no_registrasi IN (SELECT no_registrasi  FROM [rls_rssm_sirs].[dbo].[tc_kunjungan] where tgl_masuk >='."'".$_POST['tgl']."'".' group by no_registrasi) 
					and a.status_batal is NULL
					order by b.nama_pegawai,a.no_mr ASC';

		return $query;

	}
	public function kunjungan_mod_3(){

		$query = 'SELECT a.no_registrasi,a.no_mr,c.nama_pasien,c.no_hp,d.nama_perusahaan,
					a.tgl_jam_masuk,e.nama_bagian, b.nama_pegawai, a.no_sep 
					FROM [rls_rssm_sirs].[dbo].[tc_registrasi] a left join [rls_rssm_sirs].[dbo].[mt_karyawan] b ON a.kode_dokter=b.kode_dokter 
					left join [rls_rssm_sirs].[dbo].[mt_master_pasien] c ON a.no_mr=c.no_mr 
					left join [rls_rssm_sirs].[dbo].[mt_perusahaan] d ON a.kode_perusahaan=d.kode_perusahaan 
					left join [rls_rssm_sirs].[dbo].[mt_bagian] e ON a.kode_bagian_masuk=e.kode_bagian  
					where convert(varchar,a.tgl_jam_masuk,23) between '."'".$_POST['from_tgl']."'".' and '."'".$_POST['to_tgl']."'".'
					order by a.no_registrasi DESC';

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
					(SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok_nm GROUP BY kode_brg) 
					AND tgl_input <= '."'".$_POST['tgl']."'".'
					ORDER BY nama_kategori, nama_golongan, nama_sub_golongan ASC';

		return $query;

	}
	public function pengadaan_mod_2(){
			// $t_stok = ( $_POST['status'] == '1' ) ? 'tc_kartu_stok' : 'tc_kartu_stok_nm' ;
			if($_POST['status'] == '1' ){
			$query = 'SELECT a.kode_bagian, d.nama_bagian, e.nama_golongan, a.kode_brg, c.nama_brg, SUM(a.pengeluaran) AS jml_pengeluaran, c.harga_beli FROM [rls_rssm_sirs].[dbo].[tc_kartu_stok] a 
					 left join mt_barang c ON c.kode_brg=a.kode_brg
  					  left join mt_bagian d ON d.kode_bagian=a.kode_bagian
  					  left join mt_golongan e ON e.kode_golongan=c.kode_golongan
					  where a.kode_bagian='."'".$_POST['bagian']."'".' AND 
					  MONTH(a.tgl_input) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".' AND YEAR(a.tgl_input)='."'".$_POST['year']."'".' 
					  		GROUP BY a.kode_bagian, d.nama_bagian, e.nama_golongan, a.kode_brg, c.nama_brg, c.harga_beli';
				}
			else{
			$query = 'SELECT a.kode_bagian, d.nama_bagian, e.nama_golongan, a.kode_brg, c.nama_brg, SUM(a.pengeluaran) AS jml_pengeluaran, c.harga_beli FROM [rls_rssm_sirs].[dbo].[tc_kartu_stok_nm] a 
					  left join mt_barang_nm c ON c.kode_brg=a.kode_brg
  					  left join mt_bagian d ON d.kode_bagian=a.kode_bagian
  					  left join mt_golongan_nm e ON e.kode_golongan=c.kode_golongan
					  where a.kode_bagian='."'".$_POST['bagian']."'".' AND 
					  MONTH(a.tgl_input) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".' AND YEAR(a.tgl_input)='."'".$_POST['year']."'".' 
								GROUP BY a.kode_bagian, d.nama_bagian, e.nama_golongan, a.kode_brg, c.nama_brg, c.harga_beli';	
			}
					// $query = 'SELECT a.nomor_permintaan, a.tgl_permintaan, a.tgl_pengiriman, a.kode_bagian_minta, d.nama_bagian, e.nama_golongan, b.kode_brg, c.nama_brg, b.jumlah_permintaan, b.jumlah_penerimaan, c.harga_beli
			  //     	  FROM [rls_rssm_sirs].[dbo].[tc_permintaan_inst_nm] a
					//   left join tc_permintaan_inst_nm_det b ON a.id_tc_permintaan_inst=b.id_tc_permintaan_inst
					//   left join mt_barang_nm c ON c.kode_brg=b.kode_brg
  			// 		  left join mt_bagian d ON d.kode_bagian=a.kode_bagian_minta
  			// 		  left join mt_golongan_nm e ON e.kode_golongan=c.kode_golongan
					//   where a.kode_bagian_minta='."'".$_POST['bagian']."'".' AND 
					//   MONTH(a.tgl_pengiriman) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".' AND YEAR(a.tgl_pengiriman)='."'".$_POST['year']."'".' 
					//   AND a.status_selesai in (4,5)
					// 			ORDER BY c.kode_golongan, c.nama_brg ASC';

		return $query;

	}
	public function pengadaan_mod_3(){
			if($_POST['status']=='1'){
					$query = 'SELECT nama_bagian, sum(hargabeli) as hargabeli
			      	  FROM [rls_rssm_sirs].[dbo].[view_rekap_stok] 
			      	  where MONTH(tgl_input) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".' AND YEAR(tgl_input)='."'".$_POST['year']."'".' 
					GROUP BY nama_bagian';
				}
				else{
					$query = 'SELECT nama_bagian, sum(hargabeli) as hargabeli
			      	  FROM [rls_rssm_sirs].[dbo].[view_rekap_stok_nm] 
			      	  where MONTH(tgl_input) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".' AND YEAR(tgl_input)='."'".$_POST['year']."'".' 
					GROUP BY nama_bagian';
				}

		return $query;

	}
	public function pengadaan_mod_4(){
		if($_POST['keterangan']=='medis'){
			if($_POST['status']=='0'){
					$query = 'SELECT a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan, COUNT(id_tc_permohonan_det) jml_brg, a.status_batal, a.tgl_acc, a.no_acc FROM tc_permohonan a INNER JOIN tc_permohonan_det b ON b.id_tc_permohonan=a.id_tc_permohonan WHERE a.status_kirim=1 AND a.status_batal=0 AND YEAR(a.tgl_permohonan)='."'".$_POST['year']."'".' AND MONTH(a.tgl_permohonan)='."'".$_POST['from_month']."'".' GROUP BY a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan, a.status_batal, a.tgl_acc, a.no_acc ORDER BY a.tgl_permohonan DESC';
				}
				else{
					$query = 'SELECT a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan, COUNT(id_tc_permohonan_det) jml_brg, a.status_batal, a.tgl_acc, a.no_acc FROM tc_permohonan a INNER JOIN tc_permohonan_det b ON b.id_tc_permohonan=a.id_tc_permohonan WHERE a.status_kirim=1 AND a.status_batal=1 AND YEAR(a.tgl_permohonan)='."'".$_POST['year']."'".' AND MONTH(a.tgl_permohonan)='."'".$_POST['from_month']."'".' GROUP BY a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan, a.status_batal, a.tgl_acc, a.no_acc ORDER BY a.tgl_permohonan DESC';
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
		if($_POST['keterangan']=='medis'){
			
					$query = 'SELECT a.no_po, a.tgl_po, b.kode_brg, b.jumlah_besar, b.harga_satuan_netto, b.jumlah_harga_netto, c.nama_brg, d.namasupplier FROM tc_po a JOIN tc_po_det b ON b.id_tc_po=a.id_tc_po JOIN mt_barang c ON c.kode_brg=b.kode_brg JOIN mt_supplier d ON d.kodesupplier=a.kodesupplier WHERE YEAR(a.tgl_po)='."'".$_POST['year']."'".' AND MONTH(a.tgl_po)='."'".$_POST['from_month']."'".' AND (b.status_batal<>1 OR b.status_batal IS NULL) ORDER BY a.tgl_po, d.namasupplier, c.nama_brg';
				
		}
		else{
			
					$query = 'SELECT a.no_po, a.tgl_po, b.kode_brg, b.jumlah_besar, b.harga_satuan_netto, b.jumlah_harga_netto, c.nama_brg, d.namasupplier FROM tc_po_nm a JOIN tc_po_nm_det b ON b.id_tc_po=a.id_tc_po JOIN mt_barang_nm c ON c.kode_brg=b.kode_brg JOIN mt_supplier d ON d.kodesupplier=a.kodesupplier WHERE YEAR(a.tgl_po)='."'".$_POST['year']."'".' AND MONTH(a.tgl_po)='."'".$_POST['from_month']."'".' AND (b.status_batal<>1 OR b.status_batal IS NULL) ORDER BY a.tgl_po, d.namasupplier, c.nama_brg';
				
		}
			
		return $query;

	}

	public function pengadaan_mod_8(){

		$query = 'SELECT tc_kartu_stok_nm_vv.kode_brg, tc_kartu_stok_nm_vv.nama_brg, tc_kartu_stok_nm_vv.harga_beli, tc_kartu_stok_nm_vv.harga_update, 
							satuan_besar, content, stok_akhir, satuan_kecil, 
							nama_golongan, nama_kategori , nama_sub_golongan, tgl_input 
					FROM tc_kartu_stok_nm_vv 
					
					WHERE id_kartu IN 
					(SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok_nm GROUP BY kode_brg) 
					AND tgl_input <= '."'".$_POST['tgl']."'".'
					ORDER BY nama_kategori, nama_golongan, nama_sub_golongan ASC';

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

		if($_POST['frekuensi']=='harian'){
			$query = 'SELECT DISTINCT no_lpb,no_po FROM fr_gg_penerimaan_brg WHERE tgl_penerimaan='."'".$_POST['tgl']."'".'';
		}
		elseif($_POST['frekuensi']=='bulanan'){
			$query = 'SELECT DISTINCT day(tgl_penerimaan)as tgl, month(tgl_penerimaan)as bln, year(tgl_penerimaan)as thn,no_lpb,no_po FROM fr_gg_penerimaan_brg WHERE MONTH(tgl_penerimaan)='."'".$_POST['from_month']."'".' AND YEAR(tgl_penerimaan)='."'".$_POST['year']."'".'';
		}
		else{
			$query = 'SELECT DISTINCT day(tgl_penerimaan)as tgl, month(tgl_penerimaan)as bln, year(tgl_penerimaan)as thn,no_lpb,no_po FROM fr_gg_penerimaan_brg WHERE YEAR(tgl_penerimaan)='."'".$_POST['year']."'".'';
		}

		return $query;

	}

	public function farmasi_mod_2(){

		$query = 'select b.kode_brg, b.nama_brg, b.content, kartu_stok.stok_akhir, b.satuan_kecil, 
					cast(c.harga_beli as int)as harga_beli, kartu_stok.tgl_input, kartu_stok.keterangan from mt_depo_stok a
					left join mt_barang b on b.kode_brg=a.kode_brg
					left join mt_rekap_stok c on c.id_obat=b.id_obat
					left join (
						select * from tc_kartu_stok
						WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu 
											FROM tc_kartu_stok 
											WHERE tgl_input <= '."'".$_POST['tgl']."'".' AND kode_bagian='."'".$_POST['kode_bagian']."'".'
											GROUP BY kode_brg) 
						and kode_bagian='."'".$_POST['kode_bagian']."'".'
					)as kartu_stok on kartu_stok.kode_brg=a.kode_brg
					where a.kode_bagian='."'".$_POST['kode_bagian']."'".' 
					order by kartu_stok.tgl_input DESC';

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

	public function akunting_mod_1(){
		$query = "select kode_tc_trans_kasir, tgl_transaksi, nama_pasien_layan, tc_trans_pelayanan.kode_bagian, mt_bagian.nama_bagian,
			mt_jenis_tindakan.kode_jenis_tindakan, 
			mt_jenis_tindakan.jenis_tindakan, nama_tindakan, CAST(SUM(bill_rs+bill_dr1+bill_dr2) as INT )
			from tc_trans_pelayanan 
			left join mt_jenis_tindakan on mt_jenis_tindakan.kode_jenis_tindakan=tc_trans_pelayanan.jenis_tindakan
			left join mt_bagian on mt_bagian.kode_bagian=tc_trans_pelayanan.kode_bagian
			where no_registrasi in (
				select no_registrasi from tc_registrasi where YEAR(tgl_jam_masuk)=2019 AND MONTH(tgl_jam_masuk)=5 AND kode_perusahaan=120
			)

			group by kode_tc_trans_kasir, nama_pasien_layan, tgl_transaksi, tc_trans_pelayanan.kode_bagian, nama_bagian, 
			mt_jenis_tindakan.kode_jenis_tindakan, mt_jenis_tindakan.jenis_tindakan, nama_tindakan
			having SUM(bill_rs+bill_dr1+bill_dr2) > 0
			order by mt_jenis_tindakan.jenis_tindakan ASC";
		return $query;
	}

	public function akunting_mod_2(){
		$query = "select tc_registrasi.no_registrasi, seri_kuitansi, tc_registrasi.no_mr, nama_pasien, 
					nama_bagian, no_sep, tgl_jam_masuk, CAST(billing.total as int) as total
					from tc_registrasi
					left join mt_bagian on mt_bagian.kode_bagian=tc_registrasi.kode_bagian_masuk
					left join mt_master_pasien on mt_master_pasien.no_mr=tc_registrasi.no_mr
					left join (
						select no_registrasi, SUM(bill)as total, seri_kuitansi from tc_trans_kasir where no_registrasi in (
						select no_registrasi from tc_registrasi
						where YEAR(tgl_jam_masuk) = ".$_POST['year']."
						and MONTH(tgl_jam_keluar) between ".$_POST['from_month']." and ".$_POST['to_month']."
						and tgl_jam_keluar is not null
						and tc_registrasi.kode_perusahaan=120 and status_batal is null
						)
						group by no_registrasi, seri_kuitansi
					) as billing on billing.no_registrasi=tc_registrasi.no_registrasi
					where tgl_jam_keluar is not null
					and tc_registrasi.kode_perusahaan=120 and status_batal is null and seri_kuitansi='".$_POST['keterangan']."'
					ORDER BY nama_bagian,tgl_jam_masuk ASC";
		return $query;
	}

	public function akunting_mod_4(){
		$query = "SELECT b.kode_brg, b.nama_brg, b.content, kartu_stok.stok_awal, kartu_stok.pemasukan, kartu_stok.pengeluaran, kartu_stok.stok_akhir, b.satuan_kecil, 
			c.harga_beli, kartu_stok.tgl_input, kartu_stok.keterangan, c.stok_minimum, b.satuan_besar, 
			b.is_active, b.path_image 
			FROM mt_depo_stok as a LEFT JOIN mt_barang b ON b.kode_brg=a.kode_brg 
			LEFT JOIN mt_rekap_stok c ON c.kode_brg=b.kode_brg 
			LEFT JOIN ( SELECT * FROM tc_kartu_stok_v WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok 
			WHERE MONTH(tgl_input)= ".$_POST['from_month']." and YEAR(tgl_input) = ".$_POST['year']." AND tgl_input is not null GROUP BY kode_brg) 
			AND kode_bagian='".$_POST['kode_bagian']."' ) AS kartu_stok ON kartu_stok.kode_brg=a.kode_brg WHERE a.kode_bagian = '".$_POST['kode_bagian']."' 
			AND nama_brg is not null AND kartu_stok.tgl_input is not null 
			GROUP BY b.kode_brg, b.nama_brg, b.content, kartu_stok.stok_awal, kartu_stok.pemasukan, kartu_stok.pengeluaran,
			kartu_stok.stok_akhir, b.satuan_kecil, 
			c.harga_beli, kartu_stok.tgl_input, kartu_stok.keterangan, c.stok_minimum, 
			b.satuan_besar, b.is_active, b.path_image ORDER BY b.kode_brg DESC";
			
		return $query;
	}

	public function so_mod_1(){

		$t_kartu_stok = ($_POST['bagian'] == 'non_medis') ? 'tc_kartu_stok_nm' : 'tc_kartu_stok' ;
		// Gudang Non Medis
		if($_POST['bagian']=='070101'){

			$this->db->select('mt_depo_stok_nm_v.kode_brg, mt_depo_stok_nm_v.nama_brg, mt_depo_stok_nm_v.satuan_besar, mt_depo_stok_nm_v.satuan_kecil, mt_depo_stok_nm_v.nama_golongan, mt_depo_stok_nm_v.nama_sub_golongan, mt_bagian.nama_bagian, mt_depo_stok_nm_v.is_active, kartu_stok.stok_akhir');
			$this->db->from('mt_depo_stok_nm_v');
			$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=mt_depo_stok_nm_v.kode_bagian','left');
			$this->db->join('( SELECT * FROM tc_kartu_stok_nm WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok_nm WHERE tgl_input <= '."'".date('Y-m-d')."'".' AND tgl_input is not null GROUP BY kode_brg) AND kode_bagian='."'".$_POST['bagian']."'".' ) AS kartu_stok', 'kartu_stok.kode_brg=mt_depo_stok_nm_v.kode_brg','left');

			$this->db->where('mt_depo_stok_nm_v.kode_bagian', $_POST['bagian']);
			$this->db->where('nama_brg LIKE '."'%'".'');

			if( isset($_POST['kode_golongan']) AND $_POST['kode_golongan'] != '' ){
				$this->db->where('kode_golongan', $_POST['kode_golongan']);
			}

			if( isset($_POST['kode_sub_gol']) AND $_POST['kode_sub_gol'] != '' ){
				$this->db->where('kode_sub_golongan', $_POST['kode_sub_gol']);
			}

			// $this->db->where('is_active', 1);
			$this->db->order_by( 'nama_sub_golongan','ASC' );
			$this->db->order_by( 'nama_golongan','ASC' );
			$this->db->order_by( 'nama_brg','ASC' );
			$query = $this->db->get();
			// print_r($this->db->last_query());die;

		// Gudang Medis
		}else{
			$this->db->select('kode_brg, nama_brg, satuan_besar, satuan_kecil, nama_sub_golongan as nama_golongan, nama_bagian, nama_kategori, nama_layanan, nama_jenis, jml_sat_kcl as stok_akhir, is_active');
			$this->db->from('mt_depo_stok_v');
			$this->db->join('mt_golongan', 'mt_golongan.kode_golongan=mt_depo_stok_v.kode_golongan','left');
			$this->db->join('mt_sub_golongan', 'mt_sub_golongan.kode_sub_gol=mt_depo_stok_v.kode_sub_golongan','left');
			$this->db->where('mt_depo_stok_v.kode_bagian', $_POST['bagian']);
			$this->db->where('nama_brg LIKE '."'%'".'');
			
			if( isset($_POST['kode_kategori']) AND $_POST['kode_kategori'] != '' ){
				$this->db->where('kode_kategori', $_POST['kode_kategori']);
			}

			if( isset($_POST['kode_layanan']) AND $_POST['kode_layanan'] != '' ){
				$this->db->where('kode_layanan', $_POST['kode_layanan']);
			}

			if( isset($_POST['jenis_obat']) AND $_POST['jenis_obat'] != '' ){
				$this->db->where('kode_jenis', $_POST['jenis_obat']);
			}

			// $this->db->where('status_aktif', 1);
			$this->db->order_by( 'nama_jenis','ASC' );
			$this->db->order_by( 'nama_layanan','ASC' );
			$this->db->order_by( 'nama_brg','ASC' );
			$query = $this->db->get();
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
			and a.set_status_aktif=".$_POST['status']." 
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
	  FROM [rls_rssm_sirs].[dbo].[tc_stok_opname] a 
  			left join [rls_rssm_sirs].[dbo].[mt_bagian] b on a.kode_bagian=b.kode_bagian 
			left join mt_barang c ON c.kode_brg=a.kode_brg
			left join mt_golongan d ON d.kode_golongan=c.kode_golongan
			WHERE a.agenda_so_id='5' AND a.kode_bagian='060201' 
			and a.set_status_aktif='1' 
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
					  FROM [rls_rssm_sirs].[dbo].[tc_stok_opname] a 
				  			left join [rls_rssm_sirs].[dbo].[mt_bagian] b on a.kode_bagian=b.kode_bagian 
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
					  FROM [rls_rssm_sirs].[dbo].[tc_stok_opname] a 
				  			left join [rls_rssm_sirs].[dbo].[mt_bagian] b on a.kode_bagian=b.kode_bagian 
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
			      	  FROM [rls_rssm_sirs].[dbo].[view_permintaan_inst_nm] 
			      	  where MONTH(tgl_pengiriman) BETWEEN '."'".$_POST['from_month']."'".'  and '."'".$_POST['to_month']."'".' AND YEAR(tgl_pengiriman)='."'".$_POST['year']."'".' ';

		return $query;

	}

	public function rl_mod_1(){

		$query = 'SELECT * FROM [rls_rssm_sirs].[dbo].[dd_konfigurasi]';

		return $query;

	}
	public function v_rl_mod_1(){

		$query = 'SELECT * FROM [rls_rssm_sirs].[dbo].[dd_konfigurasi]';

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

}
