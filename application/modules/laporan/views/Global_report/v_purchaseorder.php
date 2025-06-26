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
<style> 
 .table_wrapper{
    display: block;
    overflow-x: auto;
    white-space: nowrap;
    width: 100%;
}
</style>
<body>
  <div class="row">
    <div class="col-xs-12">
      <?php
      switch($keterangan){
        case "medis":
           $ket = "BARANG MEDIS";
            break;
        case "nmmedis":
          $ket = "BARANG NON MEDIS";
            break;
      }
      foreach($result['data'] as $r_data);?>
      <center><h4><?php echo $title?>
        <br><?php echo $ket?>
      </h4></center>
      <div class="table_wrapper">
        <table class="table" border="0">
          <thead>
            <tr>
              <th width="25">No.</th>
              <th>No Permintaan</th>
              <th>Tgl Permintaan</th>
              <th>Tgl Persetujuan</th>
              <th>No PO</th>
              <th>Tgl PO</th>
              <th>Tgl Revisi PO</th>
              <th>Nama Supplier</th>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Satuan Besar</th>
              <th>Satuan Kecil</th>
              <th>Rasio</th>
              <th>Pabrikan</th>
              <th>Jumlah Usulan</th>
              <th>Jumlah ACC</th>
              <th>Jumlah Order</th>
              <th>Jumlah Diterima</td>
              <th>Barang Belum Diterima</td>
              <th>Harga Satuan Netto</th>
              <th>Jumlah Harga Netto</td>
              <th>Tgl Penerimaan</td>
              <th>No Faktur</td>
              
          
          </thead>
          <tbody>
            <?php $no  = 0; 
            $ttlharganetto = [];
            foreach($result['data'] as $row_data){
            $subttlharganetto = $row_data->jml_diterima * $row_data->harga_satuan_netto;
            $ttlharganetto[] = $subttlharganetto;
              $no++; 
              ?>
              <tr>
                <td align="right" width="25"><?php echo $no ?></td>
                <td align="center"><?php echo $row_data->kode_permohonan ?></td>
                <td align="center"><?php echo $row_data->tgl_permohonan ?></td>
                <td align="center"><?php echo $row_data->tgl_acc ?></td>
                <td align="center"><?php echo $row_data->no_po ?>&nbsp;</td>
                <td align="center"><?php echo $row_data->tgl_po ?></td>
                <td align="center"><?php echo ($row_data->revisi != $row_data->tgl_po) ? $row_data->revisi : '' ?></td>
                <td align="left"><?php echo $row_data->namasupplier ?>&nbsp;</td>
                <td align="left"><?php echo $row_data->kode_brg ?>&nbsp;</td>
                <td align="left"><?php echo $row_data->nama_brg ?>&nbsp;</td>
                <td align="left"><?php echo $row_data->satuan_besar ?>&nbsp;</td>
                <td align="left"><?php echo $row_data->satuan_kecil ?>&nbsp;</td>
                <td align="left"><?php echo $row_data->content ?>&nbsp;</td>
                <td align="left"><?php echo $row_data->nama_pabrik ?>&nbsp;</td>
                <td align="center"><?php echo $row_data->jumlah_usulan ?></td>
                <td align="center"><?php echo $row_data->jumlah_diacc ?></td>
                <td align="center"><?php echo $row_data->jml_order ?></td>
                <td align="center"><?php echo $row_data->jml_diterima ?></td>
                <td align="center"><?php $selisih = $row_data->jml_order - $row_data->jml_diterima; echo $selisih; ?></td>
                <td align="right"><?php echo ($_POST['submit'] == 'excel') ? $row_data->harga_satuan_netto : number_format($row_data->harga_satuan_netto) ?></td>
                <td align="right"><?php echo ($_POST['submit'] == 'excel') ? $subttlharganetto : number_format($subttlharganetto) ?></td>
                <td><?php echo $row_data->tgl_penerimaan ?></td>
                <td><?php echo $row_data->no_faktur ?></td>
                
              </tr>
            <?php 
          // endforeach; 
        }?>
        <td colspan="11" align="right" style="text-transform: uppercase; font-weight: bold">Total Harga Netto Penerimaan Barang</td>
        <td align="left" colspan="2" style="font-weight: bold"><?php echo ($_POST['submit'] == 'excel') ? array_sum($ttlharganetto) : number_format(array_sum($ttlharganetto)) ?></td>
        </tr>            
          </tbody>
        </table>

      </div><!-- /.col -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






