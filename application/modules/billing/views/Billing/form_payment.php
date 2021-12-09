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

    $('input[name=metode_penyesuaian_NK_asuransi]').change(function(){
        preventDefault();
        if($(this).is(':checked')){
          $('#div_bon_karyawan').show();
          $('#divNkAsuransi').show();
          $('#divPersentaseDiskon').hide();
          $('#divNkPerusahaanOrKaryawan').hide();
          $('#jumlah_nk_asuransi').val(0);
          // $('#jumlah_nk').removeAttr('readonly');
          sum_um_nk_diskon();
        } else {
          $('#div_bon_karyawan').hide();
          $('#divNkAsuransi').hide();
          $('#divPersentaseDiskon').show();
          $('#divNkPerusahaanOrKaryawan').show();
          $('#jumlah_nk_asuransi').val(0);
          $('#jml_nk_dibayarkan').text( formatMoney(0) );
          // $('#jumlah_nk').val(0);
          sum_um_nk_diskon();
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
    
    $('input[name=hutang_nk]').change(function(){
        preventDefault();
        if($(this).is(':checked')){
          $('#jumlah_nk').removeAttr('readonly');
          cek_sisa_belum_bayar('jumlah_nk');
          hitungDiskon();
        } else {
          $('#jumlah_nk').attr('readonly', true);
          $('#jumlah_nk').val(0);
          sum_total_pembayaran();
        }
        
    });

    let id_kel = $('#id_kel').val();
    console.log('kode kelompok '+ id_kel);
    statusKaryawan(id_kel);

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
      $('<tr><td><input type="text" name="pembayar" class="form-control" style="border-radius: 4px !important; border: none; box-shadow: 0 0 0 0.2rem rgba(193, 255, 155, 0.25) !important;" value="'+$('#nama_pasien_val').val()+'"></td><td align="right" style="padding-top: 7px;"><span style="font-size: 14px; font-weight: bold; color: red" class="blink_me_xx">'+formatMoney(sisa_nk)+'</span></td></tr>').appendTo($('#table_pembayar'));
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
  var jml_diskon = formatNumberFromCurrency($('#jml_diskon_internal').text());
  var sum_class = sumClass('uang_dibayarkan');
  
  // console.log(sum_class + ' sum_class Uang Dibayarkan - sum_total_pembayaran');
  // console.log(total);

  // diskon
  // var diskon_rp = total * (jml_diskon/100);
  // $('#jml_diskon_internal').text(formatMoney(parseInt(diskon_rp)));
  // $('#nominal_diskon').val(diskon_rp);
  // console.log('Diskon RP : '+diskon_rp);
  // console.log('total : '+total);
  // console.log('Diskon jml : '+jml_diskon);


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

  // $('#jml_nk_dibayarkan').text( formatMoney($('#total_nk').val()) );

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
  // console.log(sum_class + 'sum_class uang_dibayarkan - cek_sisa_belum_bayar');
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

function statusKaryawan(id){

  // console.log(id);
  let kode_kel = id;
  // console.log(kode_kel);
  var total = formatNumberFromCurrency($('#jumlah_bayar_tunai').text());

  if ( kode_kel != 0 && kode_kel != 1 && kode_kel !=2 && kode_kel !=3 && kode_kel !=5 && kode_kel !=6 && kode_kel !=10 && kode_kel !=null ){
    console.log('Bisa Bon Karyawan + Diskon');
    // console.log(kode_kel);
    $('#hutang_nk').removeAttr("disabled");
    $('#div_bon_karyawan').show();
    $('#jumlah_diskon').val(20);

    // diskon
    
    var jml_diskon = $('#jumlah_diskon').val();
    var diskon_rp = total * (jml_diskon/100);
    $('#jml_diskon_internal').text(formatMoney(parseInt(diskon_rp)));
    $('#nominal_diskon').val(diskon_rp);

    // hitung ulang UM + NK + Diskon
    let jml_um_rp = formatNumberFromCurrency($('#jml_um').text());
    let jml_nk_rp = formatNumberFromCurrency($('#jml_nk_dibayarkan').text());
    let total_um_nk_disc = jml_um_rp + jml_nk_rp + diskon_rp;

    $('#jml_um_nk').text(formatMoney(parseInt(total_um_nk_disc)));

    let total_setelah_diskon = total - total_um_nk_disc;
    $('#jml_dibayarkan').text(formatMoney(total_setelah_diskon));

    // sum_total_pembayaran();
  }else {
    console.log('Gak Bisa Bon Karyawan');
    // console.log(kode_kel);
    $('#hutang_nk').attr("disabled", true);
    $('#jumlah_diskon').val(0);
    $('#jml_um_nk').text(0);
    $('#jml_diskon_internal').text(0);
    $('#div_bon_karyawan').hide();
    $('#jml_dibayarkan').text(formatMoney(total));

  }

  // Penyesuaian selisih khusus asuransi umum
  if ( kode_kel == 3){
    $('#labelNK').removeAttr('hidden');
    $('#labelBonKaryawan').attr('hidden', true);
  }else{
    $('#labelNK').attr('hidden', true);
    $('#checkBoxNK').attr('checked', false);
    $('#jumlah_nk').val(0);
    $('#labelBonKaryawan').removeAttr('hidden');
  }

  sum_total_pembayaran();
}

function hitungDiskon(){
  let persentaseDiskon = $('#jumlah_diskon').val();
  let total_payment = $('#total_payment').val();

  // console.log(persentaseDiskon+' Persentase Diskon');
  // console.log(total_payment+' Nominal Utuh');
  
  let intDiskon = (persentaseDiskon/100) * total_payment;
  // console.log(intDiskon+' Nominal Diskon');

  let intUangMuka = formatNumberFromCurrency($('#jml_um').text());
  let intNKPerusahaan = formatNumberFromCurrency($('#jml_nk_dibayarkan').text());

  let sumUmNkDisc = 0;
  sumUmNkDisc = parseInt(intDiskon) + parseInt(intUangMuka) + parseInt(intNKPerusahaan);

  $('#diskon_int').val(intDiskon);
  $('#jml_diskon_internal').text( formatMoney(intDiskon) );
  $('#jml_um_nk').text( formatMoney(sumUmNkDisc) );

  let jumlah_dibayarkan = 0;
  jumlah_dibayarkan = parseInt(total_payment) - parseInt(sumUmNkDisc);

  $('#jml_dibayarkan').text( formatMoney(jumlah_dibayarkan) );
}

function inputNominalNK(){
  // add nominal Nota Kredit
  let intNK = $('#jumlah_nk_asuransi').val();
  $('#jml_nk_dibayarkan').text( formatMoney(intNK) );

  sum_um_nk_diskon();

  sum_total_pembayaran();

}

function sum_um_nk_diskon(){
  let intNK = $('#jumlah_nk_asuransi').val();
  let uangMuka = formatNumberFromCurrency( $('#jml_um').text() );
  let diskon = formatNumberFromCurrency( $('#jml_diskon_internal').text() );
  let total_um_nk_diskon = parseInt(intNK) + parseInt(uangMuka) + parseInt(diskon);

  $('#jml_um_nk').text( formatMoney(total_um_nk_diskon) );

  // Set Jumlah yang harus dibayar
  let total_payment = $('#total_payment').val();
  let jumlah_dibayarkan = 0;
  jumlah_dibayarkan = parseInt(total_payment) - parseInt(total_um_nk_diskon);
  $('#jml_dibayarkan').text( formatMoney(jumlah_dibayarkan) );

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
<input type="hidden" value="<?php echo $result->reg_data->kode_kelompok; ?>" id="id_kel">
<input type="hidden" value="" name="diskon_int" id="diskon_int">

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
        <div class="form-group" id="">
          <label class="control-label col-md-4">Kategori Pasien</label>
          <div class="col-md-8" id="kode_kelompok_form">
          <?php ($result->reg_data->kode_perusahaan == 120) ? $state='disabled' : $state='';
            echo $this->master->custom_selection($params = array('table' => 'mt_nasabah', 'id' => 'kode_kelompok', 'name' => 'nama_kelompok', 'where' => array()), $result->reg_data->kode_kelompok , 'kode_penjamin_pasien', 'kode_penjamin_pasien', 'form-control', 'onchange=statusKaryawan(value);', $state) ?>
          </div>
        </div>

        <hr>

        <div class="form-group">
          <label class="control-label col-md-4">Nama Pasien</label>
          <div class="col-md-8" style="margin-left: 8px; padding-top: 5px">
            <b><?php echo $result->reg_data->nama_pasien?></b>
          </div>
        </div>
        <!-- <?php if($result->reg_data->kode_perusahaan == 0) :?>
        <div class="form-group" id="">
          <label class="control-label col-md-4">Penjamin</label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="nama_penjamin_pasien" id="nama_penjamin_pasien">
          </div>
        </div>
        
        <?php endif; ?>
           -->
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
          <div class="col-md-8" style="padding-top: 3px;padding-left: 20px;padding-right: -20px;">
            <label>
              <input name="metode_tunai" id="tunai" type="checkbox" class="ace" value="1" checked>
              <span class="lbl"> Tunai &nbsp;&nbsp; </span>
            </label>
            <label>
              <input name="metode_debet" id="debet" type="checkbox" class="ace" value="2">
              <span class="lbl"> Kartu Debit/Kredit &nbsp;&nbsp; </span>
            </label>
            <label id="labelNK" hidden>
              <input name="metode_penyesuaian_NK_asuransi" id="checkBoxNK" type="checkbox" class="ace" value="3">
              <span class="lbl"> Nota Kredit &nbsp;&nbsp; </span>
            </label>
            <label id="labelBonKaryawan">
              <?php
                $arr_kode_kelompok = [4,7,8,9,11,12,13,14,15,16];
                $kode_kelompok = $result->reg_data->kode_kelompok;
              ?>
              <input name="hutang_nk" id="hutang_nk" value="" class="ace" type="checkbox" <?php echo (in_array($kode_kelompok, $arr_kode_kelompok)) ? '' : 'disabled' ?>>
              <span class="lbl"> Bon Karyawan</span>
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
          <p><b>PEMBAYARAN KARTU DEBIT</b></p>
          <div class="form-group">
            <label class="control-label col-md-4">Jumlah Pembayaran Debit</label>
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

        <div id="div_bon_karyawan" style="<?php echo (in_array($kode_kelompok, $arr_kode_kelompok)) ? '' : 'display:none' ?>">
          <hr class="separator">
          <p><b>NOTA KREDIT PERUSAHAAN</b></p>
          <div class="form-group" id="divNkPerusahaanOrKaryawan">
            <label class="control-label col-md-4">NK Perusahaan/Karyawan</label>
            <div class="col-md-8">
              <input name="jumlah_nk" id="jumlah_nk" value="" class="form-control uang_dibayarkan nominalNK format_number" type="text" style="text-align: right" oninput="inputNominalNK()" readonly>
            </div>
          </div>
          <div class="form-group" id="divNkAsuransi" style="display: none;">
            <label class="control-label col-md-4">NK Asuransi</label>
            <div class="col-md-8">
              <input name="jumlah_nk_asuransi" id="jumlah_nk_asuransi" value="" class="form-control nominalNK format_number" type="text" style="text-align: right" oninput="inputNominalNK()">
            </div>
          </div>
          <div class="form-group" id="divPersentaseDiskon">
            <label class="control-label col-md-4">Diskon ( % )</label>
            <div class="col-md-8">
              <input name="jumlah_diskon" id="jumlah_diskon" value="" class="form-control" type="text" style="text-align: right" oninput="hitungDiskon()">
            </div>
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
            <td>NK Perusahaan/Karyawan <span id="nama_perusahaan_nk"></span> </td>
            <td align="right">Rp. <span id="jml_nk_dibayarkan">0</span></td>
          </tr>
          <tr>
            <td>Diskon ( % ) <span id="diskon_karyawan"></span> </td>
            <td align="right">Rp. <span id="jml_diskon_internal">0</span></td>
          </tr>
          <tr>
            <td>UM + NK Perusahaan + Diskon</span> </td>
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


