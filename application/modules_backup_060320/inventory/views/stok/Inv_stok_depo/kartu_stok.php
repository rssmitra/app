<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<style>
tr, td {
  padding: 2px;
  text-align: left;
}
</style>
<form class="form-horizontal">

  <div id="barPrint" style="float: right">
    <button class="tular" onClick="window.close()">Tutup</button>
    <button class="tular" onClick="printpage()">Cetak</button>
  </div>

  <div class="row">
    <div class="col-xs-10">
        <table>
          <tr>
            <td width="180px">Unit/Depo</td>
            <td>: <?php echo strtoupper($unit->nama_bagian)?></td>
          </tr>
          <tr>
            <td>Kode & Nama Barang</td>
            <td>: <?php echo isset($header)?$header->kode_brg.' - '.$header->nama_brg:''?></td>
          </tr>
          <tr>
            <td>Stok Akhir</td>
            <td>: <?php echo isset($header)?$header->stok_akhir.' '.$header->satuan_kecil:''?></td>
          </tr>
          <tr>
            <td>Rasio</td>
            <td>: <?php echo isset($header)?$header->content.' '.$header->satuan_kecil.'/'.$header->satuan_besar:''?></td>
          </tr>
          <tr>
            <td>Mutasi Terakhir</td>
            <td>: <?php echo isset($header)?$this->tanggal->formatDateTime($header->tgl_input):''?></td>
          </tr>
        </table>
        
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <table class="table">
        <thead>
          <tr style="background-color: #87b87f">
            <th style="vertical-align: middle" width="30px">No</th>
            <th style="vertical-align: middle">Tanggal</th>
            <th style="vertical-align: middle">Stok Awal</th>
            <th style="vertical-align: middle">Masuk</th>
            <th style="vertical-align: middle">Keluar</th>
            <th style="vertical-align: middle">Stok Akhir<br>(<?php echo date('d/M/Y')?>)</th>
            <th style="vertical-align: middle">Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=0; foreach($value as $row) : $no++; ?>
            <tr>
              <td class="center" width="30px"><?php echo $no?></td>
              <td><?php echo $this->tanggal->formatDateTime($row->tgl_input)?></td>
              <td class="center"><?php echo $row->stok_awal?></td>
              <td class="center"><?php echo $row->pemasukan?></td>
              <td class="center"><?php echo $row->pengeluaran?></td>
              <td class="center"><?php echo $row->stok_akhir?></td>
              <td><?php echo $row->keterangan?></td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div><!-- /.col -->
  </div><!-- /.row -->

</form>
<script type="text/javascript">
  
  function printpage() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("barPrint");
        //Set the print button visibility to 'hidden' 
        printButton.style.visibility = 'hidden';
        //Print the page content
        window.print()
        printButton.style.visibility = 'visible';
    }

</script>

