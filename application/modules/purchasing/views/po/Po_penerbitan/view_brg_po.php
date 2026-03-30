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

  function inputDiscRp(kode_brg){

    var discount_rp = parseFloat($('#potongan_diskon_'+kode_brg+'').val()).toFixed(2);
    var price = $('#hidden_form_input_harga_satuan_'+kode_brg+'').val();
    var qty = parseFloat($('#jml_permohonan_'+kode_brg+'').text()).toFixed(2);
    var discount_percent = (discount_rp / parseFloat(price)) * 100;
    $('#form_input_diskon_'+kode_brg+'').val(discount_percent.toFixed(2));

    if( discount_rp != '' ){
      var potonganDisc = discount_rp;
      var afterDisc = parseFloat(price) - parseFloat(discount_rp);
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

<style>
  .po-tbl { width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 0; }
  .po-tbl thead tr { background: #2c6fad; color: #fff; }
  .po-tbl thead th { padding: 9px 8px; text-align: center; font-weight: 600; border: 1px solid #1e5590; white-space: nowrap; }
  .po-tbl thead th.text-left { text-align: left; }
  .po-tbl tbody tr:nth-child(even) { background: #f5f9fd; }
  .po-tbl tbody tr:hover { background: #e8f0f9; }
  .po-tbl tbody td { padding: 7px 8px; border: 1px solid #d0dce8; vertical-align: middle; }
  .po-tbl tbody td.center { text-align: center; }
  .po-tbl tfoot tr { background: #eaf1fa; }
  .po-tbl tfoot td { padding: 8px 10px; border: 1px solid #c0cfe0; font-weight: 700; font-size: 12px; }
  .po-tbl tfoot tr.total-row { background: #2c6fad; color: #fff; }
  .po-tbl tfoot tr.total-row td { border-color: #1e5590; }

  .po-input { height: 36px; text-align: center; border: 1px solid #c5d5e8; border-radius: 3px; font-size: 12px; padding: 4px 6px; }
  .po-input:focus { border-color: #2c6fad; outline: none; box-shadow: 0 0 0 2px rgba(44,111,173,.15); }
  .po-input-right { text-align: right; }

  .po-tbl-wrap { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; box-shadow: 0 1px 4px rgba(44,111,173,.07); }
  .po-tbl-header { background: #1a4f8a; color: #fff; padding: 9px 14px; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; }
</style>

<div class="po-tbl-wrap">
  <div class="po-tbl-header">
    <i class="fa fa-list-alt"></i> Daftar Barang — Belum Dibuatkan PO
  </div>
  <table id="table_brg_po" class="po-tbl">
    <thead>
      <tr>
        <th width="28px"><input type="checkbox" onClick="checkAll(this);" style="cursor:pointer;width:15px;height:15px"></th>
        <th width="35px">No</th>
        <th width="5%" class="text-left">Kode</th>
        <th class="text-left">Nama Barang</th>
        <th width="14%">Referensi Vendor</th>
        <th width="6%">Satuan</th>
        <th width="5%">Rasio</th>
        <th width="6%">Jml Pesan</th>
        <th width="9%">Harga Satuan</th>
        <th width="6%">Disc (%)</th>
        <th width="8%">Disc (Rp)</th>
        <th width="6%">PPN (%)</th>
        <th width="9%">Total</th>
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

        <td class="center">
          <input type="checkbox" class="checkbox_brg" id="checkbox_brg_<?php echo $row_dt[0]->kode_brg?>" value="<?php echo $row_dt[0]->kode_brg?>" onClick="checkOne('<?php echo $row_dt[0]->kode_brg?>');" style="cursor:pointer;width:15px;height:15px" name="is_checked[<?php echo $row_dt[0]->kode_brg?>]">
          <input type="hidden" name="id_tc_permohonan[<?php echo $row_dt[0]->kode_brg?>]" id="id_tc_permohonan" value="<?php echo $row_dt[0]->id_tc_permohonan?>">
        </td>

        <td class="center"><?php echo $no?></td>
        <td style="font-size:11px;color:#888"><?php echo $row_dt[0]->kode_brg?></td>
        <td><strong style="font-size:12px"><?php echo $row_dt[0]->nama_brg?></strong></td>

        <td>
          <?php
            $vendors = $history_po[$row_dt[0]->kode_brg];
            echo $this->master->custom_selection_ref_vendor_po(array('data' => $vendors, 'label' => 'namasupplier', 'value' => 'kodesupplier'), '','reff_vendor_'.$row_dt[0]->kode_brg.'','reff_vendor_'.$row_dt[0]->kode_brg.'','form-control input-sm','onchange="changeVendor('."'".$row_dt[0]->kode_brg."'".')"','','');
          ?>
        </td>

        <td class="center"><?php echo $row_dt[0]->satuan_besar?></td>

        <td class="center">
          <input type="text" name="rasio[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_rasio_<?php echo $row_dt[0]->kode_brg?>" class="po-input form-control" style="width:55px" value="<?php echo $row_dt[0]->rasio?>" disabled>
        </td>

        <td class="center" id="jml_permohonan_<?php echo $row_dt[0]->kode_brg?>">
          <strong><?php echo array_sum($jumlah_pesan_arr[$key_dt])?></strong>
          <input type="hidden" name="jml_permohonan[<?php echo $row_dt[0]->kode_brg?>]" id="form_jml_permohonan_<?php echo $row_dt[0]->kode_brg?>" value="<?php echo array_sum($jumlah_pesan_arr[$key_dt])?>">
        </td>

        <?php
          $harga_dasar = ($flag=='medis')?round($row_dt[0]->harga_po_terakhir / 1.1): round($row_dt[0]->harga_po_terakhir);
          $jumlah_harga_dasar_satuan_besar = round($harga_dasar * $row_dt[0]->rasio);
          $history = isset($history_po[$row_dt[0]->kode_brg][0])?$history_po[$row_dt[0]->kode_brg][0]:[];
          $discount = isset($history['discount'])?$history['discount']:0;
        ?>

        <td class="center">
          <input type="text" name="harga_satuan[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_harga_satuan_<?php echo $row_dt[0]->kode_brg?>" class="po-input po-input-right format_number form-control" value="<?php echo isset($history['harga_satuan'])?$history['harga_satuan']:'0'; ?>" onchange="inputHargaSatuan('<?php echo $row_dt[0]->kode_brg?>')" disabled>
          <input type="hidden" name="harga_satuan_val[<?php echo $row_dt[0]->kode_brg?>]" id="hidden_form_input_harga_satuan_<?php echo $row_dt[0]->kode_brg?>" value="<?php echo isset($history['harga_satuan'])?$history['harga_satuan']:''; ?>">
        </td>

        <td class="center">
          <input type="text" name="diskon[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_diskon_<?php echo $row_dt[0]->kode_brg?>" class="po-input form-control" value="<?php echo number_format($discount, 2)?>" onchange="inputDisc('<?php echo $row_dt[0]->kode_brg?>')" disabled>
          <input type="hidden" name="diskon_val[<?php echo $row_dt[0]->kode_brg?>]" id="nominal_diskon_<?php echo $row_dt[0]->kode_brg?>" class="diskon" value="0">
        </td>

        <td class="center">
          <input type="text" onchange="inputDiscRp('<?php echo $row_dt[0]->kode_brg?>')" name="potongan_diskon[<?php echo $row_dt[0]->kode_brg?>]" id="potongan_diskon_<?php echo $row_dt[0]->kode_brg?>" class="po-input po-input-right format_number potongan_diskon form-control" style="width:90px" value="0" disabled>
        </td>

        <td class="center">
          <input type="text" name="ppn[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_ppn_<?php echo $row_dt[0]->kode_brg?>" class="po-input form-control" onchange="inputPpn('<?php echo $row_dt[0]->kode_brg?>')" value="<?php echo PPN; ?>" disabled>
          <input type="hidden" name="ppn_val[<?php echo $row_dt[0]->kode_brg?>]" id="nominal_ppn_<?php echo $row_dt[0]->kode_brg?>" class="ppn" value="0">
        </td>

        <td class="center">
          <input type="text" name="total[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_total_<?php echo $row_dt[0]->kode_brg?>" class="po-input po-input-right format_number form-control" value="0" readonly disabled>
          <input type="hidden" name="total_val[<?php echo $row_dt[0]->kode_brg?>]" id="nominal_total_<?php echo $row_dt[0]->kode_brg?>" class="total" value="0">
        </td>

      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="12" style="text-align:right;color:#555">DPP (Dasar Pengenaan Pajak)</td>
        <td style="text-align:right">
          <input type="text" class="po-input po-input-right format_number form-control" name="total_dpp" id="total_dpp" value="" readonly>
          <input type="hidden" name="total_dpp_val" id="total_dpp_val" value="" readonly>
        </td>
      </tr>
      <tr>
        <td colspan="12" style="text-align:right;color:#555">PPN</td>
        <td style="text-align:right">
          <input type="text" class="po-input po-input-right format_number form-control" name="total_ppn" id="total_ppn" value="" readonly>
          <input type="hidden" name="total_ppn_val" id="total_ppn_val" value="" readonly>
        </td>
      </tr>
      <tr class="total-row">
        <td colspan="12" style="text-align:right">TOTAL</td>
        <td style="text-align:right">
          <input type="hidden" name="total_potongan_diskon_val" id="total_potongan_diskon_val" value="" readonly>
          <input type="text" class="po-input po-input-right format_number form-control" name="total_nett" id="total_nett" style="background:#fff;color:#1a4f8a;font-weight:700" value="" readonly>
          <input type="hidden" name="total_nett_val" id="total_nett_val" value="" readonly>
        </td>
      </tr>
    </tfoot>
  </table>
</div>
