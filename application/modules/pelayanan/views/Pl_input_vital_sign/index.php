<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script>

jQuery(function($) {
  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});

var selectedPatient = null;

$(document).ready(function(){

  // Load worklist on page load
  loadWorklist();

  // Date change reloads worklist
  $('#tgl_kunjungan_ttv').change(function(){
    loadWorklist();
  });

  // Search filter
  $('#search_keyword_ttv').keyup(function(){
    filterWorklist();
  });

  // Search by change
  $('select[name="search_by_ttv"]').change(function(){
    loadWorklist();
  });

  // Enter key on search
  $('#search_keyword_ttv').keypress(function(e){
    if(e.which == 13){
      e.preventDefault();
      loadWorklist();
    }
  });

});

function loadWorklist(){
  var tgl = $('#tgl_kunjungan_ttv').val() || '<?php echo date('Y-m-d')?>';
  var keyword = $('#search_keyword_ttv').val() || '';
  var search_by = $('select[name="search_by_ttv"]').val() || '';

  var params = 'from_tgl=' + tgl;
  if(keyword && search_by){
    params += '&search_by=' + search_by + '&keyword=' + keyword;
  }

  $('#ttv-worklist-body').html('<tr><td style="padding:30px;text-align:center;color:#94a3b8;"><i class="fa fa-spinner fa-spin" style="font-size:22px;"></i><br>Memuat data...</td></tr>');

  $.ajax({
    url: 'pelayanan/Pl_input_vital_sign/get_data?' + params,
    type: 'POST',
    data: { draw: 1, start: 0, length: -1, search: { value: '' } },
    dataType: 'json',
    success: function(response){
      var data = response.data;
      $('#ttv-worklist-body').empty();
      $('#ttv_total_pasien').text(data.length);

      var sudahIsi = 0;
      var belumIsi = 0;

      if(!data || data.length === 0){
        $('#ttv-worklist-body').html('<tr><td style="padding:30px;text-align:center;color:#94a3b8;"><i class="fa fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>Tidak ada data pasien</td></tr>');
        $('#ttv_sudah_isi').text(0);
        $('#ttv_belum_isi').text(0);
        return;
      }

      $.each(data, function(i, row){
        // row[2] = no_kunjungan, row[3] = no_registrasi
        // row[4] = No, row[5] = No MR, row[6] = Nama Pasien
        // row[7] = Penjamin, row[8] = Tujuan, row[9] = Tgl Kunjungan
        // row[10-14] = vital sign inputs (HTML), row[15] = Assesmen

        var noMr = stripHtml(row[5]);
        var namaPasien = stripHtml(row[6]);
        var penjamin = stripHtml(row[7]);
        var tujuan = stripHtml(row[8]);
        var tglKunjungan = stripHtml(row[9]);
        var noKunjungan = row[2];
        var noRegistrasi = row[3];

        // Extract vital sign values from HTML inputs
        var tb = extractInputValue(row[10]);
        var bb = extractInputValue(row[11]);
        var td_val = extractInputValue(row[12]);
        var nadi = extractInputValue(row[13]);
        var suhu = extractInputValue(row[14]);
        // row[15] = Assesmen, row[16] = resep_iter, row[17] = jumlah_iter
        // row[18] = updated_by (petugas TTV), row[19] = updated_at (waktu TTV)
        var resepIter  = row[16] || '';
        var jumlahIter = row[17] || '';
        var ttvPetugas = row[18] || '';
        var ttvWaktu   = row[19] || '';

        var hasTTV = (tb || bb || td_val || nadi || suhu);
        if(hasTTV) sudahIsi++; else belumIsi++;

        var statusClass = hasTTV ? 'ttv-filled' : 'ttv-empty';
        var statusBadge = hasTTV
          ? '<span class="ttv-badge ttv-badge-filled"><i class="fa fa-check-circle"></i> Terisi</span>'
          : '<span class="ttv-badge ttv-badge-empty"><i class="fa fa-exclamation-circle"></i> Belum</span>';

        var isSelected = (selectedPatient && selectedPatient.noKunjungan == noKunjungan) ? 'ttv-selected' : '';

        var html = '<tr class="ttv-wl-item ' + statusClass + ' ' + isSelected + '" ' +
          'onclick="selectPatient(\'' + noKunjungan + '\', \'' + noRegistrasi + '\', \'' + escape(namaPasien) + '\', \'' + escape(noMr) + '\', \'' + escape(penjamin) + '\', \'' + escape(tujuan) + '\', \'' + escape(tglKunjungan) + '\', \'' + escape(tb) + '\', \'' + escape(bb) + '\', \'' + escape(td_val) + '\', \'' + escape(nadi) + '\', \'' + escape(suhu) + '\', \'' + escape(resepIter) + '\', \'' + escape(jumlahIter) + '\', \'' + escape(ttvPetugas) + '\', \'' + escape(ttvWaktu) + '\')">' +
          '<td><div class="ttv-wl-card">' +
          '<div class="ttv-wl-top">' +
          '<span class="ttv-wl-no">' + (i+1) + '</span>' +
          statusBadge +
          '</div>' +
          '<div class="ttv-wl-name">' + namaPasien + '</div>' +
          '<div class="ttv-wl-meta"><i class="fa fa-id-card-o"></i> ' + noMr + '</div>' +
          '<div class="ttv-wl-meta"><i class="fa fa-hospital-o"></i> ' + tujuan + '</div>' +
          '</div></td></tr>';

        $('#ttv-worklist-body').append(html);
      });

      $('#ttv_sudah_isi').text(sudahIsi);
      $('#ttv_belum_isi').text(belumIsi);
    }
  });
}

function filterWorklist(){
  var filter = $('#search_keyword_ttv').val().toUpperCase();
  var table = document.getElementById('ttv-worklist-body');
  if(!table) return;
  var tr = table.getElementsByTagName('tr');
  for(var i = 0; i < tr.length; i++){
    var td = tr[i].getElementsByTagName('td');
    var found = false;
    for(var j = 0; j < td.length; j++){
      if(td[j].textContent.toUpperCase().indexOf(filter) > -1){
        found = true;
      }
    }
    tr[i].style.display = found ? '' : 'none';
  }
}

function stripHtml(html){
  var tmp = document.createElement('div');
  tmp.innerHTML = html;
  return tmp.textContent || tmp.innerText || '';
}

function extractInputValue(html){
  var match = html ? html.match(/value="([^"]*)"/) : null;
  return match ? match[1] : '';
}

function selectPatient(noKunjungan, noRegistrasi, namaPasien, noMr, penjamin, tujuan, tglKunjungan, tb, bb, td_val, nadi, suhu, resepIter, jumlahIter, ttvPetugas, ttvWaktu){

  selectedPatient = {
    noKunjungan: noKunjungan,
    noRegistrasi: noRegistrasi,
    namaPasien: unescape(namaPasien),
    noMr: unescape(noMr),
    penjamin: unescape(penjamin),
    tujuan: unescape(tujuan),
    tglKunjungan: unescape(tglKunjungan),
    tb: unescape(tb),
    bb: unescape(bb),
    td: unescape(td_val),
    nadi: unescape(nadi),
    suhu: unescape(suhu),
    resepIter: unescape(resepIter || ''),
    jumlahIter: unescape(jumlahIter || ''),
    ttvPetugas: unescape(ttvPetugas || ''),
    ttvWaktu: unescape(ttvWaktu || '')
  };

  // Highlight selected
  $('.ttv-wl-item').removeClass('ttv-selected');
  $(event.currentTarget).closest('tr').addClass('ttv-selected');

  // Fill patient info card
  $('#ttv_patient_name').text(selectedPatient.namaPasien);
  $('#ttv_patient_mr').text(selectedPatient.noMr);
  $('#ttv_patient_penjamin').text(selectedPatient.penjamin);
  $('#ttv_patient_tujuan').text(selectedPatient.tujuan);
  $('#ttv_patient_tgl').text(selectedPatient.tglKunjungan);

  // Auto-fill TTS announcement text (nama dibersihkan dari prefiks & gelar)
  var cleanName    = cleanPatientName(selectedPatient.namaPasien);
  var announceTujuan = 'silahkan menuju ke meja tensi';
  $('#ttv_tts_text').val('Pasien atas nama ' + cleanName.toLowerCase() + ', ' + announceTujuan);

  // Fill TTV form
  $('#ttv_tinggi_badan').val(selectedPatient.tb);
  $('#ttv_berat_badan').val(selectedPatient.bb);
  $('#ttv_tekanan_darah').val(selectedPatient.td);
  $('#ttv_nadi').val(selectedPatient.nadi);
  $('#ttv_suhu').val(selectedPatient.suhu);

  // Fill resep iter from saved data
  if(selectedPatient.resepIter === 'Y'){
    $('input[name="resep_iter"][value="Y"]').prop('checked', true);
    $('#ttv_iter_detail').show();
    if(selectedPatient.jumlahIter){
      $('input[name="jumlah_iter"][value="' + selectedPatient.jumlahIter + '"]').prop('checked', true);
    } else {
      $('input[name="jumlah_iter"]').prop('checked', false);
    }
  } else {
    $('input[name="resep_iter"][value="N"]').prop('checked', true);
    $('input[name="jumlah_iter"]').prop('checked', false);
    $('#ttv_iter_detail').hide();
  }

  // Show/hide TTV audit info banner
  var hasTTV = (selectedPatient.tb || selectedPatient.bb || selectedPatient.td || selectedPatient.nadi || selectedPatient.suhu);
  if(hasTTV && (selectedPatient.ttvPetugas || selectedPatient.ttvWaktu)){
    var petugasText = selectedPatient.ttvPetugas || '-';
    var waktuText   = selectedPatient.ttvWaktu   || '-';
    $('#ttv_audit_petugas').text(petugasText);
    $('#ttv_audit_waktu').text(waktuText);
    $('#ttv-audit-banner').show();
  } else {
    $('#ttv-audit-banner').hide();
  }

  // Show form area
  $('#ttv-form-area').show();
  $('#ttv-empty-state').hide();

  // Load riwayat medis — reset ke tab pertama
  $('#ttv_riwayat_tab li').removeClass('active');
  $('#ttv_riwayat_tab li:first').addClass('active');
  loadRiwayatMedisAll(selectedPatient.noMr, selectedPatient.noRegistrasi, selectedPatient.noKunjungan);
}

function saveAllTTV(){
  if(!selectedPatient){
    $.achtung({message: 'Silahkan pilih pasien terlebih dahulu!', timeout:5, className: 'achtungFail'});
    return;
  }

  var resepIter = $('input[name="resep_iter"]:checked').val() || 'N';
  var jumlahIter = (resepIter === 'Y') ? ($('input[name="jumlah_iter"]:checked').val() || '') : '';

  // Validasi: jika resep iter Ya, jumlah harus dipilih
  if(resepIter === 'Y' && !jumlahIter){
    $.achtung({message: 'Silahkan pilih jumlah iter!', timeout:5, className: 'achtungFail'});
    return;
  }

  var formData = {
    no_registrasi : selectedPatient.noRegistrasi,
    no_mr : $('#ttv_patient_mr').text(),
    no_kunjungan  : selectedPatient.noKunjungan,
    tinggi_badan  : $('#ttv_tinggi_badan').val(),
    berat_badan   : $('#ttv_berat_badan').val(),
    tekanan_darah : $('#ttv_tekanan_darah').val(),
    nadi          : $('#ttv_nadi').val(),
    suhu          : $('#ttv_suhu').val(),
    resep_iter    : resepIter,
    jumlah_iter   : jumlahIter
  };

  achtungShowLoader();

  $.ajax({
    url: 'pelayanan/Pl_input_vital_sign/process_all',
    data: formData,
    dataType: 'json',
    type: 'POST',
    complete: function(xhr){
      var jsonResponse = JSON.parse(xhr.responseText);
      if(jsonResponse.status === 200){
        $.achtung({message: jsonResponse.message, timeout:5});
        loadWorklist();
      } else {
        $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
      }
      achtungHideLoader();
    }
  });
}

function loadRiwayatMedis(noMr, noRegistrasi, noKunjungan){
  $('#ttv_riwayat_content').html('<div style="padding:30px;text-align:center;color:#94a3b8;"><i class="fa fa-spinner fa-spin" style="font-size:18px;"></i><br>Memuat riwayat...</div>');
  $('#ttv-riwayat-empty').hide();
  $('#ttv_riwayat_content').show();

  $.getJSON('pelayanan/Pl_pelayanan/view_detail_resume_medis_sidebar/' + noRegistrasi + '/' + noKunjungan, '', function(data){
    if(data && data.html){
      $('#ttv_riwayat_content').html(data.html);
    } else {
      $('#ttv_riwayat_content').html('<div style="padding:20px;text-align:center;color:#94a3b8;">Tidak ada data riwayat</div>');
    }
  }).fail(function(){
    $('#ttv_riwayat_content').html('<div style="padding:20px;text-align:center;color:#94a3b8;">Gagal memuat riwayat</div>');
  });
}

function loadRiwayatMedisAll(noMr){
  $('#ttv_riwayat_content').html('<div style="padding:30px;text-align:center;color:#94a3b8;"><i class="fa fa-spinner fa-spin" style="font-size:18px;"></i><br>Memuat riwayat...</div>');
  $('#ttv-riwayat-empty').hide();
  $('#ttv_riwayat_content').show();

  $.getJSON('templates/References/get_riwayat_medis/' + noMr, '', function(data){
    if(data && data.html){
      $('#ttv_riwayat_content').html(data.html);
    } else {
      $('#ttv_riwayat_content').html('<div style="padding:20px;text-align:center;color:#94a3b8;">Tidak ada data riwayat</div>');
    }
  }).fail(function(){
    $('#ttv_riwayat_content').html('<div style="padding:20px;text-align:center;color:#94a3b8;">Gagal memuat riwayat</div>');
  });
}

function loadRiwayatPerjanjian(noMr){
  $('#ttv_riwayat_content').html('<div style="padding:30px;text-align:center;color:#94a3b8;"><i class="fa fa-spinner fa-spin" style="font-size:18px;"></i><br>Memuat perjanjian...</div>');
  $('#ttv-riwayat-empty').hide();
  $('#ttv_riwayat_content').show();

  $.getJSON('templates/References/get_riwayat_perjanjian/' + noMr, '', function(data){
    if(data && data.html){
      $('#ttv_riwayat_content').html(data.html);
    } else {
      $('#ttv_riwayat_content').html('<div style="padding:20px;text-align:center;color:#94a3b8;">Tidak ada data perjanjian</div>');
    }
  }).fail(function(){
    $('#ttv_riwayat_content').html('<div style="padding:20px;text-align:center;color:#94a3b8;">Gagal memuat data perjanjian</div>');
  });
}

function toggleIterOption(){
  var val = $('input[name="resep_iter"]:checked').val();
  if(val === 'Y'){
    $('#ttv_iter_detail').slideDown(200);
  } else {
    $('#ttv_iter_detail').slideUp(200);
    $('input[name="jumlah_iter"]').prop('checked', false);
  }
}

// ── Bersihkan nama pasien dari prefiks & gelar ────────────────────
function cleanPatientName(fullName) {
  if (!fullName) return '';
  var name = fullName;

  // 1. Hapus prefiks gender/honorifik di awal (bisa bertumpuk, mis. "By. Ny.")
  //    Mencakup: Ny, Tn, Nn, An, By, Bp, Bpk, Ibu, Bapak, Sdr, Sdri,
  //              dr, drs, drg, Prof, Apt, H, Hj — dengan atau tanpa titik/spasi
  var leadingPrefix = /^(?:(?:ny|tn|nn|an|by|bp|bpk|bapak|ibu|sdr|sdri|dr|drs|drg|prof|apt|hj?)\s*\.?\s*)+/gi;
  var prev;
  do { prev = name; name = name.replace(leadingPrefix, ''); } while (name !== prev);

  // 2. Hapus semua bagian setelah koma pertama (gelar akademik biasanya di sini)
  //    Mis. "Budi Santoso, S.E., M.M." → "Budi Santoso"
  name = name.replace(/,.*$/, '');

  // 3. Hapus kata berformat singkatan/gelar yang mengandung titik di tengah
  //    Mis. S.Ked., M.Kes., Sp.OG, S.T., A.Md.
  name = name.replace(/\b\w+\.\w[\w.]*\b/g, '');

  // 4. Hapus sisa singkatan huruf tunggal diikuti titik (mis. "H." "S.")
  name = name.replace(/\b[A-Za-z]\.\s?/g, '');

  // 5. Normalisasi spasi ganda & trim
  name = name.replace(/\s+/g, ' ').trim();

  return name;
}

// ── Panggil Pasien (TTS) ─────────────────────────────────────────
var ttvSynth  = window.speechSynthesis;
var ttvVoices = [];

function ttvLoadVoices(){
  ttvVoices = ttvSynth.getVoices();
}
ttvLoadVoices();
if(ttvSynth.onvoiceschanged !== undefined){
  ttvSynth.onvoiceschanged = ttvLoadVoices;
}

function ttvSpeak(){
  if(!selectedPatient){
    $.achtung({message: 'Silahkan pilih pasien terlebih dahulu!', timeout:5, className:'achtungFail'});
    return;
  }
  var text = $('#ttv_tts_text').val();
  if(!text){ return; }

  ttvSynth.cancel();
  var utter = new SpeechSynthesisUtterance(text);
  utter.rate  = parseFloat($('#ttv_tts_rate').val())  || 1;
  utter.pitch = parseFloat($('#ttv_tts_pitch').val()) || 1;

  // Prefer Indonesian voice if available
  var idVoice = null;
  for(var i = 0; i < ttvVoices.length; i++){
    if(ttvVoices[i].lang.toLowerCase().indexOf('id') === 0){
      idVoice = ttvVoices[i]; break;
    }
  }
  if(idVoice) utter.voice = idVoice;

  var $btn = $('#btn_panggil_ttv');
  $btn.prop('disabled', true).html('<i class="fa fa-volume-up fa-pulse"></i> Memanggil...');
  utter.onend  = function(){ $btn.prop('disabled', false).html('<i class="fa fa-bullhorn"></i> Panggil'); };
  utter.onerror = function(){ $btn.prop('disabled', false).html('<i class="fa fa-bullhorn"></i> Panggil'); };

  ttvSynth.speak(utter);

  // Log to backend
  $.getJSON('pelayanan/Pl_pelayanan/callPatient', {
    no_kunjungan: selectedPatient.noKunjungan,
    dokter: '',
    poli: ''
  });
}

// Rate & Pitch display update
$(document).on('input', '#ttv_tts_rate',  function(){ $('#ttv_tts_rate_val').text($(this).val()); });
$(document).on('input', '#ttv_tts_pitch', function(){ $('#ttv_tts_pitch_val').text($(this).val()); });

// Collapsible TTS settings toggle
function ttvToggleTtsSettings(){
  $('#ttv-tts-settings').slideToggle(200, function(){
    var open = $(this).is(':visible');
    $('#ttv-tts-chevron').css('transform', open ? 'rotate(180deg)' : 'rotate(0deg)');
  });
}

// Keep old function for backward compatibility
function save_vital_sign(type, no_kunjungan, no_registrasi){
  var formData = {
    no_registrasi: no_registrasi,
    no_kunjungan: no_kunjungan,
    type: type,
    value: $('#'+type+'_'+no_kunjungan).val(),
  };
  $.ajax({
    url: 'pelayanan/Pl_input_vital_sign/process',
    data: formData,
    dataType: 'json',
    type: 'POST',
    complete: function(xhr){
      var data = xhr.responseText;
      var jsonResponse = JSON.parse(data);
      if(jsonResponse.status === 200){
        $.achtung({message: jsonResponse.message, timeout:5});
      } else {
        $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
      }
      achtungHideLoader();
    }
  });
}

</script>

<style type="text/css">
  /* ── Scoped to #ttv-wrap ─────────────────────────────────────── */
  #ttv-wrap {
    font-family: 'Segoe UI', system-ui, Arial, sans-serif;
    font-size: 13px;
  }

  /* ── Stats Bar ───────────────────────────────────────────────── */
  .ttv-stats-bar {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    background: #ffffff;
    border-top: 4px solid #0ea5e9;
    padding: 14px 20px;
    border-radius: 12px 12px 0 0;
    border-left: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
  }
  .ttv-stats-bar::before {
    content: '';
    position: absolute;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(14,165,233,0.07) 0%, transparent 70%);
    right: -40px; top: -80px;
    pointer-events: none;
  }
  .ttv-title-info {
    display: flex; align-items: center; gap: 12px;
    flex: 1; min-width: 220px; position: relative;
  }
  .ttv-title-icon {
    width: 48px; height: 48px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #0ea5e9, #0369a1);
    color: #fff; font-size: 22px;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(14,165,233,.25);
  }
  .ttv-title-name {
    font-size: 16px; font-weight: 700; color: #0f172a;
    display: block; line-height: 1.3;
  }
  .ttv-title-sub {
    font-size: 11.5px; color: #64748b;
    display: block; margin-top: 2px;
  }
  .ttv-stats-group {
    display: flex; align-items: center; gap: 8px;
    flex-wrap: wrap; position: relative;
  }
  .ttv-stat-pill {
    display: flex; flex-direction: column; align-items: center;
    padding: 6px 16px; border-radius: 9px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    min-width: 72px; cursor: default;
    transition: background .18s, box-shadow .18s;
  }
  .ttv-stat-pill:hover { background: #f1f5f9; box-shadow: 0 2px 8px rgba(0,0,0,.07); }
  .ttv-stat-pill.ttv-blue  { background: #eff8ff;  border-color: #bae6fd; }
  .ttv-stat-pill.ttv-green { background: #f0fdf4;  border-color: #bbf7d0; }
  .ttv-stat-pill.ttv-orange{ background: #fff7ed;  border-color: #fed7aa; }
  .ttv-stat-num {
    font-size: 22px; font-weight: 800; line-height: 1;
  }
  .ttv-stat-pill.ttv-blue .ttv-stat-num {
    background: linear-gradient(135deg, #0369a1, #0ea5e9);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .ttv-stat-pill.ttv-green .ttv-stat-num {
    background: linear-gradient(135deg, #15803d, #22c55e);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .ttv-stat-pill.ttv-orange .ttv-stat-num {
    background: linear-gradient(135deg, #c2410c, #f97316);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .ttv-stat-lbl { font-size: 10px; color: #94a3b8; margin-top: 3px; text-transform: uppercase; letter-spacing: 0.5px; }

  /* ── Body wrapper ────────────────────────────────────────────── */
  .ttv-body {
    background: #f1f5f9;
    border: 1px solid #dde3ec;
    border-top: none;
    border-radius: 0 0 12px 12px;
    padding: 12px;
  }

  /* ── Generic panel ───────────────────────────────────────────── */
  .ttv-panel {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 6px rgba(0,0,0,0.05);
    overflow: hidden;
  }
  .ttv-panel-hdr {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    border-left: 3px solid #0ea5e9;
    padding: 9px 14px;
    display: flex; align-items: center; gap: 8px;
    color: #0f172a; font-size: 12px; font-weight: 700;
  }
  .ttv-panel-hdr i { color: #0ea5e9; }
  .ttv-panel-hdr .ttv-panel-hdr-right { margin-left: auto; }
  .ttv-panel-body { padding: 10px 12px; }
  .ttv-flabel {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.8px;
    color: #64748b; margin: 8px 0 4px;
  }

  /* ── Sticky sidebar columns ─────────────────────────────────── */
  .ttv-sticky-col {
    position: sticky;
    top: 0;
    height: 100vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }
  .ttv-sticky-col .ttv-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    min-height: 0;
  }
  .ttv-sticky-col .ttv-panel-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    min-height: 0;
  }

  /* ── Worklist Items ───────────────────────────────────── */
  .ttv-wl-wrap {
    margin-top: 8px; border-radius: 6px;
    border: 1px solid #eef2f7;
    flex: 1; overflow-y: auto; min-height: 0;
  }
  .ttv-wl-wrap::-webkit-scrollbar { width: 4px; }
  .ttv-wl-wrap::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
  #ttv-worklist-table { width: 100%; border-collapse: collapse; }
  #ttv-worklist-table td { padding: 0 !important; border: none !important; }

  .ttv-wl-item { cursor: pointer; }
  .ttv-wl-item + .ttv-wl-item .ttv-wl-card { border-top: 1px solid #f1f5f9; }
  .ttv-wl-item:hover .ttv-wl-card { background: #f8faff !important; }
  .ttv-wl-item.ttv-selected .ttv-wl-card { background: #eff6ff !important; border-left-color: #3b82f6 !important; }
  .ttv-wl-card {
    display: block; padding: 8px 12px;
    cursor: pointer; transition: background .12s;
    border-left: 3px solid transparent; background: #fff;
  }
  .ttv-wl-item.ttv-empty .ttv-wl-card { border-left-color: #f59e0b; }
  .ttv-wl-item.ttv-filled .ttv-wl-card { border-left-color: #22c55e; background: #fdfffe; }
  .ttv-wl-top {
    display: flex; align-items: center;
    justify-content: space-between; margin-bottom: 4px;
  }
  .ttv-wl-no {
    font-size: 11px; font-weight: 800; color: #94a3b8;
    background: #f1f5f9; border-radius: 4px;
    padding: 1px 7px; min-width: 22px; text-align: center;
  }
  .ttv-wl-name {
    font-size: 12px; font-weight: 700; color: #0f172a;
    word-break: break-word; line-height: 1.35; margin-bottom: 2px;
  }
  .ttv-wl-meta {
    font-size: 10.5px; color: #64748b; margin-bottom: 1px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .ttv-wl-meta i { width: 14px; text-align: center; color: #94a3b8; }
  .ttv-badge {
    font-size: 10px; font-weight: 700; padding: 2px 7px;
    border-radius: 4px; white-space: nowrap;
  }
  .ttv-badge-filled { background: #dcfce7; color: #15803d; }
  .ttv-badge-empty  { background: #fef9c3; color: #854d0e; }

  /* ── Patient Info Card ────────────────────────────────────────── */
  .ttv-patient-card {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 6px rgba(0,0,0,0.05);
    margin-bottom: 10px;
    overflow: hidden;
  }
  .ttv-patient-hdr {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-bottom: 1px solid #bae6fd;
    padding: 14px 18px;
    display: flex; align-items: center; gap: 14px;
  }
  .ttv-patient-avatar {
    width: 50px; height: 50px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #0ea5e9, #0369a1);
    color: #fff; font-size: 22px;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(14,165,233,.2);
  }
  .ttv-patient-info { flex: 1; min-width: 0; }
  .ttv-patient-name { font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 3px; }
  .ttv-patient-sub { font-size: 12px; color: #475569; }
  .ttv-patient-meta {
    display: flex; flex-wrap: wrap; gap: 6px 20px;
    padding: 9px 18px; background: #f8fafc;
    border-top: 1px solid #eef2f7; font-size: 12px; color: #4a5568;
  }
  .ttv-meta-item { display: flex; align-items: center; gap: 5px; }
  .ttv-meta-item i { color: #0ea5e9; width: 14px; text-align: center; font-size: 11px; }

  /* ── TTV Form ─────────────────────────────────────────────────── */
  .ttv-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    padding: 16px;
  }
  .ttv-form-grid.ttv-form-single {
    grid-template-columns: 1fr;
  }
  .ttv-input-group {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 14px;
    transition: border-color .2s, box-shadow .2s;
  }
  .ttv-input-group:focus-within {
    border-color: #0ea5e9;
    box-shadow: 0 0 0 3px rgba(14,165,233,.12);
  }
  .ttv-input-label {
    font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.8px;
    color: #64748b; margin-bottom: 6px;
    display: flex; align-items: center; gap: 6px;
  }
  .ttv-input-label i { font-size: 13px; }
  .ttv-input-label .ttv-unit { margin-left: auto; font-weight: 500; color: #94a3b8; text-transform: none; letter-spacing: 0; font-size: 11px; }
  .ttv-input-field {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 7px;
    padding: 8px 12px;
    font-size: 16px;
    font-weight: 600;
    color: #0f172a;
    text-align: center;
    background: #fff;
    transition: border-color .2s;
    outline: none;
  }
  .ttv-input-field:focus {
    border-color: #0ea5e9;
  }
  .ttv-input-field::placeholder {
    color: #cbd5e1;
    font-weight: 400;
    font-size: 13px;
  }
  .ttv-form-actions {
    padding: 0 16px 16px;
    display: flex; gap: 8px; justify-content: flex-end;
  }
  .ttv-btn-save {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 22px; border-radius: 8px;
    background: linear-gradient(135deg, #0ea5e9, #0369a1);
    color: #fff; font-size: 13px; font-weight: 600;
    border: none; cursor: pointer;
    transition: opacity .18s, transform .18s;
    box-shadow: 0 2px 8px rgba(14,165,233,.25);
  }
  .ttv-btn-save:hover {
    opacity: .88; transform: translateY(-1px);
  }
  .ttv-btn-reset {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: 8px;
    background: #f1f5f9;
    color: #64748b; font-size: 13px; font-weight: 600;
    border: 1px solid #e2e8f0; cursor: pointer;
    transition: background .18s;
  }
  .ttv-btn-reset:hover { background: #e2e8f0; }

  /* ── Resep Iter ──────────────────────────────────────────────── */
  .ttv-iter-section {
    margin: 0 16px 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px 16px;
  }
  .ttv-iter-question {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }
  .ttv-iter-label {
    font-size: 13px; font-weight: 700; color: #0f172a;
    display: flex; align-items: center; gap: 8px;
  }
  .ttv-iter-toggle {
    display: flex; gap: 6px;
  }
  .ttv-radio-pill {
    cursor: pointer; margin: 0;
  }
  .ttv-radio-pill input[type="radio"] { display: none; }
  .ttv-radio-text {
    display: inline-block;
    padding: 5px 18px;
    border-radius: 20px;
    font-size: 12px; font-weight: 600;
    border: 1.5px solid #d1d5db;
    color: #64748b;
    background: #fff;
    transition: all .18s;
  }
  .ttv-radio-pill input[type="radio"]:checked + .ttv-radio-text {
    background: #0ea5e9;
    color: #fff;
    border-color: #0284c7;
    box-shadow: 0 2px 8px rgba(14,165,233,.25);
  }
  .ttv-iter-detail {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px dashed #d1d5db;
    animation: ttvSlideDown .25s ease;
  }
  @keyframes ttvSlideDown {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .ttv-iter-detail-label {
    font-size: 12px; font-weight: 600; color: #475569;
    margin-bottom: 8px;
  }
  .ttv-iter-options {
    display: flex; gap: 8px;
  }
  .ttv-iter-pill {
    cursor: pointer; margin: 0;
  }
  .ttv-iter-pill input[type="radio"] { display: none; }
  .ttv-iter-pill-text {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 52px; padding: 8px 16px;
    border-radius: 8px;
    font-size: 14px; font-weight: 700;
    border: 1.5px solid #d1d5db;
    color: #475569;
    background: #fff;
    transition: all .18s;
  }
  .ttv-iter-pill input[type="radio"]:checked + .ttv-iter-pill-text {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #fff;
    border-color: #4338ca;
    box-shadow: 0 2px 8px rgba(99,102,241,.3);
  }
  .ttv-iter-pill:hover .ttv-iter-pill-text {
    border-color: #6366f1;
  }

  /* ── Empty state ──────────────────────────────────────────────── */
  .ttv-empty-state {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 60px 20px; color: #94a3b8; text-align: center;
  }
  .ttv-empty-state i { font-size: 48px; margin-bottom: 12px; color: #cbd5e1; }
  .ttv-empty-state .ttv-empty-title { font-size: 15px; font-weight: 600; color: #64748b; margin-bottom: 4px; }
  .ttv-empty-state .ttv-empty-sub { font-size: 12px; }

  /* ── Riwayat panel ────────────────────────────────────────────── */
  .ttv-riwayat-scroll {
    flex: 1; overflow-y: auto; padding: 10px; min-height: 0;
  }
  .ttv-riwayat-scroll::-webkit-scrollbar { width: 4px; }
  .ttv-riwayat-scroll::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }

  .ttv-riwayat-tabs {
    border: none; padding: 0; margin: 0;
    display: flex; gap: 3px; background: transparent;
    list-style: none;
  }
  .ttv-riwayat-tabs li a {
    padding: 4px 9px; font-size: 11px; font-weight: 600;
    border-radius: 6px;
    border: 1px solid transparent;
    color: #475569;
    background: rgba(14,165,233,0.07);
    transition: background .15s, color .15s;
    cursor: pointer; text-decoration: none;
    display: inline-block;
  }
  .ttv-riwayat-tabs li a:hover { background: rgba(14,165,233,0.15); color: #0369a1; text-decoration: none; }
  .ttv-riwayat-tabs li.active a {
    background: #0ea5e9;
    color: #fff;
    border-color: #0284c7;
    box-shadow: 0 1px 4px rgba(14,165,233,.3);
  }

  /* ── TTS Panel ────────────────────────────────────────────────── */
  .ttv-tts-panel {
    margin-bottom: 10px;
  }
  .ttv-tts-panel .ttv-panel-hdr {
    border-left-color: #0369a1;
    user-select: none;
  }
  .ttv-tts-panel .ttv-panel-hdr:hover {
    background: #f1f5f9;
  }

  /* ── Responsive ──────────────────────────────────────────────── */
  @media (max-width: 992px) {
    .ttv-sticky-col {
      position: relative;
      height: auto;
    }
    .ttv-stats-bar { border-radius: 8px; flex-direction: column; align-items: flex-start; }
    .ttv-body { padding: 8px; }
    .ttv-form-grid { grid-template-columns: 1fr; }
  }

  /* ── TTV Audit Banner ──────────────────────────────────────────── */
  .ttv-audit-banner {
    display: flex; align-items: flex-start; gap: 10px;
    margin: 10px 14px 0;
    padding: 10px 14px;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border: 1px solid #93c5fd;
    border-radius: 8px;
    border-left: 4px solid #2563eb;
  }
  .ttv-audit-icon {
    font-size: 18px; color: #2563eb; flex-shrink: 0; margin-top: 1px;
  }
  .ttv-audit-body { flex: 1; min-width: 0; }
  .ttv-audit-title {
    font-size: 11px; font-weight: 700; color: #1e40af;
    text-transform: uppercase; letter-spacing: .4px; margin-bottom: 6px;
  }
  .ttv-audit-row {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: #1e3a5f; margin-bottom: 3px;
  }
  .ttv-audit-row:last-child { margin-bottom: 0; }
  .ttv-audit-lbl {
    min-width: 90px; color: #3b82f6; font-weight: 600; display: flex; align-items: center; gap: 4px;
  }
  .ttv-audit-val { color: #0f172a; font-weight: 500; }
</style>

<div id="ttv-wrap">

  <!-- ── Stats Bar ─────────────────────────────────────────── -->
  <div class="ttv-stats-bar">
    <div class="ttv-title-info">
      <div class="ttv-title-icon">
        <i class="fa fa-heartbeat"></i>
      </div>
      <div>
        <span class="ttv-title-name"><?php echo $title?></span>
        <span class="ttv-title-sub"><i class="fa fa-angle-double-right"></i> <?php echo isset($breadcrumbs)?$breadcrumbs:''?></span>
      </div>
    </div>
    <div class="ttv-stats-group">
      <div class="ttv-stat-pill ttv-blue">
        <span class="ttv-stat-num" id="ttv_total_pasien">-</span>
        <span class="ttv-stat-lbl">Total Pasien</span>
      </div>
      <div class="ttv-stat-pill ttv-green">
        <span class="ttv-stat-num" id="ttv_sudah_isi">-</span>
        <span class="ttv-stat-lbl">TTV Terisi</span>
      </div>
      <div class="ttv-stat-pill ttv-orange">
        <span class="ttv-stat-num" id="ttv_belum_isi">-</span>
        <span class="ttv-stat-lbl">Belum Diisi</span>
      </div>
    </div>
  </div>

  <div class="ttv-body">
  <div class="row" style="margin:0">

    <!-- ── Left: WorkList Pasien ─────────────────────── -->
    <div class="col-md-3 ttv-sticky-col" style="padding:10px 6px 10px 0">
      <div class="ttv-panel">
        <div class="ttv-panel-hdr">
          <i class="fa fa-list-ol"></i> WorkList Pasien
        </div>
        <div class="ttv-panel-body">
          <div class="ttv-flabel">Tanggal Kunjungan</div>
          <div class="input-group">
            <input name="tgl_kunjungan_ttv" id="tgl_kunjungan_ttv"
                   placeholder="<?php echo date('Y-m-d')?>"
                   class="form-control date-picker"
                   data-date-format="yyyy-mm-dd" type="text"
                   value="<?php echo date('Y-m-d')?>"
                   style="font-size:12px">
            <span class="input-group-addon"><i class="ace-icon fa fa-calendar"></i></span>
          </div>
          <div class="ttv-flabel">Pencarian</div>
          <select name="search_by_ttv" class="form-control" style="font-size:12px; margin-bottom:5px">
            <option value="tc_kunjungan.no_mr" selected>No MR</option>
            <option value="pl_tc_poli.nama_pasien">Nama Pasien</option>
          </select>
          <input type="text" id="search_keyword_ttv" placeholder="Ketik keyword..." class="form-control" style="font-size:12px">
          <div class="ttv-wl-wrap">
            <table id="ttv-worklist-table"><tbody id="ttv-worklist-body"></tbody></table>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Center: Form Pengisian TTV ──────────────────── -->
    <div class="col-md-6" style="padding:10px 6px">

      <!-- Empty State (before patient selected) -->
      <div id="ttv-empty-state">
        <div class="ttv-empty-state">
          <i class="fa fa-hand-pointer-o"></i>
          <div class="ttv-empty-title">Pilih Pasien dari WorkList</div>
          <div class="ttv-empty-sub">Klik salah satu pasien di panel kiri untuk mulai mengisi data Tanda-Tanda Vital</div>
        </div>
      </div>

      <!-- Form Area (shown after patient selected) -->
      <div id="ttv-form-area" style="display:none">

        <!-- Patient Info Card -->
        <div class="ttv-patient-card">
          <div class="ttv-patient-hdr">
            <div class="ttv-patient-avatar">
              <i class="fa fa-user"></i>
            </div>
            <div class="ttv-patient-info">
              <div class="ttv-patient-name" id="ttv_patient_name">-</div>
              <div class="ttv-patient-sub">
                <i class="fa fa-id-card-o"></i> <span id="ttv_patient_mr">-</span>
              </div>
            </div>
            <button type="button" id="btn_panggil_ttv" onclick="ttvSpeak()"
              style="display:flex;align-items:center;gap:5px;padding:7px 14px;border:none;border-radius:7px;background:#0369a1;color:#fff;font-size:12px;font-weight:600;cursor:pointer;flex-shrink:0;transition:opacity .18s;"
              onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
              <i class="fa fa-bullhorn"></i> Panggil
            </button>
          </div>
          <div class="ttv-patient-meta">
            <div class="ttv-meta-item">
              <i class="fa fa-shield"></i>
              <span id="ttv_patient_penjamin">-</span>
            </div>
            <div class="ttv-meta-item">
              <i class="fa fa-hospital-o"></i>
              <span id="ttv_patient_tujuan">-</span>
            </div>
            <div class="ttv-meta-item">
              <i class="fa fa-calendar"></i>
              <span id="ttv_patient_tgl">-</span>
            </div>
          </div>
        </div>

        <!-- Panggil Pasien TTS Settings -->
        <div class="ttv-panel ttv-tts-panel">
          <div class="ttv-panel-hdr" style="cursor:pointer" onclick="ttvToggleTtsSettings()">
            <i class="fa fa-microphone" style="color:#0ea5e9"></i> Pengaturan Suara Pemanggilan
            <span class="ttv-panel-hdr-right">
              <i class="fa fa-chevron-down" id="ttv-tts-chevron" style="transition:transform .2s;color:#94a3b8;font-size:11px"></i>
            </span>
          </div>
          <div id="ttv-tts-settings" style="display:none;padding:12px 14px">
            <div style="margin-bottom:10px">
              <label class="ttv-flabel" style="margin-top:0">Teks Pemanggilan</label>
              <input type="text" id="ttv_tts_text" class="form-control"
                style="font-size:12px;margin-top:4px"
                placeholder="Teks yang akan diucapkan...">
            </div>
            <div style="display:flex;gap:16px;align-items:flex-start">
              <div style="flex:1">
                <label class="ttv-flabel" style="margin-top:0;margin-bottom:6px">Rate &mdash; <span id="ttv_tts_rate_val">1</span></label>
                <input type="range" id="ttv_tts_rate" min="0.5" max="2" value="1" step="0.1" style="width:100%;accent-color:#0ea5e9">
              </div>
              <div style="flex:1">
                <label class="ttv-flabel" style="margin-top:0;margin-bottom:6px">Pitch &mdash; <span id="ttv_tts_pitch_val">1</span></label>
                <input type="range" id="ttv_tts_pitch" min="0" max="2" value="1" step="0.1" style="width:100%;accent-color:#0ea5e9">
              </div>
            </div>
          </div>
        </div>

        <!-- TTV Form Panel -->
        <div class="ttv-panel">
          <div class="ttv-panel-hdr">
            <i class="fa fa-heartbeat"></i> Form Tanda-Tanda Vital (TTV)
          </div>

          <!-- Audit Banner: tampil jika TTV sudah terisi -->
          <div id="ttv-audit-banner" style="display:none">
            <div class="ttv-audit-banner">
              <div class="ttv-audit-icon"><i class="fa fa-info-circle"></i></div>
              <div class="ttv-audit-body">
                <div class="ttv-audit-title">TTV Sudah Diinput</div>
                <div class="ttv-audit-row">
                  <span class="ttv-audit-lbl"><i class="fa fa-user-md"></i> Petugas</span>
                  <span class="ttv-audit-val" id="ttv_audit_petugas">-</span>
                </div>
                <div class="ttv-audit-row">
                  <span class="ttv-audit-lbl"><i class="fa fa-clock-o"></i> Waktu Input</span>
                  <span class="ttv-audit-val" id="ttv_audit_waktu">-</span>
                </div>
              </div>
            </div>
          </div>

          <div class="ttv-form-grid">
            <div class="ttv-input-group">
              <div class="ttv-input-label">
                <i class="fa fa-arrows-v" style="color:#0ea5e9"></i> Tinggi Badan
                <span class="ttv-unit">cm</span>
              </div>
              <input type="text" class="ttv-input-field" id="ttv_tinggi_badan"
                     placeholder="Contoh: 170">
            </div>
            <div class="ttv-input-group">
              <div class="ttv-input-label">
                <i class="fa fa-balance-scale" style="color:#8b5cf6"></i> Berat Badan
                <span class="ttv-unit">kg</span>
              </div>
              <input type="text" class="ttv-input-field" id="ttv_berat_badan"
                     placeholder="Contoh: 65">
            </div>
            <div class="ttv-input-group">
              <div class="ttv-input-label">
                <i class="fa fa-tachometer" style="color:#ef4444"></i> Tekanan Darah
                <span class="ttv-unit">mmHg</span>
              </div>
              <input type="text" class="ttv-input-field" id="ttv_tekanan_darah"
                     placeholder="Contoh: 120/80">
            </div>
            <div class="ttv-input-group">
              <div class="ttv-input-label">
                <i class="fa fa-heart" style="color:#f97316"></i> Nadi
                <span class="ttv-unit">bpm</span>
              </div>
              <input type="text" class="ttv-input-field" id="ttv_nadi"
                     placeholder="Contoh: 80">
            </div>
          </div>
          <div class="ttv-form-grid ttv-form-single" style="padding-top:0">
            <div class="ttv-input-group">
              <div class="ttv-input-label">
                <i class="fa fa-thermometer-half" style="color:#eab308"></i> Suhu Tubuh
                <span class="ttv-unit">&deg;C</span>
              </div>
              <input type="text" class="ttv-input-field" id="ttv_suhu"
                     placeholder="Contoh: 36.5">
            </div>
          </div>

          <!-- Resep Iter -->
          <div class="ttv-iter-section">
            <div class="ttv-iter-question">
              <div class="ttv-iter-label">
                <i class="fa fa-medkit" style="color:#6366f1"></i> Apakah ada resep iter?
              </div>
              <div class="ttv-iter-toggle">
                <label class="ttv-radio-pill">
                  <input type="radio" name="resep_iter" value="N" checked onchange="toggleIterOption()">
                  <span class="ttv-radio-text">Tidak</span>
                </label>
                <label class="ttv-radio-pill">
                  <input type="radio" name="resep_iter" value="Y" onchange="toggleIterOption()">
                  <span class="ttv-radio-text">Ya</span>
                </label>
              </div>
            </div>
            <div class="ttv-iter-detail" id="ttv_iter_detail" style="display:none">
              <div class="ttv-iter-detail-label">Berapa kali iter?</div>
              <div class="ttv-iter-options">
                <label class="ttv-iter-pill">
                  <input type="radio" name="jumlah_iter" value="1">
                  <span class="ttv-iter-pill-text">1x</span>
                </label>
                <label class="ttv-iter-pill">
                  <input type="radio" name="jumlah_iter" value="2">
                  <span class="ttv-iter-pill-text">2x</span>
                </label>
                <label class="ttv-iter-pill">
                  <input type="radio" name="jumlah_iter" value="3">
                  <span class="ttv-iter-pill-text">3x</span>
                </label>
              </div>
            </div>
          </div>

          <div class="ttv-form-actions">
            <button type="button" class="ttv-btn-reset" onclick="$('.ttv-input-field').val('')">
              <i class="fa fa-eraser"></i> Reset
            </button>
            <button type="button" class="ttv-btn-save" onclick="saveAllTTV()">
              <i class="fa fa-check-circle"></i> Simpan Semua TTV
            </button>
          </div>
        </div>

      </div>

    </div><!-- /.col-md-6 -->

    <!-- ── Right: Riwayat Kunjungan / Medis ────────────────────── -->
    <div class="col-md-3 ttv-sticky-col" style="padding:10px 0 10px 6px">
      <div class="ttv-panel" style="flex:1;display:flex;flex-direction:column;overflow:hidden;min-height:0">
        <div class="ttv-panel-hdr" style="justify-content:space-between">
          <!-- <span><i class="fa fa-book"></i> Riwayat Medis</span> -->
          <ul class="ttv-riwayat-tabs" id="ttv_riwayat_tab">
            <!-- <li class="active">
              <a href="#" onclick="if(selectedPatient) loadRiwayatMedis(selectedPatient.noMr, selectedPatient.noRegistrasi, selectedPatient.noKunjungan); $('#ttv_riwayat_tab li').removeClass('active'); $(this).parent().addClass('active'); return false;" title="Resume Medis">
                <i class="fa fa-file-text-o"></i> Resume
              </a>
            </li> -->
            <li class="active">
              <a href="#" onclick="if(selectedPatient){ loadRiwayatMedisAll(selectedPatient.noMr); $('#ttv_riwayat_tab li').removeClass('active'); $(this).parent().addClass('active'); } return false;" title="Riwayat Medis">
                <i class="fa fa-history"></i> Riwayat Medis
              </a>
            </li>

            <li>
              <a href="#" onclick="if(selectedPatient){ loadRiwayatPerjanjian(selectedPatient.noMr); $('#ttv_riwayat_tab li').removeClass('active'); $(this).parent().addClass('active'); } return false;" title="Perjanjian Pasien">
                <i class="fa fa-calendar"></i> Riwayat Perjanjian
              </a>
            </li>


          </ul>
        </div>
        <div class="ttv-riwayat-scroll">
          <!-- Empty state before patient selected -->
          <div id="ttv-riwayat-empty" style="padding:40px 20px;text-align:center;color:#94a3b8;">
            <i class="fa fa-folder-open-o" style="font-size:36px;display:block;margin-bottom:10px;color:#cbd5e1"></i>
            <div style="font-size:13px;font-weight:600;color:#64748b;margin-bottom:4px">Riwayat Medis Pasien</div>
            <div style="font-size:11px">Pilih pasien untuk melihat riwayat medis</div>
          </div>
          <div id="ttv_riwayat_content" style="display:none"></div>
        </div>
      </div>
    </div><!-- /.col-md-3 -->

  </div><!-- /.row -->
  </div><!-- /.ttv-body -->

</div><!-- /#ttv-wrap -->
