<?php 
  if( isset($_GET['export']) AND $_GET['export']=='true') {
    $title=="REKAP_LEMBUR_PEGAWAI_".str_replace(" ", "_", strtoupper($title))."";
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$title.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  }

?>

<div class="page-header">
  <span id="" style="font-size: 16px; font-style: italic"><?php echo $title; ?></span>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    
    <!-- PAGE CONTENT BEGINS -->

      <div class="widget-body">
        <div class="widget-main no-padding">
          
            <?php 
              $no = 0;
              foreach ($getData as $key => $value) {
                
                echo '<p style="font-weight: bold">UNIT/ BAGIAN : '.$key.'</p>';

                foreach ($value as $key2 => $rincian_lembur) {
                  $no++;
                  $pg = $rincian_lembur[0];
                  echo '<table class="table">';

                  echo '<tr>';
                  echo '<td align="center">
                  <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="373">
                            <span class="lbl"></span>
                        </label></td>';
                  echo '<th style="width: 30px" align="center">No</th>';
                  echo '<th style="width: 200px" align="center">Tugas di Unit/Bagian</th>';
                  echo '<th style="width: 100px">Tgl Lembur</th>';
                  echo '<th style="width: 100px">Dari Jam</th>';
                  echo '<th style="width: 100px">S.d Jam</th>';
                  echo '<th style="width: 150px">Jumlah Jam Lembur</th>';
                  echo '<th style="width: 120px">Pembulatan</th>';
                  echo '<th>Deskripsi Pekerjaan</th>';
                  echo '<th>Verifikasi SDM</th>';
                  echo '</tr>';

                  echo '<tr>';
                  echo '<td align="center">
                  <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="373">
                            <span class="lbl"></span>
                        </label></td>';
                  echo '<td style="width: 30px" align="center">'.$no.'</td>';
                  echo '<td colspan="8" style="vertical-align: middle !important">'.strtoupper($key2).' - '.$pg->nama_level.' '.$pg->nama_unit.' ('.$pg->kepeg_gol.')</td>';
                  echo '</tr>';
                    
                  foreach ($rincian_lembur as $key3 => $row_lembur) {
                    

                    echo '<tr>';
                    echo '<td align="center">
                    <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="373">
                            <span class="lbl"></span>
                        </label></td>';
                    echo '<td></td>';
                    echo '<td>'.$row_lembur->nama_unit_tugas.'</td>';
                    echo '<td align="center">'.$this->tanggal->formatDateDmy($row_lembur->tgl_lembur).'</td>';
                    echo '<td align="center">'.$this->tanggal->formatTime($row_lembur->dari_jam).'</td>';
                    echo '<td align="center">'.$this->tanggal->formatTime($row_lembur->sd_jam).'</td>';
                    echo '<td align="center">'.$row_lembur->jml_jam_lembur.'</td>';
                    echo '<td align="center">'.$row_lembur->pembulatan_jam_lembur.'</td>';
                    echo '<td align="left">'.$row_lembur->deskripsi_pekerjaan.'</td>';
                    echo '<td align="left"><input type="text" class="form-control"></td>';
                    echo '</tr>';
                  }
                }
                echo "</table>";
              } 
            ?>
          
        </div>
      </div>
    
    <!-- PAGE CONTENT ENDS -->

  </div><!-- /.col -->
</div><!-- /.row -->


