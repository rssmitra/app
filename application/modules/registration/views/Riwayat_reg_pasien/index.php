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

var oTable;
var base_url = $('#dynamic-table').attr('base-url'); 
var params = $('#dynamic-table').attr('data-id'); 

oTable = $('#dynamic-table').DataTable({ 
          
  "processing": true, //Feature control the processing indicator.
  "serverSide": true, //Feature control DataTables' server-side processing mode.
  "ordering": false,
  "searching": false,
  "bLengthChange" : false,
  "paging" : false,
  // "pageLength": 25,
  // "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
  // Load data for the table's content from an Ajax source
  "drawCallback": function (response) { 
    // Here the response
      var objData = response.json;
      console.log(objData);
      $('#rekap_data tbody').remove();        
      var no = 0;
      $.each(objData.resume, function (i, o) {       
        no++;
          $('<tr><td align="center">'+no+'</td><td>'+o.unit+'</td><td align="center">'+o.total+'</td></tr>').appendTo($('#rekap_data'));   
      });
      $('.total_rekap').html(objData.recordsTotal);
      $('#rekap_batal').html(objData.rekap_batal);
      var total_berkunjung = parseInt(objData.recordsTotal) - parseInt(objData.rekap_batal);
      $('#total_berkunjung').html(total_berkunjung);

      $('#resume_rekap_data tbody').remove();  
      var noa = 0;      
      $.each(objData.rekap, function (k, v) {       
        noa++;
          $('<tr><td align="center">'+noa+'</td><td>'+v.unit+'</td><td align="center">'+v.total+'</td></tr>').appendTo($('#resume_rekap_data'));   
      });

      $('#resume_rekap_data_dr tbody').remove();  
      var nob = 0;      
      $.each(objData.rekap_dr, function (k, v) {       
        nob++;
          $('<tr><td align="center">'+nob+'</td><td>'+v.nama_dr+'</td><td align="center">'+v.total+'</td></tr>').appendTo($('#resume_rekap_data_dr'));   
      });

      $('#resume_rekap_data_asuransi tbody').remove();  
      var noc = 0;      
      $.each(objData.rekap_asuransi, function (k, v) {       
        noc++;
          $('<tr><td align="center">'+noc+'</td><td>'+v.penjamin+'</td><td align="center">'+v.total+'</td></tr>').appendTo($('#resume_rekap_data_asuransi'));   
      });

      $('#resume_rekap_stat_pasien tbody').remove();  
      var noc = 0;      
      $.each(objData.rekap_stat_pasien, function (k, v) {       
        noc++;
          $('<tr><td align="center">'+noc+'</td><td>PASIEN '+v.status.toUpperCase()+'</td><td align="center">'+v.total+'</td></tr>').appendTo($('#resume_rekap_stat_pasien'));   
      });

  },
  "ajax": {
      "url": base_url+"/get_data",
      "type": "POST"
  },

});

$('#dynamic-table tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
    }
    else {
        oTable.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
    }
} );

$('#btn_reset_data').click(function (e) {
    e.preventDefault();
    oTable.ajax.url(base_url+'/get_data').load();
    $('#form_search')[0].reset();
});

$('#btn_search_data').click(function (e) {
    var url_search = $('#form_search').attr('action');
    e.preventDefault();
    $.ajax({
    url: url_search,
    type: "post",
    data: $('#form_search').serialize(),
    dataType: "json",
    success: function(data) {
      console.log(data.data);
      oTable.ajax.url(base_url+'/get_data?'+data.data).load();
    }
  });
});


$('select[name="bagian"]').change(function () {      

  if ($(this).val()) {          

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

$( ".form-control" )    

      .keypress(function(event) {        

        var keycode =(event.keyCode?event.keyCode:event.which);         

        if(keycode ==13){          

          event.preventDefault();          

          if($(this).valid()){            

            $('#btn_search_data').focus();            

          }          

          return false;                 

        }        

}); 

function saveRow(no_registrasi){  

  preventDefault();
  $.ajax({
      url: 'registration/Riwayat_reg_pasien/updateNoSEP',
      type: "post",
      data: {ID:no_registrasi, no_sep: $('#no_sep_'+no_registrasi+'').val()},
      dataType: "json",
      beforeSend: function() {
        // achtungShowLoader();  
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        if(jsonResponse.status === 200){
          $.achtung({message: jsonResponse.message, timeout:5});
          // reload_table();
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

    <form class="form-horizontal" method="post" id="form_search" action="registration/Riwayat_reg_pasien/find_data">

    <div class="col-md-12">

      <center><h4>FORM PENCARIAN DATA REGISTRASI PASIEN<br><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data hari ini tanggal <?php echo date('d/M/Y')?></small></h4></center>
      <br>

      <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
            <select name="search_by" class="form-control">
              <option value="">-Silahkan Pilih-</option>
              <option value="no_mr" selected>No MR</option>
              <option value="nama">Nama Pasien</option>
            </select>
          </div>

          <label class="control-label col-md-1">Keyword</label>
          <div class="col-md-2">
            <input type="text" class="form-control" name="keyword" id="keyword_form">
          </div>

      </div>

      <!-- <div class="form-group">
        <label class="control-label col-md-2">Bulan</label>
          <div class="col-md-2">
            <?php echo $this->master->get_bulan('' , 'bulan', 'bulan', 'form-control', '','') ?>
          </div>
          <label class="control-label col-md-1">Tahun</label>
          <div class="col-md-2">
            <?php echo $this->master->get_tahun('' , 'tahun', 'tahun', 'form-control', '', '') ?>
          </div>
      </div> -->

      <div class="form-group">

          <label class="control-label col-md-2">Bagian</label>

          <div class="col-md-4">

              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1,'status_aktif' => 1), 'where_in' => array('col' => 'validasi', 'val' => array('0100','0300','0500')) ), '' , 'bagian', 'bagian', 'form-control', '', '') ?>

          </div>

      </div>

      <div class="form-group">
          <label class="control-label col-md-2">*Nama Dokter</label>

          <div class="col-md-4">
            <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'dokter', 'dokter', 'form-control', '', '') ?>
          </div>

      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Registrasi</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
      </div>

      <div class="form-group">
        <label class="col-md-2">&nbsp;</label>
        <div class="col-md-10" style="margin-left: 7px">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
          <!-- <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
            <i class="fa fa-file-word-o bigger-110"></i>
            Export Excel
          </a> -->
        </div>
      </div>

    </div>

    <hr class="separator">
    
    <div class="col-md-12" style="padding-top: 10px">
      <div class="tabbable">
        <ul class="nav nav-tabs" id="myTab">
          <li class="active">
            <a data-toggle="tab" href="#datatables">
              DATA TABLE
            </a>
          </li>

          <li>
            <a data-toggle="tab" href="#resume_data">
              REKAPITULASI DATA
            </a>
          </li>
        </ul>

        <div class="tab-content">

          <div id="datatables" class="tab-pane fade in active">
            <div>
              <p style="font-size: 12px; font-weight: bold">RIWAYAT REGISTRASI PASIEN</p>
              <table id="dynamic-table" base-url="registration/Riwayat_reg_pasien" class="table table-bordered table-hover">
              <thead>
                <tr>  
                  <th width="30px" class="center"></th>
                  <th style="width: 80px !important">No Reg</th>
                  <th>No MR</th>
                  <th>Nama Pasien</th>
                  <th>Penjamin</th>
                  <th>Tanggal Registrasi</th>
                  <th>Tujuan Bagian</th>
                  <th>Nama Dokter</th>          
                  <th>Nomor SEP</th>          
                </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>

          <div id="resume_data" class="tab-pane fade">

            <div class="row">
              <div class="col-md-4">
                <p style="font-size: 12px; font-weight: bold;">REKAP DATA REGISTRASI PASIEN</p>
                <table class="table" id="rekap_data">
                  <thead>
                    <tr>
                      <th class="center" width="30px">No</th>
                      <th>Tujuan Kunjungan</th>
                      <th class="center" width="100px">Jumlah</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr><td colspan="2" align="right"><b>TOTAL KUNJUNGAN PASIEN</b></td><td align="center"><span class="total_rekap"></span></td></tr>
                  </tfoot>
                </table>
              </div>

              <div class="col-md-4">
                <p style="font-size: 12px; font-weight: bold;">REKAP DATA BERDASARKAN DOKTER</p>
                <table class="table" id="resume_rekap_data_dr">
                  <thead>
                    <tr>
                      <th class="center" width="30px">No</th>
                      <th>Nama Dokter</th>
                      <th class="center" width="100px">Jumlah</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr><td colspan="2" align="right"><b>TOTAL KUNJUNGAN PASIEN</b></td><td align="center"><span class="total_rekap"></span></td></tr>
                  </tfoot>
                </table>
              </div>

              <div class="col-md-3">
                <p style="font-size: 12px; font-weight: bold;">REKAP DATA BERDASARKAN INSTALASI</p>
                <table class="table" id="resume_rekap_data">
                  <thead>
                    <tr>
                      <th class="center" width="30px">No</th>
                      <th>Tujuan</th>
                      <th class="center" width="100px">Jumlah</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr><td colspan="2" align="right"><b>TOTAL KUNJUNGAN PASIEN</b></td><td align="center"><span class="total_rekap"></span></td></tr>
                  </tfoot>
                </table>
                <br>
                <p style="font-size: 12px; font-weight: bold;">REKAP DATA BERDASARKAN ASURANSI</p>
                <table class="table" id="resume_rekap_data_asuransi">
                  <thead>
                    <tr>
                      <th class="center" width="30px">No</th>
                      <th>Tujuan</th>
                      <th class="center" width="100px">Jumlah</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr><td colspan="2" align="right"><b>TOTAL KUNJUNGAN PASIEN</b></td><td align="center"><span class="total_rekap"></span></td></tr>
                  </tfoot>
                </table>
                <br>
                <p style="font-size: 12px; font-weight: bold;">REKAP DATA BERDASARKAN STATUS PASIEN</p>
                <table class="table" id="resume_rekap_stat_pasien">
                  <thead>
                    <tr>
                      <th class="center" width="30px">No</th>
                      <th>Status</th>
                      <th class="center" width="100px">Jumlah</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr><td colspan="2" align="right"><b>TOTAL KUNJUNGAN PASIEN</b></td><td align="center"><span class="total_rekap"></span></td></tr>
                  </tfoot>
                </table>
                <br>
                <p style="font-size: 12px; font-weight: bold;">REKAP KUNJUNGAN PASIEN</p>
                <table class="table" id="resume_rekap_batal">
                    <tr>
                      <td class="center" widtd="30px">1</td>
                      <td>TOTAL DATA PASIEN</td>
                      <td class="center" width="100px"><span class="total_rekap"></span></td>
                    </tr>
                    <tr>
                      <td class="center" widtd="30px">2</td>
                      <td>TOTAL PASIEN BATAL KUNJUNGAN</td>
                      <td class="center" width="100px"><span id="rekap_batal"></span></td>
                    </tr>
                    <tr>
                      <td class="center" widtd="30px">&nbsp;</td>
                      <td align="right"><b>TOTAL PASIEN BERKUNJUNG</b></td>
                      <td class="center" width="100px"><span id="total_berkunjung"></span></td>
                    </tr>
                </table>

              </div>

            </div>

          </div>

        </div>
      </div>
    </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->



