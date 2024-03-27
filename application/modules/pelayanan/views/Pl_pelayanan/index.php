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

oTable = $('#dynamic-table').DataTable({ 
          
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "ordering": false,
          "searching": false,
          "bLengthChange": false,
          // "pageLength": 25,
          "bInfo": false,
          "paging": false,
          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": $('#dynamic-table').attr('base-url'),
              "type": "POST"
          },
      });
      

$( ".form-control" )    
    .keypress(function(event) {  
      var keycode =(event.keyCode?event.keyCode:event.which);  
      if(keycode ==13){   
        event.preventDefault();  
        $('#btn_search_data').click();  
        return false;  
      }  
});

$('#btn_search_data').click(function (e) {
    e.preventDefault();
    $.ajax({
    url:  $('#form_search').attr('action'),
    type: "post",
    data: $('#form_search').serialize(),
    dataType: "json",
    beforeSend: function() {
      achtungShowLoader();  
    },
    success: function(data) {
      achtungHideLoader();
      find_data_reload(data);
    }
  });
});

function find_data_reload(result){

  oTable.ajax.url( $('#dynamic-table').attr('base-url')+'&'+result.data).load();
  $("html, body").animate({ scrollTop: "400px" });

}

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
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
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
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });

}

function rollback(no_registrasi, no_kunjungan, flag){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan/rollback",
      data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan, kode_bag: $('#kode_bagian_val').val(), flag: flag },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          reload_table();
          //getMenu('pelayanan/Pl_pelayanan');
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
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

    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan/find_data" autocomplete="off">

    <div class="col-md-12">

      <center><h4><?php echo strtoupper($nama_bagian); ?> <br> <small style="font-size:12px"><b><?php echo isset($nama_dokter)?'('.strtoupper($nama_dokter).')<br>':''?></b> </small><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data per Hari ini yaitu tanggal <?php echo $this->tanggal->formatDate(date('Y-m-d'))?> </small></h4></center>
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

          <label class="control-label col-md-1">s/d</label>
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
        <label class="col-md-2 ">&nbsp;</label>
        <div class="col-md-10" style="margin-left:6px">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-primary" action="pelayanan/Pl_pelayanan/find_data">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
          <?php if(!isset($_GET['bag'])) :?>
            <a href="#" id="btn_update_session_poli" class="btn btn-xs btn-success">
              <i class="ace-icon fa fa-bolt icon-on-right bigger-110"></i>
              Ganti Session Poli
            </a>
          <?php endif;?>
        </div>
      </div>

    </div>

    <hr class="separator">
    <br>
    <!-- div.dataTables_borderWrap -->
    <div>
      <table id="dynamic-table" base-url="pelayanan/Pl_pelayanan/get_data?bag=<?php echo isset($kode_bagian)?$kode_bagian:''?>" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center">No</th>
          <th class="center">#</th>
          <th>Kode</th>
          <th>No MR</th>
          <th>Nama Pasien</th>
          <th>Penjamin</th>
          <th>Tanggal Kunjungan</th>
          <!-- <th>Dokter</th> -->
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




