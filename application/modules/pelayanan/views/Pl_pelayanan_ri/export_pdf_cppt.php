
<?php echo $header?>
<table width="100%">
  <thead>
    <tr style="border-bottom: 1px solid grey; padding: 5px;">  
      <th width="30px" style="font-weight: bold; border-bottom: 1px solid grey; border-top: 1px solid grey;; text-align: center">No</th>
      <th width="150px" style="font-weight: bold; border-bottom: 1px solid grey; border-top: 1px solid grey;">Tanggal/Jam/PPA</th>
      <th width="350px" style="font-weight: bold; border-bottom: 1px solid grey; border-top: 1px solid grey;">SOAP</th>
      <th width="150px" style="font-weight: bold; border-bottom: 1px solid grey; border-top: 1px solid grey;">Verifikasi DPJP</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      foreach($data as $row){
        echo '<tr>';
        echo '<td align="center" width="30px" style="border-bottom: 1px solid grey; text-align: center">'.$row['no'].'</td>';
        echo '<td width="150px" style="border-bottom: 1px solid grey;">'.$row['tanggal'].'<br/>'.$row['ppa'].'<br>'.$row['nama_ppa'].'</td>';
        echo '<td width="350px" style="border-bottom: 1px solid grey;"><p style="text-align: justify">'.$row['soap'].'</p></td>';
        echo '<td width="150px" style="border-bottom: 1px solid grey;">'.$row['ttd'].'</td>';
        echo '</tr>';
      }
    ?>
  </tbody>
</table>