<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});

$(document).ready(function(){

    $('#form_permintaan').ajaxForm({
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
          $('#page-area-content').load('purchasing/permintaan/Req_pembelian?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }
        achtungHideLoader();
      }
    });

    var flag = ( $('#flag_string').val() ) ? $('#flag_string').val() : '' ;
    var search_by = $('select[name="search_by"]').val();
    var keyword = $('#inputKeyWord').val();

    $('#btn_search_brg').click(function (e) {
        if ( $('#inputKeyWord').val()=='' ) {
          alert('Silahkan Masukan Kata Kunci !'); return false;
        }
        search_selected_brg(flag, search_by, keyword);
        e.preventDefault();
    });

    $( "#inputKeyWord" ).keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            search_selected_brg(flag, search_by, keyword);
          }
          return false;
        }
    });

})

function search_selected_brg(flag, search_by, keyword){
  $.ajax({
      type      : 'POST',
      url       : 'Templates/References/getRefBrg',
      data      : {keyword: $('#inputKeyWord').val(), flag: flag, search_by: search_by},
      dataType  : 'json',
      success   : function(data) {
          $('#show_detail_selected_brg').html(data.html);
      }
  })
}
</script>

<style>
  .frm-card { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; margin-bottom: 12px; }
  .frm-card-hdr { background: #1a4f8a; color: #fff; padding: 8px 14px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  .frm-card-hdr small { font-weight: 400; opacity: .85; }
  .frm-card-body { padding: 16px 14px; background: #fff; }
  .frm-actions { padding: 10px 14px; background: #f8fafd; border-top: 1px solid #d0dce8; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
</style>

<div class="frm-card">
  <div class="frm-card-hdr">
    <i class="fa fa-inbox"></i> <?php echo $title?>
  </div>
  <div class="frm-card-body">
    <form class="form-horizontal" method="post" id="form_permintaan" action="<?php echo site_url('purchasing/permintaan/Req_pembelian/process')?>" enctype="multipart/form-data">

      <input type="hidden" name="flag" id="flag_string" value="<?php echo $flag?>">

      <div class="form-group">
        <label class="control-label col-md-2">No. Penerimaan (No.LPB)</label>
        <div class="col-md-2">
          <input name="id" id="id" value="" class="form-control" type="text">
        </div>
        <label class="control-label col-md-2">Tanggal Penerimaan</label>
        <div class="col-md-2">
          <div class="input-group">
            <input class="form-control date-picker" name="tgl_po" id="tgl_po" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($dt_detail_brg[0])?$this->tanggal->formatDateTimeToSqlDate($dt_detail_brg[0]->tgl_po):'';?>"/>
            <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">No. Faktur/Surat Jalan</label>
        <div class="col-md-3">
          <input name="no_surat_jalan" id="no_surat_jalan" value="" class="form-control" type="text">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Penerima</label>
        <div class="col-md-2">
          <input name="penerima" id="penerima" value="" class="form-control" type="text">
        </div>
        <label class="control-label col-md-2">Pengirim</label>
        <div class="col-md-2">
          <input name="pengirim" id="pengirim" value="" class="form-control" type="text">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Disetujui Oleh</label>
        <div class="col-md-2">
          <input name="disetujui_oleh" id="disetujui_oleh" value="" class="form-control" type="text">
        </div>
        <label class="control-label col-md-2">Diketahui Oleh</label>
        <div class="col-md-2">
          <input name="diketahui_oleh" id="diketahui_oleh" value="" class="form-control" type="text">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Keterangan</label>
        <div class="col-md-4">
          <textarea class="form-control" style="height:50px !important"></textarea>
        </div>
      </div>

      <?php echo $view_brg_po?>

      <div class="frm-actions">
        <a onclick="getMenuTabs('purchasing/penerimaan_brg/Pb_gudang/view_data?flag=<?php echo $flag?>', 'tabs_form_po')" href="#" class="btn btn-sm btn-success">
          <i class="fa fa-arrow-left"></i> Kembali ke daftar
        </a>
        <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
          <i class="fa fa-close"></i> Reset
        </button>
        <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
          <i class="fa fa-check-square-o"></i> Submit
        </button>
      </div>

    </form>
  </div>
</div>
