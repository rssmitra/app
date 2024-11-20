<script type="text/javascript">
$('#nama_dokter_konsul').typeahead({
    source: function (query, result) {
        $.ajax({
            url: "templates/references/getAllDokter",
            data: { keyword:query },            
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
      var val_item=item.split(':')[0];
      var label_item=item.split(':')[1];
      console.log(val_item);
      $('#nama_dokter_konsul').val(label_item);
    }

});
</script>

<div style="text-align: center; font-size: 14px"><b>LEMBAR KONSULTASI INTERNAL RAWAT JALAN</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<div>
  Kepada Yth : <input type="text" style="width: 100% !important" name="form_29[nama_dokter_konsul]" id="nama_dokter_konsul" onchange="fillthis('nama_dokter_konsul')">
  <br>
  <br>
  Mohon pemeriksaan dan pengobatan untuk :<br>
  <table class="table">
    <tr>
      <td width="100px">Nama Pasien</td><td><input type="text" style="width: 100% !important" name="form_29[nama_pasien_konsul]" id="nama_pasien_konsul" onchange="fillthis('nama_pasien_konsul')" value="<?php echo isset($data_pasien->nama_pasien)?$data_pasien->nama_pasien:''?>"></td>
    </tr>
    <tr>
      <td width="100px">Alamat</td><td><input type="text" style="width: 100% !important" name="form_29[alamat_pasien]" id="alamat_pasien" onchange="fillthis('alamat_pasien')" value="<?php echo isset($data_pasien->almt_ttp_pasien)?$data_pasien->almt_ttp_pasien:''?>"></td>
      <tr>
      <td width="100px">Usia</td><td><input type="text" style="width: 100% !important" name="form_29[usia_pasien]" id="usia_pasien" onchange="fillthis('usia_pasien')" value="<?php echo isset($data_pasien->umur)?$data_pasien->umur:''?>"></td>
    </tr>
    </tr>
  </table>
  <hr>
  Riwayat Penyakit (Penjelasan Singkat) :<br>
  <textarea style="width: 100% !important; height: 50px !important" name="form_29[riwayat_penyakit]" id="riwayat_penyakit" onchange="fillthis('riwayat_penyakit')"></textarea>
  <br>

  Diagnosis : <br>
  <textarea style="width: 100% !important; height: 50px !important" name="form_29[diagnosis]" id="diagnosis" onchange="fillthis('diagnosis')"></textarea>
  <br>
  Telah diberikan pengobatan : <br>
  <textarea style="width: 100% !important; height: 50px !important" name="form_29[pengobatan]" id="pengobatan" onchange="fillthis('pengobatan')"></textarea>

</div>



