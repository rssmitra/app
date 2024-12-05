<script type="text/javascript">

    $('.date-picker').datepicker({    
        autoclose: true,    
        todayHighlight: true    
    })  
    //show datepicker when clicking on the icon
    .next().on(ace.click_event, function(){    
        $(this).prev().focus();    
    }); 

    $('select[name="pasca_pulang"]').change(function () {      

        if ($(this).val() == 'Meninggal') {        
            $('#kode_kematian').show('fast');
        }else{
            $('#kode_kematian').hide('fast');
        }
    }); 

    $('#btn_pasien_pulang').click(function (e) {  
      e.preventDefault();
      /*process pasien pulang*/

      $.ajax({
          url: "pelayanan/Pl_pelayanan_ri/processPelayananSelesai",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              $.achtung({message: response.message, timeout:5});
              $('#div_main_form').load('pelayanan/Pl_pelayanan_ri/form_main/'+$('#kode_ri').val()+'/'+$('#no_kunjungan').val()+'');
            }else{
              $.achtung({message: response.message, timeout:5, className: 'achtungFail'});
            }
          }
      });

    });
</script>
<div class="row" id="section_form_diagnosa" style="padding:8px">

    <div class="col-sm-12">

        <p><b>DIAGNOSA DAN PEMERIKSAAN </b></p>

        <!-- hidden form -->
        <input type="hidden" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:0?>">
        <input type="hidden" name="status_pulang" id="status_pulang" value="<?php echo isset($riwayat->status_pulang)?$riwayat->status_pulang:0?>">

        <div style="margin-top: 6px">
            <label for="form-field-8">Anamnesa <span style="color:red">* </span> <small>(minimal 8 karakter)</small> </label>
            <textarea class="form-control" name="pl_anamnesa" style="height: 100px !important"><?php echo isset($riwayat->anamnesa)?$this->master->br2nl($riwayat->anamnesa):''?></textarea>
        </div>

        <div style="margin-top: 6px">
            <label for="form-field-8">Pemeriksaan Fisik <span style="color:red">* </span> <small>(minimal 8 karakter)</small> </label>
            <textarea class="form-control" name="pl_pemeriksaan" style="height: 100px !important"><?php echo isset($riwayat->pemeriksaan)?$this->master->br2nl($riwayat->pemeriksaan):''?></textarea>
        </div>

        <div class="row">
            <div class="col-md-6" style="margin-top: 6px">
                <label for="form-field-8">Instruksi / Anjuran Dokter / Hasil Konsultasi</label>
                <textarea name="pl_anjuran_dokter" id="pl_anjuran_dokter" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->anjuran_dokter)?$this->master->br2nl($riwayat->anjuran_dokter):''?></textarea>
            </div>

            <div class="col-md-6" style="margin-top: 6px">
                <label for="form-field-8">Pengobatan </label>
                <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pengobatan)?$this->master->br2nl($riwayat->pengobatan):''?></textarea>
            </div>

        </div>

        <div style="margin-top: 6px">
            <label for="form-field-8">Diagnosa Utama (ICD10) <span style="color:red">* </span></label>
            <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_awal)?$riwayat->diagnosa_awal:''?>">
            <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
        </div>

        <div style="margin-top: 6px">
            <label for="form-field-8">Diagnosa Sekunder (ICD10) <span style="color:red">* </span></label>
            <textarea class="form-control" name="pl_diagnosa_sekunder" id="pl_diagnosa_sekunder" placeholder="" style="height: 50px !important"><?php echo isset($riwayat->diagnosa_sekunder)?$this->master->br2nl($riwayat->diagnosa_sekunder):''?></textarea>
        </div>

        <div style="margin-top: 6px">
            <label for="form-field-8">Tindakan / Prosedur <span style="color:red">* </span></label>
            <textarea type="text" class="form-control" name="pl_tindakan_prosedur" id="pl_tindakan_prosedur" placeholder="" style="height: 50px !important"><?php echo isset($riwayat->tindakan_prosedur)?$this->master->br2nl($riwayat->tindakan_prosedur):''?></textarea>
        </div>

        <div style="margin-top: 6px">
            <label for="form-field-8">Alergi (Reaksi Obat)</label>
            <textarea name="pl_alergi_obat" id="pl_alergi_obat" class="form-control" style="height: 50px !important"><?php echo isset($riwayat->alergi_obat)?$this->master->br2nl($riwayat->alergi_obat):''?></textarea>
        </div>

        <div style="margin-top: 6px">
            <label for="form-field-8">Diet</label>
            <textarea name="pl_diet" id="pl_diet" class="form-control" style="height: 50px !important"><?php echo isset($riwayat->diet)?$this->master->br2nl($riwayat->diet):''?></textarea>
        </div>

        <div style="margin-top: 6px">
            <label for="form-field-8">Obat yang diberikan</label>
              <textarea name="obat_diberikan" id="obat_diberikan" class="form-control" style="height: 70px !important" placeholder="" ><?php echo isset($riwayat->obat_diberikan)?$this->master->br2nl($riwayat->obat_diberikan):''?></textarea>  
        </div>
        <br>
        <p><b>TGL KONTROL </b></p>
        <div class="form-group">
            <label class="control-label col-sm-2" for="">*Tgl Kembali</label>
            <div class="col-md-3">
                <div class="input-group">
                    <input name="tgl_kontrol_kembali" id="tgl_kontrol_kembali" data-date-format="yyyy-mm-dd" placeholder="" class="form-control date-picker" type="text" value="<?php echo isset($riwayat->tgl_kontrol_kembali)?$this->master->br2nl($riwayat->tgl_kontrol_kembali):''?>">
                    <span class="input-group-addon">
                    <i class="ace-icon fa fa-calendar"></i>
                    </span>
                </div>
            </div>
        </div>


        <br>
        <p><b>KONDISI WAKTU KELUAR </b></p>

        <input type="hidden" value="Atas Persetujuan Dokter" name="cara_keluar" id="cara_keluar">

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Pasca Pulang</label>
            <div class="col-sm-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'pasca_pulang')), ($riwayat->pasca_pulang)?$riwayat->pasca_pulang:'' , 'pasca_pulang', 'pasca_pulang', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="form-group" id="kode_kematian" style="display:none">
            <label class="control-label col-sm-2" for="">Kode Kematian</label>
            <div class="col-sm-5">
                <input type="text" name="pl_kode_kematian" class="form-control">
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-4" style="margin-left:6px">
                <?php if($riwayat->status_pulang == 0) :?>
                    <button type="button" class="btn btn-xs btn-primary" id="btn_pasien_pulang" name="btn_pasien_pulang" value="simpan_dan_pulangkan"><i class="fa fa-save"></i> Simpan Resume dan Pulangkan Pasien </button>
                <?php else: ?>
                    <a href="#" class="btn btn-xs btn-success" id="btn_pasien_pulang"><i class="fa fa-save"></i> Update Resume Pasien  </a>
                    <!-- <a href="#" class="btn btn-xs btn-danger" id="btn_export_resume_pdf"><i class="fa fa-file-pdf-o"></i> Export PDF </a> -->
                <?php endif;?>
            </div>
        </div>

    </div>

    <!-- <div class="col-sm-4 no-padding">
        <center><span style="font-size: 14px"><b>CATATAN PERKEMBANGAN PASIEN TERINTEGRASI (CPPT)</b></span></center>
        <hr>
        <div style="height: 750px;overflow: scroll;">
        <table class="table" id="table-riwayat-cppt" style="padding: 5px">
            
        <tbody>
                <?php 
                    if(count($cppt) > 0 ) : 
                        foreach($cppt as $row_cppt) :
                            if($row_cppt->jenis_form == null) :
                ?>
                <tr>
                    <td style="padding: 5px">
                        <table class="table table-bordered" style="width: 100%">
                            <tr><td width="20%">Tgl/Jam</td><td width="80%"> : <?php echo $this->tanggal->formatDateTime($row_cppt->cppt_tgl_jam)?></td></tr>
                            <tr><td>PPA</td><td> : <?php echo $row_cppt->cppt_nama_ppa?> (<?php echo strtoupper($row_cppt->cppt_ppa)?>)</td></tr>
                            <tr><td>Verifikasi DPJP</td><td> : <?php echo ($row_cppt->is_verified == 1) ? '<label class="label label-success">Sudah diverifikasi</label> '.$row_cppt->verified_by.' - '.$row_cppt->verified_date.'' : '<label class="label label-danger">Belum diverifikasi</label>';?></td></tr>
                        </table>
                        <br>
                        <b>S <i>(Subjective)</i> : </b><br>
                        <?php echo nl2br($row_cppt->cppt_subjective)?><br>
                        <br>
                        <b>O <i>(Objective)</i> : </b><br>
                        <?php echo nl2br($row_cppt->cppt_objective)?><br>
                        <br>
                        <b>A <i>(Assesment)</i> : </b><br>
                        <?php echo nl2br($row_cppt->cppt_assesment)?><br>
                        <br>
                        <b>P <i>(Plan)</i> : </b><br>
                        <?php echo nl2br($row_cppt->cppt_plan)?><br>
                        <hr style="width: 100%">
                    </td>
                </tr>
                <?php else : ?>
                <tr>
                    <td style="padding: 5px">
                        <table class="table table-bordered" style="width: 100%">
                            <tr><td width="20%">Tgl/Jam</td><td width="80%"> : <?php echo $this->tanggal->formatDateTime($row_cppt->cppt_tgl_jam)?></td></tr>
                            <tr><td>PPA</td><td> : <?php echo $row_cppt->cppt_nama_ppa?> (<?php echo strtoupper($row_cppt->cppt_ppa)?>)</td></tr>
                            <tr><td>Verifikasi DPJP</td><td> : <?php echo ($row_cppt->is_verified == 1) ? '<label class="label label-success">Sudah diverifikasi</label> '.$row_cppt->verified_by.' - '.$row_cppt->verified_date.'' : '<label class="label label-danger">Belum diverifikasi</label>';?></td></tr>
                        </table>
                        <br>
                        <?php echo $row_cppt->catatan_pengkajian?>
                    </td>
                </tr>
                <?php endif; endforeach; else: echo '<span style="padding-top: 20px; color: red; font-weight: bold">Tidak ada ditemukan</span>'; endif; ?>
            </tbody>
        </table>
        </div>
    </div> -->

</div>





