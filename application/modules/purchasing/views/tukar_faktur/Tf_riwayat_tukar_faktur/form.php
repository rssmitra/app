<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>

<script>
jQuery(function($) {

  $('.format_number').number( true, 2 );

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});

$(document).ready(function(){

    $('#form_create_invoice').ajaxForm({
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
          PopupCenter('purchasing/po/Po_penerbitan/print_preview?ID='+jsonResponse.id+'&flag='+jsonResponse.flag+'','Cetak PO',900,650);
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }
        achtungHideLoader();
      }
    });

})

function checkAll(elm) {
  if($(elm).prop("checked") == true){
    $('.checkbox_trx').each(function(){
      var kode_tc_trans_kasir = $(this).val();
      $(this).prop("checked", true);
      checkOne(kode_tc_trans_kasir);
    });
  }else{
    $('.checkbox_trx').each(function(){
      var kode_tc_trans_kasir = $(this).val();
      $('#checkbox_trx_'+kode_tc_trans_kasir+'').prop("checked", false);
      checkOne(kode_tc_trans_kasir);
    });
  }
}

function checkOne(kode_tc_trans_kasir) {
  if($('#checkbox_trx_'+kode_tc_trans_kasir+'').prop("checked") == true){
    $('#tr_'+kode_tc_trans_kasir+' input[type=text]').attr('disabled', false);
    $('#txt_penyesuaian_'+kode_tc_trans_kasir+'').text(formatMoney($('#beban_pasien_'+kode_tc_trans_kasir+'').val()));
    $('#input_penyesuaian_'+kode_tc_trans_kasir+'').val($('#beban_pasien_'+kode_tc_trans_kasir+'').val());
    $('#jml_tagihan_'+kode_tc_trans_kasir+'').text(formatMoney($('#jml_tagihan_hidden_'+kode_tc_trans_kasir+'').val()));
    $('#jml_tagihan_class_'+kode_tc_trans_kasir+'').val($('#jml_tagihan_hidden_'+kode_tc_trans_kasir+'').val());
  }else{
    $('#tr_'+kode_tc_trans_kasir+' input[type=text]').attr('disabled', true);
    $('#input_penyesuaian_'+kode_tc_trans_kasir+'').val(0);
    $('#jml_tagihan_'+kode_tc_trans_kasir+'').text(0);
    $('#jml_tagihan_class_'+kode_tc_trans_kasir+'').val(0);
  }
  hitungSubtotalTrx();
}

function inputPenyesuaian(kode_tc_trans_kasir){
  preventDefault();
  var input = $('#input_penyesuaian_'+kode_tc_trans_kasir+'').val();
  var bill = $('#bill_'+kode_tc_trans_kasir+'').val();
  $('#txt_penyesuaian_'+kode_tc_trans_kasir+'').text(formatMoney(input));
  console.log(input);
  console.log(bill);
  var jml_tagihan = parseInt(bill) - parseInt(input);
  console.log(jml_tagihan);
  $('#jml_tagihan_'+kode_tc_trans_kasir+'').text(formatMoney(jml_tagihan));
  $('#jml_tagihan_class_'+kode_tc_trans_kasir+'').val(jml_tagihan);
  hitungSubtotalTrx();
}

function inputDisc(){
  var disc = $('#diskon').val();
  var subtotal = parseInt(formatNumberFromCurrency($('#subtotal').text()));
  var rp_disc = subtotal * (disc/100);
  $('#total_diskon').text(formatMoney(Math.floor(rp_disc)));
  console.log(rp_disc);
  hitungSubtotalTrx();
}

function hitungSubtotalTrx(){
  var jml_tagihan = sumClass('jml_tagihan_class');
  $('#subtotal').text(formatMoney(jml_tagihan));
  console.log(jml_tagihan);
  var disc = $('#diskon').val();
  var rp_disc = parseInt(jml_tagihan) * (parseInt(disc)/100);
  var total = jml_tagihan - Math.floor(rp_disc);
  $('#total_tagihan').text(formatMoney(total));
}

function showHideDiv(id, show, hide){
  preventDefault();
  $('#'+show+'').show();
  $('#'+hide+'').hide();
}
</script>

<style>
  .frm-card { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; margin-bottom: 12px; }
  .frm-card-hdr { background: #1a4f8a; color: #fff; padding: 8px 14px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  .frm-card-body { padding: 16px 14px; background: #fff; }
  .frm-actions { padding: 10px 14px; background: #f8fafd; border-top: 1px solid #d0dce8; display: flex; justify-content: flex-end; gap: 6px; flex-wrap: wrap; }
  .frm-supplier-info { margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid #d0dce8; }
  .frm-supplier-info .sup-name { font-size: 16px; font-weight: 700; color: #1a4f8a; }
  .frm-supplier-info .sup-meta { font-size: 11px; color: #666; margin-top: 3px; }
  .pel-tbl { width: 100%; border-collapse: collapse; font-size: 12px; margin: 12px 0; }
  .pel-tbl thead tr { background: #2c6fad; color: #fff; }
  .pel-tbl thead th { padding: 8px 10px; text-align: center; font-weight: 600; border: 1px solid #1e5590; }
  .pel-tbl tbody td { padding: 6px 10px; border: 1px solid #d0dce8; }
  .pel-tbl .footer-row td { background: #eef4fb; font-weight: 600; border: 1px solid #d0dce8; padding: 6px 10px; }
  .total-tagihan-box { text-align: right; padding: 8px 0; font-size: 14px; }
  .total-tagihan-box .lbl { color: #555; }
  .total-tagihan-box .val { color: #c0392b; font-weight: 700; font-size: 16px; }
</style>

<div class="frm-card">
  <div class="frm-card-hdr">
    <i class="fa fa-money"></i> <?php echo $title?>
  </div>
  <div class="frm-card-body">

    <div class="frm-supplier-info">
      <div class="sup-name"><?php echo $value->namasupplier; ?></div>
      <div class="sup-meta">
        <?php echo $value->no_terima_faktur; ?> &nbsp;|&nbsp;
        Tgl. <?php echo $this->tanggal->formatDateDmy($value->tgl_faktur)?> &nbsp;|&nbsp;
        Jatuh tempo <?php echo $this->tanggal->formatDateDmy($value->tgl_rencana_bayar); ?>
      </div>
    </div>

    <form class="form-horizontal" method="post" id="form_create_invoice" action="<?php echo site_url('purchasing/po/Po_penerbitan/process')?>" enctype="multipart/form-data" autocomplete="off">

      <div class="form-group">
        <label class="control-label col-md-3">Tgl Pembayaran</label>
        <div class="col-md-2">
          <div class="input-group" style="width: 150px;">
            <input class="form-control date-picker" name="tgl_po" id="tgl_po" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
            <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">No. Kuitansi</label>
        <div class="col-md-4">
          <input type="text" class="form-control" name="no_kuitansi" id="no_kuitansi">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Metode Pembayaran</label>
        <div class="col-md-6">
          <?php echo $this->master->custom_selection_with_label($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'metode_pemb_inv') ), '' , 'metode_pembayaran', 'metode_pembayaran', 'form-control', '', '') ?>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Penerima Pembayaran</label>
        <div class="col-md-4">
          <input type="text" class="form-control" name="no_kuitansi" id="no_kuitansi">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Jenis Pembayaran</label>
        <div class="col-md-6">
          <?php echo $this->master->custom_selection_with_label($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'metode_pemb_inv') ), '' , 'metode_pembayaran', 'metode_pembayaran', 'form-control', '', '') ?>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Bank</label>
        <div class="col-md-5">
          <?php echo $this->master->custom_selection_with_label($params = array('table' => 'mt_bank', 'id' => 'acc_no', 'name' => 'nama_bank', 'where' => array() ), '' , 'bank', 'bank', 'form-control', '', '') ?>
        </div>
      </div>

      <table class="pel-tbl" style="width: 70%">
        <thead>
          <tr>
            <th width="30px">No</th>
            <th>Kode Penerimaan</th>
            <th>No Faktur</th>
            <th width="120px">Total (Rp)</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $no = 0;
            foreach($detail_faktur as $row) :
              $no++;
              $arr_ttl[] = $row->total_hutang;
          ?>
            <tr id="tr_<?php echo $row->kode_penerimaan; ?>">
              <td class="center"><?php echo $no; ?></td>
              <td><?php echo $row->kode_penerimaan; ?></td>
              <td><?php echo $row->no_faktur; ?></td>
              <td align="right"><?php echo number_format($row->total_hutang)?></td>
            </tr>
          <?php endforeach; ?>
          <tr class="footer-row">
            <td colspan="3" align="right">Subtotal</td>
            <td id="subtotal" align="right"><?php echo number_format(array_sum($arr_ttl))?></td>
          </tr>
          <tr class="footer-row">
            <td colspan="3" align="right">Biaya Materai</td>
            <td align="right"><?php echo number_format($value->biaya_materai)?></td>
          </tr>
        </tbody>
      </table>

      <div class="total-tagihan-box">
        <span class="lbl">Total tagihan :</span>
        <span class="val" id="total_tagihan"><?php echo number_format($value->total_harga)?></span>
      </div>

      <div class="frm-actions">
        <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
          <i class="fa fa-check-square-o"></i> Submit
        </button>
      </div>

    </form>
  </div>
</div>
