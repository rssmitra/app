<style>

@media print {
  #printpagebutton {
    display: none;
  }
}

</style>

<center>
<?php

  if(empty($value)){
    echo '<p style="padding: 30px; color: red; font-style: italic; font-weight: bold">~ Tidak ada data ditemukan ~</p>';
    exit;
  }
  $tunjangan = $value->t_keluarga + $value->t_kerja + $value->t_jabatan + $value->t_shift + $value->t_khusus + $value->t_fungsional;
  $potongan = $value->p_absensi + $value->p_ppni + $value->p_biaya_perawatan + $value->p_apotik + $value->p_koperasi + $value->p_jamsostek + $value->p_pph21 + $value->p_bpjs;
  $lainnya = $value->lain_lain + $value->lembur + $value->insentif + $value->dkk + $value->cito + $value->case_manager + $value->oncall + $value->transport + $value->pjgk_prwt + $value->home_care + $value->fee_agent;
?>

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

<div id="options">
<button id="printpagebutton" style="font-family: arial; background: blue; color: white; cursor: pointer" onclick="printpage()" style="cursor: pointer"/>Print Slip Gaji</button>
</div>
<table class="table" style="width: 80%">
  <tr>
    <th colspan="3">
      <p style="padding: 15px; font-size: 14px; font-weight: bold; text-align: center">
        <span>RINCIAN GAJI PEGAWAI</span><br>
        BULAN <?php echo strtoupper($this->tanggal->getBulan($value->kg_periode_bln))?> TAHUN <?php echo $value->kg_periode_thn?><br><br>
        <span style="font-size: 13px; font-weight: bold"><?php echo $value->nip.' - '.$value->nama_pegawai; ?></span>
      </p>
    </th>
  <tr>

  <tr>
    <td align="center" colspan="3"><hr></td>
  </tr>
  
  <tr>
    <th width="30px" align="center">NO</th>
    <th>RINCIAN GAJI</th>
    <th>TOTAL</th>
  </tr>

  <tr>
    <td align="center" colspan="3"><hr></td>
  </tr>
  
  <tr>
    <td align="center"><b>1. </b></td>
    <td colspan="2"><b>GAJI DASAR</b></td>
  </tr>
  <?php if($value->gaji_dasar > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">Gaji Dasar Karyawan</td>
    <td align="right"><?php echo number_format($value->gaji_dasar)?></td>
  </tr>
  <?php endif; ?>

  <tr>
    <td align="center" colspan="3"><hr></td>
  </tr>

  <?php if($value->gaji_dasar > 0) :?>
  <tr>
    <td colspan="2" align="left"><b>TOTAL GAJI DASAR</b></td>
    <td align="right"><b><?php echo number_format($value->gaji_dasar)?><b></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td align="center" colspan="3"><hr></td>
  </tr>
  

  <tr>
    <td align="center"><b>2.</b></td>
    <td colspan="2"><b>TUNJANGAN</b></td>
  </tr>

  <?php if($value->t_keluarga > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">Tunjangan Keluarga</td>
    <td align="right"><?php echo number_format($value->t_keluarga)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->t_kerja > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">Tunjangan Kerja</td>
    <td align="right"><?php echo number_format($value->t_kerja)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->t_jabatan > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">Tunjangan Jabatan</td>
    <td align="right"><?php echo number_format($value->t_jabatan)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->t_shift > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">Tunjangan Shift</td>
    <td align="right"><?php echo number_format($value->t_shift)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->t_khusus > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">Tunjangan Khusus</td>
    <td align="right"><?php echo number_format($value->t_khusus)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->t_fungsional > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">Tunjangan Fungsional</td>
    <td align="right"><?php echo number_format($value->t_fungsional)?></td>
  </tr>
  <?php endif; ?>

  <tr>
    <td align="center" colspan="3"><hr></td>
  </tr>

  <tr>
    <td colspan="2" align="left"><b>TOTAL TUNJANGAN</b></td>
    <td align="right"><b><?php echo number_format($tunjangan)?></b></td>
  </tr>

  <tr>
    <td align="center" colspan="3"><hr></td>
  </tr>

  <tr>
    <td align="center"><b>3.</b></td>
    <td colspan="2"><b>PENDAPATAN LAINNYA</b></td>
  </tr>

  <?php if($value->lain_lain > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left:30px ">Lain-Lain</td>
    <td align="right"><?php echo number_format($value->lain_lain)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->lembur > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left:30px ">Lembur</td>
    <td align="right"><?php echo number_format($value->lembur)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->insentif > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left:30px ">Lembur</td>
    <td align="right"><?php echo number_format($value->insentif)?></td>
  </tr>
  <?php endif; ?>
  
  <?php if($value->cito > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left:30px ">Cito</td>
    <td align="right"><?php echo number_format($value->cito)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->case_manager > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left:30px ">Case Manager</td>
    <td align="right"><?php echo number_format($value->case_manager)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->oncall > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left:30px ">On Call</td>
    <td align="right"><?php echo number_format($value->oncall)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->transport > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left:30px ">Transport</td>
    <td align="right"><?php echo number_format($value->transport)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->pjgk_prwt > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left:30px ">PJGK PRWT</td>
    <td align="right"><?php echo number_format($value->pjgk_prwt)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->home_care > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left:30px ">Home Care</td>
    <td align="right"><?php echo number_format($value->home_care)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->fee_agent > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left:30px ">Fee Agent</td>
    <td align="right"><?php echo number_format($value->fee_agent)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->dkk > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left:30px ">Dana Kesejahteraan Karyawan</td>
    <td align="right"><?php echo number_format($value->dkk)?></td>
  </tr>
  <?php endif; ?>

  <tr>
    <td align="center" colspan="3"><hr></td>
  </tr>

  <tr>
    <td colspan="2" align="left"><b>TOTAL PENDAPATAN LAINNYA</b></td>
    <td align="right"><b><?php echo number_format($lainnya)?></b></td>
  </tr>

  <tr>
    <td align="center" colspan="3"><hr></td>
  </tr>

  <tr>
    <td align="center"><b>4.</b></td>
    <td colspan="2"><b>POTONGAN</b></td>
  </tr>

  <?php if($value->p_absensi > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">Sanksi Absensi</td>
    <td align="right"><?php echo number_format($value->p_absensi)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->p_ppni > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">PPNI/IBI</td>
    <td align="right"><?php echo number_format($value->p_ppni)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->p_biaya_perawatan > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">Biaya Perawatan</td>
    <td align="right"><?php echo number_format($value->p_biaya_perawatan)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->p_apotik > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">Bon Karyawan / Apotik</td>
    <td align="right"><?php echo number_format($value->p_apotik)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->p_koperasi > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">Koperasi</td>
    <td align="right"><?php echo number_format($value->p_koperasi)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->p_jamsostek > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">Jamsostek</td>
    <td align="right"><?php echo number_format($value->p_jamsostek)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->p_pph21 > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">PPh 21 Karyawan</td>
    <td align="right"><?php echo number_format($value->p_pph21)?></td>
  </tr>
  <?php endif; ?>

  <?php if($value->p_bpjs > 0) :?>
  <tr>
    <td></td>
    <td style="padding-left: 30px">BPJS Kesehatan</td>
    <td align="right"><?php echo number_format($value->p_bpjs)?></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td align="center" colspan="3"><hr></td>
  </tr>
  <tr>
    <td colspan="2" align="left"><b>TOTAL POTONGAN</b></td>
    <td align="right" style="font-weight: bold"><?php echo number_format($potongan)?></td>
  </tr>

  <tr>
    <td align="center" colspan="3"><hr></td>
  </tr>

  <tr>
    <td></td>
    <td style="font-weight: bold">GAJI DASAR</td>
    <td align="right"><?php echo number_format($value->gaji_dasar)?></td>
  </tr>

  <tr>
    <td></td>
    <td style="font-weight: bold">TUNJANGAN</td>
    <td align="right"><?php echo number_format($tunjangan)?></td>
  </tr>
  <tr>
    <td></td>
    <td style="font-weight: bold">PENDAPATAN LAINNYA</td>
    <td style="text-align: right"><?php echo number_format($lainnya)?></td>
  <tr>
  <tr>
    <td></td>
    <td style="font-weight: bold">GAJI KOTOR</td>
    <td style="text-align: right"><?php echo number_format($value->ttl_pendapatan)?></td>
  <tr>
  <tr>
    <td></td>
    <td style="font-weight: bold">POTONGAN</td>
    <td style="text-align: right"><?php echo number_format($potongan)?></td>
  <tr>
  <tr>
    <td align="center" colspan="3"><hr></td>
  </tr>
  <tr>
    <td style="font-weight: bold" colspan="2">GAJI DITERIMA</td>
    <td style="text-align: right; font-weight: bold"><?php echo number_format($value->gaji_diterima)?></td>
  <tr>
  
</table>
  </center>



