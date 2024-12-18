<script type="text/javascript">
  $(document).ready(function() {

  //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "paging": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "information/Regon_info_jadwal_dr/get_data",
          "type": "POST"
      },

    });

    $('#dynamic-table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );


});

</script>
<style type="text/css">
    table{
      width: 100% !important;
      font-size: 12px;
    }
    .table-custom thead {
      background-color: #14506b;
      color: white;
    }

    .table-custom th, td {
      padding: 10px;
      border: 1px solid #c5d0dc;
    }
    .table-custom tbody tr:hover {background-color: #e6e6e6e0;}
</style>

<div class="clearfix" style="margin-bottom:-5px">
  <?php echo $this->authuser->show_button('information/regon_info_jadwal_dr','C','',1)?>
  <?php echo $this->authuser->show_button('information/regon_info_jadwal_dr','D','',8)?>
</div>
<hr class="separator">


<div style="margin-top:-27px">
    <table id="dynamic-table" base-url="information/regon_info_jadwal_dr" class="table-custom">
      <thead>
      <tr>  
        <th style="color: white !important" width="30px" class="center" rowspan="2"></th>
        <th style="color: white !important" rowspan="2" width="180px">Action</th>
        <th style="color: white !important" rowspan="2">Nama Dokter</th>
        <th style="color: white !important" rowspan="2">Spesialis</th>
        <th style="color: white !important" colspan="7" class="center">Hari/Jam Praktek</th>
      </tr>
      <tr style="color: white !important">
        <th style="color: white !important" class="center" width="65px">Senin</th>
        <th style="color: white !important" class="center" width="65px">Selasa</th>
        <th style="color: white !important" class="center" width="65px">Rabu</th>
        <th style="color: white !important" class="center" width="65px">Kamis</th>
        <th style="color: white !important" class="center" width="65px">Jumat</th>
        <th style="color: white !important" class="center" width="65px">Sabtu</th>
        <th style="color: white !important" class="center" width="65px">Minggu</th>        
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>





