<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

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

jQuery(function($) {

    $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: 'yyyy-mm-dd'
    })
    //show datepicker when clicking on the icon
    .next().on(ace.click_event, function(){
    $(this).prev().focus();
    });
});

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
        //   $('#page-area-content').load('billing/Billing/print_preview?no_registrasi='+$('#no_registrasi').val()+'&flag_bill=real');
          PopupCenter('billing/Billing/print_preview?no_registrasi='+$('#no_registrasi').val()+'&flag_bill=real', 'Cetak Billing', 600, 750);
          load_billing_data();
        //   show btn for generate dokumen klaim

        //   $('#total_payment').val(jsonResponse.count_um);
        //   if (jsonResponse.billing_um > 0) {
        //     PopupCenter('billing/Billing/print_preview?no_registrasi='+$('#no_registrasi').val()+'&flag_bill=real&status_nk=&kode_tc_trans_kasir='+jsonResponse.kode_tc_trans_kasir+'','Cetak',600,750);
        //   }

        //   if(jsonResponse.kode_perusahaan == 120){
        //     PopupCenter(jsonResponse.redirect,'SEP',1000,700);
        //   }

        }else{
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }
        achtungHideLoader();
      }
    }); 

    $('#diagnosa_akhir').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getICD10",
              data: 'keyword=' + query,            
              dataType: "json",
              type: "POST",
              success: function (response) {
                result($.map(response, function (item) {
                      return item;
                  }));
                
              }
          });
      },
      afterSelect: function (item) {
        // do what is needed with item
        var label_item=item.split(':')[1];
        var val_item=item.split(':')[0];
        console.log(val_item);
        $('#diagnosa_akhir').val(label_item);
        $('#diagnosa_akhir_hidden').val(val_item);
      }

  });

    $('#perusahaan_penjamin').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getPerusahaan",
                data: { keyword:query },            
                dataType: "json",
                type: "POST",
                success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                }
            });
        },
        afterSelect: function (item) {
            // do what is needed with item
            var val_item=item.split(':')[0];
            var val_label=item.split(':')[1];
            console.log(val_item);
            // save or update kode perusahaan to transaction
            if(confirm('Are you sure?')){
                $('#perusahaan_penjamin').val(val_label);
                $('#kode_perusahaan_val').val(val_item);
                $.ajax({
                    url: "billing/Billing/update_penjamin",
                    data: {no_reg : $('#no_registrasi').val(), kode_perusahaan : val_item},            
                    dataType: "json",
                    type: "POST",
                    success: function (response) {
                        // after submit success
                        alert('Data penjamin berhasil diperbaharui!');
                    }
                })
            }else{
                // not change
                $('#perusahaan_penjamin').val('');
                return false;
            }
        }
    });

  
});

function load_billing_data(){
    $('#billing_data').html(loading);
    $('#btn_lanjutkan_pembayaran').attr('disabled', false);
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
            $('#btn_lanjutkan_pembayaran').attr('disabled', false);
          }
        })
    }
}

function getSum(total, num) {
  return total + Math.round(num);
}

function payment(){

    preventDefault();
    $('#btn_lanjutkan_pembayaran').attr('disabled', true);
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
    $('#btn_lanjutkan_pembayaran').attr('disabled', false);
    $('#billing_data').html(loading);
    getMenuTabs('billing/Billing/payment_um_view/<?php echo $no_registrasi?>/<?php echo $tipe?>', 'billing_data');

}

function cetak_kuitansi(){
    PopupCenter('billing/Billing/print_kuitansi?no_registrasi=<?php echo $no_registrasi?>&payment='+$('#total_payment').val()+'','Cetak Kuitansi', 900, 550);
}

function cetak_kuitansi_pasien(){
    PopupCenter('billing/Billing/print_kuitansi_pasien?no_registrasi=<?php echo $no_registrasi?>&payment='+$('#total_payment').val()+'','Cetak Kuitansi', 900, 550);
}

</script>

<form class="form-horizontal" method="post" id="form_billing_kasir" action="<?php echo site_url('billing/Billing/process')?>" enctype="multipart/form-data" autocomplete="off">

    <?php echo isset($header)?$header:''?>

    <b>TRANSAKSI KASIR</b>
    <div class="form-group">                        
        <label class="control-label col-md-2">Tanggal Transaksi</label>        
        <div class="col-md-2">
            <div class="input-group">
                <input name="tgl_trans_kasir" id="tgl_trans_kasir"  class="form-control date-picker" type="text" value="<?php echo isset($data->reg_data->tgl_jam_keluar)?$this->tanggal->formatDateTimeToSqlDate($data->reg_data->tgl_jam_keluar):$this->tanggal->formatDateTimeToSqlDate($data->reg_data->tgl_jam_masuk);?>">
                <span class="input-group-addon">
                    <i class="ace-icon fa fa-calendar"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-2">No. SEP</label>
        <div class="col-md-2">
        <input name="no_sep_val" id="no_sep_val" value="<?php echo isset($data->reg_data->no_sep)?$data->reg_data->no_sep: ''?>" class="form-control" type="text" style="text-transform: uppercase">
        </div>
        <label class="control-label col-md-1">Tgl Masuk</label>
        <div class="col-md-2">
            <div class="input-group">
            <input class="form-control date-picker" name="tgl_jam_masuk" id="tgl_jam_masuk" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($data->reg_data->tgl_jam_masuk)?$this->tanggal->formatDateTimeToSqlDate($data->reg_data->tgl_jam_masuk): ''?>"/>
            <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
            </span>
            </div>
        </div>

        <label class="control-label col-md-1">Tgl Keluar</label>
        <div class="col-md-2">
            <div class="input-group">
            <input class="form-control date-picker" name="tgl_jam_keluar" id="tgl_jam_keluar" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($data->reg_data->tgl_jam_keluar)?$this->tanggal->formatDateTimeToSqlDate($data->reg_data->tgl_jam_keluar): date('Y-m-d')?>"/>
            <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
            </span>
            </div>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-md-2" for="">Diagnosa <span style="color:red">(*)</span></label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="diagnosa_akhir" id="diagnosa_akhir" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
            <input type="hidden" class="form-control" name="diagnosa_akhir_hidden" id="diagnosa_akhir_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
            <input type="hidden" class="form-control" name="kode_riwayat_hidden" id="kode_riwayat_hidden" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">
        </div>
    </div>

    
    <div class="center no-padding">
    
        <a href="#" class="btn btn-xs btn-purple" onclick="load_billing_data()" id="btn_reload_billing"> <i class="fa fa-refresh"></i> Reload Billing </a>

        <a href="#" class="btn btn-xs btn-danger" onclick="rollback_kasir(<?php echo $no_registrasi?>)" > <i class="fa fa-undo"></i> Rollback Transaksi</a>

        <!-- <a href="#" class="btn btn-xs btn-danger" onclick="add_billing()"> <i class="fa fa-plus"></i> Tambah Billing  </a> -->

        <a href="#" class="btn btn-xs btn-success" onclick="payment()" id="btn_lanjutkan_pembayaran"> <i class="fa fa-money"></i> Proses Pembayaran  </a>

        <div class="btn-group">
            <button type="button" class="btn btn-xs btn-yellow"><i class="fa fa-print"></i> Cetak Billing</button>

            <button data-toggle="dropdown" class="btn btn-xs btn-yellow dropdown-toggle" aria-expanded="false">
                <i class="ace-icon fa fa-angle-down icon-only"></i>
            </button>

            <ul class="dropdown-menu dropdown-yellow">
                <li>
                    <?php
                        echo '<a href="#" onclick="PopupCenter('."'billing/Billing/print_preview?no_registrasi=".$no_registrasi."'".','."'Cetak'".',600,750);">Billing Sementara</a>';
                    ?>
                </li>

                <li>
                    <?php
                        echo '<a href="#" onclick="PopupCenter('."'billing/Billing/print_preview?flag_bill=true&no_registrasi=".$no_registrasi."&status_nk=0'".','."'Cetak'".',600,750);"> Billing Pasien</a>';
                    ?>
                </li>

                <li>
                    <?php
                        echo '<a href="#" onclick="PopupCenter('."'billing/Billing/print_preview?flag_bill=true&no_registrasi=".$no_registrasi."&status_nk=1'".','."'Cetak'".',600,750);"> Billing NK</a>';
                    ?>
                </li>
                <li>
                    <?php
                        echo '<a href="#" onclick="cetak_kuitansi();" data-id="0" id="btn-cetak-kuitansi"> Kuitansi</a>';
                    ?>
                </li>
                </li>
                <li>
                    <?php
                        echo '<a href="#" onclick="cetak_kuitansi_pasien();" data-id="0" id="btn-cetak-kuitansi"> Kuitansi Pasien</a>';
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