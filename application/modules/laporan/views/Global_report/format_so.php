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
<body style="background-color: white; font-family: 'calibri'; font-size:14px !important">
  <div class="row">
    <div class="col-xs-12">

      <center><h4>FORM STOK OPNAME <?php echo ($result['data'][0]->nama_bagian)?strtoupper($result['data'][0]->nama_bagian):'GUDANG NON MEDIS'?></h4></center>

      <div style="width:200px">
        Tanggal Stok Opname : <br>
        Nama Petugas :
      </div>
     
<?php if($_POST['flag_string'] == 'non_medis') : ?>
      <span style="font-size: 16px; font-weight: bold">Golongan : <?php echo ($result['data'][0]->nama_golongan)?strtoupper($result['data'][0]->nama_golongan):'-'?> </span>
      <?php endif; ?>

      <?php if($_POST['flag_string'] == 'medis') : ?>
      <span style="font-size: 16px; font-weight: bold">Kategori  : 
      <?php echo ($result['data'][0]->nama_kategori)?strtoupper($result['data'][0]->nama_kategori):'-'?>  </span>
      <?php endif; ?>

      <!-- table for data barang non medis -->
      <table class="table" border="1" style="font-size:14px !important">
        <thead>
          <tr>
            <th class="center">NO</th>
            <th class="center">KODE</th>
            <th class="center">NAMA BARANG</th>
            <?php if($_POST['flag_string'] == 'non_medis') : ?>
            <th class="center">GOLONGAN</th>
            <th class="center">SUB GOLONGAN</th>
          <?php endif; ?>

          <?php if($_POST['flag_string'] == 'medis') : ?>
            <th class="center">GOLONGAN</th>
            <th class="center">JENIS</th>
          <?php endif; ?>
            
            <th class="center">SATUAN KCL</th>
            <th class="center">STATUS AKTIF</th>
            <th class="center">STOK AKHIR</th>
            <th class="center">STOK KARTU</th>
            <th class="center">STOK FISIK</th>
            <th class="center">KETERANGAN</th>
          </tr>
        </thead>
        <tbody>
          <?php                                                                                                                                                                    
            $no = 0; 
            foreach($result['data'] as $row_data) : $no++; 
            $satuan = ($row_data->satuan_kecil==$row_data->satuan_besar) ? $row_data->satuan_kecil : $row_data->satuan_kecil;
          ?>
            <tr>
              <td align="center" style="width:50px !important; overflow-wrap: break-word;vertical-align: middle"><?php echo $no;?></td>
              <td style="width:90px !important; overflow-wrap: break-word; vertical-align: middle"><?php echo $row_data->kode_brg;?></td>
              <td style="width:230px !important; overflow-wrap: break-word; vertical-align: middle"><?php echo $row_data->nama_brg;?></td>
               <?php if($_POST['flag_string'] == 'non_medis') : ?>

              <td align="center" style="width:120px !important; overflow-wrap: break-word;vertical-align: middle">
                <?php echo $row_data->nama_golongan;?>
                <td align="center" style="width:120px !important; overflow-wrap: break-word;vertical-align: middle"> <?php echo $row_data->nama_sub_golongan;?></td>
             
              <?php endif; ?>

              <?php if($_POST['flag_string'] == 'medis') : ?>

                 <td align="center" style="width:120px !important; overflow-wrap: break-word;vertical-align: middle">
                  <?php echo $row_data->nama_golongan;?>
               <td align="center" style="width:120px !important; overflow-wrap: break-word;vertical-align: middle">
                <?php echo $row_data->nama_jenis;?>
                              
              <?php endif; ?>

              <td align="center" style="width:120px !important; overflow-wrap: break-word;vertical-align: middle"><?php echo $satuan;?></td>
              <td align="center" style="width:100px !important; overflow-wrap: break-word;vertical-align: middle"><?php echo ($row_data->is_active == 1)?'AKTIF':'NON AKTIF' ;?></td>
              <td align="center" style="width:100px !important; overflow-wrap: break-word;vertical-align: middle"><?php echo $row_data->stok_akhir;?></td>
              <td style="width:100px !important; overflow-wrap: break-word;"></td>
              <td style="width:100px !important; overflow-wrap: break-word;"></td>
              <td style="width:150px !important; overflow-wrap: break-word;"></td>
              
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>


    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






