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

  $( "#form_search" )    
      .keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);  
        if(keycode ==13){   
          event.preventDefault();  
          $('#btn_search_data').click();  
          return false;  
        }  
  });

  oTable = $('#dynamic-table').DataTable({ 
          
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": true,
    "bPaginate": true,
    "bInfo": true,
    "pageLength": 25,
     "ajax": {
        "url": "reference/tabel/dokter/get_data",
        "type": "POST"
    },
    "columnDefs": [
        { 
          "targets": [ -1 ], //last column
          "orderable": false, //set not orderable
        },
        {"aTargets" : [0], "mData" : 1, "sClass":  "details-control"}, 
        { "visible": false, "targets": [1,2] },
      ],

  });

  $('#dynamic-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTable.row( tr );
        var data = oTable.row( $(this).parents('tr') ).data();
        var kode_tarif = data[ 2 ];
        

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/
            
            $.getJSON("reference/tabel/dokter/getDetail/" + kode_tarif , '', function (data) {
                response_data = data;
                  // Open this row
                row.child( format( response_data ) ).show();
                tr.addClass('shown');
            });
            
        }
    } );

    
    $('#btn_search_data').click(function (e) {
        e.preventDefault();
        $.ajax({
        url: 'reference/tabel/dokter/find_data',
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        success: function(data) {
          achtungHideLoader();
          find_data_reload(data,'reference/tabel/dokter');
        }
      });
    });

    $('#btn_reset_data').click(function (e) {
    e.preventDefault();

    // reset form
    $('#form_search')[0].reset();

    // reload datatable TANPA parameter
    oTable.ajax.url('reference/tabel/dokter/get_data').load();
});

})

function format ( data ) {
    return data.html;
}

function find_data_reload(result=''){

  oTable.ajax.url('reference/tabel/dokter/get_data?'+result.data).load();

}

function reload_data(){

  oTable.ajax.url('reference/tabel/dokter/get_data').load();

}

function delete_data(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'reference/tabel/dokter/delete',
        type: "post",
        data: {ID:myid},
        dataType: "json",
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
            reload_table();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }
  
}

function delete_tarif_klas(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'reference/tabel/dokter/delete_tarif_klas',
        type: "post",
        data: {ID:myid},
        dataType: "json",
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
            reload_table();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }
  
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
    
    <form class="form-horizontal" method="post" id="form_search" action="reference/tabel/dokter/find_data">
	  
	<div class="col-md-12">
	  <div class="form-group">
  <label class="control-label col-md-2">Masa Berlaku SIP</label>
  <div class="col-md-2">
    <div class="input-group">
      <input type="text"
             name="masa_berlaku_sip"
             id="masa_berlaku_sip"
             class="form-control date-picker"
             data-date-format="dd-mm-yyyy"
             placeholder="dd-mm-yyyy">
      <span class="input-group-addon">
        <i class="fa fa-calendar bigger-110"></i>
      </span>
    </div>
  </div>

<br><br>

  <label class="control-label col-md-2">Status Dokter</label>
  <div class="col-md-3">
    <select name="is_active" id="is_active" class="form-control">
      <option value="">-- Semua --</option>
      <option value="Y">Aktif</option>
      <option value="N">Tidak Aktif</option>
    </select>
  </div>
</div>

<div class="form-group">
  <div class="col-md-offset-2 col-md-6">
    <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
      <i class="ace-icon fa fa-search"></i> Search
    </a>
    <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
      <i class="ace-icon fa fa-refresh"></i> Reset
    </a>
  </div>
</div>
</div>
<br>
      <div class="clearfix" style="margin-bottom:-5px">
        <?php echo $this->authuser->show_button('reference/tabel/dokter','C','',1)?>
      </div>
	  
	  <br>
      
      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="reference/tabel/dokter" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th width="50px">&nbsp;</th>
              <th width="50px">&nbsp;</th>
              <th></th>
              <th>Kode</th>         
              <th>Nama Dokter</th>         
              <th>No. SIP</th>         
              <th>Masa Berlaku SIP</th>         
              <th>Spesialis</th>         
              <th>TTD</th>         
              <th>Stamp</th>         
              <th>Status</th>         
              <th>Action</th>         
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<!-- <script src="<?php //echo base_url().'assets/js/custom/als_datatable.js'?>"></script> -->



