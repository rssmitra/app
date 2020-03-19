<?php 
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=pendaftaran_pasien_general.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
?>
<style>
    .border_{
        border:1px solid black;
    }
</style>
    <h1>LAPORAN GENERAL REGISTRASI PASIEN </h1>
    <h3><?php echo "Tanggal : ".$from_tgl ?> <?php if($from_tgl != $to_tgl) echo " s/d ".$to_tgl ?></h3>    
    
    <table id="dynamic-table" class="border_">
       <thead>
        <tr class="border_">  
          <th class="center">No.</th>
          <th width="80px">Bagian</th>
          <th>Jumlah Total Registrasi</th>
          <th>Pasien BPJS</th>   
          <th>Jaminan Perusahaan</th>   
          <th>Umum</th>        
        </tr>
      </thead>
      <tbody>

      <?php

            $no=0;
            $jml=0;
            $jml_bpjs = 0;
            $jml_jaminan = 0;
            $jml_umum = 0;
            foreach ($result as $row_list) {
                $no++;
                $row = array();
                $bpjs = ($row_list->bpjs)?$row_list->bpjs:0;
                $jaminan = ($row_list->jaminan)?$row_list->jaminan:0;
                $umum = ($row_list->umum)?$row_list->umum:0;
                echo '
                <tr class="border_">
                    <td>'.$no.'</td>
                    <td>'.$row_list->nama_bagian.'</td>
                    <td><center>'.$row_list->number.'</center></td>
                    <td><center>'.$bpjs.'</center></td>
                    <td><center>'.$jaminan.'</center></td>
                    <td><center>'.$umum.'</center></td>
                </tr>';

                $jml += $row_list->number;
                $jml_bpjs += $bpjs;
                $jml_jaminan += $jaminan;
                $jml_umum += $umum;
            }

            echo '
            <tr class="border_">  
                <th colspan="2">Total</th>
                <th><center>'.$jml.'</center></th>  
                <th><center>'.$jml_bpjs.'</center></th>   
                <th><center>'.$jml_jaminan.'</center></th>   
                <th><center>'.$jml_umum.'</center></th>        
            </tr>
            ';
        ?>

      </tbody>
    </table>