<?php

	if( ! ini_get('date.timezone') )
	{
		date_default_timezone_set('GMT');
		date_default_timezone_set('Asia/Bangkok');
	}

	/*check directory is exist*/
	$filename = '../uploaded/casemix/merge-'.$_GET['month'].'-'.$_GET['year'];
	if (file_exists($filename)) {
		//echo "The file $filename exists";
	} else {
		mkdir($filename, 0777, true);
		mkdir($filename.'/RI', 0777, true);
		mkdir($filename.'/RJ', 0777, true);
	}

	include 'PDFMerger-master/PDFMerger.php';

	$pdf = new PDFMerger; // or use $pdf = new \PDFMerger; for Laravel

	$file_name_merge_emr = 'EMR-'.$_GET['noreg'];

	sscanf($_GET['date'], '%d-%d-%d', $y, $m, $d);
	// echo '<pre>';print_r($y);
	// echo '<pre>';print_r($m);
	// echo '<pre>';print_r($_GET);
	// exit;

	$file_grouper = '../uploaded/casemix/grouper/'.$y.'/'.$m.'/'.$d.'/'.$_GET['sep'].'.pdf';
	if(file_exists($file_grouper)){
		$pdf->addPDF('../uploaded/casemix/grouper/'.$y.'/'.$m.'/'.$d.'/'.$_GET['sep'].'.pdf', 'all');	
	}

	foreach ($_GET as $key => $value) {
		if ( !in_array($key, array('action','noreg','sep','tipe','month','year','no_mr','date',''))) {
			// untuk file casemix
			$split = explode('.', $value);
			if(isset($split[1]) && $split[1] == 'pdf'){
				// $pdf->addPDF('../uploaded/casemix/log/'.$_GET[$key].'', 'all');		
				$pdf->addPDF('../uploaded/casemix/log/'.$_GET[$key].'', 'all');		
			}
			// $getData[] = $_GET[$key];
		}
	}

	// echo '<pre>';print_r($filename);die;
	if($_GET['action']=='download'){
		// casemix
		$pdf->merge('file', 'sirs-dev/app'.str_replace('..','',$filename).'/'.$_GET['tipe'].'/'.$_GET['sep'].'.pdf');
	}else{
		$pdf->merge($_GET['action'], ''.$_GET['sep'].'.pdf'); 
	}

	$pdf->merge('browser', ''.$_GET['sep'].'.pdf');


?>