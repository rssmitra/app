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
  
    $('#form_create_trx').ajaxForm({
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
          $('#page-area-content').load('keuangan/Keu_trans_umum');
          // popup cetak po
          PopupCenter('keuangan/Keu_trans_umum/preview_kuitansi/'+jsonResponse.id+'','Cetak PO',900,650);

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
<div class="page-header">
  <h1>
    <?php echo $title?>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
              <form class="form-horizontal" method="post" id="form_create_trx" action="<?php echo site_url('keuangan/Keu_trans_umum/process')?>" enctype="multipart/form-data" autocomplete="off">
                <br>

                <div class="form-group">
                  <label class="control-label col-md-2">ID</label>
                  <div class="col-md-1">
                    <input name="id" id="id" value="<?php echo isset($value)?$value->id_bd_tc_trans:0?>" placeholder="Auto" class="form-control" type="text" readonly>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Tgl Transaksi</label>
                  <div class="col-md-2">
                    <div class="input-group">
                      <input class="form-control date-picker" name="tgl_transaksi" id="tgl_transaksi" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($value->tgl_transaksi)?$value->tgl_transaksi:date('Y-m-d'); ?>"/>
                      <span class="input-group-addon">
                        <i class="fa fa-calendar bigger-110"></i>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">No. Kuitansi</label>
                  <div class="col-md-2">
                    <input name="no_bukti" id="no_bukti" value="<?php echo isset($value)?$value->no_bukti:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Jenis Transaksi</label>
                  <div class="col-md-2">
                    <?php echo $this->master->custom_selection(array('table'=>'global_parameter', 'where'=>array('flag'=>'jenis_transaksi', 'is_active' => 'Y'), 'id'=>'value', 'name' => 'label'),isset($value)?$value->jenis_transaksi:'','jenis_transaksi','jenis_transaksi','chosen-slect form-control',($flag=='read')?'readonly':'','');?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Pembayaran</label>
                  <div class="col-md-2">
                    <div class="radio">
                          <label>
                            <input name="metode_pembayaran" type="radio" class="ace" value="kas" <?php echo isset($value) ? ($value->metode_pembayaran == 'kas') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl">  Kas</span>
                          </label>
                          <label>
                            <input name="metode_pembayaran" type="radio" class="ace" value="bank" <?php echo isset($value) ? ($value->metode_pembayaran == 'bank') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                            <span class="lbl">Bank</span>
                          </label>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Bank</label>
                  <div class="col-md-3">
                    <?php echo $this->master->custom_selection_with_label($params = array('table' => 'mt_bank', 'id' => 'acc_no', 'name' => 'nama_bank', 'where' => array() ), isset($value)?$value->kode_bank:'' , 'kode_bank', 'kode_bank', 'form-control', '', '') ?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Unit/Bagian</label>
                  <div class="col-md-3">
                    <?php echo $this->master->custom_selection(array('table'=>'mt_bagian', 'where'=>array('status_aktif' => 1), 'id'=>'kode_bagian', 'name' => 'nama_bagian'),isset($value)?$value->kode_bagian:'','kode_bagian','kode_bagian','chosen-slect form-control',($flag=='read')?'readonly':'','');?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Upload Bukti Transaksi</label>
                  <div class="col-md-3" style="margin-left: 0.5%">
                    <input type="file" name="file_bukti_trans" class="form-control" id="file_bukti_trans">
                  </div>
                </div>
                <?php if(isset($value->file_bukti_trans)) :?>
                   <div class="form-group">
                      <label class="control-label col-md-2">&nbsp;</label>
                      <div class="col-md-4">
                        <img style="max-width:150px" class="editable img-responsive" alt="Cover Login Page" id="avatar2" src="<?php echo base_url().PATH_IMG_DEFAULT.$value->file_bukti_trans?>" />
                      </div>
                    </div>
                <?php endif;?>

                <div class="form-group">
                  <label class="control-label col-md-2">Uraian Transaksi</label>
                  <div class="col-md-6">
                    <input name="uraian" id="uraian" value="<?php echo isset($value)?$value->uraian:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Jumlah (Rp.)</label>
                  <div class="col-md-2">
                    <input name="jumlah" id="jumlah" value="<?php echo isset($value)?$value->jumlah:''?>" placeholder="" class="format_number form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Penerima</label>
                  <div class="col-md-2">
                    <input name="penerima" id="penerima" value="<?php echo isset($value)?$value->penerima:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>

                <div class="form-group" style="padding-bottom: 10px">
                  <label class="control-label col-md-2">Catatan</label>
                  <div class="col-md-4">
                  <textarea name="catatan" class="form-control" <?php echo ($flag=='read')?'readonly':''?> style="height:50px !important"><?php echo isset($value)?$value->catatan:''?></textarea>
                  </div>
                </div>

                <div class="form-actions center">
                  <a onclick="getMenu('keuangan/Keu_trans_umum?<?php echo $qry_url?>')" href="#" class="btn btn-xs btn-success">
                        <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                        Kembali ke daftar
                  </a>
                  <?php if($flag != 'read'):?>
                  <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                    <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                    Reset
                  </button>
                  <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Submit
                  </button>
                <?php endif; ?>
                </div>
              </form>
            </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->



