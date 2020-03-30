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
    $('#billing_data').load('billing/Billing/load_billing_view/<?php echo $no_registrasi?>/<?php echo $tipe?>');
}

function rollback_kasir(no_reg){
    if(confirm('Are you sure?')){
        $.ajax({
          url: "billing/Billing/rollback_kasir",
          data: {no_reg : no_reg},            
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
    getMenuTabs('billing/Billing/payment_view/<?php echo $no_registrasi?>/<?php echo $tipe?>', 'billing_data');

}

function pembayaran_um(){

    preventDefault();
    $('#billing_data').html(loading);
    getMenuTabs('billing/Billing/payment_um_view/<?php echo $no_registrasi?>/<?php echo $tipe?>', 'billing_data');

}

function cetak_kuitansi(){
    PopupCenter('billing/Billing/print_kuitansi?no_registrasi=<?php echo $no_registrasi?>&payment='+$('#total_payment').val()+'','Cetak Kuitansi', 900, 550);
}

</script>

<form class="form-horizontal" method="post" id="form_billing_kasir" action="<?php echo site_url('billing/Billing/process')?>" enctype="multipart/form-data" >

    <?php echo isset($header)?$header:''?>

    <div class="center no-padding">
    
        <a href="#" class="btn btn-xs btn-purple" onclick="load_billing_data()" > <i class="fa fa-refresh"></i> Reload Billing </a>

        <a href="#" class="btn btn-xs btn-primary" onclick="rollback_kasir(<?php echo $no_registrasi?>)" > <i class="fa fa-undo"></i> Rollback</a>

        <a href="#" class="btn btn-xs btn-danger" onclick="pembayaran_um()"> <i class="fa fa-credit-card"></i> Pembayaran DP / Bertahap  </a>

        <a href="#" class="btn btn-xs btn-success" onclick="payment()"> <i class="fa fa-money"></i> Lanjutkan Pembayaran  </a>

        <div class="btn-group">
            <button class="btn btn-xs btn-yellow"><i class="fa fa-print"></i> Cetak Billing</button>

            <button data-toggle="dropdown" class="btn btn-xs btn-yellow dropdown-toggle" aria-expanded="false">
                <i class="ace-icon fa fa-angle-down icon-only"></i>
            </button>

            <ul class="dropdown-menu dropdown-yellow">
                <li>
                    <?php
                        echo '<a href="#" onclick="PopupCenter('."'billing/Billing/print_preview?no_registrasi=".$no_registrasi."'".','."'Cetak'".',1200,750);">Billing Sementara</a>';
                    ?>
                </li>

                <li>
                    <?php
                        echo '<a href="#" onclick="PopupCenter('."'billing/Billing/print_preview?flag_bill=true&no_registrasi=".$no_registrasi."&status_nk=0'".','."'Cetak'".',1200,750);"> Billing Pasien</a>';
                    ?>
                </li>

                <li>
                    <?php
                        echo '<a href="#" onclick="PopupCenter('."'billing/Billing/print_preview?flag_bill=true&no_registrasi=".$no_registrasi."&status_nk=1'".','."'Cetak'".',1200,750);"> Billing NK</a>';
                    ?>
                </li>
                <li>
                    <?php
                        echo '<a href="#" onclick="cetak_kuitansi();"> Kuitansi</a>';
                    ?>
                </li>
            </ul>
        </div>
        
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div id="billing_data" style="width: 100%"></div>
        </div>
    </div>

</form>