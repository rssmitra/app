<?php 

  if($_POST['submit']=='format_so') {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$result['data'][0]->nama_bagian.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  }

?>

<html>
<head>
  <title>Format Stok Opname</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
</head>
<body style="background-color: white; font-family: 'calibri'">
  <div class="row">
    <div class="col-xs-12">

      <center><h4>FORM STOK OPNAME <?php echo ($result['data'][0]->nama_bagian)?strtoupper($result['data'][0]->nama_bagian):'GUDANG NON MEDIS'?></h4></center>

      <div style="width:200px">
        Tanggal Stok Opname :<br>
        Nama Petugas :
      </div>
      <br>
      <table class="table" border="1" style="font-size:14px !important">
        <thead>
          <tr>
            <th class="center">NO</th>
            <th>KODE</th>
            <th>NAMA BARANG</th>
            <th class="center">SATUAN<br>KECIL/BESAR</th>
            <th>GOLONGAN<br>(Gol Terkecil)</th>
            <th class="center">STOK KARTU</th>
            <th class="center">STOK FISIK</th>
            <th class="center">KETERANGAN</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            foreach($result['data'] as $row_data) : $no++; 
            $satuan = ($row_data->satuan_kecil==$row_data->satuan_besar) ? $row_data->satuan_kecil : $row_data->satuan_kecil.'/ '.$row_data->satuan_besar;
          ?>
            <tr>
              <td align="center" style="width:50px !important; overflow-wrap: break-word;"><?php echo $no;?></td>
              <td style="width:100px !important; overflow-wrap: break-word;"><?php echo $row_data->kode_brg;?></td>
              <td style="width:200px !important; overflow-wrap: break-word;"><?php echo $row_data->nama_brg;?></td>
              <td align="center" style="width:120px !important; overflow-wrap: break-word;"><?php echo $satuan;?></td>
              <td style="width:200px !important; overflow-wrap: break-word;"><?php echo $row_data->nama_golongan;?></td>
              <td style="width:80px !important; overflow-wrap: break-word;"></td>
              <td style="width:80px !important; overflow-wrap: break-word;"></td>
              <td style="width:120px !important; overflow-wrap: break-word;"></td>
              
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






