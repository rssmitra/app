<style>
/* ===== Observasi Harian Keperawatan — Professional Redesign ===== */

/* — Page title — */
.obs-page-title {
  font-size: 15px; font-weight: 700; color: #0f172a;
  text-align: center; padding: 12px 0 6px;
  letter-spacing: .5px;
  border-bottom: 2px solid #e2e8f0;
  margin-bottom: 16px;
}
.obs-page-title i { color: #0ea5e9; margin-right: 8px; }

/* — Section header cards — */
.obs-section-hdr {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 14px; margin: 18px 0 12px;
  border-radius: 8px; font-size: 13px; font-weight: 700;
  border: 1px solid transparent;
}
.obs-section-hdr i.obs-icon {
  width: 28px; height: 28px; border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; flex-shrink: 0; color: #fff;
}
.obs-hdr-blue    { background: #eff6ff; border-color: #bfdbfe; color: #1e40af; }
.obs-hdr-blue i.obs-icon    { background: #3b82f6; }
.obs-hdr-orange  { background: #fff7ed; border-color: #fed7aa; color: #9a3412; }
.obs-hdr-orange i.obs-icon  { background: #f97316; }
.obs-hdr-pink    { background: #fdf2f8; border-color: #fbcfe8; color: #9d174d; }
.obs-hdr-pink i.obs-icon    { background: #ec4899; }
.obs-hdr-green   { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
.obs-hdr-green i.obs-icon   { background: #22c55e; }
.obs-hdr-purple  { background: #faf5ff; border-color: #e9d5ff; color: #6b21a8; }
.obs-hdr-purple i.obs-icon  { background: #a855f7; }

/* — Date/ID row — */
.obs-meta-row {
  display: flex; flex-wrap: wrap; gap: 12px;
  align-items: center; margin-bottom: 14px;
  padding: 10px 14px; background: #f8fafc;
  border: 1px solid #e2e8f0; border-radius: 8px;
}
.obs-meta-row .form-group { margin-bottom: 0; }
.obs-meta-row label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .3px; }

/* — Form labels — */
.obs-form-label, .obs-sub-title {
  font-size: 12px; font-weight: 700; color: #334155;
  margin-bottom: 3px;
}
.obs-sub-title {
  font-size: 12.5px; padding: 5px 10px;
  background: #f8fafc; border-left: 3px solid #94a3b8;
  border-radius: 0 6px 6px 0; margin: 8px 0 6px; color: #475569;
}

/* — Form controls — */
.obs-form textarea.form-control {
  border-color: #d1d5db; border-radius: 6px; font-size: 12px;
  resize: vertical; transition: border-color .15s;
}
.obs-form textarea.form-control:focus {
  border-color: #93c5fd; box-shadow: 0 0 0 3px rgba(59,130,246,.08);
}
.obs-form input.form-control {
  border-color: #d1d5db; border-radius: 6px; font-size: 12px;
}
.obs-form input.form-control:focus {
  border-color: #93c5fd; box-shadow: 0 0 0 3px rgba(59,130,246,.08);
}
.obs-form .input-group-addon {
  background: #f8fafc; border-color: #d1d5db;
  color: #64748b; font-size: 12px;
}

/* — Save button — */
.obs-btn-save {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 18px; border: none; border-radius: 7px;
  background: linear-gradient(135deg, #1d4ed8, #0ea5e9);
  color: #fff; font-size: 12px; font-weight: 600;
  cursor: pointer; box-shadow: 0 2px 6px rgba(29,78,216,.2);
  transition: all .18s; margin: 8px 0 4px;
}
.obs-btn-save:hover {
  background: linear-gradient(135deg, #1e40af, #0284c7);
  box-shadow: 0 3px 10px rgba(29,78,216,.3);
  transform: translateY(-1px);
}

/* — Tables — */
.obs-table { width: 100%; font-size: 11.5px; border-collapse: separate; border-spacing: 0; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; margin-top: 8px; margin-bottom: 14px; }
.obs-table thead tr th {
  background: #f8fafc; color: #334155;
  font-size: 10.5px; font-weight: 700;
  padding: 7px 6px; border-bottom: 2px solid #e2e8f0;
  border-right: 1px solid #f1f5f9;
  text-transform: uppercase; letter-spacing: .2px;
  text-align: center; vertical-align: middle;
}
.obs-table thead tr th:last-child { border-right: none; }
.obs-table tbody tr td {
  padding: 6px; border-top: 1px solid #f1f5f9;
  color: #334155; vertical-align: middle; background: #fff;
  font-size: 11.5px;
}
.obs-table tbody tr:hover td { background: #f0f9ff; }

/* — Vital signs labels — */
.obs-vital-group { font-size: 12px; font-weight: 700; color: #334155; padding: 6px 10px; background: #f8fafc; border-left: 3px solid #94a3b8; border-radius: 0 6px 6px 0; margin: 10px 0 6px; }
.obs-vital-group.obs-vg-td    { border-left-color: #0f172a; }
.obs-vital-group.obs-vg-nadi  { border-left-color: #dc2626; color: #dc2626; }
.obs-vital-group.obs-vg-suhu  { border-left-color: #2563eb; color: #2563eb; }
.obs-vital-group.obs-vg-spo2  { border-left-color: #16a34a; color: #16a34a; }
.obs-vital-group.obs-vg-ssp   { border-left-color: #7c3aed; }
.obs-vital-group.obs-vg-motor { border-left-color: #0891b2; }
.obs-vital-group.obs-vg-cm    { border-left-color: #0369a1; }
.obs-vital-group.obs-vg-ck    { border-left-color: #be185d; }
.obs-vital-group.obs-vg-resp  { border-left-color: #ea580c; }
.obs-vital-group.obs-vg-cvp   { border-left-color: #4338ca; }

/* — IWL info box — */
.obs-info-box {
  background: #f8fafc; border: 1px solid #e2e8f0;
  border-radius: 8px; padding: 10px 14px;
  font-size: 12px; color: #475569; margin: 6px 0 10px;
  line-height: 1.6;
}
.obs-info-box b { color: #0f172a; }

/* — Section content wrapper — */
.obs-form-grid {
  display: grid; gap: 12px; margin-bottom: 8px;
}
.obs-form-grid.cols-4 { grid-template-columns: repeat(4, 1fr); }
.obs-form-grid.cols-3 { grid-template-columns: repeat(3, 1fr); }
.obs-form-grid.cols-2 { grid-template-columns: 1fr 1fr; }
@media(max-width:900px) { .obs-form-grid.cols-4, .obs-form-grid.cols-3 { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:600px) { .obs-form-grid.cols-4, .obs-form-grid.cols-3, .obs-form-grid.cols-2 { grid-template-columns: 1fr; } }
.obs-form-card {
  background: #fff; border: 1px solid #e2e8f0;
  border-radius: 8px; padding: 10px 12px;
}
.obs-form-card label { font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 4px; display: block; }
.obs-form-card textarea { height: 55px !important; }

/* — Nav tabs inside hemodinamik — */
#myTab2.nav-tabs { border-bottom: 2px solid #e2e8f0; }
#myTab2.nav-tabs > li > a { font-size: 12px; font-weight: 600; color: #64748b; border-radius: 6px 6px 0 0; }
#myTab2.nav-tabs > li > a:hover { background: #f8fafc; color: #334155; }
#myTab2.nav-tabs > li.active > a { background: #fff; color: #0f172a; border-bottom: 2px solid #0ea5e9; font-weight: 700; }

/* — Main observasi tabs — */
#obsTab.nav-tabs {
  border-bottom: 2px solid #e2e8f0; margin: 0;
  background: #f8fafc; border-radius: 8px 8px 0 0;
  padding: 6px 6px 0;
}
#obsTab.nav-tabs > li { margin-bottom: -2px; }
#obsTab.nav-tabs > li > a {
  font-size: 11.5px; font-weight: 600; color: #64748b;
  border: 1px solid transparent; border-radius: 8px 8px 0 0;
  padding: 9px 15px; transition: all .15s; white-space: nowrap;
}
#obsTab.nav-tabs > li > a:hover {
  background: #fff; color: #334155;
  border-color: #e2e8f0 #e2e8f0 transparent;
}
#obsTab.nav-tabs > li.active > a,
#obsTab.nav-tabs > li.active > a:hover,
#obsTab.nav-tabs > li.active > a:focus {
  background: #fff; color: #0f172a; font-weight: 700;
  border-color: #e2e8f0 #e2e8f0 #fff;
}
#obsTab.nav-tabs > li > a > i { margin-right: 5px; }
.obs-tab-blue > i { color: #3b82f6 !important; }
.obs-tab-orange > i { color: #f97316 !important; }
.obs-tab-pink > i { color: #ec4899 !important; }
.obs-tab-green > i { color: #22c55e !important; }
.obs-tab-purple > i { color: #a855f7 !important; }
#obsTab.nav-tabs > li.active > a.obs-tab-blue { border-top: 2px solid #3b82f6; }
#obsTab.nav-tabs > li.active > a.obs-tab-orange { border-top: 2px solid #f97316; }
#obsTab.nav-tabs > li.active > a.obs-tab-pink { border-top: 2px solid #ec4899; }
#obsTab.nav-tabs > li.active > a.obs-tab-green { border-top: 2px solid #22c55e; }
#obsTab.nav-tabs > li.active > a.obs-tab-purple { border-top: 2px solid #a855f7; }
.obs-tab-content {
  border: 1px solid #e2e8f0; border-top: none;
  border-radius: 0 0 8px 8px;
  padding: 18px 14px; background: #fff;
  min-height: 200px;
}

/* — Keperawatan Modal — */
#modalKeperawatan .modal-header {
  background: linear-gradient(135deg, #1d4ed8, #0ea5e9);
  color: #fff; border-radius: 8px 8px 0 0;
  padding: 12px 18px; border-bottom: none;
}
#modalKeperawatan .modal-header .modal-title {
  font-size: 14px; font-weight: 700;
}
#modalKeperawatan .modal-header .close {
  color: #fff; opacity: .8; text-shadow: none; font-size: 20px;
}
#modalKeperawatan .modal-header .close:hover { opacity: 1; }
#modalKeperawatan .modal-content {
  border: none; border-radius: 8px;
  box-shadow: 0 8px 30px rgba(0,0,0,.18);
}
#modalKeperawatan .modal-body { padding: 18px 20px; }
#modalKeperawatan .modal-body label {
  font-size: 11.5px; font-weight: 700; color: #334155;
  text-transform: uppercase; letter-spacing: .3px;
}
#modalKeperawatan .modal-body .form-control {
  border-color: #d1d5db; border-radius: 6px; font-size: 12px;
}
#modalKeperawatan .modal-body .form-control:focus {
  border-color: #93c5fd; box-shadow: 0 0 0 3px rgba(59,130,246,.08);
}
#modalKeperawatan .modal-body textarea.form-control {
  resize: vertical; height: 55px !important;
}
#modalKeperawatan .modal-body .obs-sub-title {
  font-size: 12.5px; padding: 5px 10px;
  background: #f8fafc; border-left: 3px solid #94a3b8;
  border-radius: 0 6px 6px 0; margin: 10px 0 8px;
  color: #475569; font-weight: 700;
}
#modalKeperawatan .modal-footer {
  border-top: 1px solid #e2e8f0; padding: 12px 18px;
}
#modalKeperawatan .modal-footer .obs-btn-save {
  margin: 0;
}
#modalKeperawatan .modal-footer .btn-default {
  border-radius: 7px; font-size: 12px; font-weight: 600;
}

/* — Monitoring Modal — */
#modalMonitoring .modal-header {
  background: linear-gradient(135deg, #9d174d, #ec4899);
  color: #fff; border-radius: 8px 8px 0 0;
  padding: 12px 18px; border-bottom: none;
}
#modalMonitoring .modal-header .modal-title { font-size: 14px; font-weight: 700; }
#modalMonitoring .modal-header .close { color: #fff; opacity: .8; text-shadow: none; font-size: 20px; }
#modalMonitoring .modal-header .close:hover { opacity: 1; }
#modalMonitoring .modal-content {
  border: none; border-radius: 8px;
  box-shadow: 0 8px 30px rgba(0,0,0,.18);
}
#modalMonitoring .modal-body { padding: 18px 20px; }
#modalMonitoring .modal-body label {
  font-size: 11.5px; font-weight: 700; color: #334155;
}
#modalMonitoring .modal-body .form-control {
  border-color: #d1d5db; border-radius: 6px; font-size: 12px;
}
#modalMonitoring .modal-body .form-control:focus {
  border-color: #f9a8d4; box-shadow: 0 0 0 3px rgba(236,72,153,.08);
}
#modalMonitoring .modal-footer {
  border-top: 1px solid #e2e8f0; padding: 12px 18px;
}
#modalMonitoring .modal-footer .obs-btn-save { margin: 0; }
#modalMonitoring .modal-footer .btn-default {
  border-radius: 7px; font-size: 12px; font-weight: 600;
}
</style>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>
<script src="<?php echo base_url()?>assets/js/custom/chart.js"></script>
<script src="<?php echo base_url()?>assets/js/sweetalert2.all.min.js"></script>
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

  $('#jam_monitor6').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#jam_monitor6').timepicker('showWidget');
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
      "bPaginate": false,
      "bInfo": false,
      // "pageLength": 5,
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
      "bPaginate": false,
      "bInfo": false,
      // "pageLength": 5,
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
      "bPaginate": false,
      "bInfo": false,
      // "pageLength": 5,
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
      "bPaginate": false,
      "bInfo": false,
      // "pageLength": 5,
      "dom": 'rtip',
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_row_data_observasi_ri?no_kunjungan="+$('#no_kunjungan').val()+"&flag=btn_program_pemberian_obat",
          "type": "POST"
      },
  });

 

  // load grafik hemodinamik
  load_graph();

  // Fix DataTable columns & reload chart when switching tabs
  $('#obsTab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
    if($(e.target).attr('href') == '#tab_hemodinamik'){
      load_graph();
    }
  });

  // --- Modal Keperawatan ---
  // Open modal for adding new data
  $('#btn_add_keperawatan').click(function () {
    $('#id').val('');
    $('#jam_monitor5').val('');
    $('#intake_enteral').val('');
    $('#intake_parenteral').val('');
    $('#obat_enteral').val('');
    $('#obat_parenteral').val('');
    $('#polavent').val('');
    $('#lain_alergi').val('');
    $('#catatan').val('');
    $('#modalKeperawatanLabel').html('<i class="fa fa-plus"></i> Tambah Data Observasi Keperawatan');
    $('#modalKeperawatan').modal('show');
  });

  // Submit from modal
  $('#btn_save_work_day').click(function (e) {
    e.preventDefault();
    var btn_value = $(this).val();
    var formData = $('#form_pelayanan').serialize() + '&submit=' + btn_value;
    // Append modal fields (since modal is outside #form_pelayanan)
    formData += '&jam_monitor5=' + encodeURIComponent($('#jam_monitor5').val());
    formData += '&intake_enteral=' + encodeURIComponent($('#intake_enteral').val());
    formData += '&intake_parenteral=' + encodeURIComponent($('#intake_parenteral').val());
    formData += '&obat_enteral=' + encodeURIComponent($('#obat_enteral').val());
    formData += '&obat_parenteral=' + encodeURIComponent($('#obat_parenteral').val());
    formData += '&polavent=' + encodeURIComponent($('#polavent').val());
    formData += '&lain_alergi=' + encodeURIComponent($('#lain_alergi').val());
    formData += '&catatan=' + encodeURIComponent($('#catatan').val());

    $.ajax({
      url: $('#form_pelayanan').attr('action'),
      data: formData,
      dataType: 'json',
      type: 'POST',
      complete: function (xhr) {
        var jsonResponse = JSON.parse(xhr.responseText);
        if (jsonResponse.status === 200) {
          $('#modalKeperawatan').modal('hide');
          tbl_observasi_harian_keperawatan.ajax.reload();
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: jsonResponse.message,
            timer: 2000,
            showConfirmButton: false
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: jsonResponse.message
          });
        }
        achtungHideLoader();
      }
    });
  });

  // Init timepicker inside modal after shown
  $('#modalKeperawatan').on('shown.bs.modal', function () {
    $('#jam_monitor5').timepicker({
      minuteStep: 1,
      showSeconds: true,
      showMeridian: false,
      disableFocus: true,
      icons: { up: 'fa fa-chevron-up', down: 'fa fa-chevron-down' }
    });
  });

  // --- Modal Monitoring ---
  var monitoringFields = ['jam_monitor2','kesadaran','pupil','ref','gcs','sup','inf',
    'cm_enteral','cm_parenteral','cm_train','ck_urin','ck_ngt','ck_bab',
    'resp_pola','resp_tv','resp_rr','resp_fo2','resp_peep','cvp','catatan_monitoring'];

  $('#btn_add_monitoring').click(function () {
    $('#id').val('');
    $.each(monitoringFields, function(i, f){ $('#'+f).val(''); });
    $('#modalMonitoringLabel').html('<i class="fa fa-plus"></i> Tambah Data Monitoring Perkembangan');
    $('#modalMonitoring').modal('show');
  });

  // Submit from monitoring modal
  $('#btn_save_monitoring').click(function (e) {
    e.preventDefault();
    var btn_value = $(this).val();
    var formData = $('#form_pelayanan').serialize() + '&submit=' + btn_value;
    $.each(monitoringFields, function(i, f){
      formData += '&' + f + '=' + encodeURIComponent($('#'+f).val());
    });

    $.ajax({
      url: $('#form_pelayanan').attr('action'),
      data: formData,
      dataType: 'json',
      type: 'POST',
      complete: function (xhr) {
        var jsonResponse = JSON.parse(xhr.responseText);
        if (jsonResponse.status === 200) {
          $('#modalMonitoring').modal('hide');
          dt_montoring_perkembangan_pasien.ajax.reload();
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: jsonResponse.message,
            timer: 2000,
            showConfirmButton: false
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: jsonResponse.message
          });
        }
        achtungHideLoader();
      }
    });
  });

  // Init timepicker inside monitoring modal
  $('#modalMonitoring').on('shown.bs.modal', function () {
    $('#jam_monitor2').timepicker({
      minuteStep: 1,
      showSeconds: true,
      showMeridian: false,
      disableFocus: true,
      icons: { up: 'fa fa-chevron-up', down: 'fa fa-chevron-down' }
    });
  });

  // proses
  $('#btn_save_perkembangan_pasien, #btn_hemodinamik, #btn_deskripsi_lainnya, #btn_keseimbangan_cairan, #btn_program_pemberian_obat').click(function (e) {   
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
        $('#jam_monitor5').val(response_data.data.jam);
        $('#modalKeperawatanLabel').html('<i class="fa fa-edit"></i> Edit Data Observasi Keperawatan');
        $('#modalKeperawatan').modal('show');

      }

      if(flag == 'dt_hemodinamik'){
        
        $('#id').val(response_data.data.id);
        $('#jam_monitor').val(response_data.data.jam);
        $('#sistolik').val(response_data.data.sistolik);
        $('#diastolik').val(response_data.data.diastolik);
        $('#nd').val(response_data.data.nd);
        $('#sh').val(response_data.data.sh);
        $('#catatan_hemodinamik').val(response_data.data.catatan);

      }

      if(flag == 'btn_monitor_perkembangan_pasien'){

        $('#id').val(response_data.data.id);
        $('#jam_monitor2').val(response_data.data.jam);
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
        $('#modalMonitoringLabel').html('<i class="fa fa-edit"></i> Edit Data Monitoring Perkembangan');
        $('#modalMonitoring').modal('show');

      }

      if(flag == 'btn_keseimbangan_cairan'){
        
        $('#id').val(response_data.data.id);
        $('#jam_monitor6').val(response_data.data.jam);
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
        $('#jam_monitor4').val(response_data.data.jam);
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

    <div class="obs-page-title">
      <i class="fa fa-clipboard"></i>OBSERVASI HARIAN KEPERAWATAN PASIEN
    </div>

    <!-- hidden form -->
    <input type="hidden" name="tipe_monitoring" id="tipe_monitoring" value="UMUM">

    <!-- Date/ID row -->
    <div class="obs-meta-row obs-form">
      <div>
        <label>ID Record</label>
        <input type="text" name="id" id="id" readonly class="form-control" style="width:100px" placeholder="-">
      </div>
      <div>
        <label>Tanggal Observasi</label>
        <div class="input-group" style="width:160px">
          <input name="tgl_monitor" id="tgl_monitor" placeholder="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>">
          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>
      </div>
    </div>

    <!-- Observasi Tabs -->
    <ul class="nav nav-tabs" id="obsTab">
      <li class="active"><a data-toggle="tab" href="#tab_keperawatan" class="obs-tab-blue"><i class="fa fa-list-alt"></i> Keperawatan</a></li>
      <li><a data-toggle="tab" href="#tab_obat" class="obs-tab-blue"><i class="fa fa-medkit"></i> Obat / Cairan</a></li>
      <li><a data-toggle="tab" href="#tab_hemodinamik" class="obs-tab-orange"><i class="fa fa-heartbeat"></i> Hemodinamik</a></li>
      <li><a data-toggle="tab" href="#tab_monitoring" class="obs-tab-pink"><i class="fa fa-line-chart"></i> Monitoring</a></li>
      <li><a data-toggle="tab" href="#tab_catatan" class="obs-tab-green"><i class="fa fa-flask"></i> Catatan Khusus</a></li>
      <li><a data-toggle="tab" href="#tab_cairan" class="obs-tab-purple"><i class="fa fa-tint"></i> Keseimbangan Cairan</a></li>
    </ul>
    <div class="tab-content obs-tab-content">

    <!-- Tab 1: Rencana Keperawatan -->
    <div id="tab_keperawatan" class="tab-pane fade in active">
    <div class="row">
      <div class="col-md-12">

        <div style="margin-bottom: 12px">
          <button type="button" class="obs-btn-save" id="btn_add_keperawatan"><i class="fa fa-plus"></i> Tambah Data</button>
        </div>

        <div class="col-md-12 no-padding">
          <table class="table obs-table" style="margin-top: 10px" id="tbl_observasi_harian_keperawatan">
            <thead>
              <tr>
                <th rowspan="2" width="50px">#</th>
                <th rowspan="2" class="center" style="width: 120px">Tanggal</th>
                <th colspan="2" class="center">Intake</th>
                <th rowspan="2" class="center" style="width: 200px">Polavent</th>
                <th colspan="2" class="center">Obat</th>
                <th rowspan="2" class="center" style="width: 200px">Lain-lain (Alergi)</th>
                <th rowspan="2" class="center" style="width: 200px">Catatan Dokter</th>
              </tr>
              <tr>
                <th class="center" style="width: 130px">Enteral</th>
                <th class="center" style="width: 130px">Parenteral</th>
                <th class="center" style="width: 130px">Enteral/Lain-lain</th>
                <th class="center" style="width: 130px">Parenteral</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

      </div>
    </div>
    </div><!-- /tab_keperawatan -->

    <!-- Tab 2: Program Pemberian Obat -->
    <div id="tab_obat" class="tab-pane fade">
    <div class="row">
      <div class="col-md-12">
        
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
          <button type="submit" name="btn_program_pemberian_obat" value="btn_program_pemberian_obat" class="obs-btn-save" id="btn_program_pemberian_obat"><i class="fa fa-save"></i> Simpan</button>
        </div>
        <br>

        <div class="col-md-12 no-padding">
          <table class="table obs-table" style="margin-top: 10px" id="tbl_program_pemberian_obat">
            <thead>
              <tr>
                <th width="50px">#</th>
                <th class="center" style="width: 50px">Tanggal Jam</th>
                <th class="center" style="width: 50px">Petugas</th>
                <th class="left" style="width: 250px">Cairan Infus</th>
                <th class="left" style="width: 250px">Nutrisi Enteral</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

      </div>
    </div>
    </div><!-- /tab_obat -->

    <!-- Tab 3: Hemodinamik -->
    <div id="tab_hemodinamik" class="tab-pane fade">
    <div class="row">
      <div class="col-md-12">
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
          <div class="obs-vital-group obs-vg-td">1. TD (Tekanan Darah)</div>
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

          <div class="obs-vital-group obs-vg-nadi">2. FN (Frekuensi Nadi)</div>
          <br>
          <div class="form-group no-padding">
              <label class="control-label col-sm-6"> Nadi (bpm)</label>
              <div class="col-md-6">
                <input type="text" class="form-control" name="nd" id="nd" value="">
              </div>
          </div>
          <br>
          <div class="obs-vital-group obs-vg-suhu">3. SH (Suhu)</div>
          <br>
          <div class="form-group no-padding">
              <label class="control-label col-sm-6">Suhu (&#x2103;)</label>
              <div class="col-md-6">
                <input type="text" class="form-control" name="sh" id="sh" value="">
              </div>
          </div>
          <br>
          <div class="obs-vital-group obs-vg-spo2">4. SPO2</div>
          <br>
          <div class="form-group no-padding">
              <label class="control-label col-sm-6">Saturasi Oksigen</label>
              <div class="col-md-6">
                <input type="text" class="form-control" name="spo2" id="spo2" value="">
              </div>
          </div>
          
          <br>
          <div>
            <label style="font-weight:600;color:#475569;font-size:12px">Catatan Pemeriksaan</label><br>
            <textarea class="form-control" style="height: 100px !important;" name="catatan_hemodinamik" id="catatan_hemodinamik"></textarea>
          </div>
          <br>
          <div class="col-md-12 no-padding" style="padding-top: 3px !important">
            <button type="submit" name="btn_hemodinamik" value="btn_hemodinamik" class="obs-btn-save" id="btn_hemodinamik"><i class="fa fa-save"></i> Simpan</button>
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
                <p style="font-size: 13px; font-weight: 700; color: #9a3412; letter-spacing: 2px; margin-bottom: 8px">H E M O D I N A M I K</p>
                <table class="table obs-table" id="dt_hemodinamik">
                  <thead>
                    <tr>
                      <th style="width: 70px">#</th>
                      <th class="center" style="width: 100px">Tanggal Jam</th>
                      <th class="center" style="width: 70px">Petugas</th>
                      <th class="center" style="width: 50px">Sistolik<br>(mmHg)</th>
                      <th class="center" style="width: 50px">Diastolik<br>(mmHg)</th>
                      <th class="center" style="width: 50px">Nadi<br>(bpm)</th>
                      <th class="center" style="width: 50px">Suhu<br>(&#x2103;)</th>
                      <th class="center" style="width: 100px">Catatan</th>
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
    </div><!-- /tab_hemodinamik -->

    <!-- Tab 4: Monitoring Perkembangan -->
    <div id="tab_monitoring" class="tab-pane fade">
    <div class="row">
      <div class="col-md-12">

        <div style="margin-bottom: 12px">
          <button type="button" class="obs-btn-save" id="btn_add_monitoring"><i class="fa fa-plus"></i> Tambah Data</button>
        </div>

        <table class="table obs-table" id="dt_montoring_perkembangan_pasien">
          <thead>
          <tr>
            <th rowspan="2" width="70px">#</th>
            <th rowspan="2" width="80px" class="center">Tanggal/Jam</th>
            <th colspan="4" class="center">SSP</th>
            <th colspan="2" class="center">Motorik</th>
            <th colspan="3" class="center">Cairan Masuk</th>
            <th colspan="3" class="center">Cairan Keluar</th>
            <th colspan="5" class="center">Respirasi</th>
            <th rowspan="2" class="center">CVP</th>
            <th rowspan="2" class="center">Catatan</th>
          </tr>
          <tr>
            <th class="center" style="font-size: 10px; width: 50px">Kes.</th>
            <th class="center" style="font-size: 10px; width: 50px">Pupil</th>
            <th class="center" style="font-size: 10px; width: 50px">Ref.</th>
            <th class="center" style="font-size: 10px; width: 50px">GCS</th>
            <th class="center" style="font-size: 10px; width: 50px">Sup.</th>
            <th class="center" style="font-size: 10px; width: 50px">Inf.</th>
            <th class="center" style="font-size: 10px; width: 50px">Ent.</th>
            <th class="center" style="font-size: 10px; width: 50px">Par.</th>
            <th class="center" style="font-size: 10px; width: 50px">Train.</th>
            <th class="center" style="font-size: 10px; width: 50px">Urin</th>
            <th class="center" style="font-size: 10px; width: 50px">NGT</th>
            <th class="center" style="font-size: 10px; width: 50px">BAB</th>
            <th class="center" style="font-size: 10px; width: 50px">Pola</th>
            <th class="center" style="font-size: 10px; width: 50px">TV</th>
            <th class="center" style="font-size: 10px; width: 50px">RR</th>
            <th class="center" style="font-size: 10px; width: 50px">FO2%</th>
            <th class="center" style="font-size: 10px; width: 50px">Peep</th>
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
    </div><!-- /tab_monitoring -->

    <!-- Tab 5: Catatan Khusus -->
    <div id="tab_catatan" class="tab-pane fade">
    <div class="row">
      <div class="col-md-12">
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
          <label style="font-weight:600;color:#475569;font-size:12px">Penjelasan Jenis Cairan / Catatan Khusus Lainnya</label><br>
          <textarea class="form-control" style="height: 100px !important;" name="catatan_khusus" id="catatan_khusus"></textarea>
        </div>
        <div class="col-md-12 no-padding" style="padding-top: 3px !important; padding-bottom: 10px !important">
          <button type="submit" name="btn_deskripsi_lainnya" value="btn_deskripsi_lainnya" class="obs-btn-save" id="btn_deskripsi_lainnya"><i class="fa fa-save"></i> Simpan</button>
        </div>
        
        <table class="table obs-table" id="dt_deskripsi_lainnya">
          <thead>
            <tr>
              <th style="width: 80px">#</th>
              <th class="center" style="width: 100px">Jam</th>
              <th class="center">Deskripsi / Jenis Cairan / Catatan Khusus</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table> 

      </div>
    </div>
    </div><!-- /tab_catatan -->

    <!-- Tab 6: Keseimbangan Cairan -->
    <div id="tab_cairan" class="tab-pane fade">
    <div class="row">
      <div class="col-md-12">

          <div class="form-group">
              <label class="control-label col-sm-1" for="">*Jam Input</label>
              <div class="col-md-2">
                <div class="input-group">
                    <input id="jam_monitor6" name="jam_monitor6"  type="text" class="form-control" value="<?php echo date('H:i:s')?>">
                    <span class="input-group-addon">
                      <i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
              </div>
          </div>
          
          <div class="obs-info-box">
            <div style="font-weight:700;font-size:13px;color:#1e40af;margin-bottom:4px"><i class="fa fa-info-circle" style="margin-right:4px"></i> IWL (Insensible Water Loss)</div>
            <p style="margin:0;font-size:12px;color:#475569">Kehilangan cairan tidak terukur melalui kulit dan paru-paru. Rumus: <b>IWL = (Konstanta x Berat Badan x Total Jam) / 24</b></p>
          </div>
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
          <div class="obs-info-box">
            <div style="font-weight:700;font-size:13px;color:#6b21a8;margin-bottom:4px"><i class="fa fa-calculator" style="margin-right:4px"></i> Balans Cairan</div>
            <p style="margin:0;font-size:12px;color:#475569">Selisih antara cairan masuk dan cairan keluar termasuk IWL. Rumus: <b>Balans = Cairan Masuk - (Cairan Keluar + IWL)</b></p>
          </div>

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
            <button type="submit" name="btn_keseimbangan_cairan" value="btn_keseimbangan_cairan" class="obs-btn-save" id="btn_keseimbangan_cairan"><i class="fa fa-save"></i> Simpan</button>
          </div>

          <table class="table obs-table" id="dt_keseimbangan_cairan">
          <thead>
            <tr>
              <th rowspan="2" style="width: 50px">#</th>
              <th class="center" rowspan="2" style="width: 100px">Tanggal/ Jam</th>
              <th class="center" rowspan="2" style="width: 100px">Nama Petugas</th>
              <th class="center" colspan="4">IWL</th>
              <th class="center" rowspan="2" style="width: 80px">Cairan Masuk<br>(Ml)</th>
              <th class="center" rowspan="2" style="width: 80px">Cairan Keluar<br>(Ml)</th>
              <th class="center" rowspan="2" style="width: 60px">Nilai Balans<br>(Ml)</th>
            </tr>
            <tr>
              <th class="center" style="width: 60px">Konstanta</th>
              <th class="center" style="width: 60px">Berat Badan<br>(Kg)</th>
              <th class="center" style="width: 60px">Total Jam<br>(Jam)</th>
              <th class="center" style="width: 60px">Nilai IWL<br>(Ml)</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
          
      </div>
    </div>
    </div><!-- /tab_cairan -->
    </div><!-- /tab-content -->

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

<!-- Modal Keperawatan -->
<div class="modal fade" id="modalKeperawatan" tabindex="-1" role="dialog" aria-labelledby="modalKeperawatanLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalKeperawatanLabel"><i class="fa fa-list-alt"></i> Data Observasi Keperawatan</h4>
      </div>
      <div class="modal-body">

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Jam Monitor</label>
              <div class="input-group">
                <input id="jam_monitor5" name="jam_monitor5" type="text" class="form-control">
                <span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span>
              </div>
            </div>
          </div>
        </div>

        <div class="obs-sub-title"><i class="fa fa-sign-in"></i> INTAKE</div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Enteral</label>
              <textarea class="form-control" name="intake_enteral" id="intake_enteral"></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Parenteral</label>
              <textarea class="form-control" name="intake_parenteral" id="intake_parenteral"></textarea>
            </div>
          </div>
        </div>

        <div class="obs-sub-title"><i class="fa fa-medkit"></i> PEMBERIAN OBAT</div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Enteral / Lain-lain</label>
              <textarea class="form-control" name="obat_enteral" id="obat_enteral"></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Parenteral</label>
              <textarea class="form-control" name="obat_parenteral" id="obat_parenteral"></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Polavent</label>
              <textarea class="form-control" name="polavent" id="polavent"></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Lain-lain (Alergi)</label>
              <textarea class="form-control" name="lain_alergi" id="lain_alergi"></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Catatan Dokter</label>
              <textarea class="form-control" name="catatan" id="catatan" style="height: 100px !important;"></textarea>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button> -->
        <button type="button" class="obs-btn-save" id="btn_save_work_day" value="btn_work_day"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Monitoring Perkembangan -->
<div class="modal fade" id="modalMonitoring" tabindex="-1" role="dialog" aria-labelledby="modalMonitoringLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalMonitoringLabel"><i class="fa fa-line-chart"></i> Data Monitoring Perkembangan Pasien</h4>
      </div>
      <div class="modal-body">

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Jam Input</label>
              <div class="input-group">
                <input id="jam_monitor2" name="jam_monitor2" type="text" class="form-control">
                <span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span>
              </div>
            </div>
          </div>
        </div>

        <div class="obs-vital-group obs-vg-ssp" style="margin-top:4px">SSP (Sistem Saraf Pusat)</div>
        <div class="row" style="margin-top:8px">
          <div class="col-md-3">
            <div class="form-group">
              <label>Kesadaran</label>
              <input type="text" class="form-control" name="kesadaran" id="kesadaran">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Pupil</label>
              <input type="text" class="form-control" name="pupil" id="pupil">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Ref.</label>
              <input type="text" class="form-control" name="ref" id="ref">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>GCS</label>
              <input type="text" class="form-control" name="gcs" id="gcs" onclick="show_modal_medium('pelayanan/Pl_pelayanan_ri/info_gcs', 'INFORMASI GCS (Glasgow Coma Scale)')">
            </div>
          </div>
        </div>

        <div class="obs-vital-group obs-vg-motor">Motorik</div>
        <div class="row" style="margin-top:8px">
          <div class="col-md-3">
            <div class="form-group">
              <label>Sup.</label>
              <input type="text" class="form-control" name="sup" id="sup">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Inf.</label>
              <input type="text" class="form-control" name="inf" id="inf">
            </div>
          </div>
        </div>

        <div class="obs-vital-group obs-vg-cm">CM (Cairan Masuk)</div>
        <div class="row" style="margin-top:8px">
          <div class="col-md-4">
            <div class="form-group">
              <label>Enteral</label>
              <input type="text" class="form-control" name="cm_enteral" id="cm_enteral">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Parenteral</label>
              <input type="text" class="form-control" name="cm_parenteral" id="cm_parenteral">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Train.</label>
              <input type="text" class="form-control" name="cm_train" id="cm_train">
            </div>
          </div>
        </div>

        <div class="obs-vital-group obs-vg-ck">CK (Cairan Keluar)</div>
        <div class="row" style="margin-top:8px">
          <div class="col-md-4">
            <div class="form-group">
              <label>Urin</label>
              <input type="text" class="form-control" name="ck_urin" id="ck_urin">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>NGT</label>
              <input type="text" class="form-control" name="ck_ngt" id="ck_ngt">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>BAB</label>
              <input type="text" class="form-control" name="ck_bab" id="ck_bab">
            </div>
          </div>
        </div>

        <div class="obs-vital-group obs-vg-resp">Respirasi</div>
        <div class="row" style="margin-top:8px">
          <div class="col-md-2">
            <div class="form-group">
              <label>Pola</label>
              <input type="text" class="form-control" name="resp_pola" id="resp_pola">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label>TV</label>
              <input type="text" class="form-control" name="resp_tv" id="resp_tv">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label>RR</label>
              <input type="text" class="form-control" name="resp_rr" id="resp_rr">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>FO2%</label>
              <input type="text" class="form-control" name="resp_fo2" id="resp_fo2">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Peep</label>
              <input type="text" class="form-control" name="resp_peep" id="resp_peep">
            </div>
          </div>
        </div>

        <div class="obs-vital-group obs-vg-cvp">CVP</div>
        <div class="row" style="margin-top:8px">
          <div class="col-md-3">
            <div class="form-group">
              <label>CVP</label>
              <input type="text" class="form-control" name="cvp" id="cvp">
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>Catatan</label>
              <input type="text" class="form-control" name="catatan_monitoring" id="catatan_monitoring">
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
        <button type="button" class="obs-btn-save" id="btn_save_monitoring" value="btn_monitor_perkembangan_pasien"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>







