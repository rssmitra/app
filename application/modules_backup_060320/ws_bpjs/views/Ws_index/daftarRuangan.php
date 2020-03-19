<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

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

    <div class="clearfix" style="margin-bottom:-5px">
      <button type="button" onclick="getMenu('ws_bpjs/ws_index?modWs=tambahRuangan')" class="btn btn-sm btn-primary" data-toggle="button">Tambah Ruangan</button>
    </div>
    <hr class="separator">
    <!-- div.table-responsive -->

    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="ws_bpjs/Ws_index/get_data_ruangan" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="30px" class="center"></th>
            <th width="80px">Kode</th>
            <th>Nama Ruangan</th>
            <th>Kelas</th>
            <th>No Kamar</th>
            <th>No Bed</th>
            <th>Status</th>
            <th>Nama Pasien</th>
            <th>Keterangan</th>
            <th>Gender</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<script type="text/javascript">

    var oTable;
    var base_url = $('#dynamic-table').attr('base-url'); 

    $(document).ready(function() {
      //initiate dataTables plugin
        oTable = $('#dynamic-table').DataTable({ 
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "scrollX": false,
          "ordering": false,
          "paging":         false,
          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": base_url,
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
              url: base_url+'/find_data',
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
                reset_table();
        });


    });
</script>


