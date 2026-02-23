<script>

$(document).ready(function() {

    add_billing();
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
    <div class="col-xs-12">
        <div class="row">
            <div id="form_add_tindakan"></div>
        </div>
        <div class="row">
            <div class="col-md-8">
                
                <div class="col-xs-12 no-padding">
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
                                            <?php endif; ?>


                                            <table class="table_billing_data table-2 table-striped table-bordered" width="100%" style="color: black">
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
                                                    <?php echo number_format(array_sum($sum_array_total[$key.''.$key_s]))?>,-
                                                    <input type="hidden" class="total_per_unit" value="<?php echo array_sum($sum_array[$key.''.$key_s])?>">
                                                </td>
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

            <div class="col-md-4">
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

                        <div class="panel-collapse collapse" id="collapseOneRiwayatKunjungan" aria-expanded="true" style="">
                            <div class="panel-body">
                                <div class="timeline-container timeline-style2">
                                    <div class="timeline-items">
                                        <center><b>Riwayat Kunjungan Pasien</b></center>
                                        <br>
                                        <?php $num_log=1; foreach($log_activity as $row_log) : $num_log++; ?>
                                            <div class="timeline-item clearfix" style="margin-bottom: 2px">
                                                <div class="timeline-info">
                                                    <span class="timeline-date" style="font-size: 11px"><?php echo isset($row_log['tgl_masuk']) ? $this->tanggal->formatDateTimeFormDmy($row_log['tgl_masuk']) :''?></span>
                                                    <i class="timeline-indicator btn btn-info no-hover"></i>
                                                </div>

                                                <div class="widget-box transparent" style="margin-bottom: 0">
                                                    <div class="widget-body" style="padding: 2px 0">
                                                        <div class="widget-main no-padding">
                                                        <?php
                                                            $icon = isset($row_log['tgl_keluar']) ? ($row_log['tgl_keluar'] != '') ? '<i class="fa fa-check green bigger-120"></i>' : '<i class="fa fa-times red bigger-120"></i>' : '<i class="fa fa-times red bigger-120"></i>' ;
                                                            echo ($row_log['nama_bagian'] == 'Farmasi') ? '<i class="fa fa-exclamation-triangle orange bigger-120"></i>' : $icon;?>
                                                            <span class="title" style="padding: 4px 8px; font-size: 12px"><?php echo ucwords($row_log['nama_bagian']); ?></span>
                                                            <?php if(isset($row_log['status_batal']) && $row_log['status_batal'] == 1) : ?>
                                                                <span class="label label-danger" style="margin-left: 3px">Batal</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <br>
                                        <span style="font-style: italic">Diurutkan berdasarkan tanggal registrasi/tanggal masuk</span>
                                        <br>
                                    </div>
                                </div>
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
                            <div class="panel-body">
                                <p style="font-weight: bold; text-align: center; padding: 10px">Grouping Transaksi Berdasarkan Jenisnya</p>
                                <table class="table">
                                    <tr style="background: lightgrey; vertical-align: middle !important">
                                        <th>JENIS TRANSAKSI</th>
                                        <th align="right">SUBTOTAL</th>
                                    </tr>
                                    <?php 
                                        foreach($data->group as $key_group=>$row_group) :
                                            foreach ($row_group as $k_group => $v_group) {
                                                $subtotal_grouping[$key_group][] = $this->Billing->get_total_tagihan($v_group);
                                            }
                                        endforeach;
                                        foreach($data->group as $key_group=>$row_group) :
                                            $total_grouping[] = array_sum($subtotal_grouping[$key_group]);
                                            echo '<tr>';
                                            echo '<td class="left">'.strtoupper($key_group).'</td>';
                                            echo '<td align="right">'.number_format(array_sum($subtotal_grouping[$key_group])).'</td>';
                                            echo '<tr>';
                                        endforeach;
                                    ?>
                                    <tr style="font-weight: bold">
                                        <td>TOTAL</td>
                                        <td align="right"><?php echo number_format(array_sum($total_grouping))?></td>
                                    </tr>
                                </table>
                                
                            </div>
                        </div>
                    </div>

                </div>
                <hr>
                <div class="row">
                    <!-- Riwayat Pembayaran Kasir -->
                    <?php if( $data->kasir_data != NULL ) : // if log transaksi kasir ?>
                    <div class="pull-left" style="padding-left: 5%;">
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
        
        

   
    
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div>