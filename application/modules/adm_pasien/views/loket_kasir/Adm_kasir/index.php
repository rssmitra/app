<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script type="text/javascript">
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

  // setInterval("reload_table();",7000);

  $(document).ready(function(){

    $('#keyword').focus();
    // get_total_billing();
   
    oTable = $('#dt_pasien_kasir').DataTable({ 
          
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "ordering": false,
        "searching": false,
        "bLengthChange": true,
        "pageLength": 25,
        "bInfo": false,
        "paging": false,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": $('#dt_pasien_kasir').attr('base-url')+'?flag='+$('#flag').val()+'&pelayanan='+$('#pelayanan').val(),
            "data": {flag:$('#flag').val(), date:$('#date').val(), month:$('#month').val(), year:$('#year').val(), dok_klaim : $('input[name=dok_klaim]:checked').val()},
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

    $('#dt_pasien_kasir tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );
            var data = oTable.row( $(this).parents('tr') ).data();
            var no_registrasi = data[ 1 ];
            

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
               
                $.getJSON("adm_pasien/loket_kasir/Adm_kasir/getDetailTransaksi/" + no_registrasi, '', function (data) {
                    response_data = data;
                     // Open this row
                    row.child( format( response_data ) ).show();
                    tr.addClass('shown');
                });
               
            }
    } );

    $('#dt_pasien_kasir tbody').on( 'click', 'tr', function () {
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


    $("#merge_registrasi").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dt_pasien_kasir input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          merge_registrasi(''+searchIDs+'')
          console.log(searchIDs);
    });

    function merge_registrasi( arr ){
      $.ajax({
          url: 'adm_pasien/loket_kasir/Adm_kasir/merge_data_registrasi',
          type: "post",
          data: { value : arr },
          dataType: "json",
          beforeSend: function() {
          },
          success: function(data) {
            
          }
      });
    }

    function format ( data ) {
        return data.html;
    }

  })

  $( "#keyword" ).keypress(function(event) {  
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){          
        event.preventDefault();         
        if($(this).valid()){           
          $('#btn_search_data').click();    
        }         
        return false;                
      }       
  });


  $('#add_search_by_date').click(function() {
    if (!$(this).is(':checked')) {
      $('#form_tanggal').hide();
    }else{
      $('#form_tanggal').show();
    }
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
          find_data_reload(data);
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
        export_excel(data);
      }
    })
  });

  function export_excel(result){

    window.open('adm_pasien/loket_kasir/Adm_kasir/export_excel?'+result.data+'','_blank'); 

  }

  

  function find_data_reload(result){
      // get_total_billing();
      oTable.ajax.url($('#dt_pasien_kasir').attr('base-url')+'?'+result.data).load();
      $("html, body").animate({ scrollTop: "400px" });
  }

  function reload_table(){
    get_total_billing();
    oTable.ajax.reload();
  }
  
  $('#btn_reset_data').click(function (e) {
      e.preventDefault();
      oTable.ajax.url($('#dt_pasien_kasir').attr('base-url')+'?flag='+$('#flag').val()+'&pelayanan='+$('#pelayanan').val()).load();
      $("html, body").animate({ scrollDown: "400px" });
      $('#form_search')[0].reset();
  });

  function get_total_billing(){
      var url_search = $('#form_search').attr('action');
      $.ajax({
        url: url_search,
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        success: function(response) {
          console.log(response.data);
          $.getJSON("adm_pasien/loket_kasir/Adm_kasir/get_total_billing?"+response.data, '', function (data) {
             // code here
              $('#total_submit').text( formatMoney(data.total_submit) );
              var total_blm_disubmit = sumClass('total_billing_class');
              $('#total_non_submit').text( formatMoney(total_blm_disubmit) );
          });
        }
      });

  }

  function proses_dokumen_klaim( no_registrasi, tipe ){
    preventDefault();
    $.ajax({
        url: 'adm_pasien/loket_kasir/Adm_kasir/costing_billing',
        type: "post",
        data: { value : no_registrasi, type : tipe },
        dataType: "json",
        beforeSend: function() {
          $('#btn_id_'+no_registrasi+'').html('<i>Wait...</i>');
        },
        success: function(data) {
          console.log(data);
          if(data.code == 200){
            // PopupCenter(data.url, '', 900, 700);
            
            window.open(data.url,'_blank'); 
            $('#btn_id_'+no_registrasi+'').html('<i class="fa fa-check circle green bigger-150"></i>');
          }else{
            alert('Dokumen gagal ['+data.message+'] ');
          }


        }
    });
  }


</script>

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->


<div class="row">
  <div class="col-xs-12">

    <!-- <div class="row" style="padding-bottom: 10px; padding-top: 10px">
      <div class="col-xs-12">
        <div class="pull-left" style="border-left: 1px solid #b2b3b5; padding-left: 10px; padding-right: 10px; background: #91ff00">
          <span style="font-size: 12px">Total pemasukan</span>
          <h3 style="font-weight: bold; margin-top : 0px">Rp. <span id="total_submit">0</span>,-</h3>
        </div>

        <div class="pull-left" style="border-left: 1px solid #b2b3b5; padding-left: 10px; padding-right: 10px; background: gold">
          <span style="font-size: 12px">Total billing belum submit</span>
          <h3 style="font-weight: bold; margin-top : 0px">Rp. <span id="total_non_submit">0</span>,-</h3>
        </div>

      </div>
    </div> -->

    <form class="form-horizontal" method="post" id="form_search" action="adm_pasien/loket_kasir/Adm_kasir/find_data">
      <!-- hidden form -->
      <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">
      <input type="hidden" name="pelayanan" id="pelayanan" value="<?php echo $pelayanan?>">

      <div class="form-group">
        <label class="control-label col-md-2">Pencarian berdasarkan</label>
        <div class="col-md-2">
          <select name="search_by" id="search_by" class="form-control">
            <option value="">-Silahkan Pilih-</option>
            <option value="a.no_mr" <?php echo ($_GET['owner']=='kasir') ? 'selected' : ''?>>No MR</option>
            <option value="c.nama_pasien">Nama Pasien</option>
            <option value="b.no_sep" <?php echo ($_GET['owner']=='costing') ? 'selected' : ''?>>Nomor SEP</option>
            <option value="b.no_registrasi">No Registrasi</option>
          </select>
        </div>
        <label class="control-label col-md-1">Keyword</label>
        <div class="col-sm-2">
          <input type="text" class="form-control" name="keyword" id="keyword">
        </div>

        <label class="control-label col-md-1">Tanggal </label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
          <div class="col-md-2" style="margin-left: -2%">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
      </div>

      <div class="form-group">
          <label class="control-label col-md-2">Poli/Klinik</label>
          <div class="col-md-4">
          <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1, 'group_bag' => 'Detail', 'status_aktif' => 1) ),'' , 'kode_bagian', 'kode_bagian', 'form-control', '', '') ?>
          </div>
        
          <div class="col-md-3" style="margin-left: -1%">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Cari
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-xs btn-danger">
              <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
              Reload
            </a>
            <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
                <i class="ace-icon fa fa-file-excel-o icon-on-right bigger-110"></i>
                Export Excel
            </a>
          </div>
      </div>

      <!-- <div class="form-group">
        <label class="control-label col-md-2">Proses Dokumen Klaim</label>
        <div class="col-md-2">
          <div class="radio">
            <label>
              <input name="dok_klaim" type="radio" class="ace" value="1" />
              <span class="lbl"> Berhasil</span>
            </label>
            <label>
              <input name="dok_klaim" type="radio" class="ace" value="0" />
              <span class="lbl"> Gagal</span>
            </label>
          </div>
        </div>
      </div> -->
      


      <div id="showDataTables">
        <table id="dt_pasien_kasir" base-url="adm_pasien/loket_kasir/adm_kasir/get_data" url-detail="billing/Billing/getDetailBillingKasir" class="table table-bordered table-hover">
          <thead>
            <tr style="background-color:#428bca">
              <th width="50px"></th>
              <th class="center"></th>
              <th width="50px" class="center">No</th>
              <th width="70px">No. MR</th>
              <th>Nama Pasien</th>
              <?php echo ($flag=='bpjs') ? '<th>No SEP</th>' : '' ; ?>
              <!-- <th>No. MR</th> -->
              <th>Poli/Klinik Asal</th>
              <th>Penjamin</th>
              <th width="120px">Tgl Masuk</th>
              <th width="120px">Tgl Transaksi</th>
              <th width="150px">Petugas</th>
              <th width="130px">Total Billing</th>
              <!-- <th width="100px">Dok Klaim</th> -->
            </tr>
          </thead>
        </table>
      </div>   

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




