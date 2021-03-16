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

          <form class="form-horizontal" method="post" id="form_create_invoice" action="<?php echo site_url('purchasing/po/Po_penerbitan/process')?>" enctype="multipart/form-data" autocomplete="off">
            <br>
            <!-- input form hidden -->
            
            <div class="col-sm-10 col-sm-offset-1">
              <div class="widget-box transparent">
                <div class="widget-header widget-header-large">
                  <h3 class="widget-title grey lighter">
                    <i class="ace-icon fa fa-leaf green"></i>
                    <?php echo $value->nama_perusahaan; ?>
                  </h3>

                  <div class="widget-toolbar no-border invoice-info">
                    <span class="invoice-info-label">Invoice:</span>
                    <span class="red">#<?php echo $_GET['jenis_pelayanan'].'-'.$no_invoice['max_num']; ?></span>

                    <br>
                    <span class="invoice-info-label">Date:</span>
                    <span class="blue"><?php echo $this->tanggal->formatDateDmy(date('Y-m-d'))?></span>
                  </div>

                  <div class="widget-toolbar hidden-480">
                    <a href="#">
                      <i class="ace-icon fa fa-print"></i>
                    </a>
                  </div>
                </div>

                <div class="widget-body">
                  <div class="widget-main padding-24">
                    <div class="row">
                      <div class="col-sm-12">

                          <div class="form-group">
                            <label class="control-label col-md-2">No Invoice</label>
                            <div class="col-md-3">
                              <input name="no_invoice" id="no_invoice" value="<?php echo $no_invoice['format']?>" class="form-control" type="text" placeholder="Auto">
                            </div>
                            <label class="control-label col-md-2">Periode Transaksi</label>
                            <div class="col-md-4" style="padding-top: 3px; padding-left: 16px">
                              <b><?php echo $this->tanggal->formatDateDmy($_GET['from_tgl'])?> s/d <?php echo $this->tanggal->formatDateDmy($_GET['to_tgl'])?></b>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="control-label col-md-2">Tgl Tagihan</label>
                            <div class="col-md-2">
                              <div class="input-group" style="width: 150px;">
                                <input class="form-control date-picker" name="tgl_po" id="tgl_po" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                                <span class="input-group-addon">
                                  <i class="fa fa-calendar bigger-110"></i>
                                </span>
                              </div>
                            </div>
                            <label class="control-label col-md-2" style="margin-left: 4.5%">Tgl Jatuh Tempo</label>
                            <div class="col-md-2">
                              <div class="input-group" style="width: 150px;">
                                <input class="form-control date-picker" name="tgl_kirim" id="tgl_kirim" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d', strtotime("+30 days"));?>"/>
                                <span class="input-group-addon">
                                  <i class="fa fa-calendar bigger-110"></i>
                                </span>
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="control-label col-md-2">Diskon Perusahaan</label>
                            <div class="col-md-1">
                              <input name="diskon" id="diskon" value="<?php echo $value->disc; ?>" class="form-control" type="text" onchange="inputDisc()">
                            </div>
                          </div>

                      </div><!-- /.col -->
                    </div><!-- /.row -->

                    <div class="space"></div>

                    <div>
                      <table class="table">
                          <tr>
                            <th class="center"><input type="checkbox" onClick="checkAll(this);" style="cursor:pointer"></th>
                            <th>Tanggal</th>
                            <th>No MR</th>
                            <th>Nama Pasien</th>
                            <th>Billing</th>
                            <th>Beban Pasien</th>
                            <th width="100px">Penyesuaian</th>
                            <th width="100px">Jml Ditagih</th>
                          </tr>
                          <?php 
                            foreach($detail_pasien as $row) : 
                              $arr_ttl[] = $row->nk_perusahaan_int;
                          ?>
                            <tr id="tr_<?php echo $row->kode_tc_trans_kasir; ?>">
                              <td align="center"><input type="checkbox" class="checkbox_trx" id="checkbox_trx_<?php echo $row->kode_tc_trans_kasir; ?>" class="form-control" value="<?php echo $row->kode_tc_trans_kasir; ?>" onClick="checkOne('<?php echo $row->kode_tc_trans_kasir; ?>');" style="cursor:pointer" name="is_checked[<?php echo $row->kode_tc_trans_kasir; ?>]"></td>
                              <td><?php echo $this->tanggal->formatDateDmy($row->tgl_jam)?></td>
                              <td><?php echo $row->no_mr; ?></td>
                              <td><?php echo $row->nama_pasien; ?></td>
                              <td align="right">
                                <!-- hidden -->
                                <input type="hidden" id="bill_<?php echo $row->kode_tc_trans_kasir; ?>" value="<?php echo $row->bill_int; ?>">
                                <?php echo number_format($row->bill_int); ?>
                              </td>
                              <!-- beban biaya pasien -->
                              <td align="right">
                                <input type="hidden" id="beban_pasien_<?php echo $row->kode_tc_trans_kasir; ?>" value="<?php echo $row->beban_pasien; ?>">
                                <?php echo number_format($row->beban_pasien); ?>
                              </td>
                              <!-- input penyesuaian -->
                              <td align="right">
                                <div class="input-group" id="frm_upd_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>" style="display: none">
                                  <input type="text" name="input_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>" id="input_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>" style="text-align: right" class="form-control search-query format_number" value="" onchange="inputPenyesuaian(<?php echo $row->kode_tc_trans_kasir; ?>)" disabled>
                                  <span class="input-group-addon" style="cursor: pointer" onclick="showHideDiv(<?php echo $row->kode_tc_trans_kasir; ?>,'div_txt_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>', 'frm_upd_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>')">
                                    <i class="ace-icon fa fa-check"></i>
                                  </span>
                                </div>
                                <span id="div_txt_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>">
                                  <a href="#" onclick="showHideDiv(<?php echo $row->kode_tc_trans_kasir; ?>,'frm_upd_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>', 'div_txt_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>')"><span id="txt_penyesuaian_<?php echo $row->kode_tc_trans_kasir; ?>"></span></a>
                                </span>
                              </td>
                              <!-- jumlah tagihan -->
                              <td align="right">
                                <!-- hidden -->
                                <input type="hidden" id="jml_tagihan_hidden_<?php echo $row->kode_tc_trans_kasir; ?>" value="<?php echo $row->nk_perusahaan_int?>">
                                <input type="hidden" class="jml_tagihan_class" id="jml_tagihan_class_<?php echo $row->kode_tc_trans_kasir; ?>" value="">
                                <span id="jml_tagihan_<?php echo $row->kode_tc_trans_kasir; ?>"></span>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                          <tr>
                            <td colspan="7" align="right">Subtotal</td>
                            <td id="subtotal" align="right">0</td>
                          </tr>
                          <tr>
                            <td colspan="7" align="right">Diskon</td>
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
                      <a onclick="getMenu('adm_pasien/penagihan/Adm_tagihan_perusahaan?<?php echo $qry_url?>')" href="#" class="btn btn-xs btn-success">
                        <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                        Kembali ke daftar
                      </a>
                      <button type="reset" id="btnReset" class="btn btn-xs btn-danger">
                        <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                        Reset
                      </button>
                      <button type="submit" id="btnSave" name="submit" class="btn btn-xs btn-info">
                        <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                        Submit
                      </button>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <br>

          </form>

        </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


