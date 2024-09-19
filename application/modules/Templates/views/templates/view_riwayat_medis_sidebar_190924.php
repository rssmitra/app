<script style="text/javascript">

  function resepkan_ulang(id){
      preventDefault();
      if(confirm('Apakah anda yakin akan meresepkan kembali obat-obat ini?')){
          var formData = {
            kode_pesan_resep : id,
            no_registrasi : $('#no_registrasi').val(),
            no_kunjungan : $('#no_kunjungan').val(),
            no_mr : $('#noMrHidden').val(),
            kode_kelompok : $('#kode_kelompok').val(),
            kode_perusahaan : $('#kode_perusahaan_val').val(),
            kode_klas : $('#kode_klas').val(),
            kode_profit : $('#kode_profit').val(),
            kode_bagian_asal : $('#kode_bagian_asal').val(),
            kode_dokter : $('#kode_dokter_poli').val(),
          };
          $.ajax({
              url: "farmasi/E_resep/proses_resepkan_ulang",
              data: formData,            
              dataType: "json",
              type: "POST",
              success: function (response) {
                  // load form pesan resep
                  $('.nav-list li').removeClass('active');
                  $('li#li_tabs_farmasi').addClass('active');
                  getMenuTabs('farmasi/Farmasi_pesan_resep/pesan_resep/'+$('#no_kunjungan').val()+'/'+$('#kode_klas').val()+'/'+$('#kode_profit').val(), 'tabs_form_pelayanan')
              }
          });
      }else{
          return false;
      }
      
  }
</script>

<style>
hr {
    margin-top: 5px !important;
    margin-bottom: 5px !important;
    border: 0;
    border-top: 1px solid #eeeeee;
}
</style>

<div id="accordion" class="accordion-style1 panel-group accordion-style2" style="overflow-y: scroll;max-height: 841px;">
  <?php 
    if(count($result) > 0):
    foreach ($result as $key => $value) : 
      $default_toogle = (in_array($key, array(0))) ? 'in' : '' ;
      // echo "<pre>"; print_r($value);die;
  ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $value->no_kunjungan?>" style="line-height: 15px; font-weight: normal !important; font-size: 13px">
            <i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
            <b><?php echo $this->tanggal->formatDateTime($value->tgl_periksa)?></b><br>
            <div style="padding-left: 20px">
              <?php echo $value->dokter_pemeriksa?><br>
              <?php echo ucwords($value->nama_bagian)?>
            </div>
          </a>
        </h4>
      </div>

      <div class="panel-collapse collapse <?php echo $default_toogle?>" id="collapse<?php echo $value->no_kunjungan?>">
        <div class="panel-body" style="border: 1px solid #dcd9d9;padding: 5px;background: lightyellow;">
          <center style="background: #f4ae124a"><span style="font-size: 14px !important; font-weight: bold">RESUME MEDIS PASIEN</span><br><i>Kode. <span><b><a href="#" onclick="show_modal('registration/reg_pasien/view_detail_resume_medis/<?php echo $value->no_registrasi?>', 'RESUME MEDIS PASIEN')"><?php echo $value->no_registrasi?></a></b></span></i></center>
          <br>

          <table style="width: 100%">
            <tr>
              <td colspan="3" align="left">
                <b>ASSESMENT PASIEN :</b>
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
                <br>
                <p>
                  <span style="font-weight: bold;">DIAGNOSA :</span><br><?php echo $value->diagnosa_awal; ?> <br><br>
                  <span style="font-weight: bold;">ANAMNESA :</span><br><?php echo nl2br($value->anamnesa); ?> <br><br>
                  <span style="font-weight: bold;">TINDAKAN/ PEMERIKSAAN :</span><br><?php echo nl2br($value->pemeriksaan); ?><br><br>
                  <span style="font-weight: bold;">ANJURAN DOKTER :</span><br><?php echo nl2br($value->pengobatan); ?><br><br>
                  <span style="font-weight: bold;">RESEP FARMASI :</span><br><?php echo nl2br($value->resep_farmasi); ?>
                </p>

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

                <b>e-RESEP</b>
                <div id="accordion_resep" class="accordion-style1 panel-group">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_resep" href="#collapseResep<?php echo $value->no_kunjungan?>" style="background: #4c8fbd; color: white;">
                          <i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                          <b>&nbsp;E-RESEP </b>
                        </a>
                      </h4>
                    </div>

                    <div class="panel-collapse collapse" id="collapseResep<?php echo $value->no_kunjungan?>">
                      <div class="panel-body" style="background: #4c8fbd1a">
                        <!-- e-resep -->
                        <div style="padding: 5px">
                          <center><span style="font-size: 14px">Resep Dokter</span><br><i>Obat yang diresepkan dokter</i></center>
                          <br>
                          <?php
                            $eresep_result = isset($eresep[$value->no_registrasi][$value->no_kunjungan])?$eresep[$value->no_registrasi][$value->no_kunjungan]:array();
                            // echo "<pre>"; print_r($eresep_result);die;
                            $html = '';
                            foreach($eresep_result as $key_er=>$val_er){
                              $html .= '<span style="font-size:11px; font-style: italic;">Kode Resep :</span><br><span style="font-size: 18px !important; font-weight: bold">'.$key_er.'</span> <small><i>('.$this->tanggal->formatDateTime($val_er[0]->created_date).')</i></small>';
                              $html .= '<br>';
                              $html .= '<table class="table" id="dt_add_resep_obat">
                                <thead>
                                <tr>
                                    <th width="30px">No</th>
                                    <th>Nama Obat</th>
                                </tr>
                                </thead>
                                <tbody style="background: white">';
                                $no = 0;
                                
                                foreach ($val_er as $ker => $ver) {
                                
                                  $no++;
                                  // get child racikan
                                  $child_racikan = $this->master->get_child_racikan_data($ver->kode_pesan_resep, $ver->kode_brg);
                                  $html_racikan = ($child_racikan != '') ? '<br><div style="padding:10px"><span style="font-size:11px; font-style: italic">bahan racik :</span><br>'.$child_racikan.'</div>' : '' ;
                                  $html .= '<tr>';
                                  $html .= '<td align="center" valign="top">'.$no.'</td>';
                                  $html .= '<td>'.strtoupper($ver->nama_brg).''.$html_racikan.'<br>'.$ver->jml_dosis.' x '.$ver->jml_dosis_obat.' '.$ver->satuan_obat.' '.$ver->aturan_pakai.'<br>Qty. '.$ver->jml_pesan.' '.$ver->satuan_obat.'<br>'.$ver->keterangan.'</td>';
                                  $html .= '</tr>';

                                }
                                $html .= '<tr><td colspan="2" align="center"><a href="#" class="btn btn-xs btn-primary" onclick="resepkan_ulang('.$ver->kode_pesan_resep.')">Resepkan Kembali</a></td></tr>';

                                $html .= '</tbody></table>';
                            }
                            echo $html;
                          ?>
                        </div>
                        <br>
                        
                      </div>
                    </div>
                  </div>

                  <!-- <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_resep" href="#collapseFileRM<?php echo $value->no_kunjungan?>">
                          <i class="ace-icon fa fa-angle-right bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                          <b>&nbsp;FILE REKAM MEDIS</b>
                        </a>
                      </h4>
                    </div>

                    <div class="panel-collapse collapse" id="collapseFileRM<?php echo $value->no_kunjungan?>">
                      <div class="panel-body">
                        <div style="padding: 5px">
                          <b>FILE REKAM MEDIS</b>
                          <?php if(count($file) > 0) : ?>
                            <center><span style="font-size: 14px">File Rekam Medis</span></center>
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
                          <?php else: echo "-Tidak ada file rekam medis"; endif; ?>
                        </div>
                      </div>
                    </div>
                  </div> -->

                </div>

              </td>
            </tr>
          </table>
          
        </div>
      </div>
    </div>
  <?php endforeach; else: echo "<div class='alert alert-warning'><b>Pasien Baru</b><br>Belum ada riwayat medis sebelumnya.</div>";endif;?>
</div>