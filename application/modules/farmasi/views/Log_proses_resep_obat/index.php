<script src="<?php echo base_url()?>assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>

  jQuery(function($) {

    $('.date-picker').datepicker({
      autoclose: true,
      todayHighlight: true
    })
    //show datepicker when clicking on the icon
    .next().on(ace.click_event, function(){
      $(this).prev().focus();
    });
  });

  // Reload semua tabel setiap 1 menit
  function reloadAllTables() {
    oTableResepDiterima.ajax.reload();
    oTableProsesRacikan.ajax.reload();
    oTableProsesEtiket.ajax.reload();
    oTableSiapDiambil.ajax.reload();
    oTableSelesai.ajax.reload();
  }
  
  $(document).ready(function() {

    // setInterval(reloadAllTables, 30000);

    //initiate dataTables plugin
    oTableResepDiterima = $('#tbl_resep_diterima').DataTable({ 
          
      "processing": false, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "bPaginate": false,
      "bInfo": false,
      "lengthChange" : false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#tbl_resep_diterima').attr('base-url')+"&tanggal="+$('#tanggal').val()+"",
          "type": "POST"
      },
      drawCallback: function( settings ) {
           var response = settings.json;
           $('#total_resep_diterima').html(response.count_data);
      },
      "columnDefs": [
          { 
            "targets": [ 0 ], 
            "orderable": false,
          },
          {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
          { "visible": false, "targets": [1] },
      ],

    });

    $('#tbl_resep_diterima tbody').on('click', 'td.details-control', function (e) {
        var url_detail = 'farmasi/Farmasi_pesan_resep/getDetail';
        e.preventDefault();
        var tr = $(this).closest('tr');
        var row = oTableResepDiterima.row( tr );
        var data = oTableResepDiterima.row( $(this).parents('tr') ).data();
        var kode_primary = data[ 1 ];                  
        console.log(data);
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/            
            $.getJSON( url_detail + "/" + kode_primary + "?flag=All", '' , function (data) {
                response_data = data;
                // Open this row
                row.child( format_html( response_data ) ).show();
                tr.addClass('shown');
            });
        }
        
    } );

    $('#tbl_resep_diterima tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTableResepDiterima.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    //initiate dataTables plugin
    oTableProsesRacikan = $('#tbl_proses_racikan').DataTable({ 
          
      "processing": false, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "bPaginate": false,
      "bInfo": false,
      "lengthChange" : false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#tbl_proses_racikan').attr('base-url')+"&tanggal="+$('#tanggal').val()+"",
          "type": "POST"
      },
      drawCallback: function( settings ) {
           var response = settings.json;
           $('#total_proses_racikan').html(response.count_data);
      },
      "columnDefs": [
          { 
            "targets": [ 0 ], 
            "orderable": false,
          },
          {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
          { "visible": false, "targets": [1] },
      ],

    });

    $('#tbl_proses_racikan tbody').on('click', 'td.details-control', function (e) {
        var url_detail = 'farmasi/Farmasi_pesan_resep/getDetail';
        e.preventDefault();
        var tr = $(this).closest('tr');
        var row = oTableProsesRacikan.row( tr );
        var data = oTableProsesRacikan.row( $(this).parents('tr') ).data();
        var kode_primary = data[ 1 ];                  
        console.log(data);
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/            
            $.getJSON( url_detail + "/" + kode_primary + "?flag=All", '' , function (data) {
                response_data = data;
                // Open this row
                row.child( format_html( response_data ) ).show();
                tr.addClass('shown');
            });
        }
        
    } );

    $('#tbl_proses_racikan tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTableProsesRacikan.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    //initiate dataTables plugin
    oTableProsesEtiket = $('#tbl_proses_etiket').DataTable({ 
          
      "processing": false, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "bPaginate": false,
      "bInfo": false,
      "lengthChange" : false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#tbl_proses_etiket').attr('base-url')+"&tanggal="+$('#tanggal').val()+"",
          "type": "POST"
      },
      drawCallback: function( settings ) {
           var response = settings.json;
           $('#total_proses_etiket').html(response.count_data);
      },
      "columnDefs": [
          { 
            "targets": [ 0 ], 
            "orderable": false,
          },
          {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
          { "visible": false, "targets": [1] },
      ],

    });

    $('#tbl_proses_etiket tbody').on('click', 'td.details-control', function (e) {
        var url_detail = 'farmasi/Farmasi_pesan_resep/getDetail';
        e.preventDefault();
        var tr = $(this).closest('tr');
        var row = oTableProsesEtiket.row( tr );
        var data = oTableProsesEtiket.row( $(this).parents('tr') ).data();
        var kode_primary = data[ 1 ];                  
        console.log(data);
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/            
            $.getJSON( url_detail + "/" + kode_primary + "?flag=All", '' , function (data) {
                response_data = data;
                // Open this row
                row.child( format_html( response_data ) ).show();
                tr.addClass('shown');
            });
        }
        
    } );

    $('#tbl_proses_etiket tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTableProsesEtiket.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    //initiate dataTables plugin
    oTableSiapDiambil = $('#tbl_siap_diambil').DataTable({ 
          
      "processing": false, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "bPaginate": false,
      "bInfo": false,
      "lengthChange" : false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#tbl_siap_diambil').attr('base-url')+"&tanggal="+$('#tanggal').val()+"",
          "type": "POST"
      },
      drawCallback: function( settings ) {
           var response = settings.json;
           $('#total_siap_diambil').html(response.count_data);
           $('#avg-tat-info').html(response.tat);
      },
      "columnDefs": [
          { 
            "targets": [ 0 ], 
            "orderable": false,
          },
          {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
          { "visible": false, "targets": [1] },
      ],

    });

    $('#tbl_siap_diambil tbody').on('click', 'td.details-control', function (e) {
        var url_detail = 'farmasi/Farmasi_pesan_resep/getDetail';
        e.preventDefault();
        var tr = $(this).closest('tr');
        var row = oTableSiapDiambil.row( tr );
        var data = oTableSiapDiambil.row( $(this).parents('tr') ).data();
        var kode_primary = data[ 1 ];                  
        console.log(data);
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/            
            $.getJSON( url_detail + "/" + kode_primary + "?flag=All", '' , function (data) {
                response_data = data;
                // Open this row
                row.child( format_html( response_data ) ).show();
                tr.addClass('shown');
            });
        }
        
    } );

    $('#tbl_siap_diambil tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTableSiapDiambil.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    //initiate dataTables plugin
    oTableSelesai = $('#tbl_selesai').DataTable({ 
          
      "processing": false, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "bPaginate": false,
      "bInfo": false,
      "lengthChange" : false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#tbl_selesai').attr('base-url')+"&tanggal="+$('#tanggal').val()+"",
          "type": "POST"
      },
      drawCallback: function( settings ) {
           var response = settings.json;
           $('#total_selesai').html(response.count_data);
           $('#count_selesai').html(response.count_selesai+' Resep');
      },
      "columnDefs": [
          { 
            "targets": [ 0 ], 
            "orderable": false,
          },
          {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
          { "visible": false, "targets": [1] },
      ],

    });

    $('#tbl_selesai tbody').on('click', 'td.details-control', function (e) {
        var url_detail = 'farmasi/Farmasi_pesan_resep/getDetail';
        e.preventDefault();
        var tr = $(this).closest('tr');
        var row = oTableSelesai.row( tr );
        var data = oTableSelesai.row( $(this).parents('tr') ).data();
        var kode_primary = data[ 1 ];                  
        console.log(data);
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/            
            $.getJSON( url_detail + "/" + kode_primary + "?flag=All", '' , function (data) {
                response_data = data;
                // Open this row
                row.child( format_html( response_data ) ).show();
                tr.addClass('shown');
            });
        }
        
    } );

    $('#tbl_selesai tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTableSelesai.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

  } ); 

  $('#btn_search_data').click(function (e) {
    e.preventDefault();
    $.ajax({
      url: $('#form_search').attr('action'),
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      beforeSend: function() {
        achtungShowLoader();  
      },
      success: function(data) {
        achtungHideLoader();
        find_data_reload(data);
      }
    });
  });

  $('#btn_reset_data').click(function (e) {
    e.preventDefault();
    $('#form_search')[0].reset();
    reloadAllTables();
  });

  function find_data_reload(result){
    oTableResepDiterima.ajax.url($('#tbl_resep_diterima').attr('base-url')+"&"+result.data).load();
    oTableProsesRacikan.ajax.url($('#tbl_proses_racikan').attr('base-url')+"&"+result.data).load();
    oTableProsesEtiket.ajax.url($('#tbl_proses_etiket').attr('base-url')+"&"+result.data).load();
    oTableSiapDiambil.ajax.url($('#tbl_siap_diambil').attr('base-url')+"&"+result.data).load();
    oTableSelesai.ajax.url($('#tbl_selesai').attr('base-url')+"&"+result.data).load();
  }

  function exc_process(kode_trans_far, flag_code, jenis_resep, status_ambil=0) {
    $.ajax({
        url: 'farmasi/Log_proses_resep_obat/process',
        type: "post",
        data: {ID : kode_trans_far, proses: flag_code, jenis : jenis_resep, status_ambil : status_ambil},
        dataType: "json",
        beforeSend: function() {
          // achtungShowLoader();  
        },
        success: function(data) {
          // achtungHideLoader();

          if(data.status === 200){          

            $.achtung({message: data.message, timeout:5});  
            reloadAllTables(); 

          }else{

            $.achtung({message: data.message, timeout:5, className: 'achtungFail'}); 

          }  

          
        }
    });
  }

  function format_html ( data ) {
    return data.html;
  }

  function cleanPatientName(fullName) {
    if (!fullName) return '';
    var name = fullName;
    // 1. Hapus prefix di awal (Ny., Tn., An., By., dr., Prof., Hj., dll.) — loop untuk prefix bertumpuk
    var leadingPrefix = /^(?:(?:ny|tn|nn|an|by|bp|bpk|bapak|ibu|sdr|sdri|dr|drs|drg|prof|apt|hj?)\s*\.?\s*)+/gi;
    var prev;
    do { prev = name; name = name.replace(leadingPrefix, ''); } while (name !== prev);
    // 2. Hapus semua yang ada setelah koma pertama (gelar akademik: S.Ked., M.Kes., dll.)
    name = name.replace(/,.*$/, '');
    // 3. Hapus singkatan berformat titik (S.Ked., Sp.OG, M.Kes.)
    name = name.replace(/\b\w+\.\w[\w.]*\b/g, '');
    // 4. Hapus singkatan satu huruf diikuti titik (H., S.)
    name = name.replace(/\b[A-Za-z]\.\s?/g, '');
    // 5. Normalisasi spasi
    name = name.replace(/\s+/g, ' ').trim();
    return name;
  }

  function call_pasien(nama_pasien, jk) {
    if (!window.speechSynthesis) {
      Swal.fire({ icon: 'error', title: 'Tidak Didukung', text: 'Browser tidak mendukung fitur Text-to-Speech.', confirmButtonColor: '#e74c3c' });
      return;
    }

    // Bersihkan nama dari prefix, gelar, singkatan
    var nama_bersih = cleanPatientName(nama_pasien);

    var sapaan = (jk === 'L') ? 'Bapak' : 'Ibu';
    var teks   = sapaan + ' ' + nama_bersih.toLowerCase() + ' silahkan mengambil obat di loket farmasi';

    var _synth  = (typeof synth  !== 'undefined') ? synth  : window.speechSynthesis;
    var _voices = (typeof voices !== 'undefined') ? voices : _synth.getVoices();
    var _pitch  = (typeof pitch  !== 'undefined' && pitch)  ? parseFloat(pitch.value)  : 1;
    var _rate   = (typeof rate   !== 'undefined' && rate)   ? parseFloat(rate.value)   : 1;

    _synth.cancel();
    var ucap = new SpeechSynthesisUtterance(teks);
    ucap.pitch  = _pitch;
    ucap.rate   = _rate;
    ucap.volume = 1;

    // Gunakan "Google Bahasa Indonesia" — sama seperti script.js
    for (var i = 0; i < _voices.length; i++) {
      if (_voices[i].name === 'Google Bahasa Indonesia') {
        ucap.voice = _voices[i];
        break;
      }
    }

    _synth.speak(ucap);
    Swal.fire({ icon: 'info', title: 'Memanggil Pasien', html: '<i class="fa fa-volume-up"></i> <b>' + nama_pasien + '</b>', timer: 4000, showConfirmButton: false, timerProgressBar: true });
  }

</script>

<!-- Hidden TTS controls — harus ada SEBELUM script.js dijalankan -->
<div style="display:none">
  <input class="txt" type="text">
  <input id="pitch" type="range" min="0" max="2" value="1" step="0.1">
  <input id="rate"  type="range" min="0.5" max="2" value="1" step="0.1">
  <select id="tts_language"></select>
</div>
<script src="<?php echo base_url()?>assets/tts/script.js"></script>


<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <form class="form-horizontal" method="post" id="form_search" action="Templates/References/find_data" autocomplete="off">

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
              <label class="control-label col-md-1">Tanggal</label>
              <div class="col-md-2">
                <div class="input-group">
                  <input class="form-control date-picker" name="tanggal" id="tanggal" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>

              <div class="col-md-6" style="margin-left: -1.3%">
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
      </div>
      <hr>
      <table>
        <tr>
          <td width="200px"><span><i>Total Resep Selesai :</i><br><span id="count_selesai" style="font-size: 24px; font-weight: bold">0 Resep</span></td>
          <td width="200px"><span><i>Rata-rata waktu pelayanan farmasi :</i><br><span id="avg-tat-info" style="font-size: 24px; font-weight: bold">(hh:ii:ss)</span></td>
        </tr>
      </table>
      
      <hr>
      <div class="tabbable">
        <ul class="nav nav-tabs" id="myTab">
          <li class="active">
            <a data-toggle="tab" href="#resep_diterima">
              Penyediaan Obat
              <span class="badge badge-danger" id="total_resep_diterima">0</span>
            </a>
          </li>

          <li>
            <a data-toggle="tab" href="#proses_racikan">
              Proses Racikan
              <span class="badge badge-primary" id="total_proses_racikan">0</span>
            </a>
          </li>

          <li>
            <a data-toggle="tab" href="#proses_etiket">
              Proses Etiket
              <span class="badge badge-purple" id="total_proses_etiket">0</span>
            </a>
          </li>

          <li>
            <a data-toggle="tab" href="#siap_diambil">
              Siap Diambil
              <span class="badge badge-inverse" id="total_siap_diambil">0</span>
            </a>
          </li>

          <li>
            <a data-toggle="tab" href="#selesai">
              Resep Selesai
              <span class="badge badge-success" id="total_selesai">0</span>
            </a>
          </li>


        </ul>
        <div class="tab-content">
          <div id="resep_diterima" class="tab-pane fade in active">
            <!-- div.dataTables_borderWrap -->
            <div>
              <table id="tbl_resep_diterima" base-url="farmasi/Log_proses_resep_obat/get_data?flag=resep_diterima" class="table table-bordered table-hover">
                <thead>
                  <tr>  
                    <th width="40px" class="center"></th>
                    <th width="40px"></th>
                    <th class="center">No</th>
                    <th>Kode</th>
                    <th width="100px">Tgl Transaksi</th>
                    <th>Nama Pasien</th>
                    <th>Jenis Resep</th>
                    <th width="100px" class="center">Resep Diterima</th>
                    <th width="100px" class="center">Penyediaan Obat</th>
                    <th width="100px" class="center">Proses Racikan</th>
                    <th width="100px" class="center">Proses Etiket</th>
                    <th width="100px" class="center">Obat Siap Diambil</th>
                    <th width="100px" class="center">Obat Diterima</th>
                    <th width="100px" class="center">Total Waktu<br>Pelayanan (hh:mm)</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>

          <div id="proses_racikan" class="tab-pane fade">
            <!-- div.dataTables_borderWrap -->
            <div>
              <table id="tbl_proses_racikan" base-url="farmasi/Log_proses_resep_obat/get_data?flag=proses_racikan" class="table table-bordered table-hover">
                <thead>
                  <tr>  
                    <th width="40px" class="center"></th>
                    <th width="40px"></th>
                    <th class="center">No</th>
                    <th>Kode</th>
                    <th width="100px">Tgl Transaksi</th>
                    <th>Nama Pasien</th>
                    <th>Jenis Resep</th>
                    <th width="100px" class="center">Resep Diterima</th>
                    <th width="100px" class="center">Penyediaan Obat</th>
                    <th width="100px" class="center">Proses Racikan</th>
                    <th width="100px" class="center">Proses Etiket</th>
                    <th width="100px" class="center">Obat Siap Diambil</th>
                    <th width="100px" class="center">Obat Diterima</th>
                    <th width="100px" class="center">Total Waktu<br>Pelayanan (hh:mm)</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>

          <div id="proses_etiket" class="tab-pane fade">
            <!-- div.dataTables_borderWrap -->
            <div>
              <table id="tbl_proses_etiket" base-url="farmasi/Log_proses_resep_obat/get_data?flag=proses_etiket" class="table table-bordered table-hover">
                <thead>
                  <tr>  
                    <th width="40px" class="center"></th>
                    <th width="40px"></th>
                    <th class="center">No</th>
                    <th>Kode</th>
                    <th width="100px">Tgl Transaksi</th>
                    <th>Nama Pasien</th>
                    <th>Jenis Resep</th>
                    <th width="100px" class="center">Resep Diterima</th>
                    <th width="100px" class="center">Penyediaan Obat</th>
                    <th width="100px" class="center">Proses Racikan</th>
                    <th width="100px" class="center">Proses Etiket</th>
                    <th width="100px" class="center">Obat Siap Diambil</th>
                    <th width="100px" class="center">Obat Diterima</th>
                    <th width="100px" class="center">Total Waktu<br>Pelayanan (hh:mm)</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>

          <div id="siap_diambil" class="tab-pane fade">
            <!-- div.dataTables_borderWrap -->
            <div>
              <table id="tbl_siap_diambil" base-url="farmasi/Log_proses_resep_obat/get_data?flag=siap_diambil" class="table table-bordered table-hover">
                <thead>
                  <tr>  
                    <th width="40px" class="center"></th>
                    <th width="40px"></th>
                    <th class="center">No</th>
                    <th>Kode</th>
                    <th width="100px">Tgl Transaksi</th>
                    <th>Nama Pasien</th>
                    <th>Jenis Resep</th>
                    <th width="100px" class="center">Resep Diterima</th>
                    <th width="100px" class="center">Penyediaan Obat</th>
                    <th width="100px" class="center">Proses Racikan</th>
                    <th width="100px" class="center">Proses Etiket</th>
                    <th width="100px" class="center">Obat Siap Diambil</th>
                    <th width="100px" class="center">Obat Diterima</th>
                    <th width="100px" class="center">Total Waktu<br>Pelayanan (hh:mm)</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>

          <div id="selesai" class="tab-pane fade">
            <!-- div.dataTables_borderWrap -->
            <div>
              <table id="tbl_selesai" base-url="farmasi/Log_proses_resep_obat/get_data?flag=selesai" class="table table-bordered table-hover">
                <thead>
                  <tr>  
                    <th width="40px" class="center"></th>
                    <th width="40px"></th>
                    <th class="center">No</th>
                    <th>Kode</th>
                    <th width="100px">Tgl Transaksi</th>
                    <th>Nama Pasien</th>
                    <th>Jenis Resep</th>
                    <th width="100px" class="center">Resep Diterima</th>
                    <th width="100px" class="center">Penyediaan Obat</th>
                    <th width="100px" class="center">Proses Racikan</th>
                    <th width="100px" class="center">Proses Etiket</th>
                    <th width="100px" class="center">Obat Siap Diambil</th>
                    <th width="100px" class="center">Obat Diterima</th>
                    <th width="100px" class="center">Total Waktu<br>Pelayanan (hh:mm)</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
      <hr>
      
      

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->





