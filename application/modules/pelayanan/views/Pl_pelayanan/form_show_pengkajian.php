
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

function printDivHtml(divId) {
     preventDefault();
     var printContents = document.getElementById(divId).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
    //  document.body.innerHTML = originalContents;
}

</script>

<div class="row">

  <div class="col-md-12">
  <div class="pull-right"><a href="<?php echo base_url()?>Templates/Export_data/exportContent?type=pdf&flag=catatan_pengkajian&mod=Pl_pelayanan_ri&cppt_id=<?php echo $result->id?>&paper=P" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Print PDF</a></div> 

    <input type="hidden" name="idx_cppt" id="idx_cppt" value="<?php echo $cppt_id?>">
    <div id="editor"><?php echo $html_form?></div>
  </div>
     
</div>








