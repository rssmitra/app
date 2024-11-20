<style>
.body{
  font-family: Arial, Helvetica, sans-serif;
  color: black;
  text-align: left;
  background-color: white; 
  border-spacing: 5em;
  width: 50%;
}
</style>

<div class="body">
  <table width="100%" border="0">
    <tr>
      <td width="20%" align="center"><img src="<?php echo base_url().COMP_ICON?>" alt="" width="50px"></td>
      <td valign="bottom" width="80%"><b><span><?php echo COMP_LONG; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
    </tr>
  </table>
  <hr>

  <div style="width: 100%; text-align: center"><span ><strong><u>MEMO HT & ACE INHIBITOR</u></strong><br></div>

  <div style="width: 100%; text-align: left">
    Kepada Yth :<br>
    Farmasi RS Setia Mitra<br>
    Ditempat
    <br>
    <br>
    Dengan hormat, <br>
    Mohon pemberian candesartan untuk :<br>
    <br>
    <table>
      <tr>
        <td width="100px">Nama Pasien</td>
        <td>: <?php echo $value->nama_pasien;?></td>
      </tr>
      <tr>
        <td width="100px">Usia</td>
        <td>: <?php echo $value->umur;?> thn</td>
      </tr>
    </table>
    <br>
    <br>
    Dengan HT dan ACE inh intolerans.
    <br>
    <br>
    Demikian memo ini dibuat HANYA untuk kepentingan pengambilan resep candesartan di RS Setia Mitra.
    <br>
    <br>
    Terima Kasih.
  </div>
  
  <table style="width: 100% !important; text-align: center">
    <tr>
      <td style="text-align: left; width: 60%">&nbsp;</td>
      <td style="text-align: center; width: 40%">
        <span><b>Ttd Dokter</b></span>
        <br>

        <?php 

          echo $qr_img;
          echo "<br>";
          echo $value->dokter_pemeriksa;
          
        ?>
      </td>
    </tr>
    
  </table>
</div>
