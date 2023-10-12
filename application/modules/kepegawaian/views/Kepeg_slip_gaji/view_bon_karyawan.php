
<div class="row">
  <div class="col-xs-12">
    
    <!-- PAGE CONTENT BEGINS -->
    <center>
      <span style="font-size: 16px; font-weight: bold"><strong><u>BON OBAT KARYAWAN</u></strong><br></span>
    </center>
    <br>
    <?php $arr_tagihan = []; foreach($bon as $key=>$val) :?>
    <center>
      <div class="pull-right">
        <button onclick="PopupCenter('farmasi/Process_entry_resep/nota_farmasi/<?php echo $key; ?>')" class="btn btn-xs btn-warning" title="Nota Farmasi">
            <i class="fa fa-print dark"></i> Nota Farmasi
        </button>
      </div>
    </center>

    <table>
      <tr>
        <td width="130px">Kode Transaksi</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $key ?></td>
      </tr>
      <tr>
        <td width="100px">No. Resep</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $val[0]->no_resep ?></td>
      </tr>
      <tr>
        <td width="100px">Tanggal</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($val[0]->tgl_trans) ?></td>
      </tr>
      <tr>
        <td width="100px">Nama Pasien</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($val[0]->nama_pasien)?></td>
      </tr>
      <tr>
        <td width="100px">&nbsp;</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> &nbsp;</td>
      </tr>
      
    </table>

    <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
      <thead>
          <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">
            <td style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">No</td>
            <td style="border-bottom: 1px solid black; border-collapse: collapse">Nama Obat</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Jumlah Tebus</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Satuan</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Subtotal</td>
          </tr>
      </thead>
          <?php 
            $no=0; 
            $arr_total = [];
            foreach($val as $key_dt=>$row_dt) : 
              $no++; 
              $arr_total[] = $row_dt->total;
          ?>

            <tr>
              <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
              <td style="border-collapse: collapse"><?php echo $row_dt->nama_brg?></td>
              <td style="text-align:center; border-collapse: collapse; font-weight: bold"><?php echo $row_dt->jumlah_pesan?></td>
              <td style="text-align: center; border-collapse: collapse"><?php echo $row_dt->satuan_kecil;?></td>
              <td style="text-align:right; border-collapse: collapse"><?php echo number_format($row_dt->total)?></td>
            </tr>
          <?php endforeach;?>

            <tr>
              <td colspan="4" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Total </td>
              <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_total))?></td>
            </tr>
            <tr>
              <td colspan="4" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Potongan Karyawan (20 %) </td>
              <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse">
                <?php $potongan = array_sum($arr_total) * 0.2; echo number_format($potongan)?>
              </td>
            </tr>
            <tr>
              <td colspan="4" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Tagihan </td>
              <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse">
                <?php $tagihan = array_sum($arr_total) - $potongan; echo number_format($tagihan); $arr_tagihan[$key] = $tagihan; ?>
              </td>
            </tr>
            <tr>
              <td colspan="5" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
              <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang($tagihan))?> Rupiah"</i></b>
              </td>
            </tr>

    </table>
    
    <?php endforeach;?>
    <p style="float: right">
      <b>Total Tagihan :</b><br>
      <span style="font-size: 23px; font-weight: bold"><?php echo number_format(array_sum($arr_tagihan))?></span>
    </p>


    <!-- PAGE CONTENT ENDS -->

  </div><!-- /.col -->
</div><!-- /.row -->


