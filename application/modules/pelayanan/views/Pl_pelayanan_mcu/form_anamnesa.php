<script type="text/javascript">

jQuery(function($) {  

  $('.date-picker').datepicker({    

    autoclose: true,    

    todayHighlight: true    

  })  

  //show datepicker when clicking on the icon

  .next().on(ace.click_event, function(){    

    $(this).prev().focus();    

  });  

});

$(document).ready(function() {

    $('#btn_anamnesa').click(function (e) {   
      e.preventDefault();

      $.ajax({
          url: "pelayanan/Pl_pelayanan_mcu/process_add_anamnesa",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
        
            if(response.status==200) {
              // var date = '<?php echo date('m/d/Y')?>';
              $.achtung({message: response.message, timeout:5});
              // /*reset all field*/
              // $('#pl_tgl_pesan').val(date);
              $('#id_tc_pemeriksaan_fisik_mcu').val(response.id_tc_pemeriksaan_fisik_mcu);
              $("#page-area-content").load("pelayanan/Pl_pelayanan_mcu/form/"+response.id_pl_tc_poli+"/"+response.no_kunjungan+"");
                      
            }else{
              $.achtung({message: response.message, timeout:5}); 
            }
            
          }
      });

    });

   
});


</script>

<div class="row">

  <div class="col-md-12">

    <!-- <div class="form-group">
        <label class="control-label col-sm-2" for="">*Tanggal</label>
          <div class="col-md-3">
                
            <div class="input-group">
                
                <input name="tgl_laporan" id="tgl_laporan" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>">
                <span class="input-group-addon">
                  
                  <i class="ace-icon fa fa-calendar"></i>
                
                </span>
              </div>
          
          </div>
    </div> -->

    <p><b><i class="fa fa-edit"></i> Keluhan Utama Penyakit Sekarang </b></p>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Keluhan</label>
      
      <div class="col-md-10">
        
        <textarea name="keluhan_utama" id="keluhan_utama" cols="50" style="height:100px !important; width: 100%"><?php echo isset($anamnesa)?$anamnesa->keluhan_utama:'' ?></textarea>
      
      </div>
    
    
    </div>

    <br><p><b><i class="fa fa-edit"></i> Riwayat penyakit masa lampau </b></p>

    <div class="form-group">
  
        <label class="control-label col-sm-3" for="Province">Penyakit Hati</label>
  
        <div class="col-md-3">

          <div class="radio">

              <label>

                <input name="riwayat_penyakit_hati" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa->riwayat_penyakit_masa_lampau->penyakit_hati)?($anamnesa->riwayat_penyakit_masa_lampau->penyakit_hati=='Ada')?'checked="checked"':'':'' ?>/>

                <span class="lbl"> Ada</span>

              </label>

              <label>

                <input name="riwayat_penyakit_hati" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa->riwayat_penyakit_masa_lampau->penyakit_hati)?($anamnesa->riwayat_penyakit_masa_lampau->penyakit_hati=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?>/>

                <span class="lbl"> Tidak Ada</span>

              </label>

          </div>

        </div>

        <label class="control-label col-sm-2" for="Province">Kencing Manis</label>
  
        <div class="col-md-4">

          <div class="radio">

              <label>

                <input name="riwayat_kencing_manis" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_masa_lampau->sakit_kuning=='Ada')?'checked="checked"':'':'checked="checked"' ?>/>

                <span class="lbl"> Ada</span>

              </label>

              <label>

                <input name="riwayat_kencing_manis" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_masa_lampau->sakit_kuning=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?>/>

                <span class="lbl"> Tidak Ada</span>

              </label>

          </div>

        </div>

    </div>

    <div class="form-group">
  
      <label class="control-label col-sm-3" for="Province">Hipertensi</label>

      <div class="col-md-3">

          <div class="radio">

              <label>

                <input name="riwayat_hipertensi" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_masa_lampau->hipertensi=='Ada')?'checked="checked"':'':'' ?>/>

                <span class="lbl"> Ada</span>

              </label>

              <label>

                <input name="riwayat_hipertensi" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_masa_lampau->hipertensi=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?>/>

                <span class="lbl"> Tidak Ada</span>

              </label>

          </div>

        </div>
        
        <label class="control-label col-sm-2" for="Province">Kencing Batu</label>

        <div class="col-md-4">

          <div class="radio">

              <label>

                <input name="riwayat_kencing_batu" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_masa_lampau->kencing_batu=='Ada')?'checked="checked"':'':'' ?>/>

                <span class="lbl"> Ada</span>

              </label>

              <label>

                <input name="riwayat_kencing_batu" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_masa_lampau->kencing_batu=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?>/>

                <span class="lbl"> Tidak Ada</span>

              </label>

          </div>

        </div>

    </div>

    <div class="form-group">
  
      <label class="control-label col-sm-3" for="Province">Asma</label>

      <div class="col-md-3">

        <div class="radio">

            <label>

              <input name="riwayat_asma" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_masa_lampau->asma=='Ada')?'checked="checked"':'':'' ?>/>

              <span class="lbl"> Ada</span>

            </label>

            <label>

              <input name="riwayat_asma" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_masa_lampau->asma=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?>/>

              <span class="lbl"> Tidak Ada</span>

            </label>

        </div>

      </div>

      
      <label class="control-label col-sm-2" for="Province">Riwayat Operasi</label>

      <div class="col-md-4">

        <div class="radio">

            <label>

              <input name="riwayat_operasi" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_masa_lampau->operasi=='Ada')?'checked="checked"':'':'' ?> onchange="toggleJenisOperasi(this)"/>

              <span class="lbl"> Ada</span>

            </label>

            <label>

              <input name="riwayat_operasi" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_masa_lampau->operasi=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?> onchange="toggleJenisOperasi(this)"/>

              <span class="lbl"> Tidak Ada</span>

            </label>

        </div>

      </div>

      <script>
        function toggleJenisOperasi(el) {
          document.getElementById('jenis_operasi_wrap').style.display = (el.value === 'Ada') ? '' : 'none';
          if (el.value !== 'Ada') document.getElementById('jenis_operasi').value = '';
        }
        function toggleAlergiDetail(field, el) {
          var wrap = document.getElementById('alergi_' + field + '_detail_wrap');
          wrap.style.display = (el.value === 'Ada') ? '' : 'none';
          if (el.value !== 'Ada') document.getElementById('alergi_' + field + '_detail').value = '';
        }
      </script>

    </div>

    <div id="jenis_operasi_wrap" class="form-group" style="<?php echo (isset($anamnesa->riwayat_penyakit_masa_lampau->operasi) && $anamnesa->riwayat_penyakit_masa_lampau->operasi=='Ada') ? '' : 'display:none;' ?>">

      <label class="control-label col-sm-3" for="jenis_operasi">Jenis Operasi</label>

      <div class="col-md-9">
        <input name="jenis_operasi" type="text" class="form-control" id="jenis_operasi" value="<?php echo isset($anamnesa->riwayat_penyakit_masa_lampau->jenis_operasi) ? $anamnesa->riwayat_penyakit_masa_lampau->jenis_operasi : '' ?>"/>
      </div>

    </div>

    <div class="form-group">
      
      <label class="control-label col-sm-3" for="Province">Penyakit karena kecelakaan</label>

      <div class="col-md-3">

        <div class="radio">

            <label>

              <input name="riwayat_krn_kecelakaan" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_masa_lampau->penyakit_karena_kecelakaan=='Ada')?'checked="checked"':'':'' ?>/>

              <span class="lbl"> Ada</span>

            </label>

            <label>

              <input name="riwayat_krn_kecelakaan" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_masa_lampau->penyakit_karena_kecelakaan=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?>/>

              <span class="lbl"> Tidak Ada</span>

            </label>

        </div>

      </div>

      <label class="control-label col-sm-2" for="riwayat_lainnya">Lain-lain</label>

      <div class="col-md-4">

        <input name="riwayat_lainnya" type="text" class="form-control" id="riwayat_lainnya" value="<?php echo isset($anamnesa->riwayat_penyakit_masa_lampau->lainnya) ? $anamnesa->riwayat_penyakit_masa_lampau->lainnya : '' ?>"/>

      </div>


    </div>

    <br><p><b><i class="fa fa-edit"></i> Riwayat penyakit dalam keluarga </b></p>

    <div class="form-group">
  
        <label class="control-label col-sm-3" for="Province">Alergi</label>
  
        <div class="col-md-3">

          <div class="radio">

              <label>

                <input name="riwayat_keluarga_alergi" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_keluarga->alergi=='Ada')?'checked="checked"':'':'' ?>/>

                <span class="lbl"> Ada</span>

              </label>

              <label>

                <input name="riwayat_keluarga_alergi" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_keluarga->alergi=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?>/>

                <span class="lbl"> Tidak Ada</span>

              </label>

          </div>

        </div>

        <label class="control-label col-sm-2" for="Province">Kencing Manis</label>
  
        <div class="col-md-4">

          <div class="radio">

              <label>

                <input name="riwayat_keluarga_kencing_manis" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_keluarga->kencing_manis=='Ada')?'checked="checked"':'':'' ?>/>

                <span class="lbl"> Ada</span>

              </label>

              <label>

                <input name="riwayat_keluarga_kencing_manis" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_keluarga->kencing_manis=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?>/>

                <span class="lbl"> Tidak Ada</span>

              </label>

          </div>

        </div>

    </div>

    <div class="form-group">
  
      <label class="control-label col-sm-3" for="Province">Penyakit Darah</label>

      <div class="col-md-3">

          <div class="radio">

              <label>

                <input name="riwayat_keluarga_penyakit_darah" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_keluarga->penyakit_darah=='Ada')?'checked="checked"':'':'' ?>/>

                <span class="lbl"> Ada</span>

              </label>

              <label>

                <input name="riwayat_keluarga_penyakit_darah" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_keluarga->penyakit_darah=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?>/>

                <span class="lbl"> Tidak Ada</span>

              </label>

          </div>

        </div>
        
        <label class="control-label col-sm-2" for="Province">Penyakit Jiwa</label>

        <div class="col-md-4">

          <div class="radio">

              <label>

                <input name="riwayat_keluarga_penyakit_jiwa" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_keluarga->penyakit_jiwa=='Ada')?'checked="checked"':'':'' ?>/>

                <span class="lbl"> Ada</span>

              </label>

              <label>

                <input name="riwayat_keluarga_penyakit_jiwa" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_keluarga->penyakit_jiwa=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?>/>

                <span class="lbl"> Tidak Ada</span>

              </label>

          </div>

        </div>

    </div>

    <div class="form-group">
  
      <label class="control-label col-sm-3" for="Province">Hipertensi</label>

      <div class="col-md-3">

        <div class="radio">

            <label>

              <input name="riwayat_keluarga_hipertensi" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_keluarga->hipertensi=='Ada')?'checked="checked"':'':'' ?>/>

              <span class="lbl"> Ada</span>

            </label>

            <label>

              <input name="riwayat_keluarga_hipertensi" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_keluarga->hipertensi=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?>/>

              <span class="lbl"> Tidak Ada</span>

            </label>

        </div>

      </div>

      <label class="control-label col-sm-2" for="Province">Kencing Batu</label>

      <div class="col-md-4">

          <div class="radio">

              <label>

                <input name="riwayat_keluarga_kencing_batu" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_keluarga->kencing_batu=='Ada')?'checked="checked"':'':'' ?>/>

                <span class="lbl"> Ada</span>

              </label>

              <label>

                <input name="riwayat_keluarga_kencing_batu" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->riwayat_penyakit_keluarga->kencing_batu=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?>/>

                <span class="lbl"> Tidak Ada</span>

              </label>

          </div>

      </div>

    </div>

    <div class="form-group">
      
      <label class="control-label col-sm-3" for="riwayat_keluarga_lainnya">Lain-lain</label>

      <div class="col-md-9">

        <input name="riwayat_keluarga_lainnya" type="text" class="form-control" id="riwayat_keluarga_lainnya" value="<?php echo isset($anamnesa->riwayat_penyakit_keluarga->lainnya) ? $anamnesa->riwayat_penyakit_keluarga->lainnya : '' ?>"/>

      </div>


    </div>

    <br><p><b><i class="fa fa-edit"></i> Catatan khusus alergi </b></p>

    <div class="form-group">

        <label class="control-label col-sm-3" for="Province">Udara</label>

        <div class="col-md-3">

          <div class="radio">

              <label>

                <input name="alergi_udara" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->alergi->alergi_udara=='Ada')?'checked="checked"':'':'' ?> onchange="toggleAlergiDetail('udara', this)"/>

                <span class="lbl"> Ada</span>

              </label>

              <label>

                <input name="alergi_udara" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->alergi->alergi_udara=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?> onchange="toggleAlergiDetail('udara', this)"/>

                <span class="lbl"> Tidak Ada</span>

              </label>

          </div>

        </div>

        <div id="alergi_udara_detail_wrap" class="col-md-5" style="<?php echo (isset($anamnesa->alergi->alergi_udara) && $anamnesa->alergi->alergi_udara=='Ada') ? '' : 'display:none;' ?>">
          <input name="alergi_udara_detail" type="text" id="alergi_udara_detail" class="form-control" placeholder="Sebutkan jenis alergi udara..." value="<?php echo isset($anamnesa->alergi->alergi_udara_detail) ? $anamnesa->alergi->alergi_udara_detail : '' ?>"/>
        </div>

    </div>
    <div class="form-group">

        <label class="control-label col-sm-3" for="Province">Makanan</label>

        <div class="col-md-3">

          <div class="radio">

              <label>

                <input name="alergi_makanan" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->alergi->alergi_makanan=='Ada')?'checked="checked"':'':'' ?> onchange="toggleAlergiDetail('makanan', this)"/>

                <span class="lbl"> Ada</span>

              </label>

              <label>

                <input name="alergi_makanan" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->alergi->alergi_makanan=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?> onchange="toggleAlergiDetail('makanan', this)"/>

                <span class="lbl"> Tidak Ada</span>

              </label>

          </div>

        </div>

        <div id="alergi_makanan_detail_wrap" class="col-md-5" style="<?php echo (isset($anamnesa->alergi->alergi_makanan) && $anamnesa->alergi->alergi_makanan=='Ada') ? '' : 'display:none;' ?>">
          <input name="alergi_makanan_detail" type="text" id="alergi_makanan_detail" class="form-control" placeholder="Sebutkan jenis alergi makanan..." value="<?php echo isset($anamnesa->alergi->alergi_makanan_detail) ? $anamnesa->alergi->alergi_makanan_detail : '' ?>"/>
        </div>

    </div>

    <div class="form-group">

      <label class="control-label col-sm-3" for="Province">Obat</label>

      <div class="col-md-3">

          <div class="radio">

              <label>

                <input name="alergi_obat" type="radio" class="ace" value="Ada" <?php echo isset($anamnesa)?($anamnesa->alergi->alergi_obat=='Ada')?'checked="checked"':'':'' ?> onchange="toggleAlergiDetail('obat', this)"/>

                <span class="lbl"> Ada</span>

              </label>

              <label>

                <input name="alergi_obat" type="radio" class="ace" value="Tidak Ada" <?php echo isset($anamnesa)?($anamnesa->alergi->alergi_obat=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?> onchange="toggleAlergiDetail('obat', this)"/>

                <span class="lbl"> Tidak Ada</span>

              </label>

          </div>

        </div>

        <div id="alergi_obat_detail_wrap" class="col-md-5" style="<?php echo (isset($anamnesa->alergi->alergi_obat) && $anamnesa->alergi->alergi_obat=='Ada') ? '' : 'display:none;' ?>">
          <input name="alergi_obat_detail" type="text" id="alergi_obat_detail" class="form-control" placeholder="Sebutkan jenis alergi obat..." value="<?php echo isset($anamnesa->alergi->alergi_obat_detail) ? $anamnesa->alergi->alergi_obat_detail : '' ?>"/>
        </div>

    </div>

    <div class="form-group">
        
        <label class="control-label col-sm-3" for="alergi_lainnya">Lain-lain</label>

        <div class="col-md-9">

          <input name="alergi_lainnya" type="text" class="form-control" id="alergi_lainnya" value="<?php echo isset($anamnesa->alergi->alergi_lainnya) ? $anamnesa->alergi->alergi_lainnya : '' ?>"/>

        </div>

    </div>
        
    <div class="form-group">
      <label class="control-label col-sm-3" for="">&nbsp;</label>
      <div class="col-sm-3" style="margin-left:6px">
        <a href="#" class="btn btn-xs btn-primary" id="btn_anamnesa"><i class="fa fa-save"></i> Submit </a>
      </div>
    </div>

  </div><!--end data keracunan -->
     
</div>







