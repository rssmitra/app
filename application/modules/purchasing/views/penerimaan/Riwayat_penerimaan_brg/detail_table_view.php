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
      <span style="font-size:12px; font-weight:bold">Log Penerimaan Barang Tanggal <?php echo $this->tanggal->formatDateTime($po_data[0]->tgl_penerimaan)?></span>
      <table style="font-size:11px;" width="100%">
      <thead>
        <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid #cecaca; border-collapse: collapse">
          <td style="text-align:center; width: 30px; border: 1px solid #cecaca; border-collapse: collapse">No</td>
          <td style="border: 1px solid #cecaca; border-collapse: collapse; width: 50px">Kode</td>
          <td style="border: 1px solid #cecaca; border-collapse: collapse">Kode & Nama Barang</td>
          <td style="text-align:center; width: 50px; border: 1px solid #cecaca; border-collapse: collapse">Rasio</td>
          <td style="text-align:center; width: 70px; border: 1px solid #cecaca; border-collapse: collapse">Satuan</td>
          <!-- <td style="text-align:center; width: 100px; border: 1px solid #cecaca; border-collapse: collapse">Jumlah Pesan</td> -->
          <td style="text-align:center; width: 100px; border: 1px solid #cecaca; border-collapse: collapse">Jumlah Kirim</td>
          <td style="width: 200px; border: 1px solid #cecaca; border-collapse: collapse">Keterangan</td>
          <td style="width: 200px; border: 1px solid #cecaca; border-collapse: collapse">Update Terakhir</td>
          <!-- <td style="text-align:center; width: 100px; border: 1px solid #cecaca; border-collapse: collapse">Harga Satuan Netto</td>
          <td style="text-align:center; width: 100px; border: 1px solid #cecaca; border-collapse: collapse">Total Harga Netto</td> -->
        </tr>
    </thead>
        <tbody>
        <?php 
          $no=0; 
          foreach($po_data as $key_dt=>$row_dt) : $no++; 
        ?>
            <tr>
              <td style="text-align:center; border: 1px solid #cecaca; border-collapse: collapse"><?php echo $no?></td>
              <td style="border: 1px solid #cecaca; border-collapse: collapse"><?php echo $row_dt->kode_detail_penerimaan_barang?></td>
              <td style="border: 1px solid #cecaca; border-collapse: collapse"><?php echo $row_dt->kode_brg.' - '.$row_dt->nama_brg?></td>
              <td style="text-align:center; border: 1px solid #cecaca; border-collapse: collapse"><?php echo $row_dt->content?></td>
              <td style="text-align:center; border: 1px solid #cecaca; border-collapse: collapse"><?php echo $row_dt->satuan_besar?></td>
              <!-- <td style="text-align:center; border: 1px solid #cecaca; border-collapse: collapse"><?php echo $row_dt->jumlah_pesan?></td> -->
              <td style="text-align:center; border: 1px solid #cecaca; border-collapse: collapse"><?php echo ($row_dt->jumlah_kirim)?$row_dt->jumlah_kirim:0; ?></td>
              <td style="border: 1px solid #cecaca; border-collapse: collapse"> <?php echo ($row_dt->keterangan)?$row_dt->keterangan:'-'; ?></td>
              <td style="border: 1px solid #cecaca; border-collapse: collapse"> <?php echo ($row_dt->updated_date)?$this->tanggal->formatDateTime($row_dt->updated_date).' - '.$row_dt->updated_by:'-'; ?></td>
            </tr>
            <?php endforeach;?>

    </tbody>
      </table>
    <!-- PAGE CONTENT ENDS -->

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


