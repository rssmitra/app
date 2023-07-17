<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_/style_wizard.css" />
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

  var step = '<?php echo $step ?>';

  oTable = $('#dynamic-table-without-wizard').DataTable({ 
          
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": false,
    "bPaginate": true,
    // "bInfo": false,
    "pageLength": 50,
    "bLengthChange": false,
    "bInfo": true,
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": "pelayanan/Pl_pelayanan_pm/get_data?sess_kode_bagian="+$("#sess_kode_bagian").val()+"&search_by="+$("#search_by").val()+"&keyword="+$("#keyword_form").val()+"&from_tgl="+$("#from_tgl").val()+"&to_tgl="+$("#to_tgl").val()+"",
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

  $('#dynamic-table-without-wizard tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );
            var data = oTable.row( $(this).parents('tr') ).data();
            var no_registrasi = data[ 0 ];
            var tipe = data[ 1 ];
            

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
               
                $.getJSON("billing/Billing/getDetail/" + no_registrasi + "/" + tipe, '', function (data) {
                    response_data = data;
                     // Open this row
                    row.child( format( response_data ) ).show();
                    tr.addClass('shown');
                });
               
            }
    } );

    $('#dynamic-table-without-wizard tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            //achtungShowLoader();
            $(this).removeClass('selected');
            //achtungHideLoader();
        }
        else {
            //achtungShowLoader();
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            //achtungHideLoader();
        }
    } );

      $('#btn_search_data').click(function (e) {
          e.preventDefault();
          $.ajax({
          url: 'pelayanan/Pl_pelayanan_pm/find_data',
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          success: function(data) {
            achtungHideLoader();
            find_data_reload(data,'pelayanan/Pl_pelayanan_pm?bag_tujuan='+$("#sess_kode_bagian").val()+'');
          }
        });
      });

      $( ".form-input-nosep" ).keypress(function(event) {        
       var keycode =(event.keyCode?event.keyCode:event.which);         
       if(keycode ==13){          
         event.preventDefault();          
         if($(this).valid()){            
           $(this).focus();            
         }          
         return false;                 
       }        
      }); 

})

function format ( data ) {
    return data.html;
}

function getBillingDetail(noreg, type, field){
  preventDefault();
  $.getJSON("billing/Billing/getRincianBilling/" + noreg + "/" + type + "/" +field, '', function (data) {
      response_data = data;
      html = '';
      html += '<div class="center"><p><b>RINCIAN BIAYA '+field+'</b></p></div>';
      //alert(response_data.html); return false;
      $('#detail_item_billing_'+noreg+'').html(data.html);
  });
 
}

function find_data_reload(result){

  oTable.ajax.url('pelayanan/Pl_pelayanan_pm/get_data?'+result.data).load();
  // $("html, body").animate({ scrollTop: "400px" });

}

function rollback(kode_penunjang){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan_pm/rollback",
      data: { kode_penunjang: kode_penunjang, kode_bagian: $("#sess_kode_bagian").val()},            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          getMenu('pelayanan/Pl_pelayanan_pm?type_tujuan='+$("#sess_kode_bagian").val()+'');
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });

}

function saveNoSep(noreg, nokunj){

  preventDefault();  

  $.ajax({
      url: "pelayanan/Pl_pelayanan_pm/saveNoSep",
      data: { no_registrasi : noreg, no_kunjungan: nokunj, no_sep : $('#no_sep_'+nokunj+'').val() },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
      }
  });

}

function periksa(kode_penunjang) {
  
  $.ajax({
      url: "pelayanan/Pl_pelayanan_pm/periksa_lab",
      data: { kode_penunjang: kode_penunjang },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          // navigation steps / progress steps
          var current_active_step = $('#btn_pemeriksaan').parents();
          var progress_line = $('#btn_pemeriksaan').parents('.f1').find('.f1-progress-line');
       
          current_active_step.removeClass('active').addClass('activated').next().addClass('active');
    			// progress bar
    			bar_progress(progress_line, 'right');
          
          $('#status_pasien').val('belum_isi_hasil');

          $.ajax({
            url: 'pelayanan/Pl_pelayanan_pm/find_data',
            type: "post",
            data: $('#form_search').serialize(),
            dataType: "json",
            beforeSend: function() {
              achtungShowLoader();  
            },
            success: function(data) {
              achtungHideLoader();
              find_data_reload(data,'pelayanan/Pl_pelayanan_pm');
            }
          });
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });
}

function cetak_slip(kode_penunjang) {
  
  noMr = $('#noMrHidden').val();
  url = 'pelayanan/Pl_pelayanan_pm/slip?kode_penunjang='+kode_penunjang+'';
  title = 'Cetak Slip';
  width = 500;
  height = 600;
  PopupCenter(url, title, width, height); 

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

    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan_pm/find_data">

    <div class="col-md-12">

      <center><h4>FORM PENCARIAN DATA PASIEN <?php echo strtoupper($nama_bag) ?><br></h4></center>
      <br>

      <!-- hidden form -->
      <input type="hidden" name="sess_kode_bagian" value="<?php echo $bag_tujuan ?>" id="sess_kode_bagian">
      <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
            <select name="search_by" id="search_by" class="form-control">
              <option value="">-Silahkan Pilih-</option>
              <option value="no_mr" selected>No MR</option>
              <option value="nama_pasien">Nama Pasien</option>
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

      <!-- <div class="form-group">
          <label class="control-label col-md-2">Status</label>
          <div class="col-md-4">
              <select name="status_pasien" id="status_pasien">
                <option value="" selected>- Silahkan Pilih -</option>
                <option value="belum ditindak">Belum ditindak</option>
                <option value="belum bayar">Belum bayar</option>
                <option value="belum periksa">Belum periksa</option>
              </select>
          </div>
      </div> -->

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
          <!-- <a href="#" id="btn_batalkan_kunjungan" class="btn btn-xs btn-danger">
            <i class="ace-icon fa fa-times-circle icon-on-right bigger-110"></i>
            Rollback
          </a> -->
        </div>
      </div>

    </div>

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table-without-wizard" base-url="pelayanan/Pl_pelayanan_pm" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="50px">&nbsp;</th>
            <th width="50px">&nbsp;</th>
            <th></th>
            <th></th>
            <th>Kode</th>
            <th>No MR</th>
            <th>Nama Pasien</th>
            <th>Urutan</th>
            <th>Penjamin</th>
            <th width="150px">No SEP</th>
            <th>Tanggal Masuk</th>
            <th>Asal Daftar</th>
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

<!-- <script src="<?php //echo base_url().'assets/js/custom/als_datatable.js'?>"></script> -->



