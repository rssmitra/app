<script type="text/javascript">
  
  $(document).ready(function(){

    oTable = $('#dt_selected_brg').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bLengthChange": false,
      "pageLength": 5,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "purchasing/permintaan/Req_selected_detail_brg/get_data?flag="+$('#flag_string').val()+"&key="+$('#keyword').val()+"&search_by="+$('#search_by').val()+"",
          "type": "POST"
      },

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

      
    $('#btn_search_data').click(function (e) {
        e.preventDefault();
        $.ajax({
        url: 'pelayanan/Pl_pelayanan_ri/find_data',
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        success: function(data) {
          achtungHideLoader();
          find_data_reload(data,'pelayanan/Pl_pelayanan_ri');
        }
      });
    });

  })
</script>
<!-- input hidden -->
<input type="hidden" name="flag_string" id="flag_string" value="<?php echo $flag?>">
<input type="hidden" name="search_by" id="search_by" value="<?php echo $search_by?>">
<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword?>">
<div class="row">
  <div class="col-md-12">
    <p style="font-size:11px">
      <?php echo 'Pencarian berdasarkan parameter<br>'; ?>
      <?php echo print_r($_POST); ?>
    </p>
    <table id="dt_selected_brg" class="table table-striped">
      <thead>
        <tr style="background-color:#428bca">
          <td width="100px" class="center">Image</td>
          <td>Nama Barang</td>
          <td align="right">Harga Satuan<br><span style="font-size:11px">(Harga Pembelian Terakhir)</span></td>
          <td class="center">Stok Akhir</td>
          <td class="center">Satuan Kecil</td>
          <td class="center">Rasio</td>
          <td class="center">Satuan Besar</td>
          <td class="center">Jumlah Permintaan</td>
          <td width="50px">&nbsp;</td>
        </tr>
      </thead>
    </table>
  </div>
</div>
