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
});

$(document).ready(function(){
    
    $('#form_Lap_hasil_so').ajaxForm({
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
          $('#page-area-content').load('inventory/so/Lap_hasil_so?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

})

</script>

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
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
              <form class="form-horizontal" method="post" id="form_Lap_hasil_so" action="<?php echo site_url('inventory/so/Lap_hasil_so/process')?>" enctype="multipart/form-data" >
                <br>

                <a onclick="getMenu('inventory/so/Lap_hasil_so')" href="#" class="btn btn-sm btn-success">
                  <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                  Kembali ke daftar
                </a>
                <hr class="separator">

                <p><b>AGENDA KEGIATAN STOK OPNAME</b></p>
                <div class="form-group">
                  <label class="control-label col-md-2">Agenda SO</label>
                  <div class="col-md-3" style="margin-top:5px; margin-left:5px">
                    <?php echo isset($value->agenda_so_name)?$value->agenda_so_name:''?>
                  </div>
                  <label class="control-label col-md-2">Tanggal Pelaksanaan</label>
                  <div class="col-md-3" style="margin-top:5px; margin-left:5px">
                    <?php echo isset($value->agenda_so_date)?$this->tanggal->formatDate($value->agenda_so_date):''?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Penanggung Jawab</label>
                  <div class="col-md-4" style="margin-top:5px; margin-left:5px">
                    <?php echo isset($value->agenda_so_spv)?$value->agenda_so_spv:''?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Keterangan</label>
                  <div class="col-md-10" style="margin-top:5px; margin-left:5px">
                    <?php echo isset($value->agenda_so_desc)?$value->agenda_so_desc:''?>
                  </div>
                </div>
                <hr class="separator">

                <div class="tabbable">  

              <ul class="nav nav-tabs" id="myTab">

                <li>
                  <a data-toggle="tab" id="tabs_medis" href="#" data-id="<?php echo $value->agenda_so_id?>/medis" data-url="inventory/so/Lap_hasil_so/view_data_bag_so" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_so')">
                    <i class="green ace-icon fa fa-history bigger-120"></i>
                    MEDIS
                  </a>
                </li>

                <li>
                  <a data-toggle="tab" data-id="<?php echo $value->agenda_so_id?>/non_medis" data-url="inventory/so/Lap_hasil_so/view_data_bag_so" id="tabs_non_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_so')" >
                    <i class="red ace-icon fa fa-list bigger-120"></i>
                    NON MEDIS
                  </a>
                </li>

              </ul>

              <div class="tab-content">

                <div id="tabs_so">
                  <div class="alert alert-block alert-success">
                      <p>
                        <strong>
                          <i class="ace-icon fa fa-check"></i>
                          Selamat Datang!
                        </strong> 
                        Klik tab untuk melihat data hasil Stok Opname (SO).
                      </p>
                    </div>
                </div>

              </div>

            </div>
              </form>
            </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


