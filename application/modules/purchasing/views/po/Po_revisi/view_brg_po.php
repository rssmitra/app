<!-- jquery number -->
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>
<script type="text/javascript">

  $(function(){
          
    $('.format_number').number( true, 2 );
    
  });

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
      var total = parseFloat($('#hidden_form_input_harga_satuan_'+kode_brg+'').val()).toFixed(2) * parseFloat($('#form_jml_permohonan_'+kode_brg+'').val()).toFixed(2);
      var format_total = total;
            
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

  function inputJumlahRevisi(kode_brg){
    var input = $('#form_jml_permohonan_'+kode_brg+'').val();
    inputDisc(kode_brg);
    inputPpn(kode_brg);
  }

  function inputRasio(kode_brg){
    var input = $('#form_input_rasio_'+kode_brg+'').val();
    inputDisc(kode_brg);
    inputPpn(kode_brg);
  }

  function inputDisc(kode_brg){
    var discount = parseFloat($('#form_input_diskon_'+kode_brg+'').val()).toFixed(2);
    var price = parseFloat($('#hidden_form_input_harga_satuan_'+kode_brg+'').val()).toFixed(2);
    var qty = parseFloat($('#form_jml_permohonan_'+kode_brg+'').val()).toFixed(2);

    if( discount != 0 ){
      var potonganDisc = parseFloat(price) * (discount/100);    
      var afterDisc = parseFloat(price) - parseFloat(potonganDisc);
      var nett = qty * afterDisc
      var nett_disc = qty * potonganDisc;
      // set value nominal diskon
      $('#nominal_diskon_'+kode_brg+'').val(nett.toFixed(2));
      // potongan disc
      $('#potongan_diskon_'+kode_brg+'').val(nett_disc.toFixed(2));
      // hitung sub total
      hitungSubTotalBarang(kode_brg);
    }else{
      // set value nominal diskon
      var nett = qty * price;
      $('#nominal_diskon_'+kode_brg+'').val(nett.toFixed(2));
      // potongan disc
      $('#potongan_diskon_'+kode_brg+'').val(0);
      // hitung sub total
      hitungSubTotalBarang(kode_brg);
    }
    inputPpn(kode_brg);

  }

  function inputPpn(kode_brg){
    var ppn = $('#form_input_ppn_'+kode_brg+'').val();
    var nominal_diskon = parseFloat($('#nominal_diskon_'+kode_brg+'').val()).toFixed(2);
    var qty = parseFloat($('#form_jml_permohonan_'+kode_brg+'').val()).toFixed(2);
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
    var price = parseFloat($('#hidden_form_input_harga_satuan_'+kode_brg+'').val()).toFixed(2);
    // qty pesan
    var qty = parseFloat($('#form_jml_permohonan_'+kode_brg+'').val()).toFixed(2);
    // nominal diskon original
    var nominal_diskon = parseFloat($('#nominal_diskon_'+kode_brg+'').val()).toFixed(2);
    // nominal ppn
    var nominal_ppn = parseFloat($('#nominal_ppn_'+kode_brg+'').val()).toFixed(2);

    // jumlah sub total
    // var harga_nett = (parseFloat(nominal_diskon) + parseFloat(nominal_ppn));
    $('#nominal_total_'+kode_brg+'').val( nominal_diskon );
    $('#form_input_total_'+kode_brg+'').val( nominal_diskon );

    var dpp = sumClass('diskon');
    $('#total_dpp').val( dpp );
    $('#total_dpp_val').val( dpp );

    var ppn = sumClass('ppn');
    $('#total_ppn').val( ppn );
    $('#total_ppn_val').val( ppn );

    var potongan_diskon = sumClass('potongan_diskon');
    $('#total_potongan_diskon_val').val( potongan_diskon );

    var total_nett = parseFloat(dpp) + parseFloat(ppn);
    $('#total_nett').val( total_nett.toFixed(2) );
    $('#total_nett_val').val( total_nett.toFixed(2) );


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
            <th class="center" width="10%">Satuan Besar</th>
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
                $jumlah_pesan_arr[$key_dt][] = $sub_row_dt->jumlah_besar_acc;
                echo '<input type="hidden" name="id_tc_permohonan_det['.$key_dt.'][]" id="id_tc_permohonan_det" value="'.$sub_row_dt->id_tc_permohonan_det.'">';
              }
            }else{
              $jumlah_pesan_arr[$key_dt][] = $row_dt[0]->jumlah_besar_acc;
              echo '<input type="hidden" name="id_tc_permohonan_det['.$key_dt.']" id="id_tc_permohonan_det" value="'.$row_dt[0]->id_tc_permohonan_det.'">';
            }
            $no++; 

        ?>
          <tr id="tr_<?php echo $row_dt[0]->kode_brg?>" <?php ( empty($row_dt[0]->kode_detail_penerimaan_barang) ) ? '' : 'style="background-color: red"' ; ?> >
            <!-- checkbox -->
            <?php if(empty($row_dt[0]->jumlah_kirim) || $row_dt[0]->jumlah_kirim == 0) :?>
            <td class="center">
                <input type="checkbox" class="checkbox_brg" id="checkbox_brg_<?php echo $row_dt[0]->kode_brg?>" class="form-control" value="<?php echo $row_dt[0]->kode_brg?>" onClick="checkOne('<?php echo $row_dt[0]->kode_brg?>');" style="cursor:pointer" name="is_checked[<?php echo $row_dt[0]->kode_brg?>]">
              
              <!-- hidden -->
              <input type="hidden" name="id_tc_permohonan[<?php echo $row_dt[0]->kode_brg?>]" id="id_tc_permohonan" value="<?php echo $row_dt[0]->id_tc_permohonan?>">
            </td>
            <?php else: ?>
              <td align="center"><i class="fa fa-check green bigger-150"></i></td>
            <?php endif; ?>

            <td class="center"><?php echo $no?></td>
            <td><?php echo $row_dt[0]->kode_brg?> </td>
            <td><?php echo $row_dt[0]->nama_brg?></td>

            <?php if(empty($row_dt[0]->jumlah_kirim) || $row_dt[0]->jumlah_kirim == 0) :?>
            <!-- satuan besar -->
            <td class="center"><?php echo $row_dt[0]->satuan_besar?></td>
            <!-- rasio -->
            <td class="center">
                <input type="text" name="rasio[<?php echo $row_dt[0]->kode_brg?>]" onchange="inputRasio('<?php echo $row_dt[0]->kode_brg?>')" id="form_input_rasio_<?php echo $row_dt[0]->kode_brg?>" style="width:70px;height:45px;text-align:center" value="<?php echo $row_dt[0]->content?>"  disabled>
            </td>
            <!-- jumlah acc -->
            <td class="center" id="jml_permohonan_<?php echo $row_dt[0]->kode_brg?>">
              <!-- <?php echo array_sum($jumlah_pesan_arr[$key_dt])?> -->
              <input type="text" name="jml_permohonan[<?php echo $row_dt[0]->kode_brg?>]" onchange="inputJumlahRevisi('<?php echo $row_dt[0]->kode_brg?>')" id="form_jml_permohonan_<?php echo $row_dt[0]->kode_brg?>" style="width:70px;height:45px;text-align:center" value="<?php echo array_sum($jumlah_pesan_arr[$key_dt])?>" disabled>

            </td>

            <!-- harga satuan -->
            <td class="center">
                <input type="text" name="harga_satuan[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_harga_satuan_<?php echo $row_dt[0]->kode_brg?>" style="height:45px;text-align:right" class="format_number form-control" value="<?php echo $row_dt[0]->harga_satuan?>" onchange="inputHargaSatuan('<?php echo $row_dt[0]->kode_brg?>')" disabled>
                <!-- perhitungan harga satuan dasar -->
                
                <input type="hidden" name="harga_satuan_val[<?php echo $row_dt[0]->kode_brg?>]" id="hidden_form_input_harga_satuan_<?php echo $row_dt[0]->kode_brg?>" value="<?php echo $row_dt[0]->harga_satuan?>">
            </td>

            <!-- diskon -->
            <td class="center">
                <input type="text" name="diskon[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_diskon_<?php echo $row_dt[0]->kode_brg?>" class="form-control" style="height:45px;text-align:center" value="<?php echo $row_dt[0]->discount?>" onchange="inputDisc('<?php echo $row_dt[0]->kode_brg?>')" disabled>
                <!-- default -->
                <input type="hidden" name="diskon_val[<?php echo $row_dt[0]->kode_brg?>]" id="nominal_diskon_<?php echo $row_dt[0]->kode_brg?>" class="diskon" style="height:45px;text-align:center" value="0">
                <input type="hidden" name="potongan_diskon[<?php echo $row_dt[0]->kode_brg?>]" id="potongan_diskon_<?php echo $row_dt[0]->kode_brg?>" class="potongan_diskon" style="height:45px;text-align:center" value="0">
            </td>

             <!-- ppn -->
             <td class="center">
                <input type="text" name="ppn[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_ppn_<?php echo $row_dt[0]->kode_brg?>" class="form-control" style="height:45px;text-align:center" onchange="inputPpn('<?php echo $row_dt[0]->kode_brg?>')" value="11" disabled>
                <input type="hidden" name="ppn_val[<?php echo $row_dt[0]->kode_brg?>]" id="nominal_ppn_<?php echo $row_dt[0]->kode_brg?>" class="ppn" style="height:45px;text-align:center" value="0">
            </td>
            
            <!-- total -->
            <td class="center">
              <input type="text" name="total[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_total_<?php echo $row_dt[0]->kode_brg?>" class="format_number form-control" style="height:45px;text-align:right" value="0" readonly disabled>
              <input type="hidden" name="total_val[<?php echo $row_dt[0]->kode_brg?>]" id="nominal_total_<?php echo $row_dt[0]->kode_brg?>" class="total" style="height:45px;text-align:center" value="0">
            </td>
            <?php else : ?>
              <td colspan="6" align="center"><span style="color: blue; letter-spacing: 2px; font-weight: bold">BARANG TELAH DITERIMA</span> <b>(<?php echo $row_dt[0]->kode_penerimaan?>)</b></td>
               <!-- total -->
            <td class="center">
              <input type="text" name="total[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_total_<?php echo $row_dt[0]->kode_brg?>" class="format_number form-control" style="height:45px;text-align:right" value="0" readonly disabled>
              <input type="hidden" name="total_val[<?php echo $row_dt[0]->kode_brg?>]" id="nominal_total_<?php echo $row_dt[0]->kode_brg?>" class="total" style="height:45px;text-align:center" value="0">
            </td>
            <?php endif; ?>

          </tr>
          <?php endforeach;?>
        </tbody>
        <tr style="font-size:12px; font-weight:bold">
          <td align="right" colspan="10">DPP</td>
          <td align="right">
              <input type="text" class="format_number form-control" name="total_dpp" id="total_dpp" style="height:45px;text-align:right" value="">
              <input type="hidden" class="form-control" name="total_dpp_val" id="total_dpp_val" style="height:45px;text-align:right" value="">
          </td>
        </tr>
        <tr style="font-size:12px; font-weight:bold">
          <td align="right" colspan="10">PPN</td>
          <td align="right">
              <input type="text" class="format_number form-control" name="total_ppn" id="total_ppn" style="height:45px;text-align:right" value="">
              <input type="hidden" class="form-control" name="total_ppn_val" id="total_ppn_val" style="height:45px;text-align:right" value="">
          </td>
        </tr>
        <tr style="font-size:12px; font-weight:bold">
          <td align="right" colspan="10">TOTAL</td>
          <td align="right">
              <!-- hidden potongan diskon -->
              <input type="hidden" class="form-control" name="total_potongan_diskon_val" id="total_potongan_diskon_val" style="height:45px;text-align:right" value="">
              <input type="text" class="format_number form-control" name="total_nett" id="total_nett" style="height:45px;text-align:right" value="">
              <input type="hidden" class="form-control" name="total_nett_val" id="total_nett_val" style="height:45px;text-align:right" value="">
          </td>
        </tr>
      </table>
    <!-- PAGE CONTENT ENDS -->


  </div><!-- /.col -->
</div><!-- /.row -->


