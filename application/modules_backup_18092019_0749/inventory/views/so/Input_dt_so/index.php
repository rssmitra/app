<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
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
});

$(document).ready(function(){
  
    $('#form_input_dt_so_header').ajaxForm({
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
          $('#page-area-content').load( jsonResponse.redirect_page );
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

    $('#InputKeyNamaKaryawan').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "templates/references/getNamaKaryawan",
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
            console.log(val_item);
            $('#kode_petugas').val(val_item);
          }
    });

})

</script>

<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <form class="form-horizontal" method="post" id="form_input_dt_so_header" action="<?php echo site_url('inventory/so/Input_dt_so/process')?>" enctype="multipart/form-data" >
      <br>

      <div class="form-group">
        <label class="control-label col-md-2">Agenda SO</label>
        <div class="col-md-4">
          <?php echo $this->master->custom_selection($params = array('table' => 'tc_stok_opname_agenda', 'id' => 'agenda_so_id', 'name' => 'agenda_so_name', 'where' => array('is_active' => 'Y') ), '#' , 'agenda_so_id', 'agenda_so_id', 'form-control', '', '') ?>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Input Data</label>
        <div class="col-md-2">
          <div class="input-group">
            <input class="form-control date-picker" name="tanggal_input" id="tanggal_input" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
            <span class="input-group-addon">
              <i class="fa fa-calendar bigger-110"></i>
            </span>
          </div>
        </div>
        <label class="control-label col-md-1">Waktu/Jam</label>
        <div class="col-md-2">
          <input name="waktu_input" id="waktu_input" value="<?php echo date('H:i')?>" placeholder="contoh : 09.00" class="form-control" type="text" >
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Bagian Unit</label>
          <div class="col-md-4">
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1)), '' , 'bagian', 'bagian', 'form-control', '', '') ?>
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Petugas Input Data</label>
          <div class="col-md-4">
            <input id="InputKeyNamaKaryawan" class="form-control" name="petugas_input" type="text" placeholder="Masukan keyword minimal 3 karakter" />
            <input type="hidden" name="kode_petugas" value="" id="kode_petugas">
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">&nbsp;</label>
          <div class="col-md-4">
            <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
              <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
              Simpan Session
            </button>
          </div>
      </div>

    </form>


  </div><!-- /.col -->
</div><!-- /.row -->




