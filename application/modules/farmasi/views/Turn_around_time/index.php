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

    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": true,
      "bInfo": false,
      "pageLength": 5,
      "bPaginate": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#dynamic-table').attr('base-url'),
          "type": "POST"
      },

    });


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
        find_data_reload(data,base_url);
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


 function exc_process(kode_trans_far, flag_code, jenis_resep){
    preventDefault();
    $.ajax({
        url: 'farmasi/Turn_around_time/process',
        type: "post",
        data: {ID : kode_trans_far, proses: flag_code, jenis : jenis_resep},
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
      
      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="farmasi/Turn_around_time/get_data?flag=All" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th class="center">No</th>
              <th>Kode</th>
              <th>Tgl Transaksi</th>
              <th>Nama Pasien</th>
              <th>Jenis Resep</th>
              <th width="130px" class="center">Resep Diterima <br>s.d<br> Selesai Input Obat</th>
              <th width="130px" class="center">Penyediaan Obat <br>s.d<br> Mulai Proses Racikan/Etiket</th>
              <th width="130px" class="center">Proses Racikan <br>s.d<br> Proses Etiket</th>
              <th width="130px" class="center">Proses Etiket <br>s.d<br> Siap Diambil</th>
              <th width="130px" class="center">Siap Diambil <br>s.d<br> Obat Diterima</th>
              <th width="130px" class="center">Total Waktu<br>Pelayanan</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->





