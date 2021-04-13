<div class="row">

  <div class="col-xs-12">

    <?php if(count($resep) > 0) : ?>

      <div class="col-xs-<?php echo (count($resep) > 0) ? 6 : 12 ?>">
          
          <table>
          <tr>
              <td width="100px">Kode</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $resep[0]['kode_trans_far']; ?> - <?php echo strtoupper($resep[0]['no_resep'])?></td>
            </tr>
            <tr>
              <td width="100px">Tanggal</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($resep[0]['tgl_trans']) ?></td>
            </tr>
            <tr>
              <td width="100px">Nama Pasien</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($resep[0]['nama_pasien'])?> - <?php echo $no_mr?></td>
            </tr>
            
          </table>

          <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
            <thead>
                <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">
                  <td style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">No</td>
                  <td style="border-bottom: 1px solid black; border-collapse: collapse">Nama Obat</td>
                  <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Jumlah Tebus</td>
                  <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Satuan</td>
                </tr>
            </thead>
                <?php 
                  $no=0; 
                  foreach($resep as $key_dt=>$row_dt) : 
                    if( $row_dt['jumlah_tebus'] > 0 AND $row_dt['resep_ditangguhkan'] != 1) :
                    $no++; 
                    $subtotal = ($row_dt['flag_resep'] == 'racikan') ? $row_dt['jasa_r'] : ($row_dt['harga_jual'] * $row_dt['jumlah_tebus']) + $row_dt['jasa_r']; 
                    $arr_total[] = $subtotal;
                    $desc = ($row_dt['flag_resep'] == 'racikan') ? 'Jasa Racikan Obat' : $row_dt['nama_brg'];
                    $satuan = ($row_dt['satuan_kecil'] != null) ? $row_dt['satuan_kecil'] : $row_dt['satuan_brg'];
                    $penangguhan_resep = ($row_dt['resep_ditangguhkan'] == 1) ? 'Ya' : '-';
                    $color_penangguhan_resep = ($row_dt['resep_ditangguhkan'] == 1) ? 'red' : 'blue';
                    $racikan = isset($row_dt['racikan'][0])?$row_dt['racikan'][0]:[];
                ?>

                  <tr>
                    <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
                    <td style="border-collapse: collapse"><?php echo $desc?></td>
                    <td style="text-align:center; border-collapse: collapse; color: <?php echo $color_penangguhan_resep; ?>; font-weight: bold"><?php echo ($row_dt['flag_resep'] == 'racikan') ? $racikan[0]->jml_content : $row_dt['jumlah_tebus'];?></td>
                    <!-- <td style="text-align:center; border-collapse: collapse;"><?php echo $penangguhan_resep;?></td> -->
                    <td style="text-align: center; border-collapse: collapse"><?php echo ($row_dt['flag_resep'] == 'racikan') ? $racikan[0]->satuan_racikan : $satuan;?></td>
                  </tr>
                  <?php 
                    if($row_dt['flag_resep'] == 'racikan') :
                      foreach ($row_dt['racikan'][0] as $key => $value) {
                        $arr_total[] = ($value->harga_jual * $value->jumlah);
                        $subtotal_racikan = ($value->harga_jual * $value->jumlah);
                        $penangguhan_resep = ($value->resep_ditangguhkan == 1) ? 'Ya' : '-';
                        echo '<tr>
                              <td style="text-align:center; border-collapse: collapse">&nbsp;</td>
                              <td style="border-collapse: collapse"> - '.$value->nama_brg.'</td>
                              <td style="text-align: center; border-collapse: collapse">'.$value->jumlah.'</td>
                              <td style="text-align: center; border-collapse: collapse">'.$penangguhan_resep.'</td>
                              <td style="text-align: center; border-collapse: collapse">'.$value->satuan.'</td>';
                        echo '</tr>';
                      }
                    endif; 
                  ?>

                  <?php endif; endforeach;?>

                  <?php 
                  $no=0; 
                  foreach($resep_kronis as $key_dt=>$row_dtk) : 
                    if( $row_dtk['jumlah_obat_23'] > 0 AND $row_dtk['resep_ditangguhkan'] != 1) :
                    $no++; 
                    $subtotal = ($row_dtk['flag_resep'] == 'racikan') ? $row_dtk['jasa_r'] : ($row_dtk['harga_jual'] * $row_dtk['jumlah_obat_23']) + $row_dtk['jasa_r']; 
                    $arr_total[] = $subtotal;
                    $desc = ($row_dtk['flag_resep'] == 'racikan') ? 'Jasa Racikan Obat' : $row_dtk['nama_brg'];
                    $satuan = ($row_dtk['satuan_kecil'] != null) ? $row_dtk['satuan_kecil'] : $row_dtk['satuan_brg'];
                    $penangguhan_resep = ($row_dtk['resep_ditangguhkan'] == 1) ? 'Ya' : '-';
                    $color_penangguhan_resep = ($row_dtk['resep_ditangguhkan'] == 1) ? 'red' : 'blue';
                    $racikan = isset($row_dtk['racikan'][0])?$row_dtk['racikan'][0]:[];
                ?>

                  <tr>
                    <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
                    <td style="border-collapse: collapse"><?php echo $desc?></td>
                    <td style="text-align:center; border-collapse: collapse; color: <?php echo $color_penangguhan_resep; ?>; font-weight: bold"><?php echo ($row_dtk['flag_resep'] == 'racikan') ? $racikan[0]->jml_content : $row_dtk['jumlah_obat_23'];?></td>
                    <!-- <td style="text-align:center; border-collapse: collapse;"><?php echo $penangguhan_resep;?></td> -->
                    <td style="text-align: center; border-collapse: collapse"><?php echo ($row_dtk['flag_resep'] == 'racikan') ? $racikan[0]->satuan_racikan : $satuan;?></td>
                  </tr>
                  <?php 
                    if($row_dtk['flag_resep'] == 'racikan') :
                      foreach ($row_dtk['racikan'][0] as $key => $value) {
                        $arr_total[] = ($value->harga_jual * $value->jumlah);
                        $subtotal_racikan = ($value->harga_jual * $value->jumlah);
                        $penangguhan_resep = ($value->resep_ditangguhkan == 1) ? 'Ya' : '-';
                        echo '<tr>
                              <td style="text-align:center; border-collapse: collapse">&nbsp;</td>
                              <td style="border-collapse: collapse"> - '.$value->nama_brg.'</td>
                              <td style="text-align: center; border-collapse: collapse">'.$value->jumlah.'</td>
                              <td style="text-align: center; border-collapse: collapse">'.$penangguhan_resep.'</td>
                              <td style="text-align: center; border-collapse: collapse">'.$value->satuan.'</td>';
                        echo '</tr>';
                      }
                    endif; 
                  ?>

                  <?php endif; endforeach;?>



          </table>

          <table style="width: 100% !important; text-align: center">
            <tr>
              <td style="text-align: left; width: 30%">&nbsp;</td>
              <td style="text-align: center; width: 40%">&nbsp;</td>
              <td style="text-align: center; width: 30%">
                <span style="font-size: 14px"><b>Petugas</b></span><br>
                <?php $decode = json_decode($resep[0]['created_by']); echo isset($decode->fullname)?$decode->fullname:$this->session->userdata('user')->fullname;?>
                <br>

              </td>
            </tr>
            
          </table>
      </div>

    <?php 
      endif; 
    ?>


  </div>
  

</div>

