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

  $('#inputDokterIGD').typeahead({
      source: function (query, result) {
              $.ajax({
                  url: "templates/references/getAllDokter",
                  data: { keyword:query },            
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                  result($.map(response, function (item) {
                      return item;
                  }));
                  }
              });
          },
          afterSelect: function (item) {
          // do what is needed with item
          var val_item=item.split(':')[0];
          console.log(val_item);
          $('#igd_dokter_jaga').val(val_item);
          
      }
  });
</script>

<p><b><i class="fa fa-edit"></i> PENDAFTARAN PASIEN IGD</b></p>

<div class="form-group">
                
  <label class="control-label col-sm-2">Tanggal Kejadian</label>
  
  <div class="col-md-2">
    
    <div class="input-group">
        
        <input name="igd_tgl_kejadian" id="igd_tgl_kejadian" placeholder="" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>">
        <span class="input-group-addon">
          
          <i class="ace-icon fa fa-calendar"></i>
        
        </span>
      </div>
  
  </div>

  <label class="control-label col-sm-2" for="Province">*Jenis Kejadian</label>

      <div class="col-sm-3">

          <?php echo $this->master->custom_selection($params = array('table' => 'dc_jns_celaka', 'id' => 'jns_celaka', 'name' => 'jns_celaka', 'where' => array('flag_celaka' => 1)), 'Kecelakaan Lalulintas' , 'igd_jns_kejadian', 'igd_jns_kejadian', 'form-control', '', '') ?>

      </div>

</div>

<div class="form-group">
                
  <label class="control-label col-sm-2">*Tempat Kejadian</label>
  
  <div class="col-md-3">
    
    <input type="text" class="form-control" name="igd_tempat_kejadian" value="-">
  
  </div>

</div>

<div class="form-group">
                
  <label class="control-label col-sm-2">Dikirim Oleh</label>
  
  <div class="col-md-3">
    
    <input type="text" class="form-control" name="igd_dikirim_oleh" value="-">
  
  </div>

  <label class="control-label col-sm-2">Diantar Oleh</label>
  
  <div class="col-md-3">
    
    <input type="text" class="form-control" name="igd_diantar_oleh" value="Keluarga dan Kerabat">
  
  </div>

</div>


<div class="form-group">
                
  <label class="control-label col-sm-2">Dibawa RS dengan</label>
  
  <div class="col-md-2">
    
    <input type="text" class="form-control" name="igd_dibawa_dengan" value="Ambulance">
  
  </div>

  <label class="control-label col-sm-2">*Status Diterima</label>
  
  <div class="col-sm-4">
      
    <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'status_igd')), 'Hidup' , 'igd_status_diterima', 'igd_status_diterima', 'form-control', '', '') ?>
  
  </div>

</div>

<div class="form-group">

      <label class="control-label col-sm-2" for="Province">*Rujukan</label>

      <div class="col-sm-4">

          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'rujukan_igd')), 'Kemauan sendiri/Keluarga' , 'igd_rujukan', 'igd_rujukan', 'form-control', '', '') ?>

      </div>

</div>

<div class="form-group">

      <label class="control-label col-sm-2" for="Province">*Dokter</label>

      <div class="col-sm-4">

        <input id="inputDokterIGD" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />

        <input type="hidden" name="igd_dokter_jaga" id="igd_dokter_jaga" class="form-control">

      </div>


</div>

<p style="margin-left:-1%"><b><i class="fa fa-user"></i> KELUARGA TERDEKAT</b></p>

<div class="form-group">
                
  <label class="control-label col-sm-2">Nama </label>
  
  <div class="col-md-3">
    
    <input type="text" class="form-control" name="igd_nama_keluarga">
  
  </div>

</div>

<div class="form-group">
                
  <label class="control-label col-sm-2">Alamat</label>
  
  <div class="col-md-3">
    
    <textarea name="igd_alamat_keluarga" class="form-control" height="50px"></textarea>
  
  </div>

  <label class="control-label col-sm-2">No Telp</label>
  
  <div class="col-md-3">
    
    <input type="text" class="form-control" name="igd_telp_keluarga">
  
  </div>

</div>


