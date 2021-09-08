<style type="text/css">
    input[type=checkbox]{
        margin:-2px 0px 0px !important;
        cursor: pointer;
    }
    .table-2 {
        font-size: 12px;
    }
    th {
        height: 35px;
    }
    .table-2 > thead > tr > th, .table-2 > tbody > tr > th, .table-2 > tfoot > tr > th, .table-2 > thead > tr > td, .table-2 > tbody > tr > td, .table-2 > tfoot > tr > td{
        padding : 1px !important;
    }
    ul .steps > li {
        cursor: pointer !important;
    }
</style>

<script>

$(document).ready(function() {

  load_billing_data();

  $('#form_billing_kasir').ajaxForm({
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
          // $('#page-area-content').load('billing/Billing/print_preview?no_registrasi='+$('#no_registrasi').val()+'&flag_bill=real');
          load_billing_data();
        //   $('#total_payment').val(jsonResponse.count_um);
          if (jsonResponse.billing_um > 0) {
            PopupCenter('billing/Billing/print_preview?no_registrasi='+$('#no_registrasi').val()+'&flag_bill=real&status_nk=null','Cetak',1200,750);
          }

          if(jsonResponse.kode_perusahaan == 120){
            PopupCenter(jsonResponse.redirect,'SEP',1000,700);
            // window.open(jsonResponse.redirect, '_blank');
          }

          

        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 
  
});

function load_billing_data(){
    $('#billing_data').html(loading);
    $('#billing_data').load('billing/Billing/load_billing_view_apt/<?php echo $kode_trans_far?>/<?php echo $tipe?>');
}

function rollback_kasir(kode_trans_far){
    if(confirm('Are you sure?')){
        $.ajax({
          url: "billing/Billing/rollback_kasir_apt",
          data: {kode_trans_far : kode_trans_far},            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*sukses*/
            load_billing_data();  
          }
        })
    }
}

function getSum(total, num) {
  return total + Math.round(num);
}

function payment(){

    preventDefault();
    // ceklist nk
    var status_nk = $(".table_billing_data input[name=checklist_nk]:checked").map(function(){
        if( $('#selected_bill_'+$(this).val()+'').prop("checked", true) ) {
            return parseInt($('#subtotal_hidden_'+$(this).val()+'').val());
        }
    }).toArray();
    var total_nk = status_nk.reduce(getSum, 0);
    
    var kode_trans_pelayanan_nk = $(".table_billing_data input[name=checklist_nk]:checked").map(function(){
        return $(this).val();
    }).toArray();


    // kode trans pelayanan
    var kode_trans_pelayanan = $(".table_billing_data input[name=selected_bill]:checked").map(function(){
        return $(this).val();
    }).toArray();

    // total billing yang di ceklist
    var billing = $(".table_billing_data input[name=selected_bill]:checked").map(function(){
        return parseInt($('#subtotal_hidden_'+$(this).val()+'').val());
    }).toArray();
    var total = billing.reduce(getSum, 0);

    console.log(billing);

    $('#total_payment').val(total);
    $('#array_data_nk_checked').val(kode_trans_pelayanan_nk);
    $('#array_data_checked').val(kode_trans_pelayanan);
    $('#array_data_billing').val(billing);
    $('#total_nk').val(total_nk);

    $('#billing_data').html(loading);
    getMenuTabs('billing/Billing/payment_apt_view/<?php echo $kode_trans_far?>/<?php echo $tipe?>', 'billing_data');

}

function cetak_kuitansi(){
    PopupCenter('billing/Billing/print_kuitansi?no_registrasi=<?php echo $kode_trans_far?>&payment='+$('#total_payment').val()+'','Cetak Kuitansi', 900, 550);
}

</script>

<form class="form-horizontal" method="post" id="form_billing_kasir" action="<?php echo site_url('billing/Billing/process_apt')?>" enctype="multipart/form-data" >

    <?php echo isset($header)?$header:''?>

    <div class="center no-padding">
    
        <a href="#" class="btn btn-xs btn-purple" onclick="load_billing_data()" > <i class="fa fa-refresh"></i> Reload Billing </a>

        <a href="#" class="btn btn-xs btn-primary" onclick="rollback_kasir(<?php echo $kode_trans_far?>)" > <i class="fa fa-undo"></i> Rollback</a>

        <a href="#" class="btn btn-xs btn-success" onclick="payment()"> <i class="fa fa-money"></i> Lanjutkan Pembayaran  </a>

        <div class="btn-group">
            <button type="button" class="btn btn-xs btn-yellow" onclick="PopupCenter('billing/Billing/print_preview_apt?kode_trans_far=<?php echo $kode_trans_far; ?>','Cetak',600,750);"><i class="fa fa-print"></i> Cetak Billing</button>
        </div>
        
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div id="billing_data" style="width: 100%"></div>
        </div>
    </div>

</form>