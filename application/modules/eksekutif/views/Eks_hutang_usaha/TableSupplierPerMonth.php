<!-- <style>
.div-scroll, table{
  max-height: 500px;
  overflow-y: scroll;
  overflow-x: clip;
}
</style> -->

<style> 
    .fixTableHead { 
      overflow-y: auto; 
      height: 500px; 
    } 
    .fixTableHead thead th { 
      position: sticky; 
      top: 0; 
    } 
    table { 
      border-collapse: collapse;         
      width: 100%; 
    } 
    th, 
    td { 
      padding: 8px 15px; 
      border: 2px solid #529432; 
    } 
    th { 
      background: #ABDD93; 
    } 
  </style> 

<div class="row fixTableHead">
    <table class="table">
        <thead>
        <tr>
          <th>No</th>
          <th>Nama Supplier</th>
          <?php for($i=1; $i<13; $i++) :?>
          <th><?php echo $this->tanggal->getBln($i); ?></th>
          <?php endfor;?>
          <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php $no=0; foreach($result as $key=>$row) : $no++; ?>
          <tr>
            <td><?php echo $no;?></td>
            <td><?php echo strtoupper($key);?></td>
            <?php 
              for($i=1; $i<13; $i++) {
                $rp = isset($row[$i])?$row[$i]:0;
                $arr_rp[$key][] = $rp;
                echo "<td align='right'>".number_format($rp)."</td>";
              }
              $total = isset($arr_rp[$key]) ? array_sum($arr_rp[$key]) : 0;
              $arr_total[] = $total;
              echo "<td align='right'>".number_format($total)."</td>";
            ?>
              
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr style="font-weight: bold">
          <td colspan="14" align="right">TOTAL</td>
          <td align="right"><?php echo number_format(array_sum($arr_total))?></td>
        </tr>
        </tfoot>
      
    </table>
</div>



