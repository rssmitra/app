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
      <span style="font-size:12px; font-weight:bold">Barang yang belum dibuatkan PO</span>
      <table style="font-size:11px;" width="100%">
        <tr style="background: darkseagreen">
          <th class="center" width="">No</th>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th class="center">Jumlah Permohonan</th>
          <th class="center">Jumlah Brg yang di ACC</th>
          <th class="center">Satuan Besar</th>
          <th class="center">Rasio</th>
          <th class="left">Keterangan</th>
        </tr>
        <?php $no=0; foreach($dt_detail_brg as $row_dt) : if($row_dt->status_po != 1) :$no++?>
          <tr>
            <td class="center"><?php echo $no?></td>
            <td><?php echo $row_dt->kode_brg?></td>
            <td><?php echo $row_dt->nama_brg?></td>
            <td class="center"><?php echo number_format($row_dt->jumlah_besar_po, 2)?></td>
            <td class="center"><?php echo number_format($row_dt->jml_acc_penyetuju, 2)?></td>
            <td class="center"><?php echo $row_dt->satuan_besar?></td>
            <td class="center"><?php echo $row_dt->rasio?></td>
            <td class="left"><?php echo $row_dt->keterangan?></td>
          </tr>
        <?php endif; endforeach;?>
      </table>
      <br>
      <span style="font-size:12px; font-weight:bold">Barang yang sudah dibuatkan PO</span>
      <table width="100%">
        <tr style="font-size:11px; background-color:#428bca;color:white">
          <th class="center" width="">No</th>
          <th class="left">Nomor PO</th>
          <th class="left">Tanggal PO</th>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th class="center">Jumlah</th>
          <th class="center">Satuan</th>
          <th class="center">Rasio</th>
          <th class="right">Harga Satuan</th>
          <th class="right">Total</th>
          <th class="center">Rollback</th>
        </tr>
        <?php 
          $no=0; 
          foreach($dt_detail_brg as $row_dt) : 
            if($row_dt->status_po == 1) : $no++;
        ?>
          <tr>
            <td class="center"><?php echo $no?></td>
            <td><?php echo ($row_dt->no_po)?$row_dt->no_po:'<span class="red">PO telah dihapus</span>'?></td>
            <td><?php echo $this->tanggal->fieldDate($row_dt->tgl_po)?></td>
            <td><?php echo $row_dt->kode_brg?></td>
            <td><?php echo $row_dt->nama_brg?></td>
            <td class="center"><?php echo number_format($row_dt->jml_acc_penyetuju, 2)?></td>
            <td class="center"><?php echo $row_dt->satuan_besar?></td>
            <td class="center"><?php echo $row_dt->content_po?></td>
            <td align="right"><?php echo number_format($row_dt->harga_satuan_po, 2).',-'?></td>
            <td align="right"><?php echo number_format($row_dt->jumlah_harga_po, 2).',-'?></td>
            <td align="center"><?php echo ($row_dt->no_po)?'-':'<a href="#" title="Rollback" onclick="rollback_status('.$row_dt->id_tc_permohonan_det.')" class="red"><b>rollback</b></a>'?></td>
          </tr>
        <?php endif; endforeach;?>
      </table>
    <!-- PAGE CONTENT ENDS -->

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


