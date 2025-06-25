<script>

jQuery(function($) {  

  $('#diagnosa_pasca_bedah').typeahead({
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
        $('#diagnosa_pasca_bedah').val(label_item);
      }

  });


});
</script>

<?php echo $header; ?>
<hr>
<br>

<div style="text-align: center; font-size: 18px;"><b>LAYOUT E-RM POLI MATA</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<style>
.oftalmo-wrap {
  /* border: 1.5px solid #222; */
  border-radius: 10px;
  /* background: #fff; */
  max-width: 700px;
  margin: 18px auto;
  padding: 18px 22px;
  font-size: 14px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.oftalmo-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 8px;
}
.oftalmo-header .label {
  /* background: #eaeaea; */
  font-weight: bold;
  padding: 7px 16px;
  border-radius: 6px;
  font-size: 15px;
  letter-spacing: 1px;
}
.oftalmo-header .hasil {
  font-size: 13px;
  /* color: #444; */
  margin-top: 6px;
}
.oftalmo-ucva-table {
  width: 100%;
  margin-bottom: 8px;
  font-size: 13px;
}
.oftalmo-ucva-table td {
  padding: 2px 4px;
  border: none;
}
.oftalmo-posisi-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 10px 0;
}
.oftalmo-posisi-eye {
  width: 187px; height: 115px;
}
.oftalmo-posisi-center {
  flex: 1;
  text-align: center;
  font-weight: bold;
  font-size: 15px;
}
.oftalmo-line-label {
  display: flex;
  align-items: center;
  margin: 8px 0 0 0;
}
.oftalmo-line-label label {
  width: 110px;
  font-weight: 500;
  color: #222;
}
.oftalmo-line-label input {
  flex: 1;
  border: none;
  border-bottom: 1.2px solid #222;
  background: transparent;
  margin-left: 8px;
  font-size: 13px;
  padding: 2px 4px;
}
.oftalmo-fundus-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 12px 0 0 0;
}
.oftalmo-fundus-img {
  width: 38px; height: 38px;
  opacity: 0.7;
}
.oftalmo-textarea {
  width: 100%;
  min-height: 38px;
  border: 1px solid #bbb;
  border-radius: 5px;
  margin-top: 4px;
  margin-bottom: 8px;
  font-size: 13px;
  padding: 4px 8px;
  resize: vertical;
}
@media (max-width: 800px) {
  .oftalmo-wrap { padding: 8px 2vw; }
  .oftalmo-header .label { font-size: 13px; padding: 5px 8px; }
  .oftalmo-line-label label { width: 80px; }
}
</style>

<div class="oftalmo-wrap">
  <div class="oftalmo-header">
    <div class=""><b>STATUS OFTALMOLOGIS</b></div>
    <div class="hasil">Hasil Auto-Ref / NCT</div>
  </div>
  <table class="oftalmo-ucva-table">
    <tr>
      <td style="width:70px;">UCVA</td>
      <td style="width:70px;">Mata Kanan:</td>
      <td>
        <input type="text" name="form_49[ucva_kanan_s]" style="width:18px; text-align:center;"> S
        <input type="text" name="form_49[ucva_kanan_c]" style="width:18px; text-align:center;"> C
        <input type="text" name="form_49[ucva_kanan_x]" style="width:18px; text-align:center;"> X
      </td>
      <td style="width:70px;">UCVA</td>
      <td style="width:70px;">Mata Kiri:</td>
      <td>
        <input type="text" name="form_49[ucva_kiri_s]" style="width:18px; text-align:center;"> S
        <input type="text" name="form_49[ucva_kiri_c]" style="width:18px; text-align:center;"> C
        <input type="text" name="form_49[ucva_kiri_x]" style="width:18px; text-align:center;"> X
      </td>
    </tr>
    <tr>
      <td></td>
      <td>Mata Kanan:</td>
      <td colspan="2"><input type="text" name="form_49[add_kanan]" style="width:90%;"></td>
      <td>Mata Kiri:</td>
      <td><input type="text" name="form_49[add_kiri]" style="width:90%;"></td>
    </tr>
  </table>

  <div class="oftalmo-posisi-row">
    <img src="<?php echo base_url('assets/img-tagging/images/eye_left.png')?>" class="oftalmo-posisi-eye" alt="eye-left">
    <div class="oftalmo-posisi-center">Posisi</div>
    <img src="<?php echo base_url('assets/img-tagging/images/eye_right.png')?>" class="oftalmo-posisi-eye" alt="eye-right">
  </div>

  <div style="text-align:center; font-size:13px; margin-bottom:4px; margin-top:2px;">
    Tekanan Intraokular <br>
    <span style="font-size:12px;">(NCT / Aplanasi / Schiotz*)</span>
  </div>

 <style>
.oftalmo-field-table {
  width: 100%;
  margin: 10px 0 0 0;
  border-collapse: separate;
  border-spacing: 0 6px;
}
.oftalmo-field-table th, .oftalmo-field-table td {
  padding: 2px 4px;
  border: none;
  font-size: 13px;
}
.oftalmo-field-table th {
  text-align: center;
  font-weight: 600;
  background: #f7f7f7;
}
.oftalmo-field-table input {
  width: 90%;
  border: none;
  border-bottom: 1.2px solid #222;
  background: transparent;
  font-size: 13px;
  padding: 2px 4px;
  text-align: center;
}
</style>

<table class="oftalmo-field-table">
  <tr>
    <th style="width:35%">Mata Kanan</th>
    <th style="width:30%"></th>
    <th style="width:35%">Mata Kiri</th>
  </tr>
  <tr>
    <td><input type="text" name="form_49[tekanan_intraokular_kanan]"></td>
    <td align="center">Tekanan Intraokular</td>
    <td><input type="text" name="form_49[tekanan_intraokular_kiri]"></td>
  </tr>
  <tr>
    <td><input type="text" name="form_49[palpebra_kanan]"></td>
    <td align="center">Palpebra</td>
    <td><input type="text" name="form_49[palpebra_kiri]"></td>
  </tr>
  <tr>
    <td><input type="text" name="form_49[konjungtiva_kanan]"></td>
    <td align="center">Konjungtiva</td>
    <td><input type="text" name="form_49[konjungtiva_kiri]"></td>
  </tr>
  <tr>
    <td><input type="text" name="form_49[kornea_kanan]"></td>
    <td align="center">Kornea</td>
    <td><input type="text" name="form_49[kornea_kiri]"></td>
  </tr>
  <tr>
    <td><input type="text" name="form_49[bmd_kanan]"></td>
    <td align="center">BMD</td>
    <td><input type="text" name="form_49[bmd_kiri]"></td>
  </tr>
  <tr>
    <td><input type="text" name="form_49[iris_kanan]"></td>
    <td align="center">Iris</td>
    <td><input type="text" name="form_49[iris_kiri]"></td>
  </tr>
  <tr>
    <td><input type="text" name="form_49[pupil_kanan]"></td>
    <td align="center">Pupil</td>
    <td><input type="text" name="form_49[pupil_kiri]"></td>
  </tr>
  <tr>
    <td><input type="text" name="form_49[lensa_kanan]"></td>
    <td align="center">Lensa</td>
    <td><input type="text" name="form_49[lensa_kiri]"></td>
  </tr>
  <tr>
    <td><input type="text" name="form_49[vitreus_kanan]"></td>
    <td align="center">Vitreus</td>
    <td><input type="text" name="form_49[vitreus_kiri]"></td>
  </tr>
  <tr>
    <td><input type="text" name="form_49[funduskopi_kanan]"></td>
    <td align="center">Funduskopi</td>
    <td><input type="text" name="form_49[funduskopi_kiri]"></td>
  </tr>
</table>
  <!-- <div class="oftalmo-fundus-row">
    <img src="<?php echo base_url('assets/img-tagging/images/oftalmo_fundus.png')?>" class="oftalmo-fundus-img" alt="fundus">
    <div style="flex:1"></div>
    <img src="<?php echo base_url('assets/img-tagging/images/oftalmo_fundus.png')?>" class="oftalmo-fundus-img" alt="fundus">
  </div> -->

  <div style="margin-top:10px;">
    <label>Anamnesis :</label>
    <textarea name="form_49[anamnesis]" class="oftalmo-textarea"></textarea>
  </div>
  <div style="margin-top:8px;">
    <label>Diagnosis :</label>
    <textarea name="form_49[diagnosis]" class="oftalmo-textarea"></textarea>
  </div>
  <div style="font-size:11px; margin-top:8px;">*Coret yang tidak perlu</div>
</div>
<br>
<br>
<hr>
<?php echo $footer; ?>