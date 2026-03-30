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
</style>

<div class="det-wrap">
  <div class="det-hdr">
    <i class="fa fa-history"></i> Log Penerimaan Barang
    <span style="margin-left:auto;font-weight:400;opacity:.85">Tanggal <?php echo $this->tanggal->formatDateTime($po_data[0]->tgl_penerimaan)?></span>
  </div>
  <table class="det-tbl">
    <thead>
      <tr>
        <th width="30px">No</th>
        <th width="60px">Kode</th>
        <th>Kode &amp; Nama Barang</th>
        <th width="50px">Rasio</th>
        <th width="70px">Satuan</th>
        <th width="100px">Jumlah Kirim</th>
        <th width="200px">Keterangan</th>
        <th width="200px">Update Terakhir</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $no=0;
        foreach($po_data as $key_dt=>$row_dt) : $no++;
      ?>
        <tr>
          <td class="center"><?php echo $no?></td>
          <td><?php echo $row_dt->kode_detail_penerimaan_barang?></td>
          <td><?php echo $row_dt->kode_brg.' - '.$row_dt->nama_brg?></td>
          <td class="center"><?php echo $row_dt->content?></td>
          <td class="center"><?php echo $row_dt->satuan_besar?></td>
          <td class="center"><?php echo ($row_dt->jumlah_kirim)?$row_dt->jumlah_kirim:0; ?></td>
          <td><?php echo ($row_dt->keterangan)?$row_dt->keterangan:'-'; ?></td>
          <td style="font-size:11px"><?php echo ($row_dt->updated_date)?$this->tanggal->formatDateTime($row_dt->updated_date).' - '.$row_dt->updated_by:'-'; ?></td>
        </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
