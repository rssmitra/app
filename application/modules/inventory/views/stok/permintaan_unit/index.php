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
      <?php echo $this->authuser->show_button('inventory/distribusi/permintaan_unit','C','',1)?>
      <div class="pull-right tableTools-container"></div>
    </div>
    <hr class="separator">
    <!-- div.table-responsive -->

    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="inventory/distribusi/permintaan_unit" class="table table-striped table-bordered table-hover">
       <thead>
        <tr>  
          <th width="50px"></th>
          <th width="130px"></th>
          <th>Nomor Permintaan</th>
          <th width="200px">Tanggal Permintaan</th>
          <th width="100px">Bagian</th>
          <th width="150px">Status</th>
          <th width="150px">User</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<!--<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>-->

<script>
  var base_url = $('#dynamic-table').attr('base-url'); 
  $(document).ready(function(){
    table_transaksi = $('#dynamic-table').DataTable({ 
      
      "processing": true, //Feature control the processing indicator.
      
      "serverSide": true, //Feature control DataTables' server-side processing mode.
            
      "ordering": false,
      
      // Load data for the table's content from an Ajax source
      
      "ajax": {
          
          "url": base_url+"/get_data",
          
          "type": "POST"
      
      },

      "columnDefs": [

        { 

          "targets": [ 0 ], //last column

          "orderable": false, //set not orderable

        },

        {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 

        { "visible": true, "targets": [0] },

      ],

    });
  
   $('#dynamic-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table_transaksi.row( tr );
            var data = table_transaksi.row( $(this).parents('tr') ).data();
            var no_permintaan = data[ 2 ];
            console.log(no_permintaan)
            var id_=no_permintaan.split('/')[0];
            var id = parseInt(id_);
            console.log(id);
            

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
               
                $.getJSON("inventory/distribusi/permintaan_unit/getDetailTransaksi/" + id, '', function (data) {
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
            table_transaksi.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            //achtungHideLoader();
        }
    } );

  });

  function format ( data ) {

    return data.html;

  }

</script>



