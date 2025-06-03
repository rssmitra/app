<script type="text/javascript">

    $(document).ready(function() {
      
        // table riwayat
        oTableRiwayat = $('#table-riwayat-pesan-resep').DataTable({ 
            
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "ordering": false,
            "searching": false,
            "bPaginate": false,
            "bInfo": false,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "farmasi/Farmasi_pesan_resep/get_data_by_mr?no_mr="+$('#no_mr_pesan_resep').val()+"",
                "type": "POST"
            },
            "columnDefs": [
                { 
                    "targets": [ 0 ], //last column
                    "orderable": false, //set not orderable
                },
                {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
                { "visible": true, "targets": [0] },
                { "visible": false, "targets": [4] },
            ],

        });

        $('#table-riwayat-pesan-resep tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = oTableRiwayat.row( tr );
                var data = oTableRiwayat.row( $(this).parents('tr') ).data();
                var kode_pesan_resep = data[ 4 ];
                        

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    /*data*/
                    
                    $.getJSON("farmasi/Farmasi_pesan_resep/getDetail/" + kode_pesan_resep, '', function (data) {
                        response_data = data;
                        // Open this row
                        row.child( format_html( response_data ) ).show();
                        tr.addClass('shown');
                    });
                    
                }
        } );

        $('#table-riwayat-pesan-resep tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                //achtungShowLoader();
                $(this).removeClass('selected');
                //achtungHideLoader();
            }
            else {
                //achtungShowLoader();
                oTableRiwayat.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                //achtungHideLoader();
            }
        } );

        
    });

    function format_html ( data ) {
    return data.html;
    }

    function preventDefault(e) {
    e = e || window.event;
    if (e.preventDefault)
        e.preventDefault();
    e.returnValue = false;  
    }

</script>

  <div style="margin-top:0px">
    <input type="hidden" id="no_mr_pesan_resep" value="<?php echo $no_mr?>" />
      <span style="font-size: 20px !important; font-weight: bold">10 Riwayat Resep Terakhir</span>
      <table id="table-riwayat-pesan-resep" base-url="farmasi/Farmasi_pesan_resep" class="table table-bordered table-hover">
      <thead>
          <tr>  
              <th style="background: green; color: white; font-weight: bold" width="40px"></th>
              <th style="background: green; color: white; font-weight: bold" width="40px"></th>
              <th style="background: green; color: white; font-weight: bold" width="150px">Tgl Resep</th>
              <th style="background: green; color: white; font-weight: bold">Asal Unit/Dokter</th>
              <th style="background: green; color: white; font-weight: bold"></th>
              <th style="background: green; color: white; font-weight: bold" width="200px">Keterangan</th>
              <!-- <th style="background: green; color: white; font-weight: bold">Lokasi Tebus</th> -->
              <!-- <th style="background: green; color: white; font-weight: bold">Jumlah R</th> -->
              <th style="background: green; color: white; font-weight: bold" width="80px">Status</th>          
              <!-- <th>e-Resep</th>           -->
          </tr>
      </thead>
      <tbody>
      </tbody>
      </table>
  </div>







