<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/bootstrap.css" /> 
  <style type="text/css">
      @font-face {
        font-family: 'DejaVu Sans';
        src: url("/assets/fonts/dejavu-fonts-ttf-2.37/DejaVuSans.ttf") format('truetype');
      }
      body{
        font-size:34px;
      }
      .center{
        text-align:center;
      }
      .title{
        width:22%;
      }
      .dejavu-font{
        font-family: DejaVu Sans, sans-serif;
        font-size:40px !important;
      }
      .content{
        font-size:40px !important;
      }


  </style>
</head>
<body> 
  
  <br>
  <div class="content" style="display:inline-block">
    <br>
    <center>
      <div class="center" style="font-weight: bold; font-size: 1.2em; text-align: center">HASIL  PEMERIKSAAN  KESEHATAN</div>
    </center>

    <p style="font-weight: bold">A. DATA PRIBADI</p>
      
    <table border="0" width="100%">
      <tr>
        <td width="20px"></td>
        <td class="title">Nama</td>
        <td width="5px">:</td>
        <td width="40%"> <?php echo ucwords(strtolower($kunjungan->nama_pasien)) ?></td>
        <td>No. RM</td>
        <td>: <?php echo $kunjungan->no_mr ?></td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Jenis Kelamin</td>
        <td width="5px">:</td>
        <td>
          <?php echo ($pasien->jen_kelamin=='L')?'Laki-Laki':'Perempuan' ?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Umur</td>
        <td width="5px">:</td>
        <td width="78%"> <?php echo $pasien->umur_lengkap ?></td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Tempat / Tgl Lahir</td>
        <td width="5px">:</td>
        <td width="78%"> <?php echo ucwords(strtolower($pasien->tempat_lahir)) ?>, <?php echo $this->tanggal->formatDate($pasien->tgl_lhr) ?></td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Agama</td>
        <td width="5px">:</td>
        <td width="80%">
          <?php echo $pasien->religion_name ?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Status Perkawinan</td>
        <td width="5px">:</td>
        <td>
          <?php echo $pasien->ms_name?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Alamat</td>
        <td width="5px">:</td>
        <td width="80%"> <?php echo ucwords(strtolower($pasien->almt_ttp_pasien)) ?><?php echo isset($pasien->kelurahan)?', '.ucwords(strtolower($pasien->kelurahan)):'' ?><?php echo isset($pasien->kecamatan)?', '.ucwords(strtolower($pasien->kecamatan)):'' ?><?php echo isset($pasien->kota)?', '.ucwords(strtolower($pasien->kota)):'' ?></td>
      </tr>
    </table><br>

    <p style="font-weight: bold">B. ANAMNESA</p>

    <table>
         
    <?php $no=1; foreach ($anamnesa as $key => $value) {
      $res = str_replace('_', ' ', $key);
      echo 
      '<tr>
        <td width="20px"></td>
        <td width="40%"><b>'.$no.'. '.ucwords($res).' </b></td>';
      if(!is_object($value)){
        echo '
        <td width="5px">:</td>
        <td width="50%"> '.$value.' </td>
      </tr>';
      }else{
        echo '
        <td width="50%"><b>:</b></td>
      </tr>';

      
        foreach ($value as $k => $v) :
          $res_ = str_replace('_', ' ', $k); ?>

          <tr>
            <td width="55px"></td>
            <td width="34%"><?php echo ucwords($res_) ?></td>
            <td width="50%">:
              <?php echo ($v=='Ada')?'Ya':'Tidak' ?>
            </td>
          </tr>
          
        <?php endforeach;

      }
        
        $no++;
    } ?>
  
    </table><br>

    <p style="font-weight: bold">C. PEMERIKSAAN FISIK</p>
      
    <table border="0" width="100%">
      <tr>
        <td width="20px"></td>
        <td class="title">Tinggi Badan</td>
        <td width="30%">: <?php echo $fisik->tinggi_badan ?> Cm</td>
        <td class="title">Berat Badan</td>
        <td width="30%">: <?php echo $fisik->berat_badan ?> Kg</td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Tekanan Darah</td>
        <td width="30%">: <?php echo $fisik->tekanan_darah ?> mmHg</td>
        <td class="title">Nadi</td>
        <td width="30%">: <?php echo $fisik->nadi ?> x/menit</td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Keadaan Umum</td>
        <td width="30%">: <?php echo $fisik->keadaan_umum ?></td>
        <td class="title">Pernafasan</td>
        <td width="30%">: <?php echo $fisik->pernafasan ?> x/menit</td>
      </tr>

      <tr>
        <td width="20px"></td>
        <td class="title">Suhu Tubuh</td>
        <td width="30%">: <?php echo $fisik->suhu_tubuh ?> C</td>
        <td class="title">BMI</td>
        <td width="30%">: <?php echo $fisik->bmi ?></td>
      </tr>

      <tr>
        <td width="20px"></td>
        <td class="title">Status Gizi</td>
        <td width="78%">:
          <?php echo $fisik->status_gizi?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Kesadaran</td>
        <td width="78%">:
          <?php echo $fisik->kesadaran?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Buta Warna</td>
        <td width="78%">:
          <?php echo $pemeriksaan_fisik->buta_warna?>
        </td>
      </tr>

      <tr>
        <td>&nbsp;</td>
      </tr>
      
      <tr>
        <td width="20px"></td>
        <td class="title" width="30%"><b>Mulut Gigi </b></td>
        <td width="75%"></td>
      </tr>
      <tr>
          <td width="20px"></td>
          <td class="title" width="30%"><span class="dejavu-font">&#x26AC;</span> Gigi Kanan Atas</td>
          <td width="20%">: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kanan_atas?> </td>
          <td class="title" width="10%" >Gigi ke -</td>
          <td >: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kanan_atas_ke; ?> (<?php echo $pemeriksaan_fisik->mulut_gigi->catatan_gigi_kanan_atas; ?>)</td>
      </tr>
      <tr>
          <td width="20px"></td>
          <td class="title" width="30%"><span class="dejavu-font">&#x26AC;</span> Gigi Kanan Bawah</td>
          <td width="20%">: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kanan_bawah?></td>
          <td class="title" width="10%" >Gigi ke -</td>
          <td >: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kanan_bawah_ke; ?> (<?php echo $pemeriksaan_fisik->mulut_gigi->catatan_gigi_kanan_bawah; ?>)</td>
      </tr>

      <tr>
          <td width="20px"></td>
          <td class="title" width="30%"><span class="dejavu-font">&#x26AC;</span> Gigi Kiri Atas</td>
          <td width="20%">: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kiri_atas?></td>
          <td class="title" width="10%" >Gigi ke -</td>
          <td >: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kiri_atas_ke; ?> (<?php echo $pemeriksaan_fisik->mulut_gigi->catatan_gigi_kiri_atas; ?>)</td>
      </tr>

      <tr>
          <td width="20px"></td>
          <td class="title" width="30%"><span class="dejavu-font">&#x26AC;</span> Gigi Kiri Bawah</td>
          <td width="20%">: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kiri_bawah?></td>
          <td class="title" width="10%" >Gigi ke -</td>
          <td >: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kiri_bawah_ke; ?> (<?php echo $pemeriksaan_fisik->mulut_gigi->catatan_gigi_kiri_bawah; ?>)</td>
      </tr>

      <tr>
          <td width="20px"></td>
          <td class="title" width="30%"><span class="dejavu-font">&#x26AC;</span> Keterangan</td>
          <td width="70%" colspan="2">: <?php echo $pemeriksaan_fisik->mulut_gigi->keterangan?></td>
      </tr>


      <?php foreach ($pemeriksaan_fisik as $key => $value) {
        if(!in_array($key, array('buta_warna', 'mulut_gigi'))){
          $res = str_replace('_', ' ', $key);
          $name = ($key=='tht')?strtoupper($res):ucwords($res);
          echo 
          '
          <tr>
            <td width="20px"></td>
            <td class="title" width="30%"><b>'.$name.' </b></td>
            <td width="75%"></td>
          </tr>';
          // if($key == 'mulut_gigi'){
          //   echo "<pre>"; print_r($value);die;
          // }
            foreach ($pemeriksaan_fisik->$key as $k => $v) :
              $rest = str_replace('_', ' ', str_replace('atau', '/', $k)); 
              $res_ = str_replace('strip', '-', $rest);
              $name = ($k=='jvp')?strtoupper($res_):ucwords($res_);
              ?>
              
              <tr>
                <td width="20px"></td>
                <td class="title" width="30%"><span class="dejavu-font">&#x26AC;</span> <?php echo $name ?></td>
                <td width="75%">: 
                  <?php 
                    switch ($k) {
                      case 'hidung':
                        echo $v;
                      break;

                      case 'gigi':
                        $vals = json_decode($v);
                        foreach ($param_gigi as $gigi) : ?>
                            <span class="dejavu-font"><?php echo (in_array($gigi->value, $vals))?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $gigi->label ?> &nbsp;&nbsp;
                        <?php 
                        endforeach ;
                      break;

                      case 'lidah':
                        echo $v;
                      break;

                      case 'jvp':
                        echo $v;
                      break;

                      case 'tiroid':
                      case 'kel_getah_bening':
                      case 'hati_atau_limpa':
                      case 'tumor':
                        echo $v;
                      break;

                      case 'besar':
                        echo $v;
                        
                      break;

                      case 'bunyi_S1_strip_S2':
                        echo $v;
                      break;

                      case 'bising':
                        echo $v;
                      break;

                      case 'extremitas':
                      case 'kulit_atau_turgor':
                        echo ($v=='Dalam Batas Normal')?'Dalam Batas Normal':'Tidak Normal';

                      break;

                      case 'nyeri_tekan':
                        echo ($v=='Negatif')?'Negatif':'Positif';
                      break;

                      case 'lainnya':
                        echo $v;
                      break;
                      
                      default:
                        echo nl2br($v);
                      break;
                    }
                    // echo "<pre>"; print_r($v);die;
                  ?>
                </td>
              </tr>
              
            <?php endforeach;
        }
      } ?>

    </table>
    
    <div style="page-break-before: always;"></div>
    <p style="font-weight: bold">D. PEMERIKSAAN RADIOLOGI</p>

    <?php 
      echo $hasil_penunjang['050201'][0]->html;
    ?>

    <p style="font-weight: bold">E. PEMERIKSAAN EKG</p>

    <table border="0" width="100%">
      <tr>
        <td width="20px"></td>
        <td class="title">Irama</td>
        <td width="78%">:
          <?php echo $pemeriksaan_ekg->irama; ?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">HR</td>
        <td width="78%">: <?php echo $pemeriksaan_ekg->hr ?> x/menit</td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Kesan</td>
        <td width="78%">: <?php echo nl2br(ucwords($pemeriksaan_ekg->kesan)) ?></td>
      </tr>
    </table>
    
    <div style="page-break-before: always;"></div>

    <p style="font-weight:bold; margin-top:0;">
      F. PEMERIKSAAN LABORATORIUM
    </p>

    <?php 
      foreach($hasil_penunjang['050101'] as $key_lab => $val_lab){
        echo $val_lab->html;
      }
    ?>
    
    <br pagebreak="true"/>

    <p style="font-weight: bold">G. KESIMPULAN</p>

    <table border="0" width="100%">
      
      <?php $no=1; foreach ($hasil as $key => $value) {
        $res = str_replace('_', ' ', $key);
        echo 
        '
        <tr>
          <td width="20px"></td>
          <td width="38%">'.$no.'. '.ucwords($res).'</td>
          <td width="2%">: </td>';
          if(!is_object($value)){
            echo '
            <td width="40%">'. nl2br(htmlspecialchars(ucfirst($value), ENT_QUOTES, 'UTF-8')).' </td>
          </tr>';
          }else{
            echo '
            <td width="40%"></td>
          </tr>';

        
          foreach ($value as $k => $v) :
            $res_ = str_replace('_', ' ', $k); 

            if($res_=='gigi') {
              echo '<tr>
              <td width="55px"></td>
              <td width="32%">'.ucwords($res_).'</td>
              <td width="2%">: </td>
              <td width="64%">
                <table width="100%"><tr>
                      <td class="title" width="35%">Gigi Kanan Atas</td>
                      <td width="20%">: '.$pemeriksaan_fisik->mulut_gigi->gigi_kanan_atas.' </td>
                      <td class="title" width="20%" >Gigi ke -</td>
                      <td width="25%">: '.$pemeriksaan_fisik->mulut_gigi->gigi_kanan_atas_ke.' ('.$pemeriksaan_fisik->mulut_gigi->catatan_gigi_kanan_atas.')</td>
                  </tr>
                  <tr>
                      <td class="title" width="35%">Gigi Kanan Bawah</td>
                      <td width="20%">: '.$pemeriksaan_fisik->mulut_gigi->gigi_kanan_bawah.'</td>
                      <td class="title" width="20%" >Gigi ke -</td>
                      <td width="25%">: '.$pemeriksaan_fisik->mulut_gigi->gigi_kanan_bawah_ke.' ('.$pemeriksaan_fisik->mulut_gigi->catatan_gigi_kanan_bawah.')</td>
                  </tr>

                  <tr>
                      <td class="title" width="35%">Gigi Kiri Atas</td>
                      <td width="20%">: '.$pemeriksaan_fisik->mulut_gigi->gigi_kiri_atas.'</td>
                      <td class="title" width="20%" >Gigi ke -</td>
                      <td width="25%">: '.$pemeriksaan_fisik->mulut_gigi->gigi_kiri_atas_ke.' ('.$pemeriksaan_fisik->mulut_gigi->catatan_gigi_kiri_atas.')</td>
                  </tr>

                  <tr>
                      <td class="title" width="35%">Gigi Kiri Bawah</td>
                      <td width="20%">: '.$pemeriksaan_fisik->mulut_gigi->gigi_kiri_bawah.'</td>
                      <td class="title" width="20%" >Gigi ke -</td>
                      <td width="25%">: '.$pemeriksaan_fisik->mulut_gigi->gigi_kiri_bawah_ke.' ('.$pemeriksaan_fisik->mulut_gigi->catatan_gigi_kiri_bawah.')</td>
                  </tr></table>
              </td>
            </tr>';

            }else{
              echo '<tr>
              <td width="55px"></td>
              <td width="32%">'.ucwords($res_).'</td>
              <td width="2%">: </td>
              <td width="64%">'.nl2br(htmlspecialchars(ucfirst($v), ENT_QUOTES, 'UTF-8')).'</td>
            </tr>';
            }

          ?>

          <?php endforeach;

        }
          
        $no++;
      } ?>

    </table>

    <p style="font-weight: bold"> </p>
    
    <table border="0" width="100%">
      <tr>
        <td width="20px"></td>
        <td width="20%"><b>KESAN :</b></td>
        <td width="80%"></td>
      </tr>

      <?php
        foreach ($param_kesan_mcu as $value) : 
          if($kesimpulan==$value->label) :
        ?>
          
          <tr>
            <td width="20px"></td>
            <td colspan="2">
              <b><?php echo $value->label.' ( '.ucwords($value->value).' )' ?></b>
            </td>
          </tr>
          
      <?php  endif; endforeach ?>

    </table><br>
    
    <br pagebreak="true"/>
    <p style="font-weight: bold">H. ANJURAN / SARAN</p>

    <table border="0" width="100%">
      <tr>
        <td width="20px"></td>
        <td width="100%"><?php echo nl2br(htmlspecialchars(ucwords($kesan), ENT_QUOTES, 'UTF-8')) ?></td>
      </tr>
    </table><br>

    <p style="font-weight: bold"></p>

    <table border="0" width="100%">
      <tr>
        <td width="60%"></td>
        <td width="40%" style="text-align:center">Penanggung Jawab</td>
      </tr>
      <tr>
        <td></td>
        <td style="text-align:center"><?php echo $img?><br><b><?php echo ucwords($kunjungan->nama_pegawai) ?></b></td>
      </tr>
      
    </table><br>
    
    <br pagebreak="true"/>
    <p style="font-weight: bold; text-align: center">LAMPIRAN HASIL PEMERIKSAAN</p>
    <?php
      foreach ($attachment as $ka => $va) {
        if(in_array( $va->csm_dex_jenis_dok, array('image/png', 'image/jpg', 'image/jpeg') )){
          $base_url = ltrim($va->base_url_dok);
          $url = ltrim($va->csm_dex_fullpath);
          if (file_exists($url)) {
              echo '<img src="'.$url.'"><br>'.$va->csm_dex_nama_dok.'<br pagebreak="true"/>';
          } else {
              echo 'Foto tidak ditemukan';
          }
        }
      }
    ?>

   
  </div>

</body>


</html>








