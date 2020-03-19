<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <style type="text/css">
      body{
        margin:20px 50px;
        font-size:12px;
      }
      .center{
        text-align:center;
      }
      .data{
        text-align:left;
      }
      .title{
        width:100px;
      }
      footer{
        position:relative;
        width:25%;
        float:right;
      }

      #table_bahan{
        border-collapse: collapse;
        width: 100%;
      }

      #table_bahan td, #table_bahan th {
          border: 1px solid #dddddd;
          text-align: left;
          padding: 2px;
        }


  </style>
</head>
<body>

  <center><h3>CATATAN MEDIS KASUS KERACUNAN</h3></center>

  <div class="data">
    
      <table border="0" width="100%">
        <tr>
          <td class="title">Tanggal</td>
          <td>&nbsp;:&nbsp; <?php echo $this->tanggal->formatDate($value->tgl_keracunan) ?> </td>
          <td class="title">No. MR</td>
          <td>&nbsp;:&nbsp; <?php echo $value->no_mr ?></td>
        </tr>
        <tr>
          <td class="title" >Rumah Sakit</td>
          <td>&nbsp;:&nbsp; Rumah Sakit Setia Mitra </td>
          <td class="title">Telp.</td>
          <td>&nbsp;:&nbsp;(021) 7656000</td>
        </tr>
        <tr>
          <td class="title">Alamat</td>
          <td>&nbsp;:&nbsp; Jl. RS. Fatmawati No. 80 - 82</td>
          <td class="title">Fax</td>
          <td>&nbsp;:&nbsp;(021) 7656875</td>
        </tr>
      </table>

      <table border="0" width="100%">
        <tr>
          <td class="title">Nama Pasien</td>
          <td>&nbsp;:&nbsp; <?php echo $value->nama_pasien ?></td>
          <td class="data" >
            <?php echo ($value->jen_kelamin=='L')?'&#9745;':'&#9744;' ?>&nbsp;L &nbsp;&nbsp;
            <?php echo ($value->jen_kelamin=='P')?'&#9745;':'&#9744;' ?>&nbsp;P
          </td>
          <td>Umur</td>
          <td>&nbsp;:&nbsp; <?php echo $value->umur_tahun ?>&nbsp; Tahun</td>
        </tr>
      </table>

      <table border="0" width="100%">
        <tr>
          <td class="title">Alamat Pasien</td>
          <td>&nbsp;:&nbsp; <?php echo $value->almt_ttp_pasien ?></td>
        </tr>
      </table>

      <table border="0" width="100%">
        <tr>
          <td class="title">Tempat Kejadian</td>
          <td>&nbsp;:&nbsp; 
            <?php echo ($value->tempat_kejadian=='Rumah')?'&#9745;':'&#9744;' ?>&nbsp;Rumah &nbsp;&nbsp;
            <?php echo ($value->tempat_kejadian=='Kantor/kerja')?'&#9745;':'&#9744;' ?>&nbsp;Kantor/kerja &nbsp;&nbsp;
            <?php echo ($value->tempat_kejadian=='Tempat Hiburan')?'&#9745;':'&#9744;' ?>&nbsp;Tempat Hiburan &nbsp;&nbsp;
            <?php echo ($value->tempat_kejadian=='Lain - lain')?'&#9745;':'&#9744;' ?>&nbsp;Lain - lain
          </td>
        </tr>
        <tr>
          <td class="title">Keluhan</td>
          <td>&nbsp;:&nbsp; <?php echo $value->keluhan_utama ?></td>
        </tr>
        <tr>
          <td class="title">RPS</td>
          <td>&nbsp;:&nbsp; <?php echo $value->rps ?></td>
        </tr>
        
      </table>

      <table border="0" width="100%">
        <tr>
          <td style="width:50%">Hamil
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;:&nbsp; 
            <?php echo ($value->hamil=='Hamil')?'&#9745;':'&#9744;' ?>&nbsp;Ya &nbsp;&nbsp;
            <?php echo ($value->hamil=='Tidak Hamil')?'&#9745;':'&#9744;' ?>&nbsp;Tidak &nbsp;&nbsp;
          </td>

          <td align="left" style="width:50%;">Menyusui
            &nbsp;:&nbsp; 
            <?php echo ($value->ket_pas_menyusui=='Ya')?'&#9745;':'&#9744;' ?>&nbsp;Ya &nbsp;&nbsp;
            <?php echo ($value->ket_pas_menyusui=='Tidak')?'&#9745;':'&#9744;' ?>&nbsp;Tidak &nbsp;&nbsp;
          </td>
        </tr>
      </table>

      <p><b>PERKIRAAN JENIS BAHAN :</b></p>

      <table id="table_bahan">
        <thead>
          <tr>
            <th><center>Kelompok Penyebab</center></th>
            <th><center>Nama Bahan</center></th>
            <th><center>Jumlah Bahan</center></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>NAPZA</td>
            <td><center><?php echo $value->bahan_napza ?></center></td>
            <td><center><?php echo $value->jumlah_napza ?></center></td>
          </tr>
          <tr>
            <td>Obat</td>
            <td><center><?php echo $value->bahan_obat ?></center></td>
            <td><center><?php echo $value->jumlah_obat ?></center></td>
          </tr>
          <tr>
            <td>Obat Tradisional</td>
            <td><center><?php echo $value->bahan_obattradisional ?></center></td>
            <td><center><?php echo $value->jumlah_obattradisional ?></center></td>
          </tr>
          <tr>
            <td>Makanan/Minuman</td>
            <td><center><?php echo $value->bahan_makanan ?></center></td>
            <td><center><?php echo $value->jumlah_makanan ?></center></td>
          </tr>
          <tr>
            <td>Suplemen Makanan/Vitamin</td>
            <td><center><?php echo $value->bahan_suplemen ?></center></td>
            <td><center><?php echo $value->jumlah_suplemen ?></center></td>
          </tr>
          <tr>
            <td>Kosmetik</td>
            <td><center><?php echo $value->bahan_kosmetik ?></center></td>
            <td><center><?php echo $value->jumlah_kosmetik ?></center></td>
          </tr>
          <tr>
            <td>Bahan Kimia</td>
            <td><center><?php echo $value->bahan_kimia ?></center></td>
            <td><center><?php echo $value->jumlah_kimia ?></center></td>
          </tr>
          <tr>
            <td>Pestisida</td>
            <td><center><?php echo $value->bahan_pestisida ?></center></td>
            <td><center><?php echo $value->jumlah_pestisida ?></center></td>
          </tr>
          <tr>
            <td>Gigitan Ular</td>
            <td><center><?php echo $value->bahan_ular ?></center></td>
            <td><center><?php echo $value->jumlah_ular ?></center></td>
          </tr>
          <tr>
            <td>Binatang Selain Ular</td>
            <td><center><?php echo $value->bahan_bukanular ?></center></td>
            <td><center><?php echo $value->jumlah_bukanular ?></center></td>
          </tr>
          <tr>
            <td>Tumbuhan Beracun</td>
            <td><center><?php echo $value->bahan_tumbuhan ?></center></td>
            <td><center><?php echo $value->jumlah_tumbuhan ?></center></td>
          </tr>
          <tr>
            <td>Pencemaran Lingkungan/Gas</td>
            <td><center><?php echo $value->bahan_pencemaran ?></center></td>
            <td><center><?php echo $value->jumlah_pencemaran ?></center></td>
          </tr>
          <tr>
            <td>Bahan Tidak Diketahui</td>
            <td><center><?php echo $value->bahan_tdkdiketahui ?></center></td>
            <td><center><?php echo $value->jumlah_tdkdiketahui ?></center></td>
          </tr>
        </tbody>
      </table>
    
      <table border="0" width="100%">
        <tr>
          <td class="title">Tipe Pemaparan &nbsp;:&nbsp; </td>
          <td>
            <?php echo ($value->tipe_pemaparan=='Melalui Mulut')?'&#9745;':'&#9744;' ?>&nbsp;Melalui Mulut &nbsp;&nbsp;
            <?php echo ($value->tipe_pemaparan=='Melalui Inhalasi')?'&#9745;':'&#9744;' ?>&nbsp;Melalui Inhalasi &nbsp;&nbsp;
            <?php echo ($value->tipe_pemaparan=='Melalui Kulit')?'&#9745;':'&#9744;' ?>&nbsp;Melalui Kulit &nbsp;&nbsp;
            <?php echo ($value->tipe_pemaparan=='Melalui Injeksi')?'&#9745;':'&#9744;' ?>&nbsp;Melalui Injeksi &nbsp;&nbsp;
            <?php echo ($value->tipe_pemaparan=='Melalui Mata')?'&#9745;':'&#9744;' ?>&nbsp;Melalui Mata &nbsp;&nbsp;
            <?php echo ($value->tipe_pemaparan=='Sengatan')?'&#9745;':'&#9744;' ?>&nbsp;Sengatan &nbsp;&nbsp;
            <?php echo ($value->tipe_pemaparan=='Gigitan')?'&#9745;':'&#9744;' ?>&nbsp;Gigitan
          </td>
        </tr>
        <tr>
          <td class="title">Tipe Kejadian &nbsp;:&nbsp;</td>
          <td> 
            <?php echo ($value->tipe_kejadian=='Tidak Disengaja')?'&#9745;':'&#9744;' ?>&nbsp;Tidak Disengaja &nbsp;&nbsp;
            <?php echo ($value->tipe_kejadian=='Disengaja')?'&#9745;':'&#9744;' ?>&nbsp;Disengaja &nbsp;&nbsp;
            <?php echo ($value->tipe_kejadian=='Tidak Diketahui')?'&#9745;':'&#9744;' ?>&nbsp;Tidak Diketahui &nbsp;&nbsp;
          </td>
        </tr>
      </table>

      <p><b>GAMBARAN KLINIS :</b></p>

      <table border="0" width="100%">
        <tr>
          <td width="33.3%"><span class="title">Kesadaran</span>
          &nbsp;:&nbsp; <?php echo $value->kesadaran ?></td>
          <td width="33.3%">Tekanan Darah
          &nbsp;:&nbsp; <?php echo $value->tekanan_darah ?> mm/Hg</td>
          <td width="33.3%">Nadi
          &nbsp;:&nbsp; <?php echo $value->nadi ?> x/menit</td>
          
        </tr>
        <tr>
          <td width="33.3%">Suhu
          &nbsp;:&nbsp; <?php echo $value->suhu ?> &#8451;</td>
          <td width="33.3%">Pernafasan
          &nbsp;:&nbsp; <?php echo $value->pernafasan ?> x/menit</td>
          <td width="33.3%">Urine
          &nbsp;:&nbsp; <?php echo $value->urine ?> cc/jam</td>
        </tr>
      </table>

      <table border="0" width="100%">
        <tr>
          <td>Bau Bahan
          &nbsp;:&nbsp; 
            <?php echo ($value->bau_bahan=='Ada')?'&#9745; Ada, '.$value->keterangan_bau_bahan.'':'&#9744; Ada' ?> &nbsp;&nbsp;
            <?php echo ($value->bau_bahan=='Tidak Ada')?'&#9745;':'&#9744;' ?>&nbsp;Tidak Ada&nbsp;
          </td>
        </tr>
      </table>
      
      <table border="0" width="100%">
        <tr>
          <td>Pupil
          &nbsp;:&nbsp; 
            <?php echo ($value->pupil=='Normal')?'&#9745;':'&#9744;' ?>&nbsp;Normal &nbsp;&nbsp;
            <?php echo ($value->pupil=='Isokor')?'&#9745;':'&#9744;' ?>&nbsp;Isokor &nbsp;&nbsp;
            <?php echo ($value->pupil=='Unisokor')?'&#9745;':'&#9744;' ?>&nbsp;Unisokor &nbsp;&nbsp;
            <?php echo ($value->pupil=='Miosis')?'&#9745;':'&#9744;' ?>&nbsp;Miosis &nbsp;&nbsp;
            <?php echo ($value->pupil=='Midriasis')?'&#9745;':'&#9744;' ?>&nbsp;Midriasis
          </td>
        </tr>
      </table>

      <table border="0" width="100%">
        <tr>
          <td style="width:60%">Diagnosis
          &nbsp;:&nbsp;&nbsp; <?php echo $diagnosa ?> 
          <td style="width:40%">ICD X
          &nbsp;:&nbsp; <?php echo $icd_x ?>
        </tr>
      </table>

      <table border="0" width="100%">
        <td style="width:60%">Pengobatan Sebelum ke IGD
        &nbsp;:&nbsp; <?php echo $value->pengobatan_sbl_igd ?></td>
        <td style="width:40%">Pemeriksaan Penunjang
        &nbsp;:&nbsp; <?php echo $value->pemeriksaan_penunjang ?></td>
      </table>

      <table border="0" width="100%">
        <td>Penatalaksanaan yang diberikan
        &nbsp;:&nbsp; <?php echo $value->penatalaksanaan ?></td>
      </table>

      <table border="0" width="100%">
        <tr>
          <td>Tindak Lanjut
          &nbsp;:&nbsp; 
            <?php echo ($value->tindak_lanjut=='Rawat Jalan')?'&#9745;':'&#9744;' ?>&nbsp;Rawat Jalan &nbsp;&nbsp;
            <?php echo ($value->tindak_lanjut=='Rawat Inap')?'&#9745;':'&#9744;' ?>&nbsp;Rawat Inap &nbsp;&nbsp;
            <?php echo ($value->tindak_lanjut=='Dirujuk')?'&#9745;':'&#9744;' ?>&nbsp;Dirujuk &nbsp;&nbsp;
            <?php echo ($value->tindak_lanjut=='Pulang Paksa')?'&#9745;':'&#9744;' ?>&nbsp;Pulang Paksa &nbsp;&nbsp;
            <?php echo ($value->tindak_lanjut=='Pulang Sembuh')?'&#9745;':'&#9744;' ?>&nbsp;Pulang Sembuh &nbsp;&nbsp;
            <?php echo ($value->tindak_lanjut=='Meninggal')?'&#9745;':'&#9744;' ?>&nbsp;Meninggal 
          </td>
        </tr>
      </table>

  </div>

</body>
<footer>
  <div style="text-align:center;">
    <div>
      <p> Jakarta, <?php echo $this->tanggal->formatDate(date('Y-m-d')) ?> </p>
    </div>
    <div>
      <p> Dokter Pemeriksa, </p>
    </div><br><br><br>
    <p>(<?php echo $value->nama_pegawai ?>)</p>
  </div>
</footer>
</html>








