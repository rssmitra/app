
<script type="text/javascript">

$(document).ready(function() {
  $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_cppt_dt') ?>", {id: $('#idx_cppt').val()} , function (response) {    
    // show data
    var obj = response.result;
    $('#editor').html(obj.catatan_pengkajian);
    // set value input
    var value_form = response.value_form;
    $.each(value_form, function(i, item) {
      var text = item;
      text = text.replace(/\+/g, ' ');
      $('#'+i).val(text);
    });
  }); 
});

function show_edit(myid){
  preventDefault();
  
}

</script>

<div class="row">

  <div class="col-md-12">
    <input type="hidden" name="idx_cppt" id="idx_cppt" value="<?php echo $result->id?>">
    <div id="editor"><?php echo $result->catatan_pengkajian?></div>
  </div>
     
</div>








