<html>
  <head>
    <title>PRINT SURAT KONTROL PASIEN</title>
  </head>
  <body>
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
      <b>SURAT KONTROL PASIEN<br><?php echo strtoupper(COMP_LONG); ?></b><br>
      <small style="font-size:9px"><?php echo COMP_ADDRESS?></small>
      </td>
      <!-- <td align="right"><div class="stamp"><h1> Advanced Type 1 </h1></div></td> -->
    </tr>
    </table>

    <hr>
    <!-- DATA PASIEN -->
    <table border="0">
        <tr>
          <td width="120px" colspan="2"><span style="font-family: arial; font-size: 14px; text-align: center"><b>DATA PASIEN</b></span></td>
        </tr>
        <tr>
          <td width="120px">No. MR</td><td>: <?php echo $value->no_mr?></td>
        </tr>
        <tr>
          <td width="120px">Nama Pasien</td><td>: <?php echo $value->nama?></td>
        </tr>
        <tr>
          <td width="120px">No. Kartu BPJS</td><td>: <?php echo $value->no_kartu_bpjs?></td>
        </tr>
    </table>
    <hr>

    <table border="0">
        <!-- <tr>
          <td align="center">
            <span style="font-weight: bold;">[Kode Booking]</span><br>
            <span style="font-size: 20px"><?php echo ($value->counter!=NULL)?$value->counter:$value->no_registrasi?></span>
          </td>
        </tr> -->
        <tr>
          <td>
            <span style="font-weight: bold;">Tanggal Kontrol :</span><br>
            <span style="font-size: 14px"><?php echo $jadwal->jd_hari.', '.$this->tanggal->formatDate($value->tgl_kembali)?></span>
          </td>
        </tr>
        <tr>
          <td>
            <span style="font-weight: bold;">Dokter :</span><br>
            <span style="font-size: 14px"><?php echo $value->dokter?></span>
          </td>
        </tr>
        <tr>
          <td>
            <span style="font-weight: bold;">Poliklinik :</span><br>
            <span style="font-size: 14px"><?php echo $value->nama_bagian?></span>
          </td>
        </tr>
        <tr>
          <td>
            <span style="font-weight: bold;">Jam Praktek :</span><br>
            <span style="font-size: 14px"><?php echo $this->tanggal->formatTime($jadwal->jd_jam_mulai).'-'.$this->tanggal->formatTime($jadwal->jd_jam_selesai)?></span>
          </td>
        </tr>
    </table>
    <hr>
    <table border="0">
        <tr>
          <td width="120px">Petugas</td><td>: <?php echo $this->session->userdata('user')->fullname?></td>
        </tr>
        <tr>
          <td width="120px">Tgl dibuat</td><td>: <?php echo $value->input_tgl?></td>
        </tr>
        <tr>
          <td width="120px" valign="top">Keterangan</td><td>: <?php echo $value->keterangan?></td>
        </tr>
        <tr>
          <td width="120px" valign="top">Catatan Lainnya</td><td>: </td>
        </tr>
        <tr>
          <td colspan="2"><br><br>&nbsp;</td>
        </tr>
    </table>

    <table>
      <tr>
        <td>
          <b>Informasi lainnya :</b>
          <ol style="margin-left: -15px">
            <li>Pasien wajib melakukan daftar online H-1 sebelum kontrol kembali dengan mengakses link https://registrasi.rssetiamitra.co.id</li>
            <li>Lakukan <i>finger print</i> ketika hari H kunjungan sebelum praktek dokter dimulai di kiosk atau di bagian pendaftaran dan mohon siapkan Kartu BPJS anda</li>
            <li>Untuk informasi selengkapnya silahkan hubungi (021) 765 6000</li>
          </ol>
        </td>
      </tr>
    </table>

  </body>
</html>


