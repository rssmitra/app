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

      .en{
        display:block;
        font-style:italic;
        font-size:0.72em;
        margin-top:2px;
        line-height:1.2;
      }
      .title{ vertical-align:top; }



  </style>
</head>
<body> 

<?php

/* ===================== DICTIONARY ===================== */
function en_dict(){
    return array(

      /* ================= SECTION ================= */
      'hasil pemeriksaan kesehatan' => 'medical examination report',
      'a. data pribadi' => 'personal information',
      'b. anamnesa' => 'medical history',
      'c. pemeriksaan fisik' => 'physical examination',
      'd. pemeriksaan radiologi' => 'radiology examination',
      'e. pemeriksaan ekg' => 'electrocardiography (ecg)',
      'f. pemeriksaan laboratorium' => 'laboratory investigation',
      'g. kesimpulan' => 'clinical assessment',
      'h. anjuran / saran' => 'recommendations',
      'kesan' => 'impression',
      'lampiran hasil pemeriksaan' => 'investigation attachments',
      'penanggung jawab' => 'attending physician',

      /* ================= IDENTIFICATION ================= */
      'nama' => 'patient name',
      'no. rm' => 'medical record number',
      'jenis kelamin' => 'sex',
      'umur' => 'age',
      'tempat / tgl lahir' => 'place / date of birth',
      'agama' => 'religion',
      'status perkawinan' => 'marital status',
      'alamat' => 'address',

      /* ================= VITAL SIGNS ================= */
      'tinggi badan' => 'height',
      'berat badan' => 'weight',
      'tekanan darah' => 'blood pressure',
      'nadi' => 'pulse rate',
      'pernafasan' => 'respiratory rate',
      'suhu tubuh' => 'body temperature',
      'bmi' => 'body mass index',
      'status gizi' => 'nutritional status',
      'kesadaran' => 'level of consciousness',
      'buta warna' => 'color vision deficiency',

      /* ================= ORAL ================= */
      'mulut gigi' => 'oral cavity & dentition',
      'gigi kanan atas' => 'upper right dentition',
      'gigi kanan bawah' => 'lower right dentition',
      'gigi kiri atas' => 'upper left dentition',
      'gigi kiri bawah' => 'lower left dentition',
      'gigi ke -' => 'tooth number',
      'keterangan' => 'remarks',
      'gigi' => 'tooths',

      /* ================= HISTORY ================= */
      'keluhan utama' => 'chief complaint',
      'keluhan saat ini' => 'present complaint',
      'riwayat penyakit masa lampau' => 'past medical history',
      'riwayat penyakit keluarga' => 'family history',
      'riwayat penyakit' => 'medical history',
      'pemeriksaan fisik' => 'physical examination',
      'lainnya' => 'others',

      'sakit kuning' => 'jaundice',
      'kencing manis' => 'diabetes mellitus',
      'hipertensi' => 'hypertension',
      'kencing batu' => 'renal calculi',
      'asma' => 'bronchial asthma',
      'operasi' => 'previous surgery',
      'penyakit karena kecelakaan' => 'traumatic injury',

      'penyakit darah' => 'hematologic disorder',
      'penyakit jiwa' => 'psychiatric disorder',

      'alergi' => 'allergy',
      'alergi makanan' => 'food allergy',
      'alergi udara' => 'airborne allergy',
      'alergi obat' => 'drug allergy',
      'alergi lainnya' => 'other allergies',

      /* ================= HEAD & NECK ================= */
      'mata' => 'eyes',
      'reflek cahaya' => 'light reflex',
      'penglihatan / visus' => 'visual acuity',
      'kacamata' => 'glasses / spectacles',
      'telinga' => 'ears',
      'hidung' => 'nose',
      'tenggorokan' => 'throat',
      'leher' => 'neck',
      'kel getah bening' => 'lymph nodes',

      /* ================= EXTREMITIES ================= */
      'anggota gerak' => 'limbs',
      'extremitas atas kanan' => 'right upper extremity',
      'extremitas atas kiri' => 'left upper extremity',
      'extremitas bawah kanan' => 'right lower extremity',
      'extremitas bawah kiri' => 'left lower extremity',

      /* ================= CHEST ================= */
      'thorax' => 'chest',
      'paru kanan' => 'right lung',
      'paru kiri' => 'left lung',

      /* ================= HEART ================= */
      'jantung' => 'heart',
      'besar' => 'cardiac size',
      'bunyi s1 - s2' => 'heart sounds',
      'bunyi s1 strip s2' => 'heart sounds',
      'reguler' => 'regular rhythm',
      'bising' => 'cardiac murmur',

      /* ================= ABDOMEN ================= */
      'abdomen' => 'abdomen',
      'hati atau limpa' => 'hepatosplenomegaly',
      'hati / limpa' => 'hepatosplenomegaly',
      'nyeri tekan' => 'tenderness',
      'tumor' => 'palpable mass',
      'tak teraba' => 'non-palpable',
      'kulit / turgor' => 'skin turgor / skin elasticity',

      /* ================= EKG ================= */
      'irama' => 'cardiac rhythm',
      'hr' => 'heart rate',

      /* ================= INVESTIGATION ================= */
      'audiometri' => 'audiometry',
      'treadmill' => 'treadmill stress test',
      'ekg' => 'electrocardiogram',
      'laboratorium' => 'laboratory test',

      /* ================= OCCUPATIONAL HEALTH ================= */
      'resiko kardiovaskular' => 'cardiovascular risk',
      'derajat kesehatan' => 'overall health status',
      'kelaikan kerja' => 'fitness for work assessment',
      'layak' => 'fit for duty',
      'tidak layak' => 'unfit for duty',
      'penjelasan kesan' => 'clinical explanation / remarks',

      /* ================= LAB CONTEXT ================= */
      'ldl' => 'ldl cholesterol',
      'egfr' => 'estimated glomerular filtration rate',

      /* ================= COMMON VALUES ================= */
      'ya' => 'yes',
      'tidak' => 'no',
      'normal' => 'normal',
      'tidak normal' => 'abnormal',
      'negatif' => 'negative',
      'positif' => 'positive',
      'dalam batas normal' => 'within normal limits',
      'lainnya' => 'others',

      'rendah' => 'low',
      'sedang' => 'moderate',
      'tinggi' => 'high'
    );

}

/* translate label */
function en($text){
    $dict=en_dict();
    $key=strtolower(trim($text));
    return isset($dict[$key]) ? ucfirst($dict[$key]) : ucfirst($text);
}

/* bilingual label */
function label_en($text){
    return ucwords($text).' <br><span class="en">('.en($text).')</span>';
}

/* bilingual section title */
function section_en($text){
    return '<p style="font-weight:bold">'.strtoupper($text).'<br><span class="en">'.strtoupper(en($text)).'</span></p>';
}

/* translate value */
function val_en($value){
    $dict=en_dict();
    $key=strtolower(trim($value));
    return isset($dict[$key]) ? ucfirst($dict[$key]) : ucfirst($value);
}

?>


  
  <br>
  <div class="content" style="display:inline-block">
    <br>
    <center>
      <div class="center" style="font-weight: bold; font-size: 1.2em; text-align: center"><?php echo section_en('hasil pemeriksaan kesehatan') ?></div>
    </center>

    <p style="font-weight: bold"><?php echo section_en('a. data pribadi') ?></p>
      
    <table border="0" width="100%">
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('nama') ?></td>
        <td width="5px">:</td>
        <td width="40%"> <?php echo ucwords(strtolower($kunjungan->nama_pasien)) ?></td>
        <td><?php echo label_en('no. rm') ?></td>
        <td>: <?php echo $kunjungan->no_mr ?></td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('jenis kelamin') ?></td>
        <td width="5px">:</td>
        <td>
          <?php echo ($pasien->jen_kelamin=='L')?'Laki-Laki':'Perempuan' ?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('umur') ?></td>
        <td width="5px">:</td>
        <td width="78%"> <?php echo $pasien->umur_lengkap ?></td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('tempat / tgl lahir') ?></td>
        <td width="5px">:</td>
        <td width="78%"> <?php echo ucwords(strtolower($pasien->tempat_lahir)) ?>, <?php echo $this->tanggal->formatDate($pasien->tgl_lhr) ?></td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('agama') ?></td>
        <td width="5px">:</td>
        <td width="80%">
          <?php echo $pasien->religion_name ?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('status perkawinan') ?></td>
        <td width="5px">:</td>
        <td>
          <?php echo $pasien->ms_name?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('alamat') ?></td>
        <td width="5px">:</td>
        <td width="80%"> <?php echo ucwords(strtolower($pasien->almt_ttp_pasien)) ?><?php echo isset($pasien->kelurahan)?', '.ucwords(strtolower($pasien->kelurahan)):'' ?><?php echo isset($pasien->kecamatan)?', '.ucwords(strtolower($pasien->kecamatan)):'' ?><?php echo isset($pasien->kota)?', '.ucwords(strtolower($pasien->kota)):'' ?></td>
      </tr>
    </table><br>

    <p style="font-weight: bold"><?php echo section_en('B. anamnesa') ?></p>

    <table>
         
    <?php $no=1; foreach ($anamnesa as $key => $value) {
      $res = str_replace('_', ' ', $key);
      echo 
      '<tr>
        <td width="20px"></td>
        <td width="40%"><b>'.$no.'. '.label_en(strtolower($res)).' </b></td>';
      if(!is_object($value)){
        echo '
        <td width="5px">:</td>
        <td width="50%"> '.ucfirst($value).' </td>
      </tr>';
      }else{
        echo '
        <td width="50%"><b>:</b></td>
      </tr>';

      
        foreach ($value as $k => $v) :
          $res_ = str_replace('_', ' ', $k); ?>

          <tr>
            <td width="55px"></td>
            <td width="34%"><?php echo label_en(strtolower($res_)) ?></td>
            <td width="50%">:
              <?php echo val_en(($v=='Ada')?'Ya':'Tidak') ?>
            </td>
          </tr>
          
        <?php endforeach;

      }
        
        $no++;
    } ?>
  
    </table><br>

    <p style="font-weight: bold"><?php echo section_en('C. pemeriksaan fisik') ?></p>
      
    <table border="0" width="100%">
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('tinggi badan') ?></td>
        <td width="30%">: <?php echo $fisik->tinggi_badan ?> Cm</td>
        <td class="title"><?php echo label_en('berat badan') ?></td>
        <td width="30%">: <?php echo $fisik->berat_badan ?> Kg</td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('tekanan darah') ?></td>
        <td width="30%">: <?php echo $fisik->tekanan_darah ?> mmHg</td>
        <td class="title"><?php echo label_en('nadi') ?></td>
        <td width="30%">: <?php echo $fisik->nadi ?> x/menit</td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title">Keadaan Umum</td>
        <td width="30%">: <?php echo $fisik->keadaan_umum ?></td>
        <td class="title"><?php echo label_en('pernafasan') ?></td>
        <td width="30%">: <?php echo $fisik->pernafasan ?> x/menit</td>
      </tr>

      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('suhu tubuh') ?></td>
        <td width="30%">: <?php echo $fisik->suhu_tubuh ?> C</td>
        <td class="title"><?php echo label_en('bmi') ?></td>
        <td width="30%">: <?php echo $fisik->bmi ?></td>
      </tr>

      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('status gizi') ?></td>
        <td width="78%">:
          <?php echo $fisik->status_gizi?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('kesadaran') ?></td>
        <td width="78%">:
          <?php echo $fisik->kesadaran?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('buta warna') ?></td>
        <td width="78%">:
          <?php echo $pemeriksaan_fisik->buta_warna?>
        </td>
      </tr>

      <tr>
        <td>&nbsp;</td>
      </tr>
      
      <tr>
        <td width="20px"></td>
        <td class="title" width="30%"><b><?php echo label_en('mulut gigi') ?></b></td>
        <td width="75%"></td>
      </tr>
      <tr>
          <td width="20px"></td>
          <td class="title" width="30%"><span class="dejavu-font">&#x26AC;</span> <?php echo label_en('gigi kanan atas') ?></td>
          <td width="20%">: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kanan_atas?> </td>
          <td class="title" width="10%" ><?php echo label_en('gigi ke -') ?></td>
          <td >: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kanan_atas_ke; ?> (<?php echo $pemeriksaan_fisik->mulut_gigi->catatan_gigi_kanan_atas; ?>)</td>
      </tr>
      <tr>
          <td width="20px"></td>
          <td class="title" width="30%"><span class="dejavu-font">&#x26AC;</span> <?php echo label_en('gigi kanan bawah') ?></td>
          <td width="20%">: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kanan_bawah?></td>
          <td class="title" width="10%" ><?php echo label_en('gigi ke -') ?></td>
          <td >: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kanan_bawah_ke; ?> (<?php echo $pemeriksaan_fisik->mulut_gigi->catatan_gigi_kanan_bawah; ?>)</td>
      </tr>

      <tr>
          <td width="20px"></td>
          <td class="title" width="30%"><span class="dejavu-font">&#x26AC;</span> <?php echo label_en('gigi kiri atas') ?></td>
          <td width="20%">: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kiri_atas?></td>
          <td class="title" width="10%" ><?php echo label_en('gigi ke -') ?></td>
          <td >: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kiri_atas_ke; ?> (<?php echo $pemeriksaan_fisik->mulut_gigi->catatan_gigi_kiri_atas; ?>)</td>
      </tr>

      <tr>
          <td width="20px"></td>
          <td class="title" width="30%"><span class="dejavu-font">&#x26AC;</span> <?php echo label_en('gigi kiri bawah') ?></td>
          <td width="20%">: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kiri_bawah?></td>
          <td class="title" width="10%" ><?php echo label_en('gigi ke -') ?></td>
          <td >: <?php echo $pemeriksaan_fisik->mulut_gigi->gigi_kiri_bawah_ke; ?> (<?php echo $pemeriksaan_fisik->mulut_gigi->catatan_gigi_kiri_bawah; ?>)</td>
      </tr>

      <tr>
          <td width="20px"></td>
          <td class="title" width="30%"><span class="dejavu-font">&#x26AC;</span> <?php echo label_en('keterangan') ?></td>
          <td width="70%" colspan="2">: <?php echo $pemeriksaan_fisik->mulut_gigi->keterangan?></td>
      </tr>


      <?php foreach ($pemeriksaan_fisik as $key => $value) {
        if(!in_array($key, array('buta_warna', 'mulut_gigi'))){
          $res = str_replace('_', ' ', $key);
          $name = ($key=='tht')?strtoupper($res):label_en(strtolower($res));
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
              $name = ($k=='jvp')?strtoupper($res_):label_en(strtolower($res_));
              ?>
              
              <tr>
                <td width="20px"></td>
                <td class="title" width="30%"><span class="dejavu-font">&#x26AC;</span> <?php echo $name ?></td>
                <td width="75%">: 
                  <?php 
                    switch ($k) {
                      case 'hidung':
                        echo ucfirst($v);
                      break;

                      case 'gigi':
                        $vals = json_decode($v);
                        foreach ($param_gigi as $gigi) : ?>
                            <span class="dejavu-font"><?php echo (in_array($gigi->value, $vals))?'&#9745;':'&#9744;' ?></span>&nbsp;<?php echo $gigi->label ?> &nbsp;&nbsp;
                        <?php 
                        endforeach ;
                      break;

                      case 'lidah':
                        echo ucfirst($v);
                      break;

                      case 'jvp':
                        echo ucfirst($v);
                      break;

                      case 'tiroid':
                      case 'kel_getah_bening':
                      case 'hati_atau_limpa':
                      case 'tumor':
                        echo ucfirst($v);
                      break;

                      case 'besar':
                        echo ucfirst($v);
                        
                      break;

                      case 'bunyi_S1_strip_S2':
                        echo ucfirst($v);
                      break;

                      case 'bising':
                        echo ucfirst($v);
                      break;

                      case 'extremitas':
                      case 'kulit_atau_turgor':
                        echo ($v=='Dalam Batas Normal')?'Dalam Batas Normal':'Tidak Normal';

                      break;

                      case 'nyeri_tekan':
                        echo ($v=='Negatif')?'Negatif':'Positif';
                      break;

                      case 'lainnya':
                        echo ucfirst($v);
                      break;
                      
                      default:
                        echo nl2br(ucfirst($v));
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
    <p style="font-weight: bold"><?php echo section_en('D. pemeriksaan radiologi') ?></p>

    <?php 
      echo $hasil_penunjang['050201'][0]->html;
    ?>

    <p style="font-weight: bold"><?php echo section_en('E. pemeriksaan ekg') ?></p>

    <table border="0" width="100%">
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('irama') ?></td>
        <td width="78%">:
          <?php echo $pemeriksaan_ekg->irama; ?>
        </td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('hr') ?></td>
        <td width="78%">: <?php echo $pemeriksaan_ekg->hr ?> x/menit</td>
      </tr>
      <tr>
        <td width="20px"></td>
        <td class="title"><?php echo label_en('kesan') ?></td>
        <td width="78%">: <?php echo nl2br(ucwords($pemeriksaan_ekg->kesan)) ?></td>
      </tr>
    </table>
    
    <div style="page-break-before: always;"></div>

    <p style="font-weight:bold; margin-top:0;">
      <?php echo section_en('F. pemeriksaan laboratorium') ?>
    </p>

    <?php 
      foreach($hasil_penunjang['050101'] as $key_lab => $val_lab){
        echo $val_lab->html;
      }
    ?>
    
    <br pagebreak="true"/>

    <p style="font-weight: bold"><?php echo section_en('G. kesimpulan') ?></p>

    <table border="0" width="100%">
      
      <?php $no=1; foreach ($hasil as $key => $value) {
        $res = str_replace('_', ' ', $key);
        echo 
        '
        <tr>
          <td width="20px"></td>
          <td width="38%">'.$no.'. '.label_en(strtolower($res)).'</td>
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

            if($res_=='gigi' || $k=='gigi') {
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
              <td width="32%">'.label_en(strtolower($res_)).'</td>
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
        <td width="20%"><b><?php echo label_en('kesan') ?> :</b></td>
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
    <p style="font-weight: bold"><?php echo section_en('H. anjuran / saran') ?></p>

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
        <td width="40%" style="text-align:center"><?php echo label_en('penanggung jawab') ?></td>
      </tr>
      <tr>
        <td></td>
        <td style="text-align:center"><?php echo $img?><br><b><?php echo ucwords($kunjungan->nama_pegawai) ?></b></td>
      </tr>
      
    </table><br>
    
    <?php if(!empty($attachment)): ?>
    <br pagebreak="true"/>
    <p style="font-weight: bold; text-align: center"><?php echo section_en('lampiran hasil pemeriksaan') ?></p>
    <?php
      // get max key
      $maxKey = array_keys($attachment, max($attachment))[0];
      foreach ($attachment as $ka => $va) {
        if(in_array( $va->csm_dex_jenis_dok, array('image/png', 'image/jpg', 'image/jpeg') )){
          $base_url = ltrim($va->base_url_dok);
          $url = ltrim($va->csm_dex_fullpath);
          if (file_exists($url)) {
              $pageBreak = ($ka==$maxKey)?'':'<br pagebreak="true"/>';
              echo '<img src="'.$url.'">'.$pageBreak.'';
          }
        }
      }
    ?>
    <?php endif; ?>

   
  </div>

</body>


</html>