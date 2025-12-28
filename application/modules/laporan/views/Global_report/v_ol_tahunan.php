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
switch($jenis){
     case "Obat":
       $nama_jenis= "OBAT";
     break;
     case "Alkes":
       $nama_jenis= "ALKES";
     break;
     case "All":
       $nama_jenis= "SELURUHNYA";
     break;
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
              <td colspan="3">Laporan Pemesanan Resep </td>
            </tr>
            <tr class="subTitle">
              <td width="15%">OBAT/ALKES </td>
              <td><?php echo $nama_jenis ?></td>
            </tr>
           
            <tr class="subTitle">
              <td width="15%">PERIODE </td>
              <td><?php echo $tahun ?></td>
            </tr>
            
          </tbody>
        </table>
      <table class="table">
          <tbody>
            <tr>
              <td class="border-rb" width="25" rowspan="1" colspan="1">No.</td>
              <td width="239" colspan="1" rowspan="1" align="center"><b>Nama Obat</b></td>
              <td width="86" rowspan="1" align="center"><b>Kode</b></td>
              <td width="134" colspan="1" rowspan="1" align="center"><b>Satuan</b></td>
              <td width="86" rowspan="1" align="center"><b>Content</b></td>
              <td width="165" rowspan="1" align="center"><b>Jumlah Satuan Besar</b></td>
              <td width="165" colspan="1" rowspan="1" align="center"><b>Harga Sat Kecil</b></td>
              <td width="99" colspan="1" rowspan="1" align="center"><b>Qty</b></td>
              <td width="166" colspan="1" rowspan="1" align="center"><b>Jumlah</b></td>
              <td width="253" colspan="1" rowspan="1" align="center"><b>Pabrik</b></td>
              
            </tr>
            
          <?php
           $no = 0; 
           $sub_harga_beli=0;
          foreach($result as $row_data){
            // $barang=$this->db->query('select id_pabrik  from mt_barang  WHERE kode_brg='."'".$row_data->kode_brg."'".'')->result();
            // $pabrik=$this->db->query('select nama_pabrik  from mt_pabrik  WHERE id_pabrik='."'".$barang->id_pabrik."'".'')->result();
            $no ++;
               
                $nama_brg = $row_data->nama_brg;
                $kode_brg = $row_data->kode_brg;
                //$kode_brg = $row_data->kode_brg;
                //$kode_brg = $row_data->kode_brg;
                $satuan_kecil = $row_data->satuan_kecil;
                $satuan_besar = $row_data->satuan_besar;
                $nama_pabrik = $row_data->nama_pabrik;
                $jml_harga = $row_data->jumlah;
                $jml_besar = $row_data->jml;
                $content = $row_data->content;
                $jml_kcl = $jml_besar*$content;
                if($jml_besar<>'' && $jml_harga<>''){
                  $harga_beli = round($jml_harga/$jml_besar);
                  $harga_sat_kcl = round($harga_beli/$content);
                }
                
                $sub_harga_beli = $sub_harga_beli + $jml_harga;
            ?>
            <tr class="contentTable">
              <td align="right" width="25"><?php echo   $no?>.</td>
              <td align="left" width="">&nbsp;<?php echo $nama_brg?>&nbsp;</td>
              <td align="left" width="">&nbsp;<?php echo $kode_brg?>&nbsp;</td>
              <td align="left" width=""><?php echo $satuan_kecil?>&nbsp;</td>
              <td align="left" width="">&nbsp;<?php echo $content?>&nbsp;</td>
              <td align="left" width="">&nbsp;<?php echo $jml_besar?>&nbsp;</td>
              <td align="right" width=""><?php echo ($_POST['submit']=='excel') ? $harga_sat_kcl : number_format($harga_sat_kcl)?></td>
              <td align="right" width=""><?php echo ($_POST['submit']=='excel') ? $jml_kcl : number_format($jml_kcl)?></td>
              <td align="right" width=""><?php echo ($_POST['submit']=='excel') ? $jml_harga : number_format($jml_harga)?></td>
              <td width="">&nbsp;<?php echo $nama_pabrik?>&nbsp;</td>
            </tr>
            <?php
            }
            ?>
           <tr class="contentTable">
              <td align="right" width="25" colspan="6">Total
                <td align="right" width="25"><?php echo number_format($sub_harga_beli)?></td>
            </tr>
          </tbody>
        </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






