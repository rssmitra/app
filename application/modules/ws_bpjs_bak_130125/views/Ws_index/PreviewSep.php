<div>
  <a href="#" onclick="getMenuTabs('<?php echo base_url().'ws_bpjs/Ws_index/view_sep/'.$sep->noSep.''?>', 'divLoadSEP')" class="btn btn-xs btn-primary">Reload</a>
  <a href="#" onclick="(function(){ $('#divLoadSEP').html(''); return false; })(); return false;" class="btn btn-xs btn-danger">Close</a>
</div>

<div style="background-color:white !important; padding: 20px !important">

  <table border="0" width="100%">
    <tr>
      <td width="200px">
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
      <td width="120px">No SEP</td><td colspan="3">: <span id="txt_sep"><?php echo isset($sep->noSep)?$sep->noSep:''?></span></td>
    </tr>
    <tr>
      <td>Tgl SEP</td><td width="230px">: <?php echo isset($sep->tglSep)?$sep->tglSep:''?></td>
      <td style="padding-left:70px; width: 150px">Peserta</td><td>: <?php echo isset($sep->peserta->jnsPeserta)?$sep->peserta->jnsPeserta:''?></td>
    </tr>
    <tr>
      <td>No Kartu</td><td width="250px">: <?php echo isset($sep->peserta->noKartu)?$sep->peserta->noKartu:''?> (MR. <?php echo isset($sep->peserta->noMr)?$sep->peserta->noMr: '' ?>)</td>
      <td style="padding-left:70px">COB</td><td>: -</td>
    </tr>
    <tr>
      <td>Nama Peserta</td><td>: <?php echo isset($sep->peserta->nama)?$sep->peserta->nama:''?></td>
      <td style="padding-left:70px">Jns. Rawat</td><td>: <?php echo isset($sep->jnsPelayanan)?$sep->jnsPelayanan:'R.Jalan'?></td>
    </tr>
    <tr>
      <td>Tgl Lahir</td><td>: <?php echo isset($sep->peserta->tglLahir)?$sep->peserta->tglLahir:''?> &nbsp;&nbsp;&nbsp;&nbsp; Kelamin : <?php echo isset($sep->peserta->kelamin)?$sep->peserta->kelamin:''?></td><td style="padding-left:70px">Kls. Rawat</td><td>: <?php echo isset($sep->kelasRawat)?$sep->kelasRawat:'-'?></td>
    </tr>
    <tr>
      <td>No Telepon</td><td>: <?php echo isset($sep->noTelp)?$sep->noTelp:''; ?></td>
      <td style="padding-left:70px">Penjamin</td><td>: <?php echo isset($sep->penjamin)?$sep->penjamin:'BPJS Kesehatan'?></td>
    </tr>
    <tr>
      <td>Poli Tujuan</td><td>: <?php echo isset($sep->poli)?$sep->poli:''?></td>
    </tr>
    <tr>
      <td>Faskes Perujuk</td><td>: <?php echo isset($sep->PPKPerujuk)?$sep->PPKPerujuk:''?></td>
    </tr>
    <tr>
      <td>Diagnosa Awal</td><td colspan="3">: <?php echo isset($sep->diagnosa)?$sep->diagnosa:''?></td>
    </tr>
    <tr>
      <td>Catatan</td><td colspan="3">: <?php echo isset($sep->catatan)?$sep->catatan:''?></td>
    </tr>
  </table>

  <table border="0" width="100%">
    <tr>
      <td width="70%">
        <p>*Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan<br>
        SEP Bukan sebagai bukti penjaminan peserta<br></p>
        <span>Cetakan ke- <?php echo ($cetakan_ke==0)?1:$cetakan_ke;?> Tanggal <?php echo date('d/m/Y H:i:s')?> wib</span>
      </td>
      <td width="30%" valign="top" style="padding-left:120px">
      Pasien/Keluarga Pasien<br><br>
      <?php 
        if(!empty('')) {
          $img_base64_encoded = '';
          $imageContent = file_get_contents($img_base64_encoded);
          $path = tempnam(sys_get_temp_dir(), 'prefix');
          
          file_put_contents ($path, $imageContent);
          
          echo $img = '<img src="' . $path . '">';
        }else{
          echo '<br><br>_______________________';
        }
        
      ?>
      </td>
    </tr>
  </table>
  <br>
  <hr>
  
  <div class="center">
    <!-- <?php foreach($jenis_printer as $row) :?>
      <a href="#" class="<?php echo $row->desc_text?>" onclick="reprintBuktiPendaftaran('<?php echo isset($registrasi->no_registrasi)?$registrasi->no_registrasi:''?>' , '<?php echo isset($no_antrian)?$no_antrian:''?>' , '<?php echo $row->value; ?>')">
        <i class="fa fa-print"></i> <?php echo $row->label?>
      </a>
    <?php endforeach; ?> -->

    <a href="#" class="btn btn-lg btn-inverse" onclick="PopupCenter('registration/Reg_klinik/print_bukti_pendaftaran_pasien_small?nama=<?php echo $registrasi->nama_pasien?>&no_mr=<?php echo $registrasi->no_mr?>&no_reg=<?php echo $registrasi->no_registrasi?>&poli=<?php echo $registrasi->nama_bagian?>&dokter=<?php echo $registrasi->nama_pegawai?>&nasabah=<?php echo $registrasi->nama_perusahaan?> (<?php echo $no_sep?>)', ' BUKTI PENDAFTARAN PASIEN', 950, 550)">
      <i class="fa fa-print"></i> PRINT BUKTI PENDAFTARAN
    </a>
  </div>
</div>




     