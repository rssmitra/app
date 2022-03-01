<script type="text/javascript">

$(document).ready(function(){

    current_day = $('#current_day').val();
    current_kode_poli = $('#kodePoliPerjanjian').val();
    current_kode_dokter = $('#kodeDokterDPJPPerjanjian').val();

    $.getJSON("<?php echo site_url('Templates/References/getKlinikFromJadwal') ?>/" +current_day, '', function (data) {              
        $('#reg_klinik_rajal_sep option').remove();  
        $('<option value="">-Pilih Klinik-</option>').appendTo($('#reg_klinik_rajal_sep'));
        $.each(data, function (i, o) {  
            var selected = (parseInt(o.kode_bagian) == current_kode_poli) ? 'selected' : '' ;   
            console.log(current_kode_poli);             
            $('<option value="' + o.kode_bagian + '" '+selected+'>' + o.nama_bagian + '</option>').appendTo($('#reg_klinik_rajal_sep'));                    
            
        });      


    });    
    
    $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialisFromJadwal') ?>/" + current_kode_poli + '/' +current_day, '', function (data) {   
        $('#reg_dokter_rajal_sep option').remove();         
        $('<option value="">-Pilih Dokter-</option>').appendTo($('#reg_dokter_rajal_sep'));  
        $.each(data, function (i, o) {   
            var selected = (parseInt(o.kode_dokter) == current_kode_dokter) ? 'selected' : '' ; 
            $('<option value="' + o.kode_dokter + '" '+selected+'>' + o.nama_pegawai + '</option>').appendTo($('#reg_dokter_rajal_sep'));  
        });   
    }); 
    
})

$('select[name="reg_klinik_rajal_sep"]').change(function () {  
    /*current day*/
    current_day = $('#current_day').val();
    if ($(this).val() != '012801') {     
        $('#reg_dokter_rajal_sep').attr('name', 'reg_dokter_rajal_sep');
        $('#reg_dokter_rajal_sep_dinamis').attr('name', 'reg_dokter_rajal_sep_');
        $('#dokter_dinamis_klinik').hide('fast')
        $('#dokter_by_klinik').show('fast');
        $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialisFromJadwal') ?>/" + $(this).val() + '/' +current_day, '', function (data) {   
            $('#reg_dokter_rajal_sep option').remove();         
            $('<option value="">-Pilih Dokter-</option>').appendTo($('#reg_dokter_rajal_sep'));  
            $.each(data, function (i, o) {   
                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#reg_dokter_rajal_sep'));  
            });   
        });   
    } else {    
        $('#reg_dokter_rajal_sep option').remove()  
        $('#reg_dokter_rajal_sep_dinamis').attr('name', 'reg_dokter_rajal_sep');
        $('#reg_dokter_rajal_sep').attr('name', 'reg_dokter_rajal_sep_');
        $('#dokter_by_klinik').hide('fast')
        $('#dokter_dinamis_klinik').show('fast')
    }    
    // title
    $('#title-select-klinik').text( $('#reg_klinik_rajal_sep option:selected').text().toUpperCase() );
}); 

$('select[id="reg_dokter_rajal_sep"]').change(function () {      

    if ($(this).val()) {          

        $.getJSON("<?php echo site_url('Templates/References/getKuotaDokter') ?>/" + $(this).val() + '/' +$('select[name="reg_klinik_rajal_sep"]').val() , '', function (data) {              

            $('#sisa_kuota').val(data.sisa_kuota); 
            $('#message_for_kuota').html(data.message);              
            if(data.sisa_kuota > 0){
                $('#btn_submit').show('fast');
                $('#message_for_kuota_null').html('');
            }else{
                $('#btn_submit').hide('fast');
                $('#message_for_kuota_null').html('<span style="color: red; font-weight: bold; font-style:italic">- Mohon Maaf Kuota Dokter Sudah Penuh !</span>');
            }
            $('#jd_id').val(data.jd_id); 
        });            
        
        $('#title-select-dokter').text( $('#reg_dokter_rajal_sep option:selected').text() );

    }      

});  

$('#inputDokter').typeahead({
    source: function (query, result) {
            $.ajax({
                url: "templates/references/getDokterByBagian",
                data: 'keyword=' + query + '&bag=' + $('#reg_klinik_rajal_sep').val(),         
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
        $('#reg_dokter_rajal_sep_dinamis').val(val_item);
        
    }
});

$('#show_dpjp').typeahead({
    source: function (query, result) {
            $.ajax({
                url: "templates/references/getRefDokterBPJS",
                data: 'keyword=' + query + '&spesialis='+$('#kodePoliHiddenTujuan').val()+' &tgl='+$('#tglKunjungan').val()+'',         
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
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#show_dpjp').val(label_item);
        $('#kodeDokterDPJPPerjanjianBPJS').val(val_item);
        
    }
});

function changeCheckboxRujukanBaru(){
    var value_checkbox = $('input[name="rujukan_baru"]:checked').val();
    if( value_checkbox == 1){
        $('#noSuratSKDP').val('');
    }
}

</script>

<div id="formDetailInsertSEP">
    
    <p><b>HASIL PENCARIAN NOMOR RUJUKAN</b></p>

    <div class="form-group">
        <label class="control-label col-md-3">No.Surat Kontrol/SKDP</label>
        <div class="col-md-3">
            <input type="text" class="form-control" id="noSuratSKDP" name="noSuratSKDP">
        </div>
        <div class="col-md-6">
            <div class="checkbox">
                <label>
                <input name="rujukan_baru" type="checkbox" class="ace" value="1" onclick="changeCheckboxRujukanBaru()">
                <span class="lbl"> Nomor Rujukan Baru</span>
                </label>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-md-3 control-label">PPK Asal Rujukan</label>
        <div class="col-md-5 col-sm-5 col-xs-12">
            <input id="inputKeyFaskes" class="form-control" name="ppkRujukan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" readonly/>
            <input type="hidden" name="kodeFaskesHidden" value="" id="kodeFaskesHidden">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3">No MR </label>
        <div class="col-md-2">
            <input type="text" class="form-control" id="noMR" name="noMR">
        </div>
        
        <div class="col-md-7">
            <div class="checkbox">
                <label>
                <input name="cob" type="checkbox" class="ace" value="1">
                <span class="lbl"> Peserta COB</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Form Rujukan, tidak ditampilkan untuk poli IGD -->

    <div id="formRujukan">

        <div class="form-group">
            <label class="control-label col-md-3">No Rujukan </label>
            <div class="col-md-3">
                <input type="text" class="form-control" id="noRujukanView" name="noRujukan" readonly>
            </div>

            <label class="control-label col-md-2">Tanggal</label>
            <div class="col-md-2">
                <div class="input-group">
                    <input name="tglRujukan" id="tglKunjungan" value="" placeholder="dd/mm/YYYY" class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                <i class="ace-icon fa fa-calendar"></i>
                </span>
                </div>
            </div>

        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Diagnosa </label>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <input id="inputKeyDiagnosa" class="form-control" name="diagAwal" type="text" placeholder="Masukan keyword minimal 3 karakter" style="text-transform: uppercase" readonly/>
                <input type="hidden" name="kodeDiagnosaHidden" value="" id="kodeDiagnosaHidden">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-3">No Telp </label>
            <div class="col-md-3">
                <input type="text" class="form-control" id="noTelp" name="noTelp">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Spesialis/SubSpesialis</label>
            <div class="col-md-5 col-sm-5 col-xs-12">
                <input id="inputKeyPoliTujuan" class="form-control" name="tujuan" type="text" placeholder="Masukan keyword minimal 3 karakter" readonly/>
                <input type="hidden" name="kodePoliHidden" value="" id="kodePoliHiddenTujuan">
            </div>

            <div class="col-md-3">
            <div class="checkbox">
                <label>
                <input name="eksekutif" type="checkbox" class="ace" value="1">
                <span class="lbl"> Eksekutif</span>
                </label>
            </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-3">Dokter DPJP </label>
            <div class="col-md-6">
            <input id="show_dpjp" class="form-control" name="show_dpjp" type="text"/>
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-md-3">Tujuan Kunjungan </label>
            <div class="col-md-3">
            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'tujuan_kunjungan')), '0' , 'tujuanKunj', 'tujuanKunj', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-3">Procedure</label>
            <div class="col-md-6">
            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'flag_procedure')), '' , 'flagProcedure', 'flagProcedure', 'form-control', '', '') ?>
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-md-3">Penunjang</label>
            <div class="col-md-6">
            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'penunjang')), '' , 'kdPenunjang', 'kdPenunjang', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-3">Assesment Pelayanan</label>
            <div class="col-md-6">
            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'assesment_pelayanan')), '' , 'assesmentPel', 'assesmentPel', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="center">
            <button type="submit" name="submit" value="sep_only" class="btn btn-xs btn-success" style="height: 30px !important; font-size: 14px">
                <i class="ace-icon fa fa-globe icon-on-right bigger-110"></i>
                Terbitkan SEP
            </button>
        </div>

    </div>

    <div id="show-sep-from-response" style="display: none" class="cener">
        <p style="font-size: 20px; font-weight: bold" id="txt-no-sep">NOMOR SEP</p>
    </div>

    <hr>
        
    <p><b><i class="fa fa-edit"></i> PENDAFTARAN RAWAT JALAN </b></p>

    <input name="current_day" id="current_day" class="form-control" type="hidden" value="<?php echo $this->tanggal->gethari(date('D'))?>">

    <div class="form-group">
        <label class="control-label col-sm-3">*Klinik</label>
        <div class="col-sm-9">
            <?php echo $this->master->get_change($params = array('table' => 'tr_jadwal_dokter', 'id' => 'jd_kode_spesialis', 'name' => 'jd_kode_spesialis', 'where' => array()), '' , 'reg_klinik_rajal_sep', 'reg_klinik_rajal_sep', 'form-control', '', '') ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3">*Dokter</label>
        <div class="col-sm-9" id="dokter_by_klinik">
            <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array() ), '' , 'reg_dokter_rajal_sep', 'reg_dokter_rajal_sep', 'form-control', '', '') ?>
            <input name="jd_id" id="jd_id" class="form-control" type="hidden">
        </div>

    </div>

    <!-- hidden kuota dr -->
    <input type="hidden" name="sisa_kuota" id="sisa_kuota" readonly>
    <div class="form-group">
            <div id="message_for_kuota" style="margin-left: 7px"></div>
            <div id="message_for_kuota_null" style="margin-left: 7px"></div>
        </div>
    </div>

    <div id="message-result"></div>
    
    <hr>

    <div class="form-group center">

        <div class="col-sm-12 no-padding" style="padding-top: 10px">

            <button type="submit" name="submit" value="register_n_sep" class="btn btn-xs btn-primary" style="height: 30px !important; font-size: 14px">

                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>

                Proses Pendaftaran Pasien dan SEP

            </button>

        </div>

    </div>
    
    

</div>