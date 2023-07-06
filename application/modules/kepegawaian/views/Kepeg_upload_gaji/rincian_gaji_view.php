<style>
  /* Style the table */
    .table-custom {
      border-collapse: collapse;
      border-spacing: 1px;
      width: 100%;
      border: 1px solid #ddd;
      padding: 2px
    }

    .myInput {
      
    }

</style>
<div style="overflow-x:auto; width: 100%; padding: 20px">
  <h3>RINCIAN GAJI PEGAWAI</h3>
  <input type="text" class="myInput" id="myInput_<?php echo $value[0]->kg_id?>" onkeyup="filterRow(<?php echo $value[0]->kg_id?>)" placeholder="Cari nama pegawai.." title="Type in a name" style="width: 98%;font-size: 14px;padding:12px 20px 12px 10px;border: 1px solid #ddd;margin-bottom: 12px !important;height: 35px !important;">
  <br>
  <table class="table" id="myTable_<?php echo $value[0]->kg_id?>">
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
        <th style="text-align: right">RINCIAN</th>
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
          <td align="center"><a href="#" class="btn btn-xs btn-primary" onclick="show_modal('<?php echo base_url().'kepegawaian/Kepeg_upload_gaji/show_detail_row?bulan='.$row->kg_periode_bln.'&tahun='.$row->kg_periode_thn.'&nip='.$row->nip.''?>','RINCIAN GAJI - <?php echo $row->nama_pegawai.' ('.$row->nip.')'?>')"><i class="fa fa-list"></i> Rincian</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
function filterRow(id) {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput_"+id+"");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable_"+id+"");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
</script>