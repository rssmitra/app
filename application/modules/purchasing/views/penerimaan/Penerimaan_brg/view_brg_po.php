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
    show_modal('purchasing/penerimaan/Penerimaan_brg/form_input_batch?kode_brg='+kode_brg+'&id_penerimaan='+$('#id').val()+'&id_tc_po_det='+id_tc_po_det+'&flag='+$('#flag_string').val()+'', 'INPUT BATCH');
  }
</script>
<div class="row">
  <div class="col-xs-12">

    <!-- PAGE CONTENT BEGINS -->
      <p><strong>BARANG YANG DITERIMA</strong></p>
      <table id="table_brg_penerimaan" class="table table-bordered table-hovered" style="font-size:12px;" width="100%">
        <thead>
          <tr style="background-color: #6fb3e0; border: 1px #d8d5d5  solid">
            <th class="center" width="30px"><input type="checkbox" onClick="checkAll(this);" style="cursor:pointer; width:17px" ></th>
            <th class="center" width="50px">No</th>
            <th width="5%">Kode</th>
            <th>Nama Barang</th>
            <th class="center" width="10%">Satuan Besar</th>
            <th class="center" width="7%">Isi Kemasan</th>
            <th class="center" width="10%">Harga Satuan (HNA)</th>
            <th class="center" width="10%">Diskon PO</th>
            <th class="center" width="7%">Pesan</th>
            <th class="center" width="7%">Terkirim</th>
            <th class="center" width="7%">Diterima</th>
            <th class="center" width="10%" style="border: 1px #d8d5d5  solid">No Batch</th>
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
                  $jumlah_kirim_arr[$key_dt][] = $sub_row_dt->jumlah_kirim;
                  echo '<input type="hidden" name="id_tc_po_det['.$key_dt.']" id="id_tc_po_det_'.$key_dt.'" value="'.$sub_row_dt->id_tc_po_det.'">';
                }
              }else{
                $jumlah_pesan_arr[$key_dt][] = $row_dt[0]->jumlah_besar_acc;
                $jumlah_kirim_arr[$key_dt][] = $sub_row_dt->jumlah_kirim;
                echo '<input type="hidden" name="id_tc_po_det['.$key_dt.']" id="id_tc_po_det_'.$key_dt.'" value="'.$row_dt[0]->id_tc_po_det.'">';
              }
              $no++; 

              $sisa_blm_diterima = array_sum($jumlah_pesan_arr[$key_dt]) - array_sum($jumlah_kirim_arr[$key_dt]);
          ?>

          <tr id="tr_<?php echo $row_dt[0]->kode_brg?>" style="border: 1px #d8d5d5  solid">
            <!-- checkbox -->
            
            <?php if( array_sum($jumlah_pesan_arr[$key_dt]) > array_sum($jumlah_kirim_arr[$key_dt])) : ?>
            <td class="center" style="border: 1px #d8d5d5  solid">
              <input type="checkbox" class="checkbox_brg" id="checkbox_brg_<?php echo $row_dt[0]->kode_brg?>" class="form-control" value="<?php echo $row_dt[0]->kode_brg?>" onClick="checkOne('<?php echo $row_dt[0]->kode_brg?>');" style="cursor:pointer;width:17px" name="is_checked[<?php echo $row_dt[0]->kode_brg?>]" <?php echo (!empty($row_dt[0]->no_batch))?'checked':''; ?> >
            </td>
            <?php else: ?>
              <td class="center" style="border: 1px #d8d5d5  solid">
                <i class="fa fa-check-circle bigger-150 green"></i>
              </td>
            <?php endif; ?>
            <td class="center"><?php echo $no?></td>
            <td><?php echo $row_dt[0]->kode_brg?></td>
            <td><?php echo $row_dt[0]->nama_brg?></td>
            <!-- satuan besar -->
            <td class="center"><?php echo $row_dt[0]->satuan_besar?></td>

            <!-- rasio -->
            <td class="center">
                <input type="text" name="rasio[<?php echo $row_dt[0]->kode_brg?>]" id="form_input_rasio_<?php echo $row_dt[0]->kode_brg?>" style="width:70px;height:45px;text-align:center" value="<?php echo $row_dt[0]->content?>" >
            </td>

            <!-- harga satuan -->
            <td class="center">
              <input type="hidden" value="<?php echo $row_dt[0]->harga_satuan?>" name="harga_satuan[<?php echo $row_dt[0]->kode_brg?>]">
              <input type="hidden" value="<?php echo $row_dt[0]->harga_satuan_netto?>" name="harga_satuan_netto[<?php echo $row_dt[0]->kode_brg?>]">
              <?php echo number_format($row_dt[0]->harga_satuan, 2)?> 
            </td>
            
            <td align="center"><?php echo number_format($row_dt[0]->discount, 2)?> %</td>

            <!-- jumlah pesan -->
            <td class="center" id="jml_pesan_<?php echo $row_dt[0]->kode_brg?>">
              <?php echo array_sum($jumlah_pesan_arr[$key_dt])?>
              <input type="hidden" name="jml_pesan[<?php echo $row_dt[0]->kode_brg?>]" id="form_jml_pesan_<?php echo $row_dt[0]->kode_brg?>" style="width:70px;height:45px;text-align:center" value="<?php echo array_sum($jumlah_pesan_arr[$key_dt])?>" >
            </td>

            <!-- telah dikirim -->
            <td class="center" id="jml_tlh_dikirim_<?php echo $row_dt[0]->kode_brg?>">
              <?php echo array_sum($jumlah_kirim_arr[$key_dt])?>
              <input type="hidden" name="jml_tlh_dikirim[<?php echo $row_dt[0]->kode_brg?>]" id="form_jml_tlh_dikirim_<?php echo $row_dt[0]->kode_brg?>" style="width:70px;height:45px;text-align:center" value="<?php echo array_sum($jumlah_kirim_arr[$key_dt])?>" >
              <input type="hidden" name="discount[<?php echo $row_dt[0]->kode_brg?>]" id="form_discount_<?php echo $row_dt[0]->kode_brg?>" style="width:70px;height:45px;text-align:center" value="<?php echo $row_dt[0]->discount?>" >
              <input type="hidden" name="ppn[<?php echo $row_dt[0]->kode_brg?>]" id="form_ppn_<?php echo $row_dt[0]->kode_brg?>" style="width:70px;height:45px;text-align:center" value="<?php echo $row_dt[0]->ppn?>" >
            </td>

            <!-- penerimaan sekarang -->
            <td class="center">
                <input type="text" name="terima_<?php echo $row_dt[0]->kode_brg?>" id="form_input_terima_<?php echo $row_dt[0]->kode_brg?>" class="form-control" style="height:45px;text-align:center" value="<?php echo $sisa_blm_diterima; ?>">
                
                <!-- <input type="text" name="terima_<?php echo $row_dt[0]->kode_brg?>" id="form_input_terima_<?php echo $row_dt[0]->kode_brg?>" class="form-control" style="height:45px;text-align:center" value="<?php echo (!empty($row_dt[0]->no_batch))?$row_dt[0]->jml_diterima:''; ?>"> -->
            </td>

            <!-- btn input batch -->
            
            <?php if(empty($row_dt[0]->no_batch)) : ?>
              <td class="center" style="border: 1px #d8d5d5  solid">
                <button type="button" onclick="show_modal_input_batch('<?php echo $row_dt[0]->kode_brg?>', <?php echo $row_dt[0]->id_tc_po_det?>)" class="btn btn-xs btn-danger" id="btn_input_batch_<?php echo $row_dt[0]->kode_brg?>" disabled>Input Batch</button>
              </td>
            <?php else: ?>
              <td class="center" style="border: 1px #d8d5d5  solid; font-size: 12px">
                <a href="#" onclick="show_modal_input_batch('<?php echo $row_dt[0]->kode_brg?>', <?php echo $row_dt[0]->id_tc_po_det?>)"><?php echo $row_dt[0]->no_batch?></a>
              </td>
            <?php endif; ?>
            
          </tr>
          
          <?php 
              // endif; 
            endforeach;
          ?>

          <tr style=" border: 1px #d8d5d5  solid; font-size: 11px">
            <td class="left" colspan="12">* Silahkan lakukan scan barcode pada Box Kemasan Besar dan Kemasan Kecil</td>
          </tr>
        </tbody>
        
      </table>
    <!-- PAGE CONTENT ENDS -->


  </div><!-- /.col -->
</div><!-- /.row -->


