<script type="text/javascript">

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

    var jml_pesan = parseFloat( $('#jml_pesan_'+kode_brg+'').text() );
    var jml_kirim = parseFloat( $('#jml_tlh_dikirim_'+kode_brg+'').text() );

    var sisa_blm_kirim = jml_pesan - jml_kirim;
    if($('#checkbox_brg_'+kode_brg+'').prop("checked") == true){
      $('#tr_'+kode_brg+' input[type=text]').attr('disabled', false);
      $('#btn_input_batch_'+kode_brg+'').attr('disabled', false);
      // jumlah pesan - jumlah kirim
      $('#form_input_terima_'+kode_brg+'').val( sisa_blm_kirim );
    }else{
        $('#tr_'+kode_brg+' input[type=text]').attr('disabled', true);
        $('#btn_input_batch_'+kode_brg+'').attr('disabled', true);
        $('#form_input_terima_'+kode_brg+'').val( 0 );
    }

  }

  function show_modal_input_batch(kode_brg, id_tc_po_det){
    show_modal_medium('purchasing/penerimaan/Penerimaan_brg/form_input_batch?kode_brg='+kode_brg+'&id_penerimaan='+$('#id').val()+'&id_tc_po_det='+id_tc_po_det+'&flag='+$('#flag_string').val()+'', 'INPUT BATCH');
  }
</script>

<style>
  .tbl-brg { width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 0; }
  .tbl-brg thead tr { background: #2c6fad; color: #fff; }
  .tbl-brg thead th { padding: 9px 10px; text-align: center; font-weight: 600; border: 1px solid #1e5590; white-space: nowrap; }
  .tbl-brg thead th.text-left { text-align: left; }
  .tbl-brg tbody tr:nth-child(even) { background: #f5f9fd; }
  .tbl-brg tbody tr:hover { background: #e8f0f9; }
  .tbl-brg tbody td { padding: 8px 10px; border: 1px solid #d0dce8; vertical-align: middle; }
  .tbl-brg tbody td.center { text-align: center; }
  .tbl-brg tbody td.right { text-align: right; }

  .tbl-brg .input-rasio { width: 65px; height: 38px; text-align: center; border: 1px solid #c5d5e8; border-radius: 3px; font-size: 12px; padding: 4px 6px; }
  .tbl-brg .input-terima { height: 38px; text-align: center; border: 1px solid #c5d5e8; border-radius: 3px; font-size: 13px; font-weight: 700; padding: 4px 8px; }
  .tbl-brg .input-rasio:focus, .tbl-brg .input-terima:focus { border-color: #2c6fad; outline: none; box-shadow: 0 0 0 2px rgba(44,111,173,.15); }

  .badge-done { display: inline-block; background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; border-radius: 10px; padding: 2px 8px; font-size: 11px; }
  .badge-pending { display: inline-block; background: #fff3e0; color: #e65100; border: 1px solid #ffcc80; border-radius: 10px; padding: 2px 8px; font-size: 11px; }
  .badge-batch { display: inline-block; background: #e3f2fd; color: #1565c0; border: 1px solid #90caf9; border-radius: 3px; padding: 2px 7px; font-size: 11px; }

  .tbl-section-header { background: #1a4f8a; color: #fff; padding: 9px 14px; border-radius: 5px 5px 0 0; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; }
  .tbl-section-wrap { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; box-shadow: 0 1px 4px rgba(44,111,173,.07); }
  .tbl-section-footer { background: #f0f5fb; border-top: 1px solid #d0dce8; padding: 8px 14px; font-size: 11px; color: #777; }
</style>

<div class="tbl-section-wrap">
  <div class="tbl-section-header">
    <i class="fa fa-list-alt"></i> Daftar Barang yang Diterima
    <span style="font-weight:400;font-size:11px;margin-left:4px;opacity:.8">&mdash; Centang barang yang diterima, isi jumlah, lalu input No. Batch</span>
  </div>
  <table id="table_brg_penerimaan" class="tbl-brg">
    <thead>
      <tr>
        <th width="30px"><input type="checkbox" onClick="checkAll(this);" style="cursor:pointer;width:15px;height:15px"></th>
        <th width="40px">No</th>
        <th width="5%" class="text-left">Kode</th>
        <th class="text-left">Nama Barang</th>
        <th width="8%">Satuan</th>
        <th width="7%">Isi Kemasan</th>
        <th width="10%">Harga (HNA)</th>
        <th width="7%">Diskon PO</th>
        <th width="6%">Pesan</th>
        <th width="6%">Terkirim</th>
        <th width="7%">Diterima</th>
        <th width="12%">No. Batch</th>
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
              $jumlah_kirim_arr[$key_dt][] = $sub_row_dt->jumlah_kirim_decimal;
              echo '<input type="hidden" name="id_tc_po_det['.$key_dt.']" id="id_tc_po_det_'.$key_dt.'" value="'.$sub_row_dt->id_tc_po_det.'">';
            }
          }else{
            $jumlah_pesan_arr[$key_dt][] = $row_dt[0]->jumlah_besar_acc;
            $jumlah_kirim_arr[$key_dt][] = $sub_row_dt->jumlah_kirim_decimal;
            echo '<input type="hidden" name="id_tc_po_det['.$key_dt.']" id="id_tc_po_det_'.$key_dt.'" value="'.$row_dt[0]->id_tc_po_det.'">';
          }
          $no++;

          $sisa_blm_diterima = array_sum($jumlah_pesan_arr[$key_dt]) - array_sum($jumlah_kirim_arr[$key_dt]);
      ?>

      <tr id="tr_<?php echo $row_dt[0]->kode_brg?>">

        <?php if( array_sum($jumlah_pesan_arr[$key_dt]) > array_sum($jumlah_kirim_arr[$key_dt])) : ?>
        <td class="center">
          <input type="checkbox" class="checkbox_brg" id="checkbox_brg_<?php echo $row_dt[0]->kode_brg?>" value="<?php echo $row_dt[0]->kode_brg?>" onClick="checkOne('<?php echo $row_dt[0]->kode_brg?>');" style="cursor:pointer;width:15px;height:15px" name="is_checked[<?php echo $row_dt[0]->kode_brg?>]" <?php echo (!empty($row_dt[0]->no_batch))?'checked':''; ?>>
        </td>
        <?php else: ?>
        <td class="center">
          <i class="fa fa-check-circle" style="color:#2e7d32;font-size:16px" title="Sudah lengkap diterima"></i>
        </td>
        <?php endif; ?>

        <td class="center"><?php echo $no?></td>
        <td style="font-size:11px;color:#888"><?php echo $row_dt[0]->kode_brg?></td>
        <td>
          <strong style="font-size:12px"><?php echo $row_dt[0]->nama_brg?></strong>
        </td>
        <td class="center"><?php echo $row_dt[0]->satuan_besar?></td>

        <!-- rasio / isi kemasan -->
        <td class="center">
          <input type="text" name="rasio[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_rasio_<?php echo $row_dt[0]->kode_brg?>" class="input-rasio" value="<?php echo $row_dt[0]->content?>">
        </td>

        <!-- harga satuan -->
        <td class="right">
          <input type="hidden" value="<?php echo $row_dt[0]->harga_satuan?>" name="harga_satuan[<?php echo $row_dt[0]->kode_brg?>]">
          <input type="hidden" value="<?php echo $row_dt[0]->harga_satuan_netto?>" name="harga_satuan_netto[<?php echo $row_dt[0]->kode_brg?>]">
          <span style="font-size:12px"><?php echo number_format($row_dt[0]->harga_satuan, 2)?></span>
        </td>

        <td class="center">
          <span class="badge-pending"><?php echo number_format($row_dt[0]->discount, 2)?>%</span>
        </td>

        <!-- jumlah pesan -->
        <td class="center" id="jml_pesan_<?php echo $row_dt[0]->kode_brg?>">
          <?php echo array_sum($jumlah_pesan_arr[$key_dt])?>
          <input type="hidden" name="jml_pesan[<?php echo $row_dt[0]->kode_brg?>]" id="form_jml_pesan_<?php echo $row_dt[0]->kode_brg?>" value="<?php echo array_sum($jumlah_pesan_arr[$key_dt])?>">
        </td>

        <!-- telah dikirim -->
        <td class="center" id="jml_tlh_dikirim_<?php echo $row_dt[0]->kode_brg?>">
          <?php echo array_sum($jumlah_kirim_arr[$key_dt])?>
          <input type="hidden" name="jml_tlh_dikirim[<?php echo $row_dt[0]->kode_brg?>]" id="form_jml_tlh_dikirim_<?php echo $row_dt[0]->kode_brg?>" value="<?php echo array_sum($jumlah_kirim_arr[$key_dt])?>">
          <input type="hidden" name="discount[<?php echo $row_dt[0]->kode_brg?>]" id="form_discount_<?php echo $row_dt[0]->kode_brg?>" value="<?php echo $row_dt[0]->discount?>">
          <input type="hidden" name="ppn[<?php echo $row_dt[0]->kode_brg?>]" id="form_ppn_<?php echo $row_dt[0]->kode_brg?>" value="<?php echo $row_dt[0]->ppn?>">
        </td>

        <!-- penerimaan sekarang -->
        <td class="center">
          <input type="text" name="terima_<?php echo $row_dt[0]->kode_brg?>" id="form_input_terima_<?php echo $row_dt[0]->kode_brg?>" class="form-control input-terima" value="<?php echo $sisa_blm_diterima; ?>">
        </td>

        <!-- btn input batch -->
        <td class="center" id="td_input_batch_<?php echo $row_dt[0]->kode_brg?>">
          <?php if(empty($row_dt[0]->no_batch)) : ?>
            <button type="button" onclick="show_modal_input_batch('<?php echo $row_dt[0]->kode_brg?>', <?php echo $row_dt[0]->id_tc_po_det?>)" class="btn btn-xs btn-danger" id="btn_input_batch_<?php echo $row_dt[0]->kode_brg?>" disabled>
              <i class="fa fa-barcode"></i> Input Batch
            </button>
          <?php else: ?>
            <a href="#" onclick="show_modal_input_batch('<?php echo $row_dt[0]->kode_brg?>', <?php echo $row_dt[0]->id_tc_po_det?>)" class="badge-batch">
              <i class="fa fa-barcode"></i> <?php echo $row_dt[0]->no_batch?>
            </a>
          <?php endif; ?>
        </td>

      </tr>

      <?php
          // endif;
        endforeach;
      ?>

    </tbody>
  </table>
  <div class="tbl-section-footer">
    <i class="fa fa-info-circle"></i> Centang barang yang akan diterima, isi jumlah penerimaan, kemudian klik <strong>Input Batch</strong> untuk memasukkan nomor batch &amp; expired date.
  </div>
</div>
