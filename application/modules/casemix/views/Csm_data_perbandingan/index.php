<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

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

  oTable = $('#table-data-perbandingan').DataTable({ 
        
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bLengthChange": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#table-data-perbandingan').attr('base-url')+'?month='+$('#month').val()+'&year='+$('#year').val()+'',
          "type": "POST"
      },

  });

  $('#btn_search_data').click(function (e) {
      var url_search = $('#form_search').attr('action');
      e.preventDefault();
      $.ajax({
        url: url_search,
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        success: function(data) {
          console.log(data.data);
          find_data_reload(data);
        }
      });
  });

})

function find_data_reload(result){
    
    oTable.ajax.url($('#table-data-perbandingan').attr('base-url')+'?'+result.data).load();
    $("html, body").animate({ scrollTop: "400px" });

}

function form_analyze(){
  getMenu('casemix/Csm_data_perbandingan/analyze_data?month='+$('#month').val()+'&year='+$('#year').val()+'');
}

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

    <form class="form-horizontal" method="post" id="form_search" action="casemix/Csm_data_perbandingan/find_data">

    <div class="form-group">
        <label class="control-label col-md-1">Bulan</label>
        <div class="col-md-2">
          <select name="month" id="month" class="form-control">
            <option value="">-Silahkan Pilih-</option>
            <?php
              for($month=1;$month<13;$month++){
                $selected = ($month==date('m'))?'selected':'';
                echo '<option value="'.$month.'" '.$selected.'>'.$this->tanggal->getBulan($month).'</option>';    
              }
            ?>
            
          </select>
        </div>
        <div class="col-md-2" style="margin-left: -1.8%">
          <select name="year" id="year" class="form-control">
            <option value="">-Silahkan Pilih-</option>
             <?php
                for($year=date('Y')-4;$year<=date('Y');$year++){
                   $selected = ($year==date('Y'))?'selected':'';
                  echo '<option value="'.$year.'" '.$selected.'>'.$year.'</option>';    
                }
              ?>
          </select>
        </div>
        <div class="col-md-3" style="margin-left: -1%">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
          <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
            <i class="fa fa-file-word-o bigger-110"></i>
            Export Excel
          </a>
        </div>
        <div class="col-md-1 pull-right" style="margin-right: 13px">
            <a href="#" id="btn_upload_file" onclick="form_analyze()" class="btn btn-xs btn-primary">
              <i class="fa fa-filter bigger-110"></i>
              Data Analisis
            </a>
        </div>
    </div>

    <hr class="separator">

    <div style="margin-top:-27px">
      <table id="table-data-perbandingan" base-url="casemix/Csm_data_perbandingan/get_data" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th class="center" width="50px">No</th>
            <th width="100px">No. Registrasi</th>
            <th>No MR</th>
            <th>Nama Pasien</th>
            <th>Tanggal</th>
            <th>No SEP <br> (SIRS)</th>
            <th>No SEP <br> (Hasil Verif)</th>
            <th>No SEP <br> (Costing)</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>

    </div>
    </form>
  </div><!-- /.col -->
</div><!-- /.row -->





