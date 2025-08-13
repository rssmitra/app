<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>
<script src="<?php echo base_url()?>assets/js/custom/chart.js"></script>
<script type="text/javascript">

$(document).ready(function() {
  
  tbl_observasi_harian_keperawatan = $('#tbl_observasi_harian_keperawatan').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": true,
      "bInfo": false,
      "pageLength": 5,
      "dom": 'rtip',
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_row_data_observasi_ri?no_kunjungan="+$('#no_kunjungan').val()+"&flag=btn_work_day",
          "type": "POST"
      },

  });

  dt_hemodinamik = $('#dt_hemodinamik').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": true,
      "bInfo": false,
      "pageLength": 5,
      "dom": 'rtip',
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_row_data_observasi_ri?no_kunjungan="+$('#no_kunjungan').val()+"&flag=btn_hemodinamik",
          "type": "POST"
      },
  });

  dt_montoring_perkembangan_pasien = $('#dt_montoring_perkembangan_pasien').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": true,
      "bInfo": false,
      "pageLength": 5,
      "dom": 'rtip',
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_row_data_observasi_ri?no_kunjungan="+$('#no_kunjungan').val()+"&flag=btn_monitor_perkembangan_pasien",
          "type": "POST"
      },
  });

  dt_deskripsi_lainnya = $('#dt_deskripsi_lainnya').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": true,
      "bInfo": false,
      "pageLength": 5,
      "dom": 'rtip',
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_row_data_observasi_ri?no_kunjungan="+$('#no_kunjungan').val()+"&flag=btn_deskripsi_lainnya",
          "type": "POST"
      },
  });

  dt_keseimbangan_cairan = $('#dt_keseimbangan_cairan').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": true,
      "bInfo": false,
      "pageLength": 5,
      "dom": 'rtip',
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_row_data_observasi_ri?no_kunjungan="+$('#no_kunjungan').val()+"&flag=btn_keseimbangan_cairan",
          "type": "POST"
      },
  });

  tbl_program_pemberian_obat = $('#tbl_program_pemberian_obat').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": true,
      "bInfo": false,
      "pageLength": 5,
      "dom": 'rtip',
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_row_data_observasi_ri?no_kunjungan="+$('#no_kunjungan').val()+"&flag=btn_program_pemberian_obat",
          "type": "POST"
      },
  });

  // load grafik hemodinamik
  load_graph();
  
  
});

function load_graph(){
  $('#grafik_content').html('Loading...');
  $.getJSON('pelayanan/Pl_pelayanan_ri/get_content_chart_monitoring', {no_kunjungan : $('#no_kunjungan').val()}, function(response_data) {
    html = '';
    $.each(response_data, function (i, o) {
      html += '<div class="col-sm-'+o.col_size+'"><div id="'+o.nameid+'"></div></div>';
      if(o.style=='line_hemodinamik'){
        GraphLineStyleHemodinamik(o.mod, o.nameid, o.url);
      }
    });
    
    $('#grafik_content').html(html);
  });
}

function GraphLineStyleHemodinamik(id, nameid, url){

  //use getJSON to get the dynamic data via AJAX call
  $.getJSON(url, {id: id}, function(chartData) {
  // Set custom colors for specific series names
  var customColors = {
    'Sistolik': '#000000', // black
    'Diastolik': '#464545ff', // black
    'Nadi': '#FF0000',           // red
    'Suhu': '#0000FF',          // blue
    'Spo2': '#008000'           // green
  };

  // Map colors to series
  chartData.series = chartData.series.map(function(series) {
    if (customColors[series.name]) {
    series.color = customColors[series.name];
    }
    return series;
  });

  $('#'+nameid).highcharts({

    title: {
      text: chartData.title,
      x: -20 //center
    },
    subtitle: {
      text: chartData.subtitle,
      x: -20
    },
    xAxis: chartData.xAxis,
    yAxis: {
      title: {
        text: 'Total'
      },
      plotLines: [{
        value: 0,
        width: 1,
        color: '#808080'
      }]
    },
    tooltip: {
      valueSuffix: ''
    },
    legend: {
      layout: 'horizontal',
      align: 'center',
      verticalAlign: 'bottom',
      borderWidth: 0
    },
    series: chartData.series

  });

  });
}

// Hide action buttons in first column of all tables (view only)
</script>
<style>
  /* Hide action buttons in first column of all tables, but show the cell for row number */
  #tbl_observasi_harian_keperawatan tbody td:first-child button,
  #tbl_observasi_harian_keperawatan tbody td:first-child a,
  #tbl_program_pemberian_obat tbody td:first-child button,
  #tbl_program_pemberian_obat tbody td:first-child a,
  #dt_hemodinamik tbody td:first-child button,
  #dt_hemodinamik tbody td:first-child a,
  #dt_montoring_perkembangan_pasien tbody td:first-child button,
  #dt_montoring_perkembangan_pasien tbody td:first-child a,
  #dt_deskripsi_lainnya tbody td:first-child button,
  #dt_deskripsi_lainnya tbody td:first-child a,
  #dt_keseimbangan_cairan tbody td:first-child button,
  #dt_keseimbangan_cairan tbody td:first-child a {
    display: none !important;
  }
</style>
<div class="row">
  <div class="col-md-12">

    <center><span style="font-weight: bold; font-size: 20px !important">OBSERVASI HARIAN KEPERAWATAN PASIEN</span></center>
    <br>
    <!-- FORM OBSERVASI HARIAN KEPERAWATAN -->
    <div class="row">
      <div class="col-md-12">

        <h3 class="header smaller lighter blue padding-10" style="background: #0d5280; font-size: 14px !important; font-weight: bold; color: white !important; padding: 5px;">
          RENCANA KEPERAWATAN HARIAN PASIEN
        </h3>

        <div class="col-md-12 no-padding">
          <table class="table" style="margin-top: 10px" id="tbl_observasi_harian_keperawatan">
            <thead>
              <tr style="background:#e7e7e7; color: black">
                <th style="background:#e7e7e7; color: black" rowspan="2" width="50px">#</th>
                <th style="background:#e7e7e7; color: black; width: 120px" rowspan="2" class="center">Tanggal</th>
                <th style="background:#e7e7e7; color: black" colspan="2" class="center">Intake</th>
                <th style="background:#e7e7e7; color: black" rowspan="2" class="center" style="vertical-align: middle; width: 200px">Polavent</th>
                <th style="background:#e7e7e7; color: black" colspan="2" class="center">Obat</th>
                <th style="background:#e7e7e7; color: black" rowspan="2" class="center" style="vertical-align: middle; width: 200px">Lain-lain (Alergi)</th>
                <th style="background:#e7e7e7; color: black" rowspan="2" class="center" style="vertical-align: middle; width: 200px">Catatan Dokter</th>
              </tr>
              <tr style="background:#e7e7e7; color: black">
                <th class="center" style="width: 130px; background:#e7e7e7; color: black">enteral</th>
                <th class="center" style="width: 130px; background:#e7e7e7; color: black">parenteral</th>
                <th class="center" style="width: 130px; background:#e7e7e7; color: black">enteral/lain-lain</th>
                <th class="center" style="width: 130px; background:#e7e7e7; color: black">parenteral</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

      </div>
    </div>
    <!-- END FORM OBSERVASI HARIAN KEPERAWATAN -->

    <!-- FORM PROGRAM PEMBERIAN OBAT CAIRAN DLL -->
    <div class="row">
      <div class="col-md-12">

        <h3 class="header smaller lighter blue padding-10" style="background: #0d5280; font-size: 14px !important; font-weight: bold; color: white !important; padding: 5px;">
          PROGRAM PEMBERIAN OBAT/CAIRAN/NUTRISI
        </h3>
        
        <div class="col-md-12 no-padding">
          <table class="table" style="margin-top: 10px" id="tbl_program_pemberian_obat">
            <thead>
              <tr style="background:#e7e7e7; color: black">
                <th style="background:#e7e7e7; color: black" width="50px">#</th>
                <th style="background:#e7e7e7; color: black; width: 50px" class="center">Tanggal Jam</th>
                <th style="background:#e7e7e7; color: black; width: 50px" class="center">Petugas</th>
                <th style="background:#e7e7e7; color: black; width: 250px" class="left">Cairan Infus</th>
                <th style="background:#e7e7e7; color: black; width: 250px" class="left">Nutrisi Enteral</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

      </div>
    </div>
    <!-- END FORM PROGRAM PEMBERIAN OBAT CAIRAN DLL -->

    <!-- HEMODINAMIK -->
    <div class="row" style="padding: 10px !important">
      <div class="col-md-12 no-padding">
        <h3 class="header smaller lighter blue padding-10" style="background: #0d5280; font-size: 14px !important; font-weight: bold; color: white !important; padding: 5px;">
            HEMODINAMIK
          </h3>
          
        <div class="col-md-12">

          <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab2">
              <li class="active">
                <a data-toggle="tab" href="#grafik_monitoring_tab">
                  <i class="green ace-icon fa fa-bar-chart-o bigger-120"></i>
                  Grafik Monitoring
                </a>
              </li>

              <li>
                <a data-toggle="tab" href="#tabel_monitoring_tab">
                  <i class="green ace-icon fa fa-list bigger-120"></i> Data Tabel
                </a>
              </li>
            </ul>

            <div class="tab-content">

              <div id="grafik_monitoring_tab" class="tab-pane fade in active">
                <div class="row no-padding" id="grafik_content">
                  <!-- Content will be loaded here via AJAX -->
                    
                </div>
                
              </div>

              <div id="tabel_monitoring_tab" class="tab-pane fade">
                <p style="font-size: 14px; font-weight: bold">H E M O D I N A M I K</p>
                <table class="table" id="dt_hemodinamik">
                  <thead>
                    <tr style="background:#e7e7e7; color: black">
                      <th style="width: 70px; background:#e7e7e7; color: black">#</th>
                      <th style="width: 100px; background:#e7e7e7; color: black" class="center">Tanggal Jam</th>
                      <th style="width: 70px; background:#e7e7e7; color: black" class="center">Petugas</th>
                      <th style="width: 50px; background:#e7e7e7; color: black" class="center">Sistolik<br>(mmHg)</th>
                      <th style="width: 50px; background:#e7e7e7; color: black" class="center">Diastolik<br>(mmHg)</th>
                      <th style="width: 50px; background:#e7e7e7; color: black" class="center">Nadi<br>(bpm)</th>
                      <th style="width: 50px; background:#e7e7e7; color: black" class="center">Suhu<br>(&#x2103;)</th>
                      <th style="width: 100px; background:#e7e7e7; color: black" class="center">Catatan</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                  </tbody>
              </table>

              </div>
              
            </div>
          </div>
          
        </div>
      </div>
    </div>
    <!-- END HEMODINAMIK -->
    
    <!-- DATA MONITORING PERKEMBANGAN PASIEN -->
    <div class="row" style="padding: 10px !important">
      <div class="col-md-12 no-padding">
        <h3 class="header smaller lighter blue padding-10" style="background: #0d5280; font-size: 14px !important; font-weight: bold; color: white !important; padding: 5px;">
          DATA MONITORING PERKEMBANGAN PASIEN
        </h3>
        

        <table class="table" id="dt_montoring_perkembangan_pasien">
          <thead>
          <tr style="background:#e7e7e7; color: black">
            <th rowspan="2" width="70px" style="background:#e7e7e7; color: black">#</th>
            <th rowspan="2" width="80px" class="center" style="background:#e7e7e7; color: black">Tanggal/Jam</th>
            <th colspan="4" class="center">SSP</th>
            <th colspan="2" class="center">MOTORIK</th>
            <th colspan="3" class="center">CAIRAN MASUK</th>
            <th colspan="3" class="center">CAIRAN KELUAR</th>
            <th colspan="5" class="center">RESPIRASI</th>
            <!-- <th colspan="6" class="center">AGD</th> -->
            <th rowspan="2" class="center" style="background:#e7e7e7; color: black">CVP</th>
            <th rowspan="2" class="center" style="background:#e7e7e7; color: black">CATATAN</th>
          </tr>
          <tr style="background:#e7e7e7; color: black">
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">Kes.</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">Pupil</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">Ref.</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">GCS</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">Sup.</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">Inf.</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">Ent.</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">Par.</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">Train.</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">Urin</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">NGT</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">BAB</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">Pola</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">TV</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">RR</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">FO2%</th>
            <th class="center" style="font-size: 10px; width: 50px; background:#e7e7e7; color: black">Peep</th>
            <!-- <th class="center" style="font-size: 10px; background:#e7e7e7; color: black">pH</th>
            <th class="center" style="font-size: 10px; background:#e7e7e7; color: black">pCO2</th>
            <th class="center" style="font-size: 10px; background:#e7e7e7; color: black">pO2</th>
            <th class="center" style="font-size: 10px; background:#e7e7e7; color: black">BE</th>
            <th class="center" style="font-size: 10px; background:#e7e7e7; color: black">HCO2</th>
            <th class="center" style="font-size: 10px; background:#e7e7e7; color: black">Sat</th> -->
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
    <!-- DATA MONITORING PERKEMBANGAN PASIEN -->








