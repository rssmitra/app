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

	foreach ($_GET as $key => $value) {
		if ( !in_array($key, array('action','kode','sep','tipe','month','year'))) {
			// untuk file farmasi
			$pdf->addPDF('../uploaded/farmasi/log/'.$_GET[$key].'', 'all');	
		}
	}

	if($_GET['action']=='download'){
		// farmasi
		$path = str_replace('..','',$filename).'/'.$_GET['sep'].'.pdf';
		if (file_exists($path)) {
			unlink($path);
		}
		$pdf->merge('file', 'sirs/app'.$path); 
	}else{
		$pdf->merge($_GET['action'], ''.$_GET['sep'].'.pdf'); 
	}
	$pdf->merge('browser', ''.$_GET['sep'].'.pdf');

?>