<?php 
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=antrian_pasien_general.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
?>
<style>
    .border_{
        border:1px solid black;
    }
</style>
    <h1>LAPORAN GENERAL ANTRIAN PASIEN </h1>
    <h3><?php echo "Tanggal : ".$from_tgl ?> <?php if($from_tgl != $to_tgl) echo " s/d ".$to_tgl ?></h3>    
    
    <table id="dynamic-table" class="border_">
       <thead>
        <tr class="border_">  
          <th class="center">Tanggal</th>
          <th width="80px">Bagian</th>
          <th>Jumlah Total Antrian</th>
          <th>Pasien BPJS</th>    
          <th>Umum</th>        
        </tr>
      </thead>
      <tbody>

      <?php

            $no=0;
            $grand_total=0;
            $grand_bpjs=0;
            $grand_umum=0;
            foreach ($result as $row_list) {
                $row = array();
                $tgl = $this->tanggal->formatDate($row_list['tanggal']);
                $jml_total= 0;
                $jml_bpjs = 0;
                $jml_umum = 0;
                echo '
                    <tr class="border_">
                        <td rowspan="'.count($row_list['data']).'"><center>'.$tgl.'</center></td>
                ';
                                          
                foreach ($row_list['data'] as $key => $value) {
                    # code...
                    $umum = isset($value['umum'])?$value['umum']:0;
                    $bpjs = isset($value['bpjs'])?$value['bpjs']:0;
                    $jml = $umum + $bpjs;
                    echo'
                        <td class="border_">'.$key.'</td>
                        <td class="border_"><center>'.$jml.'</center></td>
                        <td class="border_"><center>'.$bpjs.'</center></td>
                        <td class="border_"><center>'.$umum.'</center></td>
                    </tr>';

                    $jml_total += $jml;
                    $jml_bpjs += $bpjs;
                    $jml_umum += $umum;
                }
                echo '
                <tr class="border_">  
                    <th colspan="2">Total</th>
                    <th><center>'.$jml_total.'</center></th>  
                    <th><center>'.$jml_bpjs.'</center></th>     
                    <th><center>'.$jml_umum.'</center></th>        
                </tr>
                ';
                $grand_total += $jml_total;
                $grand_bpjs += $jml_bpjs;
                $grand_umum += $jml_umum;
            }

            echo '
            <tr class="border_">  
                <th colspan="2">Grand Total</th>
                <th><center>'.$grand_total.'</center></th>  
                <th><center>'.$grand_bpjs.'</center></th>     
                <th><center>'.$grand_umum.'</center></th>        
            </tr>
            ';
        ?>

      </tbody>
    </table>