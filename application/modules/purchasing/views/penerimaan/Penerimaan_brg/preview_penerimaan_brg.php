<body>
  <table width="100%" border="0">
    <tr>
      <td width="80px"><img src="<?php echo base_url().COMP_ICON; ?>" alt="" width="70px"></td>
      <td valign="bottom" width="500px"><b><span style="font-size: 18px"><?php echo COMP_FULL; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
      <td align="right"><div id="barcodeTarget" class="barcodeTarget"></div></td>
    </tr>
  </table>
  <hr>
  <table id="no-border" style="width: 100% !important;">
    <tr>
      <td width="50%">
        <table>
          <tr>
            <td width="150px">Nomor Penerimaan</td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse"><?php echo $penerimaan->kode_penerimaan?></td>
          </tr>
          <tr>
            <td>Tanggal</td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse"><?php echo $this->tanggal->formatDatedmY($penerimaan->tgl_penerimaan); ?></td>
          </tr>
          <tr>
            <td>Jenis Permintaan</td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse">Rutin</td>
          </tr>
          <tr>
            <td>Total Harga</td>
            <td style="background-color: #FFF;color: #0a0a0a;font-weight: bold; border: 1px solid #FFF; border-collapse: collapse">Rp. <?php echo number_format($penerimaan->total_stl_ppn)?>,-</td>
          </tr>
        </table>
      </td>
      <td width="50%" valign="top">
        Kepada Yth :<br>
        <b><?php echo $penerimaan->namasupplier?></b><br>
        <?php echo $penerimaan->alamat?><br>
        <?php echo $penerimaan->telpon1?><br>
      </td>
    </tr>
  </table>
  <hr>
  <center><span style="font-size: 16px"><strong>BUKTI PENERIMAAN BARANG</strong></span></center>
  <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
    <thead>
        <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
          <td rowspan="2" style="text-align:center; width: 30px; border: 1px solid black; border-collapse: collapse">No</td>
          <td rowspan="2" style="border: 1px solid black; border-collapse: collapse">Kode & Nama Barang</td>
          <td rowspan="2" style="text-align:center; width: 50px; border: 1px solid black; border-collapse: collapse">Rasio</td>
          <td rowspan="2" style="text-align:center; width: 70px; border: 1px solid black; border-collapse: collapse">Satuan</td>
          <td rowspan="2" style="text-align:center; width: 60px; border: 1px solid black; border-collapse: collapse">Jumlah Pesan</td>
          <td rowspan="2" style="text-align:center; width: 60px; border: 1px solid black; border-collapse: collapse">Jumlah Kirim Sebelumnya</td>
          <td rowspan="2" style="text-align:center; width: 60px; border: 1px solid black; border-collapse: collapse">Jumlah Kirim Sekarang</td>
          <!-- <td rowspan="2" style="text-align:center; width: 75px; border: 1px solid black; border-collapse: collapse">Harga Satuan</td>
          <td colspan="2" style="text-align:center; width: 70px; border: 1px solid black; border-collapse: collapse">Diskon</td>
          <td rowspan="2" style="text-align:center; width: 75px; border: 1px solid black; border-collapse: collapse">Total Harga</td> -->
        </tr>
        <!-- <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
          <td style="text-align:center; width: 60px; border: 1px solid black; border-collapse: collapse">%</td>
          <td rowspan="2" style="text-align:center; width: 60px; border: 1px solid black; border-collapse: collapse">Rp</td>
        </tr> -->
    </thead>
    <tbody>
        <?php 
          $no=0; 
          $total_diterima = [];
          foreach($penerimaan_data as $key_dt=>$row_dt) : $no++; 
          if(count($row_dt) > 0){
            foreach($row_dt as $key_row=>$row_sub_data){
              $jumlah_pesan[$key_dt][] = $row_sub_data->jumlah_kirim_decimal;
              $jumlah_harga_netto[$key_dt][] = $row_sub_data->harga_satuan;
              if($key_row != 0){
                $total_diterima[] = $row_sub_data->jumlah_kirim_decimal;
              }
            }
          }else{
            $jumlah_pesan[$key_dt][] = $row_dt[0]->jumlah_kirim_decimal;
            $jumlah_harga_netto[$key_dt][] = $row_dt[0]->harga_satuan;
          }
          
          $total_harga = array_sum($jumlah_harga_netto[$row_dt[0]->kode_brg]) - $row_dt[0]->discount_harga;
          
            
        ?>
        <tr>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $no?></td>
          <td style="border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->kode_brg.' - '.$row_dt[0]->nama_brg?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->content?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->satuan_besar?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->jumlah_pesan_decimal?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($total_diterima))?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->jumlah_kirim_decimal?></td>
          <!-- <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($row_dt[0]->harga_satuan).',-'; ?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt[0]->discount; ?></td>
          <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($row_dt[0]->discount_harga).',-'; ?></td>
          <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($total_harga).',-';?></td> -->
        </tr>
        <?php endforeach;?>

        <!-- <tr>
          <td colspan="9" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">DPP </td>
          <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($penerimaan->total_sbl_ppn)?></td>
        </tr>
        <tr>
          <td colspan="9" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">PPN </td>
          <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($penerimaan->ppn)?></td>
        </tr>

        <tr>
          <td colspan="9" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Total </td>
          <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($penerimaan->total_stl_ppn)?></td>
        </tr>
        <tr>
          <td colspan="10" style="text-align:right; border: 1px solid black; border-collapse: collapse">Terbilang : 
          <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang($penerimaan->total_stl_ppn))?> Rupiah"</i></b>
          </td>
        </tr> -->
    </tbody>
  </table>
  <center>
        <button class="btn btn-sm btn-success" onclick="getMenu('purchasing/penerimaan/Riwayat_penerimaan_brg/view_data?flag=<?php echo $flag?>')">Kembali ke Riwayat</button>
        <button class="btn btn-sm btn-primary" onclick="getMenu('purchasing/pendistribusian/Pengiriman_unit/form_pengiriman_unit?ID=<?php echo $id_penerimaan; ?>&flag=<?php echo $flag?>')">Distribusikan ke Unit/Depo</button>
  </center>
</body>