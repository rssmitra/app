<script type="text/javascript">

  function checkAll(elm) {

    if($(elm).prop("checked") == true){
      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>').each(function(){
        var kode_brg = $(this).val();
        $('#form_input_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val( $('#jml_permohonan_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').text() );
          $(this).prop("checked", true);
      });
    }else{
      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>').prop("checked", false);
      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>').each(function(){
        var kode_brg = $(this).val();
        $('#form_input_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val( '' );
      });
    }

  }

  function checkOne(elm) {

    if($(elm).prop("checked") == true){
        var kode_brg = $(elm).val();
        $('#form_input_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val( $('#jml_permohonan_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').text() );
          $(elm).prop("checked", true);
    }else{
      $(elm).prop("checked", false);
        var kode_brg = $(elm).val();
        $('#form_input_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val( '' );
    }

  }

</script>
<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_permintaan" action="<?php echo site_url('purchasing/persetujuan_pemb/App_persetujuan_pemb/process')?>" enctype="multipart/form-data" >

    <!-- PAGE CONTENT BEGINS -->

      <table class="" style="font-size:12px;" width="100%">
        <tr>
          <th class="center"><input type="checkbox" onClick="checkAll(this);" style="cursor:pointer"></th>
          <th class="center" width="">No</th>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th class="center">Jumlah Pesan</th>
          <th class="center">Satuan Besar</th>
          <th class="center">Rasio</th>
          <th class="center">Harga<br>Satuan Besar</th>
          <th class="center">Discount (%)</th>
          <th class="center">PPN (%)</th>
          <th class="center">Total</th>
        </tr>
        <?php $no=0; foreach($dt_detail_brg as $row_dt) : if($row_dt->status_po != 1) :$no++?>
          <tr>
            <td class="center"><input type="checkbox" class="checkbox_brg_<?php echo $flag?>_<?php echo $row_dt->id_tc_permohonan_det?>"
            id="checkbox_brg_<?php echo $flag?>_<?php echo $row_dt->id_tc_permohonan_det?>_<?php echo $row_dt->kode_brg?>" class="form-control" value="<?php echo $row_dt->kode_brg?>" onClick="checkOne(this);" style="cursor:pointer"></td>
            <td class="center"><?php echo $no?></td>
            <td><?php echo $row_dt->kode_brg?></td>
            <td><?php echo $row_dt->nama_brg?></td>
            <td class="center"><?php echo $row_dt->jumlah_besar_acc?></td>
            <td class="center"><?php echo $row_dt->satuan_besar?></td>
            <td class="center"><?php echo $row_dt->rasio?></td>
            <td class="center">
              <?php $harga_satuan = ($row_dt->harga_po_terakhir > $row_dt->master_harga) ? $row_dt->harga_po_terakhir : $row_dt->master_harga?>
              <input type="text" name="harga_satuan_besar" style="width:80px; text-align: right" value="<?php echo number_format($harga_satuan)?>">
              <input type="hidden" name="harga_satuan_besar_hidden" style="width:80px; text-align: right" value="<?php echo $harga_satuan?>">
            </td>
            <td class="center">
              <input type="text" name="harga_satuan_besar" style="width:80px; text-align: center" value="0">
            </td>
            <td class="center">
              <input type="text" name="harga_satuan_besar" style="width:80px; text-align: center" value="0">
            </td>
            <td align="right">
              <?php 
                $harga_total = $harga_satuan * $row_dt->jumlah_besar_acc; 
                $arr_harga_satuan[] = $harga_total;
              ?>
              <?php echo number_format($harga_total).',-'?>
              <input type="hidden" name="harga_satuan_besar_hidden" style="width:80px; text-align: right" value="<?php echo $harga_total?>" readonly>
            </td>
          </tr>
        <?php endif; endforeach;?>

        <tr style="font-size:12px; font-weight:bold">
          <td colspan="10" align="right">DPP</td>
          <td align="right">
              <?php $ttl_dpp = array_sum($arr_harga_satuan); echo number_format($ttl_dpp).',-'; ?>
              <input type="hidden" name="harga_satuan_besar_hidden" style="width:80px; text-align: right" value="<?php echo $harga_total?>" readonly>
          </td>
        </tr>
        <tr style="font-size:12px; font-weight:bold">
          <td colspan="10" align="right">PPN</td>
          <td align="right">
              <?php echo number_format(0).',-'?>
              <input type="hidden" name="harga_satuan_besar_hidden" style="width:80px; text-align: right" value="<?php echo $harga_total?>" readonly>
          </td>
        </tr>
        <tr style="font-size:12px; font-weight:bold">
          <td colspan="10" align="right">TOTAL</td>
          <td align="right">
              <?php 
                $total_all = $ttl_dpp + 0;
                echo number_format($total_all).',-';
              ?>
              <input type="hidden" name="harga_satuan_besar_hidden" style="width:80px; text-align: right" value="<?php echo $harga_total?>" readonly>
          </td>
        </tr>
      </table>
    <!-- PAGE CONTENT ENDS -->

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


