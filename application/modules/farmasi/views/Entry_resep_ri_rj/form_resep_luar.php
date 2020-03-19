<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>

  $('#inputKeyNamaPasien').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "templates/references/getNamaPasien",
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

          }
  });

  $('#inputKeyDokterPengirim').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "templates/references/getAllDokter",
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
            $('#kode_dokter').val(val_item);
            $('#dokter_pengirim').val(label_item);
            $('#inputKeyObat').focus();

          }
  });

</script>

<p><b>RESEP LUAR</b></p>

<div class="form-group">
  <label class="control-label col-sm-2">Nama Pasien / (a.n)</label>
  <div class="col-md-3">
    <input type="text" class="form-control" name="nama_pasien_keyword" id="inputKeyNamaPasien">
  </div>
  <label class="control-label col-sm-1">No.Telp/ HP</label>
  <div class="col-md-2">
    <input type="text" class="form-control" name="no_telp">
  </div>
</div>
<div class="form-group">
  <label class="control-label col-sm-2">Alamat</label>
  <div class="col-md-3">
    <textarea name="alamat_pasien" id="" style="height: 50px !important; margin-bottom: 3px" class="form-control"></textarea>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-sm-2">Dokter Pengirim</label>
  <div class="col-md-3">
    <input type="text" class="form-control" id="inputKeyDokterPengirim" name="dokter_pengirim_keyword">
  </div>
</div>

<hr>