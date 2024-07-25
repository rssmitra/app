<?php 
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=".$title.'_'.date('Ymd').".xls");  //File name extension was wrong
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false);
  
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

      <center><h4>STOK OPNAME <?php echo ($title)?strtoupper($title):'GUDANG NON MEDIS'?></h4></center>

      <div style="width:200px">
        Tanggal Stok Opname : <?php echo isset($value->agenda_so_date)?$value->agenda_so_date:'-'?><br>
        Penanggung Jawab : <?php echo isset($value->agenda_so_spv)?$value->agenda_so_spv:'-'?><br>
        Bagian / Unit : <?php echo isset($title)?$title:'-'?><br>
      </div>
      <br>
      <table class="table" border="1" style="font-size:14px !important">
        <thead>
          <tr>
            <th class="center">NO</th>
            <th>KODE BARANG</th>
            <th>NAMA BARANG</th>
            <th class="center">STOK SEBELUM</th>
            <th class="center">HASIL SO</th>
            <th class="center">HARGA SATUAN PEMBELIAN</th>
            <th align="right">TOTAL</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            foreach($result_content as $row_data) : $no++; 
            $harga = $row_data->stok_sekarang * $row_data->harga_pembelian_terakhir;
            $content = isset($row_data->content)?$row_data->content:0;
            $hpa = ($content > 0) ? $harga / $content : 0;
            $total = $row_data->stok_sekarang * $hpa;

            $arr_total[] = $total;
          ?>
            <tr>
              <td align="center" style="width:50px !important; overflow-wrap: break-word;"><?php echo $no;?></td>
              <td style="width:100px !important; overflow-wrap: break-word;"><?php echo $row_data->kode_brg;?></td>
              <td style="width:200px !important; overflow-wrap: break-word;"><?php echo $row_data->nama_brg;?></td>
              <td style="width:100px !important; overflow-wrap: break-word;"><?php echo $row_data->stok_sebelum;?></td>
              <td style="width:100px !important; overflow-wrap: break-word;"><?php echo $row_data->stok_sekarang;?></td>
              <td style="width:100px !important; overflow-wrap: break-word;"><?php echo $row_data->harga_pembelian_terakhir;?></td>
              <td style="width:100px !important; overflow-wrap: break-word;"><?php echo $total?></td>
            </tr>
          <?php endforeach; ?>
          <tr><td style="width:100px !important; overflow-wrap: break-word;" colspan="6" align="right">JUMLAH  </td>
             <td style="width:100px !important; overflow-wrap: break-word;"><?php echo array_sum($arr_total);?></td>
        </tbody>
      </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






