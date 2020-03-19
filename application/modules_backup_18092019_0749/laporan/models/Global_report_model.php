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

	public function farmasi_mod_2(){

		$query = 'select b.kode_brg, b.nama_brg, b.content, kartu_stok.stok_akhir, b.satuan_kecil, 
					cast(c.harga_beli as int)as harga_beli, kartu_stok.tgl_input, kartu_stok.keterangan from mt_depo_stok a
					left join mt_barang b on b.kode_brg=a.kode_brg
					left join mt_rekap_stok c on c.id_obat=b.id_obat
					left join (
						select * from tc_kartu_stok_v 
						WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu 
											FROM tc_kartu_stok 
											WHERE tgl_input <= '."'".$_POST['tgl']."'".' AND tgl_input is not null
											GROUP BY kode_brg) 
						and kode_bagian='."'".$_POST['kode_bagian']."'".'
					)as kartu_stok on kartu_stok.kode_brg=a.kode_brg
					where a.kode_bagian='."'".$_POST['kode_bagian']."'".' and nama_brg is not null  and kartu_stok.tgl_input is not null
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
					where YEAR(tgl_jam_masuk) = ".$_POST['year']."
					and MONTH(tgl_jam_keluar) between ".$_POST['from_month']." and ".$_POST['to_month']."
					and tgl_jam_keluar is not null
					and tc_registrasi.kode_perusahaan=120 and status_batal is null
					ORDER BY tgl_jam_masuk ASC";
		return $query;
	}

	public function so_mod_1(){

		if($_POST['bagian']=='070101'){
			$this->db->select('kode_brg, nama_brg, satuan_besar, satuan_kecil, nama_sub_golongan as nama_golongan, nama_bagian');
			$this->db->from('mt_depo_stok_nm_v');
			$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=mt_depo_stok_nm_v.kode_bagian','left');
			$this->db->where('mt_depo_stok_nm_v.kode_bagian', $_POST['bagian']);
			$this->db->where('nama_brg LIKE '."'%'".'');
			$this->db->where('is_active', 1);
			$this->db->order_by( array('nama_brg' => 'ASC') );
			$query = $this->db->get();
		}else{
			$this->db->select('kode_brg, nama_brg, satuan_besar, satuan_kecil, nama_sub_golongan as nama_golongan, nama_bagian');
			$this->db->from('mt_depo_stok_v');
			$this->db->join('mt_golongan', 'mt_golongan.kode_golongan=mt_depo_stok_v.kode_golongan','left');
			$this->db->join('mt_sub_golongan', 'mt_sub_golongan.kode_sub_gol=mt_depo_stok_v.kode_sub_golongan','left');
			$this->db->where('mt_depo_stok_v.kode_bagian', $_POST['bagian']);
			$this->db->where('nama_brg LIKE '."'%'".'');
			$this->db->where('status_aktif', 1);
			$this->db->order_by( array('kode_sub_golongan' => 'DESC', 'nama_brg' => 'ASC') );
			$query = $this->db->get();
		}

		return $this->db->last_query();
	}


}
