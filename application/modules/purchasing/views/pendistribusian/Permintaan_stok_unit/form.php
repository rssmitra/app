<script type="text/javascript" src="<?php echo base_url()?>assets/jQuery-Scanner/jquery.scannerdetection.js"></script>
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<style>
  /* ===== Card / Wrap ===== */
  .psu-wrap {
    border: 1px solid #b8d0e8;
    border-radius: 7px;
    overflow: hidden;
    margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(26,79,138,.09);
    background: #fff;
  }
  .psu-hdr {
    background: linear-gradient(135deg, #1a4f8a 0%, #2c6fad 100%);
    color: #fff;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 9px;
    letter-spacing: .3px;
  }
  .psu-hdr .psu-badge {
    margin-left: auto;
    background: rgba(255,255,255,.18);
    border-radius: 20px;
    padding: 2px 11px;
    font-size: 11px;
    font-weight: 600;
  }
  .psu-body {
    padding: 16px 18px 10px;
  }

  /* ===== Form ===== */
  .psu-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 11px;
    flex-wrap: wrap;
  }
  .psu-label {
    width: 130px;
    min-width: 130px;
    font-size: 12px;
    font-weight: 600;
    color: #2c4a6e;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .psu-label i {
    color: #2c6fad;
    width: 14px;
    text-align: center;
  }
  .psu-field {
    flex: 1;
    min-width: 0;
  }
  .psu-field .form-control {
    font-size: 12px;
    height: 30px;
    padding: 4px 9px;
    border-color: #c0d4e8;
    border-radius: 4px;
  }
  .psu-field .form-control:focus {
    border-color: #2c6fad;
    box-shadow: 0 0 0 2px rgba(44,111,173,.15);
  }

  /* ===== Radio / Checkbox ===== */
  .psu-radio-group {
    display: flex;
    gap: 18px;
    flex-wrap: wrap;
  }
  .psu-radio-group label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 500;
    color: #334e68;
    cursor: pointer;
    margin: 0;
  }
  .psu-chk-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 500;
    color: #334e68;
    cursor: pointer;
    margin: 0;
  }

  /* ===== Table ===== */
  .psu-tbl-wrap {
    border: 1px solid #c0d4e8;
    border-radius: 5px;
    overflow: hidden;
    margin-top: 14px;
    margin-bottom: 14px;
  }
  .psu-tbl-hdr {
    background: #1a4f8a;
    color: #fff;
    padding: 7px 13px;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 7px;
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
    padding: 7px 9px;
    text-align: center;
    font-weight: 600;
    border: 1px solid #1e5590;
    vertical-align: middle;
  }
  #cart-data tbody tr:nth-child(even) { background: #f5f9fd; }
  #cart-data tbody tr:hover { background: #e8f0f9; }
  #cart-data tbody td {
    padding: 6px 9px;
    border: 1px solid #d0dce8;
    vertical-align: middle;
    font-size: 12px;
  }

  /* ===== Catatan ===== */
  .psu-note-wrap {
    border: 1px solid #c0d4e8;
    border-radius: 5px;
    overflow: hidden;
    margin-bottom: 14px;
  }
  .psu-note-hdr {
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
  .psu-note-body textarea.form-control {
    border: none;
    border-radius: 0;
    font-size: 12px;
    resize: vertical;
    min-height: 58px;
  }
  .psu-note-body textarea.form-control:focus {
    box-shadow: none;
    border-color: transparent;
    outline: 2px solid #2c6fad;
    outline-offset: -2px;
  }

  /* ===== Detail Barang Panel ===== */
  #div_detail_brg .det-wrap {
    border: 1px solid #c0d4e8;
    border-radius: 5px;
    overflow: hidden;
  }
  #div_detail_brg .det-hdr {
    background: linear-gradient(135deg, #1a4f8a 0%, #2c6fad 100%);
    color: #fff;
    padding: 8px 13px;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 7px;
  }

  /* ===== Buttons ===== */
  .psu-btn-add {
    background: linear-gradient(135deg, #0d7f3f 0%, #15a854 100%);
    color: #fff !important;
    border: none;
    border-radius: 4px;
    padding: 5px 14px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .15s;
  }
  .psu-btn-add:hover { opacity: .88; color: #fff !important; }
  .psu-btn-submit {
    background: linear-gradient(135deg, #1a4f8a 0%, #2c6fad 100%);
    color: #fff !important;
    border: none;
    border-radius: 4px;
    padding: 6px 20px;
    font-size: 12px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .15s;
  }
  .psu-btn-submit:hover { opacity: .88; color: #fff !important; }
  .psu-btn-reset {
    background: #fff;
    color: #c0392b !important;
    border: 1.5px solid #c0392b;
    border-radius: 4px;
    padding: 5px 16px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    text-decoration: none;
    transition: background .15s, color .15s;
  }
  .psu-btn-reset:hover { background: #c0392b; color: #fff !important; }
  .psu-btn-back {
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
  .psu-btn-back:hover { background: #dbeafe; color: #1a4f8a !important; }

  /* ===== Footer actions ===== */
  .psu-footer {
    padding: 10px 18px 14px;
    border-top: 1px solid #dde8f4;
    display: flex;
    align-items: center;
    gap: 10px;
    justify-content: flex-end;
  }

  /* ===== Update badge ===== */
  .update-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #fff3cd;
    border: 1px solid #ffc107;
    color: #856404;
    border-radius: 4px;
    padding: 3px 10px;
    font-size: 11px;
    font-weight: 700;
    margin-left: 8px;
  }

  /* ===== Qty row ===== */
  .psu-qty-group {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }
  .psu-qty-input {
    width: 100px;
  }

  /* ===== Divider ===== */
  .psu-divider {
    border: none;
    border-top: 1px solid #dde8f4;
    margin: 14px 0;
  }

  /* ===== Typeahead fix ===== */
  .twitter-typeahead { width: 100%; }
  .tt-hint, .tt-input { width: 100%; }
  .tt-menu {
    background: #fff;
    border: 1px solid #c0d4e8;
    border-radius: 4px;
    box-shadow: 0 3px 10px rgba(0,0,0,.12);
    font-size: 12px;
    max-height: 220px;
    overflow-y: auto;
    width: 100%;
    z-index: 9999;
  }
  .tt-suggestion {
    padding: 6px 11px;
    cursor: pointer;
  }
  .tt-suggestion:hover, .tt-suggestion.tt-cursor { background: #e8f0f9; }

  /* ===== Error ===== */
  .error { color: #c0392b; font-size: 10px; }

  /* ===== BHP info ===== */
  .psu-bhp-info { display: inline; }

  /* ===== Stock alert ===== */
  .psu-alert-info {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    background: #fffbeb;
    border: 1px solid #f59e0b;
    border-left: 4px solid #f59e0b;
    border-radius: 5px;
    padding: 9px 12px;
    font-size: 12px;
    color: #78350f;
  }
  .psu-alert-info .ai-icon {
    color: #f59e0b;
    font-size: 15px;
    margin-top: 1px;
    flex-shrink: 0;
  }
  .psu-alert-info .ai-title {
    font-weight: 700;
    margin-bottom: 2px;
    color: #92400e;
  }
  .psu-alert-info .ai-body {
    line-height: 1.5;
  }
  .psu-alert-info .ai-stok {
    font-weight: 700;
    color: #15803d;
  }
  .psu-alert-info .ai-min {
    font-weight: 700;
    color: #b45309;
  }

  /* ===== blink ===== */
  .blink_me {
    animation: blinker 1s linear infinite;
  }
  @keyframes blinker { 50% { opacity: 0; } }
</style>

<div class="page-header" style="margin-bottom: 14px">
  <h1 style="font-size: 18px; color: #1a4f8a; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px;">
    <i class="fa fa-shopping-basket" style="font-size: 16px"></i>
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
          action="<?php echo base_url().'purchasing/pendistribusian/Permintaan_stok_unit/process'?>"
          enctype="multipart/form-data" autocomplete="off">

      <!-- Hidden inputs -->
      <input type="hidden" name="id" id="id" value="<?php echo isset($value->id_tc_permintaan_inst)?$value->id_tc_permintaan_inst:''?>">
      <input type="hidden" name="id_tc_permintaan_inst_det" id="id_tc_permintaan_inst_det" value="<?php echo isset($value->id_tc_permintaan_inst_det)?$value->id_tc_permintaan_inst_det:''?>">
      <input type="hidden" name="type_tbl" id="type_tbl" value="<?php echo isset($value->id_tc_permintaan_inst) ? 'tc_permintaan_inst' : 'cart_log'?>">
      <input type="hidden" name="stock_card" id="stock_card" value="">
      <input type="hidden" name="flag" id="flag_cart" value="<?php echo isset($flag)?$flag:''?>">
      <input type="hidden" name="flag_form" id="flag_form" value="permintaan_stok_unit">
      <input type="hidden" name="kode_brg_hidden" id="kode_brg_hidden">
      <input type="hidden" name="nama_brg_hidden" id="nama_brg_hidden">
      <input type="hidden" name="satuan_brg_hidden" id="satuan_brg_hidden">
      <input type="hidden" name="harga_brg_hidden" id="harga_brg_hidden">

      <div class="col-md-9" style="padding-left: 0">

        <!-- ===== Card: Form Input ===== -->
        <div class="psu-wrap">
          <div class="psu-hdr">
            <i class="fa fa-pencil-square-o"></i>
            Form Permintaan Stok Unit
            <?php if($flag == 'update'): ?>
              <span class="psu-badge"><i class="fa fa-edit"></i> Mode Update</span>
            <?php endif; ?>
          </div>
          <div class="psu-body">

            <!-- Back button -->
            <div style="margin-bottom: 14px">
              <a href="#" onclick="getMenu('purchasing/pendistribusian/Permintaan_stok_unit')" class="psu-btn-back">
                <i class="fa fa-arrow-left"></i> Kembali ke Daftar
              </a>
              <?php if($flag == 'update'): ?>
                <span class="update-badge"><i class="fa fa-pencil"></i> Update Form</span>
              <?php endif; ?>
            </div>

            <!-- Jenis Barang -->
            <div class="psu-row">
              <div class="psu-label">
                <i class="fa fa-tag"></i> Jenis Barang
              </div>
              <div class="psu-field">
                <div class="psu-radio-group">
                  <label>
                    <input name="flag_gudang" type="radio" class="ace" value="medis"
                           <?php echo ($flag_type == 'medis')?'checked':''?>>
                    <span class="lbl"> Medis</span>
                  </label>
                  <label>
                    <input name="flag_gudang" type="radio" class="ace" value="non_medis"
                           <?php echo ($flag_type == 'non_medis')?'checked':''?>>
                    <span class="lbl"> Non Medis</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Unit Bagian -->
            <div class="psu-row">
              <div class="psu-label">
                <i class="fa fa-building-o"></i> Unit / Bagian
              </div>
              <div class="psu-field">
                <?php
                  if(isset($cart_data[0]->kode_bagian)){
                    $kode_bagian_minta = isset($cart_data[0]->kode_bagian)?$cart_data[0]->kode_bagian:'';
                  }else{
                    $kode_bagian_minta = isset($value->kode_bagian_minta)?$value->kode_bagian_minta:'';
                  }
                  echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), $kode_bagian_minta , 'dari_unit', 'dari_unit', 'chosen-select form-control', '', '')
                ?>
              </div>
            </div>

            <hr class="psu-divider">

            <!-- Cari Barang -->
            <div class="psu-row">
              <div class="psu-label">
                <i class="fa fa-search"></i> Cari Barang
              </div>
              <div class="psu-field">
                <input type="text" class="form-control" name="keyword" id="inputKeyBarang"
                       placeholder="Ketik nama atau kode barang...">
              </div>
            </div>

            <!-- Alert stok di atas minimal -->
            <div id="psu-stok-alert" style="display:none; margin-left:140px; margin-bottom: 8px"></div>

            <!-- Qty + BHP -->
            <div class="psu-row">
              <div class="psu-label">
                <i class="fa fa-sort-numeric-asc"></i> Qty
              </div>
              <div class="psu-field">
                <div class="psu-qty-group">
                  <div class="psu-qty-input">
                    <input class="form-control" type="number" name="qtyBrg" id="qtyBarang"
                           placeholder="0" min="1">
                    <input class="form-control" type="hidden" name="qtyBrgStok" id="qtyStok">
                  </div>
                  <label class="psu-chk-label">
                    <input name="is_bhp" id="is_bhp" value="1" type="checkbox" class="ace">
                    <span class="lbl"> BHP
                      <span class="psu-bhp-info" data-toggle="tooltip" data-placement="top"
                            title="Barang Habis Pakai (BHP) adalah barang yang habis terpakai dalam satu kali penggunaan (mis. sarung tangan, jarum suntik, kasa, dll). Centang jika barang ini termasuk kategori BHP.">
                        <i class="fa fa-question-circle" style="color:#2c6fad; cursor:help; margin-left:3px; width: 200px"></i>
                      </span>
                    </span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Keterangan -->
            <div class="psu-row">
              <div class="psu-label">
                <i class="fa fa-info-circle"></i> Keterangan
              </div>
              <div class="psu-field">
                <input class="form-control" type="text" name="keterangan_permintaan"
                       id="keterangan_permintaan" placeholder="Keterangan tambahan (opsional)">
              </div>
            </div>

            <!-- Tambah ke List -->
            <div class="psu-row">
              <div class="psu-label">&nbsp;</div>
              <div class="psu-field">
                <a href="#" onclick="insert_cart_log()" class="psu-btn-add">
                  <i class="fa fa-plus-circle"></i> Tambah ke Daftar
                </a>
              </div>
            </div>

          </div><!-- /psu-body -->
        </div><!-- /psu-wrap -->

        <!-- ===== Card: Daftar Barang ===== -->
        <div class="psu-tbl-wrap">
          <div class="psu-tbl-hdr">
            <i class="fa fa-list-ul"></i> Daftar Barang Diminta
          </div>
          <table id="cart-data" base-url="purchasing/pendistribusian/Permintaan_stok_unit"
                 data-id="flag=<?php echo $flag?>">
            <thead>
              <tr>
                <th width="32px">No</th>
                <th width="50px">Kode Brg</th>
                <th>Nama Barang</th>
                <th width="60px">BHP?</th>
                <th width="70px">Stok</th>
                <th width="60px">Qty</th>
                <th width="65px">Satuan</th>
                <th width="80px">Total</th>
                <th>Keterangan</th>
                <th width="80px">Aksi</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

        <!-- ===== Card: Catatan ===== -->
        <div class="psu-note-wrap">
          <div class="psu-note-hdr">
            <i class="fa fa-sticky-note-o"></i> Catatan Permintaan
          </div>
          <div class="psu-note-body">
            <textarea class="form-control" id="catatan" name="catatan"
                      placeholder="Tambahkan catatan jika diperlukan..."><?php echo isset($value->catatan)?$value->catatan:''?></textarea>
          </div>
        </div>

        <!-- ===== Footer Buttons ===== -->
        <div class="psu-footer" style="padding: 0; margin-top: 4px; justify-content: flex-start; gap: 10px">
          <a href="#" onclick="getMenu('purchasing/pendistribusian/Permintaan_stok_unit')"
             class="psu-btn-reset">
            <i class="fa fa-times-circle"></i> Reset / Batal
          </a>
          <a href="#" onclick="submit_cart()" class="psu-btn-submit">
            <i class="fa fa-paper-plane"></i> Submit Permintaan
          </a>
        </div>

      </div><!-- /col-md-9 -->

      <!-- ===== Right Panel: Detail Barang ===== -->
      <div class="col-md-3">
        <div id="div_detail_brg" style="margin-top: 0"></div>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


<script>
jQuery(function($) {

  $('#flag_cart').val($("input[name='flag_gudang']:checked").val());

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

  $('#form_cart').ajaxForm({
    beforeSend: function() {
      achtungShowLoader();
    },
    uploadProgress: function(event, position, total, percentComplete) {},
    complete: function(xhr) {
      var data = xhr.responseText;
      var jsonResponse = JSON.parse(data);
      if(jsonResponse.status === 200){
        $.achtung({message: jsonResponse.message, timeout: 5});
        getMenu('purchasing/pendistribusian/Permintaan_stok_unit?flag=' + jsonResponse.flag + '');
        PopupCenter('purchasing/pendistribusian/Permintaan_stok_unit/print_preview/' + jsonResponse.id + '?flag=' + jsonResponse.flag + '', 'Cetak Permintaan Barang ke Unit', 900, 600);
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
    "ordering": false,
    "paging": false,
    "bInfo": false,
    "searching": false,
    "ajax": {
      "url": "purchasing/pendistribusian/Permintaan_stok_unit/get_detail_cart?flag=" + $('#flag_cart').val() + "&id=" + id,
      "type": "POST"
    },
  });

  if(!ace.vars['touch']) {
    $('.chosen-select').chosen({allow_single_deselect: true});
    $(window).off('resize.chosen').on('resize.chosen', function() {
      $('.chosen-select').each(function() {
        var $this = $(this);
        $this.next().css({'width': $this.parent().width()});
      });
    }).trigger('resize.chosen');
  }

});

$(document).ready(function(){

  // Init tooltip untuk info BHP
  $('[data-toggle="tooltip"]').tooltip();

  $( "#inputKeyWord" ).keypress(function(event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == 13){
      event.preventDefault();
      if($(this).valid()){
        search_selected_brg(flag, search_by, keyword);
      }
      return false;
    }
  });

  $('#inputKeyBarang').typeahead({
    source: function (query, result) {
      $.ajax({
        url: "templates/References/getItemBarangByUnit",
        data: { keyword: query, flag: $("input[name='flag_gudang']:checked").val(), unit: '060201' },
        dataType: "json",
        type: "POST",
        success: function (response) {
          result($.map(response, function (item) { return item; }));
        }
      });
    },
    afterSelect: function (item) {
      var val_item   = item.split(':')[0];
      var label_item = item.split(':')[1];
      $('#div_detail_brg').html('');
      getDetailBarang(val_item);
      $('#qtyBarang').focus();
      $('#inputKeyBarang').val(label_item);
      $('#barcode_value').val('');
      $('#barcode_text').text('');
      $('#barcode_input').hide();
    }
  });

  $('input[name=flag_metode]').change(function(){
    var value = $('input[name=flag_metode]:checked').val();
    if(value == 'cari_brg'){
      $('#div_cari_brg').show();
    } else {
      $('#div_cari_brg').hide();
    }
  });

  $('input[name=flag_gudang]').change(function(){
    var value = $('input[name=flag_gudang]:checked').val();
    $('#flag_cart').val(value);
  });

  $( "#qtyBarang" ).keypress(function(event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == 13){
      event.preventDefault();
      if($(this).valid()){
        insert_cart_log();
      }
      return false;
    }
  });

});

function submit_cart(){
  preventDefault();
  $('#form_cart').submit();
}

function show_hide_note(action){
  if (action == 'show') {
    $('#catatan_form').show();
    $('#add_note_span').hide();
    $('#hide_note_span').show();
    $('#catatan').val();
  } else {
    $('#catatan_form').hide();
    $('#add_note_span').show();
    $('#hide_note_span').hide();
    $('#catatan').val('');
  }
}

function getDetailBarang(kode_brg){
  preventDefault();
  $('#div_detail_brg').show();
  $('#psu-stok-alert').hide().html('');
  $.getJSON('Templates/References/getItemBarangDetailByUnit?kode_brg=' + kode_brg + '&flag=' + $("input[name='flag_gudang']:checked").val() + '&from_unit=' + $('#dari_unit').val() + '', '', function (response) {
    var dt_brg = response.data;
    if(dt_brg == 0){
      $('#stock_card').val(0);
      $('#psu-stok-alert').hide().html('');
    } else {
      $('#stock_card').val(1);
      // Cek apakah stok saat ini masih di atas stok minimum
      var stok_akhir = parseInt(dt_brg.jml_sat_kcl) || 0;
      var stok_min   = parseInt(dt_brg.stok_minimum) || 0;
      if(stok_min > 0 && stok_akhir > stok_min){
        $('#psu-stok-alert').html(
          '<div class="psu-alert-info">' +
            '<i class="fa fa-info-circle ai-icon"></i>' +
            '<div>' +
              '<div class="ai-title">Informasi Stok</div>' +
              '<div class="ai-body">' +
                'Stok barang <strong>' + dt_brg.nama_brg + '</strong> saat ini masih ' +
                '<span class="ai-stok">' + stok_akhir + ' ' + dt_brg.satuan_kecil + '</span>, ' +
                'berada di atas batas minimal stok <span class="ai-min">' + stok_min + ' ' + dt_brg.satuan_kecil + '</span>. ' +
                'Pastikan permintaan ini benar-benar diperlukan.' +
              '</div>' +
            '</div>' +
          '</div>'
        ).show();
      } else {
        $('#psu-stok-alert').hide().html('');
      }
    }
    $('#kode_brg_hidden').val(kode_brg);
    $('#nama_brg_hidden').val(dt_brg.nama_brg);
    $('#satuan_brg_hidden').val(dt_brg.satuan_kecil);
    $('#harga_brg_hidden').val(dt_brg.harga_beli);
    $('#qtyBarang').attr('max', parseInt(dt_brg.jml_sat_kcl));
    $('#qtyStok').val(parseInt(dt_brg.jml_sat_kcl));
    $('#qtyBarang').val('');
    $('#qtyBarang').focus();
    $('#div_detail_brg').html(response.html);
  });
}

function update_cart(kode_brg, id_det='', type_tbl=''){
  preventDefault();
  $('#div_detail_brg').show();
  $.getJSON('purchasing/pendistribusian/Permintaan_stok_unit/get_item_detail?ID=' + id_det + '&flag=' + $("input[name='flag_gudang']:checked").val() + '&type=' + type_tbl + '', '', function (response) {
    var dt_brg = response.data;
    $('#id_tc_permintaan_inst_det').val(dt_brg.id_tc_permintaan_inst_det);
    $('#kode_brg_hidden').val(dt_brg.kode_brg);
    $('#inputKeyBarang').val(dt_brg.nama_brg);
    $('#nama_brg_hidden').val(dt_brg.nama_brg);
    $('#satuan_brg_hidden').val(dt_brg.satuan_kecil);
    $('#harga_brg_hidden').val(dt_brg.harga_beli);
    $('#qtyBarang').attr('max', parseInt(dt_brg.jumlah_stok_sebelumnya));
    $('#qtyStok').val(parseInt(dt_brg.jumlah_stok_sebelumnya));
    $('#qtyBarang').val(dt_brg.jumlah_permintaan);
    $('#keterangan_permintaan').val(dt_brg.keterangan_permintaan);
    if (dt_brg.is_bhp == 1) {
      $('#is_bhp').prop('checked', true);
    } else {
      $('#is_bhp').prop('checked', false);
    }
  });
}

function show_default_cart(){
  getMenu('purchasing/pendistribusian/Permintaan_stok_unit/form?flag=medis');
}

function insert_cart_log(){
  var post_data = {
    id_tc_permintaan_inst     : $('#id').val(),
    id_tc_permintaan_inst_det : $('#id_tc_permintaan_inst_det').val(),
    barcode                   : $('#barcode_value').val(),
    flag                      : $("input[name='flag_gudang']:checked").val(),
    kode_brg                  : $('#kode_brg_hidden').val(),
    nama_brg                  : $('#nama_brg_hidden').val(),
    satuan                    : $('#satuan_brg_hidden').val(),
    keterangan_permintaan     : $('#keterangan_permintaan').val(),
    harga                     : $('#harga_brg_hidden').val(),
    qty                       : $('#qtyBarang').val(),
    qtyBefore                 : $('#qtyStok').val(),
    dari_unit                 : $('#dari_unit').val(),
    is_bhp                    : $('#is_bhp').is(':checked') ? 1 : 0,
    flag_form                 : 'permintaan_stok_unit',
    type_tbl                  : $('#type_tbl').val(),
    stock_card                : $('#stock_card').val(),
  };

  $.ajax({
    type      : 'POST',
    url       : 'purchasing/pendistribusian/Permintaan_stok_unit/insert_cart_log',
    data      : post_data,
    dataType  : 'json',
    beforeSend: function() { achtungShowLoader(); },
    complete: function(xhr) {
      var data = xhr.responseText;
      var jsonResponse = JSON.parse(data);
      if(jsonResponse.status === 200){
        cartData.ajax.url("purchasing/pendistribusian/Permintaan_stok_unit/get_detail_cart?flag=" + jsonResponse.flag + "&id=" + $('#id').val()).load();
        $('#inputKeyBarang').val('');
        $('#kode_brg_hidden').val('');
        $('#nama_brg_hidden').val('');
        $('#satuan_brg_hidden').val('');
        $('#harga_brg_hidden').val('');
        $('#qtyBarang').val('');
        $('#qtyStok').val('');
        $('#keterangan_permintaan').val('');
        $('#is_bhp').prop('checked', false);
        $('#psu-stok-alert').hide().html('');
      } else if(jsonResponse.status === 302){
        Swal.fire({
          title: 'Barang Sudah Ada!',
          html: jsonResponse.message,
          icon: 'warning',
          confirmButtonText: 'OK',
          confirmButtonColor: '#2c6fad',
        });
      } else {
        $('#div_detail_brg').html('<div style="color:#c0392b; padding:8px 0; font-size:12px"><i class="fa fa-exclamation-triangle"></i> ' + jsonResponse.message + '</div>');
      }
      achtungHideLoader();
    }
  });
}

function delete_cart(kode_brg, id_det, type_tbl){
  Swal.fire({
    title: 'Hapus Barang?',
    text: 'Apakah Anda yakin ingin menghapus barang ini dari daftar?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, Hapus',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#c0392b',
    cancelButtonColor: '#6c757d',
  }).then((result) => {
    if(result.isConfirmed){
      $.ajax({
        url: 'purchasing/pendistribusian/Permintaan_stok_unit/delete_cart',
        type: "post",
        data: {
          ID: kode_brg,
          flag: $('#flag_cart').val(),
          flag_form: 'permintaan_stok_unit',
          id_tc_permintaan_inst_det: id_det,
          type: type_tbl
        },
        dataType: "json",
        beforeSend: function() { achtungShowLoader(); },
        success: function(data) {
          achtungHideLoader();
          cartData.ajax.reload();
        }
      });
    }
  });
}
</script>
