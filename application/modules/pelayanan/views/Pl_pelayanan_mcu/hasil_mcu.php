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

    <span class="center">
      <h2>HASIL  PEMERIKSAAN  KESEHATAN</h2>
    </span>

    <h3>A. DATA PRIBADI</h3>
      
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
          <span class="dejavu-font"><?php echo ($pasien->jen_kelamin=='L')?'&#9745;':'&#9744;' ?></span>&nbsp;Pria &nbsp;&nbsp;
          <span class="dejavu-font"><?php echo ($pasien->jen_kelamin=='P')?'&#9745;':'&#9744;' ?></span>&nbsp;Wanita &nbsp;&nbsp;
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
          <?php
            foreach ($param_agama as $value) : ?>
              
              <span class="dejavu-font"><?php echo ($pasien->id_dc_agama==$value->value)?'&#9745;':'&#9744;' ?></span>&nbsp; <?php echo $value->label ?> <br>

          <?php  endforeach ?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Status Perkawinan</td>
        <td width="5px">:</td>
        <td>
          <?php
            foreach ($param_perkawinan as $value) : ?>
              <span class="dejavu-font"><?php echo ($pasien->id_dc_kawin==$value->value)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $value->label ?> &nbsp;&nbsp;
          <?php  endforeach ?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Alamat</td>
        <td width="5px">:</td>
        <td width="80%"> <?php echo ucwords(strtolower($pasien->almt_ttp_pasien)) ?><?php echo isset($pasien->kelurahan)?', '.ucwords(strtolower($pasien->kelurahan)):'' ?><?php echo isset($pasien->kecamatan)?', '.ucwords(strtolower($pasien->kecamatan)):'' ?><?php echo isset($pasien->kota)?', '.ucwords(strtolower($pasien->kota)):'' ?></td>
      </tr>
    </table><br>

    <h3>B. ANAMNESA</h3>

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
              <span class="dejavu-font"><?php echo ($v=='Ada')?'&#9745;':'&#9744;' ?></span>&nbsp;Ya &nbsp;&nbsp;
              <span class="dejavu-font"><?php echo ($v=='Tidak Ada')?'&#9745;':'&#9744;' ?></span>&nbsp;Tidak &nbsp;&nbsp;
            </td>
          </tr>
          
        <?php endforeach;

      }
        
        $no++;
    } ?>
  
    </table><br>

    <br pagebreak="true"/>

    <h3>C. PEMERIKSAAN FISIK</h3>
      
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
          <?php
            foreach ($param_status_gizi as $value) : ?>
              
              <span class="dejavu-font"><?php echo ($fisik->status_gizi==$value->value)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $value->label ?> &nbsp;&nbsp;

          <?php  endforeach ?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Kesadaran</td>
        <td width="78%">:
          <?php
            foreach ($param_kesadaran as $value) : ?>
              
              <span class="dejavu-font"><?php echo ($fisik->kesadaran==$value->value)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $value->label ?> &nbsp;&nbsp;

          <?php  endforeach ?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Buta Warna</td>
        <td width="78%">:
          <?php
            foreach ($param_buta_warna as $value) : ?>
              
              <span class="dejavu-font"><?php echo ($pemeriksaan_fisik->buta_warna==$value->value)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $value->label ?> &nbsp;&nbsp;

          <?php  endforeach ?>
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
                        foreach ($param_hidung as $hidung) : ?>
                
                        <span class="dejavu-font"><?php echo ($v==$hidung->value)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $hidung->label ?> &nbsp;&nbsp;
          
                        <?php  endforeach ;
                      break;

                      case 'gigi':
                        $vals = json_decode($v);
                        foreach ($param_gigi as $gigi) : ?>
                            <span class="dejavu-font"><?php echo (in_array($gigi->value, $vals))?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $gigi->label ?> &nbsp;&nbsp;
                        <?php 
                        endforeach ;
                      break;

                      case 'lidah':
                        foreach ($param_lidah as $lidah) : ?>
                
                          <span class="dejavu-font"><?php echo ($v==$lidah->value)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $lidah->label ?> &nbsp;&nbsp;
          
                        <?php  endforeach ;
                      break;

                      case 'jvp':
                        foreach ($param_jvp as $jvp) : ?>
                
                          <span class="dejavu-font"><?php echo ($v==$jvp->value)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $jvp->label ?> &nbsp;&nbsp;
          
                        <?php  endforeach ;
                      break;

                      case 'tiroid':
                      case 'kel_getah_bening':
                      case 'hati_atau_limpa':
                      case 'tumor':
                        foreach ($param_fisik as $fsk) : ?>
                
                          <span class="dejavu-font"><?php echo ($v==$fsk->value)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $fsk->label ?> &nbsp;&nbsp;
          
                        <?php  endforeach ;
                      break;

                      case 'besar':
                        foreach ($param_jantung_besar as $jbesar) : ?>
                
                          <span class="dejavu-font"><?php echo ($v==$jbesar->value)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $jbesar->label ?> &nbsp;&nbsp;
          
                        <?php  endforeach ;
                      break;

                      case 'bunyi_S1_strip_S2':
                        foreach ($param_jantung_S1_S2 as $jbunyi) : ?>
                
                          <span class="dejavu-font"><?php echo ($v==$jbunyi->value)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $jbunyi->label ?> &nbsp;&nbsp;
          
                        <?php  endforeach ;
                      break;

                      case 'bising':
                        foreach ($param_jantung_bising as $jbising) : ?>
                
                          <span class="dejavu-font"><?php echo ($v==$jbising->value)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $jbising->label ?> &nbsp;&nbsp;
          
                        <?php  endforeach ;
                      break;

                      case 'extremitas':
                      case 'kulit_atau_turgor':?>
                        <span class="dejavu-font"><?php echo ($v=='Dalam Batas Normal')?'&#9745;':'&#9744;' ?></span>&nbsp;Dalam Batas Normal&nbsp;&nbsp;
                        <span class="dejavu-font"><?php echo ($v=='Tak Normal')?'&#9745;':'&#9744;' ?></span>&nbsp;Tak Normal&nbsp;&nbsp;
                      <?php
                      break;

                      case 'nyeri_tekan':?>
                        <span class="dejavu-font"><?php echo ($v=='Negatif')?'&#9745;':'&#9744;' ?></span>&nbsp;Negatif&nbsp;&nbsp;
                        <span class="dejavu-font"><?php echo ($v=='Positif')?'&#9745;':'&#9744;' ?></span>&nbsp;Positif&nbsp;&nbsp;
                      <?php
                      break;

                      case 'lainnya':
                        foreach ($param_abdomen_lainnya as $lainnya) : ?>
                
                          <span class="dejavu-font"><?php echo ($v==$lainnya->value)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $lainnya->label ?> &nbsp;&nbsp;
          
                        <?php  endforeach ;
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

    </table><br>
    
    <br pagebreak="true"/>

    <h3>D. PEMERIKSAAN RADIOLOGI</h3>

    <table border="0" width="100%">
      <tr>
        <td width="20px"></td>
        <td class="title">Thorax Foto</td>
        <td width="3%">: </td>
        <td width="75%"><?php echo nl2br(htmlspecialchars($pemeriksaan_radiologi->hasil, ENT_QUOTES, 'UTF-8')) ?></td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Kesan</td>
        <td width="3%">: </td>
        <td width="75%"><?php echo nl2br(htmlspecialchars($pemeriksaan_radiologi->kesan, ENT_QUOTES, 'UTF-8')) ?></td>
      </tr>
    </table><br>

    <h3>E. PEMERIKSAAN EKG</h3>

    <table border="0" width="100%">
      <tr>
        <td width="20px"></td>
        <td class="title">Irama</td>
        <td width="78%">:
          <?php
            foreach ($param_ekg as $value) : ?>
              
              <span class="dejavu-font"><?php echo ($pemeriksaan_ekg->irama==$value->value)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $value->label ?> &nbsp;&nbsp;

          <?php  endforeach ?>
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
        <td width="78%">: <?php echo nl2br($pemeriksaan_ekg->kesan) ?></td>
      </tr>
    </table><br>

    <h3>F. PEMERIKSAAN LABORATORIUM</h3>

    <table border="0" width="100%">
      <tr>
        <td width="20px"></td>
        <td class="title"><b>Terlampir</b></td>
        <td width="78%"></td>
      </tr>
    </table><br>

    <h3>G. KESIMPULAN</h3>

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
            <td width="40%">'. nl2br($value).' </td>
          </tr>';
          }else{
            echo '
            <td width="40%"></td>
          </tr>';

        
          foreach ($value as $k => $v) :
            $res_ = str_replace('_', ' ', $k); ?>

            <tr>
              <td width="55px"></td>
              <td width="32%"><?php echo ucwords($res_) ?></td>
              <td width="2%">: </td>
              <td width="64%"><?php echo nl2br($v) ?></td>
            </tr>
            
          <?php endforeach;

        }
          
          $no++;
      } ?>

    </table>

    <h3> </h3>
    
    <table border="0" width="100%">
      <tr>
        <td width="20px"></td>
        <td width="20%"><b>KESAN :</b></td>
        <td width="80%"></td>
      </tr>

      <?php
        foreach ($param_kesan_mcu as $value) : ?>
          
          <tr>
            <td width="20px"></td>
            <td colspan="2">
              <span class="dejavu-font"><?php echo ($kesimpulan==$value->label)?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $value->label.' ( '.ucwords($value->value).' )' ?> &nbsp;&nbsp;
            </td>
          </tr>
          
      <?php  endforeach ?>

    </table><br>
    
    <br pagebreak="true"/>
    <h3>H. ANJURAN / SARAN</h3>

    <table border="0" width="100%">
      <tr>
        <td width="20px"></td>
        <td width="100%"><?php echo nl2br(htmlspecialchars(ucwords($kesan), ENT_QUOTES, 'UTF-8')) ?></td>
      </tr>
    </table><br>

    <h3></h3>

    <table border="0" width="100%">
      <tr>
        <td width="60%"></td>
        <td width="40%" style="text-align:center">Penanggung Jawab</td>
      </tr>
      <tr>
        <td width="60%"></td>
        <td width="40%"></td>
      </tr>
      <tr>
        <td width="60%"></td>
        <td width="40%"></td>
      </tr>
      <tr>
        <td width="60%"></td>
        <td width="40%"></td>
      </tr>
      <tr>
        <td ></td>
        <td style="text-align:center"><b><?php echo ucwords($kunjungan->nama_pegawai) ?></b></td>
      </tr>
      
    </table><br>
  
   
  </div>

</body>


</html>








