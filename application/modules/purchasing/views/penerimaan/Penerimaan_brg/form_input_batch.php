<script type="text/javascript" src="<?php echo base_url()?>assets/jQuery-Scanner/jquery.scannerdetection.js"></script>

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

$(document).scannerDetection({
	timeBeforeScanTest: 200, // wait for the next character for upto 200ms
	startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
	endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
	avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
	onComplete: function(barcode, qty){ 
    return false;
   } // main callback function	
});

$(document).ready(function(){

    $('#form_input_batch').ajaxForm({
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
          get_barang_po();
          $('#globalModalView').modal('hide');
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

    $( "#kode_box" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
           $('#kode_pcs').focus();       
          }         
          return false;                
        }       
    }); 

    $( "#kode_pcs" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
           $('#jml_diterima').focus();       
          }         
          return false;                
        }       
    }); 

    $( "#jml_diterima" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
           $('#btnSaveInputbatch').click();       
          }         
          return false;                
        }       
    });

    $('#no_expired').click(function (e) {   
      if (($(this).is(':checked'))) {
        $('#tgl_expired').val('');
      }  else{
        $('#tgl_expired').val(getExpiredDate());
      }
    });


})

function getExpiredDate(){

  var d = new Date();
  var year = d.getFullYear();
  var month = d.getMonth();
  var day = d.getDate();

  var fulldate = new Date(year + 1, month, day);

  var toDate = fulldate.toISOString().slice(0, 10);

  return toDate;
  
}

</script>

<div class="row">

  <div class="col-xs-2 center">
    <?php
      $link_image = ( $value->path_image != NULL ) ? PATH_IMG_MST_BRG.$value->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ;
    ?>
    <a href="<?php echo base_url().$link_image; ?>" target="_blank"><img src="<?php echo base_url().$link_image; ?>" width="100%"></a><br>
    <?php echo '<span style="font-size: 13px"><b>'.$value->kode_brg.'<br>'.$value->nama_brg.'</b></span>'; ?>

  </div>
  <div class="col-xs-10">
    <!-- PAGE CONTENT BEGINS -->
      <div class="widget-body">
        <div class="widget-main no-padding">
         
          <form class="form-horizontal" method="post" id="form_input_batch" action="<?php echo site_url('purchasing/penerimaan/Penerimaan_brg/process_input_batch')?>" enctype="multipart/form-data" style="margin-top: -10px" autocomplete="off">

            

            <?php if(isset($penerimaan)) :?>

            <!-- hidden form -->
            <input type="hidden" name="flag" value="<?php echo $flag?>">
            <input type="hidden" name="id_tc_po_det" value="<?php echo $id_tc_po_det?>">
            <input type="hidden" name="id_penerimaan" value="<?php echo $penerimaan->id_penerimaan?>">
            <input type="hidden" name="kode_brg" value="<?php echo $value->kode_brg?>">
            <input type="hidden" name="nama_brg" value="<?php echo $value->nama_brg?>">
            <input type="hidden" name="satuan_besar" value="<?php echo $value->satuan_besar?>">
            <input type="hidden" name="satuan_kecil" value="<?php echo $value->satuan_kecil?>">
            <input type="hidden" name="rasio" value="<?php echo $value->content?>">

            <div class="form-group">
              <label class="control-label col-md-3">ID</label>
              <div class="col-md-2">
                <input name="id_tc_batch_log" id="id_tc_batch_log" value="<?php echo isset($value->id_tc_batch_log)?$value->id_tc_batch_log : ''?>" class="form-control" type="text" placeholder="Auto" readonly>
              </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-3">Tanggal Kadaluarsa</label>
              <div class="col-md-3">
                <div class="input-group">
                  <input class="form-control date-picker" name="tgl_expired" id="tgl_expired" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($value->tgl_expired)?$value->tgl_expired :  date('Y-m-d', strtotime('+1 year'))?>"/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" class="ace" name="no_expired" value="N" id="no_expired">
                    <span class="lbl"> Tidak ada Expired</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">No. Batch</label>
              <div class="col-md-3">
                <input type="text" class="form-control" name="no_batch" value="<?php echo isset($value->no_batch)?$value->no_batch : ''?>">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Scan Kemasan Besar</label>
              <div class="col-md-4">
                <input type="text" class="form-control" name="kode_box" id="kode_box" value="<?php echo isset($value->kode_box)?$value->kode_box : ''?>">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Scan Kemasan Kecil</label>
              <div class="col-md-4">
                <input type="text" class="form-control" name="kode_pcs" id="kode_pcs" value="<?php echo isset($value->kode_pcs)?$value->kode_pcs : ''?>">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Jumlah diterima</label>
              <div class="col-md-2">
                <input type="text" class="form-control" name="jml_diterima" id="jml_diterima" value="<?php echo isset($value->jml_diterima)?$value->jml_diterima : ''?>">
              </div>
              <div class="col-md-3 no-padding" style="margin-left: -8px">
                <select name="satuan_brg" id="satuan_brg">
                  <option value="satuan_besar" <?php echo isset($value->satuan_besar)?($value->satuan_besar=='satuan_besar')?'selected':'' : ''?> >Satuan Besar (<?php echo $value->satuan_besar?>)</option>
                  <option value="satuan_kecil" <?php echo isset($value->satuan_besar)?($value->satuan_besar=='satuan_kecil')?'selected':'' : ''?>>Satuan Kecil (<?php echo $value->satuan_kecil?>)</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3">&nbsp;</label>
              <div class="col-md-5" style="padding-top: 0px; margin-left: 7px">
              <button type="submit" id="btnSaveInputbatch" name="submit" value="input_batch" class="btn btn-xs btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
              </div>
            </div>
            <?php else: echo '<div class="alert alert-danger"><strong>Peringatan !</strong> Silahkan mengisi form penerimaan barang terlebih dahulu.</div>'; endif; ?>
            

          </form>

        </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


