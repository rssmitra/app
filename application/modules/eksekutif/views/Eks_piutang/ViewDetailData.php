<div class="pull-right"><a href="#" alt="close detail" onclick="hide_detail()"><i class="fa fa-times bigger-150 red"></i> Close</a></div>
<br>
<div class="row">
  <div class="col-md-6">
    <center>PIUTANG ASURANSI <?php echo $value['title']; ?> <br> Berdasarkan Tanggal Invoice Penagihan</center>
    
    <table class="table">
      <tr style="background: darkorange">
        <th class="center" width="30px">No</th>
        <th>No Invoice</th>
        <th>Tanggal</th>
        <th>Nama Asuransi</th>
        <th>Jumlah</th>
      </tr>
      <?php 
        $no = 0;
        foreach($value['piutang'] as $key_p=>$row_p) :
          if($row_p->total > 0 ) : 
            $no++;
            $arr_p[] = $row_p->total; 
      ?>
      <tr>
        <td align="center"><?php echo $no?></td>
        <td><?php echo $row_p->no_invoice_tagih?></td>
        <td><?php echo $this->tanggal->formatDateDmy($row_p->tgl_tagih)?></td>
        <td><?php echo $row_p->nama_tertagih?></td>
        <td align="right"><?php echo number_format($row_p->total)?></td>
      </tr>
      <?php endif; endforeach; ?>
      <tr>
        <td colspan="4" align="right"><b>Total</b></td>
        <td align="right"><b><?php echo number_format(array_sum($arr_p))?></b></td>
      </tr>
    </table>
  </div>

  <div class="col-md-6">
    <center>RESUME PIUTANG ASURANSI <?php echo $value['title']; ?> <br> Berdasarkan Tanggal Invoice Penagihan</center>
    
    <table class="table">
      <tr style="background: darkorange">
        <th class="center" width="30px">No</th>
        <th>Nama Asuransi</th>
        <th>Jumlah</th>
      </tr>
      <?php 
        $no = 0;
        foreach($value['resume_piutang'] as $key_rp=>$row_rp) :
          if($row_rp->total > 0 ) : 
            $no++;
            $arr_rp[] = $row_rp->total; 
      ?>
      <tr>
        <td align="center"><?php echo $no?></td>
        <td><?php echo $row_rp->nama_tertagih?></td>
        <td align="right"><?php echo number_format($row_rp->total)?></td>
      </tr>
      <?php endif; endforeach; ?>
      <tr>
        <td colspan="2" align="right"><b>Total</b></td>
        <td align="right"><b><?php echo number_format(array_sum($arr_rp))?></b></td>
      </tr>
    </table>
  </div>


</div>