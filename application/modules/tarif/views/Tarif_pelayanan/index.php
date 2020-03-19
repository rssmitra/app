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


$('#btn_update_session_poli').click(function (e) {  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan/destroy_session_kode_bagian",
      data: { kode: $('#sess_kode_bagian').val()},            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          getMenu('pelayanan/Pl_pelayanan');
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5});  
        } 
        achtungHideLoader();
      }
  });

});

function cancel_visit(no_registrasi, no_kunjungan){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan/cancel_visit",
      data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan, kode_bag: $('#sess_kode_bagian').val() },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          getMenu('pelayanan/Pl_pelayanan');
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

    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan/find_data">

    <div class="col-md-12">

      <center><h4><?php echo ($this->session->userdata('kode_bagian'))?strtoupper($this->session->userdata('nama_bagian')):''?> <br> <small style="font-size:12px"><b>( <?php echo ($this->session->userdata('sess_kode_dokter'))?strtoupper($this->session->userdata('sess_nama_dokter')):''?> )</b> </small><br><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data per Hari ini yaitu tanggal <?php echo $this->tanggal->formatDate(date('Y-m-d'))?> </small></h4></center>
      <br>

      <!-- hidden form -->
      <input type="hidden" name="sess_kode_bagian" value="<?php echo ($this->session->userdata('kode_bagian'))?$this->session->userdata('kode_bagian'):''?>" id="sess_kode_bagian">
      <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
            <select name="search_by" class="form-control">
              <option value="">-Silahkan Pilih-</option>
              <option value="tc_kunjungan.no_mr" selected>No MR</option>
              <option value="pl_tc_poli.nama_pasien">Nama Pasien</option>
            </select>
          </div>

          <label class="control-label col-md-1">Keyword</label>
          <div class="col-md-2">
            <input type="text" class="form-control" name="keyword" id="keyword_form">
          </div>

      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Registrasi</label>
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
        <div class="col-md-10" style="margin-left:6px">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
          <a href="#" id="btn_update_session_poli" class="btn btn-xs btn-success">
            <i class="ace-icon fa fa-bolt icon-on-right bigger-110"></i>
            Ganti Session Poli
          </a>
          <!-- <a href="#" id="btn_batalkan_kunjungan" class="btn btn-xs btn-danger">
            <i class="ace-icon fa fa-times-circle icon-on-right bigger-110"></i>
            Batalkan Kunjungan
          </a> -->
        </div>
      </div>

    </div>

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="pelayanan/Pl_pelayanan" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center"></th>
          <th></th>
          <th>Kode</th>
          <th>No MR</th>
          <th>Nama Pasien</th>
          <th>Penjamin</th>
          <th>Tanggal Masuk</th>
          <th>Dokter</th>
          <th>Antrian ke-</th>
          <th>Petugas</th>
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

<script src="<?php echo base_url().'assets/js/custom/als_datatable_no_style.js'?>"></script>



