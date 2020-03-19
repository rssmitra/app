<script type="text/javascript">

  $(document).ready(function(){

    var no_mr = '<?php echo $no_mr?>';

    table_riwayat = $('#riwayat-table').DataTable({ 
      
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
          
          "url": "registration/Reg_pasien/get_riwayat_perjanjian?mr="+no_mr,
          
          "type": "POST"
      
      },
    
    });

  });

  
  $('input[name="flag"]').click(function (e) {
    var value = $(this).val();
    var no_mr = $('#tabs_riwayat_perjanjian_id').attr('data-id');   
    table_riwayat.ajax.url('registration/Reg_pasien/get_riwayat_perjanjian?mr='+no_mr+'&flag='+value).load();
    
  }); 

  function cetak_surat_kontrol(ID) {   
    var no_mr = $('#tabs_riwayat_perjanjian_id').attr('data-id');  
    if( no_mr == '' ){
      alert('Silahkan cari pasien terlebih dahulu !'); return false;
    }else{
      url = 'registration/Reg_pasien/surat_control?id_tc_pesanan='+ID;
      title = 'Cetak Barcode';
      width = 850;
      height = 500;
      PopupCenter(url, title, width, height);
    }

  }

</script>

<form class="form-horizontal" method="post" id="form_search" action="registration/Perjanjian_rj/find_data">

  <div class="form-group">
                  
    <label class="control-label col-sm-4">Jenis Perjanjian</label>

    <div class="col-md-8">

      <div class="radio">

          <label>

            <input name="flag" type="radio" class="ace" value="NULL" checked />

            <span class="lbl"> Rawat Jalan</span>

          </label>

          <label>

            <input name="flag" type="radio" class="ace" value="bedah" />

            <span class="lbl"> Bedah</span>

          </label>

          <label>

            <input name="flag" type="radio" class="ace" value="HD" />

            <span class="lbl"> Hemodialisa</span>

          </label>

      </div>

    </div>

  </div>

<hr class="separator">
<!-- div.dataTables_borderWrap -->
<div style="margin-top:-27px">
  <table id="riwayat-table" base-url="registration/Perjanjian_rj/get_data?no_mr=<?php echo $no_mr?>" class="table table-bordered table-hover">
    <thead>
    <tr>  
      <th width="30px" class="center"></th>
      <th></th>
      <th>Deskripsi</th>
      
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>

</form>
