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
  .det-tbl .terbilang-row td { background: #f8fafd; font-style: italic; border: 1px solid #d0dce8; padding: 7px 10px; }
</style>

<form class="form-horizontal" method="post" id="form_permintaan" action="<?php echo site_url('purchasing/persetujuan_pemb/App_persetujuan_pemb/process')?>" enctype="multipart/form-data">

  <div class="det-wrap">
    <div class="det-hdr"><i class="fa fa-list"></i> Rincian Barang PO</div>
    <table class="det-tbl">
      <thead>
        <tr>
          <th rowspan="2" width="30px">No</th>
          <th rowspan="2">Kode &amp; Nama Barang</th>
          <th rowspan="2" width="50px">Rasio</th>
          <th rowspan="2" width="70px">Satuan</th>
          <th rowspan="2" width="80px">Jumlah Pesan</th>
          <th rowspan="2" width="80px">Harga Satuan</th>
          <th colspan="2" width="120px">Diskon</th>
          <th rowspan="2" width="100px">Total Harga</th>
        </tr>
        <tr>
          <th width="60px">%</th>
          <th width="60px">Rp</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $no=0;
          foreach($po_data as $key_dt=>$row_dt) : $no++;
            $total_harga = ($row_dt[0]->harga_satuan * $row_dt[0]->jumlah_besar_acc) - $row_dt[0]->discount_rp;
            $total_harga_add_tax = $total_harga * (PPN/100);
            $arr_total[] = $total_harga;
            $arr_total_tax[] = $total_harga_add_tax;
        ?>
          <tr>
            <td class="center"><?php echo $no?></td>
            <td><?php echo $row_dt[0]->kode_brg.' - '.$row_dt[0]->nama_brg?></td>
            <td class="center"><?php echo $row_dt[0]->content?></td>
            <td class="center"><?php echo $row_dt[0]->satuan_besar?></td>
            <td class="center"><?php echo $row_dt[0]->jumlah_besar_acc?></td>
            <td align="right"><?php echo number_format($row_dt[0]->harga_satuan).',-'; ?></td>
            <td class="center"><?php echo $row_dt[0]->discount; ?></td>
            <td align="right"><?php echo number_format($row_dt[0]->discount_rp).',-'; ?></td>
            <td align="right"><?php echo number_format($total_harga).',-';?></td>
          </tr>
        <?php
          endforeach;
          $total_all = array_sum($arr_total);
          $total_all_tax = array_sum($arr_total_tax);
          $ppn = $po->ppn;
          $total_after_ppn = $total_all + $ppn;
        ?>
        <tr class="footer-row">
          <td colspan="8" align="right">DPP</td>
          <td align="right"><?php echo number_format(array_sum($arr_total))?>,-</td>
        </tr>
        <tr class="footer-row">
          <td colspan="8" align="right">PPN</td>
          <td align="right"><?php echo number_format($ppn)?>,-</td>
        </tr>
        <tr class="footer-row">
          <td colspan="8" align="right">TOTAL</td>
          <td align="right"><?php echo number_format($total_after_ppn)?>,-</td>
        </tr>
        <tr class="terbilang-row">
          <td colspan="9">Terbilang :
            <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang($total_after_ppn))?> Rupiah"</i></b>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

</form>
