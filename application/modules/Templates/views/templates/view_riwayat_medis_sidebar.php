<style>
hr {
    margin-top: 5px !important;
    margin-bottom: 5px !important;
    border: 0;
    border-top: 1px solid #eeeeee;
}
</style>
<div id="accordion" class="accordion-style1 panel-group accordion-style2" style="overflow-y: scroll;max-height: 841px;">
  <?php foreach ($result as $key => $value) : 
      $default_toogle = (in_array($key, array(0))) ? 'in' : '' ;
  ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $value->no_kunjungan?>" style="background: linear-gradient(1deg, #c0ef6b, #f9f9f9a3); line-height: 15px; font-weight: normal !important">
            <i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
            <b><?php echo $this->tanggal->formatDateTime($value->tgl_periksa)?></b><br>
            <?php echo $value->dokter_pemeriksa?><br>
            <?php echo ucwords($value->nama_bagian)?>
          </a>
        </h4>
      </div>

      <div class="panel-collapse collapse <?php echo $default_toogle?>" id="collapse<?php echo $value->no_kunjungan?>">
        <div class="panel-body" style="border: 1px solid #dcd9d9;padding: 5px;background: lightyellow;">
          <table style="width: 100%">
            <tr>
              <td style="vertical-align: text-top" colspan="2" >No. Registrasi <a href="#" onclick="show_modal('registration/reg_pasien/view_detail_resume_medis/<?php echo $value->no_registrasi?>', 'RESUME MEDIS PASIEN')"><?php echo $value->no_registrasi?></a> </td>
              <td> </td>
            </tr>

            <tr>
              <td style="vertical-align: text-top"><i class="fa fa-building"></i></td>
              <td>
                <?php echo ucwords($value->nama_bagian)?>
                <?php echo ($value->validasi ==  300) ? '<a href="#" class="pull-right label label-primary" onclick="show_modal_medium('."'pelayanan/Pl_pelayanan_ri/view_cppt?no_kunjungan=".$value->no_kunjungan."'".', '."'CATATAN PERKEMBANGAN PASIEN TERINTEGRASI (CPPT)'".')">Lihat CPPT</a>' : '' ;?>
              </td>
            </tr>

            <tr>
              <td style="vertical-align: text-top"><i class="fa fa-user"></i></td>
              <td> <?php echo $value->dokter_pemeriksa?></td>
            </tr>
            <tr>
              <td colspan="3" align="left"><hr></td>
            </tr>
            <tr>
              <td colspan="3" align="left">
              <b>Assesment Pasien :</b>
              <table class="table table-bordered">
                <tr>
                  <td class="center">TB (cm)</td>
                  <td class="center">TD</td>
                  <td class="center">Nadi</td>
                  <td class="center">BB (Kg)</td>
                  <td class="center">Suhu (C)</td>
                </tr>
                <tr>
                  <td class="center"><?php echo ($value->tinggi_badan != '')?$value->tinggi_badan:'-';?></td>
                  <td class="center"><?php echo ($value->tekanan_darah != '')?$value->tekanan_darah:'-';?></td>
                  <td class="center"><?php echo ($value->nadi != '')?$value->nadi:'-';?></td>
                  <td class="center"><?php echo ($value->berat_badan != '')?$value->berat_badan:'-';?></td>
                  <td class="center"><?php echo ($value->suhu != '')?$value->suhu:'-';?></td>
                </tr>
              </table>
              <b>Anamnesa dan Pemeriksaan Fisik :</b>
              <table class="table table-bordered">
                <tr>
                  <td>
                    <?php echo ($value->anamnesa != '')?nl2br($value->anamnesa).'<br>':'';?>
                    <?php echo ($value->pemeriksaan != '')?nl2br($value->pemeriksaan):''?>

                  </td>
                </tr>
              </table>
              
              <b>Diagnosis (Kode ICD) :</b>
              <table class="table table-bordered">
                <tr>
                  <td>
                  <?php echo ($value->diagnosa_awal != '') ? ($value->diagnosa_akhir != $value->diagnosa_awal) ? 'Diagnosa Awal. '.nl2br($value->diagnosa_awal).'<br>': ''.nl2br($value->diagnosa_awal) : ''?>
                  <?php echo ($value->diagnosa_akhir != '') ? ($value->diagnosa_akhir != $value->diagnosa_awal) ? 'Diagnosa Akhir. '.nl2br($value->diagnosa_akhir): '' : ''?><br>
                  <?php 
                    if((int)$value->validasi == 300) {
                      echo ($value->diagnosa_sekunder != '') ? '<br>Diagnosa Sekunder : <br>'.$value->diagnosa_sekunder : '';
                    }
                  ?>
                  </td>
                </tr>
              </table>
              
              <b>Terapi/Tindakan/Prosedur :</b>
              <table class="table table-bordered">
                <tr>
                  <td>
                    <?php echo ($value->pengobatan != '')?nl2br($value->pengobatan):'-'?><br>
                    <?php echo ($value->tindakan_prosedur != '')?nl2br($value->tindakan_prosedur):'-'?>
                    <?php echo ($value->anjuran_dokter != '')?nl2br($value->anjuran_dokter):'-'?>
                  </td>
                </tr>
              </table>

              <b>Lainnya :</b>
              <table class="table table-bordered">
                <tr>
                  <td>
                    <?php echo ($value->alergi_obat != '')?nl2br($value->alergi_obat):'-'?><br>
                    <?php echo ($value->diet != '')?nl2br($value->diet):'-'?>
                  </td>
                </tr>
              </table>
              
              <b>Resep Dokter :</b>
              <table class="table table-bordered">
                <tr>
                  <td>
                    <?php echo ($value->resep_farmasi != '')?nl2br($value->resep_farmasi):'-'?>
                  </td>
                </tr>
              </table>

              <b>Obat yang diberikan farmasi :</b>
              <table class="table table-bordered">
                <tr>
                  <td>
                  <?php 
                    $result = isset($obat[$value->no_registrasi])?$obat[$value->no_registrasi]:array();
                    foreach($result as $row_obt) : ?>
                    - <?php echo $row_obt->nama_tindakan?></br>
                  <?php endforeach; ?>
                  </td>
                </tr>
              </table>
              
              <?php if(count($penunjang) > 0) : ?>
              <b>Hasil Penunjang Medis :</b>
              <table class="table table-bordered">
                <tr>
                  <td>
                  <?php 
                    $result_pm = isset($penunjang[$value->no_registrasi])?$penunjang[$value->no_registrasi]:array();
                    foreach($result_pm as $row_pm) : 
                      switch ($row_pm->kode_bagian_tujuan) {
                        case '050101':
                          $type_pm = 'LAB';
                          $color_pm = '#e8b0b0';
                          break;
                        case '050201':
                          $type_pm = 'RAD';
                          $color_pm = '#e2b73e';
                          break;
                        case '050201':
                          $type_pm = 'FISIO';
                          $color_pm = '#5ed3f7';
                          break;
                      }
                    ?>
                    - <a href="#" onclick="PopupCenter('<?php echo base_url()?>Templates/Export_data/export?type=pdf&flag=<?php echo $type_pm; ?>&noreg=<?php echo $row_pm->no_registrasi;?>&pm=<?php echo $row_pm->kode_penunjang?>&kode_pm=<?php echo $row_pm->kode_bagian_tujuan?>&no_kunjungan=<?php echo $row_pm->no_kunjungan?>', 'Hasil Penunjang Medis', 850, 650)" style="font-weight: bold; background: <?php echo $color_pm?>; color: black; padding: 2px"><?php echo $row_pm->nama_bagian?></a></br>
                  <?php endforeach; ?>
                  </td>
                </tr>
              </table>
              <?php endif;?>

              <?php if(count($file) > 0) : ?>
              <b>File Rekam Medis :</b>
              <table class="table table-bordered">
                <tr>
                  <td>
                  <?php 
                    $result_file = isset($file[$value->no_registrasi][$value->no_kunjungan])?$file[$value->no_registrasi][$value->no_kunjungan]:array();
                    foreach($result_file as $row_file) : 
                      $exp_file = explode('-', $row_file->csm_dex_nama_dok);
                      $filename = isset($exp_file[0])?$exp_file[0]:'Lampiran File';
                      if(!in_array($filename, array('SEP','RJ'))) :
                    ?>
                    - <a href="#" onclick="show_modal_with_iframe('<?php echo BASE_FILE_RM.$row_file->csm_dex_fullpath?>', '<?php echo $filename; ?>')"><?php echo $row_file->csm_dex_nama_dok; ?></a></br>
                  <?php endif; endforeach; ?>
                  </td>
                </tr>
              </table>
              <?php endif; ?>

              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  <?php endforeach;?>
</div>