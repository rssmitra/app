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

/* ── Pending save context (used when selisih modal is required) ── */
var _pendingSave  = null;
var _is_sysadmin  = <?php echo ($this->authuser->is_administrator($this->session->userdata('user')->user_id)) ? 'true' : 'false'; ?>;

$(document).ready(function() {

  /* ── Show/hide filter sections based on kode_bagian ── */
  if ($('#kode_bagian').val() != '070101') {
    $('#section_medis').show();
    $('#section_non_medis').hide();
    $('#flag_string').val('medis');
  } else {
    $('#section_non_medis').show();
    $('#section_medis').hide();
    $('#flag_string').val('non_medis');
  }

  /* ── DataTable init ── */
  oTable = $('#dt-input-so-bag').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering":   false,
    "pageLength":  50,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "ajax": {
      "url":  $('#dt-input-so-bag').attr('base-url'),
      "type": "POST"
    },
    "columnDefs": [
      { "targets": [5, 8, 10, 13], "orderable": false },
      { "targets": [1],            "sClass": "hidden-480" }
    ]
  });

  /* ── Keluar sesi ── */
  $('#sign-out-sess-so').on('click', function(e) {
    e.preventDefault();
    $.ajax({
      url:      "inventory/so/Input_dt_so/destroy_session_input_so",
      data:     {},
      dataType: "json",
      type:     "POST",
      complete: function(xhr) {
        var res = JSON.parse(xhr.responseText);
        if (res.status === 200) {
          $.achtung({message: res.message, timeout: 5});
          getMenu('inventory/so/Input_dt_so');
        } else {
          $.achtung({message: res.message, timeout: 5});
        }
        achtungHideLoader();
      }
    });
  });

  /* ── Selisih modal: Confirm button ── */
  $('#btn-modal-confirm-selisih').on('click', function() {
    var klar = $.trim($('#modal-klarifikasi').val());
    if (klar === '') {
      $('#modal-klar-error').show();
      return;
    }
    $('#modal-klar-error').hide();

    /* Copy klarifikasi back to the row input */
    if (_pendingSave) {
      $('#row_klar_' + _pendingSave.row_id).val(klar);
      _doSave(_pendingSave.kode_brg, _pendingSave.kode_bag, _pendingSave.agenda_so_id,
              _pendingSave.row_id,   _pendingSave.type,     klar,
              _pendingSave.stok_akhir, _pendingSave.cutoff, _pendingSave.mv_in, _pendingSave.mv_out,
              _pendingSave.nama_brg,  _pendingSave.satuan);
    }
    $('#modalSelisihKlarifikasi').modal('hide');
  });

  /* Reset modal state on close */
  $('#modalSelisihKlarifikasi').on('hidden.bs.modal', function() {
    _pendingSave = null;
    $('#modal-klar-error').hide();
  });

});

/* ── Calculate selisih + stok final live (onchange of SO / Expired / Adjustment inputs) ── */
function calcSelisih(kode_brg, agenda_so_id, stok_akhir) {
  var row_id  = kode_brg + '_' + kode_brg + '_' + agenda_so_id;
  var stok_so = parseFloat($('#row_so_' + row_id).val());

  if (isNaN(stok_so)) {
    $('#row_selisih_'   + row_id).html('<span style="color:#aaa">&mdash;</span>');
    $('#row_stokfinal_' + row_id).html('<span style="color:#aaa">&mdash;</span>');
    return;
  }

  var stok_exp = parseInt($('#row_exp_' + row_id).val(), 10) || 0;
  var stok_adj = parseInt($('#row_adj_' + row_id).val(), 10) || 0;

  /* ── Selisih = SO + Expired + Adjustment − Stok Akhir Berjalan ── */
  var selisih = (stok_so + stok_exp + stok_adj) - stok_akhir;
  var s_color = selisih > 0 ? '#27ae60' : (selisih < 0 ? '#c0392b' : '#555');
  var s_pfx   = selisih >= 0 ? '+' : '';
  $('#row_selisih_' + row_id).html(
    '<span style="color:' + s_color + ';font-weight:bold">' + s_pfx + selisih + '</span>'
  );

  /* ── Stok Final = SO + Adjustment ── */
  var sf_val   = stok_so + stok_adj;
  var sf_color = sf_val > 0 ? '#1a5276' : (sf_val < 0 ? '#c0392b' : '#555');
  $('#row_stokfinal_' + row_id).html(
    '<span style="color:' + sf_color + ';font-weight:bold">' + sf_val + '</span>'
  );
}

/* ── Save Draft button handler ── */
function saveDraftRow(kode_brg, kode_bag, agenda_so_id, stok_akhir, cutoff, mv_in, mv_out, nama_brg, satuan) {
  var row_id  = kode_brg + '_' + kode_brg + '_' + agenda_so_id;
  var $inp_so = $('#row_so_' + row_id);
  var stok_so = parseFloat($inp_so.val());

  if ($inp_so.val() === '' || isNaN(stok_so)) {
    $inp_so.css({'border-color':'#e74c3c','box-shadow':'0 0 4px rgba(231,76,60,.5)'});
    $.achtung({message: 'Stok Opname wajib diisi sebelum menyimpan sebagai Draft.', timeout: 5, className: 'achtungFail'});
    $inp_so.focus();
    return;
  }
  $inp_so.css({'border-color':'', 'box-shadow':''});

  var stok_exp = parseInt($('#row_exp_' + row_id).val(), 10) || 0;
  var stok_adj = parseInt($('#row_adj_' + row_id).val(), 10) || 0;
  var selisih  = (stok_so + stok_exp + stok_adj) - stok_akhir;

  if (selisih !== 0) {
    _showSelisihModal(kode_brg, kode_bag, agenda_so_id, row_id, stok_so, stok_akhir, selisih, 'draft',
                      cutoff, mv_in, mv_out, nama_brg, satuan);
  } else {
    _doSave(kode_brg, kode_bag, agenda_so_id, row_id, 'draft',
            $('#row_klar_' + row_id).val(), stok_akhir, cutoff, mv_in, mv_out, nama_brg, satuan);
  }
}

/* ── Save Final button handler ── */
function saveFinalRow(kode_brg, kode_bag, agenda_so_id, stok_akhir, cutoff, mv_in, mv_out, nama_brg, satuan) {
  var row_id  = kode_brg + '_' + kode_brg + '_' + agenda_so_id;
  var $inp_so = $('#row_so_' + row_id);
  var stok_so = parseFloat($inp_so.val());

  if ($inp_so.val() === '' || isNaN(stok_so)) {
    $inp_so.css({'border-color':'#e74c3c','box-shadow':'0 0 4px rgba(231,76,60,.5)'});
    $.achtung({message: 'Stok Opname wajib diisi sebelum finalisasi.', timeout: 5, className: 'achtungFail'});
    $inp_so.focus();
    return;
  }
  $inp_so.css({'border-color':'', 'box-shadow':''});

  var stok_exp = parseInt($('#row_exp_' + row_id).val(), 10) || 0;
  var stok_adj = parseInt($('#row_adj_' + row_id).val(), 10) || 0;
  var selisih  = (stok_so + stok_exp + stok_adj) - stok_akhir;

  if (selisih !== 0) {
    _showSelisihModal(kode_brg, kode_bag, agenda_so_id, row_id, stok_so, stok_akhir, selisih, 'final',
                      cutoff, mv_in, mv_out, nama_brg, satuan);
  } else {
    _doSave(kode_brg, kode_bag, agenda_so_id, row_id, 'final',
            $('#row_klar_' + row_id).val(), stok_akhir, cutoff, mv_in, mv_out, nama_brg, satuan);
  }
}

/* ── Internal: show the complete info + selisih modal ── */
function _showSelisihModal(kode_brg, kode_bag, agenda_so_id, row_id, stok_so, stok_akhir, selisih, type,
                            cutoff, mv_in, mv_out, nama_brg, satuan) {
  _pendingSave = { kode_brg: kode_brg, kode_bag: kode_bag, agenda_so_id: agenda_so_id,
                   row_id: row_id, type: type,
                   stok_akhir: stok_akhir, cutoff: cutoff, mv_in: mv_in, mv_out: mv_out,
                   nama_brg: nama_brg, satuan: satuan };

  var stok_exp = parseInt($('#row_exp_' + row_id).val(), 10) || 0;
  var stok_adj = parseInt($('#row_adj_' + row_id).val(), 10) || 0;
  var sf_val   = stok_so + stok_adj;
  var s_color  = selisih > 0 ? '#27ae60' : '#c0392b';
  var s_prefix = selisih >= 0 ? '+' : '';
  var label    = type === 'final'
    ? '<span class="label label-success"><i class="fa fa-check-circle"></i> Save Final</span>'
    : '<span class="label label-info"><i class="fa fa-save"></i> Save Draft</span>';

  $('#modal-so-type').html(label);
  $('#modal-nama-brg').text(nama_brg || '');
  $('#modal-satuan').text(satuan ? '(' + satuan + ')' : '');
  $('#modal-cutoff').text(cutoff);
  $('#modal-stok-akhir').text(stok_akhir);
  $('#modal-mv-in').html(mv_in > 0
    ? '<span class="text-success">+' + mv_in + '</span>'
    : '<span style="color:#aaa">&mdash;</span>');
  $('#modal-mv-out').html(mv_out > 0
    ? '<span class="text-danger">&minus;' + mv_out + '</span>'
    : '<span style="color:#aaa">&mdash;</span>');
  $('#modal-stok-so').text(stok_so);
  $('#modal-stok-exp').text(stok_exp);
  $('#modal-stok-adj').text(stok_adj >= 0 ? '+' + stok_adj : stok_adj);
  $('#modal-selisih-val').html(
    '<strong style="color:' + s_color + ';font-size:15px">' + s_prefix + selisih + '</strong>'
  );
  $('#modal-stok-final').text(sf_val);

  $('#modal-klarifikasi').val($('#row_klar_' + row_id).val());
  $('#modal-klar-error').hide();
  $('#modalSelisihKlarifikasi').modal('show');
}

/* ── Internal: execute the AJAX save (no global overlay — button shows loading state) ── */
function _doSave(kode_brg, kode_bag, agenda_so_id, row_id, type, klarifikasi, stok_akhir, cutoff, mv_in, mv_out, nama_brg, satuan) {
  nama_brg = nama_brg || '';
  satuan   = satuan   || '';

  var endpoint  = (type === 'final') ? 'inventory/so/Input_dt_so/save_final_so'
                                     : 'inventory/so/Input_dt_so/save_draft_so';
  var is_active = $('#stat_on_off_' + row_id).is(':checked') ? 1 : 0;
  var stok_so   = parseInt($('#row_so_'  + row_id).val(), 10) || 0;
  var stok_exp  = parseInt($('#row_exp_' + row_id).val(), 10) || 0;
  var stok_adj  = parseInt($('#row_adj_' + row_id).val(), 10) || 0;
  var selisih   = (stok_so + stok_exp + stok_adj) - (stok_akhir || 0);

  /* ── Put save button into loading state ── */
  var $actCell  = $('#row_act_' + row_id);
  var $btn      = $actCell.find('button').first();
  var origHtml  = $btn.length ? $btn.html()         : '';
  var origClass = $btn.length ? $btn.attr('class')  : '';
  if ($btn.length) {
    $btn.prop('disabled', true)
        .html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...')
        .removeClass('btn-info btn-success btn-warning btn-danger')
        .addClass('btn-default');
  }

  $.ajax({
    url:  endpoint,
    type: 'POST',
    dataType: 'json',
    data: {
      kode_bagian:          kode_bag,
      kode_brg:             kode_brg,
      agenda_so_id:         agenda_so_id,
      stok_opname:          stok_so,
      stok_exp:             stok_exp,
      stok_adjustment:      stok_adj,
      klarifikasi_stok:     klarifikasi || $('#row_klar_' + row_id).val(),
      status_aktif:         is_active,
      stok_akhir_berjalan:  stok_akhir  || 0,
      stok_cutoff:          cutoff       || 0,
      total_pemasukan:      mv_in        || 0,
      total_pengeluaran:    mv_out       || 0,
      selisih:              selisih,
      stok_final:           stok_so + stok_adj
    },
    complete: function(xhr) {
      var res;
      try { res = JSON.parse(xhr.responseText); } catch(e) { res = {status: 500, message: 'Server error'}; }
      if (res.status === 200) {
        $.achtung({message: res.message, timeout: 3});
        /* Update action cell in-place — no DataTable reload needed */
        _updateActCell($actCell, row_id, kode_brg, kode_bag, agenda_so_id,
                       type, stok_akhir, cutoff, mv_in, mv_out, nama_brg, satuan);
      } else {
        $.achtung({message: res.message, timeout: 5, className: 'achtungFail'});
        /* Restore button on failure */
        if ($btn.length) {
          $btn.prop('disabled', false).html(origHtml).attr('class', origClass);
        }
      }
    }
  });
}

/* ── Update action cell in-place after a successful save ── */
function _updateActCell($actCell, row_id, kode_brg, kode_bag, agenda_so_id,
                        type, stok_akhir, cutoff, mv_in, mv_out, nama_brg, satuan) {
  var now = new Date();
  var ts  = ('0'+now.getHours()).slice(-2) + ':' + ('0'+now.getMinutes()).slice(-2)
          + ':' + ('0'+now.getSeconds()).slice(-2);

  $actCell.empty();

  if (type === 'final') {
    $actCell
      .append('<span class="label label-success"><i class="fa fa-lock"></i> Final</span>')
      .append('<br><small style="color:#aaa">' + ts + '</small>')
      .append('<div style="margin-top:5px;color:#27ae60;font-size:11px"><i class="fa fa-lock"></i> Finalized</div>');
    if (_is_sysadmin) {
      $actCell.append(
        $('<div style="margin-top:4px"></div>').append(
          $('<button class="btn btn-xs btn-warning" style="white-space:nowrap;font-size:10px" title="Rollback ke Draft (Sysadmin)">')
            .html('<i class="fa fa-undo"></i> Rollback Draft')
            .on('click', function() { rollbackToDraft(kode_brg, kode_bag, agenda_so_id); })
        )
      );
    }
  } else {
    /* draft saved → promote button to Save Final */
    $actCell
      .append('<span class="label label-warning"><i class="fa fa-edit"></i> Draft</span>')
      .append('<br><small style="color:#aaa">' + ts + '</small>')
      .append(
        $('<div style="margin-top:5px"></div>').append(
          $('<button class="btn btn-xs btn-success" style="white-space:nowrap">')
            .html('<i class="fa fa-check-circle"></i> Save Final')
            .on('click', function() {
              saveFinalRow(kode_brg, kode_bag, agenda_so_id,
                           stok_akhir, cutoff, mv_in, mv_out, nama_brg, satuan);
            })
        )
      );
  }
}

/* ── Status Aktif toggle ── */
function setStatusAktifBrg(kode_brg, kode_bag, agenda_so_id, stok_akhir, cutoff, mv_in, mv_out) {
  var row_id        = kode_brg + '_' + kode_brg + '_' + agenda_so_id;
  var val_id        = $('#stat_on_off_' + row_id).val();
  /* onclick fires after checkbox state has already changed */
  var is_now_inactive = !$('#stat_on_off_' + row_id).is(':checked');

  /* When going inactive: auto-fill SO=0, Adj=0, then recalculate */
  if (is_now_inactive) {
    $('#row_so_'  + row_id).val(0);
    $('#row_adj_' + row_id).val(0);
    calcSelisih(kode_brg, agenda_so_id, stok_akhir);
  }

  $.ajax({
    url:      "inventory/so/Input_dt_so/set_status_brg",
    data:     { kode_bagian: kode_bag, kode_brg: kode_brg, agenda_so_id: agenda_so_id,
                input_stok_so: 0, exp_stok: 0, value: val_id, status_aktif: val_id,
                will_exp_stok: 0, flag: 'setstatusaktif' },
    dataType: "json",
    type:     "POST",
    complete: function(xhr) {
      var res = JSON.parse(xhr.responseText);
      if (res.status === 200) {
        $.achtung({message: res.message, timeout: 3});
        if (is_now_inactive) {
          /* Auto-save SO=0, Adj=0 as draft so DB is in sync */
          _doSave(kode_brg, kode_bag, agenda_so_id, row_id, 'draft', '',
                  stok_akhir, cutoff, mv_in, mv_out);
        } else {
          // reset_table(kode_bag);
        }
      } else {
        $.achtung({message: res.message, timeout: 5, className: 'achtungFail'});
        achtungHideLoader();
      }
    }
  });
}

function reset_table(kode_bag) {
  var golongan = ($('#kode_bagian').val() == '070101') ? $('#kode_golongan').val() : $('#kode_kategori').val();
  var rak      = ($('#kode_bagian').val() == '070101') ? $('#rak_nm').val()        : $('#rak_m').val();
  /* Use base-url so the cutoff param is preserved */
  var baseUrl  = $('#dt-input-so-bag').attr('base-url');
  oTable.ajax.url(baseUrl + '&gol=' + golongan + '&rak=' + rak).load();
}

function find_data_reload() {
  var golongan = ($('#kode_bagian').val() == '070101') ? $('#kode_golongan').val() : $('#kode_kategori').val();
  var rak      = ($('#kode_bagian').val() == '070101') ? $('#rak_nm').val()        : $('#rak_m').val();
  oTable.ajax.url($('#dt-input-so-bag').attr('base-url') + '&gol=' + golongan + '&rak=' + rak).load();
}

/* ── Rollback Final → Draft (sysadmin only) ── */
function rollbackToDraft(kode_brg, kode_bag, agenda_so_id) {
  if (!confirm(
    'Kembalikan status FINAL ke DRAFT?\n\n' +
    'PERHATIAN: Mutasi stok yang sudah terjadi tidak akan dibalik otomatis.\n' +
    'Lakukan rollback ini hanya jika benar-benar diperlukan!'
  )) { return; }

  achtungShowLoader();
  $.ajax({
    url:      'inventory/so/Input_dt_so/rollback_to_draft_so',
    type:     'POST',
    dataType: 'json',
    data: {
      kode_bagian:  kode_bag,
      kode_brg:     kode_brg,
      agenda_so_id: agenda_so_id
    },
    complete: function(xhr) {
      var res = JSON.parse(xhr.responseText);
      if (res.status === 200) {
        $.achtung({message: res.message, timeout: 4});
        oTable.ajax.reload(null, false);
      } else {
        $.achtung({message: res.message, timeout: 6, className: 'achtungFail'});
      }
      achtungHideLoader();
    }
  });
}
</script>

<style>
  .so-info-table td           { font-size: 13px; padding: 4px 7px; vertical-align: top; }
  .so-info-table td.lbl       { color: #777; width: 130px; white-space: nowrap; }
  #dt-input-so-bag thead th   { font-size: 11px; vertical-align: middle; text-align: center; }
  #dt-input-so-bag thead th small { font-weight: normal; font-size: 10px; display: block; margin-top: 2px }
  .so-th-cutoff    { background: #d6eaf8 !important; color: #1a5276 !important; }
  .so-th-stokakhir { background: #d5f5e3 !important; color: #145a32 !important; }
  .so-th-pergerakan{ background: #f4ecf7 !important; color: #512e5f !important; }
  .so-th-stokfisik { background: #fef9e7 !important; color: #7d6608 !important; }
  .so-th-exp       { background: #fdedec !important; color: #78281f !important; }
  .so-th-selisih   { background: #fdfefe !important; color: #333    !important; }
  .so-th-adj       { background: #eaf4fb !important; color: #1a5276 !important; }
  .so-th-stokfinal { background: #fdebd0 !important; color: #784212 !important; }
</style>

<!-- ═══════════════════════════════════════════════════════════════════════ -->
<!-- Selisih Klarifikasi Modal                                               -->
<!-- ═══════════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalSelisihKlarifikasi" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header" style="background:#fff3cd; padding:10px 15px">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title" style="color:#856404">
          <i class="fa fa-exclamation-triangle"></i>
          Konfirmasi Selisih Stok &mdash; <span id="modal-so-type"></span>
        </h5>
      </div>

      <div class="modal-body" style="padding:14px 18px">

        <!-- ── Nama & Satuan ─────────────────────────────────────────── -->
        <div style="margin-bottom:10px; padding:8px 10px; background:#f4f8fb; border-radius:4px; border-left:3px solid #3a8fc1">
          <strong style="font-size:13px" id="modal-nama-brg"></strong>
          <span style="font-size:12px; color:#777; margin-left:5px" id="modal-satuan"></span>
        </div>

        <!-- ── Data Lengkap Stok ──────────────────────────────────────── -->
        <table style="width:100%; font-size:12px; margin-bottom:12px; border-collapse:collapse">

          <!-- Cut Off & Stok Akhir -->
          <tr style="background:#d6eaf8">
            <td style="padding:4px 8px; color:#1a5276; font-weight:600; width:170px">Cut Off Stok</td>
            <td style="padding:4px 8px; font-weight:bold; color:#1a5276" id="modal-cutoff"></td>
            <td style="padding:4px 8px; color:#145a32; font-weight:600; width:170px">Stok Akhir Berjalan</td>
            <td style="padding:4px 8px; font-weight:bold; color:#145a32" id="modal-stok-akhir"></td>
          </tr>

          <!-- Pergerakan Masuk & Keluar -->
          <tr style="background:#f4ecf7">
            <td style="padding:4px 8px; color:#512e5f; font-weight:600">Pergerakan Masuk</td>
            <td style="padding:4px 8px; font-weight:bold" id="modal-mv-in"></td>
            <td style="padding:4px 8px; color:#512e5f; font-weight:600">Pergerakan Keluar</td>
            <td style="padding:4px 8px; font-weight:bold" id="modal-mv-out"></td>
          </tr>

          <!-- Stok Opname & Expired -->
          <tr style="background:#fef9e7">
            <td style="padding:4px 8px; color:#7d6608; font-weight:600">Stok Opname (Input)</td>
            <td style="padding:4px 8px; font-weight:bold; color:#7d6608" id="modal-stok-so"></td>
            <td style="padding:4px 8px; color:#78281f; font-weight:600">Stok Expired</td>
            <td style="padding:4px 8px; font-weight:bold; color:#78281f" id="modal-stok-exp"></td>
          </tr>

          <!-- Adjustment -->
          <tr style="background:#eaf4fb">
            <td style="padding:4px 8px; color:#1a5276; font-weight:600">Adjustment</td>
            <td style="padding:4px 8px; font-weight:bold; color:#1a5276" id="modal-stok-adj"></td>
            <td colspan="2"></td>
          </tr>

          <!-- Selisih & Stok Final -->
          <tr style="background:#fff; border-top:2px solid #ccc">
            <td style="padding:6px 8px; font-weight:700; color:#555">Selisih</td>
            <td style="padding:6px 8px" id="modal-selisih-val"></td>
            <td style="padding:6px 8px; font-weight:700; color:#784212">Stok Final</td>
            <td style="padding:6px 8px; font-weight:bold; color:#784212" id="modal-stok-final"></td>
          </tr>

        </table>

        <!-- ── Klarifikasi ────────────────────────────────────────────── -->
        <div class="form-group" style="margin-bottom:4px">
          <label style="font-size:12px; font-weight:600">
            Klarifikasi / Keterangan Selisih <span class="text-danger">*</span>
          </label>
          <textarea id="modal-klarifikasi" rows="3" class="form-control"
                    placeholder="Jelaskan penyebab selisih stok ini..."
                    style="font-size:12px; resize:vertical; height: 70px !important"></textarea>
          <small id="modal-klar-error" class="text-danger" style="display:none">
            <i class="fa fa-warning"></i> Klarifikasi wajib diisi jika terdapat selisih.
          </small>
        </div>

      </div>

      <div class="modal-footer" style="padding:10px 15px">
        <button type="button" id="btn-modal-confirm-selisih" class="btn btn-sm btn-primary">
          <i class="fa fa-check"></i> Konfirmasi &amp; Simpan
        </button>
        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">
          <i class="fa fa-times"></i> Batal
        </button>
      </div>

    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════════════ -->
<!-- Page layout                                                             -->
<!-- ═══════════════════════════════════════════════════════════════════════ -->
<div class="row">
  <div class="col-xs-12">

    <div class="page-header" style="margin-bottom: 12px">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs) ? $breadcrumbs : '' ?>
        </small>
      </h1>
    </div>

    <!-- ─── Info cards ─────────────────────────────────────────────── -->
    <div class="row" style="margin-bottom: 10px">

      <div class="col-md-6">
        <div class="panel panel-primary" style="margin-bottom: 10px">
          <div class="panel-heading" style="padding: 9px 14px">
            <span style="font-size:13px; font-weight:600">
              <i class="fa fa-clipboard" style="margin-right:5px"></i>
              Informasi Agenda Stok Opname
            </span>
          </div>
          <div class="panel-body" style="padding: 10px 14px">
            <table class="so-info-table" style="width:100%">
              <tr>
                <td class="lbl">Nama Agenda</td>
                <td><strong><?php echo isset($value->agenda_so_name) ? ucwords($value->agenda_so_name) : '-' ?></strong></td>
              </tr>
              <tr>
                <td class="lbl">Tgl Pelaksanaan</td>
                <td><?php echo isset($value->agenda_so_date) ? $this->tanggal->formatDate($value->agenda_so_date) : '-' ?></td>
              </tr>
              <tr>
                <td class="lbl">Cut-off Stok</td>
                <td>
                  <span class="label label-info" style="font-size:11px">
                    <?php echo isset($value->agenda_so_cut_off_stock) ? $this->tanggal->formatDatedmY($value->agenda_so_cut_off_stock) : '-' ?>
                  </span>
                </td>
              </tr>
              <tr>
                <td class="lbl">Penanggung Jawab</td>
                <td><?php echo isset($value->agenda_so_spv) ? $value->agenda_so_spv : '-' ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="panel panel-info" style="margin-bottom: 10px">
          <div class="panel-heading" style="padding: 9px 14px">
            <span style="font-size:13px; font-weight:600">
              <i class="fa fa-user" style="margin-right:5px"></i>
              Sesi Input Aktif
            </span>
            <a href="#" id="sign-out-sess-so" class="btn btn-xs btn-danger pull-right" style="margin-top:-1px">
              <i class="fa fa-sign-out"></i> Keluar Sesi
            </a>
          </div>
          <div class="panel-body" style="padding: 10px 14px">
            <table class="so-info-table" style="width:100%">
              <tr>
                <td class="lbl">Tgl Input</td>
                <td>
                  <?php echo isset($this->session->userdata('session_input_so')['tanggal_input'])
                    ? $this->tanggal->formatDate($this->session->userdata('session_input_so')['tanggal_input'])
                    : '-' ?>
                </td>
              </tr>
              <tr>
                <td class="lbl">Waktu/Jam</td>
                <td>
                  <?php echo isset($this->session->userdata('session_input_so')['waktu_input'])
                    ? $this->session->userdata('session_input_so')['waktu_input']
                    : '-' ?>
                </td>
              </tr>
              <tr>
                <td class="lbl">Petugas</td>
                <td>
                  <strong><?php echo isset($this->session->userdata('session_input_so')['nama_pegawai'])
                    ? $this->session->userdata('session_input_so')['nama_pegawai']
                    : '-' ?></strong>
                </td>
              </tr>
              <tr>
                <td class="lbl">Bagian / Unit</td>
                <td>
                  <strong><?php echo isset($this->session->userdata('session_input_so')['bagian'])
                    ? $this->session->userdata('session_input_so')['nama_bagian']
                    : '-' ?></strong>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>

    </div><!-- /.row info cards -->

    <!-- ─── Filter Panel ──────────────────────────────────────────── -->
    <form class="form-horizontal" method="post" id="form_Create_agenda_so"
          action="<?php echo site_url('inventory/so/Create_agenda_so/process')?>"
          enctype="multipart/form-data">

      <input type="hidden" name="kode_bagian" id="kode_bagian"
             value="<?php echo isset($this->session->userdata('session_input_so')['bagian'])
               ? $this->session->userdata('session_input_so')['bagian'] : '' ?>">
      <input type="hidden" id="flag_string" value="">

      <div class="panel panel-default" style="margin-bottom:10px; border-top:3px solid #3a8fc1">
        <div class="panel-heading" style="padding:9px 14px; background:#f4f8fb">
          <span style="font-size:13px; font-weight:600; color:#3a8fc1">
            <i class="fa fa-filter" style="margin-right:5px"></i>
            Filter Pencarian Barang
          </span>
        </div>
        <div class="panel-body" style="padding:12px 16px">

          <div id="section_non_medis">
            <div class="form-group" style="margin-bottom:0">
              <label class="control-label col-md-1" style="font-size:12px;font-weight:600">Golongan</label>
              <div class="col-md-3">
                <?php echo $this->master->custom_selection(array('table'=>'mt_golongan_nm','id'=>'kode_golongan','name'=>'nama_golongan','where'=>array()),''  ,'kode_golongan','kode_golongan','form-control input-sm','','') ?>
              </div>
              <label class="control-label col-md-1" style="font-size:12px;font-weight:600">Rak/Lemari</label>
              <div class="col-md-3">
                <?php echo $this->master->custom_selection(array('table'=>'global_parameter','id'=>'value','name'=>'label','where'=>array('flag'=>'rak_non_medis')),''  ,'rak_nm','rak_nm','form-control input-sm','','') ?>
              </div>
              <div class="col-md-4">
                <button type="button" class="btn btn-sm btn-primary" onclick="find_data_reload()">
                  <i class="fa fa-search"></i> Proses Pencarian
                </button>
              </div>
            </div>
          </div>

          <div id="section_medis" style="display:none">
            <div class="form-group" style="margin-bottom:0">
              <label class="control-label col-md-1" style="font-size:12px;font-weight:600">Kategori</label>
              <div class="col-md-2">
                <?php echo $this->master->custom_selection(array('table'=>'mt_kategori','id'=>'kode_kategori','name'=>'nama_kategori','where'=>array()),''  ,'kode_kategori','kode_kategori','form-control input-sm','','') ?>
              </div>
              <label class="control-label col-md-1" style="font-size:12px;font-weight:600">Rak/Lemari</label>
              <div class="col-md-3">
                <?php echo $this->master->custom_selection(array('table'=>'global_parameter','id'=>'value','name'=>'label','where'=>array('flag'=>'rak_medis','reff_id'=>isset($this->session->userdata('session_input_so')['bagian'])?$this->session->userdata('session_input_so')['bagian']:'')),'' ,'rak_m','rak_m','form-control input-sm','','') ?>
              </div>
              <div class="col-md-5">
                <button type="button" class="btn btn-sm btn-primary" onclick="find_data_reload()">
                  <i class="fa fa-search"></i> Proses Pencarian
                </button>
              </div>
            </div>
          </div>

        </div>
      </div>

    </form>

    <!-- ─── DataTable Panel ────────────────────────────────────────── -->
    <div class="panel panel-default" style="border-top:3px solid #27ae60">
      <div class="panel-heading" style="padding:9px 14px; background:#f4fbf6">
        <span style="font-size:13px; font-weight:600; color:#1e8449">
          <i class="fa fa-table" style="margin-right:5px"></i>
          Data Barang Stok Opname
        </span>
        <span class="pull-right" style="font-size:11px; color:#888; line-height:22px">
          <i class="fa fa-info-circle"></i>
          Isi Stok Opname &rarr; klik <strong>Save Draft</strong> untuk simpan sementara, lalu <strong>Save Final</strong> untuk memutasi stok
        </span>
      </div>
      <div class="panel-body" style="padding:10px; overflow-x:auto">

        <table id="dt-input-so-bag"
               base-url="inventory/so/Input_dt_so/get_data?bag=<?php echo $this->session->userdata('session_input_so')['bagian']?>&cutoff=<?php echo isset($value->agenda_so_cut_off_stock) ? $value->agenda_so_cut_off_stock : '' ?>"
               class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th class="center" style="width:40px">NO</th>
              <th>NAMA BARANG</th>
              <th class="center" style="width:70px">SATUAN</th>
              <th class="center so-th-cutoff" style="width:100px">
                CUT OFF STOK
                <small>Tgl <?php echo isset($value->agenda_so_cut_off_stock) ? $this->tanggal->formatDatedmY($value->agenda_so_cut_off_stock) : '' ?></small>
              </th>
              <th class="center so-th-stokakhir" style="width:90px">
                STOK AKHIR
                <small>Stok Berjalan</small>
              </th>
              <th class="center so-th-pergerakan" style="width:90px">
                PERGERAKAN
                <small>Sejak Freeze</small>
              </th>
              <th class="center so-th-stokfisik" style="width:90px">
                STOK OPNAME
                <small>Input Fisik</small>
              </th>
              <th class="center so-th-exp" style="width:90px">
                STOK EXPIRED
                <small>Input</small>
              </th>
              <th class="center so-th-selisih" style="width:80px">
                SELISIH
                <small>SO &minus; Sistem</small>
              </th>
              <th class="center so-th-adj" style="width:85px">
                ADJUSTMENT
                <small>Input</small>
              </th>
              <th class="center so-th-stokfinal" style="width:90px">
                STOK FINAL
                <small>Hasil Hitung</small>
              </th>
              <th class="center" style="width:80px">STATUS<br>AKTIF</th>
              <th style="min-width:120px">KLARIFIKASI</th>
              <th class="center" style="width:110px">STATUS / SIMPAN</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

      </div>
    </div>

  </div><!-- /.col-xs-12 -->
</div><!-- /.row -->
