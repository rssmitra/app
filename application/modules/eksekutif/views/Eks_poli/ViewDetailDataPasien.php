
<!-- <div class="pull-right"><a href="#" alt="close detail" onclick="hide_detail()"><i class="fa fa-times bigger-150 red"></i> Close</a></div>
<br> -->
<div class="row">
  <div class="col-md-12">
    <center>KUNJUNGAN <?php echo $value['title']; ?> <br> Berdasarkan Nama Pasien</center>
    <div style="height:550px; overflow:auto;">
      <table class="table">
        <tr style="background: chartreuse">
          <th class="center">No</th>
          <th>No MR</th>
          <th>Nama Pasien</th>
          <th>Total (Rp.)</th>
        </tr>
        <?php 
          $no = 0;
          foreach($value['result'] as $ky3=>$row_k3) :
            $arr_total[] = $row_k3->total;
            $no++;
        ?>
        <tr>
          <td align="center"><?php echo ucwords($no)?></td>
          <td><?php echo ucwords($row_k3->no_mr)?></td>
          <td><?php echo ucwords($row_k3->nama_pasien)?></td>
          <td align="right">
            <a href="#"><?php echo number_format($row_k3->total)?></a>
          </td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <td align="right" colspan="3"><b>Total</b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_total))?></b></td>
        </tr>
      </table>
    </div>
  </div>
  <div class="col-md-6">
    <div id="show_detail_level_2"></div>
  </div>
</div>