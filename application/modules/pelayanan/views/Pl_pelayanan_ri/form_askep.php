<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>
<script src="<?php echo base_url()?>assets/js/custom/chart.js"></script>

<style>
/* ── CPPT base (shared) ── */
.cppt-card-hdr {
  background: linear-gradient(135deg,#f0f9ff,#e8f4fd);
  border-bottom: 1px solid #bae6fd;
  padding: 7px 12px;
  border-radius: 6px 6px 0 0;
  font-size: 12px;
}
.cppt-card-hdr .ppa-name { font-weight:700; color:#0f172a; }
.cppt-card-hdr .ppa-role { color:#475569; font-size:11px; }
.cppt-card-wrap {
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  overflow: hidden;
  background: #f8fafc;
}
.cppt-card-body { padding: 10px 14px; }
/* ── Askep entry cards ── */
.askep-entry {
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 8px;
  background: #fff;
  transition: box-shadow .15s;
}
.askep-entry:hover { box-shadow: 0 2px 10px rgba(0,0,0,.07); }
.askep-hdr {
  background: linear-gradient(135deg,#f0f9ff,#e8f4fd);
  border-bottom: 1px solid #bae6fd;
  padding: 6px 12px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 11.5px;
  gap: 8px;
}
.askep-hdr .askep-date { font-weight:700; color:#0369a1; }
.askep-hdr .askep-author { color:#475569; }
.askep-body {
  padding: 8px 14px;
  font-size: 12.5px;
  color: #1e293b;
  line-height: 1.6;
  white-space: pre-wrap;
  word-break: break-word;
}
.askep-entry.is-deleted .askep-body,
.askep-entry.is-deleted .askep-date,
.askep-entry.is-deleted .askep-author {
  text-decoration: line-through;
  color: #dc2626 !important;
}
/* ── Section dividers ── */
.askep-section-hdr {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 18px 0 10px;
  padding-bottom: 8px;
  border-bottom: 2px solid #e2e8f0;
}
.askep-section-hdr .askep-section-title {
  font-size: 13px;
  font-weight: 700;
  color: #0f172a;
}
.askep-section-hdr i { color: #0369a1; font-size: 14px; }
/* ── Page header ── */
.askep-page-hdr {
  text-align: center;
  margin-bottom: 18px;
  padding-bottom: 12px;
  border-bottom: 2px solid #e2e8f0;
}
.askep-page-hdr .askep-page-title {
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 3px 0;
}
.askep-page-hdr small { color: #64748b; font-size: 12px; }
/* ── Input card ── */
.askep-input-card {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  margin-bottom: 18px;
  overflow: hidden;
}
.askep-input-card .askep-input-hdr {
  background: linear-gradient(135deg,#f0f9ff,#e8f4fd);
  border-bottom: 1px solid #bae6fd;
  padding: 8px 14px;
  font-size: 12.5px;
  font-weight: 700;
  color: #0369a1;
}
.askep-input-card .askep-input-body { padding: 14px 16px; }
/* ── Empty state ── */
.askep-empty {
  text-align: center;
  padding: 20px;
  color: #94a3b8;
  font-size: 12.5px;
  background: #f8fafc;
  border: 1px dashed #e2e8f0;
  border-radius: 6px;
  margin-bottom: 8px;
}
</style>

<script type="text/javascript">

jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

  $('#timepicker1').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#timepicker1').timepicker('showWidget');
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

});

$(document).ready(function() {

  $('#btn_save_askep').click(function (e) {
    e.preventDefault();
    $.ajax({
      url: $('#form_pelayanan').attr('action'),
      data: $('#form_pelayanan').serialize(),
      dataType: "json",
      type: "POST",
      complete: function(xhr) {
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        if(jsonResponse.status === 200){
          $('#btn_form_askep').click();
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
  $.getJSON('pelayanan/Pl_pelayanan_ri/update_status_dt_monitoring', {ID: id, table: 'th_asuhan_keperawatan', deleted : status} , function(response_data) {
    if(status == 1){
      $('#tbl_dt_'+id).addClass('is-deleted');
      $('#btn_action_'+id).html("<a href='#' onclick='set_line_through("+id+", 0)'><i class='fa fa-refresh' style='color:#16a34a'></i></a>");
    }else{
      $('#tbl_dt_'+id).removeClass('is-deleted');
      $('#btn_action_'+id).html("<a href='#' onclick='set_line_through("+id+", 1)'><i class='fa fa-times-circle' style='color:#dc2626'></i></a>");
    }
  });
}

</script>

<div class="row">
  <div class="col-md-12">

    <!-- Page header -->
    <div class="askep-page-hdr">
      <p class="askep-page-title">
        <i class="fa fa-heartbeat" style="color:#0369a1; margin-right:7px;"></i>Asuhan Keperawatan
      </p>
      <small><i>Catatan Keperawatan &amp; Perkembangan / Evaluasi</i></small>
    </div>

    <!-- Input form card -->
    <div class="askep-input-card">
      <div class="askep-input-hdr">
        <i class="fa fa-plus-circle" style="margin-right:5px;"></i> Input Catatan Baru
      </div>
      <div class="askep-input-body">
        <div class="form-horizontal">

          <div class="form-group">
            <label class="control-label col-sm-2">Tanggal / Jam <span style="color:#dc2626">*</span></label>
            <div class="col-md-6">
              <div class="input-group">
                <input name="tgl_askep" id="tgl_askep" placeholder="<?php echo date('Y-m-d')?>"
                       data-date-format="yyyy-mm-dd" class="form-control date-picker"
                       type="text" value="<?php echo date('Y-m-d')?>">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input id="timepicker1" name="jam_askep" type="text" class="form-control">
                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-2">Jenis Catatan</label>
            <div class="col-md-5">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_askep')), '' , 'jenis_catatan_askep', 'jenis_catatan_askep', 'form-control', '', '');?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-2">Catatan</label>
            <div class="col-md-8">
              <textarea class="form-control" name="catatan_askep" id="catatan_askep"
                        style="height:130px !important" placeholder="Tuliskan catatan keperawatan di sini..."></textarea>
            </div>
          </div>

          <div class="form-group" style="margin-bottom:0;">
            <label class="col-sm-2">&nbsp;</label>
            <div class="col-md-10">
              <a href="#" class="btn btn-sm btn-primary" id="btn_save_askep">
                <i class="fa fa-save"></i> Simpan Catatan
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- ─── Catatan Tindakan Keperawatan ─── -->
    <div class="askep-section-hdr">
      <i class="fa fa-stethoscope"></i>
      <span class="askep-section-title">Catatan Tindakan Keperawatan</span>
    </div>

    <?php
      $has_tindakan = false;
      foreach ($askep as $row) {
        if ($row->jenis_catatan == 'catatan_tindakan') { $has_tindakan = true; break; }
      }
      if (!$has_tindakan):
    ?>
    <div class="askep-empty">
      <i class="fa fa-inbox" style="font-size:20px; display:block; margin-bottom:5px;"></i>
      Belum ada catatan tindakan keperawatan
    </div>
    <?php else: $no = 0; ?>
    <?php foreach ($askep as $row): ?>
    <?php if ($row->jenis_catatan == 'catatan_tindakan'): $no++; ?>
    <div class="askep-entry <?php echo ($row->is_deleted == 1) ? 'is-deleted' : '' ?>" id="tbl_dt_<?php echo $row->id ?>">
      <div class="askep-hdr">
        <span class="askep-date">
          <i class="fa fa-calendar-o" style="margin-right:4px;"></i>
          <?php echo $this->tanggal->formatDateDmy($row->tgl_askep) . ' ' . $this->tanggal->formatTime($row->jam_askep) ?>
        </span>
        <span class="askep-author">
          <i class="fa fa-user-md" style="margin-right:3px;"></i><?php echo htmlspecialchars($row->created_by) ?>
        </span>
        <span id="btn_action_<?php echo $row->id ?>">
          <?php if ($row->is_deleted == 1): ?>
            <a href="#" onclick="set_line_through(<?php echo $row->id ?>, 0)"><i class="fa fa-refresh" style="color:#16a34a"></i></a>
          <?php else: ?>
            <a href="#" onclick="set_line_through(<?php echo $row->id ?>, 1)"><i class="fa fa-times-circle" style="color:#dc2626"></i></a>
          <?php endif ?>
        </span>
      </div>
      <div class="askep-body"><?php echo nl2br(htmlspecialchars($row->catatan_askep)) ?></div>
    </div>
    <?php endif ?>
    <?php endforeach ?>
    <?php endif ?>

    <!-- ─── Evaluasi (SOAP) ─── -->
    <div class="askep-section-hdr">
      <i class="fa fa-clipboard"></i>
      <span class="askep-section-title">Evaluasi (SOAP)</span>
    </div>

    <?php
      $has_evaluasi = false;
      foreach ($askep as $row) {
        if ($row->jenis_catatan == 'evaluasi_soap') { $has_evaluasi = true; break; }
      }
      if (!$has_evaluasi):
    ?>
    <div class="askep-empty">
      <i class="fa fa-inbox" style="font-size:20px; display:block; margin-bottom:5px;"></i>
      Belum ada data evaluasi SOAP
    </div>
    <?php else: $no = 0; ?>
    <?php foreach ($askep as $row): ?>
    <?php if ($row->jenis_catatan == 'evaluasi_soap'): $no++; ?>
    <div class="askep-entry <?php echo ($row->is_deleted == 1) ? 'is-deleted' : '' ?>" id="tbl_dt_<?php echo $row->id ?>">
      <div class="askep-hdr">
        <span class="askep-date">
          <i class="fa fa-calendar-o" style="margin-right:4px;"></i>
          <?php echo $this->tanggal->formatDateDmy($row->tgl_askep) . ' ' . $this->tanggal->formatTime($row->jam_askep) ?>
        </span>
        <span class="askep-author">
          <i class="fa fa-user-md" style="margin-right:3px;"></i><?php echo htmlspecialchars($row->created_by) ?>
        </span>
        <span id="btn_action_<?php echo $row->id ?>">
          <?php if ($row->is_deleted == 1): ?>
            <a href="#" onclick="set_line_through(<?php echo $row->id ?>, 0)"><i class="fa fa-refresh" style="color:#16a34a"></i></a>
          <?php else: ?>
            <a href="#" onclick="set_line_through(<?php echo $row->id ?>, 1)"><i class="fa fa-times-circle" style="color:#dc2626"></i></a>
          <?php endif ?>
        </span>
      </div>
      <div class="askep-body"><?php echo nl2br(htmlspecialchars($row->catatan_askep)) ?></div>
    </div>
    <?php endif ?>
    <?php endforeach ?>
    <?php endif ?>

  </div>
</div>
