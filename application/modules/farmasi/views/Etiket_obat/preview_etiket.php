<style>
.body{
  max-width: 280px;
  max-height: 230px;
  /* border: 1px solid grey; */
  font-weight: bold;
  font-size: 14px;
  font-family: Arial Narrow;
  color: black;
  text-align: left;
  background-color: white; 
}

table{
  font-weight: bold;
}

@media print{ #barPrint{
		display:none;
	}
}
.monotype_style{
    font-family : Monotype Corsiva, Times, Serif !important;
    font-size: 14px; 
  }
</style>

<?php 
  if(count($result) == 0 )
  { 
    echo '<h2>Pemberitahuan</h2>- Tidak ada etiket ditemukan - '; exit; 
  } 

  foreach( $result as $rows ) : 
?>
<center style="padding-bottom: 3px">
<div class="body" style="page-break-after: always; border: 0px solid;">
  <!-- <table>
    <tr>
      <td valign="top">
        <img src="<?php echo base_url().'assets/images/logo-black.png'?>" alt="" style="width: 40px">
      </td>
      <td align="center">
        <span style="font-size: 14px">Instalasi Farmasi <br> <?php echo strtoupper(COMP_LONG); ?></span><br>
        <span style="font-size: 11px"><?php echo COMP_ADDRESS; ?></span><br>
        <span style="font-size: 14px">Apoteker : Zora Almira, S.Farm., Apt</span><br>
        <span style="font-size: 14px">4/B.19/31.74.06.1001.025.S.2.b.d/3/-1.779.3/e/1019
      </td>
    </tr>
  </table>  -->
  <div style="width: 100%; font-size: 14px;"> 
    <!-- <div style="float:left;"> 
      <img src="<?php echo base_url().'assets/images/logo-black.png'?>" alt="" style="width: 43px">
    </div> -->
    <div style="text-align: center"> 
        INSTALASI FARMASI <?php echo strtoupper(COMP_LONG); ?><br>
        <!-- <span style="font-size: 11px"><?php echo COMP_ADDRESS_SORT; ?></span><br> -->
    </div>
    <div style="border-bottom: 1px solid; text-align: center">
      <span>Apoteker : Sendi Permana, S.Farm., Apt</span><br>
      <span style="font-size: 12px">2/B.19/31.74.06.1001.02.026.S.2.b.d/3/-1.779.3/e/2021
    </div>
  </div>
  
  <!-- nomor transaksi dan tgl transaksi -->
  <div style="width: 100%; font-size: 14px;"> 
      <span>MR: <?php echo $rows->no_mr; ?></span> 
      <span style="padding-left: 60px">Tgl Lhr: <?php echo $this->tanggal->formatDatedmY($rows->tgl_lhr); ?></span>
  </div>
  
  <!-- profil and data transaksi pasien -->
  <div style="width: 100%; font-size: 14px !important; border-bottom:1px solid black"> 
    <span>Nama Pasien</span> : <?php echo ucwords(strtolower($rows->nama_pasien)); ?><br>
    
  </div>

  <!-- nama obat dan dosis penggunaan -->
  <div style="text-align: center; padding-top: 10px">
  <?php echo strtoupper(strtolower($rows->nama_brg));?><br>
    <span>Sehari <?php echo $rows->dosis_per_hari; ?> x <?php echo $rows->dosis_obat; ?> <?php echo $rows->satuan_obat; ?>
    <?php echo $rows->anjuran_pakai; ?> </span>
  </div>

  <!-- Keterangan detail obat -->
  <div style="padding-top:10px; font-size: 11px;">
    <?php
       $jml_obat = ( $rows->jumlah_tebus ) ? ($rows->prb_ditangguhkan == 0) ? $rows->jumlah_tebus + $rows->jumlah_obat_23 : $rows->jumlah_tebus : $rows->jumlah_tebus;
    ?>
    <span>Jml Obat</span> :  <?php echo (int)$jml_obat; ?> <?php echo ucwords(strtolower($rows->satuan_kecil)); ?>
    <span style="padding-left: 30px">Tgl Resep: <?php echo $this->tanggal->formatDatedmY($rows->tgl_trans); ?></span><br />
    <span style="">Petunjuk Khusus : <?php echo $rows->catatan_lainnya; ?></span><br />
    <span>Ed</span> :  
  </div>
  
  <!-- footer -->
  <div style="text-align: center; padding-top: 10px">
    <span class="monotype_style">Semoga lekas sembuh</span>
  </div>
  
</div>
</center>
<?php endforeach;?>

<!-- <div id="options">
<button id="printpagebutton" style="font-family: arial; background: blue; color: white; cursor: pointer" onclick="printpage()" style="cursor: pointer">Print Etiket</button>
</div>


<script>
  function printpage() {
      //Get the print button and put it into a variable
      var printButton = document.getElementById("options");
      $('#option').remove()
      //Set the print button visibility to 'hidden' 
      printButton.remove = true;
      //Print the page content
      window.print()
      ('#option').remove()
      printButton.style.visibility = 'visible';
  }
</script> -->