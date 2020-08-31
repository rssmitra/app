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
    body table {
        font-family: arial;
        font-size: 12px
    }

    .table-content th {
      height: 30px;
      border-bottom: 1px solid #ddd;
      border-top: 1px solid #ddd;
      text-align: left;
      padding: 8px
    }

    /*.table-content td {
      border: 1px solid #ddd;
    }*/

</style>
<body>
<table border="0">
<tr>
  <td width="70px">
  <img src="<?php echo base_url().COMP_ICON; ?>" style="width:70px">
  </td>
  <td style="padding-left:10px;">
  <b>FORM BUKTI PENDAFTARAN PASIEN<br><?php echo strtoupper(COMP_LONG); ?></b><br>
  <small style="font-size:9px"><?php echo COMP_ADDRESS?></small>
  </td>
  <!-- <td align="right"><div class="stamp"><h1> Advanced Type 1 </h1></div></td> -->
</tr>
</table>
<hr>
<span style="font-family: arial; font-size: 14px"><b>Data Pasien</b></span>
<table width="100%">
  <tr>
    <td width="50%">
      <table border="0">
        <tr>
          <td width="150px">No. MR</td><td>: <?php echo $_GET['no_mr']?></td>
        </tr>
        <tr>
          <td width="150px">Nama Pasien</td><td>: <?php echo $_GET['nama']?></td>
        </tr>
        <tr>
          <td width="150px">Tanggal Registrasi</td><td>: <?php echo $this->tanggal->formatDateTime($registrasi->tgl_jam_masuk)?></td>
        </tr>
        <tr>
          <td width="150px">Poli Tujuan</td><td>: <?php echo ucwords($_GET['poli'])?></td>
        </tr>
        <tr>
          <td width="150px">Dokter</td><td>: <?php echo $_GET['dokter']?></td>
        </tr>
        <tr>
          <td width="150px">Nasabah</td><td>: <?php echo $_GET['nasabah']?></td>
        </tr>
      </table>
    </td>

    <?php if(in_array($registrasi->kode_bagian_masuk, array('013101','050301') )) :?>
    <td width="50%" valign="top" align="right">
      <table border="0" width="100%" style="border: 1px solid black; border-collapse: collapse;">
        <tr style="border: 1px solid black; border-collapse: collapse;">
          <td colspan="7" style="border: 1px solid black; border-collapse: collapse;">Kunjungan Ke-</td>
        </tr>
        <tr style="border: 1px solid black; border-collapse: collapse;">
          <?php for($i=1;$i<8;$i++) :?>
            <td valign="top" style="border: 1px solid black; border-collapse: collapse;height: 50px"><?php echo $i;?></td>
          <?php endfor;?>
        </tr>
      </table>
    </td>
    <?php endif;?>

  </tr>
</table>

<br>
<span style="font-family: arial; font-size: 14px"><b>Checklist Pelayanan Pasien</b></span>
<br>
<table border="0" width="100%" class="table-content">

  <tr>
    <td valign="top">
      <table border="0">
        <tr>
          <td>1. </td>
          <td>Asessment Rawat Jalan</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <div style="width: 150px; float: left"> Tekanan Darah (TD)</div>
            <div style="width: 80px; float: right">[ &nbsp;&nbsp;&nbsp; ] &nbsp;&nbsp; mmhg</div>
          </td>
        </tr>

        <tr>
          <td>&nbsp;</td>
          <td>
            <div style="width: 150px; float: left"> Berat Badan (BB)</div>
            <div style="width: 80px; float: right">[ &nbsp;&nbsp;&nbsp; ] &nbsp;&nbsp; kg </div>
          </td>
        </tr>

        <tr>
          <td>&nbsp;</td>
          <td>
            <div style="width: 150px; float: left"> EKG</div>
            <div style="width: 80px; float: right">[ &nbsp;&nbsp;&nbsp; ]</div>
          </td>
        </tr>

        <tr>
          <td>2. </td>
          <td>Poli/Klinik</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 15px; float: left"> 
            <span style="margin-top: -0px; padding-left: 10px"> Konsultasi Dokter</span>
          </td>
        </tr>
        <tr>
          <td>3. </td>
          <td>Instalasi Gawat Darurat (IGD)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 15px; float: left"> 
            <span style="margin-top: -0px; padding-left: 10px"> Konsultasi Dokter</span>
          </td>
        </tr>
      </table>
    </td>
    <td valign="top">
      <table border="0">
        <tr>
          <td>4. </td>
          <td>Penunjang Medis</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 15px; float: left"> 
            <span style="margin-top: -0px; padding-left: 10px"> Laboratorium</span>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 15px; float: left"> 
            <span style="margin-top: -0px; padding-left: 10px"> Radiologi</span>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 15px; float: left"> 
            <span style="margin-top: -0px; padding-left: 10px"> Fisioterapi</span>
          </td>
        </tr>

        <tr>
          <td>5. </td>
          <td>Farmasi</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 15px; float: left"> 
            <span style="margin-top: -0px; padding-left: 10px"> Resep</span>
          </td>
        </tr>
      </table>
    </td>
    <td valign="top">
      <table border="0">
        <tr>
          <td>6. </td>
          <td>Kasir</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 15px; float: left"> 
            <span style="margin-top: -0px; padding-left: 10px"> Pelunasan Administrasi</span>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <?php if(!in_array($registrasi->kode_bagian_masuk, array('013101','050301') )) :?>
              <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 15px; float: left"> 
              <span style="margin-top: -0px; padding-left: 10px"> Biaya APD <?php echo ucwords($_GET['poli'])?> (Rp. 25,000)</span>
            <?php else: ?>
              <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 15px; float: left"> 
              <span style="margin-top: -0px; padding-left: 10px"> Biaya APD <?php echo ucwords($_GET['poli'])?> </span>
            <?php endif; ?>
          </td>
        </tr>
      </table>
    </td>
    
  </tr>

</table>

<br>
<table border="0" width="100%">
<tr>
  <td valign="top">
      <i>Berikan form cheklist ini ke petugas setiap kali akan dilakukan pemeriksaan </i>
  </td>
  <td valign="bottom" style="padding-top:25px" align="right">
    Jakarta, .......................... <br><br><br><br>_________________________
  </td>
</tr>
</table>
</body>

