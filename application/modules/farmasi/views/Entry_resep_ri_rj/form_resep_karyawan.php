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
                $('#show_find_result_from_inputKeyKaryawan').html('*) Terdapat transaksi yang belum diproses pada tanggal '+response.tgl_trans+'');
              }else{
                $('#show_find_result_from_inputKeyKaryawan').html('Tidak ada data ditemukan');
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
<p><b>RESEP KARYAWAN</b></p>

<div class="form-group">
  <label class="control-label col-sm-2">Nama Karyawan</label>
  <div class="col-md-4">
    <input type="text" class="form-control" name="" id="inputKeyKaryawan" placeholder="Masukan keyword">
  </div>
</div>
<div id="show_find_result_from_inputKeyKaryawan" style="color: red; font-size:10px"></div>
<hr>