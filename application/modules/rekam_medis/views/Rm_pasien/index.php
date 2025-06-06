<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

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

  $('input[name=search_by_field]').click(function(e){
    var field = $('input[name=search_by_field]:checked').val();
    if ( field == 'month_year' ) {
      $('#month_year_field').show('fast');
      $('#tanggal_field').hide('fast');
    }else{
      // if (field=='created_date') {
      //   $('#text_label').html('Pilih Tanggal');
      // }else {
      //   $('#text_label').html('Tanggal Transaksi');
      // }
      $('#month_year_field').hide('fast');
      $('#tanggal_field').show('fast');
    }
  });

});  

var oTable;
var base_url = $('#dynamic-table').attr('base-url'); 
var params = $('#dynamic-table').attr('data-id'); 

$(document).ready(function() {

  $( ".form-control" )  
    .keypress(function(event) {  
      var keycode =(event.keyCode?event.keyCode:event.which);  
      if(keycode ==13){    
        event.preventDefault();     
        if($(this).valid()){  
          $('#btn_search_data').click();  
        }    
        return false;   
      }  
  }); 

    //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "pageLength": 25,
      "scrollY": "600px",
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": base_url,
          "type": "POST"
      },
      "columnDefs": [
        { 
          "targets": [ -1 ], //last column
          "orderable": false, //set not orderable
        },
        {"aTargets" : [0], "mData" : 2, "sClass":  "details-control"}, 
        { "visible": false, "targets": [1,2] },
      ],

    });

    $('#dynamic-table tbody').on('click', 'td.details-control', function () {
          var tr = $(this).closest('tr');
          var row = oTable.row( tr );
          var data = oTable.row( $(this).parents('tr') ).data();
          var no_registrasi = data[ 0 ];

          if ( row.child.isShown() ) {
              // This row is already open - close it
              row.child.hide();
              tr.removeClass('shown');
          }
          else {
              /*data*/
              
              $.getJSON("pelayanan/Pl_pelayanan/view_detail_resume_medis/" + no_registrasi , '', function (data) {
                  response_data = data;
                    // Open this row
                  row.child( format( response_data ) ).show();
                  tr.addClass('shown');
              });
              
          }
  } );
    

    $('#dynamic-table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
      
    $("#button_delete").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          delete_data(''+searchIDs+'')
          console.log(searchIDs);
    });

    $('#btn_search_data').click(function (e) {
        
          e.preventDefault();
          $.ajax({
          url: $('#form_search').attr('action'),
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          success: function(data) {
            achtungHideLoader();
            find_data_reload(data,base_url);
          }
        });
      });

    $('#btn_reset_data').click(function (e) {
            e.preventDefault();
            reset_table();
    });


});

function find_data_reload(result, base_url){
  
    var data = result.data;    
    oTable.ajax.url(base_url+'&'+data).load();

}

function reset_table(){
    oTable.ajax.url(base_url).load();

}

function reload_table(){
   oTable.ajax.reload(); //reload datatable ajax 
}

function format ( data ) {
    return data.html;
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

    <form class="form-horizontal" method="post" id="form_search" action="rekam_medis/Rm_pasien/find_data" autocomplete="off">

    <div class="col-md-12">
      <center><h4>Resume Medis Pasien<br><small style="font-size:12px">(Silahkan lakukan pencarian data berdasarkan parameter dibawah ini)</small></h4></center>
      <br>

      <div class="form-group">
        <label class="control-label col-md-2">Pencarian berdasarkan</label>
        <div class="col-md-2">
          <select name="search_by" id="search_by" class="form-control">
            <option value="">-Silahkan Pilih-</option>
            <option value="tc_registrasi.no_mr" selected>No MR</option>
            <option value="tc_registrasi.no_sep">Nomor SEP</option>
            <option value="nama_pasien">Nama Pasien</option>
          </select>
        </div>
        <label class="control-label col-md-1">Keyword</label>
        <div class="col-sm-2">
          <input type="text" class="form-control" name="keyword" id="keyword">
        </div>

      </div>

      <div class="form-group" id="tanggal_field">
        <label class="control-label col-md-2" id="text_label">Tanggal Pendaftaran</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-2">Poli/Klinik</label>
          <div class="col-md-4">
          <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1, 'status_aktif' => 1)), '' , 'kode_bagian', 'kode_bagian', 'chosen-select form-control', '', '') ?>

          </div>
      </div>

      <div class="form-group">
          <div class="col-md-6 no-padding">
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

      <br>

    </div>

    <hr class="separator">

    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="rekam_medis/Rm_pasien/get_data?flag=" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="50px" class="center"></th>
            <th width="30px">&nbsp;</th>
            <th></th>
            <th width="30px" class="center">No</th>
            <th width="70px">No. MR</th>
            <th>Nama Pasien</th>
            <th>Poliklinik/ Dokter</th>
            <th>Penjamin</th>
            <th width="150px">Tanggal Masuk/Keluar</th>
            <th width="200px">Diagnosa Rujukan</th>
            <!-- <th width="80px" class="center">Tipe (RI/RJ)</th> -->
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      

    </div>
    </form>
  </div><!-- /.col -->
</div><!-- /.row -->





