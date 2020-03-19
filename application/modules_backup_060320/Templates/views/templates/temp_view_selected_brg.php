<script type="text/javascript">
  
  $(document).ready(function(){

    oTable = $('#dt_selected_brg').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bLengthChange": false,
      "paging": false,
      // "pageLength": 5,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "purchasing/permintaan/Req_selected_detail_brg/get_data?flag="+$('#flag_string').val()+"&key="+$('#keyword').val()+"&search_by="+$('#search_by').val()+"",
          "type": "POST"
      },

      "columnDefs": [
          { "aTargets" : [ 10 ], "mData" : 10, "sClass":  "btn-add-to-cart"}, 
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
        var value_input = $('#input_'+kode_brg+'').val();
        var rasio = $('#input_rasio_'+kode_brg+'').val();
        var satuan = $('#select_satuan_'+kode_brg+'').val();
        var stok_akhir = $('#stok_akhir_'+kode_brg+'').val();
        var keterangan = $('#keterangan_'+kode_brg+'').val();
        if( value_input == '' || value_input == 0){
          alert('Masukan jumlah permintaan !');
          return false;
        }
        // here process
        var post_data = {
          id_tc_permohonan : $('#id_tc_permohonan').val(),
          kode_brg : kode_brg,
          jumlah_besar : value_input,
          satuan_besar : satuan,
          rasio : rasio,
          stok_akhir : stok_akhir,
          keterangan : keterangan,
          flag : $('#flag_string').val(),
        }

        $.ajax({
          url: 'purchasing/permintaan/Req_selected_detail_brg/process',
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
            $('#total_brg').text( data.total_brg );
            $('#div_daftar_permintaan_brg').hide();
          }
        });
        console.log( value_input );
    } );

  })
</script>
<!-- input hidden -->
<input type="hidden" name="flag_string" id="flag_string" value="<?php echo $flag?>">
<input type="hidden" name="search_by" id="search_by" value="<?php echo $search_by?>">
<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword?>">
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
          <td width="200px">Nama Barang</td>
          <td align="center" width="120px">Harga Satuan<br><span style="font-size:11px">(Harga Pembelian Terakhir)</span></td>
          <td class="center">Stok Akhir</td>
          <td class="center" width="180px">Jumlah Permintaan</td>
          <td class="center">Rasio</td>
          <td class="center">Hasil Konversi<br>Jumlah Permintaan</td>
          <td class="center">Keterangan</td>
          <td class="center">&nbsp;</td>
        </tr>
      </thead>
    </table>
  </div>
</div>
