<?php
	if( ! ini_get('date.timezone') )
	{
	    date_default_timezone_set('Asia/Bangkok');
	}

	/*check directory is exist*/
	$filename = '../uploaded/farmasi/merge-'.$_GET['month'].'-'.$_GET['year'];
	if (file_exists($filename)) {
	    //echo "The file $filename exists";
	} else {
	    mkdir($filename, 0777, true);
	}

	include 'PDFMerger-master/PDFMerger.php';

	$pdf = new PDFMerger; // or use $pdf = new \PDFMerger; for Laravel
	// echo '<pre>';
	// print_r($_GET);exit;
	foreach ($_GET as $key => $value) {
		if ( !in_array($key, array('action','kode','sep','tipe','month','year','addfilesscan'))) {
			// untuk file farmasi
			$fileex = '../uploaded/farmasi/log/'.$_GET[$key].'';
			if(file_exists($fileex)){
				$pdf->addPDF('../uploaded/farmasi/log/'.$_GET[$key].'', 'all');	
			}
		}
	}

	// substr sep
	$substr_sep = substr($_GET['sep'], -4);
	if(isset($_GET['addfilesscan'])){
		sscanf($_GET['addfilesscan'], '%d-%d-%d', $y, $m, $d);
		$file_scan = '../uploaded/farmasi/scan_'.$m.$y.'/'.$_GET['addfilesscan'].'/'.$substr_sep.'.pdf';
		if(file_exists($file_scan)){
			$pdf->addPDF('../uploaded/farmasi/scan_'.$m.$y.'/'.$_GET['addfilesscan'].'/'.$substr_sep.'.pdf', 'all');	
		}
	}

	if($_GET['action']=='download'){
		// farmasi
		$path = str_replace('..','',$filename).'/'.$_GET['sep'].'.pdf';
		if (file_exists($path)) {
			unlink($path);
		}
		$pdf->merge('file', 'sirs-dev/app'.$path); 
	}else{
		$pdf->merge($_GET['action'], ''.$_GET['sep'].'.pdf'); 
	}
	$pdf->merge('browser', ''.$_GET['sep'].'.pdf');

?>