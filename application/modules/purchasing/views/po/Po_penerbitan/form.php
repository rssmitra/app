<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<style>
  .frm-card { border: 1px solid #d0dce8; border-radius: 5px; background: #fff; margin-bottom: 14px; box-shadow: 0 1px 4px rgba(44,111,173,.07); }
  .frm-card-header { background: #2c6fad; color: #fff; padding: 10px 16px; border-radius: 5px 5px 0 0; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; }
  .frm-card-body { padding: 16px 20px; }
  .frm-actions { display: flex; gap: 8px; justify-content: flex-end; padding: 10px 16px; background: #f0f5fb; border-top: 1px solid #d8e6f3; border-radius: 0 0 5px 5px; }

  .frm-field-row { display: flex; align-items: center; margin-bottom: 10px; }
  .frm-field-label { min-width: 140px; text-align: right; padding-right: 14px; font-size: 12px; color: #555; font-weight: 600; flex-shrink: 0; }
  .frm-field-input { flex: 1; }

  .page-header-frm { border-bottom: 3px solid #2c6fad; padding-bottom: 10px; margin-bottom: 16px; }
  .page-header-frm h1 { font-size: 20px; margin: 0; color: #1a4f8a; font-weight: 700; }

  .sup-display { padding: 14px 16px; min-height: 80px; }
  .sup-display #detail_supplier { font-size: 12px; color: #444; line-height: 1.8; }

  .jenis-po-group { display: flex; gap: 10px; flex-wrap: wrap; }
  .jenis-po-label { display: flex; align-items: center; gap: 6px; padding: 5px 14px; border: 1px solid #c5d5e8; border-radius: 20px; cursor: pointer; font-size: 12px; font-weight: 600; color: #555; transition: all .15s; }
  .jenis-po-label input[type=radio] { cursor: pointer; }
  .jenis-po-label:has(input:checked) { background: #2c6fad; border-color: #2c6fad; color: #fff; }

  .hint-text { font-size: 10px; color: #e65100; font-style: italic; display: flex; align-items: center; gap: 4px; margin-top: 4px; }
</style>

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

$(document).ready(function(){

    $('#form_penerbitan_po').ajaxForm({
      beforeSend: function() {
        achtungShowLoader();
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status === 200){
          $.achtung({message: jsonResponse.message, timeout:5});
          $('#page-area-content').load('purchasing/po/Po_revisi/view_data?flag=<?php echo $flag?>');
          // popup cetak po
          PopupCenter('purchasing/po/Po_penerbitan/print_preview?ID='+jsonResponse.id+'&flag='+jsonResponse.flag+'','Cetak PO',900,650);

        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    });

    var flag = ( $('#flag_string').val() ) ? $('#flag_string').val() : '' ;
    var search_by = $('select[name="search_by"]').val();
    var keyword = $('#inputKeyWord').val();

    $('#btn_search_brg').click(function (e) {

        if ( $('#inputKeyWord').val()=='' ) {
          alert('Silahkan Masukan Kata Kunci !'); return false;
        }

        search_selected_brg(flag, search_by, keyword);

        e.preventDefault();

    });

    $( "#inputKeyWord" ).keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            search_selected_brg(flag, search_by, keyword);
          }
          return false;
        }
    });

    $('#inputSupplier').typeahead({
        source: function (query, result) {
                $.ajax({
                    url: "templates/references/getSupplier",
                    data: 'keyword=' + query,
                    dataType: "json",
                    type: "POST",
                    success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                    }
                });
            },
            afterSelect: function (item) {
            preventDefault();
            // do what is needed with item
            var val_item=item.split(':')[0];
            $('#detail_supplier').html('');
            console.log(val_item);
            $('#supplier_id_hidden').val(val_item);

            // get detail data supplier
            $.getJSON("<?php echo site_url('Templates/References/getSupplierById') ?>/" + val_item, '', function (response) {
                // detail supplier
                $('#detail_supplier').html(
                  '<div style="font-size:13px;font-weight:700;color:#1a4f8a;margin-bottom:4px">' + response.namasupplier + '</div>' +
                  '<div style="font-size:11px;color:#666;line-height:1.7">' +
                    '<i class="fa fa-map-marker" style="width:14px;color:#999"></i> ' + response.alamat + '<br>' +
                    '<i class="fa fa-phone" style="width:14px;color:#999"></i> ' + response.telpon1 +
                  '</div>'
                );
            });


        }
    });


})

function search_selected_brg(flag, search_by, keyword){

  $.ajax({ //Process the form using $.ajax()
      type      : 'POST', //Method type
      url       : 'Templates/References/getRefBrg', //Your form processing file URL
      data      : {keyword: $('#inputKeyWord').val(), flag: flag, search_by: search_by}, //Forms name
      dataType  : 'json',
      success   : function(data) {
          $('#show_detail_selected_brg').html(data.html);
      }
  })

}


</script>

<div class="page-header-frm">
  <h1><?php echo $title?> <small style="font-size:13px;color:#888;font-weight:400"><i class="ace-icon fa fa-angle-double-right"></i> <?php echo ($flag=='medis') ? 'Gudang Medis' : 'Gudang Non Medis'; ?></small></h1>
</div>

<form class="form-horizontal" method="post" id="form_penerbitan_po" action="<?php echo site_url('purchasing/po/Po_penerbitan/process')?>" enctype="multipart/form-data" autocomplete="off">

  <!-- hidden inputs -->
  <input name="id" id="id" value="" type="hidden">
  <input type="hidden" name="flag" id="flag_string" value="<?php echo $flag?>">
  <input type="hidden" name="action" id="action" value="create">

  <div class="row" style="margin-bottom:0">

    <!-- Left: PO Info -->
    <div class="col-xs-6">
      <div class="frm-card">
        <div class="frm-card-header"><i class="fa fa-file-text-o"></i> Informasi Purchase Order</div>
        <div class="frm-card-body">

          <div class="frm-field-row">
            <span class="frm-field-label">Nomor Periodik</span>
            <div class="frm-field-input">
              <input name="no_urut_periodik" id="no_urut_periodik" value="<?php echo $no_urut_periodik?>" class="form-control input-sm" type="text" placeholder="Auto" readonly style="width:100px">
            </div>
          </div>

          <div class="frm-field-row">
            <span class="frm-field-label">Nomor PO</span>
            <div class="frm-field-input">
              <input name="no_po" id="no_po" value="<?php echo $no_po?>" class="form-control input-sm" type="text" placeholder="Auto" readonly style="width:180px">
            </div>
          </div>

          <div class="frm-field-row">
            <span class="frm-field-label">SIK AA</span>
            <div class="frm-field-input">
              <input name="sipa" id="sipa" value="" class="form-control input-sm" type="text" style="width:180px">
            </div>
          </div>

          <div class="frm-field-row">
            <span class="frm-field-label">Tanggal PO</span>
            <div class="frm-field-input">
              <div class="input-group" style="width:165px">
                <input class="form-control input-sm date-picker" name="tgl_po" id="tgl_po" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
          </div>

          <div class="frm-field-row">
            <span class="frm-field-label">Estimasi Kirim</span>
            <div class="frm-field-input">
              <div class="input-group" style="width:165px">
                <input class="form-control input-sm date-picker" name="tgl_kirim" id="tgl_kirim" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d', strtotime("+7 days"));?>"/>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
              <div class="hint-text"><i class="fa fa-exclamation-circle"></i> Maksimal pengiriman 7 hari</div>
            </div>
          </div>

          <div class="frm-field-row">
            <span class="frm-field-label">Diajukan Oleh</span>
            <div class="frm-field-input">
              <input name="diajukan_oleh" id="diajukan_oleh" value="<?php echo $this->session->userdata('user')->fullname ?>" class="form-control input-sm" type="text">
            </div>
          </div>

          <div class="frm-field-row">
            <span class="frm-field-label">Disetujui Oleh</span>
            <div class="frm-field-input">
              <input name="disetujui_oleh" id="disetujui_oleh" value="<?php echo ($flag=='non_medis') ? $this->master->get_ttd_data('ttd_waka_rs_bid_adm', 'label') : $this->master->get_ttd_data('ttd_waka_rs_bid_pl', 'label') ;?>" class="form-control input-sm" type="text">
            </div>
          </div>

          <div class="frm-field-row">
            <span class="frm-field-label">KARS</span>
            <div class="frm-field-input">
              <input name="krs" id="krs" value="<?php echo $this->master->get_ttd_data('ttd_ka_rs', 'label')?>" class="form-control input-sm" type="text">
            </div>
          </div>

          <div class="frm-field-row">
            <span class="frm-field-label">Jenis PO</span>
            <div class="frm-field-input">
              <div class="jenis-po-group">
                <label class="jenis-po-label">
                  <input name="jenis_po" type="radio" class="ace" value="Rutin" <?php echo isset($value) ? ($value->jenis_po == '2') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                  <span>Rutin</span>
                </label>
                <label class="jenis-po-label">
                  <input name="jenis_po" type="radio" class="ace" value="Non Rutin" <?php echo isset($value) ? ($value->jenis_po == '3') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                  <span>Non Rutin</span>
                </label>
                <label class="jenis-po-label">
                  <input name="jenis_po" type="radio" class="ace" value="Cito" <?php echo isset($value) ? ($value->jenis_po == '1') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                  <span>Cito</span>
                </label>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Right: Supplier + Syarat Bayar -->
    <div class="col-xs-6">

      <div class="frm-card">
        <div class="frm-card-header"><i class="fa fa-truck"></i> Pilih Supplier</div>
        <div class="frm-card-body" style="padding-bottom:10px">
          <div class="frm-field-row">
            <span class="frm-field-label">Supplier</span>
            <div class="frm-field-input">
              <input id="inputSupplier" class="form-control input-sm" type="text" placeholder="Ketik min. 3 karakter untuk mencari..." autocomplete="off">
              <input type="hidden" name="kodesupplier" id="supplier_id_hidden">
            </div>
          </div>
        </div>
        <div class="sup-display">
          <div id="detail_supplier">
            <span style="color:#bbb;font-size:12px"><i class="fa fa-info-circle"></i> Pilih supplier dari pencarian di atas</span>
          </div>
        </div>
      </div>

      <div class="frm-card">
        <div class="frm-card-header"><i class="fa fa-credit-card"></i> Syarat Pembayaran</div>
        <div class="frm-card-body">
          <textarea class="form-control input-sm" style="height:60px;resize:vertical" name="term_of_pay">30 hari setelah tukar faktur</textarea>
        </div>
      </div>

    </div>

  </div>

  <!-- Action Buttons -->
  <div class="frm-actions" style="background:#fff;border:1px solid #d0dce8;border-radius:5px;padding:12px 16px;margin-bottom:14px">
    <a onclick="getMenu('purchasing/po/Po_penerbitan/view_data?flag=<?php echo $flag?>', 'tabs_form_po')" href="#" class="btn btn-sm btn-default">
      <i class="fa fa-arrow-left"></i> Kembali ke Daftar
    </a>
    <button type="reset" id="btnReset" class="btn btn-sm btn-warning">
      <i class="fa fa-refresh"></i> Reset
    </button>
    <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-primary">
      <i class="fa fa-check"></i> Simpan PO
    </button>
  </div>

  <?php echo $view_brg_po?>

</form>
