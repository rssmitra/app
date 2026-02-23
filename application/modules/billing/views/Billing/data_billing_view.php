<script>

$(document).ready(function() {

    add_billing();

    // Format nilai diskon awal (dari DB) pada saat halaman dimuat
    $('.input-diskon').each(function() {
        var id   = $(this).data('id');
        var type = $('#diskon_type_' + id).val() || 'rp';
        if (type === 'rp') {
            var num = unformatRp($(this).val());
            if (num > 0) $(this).val(fmtRp(num));
        }
    });
    $('.input-total-diskon').each(function() {
        var num = unformatRp($(this).val());
        if (num > 0) $(this).val(fmtRp(num));
    });

    recalcGrouping();
    var total_all = sumClass('total_per_unit');
    $('#total_payment').val(total_all);
    $('#total_billing_all').html( '<span style="font-size: 25px; font-weight: bold">'+formatMoney(total_all)+'</span>' );
    $('#total_payment_all').val( total_all );
    // if( $('#kode_perusahaan_val').val() == 0 ) {
    //     $('input[name=checklist_nk]').attr("disabled", true);
    // }else{
    //     $('input[name=checklist_nk]').prop("checked", true);
    // }

});

function add_billing(){
    $('#form_add_tindakan').html(loading);
    $('#form_add_tindakan').load('billing/Billing/add_tindakan/<?php echo $no_registrasi?>/<?php echo $tipe?>');
}

function checkAllItem(elm, kode_bagian) {

    if($(elm).prop("checked") == true){
        $('.checked_'+kode_bagian+'').each(function(){
            var kode = $(this).val();
            $(this).prop("checked", true);
            $('.checklist_nk_'+kode_bagian+'').prop("checked", true);
        });
    }else{
        $('.checked_'+kode_bagian+'').each(function(){
            var kode = $(this).val();
            $(this).prop("checked", false);
            $('.checklist_nk_'+kode_bagian+'').prop("checked", false);
        });
    }

}

function checkOne(kode) {
    
    if($('#selected_bill_'+kode+'').prop("checked") == true){
        $('#selected_nk_'+kode+'').prop("checked", true);
    }else{
        $('#selected_nk_'+kode+'').prop("checked", false);
    }

}

function checkedNk(kode) {
    
    if($('#selected_nk_'+kode+'').prop("checked") == true){
        $('#selected_bill_'+kode+'').prop("checked", true);
    }

}

function delete_transaksi(myid){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan/delete',
        type: "post",
        data: {ID:myid},
        dataType: "json",
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
            load_billing_data();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }

}

// Debounce timers per item (auto-save saat user berhenti mengetik)
var _diskonTimers = {};

// Simpan diskon ke server via AJAX
function saveDiskonItem(id, diskon_rp) {
    $('#diskon_save_' + id).html('<i class="fa fa-spinner fa-spin" style="color:#aaa; font-size:10px;"></i>');
    $.ajax({
        url     : 'billing/Billing/update_diskon_item',
        type    : 'POST',
        data    : { kode_trans_pelayanan: id, diskon_rp: diskon_rp },
        dataType: 'json',
        success : function(res) {
            if (res.status === 200) {
                $('#diskon_save_' + id).html('<i class="fa fa-check" style="color:#27ae60; font-size:10px;" title="Tersimpan"></i>');
                setTimeout(function() { $('#diskon_save_' + id).html(''); }, 2500);
            } else {
                $('#diskon_save_' + id).html('<i class="fa fa-times" style="color:#e74c3c; font-size:10px;" title="' + (res.message || 'Gagal') + '"></i>');
            }
        },
        error   : function() {
            $('#diskon_save_' + id).html('<i class="fa fa-times" style="color:#e74c3c; font-size:10px;" title="Koneksi gagal"></i>');
        }
    });
}

// Toggle mode diskon: Rp <-> %
function toggleDiskonType(btn) {
    var $btn     = $(btn);
    var id       = $btn.data('id');
    var type     = $btn.data('type');        // tipe saat ini
    var $input   = $('#diskon_' + id);
    var subtotal = parseFloat($input.data('subtotal')) || 0;
    // Baca nilai sesuai tipe saat ini (Rp perlu di-unformat dulu)
    var rawVal   = (type === 'rp') ? unformatRp($input.val()) : (parseFloat($input.val()) || 0);
    var newType  = (type === 'rp') ? 'pct' : 'rp';

    // Konversi nilai saat switch tipe
    var converted = 0;
    if (newType === 'pct') {
        // Rp → %
        converted = (subtotal > 0) ? parseFloat(((rawVal / subtotal) * 100).toFixed(2)) : 0;
        if (converted > 100) converted = 100;
        $btn.text('%').css({ background: '#e8f4fd', color: '#2980b9', fontWeight: 'bold' });
        $input.attr('placeholder', '0 – 100');
        $input.val(converted > 0 ? converted : '');
    } else {
        // % → Rp
        converted = Math.round((rawVal / 100) * subtotal);
        $btn.text('Rp').css({ background: '#f0f0f0', color: '#555', fontWeight: 'normal' });
        $input.attr('placeholder', '0');
        $input.val(converted > 0 ? fmtRp(converted) : '');
    }

    $btn.data('type', newType);
    $('#diskon_type_' + id).val(newType);
    hitungDiskon($input[0]);
}

// Hitung diskon dengan memperhatikan mode (Rp atau %)
function hitungDiskon(input) {
    var $input   = $(input);
    var id       = $input.data('id');
    var subtotal = parseFloat($input.data('subtotal')) || 0;
    var type     = $('#diskon_type_' + id).val() || 'rp';

    var rawVal, diskon_rp, diskon_pct;

    if (type === 'pct') {
        // Mode persen: tidak diformat, baca apa adanya
        rawVal = parseFloat($input.val().replace(/[^0-9.]/g, '')) || 0;
        if (rawVal > 100) { rawVal = 100; $input.val(rawVal); }
        diskon_pct = rawVal;
        diskon_rp  = Math.round((rawVal / 100) * subtotal);
    } else {
        // Mode Rp: format real-time dengan titik ribuan
        applyRpFormat(input);
        rawVal = unformatRp($input.val());
        if (rawVal > subtotal) {
            rawVal = subtotal;
            $input.val(fmtRp(rawVal));
        }
        diskon_rp  = rawVal;
        diskon_pct = (subtotal > 0) ? ((rawVal / subtotal) * 100).toFixed(1) : 0;
    }

    var net = subtotal - diskon_rp;

    // Update tampilan net per item
    $('#net_'        + id).text(formatMoney(net) + ',-');
    $('#net_hidden_' + id).val(net);
    // Simpan nilai Rp ke hidden (untuk form submit)
    $('#diskon_rp_'  + id).val(diskon_rp);

    // Info tambahan di bawah input
    if (diskon_rp > 0) {
        var info = (type === 'pct')
            ? 'Rp ' + formatMoney(diskon_rp)
            : '(' + parseFloat(diskon_pct).toFixed(1) + '%)';
        $('#diskon_info_' + id).text(info);
    } else {
        $('#diskon_info_' + id).text('');
    }

    recalcUnitTotal($input.closest('table.table_billing_data'));
    recalcGrouping();

    // Auto-save ke DB dengan debounce 800ms (tunggu user selesai mengetik)
    clearTimeout(_diskonTimers[id]);
    _diskonTimers[id] = setTimeout(function() {
        saveDiskonItem(id, diskon_rp);
    }, 800);
}

function recalcUnitTotal($table) {
    var totalDiskon = 0;
    var totalNet    = 0;

    $table.find('.input-diskon').each(function() {
        var $inp     = $(this);
        var id       = $inp.data('id');
        // Ambil nilai Rp dari hidden (sudah dikonversi oleh hitungDiskon)
        var diskon_rp = parseFloat($('#diskon_rp_' + id).val()) || 0;
        var subtotal  = parseFloat($inp.data('subtotal')) || 0;
        totalDiskon  += diskon_rp;
        // Net unit hanya dari item yang belum dibayar (input tidak disabled)
        if (!$inp.prop('disabled')) {
            totalNet += subtotal - diskon_rp;
        }
    });

    $table.find('.input-total-diskon').val(totalDiskon > 0 ? fmtRp(totalDiskon) : '');
    $table.find('.td_total_net').text(formatMoney(totalNet) + ',-');
    $table.find('.total_per_unit').val(totalNet);

    // Recalculate grand total
    var grandTotal = 0;
    $('.total_per_unit').each(function() { grandTotal += parseFloat($(this).val()) || 0; });
    $('#total_billing_all').html('<span style="font-size:25px; font-weight:bold">' + formatMoney(grandTotal) + '</span>');
    $('#total_payment_all').val(grandTotal);
    $('#total_payment').val(grandTotal);
}

// Distribusikan total diskon unit secara proporsional ke tiap item (top-down)
function applyUnitDiskon(input) {
    var $input    = $(input);
    var nk        = $input.data('nk');
    var $table    = $('#tbl_' + nk);
    // Format real-time untuk input total diskon unit, lalu baca nilainya
    applyRpFormat(input);
    var totalDisk = unformatRp($input.val());

    // Kumpulkan item yang belum dibayar (enabled)
    var items    = [];
    var totalSub = 0;
    $table.find('.input-diskon:not([disabled])').each(function() {
        var sub = parseFloat($(this).data('subtotal')) || 0;
        items.push({ $el: $(this), id: $(this).data('id'), sub: sub });
        totalSub += sub;
    });

    if (items.length === 0) return;

    // Cap diskon tidak melebihi total subtotal item yang bisa diubah
    if (totalDisk > totalSub) { totalDisk = totalSub; $input.val(fmtRp(totalDisk)); }

    // Distribusi proporsional; sisa pembulatan diberikan ke item terakhir
    var distributed = 0;
    for (var i = 0; i < items.length; i++) {
        var item = items[i];
        var itemDisk;
        if (i === items.length - 1) {
            itemDisk = Math.max(0, totalDisk - distributed);   // sisa
        } else {
            itemDisk = (totalSub > 0) ? Math.round((item.sub / totalSub) * totalDisk) : 0;
        }

        var id  = item.id;
        var net = item.sub - itemDisk;
        var pct = (item.sub > 0) ? ((itemDisk / item.sub) * 100).toFixed(1) : 0;

        // Reset mode ke Rp, update input per-item
        $('#diskon_type_' + id).val('rp');
        $('#diskon_type_btn_' + id).text('Rp').css({ background: '#f0f0f0', color: '#555', fontWeight: 'normal' });
        item.$el.val(itemDisk > 0 ? fmtRp(itemDisk) : '').attr('placeholder', '0');

        // Update hidden Rp, tampilan net, dan info persen
        $('#diskon_rp_'   + id).val(itemDisk);
        $('#net_'         + id).text(formatMoney(net) + ',-');
        $('#net_hidden_'  + id).val(net);
        $('#diskon_info_' + id).text(itemDisk > 0 ? '(' + pct + '%)' : '');
        $('#diskon_save_' + id).html('');

        distributed += itemDisk;

        // Auto-save ke DB per item dengan debounce (closure untuk capture nilai)
        clearTimeout(_diskonTimers[id]);
        _diskonTimers[id] = setTimeout((function(cId, cDisk) {
            return function() { saveDiskonItem(cId, cDisk); };
        })(id, itemDisk), 800);
    }

    // Update total net unit dan grand total (tanpa overwrite input yang baru diketik)
    var totalNet = 0;
    $table.find('.input-diskon').each(function() {
        var id_      = $(this).data('id');
        var sub_     = parseFloat($(this).data('subtotal')) || 0;
        var disk_    = parseFloat($('#diskon_rp_' + id_).val()) || 0;
        if (!$(this).prop('disabled')) { totalNet += sub_ - disk_; }
    });
    $table.find('.td_total_net').text(formatMoney(totalNet) + ',-');
    $table.find('.total_per_unit').val(totalNet);

    var grandTotal = 0;
    $('.total_per_unit').each(function() { grandTotal += parseFloat($(this).val()) || 0; });
    $('#total_billing_all').html('<span style="font-size:25px; font-weight:bold">' + formatMoney(grandTotal) + '</span>');
    $('#total_payment_all').val(grandTotal);
    $('#total_payment').val(grandTotal);

    recalcGrouping();
}

// Format angka dengan titik sebagai pemisah ribuan (konsisten dengan number_format PHP)
function fmtRp(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Strip format titik ribuan dan parse ke integer
function unformatRp(str) {
    return parseInt((str + '').replace(/\./g, '').replace(/[^0-9]/g, ''), 10) || 0;
}

// Format Rp real-time pada input sambil menjaga posisi kursor
function applyRpFormat(el) {
    var pos      = el.selectionStart;
    var oldVal   = el.value;
    var digits   = oldVal.replace(/[^0-9]/g, '');
    var num      = parseInt(digits, 10) || 0;
    var newVal   = num > 0 ? fmtRp(num) : '';
    if (oldVal === newVal) return;

    // Hitung berapa digit sebelum kursor pada nilai lama
    var digitsBeforeCursor = oldVal.substring(0, pos).replace(/[^0-9]/g, '').length;
    el.value = newVal;

    // Temukan posisi kursor baru berdasarkan jumlah digit yang sama
    var newPos = newVal.length;
    var count  = 0;
    for (var i = 0; i < newVal.length; i++) {
        if (/[0-9]/.test(newVal[i])) count++;
        if (count === digitsBeforeCursor) { newPos = i + 1; break; }
    }
    el.setSelectionRange(newPos, newPos);
}

// Recalculate billing grouping berdasarkan diskon yang sudah diinput per item
function recalcGrouping() {
    var groupNet = {};
    var groupSub = {};
    var grandNet = 0;
    var grandSub = 0;

    // Kumpulkan net per group dari hidden .grp-item
    $('.grp-item').each(function() {
        var grp  = $(this).data('group');
        var ktp  = $(this).data('ktp');
        var sub  = parseFloat($(this).data('subtotal')) || 0;
        var disk = parseFloat($('#diskon_rp_' + ktp).val()) || 0;
        var net  = sub - disk;

        groupSub[grp] = (groupSub[grp] || 0) + sub;
        groupNet[grp] = (groupNet[grp] || 0) + net;
        grandNet     += net;
        grandSub     += sub;
    });

    // Update tampilan per group
    $.each(groupNet, function(grp, net) {
        var pct  = (grandNet > 0) ? ((net / grandNet) * 100).toFixed(1) : 0;
        var disk = groupSub[grp] - net;

        // Update jumlah net + persentase
        $('#grp_amount_' + grp).html(
            'Rp ' + fmtRp(net) +
            ' &nbsp;<small style="font-weight:400;color:#aaa;font-size:10px;">' + pct + '%</small>'
        );

        // Tampilkan harga coret jika ada diskon
        if (disk > 0) {
            $('#grp_diskon_' + grp).html(
                '<small style="color:#e74c3c;font-size:10px;"><s>Rp ' + fmtRp(groupSub[grp]) + '</s></small>'
            );
        } else {
            $('#grp_diskon_' + grp).html('');
        }

        // Update lebar progress bar
        $('#grp_bar_' + grp).css('width', pct + '%');
    });

    // Update grand total grouping
    var totalDisk = grandSub - grandNet;
    if (totalDisk > 0) {
        $('#grp_grand_total').html(
            'Rp ' + fmtRp(grandNet) +
            '&nbsp;<small style="font-weight:400;color:#e74c3c;font-size:11px;">(-Rp ' + fmtRp(totalDisk) + ' diskon)</small>'
        );
    } else {
        $('#grp_grand_total').html('Rp ' + fmtRp(grandNet));
    }
}

// Tampilkan modal approval untuk mengaktifkan diskon per unit
function showDiskonApproval(nk) {
    $('#password_user').val('');
    $('#kode_verifikasi').val('');
    $('#modal-approval-kepala-keuangan')
        .data('diskon-mode', true)
        .data('diskon-nk', nk)
        .modal('show');
}

</script>

<style>
    .scrolls {
        overflow-x: scroll;
        overflow-y: hidden;
        height: 120px;
        white-space:nowrap
    }
    /* Kompres jarak antar item timeline Riwayat Kunjungan */
    #collapseOneRiwayatKunjungan .timeline-item        { min-height: 28px !important; margin-bottom: 0 !important; }
    #collapseOneRiwayatKunjungan .timeline-info        { min-height: 28px !important; padding-bottom: 0 !important; }
    #collapseOneRiwayatKunjungan .timeline-indicator   { top: 2px !important; }
    #collapseOneRiwayatKunjungan .widget-box           { margin-bottom: 0 !important; }
    #collapseOneRiwayatKunjungan .widget-body          { padding: 1px 0 !important; }
    #collapseOneRiwayatKunjungan .widget-main          { padding: 0 !important; }
</style>
<hr class="separator">

<div class="row">
    <div id="form_add_tindakan"></div>
</div>
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="pull-right" style="margin-bottom: -45px">
            Total billing : <br>
            <span id="total_billing_all"><b>Rp. 0,-</b></span>
        </div>
        <?php foreach($kunjungan as $key=>$row_dt_kunj) :?>
        
        <div class="timeline-container timeline-style2" style="z-index: 1;">
            <span class="timeline-label" style="width:210px !important">
                <b><span style="font-size: 14px"><?php echo ucwords($key)?></span></b>
            </span>

            <div class="timeline-items">
                <?php 
                    foreach($row_dt_kunj as $key_s=>$row_s) : 
                        // echo "<pre>"; print_r($row_s); echo "</pre>";
                ?>
                    
                <div class="timeline-item clearfix">
                    <div class="timeline-info">

                        <span class="timeline-date"> 
                            <?php echo ($row_s[0]->tgl_keluar==NULL) ? '<i class="fa fa-times-circle bigger-120 red"></i>' : '<i class="fa fa-check bigger-120 green"></i>' ;?> <?php echo $this->tanggal->formatDateTimeToTime($row_s[0]->tgl_masuk)?> s/d <?php echo $this->tanggal->formatDateTimeToTime($row_s[0]->tgl_keluar)?></span>

                        <i class="timeline-indicator btn btn-info no-hover"></i>
                    </div>

                    <div class="widget-box transparent">
                        <div class="widget-body">
                            <div class="widget-main no-padding">
                                <?php echo '<span style="font-size: 14px; font-weight: bold">'.ucwords($key_s).'</span> ';?>
                                
                                <?php if(substr($row_s[0]->kode_bagian, 0, 2) == '05') : ?>
                                    <a href="#" class="label label-success" style="margin: 3px" onclick="PopupCenter('pelayanan/Pl_pelayanan_pm/preview_pengantar_penunjang/<?php echo $row_s[0]->no_kunjungan?>?kode_penunjang=<?php echo $row_s[0]->kode_penunjang?>&type=PM&kode_bagian=<?php echo $row_s[0]->kode_bagian?>&kode_bag_asal=<?php echo $row_s[0]->kode_bagian_asal?>&no_mr=<?php echo $row_s[0]->no_mr?>&klas=<?php echo $row_s[0]->kode_klas?>', 'change_form_pengantar_pm')">Surat Pengantar</a>
                                <?php endif; ?>

                                <!-- tambahkan label status batal -->
                                <?php if($row_s[0]->status_batal == 1) : ?>
                                    <span class="label label-danger" style="margin: 3px">Batal</span>
                                <?php else : ?>
                                    <button type="button"
                                            id="btn_diskon_<?php echo $row_s[0]->no_kunjungan . '_' . $row_s[0]->kode_bagian?>"
                                            class="btn btn-xs btn-warning btn-tambah-diskon"
                                            style="margin: 3px; float: right"
                                            onclick="showDiskonApproval('<?php echo $row_s[0]->no_kunjungan . '_' . $row_s[0]->kode_bagian?>')">
                                        <i class="fa fa-tag"></i> Tambahkan Diskon
                                    </button>
                                <?php endif; ?>


                                <table id="tbl_<?php echo $row_s[0]->no_kunjungan . '_' . $row_s[0]->kode_bagian?>" class="table_billing_data table-2 table-striped table-bordered" width="100%" style="color: black">
                                    <tr style="background-color: lightgrey;">
                                        <th class="center" width="50px">
                                            <label>
                                                <input type="checkbox" class="ace" checked onClick="checkAllItem(this, '<?php echo $row_s[0]->kode_bagian ?>');" value="<?php echo $row_s[0]->kode_bagian ?>" id="<?php echo $row_s[0]->kode_bagian ?>" <?php echo ($row_s[0]->status_batal == 1) ? 'disabled' : ''; ?>>
                                                <span class="lbl"></span>
                                            </label>
                                        </th>
                                        <!-- <th width="70px"> Kode</th> -->
                                        <th> Deskripsi Item / Nama Tindakan</th>
                                        <!-- <th>Penjamin</th> -->
                                        <!-- <th class="center" width="200px">Dokter</th> -->
                                        <th class="center" width="50px">NK</th>
                                        <!-- <th align="right" width="100px">Bill RS</th>
                                        <th align="right" width="100px">Bill dr1</th>
                                        <th align="right" width="100px">Bill dr2</th>
                                        <th align="right" width="100px">Bill dr3</th> -->
                                        <th class="center" width="100px">Subtotal (Rp.)</th>
                                        <th class="center" width="130px">Diskon (Rp.)</th>
                                        <th class="center" width="100px">Net (Rp.)</th>
                                        <th class="center" width="70px">Action</th>
                                    </tr>

                                    <!-- FOREACH -->
                                <?php 
                                    $sum_array[$key.''.$key_s] = array();
                                    
                                    foreach( $row_s as $value_data ) : 
                                ?>
                                    <!-- filter untuk BPAKO -->
                                    
                                    <?php 
                                        $subtotal = $this->Billing->get_total_tagihan($value_data);
                                        $sign_pay = ($value_data->kode_tc_trans_kasir==NULL)?'#d3d3d32b':'#87b87f45';

                                        $penjamin = $this->master->custom_selection($params = array('table' => 'mt_perusahaan', 'id' => 'kode_perusahaan', 'name' => 'nama_perusahaan', 'where' => array() ), $value_data->kode_perusahaan , 'penjamin[]', 'penjamin_val_'.$value_data->kode_trans_pelayanan.'', '', '', ' style="width: 150px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 100% border-bottom: 1px #ccc solid; margin: 0px 1px !important; display: none"').'<span id="penjamin_txt_'.$value_data->kode_trans_pelayanan.'">'.$value_data->nama_perusahaan.'</span>';

                                        $dokter = $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array() ), $value_data->kode_dokter1 , 'kode_dokter[]', 'kode_dokter_val_'.$value_data->kode_trans_pelayanan.'', '', '', ' style="width: 150px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 100% border-bottom: 1px #ccc solid;margin: 0px 1px !important; display: none"').'<span id="kode_dokter_txt_'.$value_data->kode_trans_pelayanan.'">'.$value_data->nama_dokter.'</span>';

                                        /*bill rs*/
                                        $bill_rs = '<input type="text" name="bill_rs[]" value="'.$value_data->bill_rs_int.'" style="width:80px;text-align:right;margin: 0px 1px !important; display: none" id="bill_rs_val_'.$value_data->kode_trans_pelayanan.'" class="input-bill" data-id="'.$value_data->kode_trans_pelayanan.'">'.'<span id="bill_rs_txt_'.$value_data->kode_trans_pelayanan.'">'.number_format($value_data->bill_rs_int).',-</span>';

                                        /*bill dr1*/
                                        $bill_dr1 = '<input type="text" name="bill_dr1[]" value="'.$value_data->bill_dr1_int.'" style="width:80px;text-align:right;margin: 0px 1px !important; display: none" id="bill_dr1_val_'.$value_data->kode_trans_pelayanan.'" class="input-bill" data-id="'.$value_data->kode_trans_pelayanan.'">'.'<span id="bill_dr1_txt_'.$value_data->kode_trans_pelayanan.'">'.number_format($value_data->bill_dr1_int).',-</span>';

                                        /*bill dr2*/
                                        $bill_dr2 = '<input type="text" name="bill_dr2[]" value="'.$value_data->bill_dr2_int.'" style="width:80px;text-align:right;margin: 0px 1px !important; display: none" id="bill_dr2_val_'.$value_data->kode_trans_pelayanan.'" class="input-bill" data-id="'.$value_data->kode_trans_pelayanan.'">'.'<span id="bill_dr2_txt_'.$value_data->kode_trans_pelayanan.'">'.number_format($value_data->bill_dr2_int).',-</span>';

                                        /*bill dr3*/
                                        $bill_dr3 = '<input type="text" name="bill_dr3[]" value="'.$value_data->bill_dr3_int.'" style="width:80px;text-align:right;margin: 0px 1px !important; display: none" id="bill_dr3_val_'.$value_data->kode_trans_pelayanan.'" class="input-bill" data-id="'.$value_data->kode_trans_pelayanan.'">'.'<span id="bill_dr3_txt_'.$value_data->kode_trans_pelayanan.'">'.number_format($value_data->bill_dr3_int).',-</span>';


                                    ?>
                                    <tr id="tr_<?php echo $value_data->kode_trans_pelayanan?>" style="background-color:<?php echo $sign_pay?>">
                                    <td align="center">
                                        <?php
                                            if($value_data->kode_tc_trans_kasir==NULL){

                                                if( $row_s[0]->tgl_keluar == NULL ){

                                                    if($value_data->kode_perusahaan != 120){
                                                        $disabled_bill = ($row_s[0]->status_batal == 1) ? 'disabled' : '';
                                                        echo '<label>
                                                                <input name="selected_bill" id="selected_bill_'.$value_data->kode_trans_pelayanan.'" value="'.$value_data->kode_trans_pelayanan.'" checked type="checkbox" class="checked_'.$row_s[0]->kode_bagian.' ace" onclick="checkOne('.$value_data->kode_trans_pelayanan.')" '.$disabled_bill.'>
                                                                <span class="lbl"></span>
                                                            </label>';
                                                    }else{
                                                        echo '<i class="fa fa-times-circle red bigger-120"></i>';
                                                    }

                                                }else{
                                                    $disabled_bill = ($row_s[0]->status_batal == 1) ? 'disabled' : '';
                                                    echo '<label>
                                                        <input name="selected_bill" id="selected_bill_'.$value_data->kode_trans_pelayanan.'" value="'.$value_data->kode_trans_pelayanan.'" checked type="checkbox" class="checked_'.$row_s[0]->kode_bagian.' ace" onclick="checkOne('.$value_data->kode_trans_pelayanan.')" '.$disabled_bill.'>
                                                        <span class="lbl"></span>
                                                    </label>';
                                                }
                                                
                                            }else{
                                                echo '<i class="fa fa-check green bigger-120"></i>';
                                            }
                                        ?>
                                    </td>
                                    
                                    <!-- <td align="left"><?php echo $value_data->kode_trans_pelayanan; ?></td> -->

                                    <td>
                                        <!-- hidden form -->
                                        <input type="hidden" name="kode_trans_pelayanan[]" id="<?php echo $value_data->kode_trans_pelayanan?>" value="<?php echo $value_data->kode_trans_pelayanan?>">
                                        <input type="hidden" name="delete_row_val[]" id="delete_row_val_<?php echo $value_data->kode_trans_pelayanan?>" value="0">
                                        <?php 
                                            $txt_dokter = ($value_data->kode_dokter1 != '') ? '<br>('.$dokter.')':'';
                                            echo $value_data->nama_tindakan. '' .$txt_dokter;?>
                                        <?php
                                            echo ($value_data->jenis_tindakan == 9) ? '<span style="color: green; font-weight: bold; background: yellow">BPAKO</span>' : '';
                                        ?>
                                    </td>
                                    
                                    <!-- <td>
                                        <?php 
                                            echo ($value_data->jenis_tindakan==1) ? ''.$this->tanggal->formatDate($value_data->tgl_transaksi).'<br>' :'';
                                            echo ucwords($value_data->nama_bagian);
                                        ?>
                                    </td> -->
                                    <!-- <td id="dokter_<?php echo $value_data->kode_trans_pelayanan?>">
                                        <?php echo $dokter?>
                                    </td> -->
                                    <!-- <td id="penjamin_<?php echo $value_data->kode_trans_pelayanan?>">
                                        <?php echo $penjamin?>
                                    </td> -->
                                    <td align="center">

                                        <?php
                                            if($value_data->kode_tc_trans_kasir==NULL){
                                                if($value_data->kode_perusahaan == 120){
                                                    $checked = 'checked';
                                                }else{
                                                    $checked = ( $value_data->status_nk ==  1 ) ? 'checked' : '';
                                                }
                                                    
                                                $disabled_nk = ($row_s[0]->status_batal == 1) ? 'disabled' : '';
                                                if( $row_s[0]->tgl_keluar == NULL ){
                                                    if($value_data->kode_perusahaan != 120){
                                                        echo '<label>
                                                            <input name="checklist_nk" id="selected_nk_'.$value_data->kode_trans_pelayanan.'" value="'.$value_data->kode_trans_pelayanan.'" type="checkbox" class="checklist_nk_'.$row_s[0]->kode_bagian.' ace" '.$checked.' onclick="checkedNk('.$value_data->kode_trans_pelayanan.')" '.$disabled_nk.'>
                                                            <span class="lbl"></span>
                                                        </label>';
                                                    }else{
                                                        echo '<i class="fa fa-times-circle red bigger-120"></i>';
                                                    }
                                                }else{
                                                    echo '<label>
                                                            <input name="checklist_nk" id="selected_nk_'.$value_data->kode_trans_pelayanan.'" value="'.$value_data->kode_trans_pelayanan.'" type="checkbox" class="checklist_nk_'.$row_s[0]->kode_bagian.' ace" '.$checked.' onclick="checkedNk('.$value_data->kode_trans_pelayanan.')" '.$disabled_nk.'>
                                                            <span class="lbl"></span>
                                                        </label>';
                                                }

                                            }else{
                                                $label_nk = ( $value_data->status_nk ==  1 ) ? '<i class="fa fa-check green bigger-120"></i>' : '';

                                                echo $label_nk;
                                            }
                                        ?>

                                    </td>
                                    
                                    <!-- <td id="bill_rs_<?php echo $value_data->kode_trans_pelayanan?>" align="right">
                                        <?php echo $bill_rs?>
                                    </td>
                                    <td id="bill_dr1_<?php echo $value_data->kode_trans_pelayanan?>" align="right">
                                        <?php echo $bill_dr1?>
                                    </td>
                                    <td id="bill_dr1_<?php echo $value_data->kode_trans_pelayanan?>" align="right">
                                        <?php echo $bill_dr2?>
                                    </td>
                                    <td id="bill_dr1_<?php echo $value_data->kode_trans_pelayanan?>" align="right">
                                        <?php echo $bill_dr2?>
                                    </td> -->

                                    <td width="80px" align="right">
                                        <span id="subtotal_<?php echo $value_data->kode_trans_pelayanan?>"><?php echo number_format($subtotal)?>,-</span>
                                        <input type="hidden" name="" id="subtotal_hidden_<?php echo $value_data->kode_trans_pelayanan?>" class="class_subtotal" value="<?php echo $subtotal?>">
                                    </td>

                                    <?php
                                        $diskon_val  = isset($value_data->diskon) ? (int)$value_data->diskon : 0;
                                        $is_paid     = ($value_data->kode_tc_trans_kasir != NULL);
                                        $dis_attr    = 'disabled';  // always disabled — approval required
                                        $lock_attr   = $is_paid ? '' : 'data-lock="approval"';  // unpaid = lockable
                                        $ktp         = $value_data->kode_trans_pelayanan;
                                    ?>
                                    <td align="center" width="150px">
                                        <!-- Input + Toggle Rp/% -->
                                        <div style="display:inline-flex; align-items:center;">
                                            <input type="text"
                                                   id="diskon_<?php echo $ktp?>"
                                                   class="input-diskon"
                                                   value="<?php echo $diskon_val?>"
                                                   data-subtotal="<?php echo $subtotal?>"
                                                   data-id="<?php echo $ktp?>"
                                                   placeholder="0"
                                                   style="width:58px; text-align:right; border:1px solid #ccc; border-radius:3px 0 0 3px; padding:2px 4px; font-size:11px;"
                                                   oninput="hitungDiskon(this)"
                                                   <?php echo $dis_attr?> <?php echo $lock_attr?>>
                                            <button type="button"
                                                    id="diskon_type_btn_<?php echo $ktp?>"
                                                    data-id="<?php echo $ktp?>"
                                                    data-type="rp"
                                                    onclick="toggleDiskonType(this)"
                                                    style="border:1px solid #ccc; border-left:none; border-radius:0 3px 3px 0; padding:2px 7px; font-size:11px; background:#f0f0f0; color:#555; cursor:pointer; white-space:nowrap;"
                                                    <?php echo $dis_attr?> <?php echo $lock_attr?>>Rp</button>
                                        </div>
                                        <!-- Hidden: Rp nominal (selalu) untuk form submit -->
                                        <input type="hidden" name="diskon[]"      id="diskon_rp_<?php echo $ktp?>"   value="<?php echo $diskon_val?>">
                                        <!-- Hidden: tipe diskon -->
                                        <input type="hidden" name="diskon_type[]" id="diskon_type_<?php echo $ktp?>" value="rp">
                                        <br>
                                        <span style="display:inline-flex; align-items:center; gap:4px; font-size:10px; margin-top:1px;">
                                            <small id="diskon_info_<?php echo $ktp?>" style="color:#aaa;"></small>
                                            <span  id="diskon_save_<?php echo $ktp?>"></span>
                                        </span>
                                    </td>

                                    <?php $net_val = $subtotal - $diskon_val; ?>
                                    <td align="right" width="100px">
                                        <span id="net_<?php echo $value_data->kode_trans_pelayanan?>"><?php echo number_format($net_val)?>,-</span>
                                        <input type="hidden" id="net_hidden_<?php echo $value_data->kode_trans_pelayanan?>" class="class_net" value="<?php echo $net_val?>">
                                    </td>

                                    <td align="center">
                                        <?php
                                            if($value_data->kode_tc_trans_kasir==NULL) :
                                            if($value_data->is_update_by_kasir == 1 || $this->authuser->is_administrator($this->session->userdata('user')->user_id) == true) :
                                        ?>

                                        <span style="cursor: pointer" onclick="delete_transaksi(<?php echo $value_data->kode_trans_pelayanan?>)"><i class="fa fa-times-circle bigger-150 red"></i></span>

                                        <?php else: echo "-"; endif; endif; ?>
                                    </td>

                                </tr>
                                <?php 
                                    if($value_data->kode_tc_trans_kasir == NULL){
                                        $sum_array[$key.''.$key_s][] = $subtotal;
                                    }
                                    $sum_array_total[$key.''.$key_s][] = $subtotal;
                                    endforeach; 
                                ?>

                                <!-- END FOREACH -->

                                <tr style="font-weight: bold; font-size: 13px">
                                    <td colspan="3" align="right">Total</td>
                                    <td align="right">
                                        <span class="td_total_subtotal"><?php echo number_format(array_sum($sum_array_total[$key.''.$key_s]))?>,-</span>
                                        <input type="hidden" class="total_per_unit" value="<?php echo array_sum($sum_array[$key.''.$key_s])?>">
                                    </td>
                                    <td align="center" style="vertical-align:middle;">
                                        <input type="text"
                                               class="td_total_diskon input-total-diskon"
                                               data-nk="<?php echo $row_s[0]->no_kunjungan . '_' . $row_s[0]->kode_bagian?>"
                                               placeholder="0"
                                               style="width:90px; text-align:right; border:1px solid #e74c3c88; border-radius:3px; padding:2px 5px; font-size:11px; color:#e74c3c; font-weight:bold;"
                                               disabled data-lock="approval"
                                               oninput="applyUnitDiskon(this)">
                                        <br><small style="font-weight:normal; color:#999; font-size:10px; white-space:nowrap;">Total Diskon Unit</small>
                                    </td>
                                    <td align="right" style="color:#27ae60;">
                                        <span class="td_total_net"><?php echo number_format(array_sum($sum_array[$key.''.$key_s]))?>,-</span>
                                    </td>
                                    <td></td>
                                </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <?php 
                        
                endforeach;
                ?>

                

            </div><!-- /.timeline-items -->
        </div>
        <?php endforeach?>
        <br>
        <p>
            Keterangan : <br>
            <i class="fa fa-times-circle red"></i> &nbsp; Belum selesai pelayanan<br><i class="fa fa-check green"></i> Sudah selesai<br><br>
            <b>NK (Nota Kredit)</b> adalah Biaya yang dibebankan ke perusahaan penjamin, jika biaya dibebankan kepada pasien, maka petugas harus melakukan unceklis pada item yang dibebankan ke pasien.
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="accordion" class="accordion-style1 panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOneRiwayatKunjungan" aria-expanded="true">
                            <i class="bigger-110 ace-icon fa fa-angle-down" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                            &nbsp;RIWAYAT KUNJUNGAN
                        </a>
                    </h4>
                </div>

                <div class="panel-collapse collapse in" id="collapseOneRiwayatKunjungan" aria-expanded="true" style="">
                    <div class="panel-body" style="padding: 10px">
                        <p style="font-weight:bold; margin-bottom:8px; font-size:13px">
                            <i class="fa fa-history"></i> Riwayat Kunjungan Pasien
                        </p>
                        <ul style="list-style:none; padding:0; margin:0;">
                            <?php 
                                $num_log=1; 
                                foreach($log_activity as $row_log) : 
                                // echo "<pre>"; print_r($row_log); echo "</pre>";
                                $num_log++;
                                $is_last   = ($num_log > count($log_activity));
                                $is_batal  = isset($row_log['status_batal']) && $row_log['status_batal'] == 1;
                                $is_farmasi= ($row_log['nama_bagian'] == 'Farmasi');
                                $has_keluar= isset($row_log['tgl_keluar']) && $row_log['tgl_keluar'] != '';

                                if ($is_batal) {
                                    $dot_color  = '#e74c3c';
                                    $icon_html  = '<i class="fa fa-ban" style="color:#e74c3c"></i>';
                                } elseif ($is_farmasi) {
                                    if(isset($row_log['kode_trans_far']) && $row_log['kode_trans_far'] != ''){
                                        $dot_color  = '#27ae60';
                                        $icon_html  = '<i class="fa fa-check-circle" style="color:#27ae60"></i>';
                                    }else{
                                        $dot_color  = '#e67e22';
                                        $icon_html  = '<i class="fa fa-exclamation-triangle" style="color:#e67e22"></i>';
                                    }
                                } elseif ($has_keluar) {
                                    $dot_color  = '#27ae60';
                                    $icon_html  = '<i class="fa fa-check-circle" style="color:#27ae60"></i>';
                                } else {
                                    $dot_color  = '#e74c3c';
                                    $icon_html  = '<i class="fa fa-times-circle" style="color:#e74c3c"></i>';
                                }
                            ?>
                            <li style="display:flex; align-items:flex-start; margin-bottom:0; position:relative;">
                                <!-- garis vertikal -->
                                <?php if (!$is_last) : ?>
                                <div style="position:absolute; left:6px; top:18px; bottom:0; width:2px; background:#ddd; z-index:0;"></div>
                                <?php endif; ?>

                                <!-- dot -->
                                <div style="flex-shrink:0; width:14px; height:14px; border-radius:50%; background:<?php echo $dot_color?>; margin-top:4px; margin-right:8px; position:relative; z-index:1; border:2px solid #fff; box-shadow:0 0 0 1px <?php echo $dot_color?>;"></div>

                                <!-- konten -->
                                <div style="flex:1; padding-bottom:8px; min-width:0;">
                                    <div style="font-size:11px; color:#888; line-height:1.3;">
                                        <?php echo isset($row_log['tgl_masuk']) ? $this->tanggal->formatDateTimeFormDmy($row_log['tgl_masuk']) : ''; ?>
                                        <?php
                                            if (isset($row_log['tgl_keluar'])) {
                                                $tgl_keluar_str = $row_log['tgl_keluar'];
                                                $jam_keluar = date('H:i:s', strtotime($tgl_keluar_str));
                                                if ($jam_keluar === '00:00:00') {
                                                    echo ' - ' . date('d/m/Y', strtotime($tgl_keluar_str));
                                                } else {
                                                    echo ' - ' . $this->tanggal->formatDateTimeFormDmy($tgl_keluar_str);
                                                }
                                            }else{
                                                echo ' - (belum selesai)';
                                            }
                                        ?>
                                    </div>
                                    <div style="font-size:12px; line-height:1.4; display:flex; align-items:center; gap:4px; flex-wrap:wrap;">
                                        <?php echo $icon_html; ?>
                                        <span style="font-weight:600"><?php echo ucwords($row_log['nama_bagian']); ?></span>
                                        <?php if ($is_batal) : ?>
                                            <span class="label label-danger" style="font-size:10px; padding:1px 5px;">Batal</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <p style="font-size:11px; color:#999; font-style:italic; margin-top:4px;">
                            Diurutkan berdasarkan tanggal registrasi/tanggal masuk
                        </p>
                        <?php
                            // Bangun daftar kunjungan yang belum selesai (untuk validasi JS)
                            $kunjungan_belum_selesai = array();
                            foreach ($log_activity as $row_log) {
                                $lb_batal   = isset($row_log['status_batal']) && $row_log['status_batal'] == 1;
                                if ($lb_batal) continue;
                                $lb_farmasi = ($row_log['nama_bagian'] == 'Farmasi');
                                $lb_keluar  = isset($row_log['tgl_keluar']) && $row_log['tgl_keluar'] != '';
                                $lb_far_ok  = isset($row_log['kode_trans_far']) && $row_log['kode_trans_far'] != '';
                                $lb_selesai = $lb_farmasi ? $lb_far_ok : $lb_keluar;
                                if (!$lb_selesai) {
                                    $kunjungan_belum_selesai[] = array(
                                        'nama_bagian' => ucwords($row_log['nama_bagian']),
                                        'tgl_masuk'   => isset($row_log['tgl_masuk']) ? $row_log['tgl_masuk'] : '',
                                    );
                                }
                            }
                        ?>
                        <script>
                            window._kunjunganBelumSelesai = <?php echo json_encode($kunjungan_belum_selesai); ?>;
                        </script>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOneBillingGrouping" aria-expanded="true">
                            <i class="bigger-110 ace-icon fa fa-angle-down" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                            &nbsp;BILLING GROUPING
                        </a>
                    </h4>
                </div>

                <div class="panel-collapse collapse" id="collapseOneBillingGrouping" aria-expanded="true" style="">
                    <div class="panel-body" style="padding:12px 16px;">
                        <?php
                            // Hitung subtotal per group
                            $subtotal_grouping = array();
                            $total_grouping    = array();
                            foreach ($data->group as $key_group => $row_group) {
                                foreach ($row_group as $k_group => $v_group) {
                                    $subtotal_grouping[$key_group][] = $this->Billing->get_total_tagihan($v_group);
                                }
                            }
                            foreach ($data->group as $key_group => $row_group) {
                                $total_grouping[$key_group] = array_sum($subtotal_grouping[$key_group]);
                            }
                            $grand_total = array_sum($total_grouping);

                            // Palet warna untuk tiap group
                            $palette = array('#3498db','#27ae60','#e67e22','#9b59b6','#1abc9c','#e74c3c','#f39c12','#16a085');
                            $ci = 0;
                        ?>

                        <!-- Group items dengan progress bar -->
                        <div style="margin-bottom:10px;">
                        <?php foreach ($data->group as $key_group => $row_group) :
                            $subtotal = $total_grouping[$key_group];
                            $pct      = ($grand_total > 0) ? round(($subtotal / $grand_total) * 100, 1) : 0;
                            $color    = $palette[$ci % count($palette)];
                            $ci++;
                        ?>
                            <div style="margin-bottom:10px;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                                    <span style="font-size:12px; font-weight:600; color:#444;">
                                        <span style="display:inline-block; width:10px; height:10px; border-radius:2px; background:<?php echo $color?>; margin-right:5px; vertical-align:middle;"></span>
                                        <?php echo strtoupper($key_group); ?>
                                    </span>
                                    <span style="font-size:12px; font-weight:700; color:#333;">
                                        Rp <?php echo number_format($subtotal, 0, ',', '.'); ?>
                                        &nbsp;<small style="font-weight:400; color:#aaa; font-size:10px;"><?php echo $pct; ?>%</small>
                                    </span>
                                </div>
                                <div style="height:5px; background:#f0f0f0; border-radius:3px; overflow:hidden;">
                                    <div style="height:100%; width:<?php echo $pct; ?>%; background:<?php echo $color?>; border-radius:3px;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>

                        <!-- Total -->
                        <div style="border-top:2px solid #e8e8e8; padding-top:8px; display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-size:12px; font-weight:700; color:#555;">
                                <i class="fa fa-calculator" style="margin-right:4px;"></i> TOTAL TAGIHAN
                            </span>
                            <span style="font-size:14px; font-weight:700; color:#27ae60;">
                                Rp <?php echo number_format($grand_total, 0, ',', '.'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <hr>
        <div class="col-md-12">
            <!-- Riwayat Pembayaran Kasir -->
            <?php if( $data->kasir_data != NULL ) : // if log transaksi kasir ?>
            <div class="pull-left">
                <?php echo '<span style="font-weight: bold;">Riwayat Pembayaran Kasir</span>' ?>
                <table class="table-bordered table-hover" style="color: black; margin-top: 3px; max-width: 80%">
                <thead>
                    <tr>
                    <?php 
                        $var_no = 0;
                        foreach($data->kasir_data as $row_kasir_data) : $var_no++;
                    ?>
                    <td class="center" colspan="2" style="padding: 0px 10px !important; text-align: center; font-size: 20px; line-height: 15px; padding-bottom: 5px !important;" width="200px">
                    <br>
                        <a href="#" onclick="PopupCenter('billing/Billing/print_preview?flag_bill=true&no_registrasi=<?php echo $row_kasir_data->no_registrasi; ?>&kode_tc_trans_kasir=<?php echo $row_kasir_data->kode_tc_trans_kasir; ?>', 'Cetak Billing' , 600 , 750);"> <b>Rp <?php echo number_format($row_kasir_data->bill);?>,-</b> </a>
                        <br>
                        <span style="font-size: 11px;"><?php echo $this->tanggal->formatDateTime($row_kasir_data->tgl_jam); ?>
                        <br>
                        <?php echo $row_kasir_data->kode_tc_trans_kasir.' - '.$row_kasir_data->fullname;?></span>
                    </td>
                    <?php endforeach; // end foreach row_kasir_data header ?>
                    </tr>
                </thead>
                <tbody>
                    <tr class="center">
                    <?php 
                        $var_no = 0;
                        foreach($data->kasir_data as $row_kasir_data) : $var_no++;
                    ?>
                    <td style="width: 125px">
                        <a href="#" class="label label-block label-primary" style="width: 100% !important;" onclick="PopupCenter('billing/Billing/print_preview?flag_bill=true&no_registrasi=<?php echo $row_kasir_data->no_registrasi; ?>&kode_tc_trans_kasir=<?php echo $row_kasir_data->kode_tc_trans_kasir; ?>&status_nk=1', 'Cetak Billing' , 600 , 750);">Bill NK</a>
                    </td>
                    <td style="width: 125px">
                        <a href="#" class="label label-block label-primary" style="width: 100% !important;" onclick="PopupCenter('billing/Billing/print_preview?flag_bill=true&no_registrasi=<?php echo $row_kasir_data->no_registrasi; ?>&kode_tc_trans_kasir=<?php echo $row_kasir_data->kode_tc_trans_kasir; ?>&status_nk=', 'Cetak Billing' , 600 , 750);">Bill Pasien</a>
                    </td>
                    <?php endforeach; // end foreach row_kasir_data actions ?>
                    </tr>
                </tbody>
                </table>
            </div> <!-- end Riwayat Pembayaran Kasir -->
            <?php endif; //endif log transaksi ?>
            <br>
        </div>
    </div>
</div>
        