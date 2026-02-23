<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

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
  
  $('#form_cart').ajaxForm({
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

        getMenu('purchasing/pendistribusian/Distribusi_permintaan?flag='+jsonResponse.flag+'');

      }else{
        $.achtung({message: jsonResponse.message, timeout:5});
      }
      achtungHideLoader();
    }
  }); 

  var id = $('#id').val();
  cartData = $('#cart-data').DataTable({ 
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "paging": false,
      "bInfo": false,
      "searching": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "purchasing/pendistribusian/Pengiriman_unit/get_detail_cart?flag="+$('#flag_cart').val()+"&id="+id,
          "type": "POST"
      },
      // drawCallBack
      "fnDrawCallback": function( response ) {
          var obj = response.json;
          if(obj.total_belum_didistribusi == 0){
            $('#btn_action').hide();
            $('#alert_finish').show();
          }else{
            $('#btn_action').show();
          }
      },

    });


});

function submit_cart(){
  preventDefault();
  $('#form_cart').submit();
}

function edit_brg(id_det, title){
  show_modal_medium('purchasing/pendistribusian/Pengiriman_unit/form_edit_brg/'+id_det+'?flag='+$('#flag_cart').val(), title);
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

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <br>
    <!-- PAGE CONTENT BEGINS -->

        <form class="form-horizontal" method="post" id="form_cart" action="<?php echo base_url().'purchasing/pendistribusian/Pengiriman_unit/process_pengiriman_brg_unit'?>" enctype="multipart/form-data" style="margin-top: -10px" autocomplete="off">

          <input class="form-control" type="hidden" name="id" id="id" value="<?php echo isset($value->id_tc_permintaan_inst)?$value->id_tc_permintaan_inst:''?>">
          <input class="form-control" type="hidden" name="kode_bagian_minta" id="kode_bagian_minta" value="<?php echo isset($value->kode_bagian_minta)?$value->kode_bagian_minta:''?>">
          
          <div class="col-md-12">
            <a href="#" onclick="getMenu('purchasing/pendistribusian/Distribusi_permintaan?flag=<?php echo $type?>')" class="btn btn-xs btn-default">
              <i class="fa fa-arrow-left bigger-150"></i>
            </a>
            <br>
            <div style="font-weight: bold; font-size: 14px; padding: 5px">Distribusi Barang Permintaan Stok Unit</div>
            <div class="form-group">
              <label class="control-label col-md-2">Jenis Barang</label>
              <div class="col-md-9" style="padding-left: 19px;padding-top: 5px;font-weight: bold;">
                <span style="color: <?php echo ($type=='medis')?'green':'blue';?>"><?php echo ucwords($type)?></span>
              </div>                
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-2">Permintaan dari Unit</label>
              <div class="col-md-7" style="padding-left: 19px;padding-top: 5px;font-weight: bold;">
                <span><?php echo isset($value->bagian_minta)?ucwords($value->bagian_minta):''?></span>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Tanggal Permintaan</label>
              <div class="col-md-7" style="padding-left: 19px;padding-top: 5px;font-weight: bold;">
                <span><?php echo isset($value->tgl_permintaan)?$this->tanggal->formatDateDmy($value->tgl_permintaan):''?></span>
              </div>
            </div>

            <!-- hidden after search barang -->
            <input type="hidden" name="flag_cart" id="flag_cart" value="<?php echo $type; ?>">

            <table id="cart-data" base-url="purchasing/pendistribusian/Pengiriman_unit" data-id="flag=<?php echo $type?>" class="table table-bordered table-hover">
              <thead>
                <tr>  
                  <th class="center" width="30px">
                    <div class="center">
                      <label class="pos-rel">
                          <input type="checkbox" class="ace" name="" onClick="checkAll(this);" value="0"/>
                          <span class="lbl"></span>
                      </label>
                    </div>
                  </th>
                  <th width="30px" class="center">No</th>
                  <th width="100px">Kode Brg</th>
                  <th>Nama Barang</th>
                  <th class="center">Satuan</th>
                  <th class="center">Stok Gudang</th>
                  <th class="center">Stok Akhir<br>Unit</th>
                  <th class="center">Jumlah<br>Permintaan</th>
                  <th class="center">Jumlah<br>Disetujui</th>
                  <th class="center">Jumlah<br>Kirim</th>
                  <th style="width: 80px">Total Nilai<br>Persediaan</th>
                  <th class="center">Keterangan Verif</th>
                  <th style="width: 50px">#</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>

            <div class="form-group">
              <label class="control-label col-md-2">Tgl Distribusi</label>
              <div class="col-md-2">
                <div class="input-group">
                    <input class="form-control date-picker" name="tgl_distribusi" id="tgl_distribusi" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                </div>
              </div>
              <label class="control-label col-md-1" style="margin-left: 42px">Petugas</label>
              <div class="col-md-2">
                <input class="form-control" type="text" name="yang_menyerahkan" id="yang_menyerahkan" value="<?php echo $this->session->userdata('user')->fullname?>">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Catatan</label>
              <div class="col-md-10">
                <textarea class="form-control" style="height: 50px !important; margin-bottom: 5px; width: 100%" id="catatan" name="catatan"></textarea>
              </div>
            </div>

            <hr>
            <div class="center" style="padding-right: 10px; padding-bottom: 5px" id="btn_action">
              <a href="#" id="btnSave" name="submit" class="btn btn-xs btn-danger">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                Reset Form
              </a>
              <a href="#" id="btnSave" onclick="submit_cart()" name="submit" class="btn btn-xs btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </a>
            </div>

            <div class="alert alert-success" id="alert_finish" style="display: none"><strong style="font-size: 16px">Barang telah didistribusikan!</strong><br> Barang yang sudah terdistribusi tidak dapat diproses ulang kembali.</div>

          </div>
          

        </form>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


