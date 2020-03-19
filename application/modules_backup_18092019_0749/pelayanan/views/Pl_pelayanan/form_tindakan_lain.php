<script>

$(document).ready(function(){

    $('#inputDokter1').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getDokterByBagian",
                data: 'keyword=' + query + '&bag=' + $('#kode_bagian_val').val(),         
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
        console.log(val_item);
        $('#inputDokter1Hidden').val(val_item);
            
        }
    });

    $('#inputDokter2').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getDokterByBagian",
                data: 'keyword=' + query + '&bag=' + $('#kode_bagian_val').val(),         
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
        console.log(val_item);
        $('#inputDokter2Hidden').val(val_item);
            
        }
    });

    $('#inputDokter3').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getDokterByBagian",
                data: 'keyword=' + query + '&bag=' + $('#kode_bagian_val').val(),         
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
        console.log(val_item);
        $('#inputDokter3Hidden').val(val_item);
            
        }
    });


})

function sumBillRs(){
  sumClass = sumClassBilling('add_bill_rs');
  $('#bill_rs').val(sumClass);

}

function sumClassBilling(classname){

  var sum = 0;

  $("."+classname).each(function() {
      var val = $.trim( $(this).val() );
      
      if ( val ) {
          val = parseFloat( val.replace( /^\$/, "" ) );
      
          sum += !isNaN( val ) ? val : 0;
      }
  });


  return sum;
}

</script>

<style type="text/css">
  .pagination{
    margin: 0px 0px !important;
  }
  .well{
    padding: 5px !important;
  }
</style>
<div class="row">

    <div class="col-md-12">
        <br>
        <p><b> TINDAKAN LAINNYA <i class="fa fa-angle-double-right bigger-120"></i></b></p>

          <div class="form-group">
              <label class="control-label col-sm-2" for="">Nama Tindakan</label>
              <div class="col-sm-4">
                 <input type="text" class="form-control" name="nama_tindakan" value="">
              </div>
          </div>

          <?php if($flag=='luar') : ?>
          <div class="form-group">
              <label class="control-label col-sm-2" for="">Bill RS</label>
              <div class="col-sm-2">
                 <input type="text" class="form-control" name="bill_rs" value="" style="text-align:right !important" >
              </div>
          </div>
          <?php endif;?>

          <div class="form-group">
              <label class="control-label col-sm-2" for="">Bill Dokter 1</label>
              <div class="col-sm-2">
                 <input type="text" class="form-control" name="bill_dr1" value="" style="text-align:right !important">
              </div>
              <label class="control-label col-sm-2" for="">Nama Dokter 1</label>
              <div class="col-sm-5">
                 <input type="text" class="form-control" name="nama_dokter_1" id="inputDokter1" value="" placeholder="Masukan 3 Keyword">
                 <input type="hidden" class="form-control" name="kode_dokter_1_hidden" id="inputDokter1Hidden" value="">
              </div>
          </div>

          <div class="form-group">
              <label class="control-label col-sm-2" for="">Bill Dokter 2</label>
              <div class="col-sm-2">
                 <input type="text" class="form-control" name="bill_dr2" value="" style="text-align:right !important">
              </div>
              <label class="control-label col-sm-2" for="">Nama Dokter 2</label>
              <div class="col-sm-5">
                 <input type="text" class="form-control" name="nama_dokter_2" id="inputDokter2" value="" placeholder="Masukan 3 Keyword">
                 <input type="hidden" class="form-control" name="kode_dokter_2_hidden" id="inputDokter2Hidden" value="">
              </div>
          </div>

          <div class="form-group">
              <label class="control-label col-sm-2" for="">Bill Dokter 3</label>
              <div class="col-sm-2">
                 <input type="text" class="form-control" name="bill_dr3" value="" style="text-align:right !important">
              </div>
              <label class="control-label col-sm-2" for="">Nama Dokter 3</label>
              <div class="col-sm-5">
                 <input type="text" class="form-control" name="nama_dokter_3" id="inputDokter3" value="" placeholder="Masukan 3 Keyword">
                 <input type="hidden" class="form-control" name="kode_dokter_3_hidden" id="inputDokter3Hidden" value="">
              </div>
          </div>

          <?php if($flag=='lain') : ?>
          <!-- detail item -->
          <table class="table table-bordered">
              <tr style="background-color:#428bca; color: white">
                <th>BHP</th>
                <th>Alat RS</th>
                <th>Pendapatan RS</th>
                <th>Kamar Tindakan</th>
                <th>Total = Bill RS</th>
              </tr>
              <tr>
                <td><input type="text" class="form-control add_bill_rs" onchange="sumBillRs()" name="bhp" value="" style="text-align:right !important"></td>
                <td><input type="text" class="form-control add_bill_rs" onchange="sumBillRs()" name="alat_rs" value="" style="text-align:right !important"></td>
                <td><input type="text" class="form-control add_bill_rs" onchange="sumBillRs()" name="pendapatan_rs" value="" style="text-align:right !important"></td>
                <td><input type="text" class="form-control add_bill_rs" onchange="sumBillRs()" name="kamar_tindakan" value="" style="text-align:right !important"></td>
                <td><input type="text" class="form-control" name="bill_rs" value="" style="text-align:right !important" readonly id="bill_rs" ></td>
              </tr>
          </table>

          <?php endif; ?>

    </div>

</div><!-- /.row -->




