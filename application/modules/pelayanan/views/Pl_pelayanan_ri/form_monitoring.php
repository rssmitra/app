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
  
  $('#grafik_content').html('Loading...');
  $.getJSON('pelayanan/Pl_pelayanan_ri/get_content_chart_monitoring', $('#form_pelayanan').serialize(), function(response_data) {
    html = '';
    $.each(response_data, function (i, o) {
      html += '<div class="col-sm-'+o.col_size+'"><div id="'+o.nameid+'"></div></div>';
      if(o.style=='line'){
        GraphLineStyle(o.mod, o.nameid, o.url);
      }
    });
    $('#grafik_content').html(html);
  });
  
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
            $('#btn_monitoring_perkembangan_pasien').click();
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
<div class="row">
  <div class="col-md-12">

    <center><span style="font-weight: bold;">MONITORING PERKEMBANGAN PASIEN RAWAT INAP</span></center>
    <br>
    <input type="hidden" name="tipe_monitoring" id="tipe_monitoring" value="UMUM">
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

    <label class="control-label col-sm-2">Tekanan Darah <i class="ace-icon fa fa-angle-double-right"></i></label>
    <div class="form-group">
        <label class="control-label col-sm-1">Sistolik</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="sistolik" id="sistolik" value="">
        </div>
        <label class="control-label col-sm-1">Diastolik</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="diastolik" id="diastolik" value="">
        </div>
    </div>

    
    <div class="form-group">
        <label class="control-label col-sm-2">Nadi</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="nd" id="nd" value="">
        </div>
        <label class="control-label col-sm-1">Suhu</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="sh" id="sh" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">Pernafasan</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="pernafasan" id="pernafasan" value="">
        </div>
        <label class="control-label col-sm-1">Oral</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="oral" id="oral" value="">
        </div>
        <label class="control-label col-sm-1">Parental</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="parental" id="parental" value="">
        </div>
        <label class="control-label col-sm-1">Jumlah</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="jumlah" id="jumlah" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">B A K</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="bak" id="bak" value="">
        </div>
        <label class="control-label col-sm-1">B A B</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="bab" id="bab" value="">
        </div>
        <label class="control-label col-sm-1">Muntah</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="muntah" id="muntah" value="">
        </div>
        <label class="control-label col-sm-1">Drainage</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="drainage" id="drainage" value="">
        </div>
        <label class="control-label col-sm-1">Jumlah</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="jumlah_b" id="jumlah_b" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">Balance Cairan</label>
        <div class="col-md-3">
          <input type="text" class="form-control" name="cairan" id="cairan" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">Diet</label>
        <div class="col-md-10">
          <input type="text" class="form-control" name="diet" id="diet" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">Catatan</label>
        <div class="col-md-10">
          <input type="text" class="form-control" name="catatan" id="catatan" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2">&nbsp;</label>
        <div class="col-md-10">
          <a href="#" class="btn btn-xs btn-primary" id="btn_save_perkembangan_pasien">Simpan</a>
        </div>
    </div>
    <hr>

    <div id="grafik_content"></div>   
    <hr>
    <br>
    <span style="font-weight: bold">DATA MONITORING PERKEMBANGAN PASIEN</span><br>
    <table class="table">
      <tr style="background: #f3f3f3">
        <th width="30px">#</th>
        <th width="50px">JAM</th>
        <th width="30px" class="center">TD</th>
        <th width="30px" class="center">ND</th>
        <th width="30px" class="center">SH</th>
        <th width="50px">NAFAS</th>
        <th width="100px">MASUK</th>
        <th width="100px">KELUAR</th>
        <th class="center" width="50px">BALANS CAIRAN</th>
        <th width="100px">DIET</th>
        <th width="100px">CATATAN</th>
      </tr>
    <?php 
      if (count($perkembangan) == 0) {
        echo "<tr><td colspan='11'><div class='alert alert-warning'>Tidak ada data ditemukan</div></td></tr>";
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
                    <td align='center'>".$row->sistolik."/".$row->diastolik."</td>
                    <td align='center'>".$row->nd."</td>
                    <td align='center'>".$row->sh."</td>
                    <td align='center'>".$row->nafas."</td>
                    <td>
                      <span class='pull-left' ".$is_deleted.">Oral</span> <span ".$is_deleted." class='pull-right'> ".$row->oral."</span><br>
                      <span class='pull-left' ".$is_deleted.">Parental</span> <span ".$is_deleted." class='pull-right'> ".$row->parenteral."</span><br>
                      <span class='pull-left' ".$is_deleted.">Jumlah</span> <span ".$is_deleted." class='pull-right'> ".$row->jumlah_a."</span>
                    </td>
                    <td>
                      <span class='pull-left' ".$is_deleted.">BAK</span> <span ".$is_deleted." class='pull-right'>".$row->bak."</span><br>
                      <span class='pull-left' ".$is_deleted.">BAB</span> <span ".$is_deleted." class='pull-right'>".$row->bab."</span><br>
                      <span class='pull-left' ".$is_deleted.">Muntah</span> <span ".$is_deleted." class='pull-right'>".$row->muntah."</span><br>
                      <span class='pull-left' ".$is_deleted.">Drainage</span> <span ".$is_deleted." class='pull-right'>".$row->drainage."</span><br>
                      <span class='pull-left' ".$is_deleted.">Jumlah</span> <span ".$is_deleted." class='pull-right'>".$row->jumlah_b."</span><br>
                    </td>
                    <td align='center'>".$row->balance_cairan."</td>
                    <td>".$row->diet."</td>
                    <td>".$row->catatan."</td>
                  </tr>";
  
          }
        }
      }
      
    ?>
    </table>
    

  </div>
</div>







