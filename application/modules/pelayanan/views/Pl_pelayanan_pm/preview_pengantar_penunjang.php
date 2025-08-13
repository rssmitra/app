<div id="barPrint" style="position: absolute; top: 10px; right: 20px; z-index: 9999;">
  <button class="btn btn-sm btn-danger" onclick="window.close()"><i class="fa fa-times"></i> Tutup</button>
  <button class="btn btn-sm btn-primary" onclick="hideAndPrint()"><i class="fa fa-print"></i> Cetak</button>
<script>
function hideAndPrint() {
  var bar = document.getElementById('barPrint');
  if(bar) bar.style.display = 'none';
  window.print();
  setTimeout(function(){ if(bar) bar.style.display = ''; }, 1000);
}
</script>
</div>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />
<!-- ace styles -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" />
<div class="row" style="padding: 50px">
  <div class="col-xs-12">
    <?php echo $header?>
    <hr>
    <center>
      <h4>
        <u><b>SURAT PENGANTAR PENUNJANG</b></u><br>
        <span style="font-size: 14px !important">
            No Surat. <?php echo $kode_penunjang?>/SP/<?php echo $unit?>/<?php echo date('m', strtotime($tgl_daftar))?>.<?php echo date('Y', strtotime($tgl_daftar))?>
        </span>
      </h4>
    </center>
    <br>
    <p>
      Berdasarkan hasil pemeriksaan medis oleh Dokter Spesialis, pasien yang tersebut memerlukan tindakan penunjang medis berupa:
    </p>
    <table class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th>No</th>
            <th>Tanggal Order</th>
            <th>Nama Pasien</th>
            <?php if($_GET['kode_bagian'] == '050301'):?>
              <th>Anamnesa & Diagnosa</th>
            <?php endif;?>
            <th>Pemeriksaan</th>
            <th>Dr Pengirim</th>
            <th>Bagian Asal</th>
          </tr>
        </thead>
        <tbody>
          <?php

            foreach($result as $row) {
              echo '<tr>';
              for($i=0; $i < 6; $i++) {
                echo '<td>' . $row[$i] . '</td>';
                $html = '';
              }
              echo '</tr>';
            }
          ?>
        </tbody>
      </table>

    <?php echo $footer?>

  </div><!-- /.col -->
</div><!-- /.row -->




