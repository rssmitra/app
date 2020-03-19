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

function preventDefault(e) {
  e = e || window.event;
  if (e.preventDefault)
      e.preventDefault();
  e.returnValue = false;  
}

    $('#inputKeyTindakanBedah').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/References/getTindakanBedah",
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
            $('#perjanjian_tindakan_bedah').val(val_item);
            $('#inputKeyTindakanBedah').val(label_item);
        }

    });


</script>

<p><b><i class="fa fa-edit"></i> PERJANJIAN BEDAH </b></p>

<div class="form-group">

    <label class="control-label col-sm-2">Tanggal Perjanjian</label>
  
    <div class="col-md-3">
        
        <div class="input-group">
            
            <input name="tanggal_perjanjian_bedah" id="tanggal_perjanjian_bedah" value="" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text">
            <span class="input-group-addon">
            
            <i class="ace-icon fa fa-calendar"></i>
            
            </span>
        </div>
    
    </div>

    <label class="control-label col-sm-2">Jam</label>
  
    <div class="col-md-3">
        
        <div class="input-group">
            <input name="selected_time" id="selected_time" placeholder="" class="form-control" type="text" >
        </div>
    
    </div>

</div>

<div class="form-group">

    <label class="control-label col-sm-2" for="City">*Nama Tindakan</label>

    <div class="col-sm-4">

        <input id="inputKeyTindakanBedah" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />

        <input type="hidden" name="perjanjian_tindakan_bedah" id="perjanjian_tindakan_bedah" class="form-control">

    </div>

    <label class="control-label col-sm-2" for="City">*Dokter</label>

    <div class="col-sm-4">

        <?php echo $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array('kd_bagian' => '030901')), '' , 'dokter_rajal', 'dokter_rajal', 'form-control', '', '') ?>

    </div>

</div>

<div class="form-group">

    <label class="control-label col-sm-2" for="City">*Diagnosa</label>

    <div class="col-sm-4">

        <textarea name="diagnosa_perjanjian_bedah" class="form-control" style="height:50px !important"></textarea>

    </div>

</div>

<div class="form-actions center">
    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Close</button>
    <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-primary">
        <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
        Submit
    </button>
</div>




