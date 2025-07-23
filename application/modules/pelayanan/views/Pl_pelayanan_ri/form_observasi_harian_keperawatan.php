<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>
<script src="<?php echo base_url()?>assets/js/custom/chart.js"></script>
<script type="text/javascript">

jQuery(function($) {  

  $('.date-picker').datepicker({    
    autoclose: true,    
    todayHighlight: true    
  }).on("change", function() {
    // Update the selected date display
      $('.selected_date').html($(this).val());
      // console.log("Selected date: " + $(this).val());
  });  

  $('#jam_monitor').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#jam_monitor').timepicker('showWidget');
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

  $('#jam_monitor2').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#jam_monitor2').timepicker('showWidget');
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

  $('#jam_monitor3').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#jam_monitor3').timepicker('showWidget');
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

  $('#jam_monitor4').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#jam_monitor4').timepicker('showWidget');
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

});

$(document).ready(function() {
  
  tbl_observasi_harian_keperawatan = $('#tbl_observasi_harian_keperawatan').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": true,
      "bInfo": false,
      "pageLength": 1,
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
  
  // proses
  $('#btn_save_perkembangan_pasien, #btn_save_work_day, #btn_hemodinamik, #btn_monitor_perkembangan_pasien, #btn_deskripsi_lainnya, #btn_keseimbangan_cairan, #btn_program_pemberian_obat').click(function (e) {   
    e.preventDefault();
    var btn_value = $(this).val();
    $.ajax({
        url: $('#form_pelayanan').attr('action'),
        data: $('#form_pelayanan').serialize()+ '&submit='+btn_value+'',            
        dataType: "json",
        type: "POST",
        complete: function(xhr) {             
          var data=xhr.responseText;        
          var jsonResponse = JSON.parse(data);        
          if(jsonResponse.status === 200){      

            tbl_observasi_harian_keperawatan.ajax.reload();
            dt_hemodinamik.ajax.reload();
            dt_montoring_perkembangan_pasien.ajax.reload();
            dt_deskripsi_lainnya.ajax.reload();
            dt_keseimbangan_cairan.ajax.reload();
            tbl_program_pemberian_obat.ajax.reload();

            load_graph();

            $('#form_pelayanan')[0].reset();

            $.achtung({message: jsonResponse.message, timeout:5});  
          }else{           
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
          }        
          achtungHideLoader();        
        } 
    });

  });

  
});

function set_line_through(id, status){
  preventDefault();
  $.getJSON('pelayanan/Pl_pelayanan_ri/update_status_dt_monitoring', {ID: id, table: 'th_monitor_perkembangan_pasien_ri', deleted : status} , function(response_data) {
    if(status == 1){
      $('tr#tbl_dt_'+id+' span').css('text-decoration', 'line-through').css('color', 'red');
      $('tr#tbl_dt_'+id+' td').css('text-decoration', 'line-through').css('color', 'red');  
      $('#btn_action_'+id+'').html("<a href='#' onclick='set_line_through("+id+", 0)'><i class='fa fa-refresh green bigger-120'></i></a>");  
    }else{
      $('tr#tbl_dt_'+id+' span').css('text-decoration', '').css('color', 'black');
      $('tr#tbl_dt_'+id+' td').css('text-decoration', '').css('color', 'black');  
      $('tr#tbl_dt_'+id+'').css('text-decoration', '').css('color', 'black');  
      $('#btn_action_'+id+'').html("<a href='#' onclick='set_line_through("+id+", 1)'><i class='fa fa-times-circle red bigger-120'></i></a>");  
    }
    load_graph();
    tbl_observasi_harian_keperawatan.ajax.reload();
    dt_hemodinamik.ajax.reload();
    dt_montoring_perkembangan_pasien.ajax.reload();
    dt_deskripsi_lainnya.ajax.reload();
    dt_keseimbangan_cairan.ajax.reload();
    tbl_program_pemberian_obat.ajax.reload();
  });
}

function edit_row(id, flag){
  preventDefault();
  $.getJSON('pelayanan/Pl_pelayanan_ri/get_row_data_observasi', {ID: id} , function(response_data) {
    if(response_data.status == 200){
        
      $('#tgl_monitor').val(response_data.data.tgl_monitor);

      if(flag == 'tbl_observasi_harian_keperawatan'){
        
        $('#id').val(response_data.data.id);
        $('#intake_enteral').val(response_data.data.intake_enteral);
        $('#intake_parenteral').val(response_data.data.intake_parenteral);
        $('#obat_enteral').val(response_data.data.obat_enteral);
        $('#obat_parenteral').val(response_data.data.obat_parenteral);
        $('#polavent').val(response_data.data.polavent);
        $('#lain_alergi').val(response_data.data.lain_alergi);
        $('#catatan').val(response_data.data.catatan);

      }

      if(flag == 'dt_hemodinamik'){
        
        $('#id').val(response_data.data.id);
        $('#jam_monitor').val(response_data.data.jam_monitor);
        $('#sistolik').val(response_data.data.sistolik);
        $('#diastolik').val(response_data.data.diastolik);
        $('#nd').val(response_data.data.nd);
        $('#sh').val(response_data.data.sh);
        $('#catatan_hemodinamik').val(response_data.data.catatan);

      }

      if(flag == 'btn_monitor_perkembangan_pasien'){
        
        $('#id').val(response_data.data.id);
        $('#jam_monitor2').val(response_data.data.jam_monitor);
        $('#kesadaran').val(response_data.data.kesadaran);
        $('#pupil').val(response_data.data.pupil);
        $('#ref').val(response_data.data.ref);
        $('#gcs').val(response_data.data.gcs);
        $('#sup').val(response_data.data.sup);
        $('#inf').val(response_data.data.inf);
        $('#cm_enteral').val(response_data.data.cm_enteral);
        $('#cm_parenteral').val(response_data.data.cm_parenteral);
        $('#cm_train').val(response_data.data.cm_train);
        $('#ck_urin').val(response_data.data.ck_urin);
        $('#ck_ngt').val(response_data.data.ck_ngt);
        $('#ck_bab').val(response_data.data.ck_bab);
        $('#resp_pola').val(response_data.data.resp_pola);
        $('#resp_tv').val(response_data.data.resp_tv);
        $('#resp_rr').val(response_data.data.resp_rr);
        $('#resp_fo2').val(response_data.data.resp_fo2);
        $('#resp_peep').val(response_data.data.resp_peep);
        $('#cvp').val(response_data.data.cvp);
        $('#catatan_monitoring').val(response_data.data.catatan);

      }

      if(flag == 'btn_keseimbangan_cairan'){
        
        $('#id').val(response_data.data.id);
        $('#konstanta').val(response_data.data.nilai_konstanta);
        $('#berat_badan').val(response_data.data.berat_badan);
        $('#total_jam').val(response_data.data.total_jam);
        $('#iwl').val(response_data.data.iwl);
        $('#cairan_masuk').val(response_data.data.total_cairan_masuk);
        $('#cairan_keluar').val(response_data.data.total_cairan_keluar);
        $('#balans_cairan').val(response_data.data.balance_cairan);

      }

      if(flag == 'btn_program_pemberian_obat'){
        
        $('#id').val(response_data.data.id);
        $('#jam_monitor4').val(response_data.data.jam_monitor);
        $('#cairan_infus').val(response_data.data.infus);
        $('#nutrisi_enteral').val(response_data.data.nutrisi_enteral);

      }

      // Show the modal or form for editing
      // For example, you can use a modal dialog to show the data
    }else{
      $.achtung({message: response_data.message, timeout:5, className: 'achtungFail'});
    }
  });
}

function load_graph(){
  $('#grafik_content').html('Loading...');
  $.getJSON('pelayanan/Pl_pelayanan_ri/get_content_chart_monitoring', {no_kunjungan : $('#no_kunjungan').val()}, function(response_data) {
    html = '';
    $.each(response_data, function (i, o) {
      html += '<div class="col-sm-'+o.col_size+'"><div id="'+o.nameid+'"></div></div>';
      if(o.style=='line'){
        GraphLineStyle(o.mod, o.nameid, o.url);
      }
    });
    
    $('#grafik_content').html(html);
  });
}

function hitung_iwl(){
  var berat_badan = parseFloat($('#berat_badan').val());
  var konstanta = parseFloat($('#konstanta').val());
  var total_jam = parseFloat($('#total_jam').val());
  
  if(berat_badan > 0 && konstanta > 0){
    var iwl = ((konstanta * berat_badan) * total_jam) / 24;
    $('#iwl').val(parseInt(iwl));
    $('#txt_total_jam').html('(Ml/'+total_jam+' Jam)');
  }else{
    $('#iwl').val('');
    $('#txt_total_jam').html('Ml/Jam');
  }
  hitung_balans_cairan();
}

function hitung_balans_cairan(){
  var iwl = parseFloat($('#iwl').val());
  var cairan_masuk = parseFloat($('#cairan_masuk').val());
  var cairan_keluar = parseFloat($('#cairan_keluar').val());

  if(iwl > 0){
    var balans_cairan = (cairan_masuk - (cairan_keluar + iwl));
    $('#balans_cairan').val(parseInt(balans_cairan));
  }else{
    $('#balans_cairan').val('');
  }
  hitung_iwl();
}

</script>
<div class="row">
  <div class="col-md-12">

    <center><span style="font-weight: bold; font-size: 20px !important">OBSERVASI HARIAN KEPERAWATAN PASIEN</span></center>
    <br>
    <!-- hidden form -->
    <input type="hidden" name="tipe_monitoring" id="tipe_monitoring" value="UMUM">
    

    <!-- TANGGAL -->
    <div class="form-group">
        <label class="control-label col-sm-1" for="">ID</label>
        <div class="col-md-2">
          <input type="text" name="id" id="id" readonly class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-1" for="">*Tanggal</label>
        <div class="col-md-2">
          <div class="input-group">
              <input name="tgl_monitor" id="tgl_monitor" placeholder="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>">
              <span class="input-group-addon">
                <i class="ace-icon fa fa-calendar"></i>
              </span>
          </div>
        </div>
    </div>
    <!-- END TANGGAL -->

    <!-- FORM OBSERVASI HARIAN KEPERAWATAN -->
    <div class="row">
      <div class="col-md-12">

        <h3 class="header smaller lighter blue padding-10" style="background: #0d5280; font-size: 14px !important; font-weight: bold; color: white !important; padding: 5px;">
          RENCANA KEPERAWATAN HARIAN PASIEN
        </h3>
        <div class="col-md-3 no-padding">
          <b><u>INTAKE</u></b><br>
              <div style="width: 100%">
                  Enteral<br>
                  <textarea class="form-control" style="height: 50px !important;" name="intake_enteral" id="intake_enteral"></textarea>
              </div>
              <br>
              <div style="width: 100%">
                  Parenteral<br>
                  <textarea class="form-control" style="height: 50px !important;" name="intake_parenteral" id="intake_parenteral"></textarea>
              </div>
        </div>

        <div class="col-md-3 ">
          <b><u>PEMBERIAN OBAT</u></b><br>
            <div style="width: 100%">
                Enteral/Lain-lain<br>
                <textarea class="form-control" style="height: 50px !important;" name="obat_enteral" id="obat_enteral"></textarea>
            </div>
            <br>
            <div style="width: 100%">
                Parenteral<br>
                <textarea class="form-control" style="height: 50px !important;" name="obat_parenteral" id="obat_parenteral"></textarea>
            </div>
        </div>

        <div class="col-md-3">
          <b>&nbsp;</b><br>
            <div style="width: 100%">
                Polavent<br>
                <textarea class="form-control" style="height: 50px !important;" name="polavent" id="polavent"></textarea>
            </div>
            <br>
            <div style="width: 100%">
                Lain-lain (Alergi)<br>
                <textarea class="form-control" style="height: 50px !important;" name="lain_alergi" id="lain_alergi"></textarea>
            </div>
        </div>

        <div class="col-md-3 no-padding">
          <b>&nbsp;</b><br>
            <div style="width: 100%">
                Catatan Dokter<br>
                <textarea class="form-control" style="height: 120px !important;" name="catatan" id="catatan"></textarea>
            </div>
        </div>

        <div class="col-md-12 no-padding" style="padding-top: 3px !important">
          <button type="submit" name="btn_work_day" value="btn_work_day" class="btn btn-xs btn-primary" id="btn_save_work_day"><i class="fa fa-save"></i> Simpan</button>
        </div>
        <br>

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
        
        <div style="width: 100%; padding-bottom: 5px">
            Jam Pemberian Obat/Infus : <br>
            <div class="input-group">
                <input id="jam_monitor4" name="jam_monitor4"  type="text" class="form-control">
                <span class="input-group-addon">
                  <i class="fa fa-clock-o bigger-110"></i>
                </span>
            </div>
        </div>
        <div class="col-md-6 no-padding">
              <div style="width: 100%">
                  Cairan Infus : <br>
                  <textarea class="form-control" style="height: 50px !important;" name="cairan_infus" id="cairan_infus"></textarea>
              </div>
        </div>

        <div class="col-md-6 ">
            <div style="width: 100%">
                Nutrisi Enteral : <br>
                <textarea class="form-control" style="height: 50px !important;" name="nutrisi_enteral" id="nutrisi_enteral"></textarea>
            </div>
        </div>

        <div class="col-md-12 no-padding" style="padding-top: 3px !important">
          <button type="submit" name="btn_program_pemberian_obat" value="btn_program_pemberian_obat" class="btn btn-xs btn-primary" id="btn_program_pemberian_obat"><i class="fa fa-save"></i> Simpan</button>
        </div>
        <br>

        <div class="col-md-12 no-padding">
          <table class="table" style="margin-top: 10px" id="tbl_program_pemberian_obat">
            <thead>
              <tr style="background:#e7e7e7; color: black">
                <th style="background:#e7e7e7; color: black" width="50px">#</th>
                <th style="background:#e7e7e7; color: black; width: 50px" class="center">Tanggal</th>
                <th style="background:#e7e7e7; color: black; width: 50px" class="center">Jam</th>
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
        <h3 class="header smaller lighter blue padding-10" style="background: #ef8122; font-size: 14px !important; font-weight: bold; color: white !important; padding: 5px;">
            HEMODINAMIK (Tanggal. <span class="selected_date"><?php echo date('Y-m-d')?></span>)
          </h3>
        <div class="col-md-3 no-padding">
          <div class="form-group">
              <label class="control-label col-sm-6" for="">*Jam Input</label>
              <div class="col-md-6">
                <div class="input-group">
                    <input id="jam_monitor" name="jam_monitor"  type="text" class="form-control">
                    <span class="input-group-addon">
                      <i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
              </div>
          </div>
          <br>
          <span style="font-weight: bold; padding: 10px">1. TD (Tekanan Darah)</span>
          <br>
          <div class="form-group">
              <label class="control-label col-sm-6">Sistolik (mmHg)</label>
              <div class="col-md-6">
                <input type="text" class="form-control" name="sistolik" id="sistolik" value="">
              </div>
          </div>

          <div class="form-group">
              <label class="control-label col-sm-6">Diastolik (mmHg)</label>
              <div class="col-md-6">
                <input type="text" class="form-control" name="diastolik" id="diastolik" value="">
              </div>
          </div>
          <br>

          <span style="font-weight: bold; padding: 10px; color: red">2. FN (Frekuensi Nadi)</span>
          <br>
          <div class="form-group no-padding">
              <label class="control-label col-sm-6"> Nadi (bpm)</label>
              <div class="col-md-6">
                <input type="text" class="form-control" name="nd" id="nd" value="">
              </div>
          </div>
          <br>
          <span style="font-weight: bold; padding: 10px; color: blue">3. SH (Suhu)</span>
          <br>
          <div class="form-group no-padding">
              <label class="control-label col-sm-6">Suhu (&#x2103;)</label>
              <div class="col-md-6">
                <input type="text" class="form-control" name="sh" id="sh" value="">
              </div>
          </div>
          
          <br>
          <div>
            <b style="font-style: italic">Catatan Pemeriksaan</b><br>
            <textarea class="form-control" style="height: 100px !important;" name="catatan_hemodinamik" id="catatan_hemodinamik"></textarea>
          </div>
          <br>
          <div class="col-md-12 no-padding" style="padding-top: 3px !important">
            <button type="submit" name="btn_hemodinamik" value="btn_hemodinamik" class="btn btn-xs btn-primary" id="btn_hemodinamik"><i class="fa fa-save"></i> Simpan</button>
          </div>
          
        </div>
        <div class="col-md-9" style="padding-left: 30px !important">

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
                      <th style="width: 10px; background:#e7e7e7; color: black" class="center">Tanggal</th>
                      <th style="width: 30px; background:#e7e7e7; color: black" class="center">Jam</th>
                      <th style="width: 70px; background:#e7e7e7; color: black" class="center">Petugas</th>
                      <th style="width: 50px; background:#e7e7e7; color: black" class="center">Sistolik (mmHg)</th>
                      <th style="width: 50px; background:#e7e7e7; color: black" class="center">Diastolik (mmHg)</th>
                      <th style="width: 50px; background:#e7e7e7; color: black" class="center">Nadi (bpm)</th>
                      <th style="width: 50px; background:#e7e7e7; color: black" class="center">Suhu (&#x2103;)</th>
                      <th style="width: 150px; background:#e7e7e7; color: black" class="center">Catatan</th>
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
        <h3 class="header smaller lighter blue padding-10" style="background: #e01a8e; font-size: 14px !important; font-weight: bold; color: white !important; padding: 5px;">
          DATA MONITORING PERKEMBANGAN PASIEN (Tanggal. <span class="selected_date"><?php echo date('Y-m-d')?></span>)
        </h3>
        <div class="col-md-12 no-padding">
          <div class="form-group">
              <label class="control-label col-sm-1" for="">*Jam Input</label>
              <div class="col-md-2">
                <div class="input-group">
                    <input id="jam_monitor2" name="jam_monitor2"  type="text" class="form-control">
                    <span class="input-group-addon">
                      <i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
              </div>
          </div>
          <div style="padding: 5px; font-weight: bold">SSP (Sistem Saraf Pusat)</div>
          <div class="form-group">
              <label class="control-label col-sm-1">Kes.</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="kesadaran" id="kesadaran" value="">
              </div>
              <label class="control-label col-sm-1">Pupil</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="pupil" id="pupil" value="">
              </div>
              <label class="control-label col-sm-1">Ref.</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="ref" id="ref" value="">
              </div>
              <label class="control-label col-sm-1">GCS</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="gcs" id="gcs" value="" onclick="show_modal_medium('pelayanan/Pl_pelayanan_ri/info_gcs', 'INFORMASI GCS (Glasgow Coma Scale)')">
              </div>
          </div>

          <div style="padding: 5px; font-weight: bold">Motorik</div>
          <div class="form-group">
              <label class="control-label col-sm-1">Sup.</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="sup" id="sup" value="">
              </div>
              <label class="control-label col-sm-1">Inf.</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="inf" id="inf" value="">
              </div>
          </div>

          <div style="padding: 5px; font-weight: bold">CM (Cairan Masuk)</div>
          <div class="form-group">
              <label class="control-label col-sm-1">Ent.</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="cm_enteral" id="cm_enteral" value="">
              </div>
              <label class="control-label col-sm-1">Par.</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="cm_parenteral" id="cm_parenteral" value="">
              </div>
              <label class="control-label col-sm-1">Train.</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="cm_train" id="cm_train" value="">
              </div>
          </div>

          <div style="padding: 5px; font-weight: bold">CK (Cairan Keluar)</div>
          <div class="form-group">
              <label class="control-label col-sm-1">Urin</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="ck_urin" id="ck_urin" value="">
              </div>
              <label class="control-label col-sm-1">NGT</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="ck_ngt" id="ck_ngt" value="">
              </div>
              <label class="control-label col-sm-1">BAB</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="ck_bab" id="ck_bab" value="">
              </div>
          </div>

          <div style="padding: 5px; font-weight: bold">Respirasi</div>
          <div class="form-group">
              <label class="control-label col-sm-1">Pola</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="resp_pola" id="resp_pola" value="">
              </div>
              <label class="control-label col-sm-1">TV</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="resp_tv" id="resp_tv" value="">
              </div>
              <label class="control-label col-sm-1">RR</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="resp_rr" id="resp_rr" value="">
              </div>
              <label class="control-label col-sm-1">Fo2%</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="resp_fo2" id="resp_fo2" value="">
              </div>
              <label class="control-label col-sm-1">Peep</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="resp_peep" id="resp_peep" value="">
              </div>
          </div>

          <div style="padding: 5px; font-weight: bold">CVP</div>
          <div class="form-group">
              <label class="control-label col-sm-1">CVP</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="cvp" id="cvp" value="">
              </div>
              <label class="control-label col-sm-1">Catatan</label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="catatan_monitoring" id="catatan_monitoring" value="">
              </div>
          </div>

        </div>
        <hr>
        <div class="col-md-12 no-padding" style="padding-top: 3px !important; padding-bottom: 10px !important">
          <button type="submit" name="btn_monitor_perkembangan_pasien" value="btn_monitor_perkembangan_pasien" class="btn btn-xs btn-primary" id="btn_monitor_perkembangan_pasien"><i class="fa fa-save"></i> Simpan</button>
        </div>

        <!-- <div style="padding-top: 10px"><a href="#" class="btn btn-xs btn-primary" onclick="add_row_dt('dt_montoring_perkembangan_pasien')"><i class="fa fa-plus"></i> Tambah Data</a></div> -->

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

    <!-- DESKRIPSI/JENIS CAIRAN/CATATAN KHUSUS/DLL -->
    <div class="row" style="padding: 10px !important">
      <div class="col-md-12 no-padding">
        <h3 class="header smaller lighter blue padding-10" style="background: #5a7416; font-size: 14px !important; font-weight: bold; color: white !important; padding: 5px;">
          RENCANA PEMERIKSAAN/KEGIATAN/JENIS CAIRAN/CATATAN KHUSUS/DLL (Tanggal. <span class="selected_date"><?php echo date('Y-m-d')?></span>)
        </h3>
        <div class="form-group">
            <label class="control-label col-sm-2" for="">*Jam Input</label>
            <div class="col-md-2">
              <div class="input-group">
                  <input id="jam_monitor3" name="jam_monitor3"  type="text" class="form-control">
                  <span class="input-group-addon">
                    <i class="fa fa-clock-o bigger-110"></i>
                  </span>
              </div>
            </div>
        </div>
        <br>
        <div>
          <b style="font-style: italic">Penjelasan Jenis Cairan/ Catatan Khusus Lainnya</b><br>
          <textarea class="form-control" style="height: 100px !important;" name="catatan_khusus" id="catatan_khusus"></textarea>
        </div>
        <div class="col-md-12 no-padding" style="padding-top: 3px !important; padding-bottom: 10px !important">
          <button type="submit" name="btn_deskripsi_lainnya" value="btn_deskripsi_lainnya" class="btn btn-xs btn-primary" id="btn_deskripsi_lainnya"><i class="fa fa-save"></i> Simpan</button>
        </div>
        
        <table class="table" id="dt_deskripsi_lainnya">
          <thead>
            <tr style="background:#e7e7e7; color: black">
              <th style="width: 80px; background:#e7e7e7; color: black">#</th>
              <th class="center" style="width: 100px; background:#e7e7e7; color: black">JAM</th>
              <th class="center" style="background:#e7e7e7; color: black"> DESKRIPSI/JENIS CAIRAN/CATATAN KHUSUS/DLL </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table> 

      </div>
    </div>
    <!-- END DESKRIPSI/JENIS CAIRAN/CATATAN KHUSUS/DLL -->

    <!-- KESEIMBANGAN CAIRAN -->
     <div class="row" style="padding: 10px !important">
      <div class="col-md-12 no-padding">
        <h3 class="header smaller lighter blue padding-10" style="background: #b378b4; font-size: 14px !important; font-weight: bold; color: white !important; padding: 5px;">
          KESEIMBANGAN CAIRAN (Tanggal. <span class="selected_date"><?php echo date('Y-m-d')?></span>)
        </h3>
          <span style="font-weight: bold; font-size: 14px; text-decoration: underline">IWL (Insensible Water Loss)</span>
          <br>
          <p>IWL adalah kehilangan cairan yang tidak terukur melalui kulit dan paru-paru. IWL dihitung berdasarkan berat badan, konstanta, dan total jam perawatan pasien.<br>Rumus IWL: <b>IWL = (Konstanta x Berat Badan x Total Jam) / 24</b></p>
          <div class="form-group">
              <label class="control-label col-sm-1">Konstanta</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="konstanta" id="konstanta" value="" onchange="hitung_iwl()">
              </div>
              <label class="control-label col-sm-1">Berat Badan</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="berat_badan" id="berat_badan" value="" onchange="hitung_iwl()">
              </div>
              <label class="control-label col-sm-1">Total Jam</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="total_jam" id="total_jam" value="24" onchange="hitung_iwl()">
              </div>
              <label class="control-label col-sm-1">Nilai IWL</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="iwl" id="iwl" value="" readonly>
              </div>
              <label class="col-sm-1"><span id="txt_total_jam">Ml/Jam</span></label>
          </div>
          <br>
          <span style="font-weight: bold; font-size: 14px; text-decoration: underline">Balans Cairan</span>
          <br>
          <p>Balans cairan adalah selisih antara cairan masuk dan cairan keluar, termasuk IWL. <br>Balans cairan dihitung dengan rumus: <b>Balans Cairan = Cairan Masuk - (Cairan Keluar + IWL)</b></p>

          <div class="form-group">
              <label class="control-label col-sm-1">Cairan Masuk</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="cairan_masuk" id="cairan_masuk" value="" onchange="hitung_balans_cairan()">
              </div>
              <label class="control-label col-sm-1">Cairan Keluar</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="cairan_keluar" id="cairan_keluar" value="" onchange="hitung_balans_cairan()">
              </div>
              <label class="control-label col-sm-1">Nilai Balans</label>
              <div class="col-md-1">
                <input type="text" class="form-control" name="balans_cairan" id="balans_cairan" value="" readonly>
              </div>
              <label class="col-sm-1">(Ml)</label>
          </div>

          <div class="col-md-12 no-padding" style="padding-top: 3px !important; padding-bottom: 10px !important">
            <button type="submit" name="btn_keseimbangan_cairan" value="btn_keseimbangan_cairan" class="btn btn-xs btn-primary" id="btn_keseimbangan_cairan"><i class="fa fa-save"></i> Simpan</button>
          </div>

          <table class="table" id="dt_keseimbangan_cairan">
          <thead>
            <tr style="background:#e7e7e7; color: black">
              <th rowspan="2" style="width: 50px; background:#e7e7e7; color: black">#</th>
              <th class="center" rowspan="2" style="width: 100px; background:#e7e7e7; color: black">Tanggal/ Jam</th>
              <th class="center" rowspan="2" style="width: 100px; background:#e7e7e7; color: black">Nama Petugas</th>
              <th class="center" style="background:#e7e7e7; color: black" colspan="4"> I W L</th>
              <th class="center" rowspan="2" style="background:#e7e7e7; color: black; width: 80px"> Cairan Masuk<br>(Ml)</th>
              <th class="center" rowspan="2" style="background:#e7e7e7; color: black; width: 80px"> Cairan Keluar<br>(Ml)</th>
              <th class="center" rowspan="2" style="background:#e7e7e7; color: black; width: 60px"> Nilai Balans<br>(Ml)</th>
            </tr>
            <tr>
              <th class="center" style="background:#e7e7e7; color: black; width: 60px"> Konstanta </th>
              <th class="center" style="background:#e7e7e7; color: black; width: 60px"> Berat Badan<br>(Kg) </th>
              <th class="center" style="background:#e7e7e7; color: black; width: 60px"> Total Jam<br>(Jam)</th>
              <th class="center" style="background:#e7e7e7; color: black; width: 60px"> Nilai IWL<br>(Ml) </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
          
      </div>
    </div>
    <!-- END KESEIMBANGAN CAIRAN -->

    <!-- RENCANA PEMERIKSAAN DAN KEGIATAN HARIAN -->
    <!-- <div class="row" style="padding: 10px !important">
      <div class="col-md-12">
        <h3 class="header smaller lighter blue padding-10">
          RENCANA PEMERIKSAAN DAN KEGIATAN HARIAN
        </h3>
        <div>
          <b style="font-style: italic">Deskripsi atau Penjelasan Kegiatan dan Pemeriksaan Harian Pasien</b><br>
          <textarea class="form-control" style="height: 100px !important;" name="deskripsi_kegiatan" id="deskripsi_kegiatan"></textarea>
        </div>
        <br>
        <div>
          <b style="font-style: italic">Jenis Kegiatan/Pemeriksaan</b><br>
          <label>
              <input name="jenis_kegiatan" id="jenis_kegiatan" type="radio" class="ace" value="pemeriksaan">
              <span class="lbl"> Rencana Pemeriksaan</span>
            </label>
            <label>
              <input name="jenis_kegiatan" id="jenis_kegiatan" type="radio" class="ace" value="kegiatan">
              <span class="lbl"> Rencana Kegiatan Harian</span>
            </label>
        </div>
        <div class="col-md-12 no-padding" style="padding-top: 3px !important; padding-bottom: 10px !important">
          <button type="submit" name="submit" value="btn_deskripsi_kegiatan" class="btn btn-xs btn-primary" id="btn_deskripsi_kegiatan"><i class="fa fa-save"></i> Simpan</button>
        </div>
        <hr>

        <table class="table" id="dt_deskripsi_kegiatan">
            <tr style="background: #f3f3f3">
              <th width="30px">#</th>
              <th width="30px">Tanggal</th>
              <th width="30px">Petuga</th>
              <th class="left"> Rencana Pemeriksaan atau Kegiatan Harian  </th>
              <th class="left" width="100px"> Jenis Rencana </th>
            </tr>
          </table>  

      </div>
    </div> -->
    <!-- END RENCANA PEMERIKSAAN DAN KEGIATAN HARIAN -->

  </div>
</div>







