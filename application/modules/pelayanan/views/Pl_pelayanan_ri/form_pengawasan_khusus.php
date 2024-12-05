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
  $('#btn_save_perkembangan_pasien').click(function (e) {   
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
              $('#btn_form_pengawasan_khusus').click();
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
  $.getJSON('pelayanan/Pl_pelayanan_ri/update_status_dt_monitoring', {ID: id, table: 'th_monitor_perkembangan_pasien_ri', deleted : status} , function(response_data) {
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
<style>
  .vertical-text {
     writing-mode: vertical-rl; /* Vertical writing mode, right-to-left */
      text-orientation: upright; /* Keeps text upright */
      border: 1px solid #000;
  }
</style>
<div class="row">
  <div class="col-md-12">

    <input type="hidden" name="tipe_monitoring" id="tipe_monitoring" value="KHUSUS">
    <center><span style="font-weight: bold;">PENGAWASAN KHUSUS RAWAT INAP</span></center>
    <br>
    <div class="form-group">
        <label class="control-label col-sm-2" for="">*Tanggal/Jam</label>
        <div class="col-md-6">
          <div class="input-group">
              <input name="tgl_monitor" id="tgl_monitor" placeholder="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>">
              <span class="input-group-addon">
                <i class="ace-icon fa fa-calendar"></i>
              </span>
              <input id="timepicker1" name="jam_monitor"  type="text" class="form-control">
              <span class="input-group-addon">
                <i class="fa fa-clock-o bigger-110"></i>
              </span>
          </div>
        </div>
    </div>
    <hr>
    <div class="col-md-6 no-padding">
      <span style="font-weight: bold">OBSERVASI</span>
      <div class="form-group">
          <label class="control-label col-sm-3">Kesadaran</label>
          <div class="col-md-3">
            <input type="text" class="form-control" name="kesadaran" id="kesadaran" value="">
          </div>
          <label class="control-label col-sm-3">Tensi</label>
          <div class="col-md-3">
            <input type="text" class="form-control" name="td" id="td" value="">
          </div>
      </div>
      <div class="form-group">
          <label class="control-label col-sm-3">Nadi</label>
          <div class="col-md-3">
            <input type="text" class="form-control" name="nd" id="nd" value="">
          </div>
          <label class="control-label col-sm-3">Pernafasan</label>
          <div class="col-md-3">
            <input type="text" class="form-control" name="pernafasan" id="pernafasan" value="">
          </div>
      </div>
      <div class="form-group">
          <label class="control-label col-sm-3">Suhu</label>
          <div class="col-md-3">
            <input type="text" class="form-control" name="sh" id="sh" value="">
          </div>
          <label class="control-label col-sm-3">CVP</label>
          <div class="col-md-3">
            <input type="text" class="form-control" name="cvp" id="cvp" value="">
          </div>
      </div>
      <div class="form-group">
          <label class="control-label col-sm-3">Obat</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="obat" id="obat" value="">
          </div>
      </div>
    </div>
    <div class="col-md-6 no-padding">
      <span style="font-weight: bold">KESEIMBANGAN CAIRAN</span>
      <div class="form-group">
          <label class="control-label col-sm-3">Oral</label>
          <div class="col-md-3">
            <input type="text" class="form-control" name="oral" id="oral" value="">
          </div>
          <label class="control-label col-sm-3">Infus</label>
          <div class="col-md-3">
            <input type="text" class="form-control" name="infus" id="infus" value="">
          </div>
      </div>
      <div class="form-group">
          <label class="control-label col-sm-3">Urine</label>
          <div class="col-md-3">
            <input type="text" class="form-control" name="urine" id="urine" value="">
          </div>
          <label class="control-label col-sm-3">Muntah</label>
          <div class="col-md-3">
            <input type="text" class="form-control" name="muntah" id="muntah" value="">
          </div>
      </div>
      <div class="form-group">
          <label class="control-label col-sm-3">Drain</label>
          <div class="col-md-3">
            <input type="text" class="form-control" name="drainage" id="drainage" value="">
          </div>
      </div>
      <div class="form-group">
          <label class="control-label col-sm-3">Tindakan</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="catatan" id="catatan" value="">
          </div>
      </div>
    </div>

    <div class="col-md-12 no-padding" >
      <a href="#" class="btn btn-xs btn-primary" id="btn_save_perkembangan_pasien">Simpan</a>
    </div>
    <hr>
    <center><span style="font-weight: bold; margin-top: 20px;">DATA MONITORING PERKEMBANGAN PASIEN</span><br></center>
    <table class="table">
      <tr style="background: #f3f3f3">
        <th class="vertical-text" style="20px; vertical-align: middle"></th>
        <th class="vertical-text" style="20px; vertical-align: middle">JAM</th>
        <th class="vertical-text" style="20px; vertical-align: middle">KESADARAN</th>
        <th class="vertical-text" style="20px; vertical-align: middle">TENSI</th>
        <th class="vertical-text" style="20px; vertical-align: middle">NADI</th>
        <th class="vertical-text" style="20px; vertical-align: middle">NAFAS</th>
        <th class="vertical-text" style="20px; vertical-align: middle">SUHU</th>
        <th class="vertical-text" style="20px; vertical-align: middle">CVP</th>
        <th class="center" width="150px" style="vertical-align: middle">PEMBERIAN OBAT</th>

        <th class="vertical-text" style="20px; vertical-align: middle">ORAL</th>
        <th class="vertical-text" style="20px; vertical-align: middle">INFUS</th>
        <th class="vertical-text" style="20px; vertical-align: middle">URINE</th>
        <th class="vertical-text" style="20px; vertical-align: middle">NGT MUNTAH</th>
        <th class="vertical-text" style="20px; vertical-align: middle">DRAIN</th>
        <th width="150px" style="vertical-align: middle" class="center">TINDAKAN / CATATAN PERAWAT</th>
      </tr>
    <?php 
      if (count($perkembangan) == 0) {
        echo "<tr><td colspan='14'><div class='alert alert-warning'>Tidak ada data ditemukan</div></td></tr>";
      }else{
        foreach($perkembangan as $key=>$rows) {
          echo "<tr>";
          echo "<td colspan='10'><b>Tanggal. ".$this->tanggal->formatDate($key)."</b></td>";
          echo "</tr>";
          foreach($rows as $row){
            $is_deleted = ($row->is_deleted == 1) ? 'style="text-decoration: line-through; color: red"' :'';
            if($row->is_deleted == 1){
              $btn = "<a href='#' onclick='set_line_through(".$row->id.", 0)'><i class='fa fa-refresh green bigger-120'></i></a>";
            }else{
              $btn = "<a href='#' onclick='set_line_through(".$row->id.", 1)'><i class='fa fa-times-circle red bigger-120'></i></a>";
            }
            echo "<tr id='tbl_dt_".$row->id."' ".$is_deleted.">
                    <td align='center'><span id='btn_action_".$row->id."'>".$btn."</span></td>
                    <td>".$this->tanggal->formatTime($row->jam_monitor)."</td>
                    <td align='center'>".$row->kesadaran."</td>
                    <td align='center'>".$row->td."</td>
                    <td align='center'>".$row->nd."</td>
                    <td align='center'>".$row->nafas."</td>
                    <td align='center'>".$row->sh."</td>
                    <td align='center'>".$row->cvp."</td>
                    <td align='left'>".$row->obat."</td>
                    <td align='center'>".$row->oral."</td>
                    <td align='center'>".$row->infus."</td>
                    <td align='center'>".$row->urine."</td>
                    <td align='center'>".$row->muntah."</td>
                    <td align='center'>".$row->drainage."</td>
                    <td align='left'>".$row->catatan."</td>
                  </tr>";

          }
        }
      }
    ?>
    </table>
    

  </div>
</div>







