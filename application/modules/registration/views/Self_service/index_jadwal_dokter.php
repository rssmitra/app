<script type="text/javascript">
  $(document).ready(function() {

  //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "paging": false,
      "searching": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "Self_service/get_data_jadwal_dokter?kode=<?php echo isset($_GET['kode'])?$_GET['kode']:''?>",
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
      background-color: #013518;
      color: white;
      font-size: 14px;
    }

    .table-custom th, td {
      padding: 5px;
      border: 1px solid #c5d0dc;
    }
    .table-custom tbody tr:hover {background-color: #e6e6e6e0;}
</style>

<div style="background: white; padding: 10px">
  <p style="font-size: 20px; font-weight: bold;text-align: center;text-shadow: 1px 1px 1px #c5c4c4; color: darkgreen"><?php echo strtoupper($nama_bagian)?></p>
  <hr class="separator">

  <div style="margin-top:-27px">
      <table id="dynamic-table" class="table-custom">
        <thead>
        <tr>  
          <!-- <th style="color: white !important" rowspan="2">No</th> -->
          <th style="color: white !important; width: 300px" rowspan="2">Nama Dokter</th>
          <th style="color: white !important" colspan="7" class="center">Hari/Jam Praktek</th>
        </tr>
        <tr style="color: white !important">
          <th style="color: white !important" class="center" width="115px">Senin</th>
          <th style="color: white !important" class="center" width="115px">Selasa</th>
          <th style="color: white !important" class="center" width="115px">Rabu</th>
          <th style="color: white !important" class="center" width="115px">Kamis</th>
          <th style="color: white !important" class="center" width="115px">Jumat</th>
          <th style="color: white !important" class="center" width="115px">Sabtu</th>
          <th style="color: white !important" class="center" width="115px">Minggu</th>        
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>

    <a href="#" class="btn btn-sm btn-success" onclick="scrollSmooth('Self_service/view_spesialis')" style="margin-bottom: 20px"><i class="fa fa-arrow-left"></i> Ganti Poli/Klinik Tujuan</a>
  </div>
</div>





