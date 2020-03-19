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
          $.achtung({message: jsonResponse.message, timeout:5});
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
        <h4>Laporan Penjualan Obat Per Kategori</h4>
        <form class="form-horizontal" method="post" id="form-default" action="<?php echo base_url()?>laporan/Global_report/show_data_obat_kategori">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Laporan Penjualan Obat Per Kategori">

                                           
        
           <div class="form-group">
            <label class="control-label col-md-1">Obat / Alkes</label>
              <div class="col-md-2">
               <select name="obat_alkes" class="form-control">
                 <option value="D"> Obat </option>
                 <option value="E"> Alkes </option>
               </select>
              </div>
              
          </div>
           <div class="form-group">
            <label class="control-label col-md-1">Kategori</label>
              <div class="col-md-2">
               <select name="kode_profit" class="form-control">
                 <option value="1000"> Rawat Inap </option>
                 <option value="2000"> Rawat Jalan </option>
                 <option value="3000"> Resep Luar </option>
                 <option value="4000"> Pembelian Bebas </option>
                 <option value="666"> Karyawan RS </option>
               </select>
              </div>
              
          </div>
          

           <div class="form-group">
            <label class="control-label col-md-1">Tanggal</label>
              <div class="col-md-2">
                <input class="form-control date-picker" name="tgl1" type="text" placeholder="Format : YYYY-MM-DD" value=""/>
              </div> 
              <label class="control-label col-md-2">s/d Tanggal</label>
              <div class="col-md-2">
                <input class="form-control date-picker" name="tgl2" type="text" placeholder="Format : YYYY-MM-DD" value=""/>
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






