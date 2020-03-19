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
              reload_table();
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
<hr>