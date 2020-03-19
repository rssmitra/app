<script type="text/javascript">

  $(document).ready(function(){

    var no_mr = '<?php echo $no_mr?>';

    table_riwayat = $('#riwayat-table').DataTable({ 
      
      "processing": true, //Feature control the processing indicator.
      
      "serverSide": true, //Feature control DataTables' server-side processing mode.
            
      "ordering": false,
      
      // Load data for the table's content from an Ajax source
      
      "ajax": {
          
          "url": "registration/Reg_pasien/get_riwayat_perjanjian?mr="+no_mr,
          
          "type": "POST"
      
      },
    
    });

    

  });

</script>

<table id="riwayat-table" class="table table-bordered table-hover">

   <thead>

    <tr>  
      <th></th>
      <th>Kode</th>
      <th>Tanggal</th>
      <th>Tujuan Poli/Klinik</th>
      <th>Nama Dokter</th>
      <th>Jam Praktek</th>
      <th>Status</th>
      
    </tr>

  </thead>

  <tbody id="table_riwayat_pasien">

  </tbody>

</table>