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
          PopupCenter('adm_pasien/pembayaran_hutang/Adm_pembayaran_riwayat/print_bp?ID='+jsonResponse.id+'&flag='+jsonResponse.flag+'','Cetak PO',900,650);

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
    padding: 5px;
  }
  th{
    height: 30px;
  }
</style>
<div class="page-header">
  <h1>
    <?php echo $title?>
  </h1>
</div>

<div class="row">
  <div class="col-xs-7">
    <!-- PAGE CONTENT BEGINS -->
      <form class="form-horizontal" method="post" id="form_create_invoice" action="<?php echo site_url('adm_pasien/pembayaran_hutang/Adm_pembayaran_pengajuan/process')?>" enctype="multipart/form-data" autocomplete="off">
    <div class="col-sm-12">
      <span style="font-size: 18px">
        <b><?php echo $value->namasupplier; ?></b>
      </span><br>
      <?php echo $value->no_terima_faktur; ?> Tgl. <?php echo $this->tanggal->formatDateDmy($value->tgl_faktur)?> | Jatuh tempo <?php echo $this->tanggal->formatDateDmy($value->tgl_rencana_bayar); ?>

        <hr>
        <span style=""><b>FORM PELUNASAN HUTANG SUPPLIER</b></span>

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
          <label class="control-label col-md-3">Jenis Transaksi</label>
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

        <table style="width: 100%">
          <thead>
            <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid #cec5c5; border-collapse: collapse">
              <th class="center" style="text-align:center; width: 2%; border: 1px solid #cec5c5; border-collapse: collapse">No</th>
              <th style="text-align:center; width: 42%; border: 1px solid #cec5c5; border-collapse: collapse">Kode Penerimaan</th>
              <th style="text-align:center; width: 42%; border: 1px solid #cec5c5; border-collapse: collapse">No Faktur</th>
              <th style="text-align:center; width: 14%; border: 1px solid #cec5c5; border-collapse: collapse" width="100px">Total (Rp)</th>
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
                <td align="center" style="text-align:center; border-left: 1px solid #cec5c5; border-collapse: collapse"><?php echo $no; ?></td>
                <td style="text-align:left; border-left: 1px solid #cec5c5; border-collapse: collapse"><?php echo $row->kode_penerimaan; ?></td>
                <td style="text-align:left; border-left: 1px solid #cec5c5; border-collapse: collapse"><?php echo $row->no_faktur; ?></td>
                <!-- jml dibayar -->
                <td align="right" style="text-align:right; border-right: 1px solid #cec5c5; border-left: 1px solid #cec5c5; border-collapse: collapse">
                  <?php echo number_format($row->total_hutang)?>
                </td>
              </tr>
            <?php endforeach; ?>
              <tr> 
                <td colspan="3" style="text-align:right; padding: 5px; border-top: 1px solid #cec5c5; border-collapse: collapse">Subtotal</td>
                <td id="subtotal" style="text-align:right; padding: 5px; border-top: 1px solid #cec5c5; border-collapse: collapse"><?php echo number_format(array_sum($arr_ttl))?></td>
              </tr>
              <tr> 
                <td colspan="3" style="text-align:right; padding: 5px; border-collapse: collapse">Biaya Materai</td>
                <td id="subtotal" style="text-align:right; padding: 5px; border-collapse: collapse"><?php echo number_format($value->biaya_materai)?></td>
              </tr>
            </tbody>
        </table>


        <div class="hr hr8 hr-double hr-dotted"></div>

        <div class="row">
          <div class="col-sm-5 pull-right">
            <h4 class="pull-right">
              Total tagihan :
              <span class="red" id="total_tagihan"><?php echo number_format($value->total_harga)?></span>
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

    


    <br>

  </form>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


