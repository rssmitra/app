<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<style>
  /* ===== Card / Wrap ===== */
  .pu-wrap {
    border: 1px solid #b8d0e8;
    border-radius: 7px;
    overflow: hidden;
    margin-bottom: 16px;
    box-shadow: 0 2px 8px rgba(26,79,138,.09);
    background: #fff;
  }
  .pu-hdr {
    background: linear-gradient(135deg, #1a4f8a 0%, #2c6fad 100%);
    color: #fff;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 9px;
  }
  .pu-hdr .pu-badge {
    margin-left: auto;
    background: rgba(255,255,255,.18);
    border-radius: 20px;
    padding: 2px 11px;
    font-size: 11px;
    font-weight: 600;
  }
  .pu-body {
    padding: 14px 18px;
  }

  /* ===== Info row ===== */
  .pu-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
    margin-bottom: 4px;
  }
  .pu-info-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }
  .pu-info-label {
    font-size: 11px;
    color: #6b8cae;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
  }
  .pu-info-val {
    font-size: 13px;
    font-weight: 700;
    color: #1a4f8a;
  }
  .pu-jenis-badge {
    display: inline-block;
    padding: 2px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
  }
  .pu-jenis-medis    { background: #dbeafe; color: #1e40af; }
  .pu-jenis-nonmedis { background: #e0f2fe; color: #0369a1; }

  /* ===== Table ===== */
  .pu-tbl-wrap {
    border: 1px solid #c0d4e8;
    border-radius: 5px;
    overflow: hidden;
    margin-bottom: 16px;
  }
  .pu-tbl-hdr {
    background: #1a4f8a;
    color: #fff;
    padding: 7px 13px;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 7px;
  }
  .pu-tbl-note {
    background: #f0f5fb;
    border-bottom: 1px solid #c0d4e8;
    padding: 5px 13px;
    font-size: 11px;
    color: #6b8cae;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  #cart-data {
    width: 100% !important;
    border-collapse: collapse;
    font-size: 12px;
  }
  #cart-data thead tr {
    background: #2c6fad;
    color: #fff;
  }
  #cart-data thead th {
    padding: 7px 8px;
    text-align: center;
    font-weight: 600;
    border: 1px solid #1e5590;
    vertical-align: middle;
    font-size: 11px;
    line-height: 1.3;
  }
  #cart-data tbody tr:nth-child(even) { background: #f5f9fd; }
  #cart-data tbody tr:hover           { background: #e8f0f9; }
  #cart-data tbody td {
    padding: 6px 8px;
    border: 1px solid #d0dce8;
    vertical-align: middle;
  }

  /* ===== Form row ===== */
  .pu-form-row {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 11px;
  }
  .pu-form-label {
    width: 120px;
    min-width: 120px;
    font-size: 12px;
    font-weight: 600;
    color: #2c4a6e;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .pu-form-label i { color: #2c6fad; width: 14px; text-align: center; }
  .pu-form-field { flex: 1; min-width: 0; }
  .pu-form-field .form-control {
    font-size: 12px;
    height: 30px;
    padding: 4px 9px;
    border-color: #c0d4e8;
    border-radius: 4px;
  }
  .pu-form-field .form-control:focus {
    border-color: #2c6fad;
    box-shadow: 0 0 0 2px rgba(44,111,173,.15);
  }

  /* ===== Catatan ===== */
  .pu-note-wrap {
    border: 1px solid #c0d4e8;
    border-radius: 5px;
    overflow: hidden;
    margin-bottom: 14px;
  }
  .pu-note-hdr {
    background: #f0f5fb;
    border-bottom: 1px solid #c0d4e8;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    color: #2c4a6e;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .pu-note-body textarea.form-control {
    border: none;
    border-radius: 0;
    font-size: 12px;
    resize: vertical;
    min-height: 52px;
  }
  .pu-note-body textarea.form-control:focus {
    box-shadow: none;
    outline: 2px solid #2c6fad;
    outline-offset: -2px;
  }

  /* ===== Buttons ===== */
  .pu-btn-submit {
    background: linear-gradient(135deg, #1a4f8a 0%, #2c6fad 100%);
    color: #fff !important;
    border: none;
    border-radius: 4px;
    padding: 6px 22px;
    font-size: 12px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .15s;
  }
  .pu-btn-submit:hover { opacity: .88; color: #fff !important; }
  .pu-btn-back {
    background: #f0f5fb;
    color: #2c4a6e !important;
    border: 1px solid #b8d0e8;
    border-radius: 4px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    text-decoration: none;
    transition: background .15s;
  }
  .pu-btn-back:hover { background: #dbeafe; color: #1a4f8a !important; }

  /* ===== Alert selesai ===== */
  #alert_finish {
    border: 1px solid #a7f3d0;
    border-left: 4px solid #10b981;
    border-radius: 5px;
    background: #ecfdf5;
    color: #065f46;
    padding: 12px 16px;
    font-size: 13px;
    display: none;
  }
  #alert_finish strong { font-size: 15px; }

  /* ===== Qty Kirim Input ===== */
  .qty-kirim-input {
    width: 64px;
    text-align: center;
    padding: 2px 4px;
    height: 26px;
    font-size: 12px;
    font-weight: 700;
    color: #1a4f8a;
    border: 1px solid #c0d4e8;
    border-radius: 4px;
    background: #f0f5fb;
    transition: border-color .15s, background .15s;
  }
  .qty-kirim-input:focus {
    outline: none;
    border-color: #2c6fad;
    background: #fff;
    box-shadow: 0 0 0 2px rgba(44,111,173,.18);
  }
  .qty-kirim-input.filled {
    background: #dbeafe;
    border-color: #2c6fad;
    color: #1a4f8a;
  }
  .qty-kirim-input.stok-kurang {
    border-color: #f39c12;
    background: #fffbf0;
    color: #b45309;
  }
  .qty-kirim-input.stok-kurang:focus {
    border-color: #e67e22;
    box-shadow: 0 0 0 2px rgba(230,126,34,.18);
  }
  .qty-kirim-input.stok-kurang.filled {
    background: #fef3c7;
    border-color: #f59e0b;
    color: #92400e;
  }
  .stok-warn-badge {
    margin-top: 4px;
    font-size: 10px;
    color: #b45309;
    line-height: 1.4;
  }
  .stok-warn-badge .fa {
    color: #e67e22;
  }
  .stok-warn-badge span {
    color: #6b4226;
  }

  /* ===== blink ===== */
  .blink_me { animation: blinker 1s linear infinite; }
  @keyframes blinker { 50% { opacity: 0; } }
</style>

<div class="page-header" style="margin-bottom: 14px">
  <h1 style="font-size: 18px; color: #1a4f8a; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px;">
    <i class="fa fa-truck" style="font-size: 16px"></i>
    <?php echo $title?>
    <small style="font-size: 12px; color: #6b8cae; font-weight: 400">
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_cart"
          action="<?php echo base_url().'purchasing/pendistribusian/Pengiriman_unit/process_pengiriman_brg_unit'?>"
          enctype="multipart/form-data" autocomplete="off">

      <input type="hidden" name="id" id="id" value="<?php echo isset($value->id_tc_permintaan_inst)?$value->id_tc_permintaan_inst:''?>">
      <input type="hidden" name="kode_bagian_minta" id="kode_bagian_minta" value="<?php echo isset($value->kode_bagian_minta)?$value->kode_bagian_minta:''?>">
      <input type="hidden" name="flag_cart" id="flag_cart" value="<?php echo $type?>">

      <!-- ===== Card: Info Permintaan ===== -->
      <div class="pu-wrap">
        <div class="pu-hdr">
          <i class="fa fa-info-circle"></i> Informasi Permintaan
        </div>
        <div class="pu-body">
          <div style="margin-bottom: 12px">
            <a href="#" onclick="getMenu('purchasing/pendistribusian/Distribusi_permintaan?flag=<?php echo $type?>')"
               class="pu-btn-back">
              <i class="fa fa-arrow-left"></i> Kembali ke Daftar
            </a>
          </div>
          <div class="pu-info-grid">
            <div class="pu-info-item">
              <div class="pu-info-label"><i class="fa fa-tag"></i> Jenis Barang</div>
              <div class="pu-info-val">
                <span class="pu-jenis-badge <?php echo ($type=='medis')?'pu-jenis-medis':'pu-jenis-nonmedis'?>">
                  <i class="fa <?php echo ($type=='medis')?'fa-medkit':'fa-cube'?>"></i>
                  <?php echo ucwords($type)?>
                </span>
              </div>
            </div>
            <div class="pu-info-item">
              <div class="pu-info-label"><i class="fa fa-building-o"></i> Permintaan dari Unit</div>
              <div class="pu-info-val"><?php echo isset($value->bagian_minta)?ucwords($value->bagian_minta):'-'?></div>
            </div>
            <div class="pu-info-item">
              <div class="pu-info-label"><i class="fa fa-calendar"></i> Tanggal Permintaan</div>
              <div class="pu-info-val"><?php echo isset($value->tgl_permintaan)?$this->tanggal->formatDateDmy($value->tgl_permintaan):'-'?></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ===== Card: Tabel Barang ===== -->
      <div class="pu-tbl-wrap">
        <div class="pu-tbl-hdr">
          <i class="fa fa-list-ul"></i> Daftar Barang Permintaan
        </div>
        <div class="pu-tbl-note">
          <i class="fa fa-info-circle"></i>
          Kolom <b>Pasien / Mutasi</b> menampilkan jumlah pasien &amp; pemakaian barang dihitung <b>sejak tanggal distribusi terakhir s/d hari ini</b> per masing-masing barang — sebagai acuan monitoring kesesuaian distribusi dengan pemakaian aktual di unit.
        </div>
        <div id="stok-kosong-notice" style="display:none;margin:0 16px 4px;padding:8px 12px;background:#fff3cd;border:1px solid #ffc107;border-radius:5px;font-size:12px;color:#856404;">
          <i class="fa fa-exclamation-triangle" style="color:#e67e22;margin-right:5px"></i>
          <span id="stok-kosong-msg"></span>
        </div>
        <table id="cart-data" base-url="purchasing/pendistribusian/Pengiriman_unit"
               data-id="flag=<?php echo $type?>">
          <thead>
            <tr>
              <th width="32px">
                <label class="pos-rel" style="margin:0">
                  <input type="checkbox" class="ace" onClick="checkAll(this);" value="0"/>
                  <span class="lbl"></span>
                </label>
              </th>
              <th width="28px">No</th>
              <th width="80px">Kode Brg</th>
              <th>Nama Barang</th>
              <th width="55px">Satuan</th>
              <th width="70px">Stok<br>Gudang</th>
              <th width="65px">Stok<br>Akhir Unit</th>
              <th width="60px">Jml<br>Minta</th>
              <th width="60px">Jml<br>Disetujui</th>
              <th width="60px">Jml<br>Kirim</th>
              <th width="95px">Distribusi<br>Terakhir</th>
              <th width="95px">Pasien / Mutasi<br><span style="font-size:10px;font-weight:400;opacity:.85">(sejak dist. terakhir)</span></th>
              <!-- <th width="80px">Total Nilai<br>Persediaan</th> -->
              <th width="80px">Ket. Verif</th>
              <th width="40px">#</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <!-- ===== Card: Form Distribusi ===== -->
      <div class="pu-wrap" id="btn_action">
        <div class="pu-hdr">
          <i class="fa fa-send"></i> Proses Distribusi
        </div>
        <div class="pu-body">
          <div class="pu-form-row">
            <div class="pu-form-label">
              <i class="fa fa-calendar"></i> Tgl Distribusi
            </div>
            <div class="pu-form-field" style="max-width: 200px">
              <div class="input-group">
                <input class="form-control date-picker" name="tgl_distribusi" id="tgl_distribusi"
                       type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
            <div class="pu-form-label" style="width:auto; min-width:auto; margin-left: 16px">
              <i class="fa fa-user"></i> Petugas Pengirim
            </div>
            <div class="pu-form-field" style="max-width: 260px">
              <input class="form-control" type="text" name="yang_menyerahkan" id="yang_menyerahkan"
                     value="<?php echo $this->session->userdata('user')->fullname?>">
            </div>
          </div>

          <!-- Catatan -->
          <div class="pu-note-wrap">
            <div class="pu-note-hdr">
              <i class="fa fa-sticky-note-o"></i> Catatan Distribusi
            </div>
            <div class="pu-note-body">
              <textarea class="form-control" id="catatan" name="catatan"
                        placeholder="Catatan tambahan (opsional)..."></textarea>
            </div>
          </div>

          <div style="display: flex; gap: 10px; align-items: center; padding-top: 4px">
            <a href="#" onclick="getMenu('purchasing/pendistribusian/Distribusi_permintaan?flag=<?php echo $type?>')"
               class="pu-btn-back">
              <i class="fa fa-times"></i> Batal
            </a>
            <a href="#" onclick="submit_cart()" class="pu-btn-submit">
              <i class="fa fa-paper-plane"></i> Submit Distribusi
            </a>
          </div>
        </div>
      </div>

      <!-- Alert: sudah selesai -->
      <div id="alert_finish">
        <strong><i class="fa fa-check-circle"></i> Semua Barang Telah Didistribusikan!</strong><br>
        <span style="font-size: 12px">Barang yang sudah terdistribusi tidak dapat diproses ulang kembali.</span>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

  $('#form_cart').ajaxForm({
    beforeSend: function() { achtungShowLoader(); },
    uploadProgress: function(event, position, total, percentComplete) {},
    complete: function(xhr) {
      var data = xhr.responseText;
      var jsonResponse = JSON.parse(data);
      if(jsonResponse.status === 200){
        $.achtung({message: jsonResponse.message, timeout: 5});
        getMenu('purchasing/pendistribusian/Distribusi_permintaan?flag=' + jsonResponse.flag + '');
      } else {
        $.achtung({message: jsonResponse.message, timeout: 5});
      }
      achtungHideLoader();
    }
  });

  var id = $('#id').val();
  cartData = $('#cart-data').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering":   false,
    "paging":     false,
    "bInfo":      false,
    "searching":  false,
    "ajax": {
      "url":  "purchasing/pendistribusian/Pengiriman_unit/get_detail_cart?flag=" + $('#flag_cart').val() + "&id=" + id,
      "type": "POST"
    },
    "fnDrawCallback": function(response) {
      var obj = response.json;
      if(obj.total_belum_didistribusi == 0){
        $('#btn_action').hide();
        $('#alert_finish').show();
      } else {
        $('#btn_action').show();
      }
      // Tampilkan notice jika ada baris stok gudang kosong atau tidak mencukupi
      var banCount  = $('#cart-data tbody .fa-ban').length;
      var warnCount = $('#cart-data tbody .stok-kurang').length;
      var noticeMsg = [];
      if(banCount > 0){
        noticeMsg.push(
          '<i class="fa fa-ban" style="color:#c0392b"></i> '
          + '<b>' + banCount + ' barang</b> tidak dapat didistribusikan karena <b>stok gudang kosong</b>.'
        );
      }
      if(warnCount > 0){
        noticeMsg.push(
          '<i class="fa fa-exclamation-triangle" style="color:#e67e22"></i> '
          + '<b>' + warnCount + ' barang</b> memiliki stok gudang yang <b>tidak mencukupi</b> seluruh permintaan — '
          + 'jumlah kirim disesuaikan otomatis dengan ketersediaan stok.'
        );
      }
      if(noticeMsg.length > 0){
        $('#stok-kosong-msg').html(noticeMsg.join('<br>'));
        $('#stok-kosong-notice').show();
      } else {
        $('#stok-kosong-notice').hide();
      }
    },
  });

});

function submit_cart(){
  // Pastikan minimal satu barang dipilih
  var $checked = $('input[name="selected_id[]"]:checked');
  if($checked.length === 0){
    var banCount = $('#cart-data tbody .fa-ban').length;
    var msg = 'Tidak ada barang yang dipilih untuk didistribusikan.';
    if(banCount > 0){
      msg += '<br><br><span style="color:#c0392b;font-size:12px">'
           + '<i class="fa fa-ban"></i> ' + banCount
           + ' barang diblokir karena stok gudang kosong.</span>';
    }
    Swal.fire({ icon: 'warning', title: 'Perhatian', html: msg, confirmButtonColor: '#1a4f8a' });
    return;
  }
  $('#form_cart').submit();
}

function edit_brg(id_det, title){
  show_modal_medium('purchasing/pendistribusian/Pengiriman_unit/form_edit_brg/' + id_det + '?flag=' + $('#flag_cart').val(), title);
}

// ── Qty Kirim: auto-fill saat checkbox diubah ─────────────────────────────

// Helper: isi input qty — gunakan data-max (min stok vs acc) sebagai nilai default
function fillQtyKirim($cb, checked){
  var $input = $cb.closest('tr').find('.qty-kirim-input');
  if(!$input.length) return;
  if(checked){
    // data-max = min(stok_aktual, jml_acc_atasan) — sudah dihitung di controller
    var fillVal = parseInt($input.data('max')) || parseInt($input.data('acc')) || 0;
    $input.val(fillVal).addClass('filled');
  } else {
    $input.val(0).removeClass('filled');
  }
}

// Individual checkbox
$(document).on('change', 'input[name="selected_id[]"]', function(){
  fillQtyKirim($(this), $(this).is(':checked'));
});

// Header checkAll — pakai click + setTimeout karena checkAll pakai native DOM
$(document).on('click', 'input[type="checkbox"][value="0"]', function(){
  var isChecked = $(this).is(':checked');
  setTimeout(function(){
    $('input[name="selected_id[]"]').each(function(){
      fillQtyKirim($(this), isChecked);
    });
  }, 30);
});

// Validasi: tidak boleh melebihi data-max (stok tersedia atau jml_acc_atasan)
$(document).on('input', '.qty-kirim-input', function(){
  var max = parseInt($(this).data('max')) || parseInt($(this).data('acc')) || 0;
  var val = parseInt($(this).val()) || 0;
  if(val > max){ $(this).val(max); }
  if(val < 1 && $(this).val() !== ''){ $(this).val(1); }
  $(this).toggleClass('filled', val > 0);
});
</script>
