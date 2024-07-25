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
    table {
        font-family: arial;
        font-size: 13px
    };
    
</style>
<div style="padding: 20px">
  <table border="0">
  <tr>
    <td>
      <img src="<?php echo base_url().COMP_ICON; ?>" style="width:70px">
    </td>
    <td style="padding-left:30px;">
  <b>SURAT KONTROL PASIEN<br><?php echo strtoupper(COMP_LONG); ?></b>
  </td>
  </tr>
  </table>
  </br>

  <table border="0">
  <tr>
  <td width="150px">Kode Booking</td><td colspan="3">: <?php echo ($value->counter!=NULL)?$value->counter:$value->no_registrasi?></td>
  </tr>

  <tr>
  <td width="150px">Nomor Surat Kontrol</td><td colspan="3">: <?php echo ($value->kode_perjanjian!=NULL)?$value->kode_perjanjian:$value->no_registrasi?></td>
  </tr>

  <tr>
  <td>Tanggal Kembali</td><td>: <?php echo $this->tanggal->formatDate($value->tgl_kembali)?></td><td style="padding-left:20px">Penjamin</td><td>: <?php echo $value->nama_perusahaan?></td>
  </tr>

  <tr>
  <td>Nama Pasien</td><td>: <?php echo $value->nama?></td><td style="padding-left:20px">No RM</td><td>: <?php echo $value->no_mr?></td>
  </tr>

  <tr>
  <td>Poli Tujuan</td><td>: <?php echo ucwords($value->nama_bagian)?></td>
  </tr>

  <tr>
  <td>Dokter</td><td>: <?php echo $value->dokter?></td>
  </tr>

  <tr>
  <td>Diagnosa Terakhir</td><td colspan="2">: <?php echo $value->diagnosa_akhir?></td>
  </tr>

  <tr>
  <td>Catatan</td><td colspan="2">: <?php //echo $value->keterangan?> </td>
  </tr>

  </table>

  <table border="0">
  <tr>
  <td>
  <p>
    Belum dapat dikembalikanke Faskes Perujuk dengan alasan :
    <ol>
      <li>Kondisi pasien masih belum stabil</li>
      <li>Masih dalam pengawasan khusus</li>
      <li>Pemantauan penggunaan obat-obatan</li>
      <li>Lain-lain</li>
    </ol>
  </p>
  <span style="font-size:11px">Cetakan ke <?php echo $value->counter?> <?php echo date('d-m-Y H:i:s')?> wib</span>
  </td>
  <td valign="top" style="padding-left:75px">
  <br><br>
  Jakarta, .......................... <br><br><br><br>_________________________
  </tr>
  <tr>
  <td>
  <!-- barcode here -->
  <!-- <div style="margin-top:5px">
  <div id="barcodeTarget" class="barcodeTarget"></div>
  </div> -->
  </td>
  </tr>
  </table>
</div>
<div style="margin-top: 20px;" class="center">  
    
    <a href="#" class="btn btn-inverse" onclick="cetak_surat_kontrol_popup(<?php echo $id_tc_pesanan?>, <?php echo $jd_id?>)"><i class="fa fa-print"></i> Cetak Surat Kontrol [testing]</a>

    <!-- <?php foreach($jenis_printer as $row) :?>
      <a href="#" class="<?php echo $row->desc_text?>" onclick="reprint(<?php echo $jd_id?>, <?php echo $id_tc_pesanan?>, '<?php echo $row->value; ?>')">
        <i class="fa fa-print"></i> <?php echo $row->label?>
      </a>
    <?php endforeach; ?> -->

</div>



