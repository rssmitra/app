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
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": base_url,
          "type": "POST"
      },
      "columnDefs": [
          { 
            "targets": [ 0 ], 
            "orderable": false,
          },
          {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
          { "visible": false, "targets": [1] },
        ],

    });
    

    $('#dynamic-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );
            var data = oTable.row( $(this).parents('tr') ).data();
            var no_registrasi = data[ 1 ];
            var tipe = data[ 8 ];
            

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
               
                $.getJSON("casemix/Csm_verifikasi_costing/viewDetailDokumen/" + no_registrasi+"/"+tipe+"", '', function (data) {
                    response_data = data;
                     // Open this row
                    row.child( format( response_data ) ).show();
                    tr.addClass('shown');
                });
               
            }
    } );

    $('#dynamic-table tbody').on( 'click', 'tr', function () {
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

    $('#btn_export_excel').click(function (e) {
        
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
            export_excel(data,base_url);
          }
        });
  });

    $('#btn_reset_data').click(function (e) {
        e.preventDefault();
        reset_table();
    });


});


function updateDokumen(no_registrasi, type){
  
  preventDefault();
  $.ajax({
    url: 'casemix/Csm_billing_pasien/process',
    type: "post",
    data: {no_registrasi_hidden: no_registrasi, submit: 'update_dok_klaim', form_type: type, csm_rp_no_sep: $('#csm_rp_no_sep_'+no_registrasi+'').val(), csm_rp_tgl_masuk: $('#tgl_masuk_'+no_registrasi+'').val(), csm_rp_tgl_keluar: $('#tgl_keluar_'+no_registrasi+'').val()},
    dataType: "json",
    beforeSend: function() {
      achtungShowLoader();  
    },
    complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status === 200){
          $.achtung({message: jsonResponse.message, timeout:5});
          window.open('casemix/Csm_billing_pasien/mergePDFFiles/'+jsonResponse.no_registrasi+'/'+jsonResponse.type+'', '_blank');
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }
        achtungHideLoader();
    }

  });
}

function export_excel(result){

  window.open('casemix/Csm_verifikasi_costing/export_excel?'+result.data+'','_blank'); 

}

function find_data_reload(result, base_url){
  
    var data = result.data;    
    oTable.ajax.url(base_url+'&'+data).load();
    $("html, body").animate({ scrollTop: "400px" });

}

function reset_table(){
    oTable.ajax.url(base_url).load();
    $("html, body").animate({ scrollTop: "400px" });

}

function reload_table(){
   oTable.ajax.reload(); //reload datatable ajax 
}

function format ( data ) {
    return data.html;
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

    <form class="form-horizontal" method="post" id="form_search" action="casemix/Csm_verifikasi_costing/find_data">

    <div class="col-md-12">
      <center><h4>VERIFIKASI COSTING<br><small style="font-size:12px">(Silahkan lakukan pencarian data berdasarkan parameter dibawah ini)</small></h4></center>
      <br>

      <div class="form-group">
        <label class="control-label col-md-2">Pencarian berdasarkan</label>
        <div class="col-md-2">
          <select name="search_by" id="search_by" class="form-control">
            <option value="">-Silahkan Pilih-</option>
            <option value="csm_reg_pasien.csm_rp_no_sep" selected>Nomor SEP</option>
            <option value="csm_reg_pasien.csm_rp_no_mr">No MR</option>
            <option value="csm_reg_pasien.csm_rp_nama_pasien">Nama Pasien</option>
          </select>
        </div>
        <label class="control-label col-md-1">Keyword</label>
        <div class="col-sm-2">
          <input type="text" class="form-control" name="keyword" id="keyword">
        </div>

      </div>


      <div class="form-group">
        <label class="control-label col-md-2">Jenis Tanggal</label>
          <div class="col-md-10">
            <div class="radio">
              <label>
                <input name="search_by_field" type="radio" class="ace" value="csm_reg_pasien.created_date" checked>
                <span class="lbl"> Tanggal Costing</span>
              </label>

              <label>
                <input name="search_by_field" type="radio" class="ace" value="csm_dokumen_klaim.tgl_transaksi_kasir">
                <span class="lbl"> Tanggal Transaksi Kasir</span>
              </label>

              <label>
                <input name="search_by_field" type="radio" class="ace" value="csm_reg_pasien.csm_rp_tgl_masuk">
                <span class="lbl"> Tanggal Kunjungan Pasien</span>
              </label>

              <!-- <label>
                <input name="search_by_field" type="radio" class="ace" value="month_year">
                <span class="lbl"> Bulan dan Tahun Transaksi</span>
              </label> -->
            </div>

          </div>
      </div>
      <div class="form-group" id="tanggal_field">
        <label class="control-label col-md-2" id="text_label">Pilih Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tgl</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
      </div>
      <div class="form-group" id="month_year_field" style="display:none">
        <label class="control-label col-md-2">Bulan</label>
          <div class="col-md-2">
            <select name="month" id="month" class="form-control">
              <option value="">-Silahkan Pilih-</option>
              <?php
                for($month=1;$month<13;$month++){
                  echo '<option value="'.$month.'">'.$this->tanggal->getBulan($month).'</option>';    
                }
              ?>
              
            </select>
          </div>

          <label class="control-label col-md-1">Tahun</label>
          <div class="col-md-2">
            <select name="year" id="year" class="form-control">
              <option value="">-Silahkan Pilih-</option>
               <?php
                  for($year=2017;$year<=date('Y');$year++){
                    echo '<option value="'.$year.'">'.$year.'</option>';    
                  }
                ?>
            </select>
          </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-2">Poli/Klinik</label>
          <div class="col-md-4">
          <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1, 'group_bag' => 'Detail', 'status_aktif' => 1) ),'' , 'kode_bagian', 'kode_bagian', 'form-control', '', '') ?>
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tipe (RI/RJ)</label>
          <div class="col-md-2">
            <select name="tipe" id="tipe" class="form-control">
              <option value="all">-Semua-</option>
              <option value="RJ">Rawat Jalan</option>
              <option value="RI">Rawat Inap</option>
            </select>
          </div>
          <div class="col-md-6">
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
      <table id="dynamic-table" base-url="casemix/Csm_verifikasi_costing/get_data?flag=" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="50px"></th>
            <th class="center"></th>
            <th width="50px" class="center">No</th>
            <th width="150px">No. MR/ Nama Pasien</th>
            <!-- <th width="70px">No. Reg</th> -->
            <th width="80px">No. SEP</th>
            <th width="80px">Tgl Masuk/Keluar</th>
            <th width="100px" class="center">Tgl Costing</th>
            <th>Poli/Klinik & Dokter</th>
            <th width="80px" class="center">Tipe (RI/RJ)</th>
            <th width="100px" class="center">Dok Klaim</th>
            <!-- <th width="100px" class="center">Location File</th> -->
            <th width="100px" class="center">Total Klaim</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      

    </div>
    </form>
  </div><!-- /.col -->
</div><!-- /.row -->





