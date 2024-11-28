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

  function copy_soap(id){
      preventDefault();
      if(confirm('Apakah anda yakin akan menyalin SOAP ini?')){
          var formData = {
            kode_riwayat : id,
          };
          $.ajax({
              url: "pelayanan/Pl_pelayanan/copy_soap",
              data: formData,            
              dataType: "json",
              type: "POST",
              success: function (response) {
                  // load form pesan resep
                  var obj = response.result;
                  console.log(obj);
                  $('#pl_anamnesa').val(obj.anamnesa);
                  $('#pl_pemeriksaan').val(obj.pemeriksaan);
                  $('#pl_diagnosa').val(obj.diagnosa_akhir);
                  $('#pl_diagnosa_hidden').val(obj.kode_icd_diagnosa);
                  $('#pl_pengobatan').val(obj.pengobatan+'\n'+obj.resep_farmasi);
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
.tab-content {
    padding: 9px 7px !important;
}

.panel-body {
    /* height:800px; */
    overflow: hidden;
    /* transform: rotate(90deg); */
    /* transform-origin: 188px 241px 0; */
}
.content-acordion {
    max-height: 500px;
    overflow: scroll;
}

</style>

<div id="accordion" class="accordion-style1 panel-group accordion-style2" style="position: relative; top: 0px; transition-property: top; transition-duration: 0.15s;">
  <?php 
    if(count($result) > 0):
    foreach ($result as $key => $value) : 
      $default_toogle = (in_array($key, array(0))) ? 'in' : '' ;
      $lembar_konsul = 0;
      $files = isset($file_pkj[$value->no_registrasi][$value->no_kunjungan])?$file_pkj[$value->no_registrasi][$value->no_kunjungan]:array();
      $html_file = '';
      if(count($files) > 0){
        $html_file .= "<ol>";
        foreach ($files as $kpkj => $vpkj) {
          $html_file .= '<li style="font-weight: bold"><a href="#" onclick="show_modal_medium_return_json('."'pelayanan/Pl_pelayanan_ri/show_catatan_pengkajian/".$vpkj->id."'".', '."'".$vpkj->jenis_pengkajian."'".')">'.$vpkj->jenis_pengkajian.'</a></li>';
          // apakah ada lembar konsul internal
          $lembar_konsul = ($vpkj->jenis_form == 29)?1:0;
        }
        $html_file .= "</ol>";
      }else{
        $html_file .= 'Tidak ada file ditemukan';
      }
  ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $value->no_kunjungan?>" style="line-height: 15px; font-weight: normal !important; font-size: 13px">
            <i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
            <b><?php echo $this->tanggal->formatDateTime($value->tgl_periksa)?></b><?php echo (empty($value->status_kunjungan)) ?' <span style="color: red; font-weight: bold">[Batal]</span>':''?><br>
            <div style="padding-left: 20px">
              <?php echo $value->dokter_pemeriksa?><br>
              <?php echo ucwords($value->nama_bagian)?>
              <?php echo (!in_array($value->cara_keluar_pasien, [null, 'Atas Persetujuan Dokter', 'Atas Permintaan Sendiri']))?'<br><span class="label label-primary">'.$value->cara_keluar_pasien.'</span>':'';?>&nbsp;
              <?php echo ($lembar_konsul == 1)?'<span class="label label-warning">Konsul Internal</span>':'';?>
            </div>
          </a>
        </h4>
      </div>

      <div class="panel-collapse collapse <?php echo $default_toogle?>" id="collapse<?php echo $value->no_kunjungan?>">
        <div class="panel-body" style="border: 1px solid #dcd9d9;padding: 5px;background: lightyellow;">
          <center>
            <a href="#" class="btn btn-xs btn-success" onclick="copy_soap(<?php echo $value->kode_riwayat?>)">Copy SOAP</a>
            <a href="#" class="btn btn-xs btn-primary" onclick="show_modal('registration/reg_pasien/view_detail_resume_medis/<?php echo $value->no_registrasi?>', 'RESUME MEDIS PASIEN')">Selengkapnya</a>
          </center>
          <br>
          <div class="content-acordion">
            <span style="font-weight: bold; font-style: italic; color: blue">(Subjective)</span>
            <div style="margin-top: 6px">
                <label for="form-field-8"> <b>Anamnesa / Keluhan Pasien</b> : </label><br>
                <?php echo isset($value->anamnesa)?nl2br($value->anamnesa):''?>
            </div>
            <br>

            <span style="font-weight: bold; font-style: italic; color: blue">(Objective)</span>
            <div style="margin-top: 6px">
                <label for="form-field-8"> <i><b>Vital Sign</b></i></label>
                <table class="table">
                    <tr style="font-size: 11px; background: beige; text-align: center">
                        <th>TB (Cm)</th>
                        <th>BB (Kg)</th>
                        <th>TD (mmHg)</th>
                        <th>Nadi (bpm)</th>
                        <th>Suhu (C&deg;)</th>
                    </tr>
                    <tbody>
                    <tr style="background: aliceblue;">
                        <td>
                            <input type="text" style="text-align: center" class="form-control" name="pl_tb" value="<?php echo isset($value->tinggi_badan)?$value->tinggi_badan:''?>">
                        </td>
                        <td>
                            <input type="text" style="text-align: center" class="form-control" name="pl_bb" value="<?php echo isset($value->berat_badan)?$value->berat_badan:''?>">
                        </td>
                        <td>
                            <input type="text" style="text-align: center" class="form-control" name="pl_td" value="<?php echo isset($value->tekanan_darah)?$value->tekanan_darah:''?>">
                        </td>
                        <td>
                            <input type="text" style="text-align: center" class="form-control" name="pl_nadi" value="<?php echo isset($value->nadi)?$value->nadi:''?>">
                        </td>
                        <td>
                            <input type="text" style="text-align: center" class="form-control" name="pl_suhu" value="<?php echo isset($value->suhu)?$value->suhu:''?>">
                        </td>
                        
                    </tr>
                    </tbody>
                </table>

                <label for="form-field-8"> <b>Pemeriksaan Fisik : </b></label><br>
                <?php echo isset($value->pemeriksaan)?nl2br($value->pemeriksaan):''?>
                
            </div>
            <br>

            <span style="font-weight: bold; font-style: italic; color: blue">(Assesment)</span>
            <div style="margin-top: 6px">
                <label for="form-field-8"><b>Diagnosa Primer(ICD10) : </b></label><br>
                <?php echo isset($value->kode_icd_diagnosa)?$value->kode_icd_diagnosa:''?> - <?php echo isset($value->diagnosa_akhir)?$value->diagnosa_akhir:''?>
            </div>

            <div style="margin-top: 6px">
                <label for="form-field-8"><b>Diagnosa Sekunder</b> </label><br>
                <div id="pl_diagnosa_sekunder_hidden_txt" style="padding: 2px; line-height: 23px; border: 1px solid #d5d5d5; min-height: 25px; margin-top: 2px">
                    <?php
                        $arr_text = isset($value->diagnosa_sekunder) ? explode('|',$value->diagnosa_sekunder) : [];
                        // echo "<pre>";print_r($arr_text);
                        $no_ds = 1;
                        foreach ($arr_text as $k => $v) {
                            $len = strlen(trim($v));
                            // echo $len;
                            if($len > 0){
                                $no_ds++;
                                $split = explode(':',$v);
                                if(count($split) > 1){
                                    echo '<span class="multi-typeahead" id="txt_icd_'.trim(str_replace('.','_',$split[0])).'"><a href="#" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span> <span class="text_icd_10"> '.$v.' </span> </span>';
                                }else{
                                    echo '<span class="multi-typeahead" id="txt_icd_'.$no_ds.'"><a href="#" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span> <span class="text_icd_10"> '.$v.' </span> </span>';
                                }
                            }
                            
                        }
                    ?>
                </div>
            </div>

            <div style="margin-top: 6px">
                <label for="form-field-8"><b>Prosedur/ Tindakan (ICD9) : </b></label><br>
                <?php echo isset($value->kode_icd9)?$value->kode_icd9:''?> - <?php echo isset($value->text_icd9)?$value->text_icd9:''?>
            </div>
            <br>
            <span style="font-weight: bold; font-style: italic; color: blue">(Planning)</span>
            <div style="margin-top: 6px">
                <label for="form-field-8"><b>Rencana Asuhan / Anjuran Dokter : </b></label><br>
                <?php echo isset($value->pengobatan)?nl2br($value->pengobatan):''?>
                <br>
                <label for="form-field-8"><b>Resep Dokter : </b></label><br>
                <?php echo isset($value->resep_farmasi)?nl2br($value->resep_farmasi):''?>
                <br>
                <label for="form-field-8"><b>Tgl Kontrol Kembali : </b></label><br>
                <?php echo isset($value->tgl_kontrol_kembali)?$this->tanggal->formatDate($value->tgl_kontrol_kembali):''?><br>
                <?php echo isset($value->catatan_kontrol_kembali)?$value->catatan_kontrol_kembali:''?>
            </div>
            <br>
            <span style="font-weight: bold; font-style: italic; color: blue">(e-Resep)</span><br>
            <label for="form-field-8"><b>Obat yang diresepkan dokter : </b></label><br>
            <?php
              $eresep_result = isset($eresep[$value->no_registrasi][$value->no_kunjungan])?$eresep[$value->no_registrasi][$value->no_kunjungan]:array();
              // echo "<pre>"; print_r($eresep_result);die;
              $html = '';
              foreach($eresep_result as $key_er=>$val_er){
                $html .= '<small>Tanggal resep. <i>('.$this->tanggal->formatDateTime($val_er[0]->created_date).')</i></small>';
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
            <br>
            <span style="font-weight: bold; font-style: italic; color: blue">(File Pengkajian Pasien)</span><br>
            <label for="form-field-8"><b>File Pengkajian Pasien/ File Rekam Medis per Periode Kunjungan </b></label><br>
            <?php echo $html_file; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; else: echo "<div class='alert alert-warning'><b>Pasien Baru</b><br>Belum ada riwayat medis sebelumnya.</div>";endif;?>
</div>