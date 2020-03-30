<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

final Class Graph_master {

    function get_graph($mod, $params) {
    	
    	$data = $this->setting_module($params);

		return $data;
		
    }

    function setting_module($params) {
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		/*total klaim berdasarkan nomor sep per tahun existing*/
		/*based query*/
		if($params['prefix']==1){
			$query = "SELECT MONTH(created_date) AS bulan, COUNT(id) AS total FROM log WHERE YEAR(created_date)=".date('Y')." GROUP BY MONTH(created_date)";	
			$fields = array('User_Activity'=>'total');
			$title = '<span style="font-size:13.5px">Log History Penggunaan Aplikasi Oleh User Tahun '.date('Y').'</span>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}

		if($params['prefix']==2){
			$query = "SELECT TOP 10 MONTH(log.created_date) AS bulan, tmp_mst_menu.name, COUNT(id) as total FROM LOG LEFT JOIN tmp_mst_menu ON tmp_mst_menu.menu_id=log.menu_id WHERE YEAR(log.created_date)=".date('Y')." AND log.modul_id !=0 AND log.menu_id!=0 GROUP BY log.menu_id, MONTH(log.created_date), tmp_mst_menu.name ORDER BY COUNT(id) DESC";	
			$fields = array('name' => 'total');
			$title = '<span style="font-size:13.5px">5 Modul yang sering diakses oleh pengguna</span>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}

		if($params['prefix']==3){
			$query = "SELECT TOP 10 content,COUNT(id) AS total FROM LOG GROUP BY content ORDER BY COUNT(id) DESC";	
			$fields = array('Activity' => 'content', 'Total' => 'total');
			$title = '<span style="font-size:13.5px">10 Fungsi yang sering diakses oleh pengguna</span>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}

		/*line*/
		if($params['prefix']==111){
			$query = "SELECT MONTH(tgl_jam_masuk) AS bulan, COUNT(no_registrasi) AS total 
						FROM tc_registrasi 
						WHERE YEAR(tgl_jam_masuk)=".date('Y')." GROUP BY MONTH(tgl_jam_masuk)";	
			$fields = array('Total Pasien'=>'total');
			$title = '<span style="font-size:13.5px">Grafik Pendaftaran Pasien Tahun '.date('Y').'</span>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}

		if($params['prefix']==51){
			$query = "SELECT MONTH(tgl_jam_poli) AS bulan, COUNT(kode_poli) AS total 
						FROM pl_tc_poli 
						WHERE YEAR(tgl_jam_poli)=".date('Y')." AND status_batal is null GROUP BY MONTH(tgl_jam_poli) ";	
			$fields = array('Total Pasien'=>'total');
			$title = '<span style="font-size:13.5px">Grafik Kunjungan Poli Tahun '.date('Y').'</span>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}

		if($params['prefix']==251){
			$query = "SELECT MONTH(tgl_masuk) AS bulan, COUNT(kode_ri) AS total 
						FROM ri_tc_rawatinap 
						WHERE YEAR(tgl_masuk)=".date('Y')." GROUP BY MONTH(tgl_masuk) ";	
			$fields = array('Total Pasien'=>'total');
			$title = '<span style="font-size:13.5px">Grafik Kunjungan Pasien Rawat Inap  Tahun '.date('Y').'</span>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}


		/*pie chart*/
		if($params['prefix']==112){
			$query = "SELECT substring(a.kode_bagian, 1, 2) as substr,a.kode_bagian, a.nama_bagian as bagian,COUNT(b.no_registrasi) AS total 
						FROM tc_registrasi b
						left join mt_bagian a on a.kode_bagian=b.kode_bagian_masuk 
						WHERE YEAR(b.tgl_jam_masuk) = ".date('Y')." and month(b.tgl_jam_masuk)=".date('m')." and day(b.tgl_jam_masuk)=".date('d')."
						GROUP BY a.kode_bagian, a.nama_bagian 
						ORDER BY COUNT(b.no_registrasi) DESC";	
			$data_qry = $CI->db->query($query)->result_array();
			$getData = [];
			foreach ($data_qry as $key => $value) {
				$getData[$value['substr']][] = $value;
			}

			foreach ($getData as $k => $v) {
				switch ($k) {
					case '01':
						# code...
						$title = 'Rawat Jalan';
						break;
					case '02':
						# code...
						$title = 'IGD';
						break;
					case '03':
						# code...
						$title = 'Rawat Inap';
						break;
					case '05':
						# code...
						$title = 'Penunjang Medis';
						break;
				}
				$data[] = array( 'name' => $title, 'total' => array_sum(array_column($v,'total')) );
			}
			
			//echo '<pre>';print_r($data);die;

			$fields = array('name' => 'total');
			$title = '<span style="font-size:13.5px">Persentase Jumlah Pengunjung Berdasarkan Instalasi</span>';
			$subtitle = 'Tanggal '.$CI->tanggal->formatDate(date('Y-m-d')).'';
		}

		if($params['prefix']==52){
			$query = "SELECT substring(a.kode_bagian, 1, 2) as substr,a.kode_bagian, a.nama_bagian as bagian,COUNT(b.kode_poli) AS total 
						FROM pl_tc_poli b
						left join mt_bagian a on a.kode_bagian=b.kode_bagian 
						WHERE YEAR(b.tgl_jam_poli) = ".date('Y')." and month(b.tgl_jam_poli)=".date('m')." and day(b.tgl_jam_poli)=".date('d')." AND b.status_batal IS NULL
						GROUP BY a.kode_bagian, a.nama_bagian 
						ORDER BY COUNT(b.kode_poli) DESC";	
			$data_qry = $CI->db->query($query)->result_array();
			$getData = [];
			foreach ($data_qry as $key => $value) {
				$data[] = array( 'name' => $value['bagian'], 'total' => $value['total'] );
			}

			$fields = array('name' => 'total');
			$title = '<span style="font-size:13.5px">Persentase Jumlah Pasien Berdasarkan Poli/Klinik</span>';
			$subtitle = 'Tanggal '.$CI->tanggal->formatDate(date('Y-m-d')).'';
		}

		if($params['prefix']==252){
			$query = "SELECT substring(a.kode_bagian, 1, 2) as substr,a.kode_bagian, a.nama_bagian as bagian,COUNT(b.kode_ri) AS total 
						FROM ri_tc_rawatinap b
						left join mt_bagian a on a.kode_bagian=b.bag_pas
						WHERE YEAR(b.tgl_masuk) = ".date('Y')." and month(b.tgl_masuk)=".date('m')."
						GROUP BY a.kode_bagian, a.nama_bagian 
						ORDER BY COUNT(b.kode_ri) DESC";	
			$data_qry = $CI->db->query($query)->result_array();
			$getData = [];
			foreach ($data_qry as $key => $value) {
				$data[] = array( 'name' => $value['bagian'], 'total' => $value['total'] );
			}

			$fields = array('name' => 'total');
			$title = '<span style="font-size:13.5px">Persentase Jumlah Pasien Rawat Inap Berdasarkan Ruangan</span>';
			$subtitle = 'Data Pasien RI Bulan '.$CI->tanggal->getBulan(date('m')).'';
		}

		/*table*/
		if($params['prefix']==113){
			$query = "SELECT TOP 10 a.nama_bagian as bagian,COUNT(b.no_registrasi) AS total 
						FROM tc_registrasi b
						left join mt_bagian a on a.kode_bagian=b.kode_bagian_masuk 
						WHERE YEAR(b.tgl_jam_masuk) = ".date('Y')." and month(b.tgl_jam_masuk)=".date('m')." and day(b.tgl_jam_masuk)=".date('d')."
						GROUP BY a.nama_bagian 
						ORDER BY COUNT(b.no_registrasi) DESC";	
			$fields = array('Nama Bagian' => 'bagian', 'Total' => 'total');
			$title = '<span style="font-size:13.5px">10 Klinik Terbanyak Dikunjungi Pasien Hari Ini<br><small style="font-size:12px !important">Tanggal '.$CI->tanggal->formatDate(date('Y-m-d')).' </span></small>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}

		if($params['prefix']==53){
			$query = "SELECT TOP 10 a.nama_bagian as bagian,COUNT(b.no_registrasi) AS total 
						FROM tc_registrasi b
						left join mt_bagian a on a.kode_bagian=b.kode_bagian_masuk 
						WHERE YEAR(b.tgl_jam_masuk) = ".date('Y')." and month(b.tgl_jam_masuk)=".date('m')." and day(b.tgl_jam_masuk)=".date('d')."
						GROUP BY a.nama_bagian 
						ORDER BY COUNT(b.no_registrasi) DESC";	
			$fields = array('Nama Bagian' => 'bagian', 'Total' => 'total');
			$title = '<span style="font-size:13.5px">10 Klinik Terbanyak Berdasarkan Pendaftaran Pasien Hari Ini<br><small style="font-size:12px !important">Tanggal '.$CI->tanggal->formatDate(date('Y-m-d')).' </span></small>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}

		if($params['prefix']==54){
			$query = "SELECT TOP 10 a.nama_pegawai as nama_dokter,COUNT(b.no_registrasi) AS total 
						FROM tc_registrasi b
						left join mt_karyawan a on a.kode_dokter=b.kode_dokter
						WHERE YEAR(b.tgl_jam_masuk) = ".date('Y')." and month(b.tgl_jam_masuk)=".date('m')." and day(b.tgl_jam_masuk)=".date('d')." AND a.nama_pegawai is not null
						GROUP BY a.nama_pegawai 
						ORDER BY COUNT(b.no_registrasi) DESC";	
			$fields = array('Nama Dokter' => 'nama_dokter', 'Total' => 'total');
			$title = '<span style="font-size:13.5px">10 Dokter dengan Pasien Terbanyak Berdasarkan Pendaftaran Pasien Hari Ini<br><small style="font-size:12px !important">Tanggal '.$CI->tanggal->formatDate(date('Y-m-d')).' </span></small>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}
		
		// modul purchasing line chart
		if($params['prefix']==321){
			$query = "SELECT MONTH(tgl_permohonan) AS bulan, COUNT(kode_permohonan) AS total FROM tc_permohonan WHERE YEAR(tgl_permohonan)=".date('Y')." GROUP BY MONTH(tgl_permohonan)";	
			$fields = array('Total_Permintaan_Medis'=>'total');
			$title = '<span style="font-size:13.5px">Total Permintaan Barang Medis Tahun '.date('Y').'</span>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}

		// modul purchasing table chart
		if($params['prefix']==323){
			$query = "SELECT month(a.tgl_po) as bulan, CAST(SUM(a.total_stl_ppn) AS INT) AS total_format_money 
						FROM tc_po a
						WHERE YEAR(a.tgl_po) = ".date('Y')."
						GROUP BY month(a.tgl_po) ORDER BY month(a.tgl_po) ASC";	
			$fields = array('Bulan' => 'bulan', 'Total' => 'total_format_money');
			$title = '<span style="font-size:13.5px">Total Pembelian Barang Medis Berdasarkan PO Tahun '.date('Y').' </span></small>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}

		// modul purchasing pie chart
		if($params['prefix']==322){
			$query = "SELECT TOP 10 a.namasupplier as supplier,COUNT(b.id_tc_po) AS total 
						FROM tc_po b
						left join mt_supplier a on a.kodesupplier=b.kodesupplier
						WHERE YEAR(b.tgl_po) = ".date('Y')." and month(b.tgl_po)=".date('m')."
						GROUP BY a.namasupplier
						ORDER BY COUNT(b.id_tc_po) DESC";	
			$data_qry = $CI->db->query($query)->result_array();
			$getData = [];
			foreach ($data_qry as $key => $value) {
				$data[] = array( 'name' => $value['supplier'], 'total' => $value['total'] );
			}

			$fields = array('name' => 'total');
			$title = '<span style="font-size:13.5px">10 Top Supplier Berdasarkan PO </span>';
			$subtitle = 'Data PO Tahun '.date('Y').'';
		}

		// MODUL ADM PASIEN //
		// modul purchasing line chart
		if($params['prefix']==201){
			$query = "SELECT MONTH(tgl_jam) AS bulan, SUM(bill) AS total FROM tc_trans_kasir WHERE YEAR(tgl_jam)=".date('Y')." GROUP BY MONTH(tgl_jam)";	
			$fields = array('Total_Pendapatan_RS'=>'total');
			$title = '<span style="font-size:13.5px">Grafik Pendapatan Rumah Sakit Tahun '.date('Y').'</span>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();

		}

		// modul purchasing pie chart
		if($params['prefix']==202){
			$query = "SELECT TOP 10 a.nama_perusahaan as nama_perusahaan, SUM(b.bill) AS total 
						FROM tc_trans_kasir b
						left join mt_perusahaan a on a.kode_perusahaan=b.kode_perusahaan
						WHERE YEAR(b.tgl_jam) = ".date('Y')." and month(b.tgl_jam)=".date('m')."
						GROUP BY a.nama_perusahaan ORDER BY SUM(b.bill) DESC";	
			$data_qry = $CI->db->query($query)->result_array();
			$getData = [];
			foreach ($data_qry as $key => $value) {
				$data[] = array( 'name' => (!empty($value['nama_perusahaan']))?$value['nama_perusahaan']:'UMUM', 'total' => $value['total'] );
			}

			$fields = array('name' => 'total');
			$title = '<span style="font-size:13.5px">10 Perusahaan Penjamin Pasien Terbesar</span>';
			$subtitle = 'Pendapatan RS Berdasarkan Perusahaan Penjamin Tahun '.date('Y').'';
		}
		
		// modul purchasing table chart
		if($params['prefix']==203){
			$query = "SELECT month(a.tgl_jam) as bulan, SUM(a.bill) AS total_format_money 
						FROM tc_trans_kasir a
						WHERE YEAR(a.tgl_jam) = ".date('Y')."
						GROUP BY month(a.tgl_jam) ORDER BY month(a.tgl_jam) ASC";	
			$fields = array('Bulan' => 'bulan', 'Total' => 'total_format_money');
			$title = '<span style="font-size:13.5px">Total Pendapatan RS Tahun '.date('Y').' s/d Bulan '.$CI->tanggal->getBulan(date('m')).' </span></small>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}


		// MODUL CASEMIX
		// modul purchasing line chart
		if($params['prefix']==341){
			$query = "SELECT MONTH(tgl_transaksi_kasir) AS bulan, COUNT(csm_dokumen_klaim.no_registrasi) AS total FROM csm_dokumen_klaim INNER JOIN csm_reg_pasien ON csm_reg_pasien.no_registrasi=csm_dokumen_klaim.no_registrasi WHERE YEAR(tgl_transaksi_kasir)=".date('Y')." AND kode_perusahaan=120 GROUP BY MONTH(tgl_transaksi_kasir)";	
			$fields = array('Total_Dokumen_Klaim'=>'total');
			$title = '<span style="font-size:13.5px">Total Dokumen Pengajuan Klaim BPJS '.date('Y').'</span>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();

		}

		// modul purchasing pie chart
		if($params['prefix']==342){
			$query = "SELECT TOP 5 a.nama_perusahaan as nama_perusahaan, SUM(b.bill) AS total 
						FROM tc_trans_kasir b
						left join mt_perusahaan a on a.kode_perusahaan=b.kode_perusahaan
						WHERE YEAR(b.tgl_jam) = ".date('Y')." and month(b.tgl_jam)=".date('m')."
						GROUP BY a.nama_perusahaan ORDER BY SUM(b.bill) DESC";	
			$data_qry = $CI->db->query($query)->result_array();
			$getData = [];
			foreach ($data_qry as $key => $value) {
				$data[] = array( 'name' => (!empty($value['nama_perusahaan']))?$value['nama_perusahaan']:'UMUM', 'total' => $value['total'] );
			}

			$fields = array('name' => 'total');
			$title = '<span style="font-size:13.5px">5 Perusahaan Penjamin Pasien Terbesar</span>';
			$subtitle = 'Persentase Pendapatan RS Berdasarkan Perusahaan Penjamin Pasien Tahun '.date('Y').'';
		}
		
		// modul purchasing table chart
		if($params['prefix']==343){
			$query = "SELECT month(a.tgl_transaksi_kasir) as bulan, SUM(a.csm_dk_total_klaim) AS total_format_money 
						FROM csm_dokumen_klaim a
						WHERE YEAR(a.tgl_transaksi_kasir) = ".date('Y')."
						GROUP BY month(a.tgl_transaksi_kasir) ORDER BY month(a.tgl_transaksi_kasir) ASC";	
			$fields = array('Bulan' => 'bulan', 'Total' => 'total_format_money');
			$title = '<span style="font-size:13.5px">Total Pengajuan Klaim s/d Bulan '.$CI->tanggal->getBulan(date('m')).' '.date('Y').' </span></small>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}

		if($params['prefix']==344){
			$query = "SELECT month(a.tgl_transaksi_kasir) as bulan, COUNT(a.no_registrasi) AS total_format_money 
						FROM csm_dokumen_klaim a
						WHERE YEAR(a.tgl_transaksi_kasir) = ".date('Y')."
						GROUP BY month(a.tgl_transaksi_kasir) ORDER BY month(a.tgl_transaksi_kasir) ASC";	
			$fields = array('Bulan' => 'bulan', 'Total' => 'total_format_money');
			$title = '<span style="font-size:13.5px">Total Dokumen Klaim  s/d Bulan '.$CI->tanggal->getBulan(date('m')).' '.date('Y').' </span></small>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}

		if($params['prefix']==345){
			$query = "SELECT created_by as petugas, COUNT(a.no_registrasi) AS total_format_money 
						FROM csm_dokumen_klaim a
						WHERE YEAR(a.tgl_transaksi_kasir) = ".date('Y')." AND MONTH(a.tgl_transaksi_kasir) = ".date('m')."
						GROUP BY created_by ORDER BY created_by ASC";	
			$fields = array('Nama_Petugas' => 'petugas', 'Total' => 'total_format_money');
			$title = '<span style="font-size:13.5px">Total Costing Bulan '.$CI->tanggal->getBulan(date('m')).' '.date('Y').'</span></small>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $db->query($query)->result_array();
		}


		/*find and set type chart*/
		$chart = $this->chartTypeData($params['TypeChart'], $fields, $params, $data);
		$chart_data = array(
			'title' 	=> $title,
			'subtitle' 	=> $subtitle,
			'xAxis' 	=> isset($chart['xAxis'])?$chart['xAxis']:'',
			'series' 	=> isset($chart['series'])?$chart['series']:'',
			);

		return $chart_data;
		
    }


    public function chartTypeData($style, $fields, $params, $data){

    	switch ($style) {
    		case 'column':
    			/*lanjutkan buat function jika ada style yang lain*/
    			if ($params['style']==1) {
    				return $this->ColumnStyleOneData($fields, $params, $data);
    			}
    			break;
    		case 'pie':
    			if ($params['style']==1) {
    				return $this->PieStyleOneData($fields, $params, $data);
    			}
    			break;
    		case 'line':
    			if ($params['style']==1) {
    				return $this->LineStyleOneData($fields, $params, $data);
    			}
    			break;
    		case 'table':
    			if ($params['style']==1) {
    				return $this->TableStyleOneData($fields, $params, $data);
    			}
    			break;
    		
    		default:
    			# code...
    			break;
    	}
    }
    public function ColumnStyleOneData($fields, $params, $data){
    	$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
    	
        $getData = array();
		foreach($data as $key=>$row){
			foreach ($fields as $kf => $vf) {
				$getData[$kf][$row['bulan']-1] = (int)$row[$vf];
			}
		}
		
		for ($i=0; $i < 12; $i++) { 
			foreach ($fields as $kf2 => $vf2) {
				if(!isset($getData[$kf2][$i])){
					$getData[$kf2][$i] = 0;
				}
				ksort($getData[$kf2]);
			}
			$categories[] = $CI->tanggal->getBulan($i+1);
			
		}

		foreach ($getData as $k => $r) {
			$series[] = array('name' => $k, 'data' => $r );
		}
		
		$chart_data = array(
			'xAxis' 	=> array('categories' => $categories),
			'series' 	=> $series,
		);
		return $chart_data;
    }

    public function PieStyleOneData($fields, $params, $data){
    	$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
    	
    	// echo '<pre>';print_r($fields);
    	// echo '<pre>';print_r($params);
    	// echo '<pre>';print_r($data);
    	// die;

        $getData = array();
		foreach($data as $key=>$row){
			foreach ($fields as $kf => $vf) {
				$getData[$row[$kf]][] = (int)$row[$vf];
			}
		}

		foreach ($getData as $k => $r) {
			$series[] = array($k, array_sum($r));
		}
		$chart_data = array(
			'series' 	=> $series,
		);
		return $chart_data;
    }

    public function LineStyleOneData($fields, $params, $data){
    	$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
    	// echo '<pre>';print_r($fields);
    	
        $getData = array();
		foreach($data as $key=>$row){
			foreach ($fields as $kf => $vf) {
				$getData[$kf][$row['bulan']-1] = round($row['total'], 2);
			}
		}
		// echo '<pre>';print_r($data);
		

		for ($i=0; $i < 12; $i++) { 
			foreach ($fields as $kf2 => $vf2) {
				if(!isset($getData[$kf2][$i])){
					$getData[$kf2][$i] = 0;
				}
				ksort($getData[$kf2]);
			}
			$categories[] = $CI->tanggal->getBulan($i+1);
			
		}

		
		foreach ($getData as $k => $r) {
			$series[] = array('name' => $k, 'data' => $r );
		}
		
		$chart_data = array(
			'xAxis' 	=> array('categories' => $categories),
			'series' 	=> $series,
		);
		return $chart_data;
    }

    public function TableStyleOneData($fields, $params, $data){
    	$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
    	
    	// echo '<pre>';print_r($fields);
    	// echo '<pre>';print_r($params);
    	// echo '<pre>';print_r($data);
    	// die;

        $html = '';
        $html .='<table class="table table-bordered table-hover"><thead>
			        <tr><th width="20px" class="center">No</th>';
		        foreach ($fields as $kf => $vf) {
		        	$html .= '<th>'.ucfirst($kf).'</th>';
		        }
      	$html .='</thead>';
      	$html .='<tbody>';
		  $no=0;
		$sum_arr = array();
      	foreach ($data as $key => $value) { $no++;
      		$html .='<tr>';
	      	$html .='<td align="center">'.$no.'</td>';
	      	foreach ($fields as $keyf => $valuef) {
				$align = (strtolower($valuef)=='total_format_money')?'right':'left';
				if( in_array($valuef, array('total_format_money', 'total') ) ){
					$format_value = number_format($value[$valuef]);
					$sum_arr[] = $value[$valuef];
				}elseif(  $valuef=='bulan' ){
					// format bulan
					$format_value = $CI->tanggal->getBulan($value[$valuef]);
				}else{
					$format_value = $value[$valuef];
				}
				
				
	      		$html .='<td align="'.$align.'">'.ucwords(strtolower($format_value)).'</td>';
	      	}
	      	$html .='</tr>';
		}

		$html .= '<tr>';
		$html .= '<td colspan="2" align="right"><b>Jumlah Total</b></td>';
		$html .= '<td align="right">'.number_format(array_sum($sum_arr)).'</td>';
		$html .= '</tr>';
      	
      	$html .='</tbody>';
      	$html .='</table>';

        $chart_data = array(
			'xAxis' 	=> 0,
			'series' 	=> $html,
		);
		return $chart_data;
    }
	
}

?> 