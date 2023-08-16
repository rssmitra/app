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
          <table class="table">
            <?php 
              $no = 0;
              foreach ($getData as $key => $value) {
                echo '<tr><th colspan="6">'.$key.'</th></tr>';
                foreach ($value as $key2 => $rincian_lembur) {
                  $no++;
                  $pg = $rincian_lembur[0];
                  echo '<tr><td colspan="6">&nbsp;&nbsp;&nbsp;'.$no.'. '.$key2.' - '.$pg->nama_level.' '.$pg->nama_unit.' ('.$pg->kepeg_gol.')</td></tr>';
                    echo '<tr>';
                    echo '<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tugas di Unit/Bagian</th>';
                    echo '<th style="width: 120px">Tgl Lembur</th>';
                    echo '<th style="width: 120px">Dari Jam</th>';
                    echo '<th style="width: 120px">S.d Jam</th>';
                    echo '<th style="width: 150px">Jumlah Jam Lembur</th>';
                    echo '<th style="width: 120px">Pembulatan</th>';
                    echo '</tr>';
                  foreach ($rincian_lembur as $key3 => $row_lembur) {
                    

                    echo '<tr>';
                    echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row_lembur->nama_unit_tugas.'</td>';
                    echo '<td align="center">'.$this->tanggal->formatDateDmy($row_lembur->tgl_lembur).'</td>';
                    echo '<td align="center">'.$this->tanggal->formatTime($row_lembur->dari_jam).'</td>';
                    echo '<td align="center">'.$this->tanggal->formatTime($row_lembur->sd_jam).'</td>';
                    echo '<td align="center">'.$row_lembur->jml_jam_lembur.'</td>';
                    echo '<td align="center">'.$row_lembur->pembulatan_jam_lembur.'</td>';
                    echo '</tr>';
                  }
                }
              } 
            ?>
          </table>
        </div>
      </div>
    
    <!-- PAGE CONTENT ENDS -->

  </div><!-- /.col -->
</div><!-- /.row -->


