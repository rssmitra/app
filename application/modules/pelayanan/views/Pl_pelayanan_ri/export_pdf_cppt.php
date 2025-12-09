<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />

<div style="padding: 30px">
<?php echo $header?>
<hr>
<div style="font-weight: bold; font-size: 16px">CATATAN PERKEMBANGAN PASIEN TERINTEGRASI (CPPT)</div>
<small style="font-style: italic">Periode Keperawatan Tanggal <?php echo $this->tanggal->formatDatedmY($_GET['from_tgl'])?> s/d <?php echo $this->tanggal->formatDatedmY($_GET['to_tgl'])?></small>
<br>
  <table width="100%" border="1">
    <thead>
      <tr style="border-bottom: 1px solid grey;">  
        <th width="30px" style="font-weight: bold; border-bottom: 1px solid grey; padding: 10px; font-size: 14px; border-top: 1px solid grey;; text-align: center">No</th>
        <th width="150px" style="font-weight: bold; border-bottom: 1px solid grey; padding: 10px; font-size: 14px; border-top: 1px solid grey;">Tanggal/Jam/PPA</th>
        <th width="350px" style="font-weight: bold; border-bottom: 1px solid grey; padding: 10px; font-size: 14px; border-top: 1px solid grey;">SOAP</th>
        <th width="150px" style="font-weight: bold; border-bottom: 1px solid grey; padding: 10px; font-size: 14px; border-top: 1px solid grey;">Verifikasi DPJP</th>
      </tr>
    </thead>
    <tbody>
      <?php 
        foreach($data as $row){
          echo '<tr>';
          echo '<td align="center" width="30px" style="padding: 5px; border-bottom: 1px solid grey; text-align: center" valign="top">'.$row['no'].'</td>';
          echo '<td width="150px" style="border-bottom: 1px solid grey; padding: 5px" valign="top">'.$row['tanggal'].'<br/>'.$row['ppa'].'<br>'.$row['nama_ppa'].'</td>';
          echo '<td width="350px" style="border-bottom: 1px solid grey; padding: 5px"><p style="text-align: justify">'.$row['soap'].'</p></td>';
          echo '<td width="150px" style="padding: 5px; border-bottom: 1px solid grey; text-align: center" valign="top">'.$row['ttd'].'<br>'.$row['verified_date'].'</td>';
          echo '</tr>';
        }
      ?>
    </tbody>
  </table>
</div>