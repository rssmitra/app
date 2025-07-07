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



function rollback_kasir(no_reg) {
    // Ambil tanggal transaksi dari input
    var tglTransaksi = $('#tgl_trans_kasir').val();
    var today = new Date();
    var yyyy = today.getFullYear();
    var mm = today.getMonth() + 1; // tanpa leading zero
    var dd = today.getDate(); // tanpa leading zero
    var todayStr = yyyy + '-' + mm + '-' + dd;
    console.log(tglTransaksi);
    console.log(todayStr);
    if (tglTransaksi !== todayStr) {
        // Tanggal transaksi berbeda dengan hari ini, tampilkan modal verifikasi
        $('#modal-approval-kepala-keuangan .modal-title').text('Verifikasi Rollback Transaksi');
        $('#password_user').val('');
        $('#kode_verifikasi').val('');
        // Simpan callback rollback
        $('#modal-approval-kepala-keuangan').data('rollback-no-reg', no_reg);
        $('#modal-approval-kepala-keuangan').data('rollback-mode', true);
        $('#modal-approval-kepala-keuangan').modal('show');
    } else {
        if (confirm('Are you sure?')) {
            $.ajax({
                url: "billing/Billing/rollback_kasir",
                data: { no_reg: no_reg },
                dataType: "json",
                type: "POST",
                success: function (response) {
                    /*sukses*/
                    load_billing_data();
                    $('#btn_lanjutkan_pembayaran').attr('disabled', false);
                }
            });
        }
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


// State to track if receipt has been printed
var kuitansiPrinted = false;
var kuitansiPopup = null;
var kuitansiPopupTimeout = null;

function openKuitansiPopup(url) {
    if (kuitansiPopup && !kuitansiPopup.closed) {
        kuitansiPopup.focus();
        return;
    }
    kuitansiPopup = window.open(url, 'Cetak Kuitansi', 'width=900,height=550');
    if (kuitansiPopupTimeout) clearTimeout(kuitansiPopupTimeout);
    kuitansiPopupTimeout = setTimeout(function() {
        if (kuitansiPopup && !kuitansiPopup.closed) {
            kuitansiPopup.close();
        }
    }, 5 * 60 * 1000); // 5 menit
}

function cetak_kuitansi(count){
    if (count==0) {
        openKuitansiPopup('billing/Billing/print_kuitansi?no_registrasi=<?php echo $no_registrasi?>&payment='+$('#total_payment').val());
        count = count + 1;
        $('#btn-cetak-kuitansi').attr('onclick','cetak_kuitansi('+count+')');
    } else {
        // Show modal for approval
        $('#password_user').val('');
        $('#modal-approval-kepala-keuangan').modal('show');
    }
}

function submitApprovalKepalaKeuangan() {
    var password = $('#password_user').val();
    var kodeVerifikasi = $('#kode_verifikasi').val();
    if (!password) {
        alert('Password harus diisi!');
        $('#password_user').focus();
        return;
    }
    if (!kodeVerifikasi) {
        alert('Kode verifikasi harus diisi!');
        $('#kode_verifikasi').focus();
        return;
    }

    // Cek apakah ini mode rollback
    var isRollback = $('#modal-approval-kepala-keuangan').data('rollback-mode') === true;
    var rollbackNoReg = $('#modal-approval-kepala-keuangan').data('rollback-no-reg');

    $.ajax({
        url: 'billing/Billing/verify_code',
        type: 'POST',
        data: {
            password: password,
            kode_verifikasi: kodeVerifikasi,
            no_registrasi: <?php echo $no_registrasi?>
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 200) {
                $('#modal-approval-kepala-keuangan').modal('hide');
                if (isRollback && rollbackNoReg) {
                    // Jalankan rollback setelah verifikasi sukses
                    $.ajax({
                        url: "billing/Billing/rollback_kasir",
                        data: { no_reg: rollbackNoReg },
                        dataType: "json",
                        type: "POST",
                        success: function (response) {
                            load_billing_data();
                            $('#btn_lanjutkan_pembayaran').attr('disabled', false);
                        }
                    });
                    // Reset mode rollback
                    $('#modal-approval-kepala-keuangan').data('rollback-mode', false);
                    $('#modal-approval-kepala-keuangan').data('rollback-no-reg', '');
                } else {
                    openKuitansiPopup('billing/Billing/print_kuitansi?no_registrasi=<?php echo $no_registrasi?>&payment='+$('#total_payment').val());
                    $('#btn-cetak-kuitansi').attr('onclick','cetak_kuitansi('+response.counter+')');
                    $('#btn-cetak-kuitansi-2').attr('onclick','cetak_kuitansi_pasien('+response.counter+')');
                }
            } else {
                alert(response.message || 'Password atau kode verifikasi salah!');
            }

            $('#kode_verifikasi').val('');
            $('#password_user').val('');
        },
        error: function() {
            alert('Terjadi kesalahan pada server.');
        }
    });
}

function cetak_kuitansi_pasien(count){
    // Optional: implement similar logic if needed
    if (count==0) {
        openKuitansiPopup('billing/Billing/print_kuitansi_pasien?no_registrasi=<?php echo $no_registrasi?>&payment='+$('#total_payment').val());
        count = count + 1;
        $('#btn-cetak-kuitansi-2').attr('onclick','cetak_kuitansi_pasien('+count+')');
    } else {
        // Show modal for approval
        $('#password_user').val('');
        $('#modal-approval-kepala-keuangan').modal('show');
    }
    
    
}


</script>

<script>
$(document).on('mousedown', '#toggle-password, #toggle-password-2', function() {
  $('#password_user').attr('type', 'text');
  $('#icon-eye').removeClass('fa-eye').addClass('fa-eye-slash');
});
$(document).on('mouseup mouseleave', '#toggle-password, #toggle-password-2', function() {
  $('#password_user').attr('type', 'password');
  $('#icon-eye').removeClass('fa-eye-slash').addClass('fa-eye');
});

// Toggle show/hide for kode_verifikasi
$(document).on('mousedown', '#toggle-kode-verifikasi', function() {
  $('#kode_verifikasi').attr('type', 'text');
  $('#icon-eye-verif').removeClass('fa-eye').addClass('fa-eye-slash');
});
$(document).on('mouseup mouseleave', '#toggle-kode-verifikasi', function() {
  $('#kode_verifikasi').attr('type', 'password');
  $('#icon-eye-verif').removeClass('fa-eye-slash').addClass('fa-eye');
});

// Ensure custom backdrop for this modal only
$('#modal-approval-kepala-keuangan').on('show.bs.modal', function () {
  setTimeout(function() {
    $('.modal-backdrop').addClass('approval-kepala-keuangan-backdrop');
  }, 10);
});
$('#modal-approval-kepala-keuangan').on('hidden.bs.modal', function () {
  $('.modal-backdrop').removeClass('approval-kepala-keuangan-backdrop');
});
</script>


<style>
  /* Custom darker modal backdrop */
  .modal-backdrop.approval-kepala-keuangan-backdrop {
    background-color: #222 !important;
    opacity: 0.85 !important;
  }
  /* Custom modal for better appearance */
  #modal-approval-kepala-keuangan .modal-dialog {
    margin-top: 10vh;
    max-width: 400px;
  }
  #modal-approval-kepala-keuangan .modal-content {
    border-radius: 10px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    border: none;
  }
  #modal-approval-kepala-keuangan .modal-header {
    border-bottom: 1px solid #eee;
    background: #f7f7f7;
    border-radius: 10px 10px 0 0;
    padding: 16px 24px 12px 24px;
  }
  #modal-approval-kepala-keuangan .modal-title {
    font-weight: bold;
    font-size: 18px;
  }
  #modal-approval-kepala-keuangan .modal-body {
    padding: 20px 24px 10px 24px;
  }
  #modal-approval-kepala-keuangan .form-group label {
    font-weight: 500;
    margin-bottom: 8px;
  }
  #modal-approval-kepala-keuangan .form-control {
    border-radius: 6px;
    font-size: 12px;
  }
  #modal-approval-kepala-keuangan .modal-footer {
    border-top: 1px solid #eee;
    padding: 12px 24px 16px 24px;
    border-radius: 0 0 10px 10px;
    background: #f7f7f7;
  }
</style>

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
                        $is_print_kuitansi = isset($data->kasir_data[0]->is_print_kuitansi)?$data->kasir_data[0]->is_print_kuitansi:0;
                        echo '<a href="#" onclick="cetak_kuitansi('.$is_print_kuitansi.');" data-id="0" id="btn-cetak-kuitansi"> Kuitansi Transaksi</a>';
                    ?>
                </li>
                <li>
                    <?php
                        $is_print_kuitansi = isset($data->kasir_data[0]->is_print_kuitansi)?$data->kasir_data[0]->is_print_kuitansi:0;
                        echo '<a href="#" onclick="cetak_kuitansi_pasien('.$is_print_kuitansi.');" data-id="0" id="btn-cetak-kuitansi-2"> Kuitansi Pasien Bayar</a>';
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


<div class="modal fade" id="modal-approval-kepala-keuangan" tabindex="-1" role="dialog" aria-labelledby="modalApprovalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalApprovalLabel">Approval Manager</h5>
      </div>
      
        <div class="modal-body">
            <div class="form-group" style="position: relative; margin-bottom: 10px;">
                <label for="password_user">Password User</label>
                <div class="input-group">
                <input type="password" class="form-control" id="password_user" placeholder="Masukkan Password User" autocomplete="off">
                <span class="input-group-addon" id="toggle-password" style="cursor: pointer; background: transparent; border-left: none;">
                    <i class="fa fa-eye" id="icon-eye"></i>
                </span>
                </div>
            </div>
            <div class="form-group" style="position: relative; margin-bottom: 10px;">
                <label for="kode_verifikasi">Kode Verifikasi</label>
                <div class="input-group">
                <input type="password" class="form-control" id="kode_verifikasi" placeholder="Masukkan Kode Verifikasi" autocomplete="off">
                <span class="input-group-addon" id="toggle-kode-verifikasi" style="cursor: pointer; background: transparent; border-left: none;">
                    <i class="fa fa-eye" id="icon-eye-verif"></i>
                </span>
                </div>
            </div>
        </div>

      <div class="modal-footer">
        <button type="button" style="height: 42px !important" class="btn btn-danger" data-dismiss="modal">Batal</button>
        <button type="button" style="height: 42px !important" class="btn btn-primary" onclick="submitApprovalKepalaKeuangan()">Submit</button>
      </div>
    </div>
  </div>
</div>
