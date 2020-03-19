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

    <!-- PAGE rasio BEGINS -->
      <span style="font-size:14px; font-weight:bold">(<?php echo isset($dt_detail_brg[0]->nomor_permintaan) ? 'PERMINTAAN NOMOR '.$dt_detail_brg[0]->nomor_permintaan : 'Tidak ada barang'; ?>)</span>
      <table class="table table-bordered" style="font-size:11px;" width="100%">
        <tr style="background-color: #d2d2d2">
          <th class="center" width="30px">No</th>
          <th style="width: 100px">Kode Barang</th>
          <th>Nama Barang</th>
          <th class="center" style="width: 100px">Jumlah<br>Permintaan</th>
          <th class="center" style="width: 100px">Satuan<br>Kecil</th>
          <th class="center" style="width: 100px">Konversi<br>Satuan Besar</th>
          <th class="center" style="width: 100px">Harga Beli<br>PO Terakhir</th>
          <th class="center" style="width: 100px">Total<br>Biaya</th>
          <th class="left" style="width: 50px">&nbsp;</th>
        </tr>
        <?php 
          $no=0; 
          $arr_total_biaya = array();
          foreach($dt_detail_brg as $row_dt) : $no++;
          $konversi = $row_dt->jumlah_permintaan / $row_dt->rasio;
          $total_biaya = $row_dt->jumlah_permintaan * $row_dt->harga_beli;
          $arr_total_biaya[] = $total_biaya;
          // tandain yang sekiranya permintaan bermasalah
          $color = ( $total_biaya > 5000000 ) ? 'red' : 'black' ;
        ?>
          <tr>
            <td class="center"><?php echo $no?></td>
            <td><?php echo $row_dt->kode_brg?></td>
            <td><?php echo $row_dt->nama_brg?></td>
            <td class="center"><?php echo $row_dt->jumlah_permintaan;?></td>
            <td class="center"><?php echo $row_dt->satuan_kecil?></td>
            <td class="center"><?php echo number_format($konversi, 2).' '.$row_dt->satuan_besar?></td>
            <td align="right"><?php echo number_format($row_dt->harga_beli).',-'?></td>
            <td align="right"><span style="color:<?php echo $color; ?>"><?php echo number_format($total_biaya).',-'?></span></td>
            <td class="center"><a href="#" class="btn btn-xs btn-white btn-danger"><i class="fa fa-trash"></i></a></td>
          </tr>
        <?php endforeach;?>
        <tr>
            <td align="right" colspan="7"><b>Total</b></td>
            <td align="right"><b><?php echo number_format(array_sum($arr_total_biaya)).',-'?></b></td>
          </tr>
      </table>
    <!-- PAGE rasio ENDS -->

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


