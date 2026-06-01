<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>
<script src="<?php echo base_url()?>assets/js/custom/chart.js"></script>

<style>
/* ── Page header ── */
.obat-page-hdr {
  text-align: center;
  margin-bottom: 18px;
  padding-bottom: 12px;
  border-bottom: 2px solid #e2e8f0;
}
.obat-page-hdr .obat-page-title {
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 3px 0;
}
.obat-page-hdr small { color: #64748b; font-size: 12px; }

/* ── Input card ── */
.obat-input-card {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  margin-bottom: 18px;
  overflow: hidden;
}
.obat-input-card .obat-input-hdr {
  background: linear-gradient(135deg, #f0f9ff, #e8f4fd);
  border-bottom: 1px solid #bae6fd;
  padding: 8px 14px;
  font-size: 12.5px;
  font-weight: 700;
  color: #0369a1;
}
.obat-input-card .obat-input-body { padding: 14px 16px; }

/* ── Sumber obat btn-group ── */
.obat-src-btn { font-size: 12px !important; }
.obat-src-hint { font-size: 11px; color: #64748b; margin-left: 8px; display: inline-block; }

/* ── Daftar obat dari resep ── */
.obat-resep-wrap {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  overflow: hidden;
  max-height: 220px;
  overflow-y: auto;
}
.obat-resep-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 7px 12px;
  border-bottom: 1px solid #f1f5f9;
  cursor: pointer;
  font-size: 12.5px;
  transition: background .12s, border-color .12s;
}
.obat-resep-item:last-child { border-bottom: none; }
.obat-resep-item:hover { background: #eff6ff; }
.obat-resep-item.selected { background: #dbeafe; border-color: #93c5fd; }
.obat-resep-item .obat-resep-nama { flex: 1; font-weight: 600; color: #0f172a; }
.obat-resep-item .obat-resep-jml {
  font-size: 11px; color: #64748b;
  background: #f1f5f9; padding: 2px 6px; border-radius: 4px;
}
.obat-resep-empty {
  text-align: center; padding: 16px; color: #94a3b8;
  font-size: 12.5px;
}
.obat-resep-loading { padding: 12px; text-align: center; color: #64748b; font-size: 12px; }

/* ── Waktu pemberian sub-card ── */
.obat-waktu-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  margin: 10px 0 14px;
  overflow: hidden;
}
.obat-waktu-hdr {
  background: linear-gradient(135deg, #fefce8, #fef9c3);
  border-bottom: 1px solid #fde68a;
  padding: 7px 14px;
  font-size: 12px;
  font-weight: 700;
  color: #92400e;
}
.obat-waktu-body { padding: 10px 14px; }
.obat-waktu-row {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 5px 0;
  border-bottom: 1px solid #f1f5f9;
  flex-wrap: wrap;
}
.obat-waktu-row:last-child { border-bottom: none; }

/* ── Section dividers ── */
.obat-section-hdr {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 18px 0 10px;
  padding-bottom: 8px;
  border-bottom: 2px solid #e2e8f0;
}
.obat-section-hdr .obat-section-title {
  font-size: 13px;
  font-weight: 700;
  color: #0f172a;
}
.obat-section-hdr i { color: #0369a1; font-size: 14px; }

/* ── Table headers ── */
.obat-tbl thead tr th {
  background: linear-gradient(135deg, #0369a1, #0284c7);
  color: #fff;
  font-size: 12px;
  font-weight: 600;
  border: none;
  padding: 8px 10px;
  vertical-align: middle;
}
.obat-tbl tbody tr td { font-size: 12px; vertical-align: middle; }

/* ── Keterangan note ── */
.obat-keterangan {
  font-style: italic;
  font-size: 11px;
  color: #64748b;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  padding: 8px 12px;
  margin-top: 10px;
}
</style>

<div class="row">
  <div class="col-md-12">

    <!-- Page header -->
    <div class="obat-page-hdr">
      <p class="obat-page-title">
        <i class="fa fa-medkit" style="color:#0369a1; margin-right:7px;"></i>Rencana &amp; Pelaksanaan Pemberian Obat
      </p>
      <small><i>Jadwal dan monitoring pemberian obat pasien rawat inap</i></small>
    </div>

    <input type="hidden" name="noMrPasienPemberianObat" id="noMrPasienPemberianObat" value="<?php echo $no_mr?>">

    <!-- Input form card -->
    <div class="obat-input-card">
      <div class="obat-input-hdr">
        <i class="fa fa-plus-circle" style="margin-right:5px;"></i> Input Rencana Pemberian Obat
      </div>
      <div class="obat-input-body">
        <div class="form-horizontal">

          <div class="form-group">
            <label class="control-label col-sm-2">Tanggal / Jam <span style="color:#dc2626">*</span></label>
            <div class="col-md-5">
              <div class="input-group">
                <input name="tgl_obat" id="tgl_obat" placeholder="<?php echo date('Y-m-d')?>"
                       data-date-format="yyyy-mm-dd" class="form-control date-picker"
                       type="text" value="<?php echo date('Y-m-d')?>">
                <span class="input-group-addon"><i class="ace-icon fa fa-calendar"></i></span>
                <input id="jam_obat" name="jam_obat" type="text" class="form-control">
                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
              </div>
            </div>
          </div>

          <!-- Sumber Obat selector -->
          <div class="form-group">
            <label class="control-label col-sm-2">Sumber Obat</label>
            <div class="col-md-9" style="padding-left: 18px">
              <div class="btn-group" id="sumber_obat_grp">
                <button type="button" class="btn btn-sm btn-primary active obat-src-btn" id="btn_src_farmasi"
                        onclick="switchSumberObat('farmasi')">
                  <i class="fa fa-hospital-o"></i> Resep Farmasi
                </button>
                <button type="button" class="btn btn-sm btn-default obat-src-btn" id="btn_src_eresep"
                        onclick="switchSumberObat('eresep')">
                  <i class="fa fa-laptop"></i> eResep
                </button>
                <button type="button" class="btn btn-sm btn-default obat-src-btn" id="btn_src_manual"
                        onclick="switchSumberObat('manual')">
                  <i class="fa fa-keyboard-o"></i> Input Manual
                </button>
              </div>
              <span class="obat-src-hint" id="obat_src_hint">Memuat daftar obat dari Resep Farmasi...</span>
            </div>
          </div>

          <!-- Daftar obat dari resep (shown when farmasi/eresep selected) -->
          <div class="form-group" id="fg_obat_resep">
            <label class="control-label col-sm-2">Pilih Obat</label>
            <div class="col-md-7" style="padding-left: 18px">
              <div class="obat-resep-wrap">
                <div class="obat-resep-loading" id="obat_resep_loading">
                  <i class="fa fa-spinner fa-spin"></i> Memuat daftar obat...
                </div>
                <div id="obat_resep_empty" class="obat-resep-empty" style="display:none;">
                  <i class="fa fa-inbox" style="font-size:18px; display:block; margin-bottom:5px;"></i>
                  Tidak ada obat dalam resep ini
                </div>
                <div id="obat_resep_items"></div>
              </div>
              <small class="text-muted" style="font-size:11px; margin-top:4px; display:block;">
                Klik obat untuk memilih. Obat yang dipilih akan terisi otomatis di bawah.
              </small>
            </div>
          </div>

          <!-- Nama Obat (always visible; readonly when resep/eresep mode) -->
          <div class="form-group">
            <label class="control-label col-sm-2">Nama Obat</label>
            <div class="col-md-6">
              <input type="text" class="form-control" name="nama_obat" id="nama_obat"
                     placeholder="Pilih dari daftar di atas..." value="" readonly
                     style="background:#f8fafc; cursor:default;">
              <input type="hidden" name="kode_brg" id="kode_brg" value="">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-2">Dosis</label>
            <div class="col-md-1">
              <input type="text" class="form-control" name="dosis" id="dosis" value="">
            </div>
            <label class="control-label col-sm-1">Frek</label>
            <div class="col-md-1">
              <input type="text" class="form-control" name="frek" id="frek" value="">
            </div>
            <label class="control-label col-sm-1">Rute</label>
            <div class="col-md-1">
              <input type="text" class="form-control" name="rute" id="rute" value="">
            </div>
            <label class="control-label col-sm-1">Jenis Terapi</label>
            <div class="col-md-2">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_terapi')), '' , 'jenis_terapi', 'jenis_terapi', 'form-control', '', '');?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-2">Catatan</label>
            <div class="col-md-7">
              <textarea class="form-control" name="catatan_obat" id="catatan_obat"
                        style="height:50px !important" placeholder="Catatan tambahan..."></textarea>
            </div>
          </div>

          <!-- Waktu Pemberian Obat -->
          <div class="form-group">
            <label class="control-label col-sm-2" style="padding-top:0;">Waktu Pemberian</label>
            <div class="col-md-10">
              <div class="obat-waktu-card">
                <div class="obat-waktu-hdr">
                  <i class="fa fa-clock-o" style="margin-right:5px;"></i> Jadwal Waktu Pemberian Obat
                </div>
                <div class="obat-waktu-body">
                  <?php foreach($waktu as $row) : ?>
                  <div class="obat-waktu-row">
                    <div style="min-width:100px;">
                      <label style="margin:0; font-weight:600; font-size:12.5px;">
                        <input type="checkbox" class="ace" name="waktu[<?php echo $row->value?>]"
                               id="<?php echo 'waktu_'.$row->value?>" value="<?php echo $row->value?>">
                        <span class="lbl"> <?php echo $row->label?></span>
                      </label>
                    </div>
                    <label style="margin:0; font-size:12px; color:#475569; min-width:28px;">Jam</label>
                    <div style="width:120px;">
                      <input type="time" name="jam[<?php echo $row->value?>]" class="form-control"
                             style="font-size:12px; padding:4px 8px;"
                             id="jam_<?php echo $row->value?>"/>
                    </div>
                    <label style="margin:0; font-size:12px; color:#475569; min-width:48px;">Catatan</label>
                    <div style="flex:1; min-width:160px;">
                      <input type="text" name="catatan[<?php echo $row->value?>]" class="form-control"
                             style="font-size:12px; padding:4px 8px;"
                             placeholder="Catatan <?php echo $row->label?>"
                             id="catatan_<?php echo $row->value?>" />
                    </div>
                  </div>
                  <?php endforeach;?>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group" style="margin-bottom:0;">
            <label class="col-sm-2">&nbsp;</label>
            <div class="col-md-10">
              <a href="#" class="btn btn-sm btn-primary" id="btn_save_pemberian_obat">
                <i class="fa fa-save"></i> Simpan
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- ─── Obat Parenteral ─── -->
    <div class="obat-section-hdr">
      <i class="fa fa-tint"></i>
      <span class="obat-section-title">Obat Parenteral</span>
    </div>

    <table class="table obat-tbl" id="tbl_pemberian_obat_parenteral">
      <thead>
        <tr>
          <th width="30px" style="text-align:center;">No</th>
          <th width="100px">Waktu Input</th>
          <th width="200px">Nama Obat</th>
          <?php foreach($waktu as $row) : ?>
          <th width="150px" style="text-align:center;"><?php echo $row->label?></th>
          <?php endforeach; ?>
          <th width="80px" style="text-align:center;">Ttd Perawat</th>
          <th width="80px" style="text-align:center;">Ttd Keluarga Pasien</th>
          <th width="120px" style="text-align:center;">Catatan</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <!-- ─── Obat Non Parenteral ─── -->
    <div class="obat-section-hdr">
      <i class="fa fa-medkit"></i>
      <span class="obat-section-title">Obat Non Parenteral</span>
    </div>

    <table class="table obat-tbl" id="tbl_pemberian_obat_enteral">
      <thead>
        <tr>
          <th width="30px" style="text-align:center;">No</th>
          <th width="100px">Waktu Input</th>
          <th width="200px">Nama Obat</th>
          <?php foreach($waktu as $row) : ?>
          <th width="150px" style="text-align:center;"><?php echo $row->label?></th>
          <?php endforeach; ?>
          <th width="80px" style="text-align:center;">Ttd Perawat</th>
          <th width="80px" style="text-align:center;">Ttd Keluarga Pasien</th>
          <th width="120px" style="text-align:center;">Catatan</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <div class="obat-keterangan">
      <i class="fa fa-info-circle" style="margin-right:4px; color:#0369a1;"></i>
      <b>Keterangan:</b> Jadwal pemberian obat tiap shift harus ditandatangani oleh perawat dan keluarga pasien.
    </div>

  </div>
</div>

<!-- Modal TTD -->
<div id="modalTTDPersetujuanPemberianObat" class="modal fade" tabindex="-1">
  <div class="modal-dialog" style="overflow-y:scroll; max-height:90%; margin-top:50px; margin-bottom:50px; width:95%;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
          </button>
          <span id="title_ttd_persetujuan">TANDA TANGAN PASIEN (DIGITAL SIGNATURE)</span>
        </div>
      </div>
      <div class="modal-body">
        <div id="form_pasien_modal_ttd"></div>
        <input type="hidden" name="id_pemberian_obat" id="id_pemberian_obat" value="">
        <input type="hidden" name="flag_ttd" id="flag_ttd" value="">
        <button type="button" id="save_ttd_pasien_form" name="submit" class="btn btn-xs btn-primary">
          <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i> Submit
        </button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

/* ── Helper: HTML-escape for dynamic content ── */
function obatEscHtml(s) {
  return $('<div>').text(String(s || '')).html();
}

/* ── Sumber obat state ── */
var currentSumberObat = 'farmasi';

function switchSumberObat(sumber) {
  currentSumberObat = sumber;

  // Update button styles
  $('#btn_src_farmasi, #btn_src_eresep, #btn_src_manual')
    .removeClass('btn-primary active').addClass('btn-default');
  $('#btn_src_' + sumber).removeClass('btn-default').addClass('btn-primary active');

  if (sumber === 'manual') {
    $('#fg_obat_resep').hide();
    $('#nama_obat')
      .prop('readonly', false)
      .css({'background': '', 'cursor': ''})
      .attr('placeholder', 'Ketik nama obat...')
      .val('');
    $('#kode_brg').val('');
    $('#obat_src_hint').text('Cari obat secara manual (ketik minimal 3 karakter)');
    // Re-bind typeahead now that field is editable
    initObatTypeahead();
  } else {
    $('#fg_obat_resep').show();
    $('#nama_obat')
      .prop('readonly', true)
      .css({'background': '#f8fafc', 'cursor': 'default'})
      .attr('placeholder', 'Pilih dari daftar di atas...')
      .val('');
    $('#kode_brg').val('');
    var hint = sumber === 'farmasi'
      ? 'Menampilkan obat dari Resep Farmasi untuk kunjungan ini'
      : 'Menampilkan obat dari eResep untuk kunjungan ini';
    $('#obat_src_hint').text(hint);
    loadObatByKunjungan(sumber);
  }
}

function loadObatByKunjungan(sumber) {
  var noKunjungan = $('#no_kunjungan').val();
  if (!noKunjungan) {
    $('#obat_resep_loading').hide();
    $('#obat_resep_empty').show().find('p').text('No kunjungan tidak ditemukan');
    return;
  }

  $('#obat_resep_loading').show();
  $('#obat_resep_items').empty();
  $('#obat_resep_empty').hide();

  $.getJSON(
    'pelayanan/Pl_pelayanan_ri/get_obat_by_kunjungan',
    {no_kunjungan: noKunjungan, sumber: sumber},
    function(res) {
      $('#obat_resep_loading').hide();
      if (res.status === 200 && res.data && res.data.length > 0) {
        var html = '';
        $.each(res.data, function(i, item) {
          html += '<div class="obat-resep-item"' +
            ' data-kode="' + obatEscHtml(item.kode_brg) + '"' +
            ' data-nama="' + obatEscHtml(item.nama_obat) + '">' +
            '<i class="fa fa-medkit" style="color:#0369a1; flex-shrink:0;"></i>' +
            '<span class="obat-resep-nama">' + obatEscHtml(item.nama_obat) + '</span>' +
            (item.jumlah ? '<span class="obat-resep-jml">Jml: ' + obatEscHtml(item.jumlah) + '</span>' : '') +
            '</div>';
        });
        $('#obat_resep_items').html(html);
      } else {
        $('#obat_resep_empty').show();
      }
    }
  ).fail(function() {
    $('#obat_resep_loading').hide();
    $('#obat_resep_empty').show();
  });
}

/* Click handler for resep items (event delegation) */
$(document).on('click', '.obat-resep-item', function() {
  var kode = $(this).data('kode');
  var nama = $(this).data('nama');
  $('#kode_brg').val(kode);
  $('#nama_obat').val(nama);
  $('.obat-resep-item').removeClass('selected');
  $(this).addClass('selected');
  // $.achtung({message: 'Obat dipilih: <b>' + obatEscHtml(nama) + '</b>', timeout: 3});
});

function initObatTypeahead() {
  // Destroy existing typeahead first to avoid double binding
  if ($('#nama_obat').data('typeahead')) {
    $('#nama_obat').typeahead('destroy');
  }
  $('#nama_obat').typeahead({
    source: function(query, result) {
      $.ajax({
        url: "templates/references/getObatByBagianAutoCompleteNoInfoStok",
        data: {keyword: query, bag: '060101'},
        dataType: "json",
        type: "POST",
        success: function(response) {
          result($.map(response, function(item) { return item; }));
        }
      });
    },
    afterSelect: function(item) {
      var val_item   = item.split(':')[0];
      var label_item = item.split(':')[1];
      $('#kode_brg').val(val_item);
      $('#nama_obat').val(label_item);
    }
  });
}

jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

  $('#jam_obat').timepicker({
    minuteStep: 1,
    showSeconds: false,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#jam_obat').timepicker('showWidget');
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

});

$(document).ready(function() {

  tbl_pemberian_obat_parenteral = $('#tbl_pemberian_obat_parenteral').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "searching": false,
    "bPaginate": true,
    "bInfo": false,
    "pageLength": 5,
    "dom": 'rtip',
    "ajax": {
      "url": "pelayanan/Pl_pelayanan_ri/get_row_data_pemberian_obat?no_kunjungan=" + $('#no_kunjungan').val() + "&flag=parenteral",
      "type": "POST"
    }
  });

  tbl_pemberian_obat_enteral = $('#tbl_pemberian_obat_enteral').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "searching": false,
    "bPaginate": true,
    "bInfo": false,
    "pageLength": 5,
    "dom": 'rtip',
    "ajax": {
      "url": "pelayanan/Pl_pelayanan_ri/get_row_data_pemberian_obat?no_kunjungan=" + $('#no_kunjungan').val() + "&flag=non_parenteral",
      "type": "POST"
    }
  });

  $('#btn_save_pemberian_obat').click(function(e) {
    e.preventDefault();
    $.ajax({
      url: $('#form_pelayanan').attr('action'),
      data: $('#form_pelayanan').serialize(),
      dataType: "json",
      type: "POST",
      complete: function(xhr) {
        var jsonResponse = JSON.parse(xhr.responseText);
        if (jsonResponse.status === 200) {
          if (jsonResponse.jenis_terapi == 'non_parenteral') {
            tbl_pemberian_obat_enteral.ajax.reload();
          } else {
            tbl_pemberian_obat_parenteral.ajax.reload();
          }
          $('#form_pelayanan')[0].reset();
          $('input[type="checkbox"]').prop('checked', false);
          // Reset obat selection
          $('.obat-resep-item').removeClass('selected');
          $('#nama_obat').val('');
          $('#kode_brg').val('');
          $.achtung({message: jsonResponse.message, timeout: 5});
        } else {
          $.achtung({message: jsonResponse.message, timeout: 5, className: 'achtungFail'});
        }
        achtungHideLoader();
      }
    });
  });

  $('#save_ttd_pasien_form').click(function(e) {
    e.preventDefault();
    $.ajax({
      url: 'pelayanan/Pl_pelayanan_ri/process_save_ttd_pemberian_obat',
      type: "post",
      data: {
        id: $('#id_pemberian_obat').val(),
        flag: $('#flag_ttd').val(),
        signature: $('#paramsSignature').val()
      },
      dataType: "json",
      beforeSend: function() { achtungShowLoader(); },
      success: function(data) {
        achtungHideLoader();
        $('#modalTTDPersetujuanPemberianObat').modal('hide');
        if (data.status == 200) {
          if ($('#flag_ttd').val() == 'perawat') {
            $('#ttd_perawat_id_' + $('#id_pemberian_obat').val()).html('<img src="' + data.signature + '" style="width:100% !important">');
          } else {
            $('#ttd_pasien_id_' + $('#id_pemberian_obat').val()).html('<img src="' + data.signature + '" style="width:100% !important">');
          }
        }
      }
    });
  });

  // Auto-load default source on page ready
  loadObatByKunjungan('farmasi');

});

function set_line_through(id, status, flag) {
  $.getJSON('pelayanan/Pl_pelayanan_ri/update_status_dt_monitoring', {ID: id, table: 'th_monitor_pemberian_obat', deleted: status}, function(response_data) {
    if (response_data.status === 200) {
      if (flag == 'non_parenteral') {
        tbl_pemberian_obat_enteral.ajax.reload();
      } else {
        tbl_pemberian_obat_parenteral.ajax.reload();
      }
    }
  });
}

function update_pelaksanaan_pemberian_obat(id, waktu, value) {
  $.getJSON('pelayanan/Pl_pelayanan_ri/update_pelaksanaan_pemberian_obat', {ID: id, status: value, waktu: waktu}, function(response_data) {
    if (response_data.status === 200) {
      if (response_data.jenis_terapi == 'non_parenteral') {
        tbl_pemberian_obat_enteral.ajax.reload();
      } else {
        tbl_pemberian_obat_parenteral.ajax.reload();
      }
    }
  });
}

function upadte_status_pemberian_obat(id, value) {
  $.getJSON('pelayanan/Pl_pelayanan_ri/update_status_pemberian_obat', {ID: id, val: value}, function(response_data) {
    if (response_data.status === 200) {
      if (response_data.jenis_terapi == 'non_parenteral') {
        tbl_pemberian_obat_enteral.ajax.reload();
      } else {
        tbl_pemberian_obat_parenteral.ajax.reload();
      }
    }
  });
}

function showModalTTD(id, flag) {
  var noMr = $('#noMrPasienPemberianObat').val();
  if (noMr == '') {
    alert('Silahkan cari pasien terlebih dahulu!'); return false;
  }
  $('#title_ttd_persetujuan').text('Tanda Tangan ' + flag.toUpperCase() + ' untuk Persetujuan Pemberian Obat');
  $('#id_pemberian_obat').val(id);
  $('#flag_ttd').val(flag);
  $('#form_pasien_modal_ttd').load('registration/reg_pasien/form_modal_ttd/' + noMr);
  $('#modalTTDPersetujuanPemberianObat').modal();
}

function edit_row(id, flag) {
  $.getJSON('pelayanan/Pl_pelayanan_ri/get_data_pemberian_obat_by_id', {ID: id}, function(response_data) {
    var obj = response_data.data;
    if (response_data.status == 200) {
      $('#id_pemberian_obat').val(obj.id);
      $('#tgl_obat').val(obj.tgl_obat);
      $('#jam_obat').val(obj.jam_obat);
      // When editing, switch to manual mode so nama_obat field is editable
      switchSumberObat('manual');
      $('#nama_obat').val(obj.nama_obat);
      $('#kode_brg').val(obj.kode_brg);
      $('#dosis').val(obj.dosis);
      $('#frek').val(obj.frek);
      $('#rute').val(obj.rute);
      $('#jenis_terapi').val(obj.jenis_terapi);
      $('#catatan_obat').val(obj.catatan);
      var waktu = JSON.parse(obj.waktu);
      $.each(waktu, function(i, o) {
        $('#jam_' + i).val(o.jam);
        $('#catatan_' + i).val(o.catatan);
        $('#waktu_' + i).prop('checked', true);
      });
    } else {
      $.achtung({message: response_data.message, timeout: 5, className: 'achtungFail'});
    }
  });
}

</script>
