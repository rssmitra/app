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
          "url": 'rekam_medis/Rm_pasien/get_riwayat_pasien?mr='+no_mr+'',  
          "type": "POST",
      },
    
    });

  });

  function reload_table(){
   table_riwayat.ajax.reload(); //reload datatable ajax 
  }


</script>

<!-- <b> RIWAYAT KUNJUNGAN PASIEN <i class="fa fa-angle-double-right bigger-120"></i> </b> -->

<table id="riwayat-table" class="table">

   <thead>

    <tr>  
      <th style="background: green; color: white; font-weight: bold">No</th>
      <th style="background: green; color: white; font-weight: bold">#</th>
      <th style="background: green; color: white; font-weight: bold">No Registrasi</th>
      <th style="background: green; color: white; font-weight: bold">Tgl Kunjungan</th>
      <th style="background: green; color: white; font-weight: bold">Poli/Klinik</th>
      <th style="background: green; color: white; font-weight: bold">Dokter</th>
      <th style="background: green; color: white; font-weight: bold">Penjamin</th>
      <th style="background: green; color: white; font-weight: bold">Status Kunjungan</th>

    </tr>

  </thead>

  <tbody id="table_riwayat_pasien">

  </tbody>

</table>
