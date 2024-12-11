<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>
<script src="<?php echo base_url()?>assets/js/custom/chart.js"></script>
<script type="text/javascript">

jQuery(function($) {  

  $('.date-picker').datepicker({    
    autoclose: true,    
    todayHighlight: true    
  })  
  .next().on(ace.click_event, function(){    
    $(this).prev().focus();    
  });  

  $('#timepicker1').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#timepicker1').timepicker('showWidget');
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

});

$(document).ready(function() {
  
  // proses add cppt
  $('#btn_save_askep').click(function (e) {   
      e.preventDefault();
      $.ajax({
          url: $('#form_pelayanan').attr('action'),
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          complete: function(xhr) {             
            var data=xhr.responseText;        
            var jsonResponse = JSON.parse(data);        
            if(jsonResponse.status === 200){          
              $('#btn_form_askep').click();
              $.achtung({message: jsonResponse.message, timeout:5});  
            }else{           
              $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
            }        
            achtungHideLoader();        
          } 
      });

    });

});

function set_line_through(id, status){
  preventDefault();
  $.getJSON('pelayanan/Pl_pelayanan_ri/update_status_dt_monitoring', {ID: id, table: 'th_asuhan_keperawatan', deleted : status} , function(response_data) {
    if(status == 1){
      $('tr#tbl_dt_'+id+' span').css('text-decoration', 'line-through').css('color', 'red');
      $('tr#tbl_dt_'+id+' td').css('text-decoration', 'line-through').css('color', 'red');  
      $('#btn_action_'+id+'').html("<a href='#' onclick='set_line_through("+id+", 0)'><i class='fa fa-refresh green bigger-120'></i></a>");  
    }else{
      $('tr#tbl_dt_'+id+' span').css('text-decoration', '').css('color', 'black');
      $('tr#tbl_dt_'+id+' td').css('text-decoration', '').css('color', 'black');  
      $('tr#tbl_dt_'+id+'').css('text-decoration', '').css('color', 'black');  
      $('#btn_action_'+id+'').html("<a href='#' onclick='set_line_through("+id+", 1)'><i class='fa fa-times-circle red bigger-120'></i></a>");  
    }
  });
}

</script>
<div class="row">
  <div class="col-md-12">

    <center><span style="font-weight: bold;">OBSERVASI <i>NATIONAL EARLY WARNING SYSTEM (NEWS)</i> USIA &gt; 16 TH</span></center>
    <br>

    <table class="table">
      <tr>
        <th width="100px" rowspan="2">Tanda-tanda vital</th>
        <th width="30px" colspan="2" class="center">Tanggal</th>
        <?php for($i=0; $i<5; $i++):?>
          <th width="30px" colspan="3" class="center"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 100px"></th>
        <?php endfor;?>
      </tr>
      <tr>
        <th class="center" width="100px">Jam</th>
        <th class="center" width="100px">PK Awal</th>
        <?php for($i=0; $i<15; $i++):?>
          <th width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></th>
        <?php endfor;?>
      </tr>
      <tr>
        <th colspan="2" class="center">Parameter</th>
        <th class="center">Score EWS</th>
        <?php for($i=0; $i<15; $i++):?>
          <th width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></th>
        <?php endfor;?>
      </tr>
      <tr>
        <td rowspan="5">Pernafasan</td>
        <td align="center">&gt;= 25</td>
        <td align="center">3</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">21-24</td>
        <td align="center">2</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">12-20</td>
        <td align="center">0</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">9-11</td>
        <td align="center">1</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">&lt;= 8</td>
        <td align="center">3</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>

      <tr>
        <td rowspan="4">Saturasi Oksigen</td>
        <td align="center">96</td>
        <td align="center">0</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">94-95</td>
        <td align="center">1</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">92-93</td>
        <td align="center">2</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">&lt;= 91</td>
        <td align="center">3</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>

      <tr>
        <td rowspan="2">Penggunaan Alat Bantu O2</td>
        <td align="center">Ya</td>
        <td align="center">2</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">Tidak</td>
        <td align="center">0</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>

      <tr>
        <td rowspan="5">Suhu</td>
        <td align="center">>= 39,1</td>
        <td align="center">2</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">38,1-39,0</td>
        <td align="center">1</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>

      <tr>
        <td align="center">36,1-38,0</td>
        <td align="center">0</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>

      <tr>
        <td align="center">35,1-36,0</td>
        <td align="center">1</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center"><=35</td>
        <td align="center">3</td>
        <?php for($i=0; $i<15; $i++):?>
          <td width="30px"><input type="text" class="input_type" name="diet" id="diet" value="" style="width: 30px"></td>
        <?php endfor;?>
      </tr>

    </table>
    

  </div>
</div>







