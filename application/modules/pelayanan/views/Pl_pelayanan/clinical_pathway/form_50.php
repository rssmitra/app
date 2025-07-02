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

  $('#diagnosis').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getICD10",
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
        $('#diagnosis').val(label_item);
      }

  });

});
</script>

<?php echo $header; ?>
<hr>
<br>

<div style="text-align: center; font-size: 18px;"><b>PERMOHONAN UNTUK RAWAT INAP</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>
<span style="text-align: left;">Diagnosis</span><br>
<input type="text" class="input-type" name="form_50[diagnosis]" id="diagnosis" onchange="fillthis('diagnosis')" value="<?php echo isset($value_form['diagnosis'])?$value_form['diagnosis']:''?>" style="width: 100% !important">
<br>
<br>
<span style="text-align: left;">Instruksi Dokter</span>
<textarea class="textarea-type" name="form_50[isntruksi]" id="isntruksi" onchange="fillthis('isntruksi')" style="height: 300px !important">
  <?php echo isset($value_form['isntruksi'])?$value_form['isntruksi']:''?>
</textarea>
<br>
<hr>
<?php echo $footer; ?>