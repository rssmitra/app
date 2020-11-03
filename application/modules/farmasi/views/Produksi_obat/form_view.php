<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <div class="widget-body">
        <div class="widget-main no-padding">
          <div class="row" style="padding-left: 20px">
            <left><span style="font-size: 12px;"><strong><u>PRODUKSI OBAT</u></strong><br>
            No. PROD -<?php echo $value->id_obat_prod; ?> - <?php echo $value->nama_brg_prod; ?>
            </span></left><br><br>
            Bahan baku produksi :
          <table class="table-utama" style="width: 60% !important;margin-top: 0px; margin-bottom: 10px">
              <thead>
                  <tr style="background-color: #c7cccb;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse; font-weight: bold">
                    <td style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">No</td>
                    <td style="border-bottom: 1px solid black; border-collapse: collapse">Nama Obat</td>
                    <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Jumlah</td>
                    <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Satuan</td>
                    <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Harga Satuan</td>
                    <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Sub Total</td>
                  </tr>
              </thead>
                <?php 
                  $no=0; 
                  $arr_total = [];
                  foreach($komposisi as $key_dt=>$row_dt) : $no++; 
                  $subtotal = $row_dt->harga_beli * $row_dt->jumlah_obat;
                  $arr_total[] = $subtotal;
                ?>

                <tr>
                  <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
                  <td style="border-collapse: collapse"><?php echo $row_dt->nama_brg?></td>
                  <td style="text-align:center; border-collapse: collapse"><?php echo number_format($row_dt->jumlah_obat);?></td>
                  <td style="text-align: center; border-collapse: collapse"><?php echo $row_dt->satuan; ?></td>
                  <td style="text-align:right; border-collapse: collapse"><?php echo number_format($row_dt->harga_beli);?></td>
                  <td style="text-align:right; border-collapse: collapse"><?php echo number_format((int)$subtotal)?></td>
                </tr>

                <?php endforeach;?>

                    <tr>
                      <td colspan="5" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse"><b>Total</b> </td>
                      <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse; font-weight: bold"><?php echo number_format(array_sum($arr_total))?></td>
                    </tr>
                    <tr>
                      <td colspan="7" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
                      <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_total)))?> Rupiah"</i></b>
                      </td>
                    </tr>

          </table>
        </div>

      </div>

      <button type="button" onclick="getMenu('farmasi/Produksi_obat')" class="btn btn-sm btn-default">
        <i class="ace-icon fa fa-arrow-circle-left icon-on-right bigger-110"></i>
        Kembali ke sebelumnya
      </button>
      
        

    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


