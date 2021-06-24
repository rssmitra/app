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

<!-- hidden form -->
<input type="hidden" name="flag_form_pelayanan" value="<?php echo ($this->session->userdata('flag_form_pelayanan')) ? $this->session->userdata('flag_form_pelayanan') : 'perawat'?>">

<p><b><i class="fa fa-edit"></i> ASSESMENT PASIEN </b></p>
<div class="form-group">
    <label class="control-label col-sm-3" for="">Tinggi Badan (cm)</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="pl_tb" value="<?php echo isset($riwayat->tinggi_badan)?$riwayat->tinggi_badan:''?>">
    </div>
    <label class="control-label col-sm-3" for="">Berat Badan (Kg)</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="pl_bb" value="<?php echo isset($riwayat->berat_badan)?$riwayat->berat_badan:''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-3" for="">Tekanan Darah</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="pl_td" value="<?php echo isset($riwayat->tekanan_darah)?$riwayat->tekanan_darah:''?>">
    </div>
    <label class="control-label col-sm-3" for="">Suhu Tubuh</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="pl_suhu" value="<?php echo isset($riwayat->suhu)?$riwayat->suhu:''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-3" for="">Nadi</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="pl_nadi" value="<?php echo isset($riwayat->nadi)?$riwayat->nadi:''?>">
    </div>
</div>

<br>
<p><b><i class="fa fa-edit"></i> DIAGNOSA DAN PEMERIKSAAN </b></p>

<div>
  <label for="form-field-8">Anamnesa <span style="color:red">* : </span> </label>
  <textarea class="form-control" name="pl_anamnesa" style="height: 100px !important"><?php echo isset($riwayat->anamnesa)?$riwayat->anamnesa:''?></textarea>
  <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">
</div>


<div style="margin-top: 6px">
    <label for="form-field-8">Diagnosa (ICD10) <span style="color:red">* : </span></label>
      <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
      <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
</div>

<div style="margin-top: 6px">
    <label for="form-field-8">Pemeriksaan : </label>
    <textarea name="pl_pemeriksaan" id="pl_pemeriksaan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pemeriksaan)?$riwayat->pemeriksaan:''?></textarea>
</div>

<div style="margin-top: 6px">
    <label for="form-field-8">Anjuran Dokter : </label>
      <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pengobatan)?$riwayat->pengobatan:''?></textarea>
</div>

<br>
<p><b><i class="fa fa-edit"></i> PENUNJANG MEDIS DAN RESEP FARMASI </b></p>

<div style="margin-top: 6px">
    <label for="form-field-8">Penunjang Medis : </label>
    <div class="checkbox">

        <?php
            $arr_pm = array('050101','050201','050301');
            foreach ($arr_pm as $v_rw) :
                $checked = ($this->Pl_pelayanan->check_rujukan_pm($v_rw, $value->kode_bagian, $value->no_registrasi) == true ) ? 'checked' : ''; 
                switch ($v_rw) {
                    case '050101':
                        $nm_pm = 'Laboratorium';
                        break;

                    case '050201':
                        $nm_pm = 'Radiologi';
                        break;
                    
                    default:
                        $nm_pm = 'Fisioterapi';
                        break;
                }
        ?>
        <label>
            <input name="check_pm[]" type="checkbox" value="<?php echo $v_rw; ?>" class="ace" <?php echo $checked; ?> >
            <span class="lbl"> <?php echo $nm_pm ; ?> </span>
        </label>

        <?php endforeach; ?>

    </div>
</div>

<div style="margin-top: 6px">
    <label for="form-field-8">Resep Farmasi : </label>
    <div class="checkbox">
        <label>
            <input name="check_resep" type="checkbox" class="ace" value="1">
            <span class="lbl"> Ya</span>
        </label>
    </div>
</div>

<div class="form-group" style="padding-top: 10px">
    <div class="col-sm-12 no-padding">
       <button type="submit" name="submit" value="<?php echo ($this->session->userdata('flag_form_pelayanan')) ? $this->session->userdata('flag_form_pelayanan') : 'perawat'?>" class="btn btn-xs btn-primary" id="btn_save_data"> <i class="fa fa-save"></i> <?php echo ($this->session->userdata('flag_form_pelayanan')) ?  ($this->session->userdata('flag_form_pelayanan') == 'perawat') ? 'Simpan Data' : 'Simpan Data dan Lanjutkan ke Pasien Berikutnya' : 'Simpan Data'?> </button>
    </div>
</div>

