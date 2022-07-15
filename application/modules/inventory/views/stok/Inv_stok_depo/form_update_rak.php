<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script>
$(document).ready(function(){
  
    $('#form_update_rak').ajaxForm({
      beforeSend: function() {
        achtungShowLoader();  
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status === 200){
          $.achtung({message: jsonResponse.message, timeout:5});
          // close modal
          $('#globalModalViewSmall').modal('hide');
          // reload tabel
          reload_table();
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }
        achtungHideLoader();
      }
    }); 

})

</script>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->

    <form class="form-horizontal" method="post" id="form_update_rak" action="<?php echo site_url('inventory/stok/Inv_stok_depo/process_update_rak')?>" enctype="multipart/form-data">
      <br>
        <!-- input hiddep -->
        <input type="hidden" name="flag_string" id="flag_string" value="medis">
        <input type="hidden" name="kode_brg" id="kode_brg" value="<?php echo $kode_brg?>">
        <input type="hidden" name="kode_bagian" id="kode_bagian" value="<?php echo $kode_bagian?>">

        <div class="form-group">
          <label class="control-label col-md-2">Rak/Lemari</label>
          <div class="col-md-6">
            <?php 
                $flag_data = 'rak_medis';
                echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => $flag_data, 'is_active' => 'Y', 'reff_id' => $kode_bagian)), isset($value->rak_lemari)?$value->rak_lemari:'' , 'rak', 'rak', 'form-control', '', '') 
            ?>
          </div>
          <div class="col-md-2" style="margin-left:-1%">
              <button type="submit" class="btn btn-xs btn-primary">
                <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                Simpan Data
              </button>
            </div>
        </div>

    </form>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


