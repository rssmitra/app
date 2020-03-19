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

<p><b><i class="fa fa-edit"></i> PERJANJIAN PENUNJANG MEDIS </b></p>

<div class="form-group">

    <label class="control-label col-sm-2">Tanggal Perjanjian</label>
  
    <div class="col-md-3">
        
        <div class="input-group">
            
            <input name="tanggal_perjanjian_pm" id="tanggal_perjanjian_pm" value="" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text">
            <span class="input-group-addon">
            
            <i class="ace-icon fa fa-calendar"></i>
            
            </span>
        </div>
    
    </div>

</div>

<div class="form-group">

      <label class="control-label col-sm-2" for="Province">*Penunjang Medis</label>

      <div class="col-sm-3">

          <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1, 'validasi' => '0500')), '' , 'pm_tujuan', 'pm_tujuan', 'form-control', '', '') ?>

      </div>

</div>


<div class="form-actions center">
    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Close</button>
    <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-primary">
        <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
        Submit
    </button>
</div>




