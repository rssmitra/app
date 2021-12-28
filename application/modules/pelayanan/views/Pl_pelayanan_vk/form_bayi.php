<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />

<script type="text/javascript">
  
  jQuery(function($) {  

    $('.date-picker').datepicker({    
      autoclose: true,    
      todayHighlight: true,
      format: 'yyyy-mm-dd'
    })  

    //show datepicker when clicking on the icon

    .next().on(ace.click_event, function(){    

      $(this).prev().focus();    

    });  

    $('.timepicker').timepicker({
        minuteStep: 1,
        showSeconds: true,
        showMeridian: false,
        disableFocus: true,
        icons: {
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down'
        }
    }).on('focus', function() {
        $('.timepicker').timepicker('showWidget');
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
    

  });

  $('#InputKeyDokterBagian').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getAllDokter",
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
        $('#InputKeyDokterBagian').val(label_item);
        $('#dokter_penolong').val(val_item);
      }

  });

</script>

<p><b><i class="fa fa-edit"></i> DATA KELAHIRAN BAYI BARU LAHIR </b></p>

<!-- hidden -->
<input type="hidden" class="form-control" name="no_mr_ibu" value="<?php echo isset($orgtuaby->no_mr)?$orgtuaby->no_mr:''?>">
<input type="hidden" class="form-control" name="nama_ibu_kandung" value="<?php echo isset($orgtuaby->nama_pasien)?$orgtuaby->nama_pasien:''?>">
<input type="hidden" class="form-control" name="id_bayi" value="<?php echo isset($value->id_bayi)?$value->id_bayi:0?>">

<div class="form-group">
    <label class="control-label col-sm-2" for="">Nama Bayi</label>
    <div class="col-sm-4">
       <input type="text" class="form-control" name="nama_bayi" value="<?php echo isset($value->nama_bayi)?strtoupper($value->nama_bayi):'BY NY. '.strtoupper($orgtuaby->nama_pasien).''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Jenis Kelamin</label>
    <div class="col-sm-2">
    <?php echo $this->master->custom_selection($params = array('table' => 'mst_gender', 'id' => 'gender_id', 'name' => 'gender_name', 'where' => array()), isset($value->jenis_kelamin)?($value->jenis_kelamin=='L')?1:2:'' , 'jenis_kelamin', 'jenis_kelamin', 'form-control', '', '') ?> 
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Berat Badan (Gram)</label>
    <div class="col-sm-1">
       <input type="text" class="form-control" name="berat_badan" value="<?php echo isset($value->berat_badan)?$value->berat_badan:''?>">
    </div>
    <label class="control-label col-sm-2" for="">Panjang Badan (Cm)</label>
    <div class="col-sm-1">
       <input type="text" class="form-control" name="panjang_badan" value="<?php echo isset($value->panjang_badan)?$value->panjang_badan:''?>">
    </div>
</div>

<div class="form-group">
    
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Nilai APGAR</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="apgar" value="<?php echo isset($value->apgar)?$value->apgar:''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Anus</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="pl_anamnesa" value="<?php echo isset($riwayat->anamnesa)?$riwayat->anamnesa:''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">No Gelang</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="anus" value="<?php echo isset($value->anus)?$value->anus:''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Tempat Lahir</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="tempat_lahir" value="<?php echo isset($value->tempat_lahir)?$value->tempat_lahir:'JAKARTA SELATAN'?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Tanggal Lahir</label>
    <div class="col-sm-2">
      <div class="input-group"> 
          <input name="tgl_jam_lahir" id="tgl_jam_lahir" value="<?php echo isset($value->tgl_jam_lahir)?$this->tanggal->sqlDateTimeToDate($value->tgl_jam_lahir):''?>"  class="form-control date-picker" type="text">
          <span class="input-group-addon">
          <i class="ace-icon fa fa-calendar"></i>
          </span>
      </div>
    </div>
    <label class="control-label col-sm-1" for="">Jam</label>
    <div class="col-sm-2">
        <div class="input-group bootstrap-timepicker">
            <input id="jam_lahir" name="jam_lahir" type="text" class="timepicker form-control" value="<?php echo isset($value->tgl_jam_lahir)?$this->tanggal->formatDateTimeToTime($value->tgl_jam_lahir):''?>">
            <span class="input-group-addon">
                <i class="fa fa-clock-o bigger-110"></i>
            </span>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Dokter/Bidan Penolong</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="InputKeyDokterBagian" name="dokter_penolong_nama" placeholder="Masukan Keyword Nama Dokter" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:''?>">
        <input type="hidden" class="form-control" id="dokter_penolong" name="dokter_penolong" value="<?php echo isset($value->dokter_penolong)?$value->dokter_penolong:''?>" >
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">&nbsp;</label>
    <div class="col-sm-4" style="margin-left:6px">
       <button type="submit" class="btn btn-xs btn-primary" id="btn_save_data"> <i class="fa fa-save"></i> Simpan Data </button>
    </div>
</div>