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

    <h4>Persetujuan Pembelian Barang</h4>

    <table>
      <tr>
        <td width="20%" style="padding-bottom:5px;padding-top:5px">Nomor Persetujuan</td>
        <td><input type="text" name="" class="form-control" placeholder="Auto" readonly></td>

        <td width="20%" rowspan="2" valign="top" style="padding-top:5px">Keterangan</td>
        <td rowspan="2"><textarea class="form-control" style="height:50px !important"></textarea></td>

      </tr>

      <tr>
        <td>Tanggal</td>
        <td><input type="text" name="" class="form-control" value="<?php echo date('Y-m-d')?>"></td>
      </tr>
    </table>

    <!-- PAGE CONTENT BEGINS -->
      <h4>Rincian Barang</h4>
      <table class="table table-bordered table-hovered" style="font-size:11px">
        <tr>
          <th class="center"><input type="checkbox" class="form-control" onClick="checkAll(this);" style="cursor:pointer"></th>
          <th class="center">No</th>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th class="center">Jumlah<br>Permohonan</th>
          <th class="center">Jumlah Brg<br>yang di ACC</th>
          <th class="center">Satuan Besar</th>
          <th class="center">Rasio</th>
        </tr>
        <?php $no=0; foreach($dt_detail_brg as $row_dt) : $no++?>
          <tr>
            <td class="center"><input type="checkbox" class="checkbox_brg_<?php echo $flag?>_<?php echo $id?>"
            id="checkbox_brg_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->kode_brg?>" class="form-control" value="<?php echo $row_dt->kode_brg?>" onClick="checkOne(this);" style="cursor:pointer"></td>
            <td class="center"><?php echo $no?></td>
            <td><?php echo $row_dt->kode_brg?></td>
            <td><?php echo $row_dt->nama_brg?></td>
            <td class="center" id="jml_permohonan_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->kode_brg?>"><?php echo $row_dt->jumlah_besar?></td>
            <td class="center"><input type="number" name="" id="form_input_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->kode_brg?>" style="width:70px;height:45px;text-align:center"></td>
            <td class="center"><?php echo $row_dt->satuan_besar?></td>
            <td class="center"><?php echo $row_dt->rasio?></td>
          </tr>
        <?php endforeach;?>
      </table>
    <!-- PAGE CONTENT ENDS -->
    <center>
      <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-times-circle"></i> Tidak Disetujui</button>
      <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-check-circle"></i> Disetujui</button>
    </center>
    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


