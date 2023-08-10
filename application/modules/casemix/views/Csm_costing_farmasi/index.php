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
  
    $('#btn_proses_dokumen').click(function (e) {
        e.preventDefault();
        $.ajax({
          url: 'casemix/Csm_costing_farmasi/run_scheduler',
          type: "post",
          data: $('#form_csm_costing_farmasi').serialize(),
          dataType: "json",
          beforeSend: function() {
            $('#response_from_cli').html("Sedang diproses, mohon menunggu dan jangan membuka aplikasi lain sampai proses ini selesai..");
          },
          complete: function(xhr) {     
            var response=xhr.responseText;
            // var jsonResponse = JSON.parse(data);
            var responsetext = response.replace(/[\n\r]/g,'<br>');
            $('#response_from_cli').html(responsetext);
          }
        });
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
          <form class="form-horizontal" method="post" id="form_csm_costing_farmasi" action="<?php echo site_url('casemix/Csm_costing_farmasi/run_scheduler')?>" enctype="multipart/form-data">
            <br>
            <div class="form-group">
                <label class="control-label col-md-2">Tanggal Transaksi</label>
                <div class="col-md-2">
                  <div class="input-group">
                    <input class="form-control date-picker" name="date" id="date" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($value)?$value->csm_klaim_sampai_tgl:''?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>
                <a href="#" id="btn_proses_dokumen" name="view_data" class="btn btn-xs btn-primary">
                Proses Dokumen
                <i class="ace-icon fa fa-play icon-on-right bigger-110"></i>
              </a>
            </div>
            
          </form>

          <div id="response_from_cli" style="background: black; font-weight: bold; color: green"></div>
        </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


