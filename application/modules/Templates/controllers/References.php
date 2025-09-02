<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class References extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	}
	/*here function used for this application*/

	public function getItemObatByKodeBrg($kode_brg)
    {
        
        $result = $this->db->where("kode_brg", $kode_brg)
                          ->get('mt_barang')->row();
        echo json_encode($result);
        
	}

	public function getNamaPasien()
    {
        
        $result = $this->db->select('no_mr, nama_pasien')->where("nama_pasien LIKE '%".$_POST['keyword']."%' ")
                          ->group_by('no_mr,nama_pasien')
                          ->order_by('nama_pasien', 'ASC')
                          ->get('mt_master_pasien')->result();
        $arrResult = [];
        foreach ($result as $key => $value) {
            $arrResult[] =  $value->no_mr.' : '.$value->nama_pasien;
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

	public function getKlinikFromJadwal($day='', $date='')
	{
		$where = ($date != date('Y-m-d')) ? "" : "and a.status_loket='on'";

		$query = "select a.jd_kode_spesialis as kode_bagian,c.nama_bagian, c.kode_poli_bpjs
					from tr_jadwal_dokter a
					left join mt_bagian c on c.kode_bagian=a.jd_kode_spesialis
					where a.jd_hari='".$day."' ".$where." or (kode_bagian = '012801' or kode_bagian='012901')
					group by  a.jd_kode_spesialis,c.nama_bagian, c.kode_poli_bpjs";
		$exc = $this->db->query($query);
		// echo $this->db->last_query(); die;
        echo json_encode($exc->result());
	}

	public function getDokterBySpesialisFromJadwal($kd_bagian='', $day='', $date='')
	{
		$where = ($date != date('Y-m-d')) ? "" : "and a.status_loket='on'";

		$query = "select a.jd_id,a.jd_kode_dokter as kode_dokter,b.nama_pegawai, CONVERT(char(10), jd_jam_mulai, 108) as jam_mulai, CONVERT(char(10), jd_jam_selesai, 108) as jam_selesai
					from tr_jadwal_dokter a
					left join mt_karyawan b on b.kode_dokter=a.jd_kode_dokter
					left join mt_bagian c on c.kode_bagian=a.jd_kode_spesialis
					where a.jd_kode_spesialis like '%".$kd_bagian."' and a.jd_hari='".$day."' ".$where."
					group by a.jd_id, a.jd_kode_dokter,b.nama_pegawai, jd_jam_mulai, jd_jam_selesai";
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

	public function getJadwalPraktek($kode_spesialis='', $kode_dokter='')
	{	
		$html = '';
			$query = "select a.jd_id, a.jd_kode_dokter,b.nama_pegawai as nama_dokter, a.jd_kode_spesialis, 
						c.nama_bagian as spesialis,a.jd_hari, a.jd_jam_mulai, a.jd_jam_selesai, a.jd_keterangan, a.jd_kuota, a.is_eksekutif
						from tr_jadwal_dokter a
						left join mt_karyawan b on b.kode_dokter=a.jd_kode_dokter
						left join mt_bagian c on c.kode_bagian=a.jd_kode_spesialis
						where a.jd_kode_spesialis=".$kode_spesialis." and a.jd_kode_dokter=".$kode_dokter."";

	        $query = $this->db->query($query);
			if($query->num_rows() > 0){
				$result = $query->result();
				$jadwal = [];
	        
				$array_color_day = array('green','red','purple','blue','black','orange','grey');
				shuffle($array_color_day);

				$html .= '<p><strong><i class="fa fa-list"></i> JADWAL PRAKTEK DOKTER</strong></p>';
				
				foreach ($result as $key => $value) {
					$time = $this->tanggal->formatTime($value->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($value->jd_jam_selesai);
					$jadwal[] = array('day' => $value->jd_hari , 'time' => $time);
					$is_eksekutif = ($value->is_eksekutif == 1)?'<i class="fa fa-star orange bigger-120"></i> ':'';
					$html .= '<a href="#"  onclick="detailJadwalPraktek('.$value->jd_id.')"><div class="infobox infobox-'.array_shift($array_color_day).' infobox-small infobox-dark">
									<div class="infobox-data">
										<div class="infobox-content">'.$is_eksekutif.''.$value->jd_hari.'</div>
										<div class="infobox-content">'.$time.'</div>
									</div>
								</div></a>';
				}
			$html .= '<br><small>* Silahkan pilih jadwal dokter praktek </small>';
			}else{
				$html .= '<span class="red bold">Jadwal dokter tidak ditemukan</span>';
			}
	        
		echo json_encode(array('html' => $html));

	}

	public function getDetailJadwalPraktek($jd_id)
	{
		$query = "select a.*, a.jd_kode_dokter as kode_dokter,b.nama_pegawai, c.nama_bagian
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

                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%); color: white">Nama Dokter</th>
                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%); color: white">Hari</th>
                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%); color: white">Jam Praktek</th>
                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%); color: white">Kuota</th>
                        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%); color: white">Keterangan</th>

                      </thead>

                      <tbody>

                        <td>'.ucwords($exc->nama_pegawai).'</td>
                        <td>'.$exc->jd_hari.'</td>
                        <td>'.$time.'</td>
                        <td>Kuota '.$quota_dokter.'</td>
                        <td>'.$exc->jd_keterangan.'</td>

                      </tbody>

                    </table>';

        /*$html .= '<address>
					<strong>'.ucwords($exc->nama_pegawai).'</strong><br>
					Hari <b>'.$exc->jd_hari.'</b><br>
					Jam Praktek <b>'.$time.'</b><br>
					Kuota Pasien <b>'.$quota_dokter.'</b>
				</address>';*/

        echo json_encode(array('html' => $html, 'data' => $exc, 'day' => $exc->jd_hari, 'time' => $time, 'id' => $exc->jd_id, 'time_start' => $this->tanggal->formatTime($exc->jd_jam_mulai) ));
	}

	function cek_kuota($kode_dokter, $tgl_pesan){
		return $this->db->get_where('tc_pesanan', array('tgl_pesanan' => $tgl_pesan, 'kode_dokter' => $kode_dokter) )->num_rows();
	}

	function CheckSelectedDate(){
		// echo '<pre>'; print_r($_POST);die;
		/*define variable data*/
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
		/*get data from post*/
		$date = $_POST['date'];
		$kode_spesialis = $_POST['kode_spesialis'];
		$kode_dokter = $_POST['kode_dokter'];
		$jd_id = $_POST['jadwal_id'];

		/*get day from date*/
		$day = $this->tanggal->getHariFromDateSql($date);
		/*change to sql date*/
		$sqlDate = $this->tanggal->sqlDateFormStrip($date);
		/*check current date*/
		$selected_date = strtotime($date);
		/*get status date*/
		if($date ==  date('Y-m-d')){
			$status = 'success';
		}else{
			$status = ($selected_date < strtotime(date('Y-m-d')) ) ? 'expired' : 'success' ;
		}

		/*get master jadwal*/
		$jadwal = $this->db->get_where('tr_jadwal_dokter', array('jd_id' => $jd_id) )->row();
		$kuota_dr = $jadwal->jd_kuota;
		/*get kuota dokter*/
		$substr_kode_spesialis = substr($kode_spesialis, 1);
		/*get data from averin*/
		$row_data_perjanjian = $this->db->get_where('tc_pesanan', array('tgl_pesanan' => $date, 'no_poli' => $substr_kode_spesialis, 'kode_dokter' => $kode_dokter) )->num_rows();
		
		$row_data_registrasi = $this->db->get_where('tc_registrasi', array('tgl_jam_masuk' => $date, 'kode_bagian_masuk' => $substr_kode_spesialis, 'kode_dokter' => $kode_dokter) )->num_rows();

		/*get data from reg online*/
		// $regon = $this->db->get_where('regon_booking', array('regon_booking_tanggal_perjanjian' => $date, 'regon_booking_klinik' => $kode_spesialis, 'regon_booking_kode_dokter' => $kode_dokter) )->num_rows();
		
		/*terisi*/
		$terisi = $row_data_perjanjian + $row_data_registrasi;
		// echo '<pre>'; print_r($terisi);die;
		/*sisa kuota*/
		$kuota = $kuota_dr - $terisi;

		// cek cuti dokter
		if($status == 'success'){
			$cuti = $this->db->where('(CAST(from_tgl as DATE) <= '."'".$date."'".' AND CAST(to_tgl as DATE) >= '."'".$date."'".')')->get_where('tr_jadwal_cuti_dr', array('kode_dr' => $kode_dokter))->row();
			// echo '<pre>'; print_r($cuti);die;
			if(!empty($cuti)){
				$status = 'cuti';
				$kuota_dr = 0;
				$terisi = 0;
				$kuota = 0;
			}
		}

		// cek 31 hari pelayanan bpjs
		$jeniskunjungan = isset($_POST['jeniskunjungan']) ? $_POST['jeniskunjungan'] : 0;
		if($jeniskunjungan != 2){
			$last_visit = $this->Reg_pasien->cek_last_visit($_POST['no_mr'], $date, $kode_spesialis);
			$allow_visit = isset($last_visit['tgl_masuk']) ? date('Y-m-d', strtotime($last_visit['tgl_masuk']. '+ 31 days')) : '' ;
		}else{
			$last_visit = [
				'tgl_masuk' => '',
				'tgl_keluar' => '',
				'poli' => '',
				'dokter' => '',
				'range' => 0,
			]; 
			$allow_visit = $date;
		}
		

		// echo '<pre>'; print_r($allow_visit);
		// echo '<pre>'; print_r($last_visit);

		$return_data = array('day' => $day, 'status' => $status, 'kuota_dr' => $kuota_dr, 'terisi' => $terisi, 'sisa' => $kuota, 'last_visit_date' => isset($last_visit['tgl_masuk'])?$last_visit['tgl_masuk']:'','allow_visit_date' => $allow_visit, 'range_visit' => isset($last_visit['range'])?$last_visit['range']:0);
		// echo '<pre>'; print_r($return_data);die;


		echo json_encode($return_data);
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
			$arrResult[] = $value->kode_kelompok.' : '.strtoupper($value->nama_kelompok);
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

	public function getFaskes()
	{
		$query = $this->db->where("nama_faskes LIKE '%".$_POST['keyword']."%' ")
		->order_by('nama_faskes', 'ASC')
		->get('mst_faskes')->result();
		$arrResult = [];
		foreach ($query as $key => $value) {
			$arrResult[] = $value->kode_faskes.' : '.strtoupper($value->nama_faskes);
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

	public function getAllKaryawan()
	{
        
		$query = $this->db->where("nama_pegawai LIKE '%".$_POST['keyword']."%' ")
        				  ->order_by('nama_pegawai', 'ASC')
                          ->get('view_dt_pegawai');
		$arrResult = [];
		foreach ($query->result() as $key => $value) {
			$arrResult[] = $value->kepeg_id.' : '.strtoupper($value->nama_pegawai);
		}
		echo json_encode($arrResult);
		
		
	}

	public function getEmployeeById($kepeg_id)
	{
        
		$query = $this->db->where('kepeg_id', $kepeg_id)->get('view_dt_pegawai')->row();
		echo json_encode($query);
		
		
	}
	
	public function getAllDokterByKeyword($key='')
	{
        $query = $this->db->where("nama_pegawai LIKE '%".$key."%' ")->where("kode_dokter is not NULL")->where('is_active', 'Y')
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
					where a.kode_klas=".$kode_klas." and a.flag_cad = 0
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
		$this->db->where('a.tgl_keluar IS NULL');
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
		$date = ($tanggal=='')?date('Y-m-d'):$tanggal;
		$day = $this->tanggal->getHariFromDateSql($date);
		// echo $date;
		// echo $day; exit;

		/*existing*/
		// $log_kuota_perjanjian = $this->db->get_where('tc_pesanan', array('CAST(jam_pesanan as DATE) = ' => date('Y-m-d'), 'kode_dokter' => $kode_dokter, 'no_poli' => $kode_spesialis, 'tgl_masuk' => NULL) )->num_rows();
		
		// perjanjian	
		// $log_kuota_perjanjian = $this->db->get_where('log_kuota_dokter', array('tanggal' => $date, 'kode_dokter' => $kode_dokter, 'kode_spesialis' => $kode_spesialis, 'flag' => 'perjanjian') )->num_rows();
		// $sisa_kuota_perjanjian = $this->db->get_where('log_kuota_dokter', array('tanggal' => $date, 'kode_dokter' => $kode_dokter, 'kode_spesialis' => $kode_spesialis, 'flag' => 'perjanjian', 'status' => 1) )->num_rows();

		$log_kuota_perjanjian = $this->db->where('CAST(tgl_pesanan as DATE) = '."'".$date."'".'')->get_where('tc_pesanan', array('kode_dokter' => $kode_dokter, 'no_poli' => $kode_spesialis) )->num_rows();

		$sisa_kuota_perjanjian = $this->db->where('CAST(tgl_pesanan as DATE) = '."'".$date."'".'')->where('CAST(tgl_masuk as DATE) IS NOT NULL')->get_where('tc_pesanan', array('kode_dokter' => $kode_dokter, 'no_poli' => $kode_spesialis) )->num_rows();;

		$sisa_perjanjian = $log_kuota_perjanjian - $sisa_kuota_perjanjian;

		// terdaftar
		$log_kuota_current = $this->db->get_where('tc_registrasi', array('CAST(tgl_jam_masuk as DATE) = ' => $date, 'kode_dokter' => $kode_dokter, 'kode_bagian_masuk' => $kode_spesialis) )->num_rows();

		// mobile jkn
		$log_kuota_mjkn = $this->db->get_where('log_kuota_dokter', array('tanggal' => $date, 'kode_dokter' => $kode_dokter, 'kode_spesialis' => $kode_spesialis, 'flag' => 'mobile_jkn') )->num_rows();
		$sisa_kuota_mjkn = $this->db->get_where('log_kuota_dokter', array('tanggal' => $date, 'kode_dokter' => $kode_dokter, 'kode_spesialis' => $kode_spesialis, 'flag' => 'mobile_jkn', 'status' => 1) )->num_rows();
		$sisa_mjkn = $log_kuota_mjkn - $sisa_kuota_mjkn;


		// mesin antrian
		$mesin_antrian = $this->db->get_where('log_kuota_dokter', array('tanggal' => $date, 'kode_dokter' => $kode_dokter, 'kode_spesialis' => $kode_spesialis, 'flag' => 'mesin_antrian') )->num_rows();
		$sisa_kuota_antrian = $this->db->get_where('log_kuota_dokter', array('tanggal' => $date, 'kode_dokter' => $kode_dokter, 'kode_spesialis' => $kode_spesialis, 'flag' => 'mesin_antrian', 'status' => 1) )->num_rows();
		$sisa_antrian = $mesin_antrian - $sisa_kuota_antrian;


        /*kuota dokter*/
        $kuota_dokter = $this->db->select('tr_jadwal_dokter.*, kode_dokter_bpjs, kode_poli_bpjs')->join('mt_karyawan', 'mt_karyawan.kode_dokter = tr_jadwal_dokter.jd_kode_dokter', 'left')->join('mt_bagian', 'mt_bagian.kode_bagian = tr_jadwal_dokter.jd_kode_spesialis')->get_where('tr_jadwal_dokter', array('jd_hari' => $day, 'jd_kode_dokter' => $kode_dokter, 'jd_kode_spesialis' => $kode_spesialis) )->row(); 
		// echo $this->db->last_query(); exit;
		$id = $kuota_dokter->jd_id; 
		$kuota_dr = $kuota_dokter->jd_kuota;
		$sisa = $kuota_dokter->jd_kuota - ($log_kuota_current + $sisa_mjkn);

		$data = array(
			'kuota' => $kuota_dr,
			'perjanjian_rj' => $log_kuota_perjanjian,
			'sisa_perjanjian_rj' => $sisa_perjanjian,
			'perjanjian_mjkn' => $log_kuota_mjkn,
			'sisa_mjkn' => $sisa_mjkn,
			'terdaftar' => $log_kuota_current,
			'antrian' => $mesin_antrian,
			'sisa_antrian' => $sisa_antrian,
			'sisa_kuota' => $sisa,
			'kode_dokter' => $kode_dokter,
			'kode_dokter_bpjs' => $kuota_dokter->kode_dokter_bpjs,
			'jam_praktek_mulai' => $this->tanggal->formatFullTime($kuota_dokter->jd_jam_mulai),
			'jam_praktek_selesai' => $this->tanggal->formatFullTime($kuota_dokter->jd_jam_selesai),
			'kode_poli_bpjs' => $kuota_dokter->kode_poli_bpjs,
			'kode_bagian' => $kode_spesialis,
			'tgl_registrasi' => $date,
		);
		$html = $this->load->view('templates/view_log_kuota_dr', $data, true);

		$message = ($sisa==0)?'<label class="label label-danger"><i class="fa fa-times-circle"></i> Maaf, Kuota sudah penuh !</label>':'<label class="label label-success"><i class="fa fa-check"></i> Kuota Terpenuhi</label>';

        echo json_encode(array('sisa_kuota' => $sisa, 'jd_id' => $id, 'message' => $html, 'data' => $data));
	}

	public function view_pasien_terdaftar_current(){
		$tgl_registrasi = isset($_GET['tgl_registrasi'])?$_GET['tgl_registrasi']:date('Y-m-d');
		$pasien_terdaftar = $this->db->select('tc_kunjungan.no_mr, nama_pasien, tgl_jam_poli, nama_perusahaan, no_antrian, tgl_keluar_poli, nama_pegawai as nama_dr, pl_tc_poli.status_batal, nama_bagian, umur')
			->join('tc_kunjungan', 'tc_kunjungan.no_kunjungan=pl_tc_poli.no_kunjungan', 'left')
			->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left')
			->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left')
			->join('mt_karyawan','mt_karyawan.kode_dokter=pl_tc_poli.kode_dokter','left')
			->join('mt_bagian','mt_bagian.kode_bagian=pl_tc_poli.kode_bagian','left')
			->order_by('no_antrian', 'ASC')
			->get_where('pl_tc_poli', array('CAST(tgl_jam_poli as DATE) = ' => $tgl_registrasi, 'pl_tc_poli.kode_dokter' => $_GET['kode_dokter'], 'pl_tc_poli.kode_bagian' => $_GET['kode_spesialis']) )->result();
		$data = array(
			'result' => $pasien_terdaftar,
		);

		// echo "<pre>";print_r($data);die;
		$this->load->view('templates/view_pasien_terdaftar', $data);
	}

	public function view_pasien_perjanjian(){
		$tgl_registrasi = isset($_GET['tgl_registrasi'])?$_GET['tgl_registrasi']:date('Y-m-d');
		$pasien_perjanjian = $this->db->get_where('tc_pesanan', array('CAST(jam_pesanan as DATE) = ' => $tgl_registrasi, 'kode_dokter' => $_GET['kode_dokter'], 'no_poli' => $_GET['kode_spesialis']) )->result();
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
		$query = "select  id_bayi, nama_bayi, mr_ibu, tgl_jam_lahir, jenis_kelamin
					from ri_bayi_lahir
					where (flag_lahir = 0 or flag_lahir is null) and nama_bayi <> '' group by id_bayi, nama_bayi, mr_ibu, tgl_jam_lahir, jenis_kelamin ";
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
		$explode = explode(";", $_POST['keyword']);
		// get max key
		$key = max(array_keys($explode));
		$keyword = $explode[$key];
        $query = "select  icd_10, nama_icd from mt_master_icd10 where nama_icd LIKE '%".$keyword."%' or icd_10 LIKE '%".$keyword."%' group by icd_10, nama_icd";
		
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


	public function getRakUnit($kode_bagian)
    {
        
        $result = $this->db->select('value, label')
							->where("flag","rak_medis")
							->where("is_active","Y")
							->where("reff_id",$kode_bagian)
                          ->order_by('label', 'ASC')
                          ->get('global_parameter')->result();
						  
        echo json_encode($result);
        
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
		if($result != false){
			foreach ($result as $key => $value) {
				$arrResult[] = $value->kode_dokter.' : '.$value->nama_pegawai;
			}
		}
		
		echo json_encode($arrResult);
		
	}

	public function getDokterByKeyword()
	{
		$query = "select a.kode_dokter, a.nama_pegawai
	 				from mt_dokter_v a
	 				where is_active = 'Y' AND a.nama_pegawai LIKE '%".$_POST['keyword']."%' and a.nama_pegawai is not NULL and a.nama_pegawai <> '' GROUP BY a.kode_dokter, a.nama_pegawai";
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
			$arrResult[] = $value->kode_bagian.' : '.strtoupper($value->nama_bagian);
		}
		echo json_encode($arrResult);
		
	}

	public function getSpesialis()
	{
        $query = "select a.kode_bagian, a.nama_bagian
					from mt_bagian a
					where a.validasi=100 AND a.nama_bagian LIKE '%".$_POST['keyword']."%' order by nama_bagian asc";
		$result = $this->db->query($query)->result();
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->kode_bagian.' : '.strtoupper($value->nama_bagian);
		}
		echo json_encode($arrResult);
		
	}

	public function getSelectSpesialis()
	{
        $query = "select a.kode_bagian, a.nama_bagian
					from mt_bagian a
					where a.validasi=100 order by nama_bagian asc";
		$result = $this->db->query($query)->result();
		echo json_encode($result);
		
	}

	public function getDokterByBagianByKeyword($key='',$bag='')
	{
		$this->db->select('a.kode_dokter, a.nama_pegawai');
		$this->db->from('mt_dokter_v a');
		$this->db->where("a.nama_pegawai LIKE '%".$key."%' and a.nama_pegawai is not NULL and a.nama_pegawai <> ''");
		$this->db->where("is_active", 'Y');
		$this->db->group_by('a.kode_dokter, a.nama_pegawai');

		if($bag > (int)'0' ){
			$this->db->where('a.kd_bagian', $bag);
		}
		
		$exc = $this->db->get();
		// echo $this->db->last_query();die;
		if($exc->num_rows() > 0){
			return $exc->result();
		}else{
			return false;
		}
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
		
		$this->db->select('a.kode_tarif, a.kode_tindakan, a.nama_tarif, c.nama_tarif as tingkat_operasi, a.new_tarif_2025 as label_tarif_baru');
		$this->db->select('REPLACE(nama_bagian, '."'Poliklinik Spesialis'".','."''".' ) as bagian');
		$this->db->from('mt_master_tarif a');
		$this->db->join('mt_master_tarif c','c.kode_tarif=a.referensi','LEFT');
		$this->db->join('mt_bagian d','d.kode_bagian=a.kode_bagian','LEFT');
		$this->db->where('a.tingkatan', 5);
		$this->db->where('a.is_active', 'Y');

		if($_POST['jenis_tarif'] == 120){
			$this->db->where("(a.nama_tarif LIKE '%".$_POST['keyword']."%' AND a.nama_tarif LIKE '%BPJS%') ");
		}else{
			$this->db->where("(a.nama_tarif LIKE '%".$_POST['keyword']."%' AND a.nama_tarif NOT LIKE '%BPJS%') ");
		}

		// if(isset($_POST['jenis_bedah']) && $_POST['jenis_bedah'] != ''){
		// 	$this->db->where('a.referensi', $_POST['jenis_bedah']);
		// }

		if(isset($_POST['show_all']) && $_POST['show_all'] == 1){
			// no filter
		}else{
			if(in_array($_POST['kode_bag'], array('030501', '031201'))){
				$this->db->where("(a.kode_bagian IN ('030501', '031201') or a.kode_bagian = 0)");
			}
			elseif(in_array($_POST['kode_bag'], array('013701', '011501', '011601', '011001'))){
				$this->db->where("(a.kode_bagian IN ('013701', '011501', '011601', '011001') or a.kode_bagian = 0)");
			}
			elseif(in_array($_POST['kode_bag'], array('030901'))){
				$this->db->where("(a.kode_bagian IN ('030901', '012801') OR a.kode_bagian = 0 OR a.referensi IN ( SELECT kode_tarif FROM mt_master_tarif WHERE kode_bagian IN ('030901','012801') AND referensi = '".$_POST['jenis_bedah']."' ) )");
			}
			else{
				$this->db->where("(a.kode_bagian = '".$_POST['kode_bag']."' or a.kode_bagian = 0)");
			}
		}

        // $query = "select  a.kode_tarif, a.kode_tindakan, a.nama_tarif, c.nama_tarif as tingkat_operasi
		// 			from mt_master_tarif a
		// 			left join mt_master_tarif_detail b on b.kode_tarif=a.kode_tarif
		// 			left join mt_master_tarif c on c.kode_tarif=a.referensi
		// 			where  a.tingkatan=5 and (".$where_kode_bag." or a.kode_bagian=0) and a.nama_tarif like '%".$_POST['keyword']."%' ".$where_str." and a.is_active = 'Y' group by a.kode_tarif, a.kode_tindakan, a.nama_tarif, a.is_old, c.nama_tarif order by a.is_old asc,a.nama_tarif asc";
		$exc = $this->db->get()->result();

		// print_r($this->db->last_query());exit;

		$arrResult = [];
		foreach ($exc as $key => $value) {
			$jenis_operasi = ($_POST['kode_bag']=='030901') ? ''.$value->tingkat_operasi.'' : '' ;
			$bagian = ($value->bagian == null)?'Global':$value->bagian;
			$label_new_tarif = ($value->label_tarif_baru != null)?'<span style="background: green; color:white; padding: 2px; font-size: 10px; border-radius: 5px">New</span>':'';
			$arrResult[] = $value->kode_tarif.' : '.$value->nama_tarif.' ('.$bagian.') '.$jenis_operasi.' '.$label_new_tarif.'';
		}
		echo json_encode($arrResult);
		
		
	}

	public function getTindakanByKunjungan()
	{
		// echo '<pre>'; print_r($_POST); die;

		// get bagian kunjungan
		$kunjungan = $this->db->select('kode_bagian_tujuan, kode_bagian_asal')->group_by('kode_bagian_tujuan, kode_bagian_asal')->get_where('tc_kunjungan', array('no_kunjungan' => $_POST['no_kunjungan']))->row();
		// rawat jalan
		if( substr($kunjungan->kode_bagian_tujuan, 1,2) == '01' ){
			$kode_klas = 16;
		}else{
			if( substr($kunjungan->kode_bagian_asal, 1,2) == '03'){
				$ri = $this->db->where("no_kunjungan = (select no_kunjungan from tc_kunjungan where no_registrasi = ".$_POST['no_registrasi']." and substr(kode_bagian_tujuan, 1,2) = '03')")->get('ri_tc_rawatinap')->row();
				$kode_klas = $ri->kelas_pas;
			}else{
				$kode_klas = 16;
			}
		}
		
		$this->db->select('a.kode_tarif, a.kode_tindakan, a.nama_tarif, c.nama_tarif as tingkat_operasi');
		$this->db->from('mt_master_tarif a');
		$this->db->join('mt_master_tarif_detail b', 'b.kode_tarif=a.kode_tarif', 'left');
		$this->db->join('mt_master_tarif c', 'c.kode_tarif=a.referensi', 'left');
		$this->db->where('a.tingkatan', '5');
		$this->db->where('a.is_active', 'Y');
		$this->db->like('a.nama_tarif', $_POST['keyword']);
		$this->db->group_by('a.kode_tarif, a.kode_tindakan, a.nama_tarif, a.is_old, c.nama_tarif');
		$this->db->order_by('a.is_old asc, a.nama_tarif asc');
		// $this->db->where('a.kode_bagian', $kunjungan->kode_bagian_tujuan);
		$query = $this->db->get()->result();
		// echo '<pre>'; print_r($this->db->last_query()); die;

		$arrResult = [];
		foreach ($query as $key => $value) {
			$jenis_operasi = $value->tingkat_operasi;
			$arrResult[] = $value->kode_tarif.' : '.$value->nama_tarif.' ('.$value->kode_tindakan.') '.$jenis_operasi.' : '.$kode_klas.'';
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
		// $where_str = ($_POST['kode_perusahaan']==120) ? 'and nama_tarif like '."'%BPJS%'".'' : 'and nama_tarif not like '."'%BPJS%'".'' ;

        $query = "select a.kode_tarif, a.kode_tindakan, a.nama_tarif, b.kode_master_tarif_detail,b.kode_tarif,b.kode_klas,b.bill_rs, b.bill_dr1, b.bill_dr2, b.bill_dr3, b.kamar_tindakan, b.bhp, b.alat_rs, b.pendapatan_rs, b.revisi_ke, b.total, b.revisi_ke
					from mt_master_tarif a
					left join mt_master_tarif_detail b on b.kode_tarif=a.kode_tarif
					where  a.tingkatan=5 and (a.kode_bagian='".$_POST['kode_bag']."') and nama_tarif like '%".$_POST['keyword']."%' and (b.kode_klas=".$_POST['kode_klas']." or b.kode_klas=0) 
					group by a.kode_tarif, a.kode_tindakan, a.nama_tarif, a.is_old, b.kode_master_tarif_detail,b.kode_tarif,b.kode_klas,b.bill_rs, b.bill_dr1, b.bill_dr2, b.bill_dr3, b.kamar_tindakan, b.bhp, b.alat_rs, b.pendapatan_rs, b.revisi_ke, b.total, b.revisi_ke
					having b.revisi_ke = (SELECT MAX(t2.revisi_ke) FROM mt_master_tarif_detail t2 WHERE a.kode_tarif=t2.kode_tarif AND b.kode_klas=t2.kode_klas ) 
					order by a.is_old asc,a.nama_tarif asc, b.revisi_ke desc";
					// echo $query;exit;
        $exc = $this->db->query($query)->result();

		$arrResult = [];
		foreach ($exc as $key => $value) {
			$arrResult[] = $value->kode_tarif.' : '.$value->nama_tarif.' : '.$value->kode_tindakan.' : IDR '.number_format($value->total).')';
		}
		echo json_encode($arrResult);
		
	}

	public function getTindakanRIByBagianAutoComplete()
	{
		// print_r($_POST);die;
        // $where_str = ($_POST['kode_perusahaan']==120) ? ($_POST['kode_klas']==1 || $_POST['kode_klas']==2)?'and nama_tarif not like '."'%BPJS%'".' and b.kode_klas= '.$_POST['kode_klas'].' ':'and nama_tarif like '."'%BPJS%'".' and b.kode_klas= '.$_POST['kode_klas'].' ' : 'and nama_tarif not like '."'%BPJS%'".' and b.kode_klas= '.$_POST['kode_klas'].' ' ;

        // $query = "select  a.kode_tarif, a.kode_tindakan, a.nama_tarif
		// 			from mt_master_tarif a
		// 			left join mt_master_tarif_detail b on b.kode_tarif=a.kode_tarif
		// 			where  a.tingkatan=5 and (a.kode_bagian like '03%' OR a.kode_bagian = 0) AND (kode_bagian <> '030901') and a.nama_tarif like '%".$_POST['keyword']."%' and a.is_active!= 'N' group by a.kode_tarif, a.kode_tindakan, a.nama_tarif order by a.nama_tarif ";
		 

		$this->db->select('a.kode_tarif, a.kode_tindakan, a.nama_tarif, c.nama_tarif as tingkat_operasi');
		$this->db->select('REPLACE(nama_bagian, '."'Poliklinik Spesialis'".','."''".' ) as bagian');
		$this->db->from('mt_master_tarif a');
		$this->db->join('mt_master_tarif c','c.kode_tarif=a.referensi','LEFT');
		$this->db->join('mt_bagian d','d.kode_bagian=a.kode_bagian','LEFT');
		$this->db->where('a.tingkatan', 5);
		$this->db->where('a.is_active', 'Y');
		if(isset($_POST['show_all']) && $_POST['show_all'] == 1){
			// no filter
		}else{
			$this->db->where("(a.kode_bagian like '03%' OR a.kode_bagian = 0) AND (a.kode_bagian <> '030901')");
		}
		$this->db->like('a.nama_tarif', $_POST['keyword']);
		$this->db->order_by('a.nama_tarif ASC');

		if($_POST['jenis_tarif'] == 120){
			$this->db->like('a.nama_tarif', 'BPJS');
		}else{
			$this->db->not_like('a.nama_tarif', 'BPJS');
		}

		$exc = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		$arrResult = [];
		foreach ($exc as $key => $value) {
			$bagian = ($value->bagian == null) ? "Global" : $value->bagian;
			$arrResult[] = $value->kode_tarif.' : '.$value->nama_tarif.' ('.$bagian.')';
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
		$tarifAktif = $this->tarif->getTarifAktif(trim($_GET['kode']), $_GET['klas']);
		// echo '<pre>'; print_r($this->db->last_query());die;
		$exc = $tarifAktif->result();
		$html = '';
		
		if(isset($exc[0]->kode_tarif)) {

			$html .= '<input type="hidden" name="kode_tarif" value="'.$exc[0]->kode_tarif.'">';
			$html .= '<input type="hidden" name="jenis_tindakan" value="'.$exc[0]->jenis_tindakan.'">';
			$html .= '<input type="hidden" name="nama_tindakan" value="'.$exc[0]->nama_tarif.'">';
			//$html .= '<input type="hidden" name="kode_bagian" value="'.$exc[0]->kode_bagian.'">';
			//$html .= '<input type="hidden" name="kode_klas" value="'.$_GET['klas'].'">';
			$html .= '<input type="hidden" name="kode_master_tarif_detail" value="'.$exc[0]->kode_master_tarif_detail.'">';
			
			if(isset($_GET['format']) && $_GET['format'] == 'formdr'){
				$label = '';
				$html .= '<br><span style="font-size: 16px; font-weight: bold">Biaya Pemeriksaan</span>';
				$label .= '<p style="padding: 8px 0px 0px"><b>';
				$label .= $exc[0]->kode_tarif.' - '.strtoupper($exc[0]->nama_tarif);
				$label .= isset($exc[0]->tingkat)?' | <span style="color: blue">'.$exc[0]->tingkat.'</span>':'';
				$label .= isset($exc[0]->tipe_operasi)?' | <span style="color: green">'.$exc[0]->tipe_operasi.'</span>':'';
				$label_new_tarif = ($exc[0]->label_tarif_baru != null)?'<span style="background: green; color:white; padding: 2px; font-size: 10px; border-radius: 5px">New</span>':'';
				$label .= isset($exc[0]->nama_klas)?' (Kelas '.str_replace('Kelas', '', $exc[0]->nama_klas).') '.$label_new_tarif.' ':'';
				$label .= '</b></p>';
				$html .= '<table class="table table-bordered" style="font-size: 14px">';
				$value = $exc[0];
				$total = isset($value->total)?$value->total:0;
				$revisi_ke = isset($value->revisi_ke)?$value->revisi_ke:0;
				$html .= '<tr style="background: #edf3f4;">';
				$html .= '<td align="left">'.$label.'</td>';
				$html .= '<td align="right" style="vertical-align: middle"><b>'.number_format($total).'</b></td>';
				$html .= '<input type="hidden" name="total" value="'.round($total).'">';
				$html .= '</tr>';
				$html .= '</table>';
			}else{
				$html .= '';
				$html .= '<p style="padding: 8px 0px 0px"><b>';
				$html .= $exc[0]->kode_tarif.' - '.strtoupper($exc[0]->nama_tarif);
				$html .= isset($exc[0]->tingkat)?' | <span style="color: blue">'.$exc[0]->tingkat.'</span>':'';
				$html .= isset($exc[0]->tipe_operasi)?' | <span style="color: green">'.$exc[0]->tipe_operasi.'</span>':'';
				$label_new_tarif = ($exc[0]->label_tarif_baru != null)?'<span style="background: green; color:white; padding: 2px; font-size: 10px; border-radius: 5px">New</span>':'';
				$html .= isset($exc[0]->nama_klas)?' (Kelas '.str_replace('Kelas', '', $exc[0]->nama_klas).') '.$label_new_tarif.' ':'';
				$html .= '</b></p>';
				
				$html .= '<table class="table table-bordered">';
				$html .= '<thead>';
				$html .= '<tr>';
				$html .= '<th>&nbsp;</th>';
				$html .= '<th>Bill dr1</th>';
				$html .= '<th>Bill dr2</th>';
				$html .= '<th>Kamar Tindakan</th>';
				$html .= '<th>BHP</th>';
				$html .= '<th>Alkes/Alat RS</th>';
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
						// grouping as alat_rs
						$alkes = isset($value->alkes)?$value->alkes:0;
						$alat = isset($value->alat_rs)?$value->alat_rs:0;
						$alat_rs = $alkes + $alat;
						// grouping as pendapatan_rs
						$adm = isset($value->adm)?$value->adm:0;
						$pendapatan = isset($value->pendapatan_rs)?$value->pendapatan_rs:0;
						$pendapatan_rs = $adm + $pendapatan;

						$total = isset($value->total)?$value->total:0;
						$revisi_ke = isset($value->revisi_ke)?$value->revisi_ke:0;
						$checked = ($key==0)?'checked':'';
						/*$sign = ($key==0)?'<i class="fa fa-check-circle green"></i>':'<i class="fa fa-times-circle red"></i>';*/
						$html .= '<tr style="background: #edf3f4;">';
						$html .= '<td align="center"><input type="radio" name="select_tarif" value="1" '.$checked.'></td>';
						/*$html .= '<td align="center">'.$sign.'</td>';*/
						if($this->session->userdata('user')->user_id == 1){
							$html .= '<td align="right">'.number_format($bill_dr1).'</td>';
							$html .= '<td align="right">'.number_format($bill_dr2).'</td>';
							$html .= '<td align="right">'.number_format($kamar_tindakan).'</td>';
							$html .= '<td align="right">'.number_format($bhp).'</td>';
							$html .= '<td align="right">'.number_format($alat_rs).'</td>';
							$html .= '<td align="right">'.number_format($pendapatan_rs).'</td>';
						}else{
							$html .= '<td align="right">-</td>';
							$html .= '<td align="right">-</td>';
							$html .= '<td align="right">-</td>';
							$html .= '<td align="right">-</td>';
							$html .= '<td align="right">-</td>';
							$html .= '<td align="right">-</td>';
						}
						
						$html .= '<td align="right"><b>'.number_format($total).'</b></td>';
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
						}

						$html .= '</tr>';
					endif;
				}
				$html .= '</table>';
			}


		}else{
			$html .= '<span style="color: red; font-weight: bold">-Tidak ada data tarif ditemukan pada kelas tersebut.-</span>';
		}

    	echo json_encode( array('html' => $html, 'data' => isset($exc[0])?$exc:[], 'jenis_bedah' => isset($exc[0]->kode_jenis_bedah)?$exc[0]->kode_jenis_bedah:'') );
        
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
			$this->db->where('b.nama_brg like '."'%".$_POST['keyword']."%'".'');
		/*Dahlia, Teratai, Melati*/
		}else if($_POST['bag'] == '030401' || $_POST['bag'] == '030801' || $_POST['bag'] == '031401' || $_POST['bag'] == '031301'){
			$this->db->where('a.kode_bagian', '030401');
			$this->db->where('b.nama_brg like '."'%".$_POST['keyword']."%'".'');
		/*wijayakusuma*/
		}else if($_POST['bag'] == '030101'){
			$this->db->where('a.kode_bagian', '030101');
			$this->db->where('b.nama_brg like '."'%".$_POST['keyword']."%'".'');
		/*ICU*/
		}else if($_POST['bag'] == '031001'){
			$this->db->where('a.kode_bagian', '031001');	
			$this->db->where('b.nama_brg like '."'%".$_POST['keyword']."%'".'');
		/*VK*/
		}else if($_POST['bag'] == '030501' || $_POST['bag'] == '013201'){
			$this->db->where('a.kode_bagian', '030501');
			$this->db->where('b.nama_brg like '."'%".$_POST['keyword']."%'".'');

		/*OK / Kamar Bedah*/
		}else if( in_array($_POST['bag'], array('030901','012801') )){
			$this->db->where_in('a.kode_bagian', array('030901','012801'));
			$this->db->where('b.nama_brg like '."'%".$_POST['keyword']."%'".'');
		}else if( in_array($_POST['bag'], array('060101','060201') )){
			$this->db->where('b.nama_brg like '."'".$_POST['keyword']."%'".'');
			$this->db->where('a.kode_bagian', $_POST['bag']);
		}else{
			$this->db->where('b.nama_brg like '."'%".$_POST['keyword']."%'".'');
			$this->db->where('a.kode_bagian', $_POST['bag']);
		}
		$this->db->where('a.is_active', 1);

		
		$this->db->order_by('b.nama_brg', 'ASC');
        $exc = $this->db->get()->result();
		// echo $this->db->last_query();
		$arrResult = [];
		foreach ($exc as $key => $value) {
			$harga_jual = $value->harga_beli + $value->margin;
			$txt_color = ($value->jml_sat_kcl > 0)?'blue':'red';
			$arrResult[] = $value->kode_brg.' : '.$value->nama_brg.' : <span style="color: '.$txt_color.'; font-weight: bold">'.$value->jml_sat_kcl.'</span> '.strtolower($value->satuan_kecil).' @'.number_format($value->harga_beli).' ';
		}
		echo json_encode($arrResult);
		
		
	}

	public function getObatByBagianAutoCompleteNoInfoStok()
	{
		$this->db->from('mt_depo_stok a, mt_barang b');
		$this->db->where('a.kode_brg=b.kode_brg');
		$this->db->where('b.nama_brg like '."'%".$_POST['keyword']."%'".'');
		$this->db->where('a.kode_bagian', $_POST['bag']);
		$this->db->where('a.is_active', 1);
		
		$this->db->order_by('b.nama_brg', 'ASC');
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
		$this->db->select('b.id_obat, a.stok_akhir, b.kode_brg, b.nama_brg, b.satuan_kecil, b.satuan_besar, a.kode_bagian, c.harga_beli, b.flag_kjs, b.flag_medis, b.path_image, b.content, d.kode_profit, b.is_restrict, b.restrict_desc');
		$this->db->select('( SELECT top 1 stok_minimum FROM mt_depo_stok WHERE mt_depo_stok.kode_brg = a.kode_brg AND mt_depo_stok.kode_bagian = a.kode_bagian ) AS stok_min ');
		
        $this->db->from('tc_kartu_stok a, mt_barang b, mt_rekap_stok c');
        $this->db->where('a.kode_brg=b.kode_brg');
		$this->db->where('a.kode_brg=c.kode_brg');
		$this->db->join('fr_mt_profit_margin d','d.id_profit=c.id_profit', 'left');

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
		$this->db->join('(select top 1 * from fr_pengadaan_cito where kode_brg='."'".$_GET['kode']."'".' order by id_fr_pengadaan_cito DESC) b','a.kode_brg=b.kode_brg','left');
		$this->db->where('a.kode_brg', $_GET['kode']);
		$this->db->where('a.kode_bagian', '060101');
		$this->db->order_by('id_kartucito', 'DESC');
		$cito = $this->db->get()->row();
		

		$html = '';
		if(count($exc) > 0){

      	// stok cito
			$stok_cito = isset($cito->stok_akhir)?$cito->stok_akhir:0;
			$harga_satuan_cito = isset($cito->harga_jual)?$cito->harga_jual:0;

			$html .= '<input type="hidden" name="id_obat" value="'.$exc[0]->id_obat.'">';
			$html .= '<input type="hidden" name="kode_brg" value="'.$exc[0]->kode_brg.'">';
			$html .= '<input type="hidden" name="nama_tindakan" value="'.$exc[0]->nama_brg.'">';
			$html .= '<input type="hidden" name="pl_satuan_kecil" value="'.$exc[0]->satuan_kecil.'">';
			$html .= '<input type="hidden" name="pl_harga_beli" value="'.(int)$exc[0]->harga_beli.'">';
			$html .= '<input type="hidden" name="pl_sisa_stok" value="'.(int)$exc[0]->stok_akhir.'">';
			$html .= '<input type="hidden" name="pl_sisa_stok_cito" value="'.(int)$stok_cito.'" id="pl_sisa_stok_cito">';

			if($_GET['type_layan']=='Ranap'){
				$html .= '<input type="hidden" name="kode_bagian_depo" value="'.$exc[0]->kode_bagian.'">';
			}else{
				$html .= '<input type="hidden" name="kode_bagian" value="'.$exc[0]->kode_bagian.'">';
			}
			$html .= '<b>INFORMASI STOK OBAT</b>';
			$html .= '<table class="table" style="font-size: 12px !important">';
				$flag_medis = ($exc[0]->flag_medis==1) ? 'Alkes' : 'Obat' ;
				$html .= '<tr>';
				$link_image = ( $exc[0]->path_image != NULL ) ? PATH_IMG_MST_BRG.$exc[0]->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ;
				$html .= '<td width="100px" rowspan="7" valign="middle" align="center"><img src="'.$link_image.'" width="100%"><br> 
				<small>Sisa Stok : </small> <br><span style="font-size: 14px; font-weight: bold; color: green"> '.$exc[0]->stok_akhir.' ('.$exc[0]->satuan_kecil.')</span>
				</td>';
				$html .= '</tr>';
				
				$html .= '<tr>';
				$html .= '<td valign="top" colspan="2" align="left"><b>'.$exc[0]->kode_brg.'</b><br> '.$flag_medis.' - '.$exc[0]->nama_brg.' ('.$exc[0]->satuan_kecil.')</td>';
				
				// stok cito
				$stok_cito = isset($cito->stok_akhir)?$cito->stok_akhir:0;
				$harga_satuan_cito = isset($cito->harga_jual)?$cito->harga_jual:0;
				$html .= '</tr>';
				// $html .= '<tr>';
				// 	$html .= '<td align="left" valign="top" style="width: 100px">Stok Cito</td>'; 
				// 	$html .= '<td align="left" valign="top"></td>'; 
				// $html .= '</tr>';

				// harga cito
				$html .= '<tr>';
				$html .= '<td valign="middle" align="left">
							<div class="radio">
								<label>
								<input type="radio" name="pl_harga_satuan" class="ace" value="'.$harga_satuan_cito.'"> 
								<input type="hidden" name="pl_harga_cito" class="ace" value="'.$harga_satuan_cito.'"> 
								<span class="lbl" style="font-size: 12px !important"> Harga Cito</span>
								</label>
							</div>
							</td>';
				$html .= '<td valign="middle" align="left">
							Rp. '.number_format($harga_satuan_cito, 2).',-  &nbsp;&nbsp;&nbsp;&nbsp; Sisa stok cito '.$stok_cito.' ('.$exc[0]->satuan_kecil.')
						  </td>';
				$html .= '</tr>';
				
				/*harga umum*/
				$html .= '<tr>';
				$harga_satuan = $this->tarif->_hitungBPAKOCurrent( $exc[0]->harga_beli, $_GET['kode_kelompok'], $exc[0]->flag_kjs, $exc[0]->kode_brg, $exc[0]->kode_profit );
				$default_selected_umum = 'checked';
					$html .= '<td valign="middle" align="left">
								<div class="radio">
									<label>
									<input type="radio" name="pl_harga_satuan" value="'.(float)$harga_satuan.'" class="ace" '.$default_selected_umum.'> 
									<input type="hidden" name="pl_harga_umum" value="'.(float)$harga_satuan.'" class="ace" > 
									<span class="lbl" style="font-size: 12px !important"> Harga Jual</span>
									</label>
								</div>
							 </td>';
					$html .= '<td valign="middle" align="left">Rp. '.number_format($harga_satuan, 2).',- </td>';
				$html .= '</tr>';

				if( (int)$exc[0]->stok_min > (int)$exc[0]->stok_akhir ) :
					$html .= '<tr>';
					$html .= '<td colspan="2" align="center" style="color: red; font-weight: bold"><span class="blink_me">stok obat sudah dibawah stok minimum</span></td>';
					$html .= '</tr>';
				endif; 
			
				// harga bpjs
				$default_selected_bpjs = '';
				// $html .= '<tr>';
				// 	$html .= '<td valign="middle" align="left">
				// 				<div class="radio">
				// 					<label>
				// 					<input type="radio" name="pl_harga_satuan" value="'.(float)$exc[0]->harga_beli.'" class="ace" '.$default_selected_bpjs.'> 
				// 					<span class="lbl" style="font-size: 12px !important"> Harga BPJS</span>
				// 					</label>
				// 				</div>
				// 			  </td>';
				// 	$html .= '<td valign="middle" align="left">Rp. '.number_format($exc[0]->harga_beli,2).',-  </td>';
				// $html .= '</tr>';
			
			$html .= '</table>';	
		}else{
			$html .= '<div class="alert alert-danger" style="text-align: left !important"><b><i class="fa fa-exclamation-triangle"></i> Peringatan !</b> Kartu Stok tidak ditemukan, silahkan lapor ke gudang farmasi.</div> ';
		}
		// print_r($exc);die;
		if(isset($exc[0])){
			echo json_encode( array('html' => $html, 'sisa_stok' => isset($exc[0]->stok_akhir)?$exc[0]->stok_akhir:0, 'satuan_kecil' => isset($exc[0]->satuan_kecil)?$exc[0]->satuan_kecil:'-', 'stok_cito' => isset($stok_cito)?$stok_cito:0, 'data' => $exc[0], 'harga_beli' => $exc[0]->harga_beli, 'harga_satuan_umum' => $harga_satuan, 'is_restrict' => $exc[0]->is_restrict, 'restrict_desc' => $exc[0]->restrict_desc) );
		}else{
			echo json_encode( array('html' => $html, 'sisa_stok' => 0) );
		}
        
	}

	public function getDataPembelianObat()
	{
		$html = '';
		if($_GET['kode_perusahaan']==120){
			$qry = 'select top 5 b.kode_trans_far, b.no_resep, d.kode_perusahaan, b.tgl_trans,c.nama_brg, a.jumlah_tebus, a.jumlah_obat_23  from fr_tc_far_detail a 
			left join fr_tc_far b on b.kode_trans_far=a.kode_trans_far
			left join mt_barang c on c.kode_brg=a.kode_brg
			left join tc_registrasi d on d.no_registrasi=b.no_registrasi
			where a.kode_brg='."'".$_GET['kode']."'".' and b.no_mr='."'".$_GET['no_mr']."'". ' and b.status_transaksi=1 and d.kode_perusahaan=120 and DATEDIFF(day,b.tgl_trans,GETDATE()) < 31
			order by b.kode_trans_far DESC';
			$exc_qry = $this->db->query($qry)->result();
			// echo $this->db->last_query();die;
			if(count($exc_qry) == 0){
				$html .= '<span style="color: blue">Tidak ada data obat ditemukan dalam 1 bulan terakhir</span>';
			}else{
				$html .= '<b><span>Riwayat Resep 1 bulan terakhir</span></b>';
				$html .= '<table class="table table-hover">';
					$html .= '<thead>';
						$html .= '<tr>';
						$html .= '<th>Kode</th>';
						$html .= '<th>No. Resep</th>';
						$html .= '<th>Tanggal</th>';
						$html .= '<th>Nama Barang</th>';
						$html .= '<th>Jumlah</th>';
						$html .= '<th>Ditangguhkan</th>';
						$html .= '</tr>';
					$html .= '</thead>';
					$html .= '<tbody>';
					foreach ($exc_qry as $key => $value) {
						$html .= '<tr style="background: #ff000038">';
							$html .= '<td>'.$value->kode_trans_far.'</td>';
							$html .= '<td>'.strtoupper($value->no_resep).'</td>';
							$html .= '<td>'.$this->tanggal->formatDateTimeFormDmy($value->tgl_trans).'</td>';
							$html .= '<td>'.$value->nama_brg.'</td>';
							$html .= '<td class="center">'.$value->jumlah_tebus.'</td>';
							$html .= '<td class="center"><a href="#" onclick="show_modal('."'farmasi/Proses_resep_prb/form_show/".$value->kode_trans_far."?flag=RJ'".', '."'COPY RESEP'".')">'.$value->jumlah_obat_23.'</td>';
						$html .= '</tr>';
					}
					$html .= '</tbody>';

				$html .= '</table>';
			}
			
		}
		// print_r($this->db->last_query());die;

    	echo json_encode( array( 'html' => $html ) );
        
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
		$len = strlen($kode) + 1;
		// echo "<pre>";print_r($len);die;

		// define table
        $table = ($_GET['flag'] == 'medis' ) ? 'mt_barang' : 'mt_barang_nm' ;
		// get last brg by kode_generik
		if( $_GET['flag'] == 'medis' ){
			$this->db->select('cast(SUBSTRING(kode_brg, 7, 11) as int) as num, *');
			$this->db->where('kode_generik', $kode);
			$this->db->order_by('cast(SUBSTRING(kode_brg, 7, 11) as int) DESC');
		}else{
      $this->db->select('cast(SUBSTRING(kode_brg, '.$len.', 12) as int) as num');
	  		$this->db->where('kode_sub_golongan', $kode);
			$this->db->order_by('cast(SUBSTRING(kode_brg, '.$len.', 12) as int) DESC');
			// $this->db->where('kode_brg LIKE '."'".$kode."%'".' ');
		}

		$query = $this->db->get( $table )->row();
    	// echo "<pre>";print_r($query);die;
		// echo "<pre>";print_r($this->db->last_query());die;

		$lastnum = isset($query->num)?$query->num + 1 : 1;
		// echo "<pre>";print_r($kode);
		// echo "<pre>";print_r($lastnum);die;
		$nextnum = ( strlen($lastnum) == 1 ) ?  "0".$lastnum : $lastnum ;
		$kode_brg = $kode.$nextnum;

		// cek kode brg existing
		$brg = $this->db->get_where($table, array('kode_brg' => $kode_brg) )->row();
		if( empty($brg) ){
			$new_kode_brg = $kode_brg;
		}else{
			$count = substr($brg->kode_brg,6);
			$lastnum = $nextnum + 1;
			$nextnum = ( strlen($lastnum) == 1 ) ?  "0".$lastnum : $lastnum ;
			$new_kode_brg = $kode.$nextnum;
		}

    echo json_encode( array('kode' => $new_kode_brg) );
	}

	public function search_pasien_rj(){
		// search kunjungan pasien
		$this->db->select('a.no_kunjungan, a.tgl_masuk, a.no_mr, c.nama_pasien, d.nama_bagian, a.no_registrasi, b.kode_dokter, e.nama_pegawai, a.kode_bagian_tujuan, b.kode_kelompok, b.kode_perusahaan, total_pesan.jml_pesan, total_pesan.kode_pesan_resep, (SELECT top 1 diagnosa_akhir FROM th_riwayat_pasien WHERE no_kunjungan=a.no_kunjungan) as diagnosa_akhir, b.no_sep, total_pesan.e_resep');
		$this->db->from('tc_kunjungan a');
		$this->db->join('tc_registrasi b ', 'b.no_registrasi=a.no_registrasi' ,'left');
		$this->db->join('mt_master_pasien c ', 'c.no_mr=b.no_mr' ,'left');
		$this->db->join('mt_bagian d ', 'd.kode_bagian=a.kode_bagian_tujuan' ,'left');
		$this->db->join('mt_dokter_v e ', 'e.kode_dokter=a.kode_dokter' ,'left');
		$this->db->join('(select no_kunjungan, kode_pesan_resep, COUNT(kode_pesan_resep) as jml_pesan, e_resep from fr_listpesanan_v group by no_kunjungan, kode_pesan_resep, e_resep) as total_pesan', 'total_pesan.no_kunjungan=a.no_kunjungan' ,'left');
		$arr_kode_bagian = array('01','02');
		$this->db->where('a.status_batal is null');
		$this->db->where_in('SUBSTRING(kode_bagian_tujuan,1,2)', $arr_kode_bagian);

		if( isset($_GET['search_by']) AND $_GET['search_by'] != '' AND isset($_GET['keyword']) AND $_GET['keyword'] != '' ){
			if($_GET['search_by']=='c.nama_pasien'){
				$this->db->like( $_GET['search_by'], $_GET['keyword']);
			}else{
				$this->db->like( $_GET['search_by'], $_GET['keyword']);
			}
		}

		if( isset($_GET['tgl_pelayanan']) AND $_GET['tgl_pelayanan'] != '' ){
			$this->db->where("CAST(a.tgl_masuk as DATE) = '".$_GET['tgl_pelayanan']."'");
		}else{
			$this->db->where('MONTH(a.tgl_masuk)', date('m') );
			$this->db->where('YEAR(a.tgl_masuk)', date('Y') );
		}

		$this->db->group_by('a.no_kunjungan, a.tgl_masuk, a.no_mr, c.nama_pasien, d.nama_bagian, a.no_registrasi, b.kode_dokter, e.nama_pegawai, a.kode_bagian_tujuan, b.kode_kelompok, b.kode_perusahaan, total_pesan.jml_pesan, total_pesan.kode_pesan_resep, b.no_sep, total_pesan.e_resep');
		$this->db->order_by('a.tgl_masuk', 'DESC');
		$this->db->limit(5);
		$result = $this->db->get()->result();
		// echo $this->db->last_query();die;
		$data['result'] = $result;
		$view = $this->load->view('Templates/templates/search_result_pasien', $data, true);

		echo json_encode(array('html' => $view));
		
	}

	public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n", 'cache' => $_POST );
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
			$txt_color = ($value->jml_sat_kcl > 0)?'blue':'red';
			$arrResult[] = $value->kode_brg.' : '.$value->nama_brg.' : <span style="color: '.$txt_color.'; font-weight: bold">'.$value->jml_sat_kcl.'</span> '.strtolower($value->satuan_kecil);
			
		}
		echo json_encode($arrResult);
	}

	public function getItemBarangByUnit()
	{
		// print_r($_POST);die;
		$table = ($_POST['flag']=='non_medis') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
		$join = ($_POST['flag']=='non_medis') ? 'mt_barang_nm' : 'mt_barang' ;

		$this->db->from($table.' as a');
		$this->db->join($join.' as b', 'b.kode_brg=a.kode_brg' , 'left');
		$this->db->like('b.kode_brg', $_POST['keyword']);
		$this->db->or_like('b.nama_brg', $_POST['keyword']);
		$this->db->where('a.is_active', 1);
		$this->db->where('a.kode_bagian', $_POST['unit']);
		$result = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		$arrResult = [];
		foreach ($result as $key => $value) {
			// $arrResult[] = $value->kode_brg.' : '.$value->nama_brg;
			$txt_color = ($value->jml_sat_kcl > 0)?'blue':'red';
			$arrResult[] = $value->kode_brg.' : '.$value->nama_brg.' : <span style="color: '.$txt_color.'; font-weight: bold">'.$value->jml_sat_kcl.'</span> '.strtolower($value->satuan_kecil);
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
		// $this->db->join($mt_rekap_stok.' as c', 'c.kode_brg=a.kode_brg' , 'left');
		$this->db->where('(a.kode_brg LIKE '."'%".$_POST['keyword']."%'".' OR b.nama_brg LIKE '."'%".$_POST['keyword']."%'".')');
    	$this->db->where('a.is_active = 1');
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
		if($jenis_retur == 'penerimaan_brg' || $jenis_retur = 'expired'){
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
			$this->db->group_by('a.jml_sat_kcl');
			$this->db->order_by('b.nama_brg', 'DESC');
		}

	}

	public function getItemBarangDetailRetur()
	{
		$kode_bagian = ($_GET['flag']=='non_medis') ? '070101' : '060201' ;
		$table = ($_GET['flag']=='non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
		$join = ($_GET['flag']=='non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;
		$nama_gudang = ($_GET['flag']=='non_medis') ? 'Gudang Non Medis' : 'Gudang Medis' ;
		$mt_barang = ($_GET['flag'] == 'non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
		$mt_rekap_stok = ($_GET['flag'] == 'non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;
		$mt_depo_stok = ($_GET['flag'] == 'non_medis') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
		$tc_penerimaan = ($_GET['flag'] == 'non_medis') ? 'tc_penerimaan_barang_nm' : 
		'tc_penerimaan_barang' ;
		$tc_permintaan_inst = ($_GET['flag'] == 'non_medis') ? 'tc_permintaan_inst_nm' : 'tc_permintaan_inst' ;

		$this->db->from($table.' as a');
		$this->db->join($mt_depo_stok.' as b', 'b.kode_brg=a.kode_brg' , 'left');
		$this->db->where('a.kode_brg', $_GET['kode_brg']);
		if($_GET['retur']=='lainnya'){
			$this->db->where('b.kode_bagian', $_GET['from_unit']);
		}else{
			$this->db->where('b.kode_bagian', $kode_bagian);
		}
		$result = $this->db->get()->row();
		// print_r($this->db->last_query());die;
		$html = '';
		$stok_akhir = ($result->jml_sat_kcl <= 0) ? '<span style="color: red; font-weight: bold">'.$result->jml_sat_kcl.'</span>' : '<span style="color: green; font-weight: bold">'.$result->jml_sat_kcl.'</span>' ;
		$warning_stok = ($result->jml_sat_kcl <= 0) ? '| <span style="color: red;" class="blink_me"><b>Stok habis !</b></span>' : '' ;
		$link_image = ( $result->path_image != NULL ) ? PATH_IMG_MST_BRG.$result->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ;

		// form retur unit ke gudang
		if( $_GET['retur'] == 'lainnya' ){
			$html .= '<div class="widget-box">
						<div class="widget-body" style="background: #edf3f4;">
							<div class="widget-main">
								<b><span style="font-size: 13px">'.$result->kode_brg.' - '.$result->nama_brg.'</span></b><br>
								<table width="100%">
									<input type="hidden" id="stok_akhir_unit" value="'.$result->jml_sat_kcl.'">
									<tr>
										<td style="text-align: right">
											<div class="alert alert-warning center" style="width: 150px; " id="div_retur_qty">
												<strong style="font-size: 14px"><span id="stok_akhir_unit_txt">'.$stok_akhir.'</span> '.$result->satuan_kecil.'</strong><br>
												<span id="unit_name">Unit</span>
											</div>
										</td>
										<td width="150px" style="text-align: center;">
											<i class="fa fa-sign-out bigger-250"></i>
										</td>
										<td>
											<div class="alert alert-success center" style="width: 150px; ">
												<strong style="font-size: 14px" id="retur_qty_text">'.$_GET['qty'].' '.$result->satuan_kecil.'</strong><br>
												'.strtoupper($nama_gudang).'
											</div>
										</td>
										
									</tr>
								</table>
							</div>
						</div>
					  </div>';
		}

		// form retur penerimaan brg
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

	public function getDataTransaksiFarmasi($kode_trans_far)
	{
		$query = "SELECT a.kd_tr_resep, c.kode_brg,
		c.nama_brg,
		c.satuan_kecil,
		a.harga_jual, (a.jumlah_tebus+a.jumlah_obat_23) as jumlah_tebus, a.jumlah_retur, biaya_tebus, id_tc_far_racikan, b.status_transaksi
		FROM
		fr_tc_far_detail AS a
		LEFT JOIN fr_tc_far AS b ON b.kode_trans_far = a.kode_trans_far
		LEFT JOIN mt_barang AS c ON c.kode_brg = a.kode_brg 
		WHERE
		a.kode_trans_far = ".$kode_trans_far." AND id_tc_far_racikan = 0
		
		UNION ALL
		
		SELECT id_tc_far_racikan_detail, kode_brg,
		nama_brg,
		satuan_kecil,
		harga_jual, jumlah, 0, jumlah_total, id_tc_far_racikan, status_input
		from fr_obat_racikan_v
		WHERE kode_trans_far=".$kode_trans_far."";
		$result = $this->db->query($query)->result();

		// print_r($this->db->last_query());die;
		$html = '';
		
		$html .= '<strong style="color: blue">DATA TRANSAKSI FARMASI</strong><br>';
		$html .= '<table class="table">';
		$html .= '<tr>';
		$html .= '<th>No</th>';
		$html .= '<th>Kode</th>';
		$html .= '<th>Nama Obat</th>';
		$html .= '<th>Jumlah Tebus</th>';
		$html .= '<th class="center">#</th>';
		$html .= '</tr>';
		$no = 0;
		foreach($result as $row_ress){
			$no++;
			$is_racikan = ($row_ress->id_tc_far_racikan == 0)?'':'(racikan)';
			$is_retur = ($row_ress->jumlah_retur > 0) ? '<span style="color: red; font-weight: bold">(-'.$row_ress->jumlah_retur.')</span>' : '';
			$html .= '<tr>';
			$html .= '<td>'.$no.'</td>';
			$html .= '<td>'.$row_ress->kode_brg.'</td>';
			$html .= '<td>'.$row_ress->nama_brg.'</td>';
			$html .= '<td class="center">'.$row_ress->jumlah_tebus.' '.$is_retur.' '.$is_racikan.'</td>';
			if($row_ress->status_transaksi != null){
				$sisa = $row_ress->jumlah_tebus - $row_ress->jumlah_retur;
				if( $sisa > 0 ){
					$html .= '<td class="center"><a class="btn btn-xs btn-inverse" onclick="click_select_item_trans_far('.$row_ress->kd_tr_resep.','."'".$row_ress->kode_brg."'".', '."'".$row_ress->nama_brg."'".', '.$row_ress->jumlah_tebus.')"><i class="fa fa-sign-out"></i></a></td>';
				}else{
					$html .= '<td class="center">-</td>';
				}
			}else{
				$html .= '<td class="center"><b>Dalam proses...</b></td>';
			}
			$html .= '</tr>';
		}
		$html .= '</table><br>';
		$html .= '<b>Keterangan : </b><br>';
		$html .= '<i>Untuk jumlah retur obat racikan harus diretur semua</i>';
		

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

	public function getItemBarangDetailByUnit()
	{
		$table = ($_GET['flag']=='non_medis') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
		$join = ($_GET['flag']=='non_medis') ? 'mt_barang_nm' : 'mt_barang' ;

		$this->db->from($table.' as a');
		$this->db->join($join.' as b', 'b.kode_brg=a.kode_brg' , 'left');
		$this->db->where('a.kode_brg', $_GET['kode_brg']);
		$this->db->where('a.kode_bagian', $_GET['from_unit']);
		$result = $this->db->get()->row();

		// echo $this->db->last_query();
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

	public function get_riwayat_medis($mr){
		
		$year = date('Y') - 1;
		$no_mr = (string)$mr;

		// resume medis pasien
		$limit = isset($_GET['key'])?$_GET['key']:35;
		// $result = $this->db->select('th_riwayat_pasien.*, mt_bagian.nama_bagian, tc_kunjungan.no_kunjungan as status_kunjungan, tc_kunjungan.cara_keluar_pasien')->join('tc_kunjungan', 'tc_kunjungan.no_kunjungan = th_riwayat_pasien.no_kunjungan', 'left')->join('mt_bagian', 'mt_bagian.kode_bagian=th_riwayat_pasien.kode_bagian','left')->order_by('no_kunjungan','DESC')->where_in('SUBSTRING(th_riwayat_pasien.kode_bagian, 1,2)', ['01','02'])->where('DATEDIFF(year,tgl_periksa,GETDATE()) < 2 ')->limit($limit)->get_where('th_riwayat_pasien', array('th_riwayat_pasien.no_mr' => $no_mr))->result(); 

		$result = $this->db->select('view_cppt.*, view_cppt.tanggal as tgl_periksa, id as kode_riwayat, nama_ppa as dokter_pemeriksa, mt_bagian.nama_bagian, tc_kunjungan.no_kunjungan as status_kunjungan, tc_kunjungan.cara_keluar_pasien, tc_kunjungan.status_batal')
		->join('tc_kunjungan', 'tc_kunjungan.no_kunjungan = view_cppt.no_kunjungan', 'left')
		->join('mt_bagian', 'mt_bagian.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left')
		->order_by('no_kunjungan','DESC')
		->where('flag', 'resume')
		->where('kode_bagian_tujuan !=', '010901')
		->where('DATEDIFF(year,tanggal,GETDATE()) < 4 ')->limit($limit)
		->get_where('view_cppt', array('view_cppt.no_mr' => $no_mr))->result(); 
		// echo '<pre>';print_r($result);die;
		// eresep
		$eresep = $this->db->get_where('fr_tc_pesan_resep_detail', ['no_mr' => $no_mr, 'parent' => '0'])->result();

		// file emr pasien
		$emr = $this->db->select('csm_dokumen_export.*, tc_kunjungan.no_mr, tc_kunjungan.no_kunjungan')->join('tc_kunjungan', 'tc_kunjungan.no_registrasi=csm_dokumen_export.no_registrasi', 'left')->get_where('csm_dokumen_export', array('tc_kunjungan.no_mr' => $no_mr))->result();
		$getDataFile = [];
		foreach ($emr as $key_file => $val_file) {
			$getDataFile[$val_file->no_registrasi][$val_file->no_kunjungan][] = $val_file;
		}

		// form pengkajian pasien / form rekam medis
		$file_pengkajian = $this->db->get_where('view_cppt', array('view_cppt.no_mr' => $no_mr, 'jenis_form !=' => 0))->result();
		$getDataFilePengkajian = [];
		foreach ($file_pengkajian as $key_file_pkj => $val_file_pkj) {
			$getDataFilePengkajian[$val_file_pkj->no_registrasi][$val_file_pkj->no_kunjungan][] = $val_file_pkj;
		}

		$getDataResep = [];
		foreach ($eresep as $key_resep => $value_resep) {
			$getDataResep[$value_resep->no_registrasi][$value_resep->no_kunjungan][$value_resep->kode_pesan_resep][] = $value_resep;
		}

		// echo '<pre>';print_r($result_key);die;
		$data = array(
			'file' => $getDataFile,
			'file_pkj' => $getDataFilePengkajian,
			// 'penunjang' => $getDataPm,
			'result' => $result,
			// 'obat' => $getData,
			'eresep' => $getDataResep,
			'no_mr' => $no_mr,
			
		);

		// echo '<pre>';print_r($data);die;
		
		$html = $this->load->view('Templates/templates/view_riwayat_medis_sidebar', $data, true);
		
		echo json_encode( array('html' => $html) );
	}

	public function get_riwayat_pm($mr){
		
		$year = date('Y') - 1;
		$no_mr = (string)$mr;

		$result = $this->db->join('mt_bagian', 'mt_bagian.kode_bagian=th_riwayat_pasien.kode_bagian','left')->order_by('no_kunjungan','DESC')->where('DATEDIFF(year,tgl_periksa,GETDATE()) < 2 ')->limit(20)->get_where('th_riwayat_pasien', array('no_mr' => $no_mr))->result();

		// echo '<pre>';print_r($this->db->last_query());die;
		// $transaksi = $this->db->select('kode_trans_pelayanan, no_registrasi, no_kunjungan, nama_tindakan, mt_jenis_tindakan.jenis_tindakan, kode_jenis_tindakan, tgl_transaksi, kode_tc_trans_kasir, nama_pegawai, jumlah_tebus')->join('mt_jenis_tindakan','mt_jenis_tindakan.kode_jenis_tindakan=tc_trans_pelayanan.jenis_tindakan','left')->join('mt_karyawan','mt_karyawan.kode_dokter=tc_trans_pelayanan.kode_dokter1','left')->join('fr_tc_far_detail','fr_tc_far_detail.kd_tr_resep=tc_trans_pelayanan.kd_tr_resep','left')->get_where('tc_trans_pelayanan', array('tc_trans_pelayanan.no_mr' => $no_mr, 'kode_jenis_tindakan' => 11, 'YEAR(tgl_transaksi)' => $year) )->result();

		$this->db->select('tc_kunjungan.no_kunjungan,tc_kunjungan.no_mr,tc_kunjungan.no_registrasi,mt_karyawan.nama_pegawai as dokter, asal.nama_bagian as asal_bagian, tujuan.nama_bagian as tujuan_bagian, mt_master_pasien.nama_pasien, tc_kunjungan.tgl_masuk, tc_kunjungan.tgl_keluar,status_isihasil,kode_penunjang,pm_tc_penunjang.flag_mcu, status_daftar, kode_bagian_tujuan');
		$this->db->select('tgl_daftar, tgl_isihasil, tgl_periksa');
		$this->db->select("CAST((
			SELECT '|' + nama_tindakan
			FROM tc_trans_pelayanan
			LEFT JOIN pm_tc_penunjang ON pm_tc_penunjang.no_kunjungan=tc_trans_pelayanan.no_kunjungan
			LEFT JOIN tc_kunjungan s ON s.no_kunjungan=pm_tc_penunjang.no_kunjungan
			WHERE s.no_kunjungan = tc_kunjungan.no_kunjungan
			FOR XML PATH(''))as varchar(max)) as nama_tarif");
		$this->db->from('tc_kunjungan');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_kunjungan.no_mr','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_kunjungan.kode_dokter','left');
		$this->db->join('mt_bagian as asal','asal.kode_bagian=tc_kunjungan.kode_bagian_asal','left');
		$this->db->join('mt_bagian as tujuan','tujuan.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left');
		$this->db->join('pm_tc_penunjang','pm_tc_penunjang.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->where('tc_kunjungan.no_mr', $no_mr);
		$this->db->where('tgl_isihasil is not null');
		$this->db->where('DATEDIFF(year,tgl_masuk,GETDATE()) < 2 ');
		$this->db->where('SUBSTRING(kode_bagian_tujuan, 1, 2) =', '05');
		$this->db->order_by('tgl_masuk', 'DESC');
		$penunjang = $this->db->get()->result();

		// file emr pasien
		$emr = $this->db->select('csm_dokumen_export.*, tc_kunjungan.no_mr, tc_kunjungan.no_kunjungan')
					->join('pm_tc_penunjang', 'pm_tc_penunjang.kode_penunjang=csm_dokumen_export.kode_penunjang', 'left')
					->join('tc_kunjungan', 'tc_kunjungan.no_kunjungan=pm_tc_penunjang.no_kunjungan', 'left')
					->get_where('csm_dokumen_export', array('tc_kunjungan.no_mr' => $no_mr))->result();
		
		$getDataFile = [];
		foreach ($emr as $key_file => $val_file) {
			$getDataFile[$val_file->kode_penunjang][] = $val_file;
		}

		$getDataPm = [];
		foreach ($penunjang as $key_pm => $val_pm) {
			$getDataPm[strtolower($val_pm->tujuan_bagian)][] = $val_pm;
		}
		// echo '<pre>';print_r($getDataFile);die;
		
		

		$data = array(
			'file' => $getDataFile,
			'penunjang' => $getDataPm,
			'result' => $result,
			'no_mr' => $no_mr,
		);

		// echo '<pre>';print_r($data);die;
		
		$html = $this->load->view('Templates/templates/view_riwayat_pm_sidebar', $data, true);
		
		echo json_encode( array('html' => $html) );
	}

	public function getPegawaiAktif()
	{
        $result = $this->db->where("nama_pegawai LIKE '%".$_POST['keyword']."%' ")
        				  ->order_by('nama_pegawai', 'ASC')
						  ->get('view_dt_pegawai')->result();
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->kepeg_id.' : '.$value->nama_pegawai;
		}
		echo json_encode($arrResult);
		
		
	}

	public function getAccountCoa()
	{
        $result = $this->db->where("(acc_nama LIKE '%".$_POST['keyword']."%' OR acc_no LIKE '%".$_POST['keyword']."%' )")
        				  ->order_by('acc_nama', 'ASC')
						  ->get('mt_account')->result();
		$arrResult = [];
		foreach ($result as $key => $value) {
			$arrResult[] = $value->acc_no.' : '.$value->acc_nama;
		}
		echo json_encode($arrResult);
		
		
	}

	public function getCoaLvl()
	{
		$this->db->from('mt_account');
		$this->db->where('level_coa', $_GET['lvl']);
		$this->db->where('acc_ref', $_GET['ref']);
        $exc = $this->db->get();

        // get last kode akun
        $max_kode_akun = $this->db->select_max('acc_no_rs')->where(array('level_coa' => $_GET['lvl'], 'acc_ref' => $_GET['ref']))->get('mt_account')->row();
        // explode to array
        $explode = explode('.', $max_kode_akun->acc_no_rs);
        // change lvl to array key
        $lvl_prev = $_GET['lvl'] - 1;
        // get max num
        $max_num = (int)$explode[$lvl_prev] + 1;
        // get new kode coa 
        foreach ($explode as $key => $value) {
        	$new_num[] = ($key == $lvl_prev) ? '0'.$max_num : $value;
        }
        $new_kode_akun = implode('.', $new_num);

        echo json_encode(array('opt_coa' => $exc->result(), 'new_kode_akun' => $new_kode_akun));
	}

	public function findKodeBooking()
	{
		// load model
		$this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');

		$kode = isset($_POST['kode'])?$_POST['kode']:$_GET['kode'];
		$this->db->select('no_mr, nama, jam_pesanan, mt_dokter_v.nama_pegawai as nama_dr, mt_bagian.nama_bagian, kode_poli_bpjs, input_tgl, kode_perjanjian, is_bridging, kode_dokter_bpjs, id_tc_pesanan, no_poli, tc_pesanan.kode_dokter, tgl_masuk');
		$this->db->select('(Select top 1 no_sep from tc_registrasi where no_mr=tc_pesanan.no_mr AND kode_perusahaan=120 order by no_registrasi DESC) as no_sep');
		$this->db->from('tc_pesanan');
		$this->db->where("(unique_code_counter LIKE '%".$kode."' OR kode_perjanjian LIKE '".$kode."%') ");
		// $this->db->where("unique_code_counter", $kode);
		// $this->db->where("kode_perjanjian", $kode);
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_pesanan.no_poli','left');
		$this->db->join('mt_dokter_v', 'mt_dokter_v.kode_dokter=tc_pesanan.kode_dokter','left');
        $exc = $this->db->get();
		if ($exc->num_rows() == 0) {
			echo json_encode(array('status' => 201, 'message' => 'Data tidak ditemukan', 'data' => false ));
		}else{
			// get data rencana kontrol
			$dt = $exc->row();

			
			// find no rujukan by sep
			$result = $this->Ws_index->check_surat_kontrol_by_sep($dt->no_sep);
        	$response = isset($result['response']) ? $result : false;
			if($response != false){
				if($response['response']->metaData->code == 200){
					$obj = $response['data'];
					$norujukan = $obj->provPerujuk->noRujukan;
				}
			}
			
			// cek registrasi by date
			$cek_register = $this->db->where("CAST(tgl_jam_masuk as DATE) = '".$dt->jam_pesanan."'")->get_where('tc_registrasi', array('no_mr' => $dt->no_mr) )->row();

			$result = array(
				'id_tc_pesanan' => $dt->id_tc_pesanan,
				'no_sep_lama' => $dt->no_sep,
				'kode' => $kode,
				'kode_perjanjian' => $dt->kode_perjanjian,
				'norujukan' => isset($norujukan)?$norujukan:'',
				'no_mr' => $dt->no_mr,
				'nama' => $dt->nama,
				'kode_poli' => $dt->no_poli,
				'kode_dokter' => $dt->kode_dokter,
				'kode_poli_bpjs' => $dt->kode_poli_bpjs,
				'kode_dokter_bpjs' => $dt->kode_dokter_bpjs,
				'tgl_kunjungan' => $this->tanggal->formatDatedmY($dt->jam_pesanan),
				'tgl_masuk' => $this->tanggal->formatDatedmY(isset($cek_register->tgl_jam_masuk)?$cek_register->tgl_jam_masuk:null),
				'tgl_kunjungan_mdy' => $this->tanggal->formatDateForm($dt->jam_pesanan),
				'tgl_rencana_kontrol' => $this->tanggal->formatDateTimeToSqlDate($dt->jam_pesanan),
				'nama_dr' => strtoupper($dt->nama_dr),
				'poli' => strtoupper($dt->nama_bagian),
				'jam_praktek' => $this->tanggal->formatDateTimeToTime($dt->jam_pesanan),
				'input_tgl' => $this->tanggal->formatDatedmY($dt->input_tgl),
				'is_bridging' => $dt->is_bridging,
			);
			$html = $this->load->view('Templates/templates/form_surat_kontrol', $result, true);
			echo json_encode(array('status' => 200, 'message' => 'Data ditemukan', 'data' => $result, 'html' => $html ));
		}
	}

	public function findPasien() { 
        
        /*define variable data*/
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        $keyword = $this->input->post('no_mr');
		/*return search pasien*/

		$data_pasien = $this->Reg_pasien->search_pasien_by_mr( $keyword, array('no_mr','no_ktp') ); 
		$data_perjanjian = $this->Reg_pasien->get_perjanjian_pasien( $keyword ); 
		$sep = $this->Reg_pasien->get_last_sep( $keyword ); 
		$no_mr = isset( $data_pasien[0]->no_mr ) ? $data_pasien[0]->no_mr : 0 ;
		$data = array(
			'count' => count($data_pasien),
			'result' => $data_pasien,
			'count_perjanjian' => count($data_perjanjian),
			'perjanjian' => $data_perjanjian,
			'no_sep' => $sep,
		);
		
		if(count($data_pasien) > 0){
			echo json_encode( array('status' => 200, 'message' => 'Data ditemukan', 'data' => $data_pasien, 'perjanjian' => $data_perjanjian, 'count_perjanjian' => count($data_perjanjian), 'sep' => $sep) );
		}else{
			echo json_encode( array('status' => 201, 'message' => 'Data tidak ditemukan!') );
			
		}
		
	}

	public function search_pasien() { 
        
		$this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        /*define variable data*/
        $keyword = $this->input->get('keyword');

        if(isset($_GET['search_by'])){
			$search_by = array($_GET['search_by']);
		}else{
			$search_by = array('no_mr','nama_pasien','no_ktp','no_kartu_bpjs');
		}
		
        /*return search pasien*/
        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $keyword, $search_by ); 
        // echo '<pre>'; print_r($data_pasien);die;
        $no_mr = isset( $data_pasien[0]->no_mr ) ? $data_pasien[0]->no_mr : 0 ;
        $data_transaksi_pending = $this->Reg_pasien->cek_status_pasien( $no_mr );
        $data = array(
            'count' => count($data_pasien),
            'result' => $data_pasien,
            'count_pending' => count($data_transaksi_pending),
            'pending' => $data_transaksi_pending,
        );
        echo json_encode( $data );
    }

	public function search_pasien_public() { 
        
		$this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        /*define variable data*/
        $keyword = $this->input->get('keyword');

        if(isset($_GET['search_by'])){
			$search_by = array($_GET['search_by']);
		}else{
			$search_by = array('no_mr','nama_pasien','no_ktp','no_kartu_bpjs');
		}
		
        /*return search pasien*/
        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $keyword, $search_by ); 
        $no_mr = isset( $data_pasien[0]->no_mr ) ? $data_pasien[0]->no_mr : 0 ;
        $log_kunjungan = $this->Reg_pasien->cek_riwayat_kunjungan_pasien_by_current_day( $no_mr );
        // echo '<pre>'; print_r($log_kunjungan);die;
        $data = array(
            'count' => count($data_pasien),
            'result' => $data_pasien,
            'count_kunjungan' => count($log_kunjungan),
            'log_kunjungan' => $log_kunjungan,
        );
        echo json_encode( $data );
    }

	public function search_kunjungan_pasien_public() { 
        
		$this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        /*define variable data*/
        $keyword = $this->input->get('keyword');

        if(isset($_GET['search_by'])){
			$search_by = array($_GET['search_by']);
		}else{
			$search_by = array('no_mr','nama_pasien','no_ktp','no_kartu_bpjs');
		}
		
        /*return search pasien*/
        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $keyword, $search_by ); 
        $no_mr = isset( $data_pasien[0]->no_mr ) ? $data_pasien[0]->no_mr : 0 ;
        $log_kunjungan = $this->Reg_pasien->cek_riwayat_kunjungan_pasien( $no_mr );
        // echo '<pre>'; print_r($log_kunjungan);die;
        $data = array(
            'count' => count($data_pasien),
            'result' => $data_pasien,
            'count_kunjungan' => count($log_kunjungan),
            'log_kunjungan' => $log_kunjungan,
        );
        echo json_encode( $data );
    }

	
	public function getRefPm($kode_bagian){
		$penunjang = $this->db->get_where('mt_bagian', array('kode_bagian' => $kode_bagian) )->row();
		echo json_encode(array('kode_bag' => $penunjang->kode_bagian, 'nama_bag' => $penunjang->nama_bagian));
	}

	function getRefDokterBPJS(){
		$this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
		$service_name = "referensi/dokter/".$_POST['keyword'];
		$service_name = "referensi/dokter/pelayanan/2/tglPelayanan/".$_POST['tgl']."/Spesialis/".$_POST['spesialis'];
		$result = $this->Ws_index->getData($service_name);

		// echo '<pre>'; print_r($_POST);die;

		if($result['response']->metaData->code==200){
			foreach ($result['data']->list as $key => $value) {
				$arrResult[] = ''.$value->kode.' : '.$value->nama;
			}
			echo json_encode($arrResult);
		}
	}

	function findFingerPrint(){

		$this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
		$this->load->model('ws/AntrianOnlineModel', 'AntrianOnline');
		$this->load->library('print_escpos');

		$this->db->select('a.no_registrasi, b.nama_pasien, b.no_mr, b.no_kartu_bpjs, c.nama_bagian, d.nama_pegawai as nama_dokter, a.tgl_jam_masuk, a.umur, CAST (b.tgl_lhr as DATE) AS tgl_lahir, a.no_sep, a.print_tracer, a.norujukan, a.jd_id, a.jeniskunjunganbpjs');
		$this->db->from('tc_registrasi a');
		$this->db->join('mt_master_pasien b', 'a.no_mr=b.no_mr','left');
		$this->db->join('mt_bagian c', 'c.kode_bagian=a.kode_bagian_masuk','left');
		$this->db->join('mt_dokter_v d', 'd.kode_dokter=a.kode_dokter','left');
		$this->db->where('b.no_kartu_bpjs', $_POST['kode']);
		$this->db->where('CAST(a.tgl_jam_masuk as DATE) = ', date('Y-m-d'));
		$query = $this->db->get();
		// echo '<pre>'; print_r($_POST);die;
		if($query->num_rows() == 0){
			echo json_encode(array('status' => 201, 'message' => 'Anda belum terdaftar, silahkan ambil nomor antrian pendaftaran'));
			exit;
		}
		// cek bridging finger print
		$this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
		$service_name = "SEP/FingerPrint/Peserta/".$_POST['kode']."/TglPelayanan/".date('Y-m-d')."";
		$result = $this->Ws_index->getData($service_name);
		
		
		if(isset($result['data'])){
			if($result['data']->kode == 0){
				$response = array(
					'status_fp' => 0,
					'status' => 201,
					'message' => $result['data']->status.', silahkan finger print terlebih dahulu di kiosk<br> atau untuk bantuan petugas silahkan ambil nomor antrian ke pendaftaran',
					
				);
			}else{
				$response = array(
					'status_fp' => 1,
					'status' => 200,
					'message' => $result['data']->status,
					'data' => $query->row(),
				);

				$detail_data = $this->Reg_pasien->get_detail_resume_medis($response['data']->no_registrasi);
				
				$data_tracer = [
					'no_mr' => $response['data']->no_mr,
					'result' => $detail_data,
				];
				// echo '<pre>'; print_r($detail_data);die;
				if($response['data']->print_tracer != 'Y'){
					$tracer = $this->print_escpos->print_direct($data_tracer);
					$status_tracer = ( $tracer == 1 ) ? 'Y' : 'N' ;
				}else{
					$status_tracer = 'Y';
				}
				// update registrasi
				$this->db->update('tc_registrasi', array('print_tracer' => $status_tracer, 'konfirm_fp' => 1, 'status_checkin' => 1, 'tgl_jam_masuk' => date('Y-m-d H:i:s'), 'checkin_date' => date('Y-m-d H:i:s')), array('no_registrasi' => $response['data']->no_registrasi) );
				
				$dt_reg = $detail_data['registrasi'];
				$dt_antrian = $detail_data['no_antrian'];
				$dt_jadwal = $detail_data['jadwal'];

				// update kunjungan
				$this->db->update('tc_kunjungan', array('tgl_masuk' => date('Y-m-d H:i:s')), array('no_kunjungan' => $dt_reg->no_kunjungan) );
				// update pl_tc_poli
				$this->db->update('pl_tc_poli', array('tgl_jam_poli' => date('Y-m-d H:i:s')), array('no_kunjungan' => $dt_reg->no_kunjungan) );

				$jam_praktek_mulai = ($dt_jadwal->jd_jam_mulai) ? $this->tanggal->formatTime($dt_jadwal->jd_jam_mulai) : '08:00';
				$jam_praktek_selesai = ($dt_jadwal->jd_jam_selesai) ? $this->tanggal->formatTime($dt_jadwal->jd_jam_selesai) : '10:00';
				$kuota_dr = ($dt_jadwal->jd_kuota) ? $dt_jadwal->jd_kuota : 10;

				// post antrian online
				$params_dt = array(
					"no_registrasi" => $dt_reg->no_registrasi,
					'jam_praktek_mulai' => $jam_praktek_mulai,
					'jam_praktek_selesai' => $jam_praktek_selesai,
					'kuota_dr' => $kuota_dr,
				);
        		// echo "<pre>";print_r($params_dt);die;

				$jeniskunjungan = ($dt_reg->jeniskunjunganbpjs > 0) ? $dt_reg->jeniskunjunganbpjs : 3;

				$config_antrol = array(
					"kodebooking" => $dt_reg->kodebookingantrol,
					"jenispasien" => "JKN",
					"nomorkartu" => $dt_reg->no_kartu_bpjs,
					"nik" => $dt_reg->no_ktp,
					"nohp" => $dt_reg->no_hp,
					"kodepoli" => $dt_reg->kode_poli_bpjs,
					"namapoli" => $dt_reg->nama_bagian,
					"pasienbaru" => 0,
					"norm" => $dt_reg->no_mr,
					"tanggalperiksa" => $this->tanggal->formatDateBPJS($this->tanggal->formatDateTimeToSqlDate($dt_reg->tgl_jam_masuk)),
					"kodedokter" => $dt_reg->kode_dokter_bpjs,
					"namadokter" => $dt_reg->nama_pegawai,
					"jampraktek" => $jam_praktek_mulai.'-'.$jam_praktek_selesai,
					"jeniskunjungan" => $jeniskunjungan,
					"nomorreferensi" => $dt_reg->norujukan,
					"nomorantrean" => $dt_reg->kode_poli_bpjs.'-'.$dt_antrian->no_antrian,
					"angkaantrean" => $dt_antrian->no_antrian,
				);

				$arr_data = array_merge($config_antrol, $params_dt);
	
				$antrol = $this->processAntrol($arr_data);

			}
		}
		
		echo json_encode($response);
		
	}

	public function processAntrol($arr_dt){
        // echo '<pre>'; print_r($arr_dt);die;
        // estimasi dilayani
        $jam_mulai_praktek = $this->tanggal->formatFullTime($arr_dt['jam_praktek_mulai']);
        $jam_selesai_praktek = $this->tanggal->formatFullTime($arr_dt['jam_praktek_selesai']);
        $date = date_create($this->tanggal->formatDateTimeToSqlDate($arr_dt['tanggalperiksa']).' '.$jam_mulai_praktek );
        
        $est_hour = ceil($arr_dt['nomorantrean'] / 12);
        $estimasi = ($arr_dt['nomorantrean'] <= 12) ? 1 : $est_hour; 
        
        // estimasi dilayani
        date_add($date, date_interval_create_from_date_string('+'.$estimasi.' hours'));
        $estimasidilayani = date_format($date, 'Y-m-d H:i:s');
        $milisecond = strtotime($estimasidilayani) * 1000;

		$kuota = round($arr_dt['kuota_dr']/2);
        $sisa_kuota = $kuota - $arr_dt['angkaantrean'];
        // add antrian
        $post_antrol = array(
            "estimasidilayani" => $milisecond,
            "sisakuotajkn" => ($sisa_kuota > 0) ? $sisa_kuota : 1,
            "kuotajkn" => $kuota,
            "sisakuotanonjkn" => ($sisa_kuota > 0) ? $sisa_kuota : 1,
            "kuotanonjkn" => $kuota,
            "keterangan" => "Silahkan tensi dengan perawat"
        );

        $getData = array_merge($arr_dt, $post_antrol);
		
		$startdate = $arr_dt['tanggalperiksa'].' '.$jam_mulai_praktek;
		// echo '<pre>'; print_r($startdate); die;
		// add antrian lainnya
		$cek_antrol = $this->AntrianOnline->cekAntrolKodeBooking($arr_dt['kodebooking']);
		$addAntrian = [];
        if(empty($cek_antrol['data'])){
          $addAntrian = $this->AntrianOnline->addAntrianOnsite($getData, $startdate);
		  
        }else{
			 // update task antrol
			 for($i=2; $i<=3; $i++){
				 $updateTask = $this->AntrianOnline->update_task_antrol($arr_dt['kodebooking'], $i);
			 }
		}

        return $addAntrian;

    }

	public function cekAntrolKodeBooking($kodebooking, $taskid=''){
		$this->load->model('ws/AntrianOnlineModel', 'AntrianOnline');
		$result = $this->AntrianOnline->cekAntrolKodeBooking($kodebooking);
		$getData = [];
		foreach ($result as $key => $value) {
			$getData[$value->taskid] = $value;
		}
		// echo "<pre>"; print_r($getData[$taskid]);die;
		if($taskid != ''){
			if(isset($getData[$taskid])){
				return $getData[$taskid];
			}else{
				return $getData;
			}
		}else{
			return $getData;
		}
	}

}
