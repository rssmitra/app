<script type="text/javascript">
  $(document).ready(function() {

  //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "paging": false,
      "searching": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "Self_service/get_data_jadwal_dokter",
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


  function setAppointment(jd_id){
    $('#globalModalView').toggle('close');
    scrollSmooth('Self_service/form_perjanjian/'+jd_id+'');
  }

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

<p style="font-size: 14px; font-style: italic; font-weight: bold">Informasi Jadwal Dokter dan Perjanjian Pasien</p>
<hr class="separator">

<div style="margin-top:-27px">
    <table id="dynamic-table" base-url="information/regon_info_jadwal_dr" class="table-custom">
      <thead>
      <tr>  
        <th style="color: white !important" rowspan="2">No</th>
        <th style="color: white !important" rowspan="2">Nama Dokter</th>
        <th style="color: white !important" rowspan="2">Poli/Klinik Spesialis</th>
        <th style="color: white !important" colspan="7" class="center">Hari/Jam Praktek</th>
      </tr>
      <tr style="color: white !important">
        <th style="color: white !important" class="center" width="105px">Senin</th>
        <th style="color: white !important" class="center" width="105px">Selasa</th>
        <th style="color: white !important" class="center" width="105px">Rabu</th>
        <th style="color: white !important" class="center" width="105px">Kamis</th>
        <th style="color: white !important" class="center" width="105px">Jumat</th>
        <th style="color: white !important" class="center" width="105px">Sabtu</th>
        <th style="color: white !important" class="center" width="105px">Minggu</th>        
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>





