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
<p><b><i class="fa fa-edit"></i> ASSESMENT PASIEN </b></p>
<div class="form-group">
<label class="control-label col-sm-2" for="">Tinggi Badan (cm)</label>
    <div class="col-sm-1">
       <input type="text" class="form-control" name="pl_tb" value="<?php echo isset($riwayat->tinggi_badan)?$riwayat->tinggi_badan:''?>">
    </div>
    <label class="control-label col-sm-2" for="">Berat Badan (Kg)</label>
    <div class="col-sm-1">
       <input type="text" class="form-control" name="pl_bb" value="<?php echo isset($riwayat->berat_badan)?$riwayat->berat_badan:''?>">
    </div>
    <label class="control-label col-sm-2" for="">Tekanan Darah</label>
    <div class="col-sm-1">
       <input type="text" class="form-control" name="pl_td" value="<?php echo isset($riwayat->tekanan_darah)?$riwayat->tekanan_darah:''?>">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="">Suhu Tubuh</label>
    <div class="col-sm-1">
       <input type="text" class="form-control" name="pl_suhu" value="<?php echo isset($riwayat->suhu)?$riwayat->suhu:''?>">
    </div>
    <label class="control-label col-sm-1" for="">Nadi</label>
    <div class="col-sm-1">
       <input type="text" class="form-control" name="pl_nadi" value="<?php echo isset($riwayat->nadi)?$riwayat->nadi:''?>">
    </div>
</div>

<br>
<p><b><i class="fa fa-edit"></i> DIAGNOSA DAN PEMERIKSAAN </b></p>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Anamnesa</label>
    <div class="col-sm-4">
       <input type="text" class="form-control" name="pl_anamnesa" value="<?php echo isset($riwayat->anamnesa)?$riwayat->anamnesa:''?>">
       <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Diagnosa <span style="color:red">(*)</span></label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
      <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Pemeriksaan</label>
    <div class="col-sm-10">
        <textarea name="pl_pemeriksaan" id="pl_pemeriksaan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pemeriksaan)?$riwayat->pemeriksaan:''?></textarea>
    </div>
</div>

<div class="form-group" style="margin-top: 6px">
    <label class="control-label col-sm-2" for="">Anjuran Dokter</label>
    <div class="col-sm-10">
      <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pengobatan)?$riwayat->pengobatan:''?></textarea>
    </div>
</div>