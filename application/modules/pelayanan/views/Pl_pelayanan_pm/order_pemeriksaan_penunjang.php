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


    oTable = $('#dynamic-table-order-lab').DataTable({ 
            
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": true,
      "bPaginate": true,
      // "bInfo": false,
      "pageLength": 50,
      "bLengthChange": false,
      "bInfo": true,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_pm/get_order_penunjang_lab_view?kode_bagian="+$("#sess_kode_bagian").val()+"&no_mr="+$("#sess_no_mr").val(),
          "type": "POST"
      },

    });

    $('#dynamic-table-order-lab tbody').on( 'click', 'tr', function () {
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


})

function find_data_reload(result){

  oTable.ajax.url('pelayanan/Pl_pelayanan_pm/get_data?'+result.data).load();
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

        <h4>PENGANTAR PEMERIKSAAN PENUNJANG MEDIS<br></h4>

        <!-- hidden form -->
        <input type="hidden" name="sess_kode_bagian" value="<?php echo $kode_bagian ?>" id="sess_kode_bagian">
        <input type="hidden" name="sess_no_mr" value="<?php echo $no_mr ?>" id="sess_no_mr">

        <hr class="separator">
        <!-- div.dataTables_borderWrap -->
        <div style="margin-top:-27px">
          <table id="dynamic-table-order-lab" base-url="pelayanan/Pl_pelayanan_pm" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th>No</th>
                <th>Tanggal Order</th>
                <th>Nama Pasien</th>
                <th>Pemeriksaan</th>
                <th>Dr Pengirim</th>
                <th>Bagian Asal</th>
                <th>Keterangan</th>
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




