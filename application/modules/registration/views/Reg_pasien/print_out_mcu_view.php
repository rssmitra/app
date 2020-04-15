<!-- 
<script src="../assets_als/barcode-master/prototype/sample/prototype.js" type="text/javascript"></script>
<script src="../assets_als/barcode-master/prototype/prototype-barcode.js" type="text/javascript"></script>
<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){
    $("barcodeTarget").update();
    var value = "0112R034000001";
    var btype = "code128";
    
    var settings = {
      output:"css",
      bgColor: "#FFFFFF",
      color: "#000000",
      barWidth: 1,
      barHeight: 50,
      moduleSize: 5,
      posX: 10,
      posY: 20,
      addQuietZone: false
    };

    $("barcodeTarget").update().show().barcode(value, btype, settings);

  }
    
</script>  -->
<style type="text/css">
    .stamp {
      margin-top: -30px;
      margin-left: 175px;
      position: absolute;
      display: inline-block;
      color: black;
      padding: 1px;
      padding-left: 10px;
      padding-right: 10px;
      background-color: white;
      box-shadow:inset 0px 0px 0px 7px black;
      opacity: 0.5;
      -webkit-transform: rotate(25deg);
      -moz-transform: rotate(25deg);
      -ms-transform: rotate(25deg);
      -o-transform: rotate(25deg);
      transform: rotate(0deg);
    }
    body table {
        font-family: arial;
        font-size: 12px
    }

    .table-content th {
      height: 30px;
      border-bottom: 1px solid #ddd;
      border-top: 1px solid #ddd;
      text-align: left;
      padding: 8px
    }

    /*.table-content td {
      border: 1px solid #ddd;
    }*/

</style>
<body>
<table border="0">
<tr>
  <td>
  <img src="<?php echo base_url().COMP_ICON; ?>" style="width:70px">
  </td>
  <td style="padding-left:30px;">
  <b>FORM CHECKLIST MCU<br><?php echo strtoupper(COMP_LONG); ?></b>
  </td>
  <!-- <td align="right"><div class="stamp"><h1> Advanced Type 1 </h1></div></td> -->
</tr>
</table>
</br>
<span style="font-family: arial; font-size: 14px"><b>Data Pasien</b></span>
<table border="0">
<tr>
  <td width="150px">No. MR</td><td>: <?php echo $_GET['no_mr']?></td>
</tr>
<tr>
  <td width="150px">Nama Pasien</td><td>: <?php echo $_GET['nama']?></td>
</tr>
<tr>
  <td width="150px">Paket MCU</td><td>: <?php echo $paket_mcu?></td>
</tr>
</table>
<br>
<span style="font-family: arial; font-size: 14px"><b>Checklist Pelayanan MCU</b></span>
<table border="0" width="100%" class="table-content">
<tr>
  <th style="text-align: center !important; width: 20px; ">No</th>
  <th width="10px">&nbsp;</th>
  <th>Bagian / Unit Tujuan</th>
  <th>Petugas</th>
  <th>Paraf</th>
</tr>
<?php 
  $no=0; 
    foreach($value as $key=>$row) : 
      $no++; 
?>
<tr>
  <td align="center"><?php echo $no?></td>
  <td align="center"><input type="checkbox" name=""></td>
  <td><b><?php echo strtoupper($key)?></b></td>
  <td></td>
  <td></td>
</tr>
<?php 
  foreach($row as $row_detail) :
?>
<tr>
  <td align="center"></td>
  <td align="center"></td>
  <td><?php echo ucwords($row_detail->detail_paket_mcu)?></td>
  <td></td>
  <td></td>
</tr>
<?php endforeach; ?>
<?php endforeach; ?>
</table>
<br>
<table border="0" width="100%">
<tr>
  <td valign="top">
      <i>Berikan form cheklist ini ke petugas setiap kali akan dilakukan pemeriksaan </i>
  </td>
  <td valign="bottom" style="padding-top:25px" align="right">
    Jakarta, .......................... <br><br><br><br>_________________________
  </td>
</tr>
</table>
</body>

