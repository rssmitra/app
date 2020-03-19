<?php 

  if($_POST['submit']=='excel') {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$flag.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  }

?>

<html>
<head>
  <title>Laporan Umum</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
</head>
<body>
  <?php 
  $i=0;
  $jmlRacikan=0;
  $jmlnonRacikan=0;
  $tgl_transaksi_lama =0;
    foreach ($result['data'] as $row_data){
          
    $i++;

    $tahun_trans        =$row_data->tahun_trans;
    $bln_trans          =$row_data->bln_trans;
    $tgl_tran           =$row_data->tgl_tran;
    $id_tc_far_racikan  =$row_data->id_tc_far_racikan;
    $tgl_transaksi      =$tgl_tran."-".$bln_trans."-".$tahun_trans;
    

    if($tgl_transaksi_lama != $tgl_transaksi ){

      $jmlRacikan   =0;
      $jmlnonRacikan  =0;

    }
    $tgl_transaksinya[$tgl_transaksi]=$tgl_transaksi;
    if($id_tc_far_racikan > 0){
      
      $jmlRacikan ++;
      $totalRacikan[$tgl_transaksi] =$jmlRacikan;

    }else{

      $jmlnonRacikan ++;
      $totalnonRacikan[$tgl_transaksi] =$jmlnonRacikan;

    }
    
    $tahun_trans_lama = $tgl_transaksi;
    $bln_trans_lama   = $bln_trans;
    $tgl_tran_lama    = $tgl_tran;
    $tgl_transaksi_lama = $tgl_transaksi;

  }
 ?>

  <div class="row">
    <div class="col-xs-12">

      <center><h4><?php echo $title?></h4></center>
<table class="table">
          <tbody>
            <tr class="mainTitleLeft">
              <td colspan="3" style="white-space: nowrap; color: #000099;">
              </td>
            </tr>
            <tr class="mainTitle">
              <td colspan="3">LAPORAN PENJUALAN OBAT RACIKAN DAN NON RACIKAN</td>
            </tr>
            
            <tr class="subTitle">
              <td width="15%">PERIODE </td>
              <td>:</td>
              <td><?php echo $tgl1 ?> s/d <?php echo $tgl2 ?></td>
            </tr>
            
          </tbody>
        </table>
      <table class="table">
          <tbody>
            <tr>
              <td class="border-rb" width="25" rowspan="1" colspan="1">No.</td>
              <td class="border-rb" width="" rowspan="1" colspan="1">Tanggal</td>
              <td class="border-rb" width="" rowspan="1" colspan="1">Racikan</td>
              <td class="border-rb" width="" rowspan="1" colspan="1">Non Racikan</td>
              
            </tr>
            
          <?php
          $totnonRacikan=0;
          $totRacikan=0;
            if(is_array($tgl_transaksinya)){
              $j=0;
              foreach($tgl_transaksinya as $key=>$value ){
              $j++;
                $racikannya       = $totalRacikan[$key];
                $nonRacikannya    = $totalnonRacikan[$key];
                $totRacikan       += $racikannya;
                $totnonRacikan    += $nonRacikannya;
                
            ?>
            
            <tr class="contentTable">
              <td align="right" width="25"><?php echo $j ?>.</td>
              <td align="left" width=""><?php echo $value?>&nbsp;</td>
              <td align="right" width=""><?php echo $racikannya?>&nbsp;</td>
              <td align="right" width=""><?php echo $nonRacikannya?>&nbsp;</td>
            </tr>
            
            <?php
              }
              }
            ?>
            <tr class="contentTable">
              <td align="right" width="25">&nbsp;</td>
              <td align="left" width=""><!-- TOTAL -->&nbsp;</td>
              <td align="right" width=""><?php echo $totRacikan ?>&nbsp;</td>
              <td align="right" width=""><?php echo $totnonRacikan ?>&nbsp;</td>
              
            </tr>
           
          </tbody>
        </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






