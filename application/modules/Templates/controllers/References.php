<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class References extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	/*here function used for this application*/

	public function getNamaPasien()
    {
        
        $result = $this->db->select('nama_pasien')->where("nama_pasien LIKE '%".$_POST['keyword']."%' ")
                          ->group_by('nama_pasien')
                          ->order_by('nama_pasien', 'ASC')
                          ->get('mt_master_pasien')->result();
        $arrResult = [];
        foreach ($result as $key => $value) {
            $arrResult[] = $value->nama_pasien;
        }
        echo json_encode($arrResult);
        
	}

	public function getNamaKaryawan()
    {
        
        $result = $this->db->where("kode_dokter IS NULL AND nama_pegawai LIKE '%".$_POST['keyword']."%' ")
                          ->order_by('nama_pegawai', 'ASC')
                          ->get('mt_karyawan')->result();
        $arrResult = [];
        foreach ($result as $key => $value) {
            $arrResult[] = $value->no_induk.' : '.$value->nama_pegawai;
        }
        echo json_encode($arrResult);
        
	}
	
	public function getkaryawanAsPasien()
    {

		$result = $this->db->where('(no_induk is not null and no_induk != '."''".') ')
							->where("nama_pasien LIKE '%".$_POST['keyword']."%' ")
                          	->order_by('nama_pasien', 'ASC')
                          	->get('mt_master_pasien')->result();
        $arrResult = [];
        foreach ($result as $key => $value) {
            $arrResult[] = $value->no_mr.' : '.$value->nama_pasien;
        }
        echo json_encode($arrResult);
        
	}
	
	public function getPasien()
    {
		$result = $this->db->where('(no_induk is null or no_induk = '."''".') ')
							->where("nama_pasien LIKE '%".$_POST['keyword']."%' ")
                          	->order_by('nama_pasien', 'ASC')
                          	->limit(25)
                          	->get('mt_master_pasien')->result();
        $arrResult = [];
        foreach ($result as $key => $value) {
            $arrResult[] = $value->no_mr.' : '.$value->nama_pasien;
        }
        echo json_encode($arrResult);
        
	}
	
	public function getPasienByMr($no_mr)
    {
		$result = $this->db->where("no_mr", $no_mr)->get('mt_master_pasien')->row();
        echo json_encode($result);
        
    }

	public function getKlinikById($kd_bagian='')
	{
		$query = "select c.kode_bagian ,c.nama_bagian
					from tr_jadwal_dokter a
					left join mt_bagian c on c.kode_bagian=a.jd_kode_spesialis
					where a.jd_kode_spesialis=".$kd_bagian."
					group by c.kode_bagian,c.nama_bagian";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getDokterById($kd_dokter='')
	{
		$query = "select a.jd_kode_dokter as kode_dokter,b.nama_pegawai
					from tr_jadwal_dokter a
					left join mt_karyawan b on b.kode_dokter=a.jd_kode_dokter
					left join mt_bagian c on c.kode_bagian=a.jd_kode_spesialis
					where a.jd_kode_dokter=".$kd_dokter."
					group by a.jd_kode_dokter,b.nama_pegawai";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getDokterBySpesialis($kd_bagian='')
	{
		$query = "select a.jd_kode_dokter as kode_dokter,b.nama_pegawai
					from tr_jadwal_dokter a
					left join mt_karyawan b on b.kode_dokter=a.jd_kode_dokter
					left join mt_bagian c on c.kode_bagian=a.jd_kode_spesialis
					where a.jd_kode_spesialis=".$kd_bagian."
					group by a.jd_kode_dokter,b.nama_pegawai";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	
	public function getDokterSpesialis($kd_bagian='')
	{
		$query = "select a.kode_dokter as kode_dokter,a.nama_pegawai
					from mt_dokter_v a where a.kd_bagian=".$kd_bagian." and a.nama_pegawai != ' '
					group by a.kode_dokter,a.nama_pegawai";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getKlinikFromJadwal($day='')
	{
		$query = "select a.jd_kode_spesialis as kode_bagian,c.nama_bagian
					from tr_jadwal_dokter a
					left join mt_bagian c on c.kode_bagian=a.jd_kode_spesialis
					where a.jd_hari='".$day."' and a.status_loket='on' or (kode_bagian = '012801' or kode_bagian='012901')
					group by  a.jd_kode_spesialis,c.nama_bagian";
		$exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getDokterBySpesialisFromJadwal($kd_bagian='', $day='')
	{
		$query = "select a.jd_id,a.jd_kode_dokter as kode_dokter,b.nama_pegawai
					from tr_jadwal_dokter a
					left join mt_karyawan b on b.kode_dokter=a.jd_kode_dokter
					left join mt_bagian c on c.kode_bagian=a.jd_kode_spesialis
					where a.jd_kode_spesialis='".$kd_bagian."' and a.jd_hari='".$day."' and a.status_loket = 'on'
					group by a.jd_id, a.jd_kode_dokter,b.nama_pegawai";
		$exc = $this->db->query($query); 
        echo json_encode($exc->result());
	}

	public function getDokterBySpesialisFromJadwalDefault($kd_bagian='', $day='')
	{
		$query = "select a.jd_id,a.jd_kode_dokter as kode_dokter,b.nama_pegawai
					from tr_jadwal_dokter a
					left join mt_karyawan b on b.kode_dokter=a.jd_kode_dokter
					left join mt_bagian c on c.kode_bagian=a.jd_kode_spesialis
					where a.jd_kode_spesialis='".$kd_bagian."' and a.jd_hari='".$day."'
					group by a.jd_id, a.jd_kode_dokter,b.nama_pegawai";
		$exc = $this->db->query($query); 
        echo json_encode($exc->result());
	}

	public function getJadwalPraktek($kode_dokter, $kode_spesialis)
	{	
		$query = "select a.jd_id, a.jd_kode_dokter,b.nama_pegawai as nama_dokter, a.jd_kode_spesialis, 
					c.nama_bagian as spesialis,a.jd_hari, a.jd_jam_mulai, a.jd_jam_selesai, a.jd_keterangan, a.jd_kuota
					from tr_jadwal_dokter a
					left join mt_karyawan b on b.kode_dokter=a.jd_kode_dokter
					left join mt_bagian c on c.kode_bagian=a.jd_kode_spesialis
					where a.jd_kode_spesialis=".$kode_spesialis." and a.jd_kode_dokter=".$kode_dokter."";

        $query = $this->db->query($query)->result();
        $jadwal = [];
        $html = '';
		$array_color_day = array('green','red','purple','blue','black','orange','grey');
    	shuffle($array_color_day);

    	$html .= '<p><strong><i class="fa fa-list"></i> JADWAL PRAKTEK DOKTER</strong></p>';
        foreach ($query as $key => $value) {
        	$time = $this->tanggal->formatTime($value->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($value->jd_jam_selesai);
        	$jadwal[] = array('day' => $value->jd_hari , 'time' => $time);
        	$html .= '<a href="#"  onclick="detailJadwalPraktek('.$value->jd_id.')"><div class="infobox infobox-'.array_shift($array_color_day).' infobox-small infobox-dark">
						    <div class="infobox-data">
						        <div class="infobox-content">'.$value->jd_hari.'</div>
						        <div class="infobox-content">'.$time.'</div>
						    </div>
						</div></a>';
        }
        $html .= '<br><small>* Silahkan pilih jadwal dokter praktek </small>';
		echo json_encode(array('html' => $html));

	}

	public function getDetailJadwalPraktek($jd_id)
	{
		$query = "select a.*, a.jd_kode_dokter as kode_dokter,b.nama_pegawai
					from tr_jadwal_dokter a
					left join mt_karyawan b on b.kode_dokter=a.jd_kode_dokter
					left join mt_bagian c on c.kode_bagian=a.jd_kode_spesialis
					where a.jd_id=".$jd_id."";
        $exc = $this->db->query($query)->row();
        /*cek ketersediaan kuota*/
        /*$quota = $this->cek_kuota($exc->jd_kode_dokter);*/
        $quota_dokter = $exc->jd_kuota;
        /*$sisa_kuota = $quota_dokter - $quota;*/

        $time = $this->tanggal->formatTime($exc->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($exc->jd_jam_selesai);
        $html = '';
        $html .= '<p style="margin-top:5px"><strong><i class="fa fa-chevron-circle-right"></i> RESUME YANG DIPILIH</strong></p>';
        $html .= '<table class="table table-bordered table-hover" id="resume_jadwal_dokter">

                      <thead>

                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Nama Dokter</th>

                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Jam Praktek</th>

                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Keterangan</th>

                      </thead>

                      <tbody>

                        <td>'.ucwords($exc->nama_pegawai).'</td>
                        <td>'.$exc->jd_hari.'<br>'.$time.'</td>
                        <td>Kuota '.$quota_dokter.'<br>'.$exc->jd_keterangan.'</td>

                      </tbody>

                    </table>';

        /*$html .= '<address>
					<strong>'.ucwords($exc->nama_pegawai).'</strong><br>
					Hari <b>'.$exc->jd_hari.'</b><br>
					Jam Praktek <b>'.$time.'</b><br>
					Kuota Pasien <b>'.$quota_dokter.'</b>
				</address>';*/

        echo json_encode(array('html' => $html, 'day' => $exc->jd_hari, 'time' => $time, 'id' => $exc->jd_id, 'time_start' => $this->tanggal->formatTime($exc->jd_jam_mulai) ));
	}

	function cek_kuota($kode_dokter, $tgl_pesan){
		return $this->db->get_where('tc_pesanan', array('tgl_pesanan' => $tgl_pesan, 'kode_dokter' => $kode_dokter) )->num_rows();
	}

	function CheckSelectedDate(){
		/*get data from post*/
		$date = $_POST['date'];
		$kode_spesialis = $_POST['kode_spesialis'];
		$kode_dokter = $_POST['kode_dokter'];
		$jd_id = $_POST['jadwal_id'];

		/*get day from date*/
		$day = $this->tanggal->getHariFromDate($date);
		/*change to sql date*/
		$sqlDate = $this->tanggal->sqlDateFormStrip($date);
		/*check current date*/
		$selected_date = strtotime($sqlDate);
		/*get status date*/
		$status = ($selected_date < time() ) ? 'expired' : 'success' ;
		/*get master jadwal*/
		$jadwal = $this->db->get_where('tr_jadwal_dokter', array('jd_id' => $jd_id) )->row();
		$kuota_dr = $jadwal->jd_kuota;
		/*get kuota dokter*/
		$substr_kode_spesialis = substr($kode_spesialis, 1);
		/*get data from averin*/
		$row_data_averin = $this->db->get_where('tc_pesanan', array('tgl_pesanan' => $date, 'no_poli' => $substr_kode_spesialis, 'kode_dokter' => $kode_dokter) )->num_rows();
		$row_data_registrasi = $this->db->get_where('tc_registrasi', array('tgl_jam_masuk' => $date, 'kode_bagian_masuk' => $substr_kode_spesialis, 'kode_dokter' => $kode_dokter) )->num_rows();
		/*get data from reg online*/
		$regon = $this->db->get_where('regon_booking', array('regon_booking_tanggal_perjanjian' => $date, 'regon_booking_klinik' => $kode_spesialis, 'regon_booking_kode_dokter' => $kode_dokter) )->num_rows();
		/*terisi*/
		$terisi = $row_data_averin + $row_data_registrasi + $regon;
		/*sisa kuota*/
		$kuota = $kuota_dr - $terisi;


		echo json_encode(array('day' => $day, 'status' => $status, 'kuota_dr' => $kuota_dr, 'terisi' => $terisi, 'sisa' => $kuota) );
	}

	public function getProvince()
	{
        
		$result = $this->getProvinceByKeyword($_POST['keyword']);
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->id.' : '.$value->name;
		}
		echo json_encode($arrResult);
		
		
	}

	public function getProvinceByKeyword($key='')
	{
        $query = $this->db->where("name LIKE '%".$key."%' ")
        				  ->order_by('name', 'ASC')
                          ->get('provinces');
		
        return $query->result();
	}

	public function getDistricts()
	{
        
		$result = $this->getDistrictsByKeyword($_POST['keyword']);
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->id.' : '.$value->name;
		}
		echo json_encode($arrResult);
		
		
	}

	public function getDistrictsByKeyword($key='',$regency='')
	{
        $query = $this->db->where("name LIKE '%".$key."%' ")
        				  ->order_by('name', 'ASC')
                          ->get('districts');
		
        return $query->result();
	}

	public function getVillage()
	{
        
		$result = $this->getVillageByKeyword($_POST['keyword'],$_POST['district']);
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->id.' : '.$value->name;
		}
		echo json_encode($arrResult);
		
		
	}

	public function getVillageByKeyword($key='',$district='')
	{
        $query = $this->db->where("name LIKE '%".$key."%' ")->where("district_id", $district)
        				  ->order_by('name', 'ASC')
                          ->get('villages_new');
		
        return $query->result();
	}

	public function getKelompokNasabah()
	{
        
		$result = $this->db->where("nama_kelompok LIKE '%".$_POST['keyword']."%' ")
        				  ->order_by('nama_kelompok', 'ASC')
                          ->get('mt_nasabah')->result();
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->kode_kelompok.' : '.$value->nama_kelompok;
		}
		echo json_encode($arrResult);
		
	}


	public function getPerusahaan()
	{
        
		$result = $this->getPerusahaanByKeyword($_POST['keyword']);
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->kode_perusahaan.' : '.$value->nama_perusahaan;
		}
		echo json_encode($arrResult);
		
		
	}
	
	public function getPerusahaanByKeyword($key='')
	{
        $query = $this->db->where("nama_perusahaan LIKE '%".$key."%' ")
        				  ->order_by('nama_perusahaan', 'ASC')
                          ->get('mt_perusahaan');
		
        return $query->result();
	}

	public function getAllDokter()
	{
        
		$result = $this->getAllDokterByKeyword($_POST['keyword']);
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->kode_dokter.' : '.$value->nama_pegawai;
		}
		echo json_encode($arrResult);
		
		
	}
	
	public function getAllDokterByKeyword($key='')
	{
        $query = $this->db->where("nama_pegawai LIKE '%".$key."%' ")->where("kode_dokter is not NULL")
        				  ->order_by('nama_pegawai', 'ASC')
                          ->get('mt_karyawan');
		
        return $query->result();
	}

	public function getRefBrg()
	{
		$table = ($_POST['flag']=='non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
		$join = ($_POST['flag']=='non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;
		
		$this->db->from($table.' as a');
		 $this->db->like($_POST['search_by'], $_POST['keyword']);
		// $this->db->where('is_active', 1);
		$this->db->limit(10);
		$result = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		$data = array(
			'value' => $result,
			'flag' => $_POST['flag'],
			'search_by' => $_POST['search_by'],
			'keyword' => $_POST['keyword'],
		);
		$html = $this->load->view('templates/temp_view_selected_brg', $data, true);

		echo json_encode(array('html' => $html, 'total_item' => count($result) ));	
	}

	public function getRefBrgPermintaanUnit()
	{
		$table = ($_POST['flag']=='non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
		$join = ($_POST['flag']=='non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;
		$this->db->from($table.' as a');
		
		 $this->db->like($_POST['search_by'], $_POST['keyword']);
		$this->db->where('is_active', 1);
		$this->db->limit(10);
		$result = $this->db->get()->result();
		$data = array(
			'value' => $result,
			'flag' => $_POST['flag'],
			'search_by' => $_POST['search_by'],
			'keyword' => $_POST['keyword'],
		);
		$html = $this->load->view('templates/temp_view_selected_brg_unit', $data, true);

		echo json_encode(array('html' => $html, 'total_item' => count($result) ));	
	}

	public function getRefBrgDepo()
	{
		$table = ($_POST['flag']=='non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
		$join = ($_POST['flag']=='non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;
		$this->db->from($table.' as a');
		
		$this->db->like($_POST['search_by'], $_POST['keyword']);
		$this->db->where('is_active', 1);
		$this->db->limit(10);
		$result = $this->db->get()->result();
		$data = array(
			'value' => $result,
			'flag' => $_POST['flag'],
			'search_by' => $_POST['search_by'],
			'keyword' => $_POST['keyword'],
			'kode_bagian' => $_POST['kode_bagian'],
		);
		// echo '<pre>'; print_r($data);die;
		$html = $this->load->view('templates/temp_view_selected_brg_depo', $data, true);

		echo json_encode(array('html' => $html, 'total_item' => count($result) ));		
		
	}

	public function getItem()
	{
        
		$result = $this->getItemByKeyword($_POST['keyword']);
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->kode_brg.' : '.$value->nama_brg;
		}
		echo json_encode($arrResult);
		
		
	}

	public function getItemByKeyword($key='')
	{
        // $query = $this->db->where("nama_brg LIKE '%".$key."%' ")->where("is_active", "1")
        // 				  ->order_by('nama_brg', 'ASC')
        //                   ->get('mt_barang_nm');
		$query = "select a.kode_brg,b.nama_brg from mt_depo_stok_nm a
					left join mt_barang_nm b on a.kode_brg=b.kode_brg
					where b.nama_brg LIKE '%".$key."%' and b.is_active=1";
		$exc = $this->db->query($query);
		return $exc->result();
	}

	/*END*/

	public function getDataItem($kode='')
	{
        $query = $this->db->where(array('kode_brg' => $kode))->get('mt_barang_nm');		
        echo json_encode($query->result());
	}

	public function get_jabatan_by_bag_unit_id($id='')
	{
        $query = $this->db->where(array('bag_unit_id' => $id))->get('mst_jabatan');		
        echo json_encode($query->result());
	}

	public function getRegencyByProvince($provinceId='')
	{
        $query = $this->db->where('province_id', $provinceId)
        				  ->order_by('name', 'ASC')
                          ->get('regencies');
		
        echo json_encode($query->result());
	}

	public function getDistrictByRegency($regency_id='')
	{
        $query = $this->db->where('regency_id', $regency_id)
        				  ->order_by('name', 'ASC')
                          ->get('districts');
		
        echo json_encode($query->result());
	}

	public function getVillagesByDistrict($district_id='')
	{
        $query = $this->db->where('district_id', $district_id)
        				  ->order_by('name', 'ASC')
                          ->get('villages');
		
        echo json_encode($query->result());
	}

	public function getVillagesById($id='')
	{
        $query = $this->db->where('id', $id)
        				  ->order_by('name', 'ASC')
                          ->get('villages_new');
		
        echo json_encode($query->result());
	}

	public function getDistrictsById($id='')
	{
		$query = "select  b.id as province_id, b.name as province_name,c.id as regency_id,c.name as regency_name
				from districts a
				left join provinces b on b.id=a.province_id
				left join regencies c on c.id=a.regency_id
				where a.id=".$id." ";
		$exc = $this->db->query($query);
		echo json_encode($exc->row());
	}

	public function getMenuByModulId($modul_id='')
	{
        $query = $this->db->where('modul_id', $modul_id)->where('parent', 0)->where('is_active', 'Y')
        				  ->order_by('name', 'ASC')
                          ->get('tmp_mst_menu');
		
        echo json_encode($query->result());
	}

	public function getKlasByRuangan($kd_bagian='')
	{
		$query = "select  a.kode_klas, c.nama_bagian,b.nama_klas
					from mt_ruangan a
					left join mt_klas b on b.kode_klas=a.kode_klas
					left join mt_bagian c on c.kode_bagian=a.kode_bagian
					where a.kode_bagian=".$kd_bagian."
					group by a.kode_klas, c.nama_bagian,b.nama_klas";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getKlasByRuanganTarif($kd_bagian='',$kode_tarif='')
	{
		$query = "select  a.kode_klas, c.nama_bagian,b.nama_klas
					from mt_ruangan a
					left join mt_klas b on b.kode_klas=a.kode_klas
					left join mt_bagian c on c.kode_bagian=a.kode_bagian
					left join mt_master_tarif_detail d on d.kode_klas=a.kode_klas
					where a.kode_bagian=".$kd_bagian." and d.kode_tarif=".$kode_tarif."
					group by a.kode_klas, c.nama_bagian,b.nama_klas";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getRuanganByKlas($kode_klas)
	{
		$query = "select  a.kode_bagian, a.kode_klas, c.nama_bagian, b.nama_klas
					from mt_ruangan a
					left join mt_klas b on b.kode_klas=a.kode_klas
					left join mt_bagian c on c.kode_bagian=a.kode_bagian
					left join mt_master_tarif_detail d on d.kode_klas=a.kode_klas
					where a.kode_klas=".$kode_klas."
					group by a.kode_bagian, a.kode_klas, c.nama_bagian,b.nama_klas";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getRuanganByTarif($kode_tarif='')
	{
		$query = "select  a.kode_bagian, a.nama_bagian
					from mt_bagian a
					left join mt_ruangan b on b.kode_bagian=a.kode_bagian
					left join mt_master_tarif_detail c on c.kode_klas=b.kode_klas
					where  c.kode_tarif=".$kode_tarif." group by a.kode_bagian, a.nama_bagian ";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getBedByKlas($kd_bagian='',$klas='')
	{
		$query = "select  a.kode_ruangan, a.no_bed, a.status, a.no_kamar
					from mt_ruangan a
					where a.kode_bagian=".$kd_bagian." and a.kode_klas=".$klas." and a.status is NULL";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getBedByKlasWithView($kd_bagian='',$klas='')
	{
		$query = "select  a.kode_ruangan, a.no_bed, a.status, a.no_kamar
					from mt_ruangan a
					where a.kode_bagian=".$kd_bagian." and a.kode_klas=".$klas." and a.flag_cad=0 order by a.no_kamar, a.no_bed ASC";
        $exc = $this->db->query($query)->result();
        $room = [];
        foreach ($exc as $key => $value) {
        	$room[$value->no_kamar][] = array(
					        				'kode_ruangan' => $value->kode_ruangan,
					        				'no_bed' => $value->no_bed,
					        				'status' => $value->status,
					        				'no_kamar' => $value->no_kamar,
					        				'reserved_by' => $this->get_data_pasien_ri_existing($value->kode_ruangan),
					        			);
        }

        // echo '<pre>';print_r($room);die;
        /*show view*/
        $html = '';

        foreach ($room as $key => $value) {
        	$html .= '<div class="col-sm-12">
						<h3 class="header smaller lighter green">
							<i class="ace-icon fa fa-circle-o"></i>
							Kamar '.(int)$key.'
						</h3>';
				foreach($value as $row) :

					$reserve = '';
					if(count($row['reserved_by']) > 0){

						if( $row['reserved_by'][0]->tgl_keluar == NULL ){
							if($row['reserved_by'][0]->nama_pasien) {
								$data_reserve = $row['reserved_by'][0];
								$img_color = 'bed_red.png';
								$reserve .= $data_reserve->nama_pasien.' ('.$data_reserve->no_mr.')<br>';
								$reserve .= 'Tanggal Masuk   : '.$this->tanggal->formatDate($data_reserve->tgl_masuk).'<br>';
								$reserve .= 'dr Pengirim : '.$data_reserve->dr_pengirim.'<br>';
								$is_available = '<a href="#"><label class="label label-danger">Sudah Terisi</label></a>';
							}
						}else{
							$img_color = 'bed_green.png';
							$is_available = '<a href="#" style="cursor:pointer" class="btn btn-xs btn-success" onclick="select_bed_from_modal_bed('."'".$row['kode_ruangan']."'".","."'".$row['no_bed']."'".","."'".$row['no_kamar']."'".')">Available</a>';
							$reserve .= 'Nama Pasien :<br>';
							$reserve .= 'Tanggal masuk : <br>';
							$reserve .= 'Dokter pengirim : <br>';
						}
						
						
					}else{
						$img_color = 'bed_green.png';
						$is_available = '<a href="#" style="cursor:pointer" class="btn btn-xs btn-success" onclick="select_bed_from_modal_bed('."'".$row['kode_ruangan']."'".","."'".$row['no_bed']."'".')">Available</a>';
						$reserve .= 'Nama Pasien :<br>';
						$reserve .= 'Tanggal masuk : <br>';
						$reserve .= 'Dokter pengirim : <br>';
					}

		        $html .= '<div class="col-md-4">';
		        	$html .= '<div class="col-md-3">
								<div class="center">
									<img src="'.base_url().'assets/images/bed/'.$img_color.'" style="width:80px">
								</div>
							  </div>
							  <div class="col-md-8" style="font-size:11px;padding-left:15px;margin-bottom:20px">
							  	<b>BED '.(int)$row['no_bed'].'</b></br> 
			        			'.$reserve.'
			        			'.$is_available.'
							  </div>';
		        $html .= '</div>';
		        endforeach;
			$html .= '</div>';
        }

        /*$html .= '<table border="1" style="margin-left: 20px">';
	        $html .= '<tr>';
	        	$html .= '<td>';
	        	$html .= '<img src="'.base_url().'assets/images/bed/bed_red.png" style="width:80px"><br>';
	        	$html .= 'Bed 1<br>';
	        	$html .= 'Bed 1<br>';
	        	$html .= 'Nama<br>';
	        	$html .= 'Tanggal masuk<br>';
	        	$html .= '</td>';
	        $html .= '</tr>';
        $html .= '</table>';*/
        



        //echo '<pre>';print_r($html);die;

        echo json_encode(array('html' => $html));
	}

	public function get_data_pasien_ri_existing($kode_ruangan){

		$this->db->select('d.no_mr,d.nama_pasien,c.no_registrasi,b.no_kunjungan, a.dr_pengirim, a.tgl_masuk, a.tgl_keluar');
		$this->db->from('ri_tc_rawatinap a');
		$this->db->join('tc_kunjungan b', 'a.no_kunjungan=b.no_kunjungan','left');
		$this->db->join('tc_registrasi c', 'b.no_registrasi=c.no_registrasi','left');
		$this->db->join('mt_master_pasien d', 'd.no_mr=c.no_mr','left');
		// $this->db->where('a.tgl_keluar IS NULL');
		$this->db->where('a.kode_ruangan='."'".$kode_ruangan."'".'');
		$this->db->order_by('a.kode_ri DESC');
		return $this->db->get()->result();

	}

	public function getDeposit($kd_bagian='',$klas='')
	{
		$query = "select  a.deposit, a.harga_r, a.harga_bpjs
					from mt_master_tarif_ruangan a
					where a.kode_bagian=".$kd_bagian." and a.kode_klas=".$klas."";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getKuotaDokter($kode_dokter='',$kode_spesialis='', $tanggal='')
	{
		$day = $this->tanggal->getHari(date('D'));
		$date = ($tanggal=='')?date('Y-m-d'):$tanggal;

		/*existing*/
		$log_kuota_perjanjian = $this->db->get_where('tc_pesanan', array('CAST(jam_pesanan as DATE) = ' => date('Y-m-d'), 'kode_dokter' => $kode_dokter, 'no_poli' => $kode_spesialis, 'tgl_masuk' => NULL) )->num_rows();
		
		$log_kuota_current = $this->db->get_where('tc_registrasi', array('CAST(tgl_jam_masuk as DATE) = ' => $date, 'kode_dokter' => $kode_dokter, 'kode_bagian_masuk' => $kode_spesialis) )->num_rows();

		$log_kuota_mjkn = $this->db->get_where('log_kuota_dokter', array('tanggal' => $date, 'kode_dokter' => $kode_dokter, 'kode_spesialis' => $kode_spesialis, 'flag' => 'mobile_jkn') )->num_rows();

		$mesin_antrian = $this->db->get_where('log_kuota_dokter', array('tanggal' => $date, 'kode_dokter' => $kode_dokter, 'kode_spesialis' => $kode_spesialis, 'flag' => 'mesin_antrian') )->num_rows();

        /*kuota dokter*/
        $kuota_dokter = $this->db->get_where('tr_jadwal_dokter', array('jd_hari' => $day, 'jd_kode_dokter' => $kode_dokter, 'jd_kode_spesialis' => $kode_spesialis) )->row(); 

		$id = $kuota_dokter->jd_id; 
		$kuota_dr = $kuota_dokter->jd_kuota;
		$sisa = $kuota_dokter->jd_kuota - ($log_kuota_perjanjian + $log_kuota_current + $log_kuota_mjkn);

		$data = array(
			'kuota' => $kuota_dr,
			'perjanjian_rj' => $log_kuota_perjanjian,
			'perjanjian_mjkn' => $log_kuota_mjkn,
			'terdaftar' => $log_kuota_current,
			'antrian' => $mesin_antrian,
			'sisa_kuota' => $sisa,
			'kode_dokter' => $kode_dokter,
			'kode_bagian' => $kode_spesialis,
		);
		$html = $this->load->view('templates/view_log_kuota_dr', $data, true);

		$message = ($sisa==0)?'<label class="label label-danger"><i class="fa fa-times-circle"></i> Maaf, Kuota sudah penuh !</label>':'<label class="label label-success"><i class="fa fa-check"></i> Kuota Terpenuhi</label>';

        echo json_encode(array('sisa_kuota' => $sisa, 'jd_id' => $id, 'message' => $html));
	}

	public function view_pasien_terdaftar_current(){
		$pasien_terdaftar = $this->db
			->join('tc_kunjungan', 'tc_kunjungan.no_kunjungan=pl_tc_poli.no_kunjungan', 'left')		 ->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left')
			->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left')
			->order_by('no_antrian', 'ASC')
			->get_where('pl_tc_poli', array('CAST(tgl_jam_poli as DATE) = ' => date('Y-m-d'), 'pl_tc_poli.kode_dokter' => $_GET['kode_dokter'], 'pl_tc_poli.kode_bagian' => $_GET['kode_spesialis']) )->result();
		$data = array(
			'result' => $pasien_terdaftar,
		);
		$this->load->view('templates/view_pasien_terdaftar', $data);
	}

	public function view_pasien_perjanjian(){
		
		$pasien_perjanjian = $this->db->get_where('tc_pesanan', array('CAST(jam_pesanan as DATE) = ' => date('Y-m-d'), 'kode_dokter' => $_GET['kode_dokter'], 'no_poli' => $_GET['kode_spesialis']) )->result();
		// print_r($this->db->last_query());die;
		$data = array(
			'result' => $pasien_perjanjian,
		);

		$this->load->view('templates/view_pasien_perjanjian', $data);
	}

	public function getTindakanByBagian($kd_bagian='')
	{
		$query = "select  a.kode_tarif, a.nama_tarif
					from mt_master_tarif a
					left join mt_master_tarif_detail b on b.kode_tarif=a.kode_tarif
					where  a.tingkatan=5 and a.kode_bagian=".$kd_bagian." and b.kode_klas=16 and a.jenis_tindakan =14 order by a.nama_tarif";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	

	public function getBayiRS()
	{
		$query = "select  id_bayi, nama_bayi, mr_ibu, tgl_jam_lahir
					from ri_bayi_lahir
					where (flag_lahir = 0 or flag_lahir is null) and nama_bayi <> '' and YEAR(tgl_jam_lahir)= ".date('Y')." ";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getBayiRSbyID($id)
	{
		$query = "select *
					from ri_bayi_lahir
					where id_bayi=".$id."";
        $exc = $this->db->query($query);
        echo json_encode($exc->row());
	}

	public function getDataIbu($id='')
	{
		$data = $this->db->get_where('ri_bayi_lahir',array('id_bayi' => $id))->row();
		
		$query = "select *
					from mt_master_pasien
					where no_mr = '".$data->mr_ibu."'";
		$exc = $this->db->query($query);
        echo json_encode($exc->row());
	}

	// public function getPaketMCU()
	// {	
	// 	$query = "	select  a.kode_tarif, a.nama_tarif
	// 				from mt_master_tarif a
	// 				left join mt_master_tarif_detail b on b.kode_tarif=a.kode_tarif
	// 				where  a.tingkatan=5 and a.kode_bagian='010901' and a.jenis_tindakan=14 group by a.kode_tarif, a.nama_tarif";
	// 	$exc = $this->db->query($query);
  //       echo json_encode($exc->result());
	// }

	public function getICD10()
	{
        $query = "select  icd_10, nama_icd from mt_master_icd10 where  nama_icd LIKE '%".$_POST['keyword']."%' or icd_10 LIKE '%".$_POST['keyword']."%' group by icd_10, nama_icd";
		
		$result = $this->db->query($query)->result();
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->icd_10.' : '.$value->nama_icd;
		}
		echo json_encode($arrResult);
		
		
	}

	public function getPaketMCU()
	{
        
		$result = $this->getPaketMCUByKeyword($_POST['keyword']);
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->kode_tarif.' : '.$value->nama_tarif;
		}
		echo json_encode($arrResult);
		
		
	}

	public function getPaketMCUByKeyword($key='',$district='')
	{
		$query = "select  a.kode_tarif, a.nama_tarif
							from mt_master_tarif a
							left join mt_master_tarif_detail b on b.kode_tarif=a.kode_tarif
							where  a.tingkatan=5 and a.kode_bagian='010901' and a.jenis_tindakan=14 and a.nama_tarif LIKE '%".$key."%' group by a.kode_tarif, a.nama_tarif";
		
		$exc = $this->db->query($query);
        return $exc->result();
	}

	public function getDokterByBagian_($kd_bagian='')
	{
		$query = "select  a.kode_dokter, a.nama_pegawai
					from mt_dokter_v a
					where a.kd_bagian=".$kd_bagian."  and a.nama_pegawai is not NULL and a.nama_pegawai <> ''";
        $exc = $this->db->query($query);
        echo json_encode($exc->result());
	}

	public function getDokterByBagian()
	{
        
		$result = $this->getDokterByBagianByKeyword($_POST['keyword'], $_POST['bag']);
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->kode_dokter.' : '.$value->nama_pegawai;
		}
		echo json_encode($arrResult);
		
	}

	public function getDokterByKeyword()
	{
		$query = "select a.kode_dokter, a.nama_pegawai
	 				from mt_dokter_v a
	 				where status=0 AND a.nama_pegawai LIKE '%".$_POST['keyword']."%' and a.nama_pegawai is not NULL and a.nama_pegawai <> '' GROUP BY a.kode_dokter, a.nama_pegawai";
        $result = $this->db->query($query)->result();
        foreach ($result as $key => $value) {
			$arrResult[] = $value->kode_dokter.' : '.$value->nama_pegawai;
		}
		echo json_encode($arrResult);
	}


	public function getSupplier()
	{
        $result = $this->db->like('namasupplier', $_POST['keyword'])->get('mt_supplier')->result();
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->kodesupplier.' : '.$value->namasupplier;
		}
		echo json_encode($arrResult);
		
	}

	public function getSupplierById($kode_supplier)
	{
		$result = $this->db->where('kodesupplier', $kode_supplier)->get('mt_supplier')->row();
		
		echo json_encode($result);
		
	}

	public function getBagian()
	{
        $query = "select a.kode_bagian, a.nama_bagian
					from mt_bagian a
					where a.nama_bagian LIKE '%".$_POST['keyword']."%' order by nama_bagian asc";
		$result = $this->db->query($query)->result();
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->kode_bagian.' : '.$value->nama_bagian;
		}
		echo json_encode($arrResult);
		
	}

	public function getDokterByBagianByKeyword($key='',$bag='')
	{
		$query = "select  a.kode_dokter, a.nama_pegawai
	 				from mt_dokter_v a
	 				where a.kd_bagian=".$bag." and a.nama_pegawai LIKE '%".$key."%' and a.nama_pegawai is not NULL and a.nama_pegawai <> ''";
		
		$exc = $this->db->query($query);
        return $exc->result();
	}

	public function getRegenciesPob()
	{
        
		$reg = $this->getRegenciesPobByKeyword($_POST['keyword']);
		$prov = $this->getProvPobByKeyword($_POST['keyword']);
		$result = array_merge($reg, $prov);
		
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->name;
		}
		echo json_encode($arrResult);
		
		
	}

	public function getRegenciesPobByKeyword($key='')
	{
        $query = $this->db->where("name LIKE '%".$key."%' ")
        				  ->order_by('name', 'ASC')
                          ->get('regencies');
		
        return $query->result();
	}

	public function getProvPobByKeyword($key='')
	{
        $query = $this->db->where("name LIKE '%".$key."%' ")
        				  ->order_by('name', 'ASC')
                          ->get('provinces');
		
        return $query->result();
	}

	public function getTindakanByBagianAutoComplete()
	{
		//$where_str = ($_POST['kode_perusahaan']==120) ? 'and nama_tarif like '."'%BPJS%'".'' : 'and nama_tarif not like '."'%BPJS%'".'' ;
		// just for kamar bedah
		$where_str = ($_POST['kode_bag']=='030901')?'and a.referensi in (select kode_tarif from mt_master_tarif where kode_bagian='."'".$_POST['kode_bag']."'".' and referensi='.$_POST['jenis_bedah'].')':'';
        $query = "select  a.kode_tarif, a.kode_tindakan, a.nama_tarif, c.nama_tarif as tingkat_operasi
					from mt_master_tarif a
					left join mt_master_tarif_detail b on b.kode_tarif=a.kode_tarif
					left join mt_master_tarif c on c.kode_tarif=a.referensi
					where  a.tingkatan=5 and (a.kode_bagian="."'".$_POST['kode_bag']."'"." or a.kode_bagian=0) and a.nama_tarif like '%".$_POST['keyword']."%' ".$where_str." group by a.kode_tarif, a.kode_tindakan, a.nama_tarif, a.is_old, c.nama_tarif order by a.is_old asc,a.nama_tarif asc";
					//echo $query;exit;
        $exc = $this->db->query($query)->result();

		$arrResult = [];
		foreach ($exc as $key => $value) {
			$jenis_operasi = ($_POST['kode_bag']=='030901') ? ' | '.$value->tingkat_operasi.'' : '' ;
			$arrResult[] = $value->kode_tarif.' : '.$value->nama_tarif.' ('.$value->kode_tindakan.') '.$jenis_operasi.'';
		}
		echo json_encode($arrResult);
		
		
	}

	public function getTindakanBedah()
	{
        
		$result = $this->getTindakanBedahByKeyword($_POST['keyword']);
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->kode_tarif.' : '.$value->nama_tarif;
		}
		echo json_encode($arrResult);
		
		
	}

	public function getTindakanBedahByKeyword($key='')
	{
        // $query = $this->db->where("name LIKE '%".$key."%' ")
        // 				  ->order_by('name', 'ASC')
        //                   ->get('provinces');
		
		$where_str = 'and a.referensi in (select kode_tarif from mt_master_tarif where kode_bagian='."'030901'".')'
		// $where_str = 'and a.referensi in (select kode_tarif from mt_master_tarif where kode_bagian='."'030901'".' and referensi='.$_POST['jenis_bedah'].')'
		;
        $query = "select  a.kode_tarif, a.kode_tindakan, a.nama_tarif, c.nama_tarif as tingkat_operasi
					from mt_master_tarif a
					left join mt_master_tarif_detail b on b.kode_tarif=a.kode_tarif
					left join mt_master_tarif c on c.kode_tarif=a.referensi
					where  a.tingkatan=5 and (a.kode_bagian="."'030901'"." or a.kode_bagian=0) and a.nama_tarif like '%".$_POST['keyword']."%' ".$where_str." group by a.kode_tarif, a.kode_tindakan, a.nama_tarif, a.is_old, c.nama_tarif order by a.is_old asc,a.nama_tarif asc";

		$query =  $this->db->query("SELECT nama_tarif , kode_tarif  FROM mt_master_tarif WHERE kode_bagian IN ('030901') AND tingkatan = 5 AND UPPER(nama_tarif) LIKE '%".trim($key)."%'  ORDER BY kode_tarif");

        return $query->result();
	}

	public function getTindakanFisioByBagianAutoComplete()
	{
		$where_str = ($_POST['kode_perusahaan']==120) ? 'and nama_tarif like '."'%BPJS%'".'' : 'and nama_tarif not like '."'%BPJS%'".'' ;

        $query = "select a.kode_tarif, a.kode_tindakan, a.nama_tarif, b.kode_master_tarif_detail,b.kode_tarif,b.kode_klas,b.bill_rs, b.bill_dr1, b.bill_dr2, b.bill_dr3, b.kamar_tindakan, b.bhp, b.alat_rs, b.pendapatan_rs, b.revisi_ke, b.total, b.revisi_ke
					from mt_master_tarif a
					left join mt_master_tarif_detail b on b.kode_tarif=a.kode_tarif
					where  a.tingkatan=5 and (a.kode_bagian=".$_POST['kode_bag']." or a.kode_bagian=0) and nama_tarif like '%".$_POST['keyword']."%' and b.kode_klas=".$_POST['kode_klas']." 
					group by a.kode_tarif, a.kode_tindakan, a.nama_tarif, a.is_old, b.kode_master_tarif_detail,b.kode_tarif,b.kode_klas,b.bill_rs, b.bill_dr1, b.bill_dr2, b.bill_dr3, b.kamar_tindakan, b.bhp, b.alat_rs, b.pendapatan_rs, b.revisi_ke, b.total, b.revisi_ke
					having b.revisi_ke = (SELECT MAX(t2.revisi_ke) FROM mt_master_tarif_detail t2 WHERE a.kode_tarif=t2.kode_tarif AND b.kode_klas=t2.kode_klas ) 
					order by a.is_old asc,a.nama_tarif asc, b.revisi_ke desc";
					//echo $query;exit;
        $exc = $this->db->query($query)->result();

		$arrResult = [];
		foreach ($exc as $key => $value) {
			$arrResult[] = $value->kode_tarif.' : '.$value->nama_tarif.' ('.$value->kode_tindakan.') (IDR '.number_format($value->total).')';
		}
		echo json_encode($arrResult);
		
		
	}

	public function getTindakanRIByBagianAutoComplete()
	{
        $where_str = ($_POST['kode_perusahaan']==120) ? ($_POST['kode_klas']==1 || $_POST['kode_klas']==2)?'and nama_tarif not like '."'%BPJS%'".' and b.kode_klas= '.$_POST['kode_klas'].' ':'and nama_tarif like '."'%BPJS%'".' and b.kode_klas= '.$_POST['kode_klas'].' ' : 'and nama_tarif not like '."'%BPJS%'".' and b.kode_klas= '.$_POST['kode_klas'].' ' ;

        $query = "select  a.kode_tarif, a.kode_tindakan, a.nama_tarif
					from mt_master_tarif a
					left join mt_master_tarif_detail b on b.kode_tarif=a.kode_tarif
					where  a.tingkatan=5 and a.kode_bagian like '03%' and (kode_bagian <> '030501') AND (kode_bagian <> '030901') and a.kode_bagian in ('030001', '031001') and a.nama_tarif like '%".$_POST['keyword']."%' and a.is_active!= 'N' group by a.kode_tarif, a.kode_tindakan, a.nama_tarif order by a.nama_tarif ";
		$exc = $this->db->query($query)->result();
		// print_r($this->db->last_query());die;
		$arrResult = [];
		foreach ($exc as $key => $value) {
			$arrResult[] = $value->kode_tarif.' : '.$value->nama_tarif.' ('.$value->kode_tindakan.')';
		}
		echo json_encode($arrResult);
		
	}

	public function getBarangAutoComplete()
	{
		
        $query = "select a.* , b.* from mt_depo_stok as a, mt_barang as b where a.kode_brg=b.kode_brg AND (b.nama_brg LIKE '%". 
				$_POST['keyword']. "%') and a.kode_bagian='".$_POST['kode_bagian']."' order by b.nama_brg ASC";
					
        $exc = $this->db->query($query)->result();

		$arrResult = [];
		foreach ($exc as $key => $value) {
			$arrResult[] = $value->kode_brg.' : '.$value->nama_brg.' ';
		}
		echo json_encode($arrResult);
		
		
	}

	public function getDetailTarif()
	{
		$this->load->library('tarif');
		$tarifAktif = $this->tarif->getTarifAktif($_GET['kode'], $_GET['klas']);
		$exc = $tarifAktif->result();
		
    	$html = '';
    	$html .= '';
    	$html .= '<p style="padding: 8px 0px 0px"><b>';
    	$html .= $exc[0]->kode_tarif.' - '.strtoupper($exc[0]->nama_tarif);
    	$html .= isset($exc[0]->tingkat)?' | <span style="color: blue">'.$exc[0]->tingkat.'</span>':'';
    	$html .= isset($exc[0]->tipe_operasi)?' | <span style="color: green">'.$exc[0]->tipe_operasi.'</span>':'';
    	$html .= isset($exc[0]->nama_klas)?' ('.$exc[0]->nama_klas.' )':'';
    	$html .= '</b></p>';
    	$html .= '<input type="hidden" name="kode_tarif" value="'.$exc[0]->kode_tarif.'">';
    	$html .= '<input type="hidden" name="jenis_tindakan" value="'.$exc[0]->jenis_tindakan.'">';
    	$html .= '<input type="hidden" name="nama_tindakan" value="'.$exc[0]->nama_tarif.'">';
    	//$html .= '<input type="hidden" name="kode_bagian" value="'.$exc[0]->kode_bagian.'">';
    	//$html .= '<input type="hidden" name="kode_klas" value="'.$_GET['klas'].'">';
    	$html .= '<input type="hidden" name="kode_master_tarif_detail" value="'.$exc[0]->kode_master_tarif_detail.'">';
    	$html .= '<table class="table table-bordered">';
    	$html .= '<thead>';
    	$html .= '<tr>';
    	$html .= '<th>&nbsp;</th>';
    	$html .= '<th>Bill dr1</th>';
    	$html .= '<th>Bill dr2</th>';
    	$html .= '<th>Bill dr3</th>';
    	$html .= '<th>Kamar Tindakan</th>';
    	$html .= '<th>BHP</th>';
    	$html .= '<th>Alkes</th>';
    	$html .= '<th>Alat RS</th>';
    	$html .= '<th>Pendapatan RS</th>';
    	$html .= '<th>Total Tarif</th>';
    	$html .= '<th>Revisi ke-</th>';
    	$html .= '</tr>';
    	$html .= '</thead>';
    	foreach ($exc as $key => $value) {
			if(in_array($key, array(0,1) )) :
				$bill_rs = isset($value->bill_rs)?$value->bill_rs:0;
				$bill_dr1 = isset($value->bill_dr1)?$value->bill_dr1:0;
				$bill_dr2 = isset($value->bill_dr2)?$value->bill_dr2:0;
				$bill_dr3 = isset($value->bill_dr3)?$value->bill_dr3:0;
				$kamar_tindakan = isset($value->kamar_tindakan)?$value->kamar_tindakan:0;
				$bhp = isset($value->bhp)?$value->bhp:0;
				$alkes = isset($value->alkes)?$value->alkes:0;
				$alat_rs = isset($value->alat_rs)?$value->alat_rs:0;
				$pendapatan_rs = isset($value->pendapatan_rs)?$value->pendapatan_rs:0;
				$total = isset($value->total)?$value->total:0;
				$revisi_ke = isset($value->revisi_ke)?$value->revisi_ke:0;
	    		$checked = ($key==0)?'checked':'';
	    		/*$sign = ($key==0)?'<i class="fa fa-check-circle green"></i>':'<i class="fa fa-times-circle red"></i>';*/
	    		$html .= '<tr>';
		    	$html .= '<td align="center"><input type="radio" name="select_tarif" value="1" '.$checked.'></td>';
		    	/*$html .= '<td align="center">'.$sign.'</td>';*/
		    	$html .= '<td align="right">'.number_format($bill_dr1).'</td>';
		    	$html .= '<td align="right">'.number_format($bill_dr2).'</td>';
		    	$html .= '<td align="right">'.number_format($bill_dr3).'</td>';
		    	$html .= '<td align="right">'.number_format($kamar_tindakan).'</td>';
		    	$html .= '<td align="right">'.number_format($bhp).'</td>';
		    	$html .= '<td align="right">'.number_format($alkes).'</td>';
		    	$html .= '<td align="right">'.number_format($alat_rs).'</td>';
		    	$html .= '<td align="right">'.number_format($pendapatan_rs).'</td>';
		    	$html .= '<td align="right">'.number_format($total).'</td>';
		    	$html .= '<td align="center">'.$revisi_ke.'</td>';
		    	if($key==0){
			    	$html .= '<input type="hidden" name="total" value="'.round($total).'">';
			    	$html .= '<input type="hidden" name="bill_dr1" value="'.round($bill_dr1).'">';
			    	$html .= '<input type="hidden" name="bill_dr2" value="'.round($bill_dr2).'">';
			    	$html .= '<input type="hidden" name="bill_dr3" value="'.round($bill_dr3).'">';
			    	$html .= '<input type="hidden" name="kamar_tindakan" value="'.round($kamar_tindakan).'">';
			    	$html .= '<input type="hidden" name="bill_rs" value="'.round($bill_rs).'">';
			    	$html .= '<input type="hidden" name="bhp" value="'.round($bhp).'">';
			    	$html .= '<input type="hidden" name="pendapatan_rs" value="'.round($pendapatan_rs).'">';
			    	$html .= '<input type="hidden" name="alat_rs" value="'.round($alat_rs).'">';
			    	$html .= '<input type="hidden" name="alkes" value="'.round($alkes).'">';
		    	}

		    	$html .= '</tr>';
	    	endif;
    	}
    	$html .= '</table>';

    	echo json_encode( array('html' => $html, 'data' => $exc, 'jenis_bedah' => $exc[0]->kode_jenis_bedah) );
        
	}

	public function tindakanLainnya()
	{

		$default_kode_tindakan = ($_GET['tindakan_lainnya']=='tindakan_luar') ? 10 : 8 ;
    	$html = '<p><b><i class="fa fa-edit"></i> TINDAKAN LAINNYA </b></p>';
		$html .= '<input type="hidden" id="tindakan_lainnya" name="tindakan_lainnya" value="'.$_GET['tindakan_lainnya'].'">';
		$html .= '<div class="form-group">
						<label class="control-label col-sm-2" for="">Jenis Tindakan</label>
						<div class="col-sm-4">
						'.$this->master->custom_selection($params = array('table' => 'mt_jenis_tindakan', 'id' => 'kode_jenis_tindakan', 'name' => 'jenis_tindakan', 'where' => array()), $default_kode_tindakan , 'kode_jenis_tindakan', 'kode_jenis_tindakan', 'form-control', '', '').'
						</div>
					</div>';
    	$html .= '<div class="form-group">
					    <label class="control-label col-sm-2" for="">Nama Tindakan</label>
					    <div class="col-sm-6">
					       <input type="text"class="form-control" name="nama_tindakan" value="">
					    </div>
				  </div>';
		$html .= '<div class="form-group">
					    <label class="control-label col-sm-2" for="">Bill RS</label>
					    <div class="col-sm-2">
					       <input type="text"class="form-control" name="bill_rs" value="">
					    </div>
					    <label class="control-label col-sm-1" for="">Bill Dr 1</label>
					    <div class="col-sm-2">
					       <input type="text"class="form-control" name="bill_dr1" value="">
					    </div>
					    <label class="control-label col-sm-1" for="">Bill Dr 2</label>
					    <div class="col-sm-2">
					       <input type="text"class="form-control" name="bill_dr2" value="">
					    </div>
					     <button type="button" class="btn btn-xs btn-danger" id="btn_hide_tindakan_luar" onclick="backToDefault()"> <i class="fa fa-angle-double-left"></i> Sembunyikan </button>
				  </div>';

  //   	$html .= '<table class="table table-bordered">';
  //   	$html .= '<thead>';
		// $html .= '<tr>';
		// $html .= '<th>Nama Tindakan</th>';
		// $html .= '<th>Bill RS</th>';
  //   	$html .= '<th>Bill dr1</th>';
  //   	$html .= '<th>Bill dr2</th>';
  //   	$html .= '</tr>';
  //   	$html .= '</thead>';
  //   		$html .= '<tr>';
		// 	$html .= '<td><input type="text" name="nama_tindakan" value=""></td>';
		// 	$html .= '<td><input type="text" name="bill_rs" value=""></td>';
	 //    	$html .= '<td><input type="text" name="bill_dr1" value=""></td>';
	 //    	$html .= '<td><input type="text" name="bill_dr2" value=""></td>';    	
	 //    	$html .= '</tr>';
  //   	$html .= '</table>';

    	echo json_encode( array('status' => 200, 'html' => $html) );
        
	}

	public function getObatByBagianAutoComplete()
	{
		$this->db->from('mt_depo_stok a, mt_barang b');
		$this->db->where('a.kode_brg=b.kode_brg');

		/*Anggrek, Seruni, Flamboyan, dll*/
		if($_POST['bag'] == '030601' || $_POST['bag'] == '030201' || $_POST['bag'] == '030301' || $_POST['bag'] == '030701' ){
			$this->db->where('a.kode_bagian', '030201');
		/*Dahlia, Teratai, Melati*/
		}else if($_POST['bag'] == '030401' || $_POST['bag'] == '030801' || $_POST['bag'] == '031401' || $_POST['bag'] == '031301'){
			$this->db->where('a.kode_bagian', '030401');
		/*wijayakusuma*/
		}else if($_POST['bag'] == '030101'){
			$this->db->where('a.kode_bagian', '030101');
		/*ICU*/
		}else if($_POST['bag'] == '031001'){
			$this->db->where('a.kode_bagian', '031001');	

		/*VK*/
		}else if($_POST['bag'] == '030501' || $_POST['bag'] == '013201'){
			$this->db->where('a.kode_bagian', '030501');

		/*OK / Kamar Bedah*/
		}else if( in_array($_POST['bag'], array('030901','012801') )){
			$this->db->where_in('a.kode_bagian', array('030901','012801'));

		}else{
			$this->db->where('a.kode_bagian', $_POST['bag']);
		}

        $this->db->where('b.nama_brg like '."'%".$_POST['keyword']."%'".'');
        $exc = $this->db->get()->result();
		// echo $this->db->last_query();
		$arrResult = [];
		foreach ($exc as $key => $value) {
			$arrResult[] = $value->kode_brg.' : '.$value->nama_brg;
		}
		echo json_encode($arrResult);
		
		
	}

	public function getDetailObat()
	{
		$this->load->library('tarif');

		// stok umum
        $this->db->select('a.stok_akhir, b.kode_brg, b.nama_brg, b.satuan_kecil, b.satuan_besar, a.kode_bagian, c.harga_beli, b.flag_kjs, b.flag_medis, b.path_image');
        $this->db->from('tc_kartu_stok a, mt_barang b, mt_rekap_stok c');
        $this->db->where('a.kode_brg=b.kode_brg');
		$this->db->where('b.kode_brg=c.kode_brg');

		if( in_array($_GET['bag'], array('030901','012801')) ){
			$this->db->where('a.kode_bagian', '030901');
		}else{
			$this->db->where('a.kode_bagian', $_GET['bag']);
		}
        $this->db->where('a.kode_brg', $_GET['kode']);
        $this->db->order_by('a.id_kartu', 'DESC');
        $this->db->limit(1);
        $exc = $this->db->get()->result();
		// print_r($this->db->last_query());die;

		// stok cito
		$this->db->from('tc_kartu_stokcito a');
		$this->db->join('fr_pengadaan_cito b','a.kode_brg=b.kode_brg','left');
		$this->db->where('a.kode_brg', $_GET['kode']);
		$this->db->where('a.kode_bagian', '060101');
		$this->db->order_by('id_kartucito', 'DESC');
		$cito = $this->db->get()->row();
		// print_r($this->db->last_query());die;
    	$html = '';
    	$html .= '<input type="hidden" name="kode_brg" value="'.$exc[0]->kode_brg.'">';
		$html .= '<input type="hidden" name="nama_tindakan" value="'.$exc[0]->nama_brg.'">';
		$html .= '<input type="hidden" name="pl_satuan_kecil" value="'.$exc[0]->satuan_kecil.'">';
		$html .= '<input type="hidden" name="pl_harga_beli" value="'.(int)$exc[0]->harga_beli.'">';
		$html .= '<input type="hidden" name="pl_sisa_stok" value="'.(int)$exc[0]->stok_akhir.'">';

		if($_GET['type_layan']=='Ranap'){
			$html .= '<input type="hidden" name="kode_bagian_depo" value="'.$exc[0]->kode_bagian.'">';
		}else{
			$html .= '<input type="hidden" name="kode_bagian" value="'.$exc[0]->kode_bagian.'">';
		}

    	$html .= '<table class="table table-bordered">';
    	// $html .= '<thead>';
    	// $html .= '<tr>';
    	// $html .= '<th>Kode</th>';
    	// $html .= '<th>Nama Obat</th>';
    	// $html .= '<th>Jenis</th>';
    	// $html .= '<th>Satuan Kecil</th>';
    	// $html .= '<th>Satuan Besar</th>';
    	// $html .= '<th>Sisa Stok</th>';
    	// $html .= '<th>Harga Satuan</th>';
    	// $html .= '</tr>';
    	// $html .= '</thead>';
    	//foreach ($exc as $key => $value) {
    		$flag_medis = ($exc[0]->flag_medis==1) ? 'Alkes' : 'Obat' ;
			$html .= '<tr style="background-color: #31ecdb30">';
			$link_image = ( $exc[0]->path_image != NULL ) ? PATH_IMG_MST_BRG.$exc[0]->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ;
	    	$html .= '<td width="100px" rowspan="5" valign="middle"><img src="'.$link_image.'" width="100%"> </td>';
			$html .= '</tr>';
			
			$html .= '<tr style="background-color: #31ecdb30">';
	    	$html .= '<td valign="top" colspan="2"><b>'.$exc[0]->kode_brg.'</b><br> '.$flag_medis.' - '.$exc[0]->nama_brg.' ('.$exc[0]->satuan_kecil.')</td>';
			$html .= '</tr>';
			
			$html .= '<tr style="background-color: #31ecdb30">';
			$html .= '<td align="left" valign="top">Stok Gudang : '.$exc[0]->stok_akhir.' ('.$exc[0]->satuan_kecil.')</td>';
			
			// stok cito
			$stok_cito = isset($cito->stok_akhir)?$cito->stok_akhir:0;
			$harga_satuan_cito = isset($cito->stok_akhir)?$this->tarif->_hitungBPAKOCurrent( $cito->harga_beli, $_GET['kode_kelompok'], $exc[0]->flag_kjs, $exc[0]->kode_brg, 2000 ):0;

			$html .= '<td align="left" valign="top">Stok Cito : '.$stok_cito.' ('.$exc[0]->satuan_kecil.')</td>';
			$html .= '</tr>';
			
			$html .= '<tr style="background-color: #31ecdb30">';
    		/*get total tarif barang*/
    		$harga_satuan = $this->tarif->_hitungBPAKOCurrent( $exc[0]->harga_beli, $_GET['kode_kelompok'], $exc[0]->flag_kjs, $exc[0]->kode_brg, 2000 );
			$html .= '<td valign="top" align="left"><span>Harga Umum : </span> '.number_format($harga_satuan).',- <input type="hidden" name="pl_harga_satuan" value="'.(float)$harga_satuan.'"> </td>';
			
			$html .= '<td valign="top" align="left"><span>Harga Cito :</span> '.number_format($harga_satuan_cito).',- <input type="hidden" name="pl_harga_satuan_cito" value="'.$harga_satuan_cito.'"> </td>';

	    	$html .= '</tr>';
			$html .= '<tr style="background-color: #31ecdb30">';
				$html .= '<td valign="top" align="left" colspan="2"><span>Harga BPJS :</span> '.number_format($exc[0]->harga_beli,2).',- <input type="hidden" name="pl_harga_satuan_bpjs" value="'.(float)$exc[0]->harga_beli.'"> </td>';
	    	$html .= '</tr>';
    	//}
    	$html .= '</table>';

    	echo json_encode( array('html' => $html, 'sisa_stok' => $exc[0]->stok_akhir, 'satuan_kecil' => $exc[0]->satuan_kecil) );
        
	}

	public function getSubGolongan($kode_gol='')
	{
		$table = ($_GET['flag'] == 'non_medis') ? 'mt_sub_golongan_nm' : 'mt_sub_golongan' ;
		$this->db->from($table);
		$this->db->where('kode_sub_gol LIKE '."'%".$kode_gol."%'".' ');
        $exc = $this->db->get();
        echo json_encode($exc->result());
	}

	public function getGenerik($kode_sub_gol='')
	{
		$this->db->from('mt_generik');
		$this->db->where('kode_generik LIKE '."'%".$kode_sub_gol."%'".' ');
        $exc = $this->db->get();
        echo json_encode($exc->result());
	}
	
	public function getDetailProfit($id_profit='')
	{
		$this->db->from('fr_mt_profit_margin');
		$this->db->where('id_profit', $id_profit);
        $exc = $this->db->get();
        echo json_encode($exc->row());
	}

	public function getKodeBrg( $kode='')
	{
		// define table
        $table = ($_GET['flag'] == 'medis' ) ? 'mt_barang' : 'mt_barang_nm' ;
		// get last brg by kode_generik
		if( $_GET['flag'] == 'medis' ){
			$this->db->where('kode_generik', $kode);
			$this->db->order_by('id_obat', 'DESC');
		}else{
			$this->db->order_by('id_mt_barang_nm', 'DESC');
			$this->db->where('kode_brg LIKE '."'%".$kode."%'".' ');
		}

		$query = $this->db->get( $table )->row();
		
		$count = substr($query->kode_brg,6);
		$lastnum = $count + 1;
		$nextnum = ( strlen($lastnum) == 1 ) ?  "0".$lastnum : $lastnum ;
		$kode_brg = $kode.$nextnum;

		// cek kode brg existing
		$brg = $this->db->get_where($table, array('kode_brg' => $kode_brg) )->row();
		if( empty($brg) ){
			$new_kode_brg = $kode_brg;
		}else{
			$count = substr($brg->kode_brg,6);
			$lastnum = $count + 1;
			$nextnum = ( strlen($lastnum) == 1 ) ?  "0".$lastnum : $lastnum ;
			$new_kode_brg = $kode.$nextnum;
		}

        echo json_encode( array('kode' => $new_kode_brg) );
	}

	public function search_pasien_rj(){
		// search kunjungan pasien
		$this->db->select('a.no_kunjungan, a.tgl_masuk, a.no_mr, c.nama_pasien, d.nama_bagian, a.no_registrasi, b.kode_dokter, a.kode_bagian_tujuan, b.kode_kelompok, b.kode_perusahaan, total_pesan.jml_pesan, total_pesan.kode_pesan_resep ');
		$this->db->from('tc_kunjungan a');
		$this->db->join('tc_registrasi b ', 'b.no_registrasi=a.no_registrasi' ,'left');
		$this->db->join('mt_master_pasien c ', 'c.no_mr=b.no_mr' ,'left');
		$this->db->join('mt_bagian d ', 'd.kode_bagian=a.kode_bagian_tujuan' ,'left');
		$this->db->join('(select no_kunjungan, kode_pesan_resep, COUNT(kode_pesan_resep) as jml_pesan from fr_listpesanan_v group by no_kunjungan, kode_pesan_resep) as total_pesan', 'total_pesan.no_kunjungan=a.no_kunjungan' ,'left');

		if( isset($_GET['search_by']) AND $_GET['search_by'] != '' AND isset($_GET['keyword']) AND $_GET['keyword'] != '' ){
			if($_GET['search_by']=='c.nama_pasien'){
				$this->db->like( $_GET['search_by'], $_GET['keyword']);
			}else{
				$this->db->where( $_GET['search_by'], $_GET['keyword']);
			}
		}

		if( isset($_GET['tgl_pelayanan']) AND $_GET['tgl_pelayanan'] != '' ){
			$this->db->where("convert(varchar,a.tgl_masuk,23) = '".$_GET['tgl_pelayanan']."'");
		}else{
			$this->db->where('MONTH(a.tgl_masuk)', date('m') );
		}

		$result = $this->db->get()->result();
		$data['result'] = $result;
		$view = $this->load->view('Templates/templates/search_result_pasien', $data, true);

		echo json_encode(array('html' => $view));
		
	}

	public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
	}
	
	public function getItemBarang()
	{
		// print_r($_POST);die;
		$table = ($_POST['flag']=='non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
		$join = ($_POST['flag']=='non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;

		$this->db->from($table.' as a');
		$this->db->join($join.' as b', 'b.kode_brg=a.kode_brg' , 'left');
		$this->db->like('a.kode_brg', $_POST['keyword']);
		$this->db->or_like('a.nama_brg', $_POST['keyword']);
		$this->db->where('a.is_active', 1);
		$result = $this->db->get()->result();

		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->kode_brg.' : '.$value->nama_brg;
		}
		echo json_encode($arrResult);
	}

	public function getItemBarangRetur()
	{
		// define table
		$mt_barang = ($_POST['flag']=='non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
		$mt_rekap_stok = ($_POST['flag']=='non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;
		$mt_depo_stok = ($_POST['flag']=='non_medis') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
		$tc_penerimaan = ($_POST['flag']=='non_medis') ? 'tc_penerimaan_barang_nm' : 'tc_penerimaan_barang' ;
		$tc_permintaan_inst = ($_POST['flag']=='non_medis') ? 'tc_permintaan_inst_nm' : 'tc_permintaan_inst' ;

		$this->db->select('b.nama_brg, a.kode_brg, b.satuan_kecil');
		$this->_queryGetReturBrg($_POST['jenis_retur'], $_POST['flag'], $_POST['unit']);
		$this->db->join($mt_barang.' as b', 'b.kode_brg=a.kode_brg' , 'left');
		$this->db->join($mt_rekap_stok.' as c', 'c.kode_brg=a.kode_brg' , 'left');
		$this->db->where('(a.kode_brg LIKE '."'%".$_POST['keyword']."%'".' OR b.nama_brg LIKE '."'%".$_POST['keyword']."%'".')');
		$this->db->group_by('b.nama_brg, a.kode_brg, b.satuan_kecil');
		$result = $this->db->get()->result();
		// print_r($this->db->last_query());die;

		$arrResult = [];
		foreach ($result as $key => $value) {
			if($_POST['jenis_retur']=='lainnya'){
				$arrResult[] = '<b>'.$value->kode_brg.'</b><br> '.$value->nama_brg.' ('.$value->qty.') <br> '.$value->qty.'';
			}else{
				$arrResult[] = '<b>'.$value->kode_brg.'</b> <br> '.$value->nama_brg.' ('.$value->qty.' '.$value->satuan_kecil.')';
			}
		}
		echo json_encode($arrResult);
	}

	public function _queryGetReturBrg($jenis_retur, $flag, $unit){

		$mt_barang = ($flag=='non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
		$mt_rekap_stok = ($flag=='non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;
		$mt_depo_stok = ($flag=='non_medis') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
		$tc_penerimaan = ($flag=='non_medis') ? 'tc_penerimaan_barang_nm' : 'tc_penerimaan_barang' ;
		$tc_permintaan_inst = ($flag=='non_medis') ? 'tc_permintaan_inst_nm' : 'tc_permintaan_inst' ;

		// jenis retur dari penerimaan barang
		if($jenis_retur == 'penerimaan_brg' ){
			$this->db->select('a.kode_brg as kode, z.nama_brg, a.jml_sat_kcl as qty');
			$this->db->from($mt_rekap_stok.' as a');
			$this->db->join($mt_barang.' as z', 'a.kode_brg=z.kode_brg' , 'left');
			$this->db->group_by('a.kode_brg, z.nama_brg, a.jml_sat_kcl');
			$this->db->order_by('z.nama_brg', 'ASC');
		}
		// retur berdasarkan pengiriman unit
		if($jenis_retur == 'pengiriman_brg_unit' ){
			$this->db->select('z.nomor_permintaan as kode, z.tgl_permintaan as tgl, a.jumlah_permintaan as qty');
			$this->db->from($tc_permintaan_inst.' as z');
			$this->db->join($tc_permintaan_inst.'_det as a', 'a.id_tc_permintaan_inst=z.id_tc_permintaan_inst' , 'left');
			$this->db->where('z.kode_bagian_minta', $unit);
			$this->db->group_by('z.nomor_permintaan, a.id_tc_permintaan_inst, z.tgl_permintaan, jumlah_permintaan');
			$this->db->order_by('a.id_tc_permintaan_inst', 'DESC');
		}
		// retur berdasarkan lainnya
		if($jenis_retur == 'lainnya' ){
			$this->db->select('a.jml_sat_kcl as qty');
			$this->db->from($mt_depo_stok.' as a');
			$this->db->where('a.kode_bagian', $_POST['unit']);
			$this->db->group_by('a.jml_sat_kcl, a.kode_depo_stok');
			$this->db->order_by('a.kode_depo_stok', 'DESC');
		}

	}

	public function getItemBarangDetailRetur()
	{
		$table = ($_GET['flag']=='non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
		$join = ($_GET['flag']=='non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;
		$nama_gudang = ($_GET['flag']=='non_medis') ? 'Gudang Non Medis' : 'Gudang Medis' ;
		$mt_barang = ($_GET['flag'] == 'non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
		$mt_rekap_stok = ($_GET['flag'] == 'non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;
		$mt_depo_stok = ($_GET['flag'] == 'non_medis') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
		$tc_penerimaan = ($_GET['flag'] == 'non_medis') ? 'tc_penerimaan_barang_nm' : 'tc_penerimaan_barang' ;
		$tc_permintaan_inst = ($_GET['flag'] == 'non_medis') ? 'tc_permintaan_inst_nm' : 'tc_permintaan_inst' ;

		$this->db->from($table.' as a');
		$this->db->join($join.' as b', 'b.kode_brg=a.kode_brg' , 'left');
		$this->db->where('a.kode_brg', $_GET['kode_brg']);
		$result = $this->db->get()->row();
		$html = '';
		$stok_akhir = ($result->jml_sat_kcl <= 0) ? '<span style="color: red; font-weight: bold">'.$result->jml_sat_kcl.'</span>' : '<span style="color: green; font-weight: bold">'.$result->jml_sat_kcl.'</span>' ;
		$warning_stok = ($result->jml_sat_kcl <= 0) ? '| <span style="color: red;" class="blink_me"><b>Stok habis !</b></span>' : '' ;
		$link_image = ( $result->path_image != NULL ) ? PATH_IMG_MST_BRG.$result->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ;
		if( $_GET['retur'] == 'lainnya' ){
			$html .= '<div class="widget-box">
						<div class="widget-body" style="background: #edf3f4;">
							<div class="widget-main">
								<b><span style="font-size: 13px">'.$result->kode_brg.' - '.$result->nama_brg.'</span></b><br>
								<table width="100%">
									<tr>
										<td style="text-align: right">
											<div class="alert alert-warning center" style="width: 150px; " id="div_retur_qty">
												<strong style="font-size: 14px"><span id="retur_qty_text">'.$_GET['qty'].'</span> '.$result->satuan_kecil.'</strong><br>
												<span id="unit_name">Unit</span>
											</div>
										</td>
										<td width="150px" style="text-align: center;">
											<i class="fa fa-sign-out bigger-250"></i>
										</td>
										<td>
											<div class="alert alert-success center" style="width: 150px; ">
												<strong style="font-size: 14px">'.$stok_akhir.' '.$result->satuan_kecil.'</strong><br>
												'.strtoupper($nama_gudang).'
											</div>
										</td>
										
									</tr>
								</table>
							</div>
						</div>
					  </div>';
		}

		if( $_GET['retur'] == 'penerimaan_brg' ){
			// cek penerimaan barang
			$this->db->select('a.kode_detail_penerimaan_barang, a.kode_penerimaan as kode, tgl_penerimaan as tgl, a.jumlah_kirim_decimal as qty, a.content, b.satuan_besar');
			$this->db->from($tc_penerimaan.'_detail as a');
			$this->db->join($tc_penerimaan.' as z', 'a.id_penerimaan=z.id_penerimaan' , 'left');
			$this->db->join($table.' as b', 'b.kode_brg=a.kode_brg' , 'left');
			$this->db->where('a.kode_brg', $_GET['kode_brg']);
			$this->db->where('a.jumlah_kirim > 0');
			$this->db->limit(3);
			$this->db->group_by('a.kode_detail_penerimaan_barang, a.kode_penerimaan, a.id_penerimaan, tgl_penerimaan, a.jumlah_kirim_decimal, a.content, b.satuan_besar');
			$this->db->order_by('z.tgl_penerimaan', 'DESC');
			$result = $this->db->get()->result();
			$html .= '<strong style="color: blue">DATA PENERIMAAN BARANG</strong><br>';
			$html .= '<table class="table">';
			$html .= '<tr>';
			$html .= '<th>No</th>';
			$html .= '<th>Kode Penerimaan</th>';
			$html .= '<th>Tanggal</th>';
			$html .= '<th class="center">Jumlah Diterima</th>';
			$html .= '<th class="center">Rasio</th>';
			$html .= '<th class="center">#</th>';
			$html .= '</tr>';
			$no = 0;
			foreach($result as $row_ress){
				$jml_retur = $row_ress->qty * $row_ress->content;
				$no++;
				$html .= '<tr>';
				$html .= '<td>'.$no.'</td>';
				$html .= '<td>'.$row_ress->kode.'</td>';
				$html .= '<td>'.$this->tanggal->formatDate($row_ress->tgl).'</td>';
				$html .= '<td class="center">'.$row_ress->qty.' '.$row_ress->satuan_besar.'</td>';
				$html .= '<td class="center">'.$row_ress->content.'</td>';
				$html .= '<td class="center"><a class="btn btn-xs btn-inverse" onclick="click_select_item('."'".$row_ress->kode_detail_penerimaan_barang."'".', '.$jml_retur.')"><i class="fa fa-sign-out"></i></a></td>';
				$html .= '</tr>';
			}
			$html .= '</table>';
		}
		

		echo json_encode( array('data' => $result, 'html' => $html ) );
	}

	
	public function getItemBarangDetail()
	{
		$table = ($_GET['flag']=='non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
		$join = ($_GET['flag']=='non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;

		$this->db->from($table.' as a');
		$this->db->join($join.' as b', 'b.kode_brg=a.kode_brg' , 'left');
		$this->db->where('a.kode_brg', $_GET['kode_brg']);
		$result = $this->db->get()->row();
		$html = '';
		$stok_akhir = ($result->jml_sat_kcl <= 0) ? '<span style="color: red; font-weight: bold">'.$result->jml_sat_kcl.'</span>' : '<span style="color: green; font-weight: bold">'.$result->jml_sat_kcl.'</span>' ;
		$warning_stok = ($result->jml_sat_kcl <= 0) ? '| <span style="color: red;" class="blink_me"><b>Stok habis !</b></span>' : '' ;
		$link_image = ( $result->path_image != NULL ) ? PATH_IMG_MST_BRG.$result->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ;
		$html .= '<div class="widget-box">
                    <div class="widget-body" style="background: #edf3f4;">
                      <div class="widget-main">
                          <b><span style="font-size: 13px">'.$result->kode_brg.' - '.$result->nama_brg.'</span></b><br>
                          Sisa stok '.$stok_akhir.' '.$result->satuan_kecil.' | Harga satuan '.number_format($result->harga_beli).',- '.$warning_stok.'
                      </div>
                    </div>
				  </div>
				  <div>
					<label class="label label-xs label-primary">Image </label> <br>
					<div class="center"><img src="'.base_url().$link_image.'" width="50%" style="   border: 1px solid darkgrey;padding: 3px;"></div>
				  </div>';

		echo json_encode( array('data' => $result, 'html' => $html ) );
	}

	public function getSubBagian($pelayanan='')
	{
		// $table = '($_GET['flag'] == 'non_medis') ? 'mt_sub_golongan_nm' : 'mt_sub_golongan'' ;
		$this->db->from('mt_bagian');
		$this->db->where('pelayanan LIKE '."'%".$pelayanan."%'".' ');
        $exc = $this->db->get();
        echo json_encode($exc->result());
	}

	public function get_riwayat_medis($no_mr){
		$result = $this->db->join('mt_bagian', 'mt_bagian.kode_bagian=th_riwayat_pasien.kode_bagian','inner')->order_by('no_kunjungan','DESC')->get_where('th_riwayat_pasien', array('no_mr' => $no_mr))->result();

		$transaksi = $this->db->select('kode_trans_pelayanan, no_registrasi, no_kunjungan, nama_tindakan, mt_jenis_tindakan.jenis_tindakan, kode_jenis_tindakan, tgl_transaksi, kode_tc_trans_kasir, nama_pegawai, jumlah_tebus')->join('mt_jenis_tindakan','mt_jenis_tindakan.kode_jenis_tindakan=tc_trans_pelayanan.jenis_tindakan','left')->join('mt_karyawan','mt_karyawan.kode_dokter=tc_trans_pelayanan.kode_dokter1','left')->join('fr_tc_far_detail','fr_tc_far_detail.kd_tr_resep=tc_trans_pelayanan.kd_tr_resep','left')->get_where('tc_trans_pelayanan', array('tc_trans_pelayanan.no_mr' => $no_mr, 'kode_jenis_tindakan' => 11) )->result();
		$getData = array();
		foreach ($transaksi as $key => $value) {
			$getData[$value->no_registrasi] [] = $value;
		}

		$data = array(
			'result' => $result,
			'obat' => $getData,
		);
		$html = $this->load->view('Templates/templates/view_riwayat_medis_sidebar', $data, true);
		
		echo json_encode( array('html' => $html) );
	}

}
