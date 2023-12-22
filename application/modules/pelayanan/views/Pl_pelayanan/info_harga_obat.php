
<script type="text/javascript">
  var oTable;
var base_url = $('#dt-info-harga-obat').attr('base-url'); 
var params = $('#dt-info-harga-obat').attr('data-id'); 

$(document).ready(function() {
    //initiate dataTables plugin
    oTable = $('#dt-info-harga-obat').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "pageLength": 25,
      "scrollY": "600px",
      "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": base_url+'?'+params,
          "type": "POST"
      },

    });

    $('#dt-info-harga-obat tbody').on('click', 'td.details-control', function () {
        var url_detail = $('#dt-info-harga-obat').attr('url-detail');
        preventDefault();
        var tr = $(this).closest('tr');
        var row = oTable.row( tr );
        var data = oTable.row( $(this).parents('tr') ).data();
        var kode_primary = data[ 2 ];                  
        console.log(data);
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/            
            $.getJSON( url_detail + "/" + kode_primary + "?" +params, '' , function (data) {
                response_data = data;
                // Open this row
                row.child( format_html( response_data ) ).show();
                tr.addClass('shown');
            });
        }
        
    } );

    $('#dt-info-harga-obat tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
      

    $('#btn_reset_data').click(function (e) {
        e.preventDefault();
        reset_table();
        $('#form_search')[0].reset();
    });


});

$('#btn_search_data').click(function (e) {
    var url_search = $('#form_search').attr('action');
    e.preventDefault();
    $.ajax({
    url: url_search,
    type: "post",
    data: $('#form_search').serialize(),
    dataType: "json",
    success: function(data) {
      console.log(data.data);
      find_data_reload(data);
    }
  });
 });

function format_html ( data ) {
  return data.html;
}

function find_data_reload(result){

    oTable.ajax.url(base_url+'/get_data?'+result.data).load();
    $("html, body").animate({ scrollTop: "400px" });

}

function reset_table(){
    oTable.ajax.url(base_url+'/get_data?'+params).load();
    $("html, body").animate({ scrollDown: "400px" });

}

function reload_table(){
   oTable.ajax.reload(); //reload datatable ajax 
}  

</script>
<hr class="separator">
<div class="row">
  <div class="col-xs-12">
    <p style="text-align: center; font-size: 14px">
      <b>INFORMASI HARGA OBAT<br>RUMAH SAKIT SETIA MITRA</b>
    </p>
    <br>
    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan_ri/find_data">
        
        <div style="margin-top:-27px">

          <table id="dt-info-harga-obat" base-url="pelayanan/Pl_pelayanan/get_data_harga_obat" data-id="flag=medis" url-detail="farmasi/Harga_jual_obat/show_detail" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th width="30px">No</th>
                <th width="100px">Kode Barang</th>
                <th>Nama Barang</th>
                <th width="150px">Rasio Satuan</th>
                <th width="100px">Harga Jual</th>
                <th width="90px">Status</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

        </div>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->


<script src="<?php echo base_url().'assets/js/custom/als_datatable_with_detail_custom_url.js'?>"></script>



