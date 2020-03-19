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

function rollback(myid){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan_bedah/rollback",
      data: { ID: myid },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          reload_table();
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5});  
        } 
        achtungHideLoader();
      }
  });

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

    <form class="form-horizontal" method="post" id="form_search" action="registration/Riwayat_kunjungan_poli/find_data">

    <div class="col-md-12">

      <center><h4>FORM PENCARIAN DATA RIWAYAT PASIEN BEDAH<br><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data Bulan <?php echo $this->tanggal->getBulan(date('m'))?> Tahun <?php echo date('Y')?> </small></h4></center>
      <br>
      <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
              <select name="search_by" class="form-control" id="search_by">
                <option value="">- Silahkan Pilih -</option>
                <option value="ok_riwayat_pasien_bedah_v.no_mr" selected>No MR</option>
                <option value="ok_riwayat_pasien_bedah_v.nama_pasien">Nama Pasien</option>
              </select>
          </div>
          <label class="control-label col-md-1">Keyword</label>
          <div class="col-md-2">
              <input type="text" class="form-control" name="keyword">
          </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-2">Bulan</label>
          <div class="col-md-2">
            <?php echo $this->master->get_bulan('' , 'bulan', 'bulan', 'form-control', '','') ?>
          </div>
          <label class="control-label col-md-1">Tahun</label>
          <div class="col-md-2">
            <?php echo $this->master->get_tahun('' , 'tahun', 'tahun', 'form-control', '', '') ?>
          </div>
          <label class="control-label col-md-1">Status</label>
          <div class="col-md-2">
              <select name="status_ranap" class="form-control" id="status_ranap">
                <option value="" selected>- Silahkan Pilih -</option>
                <option value="masih dirawat">Masih dirawat</option>
                <option value="sudah pulang">Sudah Pulang</option>
                <!-- <option value="belum lunas">Sudah Lunas</option> -->
              </select>
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Masuk</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2 ">&nbsp;</label>
        <div class="col-md-10">
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
      </div>

    </div>

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="registration/Riwayat_pasien_bedah/get_data" url-detail="registration/Riwayat_pasien_bedah/show_detail" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center"></th>
          <th width="40px" class="center"></th>
          <th width="40px" class="center"></th>
          <th width="40px"></th>
          <th>No.MR</th>
          <th>Data Pasien</th>
          <th>Penjamin</th>
          <th>Tanggal/Jam</th>
          <th>Dokter</th>
          <th>Tindakan</th>
          <th>Kamar</th>
          <th>Status</th>          
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable_with_detail.js'?>"></script>



