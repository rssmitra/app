<hr>
<p><b>HASIL PENCARIAN PASIEN</b></p>
<!-- input hidden -->
<input type="hidden" name="search_by" id="search_by" value="<?php echo $_GET['search_by']?>">
<input type="hidden" name="keyword" id="keyword" value="<?php echo $_GET['keyword']?>">
<div class="row">
  <div class="col-md-12">
    <table id="dt_selected_brg" class="table table-striped table-bordered">
        <tr>
          <th></th>
          <th>No</th>
          <th>No Kunjungan</th>
          <th>Nama Pasien</th>
          <th>Dokter/Poli</th>
          <th>No SEP</th>
          <th>Diagnosa Akhir</th>
          <th>Pesan Resep</th>
          <th>#</th>
        </tr>
        <?php 
          if( count($result) > 0 ) {
            $no = 0;
            foreach( $result as $row_result ) {
              $no++;
              echo '<tr id="tr_kunjungan_'.$row_result->no_kunjungan.'">';
              // hidden form
              echo '<td style="background-color: #c7cccb">';
                echo '<input type="hidden" name="no_registrasi" value="'.$row_result->no_registrasi.'">';
                echo '<input type="hidden" name="no_kunjungan" value="'.$row_result->no_kunjungan.'">';
                echo '<input type="hidden" name="no_mr" value="'.$row_result->no_mr.'">';
                echo '<input type="hidden" name="kode_perusahaan" value="'.$row_result->kode_perusahaan.'">';
                echo '<input type="hidden" name="kode_kelompok" value="'.$row_result->kode_kelompok.'">';
                echo '<input type="hidden" name="kode_klas" value="16">';
                echo '<input type="hidden" name="kode_profit" value="2000">';
                echo '<input type="hidden" name="kode_bagian_asal" value="'.$row_result->kode_bagian_tujuan.'">';
                echo '<input type="hidden" name="kode_dokter" value="'.$row_result->kode_dokter.'">';
                echo '<input type="hidden" name="jumlah_r" value="1">';
                echo '<input type="hidden" name="lokasi_tebus" value="1">';
                echo '<input type="hidden" name="tgl_pesan" value="'.date('Y-m-d').'">';
              echo '</td>';

              echo '<td align="center">'.$no.'</td>';
              echo '<td><b>'.$row_result->no_kunjungan.'</b><br>'.$this->tanggal->formatDateTime($row_result->tgl_masuk).'</td>';
              echo '<td>'.$row_result->no_mr.'<br>'.$row_result->nama_pasien.'</td>';
              echo '<td>'.ucwords($row_result->nama_bagian).'<br>'.$row_result->nama_pegawai.'</td>';
              echo '<td>'.$row_result->no_sep.'</td>';
              echo '<td>'.ucwords($row_result->diagnosa_akhir).'</td>';
              echo '<td>Jml Pesan : '.$row_result->jml_pesan.' ( <a href="#" onclick="getMenu('."'farmasi/Entry_resep_ri_rj/form/".$row_result->kode_pesan_resep."?mr=".$row_result->no_mr."&tipe_layanan=RJ'".')" >'.$row_result->kode_pesan_resep.'</a> )</td>';
              echo '<td align="center" width="50px"><a href="#" class="btn btn-xs btn-primary" onclick="submitPesanResep('.$row_result->no_kunjungan.')">Pesan Resep</a></td>';
              
              echo '</tr>';

              

              
            }
          }else{
            echo '<tr>';
            echo '<td style="color: red">Tidak ada data pasien <b><i>"'.$_GET['keyword'].'"</i></b> pada bulan ini</td>';
            echo '</tr>';
          }
        ?>
      <??>
    </table>
  </div>
</div>