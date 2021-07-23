<?php
 
 final Class Accounting {
       
    function get_jurnal_debet($config) {

		// pelayanan
		if ($config['seri_kuitansi'] == "RJ"){

            if( $config['transaksi']['tunai'] > 0 || $config['transaksi']['debet'] > 0 || $config['transaksi']['kredit'] > 0 ){
            	$total_nominal = $config['transaksi']['tunai'] + $config['transaksi']['debet'] + $config['transaksi']['kredit'];
                $acc_no = '101010101' ;
                $insertMapingDet["id_ak_tc_transaksi"] = $config['id_ak_tc_transaksi'];
                $insertMapingDet["acc_no"] = $acc_no;
                $insertMapingDet["tipe_tx"] = 'D';
                $insertMapingDet["nominal"] = $total_nominal;
                $insertMapingDet["keterangan"] = "Penerimaan Kas/Bank : [".$config['transaksi']['nama_pasien']."] ";
                $jurnal_data[] = $insertMapingDet;
            }

            if( $config['transaksi']['nk_perusahaan'] > 0){
                $acc_no = '101030101' ;
                $insertMapingDet["id_ak_tc_transaksi"] = $config['id_ak_tc_transaksi'];
                $insertMapingDet["acc_no"] = $acc_no;
                $insertMapingDet["tipe_tx"] = 'D';
                $insertMapingDet["nominal"] = $config['transaksi']['nk_perusahaan'];
                $insertMapingDet["keterangan"] = "Penerimaan Kas/Bank : [".$config['transaksi']['nama_pasien']."] ";
                $jurnal_data[] = $insertMapingDet;
            }

            if( $config['transaksi']['nk_karyawan'] > 0){
                $acc_no = '101030105' ;
                $insertMapingDet["id_ak_tc_transaksi"] = $config['id_ak_tc_transaksi'];
                $insertMapingDet["acc_no"] = $acc_no;
                $insertMapingDet["tipe_tx"] = 'D';
                $insertMapingDet["nominal"] = $config['transaksi']['nk_karyawan'];
                $insertMapingDet["keterangan"] = "Penerimaan Kas/Bank : [".$config['transaksi']['nama_pasien']."] ";
                $jurnal_data[] = $insertMapingDet;
            }

            if( $config['transaksi']['potongan'] > 0){
                $acc_no = '401090201' ;
                $insertMapingDet["id_ak_tc_transaksi"] = $config['id_ak_tc_transaksi'];
                $insertMapingDet["acc_no"] = $acc_no;
                $insertMapingDet["tipe_tx"] = 'D';
                $insertMapingDet["nominal"] = $config['transaksi']['potongan'];
                $insertMapingDet["keterangan"] = "Penerimaan Kas/Bank : [".$config['transaksi']['nama_pasien']."] ";
                $jurnal_data[] = $insertMapingDet;
            }

        }else{

            if( $config['transaksi']['tunai'] > 0 || $config['transaksi']['debet'] > 0 || $config['transaksi']['kredit'] > 0 ){
				$total_nominal = $config['transaksi']['tunai'] + $config['transaksi']['debet'] + $config['transaksi']['kredit'];
                $acc_no = '101010102' ;
                $insertMapingDet["id_ak_tc_transaksi"] = $config['id_ak_tc_transaksi'];
                $insertMapingDet["acc_no"] = $acc_no;
                $insertMapingDet["tipe_tx"] = 'D';
                $insertMapingDet["nominal"] = $total_nominal;
                $insertMapingDet["keterangan"] = "Penerimaan Kas/Bank : [".$config['transaksi']['nama_pasien']."] ";
                $jurnal_data[] = $insertMapingDet;
            }

            // if( $nk_perusahaan > 0){
            //     $acc_no = '101030201' ;
            //     $insertMapingDet["id_ak_tc_transaksi"] = $config['id_ak_tc_transaksi'];
            //     $insertMapingDet["acc_no"] = $acc_no;
            //     $insertMapingDet["tipe_tx"] = 'D';
            //     $insertMapingDet["nominal"] = $nk_perusahaan;
            //     $insertMapingDet["keterangan"] = "Penerimaan Kas/Bank : [".$config['transaksi']['nama_pasien']."] ";
            //     $jurnal_data[] = $insertMapingDet;
            // }

            if( $config['transaksi']['nk_perusahaan'] > 0){
                $acc_no = '101030105' ;
                $insertMapingDet["id_ak_tc_transaksi"] = $config['id_ak_tc_transaksi'];
                $insertMapingDet["acc_no"] = $acc_no;
                $insertMapingDet["tipe_tx"] = 'D';
                $insertMapingDet["nominal"] = $config['transaksi']['nk_karyawan'];
                $insertMapingDet["keterangan"] = "Penerimaan Kas/Bank : [".$config['transaksi']['nama_pasien']."] ";
                $jurnal_data[] = $insertMapingDet;
            }

            if( $config['transaksi']['potongan'] > 0){
                $acc_no = '401090201' ;
                $insertMapingDet["id_ak_tc_transaksi"] = $config['id_ak_tc_transaksi'];
                $insertMapingDet["acc_no"] = $acc_no;
                $insertMapingDet["tipe_tx"] = 'D';
                $insertMapingDet["nominal"] = $config['transaksi']['potongan'];
                $insertMapingDet["keterangan"] = "Penerimaan Kas/Bank : [".$config['transaksi']['nama_pasien']."] ";
                $jurnal_data[] = $insertMapingDet;
            }

		}
		
        return $jurnal_data;
	}

	function get_jurnal_um($config){

		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		$jurnalData = array();
		// uang muka
		$nominal = $db->query("select sum(tunai)as tunai, sum(debit) as debit, sum(kredit)as kredit from ks_tc_trans_um where no_registrasi='".$config['transaksi']['no_registrasi']."'group by no_registrasi")->row();
		$tunai = isset($nominal->tunai) ? $nominal->tunai : 0 ;
		$kredit = isset($nominal->kredit) ? $nominal->kredit : 0 ;
		$debit = isset($nominal->debit) ? $nominal->debit : 0 ;
		$um_total = $tunai + $kredit + $debit;
		$uang_kembali = $um_total - $config['transaksi']['bill'];							
		// echo '<pre>'; print_r($uang_kembali);die;
		if( $debit > 0 || $kredit > 0 || $tunai > 0){
			$insertMapingDet["id_ak_tc_transaksi"] = $config['id_ak_tc_transaksi'];
			$insertMapingDet["acc_no"] = '201020301';
			$insertMapingDet["tipe_tx"] = 'D';
			$insertMapingDet["nominal"] = $um_total;
			$uraian="Uang Muka : [".$config['transaksi']['nama_pasien']."] "; //Catatan Keterangan
			$insertMapingDet["keterangan"] = $uraian;
			$jurnalData[] = $insertMapingDet;
		}

		if( $um_total > $config['transaksi']['bill'] ){
			$insertMapingDet["id_ak_tc_transaksi"] = $id_ak_tc_transaksi;
			$insertMapingDet["acc_no"] = '101010105';
			$insertMapingDet["tipe_tx"] = 'K';
			$insertMapingDet["nominal"] = $uang_kembali;
			$uraian = "Uang Muka : [".$config['transaksi']['nama_pasien']."] "; //Catatan Keterangan
			$insertMapingDet["keterangan"] = $uraian;
			$jurnalData[] = $insertMapingDet;
		}

		return $jurnalData;
	}

	function mapping_account(){
		$account = array(
			array('jasmed_rj' => '401010101'),
			array('jasmed_ri' => '401020103'),
			array('jasmed_vk' => '401030103'),
			array('jasmed_kb' => '401040103'),
			array('jasmed_ok' => '401080103'),
			array('jasmed_icu' => '401070103'),
			array('jasmed_igd' => '401050103'),
			array('jasmed_hd' => '401060103'),
			array('jasmed_pm' => '401090102'),
			array('jasmed_mcu' => '401100102'),
			array('tind_rj' => '401010103'),
			array('tind_ri' => '401020104'),
			array('tind_vk' => '401030104'),
			array('tind_vk' => '401030104'),
			array('tind_kb' => '401040104'),
			array('tind_ok' => '401080104'),
			array('tind_icu' => '401070104'),
			array('tind_igd' => '401050104'),
			array('tind_hd' => '401060104'),
			array('tind_pm' => '401090103'),
			array('adm_rj' => '401010106'),
			array('adm_ri' => '401020106'),
			array('adm_vk' => '401030106'),
			array('adm_kb' => '401040106'),
			array('adm_ok' => '401080106'),
			array('adm_icu' => '401070106'),
			array('adm_igd' => '401050106'),
			array('adm_hd' => '401060106'),
			array('adm_pm' => '401090106'),
			array('bpako_rj' => '401010105'),
			array('bpako_ri' => '401020106'),
			array('bpako_vk' => '401030106'),
			array('bpako_kb' => '401040106'),
			array('bpako_ok' => '401080106'),
			array('bpako_icu' => '401070106'),
			array('bpako_igd' => '401050106'),
			array('bpako_hd' => '401060106'),
			array('bpako_pm' => '401090105'),
			array('lain_rj' => '401010107'),
			array('lain_ri' => '401020203'),
			array('lain_vk' => '401030203'),
			array('lain_kb' => '401040203'),
			array('lain_ok' => '401080203'),
			array('lain_icu' => '401070203'),
			array('lain_igd' => '401050203'),
			array('lain_hd' => '401060203'),
			array('lain_pm' => '401090202'),
			array('kamar_rj' => '401020101'),
			array('kamar_ri' => '401020101'),
			array('kamar_vk' => '401030101'),
			array('kamar_kb' => '401040101'),
			array('kamar_ok' => '401080101'),
			array('kamar_icu' => '401070101'),
			array('kamar_igd' => '401050101'),
			array('kamar_hd' => '401060101'),
			array('kamar_pm' => '401090101'),
			array('alat_rj' => '401020101'),
			array('alat_ri' => '401020108'),
			array('alat_vk' => '401030108'),
			array('alat_kb' => '401040108'),
			array('alat_ok' => '401080108'),
			array('alat_icu' => '401070108'),
			array('alat_igd' => '401050108'),
			array('alat_hd' => '401060108'),
			array('alat_pm' => '401090108'),
			array('oks_rj' => '401020101'),
			array('oks_ri' => '401020107'),
			array('oks_vk' => '401030107'),
			array('oks_kb' => '401040107'),
			array('oks_ok' => '401080107'),
			array('oks_icu' => '401070107'),
			array('oks_igd' => '401050107'),
			array('oks_hd' => '401060107'),
			array('oks_pm' => '401090107'),
		);

		return $account;
	}

	function get_jurnal_kredit($config) {

		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		$resume_pendapatan = $db->get_where('ak_pendapatan_v', array('kode_tc_trans_kasir' => $config['kode_tc_trans_kasir']))->row();

		$account_number = $this->mapping_account();
		// print_r($account_number);die;
		$getData = array();
		foreach ($account_number as $key => $value) {
			# code...
			// print_r($value);die;
			$field = key($value);
			$values = array_values($value);
			// print_r($resume_pendapatan);die;
			if( isset($resume_pendapatan->$field) AND $resume_pendapatan->$field > 0 ){
				$dataJurnalKredit = array();
	            $dataJurnalKredit["id_ak_tc_transaksi"] = $config['id_ak_tc_transaksi'];
	            $dataJurnalKredit["acc_no"]  = $value[$field];
	            $dataJurnalKredit["tipe_tx"] = 'K';
				$dataJurnalKredit["nominal"] = $resume_pendapatan->$field;
				$uraian_title = $this->get_title_text($field);
	            $uraian="ADM MEDIS : [".$config['transaksi']['nama_pasien']."] "; //Catatan Keterangan
	            $dataJurnalKredit["keterangan"] = $uraian;
	            $getData[] = $dataJurnalKredit;
	        }
		}
        
        return $getData;

	}
	
	function get_jurnal_kredit_dokter($config){

		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		$pelayanan = $db->get_where('ak_hutang_dokter_v', array('kode_tc_trans_kasir' => $config['kode_tc_trans_kasir']) )->row();
		
		$dokter1_rj = isset( $pelayanan->dokter1_rj ) ? $pelayanan->dokter1_rj : 0 ;
		$dokter2_rj = isset( $pelayanan->dokter2_rj ) ? $pelayanan->dokter2_rj : 0 ;
		$dokter1_ri = isset( $pelayanan->dokter1_ri ) ? $pelayanan->dokter1_ri : 0 ;
		$dokter2_ri = isset( $pelayanan->dokter2_ri ) ? $pelayanan->dokter2_ri : 0 ;
		$dokter1_pm = isset( $pelayanan->dokter1_pm ) ? $pelayanan->dokter1_pm : 0 ;
		$dokter2_pm = isset( $pelayanan->dokter2_pm ) ? $pelayanan->dokter2_pm : 0 ;

		$nominal = $dokter1_rj + $dokter1_ri + $dokter1_pm + $dokter2_rj + $dokter2_ri + $dokter2_pm;
		
		$getData = array();
		if( $nominal > 0 ){
			$insertJurnalKreditDr["id_ak_tc_transaksi"] = $config['id_ak_tc_transaksi'];
			$insertJurnalKreditDr["acc_no"]  = '201020401';
			$insertJurnalKreditDr["tipe_tx"] = 'K';
			$insertJurnalKreditDr["nominal"] = $nominal;
			$uraian="DOKTER : [".$config['transaksi']['nama_pasien']."] ";
			$insertJurnalKreditDr["keterangan"] = $uraian;
			$getData[] = $insertJurnalKreditDr;
		}

		return $getData;

	}

	function get_jurnal_obat($config){

		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$pelayanan = $db->get_where('ak_obat_v', array('kode_tc_trans_kasir' => $config['kode_tc_trans_kasir']) )->row();
		
		$obat = isset($pelayanan->obat)?$pelayanan->obat:0;
		$lain = isset($pelayanan->lain)?$pelayanan->lain:0;
		$nominal = $obat + $lain;
		$getData = array();
		if( $obat > 0 ){
			$insertJurnalKreditObat["id_ak_tc_transaksi"] =  $config['id_ak_tc_transaksi'];;
			$insertJurnalKreditObat["acc_no"]  = '401090105';
			$insertJurnalKreditObat["tipe_tx"] = 'K';
			$insertJurnalKreditObat["nominal"] = $nominal;
			$uraian="OBAT & ALKES : [".$config['transaksi']['nama_pasien']."] ";
			$insertJurnalKreditObat["keterangan"] = $uraian;
			$getData[] = $insertJurnalKreditObat;
		}
		
		return $getData;
	
	}

	public function get_title_text($field){
		$explode_str = explode('_',$field);
		switch ($explode_str[0]) {
			case 'jasmed':
				$title = 'Jasa Medis';
				break;
			case 'tind':
				$title = 'Tindakan Medis';
				break;
			case 'adm':
				$title = 'Administrasi Pasien';
				break;
			case 'bpako':
				$title = 'BPAKO Medis';
				break;
			case 'lain':
				$title = 'Lain - lain';
				break;
			case 'lain':
				$title = 'Lain - lain';
				break;
			case 'kamar':
				$title = 'Kamar Tindakan';
				break;
			case 'alat':
				$title = 'Pemakaian Alat';
				break;
			case 'oks':
				$title = 'Pemakaian Alat';
				break;
			
			default:
				# code...
				$title = 'Lain - lain';
				break;
		}

		return $title;
	}

	public function create_jurnal_piutang(){
		$mapping = array();
		/*
			1. get data transaksi pasien
			2. maaping coa berdasarkan jenis transaksi dan coa
			3. insert ke tabel 
				- ak_tc_transaksi(header)
				- ak_tc_transaksi_det(child)
			4. menampilkan data jurnal
		*/
		$mapping['debet']= array('101030000');
		$mapping['kredit']= array('101030000');
	}

}
     
?>