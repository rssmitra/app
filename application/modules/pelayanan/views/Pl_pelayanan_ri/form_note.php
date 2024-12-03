
<style>
.kbw-signature { width: 100%; height: 450px; border: 0px }
audio, canvas, progress, video {
    border: 0px solid white !important;
}
</style>
<script src="<?php echo base_url()?>assets/jSignature/js/jquery.signature.js"></script>
<script>
$(function() {
  var sig = $('#drawing_content').signature({thickness: 4});

	$('#clear').click(function() {
		sig.signature('clear');
  });
  
  $('#save_image').click(function() {
    $('#paramsSignature').val(sig.signature('toDataURL', 'image/png', 1));
  });
  
});
</script>
</head>
<body>

<div class="form-group">
    <label class="control-label col-sm-2">Dibuat oleh</label>
    <div class="col-md-10">
      <div class="radio">
          <label>
            <input name="created_by" type="radio" class="ace" value="perawat" checked="checked"  />
            <span class="lbl"> Perawat</span>
          </label>

          <label>
            <input name="created_by" type="radio" class="ace" value="dokter"/>
            <span class="lbl"> Dokter</span>
          </label>
      </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2">Nama</label>
    <div class="col-md-3">
      <input type="text" class="form-control" name="created_name" value="<?php echo $this->session->userdata('user')->fullname?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2">Jenis Catatan</label>
    <div class="col-md-10">
      <div class="radio">
          <label>
            <input name="note_type" type="radio" class="ace" value="perawat" checked="checked"  />
            <span class="lbl"> Perkembangan Pasien</span>
          </label>

          <label>
            <input name="note_type" type="radio" class="ace" value="dokter"/>
            <span class="lbl"> Lainnya</span>
          </label>
      </div>
    </div>
</div>
<hr>
<span style="font-style: italic">Make your drawings or notes here</span>

<div id="drawing_content" style="min-height: 700px !important"></div>

<label>Signature code : </label><br>
<input type="text" value="" name="paramsSignature" class="form-control" id="paramsSignature" style="width: 100%; margin-bottom: 10px">

<p style="clear: both;" class="center">
	<!-- <button type="button" id="disable">Disable</button>  -->
	<button type="button" id="clear" class="btn btn-xs btn-danger"><i class="fa fa-undo"></i> Clear Image</button> 
	<!-- <button type="button" id="json">To JSON</button>
	<button type="button" id="svg">To SVG</button> -->
	<button type="button" id="save_image" class="btn btn-xs btn-success"><i class="fa fa-save"></i> Save Image</button>
</p>

<!-- hidden form -->
<input type="hidden" value="<?php echo isset($value)?$value->no_mr:''?>" name="no_mr_notes" id="no_mr_notes">

      

    






<!-- end form create SEP