<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

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

    $('#pl_diagnosa_sekunder_igd').typeahead({
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
            $('#pl_diagnosa_sekunder_igd').val('');
            $('<span class="multi-typeahead" id="txt_icd_'+val_item.trim().replace('.', '_')+'"><a href="#" onclick="remove_icd('+"'"+val_item.trim().replace('.', '_')+"'"+')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span><span class="text_icd_10"> '+item+' </span> </span>').appendTo('#pl_diagnosa_sekunder_igd_hidden_txt');
        }
    });

    $( "#pl_diagnosa_sekunder_igd" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            var val_item = 1 + Math.floor(Math.random() * 100);
            console.log(val_item);
            var item = $('#pl_diagnosa_sekunder_igd').val();
            $('<span class="multi-typeahead" id="txt_icd_'+val_item+'"><a href="#" onclick="remove_icd('+"'"+val_item+"'"+')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span><span class="text_icd_10"> '+item+' </span> </span>').appendTo('#pl_diagnosa_sekunder_igd_hidden_txt'); 
          }          
          return $('#pl_diagnosa_sekunder_igd').val('');                 
        }    
    });

    function remove_icd(icd){
        preventDefault();
        $('#txt_icd_'+icd+'').html('');
        $('#txt_icd_'+icd+'').hide();
    }

    $('#pl_procedure').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "ws_bpjs/Ws_index/getRef?ref=RefProcedure",
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
        var label_item=item.split('-')[1];
        var val_item=item.split('-')[0];
        console.log(val_item);
        $('#pl_procedure').val(label_item);
        $('#pl_procedure_hidden').val(val_item);
        }
    });

</script>
<!-- default value from triase form -->
 <?php
    
    // form pengkajian keperawatan dokter
    // soap
    $subjective_anamnesa = isset($pengkajian_keperawatan['igd_keluhan_utama']) ? $pengkajian_keperawatan['igd_keluhan_utama']:'';
    $objective_pemeriksaan_fisik = isset($pengkajian_keperawatan['igd_keluhan_tambahan_pf']) ? $pengkajian_keperawatan['igd_keluhan_tambahan_pf']:'';
    $assesmen_diagnosa_primer = isset($pengkajian_keperawatan['igd_diagnosa_kerja']) ? $pengkajian_keperawatan['igd_diagnosa_kerja']:'';
    $assesmen_diagnosa_primer_hidden = isset($pengkajian_keperawatan['igd_diagnosa_kerja_hidden']) ? $pengkajian_keperawatan['igd_diagnosa_kerja_hidden']:'';
    $diagnosa_sekunder = isset($pengkajian_keperawatan['igd_diagnosa_banding']) ? $pengkajian_keperawatan['igd_diagnosa_banding']:'';
    $html_planning = '';
    for($i=0; $i<6; $i++){
        $jam_planning_anjuran_dokter = isset($pengkajian_keperawatan['jam_tindakan_pu_'.$i.'']) ? $pengkajian_keperawatan['jam_tindakan_pu_'.$i.'']:'';
        $planning_anjuran_dokter = isset($pengkajian_keperawatan['periksa_pu_'.$i.'']) ? $pengkajian_keperawatan['periksa_pu_'.$i.'']:'';
        if(!empty($planning_anjuran_dokter) && $planning_anjuran_dokter != ''){
            $html_planning .= $i.' - '.$jam_planning_anjuran_dokter.' - '.$planning_anjuran_dokter.'<br>';
        }
    }
    // form pengkajian keperawatan
    $penanganan_meninggal = isset($pengkajian_keperawatan_triase['penanganan_meninggal']) ? $pengkajian_keperawatan_triase['penanganan_meninggal']:'';
    $bb_v = isset($pengkajian_keperawatan_triase['bb_v']) ? $pengkajian_keperawatan_triase['bb_v']:'';
    $tinggi_badan = isset($pengkajian_keperawatan_triase['tinggi_badan']) ? $pengkajian_keperawatan_triase['tinggi_badan']:'';
    $nadi = isset($pengkajian_keperawatan_triase['nadi']) ? $pengkajian_keperawatan_triase['nadi']:'';
    $suhu = isset($pengkajian_keperawatan_triase['suhu']) ? $pengkajian_keperawatan_triase['suhu']:'';
    $saturasi = isset($pengkajian_keperawatan_triase['saturasi']) ? $pengkajian_keperawatan_triase['saturasi']:'';
    $tekanan_darah = isset($pengkajian_keperawatan_triase['tekanan_darah']) ? $pengkajian_keperawatan_triase['tekanan_darah']:'';
 ?>
<input type="hidden" class="form-control" name="kode_meninggal" id="kode_meninggal" value="<?php $is_die = isset($pengkajian_keperawatan_triase['penanganan_meninggal'])?$pengkajian_keperawatan_triase['penanganan_meninggal']:''; echo ($is_die == 'on') ? 1 : 0?>">
<input type="hidden" class="form-control" name="kode_gd" id="kode_gd" value="<?php echo isset($value->kode_gd)?$value->kode_gd:''?>">

<div class="col-md-12">
    <p style="text-align: right; margin-top: -10px"><b><span style="font-size: 36px;font-family: 'Glyphicons Halflings';">S O A P</span> <br>(<i>Subjective, Objective, Assesment, Planning</i>) </b></p>
</div>

<br>
<div class="form-group">
    <label class="control-label col-sm-2" for="">Kode Riwayat</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>" readonly>
    </div>
    <label class="control-label col-sm-2" for="">Tanggal</label>
    <div class="col-md-3">
        <div class="input-group">
        <input name="pl_tgl_transaksi" id="pl_tgl_transaksi" class="form-control date-picker" data-date-format="yyyy-mm-dd" type="text" value="<?php echo isset($riwayat->tgl_periksa)?$this->tanggal->formatDateTimeToSqlDate($riwayat->tgl_periksa):date('Y-m-d')?>">
        <span class="input-group-addon">
            <i class="ace-icon fa fa-calendar"></i>
        </span>
        </div>
    </div>
</div>


<div class="form-group">
    <label class="control-label col-sm-2" for="">Kategori Triase</label>
    <div class="col-sm-3">
      <?php 
        if(isset($pengkajian_keperawatan_triase['triase_merah']) && $pengkajian_keperawatan_triase['triase_merah'] == 'on'){
            $selected = 1;
        }elseif(isset($pengkajian_keperawatan_triase['triase_kuning']) && $pengkajian_keperawatan_triase['triase_kuning'] == 'on'){
            $selected = 2;
        }elseif(isset($pengkajian_keperawatan_triase['triase_hijau']) && $pengkajian_keperawatan_triase['triase_hijau'] == 'on'){
            $selected = 3;
        }elseif(isset($pengkajian_keperawatan_triase['triase_hitam']) && $pengkajian_keperawatan_triase['triase_hitam'] == 'on'){
            $selected = 4;
        }else{
            $selected = isset($riwayat->kategori_tindakan)?$riwayat->kategori_tindakan:'';
        }

        echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'kategori_tindakan')), $selected , 'kategori_tindakan', 'kategori_tindakan', 'form-control', '', '') ?>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="">Jenis Kasus</label>
    <div class="col-sm-4">
      <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_kasus_igd')), ($value->jenis_kasus)?$value->jenis_kasus:'' , 'jenis_kasus_igd', 'jenis_kasus_igd', 'form-control', '', '') ?>
    </div>
</div>
<br>
<span style="font-weight: bold; font-style: italic; color: blue">(Subjective)</span>
<div style="margin-top: 6px">
    <label for="form-field-8"> <b>Anamnesa / Keluhan Pasien</b> <span style="color:red">* </span> <br><span style="font-size: 11px; font-style: italic">(Masukan anamnesa minimal 8 karakter)</span> </label>
    <textarea class="form-control" name="pl_anamnesa" style="height: 100px !important" id="pl_anamnesa"><?php echo isset($riwayat->anamnesa)?$this->master->br2nl($riwayat->anamnesa):$this->master->br2nl($subjective_anamnesa)?></textarea>
    
</div>
<br>

<span style="font-weight: bold; font-style: italic; color: blue">(Objective)</span>

<div style="margin-top: 6px">
    <label for="form-field-8"> <i><b>Vital Sign</b></i><br><span style="font-size: 11px; font-style: italic">(Masukan tanda-tanda vital)</span></label>
    <table class="table">
        <tr style="font-size: 11px; background: beige;">
            <th>Tinggi Badan (Cm)</th>
            <th>Berat Badan (Kg)</th>
            <th>Tekanan Darah (mmHg)</th>
            <th>Nadi (bpm)</th>
            <th>Suhu Tubuh (C&deg;)</th>
            <th>Saturasi (mmHg)</th>
        </tr>
        <tbody>
        <tr style="background: aliceblue;">
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_tb_igd" value="<?php echo isset($riwayat->tinggi_badan)?$riwayat->tinggi_badan:$tinggi_badan?>">
            </td>
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_bb_igd" value="<?php echo isset($riwayat->berat_badan)?$riwayat->berat_badan:$bb_v?>">
            </td>
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_td_igd" value="<?php echo isset($riwayat->tekanan_darah)?$riwayat->tekanan_darah:$tekanan_darah?>">
            </td>
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_nadi_igd" value="<?php echo isset($riwayat->nadi)?$riwayat->nadi:$nadi?>">
            </td>
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_suhu_igd" value="<?php echo isset($riwayat->suhu)?$riwayat->suhu:$suhu?>">
            </td>
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_saturasi_igd" value="<?php echo isset($riwayat->saturasi)?$riwayat->saturasi:$saturasi?>">
            </td>
        </tr>
        </tbody>
    </table>

    <label for="form-field-8"> <b>Pemeriksaan Fisik</b><br><span style="font-size: 11px; font-style: italic">(Mohon dijelaskan kondisi fisik pasien)</span></label>
    <textarea name="pl_pemeriksaan" id="pl_pemeriksaan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pemeriksaan)?$this->master->br2nl($riwayat->pemeriksaan):$this->master->br2nl($objective_pemeriksaan_fisik)?></textarea>
    <input type="hidden" name="flag_form_pelayanan" value="dokter"><br>
    
</div>

<span style="font-weight: bold; font-style: italic; color: blue">(Assesment)</span>

<div style="margin-top: 6px">
    <label for="form-field-8"><b>Diagnosa Primer(ICD10)</b> <span style="color:red">* </span><br><i style="font-size: 11px">(Wajib mengisi menggunakan ICD10)</i></label>
    <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:$assesmen_diagnosa_primer?>">
    <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:$assesmen_diagnosa_primer_hidden?>">
</div>

<div style="margin-top: 6px">
    <label for="form-field-8"><b>Diagnosa Sekunder</b> <br><i style="font-size: 11px">(Klik <b>"enter"</b> untuk menambahkan Diagnosa Sekunder dan dapat diisi lebih dari satu )</i></label>
    <input type="text" class="form-control" name="pl_diagnosa_sekunder_igd" id="pl_diagnosa_sekunder_igd" placeholder="Masukan keyword ICD 10" value="">
    <div id="pl_diagnosa_sekunder_igd_hidden_txt" style="padding: 2px; line-height: 23px; border: 1px solid #d5d5d5; min-height: 25px; margin-top: 2px">
        <?php
            $arr_text = isset($riwayat->diagnosa_sekunder) ? explode('|',$riwayat->diagnosa_sekunder) : explode('|',$diagnosa_sekunder);
            // echo "<pre>";print_r($arr_text);
            $no_ds = 1;
            foreach ($arr_text as $k => $v) {
                $len = strlen(trim($v));
                // echo $len;
                if($len > 0){
                    $no_ds++;
                    $split = explode(':',$v);
                    if(count($split) > 1){
                        echo '<span class="multi-typeahead" id="txt_icd_'.trim(str_replace('.','_',$split[0])).'"><a href="#" onclick="remove_icd('."'".trim(str_replace('.','_',$split[0]))."'".')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span> <span class="text_icd_10"> '.$v.' </span> </span>';
                    }else{
                        echo '<span class="multi-typeahead" id="txt_icd_'.$no_ds.'"><a href="#" onclick="remove_icd('."'".$no_ds."'".')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span> <span class="text_icd_10"> '.$v.' </span> </span>';
                    }
                }
                
            }
        ?>
    </div>
    <input type="hidden" class="form-control" name="konten_diagnosa_sekunder_igd" id="konten_diagnosa_sekunder_igd" value="<?php echo isset($riwayat->diagnosa_sekunder)?$riwayat->diagnosa_sekunder:''?>">
</div>

<div style="margin-top: 6px">
    <label for="form-field-8"><b>Prosedur/ Tindakan(ICD9)</b> <span style="color:red">* </span><br><i style="font-size: 11px">(Wajib mengisi menggunakan ICD9)</i></label>
    <input type="text" class="form-control" name="pl_procedure" id="pl_procedure" placeholder="Masukan keyword ICD 9" value="<?php echo isset($riwayat->text_icd9)?$riwayat->text_icd9:' Other consultation'?>">
    <input type="hidden" class="form-control" name="pl_procedure_hidden" id="pl_procedure_hidden" value="<?php echo isset($riwayat->kode_icd9)?$riwayat->kode_icd9:'89.08'?>">
</div>

<br>
<span style="font-weight: bold; font-style: italic; color: blue">(Planning)</span>
<div style="margin-top: 6px">
    <label for="form-field-8"><b>Rencana Asuhan / Anjuran Dokter</b><br><i style="font-size: 11px">(Mohon dijelaskan Rencana Asuhan Pasien dan Tindak Lanjutnya)</i></label>
    <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pengobatan)?$this->master->br2nl($riwayat->pengobatan):$this->master->br2nl($html_planning)?></textarea>
</div>

<div style="margin-top: 6px">
    <label for="form-field-8"><b>Alergi (Reaksi Obat)</b><br><i style="font-size: 11px">(Mohon dijelaskan jika pasien memiliki alergi terhadap obat)</i></label>
    <textarea name="pl_alergi" id="pl_alergi" class="form-control" style="height: 70px !important"><?php echo isset($riwayat->alergi_obat)?$this->master->br2nl($riwayat->alergi_obat):''?></textarea>
</div>

<div style="margin-top: 6px">
    <label for="form-field-8"><b>Diet</b><br><i style="font-size: 11px">(Mohon dijelaskan petunjuk untuk diet pasien)</i></label>
    <textarea name="pl_diet" id="pl_diet" class="form-control" style="height: 70px !important"><?php echo isset($riwayat->diet)?$this->master->br2nl($riwayat->diet):''?></textarea>
</div>

<div style="margin-top: 6px">
    <label for="form-field-8"><b>Tanggal Kontrol Kembali</b><br><i style="font-size: 11px">(Secara default untuk pasien BPJS kontrol kembali setelah 31 hari)</i></label><br>
    <input type="text" class="date-picker" data-date-format="yyyy-mm-dd" name="pl_tgl_kontrol_kembali" id="pl_tgl_kontrol_kembali" class="form-control" style="width: 100% !important" placeholder="ex: <?php echo date('Y-m-d')?>" value="<?php $next_date = date('Y-m-d', strtotime("+31 days")); echo isset($riwayat->tgl_kontrol_kembali)?$riwayat->tgl_kontrol_kembali:$next_date?>">
</div>
<div style="margin-top: 6px">
    <label for="form-field-8"><b>Catatan Kontrol</b></label>
    <textarea name="pl_catatan_kontrol" id="pl_catatan_kontrol" class="form-control" style="height: 70px !important" placeholder="ex. Mohon membawa hasil LAB saat kontrol kembali"><?php echo isset($riwayat->catatan_kontrol_kembali)?$this->master->br2nl($riwayat->catatan_kontrol_kembali):''?></textarea>
</div>
<br>


<div class="form-group" style="padding-top: 10px">
    <div class="col-sm-12 no-padding">
        <button type="submit" name="submit" value="dokter" class="btn btn-xs btn-primary" id="btn_save_data"> <i class="fa fa-save"></i> Simpan Data </button>
    </div>
</div>
