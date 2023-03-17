<html>
<head>
<title>Laporan Umum</title>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script type="text/javascript">
  window.jQuery || document.write("<script src='<?php echo base_url()?>/assets/js/jquery.js'>"+"<"+"/script>");
</script>
<script type="text/javascript" src="<?php echo base_url()?>assets/jSignature/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/jSignature/jquery-ui.min.js"></script>

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
        <h4>Pencarian Data Keterlambatan Praktek Dokter</h4>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data_keterlambatan?flag=kunjungan_mod_5" target="blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="kunjungan_mod_5">
          <input type="hidden" name="title" value="Data Keterlambatan Praktek Dokter">

          <div class="form-group">
              <label class="control-label col-md-2">*Klinik</label>
              <div class="col-md-4">
                  <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100)), '' , 'kode_bagian', 'kode_bagian', 'form-control', '', '') ?>
              </div>
          </div>
          <div class="form-group">
              <label class="control-label col-md-2">*Dokter</label>
              <div class="col-md-4" id="dokter_by_klinik">
                  <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array() ), '' , 'kode_dokter', 'kode_dokter', 'form-control', '', '') ?>
              </div>
          </div>

          <div class="form-group">
              <label class="control-label col-md-2">Tanggal Kunjungan</label>
              <div class="col-md-2">
                <div class="input-group">
                    <input name="from_tgl" id="from_tgl" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>">
                    <span class="input-group-addon">
                      <i class="ace-icon fa fa-calendar"></i>
                    </span>
                </div>
              </div>
              <label class="control-label col-md-1">s/d Tanggal</label>
              <div class="col-md-2">
                <div class="input-group">
                    <input name="to_tgl" id="to_tgl" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>">
                    <span class="input-group-addon">
                      <i class="ace-icon fa fa-calendar"></i>
                    </span>
                </div>
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
<script type="text/javascript">
  jQuery(function($) {  

    $('.date-picker').datepicker({    
      autoclose: true,   
      todayHighlight: true,
      dateFormat: 'yy-mm-dd'   
    })  
    //show datepicker when clicking on the icon
    .next().on(ace.click_event, function(){    
      $(this).prev().focus();    
    });  

  });

  $('select[name="kode_bagian"]').change(function () {  
    $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val(), '', function (data) {   
        $('#kode_dokter option').remove();         
        $('<option value="">-Pilih Dokter-</option>').appendTo($('#kode_dokter'));  
        $.each(data, function (i, o) {   
            $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#kode_dokter'));  
        });   
    }); 
  }); 
</script>
</html>






