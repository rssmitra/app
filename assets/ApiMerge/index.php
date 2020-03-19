<?php

/*check directory is exist*/
$filename = '../uploaded/casemix/merge-'.$_GET['month'].'-'.$_GET['year'];
//echo '<pre>';print_r($_GET);die;
if (file_exists($filename)) {
    //echo "The file $filename exists";
} else {
    mkdir($filename, 0777, true);
    mkdir($filename.'/RI', 0777, true);
    mkdir($filename.'/RJ', 0777, true);
}

include 'PDFMerger-master/PDFMerger.php';

$pdf = new PDFMerger; // or use $pdf = new \PDFMerger; for Laravel

foreach ($_GET as $key => $value) {
	if ( !in_array($key, array('action','noreg','sep','tipe','month','year'))) {
		# code...
		$pdf->addPDF('../uploaded/casemix/'.$_GET[$key].'', 'all');	
		//$getData[] = $_GET[$key];
	}
}
//echo '<pre>';print_r($getData);die;
//$pdf->merge('file', 'libraries/merge_files/samplepdfs/TEMP.pdf'); // generate the file
if($_GET['action']=='download'){
	//$pdf->merge('file', 'rssm/sirs-v2/uploaded/casemix/fixed_document/'.$_GET['sep'].'.pdf'); 
	$pdf->merge('file', 'rssm/sirs-v2'.str_replace('..','',$filename).'/'.$_GET['tipe'].'/'.$_GET['sep'].'.pdf'); 
}else{
	$pdf->merge($_GET['action'], ''.$_GET['sep'].'.pdf'); // force download
}
$pdf->merge('browser', ''.$_GET['sep'].'.pdf'); // force download




// REPLACE 'file' WITH 'browser', 'download', 'string', or 'file' for output options

?>