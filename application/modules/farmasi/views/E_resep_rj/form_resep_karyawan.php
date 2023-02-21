<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>
 $('#inputKeyKaryawan').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getkaryawanAsPasien",
                data: { keyword:query},            
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
          $('#no_mr').val(val_item);
          $('#nama_pasien').val(label_item);
          $('#inputKeyObat').focus();
          // cek existing
          $.getJSON("<?php echo site_url('farmasi/Entry_resep_ri_rj/cek_resep_karyawan_today') ?>?no_mr="+val_item, '' , function (response) {
              $('#kode_trans_far').val(response.kode_trans_far);
              if(response.kode_trans_far) {
                $('#show_find_result_from_inputKeyKaryawan').html('<div class="alert alert-danger"><strong>Peringatan!</strong >Terdapat transaksi yang belum diproses pada tanggal '+response.tgl_trans+', silahkan selesaikan transaksi sebelumnya dahulu.</div>');
              }else{
                $('#show_find_result_from_inputKeyKaryawan').html('<div class="alert alert-info"><strong>Pemberitahuan!</strong > Data transaksi bersih, silahkan lanjutkan pencarian obat.</div>');
              }
              reload_table();
          })

          $.getJSON("templates/references/getPasienByMr/"+val_item, '' , function (response) {
              $('#kode_perusahaan').val(response.kode_perusahaan);
              $('#kode_kelompok').val(response.kode_kelompok);
          })

        }
});
</script>

<p class="center" style="margin-top: 10px">
  <span style="font-size: 16px; font-weight: bold">RESEP KARYAWAN</span><br>
  <span>Silahkan lakukan pencarian Nama Karyawan terlebih dahulu pada form dibawah ini.</span>
</p>
<div>
  <label for="form-field-8"><b>Nama Karyawan : </b></label>
  <input type="text" class="form-control" name="" id="inputKeyKaryawan" placeholder="Masukan keyword" value="<?php echo isset($value->nama_pasien)?$value->nama_pasien:''?>">
  <small id="">Silahkan lakukan pencarian data karyawan</small>
</div>
<div id="show_find_result_from_inputKeyKaryawan"></div>
<hr>