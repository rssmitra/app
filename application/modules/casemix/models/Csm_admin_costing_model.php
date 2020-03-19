<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csm_admin_costing_model extends CI_Model {

	var $table = 'tc_registrasi';
	var $column = array('tc_registrasi.no_registrasi','tc_registrasi.no_sep','tc_registrasi.kode_bagian_masuk', 'tc_registrasi.kode_bagian_keluar','mt_karyawan.nama_pegawai');
	var $select = 'tc_registrasi.no_mr,tc_registrasi.no_registrasi,tc_registrasi.kode_perusahaan,tc_registrasi.kode_dokter,tc_registrasi.tgl_jam_masuk,tc_registrasi.tgl_jam_keluar,tc_registrasi.kode_bagian_masuk,tc_registrasi.kode_bagian_keluar,tc_registrasi.no_sep,tc_registrasi.umur, mt_master_pasien.nama_pasien, mt_master_pasien.jen_kelamin as jk, bagian_masuk.nama_bagian as bagian_masuk_field, bagian_keluar.nama_bagian as bagian_keluar_field, mt_karyawan.nama_pegawai';
	var $order = array('tc_registrasi.no_sep' => 'ASC', 'tc_registrasi.tgl_jam_masuk' => 'DESC');
    var $fields = array('bill_kamar_perawatan','bill_kamar_icu','bill_tindakan_inap','bill_tindakan_oksigen','bill_tindakan_bedah','bill_tindakan_vk','bill_obat','bill_ambulance','bill_dokter','bill_apotik','bill_lain_lain','bill_adm','bill_ugd','bill_rad','bill_lab','bill_fisio','bill_klinik','bill_pemakaian_alat',
            );
	

	public function __construct()
	{
		parent::__construct();
	}

    public function getDataLastMonth($month){
        $qry = "SELECT * FROM tc_registrasi WHERE YEAR(tgl_jam_keluar) > 2017 AND MONTH(tgl_jam_keluar)=".$month." AND kode_perusahaan=120 ORDER BY no_sep ASC";
        return $this->db->query($qry)->result();
    }

	private function _main_query(){
		
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr='.$this->table.'.no_mr', 'left');
        $this->db->join('mt_bagian as bagian_masuk', 'bagian_masuk.kode_bagian='.$this->table.'.kode_bagian_masuk', 'left');
		$this->db->join('mt_bagian as bagian_keluar', 'bagian_keluar.kode_bagian='.$this->table.'.kode_bagian_keluar', 'left');
		$this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter='.$this->table.'.kode_dokter', 'left');

        if(isset($_GET['num']) AND $_GET['num']!=''){
            $explode = explode('-', $_GET['num']);
            $this->db->where_in($this->table.'.'.$_GET['search_by'], $explode);
        }

        if (isset($_GET['frmdt']) AND $_GET['frmdt'] != '' || isset($_GET['todt']) AND $_GET['todt'] != '') {
            //$this->db->where("tc_registrasi.tgl_jam_masuk BETWEEN '".$_GET['frmdt']."' AND '".$_GET['todt']."' " );
            $this->db->where("tc_registrasi.tgl_jam_masuk >= '".$this->tanggal->selisih($_GET['frmdt'],'-1')."'" );
            $this->db->where("tc_registrasi.tgl_jam_masuk <= '".$this->tanggal->selisih($_GET['todt'],'+1')."'" );
        }

        $this->db->where('YEAR(tgl_jam_masuk)>2018');

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

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
		//print_r($this->db->last_query());die;
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		//print_r($query);die;
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->_main_query();
		return $this->db->count_all_results();
	}

	/*get data transaksi*/
	public function getTransData($no_registrasi){
		$this->db->select('tc_trans_pelayanan.*,mt_jenis_tindakan.jenis_tindakan as nama_jenis_tindakan, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai as nama_dokter');
		$this->db->from('tc_trans_pelayanan');
		$this->db->join('mt_jenis_tindakan','mt_jenis_tindakan.kode_jenis_tindakan=tc_trans_pelayanan.jenis_tindakan','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_trans_pelayanan.kode_bagian','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_trans_pelayanan.kode_dokter1','left');
		$this->db->where('no_registrasi', $no_registrasi);
		$this->db->where('nama_tindakan IS NOT NULL');
		$this->db->where('tc_trans_pelayanan.kode_tc_trans_kasir IN (SELECT kode_tc_trans_kasir FROM tc_trans_kasir WHERE no_registrasi='.$no_registrasi.' and nk_perusahaan > 0)');
		$this->db->order_by('tc_trans_pelayanan.jenis_tindakan', 'ASC');
        //print_r($this->db->last_query());die;
		return $this->db->get()->result();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.no_registrasi',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.no_registrasi',$id);
			$query = $this->db->get();
			//echo '<pre>';print_r($this->db->last_query());die;
			return $query->row();
		}
		
	}

	/*get kasir data*/
	public function getKasirData($no_registrasi)
	{
		$this->db->from('tc_trans_kasir');
		$this->db->where('no_registrasi', $no_registrasi);
		$this->db->where('no_registrasi', $no_registrasi);
		return $this->db->get()->result();
	}

	/*Check existing data*/
	public function checkExistingData($no_registrasi)
	{
		$this->db->from('csm_billing_pasien');
		$this->db->where('no_registrasi', $no_registrasi);
		return $this->db->get()->num_rows();
	}

	/*get all billing data pasien*/
	public function getBillingDataLocal($no_registrasi, $tipe='')
	{
		/*get reg pasien*/
		$data = array();
		$data['reg_data'] = $this->getRegDataLocal($no_registrasi);
		$data['billing'] = $this->getBillingLocal($no_registrasi);
        if( $tipe=='RJ' ){
            $data['resume'] = $this->db->get_where('csm_resume_billing_pasien', array('no_registrasi' => $no_registrasi))->row();
        }else{
		    $data['resume'] = $this->db->get_where('csm_resume_billing_pasien_ri', array('no_registrasi' => $no_registrasi))->row();
        }
		return $data;
	}

    public function getRegDataLocal($no_registrasi){
        return $this->db->get_where('csm_reg_pasien', array('no_registrasi' => $no_registrasi))->row();
    }

    public function getBillingLocal($no_registrasi){
        return $this->db->get_where('csm_billing_pasien', array('no_registrasi' => $no_registrasi))->result();
    }

	/*get detail data*/
	public function getDetailData($no_registrasi){
        /*get data registrasi*/
        $reg_data = $this->get_by_id($no_registrasi);
        /*get kasir data*/
        $kasir_data = $this->getKasirData($no_registrasi);
        /*get data trans pelayanan by no registrasi*/
        $trans_data = $this->getTransData($no_registrasi);
        //echo '<pre>';print_r($this->db->last_query());die;
        $group = array();
        foreach ($trans_data as $value) {
            $group[$value->nama_jenis_tindakan][] = $value;
        }
        $result = json_encode(array('group' => $group, 'kasir_data' => $kasir_data, 'no_registrasi' => $no_registrasi, 'trans_data' => $trans_data, 'reg_data' => $reg_data));

        return $result;
    }

    public function insertDataFirstTime($sirs_data, $no_registrasi){
    	/*transaction begin*/
    	$this->db->trans_begin();
        //echo '<pre>';print_r($sirs_data);die;
        /*get type RI/RJ*/
        $kode_bag = ($sirs_data->reg_data->kode_bagian_keluar!=null)?$sirs_data->reg_data->kode_bagian_keluar:$sirs_data->reg_data->kode_bagian_masuk;
        $str_kode_bag = substr((string)$kode_bag, 0,2);
        $str_type = ($str_kode_bag=='01' || $str_kode_bag=='02' || $str_kode_bag=='05')?'RJ':'RI';

    	/*data registrasi*/
            $data_registrasi = array(
                'no_registrasi' => $sirs_data->reg_data->no_registrasi,
                'csm_rp_no_sep' => $sirs_data->reg_data->no_sep,
                'csm_rp_no_mr' => $sirs_data->reg_data->no_mr,
                'csm_rp_nama_pasien' => $sirs_data->reg_data->nama_pasien,
                'csm_rp_tgl_masuk' => $sirs_data->reg_data->tgl_jam_masuk,
                'csm_rp_tgl_keluar' => $sirs_data->reg_data->tgl_jam_keluar,
                'csm_rp_kode_dokter' => $sirs_data->reg_data->kode_dokter,
                'csm_rp_nama_dokter' => $sirs_data->reg_data->nama_pegawai,
                'csm_rp_kode_bagian' => $sirs_data->reg_data->kode_bagian_masuk,
                'csm_rp_bagian' => $sirs_data->reg_data->nama_bagian,
                'csm_rp_tipe' => $str_type,
                'csm_rp_status' => 0,
            );

           if( $this->db->insert('csm_reg_pasien', $data_registrasi) ) :

                if( count($sirs_data->trans_data) > 0 ) :
                    foreach ($sirs_data->trans_data as $key_trans_data => $val_trans_data) {
                        /*data billing*/
                        $subtotal = (double)$val_trans_data->bill_rs + (double)$val_trans_data->bill_dr1 + (double)$val_trans_data->bill_dr2 + (double)$val_trans_data->lain_lain;
                        $data_billing[] = array(
                            'no_registrasi' => $no_registrasi,
                            'csm_bp_jenis_tindakan' => $val_trans_data->jenis_tindakan,
                            'csm_bp_nama_jenis_tindakan' => $val_trans_data->nama_jenis_tindakan,
                            'csm_bp_nama_tindakan' => $val_trans_data->nama_tindakan,
                            'csm_bp_subtotal' => $subtotal,
                            'csm_bp_kode_bagian' => $val_trans_data->kode_bagian,
                            'csm_bp_kode_dokter' => $val_trans_data->kode_dokter1,
                            //'csm_bp_nama_dokter' => $val_trans_data->kode_dokter1,
                            'csm_bp_kode_trans_pelayanan' => $val_trans_data->kode_trans_pelayanan,
                            'csm_bp_bill_rs' => $val_trans_data->bill_rs,
                            'csm_bp_bill_dr1' => $val_trans_data->bill_dr1,
                            'csm_bp_bill_dr2' => $val_trans_data->bill_dr2,
                            'csm_bp_bill_lain_lain' => $val_trans_data->lain_lain,
                            'csm_bp_revisi' => 0,
                            );

                        /*resume billing*/
                        if( $str_type=='RJ' ){
                            $resume_billing[] = $this->resumeBillingRJ($val_trans_data->jenis_tindakan, $val_trans_data->kode_bagian, $subtotal);                    
                        }else{
                            $resume_billing[] = $this->resumeBillingRI($val_trans_data);
                        }
                    }
                    $this->db->insert_batch('Csm_billing_pasien', $data_billing);
                    if( $str_type=='RJ' ){
                        /*split resume billing*/
                        $split_billing = $this->splitResumeBilling($resume_billing);
                        /*resume billing pasien*/
                        $data_resume_billing = array(
                            'no_registrasi' => $no_registrasi,
                            'csm_brp_bill_dr' => $split_billing['bill_dr'],
                            'csm_brp_bill_adm' => $split_billing['bill_adm_rs'],
                            'csm_brp_bill_far' => $split_billing['bill_farm'],
                            'csm_brp_bill_pm' => $split_billing['bill_pm'],
                            'csm_brp_bill_tindakan' => $split_billing['bill_tindakan'],
                            );
                        $this->db->insert('csm_resume_billing_pasien', $data_resume_billing);
                    }else{
                        /*split resume billing ri*/
                        $split_billing = $this->splitResumeBillingRI($resume_billing);
                        foreach ($split_billing as $ksb => $vsb) {
                            $arr_sp[] = array(
                                'no_registrasi' => $no_registrasi,
                                'csm_rbp_ri_title' => $vsb['title'],
                                'csm_rbp_ri_total' => $vsb['subtotal'],
                                'csm_rbp_ri_field' => $vsb['field'],
                                'created_date' => date('Y-m-d H:i:s'),
                                'created_by' => $this->session->userdata('user')->fullname,
                                );
                        }
                        $this->db->insert_batch('csm_resume_billing_pasien_ri', $arr_sp);
                        //echo '<pre>';print_r($arr_sp);die;

                    }
                endif;

            endif;



            

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                return json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                return json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }
        }

        public function resumeBillingRJ($jenis_tindakan, $kode_bagian, $subtotal){
        /*dokter*/
        if (in_array($jenis_tindakan, array(12))) {
            $bill_dr = $subtotal;
        }else{
            $bill_dr = 0;
        }
        /*obat farmasi*/
        if (in_array($jenis_tindakan, array(11))) {
            $bill_farm = $subtotal;
        }else{
            $bill_farm = 0;
        }
        /*adm dan sarana rs*/
        if (in_array($jenis_tindakan, array(2,13))) {
            $bill_adm_rs = $subtotal;
        }else{
            $bill_adm_rs = 0;
        }

        /*penunjang medis*/
        $str_pm = substr((string)$kode_bagian, 0,2);
        if($str_pm == '05'){
            if (in_array($jenis_tindakan, array(3))) {
                $bill_pm = $subtotal;
            }else{
                $bill_pm = 0;
            }
            $bill_tindakan = 0;
        }else{
            $bill_pm = 0;
            /*tindakan*/
            if (in_array($jenis_tindakan, array(3))) {
                $bill_tindakan = $subtotal;
            }else{
                $bill_tindakan = 0;
            }
        }

        $data = array(
            'bill_dr' => $bill_dr,
            'bill_farm' => $bill_farm,
            'bill_adm_rs' => $bill_adm_rs,
            'bill_pm' => $bill_pm,
            'bill_tindakan' => $bill_tindakan,
            );

        return $data;
    }

    public function resumeBillingRI($data){
    	//print_r($data);die;
    	/*subtotal*/
    	$subtotal = (double)$data->bill_rs + (double)$data->bill_dr1 + (double)$data->bill_dr2 + (double)$data->lain_lain;
    	/*kode str tarif*/
    	$str_type = substr((string)$data->kode_bagian, 0,2);
    	/*fields billing*/
    	$fields = $this->fields;

        /*kamar perawatan / ruangan / ICU*/
    	if (in_array($data->jenis_tindakan, array(1))) {
    		if (strpos($data->nama_tindakan, 'Ruangan') !== false) {
			    if (strpos($data->nama_tindakan, 'Ruangan ICU') !== false) {
			    	$bill['bill_kamar_icu'] = $subtotal;
			    	$kode_trans_pelayanan['bill_kamar_icu'] = $data->kode_trans_pelayanan;
				}else{
					$bill['bill_kamar_perawatan'] = $subtotal;
					$kode_trans_pelayanan['bill_kamar_perawatan'] = $data->kode_trans_pelayanan;
				}
			}
        }
        /*tindakan inap*/
        if ( in_array($data->jenis_tindakan, array(3)) ) {
        	if( $str_type=='03'){
        		if(!in_array($data->kode_bagian, array('030501','030901'))){
        			$bill['bill_tindakan_inap'] = $subtotal;
        			$kode_trans_pelayanan['bill_tindakan_inap'] = $data->kode_trans_pelayanan;
        		}
        	}
        }
        /*tindakan oksigen*/
        if ( in_array($data->jenis_tindakan, array(7)) ) {
        	if( $str_type=='03' ){
        		if(!in_array($data->kode_bagian, array('030501','030901'))){
        			$bill['bill_tindakan_oksigen'] = $subtotal;
        			$kode_trans_pelayanan['bill_tindakan_oksigen'] = $data->kode_trans_pelayanan;
        		}
        	}
        }
        /*tindakan bedah*/
        if ( in_array($data->kode_bagian, array('030901')) ) {
        	$bill['bill_tindakan_bedah'] = $subtotal;
        	$kode_trans_pelayanan['bill_tindakan_bedah'] = $data->kode_trans_pelayanan;
        }
        /*tindakan vk*/
        if ( in_array($data->kode_bagian, array('030501')) ) {
        	$bill['bill_tindakan_vk'] = $subtotal;
        	$kode_trans_pelayanan['bill_tindakan_vk'] = $data->kode_trans_pelayanan;
        }
        /*obat/alkes*/
        if ( in_array($data->jenis_tindakan, array(9)) ) {
        	if( $str_type=='03' ){
        		$bill['bill_obat'] = $subtotal;
        		$kode_trans_pelayanan['bill_obat'] = $data->kode_trans_pelayanan;
        	}
        }
        /*ambulance*/
        if ( in_array($data->jenis_tindakan, array(6)) ) {
    		$bill['bill_ambulance'] = $subtotal;
    		$kode_trans_pelayanan['bill_ambulance'] = $data->kode_trans_pelayanan;
        }

        /*jasa dokter/bidan*/
        if ( in_array($data->jenis_tindakan, array(4,12)) ) {
        	if( $str_type=='03' ){
        		$bill['bill_dokter'] = $subtotal;
        		$kode_trans_pelayanan['bill_dokter'] = $data->kode_trans_pelayanan;
        	}
        }
        /*biaya apotik*/
        if ( in_array($data->jenis_tindakan, array(11)) ) {
        	if( $str_type=='06' ){
        		$bill['bill_apotik'] = $subtotal;
        		$kode_trans_pelayanan['bill_apotik'] = $data->kode_trans_pelayanan;
        	}
        }
        /*biaya klinik*/
        if( $str_type=='01' ){
    		$bill['bill_klinik'] = $subtotal;
    		$kode_trans_pelayanan['bill_klinik'] = $data->kode_trans_pelayanan;
    	}

        /*biaya lain-lain*/
        if ( in_array($data->jenis_tindakan, array(8)) ) {
        	$bill['bill_lain_lain'] = $subtotal;
        	$kode_trans_pelayanan['bill_lain_lain'] = $data->kode_trans_pelayanan;
        }

        /*biaya pemakaian alat*/
        if ( in_array($data->jenis_tindakan, array(5)) ) {
        	$bill['bill_pemakaian_alat'] = $subtotal;
        	$kode_trans_pelayanan['bill_pemakaian_alat'] = $data->kode_trans_pelayanan;
        }

        /*biaya administrasi*/
        if ( in_array($data->jenis_tindakan, array(2)) ) {
        	$bill['bill_adm'] = $subtotal;
        	$kode_trans_pelayanan['bill_adm'] = $data->kode_trans_pelayanan;
        }

        /*biaya ugd*/
        if ( in_array($data->kode_bagian, array('020101')) ) {
        	$bill['bill_ugd'] = $subtotal;
        	$kode_trans_pelayanan['bill_ugd'] = $data->kode_trans_pelayanan;
        }

        /*penunjang medis*/
        /*biaya lab*/
        if ( in_array($data->kode_bagian, array('050101')) ) {
        	$bill['bill_lab'] = $subtotal;
        	$kode_trans_pelayanan['bill_lab'] = $data->kode_trans_pelayanan;
        }
        /*biaya radiologi*/
        if ( in_array($data->kode_bagian, array('050201')) ) {
        	$bill['bill_rad'] = $subtotal;
        	$kode_trans_pelayanan['bill_rad'] = $data->kode_trans_pelayanan;
        }
        /*biaya fisio*/
        if ( in_array($data->kode_bagian, array('050301')) ) {
        	$bill['bill_fisio'] = $subtotal;
        	$kode_trans_pelayanan['bill_fisio'] = $data->kode_trans_pelayanan;
        }
        /*return bill data by fields billing*/
        foreach ($fields as $key => $value) {
        	$getData[$value] = isset($bill[$value])?$bill[$value]:0;
        	$getKodeTrans[$value] = isset($kode_trans_pelayanan[$value])?$kode_trans_pelayanan[$value]:0;
        }
        $array = array(
        	'billing' => $getData,
        	'kode_trans_pelayanan' => $getKodeTrans,
        	);
        return $array;
    }

    public function splitResumeBillingRI($arrays){
    	$sumArray = [];
    	foreach ($arrays as $k=>$subArray) {
		  foreach ($subArray['billing'] as $keys=>$value) {
		    $sumArray[$keys][] = $value;
		  }
		}
		foreach ($sumArray as $ky=>$vl) {
			$getData[$ky] = array_sum($vl);
		}
		
		foreach($getData as $kys=>$vls){
			$data[] = array('title' => $this->getTitleNameBilling($kys), 'subtotal' => $vls, 'field' => $kys);
		}
        return $data;
    }

    public function getTitleNameBilling($field){
    	
    	switch ($field) {
    		case 'bill_kamar_perawatan':
    			$title_name = 'Kamar Perawatan';
    			break;
    		case 'bill_kamar_icu':
    			$title_name = 'Ruangan ICU';
    			break;
    		case 'bill_tindakan_inap':
    			$title_name = 'Tindakan Rawat Inap';
    			break;
    		case 'bill_tindakan_oksigen':
    			$title_name = 'Tindakan Oksigen';
    			break;
    		case 'bill_tindakan_bedah':
    			$title_name = 'Tindakan Bedah';
    			break;
    		case 'bill_tindakan_vk':
    			$title_name = 'Tindakan VK';
    			break;
    		case 'bill_obat':
    			$title_name = 'Biaya Obat/Alkes';
    			break;
    		case 'bill_ambulance':
    			$title_name = 'Sewa Ambulance';
    			break;
    		case 'bill_dokter':
    			$title_name = 'Jasa Dokter/Bidan';
    			break;
    		case 'bill_apotik':
    			$title_name = 'Biaya Apotik';
    			break;
    		case 'bill_lain_lain':
    			$title_name = 'Lain-lain';
    			break;
    		case 'bill_adm':
    			$title_name = 'Administrasi';
    			break;
    		case 'bill_ugd':
    			$title_name = 'Gawat Darurat';
    			break;
    		case 'bill_rad':
    			$title_name = 'Radiologi';
    			break;
    		case 'bill_lab':
    			$title_name = 'Laboratorium';
    			break;
    		case 'bill_fisio':
    			$title_name = 'Fisioterapi';
    			break;
    		case 'bill_klinik':
    			$title_name = 'Poliklinik';
    			break;
    		case 'bill_pemakaian_alat':
    			$title_name = 'Pemakaian Alat';
    			break;
    		default:
    		$title_name = '';
    			break;

    	}
    	return $title_name;
    }

    public function splitResumeBilling($arrays){
        foreach ($arrays as $key => $value) {
            $bill_dr[] = $value['bill_dr'];
            $bill_farm[] = $value['bill_farm'];
            $bill_adm_rs[] = $value['bill_adm_rs'];
            $bill_pm[] = $value['bill_pm'];
            $bill_tindakan[] = $value['bill_tindakan'];
        }
        $data = array(
            'bill_dr' => array_sum($bill_dr),
            'bill_farm' => array_sum($bill_farm),
            'bill_adm_rs' => array_sum($bill_adm_rs),
            'bill_pm' => array_sum($bill_pm),
            'bill_tindakan' => array_sum($bill_tindakan),
            );

        return $data;
    }

    public function getDetailBillingRJ($no_registrasi, $tipe, $data){
    	/*html data untuk tampilan*/
        $html = '';
        if( count($data->group) > 0 ) :

        $html .= '<b><h3>Rawat Jalan</h3></b>';
        $html .= '<div class="col-sm-6">';
        $html .= '<div><h4>Billing Pasien</h4></div>';
        //print_r($data->group);die;
        $html .= '<table class="table table-striped">';
        $html .= '<tr>';
            $html .= '<th width="30px" class="center">No</th>';
            $html .= '<th>Uraian</th>';
            $html .= '<th width="100px" class="center">Subtotal (Rp.)</th>';
        $html .= '</tr>'; 
        $no=1;
        foreach ($data->group as $k => $val) {
            $html .= '<tr>';
            $html .= '<td width="30px" class="center">'.$no.'</td>';
            $html .= '<td width="100px"><b>'.$k.'</b></td>';
            $html .= '<td width="100px" align="right"></td>';
            $html .= '</tr>';
            $no++; 
            foreach ($val as $value_data) {
                $subtotal = (double)$value_data->bill_rs + (double)$value_data->bill_dr1 + (double)$value_data->bill_dr2 + (double)$value_data->lain_lain;
                $html .= '<tr>';
                $html .= '<td width="30px">&nbsp;</td>';
                $html .= '<td width="100px">'.$value_data->nama_tindakan.'</td>';
                $html .= '<td width="100px" align="right">Rp. '.number_format($subtotal).',-</td>';
                $html .= '</tr>';
                /*total*/
                $sum_subtotal[] = $subtotal;
                /*resume billing*/
                $resume_billing[] = $this->Csm_billing_pasien->resumeBillingRJ($value_data->jenis_tindakan, $value_data->kode_bagian, $subtotal);
            }        
        }
        $html .= '<tr>';
            $html .= '<td colspan="2" align="right"><b>Total</b></td>';
            $html .= '<td width="100px" align="right"><b>Rp. '.number_format(array_sum($sum_subtotal)).',-</b></td>';
        $html .= '</tr>';   
        $html .= '</table>';

        $html .= '<br>';
        $html .= '<h4>Resume Billing</h4>';
        $html .= '<table class="table table-striped">';
        $html .= '<tr>';
            $html .= '<th align="right">Dokter</th>';
            $html .= '<th align="right">Administrasi</th>';
            $html .= '<th align="right">Obat/Farmasi</th>';
            $html .= '<th align="right">Penunjang Medis</th>';
            $html .= '<th align="right">Tindakan</th>';
        $html .= '</tr>';
         /*split resume billing*/
        $split_billing = $this->splitResumeBilling($resume_billing);
        $html .= '<tr>';
            $html .= '<td align="right">Rp. '.number_format($split_billing['bill_dr']).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($split_billing['bill_adm_rs']).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($split_billing['bill_farm']).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($split_billing['bill_pm']).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($split_billing['bill_tindakan']).',-</td>';
        $html .= '</tr>'; 
        $html .= '<tr>';
            $html .= '<td align="right" colspan="4"><b>Total</b></td>';
            $total_billing = (double)$split_billing['bill_dr'] + (double)$split_billing['bill_adm_rs'] + (double)$split_billing['bill_farm'] + (double)$split_billing['bill_pm'] + (double)$split_billing['bill_tindakan']; 
            $html .= '<td align="right"><b>Rp. '.number_format($total_billing).',-</b></td>';
        $html .= '</tr>';
        $html .= '</table>'; 
        $html .= '</div>';

        $html .= '<div class="col-sm-6">';
	        $html .= '<div><h4>Resume Pasien</h4></div>';
		        $html .= '<table class="table table-striped">';  
		        $html .= '<tr>';
		            $html .= '<th width="30px">No</th>';
                    $html .= '<th width="100px">Kode</th>';
		            $html .= '<th>Deskripsi Resume</th>';
		            $html .= '<th>Jenis</th>';
		        $html .= '</tr>';
		        /*Billing RS*/
		        foreach ($data->kasir_data as $key_kasir_data => $val_kasir_data) {
		            $no=1;
		            $html .= '<tr>';
                    $html .= '<td>'.$no.'</td>';
		            $html .= '<td>'.$val_kasir_data->seri_kuitansi.'-'.$val_kasir_data->kode_tc_trans_kasir.'</td>';
		            $html .= '<td><a href="'.base_url().'Templates/Export_data/export?type=pdf&flag=RJ&noreg='.$no_registrasi.'" target="_blank" >Billing Pasien Rawat Jalan</a></td>';
		            $html .= '<td>Billing</td>';
		            $html .= '</tr>';
		            $no++;
		        }
        $cont_no = $no;
        /*Hasil penunjang medis*/
        /*grouping document pm*/
        $grouping_doc = $this->groupingDocumentPM($data->group);
        foreach ($grouping_doc['grouping_dokumen'] as $key_group => $val_group) {
            $explode_key = explode('-',$key_group);
            $offset_kode_penunjang = $explode_key[0];
            $offset_kode_bagian = $explode_key[1];
            $offset_nama_pm = $explode_key[2];
            /*convert arr tindakan to string*/
            $convert_to_string_tindakan = implode(' / ', $grouping_doc['grouping_tindakan'][$offset_kode_penunjang]);
            if($offset_kode_bagian == '050101'){
                $flag = 'LAB';
            }elseif ($offset_kode_bagian == '050201') {
                $flag = 'RAD';
            }elseif ($offset_kode_bagian == '050301') {
                $flag = 'FSO';
            }else{
                $flag = 0;
            }
            $html .= '<tr>';
            $html .= '<td>'.$cont_no.'</td>';
            $html .= '<td width="50px"><a href="'.base_url().'Templates/Export_data/export?type=pdf&flag='.$flag.'&noreg='.$no_registrasi.'&pm='.$offset_kode_penunjang.'&kode_pm='.$offset_kode_bagian.'" target="blank" >'.$offset_kode_penunjang.'</a></td>';
            $html .= '<td>'.$offset_nama_pm.' ( '.$convert_to_string_tindakan.' ) </td>';
            $html .= '<td>Hasil Penunjang Medis</td>';
            $html .= '</tr>';
            
            $cont_no++;
        }

        $html .= '</table>';
        /*$html .= '<br>';
        $link = 'casemix/Csm_billing_pasien';
        $html .= '<a href="#" onclick="getMenu('."'".$link.'/editBilling/'.$no_registrasi.''."/RJ'".')" class="btn btn-xs btn-success"><i class="fa fa-edit bigger-50"></i> Edit Billing</a> ';
        $html .= '<a href="#" class="btn btn-xs btn-primary"><i class="fa fa-send bigger-50"></i> Submit</a> ';
        $html .= '<a href="#" class="btn btn-xs btn-danger"><i class="fa fa-file-pdf-o bigger-50"></i> Merge PDF Files</a> ';*/
        $html .= '</div>';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';
        else:
            $html .= '<div class="center"><br><p style="color:red;font-weight:bold"><b> PASIEN BELUM DIPULANGKAN DAN ATAU BELUM DISUBMIT KASIR</b></p></div>';
        endif;
        return $html;
    }

    public function getDetailBillingRI($no_registrasi, $tipe, $data){
    	/*html data untuk tampilan*/
    	$dataRI = $this->getDataRI($no_registrasi);
        //print_r($dataRI);die;
    	$no=1;
        //print_r($data->group);die;
        foreach ($data->group as $k => $val) {
            foreach ($val as $value_data) {
            	$subtotal = (double)$value_data->bill_rs + (double)$value_data->bill_dr1 + (double)$value_data->bill_dr2 + (double)$value_data->lain_lain;
                $resume_billing[] = $this->Csm_billing_pasien->resumeBillingRI($value_data);
            }        
        }
        

        $html = '<b><h3>Rawat Inap</h3></b>';
        $html .= '<table class="table table-striped">';
        $html .= '<tr>';
            $html .= '<th>Umur</th>';
            $html .= '<th>Ruangan</th>';
            $html .= '<th>Kelas</th>';
            $html .= '<th>Nasabah</th>';
            $html .= '<th>Perusahaan</th>';
        $html .= '</tr>';
        $html .= '<tr>';
            $html .= '<td>'.$data->reg_data->umur.' Tahun</td>';
            $html .= '<td>'.$dataRI->nama_bagian.'</td>';
            $html .= '<td>'.$dataRI->nama_klas.'</td>';
            $html .= '<td>Jaminan Perusahaan</td>';
            $html .= '<td>BPJS Kesehatan</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</br>';
       

        if( isset($resume_billing) ) :
        /*split resume billing*/
        $split_billing = $this->splitResumeBillingRI($resume_billing);
        //print_r($split_billing);
         /*lampiran dokumen*/
        $html .= '<div class="col-sm-12">';
        $html .= '<div class="center"><p><b>DOKUMEN LAMPIRAN HASIL PENUNJANG MEDIS DAN BILLING PASIEN</b></p></div>';
        $html .= '<table class="table table-striped" width="60%">';
        $html .= '<tr>';
            $html .= '<th width="30px">No</th>';
            $html .= '<th width="50px">Kode</th>';
            $html .= '<th width="120px">Nama Dokumen Lampiran</th>';
            $html .= '<th width="100px">Jenis</th>';
        $html .= '</tr>';

        /*Billing RS*/
        foreach ($data->kasir_data as $key_kasir_data => $val_kasir_data) {
            $no=1;
            $html .= '<tr>';
            $html .= '<td width="30px">'.$no.'</td>';
            $html .= '<td width="50px">'.$val_kasir_data->seri_kuitansi.'-'.$val_kasir_data->kode_tc_trans_kasir.'</td>';
            $html .= '<td><a href="'.base_url().'Templates/Export_data/export?type=pdf&flag=RI&noreg='.$no_registrasi.'" target="_blank" >Rincian Biaya Keseluruhan Pasien Rawat Inap </a></td>';
            $html .= '<td>Billing Kasir</td>';
            $html .= '</tr>';
            $no++;
        }
        $cont_no = $no;
        /*Hasil penunjang medis*/
        /*grouping document pm*/
        $grouping_doc = $this->groupingDocumentPM($data->group);
        foreach ($grouping_doc['grouping_dokumen'] as $key_group => $val_group) {
            $explode_key = explode('-',$key_group);
            $offset_kode_penunjang = $explode_key[0];
            $offset_kode_bagian = $explode_key[1];
            $offset_nama_pm = $explode_key[2];
            /*convert arr tindakan to string*/
            $convert_to_string_tindakan = implode(' / ', $grouping_doc['grouping_tindakan'][$offset_kode_penunjang]);
            if($offset_kode_bagian == '050101'){
                $flag = 'LAB';
            }elseif ($offset_kode_bagian == '050201') {
                $flag = 'RAD';
            }elseif ($offset_kode_bagian == '050301') {
                $flag = 'FSO';
            }else{
                $flag = 0;
            }

            $html .= '<tr>';
            $html .= '<td>'.$cont_no.'</td>';
            $html .= '<td width="50px"><a href="'.base_url().'Templates/Export_data/export?type=pdf&flag='.$flag.'&noreg='.$no_registrasi.'&pm='.$offset_kode_penunjang.'&kode_pm='.$offset_kode_bagian.'" target="blank" >'.$offset_kode_penunjang.'</a></td>';
            $html .= '<td>'.$offset_nama_pm.' ( '.$convert_to_string_tindakan.' ) </td>';
            $html .= '<td>Hasil Penunjang Medis</td>';
            $html .= '</tr>';
            
            $cont_no++;
        }
        $html .= '</table>';
        $html .= '</div>';
        /*rincian billing*/
        $html .= '<div class="col-sm-4">';
        $html .= '<br>';
        $html .= '<div class="center"><p><b>RINCIAN BIAYA KESELURUHAN PASIEN RAWAT INAP</b></p></div>';
        $html .= '<table class="table table-striped">';
        $html .= '<tr>';
            $html .= '<th width="30px" class="center">No</th>';
            $html .= '<th>Uraian</th>';
            $html .= '<th width="100px" class="center">Subtotal (Rp.)</th>';
        $html .= '</tr>'; 
        $no=1;
        foreach ($split_billing as $k => $val) {
        	/*total*/
        	if((int)$val['subtotal'] > 0){
        		$sum_subtotal[] = $val['subtotal'];
	            $html .= '<tr>';
	            $html .= '<td width="30px" class="center">'.$no.'</td>';
	            $html .= '<td width="100px"><a href="#" onclick="getBillingDetail('.$no_registrasi.','."'".$tipe."'".','."'".$val['field']."'".')">'.$val['title'].'</a></td>';
	            $html .= '<td width="100px" align="right">'.number_format($val['subtotal']).'</td>';
	            $html .= '</tr>';
	            $no++;
        	}
                 
        }
	        /*biaya materai*/
	         $html .= '<tr>';
		            $html .= '<td width="30px" class="center">'.$no.'</td>';
		            $html .= '<td width="100px">Materai</td>';
		            $html .= '<td width="100px" align="right">6,000,-</td>';
		            $html .= '</tr>';
	        $html .= '<tr>';
		    /*total plus materai*/
		    $total_plus_materai = array_sum($sum_subtotal) + 6000;
            $html .= '<td align="right"><b>Total</b></td>';
            $html .= '<td colspan="2" width="100px" align="right"><b>Rp. '.number_format($total_plus_materai).',-</b></td>';
        $html .= '</tr>';   
        $html .= '</table>';
        $html .= '<br>';
            
        $html .= '</div>';

        $html .= '<div class="col-sm-8">';
        	$html .= '<div id="detail-'.$no_registrasi.'">';
            $html .= $no_registrasi;
	        $html .= '</div>';
        $html .= '</div>';

        else :

            $html .= '<div class="center"><p style="color:red;font-weight:bold"><b>PASIEN BELUM DIPROSES OLEH ADM PASIEN</b></p></div>';
        
        endif;

        return $html;
    }


    function SumObjectValue($object, $fields){
    	//print_r($object);die;
    	$sum = 0;
    	foreach($object as $key=>$value){ 
			$sum += $value->$fields;
		}

		return $sum;
    }

    public function getDataRI($no_registrasi){
    	$this->db->from('ri_tc_rawatinap');
    	$this->db->join('mt_ruangan','mt_ruangan.kode_ruangan=ri_tc_rawatinap.kode_ruangan','left');
    	$this->db->join('mt_klas','mt_klas.kode_klas=ri_tc_rawatinap.kelas_pas','left');
    	$this->db->join('mt_bagian','mt_bagian.kode_bagian=mt_ruangan.kode_bagian','left');
    	$this->db->where("ri_tc_rawatinap.no_kunjungan IN (SELECT no_kunjungan FROM tc_kunjungan WHERE no_registrasi = ".$no_registrasi." and kode_bagian_tujuan like '03%' and kode_bagian_tujuan != '030001')");
        
    	return $this->db->get()->row();
    }

    public function getKodeTransPelayanan($array, $field){
    	foreach ($array as $value_data) {
            $resume_billing[] = $this->Csm_billing_pasien->resumeBillingRI($value_data);
        }  
        foreach ($resume_billing as $key => $value) {
        	$getData[] = $value['kode_trans_pelayanan'];
        }
    	return $getData;
    }

    public function arraySearchResume($array, $field){
    	$filtered = array();
		foreach($array as $index => $columns) {
		    foreach($columns as $key => $value) {
		        if ($key == $field && $value != 0) {
		            $filtered[] = $value;
		        }
		    }
		}
		return $filtered;
    }

    public function getHasilLab($params, $kode_penunjang){
        //print_r($params);die;
        $this->db->select("a.kode_tarif, a.nama_pemeriksaan, REPLACE(a.nama_tindakan, 'BPJS' , '') as nama_tindakan, a.hasil, a.standar_hasil_pria, a.standar_hasil_wanita, a.satuan, a.keterangan, a.detail_item_1, a.detail_item_2");
        $this->db->from('pm_hasilpasien_v as a');
        $this->db->where('a.kode_trans_pelayanan IN (SELECT kode_trans_pelayanan FROM tc_trans_pelayanan WHERE kode_penunjang='.$kode_penunjang.')');
        $this->db->order_by('kode_tc_hasilpenunjang', 'ASC');
        return $this->db->get()->result();
    }

    public function getNamaDokter($flag, $kode_pm){
        $this->db->from('pm_tc_penunjang');
        $this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter=pm_tc_penunjang.dr_pengirim', 'left');
        $this->db->where('pm_tc_penunjang.kode_penunjang', $kode_pm);
        $exc = $this->db->get()->row();
        //print_r($this->db->last_query());die;
        if( $exc && isset($exc->nama_pegawai) || $exc->nama_pegawai!=''){
            return $exc->nama_pegawai;
        }else{
            return 'Administrator';
        }

    }

    public function groupingDocumentPM($array_data){
            
        $grouping_tindakan = array();
        $grouping = array();
        foreach ( $array_data as $k_pm=>$value ) {
            if(in_array($k_pm, array('Tindakan'))){
                foreach ($value as $kval_pm => $vvval_pm) {
                    /*grouping document pm*/
                    $str_pm_resume = substr((string)$vvval_pm->kode_bagian, 0,2);
                    if ($str_pm_resume=='05') {
                        /*get tindakan by kode penunjang*/
                        $grouping_tindakan[$vvval_pm->kode_penunjang][] = $vvval_pm->nama_tindakan; 
                        $grouping[$vvval_pm->kode_penunjang.'-'.$vvval_pm->kode_bagian.'-'.$vvval_pm->nama_bagian][] = array('kode_bagian' => $vvval_pm->kode_bagian,'kode_penunjang' => $vvval_pm->kode_penunjang, 'pm_name' => $vvval_pm->nama_bagian);
                    }
                }
            }
        }
        $data = array('grouping_tindakan' => $grouping_tindakan, 'grouping_dokumen' => $grouping);
        //echo '<pre>';print_r($grouping_tindakan);die;
        return $data;

    }

    /*create Document*/
    public function createDocument($no_registrasi, $tipe){

        /*get data*/
        $data = $this->getDetailData($no_registrasi);
        $decode_data = json_decode($data);
        $filename = array();
        /*document billing*/
        //if ( $tipe == 'RI') {
            # code...
            $grouping_doc = $this->groupingDocumentPM($decode_data->group);
            foreach ($decode_data->kasir_data as $key_kasir_data => $val_kasir_data) {
                /*filenam BILL-{no_mr}{no_reg}{kode_tc_trans_kasir}*/
                $filename[] ='BILL'.$tipe.'-'.$decode_data->reg_data->no_mr.'-'.$no_registrasi.'-'.$val_kasir_data->kode_tc_trans_kasir.'';

                /*merge rincian biaya keseluruhan pasien rawat inap*/
                //$this->export->getContentPDF($no_registrasi, $tipe,'','F');
            }

            foreach ($grouping_doc['grouping_dokumen'] as $key_group => $val_group) {
                $explode_key = explode('-',$key_group);
                $offset_kode_penunjang = $explode_key[0];
                $offset_kode_bagian = $explode_key[1];
                $offset_nama_pm = $explode_key[2];
                /*convert arr tindakan to string*/
                $convert_to_string_tindakan = implode(' / ', $grouping_doc['grouping_tindakan'][$offset_kode_penunjang]);

                if($offset_kode_bagian == '050101'){
                    $flag = 'LAB';
                }elseif ($offset_kode_bagian == '050201') {
                    $flag = 'RAD';
                }elseif ($offset_kode_bagian == '050301') {
                    $flag = 'FSO';
                }else{
                    $flag = 0;
                }
                /*filename ex: {flag}{no_mr}{no_registrasi}{kode_penunjang}*/
                $filename[] = $flag.'-'.$decode_data->reg_data->no_mr.'-'.$no_registrasi.'-'.$offset_kode_penunjang.'';
            }
        //}

        return $filename;

    }

    public function getDocumentPDF($no_registrasi){
        return $this->db->join('csm_reg_pasien','csm_reg_pasien.no_registrasi=csm_dokumen_export.no_registrasi','left')->get_where('csm_dokumen_export', array('csm_dokumen_export.no_registrasi'=>$no_registrasi))->result();
    }

    public function cekIfExist($no_registrasi){
        return $this->db->get_where('csm_reg_pasien', array('no_registrasi'=>$no_registrasi,'is_submitted' => 'Y'));
    }
    public function checkIfDokExist($no_registrasi, $file_name){
        $qry = $this->db->get_where('csm_dokumen_export', array('no_registrasi'=>$no_registrasi,'csm_dex_nama_dok' => $file_name));
        return ($qry->num_rows() > 0) ? TRUE : FALSE;
    }

    public function getTotalBilling($no_registrasi, $tipe){
        if($tipe=='RJ'){
            $qry = $this->db->select('SUM(csm_brp_bill_dr + csm_brp_bill_adm + csm_brp_bill_far + csm_brp_bill_pm + csm_brp_bill_tindakan)AS total')->get_where('csm_resume_billing_pasien', array('no_registrasi' => $no_registrasi))->row();
        }else{
            $qry = $this->db->select('SUM(csm_rbp_ri_total)AS total')->get_where('csm_resume_billing_pasien_ri', array('no_registrasi' => $no_registrasi))->row();
        }
        return $qry->total;
    }

    public function update_status_nk_kode_perusahaan($no_registrasi){
        /*update tc_registrasi*/
        $this->db->update('tc_registrasi', array('kode_perusahaan'=>120), array('no_registrasi'=>$no_registrasi));
        $this->db->update('tc_trans_pelayanan', array('kode_perusahaan'=>120, 'status_nk'=>1), array('no_registrasi'=>$no_registrasi));

        $this->db->query('update tc_trans_kasir set kode_perusahaan=120 where kode_tc_trans_kasir in (select kode_tc_trans_kasir from tc_trans_pelayanan where no_registrasi='.$no_registrasi.')');
        return true;
    }

    public function submit_kasir($no_registrasi){
        /*update tc_registrasi*/
        $this->db->update('tc_registrasi', array('kode_perusahaan'=>120), array('no_registrasi'=>$no_registrasi));
        $this->db->update('tc_trans_pelayanan', array('kode_perusahaan'=>120, 'status_nk'=>1), array('no_registrasi'=>$no_registrasi));

        $this->db->query('update tc_trans_kasir set kode_perusahaan=120 where kode_tc_trans_kasir in (select kode_tc_trans_kasir from tc_trans_pelayanan where no_registrasi='.$no_registrasi.')');
        return true;
    }


}
