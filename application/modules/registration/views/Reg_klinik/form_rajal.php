<script type="text/javascript">

$(document).ready(function(){

    current_day = $('#current_day').val();

    $.getJSON("<?php echo site_url('Templates/References/getKlinikFromJadwal') ?>/" +current_day, '', function (data) {              
        $('#reg_klinik_rajal option').remove();  
        $('<option value="">-Pilih Klinik-</option>').appendTo($('#reg_klinik_rajal'));
        $.each(data, function (i, o) {                  
            $('<option value="' + o.kode_bagian + '">' + o.nama_bagian + '</option>').appendTo($('#reg_klinik_rajal'));                    
            
        });      


    });            
    
})

$('select[name="reg_klinik_rajal"]').change(function () {  
    /*current day*/
    current_day = $('#current_day').val();
    if ($(this).val() != '012801') {     
        $('#reg_dokter_rajal').attr('name', 'reg_dokter_rajal');
        $('#reg_dokter_rajal_dinamis').attr('name', 'reg_dokter_rajal_');
        $('#dokter_dinamis_klinik').hide('fast')
        $('#dokter_by_klinik').show('fast')
        $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialisFromJadwal') ?>/" + $(this).val() + '/' +current_day, '', function (data) {   
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
}); 

$('select[id="reg_dokter_rajal"]').change(function () {      

    if ($(this).val()) {          

        $.getJSON("<?php echo site_url('Templates/References/getKuotaDokter') ?>/" + $(this).val() + '/' +$('select[name="reg_klinik_rajal"]').val() , '', function (data) {              

            $('#sisa_kuota').val(data.sisa_kuota);              
            $('#message_for_kuota').html(data.message);              
            $('#jd_id').val(data.jd_id); 
        });            
        
        $('#title-select-dokter').text( $('#reg_dokter_rajal option:selected').text() );

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


</script>

<p><b><i class="fa fa-edit"></i> PENDAFTARAN RAWAT JALAN </b></p>

<input name="current_day" id="current_day" class="form-control" type="hidden" value="<?php echo $this->tanggal->gethari(date('D'))?>">

<div class="form-group">
    <label class="control-label col-sm-3">*Klinik</label>
    <div class="col-sm-9">
        <?php echo $this->master->get_change($params = array('table' => 'tr_jadwal_dokter', 'id' => 'jd_kode_spesialis', 'name' => 'jd_kode_spesialis', 'where' => array()), '' , 'reg_klinik_rajal', 'reg_klinik_rajal', 'form-control', '', '') ?>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-3">*Dokter</label>
    <div class="col-sm-9" id="dokter_by_klinik">
        <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array() ), '' , 'reg_dokter_rajal', 'reg_dokter_rajal', 'form-control', '', '') ?>
        <input name="jd_id" id="jd_id" class="form-control" type="hidden">
    </div>

    <div class="col-sm-8" id="dokter_dinamis_klinik" style="display:none;">
        <input id="inputDokter" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />
        <input type="hidden" name="" id="reg_dokter_rajal_dinamis" class="form-control">
    </div>
</div>

<?php if(isset($id_tc_pesanan) && $id_tc_pesanan == '') :?>
<!-- hidden kuota dr -->
<input type="hidden" name="sisa_kuota" id="sisa_kuota" readonly>
<div class="form-group">
        <div id="message_for_kuota" style="margin-left: 7px"></div>
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


