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
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/blue.css"/>
</head>
<body>
  <div class="row">
    <div class="col-xs-12">

      <center><h4><?php echo $title?></h4></center>
      <b>Parameter :</b> <i><?php echo print_r($_POST);?></i>

      <table class="table" border="0">
        <tbody>
        <tr class="greyGridTable">
          <td rowspan="3">No</td>
          <td rowspan="3">Nama Tarif</td>
          <td colspan="63">Klas Tarif</td>
        </tr>
        <tr>
          <!-- nama klas -->
           <?php foreach($klas as $row_klas){
            echo "<td colspan='8'>".$row_klas->nama_klas."</td>";
           }?>
        </tr>
        <tr>
        <?php foreach($klas as $row_klas) :?>
          <td>Jasa Dr 1</td>
          <td>Jasa Dr 2</td>
          <td>BHP</td>
          <td>Kamar Tindakan</td>
          <td>Alkes/Alat RS</td>
          <td>Administrasi</td>
          <td>Pendapatan RS</td>
          <td>Total</td>
        <?php endforeach; ?>
        </tr>
        <?php 
          $no=1;
          foreach($result as $k=>$r){
            $no++;
            echo "<tr>";
            echo "<td>".$no."</td>";
            echo "<td>".$k."</td>";
            foreach($klas as $row_klas){
              $dtbyklas = isset($r[$row_klas->kode_klas]) ? $r[$row_klas->kode_klas] : '';
              // get max key
              $max_key = (!empty($dtbyklas)) ? max(array_keys($dtbyklas)) : '';
              $dt = isset($dtbyklas[$max_key])?$dtbyklas[$max_key]:'';
              // $bill_dr1 = $dt->bill_dr1;
              echo "<td>". $dt->bill_dr1 ."</td>";
              echo "<td>". $dt->bill_dr2 ."</td>";
              echo "<td>". $dt->bhp ."</td>";
              echo "<td>". $dt->kamar_tindakan ."</td>";
              echo "<td>". $dt->alat_rs ."</td>";
              echo "<td>". $dt->adm ."</td>";
              echo "<td>". $dt->pendapatan_rs ."</td>";
              echo "<td>". $sum = ($dt->bill_dr1 + $dt->bill_dr2 + $dt->bhp + $dt->kamar_tindakan + $dt->alat_rs + $dt->adm + $dt->pendapatan_rs ) ."</td>";
              // echo "<td>".($dt->bill_dr1)?$dt->bill_dr1:''."</td>";
              // echo "<td>".($dt->bill_dr2)?$dt->bill_dr2:''."</td>";
              // echo "<td>".($dt->bhp)?$dt->bhp:''."</td>";
              // echo "<td>".($dt->kamar_tindakan)?$dt->kamar_tindakan:''."</td>";
              // echo "<td>".($dt->alat_rs)?$dt->alat_rs:''."</td>";
              // echo "<td>".($dt->adm)?$dt->adm:''."</td>";
              // echo "<td>".($dt->pendapatan_rs)?$dt->pendapatan_rs:''."</td>";
              // echo "<td>".($dt->bill_dr1)?$dt->bill_dr1:''."</td>";
            }
            echo "</tr>";
          }
        ?>

        </tbody>
      </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






