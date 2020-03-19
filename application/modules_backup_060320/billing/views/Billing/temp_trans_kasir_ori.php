<style type="text/css">
    input[type=checkbox]{
        margin:-2px 0px 0px !important;
        cursor: pointer;
    }
    .table-2 {
        font-size: 11px;
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

<script type="text/javascript">

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

    $('.input-bill').keypress(function(event) {  
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){          
        event.preventDefault();         
        var kode = $( this ).attr('data-id');
        save_row(kode);
        return false;                
      }       
  });

    function edit_row(kode){
        preventDefault();
        $('#btn_save_'+kode+'').show('fast');
        $('#btn_delete_'+kode+'').show('fast');
        $('#btn_rollback_'+kode+'').hide('fast');
        $('#btn_edit_'+kode+'').hide('fast');
        $('#tr_'+kode+'').css('background-color', 'white');

        /*show field value*/
        show_field_value(kode);
    }

    function save_row(kode){
        preventDefault();
        $('#btn_edit_'+kode+'').show('fast');
        $('#btn_save_'+kode+'').hide('fast');
        $('#btn_delete_'+kode+'').hide('fast');
        $('#btn_rollback_'+kode+'').hide('fast');
        $('#tr_'+kode+'').css('background-color', '#c72a0f14');
        /*hide field value*/
        hide_field_value(kode);
        proses_save_row(kode);
    }

    function proses_save_row(kode){

        var text_penjamin = $('#penjamin_val_'+kode+' option:selected').text();
        $('#penjamin_txt_'+kode+'').show('fast');
        $('#penjamin_txt_'+kode+'').text( text_penjamin );

        var text_kode_dokter = $('#kode_dokter_val_'+kode+' option:selected').text();
        $('#kode_dokter_txt_'+kode+'').show('fast');
        $('#kode_dokter_txt_'+kode+'').text( text_kode_dokter );

        var txt_billl_rs = $('#bill_rs_val_'+kode+'').val();
        $('#bill_rs_txt_'+kode+'').show('fast');
        $('#bill_rs_txt_'+kode+'').text( formatMoney(txt_billl_rs) );

        var txt_billl_dr1 = $('#bill_dr1_val_'+kode+'').val();
        $('#bill_dr1_txt_'+kode+'').show('fast');
        $('#bill_dr1_txt_'+kode+'').text( formatMoney(txt_billl_dr1) );

        var txt_billl_dr2 = $('#bill_dr2_val_'+kode+'').val();
        $('#bill_dr2_txt_'+kode+'').show('fast');
        $('#bill_dr2_txt_'+kode+'').text( formatMoney(txt_billl_dr2) );

        proses_counting_subtotal( kode );

    }

    function proses_counting_subtotal(kode){
        var txt_billl_rs = $('#bill_rs_val_'+kode+'').val();
        var txt_billl_dr1 = $('#bill_dr1_val_'+kode+'').val();
        var txt_billl_dr2 = $('#bill_dr2_val_'+kode+'').val();

        /*sub total row*/
        var sum_total = parseInt(txt_billl_rs) + parseInt(txt_billl_dr1) + parseInt(txt_billl_dr2);
        $('#subtotal_'+kode+'').text( formatMoney(sum_total) );
        $('#subtotal_hidden_'+kode+'').val( sum_total );

        /*sub total bayar*/
        var sub_total_bayar = sumClass('class_subtotal');
        $('#sub_total_bayar').text( formatMoney(sub_total_bayar) );
        $('#sub_total_bayar_hidden').val( sub_total_bayar );

        /*sudah bayar*/
        var sudah_bayar = $('#sudah_bayar_hidden').val();
        $('#sudah_bayar').text( formatMoney(sudah_bayar) );
        $('#sudah_bayar_hidden').val( sudah_bayar );

        /*sisa pembayaran*/
        var sisa_bayar = parseInt(sub_total_bayar) - parseInt(sudah_bayar);
        $('#sisa_bayar').text( formatMoney(sisa_bayar) );
        $('#sisa_bayar_hidden').val( sisa_bayar );


    }

    function delete_row(kode){
        preventDefault();
        $('#btn_save_'+kode+'').hide('fast');
        $('#btn_edit_'+kode+'').hide('fast');
        $('#btn_delete_'+kode+'').hide('fast');
        $('#btn_rollback_'+kode+'').show('fast');
        $('#tr_'+kode+'').css('background-color', '#8080807a');
        $('#delete_row_val_'+kode+'').val(1);
        /*remove class input bill*/
        remove_class_subtotal(kode);
        /*hide field value*/
        hide_field_value(kode);
        /*proses counting*/
        proses_counting_subtotal();
    }

    function remove_class_subtotal(kode){
        $( "#subtotal_hidden_"+kode+"" ).removeClass( "class_subtotal" );
    }

    function add_class_subtotal(kode){
        $( "#subtotal_hidden_"+kode+"" ).addClass( "class_subtotal" );
    }

    function rollback_row(kode){
        preventDefault();
        $('#delete_row_val_'+kode+'').val(0);
        edit_row(kode);
        /*remove class input bill*/
        add_class_subtotal(kode);
    }

    function show_field_value(kode){
        /*show hide field*/
        $('#penjamin_txt_'+kode+'').hide('fast');
        $('#penjamin_val_'+kode+'').show('fast');

        $('#kode_dokter_txt_'+kode+'').hide('fast');
        $('#kode_dokter_val_'+kode+'').show('fast');

        $('#bill_rs_txt_'+kode+'').hide('fast');
        $('#bill_rs_val_'+kode+'').show('fast');

        $('#bill_dr1_txt_'+kode+'').hide('fast');
        $('#bill_dr1_val_'+kode+'').show('fast');

        $('#bill_dr2_txt_'+kode+'').hide('fast');
        $('#bill_dr2_val_'+kode+'').show('fast');
    }

    function hide_field_value(kode){
        /*show hide field*/
        $('#penjamin_txt_'+kode+'').show('fast');
        $('#penjamin_val_'+kode+'').hide('fast');

        $('#kode_dokter_txt_'+kode+'').show('fast');
        $('#kode_dokter_val_'+kode+'').hide('fast');

        $('#bill_rs_txt_'+kode+'').show('fast');
        $('#bill_rs_val_'+kode+'').hide('fast');

        $('#bill_dr1_txt_'+kode+'').show('fast');
        $('#bill_dr1_val_'+kode+'').hide('fast');

        $('#bill_dr2_txt_'+kode+'').show('fast');
        $('#bill_dr2_val_'+kode+'').hide('fast');
    }

    function getChange(){
        var jml_bayar = $('#jumlah_pembayaran_last_hidden').val();
        var jml_uang = $('#jml_byr_tunai').val();
        kembali = parseInt(jml_uang) - parseInt(jml_bayar);
        $( '#jml_kembali' ).val( formatMoney(kembali) );
        $( '#jml_kembali_hidden' ).val( kembali );
        $( '#jml_byr_tunai' ).val( formatMoney(jml_uang) );
        $( '#jml_byr_tunai_hidden' ).val( jml_uang );
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
                getMenu('adm_pasien/loket_kasir/Adm_kasir?flag=umum');    
              }
            })
        }
    }

</script>

<?php echo isset($header)?$header:''?>

<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="wizard-actions">
            <!-- #section:plugins/fuelux.wizard.buttons -->
            <button type="button" class="btn btn-xs btn-prev">
                <i class="ace-icon fa fa-arrow-left"></i>
                Sebelumnya
            </button>

            <button type="button" class="btn btn-xs btn-success btn-next" data-last="Finish">
                Selanjutnya
                <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
            </button>

            <!-- /section:plugins/fuelux.wizard.buttons -->
        </div>
        <br>
        <div id="fuelux-wizard-container" style="background-color:white">
            <div>
                <!-- #section:plugins/fuelux.wizard.steps -->
                <ul class="steps">
                    <li data-step="1" class="active">
                        <span class="step">1</span>
                        <span class="title">KUNJUNGAN PASIEN</span>
                    </li>

                    <li data-step="2">
                        <span class="step">2</span>
                        <span class="title">RINCIAN TAGIHAN</span>
                    </li>

                    <li data-step="3">
                        <span class="step">3</span>
                        <span class="title">REVIEW PEMBAYARAN</span>
                    </li>

                    <li data-step="4">
                        <span class="step">4</span>
                        <span class="title">SELESAI</span>
                    </li>
                </ul>

                <!-- /section:plugins/fuelux.wizard.steps -->
            </div>

            <hr />            

            <!-- #section:plugins/fuelux.wizard.container -->
            <div class="step-content pos-rel">

                <!-- STEP 1 -->
                <div class="step-pane active" data-step="1">
                    
                    <?php foreach($kunjungan as $key=>$row_dt_kunj) :?>
                        
                        <div class="timeline-container timeline-style2">
                            <span class="timeline-label" style="width:150px !important">
                                <b><?php echo $key?></b>
                            </span>

                            <div class="timeline-items">
                                <?php foreach($row_dt_kunj as $row_s) :?>
                                <div class="timeline-item clearfix">
                                    <div class="timeline-info">

                                        <span class="timeline-date"> 
                                            <?php echo ($row_s->tgl_keluar==NULL) ? '<i class="fa fa-times-circle bigger-120 red"></i>' : '<i class="fa fa-check bigger-120 green"></i>' ;?> <?php echo $this->tanggal->formatDateTimeToTime($row_s->tgl_masuk)?> - <?php echo $this->tanggal->formatDateTimeToTime($row_s->tgl_keluar)?></span>

                                        <i class="timeline-indicator btn btn-info no-hover"></i>
                                    </div>

                                    <div class="widget-box transparent">
                                        <div class="widget-body">
                                            <div class="widget-main no-padding">
                                                <span class="bigger-110">
                                                    <a href="#" class="purple bolder"><?php echo ucwords($row_s->nama_bagian)?></a>
                                                </span>

                                                <br>
                                                <?php if($row_s->nama_dokter != '') :?>
                                                <i class="ace-icon fa fa-user grey bigger-125"></i>
                                                <a href="#"><?php echo $row_s->nama_dokter?></a>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach;?>

                            </div><!-- /.timeline-items -->
                        </div>
                        
                    <?php endforeach?>

                    <p>
                        Keterangan : <br>
                        <i class="fa fa-times-circle red"></i> &nbsp; Belum selesai pelayanan &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-check green"></i> Sudah selesai
                    </p>
                </div>

                <!-- STEP 2 -->
                <div class="step-pane" data-step="2">

                    <form class="form-horizontal" id="form_rincian_billing_<?php echo $no_registrasi?>" action="" method="post">

                        <!-- input hidden -->
                        <input type="hidden" name="no_registrasi" id="no_registrasi" value="<?php echo $no_registrasi ?>">
                        <input type="hidden" name="tipe_pasien" id="tipe_pasien" value="<?php echo $tipe ?>">
                        <!-- end input hidden -->

                        <center><b>RINCIAN BILLING PASIEN</b></center>
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="col-sm-6 no-padding">
                                    <label class="label label-danger"> Belum dibayar </label>
                                    <label class="label label-success"> Sudah dibayar </label>
                                    <label class="label label-grey"> Dihapus </label>
                                </div>

                                <div class="col-sm-6">
                                    <div class="pull-right no-padding">
                                        <a href="#" class="btn btn-xs btn-warning" onclick="rollback_kasir(<?php echo $no_registrasi?>)" > <i class="fa fa-undo"></i> Rollback Kasir </a>
                                        <a href="#" class="btn btn-xs btn-inverse"> <i class="fa fa-print"></i> Cetak Billing Sementara</a>
                                    </div>
                                </div>
                                
                                <table class="table-2 table-striped table-bordered" width="100%">
                                    <tr style="background-color: darkorange;">
                                        <th width="30px" class="center">No</th>
                                        <th colspan="3"> Uraian</th>
                                        <th>Bagian</th>
                                        <th>Dokter</th>
                                        <th>Penjamin</th>
                                        <th align="right">Bill RS</th>
                                        <th align="right">Bill dr1</th>
                                        <th align="right">Bill dr2</th>
                                        <th class="center">Subtotal (Rp.)</th>
                                        <th class="center">&nbsp;</th>
                                    </tr>
                                    <?php
                                        $no=1;
                                        foreach ($data->group as $k => $val) :
                                    ?>
                                        <tr>
                                        <td width="30px" class="center"><?php echo $no?></td>
                                        <td colspan="11" valign="top"> <b><?php echo $k?></b></td>
                                        </tr>
                                        <?php 
                                        $no++; 
                                        foreach ($val as $value_data) :
                                            $subtotal = $this->Billing->get_total_tagihan($value_data);
                                            $sign_pay = ($value_data->kode_tc_trans_kasir==NULL)?'#c72a0f14':'#87b87f45';
                                            $checkbox = ($value_data->kode_tc_trans_kasir==NULL)?'<input type="checkbox" name="selected_bill[]" value="'.$value_data->kode_trans_pelayanan.'" checked>':'';
                                            $penjamin = $this->master->custom_selection($params = array('table' => 'mt_perusahaan', 'id' => 'kode_perusahaan', 'name' => 'nama_perusahaan', 'where' => array() ), $value_data->kode_perusahaan , 'penjamin[]', 'penjamin_val_'.$value_data->kode_trans_pelayanan.'', '', '', ' style="width: 150px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 100% border-bottom: 1px #ccc solid; margin: 0px 1px !important; display: none"').'<span id="penjamin_txt_'.$value_data->kode_trans_pelayanan.'">'.$value_data->nama_perusahaan.'</span>';

                                            $dokter = $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array() ), $value_data->kode_dokter1 , 'kode_dokter[]', 'kode_dokter_val_'.$value_data->kode_trans_pelayanan.'', '', '', ' style="width: 150px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 100% border-bottom: 1px #ccc solid;margin: 0px 1px !important; display: none"').'<span id="kode_dokter_txt_'.$value_data->kode_trans_pelayanan.'">'.$value_data->nama_dokter.'</span>';

                                            /*bill rs*/
                                            $bill_rs = '<input type="text" name="bill_rs[]" value="'.$value_data->bill_rs_int.'" style="width:80px;text-align:right;margin: 0px 1px !important; display: none" id="bill_rs_val_'.$value_data->kode_trans_pelayanan.'" class="input-bill" data-id="'.$value_data->kode_trans_pelayanan.'">'.'<span id="bill_rs_txt_'.$value_data->kode_trans_pelayanan.'">'.number_format($value_data->bill_rs_int).'</span>';

                                            /*bill dr1*/
                                            $bill_dr1 = '<input type="text" name="bill_dr1[]" value="'.$value_data->bill_dr1_int.'" style="width:80px;text-align:right;margin: 0px 1px !important; display: none" id="bill_dr1_val_'.$value_data->kode_trans_pelayanan.'" class="input-bill" data-id="'.$value_data->kode_trans_pelayanan.'">'.'<span id="bill_dr1_txt_'.$value_data->kode_trans_pelayanan.'">'.number_format($value_data->bill_dr1_int).'</span>';

                                            /*bill dr2*/
                                            $bill_dr2 = '<input type="text" name="bill_dr2[]" value="'.$value_data->bill_dr2_int.'" style="width:80px;text-align:right;margin: 0px 1px !important; display: none" id="bill_dr2_val_'.$value_data->kode_trans_pelayanan.'" class="input-bill" data-id="'.$value_data->kode_trans_pelayanan.'">'.'<span id="bill_dr2_txt_'.$value_data->kode_trans_pelayanan.'">'.number_format($value_data->bill_dr2_int).'</span>';

                                        ?>
                                            <tr id="tr_<?php echo $value_data->kode_trans_pelayanan?>" style="background-color:<?php echo $sign_pay?>">
                                                <td width="30px" align="center"> 
                                                    <!-- hidden form -->
                                                    <input type="hidden" name="kode_trans_pelayanan[]" id="<?php echo $value_data->kode_trans_pelayanan?>" value="<?php echo $value_data->kode_trans_pelayanan?>">
                                                    <input type="hidden" name="delete_row_val[]" id="delete_row_val_<?php echo $value_data->kode_trans_pelayanan?>" value="0">
                                                </td>
                                                <td align="center" colspan="2">
                                                    <?php echo $checkbox?>
                                                </td>
                                                <td>
                                                    <?php echo $value_data->kode_trans_pelayanan.' - '.$value_data->nama_tindakan;?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        echo ($value_data->jenis_tindakan==1) ? ''.$this->tanggal->formatDate($value_data->tgl_transaksi).'<br>' :'';
                                                        echo ucwords($value_data->nama_bagian);
                                                    ?>
                                                </td>
                                                <td id="dokter_<?php echo $value_data->kode_trans_pelayanan?>">
                                                    <?php echo $dokter?>
                                                </td>
                                                <td id="penjamin_<?php echo $value_data->kode_trans_pelayanan?>">
                                                    <?php echo $penjamin?>
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

                                                <td width="80px" align="right">
                                                    <span id="subtotal_<?php echo $value_data->kode_trans_pelayanan?>"><?php echo number_format($subtotal)?>,-</span>
                                                    <input type="hidden" name="" id="subtotal_hidden_<?php echo $value_data->kode_trans_pelayanan?>" class="class_subtotal" value="<?php echo $subtotal?>">
                                                </td>
                                                <td width="70px" align="center">
                                                    <?php
                                                        if($value_data->kode_tc_trans_kasir==NULL){
                                                            if($value_data->status_selesai==2){
                                                                echo '
                                                                <a href="#" id="btn_edit_'.$value_data->kode_trans_pelayanan.'" class="btn btn-minier btn-white btn-warning" onclick="edit_row('.$value_data->kode_trans_pelayanan.')"><i class="fa fa-pencil"></i></a>
                                                                <a href="#" id="btn_save_'.$value_data->kode_trans_pelayanan.'" class="btn btn-minier btn-white btn-primary" onclick="save_row('.$value_data->kode_trans_pelayanan.')" style="display:none"><i class="fa fa-check"></i></a> 
                                                                <a href="#" id="btn_delete_'.$value_data->kode_trans_pelayanan.'" class="btn btn-minier btn-white btn-danger" onclick="delete_row('.$value_data->kode_trans_pelayanan.')" style="display:none"><i class="fa fa-times-circle"></i></a>
                                                                <a href="#" id="btn_rollback_'.$value_data->kode_trans_pelayanan.'" class="btn btn-minier btn-white btn-danger" onclick="rollback_row('.$value_data->kode_trans_pelayanan.')" style="display:none"><i class="fa fa-undo"></i></a>';
                                                            }else{
                                                                echo '<label class="label label-danger">Belum selesai</label>';
                                                            }
                                                            
                                                        }else{
                                                            echo '<a href="#" id="btn_rollback_'.$value_data->kode_trans_pelayanan.'" class="btn btn-minier btn-white btn-danger" onclick="rollback_row('.$value_data->kode_trans_pelayanan.')" style="display:none"><i class="fa fa-undo"></i></a>';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                            /*total*/
                                            $sum_subtotal[] = $subtotal;
                                            $sum_subtotal_key[$k][] = $subtotal;
                                            /*resume billing*/
                                            $resume_billing[] = $this->Billing->resumeBillingRJ($value_data->jenis_tindakan, $value_data->kode_bagian, $subtotal);
                                            $has_paid[] = ($value_data->kode_tc_trans_kasir==NULL)?0:$subtotal;
                                        endforeach;   
                                        echo '<tr>';    
                                            echo '<td colspan="10" align="right"><i><b>Sub Total</b></i></td>';    
                                            echo '<td align="right"><b>'.number_format( array_sum($sum_subtotal_key[$k]) ).',-</b></td>';    
                                        echo '</tr>';    
                                    endforeach; 
                                    /*total*/
                                    $total = array_sum($sum_subtotal);
                                    $sudah_bayar = array_sum($has_paid);
                                    $sisa = $total - $sudah_bayar;

                                    ?> 
                                </table>
                                
                                <br>
                                <b>TOTAL PEMBAYARAN</b>
                                <table class="table table-striped">
                                    <tr>
                                        <td width="85%">Sub Total</td>
                                        <td align="right">
                                            <span id="sub_total_bayar">
                                                <?php echo number_format($total)?>,-
                                            </span>
                                            <input type="hidden" name="" value="<?php echo $total?>" id="sub_total_bayar_hidden">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sudah Dibayar</td>
                                        <td align="right">
                                            <span id="sudah_bayar">
                                                <?php echo number_format($sudah_bayar)?>,-
                                            </span>
                                            <input type="hidden" name="" value="<?php echo $sudah_bayar?>" id="sudah_bayar_hidden">
                                        </td>
                                    </tr>
                                    <tr style="font-size: 14px">
                                        <td><b>Sisa Pembayaran</b></td>
                                        <td align="right">
                                            <b>
                                                <span id="sisa_bayar">
                                                    <?php echo number_format($sisa)?>,-
                                                </span>
                                                <input type="hidden" name="sisa_bayar_hidden" value="<?php echo $sisa?>" id="sisa_bayar_hidden">
                                            </b>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>

                    </form>

                </div>
                
                <!-- STEP 3 -->
                <div class="step-pane" data-step="3">

                    <form class="form-horizontal" id="form_pembayaran" action="" method="post">
                    <b>PEMBAYARAN TAGIHAN PASIEN</b>

                        <div id="billing_by_penjamin_div"></div>
                        <hr class="separator">

                        <div class="form-group" style="width:100%">
                        <label class="control-label col-md-2" style="width:15%">a.n Pasien</label>
                        <div class="col-md-6">
                            <input name="pembayar" id="pembayar" value="<?php echo $data->reg_data->nama_pasien?>" class="form-control" type="text" style="width:40%">
                        </div>
                        </div>

                        <div class="form-group" style="width:100%">
                            <label class="control-label col-md-2" style="width:15%">Metode Pembayaran</label>
                            <div class="col-md-6">
                                <select class="form-control" name="metode_pembayaran">
                                    <option>-Silahkan Pilih-</option>
                                    <option value="1" selected>Tunai/ Cash</option>
                                    <option value="2">Kartu Debit</option>
                                    <option value="3">Kartu Kredit</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="width:100%">
                            <label class="control-label col-md-2" style="width:15%">TOTAL</label>
                            <div class="col-md-6">
                                <div style="margin-top:3px; margin-left: 10px; font-size: 14px; font-weight: bold; color: blue">
                                    <span id="total_pembayaran_last"> <?php echo number_format($sisa)?>,-</span>
                                </div>
                            </div>
                        </div>

                        <div id="div_tunai">
                            <hr class="separator">
                            <p><b>PEMBAYARAN TUNAI</b></p>
                            <div class="form-group" style="width:100%">
                                <label class="control-label col-md-2" style="width:15%">Jumlah Pembayaran</label>
                                <div class="col-md-2">
                                    <input name="jumlah_pembayaran_last" id="jumlah_pembayaran_last" value="" class="form-control" type="text" style="text-align:right" readonly>
                                    <input name="jumlah_pembayaran_last_hidden" id="jumlah_pembayaran_last_hidden" value="" class="form-control" type="hidden" style="text-align:right" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="width:100%">
                                <label class="control-label col-md-2" style="width:15%">Uang Yang Dibayarkan</label>
                                <div class="col-md-2">
                                    <input name="jml_byr_tunai" id="jml_byr_tunai" value="" class="form-control" type="text" style="text-align:right" onchange="getChange()">
                                    <input name="jml_byr_tunai_hidden" id="jml_byr_tunai_hidden" value="" class="form-control" type="hidden" style="text-align:right" >
                                </div>
                            </div>

                            <div class="form-group" style="width:100%">
                                <label class="control-label col-md-2" style="width:15%">Pengembalian Uang</label>
                                <div class="col-md-2">
                                    <input name="jml_kembali" id="jml_kembali" value="0" class="form-control" type="text" style="text-align:right" readonly>
                                    <input name="jml_kembali_hidden" id="jml_kembali_hidden" value="0" class="form-control" type="hidden" style="text-align:right" readonly>
                                </div>
                            </div>
                        </div>

                        <div id="div_debet" style="display:none">
                            <hr class="separator">
                            <p><b>PEMBAYARAN KARTU DEBET</b></p>
                            <div class="form-group" style="width:100%">
                                <label class="control-label col-md-2" style="width:15%">Jumlah Pembayaran</label>
                                <div class="col-md-2">
                                <input name="jml_byr_debet" id="jml_byr_debet" value="Rp. <?php echo number_format($sisa)?>,-" class="form-control" type="text">
                                </div>
                            </div>

                            <div class="form-group" style="width:100%">
                                <label class="control-label col-md-2" style="width:15%">Kartu Debit</label>
                                <div class="col-md-4">
                                <?php echo $this->master->custom_selection_with_label($params = array('table' => 'mt_bank', 'id' => 'acc_no', 'name' => 'nama_bank', 'where' => array() ), '' , 'debet_bank', 'debet_bank', 'form-control', '', '') ?>
                                </div>
                            </div>

                            <div class="form-group" style="width:100%">
                                <label class="control-label col-md-2" style="width:15%">Nomor Kartu</label>
                                <div class="col-md-2">
                                <input name="debet_card_no" id="debet_card_no" value="" class="form-control" type="text">
                                </div>
                            </div>

                            <div class="form-group" style="width:100%">
                                <label class="control-label col-md-2" style="width:15%">Nomor Batch</label>
                                <div class="col-md-2">
                                <input name="debet_no_batch" id="debet_no_batch" value="" class="form-control" type="text">
                                </div>
                            </div>
                        </div>                        

                        <div id="div_kredit" style="display:none">
                            <hr class="separator">
                            <p><b>PEMBAYARAN KARTU KREDIT</b></p>
                            <div class="form-group" style="width:100%">
                                <label class="control-label col-md-2" style="width:15%">Jumlah Pembayaran</label>
                                <div class="col-md-2">
                                <input name="jml_byr_kredit" id="jml_byr_kredit" value="" class="form-control" type="text">
                                </div>
                            </div>

                            <div class="form-group" style="width:100%">
                                <label class="control-label col-md-2" style="width:15%">Kartu Kredit</label>
                                <div class="col-md-4">
                                <?php echo $this->master->custom_selection_with_label($params = array('table' => 'mt_bank', 'id' => 'acc_no', 'name' => 'nama_bank', 'where' => array() ), '' , 'kredit_bank', 'kredit_bank', 'form-control', '', '') ?>
                                </div>
                            </div>

                            <div class="form-group" style="width:100%">
                                <label class="control-label col-md-2" style="width:15%">Nomor Kartu</label>
                                <div class="col-md-2">
                                <input name="kredit_card_no" id="kredit_card_no" value="" class="form-control" type="text">
                                </div>
                            </div>

                            <div class="form-group" style="width:100%">
                                <label class="control-label col-md-2" style="width:15%">Nomor Batch</label>
                                <div class="col-md-2">
                                <input name="kredit_no_batch" id="kredit_no_batch" value="" class="form-control" type="text">
                                </div>
                            </div>
                        </div>

                        <hr class="separator">
                        <p><b>NOTA KREDIT PERUSAHAAN</b></p>
                        <div class="form-group" style="width:100%">
                            <label class="control-label col-md-2" style="width:15%">Jumlah</label>
                            <div class="col-md-2">
                                <input name="nk_company" id="nk_company" value="" class="form-control" type="text">
                            </div>
                        </div>
                        
                        <div class="form-group" style="width:100%">
                            <label class="control-label col-md-2" style="width:15%">Diskon</label>
                            <div class="col-md-2">
                                <input name="discount" id="discount" value="" class="form-control" type="text">
                            </div>
                        </div>
                        
                    </form>
                    
                </div>
                
                <!-- STEP 4 -->
                <div class="step-pane" data-step="4">
                    
                    Step 4 

                </div>

            </div>

            <!-- /section:plugins/fuelux.wizard.container -->
        </div>

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->


<!-- page specific plugin scripts -->
<script src="<?php echo base_url()?>assets/js/fuelux/fuelux.wizard.js"></script>
<script src="<?php echo base_url()?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url()?>assets/js/additional-methods.js"></script>
<script src="<?php echo base_url()?>assets/js/bootbox.js"></script>
<script src="<?php echo base_url()?>assets/js/jquery.maskedinput.js"></script>
<script src="<?php echo base_url()?>assets/js/select2.js"></script>


<script type="text/javascript">
    jQuery(function($) {
        
        var no_registrasi = $('#no_registrasi').val();
        var $validation = false;
        $('#fuelux-wizard-container')
        .ace_wizard({
            //step: 2 //optional argument. wizard will jump to step "2" at first
            //buttons: '.wizard-actions:eq(0)'
        })
        .on('actionclicked.fu.wizard' , function(e, info){
            /*if(info.step == 3 && $validation) {
                if(!$('#form_pembayaran').valid()) e.preventDefault();
            }*/
            /*here action every click */
            // if(info.step == 2) {
            //     e.preventDefault();
            //     /*here execution for step 2*/
            //     $.ajax({ //Process the form using $.ajax()
            //         type      : 'POST', //Method type
            //         url       : 'billing/Billing/proses_update_billing', //Your form processing file URL
            //         data      : $('#form_rincian_billing_'+no_registrasi+'').serialize(), //Forms name
            //         dataType  : 'json',
            //         success   : function(data) {
            //         /*return data*/
            //         $('#total_pembayaran_last').text( formatMoney( data.total_bayar ) );
            //         $('#jumlah_pembayaran_last').val( formatMoney( data.total_bayar ) );
            //         $('#jumlah_pembayaran_last_hidden').val( data.total_bayar );
            //         var wizard = $('#fuelux-wizard-container').data('fu.wizard')
            //             wizard.currentStep = 3;
            //             wizard.setState();

            //         /*get data for step 3*/
            //         $.getJSON("billing/Billing/getBillingByPenjamin/" + data.no_registrasi+ "/" + data.tipe, '', function (result) {
            //             $('#billing_by_penjamin_div').html(result.html);
            //         });

            //         }
            //     })
            //     console.log(  );
            // }
        })
        .on('finished.fu.wizard', function(e) {
            bootbox.dialog({
                message: "Thank you! Your information was successfully saved!", 
                buttons: {
                    "success" : {
                        "label" : "OK",
                        "className" : "btn-sm btn-primary"
                    }
                }
            });
        }).on('stepclick.fu.wizard', function(e){
            //e.preventDefault();//this will prevent clicking and selecting steps
        });
    
    
        //jump to a step
        /**
        var wizard = $('#fuelux-wizard-container').data('fu.wizard')
        wizard.currentStep = 3;
        wizard.setState();
        */
    
        //determine selected step
        //wizard.selectedItem().step
    
    })
</script>