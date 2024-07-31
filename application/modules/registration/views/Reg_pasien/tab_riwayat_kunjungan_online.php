<script type="text/javascript">

  $(document).ready(function(){

    var no_mr = '<?php echo $no_mr?>';
    var kode_bagian = '<?php echo (isset($kode_bagian))?$kode_bagian:''?>';
    var no_reg = '<?php echo (isset($no_reg))?$no_reg:''?>';
    var tujuan = '<?php echo (isset($tujuan))?$tujuan:''?>';

    var url = 'registration/Reg_pasien/get_riwayat_pasien_online';

    table_riwayat_pasien_online = $('#riwayat-table').DataTable({ 
      
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
          
          "url": url,
          
          "type": "POST"
      
      },
    
    });

    $('#btn_search_data_pasien_online').click(function (e) {
      e.preventDefault();
      table_riwayat_pasien_online.ajax.url(url+'?search_by=nama_pasien&keyword='+$('#keyword_pasien_online').val()).load();
    });

    $( "#keyword_pasien_online" )    
      .keypress(function(event) {       
        var keycode =(event.keyCode?event.keyCode:event.which);    
        if(keycode ==13){     
          event.preventDefault();    
          if($(this).valid()){          
            $('#btn_search_data_pasien_online').click();    
          }          
          return false;   
        }    
    });   


  });

  function reload_table(){
   table_riwayat_pasien_online.ajax.reload(); //reload datatable ajax 
  }

</script>

<center><b> DATA PASIEN YANG BELUM MELAKUKAN KONFIRMASI FINGER PRINT DAN BELUM DIBUATKAN SEP </b></center>
<hr>
<form class="form-horizontal" method="post" id="form-search-pasien-online" action="#" enctype="multipart/form-data" autocomplete="off" >      
  
  <div>
      <label>Masukan keyword pencarian : </label><br>
      <div class="input-group">
        <span class="input-group-addon">
          <i class="ace-icon fa fa-check"></i>
        </span>

        <input type="text" id="keyword_pasien_online" class="form-control search-query" placeholder="Type your query">
        <span class="input-group-btn">
          <button type="button" id="btn_search_data_pasien_online" class="btn btn-purple btn-sm">
            <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
            Search
          </button>
        </span>
      </div>
  </div>

  <table id="riwayat-table" class="table">

    <thead>

      <tr>  
        
        <th>No</th>

        <th>Deskripsi</th>

      </tr>

    </thead>

    <tbody>

    </tbody>

  </table>

</form>