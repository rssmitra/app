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

      <!-- <center><h4>STOK OPNAME <?php echo ($title)?strtoupper($title):'GUDANG NON MEDIS'?></h4></center>
      <div style="width:200px">
        Tanggal Stok Opname : <?php echo isset($value->agenda_so_date)?$value->agenda_so_date:'-'?><br>
        Penanggung Jawab : <?php echo isset($value->agenda_so_spv)?$value->agenda_so_spv:'-'?><br>
        Bagian / Unit : <?php echo isset($title)?$title:'-'?><br>
      </div> -->
      <br>
      <table class="table" border="1" style="font-size:14px !important">
        <thead>
          <tr>
            <th class="center">NO</th>
            <th>KODE</th>
            <th>NAMA BARANG</th>
            <th class="center">HARGA SATUAN<br>RATA-RATA</th>
            <th class="center">STOK SEBELUM</th>
            <th class="center">HASIL SO</th>
            <th class="center">EXPIRED</th>
            <th class="center">EXPIRED -3 Bln</th>
            <th class="center">SATUAN<br>KECIL/ BESAR</th>
            <th class="center">TOTAL PERSEDIAAN</th>
            <th class="center">TOTAL EXPIRED</th>
            <th class="center">TOTAL EXPIRED<br>-3 BLN</th>
            <th class="center">STATUS</th>
            <th class="center">NAMA PETUGAS</th>
            <th class="center">WAKTU INPUT</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            $totalhasil=0;
            $arr_totalhasil_exp=0;
            $arr_totalhasil_will_exp=0;
            foreach($result_content as $row_data) : $no++; 
            $satuan = ($row_data->satuan_kecil==$row_data->satuan_besar) ? $row_data->satuan_kecil : $row_data->satuan_kecil.'/ '.$row_data->satuan_besar;
            $content = $row_data->content;
            $hreal = ( $row_data->harga_pembelian_terakhir > 0 AND $content > 0) ? ($row_data->harga_pembelian_terakhir / $content) : 0;
            $total = $hreal  * $row_data->stok_sekarang;
            $total_exp = $hreal  * $row_data->stok_exp;
            $total_will_exp = $hreal  * $row_data->will_stok_exp;
            $totalhasil = $totalhasil + $total;
            $arr_totalhasil_exp = $arr_totalhasil_exp + $total_exp;
            $arr_totalhasil_will_exp = $arr_totalhasil_will_exp + $total_will_exp;
            $status = ($row_data->set_status_aktif == 0)?'Tidak Aktif':'Aktif';
          ?>
            <tr>
              <td align="center" style="width:50px !important; overflow-wrap: break-word;"><?php echo $no;?></td>
              <td style="width:100px !important; overflow-wrap: break-word;"><?php echo $row_data->kode_brg;?></td>
              <td style="width:200px !important; overflow-wrap: break-word;"><?php echo $row_data->nama_brg;?></td>
              <td style="width:200px !important; overflow-wrap: break-word;"><?php echo (int)$hreal;?>
              <td style="width:100px !important; overflow-wrap: break-word; text-align: center;"><?php echo $row_data->stok_sebelum;?></td>
              <td style="width:100px !important; overflow-wrap: break-word; text-align: center;"><?php echo $row_data->stok_sekarang;?></td>
              <td style="width:100px !important; overflow-wrap: break-word; text-align: center;"><?php echo $row_data->stok_exp;?></td>
              <td style="width:100px !important; overflow-wrap: break-word; text-align: center;"><?php echo $row_data->will_stok_exp;?></td>
              <td align="center" style="width:120px !important; overflow-wrap: break-word;"><?php echo $satuan;?></td>
                
              </td>

              <td style="width:200px !important; overflow-wrap: break-word;"><?php echo (int)$total;?></td>
              <td style="width:200px !important; overflow-wrap: break-word;"><?php echo (int)$total_exp;?></td>
              <td style="width:200px !important; overflow-wrap: break-word;"><?php echo (int)$total_will_exp;?></td>
              <td style="width:200px !important; overflow-wrap: break-word;text-align: center"><?php echo $status;?></td>
              <td style="width:200px !important; overflow-wrap: break-word;"><?php echo $row_data->nama_petugas;?></td>
              <td style="width:200px !important; overflow-wrap: break-word;"><?php echo $this->tanggal->formatDateTime($row_data->tgl_stok_opname);?></td>
              
            </tr>
          <?php endforeach; ?>
          <tr>
            <td style="width:100px !important; overflow-wrap: break-word;" colspan="9" align="right">JUMLAH  </td>
             <td style="width:100px !important; overflow-wrap: break-word;"><?php echo (int)$totalhasil;?></td>
             <td style="width:100px !important; overflow-wrap: break-word;"><?php echo (int)$arr_totalhasil_exp;?></td>
             <td style="width:100px !important; overflow-wrap: break-word;"><?php echo (int)$arr_totalhasil_will_exp;?></td>
             <td></td>
             <td></td>
             <td></td>
        </tbody>
      </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






