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
    "searching": false,
    "bPaginate": true,
    "bInfo": false,
    "pageLength": 100,
     "ajax": {
        "url": "tarif/Mst_tarif/get_data",
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
            
            $.getJSON("tarif/Mst_tarif/getDetail/" + kode_tarif , '', function (data) {
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


})

function format ( data ) {
    return data.html;
}

function find_data_reload(result=''){

  oTable.ajax.url('tarif/Mst_tarif/get_data?'+result.data).load();

}

function reload_data(){

  oTable.ajax.url('tarif/Mst_tarif/get_data').load();

}

function delete_data(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'tarif/Mst_tarif/delete',
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
        url: 'tarif/Mst_tarif/delete_tarif_klas',
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
    
    <form class="form-horizontal" method="post" id="form_search" action="tarif/Mst_tarif/find_data">

      <center>
          <h4>FORM PENCARIAN MASTER TARIF<br><small style="font-size:12px">Data yang ditampilkan adalah data master tarif terbaru  </small></h4>
      </center>
      <br>
      <div class="form-group">
        <label class="control-label col-md-2">Unit/Bagian</label>
        <div class="col-md-4">
          <?php echo $this->master->custom_selection(array('table'=>'view_unit_tarif', 'where'=>array(), 'id'=>'kode_bagian', 'name' => 'nama_bagian'),'','unit','unit','chosen-slect form-control','','');?>
        </div>
      </div>

      <div class="form-group">
          <div class="control-label col-md-2">
            <div class="checkbox" style="margin-top: -5px">
              <label>
                <input name="checked_nama_tarif" id="checked_nama_tarif" type="checkbox" class="ace" value="1">
                <span class="lbl"> Nama Tarif</span>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="margin-left: -15px">
              <input type="text" value="" name="nama_tarif" id="nama_tarif" class="form-control">
          </div>

          <div class="control-label col-md-2">
            <div class="checkbox" style="margin-top: -5px">
              <label>
                <input name="checked_jenis_tindakan" value="1" type="checkbox" class="ace">
                <span class="lbl"> Jenis Tindakan</span>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="margin-left: -15px">
              <?php echo $this->master->custom_selection(array('table'=>'mt_jenis_tindakan', 'where'=>array(), 'id'=>'kode_jenis_tindakan', 'name' => 'jenis_tindakan'),'','jenis_tindakan','jenis_tindakan','chosen-slect form-control','','');?>
          </div>
          <div class="col-md-2" style="margin-left: -15px">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Tampilkan Data
            </a>
          </div>
      </div>
      <hr class="separator">
      <div class="clearfix" style="margin-bottom:-5px">
        <?php echo $this->authuser->show_button('tarif/Mst_tarif','C','',1)?>
      </div>
      
      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="tarif/Mst_tarif" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th width="50px">&nbsp;</th>
              <th width="50px">&nbsp;</th>
              <th></th>
              <th>Nama Tarif</th>         
              <th>Jenis Tindakan</th>         
              <th>Unit/Bagian</th>         
              <th>Revisi ke-</th>         
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



