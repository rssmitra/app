<style type="text/css">
    table {
        font-family: arial;
        font-size: 12px;
        margin-top:20px;
    };
</style>
<div class="col-md-3">

  <table border="0" width="100%">
    <tr>
      <td align="center">
        <img src="<?php echo base_url().COMP_ICON; ?>" style="width:50px;float:left">
        <div style="float:left;margin-left:10px;margin-top:5px"><b>BUKTI REGISTRASI ONLINE<br><?php echo strtoupper(COMP_LONG); ?></b></div>
      </td>
    </tr>

    <tr>
      <td colspan="2" align="center">
        <img class="img-responsive" src="<?php echo base_url().'assets/barcode.php?s=qrh&d='.$qr_code.''?>" style="min-width:300px"><br>
        <span style="margin-top:-30px !important; font-size:20px"><strong><?php echo $kode_booking?></strong></span>
      </td>
    </tr>

    <tr>
      <td colspan="2" align="center">
        <address style="font-size:10px">
            Scan QR Code anda pada loket
          </address>
      </td>
    </tr>

    <tr>
      <td colspan="2" align="left">
        <p style="font-size:10px"><b>Pemberitahuan !</b><br>
          <ul style="font-size:10px;">
            <li>Waktu kedatangan anda untuk melakukan registrasi ulang adalah <b><?php echo $this->tanggal->formatDateTime($profile->regon_booking_waktu_datang) ?></b> dengan nomor urut poli <?php echo $profile->regon_booking_urutan?> </li>
            <li>Kami tidak akan melayani registrasi ulang pasien diluar waktu yang sudah ditentukan</li>
          </ul>
        </p>
      </td>
    </tr>

  </table>
</div>






     