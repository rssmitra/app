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


    oTable = $('#dynamic-table-order-fisio').DataTable({ 
            
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
          "url": "pelayanan/Pl_pelayanan_pm/get_order_penunjang_fisio_view?kode_bagian="+$("#kode_bagian").val()+"&no_mr="+$("#no_mr_fisio").val(),
          "type": "POST"
      },

    });

    $('#dynamic-table-order-fisio tbody').on( 'click', 'tr', function () {
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
          find_data_reload(data,'pelayanan/Pl_pelayanan_pm?bag_tujuan='+$("#kode_bagian").val()+'');
        }
      });
    });

})

function find_data_reload(result){

  oTable.ajax.url('pelayanan/Pl_pelayanan_pm/get_order_penunjang_fisio_view?'+result.data).load();
  // $("html, body").animate({ scrollTop: "400px" });

}

</script>
<div class="row">
  <div class="col-xs-12">

    <?php if(empty($no_mr)) :?>
    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->
    <?php endif; ?>


    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan_pm/find_data">
      
      
      <div class="col-md-12">

        <h4>PENGANTAR PEMERIKSAAN FISIOTERAPI<br></h4>
        <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
            <select name="search_by" id="search_by" class="form-control">
              <option value="">-Silahkan Pilih-</option>
              <option value="no_mr" selected="">No MR</option>
              <option value="nama_pasien">Nama Pasien</option>
            </select>
          </div>
          <label class="control-label col-md-1">Keyword</label>
          <div class="col-md-2">
            <input type="text" class="form-control" name="keyword" id="keyword_form">
          </div>
          <div class="col-md-4">
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

        <!-- hidden form -->
        <input type="hidden" name="kode_bagian" value="<?php echo $kode_bagian ?>" id="kode_bagian">
        <input type="hidden" name="no_mr" value="<?php echo $no_mr ?>" id="no_mr_fisio">

        <hr class="separator">
        <!-- div.dataTables_borderWrap -->
        <div style="margin-top:-27px">
          <table id="dynamic-table-order-fisio" base-url="pelayanan/Pl_pelayanan_pm" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th>No</th>
                <th>Tanggal Order</th>
                <th>Nama Pasien</th>
                <th>Pemeriksaan</th>
                <th>Diagnosa</th>
                <th>X-Ray Foto</th>
                <th>Kontra Indikasi</th>
                <th>Keterangan</th>
                <th>Dokter Pengirim</th> 
                <th>Status</th> 
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->




