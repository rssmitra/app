<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script type="text/javascript">
  
  $('#pl_diagnosa').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getICD10",
              data: 'keyword=' + query,            
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
        var label_item=item.split(':')[1];
        var val_item=item.split(':')[0];
        console.log(val_item);
        $('#pl_diagnosa').val(label_item);
        $('#pl_diagnosa_hidden').val(val_item);
      }

  });

</script>

<p><b><i class="fa fa-edit"></i> DIAGNOSA DAN PEMERIKSAAN </b></p>

<input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">

<div class="form-group">
    <label class="control-label col-sm-2" for="">Kategori Triase</label>
    <div class="col-sm-3">
      <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'kategori_tindakan')), 3 , 'kategori_tindakan', 'kategori_tindakan', 'form-control', '', '') ?>
    </div>

    <label class="control-label col-sm-2" for="">Jenis Kasus</label>
    <div class="col-sm-4">
      <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_kasus_igd')), '' , 'jenis_kasus_igd', 'jenis_kasus_igd', 'form-control', '', '') ?>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Diagnosa <span style="color:red">(*)</span></label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
      <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
    </div>
</div>

<div class="form-group" >
    <label class="control-label col-sm-2" for="">Anamnesa</label>
    <div class="col-sm-10">
      <textarea name="pl_anamnesa" id="pl_anamnesa" class="form-control" style="height: 150px !important"><?php echo isset($riwayat->anamnesa)?$riwayat->anamnesa:''?></textarea>
    
       <!-- <input type="text" class="form-control" name="pl_anamnesa" value="<?php echo isset($riwayat->anamnesa)?$riwayat->anamnesa:''?>"> -->
       
    </div>
</div>

<div class="form-group" style="padding-top: 6px">
    <label class="control-label col-sm-2" for="">Pemeriksaan</label>
    <div class="col-sm-10">
        <textarea name="pl_pemeriksaan" id="pl_pemeriksaan" class="form-control" style="height: 150px !important"><?php echo isset($riwayat->pemeriksaan)?$riwayat->pemeriksaan:''?></textarea>
    </div>
</div>

<div class="form-group" style="margin-top: 6px">
    <label class="control-label col-sm-2" for="">Anjuran Dokter</label>
    <div class="col-sm-10">
      <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height: 150px !important"><?php echo isset($riwayat->pengobatan)?$riwayat->pengobatan:''?></textarea>
    </div>
</div>

<div class="form-group" style="padding-top: 10px">
    <label class="control-label col-sm-2" for="">&nbsp;</label>
    <div class="col-sm-4" style="margin-left:6px">
       <button type="submit" class="btn btn-xs btn-primary" id="btn_save_data"> <i class="fa fa-save"></i> Simpan Data </button>
    </div>
</div>