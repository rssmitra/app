<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>
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


 function exc_process(kode_trans_far, flag_code, jenis_resep){
    preventDefault();
    $.ajax({
        url: 'farmasi/Log_proses_resep_obat/process',
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

      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="farmasi/Log_proses_resep_obat/get_data?flag=All" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th class="center">No</th>
              <th>Kode</th>
              <th>Tgl Transaksi</th>
              <th>Nama Pasien</th>
              <th>Jenis Resep</th>
              <th width="100px">Resep Diterima</th>
              <th width="100px">Penyediaan Obat</th>
              <th width="100px">Proses Racikan</th>
              <th width="100px">Proses Etiket</th>
              <th width="100px">Siap Diambil</th>
              <th width="100px">Obat Diterima</th>
              <th width="100px">Total Waktu<br>Pelayanan</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->





