<style>
.body{
  font-family: Arial, Helvetica, sans-serif;
  color: black;
  text-align: left;
  background-color: white; 
  border-spacing: 5em;
}

.monotype_style{
    font-family : Monotype Corsiva, Times, Serif !important;
}

</style>

<div class="body">
  <table width="100%" border="0">
    <tr>
      <td width="10%"><img src="<?php echo base_url().COMP_ICON?>" alt="" width="90px"></td>
      <td align="left" width="90%">
        <span><b>INSTALASI FARMASI</b></span><br>
        <span><?php echo strtoupper(COMP_LONG); ?></span><br>
        <span><?php echo COMP_ADDRESS_SORT; ?></span>
      </td>
    </tr>
  </table> 
  <hr>
  <br>
  <p style="align: center; font-weight: bold;">SALINAN RESEP</p>
  <table style="width: 100%">
    <tr>
      <td width="20%">No. Resep</td>
      <td>: <?php echo $result->kode_trans_far; ?></td>
      <td width="20%">Tgl. <?php echo $this->tanggal->formatDatedmY($result->tgl_trans); ?></td>
    </tr>
    <tr>
      <td width="20%">Dari Dokter</td>
      <td colspan="3">: <?php echo $result->dokter_pengirim; ?></td>
    </tr>
    <tr>
      <td width="20%">Poli/Klinik</td>
      <td colspan="2">: <?php echo ucwords($result->nama_bagian); ?></td>
    </tr>
  </table>
    
  <div style="min-height: 500px !important; vertical-align: top">
    <?php 
      foreach($detail_obat as $row){
        if($row['flag_resep'] == 'biasa'){
          $config = array(
            'dd' => $row['dosis_per_hari'],
            'qty' => $row['dosis_obat'],
            'unit' => $row['satuan_obat'],
            'use' => $row['anjuran_pakai'],
          );
          $format_signa = $this->master->formatSigna($config);
          $jumlah_tebus_bln = (int)$row['jumlah_tebus'] + (int)$row['jumlah_obat_23'];
          echo '<span>R/</span><br>';
          echo '<div style="padding-left: 15px">';
          echo $row['nama_brg'].' &nbsp;&nbsp; No. '.$this->master->formatRomawi($jumlah_tebus_bln).'<br>';
          echo '<i>'.$format_signa.'</i>';
          // echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['dosis_per_hari'].' x '.$row['dosis_obat'].'&nbsp; '.$row['satuan_obat'].'  ('.$row['anjuran_pakai'].')<br>';
          echo ' ____________ det / nedet<br>';
          echo '</div>';
        }else{
          echo '<span>R/</span><br>';
          echo '<div style="padding-left: 15px">';
          echo '<table>';
          $first_dt = $row['racikan'][0];
          $jumlah_tebus_bln = (int)$row['jumlah_tebus'] + (int)$row['jumlah_obat_23'];
          foreach ($row['racikan'][0] as $key => $value) {

            echo '<tr>';  
            echo '<td width="70%">'.$value->nama_brg.'</td>';  
            echo '<td width="30%" style="padding-left: 10px">'.$value->jumlah.' '.strtolower($value->satuan).'</td>';  
            echo '</tr>';  
          }
          echo '</table>';
          $unit_code = $this->master->get_string_data('reff_id', 'global_parameter', array('flag' => 'satuan_obat', 'value' => ucfirst($first_dt[0]->satuan_racikan)) );
          echo '<i>m.f '.$unit_code.' dtd no. '.$this->master->formatRomawi((int)$jumlah_tebus_bln).' da in '.$unit_code.'</i> <br>';

          $config_racikan = array(
            'dd' => $first_dt[0]->dosis_per_hari,
            'qty' => $first_dt[0]->dosis_obat,
            'unit' => $first_dt[0]->satuan_racikan,
            'use' => $first_dt[0]->anjuran_pakai,
          );
          // format signa racikan

          $format_signa_racikan = $this->master->formatSigna($config_racikan);
          // echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['dosis_per_hari'].' x '.$row['dosis_obat'].'&nbsp; '.$row['satuan_obat'].'  ('.$row['anjuran_pakai'].')<br>';
          echo '<i>'.$format_signa_racikan.'</i>';
          echo ' ____________ det / nedet<br><br>';
          echo '</div>';
        }
        
      }
    ?>
    
  </div>
  
  <table>
    <tr>
      <td width="20%">Nama Pasien</td>
      <td>: <?php echo ucwords(strtolower($result->nama_pasien)); ?></td>
    </tr>
    <tr>
      <td width="20%">Umur</td>
      <td>: <?php echo $this->tanggal->AgeWithYearMonthDay($result->tgl_lhr)?></td>
    </tr>
    <tr>
      <td width="20%">Alamat</td>
      <td>: <?php echo ucwords(strtolower($result->almt_ttp_pasien)); ?></td>
    </tr>
  </table>
  <?php
  if($result->kode_dokter != null || $result->kode_dokter != 0){
      $get_dokter = $this->db->get_where('mt_karyawan', array('kode_dokter' => $result->kode_dokter))->row();
  }
  
  $ttd = isset($get_dokter) ? (!empty($get_dokter->ttd))?$get_dokter->ttd:NULL : NULL;
  $stamp_dr = isset($get_dokter) ? (!empty($get_dokter->stamp))?$get_dokter->stamp:NULL : NULL;
  $nama_dr = isset($get_dokter) ? (!empty($get_dokter->nama_pegawai))?$get_dokter->nama_pegawai:NULL : NULL;

  $ttd = ($ttd != NULL) ? '<img src="'.BASE_FILE_RM.'uploaded/ttd/'.$ttd.'" width="250px">' : '';
  $stamp = ($stamp_dr != NULL) ? '<img src="'.BASE_FILE_RM.'uploaded/ttd/'.$stamp_dr.'" style="width: 700px">' : '<u>'.$nama_dr.'</u><br>SIP. '.$get_dokter->no_sip.'';
  

  ?>

  <table width="100%" border="1" cellspacing="0" cellpadding="0" border="0">
    <tr> 
        <td width="60%"></td>
        <td align="center" width="40%">
        <?php echo $ttd; ?><br>
        <?php echo $stamp; ?>
        </td>   
    </tr>
  </table>
  
  <hr>
  <span style="font-style: italic">a copy of this recipe is generated by the system.</span>
</div>

