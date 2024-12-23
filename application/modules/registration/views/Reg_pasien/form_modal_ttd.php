
<style>
.kbw-signature { width: 100%; height: 450px; border: 0px }
audio, canvas, progress, video {
    border: 0px solid white !important;
}
</style>
<script src="<?php echo base_url()?>assets/jSignature/js/jquery.signature.js"></script>
<script>
$(function() {
  var sig = $('#content_drawing').signature({thickness: 4});

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
<br>
<b>Masukan Tanda Tangan : </b><br>

<div id="content_drawing"></div>
<label>Signature code : </label><br>
<input type="text" value="" name="paramsSignature" class="form-control" id="paramsSignature" style="width: 100%; margin-bottom: 10px">

<p style="clear: both;" class="center">
	<!-- <button type="button" id="disable">Disable</button>  -->
	<button type="button" id="clear" class="btn btn-xs btn-danger"><i class="fa fa-undo"></i> Clear Signature</button> 
	<!-- <button type="button" id="json">To JSON</button>
	<button type="button" id="svg">To SVG</button> -->
	<button type="button" id="save_image" class="btn btn-xs btn-success"><i class="fa fa-save"></i> Save Digital Signature</button>
</p>

<!-- hidden form -->
<input type="hidden" value="<?php echo isset($value)?$value->no_mr:''?>" name="noMrHiddenPasien" id="noMrHiddenPasien">

      

    






<!-- end form create SEP