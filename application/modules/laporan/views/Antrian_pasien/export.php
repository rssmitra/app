<?php 
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=antrian_pasien_detail.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
?>
<style>
    .border_{
        border:1px solid black;
    }
</style>
    <h1>LAPORAN DETAIL ANTRIAN PASIEN </h1>
    <h3><?php echo "Tanggal : ".$from_tgl ?> <?php if($from_tgl != $to_tgl) echo " s/d ".$to_tgl ?></h3>    
    
    <table id="dynamic-table" class="border_">
       <thead>
        <tr class="border_">  
            <th>Tanggal</th>
            <th width="80px">No. Antrian</th>
            <th>Klinik</th>
            <th>Dokter</th>
            <th>Jam Praktek</th>
            <th>Type</th>       
        </tr>
      </thead>
      <tbody>

      <?php

            $no=0;
            foreach ($result as $row_list) {
                $tgl = $this->tanggal->formatDate($row_list['tanggal']);
                
                foreach ($row_list['data'] as $value) {
                    # code...
                    echo '
                <tr class="border_">
                    <td>'.$tgl.'</td>
                    <td><center>'.$value->nomor.'</center></td>
                    <td>'.$value->klinik.'</td>
                    <td>'.$value->dokter.'</td>
                    <td><center>'.$value->jam_praktek.'</center></td>
                    <td>'.strtoupper($value->type).'</td>
                </tr>';
                }
                
                
                                        
            }
        ?>

      </tbody>
    </table>