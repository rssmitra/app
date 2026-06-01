<style>
/* ── Page header ── */
.note-page-hdr {
  text-align: center;
  margin-bottom: 18px;
  padding-bottom: 12px;
  border-bottom: 2px solid #e2e8f0;
}
.note-page-hdr .note-page-title { font-size:18px; font-weight:700; color:#0f172a; margin:0 0 3px; }
.note-page-hdr small { color:#64748b; font-size:12px; }

/* ── Input card ── */
.note-input-card {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  margin-bottom: 20px;
  overflow: hidden;
}
.note-input-hdr {
  background: linear-gradient(135deg,#f0f9ff,#e8f4fd);
  border-bottom: 1px solid #bae6fd;
  padding: 8px 14px;
  font-size:12.5px; font-weight:700; color:#0369a1;
}
.note-input-body { padding:14px 16px; }

/* ── Canvas toolbar ── */
.canvas-toolbar {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
  align-items: center;
  background: #f1f5f9;
  border: 1px solid #e2e8f0;
  border-bottom: none;
  border-radius: 6px 6px 0 0;
  padding: 7px 10px;
}
.canvas-toolbar .tool-btn {
  border: 1px solid #cbd5e1;
  background: #fff;
  border-radius: 4px;
  padding: 4px 9px;
  font-size: 12px;
  cursor: pointer;
  color: #475569;
  line-height: 1.4;
}
.canvas-toolbar .tool-btn:hover,
.canvas-toolbar .tool-btn.active {
  background: #0369a1;
  border-color: #0369a1;
  color: #fff;
}
.canvas-toolbar .tool-sep {
  width: 1px; height: 24px;
  background: #cbd5e1; margin: 0 3px;
}
.canvas-toolbar input[type="color"] {
  width: 30px; height: 28px;
  border: 1px solid #cbd5e1;
  border-radius: 4px;
  cursor: pointer; padding: 1px 2px; background: #fff;
}
.canvas-toolbar select {
  border: 1px solid #cbd5e1;
  border-radius: 4px; padding: 3px 6px;
  font-size: 12px; color: #475569; background: #fff; cursor: pointer;
}
.upload-img-btn {
  border: 1px solid #0891b2;
  background: #e0f7fa; color: #0369a1;
  border-radius: 4px; padding: 4px 9px;
  font-size: 12px; cursor: pointer; line-height: 1.4; margin-bottom: 0;
}
.upload-img-btn:hover { background: #0369a1; color: #fff; border-color: #0369a1; }

/* ── Color swatch ── */
.color-swatch {
  width: 22px; height: 22px;
  border-radius: 50%; cursor: pointer;
  border: 2px solid transparent;
  padding: 0; outline: none;
}
.color-swatch.active-swatch { border-color: #0f172a !important; box-shadow: 0 0 0 2px #fff inset; }

/* ── Canvas wrapper ── */
.canvas-wrap {
  border: 1px solid #e2e8f0;
  border-radius: 0 0 6px 6px;
  overflow: hidden; background: #fff;
  position: relative; cursor: crosshair; margin-bottom: 10px;
}
#annotationCanvas { display: block; width: 100%; touch-action: none; }
.canvas-placeholder {
  position: absolute; top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  color: #cbd5e1; text-align: center; pointer-events: none;
}
.canvas-placeholder i { font-size: 40px; display:block; margin-bottom:8px; }
.canvas-placeholder span { font-size:13px; }

/* ── Canvas actions ── */
.canvas-actions {
  display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-top: 6px;
}

/* ── History ── */
.note-section-hdr {
  display: flex; align-items: center; gap: 8px;
  margin: 20px 0 10px; padding-bottom: 8px; border-bottom: 2px solid #e2e8f0;
}
.note-section-hdr .note-section-title { font-size:13px; font-weight:700; color:#0f172a; }
.note-section-hdr i { color:#0369a1; font-size:14px; }
.note-entry {
  border: 1px solid #e2e8f0; border-radius: 8px;
  overflow: hidden; margin-bottom: 8px; background: #fff;
}
.note-entry-hdr {
  background: linear-gradient(135deg,#f0f9ff,#e8f4fd);
  border-bottom: 1px solid #bae6fd; padding: 6px 12px;
  display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 6px; font-size: 11.5px;
}
.note-entry-hdr .ne-date  { font-weight:700; color:#0369a1; }
.note-entry-hdr .ne-author { color:#475569; }
.note-entry-hdr .ne-type  {
  display:inline-block; border-radius:4px; padding:1px 8px;
  font-size:10px; font-weight:700; color:#fff; background:#0891b2;
}
.note-entry-body {
  padding: 8px 12px; font-size: 12.5px; color: #1e293b;
  display: flex; align-items: center; justify-content: space-between; gap: 8px;
}
.note-empty {
  text-align: center; padding: 22px; color: #94a3b8;
  font-size: 12.5px; background: #f8fafc;
  border: 1px dashed #e2e8f0; border-radius: 6px;
}
.note-empty i { font-size:22px; display:block; margin-bottom:6px; }
</style>

<!-- ─────────────────────────── HTML ─────────────────────────────────────── -->
<div class="row">
<div class="col-md-12">

  <div class="note-page-hdr">
    <p class="note-page-title">
      <i class="fa fa-pencil-square-o" style="color:#0369a1; margin-right:7px;"></i>Catatan &amp; Gambar
    </p>
    <small><i>Drawing Notes &amp; Anotasi Gambar Medis</i></small>
  </div>

  <div class="note-input-card">
    <div class="note-input-hdr">
      <i class="fa fa-plus-circle" style="margin-right:5px;"></i> Buat Catatan Gambar Baru
    </div>
    <div class="note-input-body">

      <div class="form-horizontal">

        <div class="form-group" style="margin-bottom:8px;">
          <label class="control-label col-sm-2">Dibuat oleh</label>
          <div class="col-md-10">
            <div class="radio" style="margin-top:5px;">
              <label><input name="created_by" type="radio" class="ace" value="perawat" checked="checked"/><span class="lbl"> Perawat</span></label>
              <label><input name="created_by" type="radio" class="ace" value="dokter"/><span class="lbl"> Dokter</span></label>
            </div>
          </div>
        </div>

        <div class="form-group" style="margin-bottom:8px;">
          <label class="control-label col-sm-2">Nama</label>
          <div class="col-md-4">
            <input type="text" class="form-control input-sm" name="created_name"
                   value="<?php echo $this->session->userdata('user')->fullname?>">
          </div>
        </div>

        <div class="form-group" style="margin-bottom:12px;">
          <label class="control-label col-sm-2">Jenis Catatan</label>
          <div class="col-md-10">
            <div class="radio" style="margin-top:5px;">
              <label><input name="note_type" type="radio" class="ace" value="perkembangan_pasien" checked="checked"/><span class="lbl"> Perkembangan Pasien</span></label>
              <label><input name="note_type" type="radio" class="ace" value="catatan_dokter"/><span class="lbl"> Catatan Dokter</span></label>
              <label><input name="note_type" type="radio" class="ace" value="lainnya"/><span class="lbl"> Catatan Lainnya</span></label>
            </div>
          </div>
        </div>

      </div><!-- /form-horizontal -->

      <!-- Toolbar -->
      <div class="canvas-toolbar">
        <button type="button" class="tool-btn active" id="tool-pen" data-tool="pen">
          <i class="fa fa-pencil"></i> Pen
        </button>
        <button type="button" class="tool-btn" id="tool-eraser" data-tool="eraser">
          <i class="fa fa-eraser"></i> Hapus
        </button>

        <div class="tool-sep"></div>

        <button type="button" class="color-swatch" data-color="#e74c3c" style="background:#e74c3c;" title="Merah"></button>
        <button type="button" class="color-swatch" data-color="#1a56db" style="background:#1a56db;" title="Biru"></button>
        <button type="button" class="color-swatch" data-color="#16a34a" style="background:#16a34a;" title="Hijau"></button>
        <button type="button" class="color-swatch" data-color="#d97706" style="background:#d97706;" title="Kuning"></button>
        <button type="button" class="color-swatch" data-color="#0f172a" style="background:#0f172a;" title="Hitam"></button>
        <input type="color" id="colorPicker" value="#e74c3c" title="Pilih warna lain">

        <div class="tool-sep"></div>

        <select id="lineWidth">
          <option value="1">Tipis (1px)</option>
          <option value="3" selected>Normal (3px)</option>
          <option value="6">Tebal (6px)</option>
          <option value="12">Sangat Tebal (12px)</option>
        </select>

        <div class="tool-sep"></div>

        <label class="upload-img-btn" title="Upload gambar sebagai latar">
          <i class="fa fa-image"></i> Upload Gambar
          <input type="file" id="imageUpload" accept="image/*" style="display:none;">
        </label>

        <div class="tool-sep"></div>

        <button type="button" class="tool-btn" id="undoBtn"><i class="fa fa-undo"></i> Undo</button>
        <button type="button" class="tool-btn" id="clearCanvas" style="color:#dc2626; border-color:#dc2626;">
          <i class="fa fa-trash"></i> Clear
        </button>
      </div><!-- /toolbar -->

      <!-- Canvas -->
      <div class="canvas-wrap" id="canvasWrap">
        <canvas id="annotationCanvas" width="900" height="550"></canvas>
        <div class="canvas-placeholder" id="canvasPlaceholder">
          <i class="fa fa-paint-brush"></i>
          <span>Mulai menggambar di sini<br><small>atau upload gambar sebagai latar</small></span>
        </div>
      </div>

      <!-- Actions -->
      <div class="canvas-actions">
        <button type="button" id="finishDrawing" class="btn btn-sm btn-warning">
          <i class="fa fa-check-circle"></i> Selesai Menggambar
        </button>
        <a href="#" class="btn btn-sm btn-primary" id="btn_save_drawing_notes">
          <i class="fa fa-save"></i> Simpan Catatan
        </a>
        <small class="text-muted" style="font-size:11px;">
          <i class="fa fa-info-circle"></i>
          Klik <b>Selesai Menggambar</b> dulu, kemudian <b>Simpan Catatan</b>
        </small>
      </div>

      <input type="hidden" value="" name="paramsSignature" id="paramsSignature">
      <input type="hidden" value="<?php echo isset($value)?$value->no_mr:''?>" name="no_mr_notes" id="no_mr_notes">

    </div><!-- /note-input-body -->
  </div><!-- /note-input-card -->

  <!-- History -->
  <div class="note-section-hdr">
    <i class="fa fa-history"></i>
    <span class="note-section-title">Riwayat Gambar &amp; Catatan</span>
  </div>

  <?php if (count($note) == 0): ?>
  <div class="note-empty">
    <i class="fa fa-inbox"></i>
    Belum ada catatan gambar yang tersimpan
  </div>
  <?php else: $no = 0; ?>
  <?php foreach ($note as $row): $no++; ?>
  <div class="note-entry" id="tbl_dt_<?php echo $row->id ?>">
    <div class="note-entry-hdr">
      <span class="ne-date">
        <i class="fa fa-calendar-o" style="margin-right:3px;"></i>
        <?php echo $this->tanggal->formatDateTimeFormDmy($row->created_date) ?>
      </span>
      <span class="ne-author">
        <i class="fa fa-user-md" style="margin-right:3px;"></i>
        <?php echo htmlspecialchars($row->created_by) ?>
        <span style="color:#94a3b8;">[<?php echo htmlspecialchars($row->type_owner) ?>]</span>
      </span>
      <span class="ne-type"><?php echo ucwords(str_replace('_', ' ', $row->jenis_catatan_draw)) ?></span>
      <span id="btn_action_<?php echo $row->id ?>">
        <?php if ($row->is_deleted == 1): ?>
          <a href="#" onclick="note_set_deleted(<?php echo $row->id ?>, 0)" title="Pulihkan">
            <i class="fa fa-refresh" style="color:#16a34a;"></i>
          </a>
        <?php else: ?>
          <a href="#" onclick="note_set_deleted(<?php echo $row->id ?>, 1)" title="Hapus">
            <i class="fa fa-times-circle" style="color:#dc2626;"></i>
          </a>
        <?php endif ?>
      </span>
    </div>
    <div class="note-entry-body">
      <span style="color:#64748b; font-size:12px;"><i class="fa fa-image" style="margin-right:4px;"></i>1 gambar tersimpan</span>
      <a href="#" onclick="view_drawing(<?php echo $row->id ?>)" class="btn btn-xs btn-info">
        <i class="fa fa-eye"></i> Lihat Gambar
      </a>
    </div>
  </div>
  <?php endforeach ?>
  <?php endif ?>

</div>
</div>

<!-- ──────────────── SCRIPT — placed AFTER HTML so all elements exist ──────── -->
<script>
(function() {

  var canvas  = document.getElementById('annotationCanvas');
  var ctx     = canvas.getContext('2d');
  var placeholder = document.getElementById('canvasPlaceholder');

  var isDrawing    = false;
  var lastX = 0, lastY = 0;
  var currentTool  = 'pen';
  var currentColor = '#e74c3c';
  var currentWidth = 3;
  var undoStack    = [];
  var MAX_UNDO     = 25;

  // ── White background ──────────────────────────────────────────────────────
  function fillWhite() {
    ctx.save();
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.restore();
  }
  fillWhite();

  function hidePlaceholder() {
    if (placeholder) placeholder.style.display = 'none';
  }

  // ── Undo ──────────────────────────────────────────────────────────────────
  function saveState() {
    undoStack.push(canvas.toDataURL());
    if (undoStack.length > MAX_UNDO) undoStack.shift();
  }

  function undo() {
    if (undoStack.length === 0) return;
    var dataUrl = undoStack.pop();
    var img = new Image();
    img.onload = function() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.drawImage(img, 0, 0);
    };
    img.src = dataUrl;
  }

  // ── Coordinate mapping (CSS px → canvas px) ───────────────────────────────
  function getPos(e) {
    var rect   = canvas.getBoundingClientRect();
    var scaleX = canvas.width  / rect.width;
    var scaleY = canvas.height / rect.height;
    return [
      (e.clientX - rect.left) * scaleX,
      (e.clientY - rect.top)  * scaleY
    ];
  }

  // ── Drawing ───────────────────────────────────────────────────────────────
  function onDown(e) {
    saveState();
    isDrawing = true;
    hidePlaceholder();
    var pos = getPos(e);
    lastX = pos[0]; lastY = pos[1];
    // single-dot on click
    ctx.beginPath();
    ctx.arc(lastX, lastY, (currentTool === 'eraser' ? currentWidth * 3 : currentWidth) / 2, 0, Math.PI * 2);
    ctx.fillStyle = currentTool === 'eraser' ? '#ffffff' : currentColor;
    ctx.fill();
  }

  function onMove(e) {
    if (!isDrawing) return;
    var pos = getPos(e);
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(pos[0], pos[1]);
    ctx.strokeStyle = currentTool === 'eraser' ? '#ffffff' : currentColor;
    ctx.lineWidth   = currentTool === 'eraser' ? currentWidth * 3 : currentWidth;
    ctx.lineCap     = 'round';
    ctx.lineJoin    = 'round';
    ctx.stroke();
    lastX = pos[0]; lastY = pos[1];
  }

  function onUp() { isDrawing = false; }

  // ── Mouse events ──────────────────────────────────────────────────────────
  canvas.addEventListener('mousedown',  onDown);
  canvas.addEventListener('mousemove',  onMove);
  canvas.addEventListener('mouseup',    onUp);
  canvas.addEventListener('mouseleave', onUp);

  // ── Touch events ──────────────────────────────────────────────────────────
  canvas.addEventListener('touchstart', function(e) {
    e.preventDefault(); onDown(e.touches[0]);
  }, { passive: false });
  canvas.addEventListener('touchmove', function(e) {
    e.preventDefault(); onMove(e.touches[0]);
  }, { passive: false });
  canvas.addEventListener('touchend', function(e) {
    e.preventDefault(); onUp();
  }, { passive: false });

  // ── Tool buttons ──────────────────────────────────────────────────────────
  document.querySelectorAll('.tool-btn[data-tool]').forEach(function(btn) {
    btn.addEventListener('click', function() {
      currentTool = this.dataset.tool;
      document.querySelectorAll('.tool-btn[data-tool]').forEach(function(b) { b.classList.remove('active'); });
      this.classList.add('active');
      canvas.style.cursor = currentTool === 'eraser' ? 'cell' : 'crosshair';
    });
  });

  // ── Color swatches ────────────────────────────────────────────────────────
  document.querySelectorAll('.color-swatch').forEach(function(s) {
    s.addEventListener('click', function() {
      currentColor = this.dataset.color;
      document.getElementById('colorPicker').value = currentColor;
      document.querySelectorAll('.color-swatch').forEach(function(x) { x.classList.remove('active-swatch'); });
      this.classList.add('active-swatch');
      // Switch back to pen when colour is picked
      currentTool = 'pen';
      document.querySelectorAll('.tool-btn[data-tool]').forEach(function(b) { b.classList.remove('active'); });
      document.getElementById('tool-pen').classList.add('active');
      canvas.style.cursor = 'crosshair';
    });
  });

  document.getElementById('colorPicker').addEventListener('input', function() {
    currentColor = this.value;
    document.querySelectorAll('.color-swatch').forEach(function(s) { s.classList.remove('active-swatch'); });
    currentTool = 'pen';
    document.querySelectorAll('.tool-btn[data-tool]').forEach(function(b) { b.classList.remove('active'); });
    document.getElementById('tool-pen').classList.add('active');
    canvas.style.cursor = 'crosshair';
  });

  // ── Line width ────────────────────────────────────────────────────────────
  document.getElementById('lineWidth').addEventListener('change', function() {
    currentWidth = parseInt(this.value, 10);
  });

  // ── Undo button ───────────────────────────────────────────────────────────
  document.getElementById('undoBtn').addEventListener('click', undo);

  // ── Clear button ──────────────────────────────────────────────────────────
  document.getElementById('clearCanvas').addEventListener('click', function() {
    if (!confirm('Hapus semua gambar di kanvas?')) return;
    undoStack = [];
    fillWhite();
    if (placeholder) placeholder.style.display = '';
  });

  // ── Image upload ──────────────────────────────────────────────────────────
  document.getElementById('imageUpload').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (!file) return;
    saveState();
    var reader = new FileReader();
    reader.onload = function(ev) {
      var img = new Image();
      img.onload = function() {
        var maxW  = canvas.parentElement.clientWidth || 900;
        var scale = Math.min(1, maxW / img.width);
        canvas.width  = Math.round(img.width  * scale);
        canvas.height = Math.round(img.height * scale);
        fillWhite();
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        hidePlaceholder();
      };
      img.src = ev.target.result;
    };
    reader.readAsDataURL(file);
    this.value = '';
  });

  // ── Finish Drawing — capture canvas to hidden field ───────────────────────
  document.getElementById('finishDrawing').addEventListener('click', function() {
    document.getElementById('paramsSignature').value = canvas.toDataURL('image/png', 0.92);
    var btn = this;
    btn.classList.remove('btn-warning');
    btn.classList.add('btn-success');
    btn.innerHTML = '<i class="fa fa-check"></i> Gambar Siap Disimpan';
    setTimeout(function() {
      btn.classList.remove('btn-success');
      btn.classList.add('btn-warning');
      btn.innerHTML = '<i class="fa fa-check-circle"></i> Selesai Menggambar';
    }, 2500);
  });

  // ── Save handler ──────────────────────────────────────────────────────────
  document.getElementById('btn_save_drawing_notes').addEventListener('click', function(e) {
    e.preventDefault();
    if (!document.getElementById('paramsSignature').value) {
      alert('Klik "Selesai Menggambar" terlebih dahulu sebelum menyimpan.');
      return;
    }
    // Serialize the form that wraps this view (parent form_pelayanan)
    $.ajax({
      url: $('#form_pelayanan').attr('action'),
      data: $('#form_pelayanan').serialize(),
      dataType: 'json',
      type: 'POST',
      complete: function(xhr) {
        var r = JSON.parse(xhr.responseText);
        if (r.status === 200) {
          $('#btn_note').click();
          $.achtung({ message: r.message, timeout: 5 });
        } else {
          $.achtung({ message: r.message, timeout: 5, className: 'achtungFail' });
        }
        achtungHideLoader();
      }
    });
  });

})();

// ── Global helpers (called from onclick in PHP-generated HTML) ────────────
function view_drawing(id) {
  show_modal('pelayanan/Pl_pelayanan_ri/show_drawing/' + id, 'Gambar / Catatan');
}

function note_set_deleted(id, status) {
  preventDefault();
  $.getJSON(
    'pelayanan/Pl_pelayanan_ri/update_status_dt_monitoring',
    { ID: id, table: 'th_drawing_notes', deleted: status },
    function() {
      $('#tbl_dt_' + id).fadeOut(300);
      $('#btn_note').click();
    }
  );
}
</script>
