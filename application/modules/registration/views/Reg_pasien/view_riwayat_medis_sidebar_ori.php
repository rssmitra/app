<style>
hr {
    margin-top: 5px !important;
    margin-bottom: 5px !important;
    border: 0;
    border-top: 1px solid #eeeeee;
}
</style>
<div id="accordion" class="accordion-style1 panel-group accordion-style2">
  <?php foreach ($result as $key => $value) : 
      $default_toogle = (in_array($key, array(0))) ? 'in' : '' ;
  ?>
    <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $value->no_kunjungan?>" style="background: linear-gradient(1deg, #9ad62c, #ceff75)">
                <i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                &nbsp;<?php echo $this->tanggal->formatDateTime($value->tgl_periksa)?>
              </a>
            </h4>
          </div>

          <div class="panel-collapse collapse <?php echo $default_toogle?>" id="collapse<?php echo $value->no_kunjungan?>">
            <div class="panel-body" style="border: 1px solid #dcd9d9;padding: 5px;background: lightyellow;">
              <table style="width: 100%">
                <tr>
                  <td style="vertical-align: text-top" width="70px">No. Reg</td>
                  <td style="vertical-align: text-top"> : </td>
                  <td> <a href="#" onclick="show_modal('registration/reg_pasien/view_detail_resume_medis/<?php echo $value->no_registrasi?>', 'RESUME MEDIS PASIEN')"><?php echo $value->no_registrasi?></a></td>
                </tr>

                <tr >
                  <td style="vertical-align: text-top">Poli/Klinik</td>
                  <td style="vertical-align: text-top"> : </td>
                  <td> <?php echo ucwords($value->nama_bagian)?></td>
                </tr>

                <tr>
                  <td style="vertical-align: text-top">Dokter</td>
                  <td style="vertical-align: text-top"> : </td>
                  <td> <?php echo $value->dokter_pemeriksa?></td>
                </tr>
                <tr>
                  <td colspan="3" align="left"><hr></td>
                </tr>
                <tr>
                  <td colspan="3" align="left">
                    <ol>
                      <li>Anamnesa dan Pemeriksaan Fisik<br><?php echo ($value->anamnesa != '')?$value->anamnesa:'-';?><br><?php echo ($value->pemeriksaan != '')?$value->pemeriksaan:'-'?></li>
                      <li>Diagnosis (Kode ICD)<br><?php echo ($value->diagnosa_akhir != '')?$value->diagnosa_akhir:'-'?></li>
                      <li>Terapi/ Tindakan<br><?php echo ($value->pengobatan != '')?$value->pengobatan:'-'?></li>
                      <li>Obat Farmasi<br>
                            <?php 
                              $result = isset($obat[$value->no_registrasi])?$obat[$value->no_registrasi]:array();
                              foreach($result as $row_obt) : ?>
                              - <?php echo $row_obt->nama_tindakan?></br>
                            <?php endforeach; ?>
                      </li>
                      <li>Penunjang Medis<br>
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
                      </li>
                    </ol>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
  <?php endforeach;?>
</div>