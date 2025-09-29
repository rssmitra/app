<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>
jQuery(function($) {

  $('#form_revisi').ajaxForm({
    beforeSend: function() {
      achtungShowLoader();  
    },
    uploadProgress: function(event, position, total, percentComplete) {
    },
    complete: function(xhr) {     
      var data=xhr.responseText;
      var jsonResponse = JSON.parse(data);

      if(jsonResponse.status === 200){
        $.achtung({message: jsonResponse.message, timeout:5});
        // close modal
        $('#globalModalViewMedium').modal('hide');
        cartData.ajax.reload();
      }else{
        $.achtung({message: jsonResponse.message, timeout:5});
      }
      achtungHideLoader();
    }
  }); 


});

function submit_revisi(){
  preventDefault();
  $('#form_revisi').submit();
}

$(document).ready(function(){

    $('#inputKeyBarang').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/References/getItemBarangByUnit",
              data: { 
                keyword : query, 
                flag : $('#flag_type').val(), 
                unit : $('#kode_gudang').val() 
              },            
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
        $('#div_detail_brg').html('');
        // get item detail
        getDetailBarang(val_item);
        $('#qtyBarang').focus();
        $('#inputKeyBarang').val(label_item);
        $('#barcode_value').val('');
        $('#barcode_text').text('');
        $('#barcode_input').hide();
      }
    });

});

function getDetailBarang(kode_brg){
  preventDefault();
  $('#div_detail_brg').show();
  
  $.getJSON('Templates/References/getItemBarangDetailByUnit?kode_brg=' + kode_brg + '&flag='+$("#flag_type").val()+'&from_unit='+$('#kode_gudang').val()+'', '', function (response) {
      // detail barang
      var dt_brg = response.data;
      $('#kode_brg_revisi').val(kode_brg);
      $('#nama_brg_hidden').val(dt_brg.nama_brg);
      $('#satuan_brg_hidden').val(dt_brg.satuan_kecil);
      $('#harga_brg_hidden').val(dt_brg.harga_beli);
      $('#qtyBarang').attr('max', parseInt(dt_brg.jml_sat_kcl));
      $('#qtyStok').val(parseInt(dt_brg.jml_sat_kcl));
      $('#qtyBarang').val('');
      $('#qtyBarang').focus();
      $('#div_detail_brg').html( response.html );
       
  });
  
}

</script>

<style>

.table-custom{
  font-family: calibri;
  font-size: 13px;
  background-color: white;
  width: 100% !important
}
th, td {
    padding: 2px;
    text-align: left;
  }

.blink_me {
  animation: blinker 1s linear infinite;
  }

  @keyframes blinker {
    50% {
      opacity: 0;
    }
  }

  .error{
    color: red;
    font-size: 10px;
  }

  .widget-body {
      min-height: 77px !important;
  }
</style>

<div class="row">
  <div class="col-xs-12">
    <br>
    <!-- PAGE CONTENT BEGINS -->

        <form class="form-horizontal" method="post" id="form_revisi" action="<?php echo base_url().'purchasing/pendistribusian/Pengiriman_unit/process_edit_brg'?>" enctype="multipart/form-data" style="margin-top: -10px" autocomplete="off">

          <input class="form-control" type="hidden" name="id" id="id" value="<?php echo isset($value->id_tc_permintaan_inst)?$value->id_tc_permintaan_inst:''?>">
          <input class="form-control" type="hidden" name="id_det" id="id_det" value="<?php echo isset($value->id_tc_permintaan_inst_det)?$value->id_tc_permintaan_inst_det:''?>">
          
            <p style="font-weight: bold">Permintaan Barang</p>
            <div class="form-group">
              <label class="control-label col-md-2">Jenis Barang</label>
              <div class="col-md-9" style="padding-left: 19px;padding-top: 5px;font-weight: bold;">
                <?php echo ucwords($flag) ?>
              </div>                
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Nama Barang</label>
              <div class="col-md-9" style="padding-left: 19px;padding-top: 5px;font-weight: bold;">
                <?php echo ucwords($value->nama_brg) ?>
              </div>                
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Jumlah Permintaan</label>
              <div class="col-md-9" style="padding-left: 19px;padding-top: 5px;font-weight: bold;">
                <?php echo $value->jumlah_permintaan ?> <?php echo ucwords($value->satuan) ?>
              </div>                
            </div>
            <hr>
            <p style="font-weight: bold">Penyesuaian Barang</p>
            
            <div class="form-group">
                <label class="control-label col-md-2">Cari Barang</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" name="keyword" id="inputKeyBarang">
                </div>
            </div>
            <!-- hidden after search barang -->
            <input class="form-control" type="hidden" name="kode_brg_revisi" id="kode_brg_revisi" readonly>
            <input type="hidden" name="flag" id="flag_type" value="<?php echo isset($flag)?$flag:''?>">
            <input type="hidden" name="kode_bagian_minta" id="kode_bagian_minta" value="<?php echo isset($value->kode_bagian_minta)?$value->kode_bagian_minta:''?>">
            <input type="hidden" name="kode_gudang" id="kode_gudang" value="<?php echo isset($kode_gudang)?$kode_gudang:''?>">

            <div class="form-group">
              <label class="control-label col-md-2">Qty</label>
              <div class="col-md-2" >
                <input class="form-control" type="number" name="qtyBrg" id="qtyBarang">
                <input class="form-control" type="hidden" name="qtyBrgStok" id="qtyStok">
              </div>
              <div class="col-md-4" >
              <label style="padding-top: 3px">
                <input name="is_bhp" id="is_bhp" value="1" type="checkbox" class="ace">
                <span class="lbl"> BHP (Barang Habis Pakai)</span>
              </label>
              </div>
            </div>

            <hr>
            <div class="center" style="padding-right: 10px; padding-bottom: 5px">

              <a href="#" id="btnSave" name="submit" class="btn btn-xs btn-danger">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                Reset Form
              </a>
              <a href="#" id="btnSave" onclick="submit_revisi()" name="submit" class="btn btn-xs btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </a>
              
            </div>

          
        </form>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


