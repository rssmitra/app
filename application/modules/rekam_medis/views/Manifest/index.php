<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>

var oTable;
var base_url = $('#manifest_table').attr('base-url'); 
var params = $('#manifest_table').attr('data-id'); 

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
  
    //initiate dataTables plugin
    oTable = $('#manifest_table').DataTable({ 
          
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "ordering": false,
          "searching": false,
          "bInfo" : false ,
          "bLengthChange" : false,
          "pageLength": 25,
          "scrollY": "600px",
          "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": base_url+'/get_data?'+params,
              "type": "POST"
          },
          "columnDefs": [
              { 
                "targets": [ 0 ], //last column
                "orderable": false, //set not orderable
              },
              { "aTargets" : [ 1 ], "mData" : 1, "sClass":  "details-control"}, 
              { "visible": true, "targets": [ 1 ] },
              { "targets": [ 2 ], "visible": false },
              { "targets": [ 3 ], "visible": false },
              { "targets": [ 4 ], "visible": false },
          ],
    
        });
    
        $('#manifest_table tbody').on('click', 'td.details-control', function () {
            var url_detail = $('#manifest_table').attr('url-detail');
            preventDefault();
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );
            var data = oTable.row( $(this).parents('tr') ).data();
            var kode_primary = data[ 2 ];                  
            var kode_bagian = data[ 3 ];                  
            var tgl_kunjungan = data[ 4 ];                  
            console.log(data);
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/            
                $.getJSON( url_detail + "/" + kode_primary + "/"+kode_bagian+"/"+tgl_kunjungan, '' , function (data) {
                    response_data = data;
                    // Open this row
                    row.child( format_html( response_data ) ).show();
                    tr.addClass('shown');
                });
            }
            
        } );
    
        $('#manifest_table tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                oTable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        } );

    $('#btn_search_data').click(function (e) {
      e.preventDefault();
      $.ajax({
        url: 'rekam_medis/Manifest/find_data',
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

    $('#btn_reset_data').click(function (e) {
            e.preventDefault();
            reset_table();
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

function format_html ( data ) {
  return data.html;
}

function checkAll(elm) {

  if($(elm).prop("checked") == true){
    $('.ace').each(function(){
        $(this).prop("checked", true);
    });
  }else{
    $('.ace').prop("checked", false);
  }

}

$('select[name="bagian"]').change(function () {    
  if ($(this).val()) {   
    $('<option value="">Loading...</option>').appendTo($('#dokter'));  
      $.getJSON("<?php echo site_url('Templates/References/getDokterByBagian_') ?>/" + $(this).val() , function (data) {              
          $('#dokter option').remove();  
          $('<option value="">-Pilih Dokter-</option>').appendTo($('#dokter'));  
          $.each(data, function (i, o) {     
              $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#dokter'));    
          });   
      });      
  } else {          
      $('#dokter option').remove()
  }     
}); 


function find_data_reload(result){

  oTable.ajax.url(base_url+'?'+result.data).load();

}

function reset_table(){
  oTable.ajax.url(base_url).load();
}

if(!ace.vars['touch']) {
      $('.chosen-select').chosen({allow_single_deselect:true}); 
      //resize the chosen on window resize

      $(window)
      .off('resize.chosen')
      .on('resize.chosen', function() {
        $('.chosen-select').each(function() {
            var $this = $(this);
            $this.next().css({'width': $this.parent().width()});
        })
      }).trigger('resize.chosen');

}

function print_selected_item(myid){

  if(confirm('Are you sure?')){

    $.ajax({
        url: 'rekam_medis/Manifest/print_selected_item',
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

    <form class="form-horizontal" method="post" id="form_search" action="rekam_medis/Manifest/find_data">

      <div class="col-md-12">

        <div class="form-group">
          <label class="control-label col-md-2">Tanggal Kunjungan</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>
        </div>


        <div class="form-group">
          <label class="control-label col-md-2">Poli/Klinik Spesialis</label>
          <div class="col-md-3">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1,'status_aktif' => 1), 'where_in' => array('col' => 'validasi', 'val' => array('0100','0500')) ), '' , 'bagian', 'bagian', 'chosen-select form-control', '', '') ?>
          </div>
          <label class="control-label col-md-1">Dokter</label>
            <div class="col-md-3">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array('status' => '0') ), '' , 'dokter', 'dokter', 'form-control', '', '') ?>
            </div>
            <div class="col-md-3">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Search
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
              <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
              Reset
            </a>
          </div>

        </div>

      </div>

      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="manifest_table" base-url="rekam_medis/Manifest/get_data" data-id="" url-detail="rekam_medis/Manifest/getDetail" class="table table-bordered table-hover">

        <thead>
          <tr>  
            <th class="center" width="50px">No</th>
            <th width="40px" class="center"></th>
            <th width="40px"></th>
            <th width="40px"></th>
            <th width="40px"></th>
            <th>Tanggal Kunjungan</th>
            <th>Nama Dokter</th>
            <th>Poliklinik Spesialis</th>
            <th>Jumlah Pasien</th>      
            <th style="min-width: 70px">Status Print</th>      
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->





