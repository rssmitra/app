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

    $('#btn_kesimpulan').click(function (e) {   
      e.preventDefault();

      $.ajax({
          url: "pelayanan/Pl_pelayanan_mcu/process_add_kesimpulan",
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

    <input type="hidden" class="form-control" name="kode_tc_hasilMcu" id="kode_tc_hasilMcu" value="<?php echo isset($kode_tc_hasilMcu)?$kode_tc_hasilMcu:0?>">

    <p><b><i class="fa fa-edit"></i> Kesimpulan </b></p>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Keluhan Saat Ini</label>
      
      <div class="col-md-3">
        
        <textarea name="kesimpulan_keluhan" id="kesimpulan_keluhan" cols="50" style="height:100px !important;" ><?php $anam=isset($anamnesa)?$anamnesa->keluhan_utama:''; echo isset($hasil)?$hasil->keluhan_saat_ini:$anam ?></textarea>
      
      </div>
    
    
    </div>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Riwayat Penyakit</label>
      
      <div class="col-md-3">
        
        <textarea name="kesimpulan_riwayat_penyakit" id="kesimpulan_riwayat_penyakit" cols="50" style="height:100px !important;" ><?php 
            $html = '';
            if(isset($anamnesa)){
              foreach ($anamnesa->riwayat_penyakit_masa_lampau as $key => $value) {
                $exp = str_replace('_', ' ', $key);
                // $penyakit1 = ucfirst($exp[1]);
                // $penyakit2 = isset($exp[2])?ucfirst($exp[2]):'';
                $riwayat = ucwords($exp);
                if($value=='Ada'){
                  $html .= $riwayat. PHP_EOL;
                }

              }
            }
            $riw = ($html=='')?'Tidak Ada':$html;
            echo isset($hasil)?$hasil->riwayat_penyakit:$riw; 
          ?>
        </textarea>
      
      </div>
    
    
    </div>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Riwayat Penyakit Keluarga</label>
      
      <div class="col-md-3">
        
        <textarea name="kesimpulan_riwayat_penyakit_keluarga" id="kesimpulan_riwayat_penyakit_keluarga" cols="50" style="height:100px !important;" ><?php 
            $html = '';
            if(isset($anamnesa)){
              foreach ($anamnesa->riwayat_penyakit_keluarga as $key => $value) {
                $exp = str_replace('_', ' ', $key);
                // $penyakit1 = ucfirst($exp[2]);
                // $penyakit2 = isset($exp[3])?ucfirst($exp[3]):'';
                $riwayat = ucwords($exp);
                if($value=='Ada'){
                  $html .= $riwayat. PHP_EOL;
                }

              }
            }
            $riw = ($html=='')?'Tidak Ada':$html;
            echo isset($hasil)?$hasil->riwayat_penyakit_keluarga:$riw;
          ?>
        </textarea>
      
      </div>
    
    
    </div>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Alergi</label>
      
      <div class="col-md-3">
        
        <textarea name="kesimpulan_alergi" id="kesimpulan_alergi" cols="50" style="height:100px !important;" ><?php 
            $html = '';
            if(isset($anamnesa)){
              foreach ($anamnesa->alergi as $key => $value) {
                $exp = str_replace('_', ' ', $key);
                $riwayat = ucwords($exp);
                if($value=='Ada'){
                  $html .= $riwayat. PHP_EOL;
                }

              }
            }
            $alergi = ($html=='')?'Tidak Ada':$html;
            echo isset($hasil)?$hasil->alergi:$alergi; 
          ?>
        </textarea>
      
      </div>
    
    
    </div> <br>

   
    <div class="form-group">
                      
      <label class="control-label col-sm-2">Status gizi</label>
      
      <div class="col-md-3">
        
        <input type="text" class="form-control" name="kesimpulan_status_gizi" id="kesimpulan_status_gizi" value="<?php $gizi=isset($status_gizi)?$status_gizi:''; echo isset($hasil)?$hasil->pemeriksaan_fisik->status_gizi:$gizi?>"  >
      
      </div>
    
    
    </div>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Gigi</label>
      
      <div class="col-md-3">
        
        <textarea name="kesimpulan_gigi" id="kesimpulan_gigi" cols="50" style="height:100px !important;" ><?php 
            $html = '';
            if(isset($pemeriksaan_fisik)){
              $gigi = json_decode($pemeriksaan_fisik->mulut_gigi->gigi);
              foreach ($gigi as $value) {
                $riwayat = ucfirst($value);
                $html .= $riwayat. PHP_EOL;
              }
            }
            $gg = ($html=='')?'Tidak Ada':$html;
            echo isset($hasil)?$hasil->pemeriksaan_fisik->gigi:$gg;
          ?>
        </textarea>
      
      </div>
    
    
    </div> <br>


    <div class="form-group">
                      
      <label class="control-label col-sm-2">Radiologi</label>
      
      <div class="col-md-3">
        
        <!-- <input type="text" class="form-control" name="kesimpulan_radiologi" id="kesimpulan_radiologi" value="<?php $rad=isset($pemeriksaan_radiologi)?$pemeriksaan_radiologi->hasil:''; echo isset($hasil)?$hasil->radiologi:$rad?>"  > -->
        <textarea name="kesimpulan_radiologi" id="kesimpulan_radiologi" cols="50" style="height:100px !important;" ><?php $rad=isset($pemeriksaan_radiologi)?$pemeriksaan_radiologi->hasil:''; echo isset($hasil)?$hasil->radiologi:$rad?></textarea>
      
      </div>
    
    
    </div>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">EKG</label>
      
      <div class="col-md-3">
        
        <input type="text" class="form-control" name="kesimpulan_ekg" id="kesimpulan_ekg" value="<?php $ekg = isset($pemeriksaan_ekg)?$pemeriksaan_ekg->kesan:'' ;echo isset($hasil)?$hasil->ekg:$ekg?>"  >
      
      </div>
    
    
    </div>         

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Laboratorium</label>
      
      <div class="col-md-5">            

        <div class="input-group">

          <input type="text" class="form-control" name="kesimpulan_laboratorium" id="kesimpulan_laboratorium" value="<?php $lab = isset($pemeriksaan_lab)?$pemeriksaan_lab:''; echo isset($hasil)?$hasil->laboratorium:$lab?>"  >

          <span class="input-group-btn">

            <?php if($status_isihasil==1): ?>
              <a href="#" class="btn btn-sm btn-info" onclick="show_modal('pelayanan/Pl_pelayanan_pm/form_isi_hasil/<?php echo isset($no_kunjungan)?$no_kunjungan:0 ?>/<?php echo isset($kode_bagian)?$kode_bagian:0 ?>/<?php echo isset($kode_penunjang)?$kode_penunjang:0 ?>?mr=<?php echo isset($no_mr)?$no_mr:0 ?>&is_mcu=1', '')">Lihat Hasil</a>
            <?php else: ?>
              <button class="btn btn-sm btn-danger" onclick="javascript:return false">Belum Isi Hasil</button>
            <?php endif ?>

          </span>

        </div>
        
      </div> 
    
    
    </div>


    <div class="form-group">
                      
      <label class="control-label col-sm-2">Buta Warna</label>
      
      <div class="col-md-3">
        
        <input type="text" class="form-control" name="kesimpulan_buta_warna" id="kesimpulan_buta_warna" value="<?php $bw = isset($pemeriksaan_fisik)?$pemeriksaan_fisik->buta_warna:'';echo isset($hasil)?$hasil->buta_warna:$bw?>"  >
      
      </div>
    
    
    </div>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Audiometri</label>
      
      <div class="col-md-3">
        
        <input type="text" class="form-control" name="kesimpulan_audiometri" id="kesimpulan_audiometri" value="<?php echo isset($hasil)?$hasil->audiometri:''?>"  >
      
      </div>
    
    
    </div>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Treadmill</label>
      
      <div class="col-md-3">
        
        <!-- <input type="text" class="form-control" name="kesimpulan_treadmill" id="kesimpulan_treadmill" value="<?php echo isset($hasil)?$hasil->treadmill:''?>"  > -->

        <textarea name="kesimpulan_treadmill" id="kesimpulan_treadmill" cols="50" style="height:100px !important;" ><?php echo isset($hasil)?$hasil->treadmill:''?></textarea>
      
      </div>
    
    
    </div><br>


    <div class="form-group">
        <label class="control-label col-sm-2" for=""><b>KESAN</b></label>
        <div class="col-sm-4">    
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'kesan_mcu')), isset($kesimpulan)?$kesimpulan:'', 'kesimpulan_kesan', 'kesimpulan_kesan', 'form-control', '', '') ?>
        </div>

       
    </div>

    <br><p><b><i class="fa fa-edit"></i> Anjuran / Saran </b></p>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Saran</label>
      
      <div class="col-md-3">
        
        <textarea name="kesimpulan_saran" id="kesimpulan_saran" cols="50" style="height:100px !important;"><?php echo isset($kesan)?$kesan:'';?>
        </textarea>
      
      </div>
    
    
    </div> <br>
        
    <div class="form-group">
      <label class="control-label col-sm-2" for="">&nbsp;</label>
      <div class="col-sm-3" style="margin-left:6px">
        <a href="#" class="btn btn-xs btn-primary" id="btn_kesimpulan"><i class="fa fa-save"></i> Submit </a>
      </div>
    </div>

  </div><!--end data keracunan -->
     
</div>






