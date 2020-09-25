<div class="row">

  <div class="col-xs-12">

    <!-- breadcrumbs -->
    <table width="100%" border="0">
      <tr>
        <td width="60px"><img src="<?php echo base_url().COMP_ICON?>" alt="" width="60px"></td>
        <td valign="bottom" width="320px"><b><span style="font-size: 18px"><?php echo COMP_LONG; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
      </tr>
    </table>
    <hr>

    <center><span style="font-size: 12px;"><strong><u>PEMBELIAN CITO</u></strong><br>
    No. PB-CITO-<?php echo $value[0]->induk_cito; ?>
    </span></center>

    <table>
      <tr>
        <td width="100px">Tanggal</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($value[0]->tgl_pembelian) ?></td>
      </tr>
      <tr>
        <td width="100px">Petugas</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($this->session->userdata('user')->fullname)?></td>
      </tr>
    </table>

    <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
      <thead>
          <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">
            <td style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">No</td>
            <td style="border-bottom: 1px solid black; border-collapse: collapse">Nama Obat</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Jumlah</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Harga Satuan</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Subtotal</td>
          </tr>
      </thead>
          <?php 
            $no=0; 
            foreach($value as $key_dt=>$row_dt) : $no++; 
            $arr_total[] = $row_dt->total_harga;
          ?>

            <tr>
              <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
              <td style="border-collapse: collapse"><?php echo $row_dt->nama_brg?></td>
              <td style="text-align:center; border-collapse: collapse"><?php echo number_format($row_dt->jumlah_kcl);?></td>
              <td style="text-align:right; border-collapse: collapse"><?php echo number_format($row_dt->harga_beli);?></td>
              <td style="text-align:right; border-collapse: collapse"><?php echo number_format((int)$row_dt->total_harga)?></td>
            </tr>

          <?php endforeach;?>

            <tr>
              <td colspan="4" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Total </td>
              <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_total))?></td>
            </tr>
            <tr>
              <td colspan="7" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
              <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_total)))?> Rupiah"</i></b>
              </td>
            </tr>

    </table>

    Catatan : Obat yang sudah dibeli tidak bisa dikembalikan
    <table style="width: 100% !important; text-align: center">
      <tr>
        <td style="text-align: left; width: 30%">&nbsp;</td>
        <td style="text-align: center; width: 40%">&nbsp;</td>
        <td style="text-align: center; width: 30%">
          <span style="font-size: 14px"><b>Petugas</b></span><br>
          <?php echo $this->session->userdata('user')->fullname; ?>
          <br>

        </td>
      </tr>
      
    </table>

  </div>

</div>
