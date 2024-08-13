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
<div class="row" style="padding-left: 44px">
  <div class="col-xs-12">

    <!-- PAGE rasio BEGINS -->
      <span style="font-size:12px; font-weight:bold; padding-bottom: 10px"><?php echo isset($dt_detail_brg[0]->kode_retur) ? 'DETAIL ITEM BARANG RETUR ': 'Tidak ada barang'; ?></span>
      <table class="table table-bordered" style="width: 80%">
        <tr style="background: #f1f1f1">
          <th class="center" width="30px">No</th>
          <th style="width: 100px">Kode Barang</th>
          <th>Nama Barang</th>
          <th class="center" style="width: 150px">Jumlah Retur</th>
          <th class="center" style="width: 150px">Satuan Kecil</th>
          <th class="center" style="width: 250px">Keterangan</th>
        </tr>
        <?php 
          $no=0; 
          foreach($dt_detail_brg as $row_dt) : $no++;
        ?>
          <tr>
            <td class="center"><?php echo $no?></td>
            <td><?php echo $row_dt->kode_brg?></td>
            <td><?php echo $row_dt->nama_brg?></td>
            <td class="center"><?php echo $row_dt->jumlah;?></td>
            <td class="center"><?php echo $row_dt->satuan_kecil?></td>
            <td class="center"><?php echo $row_dt->alasan?></td>
          </tr>
        <?php endforeach;?>
      </table>
    <!-- PAGE rasio ENDS -->

  </div><!-- /.col -->
</div><!-- /.row -->


