<div class="pull-right"><a href="#" alt="close detail" onclick="hide_detail()"><i class="fa fa-times bigger-150 red"></i> Close</a></div>
<br>
<div class="row">
  <div class="col-md-4">
    <center>KUNJUNGAN <?php echo $value['title']; ?> <br> Berdasarkan Nama Unit/Bagian</center>
    
    <table class="table">
      <tr style="background: chartreuse">
        <th>Unit/Bagian</th>
        <th>Jumlah Pasien</th>
      </tr>
      <?php foreach($value['kunjungan'] as $row_k) : $arr_k[] = $row_k->total; ?>
      <tr>
        <td>
          <?php 
            switch ($row_k->kode_unit) {
              case '01':
                $nama_unit = 'Poli/Klinik Rawat Jalan';
                break;
              case '02':
                $nama_unit = 'IGD';
                break;
              case '03':
                $nama_unit = 'Rawat Inap';
                break;
              case '05':
                $nama_unit = 'Penunjang Medis';
                break;
              case '06':
                $nama_unit = 'Apotik';
                break;
            }
            echo $nama_unit; ?>
        </td>
        <td align="right"><a href="#"><?php echo number_format($row_k->total)?></a></td>
      </tr>
      <?php endforeach; ?>
      <tr>
        <td align="right"><b>Total</b></td>
        <td align="right"><b><?php echo number_format(array_sum($arr_k))?></b></td>
      </tr>
    </table>
  </div>

  <div class="col-md-4">
    <center>PENDAPATAN <?php echo $value['title']; ?> <br> Berdasarkan Jenis Tindakan / Item tarif</center>
    
    <table class="table">
      <tr style="background: cornflowerblue">
        <th class="center" width="30px">No</th>
        <th>Jenis Tindakan</th>
        <th>Jumlah</th>
      </tr>
      <?php 
        $no = 0;
        foreach($value['pendapatan'] as $key_pp=>$row_pp) :
          if($row_pp->total > 0 ) : 
            $no++;
            $arr_pp[] = $row_pp->total; 
      ?>
      <tr>
        <td align="center"><?php echo $no?></td>
        <td><?php echo $row_pp->jenis_tindakan?></td>
        <td align="right"><?php echo number_format($row_pp->total)?></td>
      </tr>
      <?php endif; endforeach; ?>
      <tr>
        <td colspan="2" align="right"><b>Total</b></td>
        <td align="right"><b><?php echo number_format(array_sum($arr_pp))?></b></td>
      </tr>
    </table>
  </div>

  <div class="col-md-4">
    <center>PENDAPATAN <?php echo $value['title']; ?> <br> Berdasarkan Nasabah Pasien</center>
    
    <table class="table">
      <tr style="background: darkorange">
        <th class="center" width="30px">No</th>
        <th>Jenis Nasabah</th>
        <th>Jumlah</th>
      </tr>
      <?php 
        $no = 0;
        foreach($value['pendapatan_2'] as $key_p=>$row_p) :
          if($row_p->total > 0 ) : 
            $no++;
            $arr_p[] = $row_p->total; 
      ?>
      <tr>
        <td align="center"><?php echo $no?></td>
        <td><?php echo ($row_p->nama_perusahaan != '') ? $row_p->nama_perusahaan : 'UMUM'?></td>
        <td align="right"><?php echo number_format($row_p->total)?></td>
      </tr>
      <?php endif; endforeach; ?>
      <tr>
        <td colspan="2" align="right"><b>Total</b></td>
        <td align="right"><b><?php echo number_format(array_sum($arr_p))?></b></td>
      </tr>
    </table>
  </div>


</div>