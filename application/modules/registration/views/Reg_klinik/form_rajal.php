<script type="text/javascript">

$(document).ready(function(){

    getKlinikByJadwalDefault();          
    
})

$('select[name="reg_klinik_rajal"]').change(function () {  

    if (($('#show_all_poli').is(':checked'))) {
        var url_get_dokter = '<?php echo site_url('Templates/References/getDokterBySpesialis/')?>'+$(this).val()+'/'+current_day+'';
    }else{
        var url_get_dokter = '<?php echo site_url('Templates/References/getDokterBySpesialisFromJadwal/')?>'+$(this).val()+'/'+current_day+'/'+$('#tgl_registrasi').val()+'';
    }

    /*current day*/
    current_day = $('#current_day').val();
    if ($(this).val() != '012801') {     
        $('#reg_dokter_rajal').attr('name', 'reg_dokter_rajal');
        $('#reg_dokter_rajal_dinamis').attr('name', 'reg_dokter_rajal_');
        $('#dokter_dinamis_klinik').hide('fast')
        $('#dokter_by_klinik').show('fast')
        $.getJSON(url_get_dokter, '', function (data) {   
            $('#reg_dokter_rajal option').remove();         
            $('<option value="">-Pilih Dokter-</option>').appendTo($('#reg_dokter_rajal'));  
            $.each(data, function (i, o) {   
                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#reg_dokter_rajal'));  
            });   
        });   
    } else {    
        $('#reg_dokter_rajal option').remove()  
        $('#reg_dokter_rajal_dinamis').attr('name', 'reg_dokter_rajal');
        $('#reg_dokter_rajal').attr('name', 'reg_dokter_rajal_');
        $('#dokter_by_klinik').hide('fast')
        $('#dokter_dinamis_klinik').show('fast')
    } 
    
    // title
    $('#title-select-klinik').text( $('#reg_klinik_rajal option:selected').text().toUpperCase() );
    $('#reg_klinik_rajal_txt').val( $('#reg_klinik_rajal option:selected').text().toUpperCase() );
}); 

$('select[id="reg_dokter_rajal"]').change(function () {      

    if ($(this).val()) {          

        if (($('#show_all_poli').is(':checked'))) {
            return false;
        }else{
            $.getJSON("<?php echo site_url('Templates/References/getKuotaDokter') ?>/" + $(this).val() + '/' +$('select[name="reg_klinik_rajal"]').val()+'/'+$('#tgl_registrasi').val() , '', function (data) {  

                var objData = data.data;
                $('#kuotadr').val(objData.kuota); 
                $('#sisa_kuota').val(data.sisa_kuota); 
                $('#kode_dokter_bpjs').val(objData.kode_dokter_bpjs); 
                $('#reg_dokter_rajal_txt').val( $('#reg_dokter_rajal option:selected').text().toUpperCase() );
                $('#kode_poli_bpjs').val(objData.kode_poli_bpjs); 
                $('#jam_praktek_mulai').val(objData.jam_praktek_mulai); 
                $('#jam_praktek_selesai').val(objData.jam_praktek_selesai); 

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

            $('#title-select-dokter').text( $('#reg_dokter_rajal option:selected').text() );
        }

    }      

});  

$('#inputDokter').typeahead({
    source: function (query, result) {
            $.ajax({
                url: "templates/references/getDokterByBagian",
                data: 'keyword=' + query + '&bag=' + $('#reg_klinik_rajal').val(),         
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
        $('#reg_dokter_rajal_dinamis').val(val_item);
        
    }
});

$('#show_all_poli').click(function (e) {   
    if (($(this).is(':checked'))) {
    
        // show all poli
        $.getJSON("<?php echo site_url('Templates/References/getSelectSpesialis') ?>", '', function (data) {              
            $('#reg_klinik_rajal option').remove();  
            $('<option value="">-Pilih Klinik-</option>').appendTo($('#reg_klinik_rajal'));
            $.each(data, function (i, o) {                  
                $('<option value="' + o.kode_bagian + '">' + o.nama_bagian + '</option>').appendTo($('#reg_klinik_rajal'));                    
            });     
        });  

    }  else{
        
        getKlinikByJadwalDefault();

    }
});

function getKlinikByJadwalDefault(){
    date = $('#tgl_registrasi').val();
    days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    var d = new Date(date);
    current_day = days[d.getDay()]; 

    $.getJSON("<?php echo site_url('Templates/References/getKlinikFromJadwal') ?>/" +current_day+'/'+date, '', function (data) {              
        $('#reg_klinik_rajal option').remove();  
        $('<option value="">-Pilih Klinik-</option>').appendTo($('#reg_klinik_rajal'));
        $.each(data, function (i, o) {                  
            $('<option value="' + o.kode_bagian + '">' + o.nama_bagian + '</option>').appendTo($('#reg_klinik_rajal'));                    
        });     
    });  
}

</script>

<p><b><i class="fa fa-edit"></i> PENDAFTARAN RAWAT JALAN </b></p>

<input name="current_day" id="current_day" class="form-control" type="hidden" value="<?php echo $this->tanggal->gethari(date('D'))?>">

<div class="checkbox">
    <label>
        <input name="form-field-checkbox" type="checkbox" class="ace" value="Y" id="show_all_poli">
        <span class="lbl"> Tampilkan semua poli.</span>
    </label>
</div>

<div class="form-group">
    <label class="control-label col-sm-3">*Klinik</label>
    <div class="col-sm-9">
        <?php echo $this->master->get_change($params = array('table' => 'tr_jadwal_dokter', 'id' => 'jd_kode_spesialis', 'name' => 'jd_kode_spesialis', 'where' => array()), '' , 'reg_klinik_rajal', 'reg_klinik_rajal', 'form-control', '', '') ?>
        <input type="hidden" name="kode_poli_bpjs" id="kode_poli_bpjs" class="form-control">
        <input type="hidden" name="reg_klinik_rajal_txt" id="reg_klinik_rajal_txt" class="form-control">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-3">*Dokter</label>
    <div class="col-sm-9" id="dokter_by_klinik">
        <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array() ), '' , 'reg_dokter_rajal', 'reg_dokter_rajal', 'form-control', '', '') ?>
        <input name="jd_id" id="jd_id" class="form-control" type="hidden">
        <input name="kode_dokter_bpjs" id="kode_dokter_bpjs" class="form-control" type="hidden">
        <input name="reg_dokter_rajal_txt" id="reg_dokter_rajal_txt" class="form-control" type="hidden">
    </div>

    <div class="col-sm-8" id="dokter_dinamis_klinik" style="display:none;">
        <input id="inputDokter" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />
        <input type="hidden" name="" id="reg_dokter_rajal_dinamis" class="form-control">
        
    </div>
</div>
<input type="hidden" name="jam_praktek_mulai" id="jam_praktek_mulai" class="form-control">
<input type="hidden" name="jam_praktek_selesai" id="jam_praktek_selesai" class="form-control">
<input type="hidden" name="sisa_kuota" id="sisa_kuota" readonly>
<input type="hidden" name="kuotadr" id="kuotadr" readonly>

<?php if(isset($id_tc_pesanan) && $id_tc_pesanan == '') :?>
<!-- hidden kuota dr -->
<div class="form-group">
        <div id="message_for_kuota" style="margin-left: 7px"></div>
        <div id="message_for_kuota_null" style="margin-left: 7px"></div>
    </div>
</div>
<?php endif;?>

<?php if(isset($no_reg) && $no_reg != '') :?>
    <input type="hidden" id="no_registrasi_rujuk" name="no_registrasi_rujuk" value="<?php echo isset($no_reg)?$no_reg:''?>">
    <input type="hidden" id="asal_pasien_rujuk" name="asal_pasien_rujuk" value="<?php echo isset($bag_asal)?$bag_asal:''?>">
    <input type="hidden" id="tgl_registrasi" name="tgl_registrasi" value="<?php echo date("m/d/Y") ?>">
    <input type="hidden" id="klas" name="klas" value="<?php  echo isset($klas)?$klas:'' ?>">

    <div class="form-group">
        <div class="col-sm-2">
            <button type="submit" href="#" class="btn btn-xs btn-primary"><i class="fa fa-save"></i> Submit</button>
        </div>
    </div>

    <br>
    <script type="text/javascript">
            
        var type = '<?php echo $type ?>';
        console.log(type)
        var no_reg = '<?php echo $no_reg?>';
        var no_mr = '<?php echo $value->no_mr?>';
        if(type=='ranap'){
            $("#tabs_riwayat_kunjungan").load("registration/reg_pasien/riwayat_kunjungan_by_reg/"+no_mr+"/rajal/"+no_reg);
            reload_table();
        } 
        
    </script>

    <div class="row">
        <div class="col-sm-12 no-padding">
        <div id="tabs_riwayat_kunjungan"></div>
        </div>
    </div>
<?php endif;?>


