<style>
body{
  font-size: 12px;
  font-family: arial;
  border-spacing: 5em;
}

table{
  font-size: 12px;
}
 .stamp {
  transform: rotate(12deg);
  color: #555;
  font-size: 2rem;
  font-weight: 700;
  border: 0.25rem solid #555;
  display: inline-block;
  padding: 0.25rem 1rem;
  text-transform: uppercase;
  border-radius: 1rem;
  font-family: 'Courier';
  -webkit-mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/8399/grunge.png');
  -webkit-mask-size: 944px 604px;
  mix-blend-mode: multiply;
}

.is-draft {
  color: #C4C4C4;
  border: 0.5rem double #C4C4C4;
  transform: rotate(-5deg);
  font-size: 1rem;
  font-family: "Open sans", Helvetica, Arial, sans-serif;
  border-radius: 0;
  padding: 0.5rem;
}


</style>
<body>
<!-- <table width="100%" border="0">
  <tr>
    <td width="60px"><img src="<?php echo base_url().COMP_ICON?>" alt="" width="60px"></td>
    <td valign="bottom" width="320px"><b><span style="font-size: 18px"><?php echo COMP_LONG; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
  </tr>
</table>
<hr> -->

<center><span><strong><u>NOTA FARMASI</u></strong><br>
No. RSK-<?php echo $resep[0]['kode_trans_far']?> - <?php echo strtoupper($resep[0]['no_resep'])?>
</span></center>
<span style="position: absolute; margin-left:63%;transform: rotate(0deg) !important;" class="stamp is-draft">Resep Kronis</span>
<table>
  <tr style="border: 1px solid black; border-collapse: collapse">
    <td width="100px">Tanggal</td>
    <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($resep[0]['tgl_trans']) ?></td>
  </tr>
  <tr style="border: 1px solid black; border-collapse: collapse">
    <td width="100px">Nama Pasien</td>
    <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($resep[0]['nama_pasien'])?></td>
  </tr>
  <tr style="border: 1px solid black; border-collapse: collapse">
    <td width="100px">No. MR</td>
    <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $resep[0]['no_mr']?></td>
  </tr>
  <tr style="border: 1px solid black; border-collapse: collapse">
    <td width="100px">Dokter</td>
    <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($resep[0]['dokter_pengirim'])?></td>
  </tr>
  <tr style="border: 1px solid black; border-collapse: collapse">
    <td width="100px">Asal</td>
    <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $resep[0]['nama_bagian']?></td>
  </tr>
</table>

<table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
  <thead>
      <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">
        <td style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">No</td>
        <td style="border-bottom: 1px solid black; border-collapse: collapse">Uraian</td>
        <td style="text-align:center; width: 50px; border-bottom: 1px solid black; border-collapse: collapse">Jumlah</td>
        <td style="text-align:center; width: 50px; border-bottom: 1px solid black; border-collapse: collapse">Satuan</td>
        <td style="text-align:center; width: 70px; border-bottom: 1px solid black; border-collapse: collapse">Subtotal</td>
      </tr>
  </thead>
      <?php 
        $no=0; 
        $arr_total = [];
        foreach($resep as $key_dt=>$row_dt) : 
          if($row_dt['jumlah_obat_23'] > 0) :
          $no++; 
          $harga_jual = $row_dt['harga_jual'];
          $jumlah_obat = ($tipe_resep == 'resep_kronis') ? $row_dt['jumlah_obat_23'] : $row_dt['jumlah_tebus'];
          $subtotal = ($row_dt['flag_resep'] == 'racikan') ? $row_dt['jasa_r'] : (($harga_jual * $jumlah_obat) + $row_dt['jasa_r']); 
          $arr_total[] = $subtotal;
          $desc = ($row_dt['flag_resep'] == 'racikan') ? 'Jasa Racikan Obat' : $row_dt['nama_brg'];
          $satuan = ($row_dt['satuan_kecil'] != null) ? $row_dt['satuan_kecil'] : $row_dt['satuan_brg'];
      ?>

        <tr>
          <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
          <td style="border-collapse: collapse"><?php echo $desc?></td>
          <td style="text-align:center; border-collapse: collapse">
          <?php echo ($row_dt['flag_resep'] == 'racikan') ? '' : number_format($jumlah_obat);?></td>
          <td style="text-align:left; border-collapse: collapse"><?php echo $satuan?></td>
          <td style="text-align:right; border-collapse: collapse"><?php echo number_format($subtotal)?></td>
        </tr>
        <?php 
          
          if($row_dt['flag_resep'] == 'racikan') :
            foreach ($row_dt['racikan'][0] as $key => $value) {
              $jumlah_obat_23 = ($tipe_resep == 'resep_kronis') ? $value->jumlah_obat_23 : $value->jumlah ;
              $arr_total[] = ($value->harga_jual * $jumlah_obat_23);
              $subtotal_racikan = ($value->harga_jual * $jumlah_obat_23);
              echo '<tr>
                        <td style="text-align:center; border-collapse: collapse">&nbsp;</td>
                        <td style="border-collapse: collapse"> - '.$value->nama_brg.'</td>
                        <td style="text-align: center; border-collapse: collapse">'.$jumlah_obat_23.'</td>
                        <td style="text-align: left; border-collapse: collapse">'.$value->satuan.'</td>
                        <td style="text-align: right; border-collapse: collapse">'.number_format($subtotal_racikan).'</td>
                      </tr>';
            }
          endif; 
        ?>

      <?php endif; endforeach;?>

        <tr>
          <td colspan="4" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Total </td>
          <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_total))?></td>
        </tr>
        <tr>
          <td colspan="5" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
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
      <span style="font-size: 14px"><b>Petugas</b></span>
      <br>
      <br>
      <?php $decode = json_decode($resep[0]['created_by']); echo isset($decode->fullname)?$decode->fullname:$this->session->userdata('user')->fullname;?>
    </td>
  </tr>
  
</table>



<div id="options">
<button id="printpagebutton" style="font-family: arial; background: blue; color: white; cursor: pointer" onclick="printpage()" style="cursor: pointer"/>Print Nota Farmasi</button>
</div>

<script>
  function printpage() {
      //Get the print button and put it into a variable
      var printButton = document.getElementById("printpagebutton");
      //Set the print button visibility to 'hidden' 
      printButton.style.visibility = 'hidden';
      //Print the page content
      window.print()
      printButton.style.visibility = 'visible';
  }
</script>
</body>
