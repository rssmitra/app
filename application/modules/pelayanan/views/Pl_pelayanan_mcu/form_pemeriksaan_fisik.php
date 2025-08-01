<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-multiselect.css" />
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

    $('#btn_pemeriksaan_fisik').click(function (e) {   
      e.preventDefault();

      $.ajax({
          url: "pelayanan/Pl_pelayanan_mcu/process_add_pemeriksaan_fisik",
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
              $("#page-area-content").load("pelayanan/Pl_pelayanan_mcu/form/"+response.id_pl_tc_poli+"/"+response.no_kunjungan+"")
                      
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

    <br><p><b><i class="fa fa-edit"></i> Pemeriksaan Fisik Umum </b></p>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">TB</label>
        <div class="col-sm-2">    
          <div class="input-group">

          <input type="text" class="form-control" name="fisik_tinggi_badan" id="fisik_tinggi_badan" value="<?php echo isset($tinggi_badan)?$tinggi_badan:''?>" >

            <span class="input-group-addon">

              cm

            </span>

          </div>
        </div>

        <label class="control-label col-sm-1" for="" style="margin-left: 5%">TD</label>
        <div class="col-sm-2">    
          <div class="input-group">

          <input type="text" class="form-control" name="fisik_tekanan_darah" id="fisik_tekanan_darah" value="<?php echo isset($tekanan_darah)?$tekanan_darah:''?>"  >

            <span class="input-group-addon">

              mmHg

            </span>

          </div>
        </div>

        <label class="control-label col-sm-1" for="" style="margin-left: 5%">BB</label>
        <div class="col-sm-2">    
          <div class="input-group">

          <input type="text" class="form-control" name="fisik_berat_badan" id="fisik_berat_badan" value="<?php echo isset($berat_badan)?$berat_badan:''?>"  >

            <span class="input-group-addon">

              Kg

            </span>

          </div>
        </div>
        

    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Nadi</label>
        <div class="col-sm-2">    
          <div class="input-group">
          <input type="text" class="form-control" name="fisik_nadi" id="fisik_nadi" value="<?php echo isset($nadi)?$nadi:''?>"  >
            <span class="input-group-addon">
              x/menit
            </span>
          </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Pernafasan</label>
        <div class="col-sm-2">    
          <div class="input-group">
          <input type="text" class="form-control" name="fisik_pernafasan" id="fisik_pernafasan" value="<?php echo isset($pernafasan)?$pernafasan:''?>"  >
            <span class="input-group-addon">
              x/menit
            </span>
          </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Suhu</label>
        <div class="col-sm-2">    
          <div class="input-group">
          <input type="text" class="form-control" name="fisik_suhu_tubuh" id="fisik_suhu_tubuh" value="<?php echo isset($suhu_tubuh)?$suhu_tubuh:''?>"  >
            <span class="input-group-addon">
            &#8451;
            </span>
          </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Status Gizi</label>
        <div class="col-sm-3">    
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'status_gizi')), isset($status_gizi)?$status_gizi:'', 'fisik_status_gizi', 'fisik_status_gizi', 'form-control', '', '') ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Kesadaran</label>   
        <div class="col-sm-3">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'kesadaran_pasien')), isset($kesadaran)?$kesadaran:'', 'fisik_kesadaran', 'fisik_kesadaran', 'form-control', '', '') ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Keadaan Umum</label>
        <div class="col-sm-2">    
          <div class="input-group">
          <input type="text" class="form-control" name="fisik_keadaan_umum" id="fisik_keadaan_umum" value="<?php echo isset($keadaan_umum)?$keadaan_umum:''?>"  >
          </div>
        </div>
    </div>

    <div class="form-group">
                      
      <label class="control-label col-sm-3">BMI (Index Masa Tubuh)</label>
      
      <div class="col-md-2">
        
        <input type="text" class="form-control" name="fisik_bmi" id="fisik_bmi" value="<?php echo isset($bmi)?$bmi:''?>"  >
      
      </div>
    
    
    </div>




    <br><p><b><i class="fa fa-edit"></i> Mata </b></p>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Konjungtiva</label>
      
      <div class="col-md-3">
        
        <input type="text" class="form-control" name="fisik_mata_konjungtiva" id="fisik_mata_konjungtiva" value="<?php echo isset($pemeriksaan_fisik)?$pemeriksaan_fisik->mata->konjungtiva:''?>"  >
      
      </div>

      <label class="control-label col-sm-2">Sclera</label>
      
      <div class="col-md-3">
        
        <input type="text" class="form-control" name="fisik_mata_sclera" id="fisik_mata_sclera" value="<?php echo isset($pemeriksaan_fisik)?$pemeriksaan_fisik->mata->sclera:''?>"  >
      
      </div>
    
    
    </div>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Reflek Cahaya</label>
      
      <div class="col-md-3">
        
        <input type="text" class="form-control" name="fisik_mata_reflek_cahaya" id="fisik_mata_reflek_cahaya" value="<?php echo isset($pemeriksaan_fisik)?$pemeriksaan_fisik->mata->reflek_cahaya:''?>"  >
      
      </div>

      <label class="control-label col-sm-2">Penglihatan/Visus</label>
      
      <div class="col-md-3">
        
        <input type="text" class="form-control" name="fisik_mata_visus" id="fisik_mata_visus" value="<?php echo isset($pemeriksaan_fisik)?$pemeriksaan_fisik->mata->penglihatan_atau_visus:''?>"  >
      
      </div>
    
    
    </div>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Kacamata</label>
      
      <div class="col-md-3">
        
        <input type="text" class="form-control" name="fisik_mata_kacamata" id="fisik_mata_kacamata" value="<?php echo isset($pemeriksaan_fisik)?$pemeriksaan_fisik->mata->kacamata:''?>"  >
      
      </div>
      <label class="control-label col-sm-2" for="">Buta Warna</label>   
      <div class="col-sm-2">
        <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'buta_warna')), isset($pemeriksaan_fisik)?$pemeriksaan_fisik->buta_warna:'', 'fisik_buta_warna', 'fisik_buta_warna', 'form-control', '', '') ?>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-2">Keterangan</label>
      <div class="col-md-9">
        <textarea name="keterangan_mata" id="keterangan_mata" class="form-control" style="height:80px !important;"><?php echo isset($pemeriksaan_fisik->mata->keterangan)?$pemeriksaan_fisik->mata->keterangan:''?></textarea>
      </div>
    </div>


    <br><p><b><i class="fa fa-edit"></i> THT </b></p>

    <div class="form-group">
                      
        <label class="control-label col-sm-2">Telinga</label>
        
        <div class="col-md-5">
          
          <input type="text" class="form-control" name="fisik_tht_telinga" id="fisik_tht_telinga" value="<?php echo isset($pemeriksaan_fisik)?$pemeriksaan_fisik->tht->telinga:''?>"  >
        
        </div>
       
      
    </div>

    <div class="form-group">
                      
        <label class="control-label col-sm-2">Hidung</label>
        
        <div class="col-sm-3">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'pemeriksaan_fisik_hidung')), isset($pemeriksaan_fisik)?$pemeriksaan_fisik->tht->hidung:'', 'fisik_tht_hidung', 'fisik_tht_hidung', 'form-control', '', '') ?>
        </div>
            
    </div>

    <div class="form-group">
                      
        <label class="control-label col-sm-2">Tenggorok/tonsil</label>
        
        <div class="col-md-6">
          
          <input type="text" class="form-control" name="fisik_tht_tenggorokan" id="fisik_tht_tenggorokan" value="<?php echo isset($pemeriksaan_fisik)?$pemeriksaan_fisik->tht->tenggorokan:''?>"  >
        
        </div>
       
    </div>

    <div class="form-group">
      <label class="control-label col-sm-2">Keterangan</label>
      <div class="col-md-9">
        <textarea name="keterangan_tht" id="keterangan_tht" class="form-control" style="height:80px !important;"><?php echo isset($pemeriksaan_fisik->tht->keterangan)?$pemeriksaan_fisik->tht->keterangan:''?></textarea>
      </div>
    </div>

    <br><p><b><i class="fa fa-edit"></i> Mulut / Gigi </b></p>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Gigi Atas Kanan</label>
        <div class="col-md-2">    
          <select id="gigi_upright" class="form-control" name="gigi_upright">
              <?php 
              foreach($gigi as $row){
                $selected = ($pemeriksaan_fisik->mulut_gigi->gigi_kanan_atas == $row->value)?'selected':'';
                echo '<option value="'.$row->value.'" '.$selected.'>'.$row->label.'</option>';
              }
            ?>
          </select>
        </div>
        <label class="control-label col-sm-2" for="">Gigi ke-</label>
        <div class="col-md-2">    
          <input type="text" class="form-control" name="gigi_upright_ke" value="<?php echo ($pemeriksaan_fisik->mulut_gigi->gigi_kanan_atas_ke)?$pemeriksaan_fisik->mulut_gigi->gigi_kanan_atas_ke:''?>">
        </div>
        <label class="control-label col-sm-1" for="">Catatan</label>
        <div class="col-md-3">    
          <input type="text" class="form-control" name="gigi_upright_note" value="<?php echo ($pemeriksaan_fisik->mulut_gigi->catatan_gigi_kanan_atas)?$pemeriksaan_fisik->mulut_gigi->catatan_gigi_kanan_atas:''?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Gigi Atas Kiri</label>
        <div class="col-md-2">    
          <select id="gigi_upleft" class="form-control" name="gigi_upleft">
              <?php 
              foreach($gigi as $row){
                $selected = ($pemeriksaan_fisik->mulut_gigi->gigi_kiri_atas == $row->value)?'selected':'';
                echo '<option value="'.$row->value.'" '.$selected.'>'.$row->label.'</option>';
              }
            ?>
          </select>
        </div>
        <label class="control-label col-sm-2" for="">Gigi ke-</label>
        <div class="col-md-2">    
          <input type="text" class="form-control" name="gigi_upleft_ke" value="<?php echo ($pemeriksaan_fisik->mulut_gigi->gigi_kiri_atas_ke)?$pemeriksaan_fisik->mulut_gigi->gigi_kiri_atas_ke:''?>">
        </div>
        <label class="control-label col-sm-1" for="">Catatan</label>
        <div class="col-md-3">    
          <input type="text" class="form-control" name="gigi_upleft_note" value="<?php echo ($pemeriksaan_fisik->mulut_gigi->catatan_gigi_kiri_atas)?$pemeriksaan_fisik->mulut_gigi->catatan_gigi_kiri_atas:''?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Gigi Bawah Kanan</label>
        <div class="col-md-2">    
          <select id="gigi_downright" class="form-control" name="gigi_downright">
              <?php 
              foreach($gigi as $row){
                $selected = ($pemeriksaan_fisik->mulut_gigi->gigi_kanan_bawah == $row->value)?'selected':'';
                echo '<option value="'.$row->value.'" '.$selected.'>'.$row->label.'</option>';
              }
            ?>
          </select>
        </div>
        <label class="control-label col-sm-2" for="">Gigi ke-</label>
        <div class="col-md-2">    
          <input type="text" class="form-control" name="gigi_downright_ke" value="<?php echo ($pemeriksaan_fisik->mulut_gigi->gigi_kanan_bawah_ke)?$pemeriksaan_fisik->mulut_gigi->gigi_kanan_bawah_ke:''?>">
        </div>
        <label class="control-label col-sm-1" for="">Catatan</label>
        <div class="col-md-3">    
          <input type="text" class="form-control" name="gigi_downright_note" value="<?php echo ($pemeriksaan_fisik->mulut_gigi->catatan_gigi_kanan_bawah)?$pemeriksaan_fisik->mulut_gigi->catatan_gigi_kanan_bawah:''?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Gigi Bawah Kiri</label>
        <div class="col-md-2">    
          <select id="gigi_downleft" class="form-control" name="gigi_downleft">
              <?php 
              foreach($gigi as $row){
                $selected = ($pemeriksaan_fisik->mulut_gigi->gigi_kiri_bawah == $row->value)?'selected':'';
                echo '<option value="'.$row->value.'" '.$selected.'>'.$row->label.'</option>';
              }
            ?>
          </select>
        </div>
        <label class="control-label col-sm-2" for="">Gigi ke-</label>
        <div class="col-md-2">    
          <input type="text" class="form-control" name="gigi_downleft_ke" value="<?php echo ($pemeriksaan_fisik->mulut_gigi->gigi_kiri_bawah_ke)?$pemeriksaan_fisik->mulut_gigi->gigi_kiri_bawah_ke:''?>">
        </div>
        <label class="control-label col-sm-1" for="">Catatan</label>
        <div class="col-md-3">    
          <input type="text" class="form-control" name="gigi_downleft_note" value="<?php echo ($pemeriksaan_fisik->mulut_gigi->catatan_gigi_kiri_bawah)?$pemeriksaan_fisik->mulut_gigi->catatan_gigi_kiri_bawah:''?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Lidah</label>   
        <div class="col-md-4">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'pemeriksaan_fisik_lidah')), isset($pemeriksaan_fisik)?$pemeriksaan_fisik->mulut_gigi->lidah:'', 'fisik_lidah', 'fisik_lidah', 'form-control', '', '') ?>
        </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-2">Keterangan</label>
      <div class="col-md-9">
        <textarea name="keterangan_mulut" id="keterangan_mulut" class="form-control" style="height:80px !important;"><?php echo isset($pemeriksaan_fisik->mulut_gigi->keterangan)?$pemeriksaan_fisik->mulut_gigi->keterangan:''?></textarea>
      </div>
    </div>

    <br><p><b><i class="fa fa-edit"></i> Leher </b></p>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">JVP</label>
        <div class="col-sm-2">    
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'pemeriksaan_JVP')), isset($pemeriksaan_fisik)?$pemeriksaan_fisik->leher->jvp:'', 'fisik_leher_JVP', 'fisik_leher_JVP', 'form-control', '', '') ?>
        </div>

        <label class="control-label col-sm-1" for="">Tiroid</label>   
        <div class="col-sm-2">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'pemeriksaan_fisik')), isset($pemeriksaan_fisik)?$pemeriksaan_fisik->leher->tiroid:'', 'fisik_leher_tiroid', 'fisik_leher_tiroid', 'form-control', '', '') ?>
        </div>

        <label class="control-label col-sm-2" for="">Kel. Getah Bening</label>   
        <div class="col-sm-3">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'pemeriksaan_fisik')), isset($pemeriksaan_fisik)?$pemeriksaan_fisik->leher->kel_getah_bening:'', 'fisik_leher_getah_bening', 'fisik_leher_getah_bening', 'form-control', '', '') ?>
        </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-2">Keterangan</label>
      <div class="col-md-9">
        <textarea name="keterangan_leher" id="keterangan_leher" class="form-control" style="height:80px !important;"><?php echo isset($pemeriksaan_fisik->leher->keterangan)?$pemeriksaan_fisik->leher->keterangan:''?></textarea>
      </div>
    </div>

    <br><p><b><i class="fa fa-edit"></i> Thorax </b></p>

    <div class="form-group">
                      
        <label class="control-label col-sm-2">Paru Kanan</label>
        
        <div class="col-md-5">
          
          <input type="text" class="form-control" name="fisik_thorax_paru_kanan" id="fisik_thorax_paru_kanan" value="<?php echo isset($pemeriksaan_fisik)?$pemeriksaan_fisik->thorax->paru_kanan:''?>"  >
        
        </div>
    </div>

    <div class="form-group">
                      
        <label class="control-label col-sm-2">Paru Kiri</label>
        
        <div class="col-md-5">
          
          <input type="text" class="form-control" name="fisik_thorax_paru_kiri" id="fisik_thorax_paru_kiri" value="<?php echo isset($pemeriksaan_fisik)?$pemeriksaan_fisik->thorax->paru_kiri:''?>"  >
        
        </div>
       
    </div>

    <div class="form-group">
      <label class="control-label col-sm-2">Keterangan</label>
      <div class="col-md-9">
        <textarea name="keterangan_thorax" id="keterangan_thorax" class="form-control" style="height:80px !important;"><?php echo isset($pemeriksaan_fisik->thorax->keterangan)?$pemeriksaan_fisik->thorax->keterangan:''?></textarea>
      </div>
    </div>

    <br><p><b><i class="fa fa-edit"></i> Jantung </b></p>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Besar</label>
        <div class="col-sm-3">    
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'pemeriksaan_jantung_besar')), isset($pemeriksaan_fisik)?$pemeriksaan_fisik->jantung->besar:'', 'fisik_jantung_besar', 'fisik_jantung_besar', 'form-control', '', '') ?>
        </div>

        <label class="control-label col-sm-2" for="">Bunyi S1-S2</label>   
        <div class="col-sm-2">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'pemeriksaan_jantung_bunyi_S1_S2')), isset($pemeriksaan_fisik)?$pemeriksaan_fisik->jantung->bunyi_S1_strip_S2:'', 'fisik_jantung_bunyi', 'fisik_jantung_bunyi', 'form-control', '', '') ?>
        </div>

        <label class="control-label col-sm-1" for="">Bising</label>   
        <div class="col-sm-2">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'pemeriksaan_jantung_bising')), isset($pemeriksaan_fisik)?$pemeriksaan_fisik->jantung->bising:'', 'fisik_jantung_bising', 'fisik_jantung_bising', 'form-control', '', '') ?>
        </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-2">Keterangan</label>
      <div class="col-md-9">
        <textarea name="keterangan_jantung" id="keterangan_jantung" class="form-control" style="height:80px !important;"><?php echo isset($pemeriksaan_fisik->jantung->keterangan)?$pemeriksaan_fisik->jantung->keterangan:''?></textarea>
      </div>
    </div>

    <br><p><b><i class="fa fa-edit"></i> Abdomen </b></p>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Hati/Limpa</label>
        <div class="col-sm-3">    
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'pemeriksaan_fisik')), isset($pemeriksaan_fisik)?$pemeriksaan_fisik->abdomen->hati_atau_limpa:'', 'fisik_abdomen_limpa', 'fisik_abdomen_limpa', 'form-control', '', '') ?>
        </div>

        <label class="control-label col-sm-1" for="">Tumor</label>   
        <div class="col-sm-3">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'pemeriksaan_fisik')), isset($pemeriksaan_fisik)?$pemeriksaan_fisik->abdomen->tumor:'', 'fisik_abdomen_tumor', 'fisik_abdomen_tumor', 'form-control', '', '') ?>
        </div>

    </div>

    <div class="form-group">

      <label class="control-label col-sm-2" for="Province">Nyeri Tekan</label>
    
      <div class="col-md-9">

        <div class="radio">

            <label>

              <input name="fisik_abdomen_nyeri_tekan" type="radio" class="ace" value="Negatif" <?php echo isset($pemeriksaan_fisik)?($pemeriksaan_fisik->abdomen->nyeri_tekan=='Negatif')?'checked="checked"':'':'checked="checked"' ?>/>

              <span class="lbl"> Negatif</span>

            </label>

            <label>

              <input name="fisik_abdomen_nyeri_tekan" type="radio" class="ace" value="Positif" <?php echo isset($pemeriksaan_fisik)?($pemeriksaan_fisik->abdomen->nyeri_tekan=='Positif')?'checked="checked"':'':'' ?>/>

              <span class="lbl"> Positif</span>

            </label>

        </div>

      </div>

    </div>

    <div class="form-group">

      <label class="control-label col-sm-2" for="Province">Extremitas</label>
    
      <div class="col-md-8">

        <div class="radio">

            <label>

              <input name="fisik_abdomen_extremitas" type="radio" class="ace" value="Dalam Batas Normal" <?php echo isset($pemeriksaan_fisik)?($pemeriksaan_fisik->abdomen->extremitas=='Dalam Batas Normal')?'checked="checked"':'':'checked="checked"' ?>/>

              <span class="lbl"> Dalam Batas Normal</span>

            </label>

            <label>

              <input name="fisik_abdomen_extremitas" type="radio" class="ace" value="Tak Normal" <?php echo isset($pemeriksaan_fisik)?($pemeriksaan_fisik->abdomen->extremitas=='Tak Normal')?'checked="checked"':'':'' ?>/>

              <span class="lbl"> Tak Normal</span>

            </label>

        </div>

      </div>
    
    </div>

    <div class="form-group">

      <label class="control-label col-sm-2">Neurologis</label>
        
      <div class="col-md-9">
        
        <!-- <input type="text" class="form-control" name="fisik_abdomen_neurologis" id="fisik_abdomen_neurologis" value="<?php echo isset($pemeriksaan_fisik)?$pemeriksaan_fisik->abdomen->neurologis:''?>"  > -->

        <textarea name="fisik_abdomen_neurologis" id="fisik_abdomen_neurologis" class="form-control" style="height:80px !important;"><?php echo isset($pemeriksaan_fisik)?$pemeriksaan_fisik->abdomen->neurologis:''?></textarea>
      
      </div>

    </div>

    <div class="form-group">

      <label class="control-label col-sm-2" for="Province">Kulit/Turgor</label>

      <div class="col-md-8">

        <div class="radio">

            <label>

              <input name="fisik_abdomen_kulit" type="radio" class="ace" value="Dalam Batas Normal" <?php echo isset($pemeriksaan_fisik)?($pemeriksaan_fisik->abdomen->kulit_atau_turgor=='Dalam Batas Normal')?'checked="checked"':'':'checked="checked"' ?>/>

              <span class="lbl"> Dalam Batas Normal</span>

            </label>

            <label>

              <input name="fisik_abdomen_kulit" type="radio" class="ace" value="Tak Normal" <?php echo isset($pemeriksaan_fisik)?($pemeriksaan_fisik->abdomen->kulit_atau_turgor=='Tak Normal')?'checked="checked"':'':'' ?>/>

              <span class="lbl"> Tak Normal</span>

            </label>

        </div>

      </div>

    </div>

    <div class="form-group">

      <label class="control-label col-sm-2" for="">Kel. Getah Bening</label>   
      <div class="col-sm-3">
        <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'pemeriksaan_fisik')), isset($pemeriksaan_fisik)?$pemeriksaan_fisik->abdomen->kel_getah_bening:'', 'fisik_abdomen_getah_bening', 'fisik_abdomen_getah_bening', 'form-control', '', '') ?>
      </div>

    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Lain-lain</label>
        <div class="col-md-3">    
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'pemeriksaan_abdomen_lainnya')), isset($pemeriksaan_fisik)?$pemeriksaan_fisik->abdomen->lainnya:'', 'fisik_abdomen_lainnya', 'fisik_abdomen_lainnya', 'form-control', '', '') ?>
        </div>

    </div>
    
    <br><p><b><i class="fa fa-edit"></i> Anggota Gerak </b></p>

    <div class="form-group">
        <label class="control-label col-sm-3">Extremitas atas kanan</label>
        <div class="col-md-9">
          <input type="text" class="form-control" name="fisik_ex_atas_kanan" id="fisik_ex_atas_kanan" value="<?php echo isset($pemeriksaan_fisik->anggota_gerak)?$pemeriksaan_fisik->anggota_gerak->extremitas_atas_kanan:''?>"  >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3">Extremitas atas kiri</label>
        <div class="col-md-9">
          <input type="text" class="form-control" name="fisik_ex_atas_kiri" id="fisik_ex_atas_kiri" value="<?php echo isset($pemeriksaan_fisik->anggota_gerak)?$pemeriksaan_fisik->anggota_gerak->extremitas_atas_kiri:''?>"  >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3">Extremitas bawah kanan</label>
        <div class="col-md-9">
          <input type="text" class="form-control" name="fisik_ex_bawah_kanan" id="fisik_ex_bawah_kanan" value="<?php echo isset($pemeriksaan_fisik->anggota_gerak)?$pemeriksaan_fisik->anggota_gerak->extremitas_bawah_kanan:''?>"  >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3">Extremitas bawah kiri</label>
        <div class="col-md-9">
          <input type="text" class="form-control" name="fisik_ex_bawah_kiri" id="fisik_ex_bawah_kiri" value="<?php echo isset($pemeriksaan_fisik->anggota_gerak)?$pemeriksaan_fisik->anggota_gerak->extremitas_bawah_kiri:''?>"  >
        </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-3">Keterangan Lainnya</label>
      <div class="col-md-9">
        <textarea name="keterangan_anggota_gerak" id="keterangan_anggota_gerak" class="form-control" style="height:80px !important;"><?php echo isset($pemeriksaan_fisik->anggota_gerak->keterangan)?$pemeriksaan_fisik->anggota_gerak->keterangan:''?></textarea>
      </div>
    </div>

    <br><p><b><i class="fa fa-edit"></i> Genitalia </b></p>

    <div class="form-group">
        <label class="control-label col-sm-2">Vagina</label>
        <div class="col-md-10">
          <input type="text" class="form-control" name="fisik_vagina" id="fisik_vagina" value="<?php echo isset($pemeriksaan_fisik->genitalia)?$pemeriksaan_fisik->genitalia->vagina:''?>"  >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">Penis</label>
        <div class="col-md-10">
          <input type="text" class="form-control" name="fisik_penis" id="fisik_penis" value="<?php echo isset($pemeriksaan_fisik->genitalia)?$pemeriksaan_fisik->genitalia->penis:''?>"  >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">Anus</label>
        <div class="col-md-10">
          <input type="text" class="form-control" name="fisik_anus" id="fisik_anus" value="<?php echo isset($pemeriksaan_fisik->genitalia)?$pemeriksaan_fisik->genitalia->anus:''?>"  >
        </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2">Keterangan</label>
      <div class="col-md-9">
        <textarea name="keterangan_genitalia" id="keterangan_genitalia" class="form-control" style="height:80px !important;"><?php echo isset($pemeriksaan_fisik->genitalia->keterangan)?$pemeriksaan_fisik->genitalia->keterangan:''?></textarea>
      </div>
    </div>


        
    <div class="form-group">
      <label class="control-label col-sm-2" for="">&nbsp;</label>
      <div class="col-sm-3" style="margin-left:6px">
        <a href="#" class="btn btn-xs btn-primary" id="btn_pemeriksaan_fisik"><i class="fa fa-save"></i> Submit </a>
      </div>
    </div>

  </div><!--end data keracunan -->
     
</div>

<script type="text/javascript">
      jQuery(function($){
          
        //////////////////
        $('.multiselect').multiselect({
         enableFiltering: true,
         buttonClass: 'btn btn-white btn-primary',
         templates: {
          button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"></button>',
          ul: '<ul class="multiselect-container dropdown-menu"></ul>',
          filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
          filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default btn-white btn-grey multiselect-clear-filter" type="button"><i class="fa fa-times-circle red2"></i></button></span>',
          li: '<li><a href="javascript:void(0);"><label></label></a></li>',
          divider: '<li class="multiselect-item divider"></li>',
          liGroup: '<li class="multiselect-item group"><label class="multiselect-group"></label></li>'
         }
        });
        
        
        //in ajax mode, remove remaining elements before leaving page
        /*$(document).one('ajaxloadstart.page', function(e) {
          $('.multiselect').multiselect('destroy');
        });*/
      
      });
    </script>





