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

<style>
  .det-wrap { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; margin: 6px 0 10px; }
  .det-hdr { background: #1a4f8a; color: #fff; padding: 8px 14px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  .det-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
  .det-tbl thead tr { background: #2c6fad; color: #fff; }
  .det-tbl thead th { padding: 8px 10px; text-align: center; font-weight: 600; border: 1px solid #1e5590; }
  .det-tbl tbody tr:nth-child(even) { background: #f5f9fd; }
  .det-tbl tbody tr:hover { background: #e8f0f9; }
  .det-tbl tbody td { padding: 7px 10px; border: 1px solid #d0dce8; vertical-align: middle; }
  .det-tbl .footer-row td { background: #eef4fb; font-weight: 600; border: 1px solid #d0dce8; padding: 7px 10px; }
</style>

<form class="form-horizontal" method="post" id="form_permintaan" action="<?php echo site_url('purchasing/persetujuan_pemb/App_persetujuan_pemb/process')?>" enctype="multipart/form-data">

  <div class="det-wrap">
    <div class="det-hdr">
      <i class="fa fa-list-alt"></i>
      <?php echo isset($dt_detail_brg[0]->nomor_permintaan) ? 'Permintaan Nomor '.$dt_detail_brg[0]->nomor_permintaan : 'Tidak ada barang'; ?>
    </div>
    <table class="det-tbl">
      <thead>
        <tr>
          <th width="35px">No</th>
          <th width="110px">Kode Barang</th>
          <th>Nama Barang</th>
          <th width="90px">Jml Permintaan</th>
          <th width="80px">Satuan Kecil</th>
          <th width="120px">Konversi Satuan Besar</th>
          <th width="110px">Harga Beli PO Terakhir</th>
          <th width="110px">Total Biaya</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $no=0;
          $arr_total_biaya = array();
          foreach($dt_detail_brg as $row_dt) : $no++;
          $konversi = $row_dt->jumlah_permintaan / $row_dt->rasio;
          $total_biaya = $row_dt->jumlah_permintaan * $row_dt->harga_beli;
          $arr_total_biaya[] = $total_biaya;
          $color = ( $total_biaya > 5000000 ) ? 'red' : 'inherit';
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
        </tr>
        <?php endforeach;?>
        <tr class="footer-row">
          <td colspan="7" align="right">Total</td>
          <td align="right"><?php echo number_format(array_sum($arr_total_biaya)).',-'?></td>
        </tr>
      </tbody>
    </table>
  </div>

</form>
