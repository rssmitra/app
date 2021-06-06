<script>

$(document).ready(function() {

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


</script>
<hr class="separator">
<div class="row">
    <div class="col-xs-12">
        <div class="row">
            <div class="pull-left col-xs-12">
                <div id="fuelux-wizard-container" class="no-steps-container">
                    <div>
                        <span style="font-weight: bold; padding-left: 100px">Log Aktifitas Kunjungan</span>
                        <ul class="steps" style="margin-left: 0">
                            <?php $num_log=1; foreach($log_activity as $row_log) : $num_log++; ?>
                            <li data-step="<?php echo $num_log?>" <?php echo isset($row_log['tgl_keluar']) ? (!empty($row_log['tgl_keluar'])) ? 'class="active"' : '' : 'class="active"';?> >
                                <span class="step">
                                    <?php 
                                        $icon = isset($row_log['tgl_keluar']) ? ($row_log['tgl_keluar'] != '') ? '<i class="fa fa-check green bigger-120"></i>' : '<i class="fa fa-times red bigger-120"></i>' : '<i class="fa fa-times red bigger-120"></i>' ;

                                        echo ($row_log['nama_bagian'] == 'Farmasi') ? '<i class="fa fa-exclamation-triangle orange bigger-120"></i>' : $icon;?>
                                </span>
                                <span class="title"><?php echo ucwords($row_log['nama_bagian']); ?><br>
                                    <?php echo isset($row_log['tgl_masuk']) ? $this->tanggal->formatDateTimeFormDmy($row_log['tgl_masuk']) :''?>
                                </span>
                            </li>
                            <?php endforeach; ?>
                            <li data-step="<?php echo $num_log?>" <?php echo isset($row_log['tgl_keluar']) ? (!empty($row_log['tgl_keluar'])) ? 'class="active"' : '' : 'class="active"';?> >
                                <span class="step"><i class="fa fa-money green bigger-120"></i></span>
                                <span class="title">Kasir</span>
                            </li>

                        </ul>
                    </div>
                </div>
            </div> 
        </div>

        <br>
        <hr>
        <div class="row">
            <div class="col-xs-12 no-padding">
            <div class="pull-right">
                Total billing : <br>
                <span id="total_billing_all"><b>Rp. 0,-</b></span>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <?php foreach($kunjungan as $key=>$row_dt_kunj) :?>
                            
            <div class="timeline-container timeline-style2">
                <span class="timeline-label" style="width:210px !important">
                    <b><span style="font-size: 14px"><?php echo ucwords($key)?></span></b>
                </span>

                <div class="timeline-items">
                    <?php 
                        foreach($row_dt_kunj as $key_s=>$row_s) : 
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
                                    <?php echo '<span style="font-size: 14px; font-weight: bold">'.ucwords($key_s).'</span>';?>
                                    <table class="table_billing_data table-2 table-striped table-bordered" width="100%" style="color: black">
                                        <tr style="background-color: lightgrey;">
                                            <th class="center" width="50px">
                                                <label>
                                                    <input type="checkbox" class="ace" checked onClick="checkAllItem(this, '<?php echo $row_s[0]->kode_bagian ?>');" value="<?php echo $row_s[0]->kode_bagian ?>" id="<?php echo $row_s[0]->kode_bagian ?>">
                                                    <span class="lbl"></span>
                                                </label>
                                            </th>
                                            <th> Deskripsi Item</th>
                                            <!-- <th>Penjamin</th> -->
                                            <th class="center" width="50px">NK</th>
                                            <th align="right" width="100px">Bill RS</th>
                                            <th align="right" width="100px">Bill dr1</th>
                                            <th align="right" width="100px">Bill dr2</th>
                                            <th align="right" width="100px">Bill dr3</th>
                                            <th class="center" width="100px">Subtotal (Rp.)</th>
                                        </tr>

                                    <?php 
                                        $sum_array[$key.''.$key_s] = array();
                                        foreach( $row_s as $value_data ) : ?>
                                        
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
                                                            echo '<label>
                                                                    <input name="selected_bill" id="selected_bill_'.$value_data->kode_trans_pelayanan.'" value="'.$value_data->kode_trans_pelayanan.'" checked type="checkbox" class="checked_'.$row_s[0]->kode_bagian.' ace" onclick="checkOne('.$value_data->kode_trans_pelayanan.')">
                                                                    <span class="lbl"></span>
                                                                </label>';
                                                        }else{
                                                            echo '<i class="fa fa-times-circle red bigger-120"></i>';
                                                        }
                                                        
                                                        
                                                    }else{
                                                            
                                                        echo '<label>
                                                            <input name="selected_bill" id="selected_bill_'.$value_data->kode_trans_pelayanan.'" value="'.$value_data->kode_trans_pelayanan.'" checked type="checkbox" class="checked_'.$row_s[0]->kode_bagian.' ace" onclick="checkOne('.$value_data->kode_trans_pelayanan.')">
                                                            <span class="lbl"></span>
                                                        </label>';
                                                    }
                                                    
                                                }else{
                                                    echo '<i class="fa fa-check green bigger-120"></i>';
                                                }
                                            ?>
                                        </td>
                                        
                                        <td>
                                            <!-- hidden form -->
                                            <input type="hidden" name="kode_trans_pelayanan[]" id="<?php echo $value_data->kode_trans_pelayanan?>" value="<?php echo $value_data->kode_trans_pelayanan?>">
                                            <input type="hidden" name="delete_row_val[]" id="delete_row_val_<?php echo $value_data->kode_trans_pelayanan?>" value="0">
                                            <?php echo $value_data->kode_trans_pelayanan.' - '.$value_data->nama_tindakan;?>
                                        </td>
                                        
                                        <!-- <td>
                                            <?php 
                                                echo ($value_data->jenis_tindakan==1) ? ''.$this->tanggal->formatDate($value_data->tgl_transaksi).'<br>' :'';
                                                echo ucwords($value_data->nama_bagian);
                                            ?>
                                        </td> -->
                                        <!-- <td id="dokter_<?php echo $value_data->kode_trans_pelayanan?>">
                                            <?php echo $dokter?> -->
                                        </td>
                                        <!-- <td id="penjamin_<?php echo $value_data->kode_trans_pelayanan?>">
                                            <?php echo $penjamin?>
                                        </td> -->
                                        <td align="center">

                                            <?php
                                                if($value_data->kode_tc_trans_kasir==NULL){

                                                    $cheked = ( $value_data->kode_perusahaan == 120 ) ? 'checked' : ( $value_data->status_nk ==  1 ) ? 'checked' : '';
                                                        echo '<label>
                                                                <input name="checklist_nk" id="selected_nk_'.$value_data->kode_trans_pelayanan.'" value="'.$value_data->kode_trans_pelayanan.'" type="checkbox" class="checklist_nk_'.$row_s[0]->kode_bagian.' ace" '.$cheked.' onclick="checkedNk('.$value_data->kode_trans_pelayanan.')">
                                                                <span class="lbl"></span>
                                                            </label>';
                                                    
                                                }else{
                                                    $label_nk = ( $value_data->status_nk ==  1 ) ? '<i class="fa fa-check green bigger-120"></i>' : '';

                                                    echo $label_nk;
                                                }
                                            ?>

                                        </td>
                                        <td id="bill_rs_<?php echo $value_data->kode_trans_pelayanan?>" align="right">
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
                                        </td>

                                        <td width="80px" align="right">
                                                
                                            <span id="subtotal_<?php echo $value_data->kode_trans_pelayanan?>"><?php echo number_format($subtotal)?>,-</span>
                                            <input type="hidden" name="" id="subtotal_hidden_<?php echo $value_data->kode_trans_pelayanan?>" class="class_subtotal" value="<?php echo $subtotal?>">
                                        </td>
                                    </tr>
                                    <?php 
                                        if($value_data->kode_tc_trans_kasir == NULL){
                                            $sum_array[$key.''.$key_s][] = $subtotal;
                                        }
                                        $sum_array_total[$key.''.$key_s][] = $subtotal;
                                        endforeach; 
                                    ?>
                                    <tr style="font-weight: bold; font-size: 13px">
                                        <td colspan="7" align="right">Total</td>
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
            </div>
        </div>

    <p>
        Keterangan : <br>
        <i class="fa fa-times-circle red"></i> &nbsp; Belum selesai pelayanan<br><i class="fa fa-check green"></i> Sudah selesai<br><br>
        <b>NK (Nota Kredit)</b> adalah Biaya yang dibebankan ke perusahaan penjamin, jika biaya dibebankan kepada pasien, maka petugas harus melakukan unceklis pada item yang dibebankan ke pasien.
    </p>
    
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div>