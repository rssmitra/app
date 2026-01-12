<script>
$(document).ready(function() {
  // pastikan tidak ada duplikasi datepicker
  $('.date-picker').datepicker('destroy'); 

  // aktifkan hanya sekali
  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: 'yyyy-mm-dd'
  });
});
</script>

<?php echo $header; ?>
<hr>
<br>
<div style="text-align: center; font-size: 18px;"><b>SURAT KETERANGAN DOKTER</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<p>
  Yang bertanda tangan dibawah ini, dokter <b>Rumah Sakit Setia Mitra</b> di Jakarta, menerangkan bahwa :<br>
  <table width="100%">
    <tr>
      <td width="100px">No. MR</td>
      <td colspan="2" width="200px">
        <input type="text" class="input_type" style="width: 100px !important" name="form_124[no_mr_pasien_istirahat]" id="no_mr_pasien_istirahat" onchange="fillthis('no_mr_pasien_istirahat')" value="<?php echo isset($value_form['no_mr_pasien_istirahat'])?$value_form['no_mr_pasien_istirahat']:$data_pasien->no_mr?>">
      </td>
    </tr>
    <tr>
      <td>Nama Pasien</td>
      <td>
        <input type="text" class="input_type" style="width: 250px !important" name="form_124[nama_pasien_istirahat]" id="nama_pasien_istirahat" onchange="fillthis('nama_pasien_istirahat')" value="<?php echo isset($value_form['nama_pasien_istirahat'])?$value_form['nama_pasien_istirahat']:$data_pasien->nama_pasien?>">
      </td>
      <td>
        <label>
          <input type="checkbox" class="ace" name="form_124[jk_l]" id="jk_l"  onclick="checkthis('jk_l')" <?php echo ($data_pasien->jen_kelamin == 'L')?"checked":"";?>>
          <span class="lbl" > Laki-laki</span>
        </label>
        
        <label>
          <input type="checkbox" class="ace" name="form_124[jk_p]" id="jk_p"  onclick="checkthis('jk_p')" <?php echo ($data_pasien->jen_kelamin == 'P')?"checked":"";?>>
          <span class="lbl" > Perempuan</span>
        </label>

      </td>
    </tr>
    <tr>
      <td>Umur</td>
      <td colspan="2">
        <input type="text" class="input_type" style="width: 50px !important" name="form_124[umur_pasien]" id="umur_pasien" onchange="fillthis('umur_pasien')" value="<?php echo isset($value_form['umur_pasien'])?$value_form['umur_pasien']:$data_pasien->umur?>">
      </td>
    </tr>
    <tr>
      <td>Alamat</td>
      <td colspan="2">
        <input type="text" class="input_type" style="width: 100% !important" name="form_124[alamat_pasien]" id="alamat_pasien" onchange="fillthis('alamat_pasien')" value="<?php echo isset($value_form['alamat_pasien'])?$value_form['alamat_pasien']:$data_pasien->almt_ttp_pasien?>">
      </td>
    </tr>
  </table>
  <br>
  Sesuai dengan pemeriksaan yang kami lakukan tanggal 
      <input type="text" class="input_type date-picker" style="width: 70px !important; text-align: center" data-date-format="yyyy-mm-dd" name="form_124[tgl_masuk]" id="tgl_masuk" onchange="fillthis('tgl_masuk')" value="<?php echo isset($value_form['tgl_masuk'])?$value_form['tgl_masuk']:date('Y-m-d')?>"> 
  yang bersangkutan dinyatakan:
      <br>
  <ol>
    <li>
      Sehat<br> Bekerja tetapi dalam pengawasan <input type="text" class="input_type" placeholder="isi keterangan (bila ada)" style="width: 460px !important; text-align: left" name="form_124[bekerja_catatan]" id="bekerja_catatan" onchange="fillthis('bekerja_catatan')" value="<?php echo isset($value_form['bekerja_catatan'])?$value_form['bekerja_catatan']:''?>">
    </li>
    <li>
      Sakit dan memerlukan istirahat selama
      (<input type="text" class="input_type" style="width: 30px !important; text-align: center" name="form_124[huruf_hari]" id="huruf_hari" onchange="fillthis('huruf_hari')" value="<?php echo isset($value_form['huruf_hari'])?$value_form['huruf_hari']:''?>">) hari, 
      terhitung mulai tanggal 
      <input type="text" class="input_type date-picker" style="width: 100px !important; text-align: center" data-date-format="yyyy-mm-dd" name="form_124[start_tgl]" id="start_tgl" onchange="fillthis('start_tgl')" value="<?php echo isset($value_form['start_tgl'])?$value_form['start_tgl']:date('Y-m-d')?>">
      &nbsp; s/d &nbsp; 
      <input type="text" class="input_type date-picker" style="width: 100px !important; text-align: center" data-date-format="yyyy-mm-dd" name="form_124[end_tgl]" id="end_tgl" onchange="fillthis('end_tgl')" value="<?php echo isset($value_form['end_tgl'])?$value_form['end_tgl']:date('Y-m-d')?>"> 
    </li>
  </ol>
  Demikian surat keterangan ini dibuat, agar dapat dipergunakan sebagaimana mestinya.
</p>
<hr>
<?php echo $footer; ?>