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

  $(document).ready(function() {
    base_url = $('#dynamic-table').attr('base-url');
    var params = $('#dynamic-table').attr('data-id'); 
    //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "pageLength": 25,
      "scrollY": "600px",
      "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
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
        var url_detail = 'farmasi/Farmasi_pesan_resep/getDetail';
        preventDefault();
        var tr = $(this).closest('tr');
        var row = oTable.row( tr );
        var data = oTable.row( $(this).parents('tr') ).data();
        var kode_primary = data[ 1 ];                  
        console.log(data);
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/            
            $.getJSON( url_detail + "/" + kode_primary + "?flag=All", '' , function (data) {
                response_data = data;
                // Open this row
                row.child( format_html( response_data ) ).show();
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

  } ); 

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
        find_data_reload(data);
      }
    });
  });

  $('#btn_reset_data').click(function (e) {
    e.preventDefault();
    $('#form_search')[0].reset();
    oTable.ajax.url($('#dynamic-table').attr('base-url')).load();
  });

  function find_data_reload(result){
      oTable.ajax.url($('#dynamic-table').attr('base-url')+'&'+result.data).load();
  }

  function exc_process(kode_trans_far, flag_code, jenis_resep, status_ambil=0) {
    preventDefault();
    $.ajax({
        url: 'farmasi/Log_proses_resep_obat/process',
        type: "post",
        data: {ID : kode_trans_far, proses: flag_code, jenis : jenis_resep, status_ambil : status_ambil},
        dataType: "json",
        beforeSend: function() {
          // achtungShowLoader();  
        },
        success: function(data) {
          // achtungHideLoader();

          if(data.status === 200){          

            $.achtung({message: data.message, timeout:5});  
            oTable.ajax.reload();  

          }else{

            $.achtung({message: data.message, timeout:5, className: 'achtungFail'}); 

          }  

          
        }
    });
  }

  function format_html ( data ) {
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

    <form class="form-horizontal" method="post" id="form_search" action="Templates/References/find_data" autocomplete="off">

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
              <label class="control-label col-md-1">Tanggal</label>
              <div class="col-md-2">
                <div class="input-group">
                  <input class="form-control date-picker" name="tanggal" id="tanggal" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>

              <div class="col-md-6" style="margin-left: -1.3%">
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
      </div>
      <hr>
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="farmasi/Log_proses_resep_obat/get_data?flag=All" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th width="40px" class="center"></th>
              <th width="40px"></th>
              <th class="center">No</th>
              <th>Kode</th>
              <th width="100px">Tgl Transaksi</th>
              <th>Nama Pasien</th>
              <th>Jenis Resep</th>
              <th width="100px" class="center">Resep Diterima</th>
              <th width="100px" class="center">Penyediaan Obat</th>
              <th width="100px" class="center">Proses Racikan</th>
              <th width="100px" class="center">Proses Etiket</th>
              <th width="100px" class="center">Obat Siap Diambil</th>
              <th width="100px" class="center">Obat Diterima</th>
              <th width="100px" class="center">Total Waktu<br>Pelayanan (hh:mm)</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->





