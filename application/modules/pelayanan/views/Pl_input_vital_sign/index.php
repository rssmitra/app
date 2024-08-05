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

  oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      "ajax": {
          "url": "pelayanan/Pl_input_vital_sign/get_data",
          "type": "POST"
      },
      "columnDefs": [
          { 
              "targets": [ -1 ], //last column
              "orderable": false, //set not orderable
          },
          {"aTargets" : [0], "mData" : 1, "sClass":  "details-control"}, 
          { "visible": false, "targets": [1,2,3] },
          ],

  });

  $('#dynamic-table tbody').on('click', 'td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = oTable.row( tr );
      var data = oTable.row( $(this).parents('tr') ).data();
      var no_kunjungan = data[ 2 ];
      var no_registrasi = data[ 3 ];
      

      if ( row.child.isShown() ) {
          // This row is already open - close it
          row.child.hide();
          tr.removeClass('shown');
      }
      else {
          /*data*/
          
          $.getJSON("pelayanan/Pl_pelayanan/view_detail_resume_medis/" + no_registrasi+"/"+no_kunjungan , '', function (data) {
              response_data = data;
              // Open this row
              row.child( format( response_data ) ).show();
              tr.addClass('shown');
          });
          
      }
  });

  $('#btn_search_data').click(function (e) {
      e.preventDefault();
      $.ajax({
          url: 'tarif/Mst_tarif/find_data',
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          beforeSend: function() {
          achtungShowLoader();  
          },
          success: function(data) {
          achtungHideLoader();
          find_data_reload(data,'tarif/Mst_tarif');
          }
      });
  });

  $('#btn_reset_data').click(function (e) {
      e.preventDefault();
      find_data_reload();
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

})

function format ( data ) {
  return data.html;
}

function find_data_reload(result=''){
    oTable.ajax.url('pelayanan/Pl_input_vital_sign/get_data?'+result.data).load();
}

function reload_data(){
    oTable.ajax.url('pelayanan/Pl_input_vital_sign/get_data').load();
}

function save_vital_sign(type, no_kunjungan, no_registrasi){
  preventDefault();  
  var formData = {
      no_registrasi : no_registrasi,
      no_kunjungan : no_kunjungan,
      type : type,
      value : $('#'+type+'_'+no_kunjungan+'').val(),
  };
  $.ajax({
      url: "pelayanan/Pl_input_vital_sign/process",
      data: formData,            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
          var data=xhr.responseText;  
          var jsonResponse = JSON.parse(data);  
          if(jsonResponse.status === 200){  
            // sukses
            $.achtung({message: jsonResponse.message, timeout:5});  
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

    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_input_vital_sign/find_data">

      <div class="form-group">
          <label class="control-label col-md-1">Pencarian</label>
          <div class="col-md-2">
            <select name="search_by" class="form-control" style="width: 130px !important">
              <option value="">-Silahkan Pilih-</option>
              <option value="tc_kunjungan.no_mr" selected>No MR</option>
              <option value="pl_tc_poli.nama_pasien">Nama Pasien</option>
            </select>
          </div>
          <div class="col-md-2" style="margin-left: -2.7%">
            <input type="text" class="form-control" name="keyword" id="keyword_form" placeholder="Masukan keyword">
          </div>
          <label class="control-label col-md-1">Tgl Masuk</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
          <div class="col-md-2" style="margin-left:-1.5%">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-primary" action="pelayanan/Pl_input_vital_sign/find_data">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Search
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
              <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
              Reset
            </a>
          </div>
      </div>

      <hr class="separator">
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="pelayanan/Pl_input_vital_sign/get_data" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="40px" class="center"></th>
            <th></th>
            <th></th>
            <th></th>
            <th>No</th>
            <th>No MR</th>
            <th>Nama Pasien</th>
            <th>Penjamin</th>
            <th width="150px">Tanggal Kunjungan</th>
            <th style="width: 100px" class="center">Tinggi Badan (Cm)</th>
            <th style="width: 100px" class="center">Berat Badan (Kg)</th>
            <th style="width: 100px" class="center">Tekanan Darah (mmHg)</th>
            <th style="width: 100px" class="center">Nadi (bpm)</th>
            <th style="width: 100px" class="center">Suhu Tubuh (C&deg;)</th>
            <!-- <th>Status</th>           -->
          </tr>
        </thead>
        <tbody>
        </tbody>
        </table>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->




