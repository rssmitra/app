<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>
$(document).ready(function(){
    
    get_resume_billing();

    $('#uang_dibayarkan').focus();

    $( "#uang_dibayarkan" ).keypress(function(event) {  
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){          
        event.preventDefault();         
        if($(this).valid()){           
          $('#jumlah_nk').focus();    
        }         
        return false;                
      }   

      
  });

    $('select[name=metode_pembayaran]').change(function () {
      if( $(this).val()==1 ){
        $('#div_tunai').show();
        $('#div_debet').hide();
        $('#div_kredit').hide();
      }

      if( $(this).val()==2 ){
        $('#div_tunai').hide();
        $('#div_debet').show();
        $('#div_kredit').hide();
      }

      if( $(this).val()==3 ){
        $('#div_tunai').hide();
        $('#div_debet').hide();
        $('#div_kredit').show();
      }

    });

})

function get_resume_billing(){
  $.getJSON("billing/Billing/getDetailLess/<?php echo $no_registrasi; ?>/<?php echo $tipe; ?>", '' , function (data) {
    $('#resume_billing').html(data.html);
    
    if( $('#perusahaan_penjamin').val() != 'UMUM' ){
      $('#pembayar').val( $('#perusahaan_penjamin').val() );
      // apend to table
      if( $('#total_nk').val() > 0 ){
        $('<tr><td>'+$('#perusahaan_penjamin').val()+'</td><td align="right">'+formatMoney($('#total_nk').val())+'</td></tr>').appendTo($('#table_pembayar'));
      }
      $('#nama_perusahaan_nk').html( '<span> ( '+$('#perusahaan_penjamin').val()+'</span> )' );
      $('#jml_nk_dibayarkan').text( formatMoney($('#total_nk').val()) );
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
      $('<tr><td>'+$('#nama_pasien_val').val()+'</td><td align="right"><span style="font-size: 14px; font-weight: bold; color: red" class="blink_me">'+formatMoney(sisa_nk)+'</span></td></tr>').appendTo($('#table_pembayar'));
    }
    
    // total uang muka atau yang sudah dibayar
    var total_um_dibayar = parseInt($('#total_uang_muka').val()) + parseInt($('#total_paid').val());
    $('#jumlah_nk').val( formatMoney($('#total_nk').val()) );
    $('.jumlah_bayar').val( formatMoney( sisa_nk) );
    $('#jml_dibayarkan').text( formatMoney( sisa_nk ) );
    $('#jml_um').text( formatMoney( total_um_dibayar ) );

    // nk + um
    var nk_um = parseInt( total_um_dibayar ) + parseInt($('#total_nk').val());
    $('#jml_um_nk').text( formatMoney( nk_um ) );
    sum_total_pembayaran();
  })
}

function sum_total_pembayaran(){

  var total_all = $('#total_payment_all').val();
  var total_payment = $('#total_payment').val();
  var total = formatNumberFromCurrency($('#jml_dibayarkan').text());
  var total_um_nk = formatNumberFromCurrency($('#jml_um_nk').text());
  var cash = $('#uang_dibayarkan').val();
  $('#uang_dibayarkan_text').text( formatMoney(parseInt(cash)) );

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
  var sisa_blm_bayar = parseInt(total_all) - parseInt(total_um_nk) - (parseInt(total) + parseInt(sisa_tunai) );
  $('#sisa_blm_dibayar').text( formatMoney(sisa_blm_bayar) );

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
    <div id="resume_billing"></div>
    <hr>
    <div class="col-xs-12">
      <b>Pembayaran Uang Muka / yang sudah dibayar</b><br>
      <table width="100%" >
      <?php
        $no = 0; 
        foreach($result->kasir_data as $row_um) : $no++;
      ?>
        <tr>
          <td width="20%"><?php echo $no.'. '.$row_um->pembayar?></td>
          <td width="10%"><a href="#" onclick="PopupCenter('billing/Billing/print_kuitansi?no_registrasi=<?php echo $no_registrasi?>&payment=<?php echo (int)$row_um->bill?>','Cetak Kuitansi', 900, 350)"><?php echo $row_um->seri_kuitansi.'-'.$row_um->no_kuitansi?></a></td>
          <td width="30%" align="right"><?php echo $this->tanggal->formatDateTime($row_um->tgl_jam)?></td>
          <td width="20%" align="right" style="font-weight: bold">Rp. <?php echo number_format($row_um->bill)?>,-</td>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="2"><hr></td>
      </tr>
      <tr>
        <td colspan="3"><b>Sisa yang belum dibayar</b></td>
        <td align="right" style="font-size: 14px; font-weight: bold; color: red" class="blink_me">Rp. <span id="sisa_blm_dibayar">0</span></td>
      </tr>

    </table>
    </div>
  </div>

</div><!-- /.row -->


