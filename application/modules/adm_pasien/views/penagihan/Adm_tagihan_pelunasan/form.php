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
  //show datepicker when clicking on the icon
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
          // popup cetak po
          PopupCenter('purchasing/po/Po_penerbitan/print_preview?ID='+jsonResponse.id+'&flag='+jsonResponse.flag+'','Cetak PO',900,650);

        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
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

    $('#txt_penyesuaian_'+kode_tc_trans_kasir+'').text(formatMoney($('#jml_tagihan_hidden_'+kode_tc_trans_kasir+'').val()));
    $('#input_penyesuaian_'+kode_tc_trans_kasir+'').val($('#jml_tagihan_hidden_'+kode_tc_trans_kasir+'').val());
    
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
    // txt penyesuaian
    $('#txt_penyesuaian_'+kode_tc_trans_kasir+'').text(formatMoney(input));
    console.log(input);
    console.log(bill);
    // jumlah tagihan row
    var jml_tagihan = parseInt(bill) - parseInt(input);
    console.log(jml_tagihan);
    $('#jml_tagihan_'+kode_tc_trans_kasir+'').text(formatMoney(jml_tagihan));
    $('#jml_tagihan_class_'+kode_tc_trans_kasir+'').val(jml_tagihan);
    hitungSubtotalTrx();
    // inputDisc(kode_tc_trans_kasir);
    // inputPpn(kode_tc_trans_kasir);
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

    // subtotal sebelum diskon
    var jml_tagihan = sumClass('jml_tagihan_class');
    $('#subtotal').text(formatMoney(jml_tagihan));
    console.log(jml_tagihan);

    // total tagihan setelah diskon
    var disc = $('#diskon').val();
    var rp_disc = parseInt(jml_tagihan) * (parseInt(disc)/100);
    var total = jml_tagihan - Math.floor(rp_disc);
    $('#total_tagihan').text(formatMoney(total));

 }

 function showHideDiv(id, show, hide){
  preventDefault();
    // show
    $('#'+show+'').show();
    $('#'+hide+'').hide();

 }

</script>
<style type="text/css">
  table tr td{
    padding-left: 3px;
    padding-right: 3px;
  }
</style>
<div class="row">
  <form class="form-horizontal" method="post" id="form_create_invoice" action="<?php echo site_url('purchasing/po/Po_penerbitan/process')?>" enctype="multipart/form-data" autocomplete="off">
    <div class="col-sm-12">
      <span style="padding-left: 13px"><b>FORM PELUNASAN TAGIHAN/PIUTANG</b></span>
      <br>
      <!-- hidden form -->
      <input name="diskon" id="diskon" value="<?php echo $value->diskon; ?>" class="form-control" type="hidden" onchange="inputDisc()">

      <div class="col-sm-7">
        <div class="form-group">
          <label class="control-label col-md-3">Tgl Pembayaran</label>
          <div class="col-md-2">
            <div class="input-group" style="width: 150px;">
              <input class="form-control date-picker" name="tgl_po" id="tgl_po" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
          <label class="control-label col-md-2" style="margin-left: 11%">No. Kuitansi</label>
          <div class="col-md-4">
            <input type="text" class="form-control" name="" id="">
          </div>
        </div>
        <div class="form-group">
          
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
      </div>

      <div class="col-sm-5">
        <div class="form-group">
          <label class="col-md-4">Nama Perusahaan</label>
          <div class="col-md-8"> : <b><?php echo $value->nama_tertagih; ?></b></div>
        </div>
        <div class="form-group">
          <label class="col-md-4">No. Invoice</label>
          <div class="col-md-8"> : <?php echo $value->no_invoice_tagih; ?></div>
        </div>
        <div class="form-group">
          <label class="col-md-4">Tgl tagihan</label>
          <div class="col-md-8"> : <?php echo $this->tanggal->formatDateDmy($value->tgl_tagih)?></div>
        </div>
        <div class="form-group">
          <label class="col-md-4">Diskon</label>
          <div class="col-md-8"> : <?php echo $value->diskon; ?></div>
        </div>
      </div>

      <div class="col-sm-12">
        <div style="margin-top: -18px">
          <table>
            <thead>
              <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid #cec5c5; border-collapse: collapse">
                <th class="center" style="text-align:center; width: 5%; border: 1px solid #cec5c5; border-collapse: collapse"><input type="checkbox" onClick="checkAll(this);" style="cursor:pointer"></th>
                <th style="text-align:center; width: 5%; border: 1px solid #cec5c5; border-collapse: collapse">Tanggal</th>
                <th style="text-align:center; width: 5%; border: 1px solid #cec5c5; border-collapse: collapse">No MR</th>
                <th style="text-align:center; width: 45%; border: 1px solid #cec5c5; border-collapse: collapse">Nama Pasien</th>
                <th style="text-align:center; width: 10%; border: 1px solid #cec5c5; border-collapse: collapse">Jml Tagihan</th>
                <th style="text-align:center; width: 10%; border: 1px solid #cec5c5; border-collapse: collapse">Sudah Dibayar</th>
                <th style="text-align:center; width: 10%; border: 1px solid #cec5c5; border-collapse: collapse" width="100px">Jml Dibayar</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                foreach($detail_pasien as $row) : 
                  $arr_ttl[] = $row->jumlah_tagih_int;
              ?>
                <tr id="tr_<?php echo $row->kode_tc_trans_kasir; ?>">
                  <td align="center" style="text-align:center; border-left: 1px solid #cec5c5; border-collapse: collapse"><input type="checkbox" class="checkbox_trx" id="checkbox_trx_<?php echo $row->kode_tc_trans_kasir; ?>" class="form-control" value="<?php echo $row->kode_tc_trans_kasir; ?>" onClick="checkOne('<?php echo $row->kode_tc_trans_kasir; ?>');" style="cursor:pointer" name="is_checked[<?php echo $row->kode_tc_trans_kasir; ?>]"></td>
                  <td style="text-align:left; border-left: 1px solid #cec5c5; border-collapse: collapse"><?php echo $this->tanggal->formatDateDmy($row->tgl_kui)?></td>
                  <td style="text-align:left; border-left: 1px solid #cec5c5; border-collapse: collapse"><?php echo $row->no_mr; ?></td>
                  <td style="text-align:left; border-left: 1px solid #cec5c5; border-collapse: collapse"><?php echo $row->nama_pasien; ?></td>
                  <!-- jumlah tagihan -->
                  <td align="right" style="text-align:right; border-left: 1px solid #cec5c5; border-collapse: collapse">
                    <input type="hidden" id="bill_<?php echo $row->kode_tc_trans_kasir; ?>" value="<?php echo $row->jumlah_tagih_int; ?>">
                    <?php echo number_format($row->jumlah_tagih_int); ?>
                  </td>
                  <!-- sudah dibayar -->
                  <td align="right" style="text-align:right; border-left: 1px solid #cec5c5; border-collapse: collapse">
                    <input type="hidden" id="beban_pasien_<?php echo $row->kode_tc_trans_kasir; ?>" value="<?php echo $row->penyesuaian_int; ?>">
                    <?php echo number_format($row->penyesuaian_int); ?>
                  </td>
                  <!-- jml dibayar -->
                  <td align="right" style="text-align:right; border-right: 1px solid #cec5c5; border-left: 1px solid #cec5c5; border-collapse: collapse">
                    <div class="input-group" id="frm_upd_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>" style="display: none">
                      <input type="text" name="input_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>" id="input_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>" style="text-align: right" class="form-control search-query format_number" value="" onchange="inputPenyesuaian(<?php echo $row->kode_tc_trans_kasir; ?>)" disabled>
                      <span class="input-group-addon" style="cursor: pointer" onclick="showHideDiv(<?php echo $row->kode_tc_trans_kasir; ?>,'div_txt_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>', 'frm_upd_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>')">
                        <i class="ace-icon fa fa-check"></i>
                      </span>
                    </div>
                    <span id="div_txt_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>">
                      <input type="hidden" id="jml_tagihan_hidden_<?php echo $row->kode_tc_trans_kasir; ?>" value="<?php echo $row->jumlah_tagih_int?>">
                      <input type="hidden" class="jml_tagihan_class" id="jml_tagihan_class_<?php echo $row->kode_tc_trans_kasir; ?>" value="">
                      <a href="#" onclick="showHideDiv(<?php echo $row->kode_tc_trans_kasir; ?>,'frm_upd_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>', 'div_txt_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>')">
                        <!-- <span id="txt_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>"></span> -->
                        <span id="jml_tagihan_<?php echo $row->kode_tc_trans_kasir; ?>"></span>
                      </a>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>`
              <tr> 
                <td colspan="6" style="text-align:right; padding: 5px; border-top: 1px solid #cec5c5; border-collapse: collapse">Subtotal</td>
                <td id="subtotal" style="text-align:right; padding: 5px; border-top: 1px solid #cec5c5; border-collapse: collapse">0</td>
              </tr>
              <tr>
                <td colspan="6" align="right">Diskon</td>
                <td id="total_diskon" align="right">0</td>
              </tr>
          </table>
        </div>

        <div class="hr hr8 hr-double hr-dotted"></div>

        <div class="row">
          <div class="col-sm-5 pull-right">
            <h4 class="pull-right">
              Total tagihan :
              <span class="red" id="total_tagihan">0</span>
            </h4>
          </div>
          <div class="col-sm-7 pull-left"> &nbsp; </div>
        </div>
        <hr>
        <div class="pull-right">
          <button type="submit" id="btnSave" name="submit" class="btn btn-xs btn-info">
            <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
            Submit
          </button>
        </div>
      </div>

    </div>

    


    <br>

  </form>
</div><!-- /.row -->


