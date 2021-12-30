<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>
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

$(document).ready(function(){
  
    $('#form_role').ajaxForm({
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
          $('#page-area-content').load('inventory/distribusi/permintaan_unit?_=' + (new Date()).getTime());
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    }); 
})

$('#inputItem').typeahead({
    source: function (query, result) {
			console.log(query)
        $.ajax({
            url: "Templates/References/getItem",
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
      var val_item=item.split(':')[0];
      //console.log(val_item);
      $('#permintaan_nm_item').val(val_item);

      if (val_item) { 
        $.getJSON("<?php echo site_url('Templates/References/getDataItem') ?>/" + val_item, '', function (data) {                   

          $('<option value="">-Pilih Satuan-</option>').appendTo($('#insertSatuan'));                

          $.each(data, function (i, o) {                  

              $('#input_item_satuan').val(o.satuan_kecil)  

          });        

        }); 
      }
    }
});

if($("#jmlcell").val()==''){
  var i=0;
}else{
  var i = $("#jmlcell").val()
}


function addRecords() {

	if ($('#inputItem').val() == '' || $('#input_item_satuan').val() == '' || $('#input_item_jumlah').val() == '' ){
		//document.getElementById("alert").innerHTML = "*Please Fill All Required Field";
		achtungCreate("*Please Fill All Required Field",false)
		return ok;
	} else if ($('#input_item_jumlah').val() == 0 ){
		achtungCreate("Jumlah Tidak boleh 0",false)
		return ok;
	} else {

		$("#record").find('input[id="permintaan_nm_kode_brg"]').each(function(){
				if($(this).val() == $('#inputItem').val()){
					achtungCreate("Barang Sudah ada dalam list",false);
          $('#table_input').find('input').val('');
	        $('#table_input').find('select').val('');
					return ok;
          
				}
		});

		$('<tr><td width="50%"><input name="permintaan_nm_kode_brg'+i+'" class="form-control" value="'+$('#inputItem').val()+'" id="permintaan_nm_kode_brg" size="30" readonly="readonly"></td><td width="20%"><input type="text" name="permintaan_nm_satuan'+i+'" class="form-control" value="'+$('#input_item_satuan').val()+'" readonly="readonly"></td><td width="10%"><input type="number" name="permintaan_nm_jumlah'+i+'" class="form-control" value="'+$('#input_item_jumlah').val()+'" readonly="readonly"></td><td width="10%" align="center"><input type="checkbox" name="chk'+i+'" id="chk" value="Y"></td></tr>').appendTo($('#result'));                    

		$("#jmlcell").val(i);
		i++;

	}

  $('#remove_row').show('fast');

	$('#table_input').find('input').val('');
	$('#table_input').find('select').val('');

}

function removeRow(){
	$("#record").find('input[id="chk"]').each(function(){
					if($(this).is(":checked")){
							$(this).parents("tr").remove();
					}
			});
}

</script>

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
              <form class="form-horizontal" method="post" id="form_role" action="<?php echo site_url('inventory/distribusi/permintaan_unit/process')?>" enctype="multipart/form-data">
                <br>

                <input name="id" id="id" value="<?php echo isset($value)?$value->id_tc_permintaan_inst:0?>" placeholder="Auto" class="form-control" type="hidden" readonly>
                  
                <div class="form-group">
                  <label class="control-label col-md-2">Nomor Permintaan</label>
                  <div class="col-md-3">            

                    <input type="text" name="no" id="no" class="form-control" value="<?php echo isset($value)?$value->nomor_permintaan:"Auto"?>" placeholder="Auto" readonly>
                    <input type="hidden" name="permintaan_nm_no" id="permintaan_nm_no" class="form-control" value="<?php echo isset($value)?$value->nomor_permintaan:$no_permintaan?>" readonly>

                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Bagian / Depo</label>
                  <div class="col-md-3">
                    <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1)), isset($value)?$value->kode_bagian_minta:'' , 'permintaan_nm_bagian_minta', 'permintaan_nm_bagian_minta', 'form-control', '', '') ?> 
                  </div>
                </div>

                <div class="form-group" >

                  <label class="control-label col-md-2">Tanggal Permintaan</label>

                  <div class="col-md-2">
                      
                      <div class="input-group">
                          
                          <input name="permintaan_nm_tanggal" id="permintaan_nm_tanggal" value="<?php echo isset($value)?$this->tanggal->formatDateForm($value->tgl_permintaan):date('m/d/Y')?>"  class="form-control date-picker" type="text">
                          <span class="input-group-addon">
                          
                          <i class="ace-icon fa fa-calendar"></i>
                          
                          </span>
                      </div>

                  </div>

                </div>

                <table id="table_input" width='100%' style="margin-left:5px;">
                  <thead> 
                    <tr>
                      <td style='color:white;background-color:#001f3f;text-align:left;padding-left:5px;' colspan='6'>Pilih Barang</td>
                    </tr>
                    <tr>
                      <td width='50%' style="padding-left:5px">Nama Barang</td>
                      <td width='20%' style="padding-left:5px">Satuan*</td>
                      <td width='10%' style="padding-left:5px">Jumlah</td>
                      <td width='10%' style="padding-left:5px"></td>
                    <tr>
                  </thead>
                  <tbody style="<?php echo ($flag=='read')?'display:none':''?>">
                    <tr>
                      <td>
                        <input id="inputItem" class="form-control" name="inputItem" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" <?php echo ($flag=='read')?'readonly':''?>/>
                        <input type="hidden" name="permintaan_nm_item" value="" id="permintaan_nm_item">
                      </td>
                      <td>     
                        <input class="form-control" type='text' name='input_item_satuan' id='input_item_satuan' <?php echo ($flag=='read')?'readonly':''?>>  
                      </td>
                      <td>      
                        <input class="form-control" type='number' placeholder='0' name='input_item_jumlah' id='input_item_jumlah' <?php echo ($flag=='read')?'readonly':''?>>  
                      </td>
                      <td >
                        <button type="button" class="btn btn-primary btn-sm" onclick="<?php echo ($flag!='read')?'addRecords()':''?>">
                          <span class="ace-icon fa fa-plus icon-on-right bigger-110"></span>
                          Tambah
                        </button>
                        <!--<a class='button3' onclick="<?php //echo ($flag!='read')?'addRecords()':''?>" style="margin-left:40px;"><i class='fa fa-plus-circle'></i> Insert</a>-->
                      </td>
                    </tr>
                    <tr id="remove_row" style="<?php echo ($flag!='update')?'display:none':''?>">
                      <td colspan='6' style="text-align:left">
                      <button  style="margin-left:0;margin-top:4px;" type="button" class="btn btn-danger btn-sm" onclick="removeRow()">
                          <span class="ace-icon fa fa-remove icon-on-right bigger-110"></span>
                          Remove
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>

                <table width='100%' id='record' style="margin-left:5px">
                  <input type='hidden' name='jmlcell' id='jmlcell' value='<?php echo isset($value_numrow)?$value_numrow:''?>'>
                  
                  <tbody id="result">
                    <?php if(isset($value_detail)){$i=0; foreach($value_detail as $key_row=>$rows_m) :?>
                      <tr>
                        <td width="50%"><input name="permintaan_nm_kode_brg<?php echo $i?>" class="form-control" value="<?php echo ''.$rows_m->kode_brg.' : '.$rows_m->nama_brg.'' ?>"  id="permintaan_nm_kode_brg" size="30" readonly="readonly"></td>
                        <td width="20%"><input type="text" name="permintaan_nm_satuan<?php echo $i?>" class="form-control" value="<?php echo $rows_m->satuan ?>" readonly="readonly"></td>
                        <td width="10%"><input type="text" name="permintaan_nm_jumlah<?php echo $i?>" class="form-control" value="<?php echo $rows_m->jumlah_permintaan ?>" readonly="readonly"></td>
                        <td width="10%" align="center"><input type="checkbox" name="chk<?php echo $i?>" id="chk" value="Y"></td>
                      </tr>
                    <?php $i++; endforeach; }?>
                  </tbody>
                </table>

                <div class="form-actions center">

                  
                  <a onclick="getMenu('inventory/distribusi/permintaan_unit')" href="#" class="btn btn-sm btn-success">
                    <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                    Kembali ke daftar
                  </a>
                  <?php if($flag != 'read'):?>
                  <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                    <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                    Reset
                  </button>
                  <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Submit
                  </button>
                <?php endif; ?>
                </div>
              </form>
            </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


