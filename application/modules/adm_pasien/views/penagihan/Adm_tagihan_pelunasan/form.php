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
          // $('#page-area-content').load('purchasing/po/Po_revisi/view_data?flag=<?php //echo $flag?>');
          // popup cetak po
          PopupCenter('adm_pasien/penagihan/Adm_tagihan_pelunasan/create_invoice?ID='+jsonResponse.id+'&id_tc_tagih='+jsonResponse.id_tc_tagih+'&flag='+jsonResponse.flag+'','Cetak Invoice Pelunasan',900,650);

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
      var id_tc_tagih_det = $(this).val();
      $(this).prop("checked", true);
      checkOne(id_tc_tagih_det);
    });
  }else{
    $('.checkbox_trx').each(function(){
      var id_tc_tagih_det = $(this).val();
      $('#checkbox_trx_'+id_tc_tagih_det+'').prop("checked", false);
      checkOne(id_tc_tagih_det);
    });
  }
}

function checkOne(id_tc_tagih_det) {
  
  if($('#checkbox_trx_'+id_tc_tagih_det+'').prop("checked") == true){
    $('#tr_'+id_tc_tagih_det+' input[type=text]').attr('disabled', false);

    $('#txt_penyesuaian_'+id_tc_tagih_det+'').text(formatMoney($('#jml_tagihan_hidden_'+id_tc_tagih_det+'').val()));
    $('#input_penyesuaian_'+id_tc_tagih_det+'').val($('#jml_tagihan_hidden_'+id_tc_tagih_det+'').val());
    
    $('#jml_tagihan_'+id_tc_tagih_det+'').text(formatMoney($('#jml_tagihan_hidden_'+id_tc_tagih_det+'').val()));
    $('#jml_tagihan_class_'+id_tc_tagih_det+'').val($('#jml_tagihan_hidden_'+id_tc_tagih_det+'').val());

    hitungSubtotalTrx();
    
  }else{
      $('#tr_'+id_tc_tagih_det+' input[type=text]').attr('disabled', true);
      $('#input_penyesuaian_'+id_tc_tagih_det+'').val(0);
      $('#jml_tagihan_'+id_tc_tagih_det+'').text(0);
      $('#jml_tagihan_class_'+id_tc_tagih_det+'').val(0);
      $('#subtotal').text(0);
      $('#total_diskon_txt').text('');
      $('#total_diskon').text('');
      $('#total_tagihan').text(0);
  }
  // inputDisc(id_tc_tagih_det);

}

function inputPenyesuaian(id_tc_tagih_det){
    preventDefault();
    var input = $('#input_penyesuaian_'+id_tc_tagih_det+'').val();
    var bill = $('#bill_'+id_tc_tagih_det+'').val();
    var beban_pasien = $('#beban_pasien_'+id_tc_tagih_det+'').val();
    // txt penyesuaian
    $('#txt_penyesuaian_'+id_tc_tagih_det+'').text(formatMoney(input));
    console.log(input);
    console.log(bill);
    console.log(beban_pasien);
    // jumlah tagihan row
    // jml_tagihan -> Jml Dibayar Oleh Perusahaan setelah dikurangi oleh yang dibayarkan oleh pasien (beban Pasein)
    var jml_tagihan = parseInt(bill) - parseInt(beban_pasien);
    console.log(jml_tagihan);
    $('#jml_tagihan_'+id_tc_tagih_det+'').text(formatMoney(jml_tagihan));
    $('#jml_tagihan_class_'+id_tc_tagih_det+'').val(jml_tagihan);
    hitungSubtotalTrx();
    // inputDisc(id_tc_tagih_det);
    // inputPpn(id_tc_tagih_det);
  }

  // function inputDisc(id_tc_tagih_det){
  //   var disc = $('#diskon').val();
  //   var tagihan_utuh = parseInt(formatNumberFromCurrency($('#bill_<?php //echo $row->id_tc_tagih_det; ?>').text()));
  //   var rp_disc = tagihan_utuh * (disc/100);
  //   $('#total_diskon').text(formatMoney(Math.floor(rp_disc)));

  //   console.log(rp_disc);
  //   hitungSubtotalTrx();


  // }


 function hitungSubtotalTrx(){

    // subtotal sebelum diskon
    var jml_tagihan = sumClass('jml_tagihan_class');
    $('#subtotal').text(formatMoney(jml_tagihan));
    $('#subtotal_val').val(jml_tagihan);
    console.log(jml_tagihan);
    
    // total tagihan setelah diskon
    // var disc = $('#diskon').val();
    // var rp_disc = parseInt(jml_tagihan) * (parseInt(disc)/100);
    // $('#total_diskon_txt').text('Total Diskon :');
    // $('#total_diskon').text(formatMoney(rp_disc));
    // $('#total_diskon_val').val(rp_disc);
    // var total = jml_tagihan - Math.floor(rp_disc);
    
    $('#total_tagihan').text(formatMoney(jml_tagihan));
    $('#total_bayar_val').val(jml_tagihan);

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
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      
      <form class="form-horizontal" method="post" id="form_create_invoice" action="adm_pasien/penagihan/Adm_tagihan_pelunasan/process" enctype="multipart/form-data" autocomplete="off">
        <div class="col-sm-12">
          <!-- hidden form -->
          <input name="diskon" id="diskon" value="<?php echo $value->diskon; ?>" class="form-control" type="hidden">
          <input name="id_tc_tagih" value="<?php echo $value->id_tc_tagih; ?>" class="form-control" type="hidden">

          <div class="widget-box transparent">
            <div class="widget-header widget-header-large">
              <h3 class="widget-title grey lighter">
                <i class="ace-icon fa fa-leaf green"></i>
                <?php echo $value->nama_tertagih; ?>
              </h3>
            </div>
          </div>
          <div class="col-sm-7 no-padding">
            <div class="form-group">
              <label class="control-label col-md-3">Tgl Pembayaran</label>
              <div class="col-md-2">
                <div class="input-group" style="width: 150px;">
                  <input name="tgl_pby" type="hidden" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                  <input class="form-control date-picker" id="tgl_pby" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>" disabled/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>
              <label class="control-label col-md-2" style="margin-left: 11%">No. Kuitansi</label>
              <div class="col-md-4">
                <input type="text" class="form-control" name="no_kui" id="no_kui">
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
          <div class="col-sm-1">&nbsp;</div>
          <div class="col-sm-4 no-padding">
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
              <div class="col-md-8"> : <?php echo $value->diskon; ?> %</div>
            </div>
          </div>

          <div class="col-md-12 no-padding">
            <div style="margin-top:1rem">
              <table>
                <thead>
                  <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid #cec5c5; border-collapse: collapse">
                    <th class="center" style="text-align:center; width: 5%; border: 1px solid #cec5c5; border-collapse: collapse"><input type="checkbox" onClick="checkAll(this);" style="cursor:pointer"></th>
                    <th style="text-align:center; width: 5%; border: 1px solid #cec5c5; border-collapse: collapse">Tanggal</th>
                    <th style="text-align:center; width: 5%; border: 1px solid #cec5c5; border-collapse: collapse">No MR</th>
                    <th style="text-align:left; padding-left:3px; width: 45%; border: 1px solid #cec5c5; border-collapse: collapse">Nama Pasien</th>
                    <th style="text-align:center; width: 10%; border: 1px solid #cec5c5; border-collapse: collapse">Jumlah Tagihan</th>
                    <th style="text-align:center; width: 10%; border: 1px solid #cec5c5; border-collapse: collapse">Penyesuaian</th>
                    <th style="text-align:center; width: 10%; border: 1px solid #cec5c5; border-collapse: collapse" width="100px">Keterangan</th>
                    <th style="text-align:center; width: 10%; border: 1px solid #cec5c5; border-collapse: collapse" width="100px">Jumlah Dibayar</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    foreach($detail_pasien as $row) : 
                      $arr_ttl[] = $row->jumlah_tagih_int;
                  ?>
                    <tr id="tr_<?php echo $row->id_tc_tagih_det; ?>" style="border: 1px solid #cec5c5; border-collapse: collapse">
                      <!-- is_checked[] -->
                      <td style="text-align:center; border-left: 1px solid #cec5c5; border-collapse: collapse">
                        <input type="checkbox" class="checkbox_trx" id="checkbox_trx_<?php echo $row->id_tc_tagih_det; ?>" class="form-control" value="<?php echo $row->id_tc_tagih_det; ?>" onClick="checkOne('<?php echo $row->id_tc_tagih_det; ?>');" style="cursor:pointer" name="is_checked[<?php echo $row->id_tc_tagih_det; ?>]">
                      </td>
                      <!-- tgl_tagih -->
                      <td style="text-align:center; border-left: 1px solid #cec5c5; border-collapse: collapse">
                        <!-- <input type="hidden" name="tgl_tagih[<?php //echo $row->id_tc_tagih_det?>]" value="<?php //echo $value->tgl_tagih ?>"> -->
                        <?php echo $this->tanggal->formatDateDmy($value->tgl_tagih)?>
                      </td>
                      <!-- no_mr -->
                      <td style="text-align:center; border-left: 1px solid #cec5c5; border-collapse: collapse">
                        <input type="hidden" name="no_mr[<?php echo $row->id_tc_tagih_det?>]" value="<?php echo $row->no_mr ?>">
                        <?php echo $row->no_mr; ?>
                      </td>
                      <!-- nama_pasien -->
                      <td style="text-align:left; border-left: 1px solid #cec5c5; border-collapse: collapse; padding-left:3px; ">
                        <!-- <input type="hidden" name="nama_pasien[<?php //echo $row->id_tc_tagih_det?>]" value="<?php //echo $row->nama_pasien ?>"> -->
                        <?php echo $row->nama_pasien; ?>
                      </td>
                      <!-- Jumlah Tagihan as jml_tagihan (tc_tagih_det -> jumlah billing, sebelum diskon per pasien) -->
                      <td style="text-align:right; padding-right:5px; border-left: 1px solid #cec5c5; border-collapse: collapse">
                        <!-- <input type="hidden" class="tagihan_utuh" id="bill_<?php //echo $row->id_tc_tagih_det; ?>" name="jml_tagihan[<?php //echo $row->id_tc_tagih_det ?>]" value="<?php //echo $row->jumlah_tagih_int; ?>"> -->
                        <?php echo number_format($row->jumlah_tagih_int); ?>
                      </td>
                      <!-- penyesuaian -->
                      <td style="text-align:right; padding-right:5px; border-left: 1px solid #cec5c5; border-collapse: collapse">
                        <!-- <input type="hidden" id="beban_pasien_<?php //echo $row->id_tc_tagih_det; ?>" name="beban_pasien[<?php //echo $row->id_tc_tagih_det; ?>]" value="<?php //echo $row->penyesuaian_int; ?>"> -->
                        <?php echo number_format($row->penyesuaian_int); ?>
                      </td>
                      <!-- field Keterangan -->
                      <td style="text-align:right; padding-right:5px; padding-left:5px; border-right: 1px solid #cec5c5; border-left: 1px solid #cec5c5; border-collapse: collapse">
                        <input type="text" name="keterangan[<?php echo $row->id_tc_tagih_det ?>]" style="text-align: center; border:none;" maxlength="15" placeholder="Silakan isi keterangan..." disabled>
                      </td>
                      <!-- jml dibayar -->
                      <td style="text-align:right; padding-right:5px; border-right: 1px solid #cec5c5; border-left: 1px solid #cec5c5; border-collapse: collapse">
                        <span id="div_txt_penyesuaian_<?php echo $row->id_tc_tagih_det; ?>">
                          <input type="hidden" class="jml_tagihan_class" id="jml_tagihan_hidden_<?php echo $row->id_tc_tagih_det; ?>" name="jml_tagihan_per_pasien[<?php echo $row->id_tc_tagih_det?>]" value="<?php echo $row->jumlah_dijamin?>">
                          <span id="jml_tagihan_<?php echo $row->id_tc_tagih_det; ?>" ></span>
                        </span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  </tbody>
                  <tr> 
                    <td colspan="7" style="text-align:right; padding: 5px; border-top: 1px solid #cec5c5; border-collapse: collapse">Subtotal :</td>
                    <td id="subtotal" style="text-align:right; padding: 5px; border-top: 1px solid #cec5c5; border-collapse: collapse">0</td>
                    <input type="hidden" name="subtotal_hidden" id="subtotal_val" val="">
                    <input type="hidden" name="total_diskon_hidden" id="total_diskon_val" value="<?php echo $value->tr_yg_diskon?>">
                  </tr>
                  <!-- <tr> 
                    <td colspan="7" style="text-align:right; padding: 5px; " id="total_diskon_txt"></td>
                    <td style="text-align:right; padding: 5px; "><span id="total_diskon"></span>
                    </td>
                  </tr> -->
              </table>
            </div>

            <div class="hr hr8 hr-double hr-dotted"></div>

            <div class="row">
              <div class="col-sm-5 pull-right">
                <h4 class="pull-right">
                  Total tagihan :
                  <b class="red">Rp <span id="total_tagihan">0</span>,-</b>
                  <input type="hidden" name="total_bayar" id="total_bayar_val" value="">
                </h4>
              </div>
              <div class="col-sm-7 pull-left"> &nbsp; </div>
            </div>
            <hr>
            <div class="pull-right">
              <a onclick="getMenu('adm_pasien/penagihan/Adm_tagihan_pelunasan?<?php echo $qry_url?>')" href="#" class="btn btn-xs btn-success">
                          <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                          Kembali ke daftar</a>
              <button type="submit" id="btnSave" name="submit" class="btn btn-xs btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit Pelunasan
              </button>
            </div>
        </div>
      </form>
  </div><!-- /.row -->
</div><!-- /.row -->


