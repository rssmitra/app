<html>
<head>
  <title>Laporan Umum</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
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
  
    $('#form-default').ajaxForm({
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
          $('#page-area-content').load('laporan/Global_report'.val());
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    }); 
})

$('select[name="pelayanan"]').change(function () {  
    /*flag string*/
    // flag_string = $('#flag_string').val();
    if ( $(this).val() ) {     
      
        $.getJSON("<?php echo site_url('Templates/References/getSubBagian') ?>/" + $(this).val() , '', function (data) {   
            $('#kode_bagian option').remove();         
            $('<option value="">-Pilih Bagian -</option>').appendTo($('#kode_bagian'));  
            $.each(data, function (i, o) {   
                $('<option value="' + o.kode_bagian + '">' + o.nama_bagian.toUpperCase() + '</option>').appendTo($('#kode_bagian'));  
            });   
        });   
    } else {    
        $('#kode_bagian option').remove();
    }    
}); 


</script>
</head>
<body>
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

      <div class="col-md-12">

        <!-- content -->
        <a href="<?php echo base_url().'laporan/Global_report'?>" class="btn btn-xs btn-primary"> Kembali ke Menu Utama</a>
        <br>
        <h4>Rekapitulasi Hutang Obat Pasien</h4>
        <form class="form-horizontal" method="post" id="form-default" action="<?php echo base_url()?>laporan/Global_report/show_data">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Rekapitulasi Hutang Obat Pasien">

          <div class="form-group">
            <label class="control-label col-md-1">Dari Bulan </label>
            <div class="col-md-1">
              <?php echo $this->master->get_bulan('','from_month','from_month','form-control','','');?>
            </div>
            <label class="control-label col-md-1">s.d Bulan </label>
            <div class="col-md-1">
              <?php echo $this->master->get_bulan('','to_month','to_month','form-control','','');?>
            </div>
            <label class="control-label col-md-1">Tahun</label>
            <div class="col-md-1">
              <?php echo $this->master->get_tahun('','year','year','form-control','','');?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2 ">&nbsp;</label>
            <div class="col-md-10" style="margin-left: 5px">
              <button type="submit" name="submit" value="data" class="btn btn-xs btn-default">
                Proses Pencarian
              </button>
              <button type="submit" name="submit" value="excel" class="btn btn-xs btn-success">
                Export Excel
              </button>
            </div>
          </div>

        </form>
        <!-- end content -->
        
     </div>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






