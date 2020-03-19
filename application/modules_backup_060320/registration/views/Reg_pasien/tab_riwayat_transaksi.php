<script>

  $(document).ready(function(){
    
    var no_mr = '00201189';

    table_transaksi = $('#riwayat-transaksi-table').DataTable({ 
      
      "processing": true, //Feature control the processing indicator.
      
      "serverSide": true, //Feature control DataTables' server-side processing mode.
            
      "ordering": false,
      "searching": false,
      "bInfo": false,
      "scrollY": "500px",
      //"scrollX": "500px",
      "lengthChange": false,
      "bPaginate": false,
      // Load data for the table's content from an Ajax source
      
      "ajax": {
          
          "url": "registration/Reg_pasien/get_riwayat_transaksi_pasien?mr="+no_mr,
          
          "type": "POST"
      
      },

      "columnDefs": [

        { 

          "targets": [ 0 ], //last column

          "orderable": false, //set not orderable

        },

        {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 

        { "visible": false, "targets": [1] },

      ],

    });

    $('#riwayat-transaksi-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table_transaksi.row( tr );
            var data = table_transaksi.row( $(this).parents('tr') ).data();
            var no_registrasi = data[ 1 ];
            

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
               
                $.getJSON("registration/Reg_pasien/getDetailTransaksi/" + no_registrasi, '', function (data) {
                    response_data = data;
                     // Open this row
                    row.child( format( response_data ) ).show();
                    tr.addClass('shown');
                });
               
            }
    } );

    $('#riwayat-transaksi-table tbody').on( 'click', 'tr', function () {
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

  function find_data_reload(mr){
  
      table_transaksi.ajax.url('registration/Reg_pasien/get_riwayat_transaksi_pasien?mr='+mr).load();

  }


</script>

<b> RIWAYAT TRANSAKSI PASIEN <i class="fa fa-angle-double-right bigger-120"></i> </b>

<table id="riwayat-transaksi-table" class="table table-bordered table-hover">

   <thead>

    <tr>  

      <th width="50px" class="center"></th>
      <th width="50px" class="center"></th>
      <th>Deskripsi Item</th>

    </tr>

  </thead>

  <tbody id="table_riwayat_pasien">

  </tbody>

</table>