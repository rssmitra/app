<style>
  /* Style the table */
    .table-custom {
      border-collapse: collapse;
      border-spacing: 1px;
      width: 100%;
      border: 1px solid #ddd;
      padding: 2px
    }

</style>
<div style="overflow-x:auto; width: 100%; padding: 20px">
  <h3>RINCIAN GAJI PEGAWAI</h3>
  <table class="table">
    <thead>
      <tr>  
        <th width="40px" class="center">No</th>
        <th>NIP</th>
        <th>NAMA</th>
        <th style="text-align: right">GAJI DASAR</th>
        <th style="text-align: right">TOTAL TUNJANGAN</th>
        <th style="text-align: right">PENDAPATAN LAINNYA</th>
        <th style="text-align: right">JUMLAH GAJI</th>
        <th style="text-align: right">TOTAL POTONGAN</th>
        <th style="text-align: right">GAJI DITERIMA</th>
      </tr>
    </thead>
    <tbody>
      <?php 
        $no = 0; 
        foreach($value as $row): $no++; 
        // total tunjangan
        $tunjangan = $row->t_keluarga + $row->t_kerja + $row->t_jabatan + $row->t_shift + $row->t_khusus + $row->t_fungsional;
        $potongan = $row->p_absensi + $row->p_ppni + $row->p_biaya_perawatan + $row->p_apotik + $row->p_koperasi + $row->p_jamsostek + $row->p_pph21 + $row->p_bpjs;
        $lainnya = $row->lain_lain + $row->lembur + $row->insentif + $row->dkk + $row->cito + $row->case_manager + $row->oncall + $row->transport + $row->pjgk_prwt + $row->home_care + $row->fee_agent;
      ?>
        <tr>
          <td align="center"><?php echo $no;?></td>
          <td><?php echo $row->nip;?></td>
          <td><?php echo $row->nama_pegawai;?></td>
          <td align="right"><?php echo number_format($row->gaji_dasar);?></td>
          <td align="right"><?php echo number_format($tunjangan);?></td>
          <td align="right"><?php echo number_format($lainnya);?></td>
          <td align="right"><?php echo number_format($row->jml_gaji);?></td>
          <td align="right"><?php echo number_format($potongan);?></td>
          <td align="right"><?php echo number_format($row->gaji_diterima);?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>