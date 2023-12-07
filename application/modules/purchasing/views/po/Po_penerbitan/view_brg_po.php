<!-- jquery number -->
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>
<script type="text/javascript">

  $(function(){
          
    $('.format_number').number( true );
    
  });

  function changeVendor(kode_brg){
    var value_select = $("#reff_vendor_"+kode_brg+"").find(":selected").text();
    // text select option
    var text=value_select.split('|')[0];
    // change discount
    var disc=value_select.split('|')[1];
    var disc_text = disc.replace(/%/gi, "");
    console.log(disc_text);
    // change price
    var price=value_select.split('|')[2];
    var price_text = price.replace(/@/gi, "");
    console.log(price_text);

    $('#form_input_diskon_'+kode_brg+'').val(disc_text);
    inputDisc(kode_brg);
    $('#form_input_harga_satuan_'+kode_brg+'').val(price_text);
    inputHargaSatuan(kode_brg);

  }

  function checkAll(elm) {

    if($(elm).prop("checked") == true){
      $('.checkbox_brg').each(function(){
        var kode_brg = $(this).val();
        $(this).prop("checked", true);
        checkOne(kode_brg);
      });
    }else{
      $('.checkbox_brg').each(function(){
        var kode_brg = $(this).val();
        $('#checkbox_brg_'+kode_brg+'').prop("checked", false);
        checkOne(kode_brg);
      });
    }

  }

  function checkOne(kode_brg) {
           
    if($('#checkbox_brg_'+kode_brg+'').prop("checked") == true){
      $('#tr_'+kode_brg+' input[type=text]').attr('disabled', false);
      $('#form_input_harga_satuan_'+kode_brg+'').val( $('#hidden_form_input_harga_satuan_'+kode_brg+'').val() );
      var total = parseFloat($('#hidden_form_input_harga_satuan_'+kode_brg+'').val()).toFixed(2) * parseFloat($('#jml_permohonan_'+kode_brg+'').text()).toFixed(2);
      var format_total = total;
      $('#form_input_total_'+kode_brg+'').val( format_total );      
      inputDisc(kode_brg);
      inputPpn(kode_brg);

    }else{
        $('#tr_'+kode_brg+' input[type=text]').attr('disabled', true);
        $('#form_input_total_'+kode_brg+'').val(0);
        $('#total_dpp').val(0);
        $('#total_ppn').val(0);
        $('#total_nett').val(0);

        $('#potongan_diskon_'+kode_brg+'').val(0);
        $('#nominal_diskon_'+kode_brg+'').val(0);
        $('#nominal_ppn_'+kode_brg+'').val(0);
        hitungSubTotalBarang(kode_brg);
    }

  }

  function inputHargaSatuan(kode_brg){
    var input = $('#form_input_harga_satuan_'+kode_brg+'').val();
    $('#hidden_form_input_harga_satuan_'+kode_brg+'').val(formatNumberFromCurrency(input));
    inputDisc(kode_brg);
    inputPpn(kode_brg);
  }

  function inputDisc(kode_brg){
    var discount = parseFloat($('#form_input_diskon_'+kode_brg+'').val()).toFixed(2);
    var price = $('#hidden_form_input_harga_satuan_'+kode_brg+'').val();
    var qty = parseFloat($('#jml_permohonan_'+kode_brg+'').text()).toFixed(2);

    if( discount != '' ){
      var potonganDisc = parseFloat(price) * (discount/100);    
      var afterDisc = parseFloat(price) - parseFloat(potonganDisc);
      var nett = qty * afterDisc;
      var nett_disc = qty * potonganDisc;
      // set value nominal diskon
      $('#nominal_diskon_'+kode_brg+'').val(nett);
      // potongan disc
      $('#potongan_diskon_'+kode_brg+'').val(nett_disc);
      // hitung sub total
      hitungSubTotalBarang(kode_brg);
    }else{
      // set value nominal diskon
      var nett = qty * price;
      $('#nominal_diskon_'+kode_brg+'').val(nett);
      // potongan disc
      $('#potongan_diskon_'+kode_brg+'').val(0);
      // hitung sub total
      hitungSubTotalBarang(kode_brg);
    }
    inputPpn(kode_brg);

  }

  function inputPpn(kode_brg){
    var ppn = $('#form_input_ppn_'+kode_brg+'').val();
    var nominal_diskon = $('#nominal_diskon_'+kode_brg+'').val();
    var qty = parseFloat($('#jml_permohonan_'+kode_brg+'').text()).toFixed(2);
    if( ppn != 0 ){
      var tambahanPpn = nominal_diskon * (ppn/100);    
      // set value nominal diskon
      $('#nominal_ppn_'+kode_brg+'').val(parseFloat(tambahanPpn).toFixed(2));
      // hitung sub total
      hitungSubTotalBarang(kode_brg);
    }else{
      // set value nominal diskon
      $('#nominal_ppn_'+kode_brg+'').val(0);
      // hitung sub total
      hitungSubTotalBarang(kode_brg);
    }

  }

 function hitungSubTotalBarang(kode_brg){

    // original price
    var price = $('#hidden_form_input_harga_satuan_'+kode_brg+'').val();
    // qty pesan
    var qty = parseFloat($('#jml_permohonan_'+kode_brg+'').text()).toFixed(2);
    // nominal diskon original
    var nominal_diskon = parseFloat($('#nominal_diskon_'+kode_brg+'').val()).toFixed(2);
    // nominal ppn
    var nominal_ppn = parseFloat($('#nominal_ppn_'+kode_brg+'').val()).toFixed(2);

    
    // jumlah sub total
    var harga_nett = (parseFloat(nominal_diskon) + parseFloat(nominal_ppn));
    $('#nominal_total_'+kode_brg+'').val( nominal_diskon );
    $('#form_input_total_'+kode_brg+'').val(nominal_diskon);

    var dpp = sumClass('diskon');
    $('#total_dpp').val( dpp );
    $('#total_dpp_val').val( dpp );

    var ppn = sumClass('ppn');
    $('#total_ppn').val( ppn );
    $('#total_ppn_val').val( ppn );

    var potongan_diskon = sumClass('potongan_diskon');
    $('#total_potongan_diskon_val').val( potongan_diskon );

    var total_nett = parseFloat(dpp) + parseFloat(ppn);
    $('#total_nett').val( parseInt(total_nett) );
    $('#total_nett_val').val( total_nett );


 }

</script>
<div class="row">
  <div class="col-xs-12">

    <!-- PAGE CONTENT BEGINS -->
      <p><strong>Barang yang belum dibuatkan PO</strong></p>
      <table id="table_brg_po" class="table table-bordered table-hovered" style="font-size:12px;" width="100%">
        <thead>
          <tr style="background-image: linear-gradient(to bottom, #c7cccb 90%, #61605f 30%)">
            <th class="center" width="20px"><input type="checkbox" onClick="checkAll(this);" style="cursor:pointer"></th>
            <th class="center" width="">No</th>
            <th width="5%">Kode</th>
            <th>Nama Barang</th>
            <th width="15%">Referensi Vendor</th>
            <th class="center" width="7%">Satuan<br>Besar</th>
            <th class="center" width="7%">Rasio</th>
            <th class="center" width="7%">Jumlah<br>Pesan</th>
            <th class="center" width="10%">Harga Satuan</th>
            <th class="center" width="7%">Disc (%)</th>
            <th class="center" width="7%">PPN (%)</th>
            <th class="center" width="10%">Total</th>
          </tr>
        </thead>
        <tbody>
        <?php 
          $no=0; 
          foreach($dt_detail_brg as $key_dt=>$row_dt) : 
            // echo '<pre>';print_r($row_dt);die;
            $count_dt = count($row_dt);
            if($count_dt > 0){
              foreach($row_dt as $sub_row_dt){
                $jumlah_pesan_arr[$key_dt][] = $sub_row_dt->jml_acc_penyetuju;
                echo '<input type="hidden" name="id_tc_permohonan_det['.$key_dt.'][]" id="id_tc_permohonan_det" value="'.$sub_row_dt->id_tc_permohonan_det.'">';
              }
            }else{
              $jumlah_pesan_arr[$key_dt][] = $row_dt[0]->jml_acc_penyetuju;
              echo '<input type="hidden" name="id_tc_permohonan_det['.$key_dt.']" id="id_tc_permohonan_det" value="'.$row_dt[0]->id_tc_permohonan_det.'">';
            }
            $no++; 

        ?>
          <tr id="tr_<?php echo $row_dt[0]->kode_brg?>">
            <!-- checkbox -->
            <td class="center">
              <input type="checkbox" class="checkbox_brg" id="checkbox_brg_<?php echo $row_dt[0]->kode_brg?>" class="form-control" value="<?php echo $row_dt[0]->kode_brg?>" onClick="checkOne('<?php echo $row_dt[0]->kode_brg?>');" style="cursor:pointer" name="is_checked[<?php echo $row_dt[0]->kode_brg?>]">
              <!-- hidden -->
              <input type="hidden" name="id_tc_permohonan[<?php echo $row_dt[0]->kode_brg?>]" id="id_tc_permohonan" value="<?php echo $row_dt[0]->id_tc_permohonan?>">
            </td>

            <td class="center"><?php echo $no?></td>
            <td><?php echo $row_dt[0]->kode_brg?></td>
            <td><?php echo $row_dt[0]->nama_brg?></td>
            <td>
              <?php
                // get vendor
                $vendors = $history_po[$row_dt[0]->kode_brg];
                // echo "<pre>"; print_r($vendors);
                echo $this->master->custom_selection_ref_vendor_po(array('data' => $vendors, 'label' => 'namasupplier', 'value' => 'kodesupplier'), '','reff_vendor_'.$row_dt[0]->kode_brg.'','reff_vendor_'.$row_dt[0]->kode_brg.'','form-control','onchange="changeVendor('."'".$row_dt[0]->kode_brg."'".')"','','');
              ?>
            </td>
            <!-- satuan besar -->
            <td class="center"><?php echo $row_dt[0]->satuan_besar?></td>
            <!-- rasio -->
            <td class="center">
                <input type="text" name="rasio[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_rasio_<?php echo $row_dt[0]->kode_brg?>" style="width:70px;height:45px;text-align:center" value="<?php echo $row_dt[0]->rasio?>"  disabled>
            </td>
            <!-- jumlah acc -->
            <td class="center" id="jml_permohonan_<?php echo $row_dt[0]->kode_brg?>">
              <?php echo array_sum($jumlah_pesan_arr[$key_dt])?>
              <input type="hidden" name="jml_permohonan[<?php echo $row_dt[0]->kode_brg?>]" id="form_jml_permohonan_<?php echo $row_dt[0]->kode_brg?>" style="width:70px;height:45px;text-align:center" value="<?php echo array_sum($jumlah_pesan_arr[$key_dt])?>" >

            </td>

            <!-- harga satuan -->
            <?php
              // harga dasar
              $harga_dasar = ($flag=='medis')?round($row_dt[0]->harga_po_terakhir / 1.1): round($row_dt[0]->harga_po_terakhir);
              // $harga_dasar = ($flag=='medis')?round($row_dt[0]->harga_po_terakhir): round($row_dt[0]->harga_po_terakhir);
              
              $jumlah_harga_dasar_satuan_besar = round($harga_dasar * $row_dt[0]->rasio);
              $history = isset($history_po[$row_dt[0]->kode_brg][0])?$history_po[$row_dt[0]->kode_brg][0]:[];
              $discount = isset($history['discount'])?$history['discount']:0;
            ?>
            <td class="center">
                <input type="text" name="harga_satuan[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_harga_satuan_<?php echo $row_dt[0]->kode_brg?>" style="height:45px;text-align:right" class="format_number form-control" value="<?php echo isset($history['harga_satuan'])?$history['harga_satuan']:'0'; ?>" onchange="inputHargaSatuan('<?php echo $row_dt[0]->kode_brg?>')" disabled>
                <!-- perhitungan harga satuan dasar -->
                <input type="hidden" name="harga_satuan_val[<?php echo $row_dt[0]->kode_brg?>]" id="hidden_form_input_harga_satuan_<?php echo $row_dt[0]->kode_brg?>" value="<?php echo isset($history['harga_satuan'])?$history['harga_satuan']:''; ?>">

            </td>

            <!-- diskon -->
            <td class="center">
                <input type="text" name="diskon[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_diskon_<?php echo $row_dt[0]->kode_brg?>" class="form-control" style="height:45px;text-align:center" value="<?php echo number_format($discount, 2)?>" onchange="inputDisc('<?php echo $row_dt[0]->kode_brg?>')" disabled>
                <input type="hidden" name="diskon_val[<?php echo $row_dt[0]->kode_brg?>]" id="nominal_diskon_<?php echo $row_dt[0]->kode_brg?>" class="diskon" style="height:45px;text-align:center" value="0">
                <input type="hidden" name="potongan_diskon[<?php echo $row_dt[0]->kode_brg?>]" id="potongan_diskon_<?php echo $row_dt[0]->kode_brg?>" class="potongan_diskon" style="height:45px;text-align:center" value="0">
            </td>

             <!-- ppn -->
             <td class="center">
                <input type="text" name="ppn[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_ppn_<?php echo $row_dt[0]->kode_brg?>" class="form-control" style="height:45px;text-align:center" onchange="inputPpn('<?php echo $row_dt[0]->kode_brg?>')" value="<?php echo PPN; ?>" disabled>
                <input type="hidden" name="ppn_val[<?php echo $row_dt[0]->kode_brg?>]" id="nominal_ppn_<?php echo $row_dt[0]->kode_brg?>" class="ppn" style="height:45px;text-align:center" value="0">
            </td>
            
            <!-- total -->
            <td class="center">
              <input type="text" name="total[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_total_<?php echo $row_dt[0]->kode_brg?>" class="format_number form-control" style="height:45px;text-align:right" value="0" readonly disabled>
              <input type="hidden" name="total_val[<?php echo $row_dt[0]->kode_brg?>]" id="nominal_total_<?php echo $row_dt[0]->kode_brg?>" class="total" style="height:45px;text-align:center" value="0">
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tr style="font-size:12px; font-weight:bold">
          <td align="right" colspan="11">DPP</td>
          <td align="right">
              <input type="text" class="format_number form-control" name="total_dpp" id="total_dpp" style="height:45px;text-align:right" value="" readonly>
              <input type="hidden" class="form-control" name="total_dpp_val" id="total_dpp_val" style="height:45px;text-align:right" value="" readonly>
          </td>
        </tr>
        <tr style="font-size:12px; font-weight:bold">
          <td align="right" colspan="11">PPN</td>
          <td align="right">
              <input type="text" class="format_number form-control" name="total_ppn" id="total_ppn" style="height:45px;text-align:right" value="" readonly>
              <input type="hidden" class="form-control" name="total_ppn_val" id="total_ppn_val" style="height:45px;text-align:right" value="" readonly>
          </td>
        </tr>
        <tr style="font-size:12px; font-weight:bold">
          <td align="right" colspan="11">TOTAL</td>
          <td align="right">
              <!-- hidden potongan diskon -->
              <input type="hidden" class="form-control" name="total_potongan_diskon_val" id="total_potongan_diskon_val" style="height:45px;text-align:right" value="" readonly>
              <input type="text" class="format_number form-control" name="total_nett" id="total_nett" style="height:45px;text-align:right" value="" readonly>
              <input type="hidden" class="form-control" name="total_nett_val" id="total_nett_val" style="height:45px;text-align:right" value="" readonly>
          </td>
        </tr>
      </table>
    <!-- PAGE CONTENT ENDS -->


  </div><!-- /.col -->
</div><!-- /.row -->


