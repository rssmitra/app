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
  .frm-section-title { font-size: 12px; font-weight: 700; color: #1a4f8a; margin: 10px 0 6px; padding-bottom: 4px; border-bottom: 1px solid #d0dce8; }
</style>

<div class="frm-card">
  <div class="frm-card-hdr">
    <i class="fa fa-file-text-o"></i> <?php echo $title?>
    <small><i class="fa fa-angle-double-right"></i> <?php echo $breadcrumbs?></small>
  </div>
  <div class="frm-card-body">
    <form class="form-horizontal" method="post" id="form_permintaan" action="<?php echo site_url('purchasing/permintaan/Req_pembelian/process')?>" enctype="multipart/form-data">

      <input type="hidden" name="flag" id="flag_string" value="<?php echo $string?>">

      <div class="form-group">
        <label class="control-label col-md-2">ID</label>
        <div class="col-md-1">
          <input name="id" id="id" value="<?php echo isset($value)?$value->id_tc_permohonan:''?>" placeholder="Auto" class="form-control" type="text" readonly>
        </div>
        <label class="control-label col-md-2">Kode Permohonan</label>
        <div class="col-md-2">
          <input name="kode_permohonan" id="kode_permohonan" value="<?php echo isset($value)?$value->kode_permohonan:''?>" placeholder="Auto" class="form-control" type="text" readonly>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Permohonan</label>
        <div class="col-md-2">
          <div class="input-group">
            <input class="form-control date-picker" name="tgl_permohonan" id="tgl_permohonan" type="text" data-date-format="yyyy-mm-dd" <?php echo ($flag=='read')?'readonly':''?> value="<?php echo isset($value)?$value->tgl_permohonan:''?>"/>
            <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
          </div>
        </div>
        <label class="control-label col-md-2">Jenis Permohonan</label>
        <div class="col-md-2">
          <div class="radio">
            <!-- radio buttons removed from original -->
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Keterangan</label>
        <div class="col-md-5">
          <input type="text" name="ket_acc" class="form-control" <?php echo ($flag=='read')?'readonly':''?> value="<?php echo isset($value)?$value->keterangan:''?>">
        </div>
      </div>

      <div class="frm-section-title"><i class="fa fa-search"></i> Rincian Barang</div>

      <div class="form-group">
        <label class="control-label col-md-1">Pencarian</label>
        <div class="col-md-2">
          <select name="search_by" id="search_by" class="form-control">
            <option value="">-Silahkan Pilih-</option>
            <option value="nama_brg" selected>Nama Barang</option>
            <option value="kode_brg">Kode Barang</option>
          </select>
        </div>
        <label class="control-label col-md-1">Kata Kunci</label>
        <div class="col-md-3">
          <input id="inputKeyWord" class="form-control" name="kata_kunci" type="text" placeholder="Masukan keyword minimal 3 karakter" />
        </div>
        <div class="col-md-2">
          <a href="#" id="btn_search_brg" class="btn btn-sm btn-primary">
            <i class="fa fa-search"></i> Cari Barang
          </a>
        </div>
      </div>

      <div id="show_detail_selected_brg"></div>

      <div class="frm-actions">
        <a onclick="getMenu('purchasing/permintaan/Req_pembelian?flag=<?php echo $string?>')" href="#" class="btn btn-sm btn-success">
          <i class="fa fa-arrow-left"></i> Kembali ke daftar
        </a>
        <?php if($flag != 'read'):?>
        <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
          <i class="fa fa-close"></i> Reset
        </button>
        <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
          <i class="fa fa-check-square-o"></i> Submit
        </button>
        <?php endif; ?>
      </div>

    </form>
  </div>
</div>
