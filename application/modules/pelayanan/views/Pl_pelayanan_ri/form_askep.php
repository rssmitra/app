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

    <center><span style="font-weight: bold;">ASUHAN KEPERAWATAN CATATAN KEPERAWATAN & PERKEMBANGAN / EVALUASI</span></center>
    <br>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">*Tanggal/Jam</label>
        <div class="col-md-6">
          <div class="input-group">
              <input name="tgl_askep" id="tgl_askep" placeholder="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>">
              <span class="input-group-addon">
                <i class="ace-icon fa fa-calendar"></i>
              </span>
              <input id="timepicker1" name="jam_askep"  type="text" class="form-control">
              <span class="input-group-addon">
                <i class="fa fa-clock-o bigger-110"></i>
              </span>
          </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">Jenis Catatan</label>
        <div class="col-md-5">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_askep')), '' , 'jenis_catatan_askep', 'jenis_catatan_askep', 'form-control', '', '');?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">Catatan</label>
        <div class="col-md-8">
          <textarea class="form-control" name="catatan_askep" id="catatan_askep" style="height: 150px !important"></textarea>
        </div>
    </div>
    
    <div class="form-group" style="padding-top: 3px; padding-left: 8px">
        <label class="col-sm-2">&nbsp;</label>
        <div class="col-md-10">
          <a href="#" class="btn btn-xs btn-primary" id="btn_save_askep">Simpan</a>
        </div>
    </div>
    <br>
    <span style="font-weight: bold">CATATAN TINDAKAN KEPERAWATAN</span>
    <table class="table">
      <tr style="background: #f3f3f3">
        <th width="30px" align="center">No</th>
        <th width="100px">Tanggal & Jam</th>
        <th width="300px" class="left">Catatan Tindakan Keperawatan</th>
        <th class="center" width="100px">Perawat</th>
        <th class="center" width="20px"></th>
      </tr>
    <?php 
      if (count($askep) == 0) {
        echo "<tr><td colspan='5'><div class='alert alert-warning'>Tidak ada data ditemukan</div></td></tr>";
      }else{
        $no=0;
        foreach($askep as $row){
          if($row->jenis_catatan == 'catatan_tindakan'){
            $no++;
            $is_deleted = ($row->is_deleted == 1) ? 'style="text-decoration: line-through; color: red"' :'';
            if($row->is_deleted == 1){
              $btn = "<a href='#' onclick='set_line_through(".$row->id.", 0)'><i class='fa fa-refresh green bigger-120'></i></a>";
            }else{
              $btn = "<a href='#' onclick='set_line_through(".$row->id.", 1)'><i class='fa fa-times-circle red bigger-120'></i></a>";
            }
            echo "<tr id='tbl_dt_".$row->id."' ".$is_deleted.">";
            echo "<td align='center'>".$no."</td>";
            echo "<td>".$this->tanggal->formatDateDmy($row->tgl_askep)." ".$this->tanggal->formatTime($row->jam_askep)."</td>";
            echo "<td>".$row->catatan_askep."</td>";
            echo "<td>".$row->created_by."</td>";
            echo "<td align='center'><span id='btn_action_".$row->id."'>".$btn."</span></td>";
            echo "</tr>";
          }
        }
      }
      
    ?>
    </table>
    <br>
    <span style="font-weight: bold">EVALUASI (SOAP)</span>
    <table class="table">
      <tr style="background: #f3f3f3">
        <th width="30px" align="center">No</th>
        <th width="100px">Tanggal & Jam</th>
        <th width="300px" class="left">Evaluasi (SOAP)</th>
        <th class="center" width="100px">Perawat</th>
        <th class="center" width="20px"></th>
      </tr>
    <?php 
      if (count($askep) == 0) {
        echo "<tr><td colspan='5'><div class='alert alert-warning'>Tidak ada data ditemukan</div></td></tr>";
      }else{
        $no=0;
        foreach($askep as $row){
          if($row->jenis_catatan == 'evaluasi_soap'){
            $no++;
            $is_deleted = ($row->is_deleted == 1) ? 'style="text-decoration: line-through; color: red"' :'';
            if($row->is_deleted == 1){
              $btn = "<a href='#' onclick='set_line_through(".$row->id.", 0)'><i class='fa fa-refresh green bigger-120'></i></a>";
            }else{
              $btn = "<a href='#' onclick='set_line_through(".$row->id.", 1)'><i class='fa fa-times-circle red bigger-120'></i></a>";
            }
            echo "<tr id='tbl_dt_".$row->id."' ".$is_deleted.">";
            echo "<td align='center'>".$no."</td>";
            echo "<td>".$this->tanggal->formatDateDmy($row->tgl_askep)." ".$this->tanggal->formatTime($row->jam_askep)."</td>";
            echo "<td>".$row->catatan_askep."</td>";
            echo "<td>".$row->created_by."</td>";
            echo "<td align='center'><span id='btn_action_".$row->id."'>".$btn."</span></td>";
            echo "</tr>";
          }
        }
      }
      
    ?>
    </table>
    

  </div>
</div>







