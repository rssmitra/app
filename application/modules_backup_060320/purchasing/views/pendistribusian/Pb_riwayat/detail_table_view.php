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
      <span style="font-size:12px; font-weight:bold">RINCIAN PENERIMAAN BARANG</span>
      <table style="font-size:11px;" width="100%">
        <tr>
          <th class="center" width="">No</th>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th class="center">Jumlah Pesan</th>
          <th class="center">Jumlah Kirim</th>
          <th class="center">Satuan Besar</th>
          <th class="center">Rasio</th>
          <th class="center">Harga Satuan</th>
          <th class="center">Total</th>
        </tr>
        <?php $no=0; foreach($dt_detail_brg as $row_dt) : $no++?>
          <tr>
            <td class="center"><?php echo $no?></td>
            <td><?php echo $row_dt->kode_brg?></td>
            <td><?php echo $row_dt->nama_brg?></td>
            <td class="center"><?php echo $row_dt->jumlah_pesan?></td>
            <td class="center"><?php echo $row_dt->jumlah_kirim?></td>
            <td class="center"><?php echo $row_dt->satuan_besar?></td>
            <td class="center"><?php echo $row_dt->rasio_penerimaan?></td>
            <td align="right"><?php echo number_format($row_dt->harga_net).',-'?></td>
            <td align="right"><?php echo number_format($row_dt->dpp).',-'?></td>
          </tr>
        <?php endforeach;?>
      </table>
    <!-- PAGE CONTENT ENDS -->

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


