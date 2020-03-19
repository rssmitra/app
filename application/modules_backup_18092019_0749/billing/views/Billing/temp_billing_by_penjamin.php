<script type="text/javascript">
    function getBillingDetail(noreg, type, field){
      preventDefault();
      $.getJSON("billing/Billing/getRincianBilling/" + noreg + "/" + type + "/" +field, '', function (data) {
          response_data = data;
          html = '';
          html += '<div class="center"><p><b>RINCIAN BIAYA '+field+'</b></p></div>';
          //alert(response_data.html); return false;
          $('#detail_item_billing_'+noreg+'').html(data.html);
      });
     
    }
</script>

<?php
    $resume_billing = array();
    foreach ($data_billing->group as $k => $val) :
        foreach ($val as $value_data) :
            $subtotal = $this->Billing->get_total_tagihan($value_data);
            $sum_subtotal[] = $subtotal;
            /*resume billing*/
            $resume_billing[] = $this->Billing->resumeBillingRJ($value_data->jenis_tindakan, $value_data->kode_bagian, $subtotal);
            $resume_billing_ri[] = $this->Billing->resumeBillingRI($value_data);

        endforeach;       
    endforeach; 
?>

<table class="table table-striped table-bordered" width="100%">
    <tr style="background-color: darkorange;">
        <th width="30px" class="center">No</th>
        <th> Penjamin / Perusahaan </th>
        <th> No MR </th>
        <th> a.n Pasien </th>
        <?php if($data_billing->reg_data->kode_perusahaan==120) : ?>
        <th width="170px"> Nomor SEP </th>
        <?php endif; ?>
        <th align="right" width="100px"> Total Tagihan</th>
    </tr>
    <?php 
        $getData = array();
        foreach($data_billing->trans_data as $row_dt_trans) {
            $total = $this->Billing->get_total_tagihan($row_dt_trans);
            $getData[$row_dt_trans->nama_perusahaan][] = $row_dt_trans;
        }
        $sum=0;
        $no=0;
        $temp_html = '';
        foreach ($getData as $key => $key_val) {
            $no++;
            foreach ($key_val as $val_key) {
                $sum += $this->Billing->get_total_tagihan($val_key);
            }
            $name = ( $key != '' ) ? $key : ' PASIEN UMUM ' ;
            $temp_html .= '<tr>';
            $temp_html .= '<td width="30px" class="center">'.$no.'</td>';
            $temp_html .= '<td> '.$name.' </td>';
            $temp_html .= '<td> '.$data_billing->reg_data->no_mr.' </td>';
            $temp_html .= '<td> '.$data_billing->reg_data->nama_pasien.' </td>';
            if( $data_billing->reg_data->kode_perusahaan==120 ) :
            $temp_html .= '<td> <input type="text" name="sep" value="'.$data_billing->reg_data->no_sep.'"          style="width:170px;text-align:left;margin: 0px 1px !important;"> </td>';
            endif;
            $temp_html .= '<td align="right"> '.number_format($sum).' </td>';
            $temp_html .= '</tr>';
            echo $temp_html;
        }
    ?>

</table>
<?php if( $tipe == 'RJ' ) : ?>
<hr class="separator">
<b>RESUME BILLING RAWAT JALAN</b>
<table class="table table-striped table-bordered" width="100%">
    <tr>
        <th align="right">Dokter</th>
        <th align="right">Administrasi</th>
        <th align="right">Obat/Farmasi</th>
        <th align="right">Penunjang Medis</th>
        <th align="right">Tindakan</th>
        <th align="right">BPAKO</th>
    </tr>
    <?php 
     /*split resume billing*/
    $split_billing = $this->Billing->splitResumeBilling($resume_billing);

    $bill_dr    = isset($split_billing['bill_dr'])?$split_billing['bill_dr']:0;
    $bill_adm_rs    = isset($split_billing['bill_adm_rs'])?$split_billing['bill_adm_rs']:0;
    $bill_farm  = isset($split_billing['bill_farm'])?$split_billing['bill_farm']:0;
    $bill_pm    = isset($split_billing['bill_pm'])?$split_billing['bill_pm']:0;
    $bill_tindakan  = isset($split_billing['bill_tindakan'])?$split_billing['bill_tindakan']:0;
    $bill_bpako     = isset($split_billing['bill_bpako'])?$split_billing['bill_bpako']:0;
    ?>
    <tr>
        <td align="right">Rp. <?php echo number_format($bill_dr)?>,-</td>
        <td align="right">Rp. <?php echo number_format($bill_adm_rs)?>,-</td>
        <td align="right">Rp. <?php echo number_format($bill_farm)?>,-</td>
        <td align="right">Rp. <?php echo number_format($bill_pm)?>,-</td>
        <td align="right">Rp. <?php echo number_format($bill_tindakan)?>,-</td>
        <td align="right">Rp. <?php echo number_format($bill_bpako)?>,-</td>
    </tr>'
    <tr>
        <td align="right" colspan="5"><b>Total</b></td>
        <?php $total_billing = (double)$bill_dr + (double)$bill_adm_rs + (double)$bill_farm + (double)$bill_pm + (double)$bill_tindakan+ (double)$bill_bpako; 
        ?>
        <td align="right"><b>Rp. <?php echo number_format($total_billing)?>,-</b></td>
    </tr>
</table>
<?php else : ?>
<hr class="separator">
<b>RESUME BILLING RAWAT INAP</b>
<table class="table table-striped table-bordered" width="100%">
    <?php
        $split_billing_ri = $this->Billing->splitResumeBillingRI($resume_billing_ri);
        $html = '';
        $html .= '<tr>';
        foreach ($split_billing_ri as $k => $val) {
            /*total*/
            if((int)$val['subtotal'] > 0){
                $html .= '<th width="100px">'.$val['title'].'</th>';
            }   
        }
        $html .= '</tr>';
        $html .= '<tr>';
        $count = 0;
        foreach ($split_billing_ri as $k2 => $val2) {
            /*total*/
            if((int)$val2['subtotal'] > 0){
                $count++;
                $sum_subtotal_ri[] = $val2['subtotal'];
                $html .= '<td width="100px" align="right"><a href="#" onclick="getBillingDetail('.$no_registrasi.','."'".$tipe."'".','."'".$val2['field']."'".')">'.number_format($val2['subtotal']).'</a></td>';
            }   
        }
        $html .= '</tr>';
        $html .= '<tr>';
        $colspan = $count - 1;
        $html .= '<td colspan="'.$colspan.'" align="right"><b><i>Sub Total</i></b></td>';
        $html .= '<td align="right"><b>'.number_format(array_sum($sum_subtotal_ri)).'</b></td>';
        $html .= '</tr>';

        echo $html;
    ?>
</table>
<div class="col-sm-12">
    <div id="detail_item_billing_<?php echo $no_registrasi?>">
    </div>
</div>
<?php endif; ?>