<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>

<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>
/* Fungsi untuk mencari data pasien berdasarkan keyword (misalnya No MR) */
function find_pasien_by_keyword(keyword) {  
  $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien_by_mr') ?>?keyword=" + keyword, '', function (data) {      

    // Jika hanya ada satu hasil data pasien
    if (data.count == 1) {
      var obj = data.result[0];
      var pending_data_pasien = data.pending; 
      var umur_pasien = getAge(obj.tgl_lhr, 1);

      // isi semua elemen di halaman
      $('#no_mr').text(obj.no_mr);
      $('#noMrHidden').val(obj.no_mr);
      $('#no_ktp').text(obj.no_ktp);

      // tambahan input
      $('#nikPasien').val(obj.no_ktp);
      $('#hpPasien').val(obj.no_hp);
      $('#noTelpPasien').val(obj.tlp_almt_ttp);

      // nama pasien
      $('#nama_pasien').text(obj.nama_pasien + ' (' + obj.jen_kelamin + ')');
      $('#nama_pasien_hidden').val(obj.nama_pasien);

      // jenis kelamin dan umur
      $('#jk').text(obj.jen_kelamin);
      $('#umur').text(umur_pasien + ' Tahun');
      $('#tgl_lhr').text(getFormattedDate(obj.tgl_lhr));

      // 👉 Tambahan ini untuk isi field input umur pasien di form kamu
      $('#umur_pasien').val(umur_pasien);  

      $('#umur_saat_pelayanan_hidden').val(umur_pasien);
      $('#alamat').text(obj.almt_ttp_pasien);
      $('#hp').text(obj.no_hp);
      $('#no_telp').text(obj.tlp_almt_ttp);
      $('#catatan_pasien').text(obj.keterangan);
      $('#ttd_pasien').attr('src', obj.ttd);
      $('#noKartuBpjs').val(obj.no_kartu_bpjs);

      // foto
      if (obj.url_foto_pasien) {
        $('#avatar').attr('src', '<?php echo base_url()?>uploaded/images/photo/' + obj.url_foto_pasien);
      } else {
        if (obj.jen_kelamin == 'L') {
          $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');
        } else {
          $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');
        }
      }

      // handling BPJS
      if (obj.kode_perusahaan == 120) {
        $('#form_sep').show('fast'); 
        $('#no_kartu_bpjs_txt').text('(' + obj.no_kartu_bpjs + ')');
      } else {
        $('#form_sep').hide('fast'); 
        $('#no_kartu_bpjs_txt').text('');
      }

      // penjamin & kelompok
      penjamin = (obj.nama_perusahaan == null) ? obj.nama_kelompok : obj.nama_perusahaan;
      kelompok = (obj.nama_kelompok == null) ? '-' : obj.nama_kelompok;

      $('#kode_perusahaan').text(penjamin);
      $('#kode_perusahaan_hidden').val(obj.kode_perusahaan);
      $('#kode_kelompok_hidden').val(obj.kode_kelompok);
      $('#InputKeyPenjamin').val(obj.nama_perusahaan);
      $('#InputKeyNasabah').val(obj.nama_kelompok);
      $('#total_kunjungan').text(obj.total_kunjungan);

      $('#full_pasien_data').text(
        obj.no_mr + ' - ' + obj.nama_pasien + 
        ' (' + obj.jen_kelamin + ') | TL. ' + getFormattedDate(obj.tgl_lhr) + 
        ' (' + umur_pasien + ')'
      );

      $("#myTab li").removeClass("active");
    }            
  }); 
}
</script>

<script>

jQuery(function($) {  
   // Unbind event lama (penting!)
  $('.date-picker').datepicker('destroy');

  // Inisialisasi ulang dengan opsi yang sama
  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: 'dd/mm/yyyy'
  }).on('show', function(e) {
    // Pastikan hanya satu instance tampil
    $('.datepicker').not($(this).data('datepicker').picker).remove();
  });

  $('#diagnosis').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getICD10",
              data: 'keyword=' + query,            
              dataType: "json",
              type: "POST",
              success: function (response) {
                result($.map(response, function (item) {
                      return item;
                  }));
                
              }
          });
      },
      afterSelect: function (item) {
        // do what is needed with item
        var label_item=item.split(':')[1];
        var val_item=item.split(':')[0];
        console.log(val_item);
        $('#diagnosis').val(label_item);
      }

  });

});
</script>

<?php echo $header; ?>
<hr>
<br>
<div style="text-align: center; font-size: 18px;">
  <b><u>MEDICAL SUPPORTING LETTER</u></b><br>
</div>
<div style="text-align: center; font-size: 13px; margin-top: -5px">Nomor. <?php echo isset($result->reg_data->no_registrasi)?$result->reg_data->no_registrasi:''?>.SKS/<?php echo date('d')?>-<?php echo date('m')?>/<?php echo date('Y')?></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<p>
  To Whom It May Concern,<br>
  This letter is to confirm that I, an Obstetrics and Gynecology (Obsgyn) Specialist, am the treating health practitioner for<br>
  <table width="100%">
    
  <tr>
  <td style="width: 220px !important">Name</td>
  <td> :
    <input 
      type="text" 
      class="input_type" 
      style="width: 350px !important" 
      name="form_116[nama_pasien_istirahat]" 
      id="nama_pasien_istirahat" 
      onchange="fillthis('nama_pasien_istirahat')" 
      value="<?php 
        $nama_pasien = isset($data_pasien->nama_pasien) ? $data_pasien->nama_pasien : ''; 
        echo isset($value_form['nama_pasien_istirahat']) ? $value_form['nama_pasien_istirahat'] : $nama_pasien; 
      ?>"
    >
  </td>
</tr>

<tr>
  <td>Gender</td>
  <td> :
    <input 
      type="text" 
      class="input_type" 
      name="form_116[jenis_kelamin]" 
      id="jenis_kelamin" 
      value="<?php 
        $jen_kelamin = isset($data_pasien->jen_kelamin) ? $data_pasien->jen_kelamin : ''; 
        $gender_text = ($jen_kelamin == 'L') ? 'Men' : (($jen_kelamin == 'P') ? 'Women' : ''); 
        echo isset($value_form['jenis_kelamin']) ? $value_form['jenis_kelamin'] : $gender_text; 
      ?>"
    >
  </td>
</tr>

    <!-- <tr>
      <td>Umur</td>
      <td colspan="2">
        <input type="text" class="input_type" style="width: 50px !important" name="form_116[umur_pasien]" id="umur_pasien" onchange="fillthis('umur_pasien')" value="<?php $umur = isset($data_pasien->umur)?$data_pasien->umur:''; echo isset($value_form['umur_pasien'])?$value_form['umur_pasien']:$data_pasien->umur?>">
      </td>
    </tr> -->

<tr>
  <td>Date of Birth</td>
  <td colspan="2">
    : <input 
      type="text" 
      class="input_type date-picker" data-date-format="yyyy-mm-dd"
      style="width: 120px !important;" 
      name="form_116[tanggal_lahir]" 
      id="tanggal_lahir"
      value="<?php 
        $tgl_lhr = isset($data_pasien->tgl_lhr) ? $data_pasien->tgl_lhr : ''; 
        // hapus waktu jika ada dan ubah format ke dd-mm-yy
        if (!empty($tgl_lhr)) {
          $tgl_lhr = date('Y-m-d', strtotime($tgl_lhr));
        }
        echo isset($value_form['tanggal_lahir']) ? $value_form['tanggal_lahir'] : $tgl_lhr; 
      ?>"
    >
  </td>
</tr>



<tr>
  <td style="width: 220px !important;">Indonesian Home Address</td>
  <td> :
    <input 
      type="text" 
      class="input_type" 
      style="width: 95% !important;" 
      name="form_116[alamat_pasien]" 
      id="alamat_pasien" 
      onchange="fillthis('alamat_pasien')" 
      value="<?php 
        $almt_ttp_pasien = isset($data_pasien->almt_ttp_pasien) ? $data_pasien->almt_ttp_pasien : ''; 
        echo isset($value_form['alamat_pasien']) ? $value_form['alamat_pasien'] : $almt_ttp_pasien; 
      ?>"
    >
  </td>
</tr>

    <tr>
      <td width="100px">Medical Record Number</td>
      <td colspan="2" width="200px"> :
        <input type="text" class="input_type" style="width: 100px !important" name="form_116[no_mr_pasien_istirahat]" id="no_mr_pasien_istirahat" onchange="fillthis('no_mr_pasien_istirahat')" value="<?php $no_mr = isset($data_pasien->no_mr)?$data_pasien->no_mr:''; echo isset($value_form['no_mr_pasien_istirahat'])?$value_form['no_mr_pasien_istirahat']:$no_mr?>">
      </td>
    </tr>
  </table>
  <br>
 
  <!-- BAGIAN KETERANGAN KEHAMILAN -->
<p style="font-size:14px;">
  Ms. <b>
    <?php 
      $nama_pasien = isset($data_pasien->nama_pasien) ? $data_pasien->nama_pasien : ''; 
      echo isset($value_form['nama_pasien_istirahat']) ? $value_form['nama_pasien_istirahat'] : $nama_pasien; 
    ?>
  </b> 
  is currently pregnant (G.
  <input 
    type="text" 
    class="input_type" 
    style="width: 40px !important; text-align:center;" 
    name="form_116[g]" 
    id="g" 
    value="<?php echo isset($value_form['g']) ? $value_form['g'] : ''; ?>"
  > 
  P.
  <input 
    type="text" 
    class="input_type" 
    style="width: 40px !important; text-align:center;" 
    name="form_116[p]" 
    id="p" 
    value="<?php echo isset($value_form['p']) ? $value_form['p'] : ''; ?>"
  > 
  A.
  <input 
    type="text" 
    class="input_type" 
    style="width: 40px !important; text-align:center;" 
    name="form_116[a]" 
    id="a" 
    value="<?php echo isset($value_form['a']) ? $value_form['a'] : ''; ?>"
  > ) with this detail:
</p>

<table width="100%" border="0" cellspacing="2" cellpadding="3" style="font-size:13px;">
  <tr>
    <td style="width: 250px;">LMP (Last Menstrual Period)</td>
    <td>:
      <input 
        type="text" 
        class="input_type" 
        style="width: 200px !important;" 
        name="form_116[lmp]" 
        id="lmp" 
        value="<?php echo isset($value_form['lmp']) ? $value_form['lmp'] : ''; ?>"
      >
    </td>
  </tr>
  <!--<tr>
    <td>28 Weeks of Pregnancy</td>
    <td>:
      <input 
        type="text" 
        class="input_type" 
        style="width: 200px !important;" 
        name="form_116[pregnancy_28]" 
        id="pregnancy_28" 
        value="<?php //echo isset($value_form['pregnancy_28']) ? $value_form['pregnancy_28'] : ''; ?>"
      >
    </td>
  </tr>-->
  <!--<tr>
    <td>32 Weeks of Pregnancy</td>
    <td>:
      <input 
        type="text" 
        class="input_type" 
        style="width: 200px !important;" 
        name="form_116[pregnancy_32]" 
        id="pregnancy_32" 
        value="<?php //echo isset($value_form['pregnancy_32']) ? $value_form['pregnancy_32'] : ''; ?>"
      >
    </td>
  </tr>-->
  <tr>
    <td>EDD (Estimated Due Date)</td>
    <td>:
      <input 
        type="text" 
        class="input_type" 
        style="width: 200px !important;" 
        name="form_116[edd]" 
        id="edd" 
        value="<?php echo isset($value_form['edd']) ? $value_form['edd'] : ''; ?>"
      >
    </td>
  </tr>
  <tr>
  <td colspan="2">
    As of the date of this letter, her gestational age is approximately 
    <input 
      type="text" 
      class="input_type" 
      style="width: 60px !important; text-align:center;" 
      name="form_116[gestational_age]" 
      id="gestational_age" 
      value="<?php echo isset($value_form['gestational_age']) ? $value_form['gestational_age'] : ''; ?>"
    > 
    weeks. Thank you for your understanding.
  </td>
</tr>

</table>

<p style="font-size:14px; margin-top:10px;">
  Thank you for your understanding.
</p>

  <br>
  
<hr>

<?php  echo $footer; ?>