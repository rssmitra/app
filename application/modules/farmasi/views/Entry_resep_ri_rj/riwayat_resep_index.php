<script>
$(document).ready(function(){
    
    table = $('#riwayat-pesan-resep').DataTable( {
        "processing": true, 
        "serverSide": true,
        "bInfo": true,
        "bPaginate": true,
        "searching": true,
        "bSort": false,
        "pageLength": 25,
        "ajax": {
            "url": "farmasi/Etiket_obat/riwayat_resep?flag=<?php echo $flag?>&profit=<?php echo $profit?>",
            "type": "POST"
        },
    }); 
    
    $('#btn_search_data').click(function (e) {
        var url_search = 'templates/References/find_data';
        e.preventDefault();
        $.ajax({
        url: url_search,
        type: "post",
        data: {search_by: $('#search_by').val(), keyword: $('#keyword').val() },
        dataType: "json",
        success: function(data) {
          console.log(data.data);
          find_data_reload(data);
        }
      });
    });

    $('#btn_reset_data').click(function (e) {
      table.ajax.url('farmasi/Etiket_obat/riwayat_resep?flag=<?php echo $flag?>&profit=<?php echo $profit?>').load();
        $("html, body").animate({ scrollTop: "400px" });
    });

    $( "#keyword" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          $('#btn_search_data').click();
          return false;       
        }
    });

    function find_data_reload(result){

        table.ajax.url('farmasi/Etiket_obat/riwayat_resep?flag=<?php echo $flag?>&profit=<?php echo $profit?>&'+result.data).load();
        $("html, body").animate({ scrollTop: "400px" });

    }

})
</script>

<div class="col-md-12">
  <p class="center" style="margin-top: 10px">
    <span style="font-size: 16px; font-weight: bold"><?php echo strtoupper($title)?></span><br>
    <span>Data yang ditampilkan dibawah ini ada data transaksi 1 bulan terkahir</span>
  </p>
  
  <div class="form-group">
      <label class="control-label col-sm-2">Pencarian berdasarkan</label>
      <div class="col-md-2">
        <select name="search_by" id="search_by" class="form-control">
          <option value="kode_trans_far">Kode Transaksi</option>
          <option value="nama_pasien">Nama Pasien</option>
        </select>
      </div>

      <label class="control-label col-sm-1">Keyword</label>
      <div class="col-md-2">
        <input type="text" class="form-control" name="keyword" id="keyword">
      </div>
      
      <div class="col-md-5" style="margin-left: -10px">
        <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
          <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
          Search
        </a>
        <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
          <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
          Reset
        </a>
      </div>
  </div>
</div>

<div class="clearfix"></div>
<!-- div.dataTables_borderWrap -->
<div class="col-md-12">
  <div>
    <table id="riwayat-pesan-resep" base-url="" class="table table-bordered table-hover">
      <thead>
        <tr>  
          <th class="center">No</th>
          <th>Kode</th>
          <th>Tgl Pesan</th>
          <th>No Mr</th>
          <th>Nama Pasien</th>
          <th>Nama Dokter</th>
          <th>Pelayanan</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>




