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

    $('#btn_radiologi').click(function (e) {   
      e.preventDefault();

      $.ajax({
          url: "pelayanan/Pl_pelayanan_mcu/process_add_radiologi",
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

    <p><b><i class="fa fa-edit"></i> Pemeriksaan Radiologi </b></p>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Thorax Foto</label>
      
      <div class="col-md-3">
        
        <textarea name="hasil_rad" id="hasil_rad" cols="50" style="height:100px !important;"><?php echo isset($pemeriksaan_radiologi)?$pemeriksaan_radiologi->hasil:'' ?></textarea>
      
      </div>
    
    
    </div>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Kesan</label>
      
      <div class="col-md-3">
        
        <textarea name="kesan_rad" id="kesan_rad" cols="50" style="height:100px !important;"><?php echo isset($pemeriksaan_radiologi)?$pemeriksaan_radiologi->kesan:'' ?></textarea>
      
      </div>
    
    
    </div>

        
    <div class="form-group">
      <label class="control-label col-sm-2" for="">&nbsp;</label>
      <div class="col-sm-3" style="margin-left:6px">
        <?php if($status_isihasil==1): ?>
          <a href="#" class="btn btn-xs btn-info" onclick="show_modal('pelayanan/Pl_pelayanan_pm/form_isi_hasil/<?php echo isset($no_kunjungan)?$no_kunjungan:0 ?>/<?php echo isset($kode_bagian)?$kode_bagian:0 ?>/<?php echo isset($kode_penunjang)?$kode_penunjang:0 ?>?mr=<?php echo isset($no_mr)?$no_mr:0 ?>&is_mcu=1', '')">Lihat Hasil</a>
        <?php else: ?>
          <a href="#" class="btn btn-xs btn-danger" onclick="javascript:return false"><i class="fa fa-times-circle"></i> Belum Isi Hasil</a>
        <?php endif ?>
      </div>
    </div>

    <p><b><i class="fa fa-edit"></i> Pemeriksaan EKG </b></p>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">Irama</label>
        <div class="col-sm-2">    
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'pemeriksaan_irama_ekg')), isset($pemeriksaan_ekg)?$pemeriksaan_ekg->irama:'', 'ekg_irama', 'ekg_irama', 'form-control', '', '') ?>
        </div>

    </div>

    <div class="form-group">

      <label class="control-label col-sm-2">HR</label>
      
      <div class="col-sm-2">    
        <div class="input-group">

        <input type="text" class="form-control" name="ekg_HR" id="ekg_HR" value="<?php echo isset($pemeriksaan_ekg)?$pemeriksaan_ekg->hr:''?>"  >

          <span class="input-group-addon">

            x/menit

          </span>

        </div>
      </div>

    </div>

    <div class="form-group">
                      
      <label class="control-label col-sm-2">Kesan</label>
      
      <div class="col-md-3">
        
        <textarea name="kesan_ekg" id="kesan_ekg" cols="50" style="height:100px !important;"><?php echo isset($pemeriksaan_ekg)?$pemeriksaan_ekg->kesan:'' ?></textarea>
      
      </div>
    
    
    </div>

        
    <div class="form-group">
      <label class="control-label col-sm-2" for="">&nbsp;</label>
      <div class="col-sm-3" style="margin-left:6px">
        <a href="#" class="btn btn-xs btn-primary" id="btn_radiologi"><i class="fa fa-save"></i> Submit </a>
      </div>
    </div>

  </div><!--end data -->
     
</div>







