<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script src="<?php echo base_url()?>assets/tts/script.js"></script>

<script type="text/javascript">
    
    var minutesCount = 0; 
    var secondCount = 0; 
    var centiSecondCount = 0;
    var minutes = document.getElementById("minutes");
    var second = document.getElementById("second");
    var centiSecond = document.getElementById("centiSecond");

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

    $("#check_resep").change(function() {
        if(this.checked) {
            $('#form_e_resep').load('farmasi/E_resep/form/'+$('#no_registrasi').val()+'');
        }else{
            $('#form_e_resep').html('');
        }
    });

    $('#callPatient').click(function (e) {  
        e.preventDefault();
        speak();
        var params = {
            no_kunjungan : $('#no_kunjungan').val(),
            dokter : $('#kode_dokter_poli').val(),
            poli : $('#kode_bagian_val').val(),
        };
      $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan/callPatient') ?>", params , function (response) {    
        return true;  
      })
    });

    function show_hide_voice_config(varid){
        if(varid == 'show_voice_config'){
            $('#'+varid).attr('id','hide_voice_config');
            $('#voice_config').show();
        }

        if(varid == 'hide_voice_config'){
            $('#'+varid).attr('id','show_voice_config');
            $('#voice_config').hide();
        }
    }

</script>

<style>
    .input-icon > input {
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
    #voice_config{
        background: aliceblue;
        padding: 7px;
    }
</style>

<!-- hidden form -->

<audio id="container" autoplay=""></audio>

<span>Waktu Pelayanan</span><br>
<div class="pull-left" style="font-size: 20px; font-weight: bold">
    <span id="minutes">00</span> : <span id="second">00</span> : <span id="centiSecond">00</span>
</div>
<div class="pull-right">
    <button type="button" class="btn btn-xs btn-inverse" id="startCount" onclick="startStopWatch()">Start <i class="fa fa-play"></i></button>
    <button type="button" class="btn btn-xs btn-inverse" id="pauseCount" onclick="pauseStopWatch()">Stop <i class="fa fa-pause"></i></button>
    <button type="button" class="btn btn-xs btn-success" id="callPatient">Panggil Pasien <i class="fa fa-bullhorn bigger-120"></i></button>
</div>
<div class="clearfix"></div>
<br>
<div class="col-sm-12 no-padding">
    <p style="font-style: italic; font-size: 11px; cursor: pointer; color: blue; font-weight: bold" id="show_voice_config" onclick="show_hide_voice_config(this.id)">Pengaturan Suara</p>
    <div id="voice_config" style="margin-bottom: 20px; display:none">
        <div class="form-group">
            <label class="control-label col-sm-1">Text : </label>
            <div class="col-sm-11">
                <input type="text" id="txt_call_patient" class="form-control txt" value="<?php echo $txt_call_patient?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-1">Bahasa</label>
            <div class="col-sm-5">
                <select></select>
            </div>  
        </div> 
        <div class="form-group">
            <label class="col-sm-1">Rate : </label>
            <div class="col-sm-5">
                <input type="range" min="0.5" max="2" value="1" step="0.1" id="rate">
                <div class="rate-value">1</div>
                <div class="clearfix"></div>
            </div>
            <label class="col-sm-1">Pitch : </label>
            <div class="col-sm-5">
                <input type="range" min="0" max="2" value="1" step="0.1" id="pitch">
            <div class="pitch-value">1</div>
            <div class="clearfix"></div>
            </div>
        </div>  
    </div>  
</div>  

<div class="hr dotted" ></div>

<input type="hidden" name="flag_form_pelayanan" value="<?php echo ($this->session->userdata('flag_form_pelayanan')) ? $this->session->userdata('flag_form_pelayanan') : 'perawat'?>">
<input type="hidden" name="no_mr_resep" id="no_mr_resep" value="<?php echo $no_mr; ?>">
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
<p><b><i class="fa fa-stethoscope bigger-120"></i>  DIAGNOSA DAN PEMERIKSAAN </b></p>

<div>
    <label for="form-field-8">Diagnosa (ICD10) <span style="color:red">* </span></label>
    <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
    <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
</div>

<div style="margin-top: 6px">
    <label for="form-field-8">Anamnesa <span style="color:red">* </span> <small>(minimal 8 karakter)</small> </label>
    <textarea class="form-control" name="pl_anamnesa" style="height: 100px !important"><?php echo isset($riwayat->anamnesa)?$this->master->br2nl($riwayat->anamnesa):''?></textarea>
    <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">
</div>

<div class="row">
    <div class="col-md-6" style="margin-top: 6px">
        <label for="form-field-8">Pemeriksaan </label>
        <textarea name="pl_pemeriksaan" id="pl_pemeriksaan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pemeriksaan)?$this->master->br2nl($riwayat->pemeriksaan):''?></textarea>
    </div>

    <div class="col-md-6" style="margin-top: 6px">
        <label for="form-field-8">Anjuran Dokter </label>
        <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pengobatan)?$this->master->br2nl($riwayat->pengobatan):''?></textarea>
    </div>
</div>

<br>
<p><b><i class="fa fa-file bigger-120"></i> e-RESEP FARMASI </b></p>
<div style="margin-top: 6px">
    <div class="checkbox" style="margin-left: -20px">
        <label>
        Apakah ada Resep Farmasi / Resep Dokter ? <span style="color:red">*</span>
        </label>
        <label>
            <?php 
                $checked_resep = ($this->Pl_pelayanan->check_resep_fr($value->kode_bagian, $value->no_registrasi) == true ) ? 'checked' : ''; 
            ?>
            <input name="check_resep" id="check_resep" type="radio" class="ace" value="1" <?php echo $checked_resep; ?>>
            <span class="lbl"> Ya</span>
        </label>
        <label>
            <?php 
                $checked_resep_no = ($this->Pl_pelayanan->check_resep_fr($value->kode_bagian, $value->no_registrasi) == false ) ? 'checked' : ''; 
            ?>
            <input name="check_resep" id="check_resep" type="radio" class="ace" value="0" <?php echo $checked_resep_no; ?>>
            <span class="lbl"> Tidak</span>
        </label>
    </div>
</div>

<!-- <div id="form_e_resep"></div> -->

<div class="row" id="form_input_resep" <?php echo ($checked_resep == '')?'style="display: none"':''; ?>>
    <div class="col-md-12" style="margin-top: 6px">
        <textarea name="pl_resep_farmasi" id="pl_resep_farmasi" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->resep_farmasi)?$this->master->br2nl($riwayat->resep_farmasi):''?></textarea>
    </div>
</div>


<br>
<p><b><i class="fa fa-stethoscope bigger-120"></i> PENUNJANG MEDIS </b></p>

<div style="margin-top: 6px">
    <label for="form-field-8">Penunjang Medis <span style="color:red">*</span></label>
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
                $arr_checked[] = $checked;
        ?>
        <label>
            <input name="check_pm[]" type="checkbox" value="<?php echo $v_rw; ?>" class="ace" <?php echo $checked; ?> >
            <span class="lbl"> <?php echo $nm_pm ; ?> </span>
        </label>

        <?php endforeach; ?>

        <label>
            <input name="check_pm[]" type="checkbox" value="0" class="ace" <?php echo (count($checked) > 0) ? '' : 'checked'?> >
            <span class="lbl"> Tidak Ada Penunjang </span>
        </label>

    </div>
</div>

<br>
<p><b><i class="fa fa-stethoscope bigger-120"></i> STATUS KUNJUNGAN PASIEN </b></p>

<div class="form-group">
    <label class="control-label col-sm-3" for="">Cara Keluar Pasien</label>
    <div class="col-sm-4">
        <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'cara_keluar')), 'Atas Persetujuan Dokter' , 'cara_keluar', 'cara_keluar', 'form-control', '', '') ?>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-3" for="">Pasca Pulang</label>
    <div class="col-sm-4">
        <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'pasca_pulang')), 'Dalam Masa Pengobatan' , 'pasca_pulang', 'pasca_pulang', 'form-control', '', '') ?>
    </div>
</div>

<div class="form-group" style="padding-top: 10px">
    <div class="col-sm-12 no-padding">
       <button type="submit" name="submit" value="<?php echo ($this->session->userdata('flag_form_pelayanan')) ? $this->session->userdata('flag_form_pelayanan') : 'perawat'?>" class="btn btn-xs btn-primary" id="btn_save_data"> <i class="fa fa-save"></i> <?php echo ($this->session->userdata('flag_form_pelayanan')) ?  ($this->session->userdata('flag_form_pelayanan') == 'perawat') ? 'Simpan Data' : 'Simpan Data ' : 'Simpan Data'?> </button>

    </div>
</div>

<script src="<?php echo base_url()?>assets/js/custom/counter_poli.js"></script>