<?php
date_default_timezone_set("Asia/Jakarta");

$serverName = "10.10.11.5";
$connectionInfo = array( "Database"=>"rls_rssm_sirs", "UID"=>"sa", "PWD"=>"4v3r1n-averin");

$conn = sqlsrv_connect( $serverName, $connectionInfo );
if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true));
}

$sql = "SELECT * FROM tr_antrian where ant_type='umum'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql , $params, $options );

$row_count = sqlsrv_num_rows( $stmt );


$sql_bpjs = "SELECT * FROM tr_antrian where ant_type='bpjs'";
$params_bpjs = array();
$options_bpjs =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_bpjs = sqlsrv_query( $conn, $sql_bpjs , $params_bpjs, $options_bpjs );

$row_count_bpjs = sqlsrv_num_rows( $stmt_bpjs );
   
$arr_umum = array();
$arr_bpjs = array();

while( $value = sqlsrv_fetch_array( $stmt ) ) {
    $log = json_decode($value['log'], true);
    $log['nomor'] = $value['ant_no'];
    $log['type'] = $value['ant_type'];

    $arr_umum[]=$log;
}

while( $value = sqlsrv_fetch_array( $stmt_bpjs ) ) {
    $log = json_decode($value['log'], true);
    $log['nomor'] = $value['ant_no'];
    $log['type'] = $value['ant_type'];

    $arr_bpjs[]=$log;
}


$json = json_encode($arr_umum);
$json2= json_encode($arr_bpjs);
$tgl = date('Y-m-d');
$created_date = date('Y-m-d h:i:s');

$tsql= "INSERT INTO log_antrian (
        tanggal,
        jumlah_umum,
        log_umum,
        jumlah_bpjs,
        log_bpjs,
        created_date) 
        VALUES
        ('".$tgl."', ".$row_count.", '".$json."', ".$row_count_bpjs.", '".$json2."', '".$created_date."')";


$res = sqlsrv_query( $conn, $tsql);
if( $res === false ) {
     die( print_r( sqlsrv_errors(), true));
}


?>



