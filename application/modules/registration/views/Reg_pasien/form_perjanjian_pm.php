<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
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

$('select[name="pm_tujuan"]').change(function () {      

    if ($(this).val() == '050201') {    
        $('#div_tindakan_radiologi').show();
        $('#bulan_perjanjian').show();
        $('#tanggal_perjanjian').hide();
    } else {     
        $('#div_tindakan_radiologi').hide();
        $('#bulan_perjanjian').hide();
        $('#tanggal_perjanjian').show();
    }        

});

$('#InputKeyTindakanPm').typeahead({
    source: function (query, result) {
        $.ajax({
            url: "templates/references/getTindakanByBagianAutoComplete",
            data: { keyword:query, kode_klas: 16, kode_bag : $('#pm_tujuan').val(), kode_perusahaan : $('input[name=jenis_tarif]:checked').val() },            
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
        $('#perjanjian_tindakan_pm').val(val_item);
    }

});

$('select[name="pm_tujuan"]').change(function () {      

    if ($(this).val()) {          

        $.getJSON("<?php echo site_url('Templates/References/getDokterByBagian_') ?>/" + $(this).val() , function (data) {              

            $('#dokter_rajal option').remove();                

            $('<option value="">-Pilih Dokter-</option>').appendTo($('#dokter_rajal'));                

            $.each(data, function (i, o) {                  

                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#dokter_rajal'));                    

            });                

        });            

    } else {          

        $('#dokter option').remove()            

    }        

}); 

</script>

<p><b><i class="fa fa-edit"></i> PERJANJIAN PENUNJANG MEDIS </b></p>

<div class="form-group">
    <label class="control-label col-sm-2" for="Province">*Penunjang Medis</label>
    <div class="col-sm-3">
        <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1, 'validasi' => '0500')), '050201' , 'pm_tujuan', 'pm_tujuan', 'form-control', '', '') ?>
    </div>
</div>

<div class="form-group" id="tanggal_perjanjian" style="display:none">
    <label class="control-label col-sm-2">Tanggal Perjanjian</label>  
    <div class="col-md-2">
        <div class="input-group">
            <input name="tanggal_perjanjian_pm" id="tanggal_perjanjian_pm" value="" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text">
            <span class="input-group-addon">
            <i class="ace-icon fa fa-calendar"></i>
            </span>
        </div>
    </div>
</div>

<div class="form-group" id="bulan_perjanjian">
    <label class="control-label col-sm-2">Kunjungan Bulan</label>  
    <div class="col-md-2">
        <?php echo $this->master->get_bulan(date('m'),'bulan_kunjungan','bulan_kunjungan','form-control','','')?>
    </div>
</div>

<div class="form-group" id="div_tindakan_radiologi">
    <label class="control-label col-sm-2" for="">Nama Tindakan</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" id="InputKeyTindakanPm" name="pl_nama_tindakan" placeholder="Masukan Keyword Tindakan">
        <input type="hidden" class="form-control" id="perjanjian_tindakan_pm" name="perjanjian_tindakan_pm" >
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2">*Dokter</label>
    <div class="col-md-4">
        <?php echo $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array('kd_bagian' => '050201', 'status' => 0)), '' , 'dokter_rajal', 'dokter_rajal', 'form-control', '', '') ?>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2">Keterangan</label>
    <div class="col-md-5">
    <textarea class="form-control" name="keterangan" style="height:50px !important"></textarea>
</div>

<div class="clearfix"></div>
<div class="form-actions center">
    <button type="button" onclick="getMenu('registration/Input_perjanjian_pm')" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Close</button>
    <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-primary">
        <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
        Submit
    </button>
</div>




