<script>

jQuery(function($) {  
   // Unbind event lama (penting!)
  $('.date-picker').datepicker('destroy');

  // Inisialisasi ulang dengan opsi yang sama
  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: 'dd/mm/yyyy'
  }).on('show', function(e) {
    // Pastikan hanya satu instance tampil
    $('.datepicker').not($(this).data('datepicker').picker).remove();
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
<div style="text-align: center; font-size: 18px;">
  <b><u>HEMODIALISIS - DAFTAR OBAT</u></b><br>
</div>

<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">
<br>

<table border="1" width="100%" style="border-collapse: collapse; font-size:13px; text-align:center;">
  <thead style="font-weight:bold; background-color:#c7cccb;">
    <tr>
      <th style="width:60px;text-align:center;">Tanggal</th>
      <th style="width:20px;text-align:center;">No</th>
      <th style="width:150px;text-align:center;">Nama Obat</th>
      <th style="width:120px;text-align:center;">Dosis</th>
      <th style="width:150px;text-align:center;">Keterangan</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      for($i=1; $i<=20; $i++): 
    ?>
    <tr>
      <td>
        <input 
          type="text" 
          class="input_type date-picker" 
          data-date-format="yyyy-mm-dd" 
          name="form_137[tanggal_<?php echo $i ?>]" 
          id="tanggal_<?php echo $i ?>" 
          onchange="fillthis('tanggal_<?php echo $i ?>')" 
          style="width:100%; text-align:center;"
        >
      </td>
      <td>
        <input 
          type="text" 
          class="input_type" 
          name="form_137[no_<?php echo $i ?>]" 
          id="no_<?php echo $i ?>" 
          onchange="fillthis('no_<?php echo $i ?>')" 
          style="width:100%; text-align:center;"
        >
      </td>
      <td>
        <input 
          type="text" 
          class="input_type" 
          name="form_137[nama_obat_<?php echo $i ?>]" 
          id="nama_obat_<?php echo $i ?>" 
          onchange="fillthis('nama_obat_<?php echo $i ?>')" 
          style="width:100%;"
        >
      </td>
      <td>
        <input 
          type="text" 
          class="input_type" 
          name="form_137[dosis_<?php echo $i ?>]" 
          id="dosis_<?php echo $i ?>" 
          onchange="fillthis('dosis_<?php echo $i ?>')" 
          style="width:100%; text-align:center;"
        >
      </td>
      <td>
        <input 
          type="text" 
          class="input_type" 
          name="form_137[keterangan_<?php echo $i ?>]" 
          id="keterangan_<?php echo $i ?>" 
          onchange="fillthis('keterangan_<?php echo $i ?>')" 
          style="width:100%;"
        >
      </td>
    </tr>
    <?php endfor; ?>
  </tbody>
</table>

<?php echo $footer; ?>
