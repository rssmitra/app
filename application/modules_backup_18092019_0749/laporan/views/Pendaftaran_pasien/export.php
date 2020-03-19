<?php 
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=pendaftaran_pasien_detail.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
?>
<style>
    .border_{
        border:1px solid black;
    }
</style>
    <h1>LAPORAN DETAIL REGISTRASI PASIEN </h1>
    <h3><?php echo "Tanggal : ".$from_tgl ?> <?php if($from_tgl != $to_tgl) echo " s/d ".$to_tgl ?></h3>    
    
    <table id="dynamic-table" class="border_">
       <thead>
        <tr class="border_">  
          <th class="center">No.</th>
          <th width="80px">No. Reg</th>
          <th>No MR</th>
          <th>Nama Pasien</th>
          <th>Penjamin</th>
          <th>Tanggal Registrasi</th>
          <th>Tujuan Bagian</th>
          <th>Nama Dokter</th>      
          <th>Status Pasien</th>         
        </tr>
      </thead>
      <tbody>

      <?php

            $no=0;
            foreach ($result as $row_list) {
                $no++;
                $row = array();
                //print_r($row_list);die;
                $penjamin = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:'UMUM';
                $tgl = $this->tanggal->formatDateTime($row_list->tgl_jam_masuk);

                echo '
                <tr class="border_">
                    <td>'.$no.'</td>
                    <td>'.$row_list->no_registrasi.'</td>
                    <td>'.$row_list->no_mr.'</td>
                    <td>'.strtoupper($row_list->nama_pasien).'</td>
                    <td>'.$penjamin.'</td>
                    <td>'.$tgl.'</td>
                    <td>'.ucwords($row_list->nama_bagian).'</td>
                    <td>'.$row_list->nama_pegawai.'</td>
                    <td>'.$row_list->stat_pasien.'</td>
                </tr>';
                                        
            }
        ?>

      </tbody>
    </table>