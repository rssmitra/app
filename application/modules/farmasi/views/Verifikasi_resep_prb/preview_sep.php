<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<style type="text/css">
    table {
        font-family: arial;
    };
</style>

<body style="background-color:white">
  <table border="0" width="100%">
  <tr>
    <td width="30%">
      <img src="<?php echo base_url()?>assets/images/logo-bpjs.png" style="width:200px">
    </td>
    <td width="70%" style="padding-left:30px">
      <b>SURAT ELEGIBILITAS PESERTA<br>
      <?php echo strtoupper(COMP_LONG); ?></b>
    </td>
  </tr>
  <tr>
    <td><br></td>
  </tr>
  </table>

  <table border="0" width="100%">
  <tr>
    <td width="100px">No SEP</td><td colspan="3">: <?php echo isset($sep->noSep)?$sep->noSep:$header->no_sep?></td>
  </tr>
  <tr>
    <td>Tgl SEP</td><td width="230px">: <?php echo isset($sep->tglSep)?$sep->tglSep:$this->tanggal->formatDateDmy($header->tgl_trans)?></td>
    <td style="padding-left:200px; width: 70px">Peserta</td><td>: <?php echo isset($sep->peserta->jnsPeserta)?$sep->peserta->jnsPeserta:''?></td>
  </tr>
  <tr>
    <td>No Kartu</td><td>: <?php echo isset($sep->peserta->noKartu)?$sep->peserta->noKartu:''?> (MR. <?php echo isset($sep->noMr)?$sep->noMr: $header->no_mr ?>)</td>
    <td style="padding-left:200px">COB</td><td>: -</td>
  </tr>
  <tr>
    <td>Nama Peserta</td><td>: <?php echo isset($sep->peserta->nama)?$sep->peserta->nama:$header->nama_pasien?></td>
    <td style="padding-left:200px">Jns. Rawat</td><td>: <?php echo isset($sep->jnsPelayanan)?$sep->jnsPelayanan: ( $header->flag_trans == 'RJ')?'R.Jalan':'R.Inap'?></td>
  </tr>
  <tr>
    <td>Tgl Lahir</td><td>: <?php echo isset($sep->peserta->tglLahir)?$sep->peserta->tglLahir:$this->tanggal->formatDateDmy($header->tgl_lhr)?> &nbsp;&nbsp;&nbsp;&nbsp; Kelamin : <?php echo isset($sep->peserta->kelamin)?$sep->peserta->kelamin:$header->jen_kelamin?></td><td style="padding-left:200px">Kls. Rawat</td><td>: <?php echo isset($sep->kelasRawat)?$sep->kelasRawat:'-'?></td>
  </tr>
  <tr>
    <td>No Telepon</td><td>: <?php echo isset($sep->noTelp)?$sep->noTelp:$header->no_hp; ?></td>
    <td style="padding-left:200px">Penjamin</td><td>: <?php echo isset($sep->penjamin)?$sep->penjamin:'BPJS Kesehatan'?></td>
  </tr>
  <tr>
    <td>Poli Tujuan</td><td>: <?php echo isset($sep->poli)?$sep->poli:$header->kode_poli_bpjs?></td>
  </tr>
  <tr>
    <td>Faskes Perujuk</td><td>: <?php echo isset($sep->PPKPerujuk)?$sep->PPKPerujuk:''?></td>
  </tr>
  <tr>
    <td>Diagnosa Awal</td><td colspan="3">: <?php echo isset($sep->diagnosa)?$sep->diagnosa:$header->diagnosa_akhir?></td>
  </tr>
  <tr>
    <td>Catatan</td><td colspan="3">: <?php echo isset($sep->catatan)?$sep->catatan:''?></td>
  </tr>
  </table>

  <table border="0">
  <tr>
    <td width="70%">
      <p>*Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan<br>
      SEP Bukan sebagai bukti penjaminan peserta<br></p>
      <span>Cetakan ke- <?php echo ($cetakan_ke==0)?1:$cetakan_ke;?> Tanggal <?php echo date('d/m/Y H:i:s')?> wib</span>
    </td>
    <td width="30%" valign="top" style="padding-left:120px">
    Pasien/Keluarga Pasien<br><br><br><br>____________________
    </td>
  </tr>
  </table>
</body>




     