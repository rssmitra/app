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
    "pageLength" : 50,
    // Load data for the table's content from an Ajax source
     "ajax": {
        "url": "adm_pasien/loket_kasir/Adm_kasir_ri/get_data?search_by="+$("#search_by").val()+"&keyword="+$("#keyword_form").val()+"&is_icu="+$("#is_icu").val()+"",
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

    
  $('#btn_search_data').click(function (e) {
      e.preventDefault();
      $.ajax({
      url: 'adm_pasien/loket_kasir/Adm_kasir_ri/find_data',
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      beforeSend: function() {
        achtungShowLoader();  
      },
      success: function(data) {
        achtungHideLoader();
        find_data_reload(data,'adm_pasien/loket_kasir/Adm_kasir_ri');
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

function find_data_reload(result=''){

  oTable.ajax.url('adm_pasien/loket_kasir/Adm_kasir_ri/get_data?'+result.data).load();
  $("html, body").animate({ scrollTop: "400px" });

}

function rollback(no_registrasi, no_kunjungan){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "adm_pasien/loket_kasir/Adm_kasir_ri/rollback",
      data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          getMenu('adm_pasien/loket_kasir/Adm_kasir_ri');
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

    <form class="form-horizontal" method="post" id="form_search" action="adm_pasien/loket_kasir/Adm_kasir_ri/find_data">

      <center>
          <h4>FORM PENCARIAN DATA PASIEN <?php echo ($is_icu=='N')?'RAWAT INAP':'ICU'; ?><br><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data Pasien Rawat Inap yang masih dirawat sampai Hari ini yaitu tanggal <?php echo $this->tanggal->formatDate(date('Y-m-d'))?> </small></h4>
            <?php if($is_icu=='N'): ?>
              <label class="label label-xs label-success">&nbsp;&nbsp;</label> LA (Lantai Atas)
              <label class="label label-xs label-danger">&nbsp;&nbsp;</label> LB (Lantai Bawah)
              <label class="label label-xs label-primary">&nbsp;&nbsp;</label> VK (Ruang Bersalin dan Nifas)
              <label class="label label-xs label-inverse">&nbsp;&nbsp;</label> Lain-lain
            <?php endif ?>
      </center>
    
      <br>
      <!-- hidden form -->
      <input type="hidden" name="is_icu" value="<?php echo $is_icu ?>" id="is_icu">
      <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2" style="margin-left:-2%">
            <select name="search_by" id="search_by" class="form-control">
              <option value="">-Silahkan Pilih-</option>
              <option value="no_mr" selected>No MR</option>
              <option value="nama_pasien">Nama Pasien</option>
            </select>
          </div>

          <label class="control-label col-md-1">Keyword</label>
          <div class="col-md-2" style="margin-left:-2%">
            <input type="text" class="form-control" name="keyword" id="keyword_form">
          </div>

          <label class="control-label col-md-1">Status</label>
          <div class="col-md-2">
              <select name="status_ranap" id="status_ranap" style="margin-left:-4%">
                <option value="" selected>- Silahkan Pilih -</option>
                <option value="masih dirawat">Masih dirawat</option>
                <option value="sudah pulang">Sudah Pulang</option>
                <!-- <option value="belum lunas">Sudah Lunas</option> -->
              </select>
          </div>

          <div class="col-md-4" style="margin-left:-3.8%">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
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
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="adm_pasien/loket_kasir/Adm_kasir_ri" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="50px">&nbsp;</th>
            <th width="50px">&nbsp;</th>
            <th></th>
            <th></th>
            <th>No</th>
            <th>Kode</th>
            <th>No MR</th>
            <th>Nama Pasien</th>
            <th>Ruangan</th>
            <th>Penjamin</th>
            <th>Kelas</th>
            <th>Hak Kelas</th>
            <th class="center"> Tarif inaCBG</th>
            <th>Tanggal Masuk</th>
            <th>Dokter</th>
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



