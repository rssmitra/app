<?php

if( ! ini_get('date.timezone') )
{
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

$filename_mr = '../uploaded/rekam_medis/'.$_GET['no_mr'];
if (file_exists($filename_mr)) {
    //echo "The file $filename exists";
} else {
    mkdir($filename_mr, 0777, true);
}

// echo '<pre>';print_r($_GET);die;

include 'PDFMerger-master/PDFMerger.php';

$pdf = new PDFMerger; // or use $pdf = new \PDFMerger; for Laravel

$file_name_merge_emr = 'EMR-'.$_GET['noreg'];

foreach ($_GET as $key => $value) {
	if ( !in_array($key, array('action','noreg','sep','tipe','month','year','no_mr'))) {
		// untuk file casemix
		$pdf->addPDF('../uploaded/casemix/log/'.$_GET[$key].'', 'all');		
		// $getData[] = $_GET[$key];
	}
}

if($_GET['action']=='download'){
	// casemix
	$pdf->merge('file', 'sirs/app'.str_replace('..','',$filename).'/'.$_GET['tipe'].'/'.$_GET['sep'].'.pdf'); 
	// emr
	$pdf->merge('file', 'sirs/app'.str_replace('..','',$filename_mr).'/'.$file_name_merge_emr.'.pdf');
}else{
	$pdf->merge($_GET['action'], ''.$_GET['sep'].'.pdf'); 
}
$pdf->merge('browser', ''.$_GET['sep'].'.pdf');

?>