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
      <span style="font-size:12px; font-weight:bold">RINCIAN BARANG PO</span>
      <table style="font-size:11px;" width="100%">
      <thead>
        <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid #cecaca; border-collapse: collapse">
          <td rowspan="2" style="text-align:center; width: 30px; border: 1px solid #cecaca; border-collapse: collapse">No</td>
          <td rowspan="2" style="border: 1px solid #cecaca; border-collapse: collapse">Kode & Nama Barang</td>
          <td rowspan="2" style="text-align:center; width: 50px; border: 1px solid #cecaca; border-collapse: collapse">Rasio</td>
          <td rowspan="2" style="text-align:center; width: 70px; border: 1px solid #cecaca; border-collapse: collapse">Satuan</td>
          <td rowspan="2" style="text-align:center; width: 80px; border: 1px solid #cecaca; border-collapse: collapse">Jumlah Pesan</td>
          <td rowspan="2" style="text-align:center; width: 80px; border: 1px solid #cecaca; border-collapse: collapse">Harga Satuan</td>
          <td colspan="2" style="text-align:center; width: 70px; border: 1px solid #cecaca; border-collapse: collapse">Diskon</td>
          <td rowspan="2" style="text-align:center; width: 75px; border: 1px solid #cecaca; border-collapse: collapse">Total Harga</td>
        </tr>
        <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid #cecaca; border-collapse: collapse">
          <td style="text-align:center; width: 60px; border: 1px solid #cecaca; border-collapse: collapse">%</td>
          <td rowspan="2" style="text-align:center; width: 60px; border: 1px solid #cecaca; border-collapse: collapse">Rp</td>
        </tr>
    </thead>
        <tbody>
        <?php 
          $no=0; 
          foreach($po_data as $key_dt=>$row_dt) : $no++; 
            $total_harga = ($row_dt[0]->harga_satuan - $row_dt[0]->discount_rp) * $row_dt[0]->jumlah_besar_acc;
            $total_harga_add_tax = $total_harga * 1.11;
            $arr_total[] = $total_harga;
            $arr_total_tax[] = $total_harga_add_tax;
        ?>
            <tr>
              <td style="text-align:center; border: 1px solid #cecaca; border-collapse: collapse"><?php echo $no?></td>
              <td style="border: 1px solid #cecaca; border-collapse: collapse"><?php echo $row_dt[0]->kode_brg.' - '.$row_dt[0]->nama_brg?></td>
              <td style="text-align:center; border: 1px solid #cecaca; border-collapse: collapse"><?php echo $row_dt[0]->content?></td>
              <td style="text-align:center; border: 1px solid #cecaca; border-collapse: collapse"><?php echo $row_dt[0]->satuan_besar?></td>
              <td style="text-align:center; border: 1px solid #cecaca; border-collapse: collapse"><?php echo $row_dt[0]->jumlah_besar_acc?></td>
              <td style="text-align:right; border: 1px solid #cecaca; border-collapse: collapse"><?php echo number_format($row_dt[0]->harga_satuan).',-'; ?></td>
              <td style="text-align:center; border: 1px solid #cecaca; border-collapse: collapse"><?php echo $row_dt[0]->discount; ?></td>
              <td style="text-align:right; border: 1px solid #cecaca; border-collapse: collapse"><?php echo number_format($row_dt[0]->discount_rp).',-'; ?></td>
              <td style="text-align:right; border: 1px solid #cecaca; border-collapse: collapse"><?php echo number_format($total_harga).',-';?></td>
            </tr>
            <?php 
              endforeach;
              $total_all = array_sum($arr_total);
              $total_all_tax = array_sum($arr_total_tax);
              $ppn = $po->ppn;
              $total_after_ppn = $total_all + $ppn;

              // echo $total_all;
            ?>

            <tr>
              <td colspan="8" style="text-align:right; padding-right: 20px; border: 0px solid #cecaca; border-collapse: collapse">DPP </td>
              <td style="text-align:right; border: 1px solid #cecaca; border-collapse: collapse"><?php echo number_format(array_sum($arr_total))?>,-</td>
            </tr>
            <tr>
              <td colspan="8" style="text-align:right; padding-right: 20px; border: 0px solid #cecaca; border-collapse: collapse">PPN </td>
              <td style="text-align:right; border: 1px solid #cecaca; border-collapse: collapse"><?php echo number_format($ppn)?>,-</td>
            </tr>

            <tr>
              <td colspan="8" style="text-align:right; padding-right: 20px; border: 0px solid #cecaca; border-collapse: collapse">Total </td>
              <td style="text-align:right; border: 1px solid #cecaca; border-collapse: collapse"><?php echo number_format($total_after_ppn)?>,-</td>
            </tr>
            <tr>
            <td colspan="9">Terbilang : 
            <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang($total_after_ppn))?> Rupiah"</i></b>
            </td>
            </tr>

    </tbody>
      </table>
    <!-- PAGE CONTENT ENDS -->

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


