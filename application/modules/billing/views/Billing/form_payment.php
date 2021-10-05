<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<!-- jquery number -->
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>
<script type="text/javascript">

$(function(){
        
  $('.format_number').number( true, 2 );
  
});

$(document).ready(function(){

    statButton();
    
    // if( $('#count_kasir').val() > 0 ) {
    //   $('#div_form_payment').load('billing/Billing/payment_success/'+$('#no_registrasi').val()+'');
    // }

    // defult pembayaran
    var sisa_nk = parseInt($('#total_payment').val()) - parseInt($('#total_nk').val());

    get_resume_billing();
    $('#uang_dibayarkan').val(formatMoney(sisa_nk));
    $('#uang_dibayarkan').focus();
    console.log($('#array_data_billing').val());
    if($('#array_data_billing').val() == 0){
      $('#alert_no_checked').html('<div class="alert alert-danger" style="margin-top: 29px;line-height: 0px;"><span><strong><i class="fa fa-info-circle red bigger-120"></i> Peringatan !</strong></span> Tidak ada data yang di ceklist, silahkan "Reload Billing" untuk mengetahui data terbaru.</div>');
      $('#btnSave').attr('disabled', true);
    }

    $( ".uang_dibayarkan" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
            $('#jumlah_nk').focus();    
          }         
          return false;                
        }   

        
    });

    $('input[name=metode_tunai]').change(function(){
        preventDefault();
        if($(this).is(':checked')){
          $('#div_tunai').show();
          cek_sisa_belum_bayar('uang_dibayarkan');
        } else {
          $('#div_tunai').hide();
          $('#uang_dibayarkan').val(0);
          sum_total_pembayaran();
        }
        
    });

    $('input[name=metode_kredit]').change(function(){
        preventDefault();
        if($(this).is(':checked')){
          $('#div_kredit').show();
          cek_sisa_belum_bayar('jumlah_bayar_kredit');
        } else {
          $('#div_kredit').hide();
          $('#jumlah_bayar_kredit').val(0);
          sum_total_pembayaran();
        }
        
    });

    $('input[name=metode_debet]').change(function(){
        preventDefault();
        if($(this).is(':checked')){
          $('#div_debet').show();
          cek_sisa_belum_bayar('jumlah_bayar_debet');
        } else {
          $('#div_debet').hide();
          $('#jumlah_bayar_debet').val(0);
          sum_total_pembayaran();
        }
        
    });

})

function get_resume_billing(){
  $.getJSON("billing/Billing/getDetailLess/<?php echo $no_registrasi; ?>/<?php echo $tipe; ?>", '' , function (data) {
    $('#resume_billing').html(data.html);
    console.log($('#no_sep_val').val());
    var no_sep = $('#no_sep_val').val();
    if( $('#perusahaan_penjamin').val() != 'UMUM' ){
      $('#pembayar').val( $('#perusahaan_penjamin').val() );
      // apend to table
      if( $('#total_nk').val() > 0 ){
        $('<tr><td>'+$('#perusahaan_penjamin').val()+'</td><td align="right">'+formatMoney($('#total_nk').val())+'</td></tr>').appendTo($('#table_pembayar'));
      }
      $('#nama_perusahaan_nk').html( '<span> ( '+$('#perusahaan_penjamin').val()+'</span> )' );
      $('#jml_nk_dibayarkan').text( formatMoney($('#total_nk').val()) );
      // jika penjamin bpjs
      if( $('#kode_perusahaan_val').val() == 120 ){
          $('#no_sep_pasien').val(no_sep);
          $('#form_sep_pasien').show();
      }
    }else{
      $('#pembayar').val( $('#nama_pasien_val').val() );
      // apend to table
      if( $('#total_nk').val() > 0 ){
        $('<tr><td>'+$('#nama_pasien_val').val()+'</td><td align="right">'+formatMoney($('#total_nk').val())+'</td></tr>').appendTo($('#table_pembayar'));
      }
    }
    // sisa yang tidak di NK kan
    var sisa_nk = parseInt($('#total_payment').val()) - parseInt($('#total_nk').val());
    if( sisa_nk > 0 ){
      $('<tr><td>'+$('#nama_pasien_val').val()+'</td><td align="right"><span style="font-size: 14px; font-weight: bold; color: red" class="blink_me_xx">'+formatMoney(sisa_nk)+'</span></td></tr>').appendTo($('#table_pembayar'));
    }
    
    // total uang muka atau yang sudah dibayar
    var total_um_dibayar = parseInt($('#total_uang_muka').val()) + parseInt($('#total_paid').val());
    $('#jumlah_nk').val( formatMoney($('#total_nk').val()) );
    $('#jumlah_bayar_tunai').val( sisa_nk );
    $('.jumlah_bayar').text( formatMoney( sisa_nk) );
    $('#uang_dibayarkan').text( formatMoney( sisa_nk ) );
    $('#jml_dibayarkan').text( formatMoney( sisa_nk ) );
    $('#jml_um').text( formatMoney( total_um_dibayar ) );

    // hide form bayar tunai 
    if( sisa_nk == 0 ){
        $('#div_tunai').hide();
        $('#metode_pembayaran_form').hide();
    }

    // nk + um
    var nk_um = parseInt( total_um_dibayar ) + parseInt($('#total_nk').val());
    $('#jml_um_nk').text( formatMoney( nk_um ) );
    sum_total_pembayaran();
  })
}

function sum_total_pembayaran(){

  preventDefault();
  var total_all = $('#total_payment_all').val();
  var total_payment = $('#total_payment').val();
  var total = formatNumberFromCurrency($('#jml_dibayarkan').text());
  var total_um_nk = formatNumberFromCurrency($('#jml_um_nk').text());
  var cash = $('#uang_dibayarkan').val();
  var sum_class = sumClass('uang_dibayarkan');
  
  console.log(sum_class);
  // console.log(total);
  // uang kembali
  var kembali = parseInt(cash) - parseInt(total);
  if( parseInt(cash) >= parseInt(total) ){
    var sisa_tunai = 0;
    var uang_kembali = kembali;
  }else{
    var sisa_tunai = kembali;
    var uang_kembali = 0;
  }
  

  $('#uang_kembali_text').text( formatMoney(uang_kembali) );
  // sisa belum dibayar
  var sisa_blm_bayar = parseInt(total) - parseInt(sum_class);
  if (parseInt(sisa_blm_bayar) > 0) {
    var blm_dibayarkan = sisa_blm_bayar;
  }else{
    var blm_dibayarkan = 0;
  }
  $('#sisa_blm_dibayar').text( formatMoney(blm_dibayarkan) );

  $('#uang_dibayarkan_text').text( formatMoney(parseInt(sum_class)) );

  statButton();

}

function cek_sisa_belum_bayar(div_id){
  var sum_class = sumClass('uang_dibayarkan');
  var total = formatNumberFromCurrency($('#jml_dibayarkan').text());
  var sisa_blm_bayar = parseInt(total) - parseInt(sum_class);
  if (parseInt(sisa_blm_bayar) > 0) {
    var blm_dibayarkan = sisa_blm_bayar;
  }else{
    var blm_dibayarkan = 0;
  }
  console.log(sum_class);
  $('#'+div_id+'').val(blm_dibayarkan);
  sum_total_pembayaran();
}

function statButton(){
  $("#btnSave").attr("disabled", true);
  let int_sisa_blm_dibayar = formatNumberFromCurrency($('#sisa_blm_dibayar').text());
  if (int_sisa_blm_dibayar == 0){
    $('#btnSave').removeAttr("disabled");
    console.log('disabled');
  }else{
    $('#btnSave').attr("disabled", true);
    console.log('enabled');
  }
}

</script>

<style>
  .blink_me {
  animation: blinker 1s linear infinite;
  }

  @keyframes blinker {
    50% {
      opacity: 0;
    }
  }
</style>

<!-- hidden form -->
<input type="hidden" value="<?php echo count($result->kasir_data)?>" id="count_kasir">
<input type="hidden" value="<?php echo $total_paid; ?>" id="total_paid">

<hr class="separator">

<div class="row" id="div_form_payment">

  <div class="col-xs-6">
       
        <p><b>DATA PEMBAYARAN</b></p>

        <table class="table table-bordered" width="98%" id="table_pembayar">
          <tr style="background: deepskyblue">
            <th>Pembayar / Perusahaan Penjamin</th>
            <th>Total Bayar</th>
          </tr>
          <tr>
        </table>

        <!-- <div class="form-group">
          <label class="control-label col-md-4">Pembayar (a.n)</label>
          <div class="col-md-8">
            <input name="pembayar" id="pembayar" value="<?php echo $result->reg_data->nama_pasien?>" class="form-control" type="text">
          </div>
        </div> -->

        <div class="form-group">
          <label class="control-label col-md-4">Nama Pasien</label>
          <div class="col-md-8" style="margin-left: 8px; padding-top: 5px">
            <b><?php echo $result->reg_data->nama_pasien?></b>
          </div>
        </div>
        <!-- jika pasien BPJS  -->
        <?php if($result->reg_data->kode_perusahaan == 120) :?>
        <div class="form-group" id="form_sep_pasien">
          <label class="control-label col-md-4">Nomor SEP</label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="no_sep_pasien" id="no_sep_pasien">
          </div>
        </div>
        <?php endif; ?>

        <div class="form-group" id="metode_pembayaran_form">
          <label class="control-label col-md-4">Metode Pembayaran</label>
          <div class="col-md-8" style="padding-top: 3px;padding-left: 20px;">
            <label>
              <input name="metode_tunai" id="tunai" type="checkbox" class="ace" value="1" checked>
              <span class="lbl"> Tunai &nbsp;&nbsp; </span>
            </label>
            <label>
              <input name="metode_debet" id="debet" type="checkbox" class="ace" value="2">
              <span class="lbl"> Debit &nbsp;&nbsp; </span>
            </label>
            <label>
              <input name="metode_kredit" id="kredit" type="checkbox" class="ace" value="3">
              <span class="lbl"> Kredit &nbsp;&nbsp; </span>
            </label>
            
          </div>
        </div>

        
        <div id="div_tunai">
          <hr class="separator">
          <p><b>PEMBAYARAN TUNAI</b></p>

          <div class="form-group">
            <label class="control-label col-md-4">Uang Yang Dibayarkan</label>
            <div class="col-md-8">
              <!-- hidden total yang harus dibayarkan -->
              <input name="jumlah_bayar_tunai" id="jumlah_bayar_tunai" value="" class="jumlah_bayar form-control" style="text-align: right" type="hidden">
              <input name="uang_dibayarkan_tunai" id="uang_dibayarkan" class="format_number uang_dibayarkan form-control" type="text" style="text-align: right" oninput="sum_total_pembayaran()">
            </div>
          </div>
        </div>
        
        <div id="div_debet" style="display:none">
          <hr>
          <p><b>PEMBAYARAN KARTU DEBET</b></p>
          <div class="form-group">
            <label class="control-label col-md-4">Jumlah Pembayaran Debet</label>
            <div class="col-md-8">
            <input name="jumlah_bayar_debet" id="jumlah_bayar_debet" value="" class="format_number uang_dibayarkan form-control" style="text-align: right" type="text" oninput="sum_total_pembayaran()">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-4">Kartu Debit</label>
            <div class="col-md-8">
              <?php echo $this->master->custom_selection_with_label($params = array('table' => 'mt_bank', 'id' => 'kode_bank', 'name' => 'nama_bank', 'where' => array() ), '' , 'kd_bank_dc', 'kd_bank_dc', 'form-control', '', '') ?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-4">Nomor Kartu</label>
            <div class="col-md-8">
              <input name="nomor_kartu_debet" id="nomor_kartu_debet" value="" class="form-control" type="text">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-4">Nomor Batch</label>
            <div class="col-md-8">
              <input name="nomor_batch_debet" id="nomor_batch_debet" value="" class="form-control" type="text">
            </div>
          </div>
        </div>
        

        <div id="div_kredit" style="display:none">
          <hr>
          <p><b>PEMBAYARAN KARTU KREDIT</b></p>
          <div class="form-group">
            <label class="control-label col-md-4">Jumlah Pembayaran Kredit</label>
            <div class="col-md-8">
            <input name="jumlah_bayar_kredit" id="jumlah_bayar_kredit" value="" class="format_number uang_dibayarkan form-control" style="text-align: right" type="text" oninput="sum_total_pembayaran()">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-4">Kartu Kredit</label>
            <div class="col-md-8">
              <?php echo $this->master->custom_selection_with_label($params = array('table' => 'mt_bank', 'id' => 'kode_bank', 'name' => 'nama_bank', 'where' => array() ), '' , 'kd_bank_cc', 'kd_bank_cc', 'form-control', '', '') ?>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-4">Nomor Kartu</label>
            <div class="col-md-8">
              <input name="nomor_kartu_kredit" id="nomor_kartu_kredit" value="" class="form-control" type="text">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-4">Nomor Batch</label>
            <div class="col-md-8">
              <input name="nomor_batch_kredit" id="nomor_batch_kredit" value="" class="form-control" type="text">
            </div>
          </div>
        </div>

        <hr class="separator">
        <p><b>NOTA KREDIT PERUSAHAAN</b></p>
        <div class="form-group">
          <label class="control-label col-md-4">Total NK Perusahaan</label>
          <div class="col-md-8">
            <input name="jumlah_nk" id="jumlah_nk" value="" class="form-control" type="text" style="text-align: right" readonly>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-md-4">Diskon</label>
          <div class="col-md-8">
            <input name="jumlah_diskon" id="jumlah_diskon" value="" class="form-control" type="text" style="text-align: right">
          </div>
        </div>

        <hr class="separator">
        <p><b>PETUGAS KASIR</b></p>
        <div class="form-group">
          <label class="control-label col-md-2">Shift</label>
          <div class="col-md-3">
            <select class="form-control" name="shift">
              <option value="1">Pagi</option>
              <option value="2">Siang</option>
              <option value="3">Malam</option>
            </select>
          </div>
          <label class="control-label col-md-2">Loket</label>
          <div class="col-md-2">
            <select class="form-control" name="loket">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
            </select>
          </div>
          <div class="col-md-4" style="margin-left:-4%">
            <input type="text" class="form-control" name="petugas" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>" readonly>
          </div>
        </div>
        
        
        <div class="form-actions center">
          <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
            <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
            Batal
          </button>
          <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
            <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
            Submit
          </button>
        </div>

  </div><!-- /.col -->

  <div class="col-xs-6">
    
    <div id="alert_no_checked">
    
      <div id="resume_billing"></div>

      <div class="col-xs-12 no-padding">
        <table width="100%" style="padding: 5px">
          <tr>
            <td width="75%">Uang Muka (UM) / Sudah dibayar</td>
            <td width="25%" align="right">Rp. <span id="jml_um">0</span></td>
          </tr>
          <tr>
            <td>NK Perusahaan <span id="nama_perusahaan_nk"></span> </td>
            <td align="right">Rp. <span id="jml_nk_dibayarkan">0</span></td>
          </tr>
          <tr>
            <td>UM + NK Perusahaan</span> </td>
            <td align="right" style="font-size: 14px; font-weight: bold">Rp. <span id="jml_um_nk">0</span></td>
          </tr>

          <tr>
            <td colspan="2"><hr></td>
          </tr>
          <tr>
            <td>Jumlah Yang Harus Dibayar</td>
            <td align="right" style="font-size: 14px; font-weight: bold">Rp. <span id="jml_dibayarkan">0</span></td>
          </tr>
          <tr>
            <td>Uang Yang Dibayarkan</td>
            <td align="right" style="font-size: 14px; font-weight: bold">Rp. <span id="uang_dibayarkan_text">0</span></td>
          </tr>
          <tr>
            <td>Uang Kembali</td>
            <td align="right" style="font-size: 14px; font-weight: bold; color: blue">Rp. <span id="uang_kembali_text">0</span></td>
          </tr>
          <tr>
            <td colspan="2"><hr></td>
          </tr>
          <tr>
            <td><b>Sisa yang belum dibayar</b></td>
            <td align="right" style="font-size: 14px; font-weight: bold; color: red" class="blink_me_xx">Rp. <span id="sisa_blm_dibayar">0</span></td>
          </tr>

        </table>
        <hr>
      </div>

    </div>
    
  </div>

</div><!-- /.row -->


