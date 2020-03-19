<script type="text/javascript">
  
  $(document).ready(function(){

    oTable = $('#dt_selected_brg').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bLengthChange": false,
      "pageLength": 10,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "inventory/stok/Req_selected_detail_brg_depo/get_data?flag="+$('#flag_string').val()+"&key="+$('#keyword').val()+"&search_by="+$('#search_by').val()+"&kode_bagian="+$('#kode_bagian').val()+"",
          "type": "POST"
      },

      "columnDefs": [
          { "aTargets" : [ 8 ], "mData" : 8, "sClass":  "btn-add-to-cart"}, 
          { "targets": [ 0 ], "visible": false },
      ],

    });

    $('#dt_selected_brg tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    $('#dt_selected_brg tbody').on( 'click', 'td.btn-add-to-cart', function (e) {
        preventDefault();
        var tr = $(this).closest('tr');
        var row = oTable.row( tr );
        var data = oTable.row( $(this).parents('tr') ).data();
        var kode_brg = data[ 0 ]; 
        var input_value = $('#input_'+kode_brg+'').val();
        if( input_value == '' || input_value == 0){
          alert('Masukan stok minimum !');
          return false;
        }
        // here process
        var post_data = {
          kode_brg : kode_brg,
          input_value : input_value,
          kode_bagian : $('#kode_bagian').val(),
          flag : $('#flag_string').val(),
        }

        $.ajax({
          url: 'inventory/stok/Req_selected_detail_brg_depo/process',
          type: "post",
          data: post_data,
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          success: function(data) {
            achtungHideLoader();
            // response after process
            preventDefault();
            oTable.ajax.reload();
          }
        });
        console.log( stok_minimum );
    } );

  })
</script>
<!-- input hidden -->
<input type="hidden" name="flag_string" id="flag_string" value="<?php echo $flag?>">
<input type="hidden" name="search_by" id="search_by" value="<?php echo $search_by?>">
<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword?>">
<input type="hidden" name="kode_bagian" id="kode_bagian" value="<?php echo $kode_bagian?>">
<div class="row">
  <div class="col-md-12">
    <p style="font-size:11px">
      <?php echo print_r($_POST); ?>
    </p>
    <table id="dt_selected_brg" class="table table-striped table-bordered">
      <thead>
        <tr style="background-color:#428bca">
          <td width="30px" class="center">&nbsp;</td>
          <td width="30px" class="center">No</td>
          <td width="100px" class="center">Image</td>
          <td>Nama Barang</td>
          <td align="right" width="160px">Harga Satuan<br><span style="font-size:11px">(Harga Pembelian Terakhir)</span></td>
          <td class="center" width="200px">Stok Minimum</td>
          <td class="center" width="120px">Rasio</td>
          <td class="center" width="70px">Status</td>
          <td class="center" width="100px">&nbsp;</td>
        </tr>
      </thead>
    </table>
  </div>
</div>
