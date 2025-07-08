<script>

jQuery(function($) {  

  $('.date-picker').datepicker({    
    autoclose: true,    
    todayHighlight: true    
  })  
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){    
    $(this).prev().focus();    
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
<div style="text-align: center; font-size: 18px;"><b>SURAT KETERANGAN ISTIRAHAT</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<p>
  Yang bertanda tangan dibawah ini, dokter <b>Rumah Sakit Setia Mitra</b> di Jakarta, menerangkan bahwa :<br>
  <table width="100%">
    <tr>
      <td width="100px">No. MR</td>
      <td colspan="2" width="200px">
        <input type="text" class="input_type" style="width: 100px !important" name="form_51[no_mr_pasien_istirahat]" id="no_mr_pasien_istirahat" onchange="fillthis('no_mr_pasien_istirahat')" value="<?php echo isset($value_form['no_mr_pasien_istirahat'])?$value_form['no_mr_pasien_istirahat']:$data_pasien->no_mr?>">
      </td>
    </tr>
    <tr>
      <td>Nama Pasien</td>
      <td>
        <input type="text" class="input_type" style="width: 250px !important" name="form_51[nama_pasien_istirahat]" id="nama_pasien_istirahat" onchange="fillthis('nama_pasien_istirahat')" value="<?php echo isset($value_form['nama_pasien_istirahat'])?$value_form['nama_pasien_istirahat']:$data_pasien->nama_pasien?>">
      </td>
      <td>
        <label>
          <input type="checkbox" class="ace" name="form_51[jk_l]" id="jk_l"  onclick="checkthis('jk_l')" <?php echo ($data_pasien->jen_kelamin == 'L')?"checked":"";?>>
          <span class="lbl" > Laki-laki</span>
        </label>
        
        <label>
          <input type="checkbox" class="ace" name="form_51[jk_p]" id="jk_p"  onclick="checkthis('jk_p')" <?php echo ($data_pasien->jen_kelamin == 'P')?"checked":"";?>>
          <span class="lbl" > Perempuan</span>
        </label>

      </td>
    </tr>
    <tr>
      <td>Umur</td>
      <td colspan="2">
        <input type="text" class="input_type" style="width: 50px !important" name="form_51[umur_pasien]" id="umur_pasien" onchange="fillthis('umur_pasien')" value="<?php echo isset($value_form['umur_pasien'])?$value_form['umur_pasien']:$data_pasien->umur?>">
      </td>
    </tr>
    <tr>
      <td>Alamat</td>
      <td colspan="2">
        <input type="text" class="input_type" style="width: 100% !important" name="form_51[alamat_pasien]" id="alamat_pasien" onchange="fillthis('alamat_pasien')" value="<?php echo isset($value_form['alamat_pasien'])?$value_form['alamat_pasien']:$data_pasien->almt_ttp_pasien?>">
      </td>
    </tr>
  </table>
  <br>
  <ol>
    <li>
      Oleh karena pasien tersebut <b>Sakit</b> / <b>Dirawat</b> di Rumah Sakit Setia Mitra, maka perlu diberikan istirahat selama 
      <input type="text" class="input_type" style="width: 50px !important; text-align: center" name="form_51[angka_hari]" id="angka_hari" onchange="fillthis('angka_hari')" value="<?php echo isset($value_form['angka_hari'])?$value_form['angka_hari']:''?>"> 
      (<input type="text" class="input_type" style="width: 50px !important; text-align: center" name="form_51[huruf_hari]" id="huruf_hari" onchange="fillthis('huruf_hari')" value="<?php echo isset($value_form['huruf_hari'])?$value_form['huruf_hari']:''?>">) hari, 
      terhitung mulai tanggal 
      <input type="text" class="input_type" style="width: 100px !important; text-align: center" name="form_51[start_tgl]" id="start_tgl" onchange="fillthis('start_tgl')" value="<?php echo isset($value_form['start_tgl'])?$value_form['start_tgl']:''?>">
      &nbsp; s/d &nbsp; 
      <input type="text" class="input_type" style="width: 100px !important; text-align: center" name="form_51[end_tgl]" id="end_tgl" onchange="fillthis('end_tgl')" value="<?php echo isset($value_form['end_tgl'])?$value_form['end_tgl']:''?>"> 
    </li>
    <li>
      Perlu diberikan cuti melahirkan selama 3 (tiga) bulan, karena diperkirakan akan melahirkan pada tanggal <input type="text" class="input_type date-picker" data-date-format="dd/mm/yyyy" style="width: 100px !important; text-align: center" name="form_51[tgl_melahirkan]" id="tgl_melahirkan" onchange="fillthis('tgl_melahirkan')" value="<?php echo isset($value_form['tgl_melahirkan'])?$value_form['tgl_melahirkan']:''?>">
    </li>
  </ol>
  Demikian surat keterangan ini dibuat, agar dapat dipergunakan sebagaimana mestinya.
</p>
<hr>
<?php echo $footer; ?>