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

  $('#nama_obat').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getObatByBagianAutoComplete",
              data: { keyword:query, bag: '060101'},            
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
        var val_item=item.split(':')[0];
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#kode_brg').val(val_item);
        $('#nama_obat').val(label_item);

      }
  });

});

$(document).ready(function() {
  
  // proses add cppt
  $('#btn_save_pemberian_obat').click(function (e) {   
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
              $('#btn_form_pemberian_obat').click();
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
  $.getJSON('pelayanan/Pl_pelayanan_ri/update_status_dt_monitoring', {ID: id, table: 'th_monitor_pemberian_obat', deleted : status} , function(response_data) {
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

    <center><span style="font-weight: bold;">DAFTAR PEMBERIAN OBAT</span></center>
    <br>

    <div class="form-group">
        <label class="control-label col-sm-2" for="">*Tanggal/Jam</label>
        <div class="col-md-6">
          <div class="input-group">
              <input name="tgl_obat" id="tgl_obat" placeholder="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>">
              <span class="input-group-addon">
                <i class="ace-icon fa fa-calendar"></i>
              </span>
              <input id="timepicker1" name="jam_obat"  type="text" class="form-control">
              <span class="input-group-addon">
                <i class="fa fa-clock-o bigger-110"></i>
              </span>
          </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">Nama Obat</label>
        <div class="col-md-8">
          <input type="text" class="form-control" name="nama_obat" id="nama_obat" value="">
          <input type="hidden" class="form-control" name="kode_brg" id="kode_brg" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">Dosis</label>
        <div class="col-md-2">
          <input type="text" class="form-control" name="dosis" id="dosis" value="">
        </div>
        <label class="control-label col-sm-1">Frek</label>
        <div class="col-md-2">
          <input type="text" class="form-control" name="frek" id="frek" value="">
        </div>
        <label class="control-label col-sm-1">Rute</label>
        <div class="col-md-2">
          <input type="text" class="form-control" name="rute" id="rute" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">Jenis Terapi</label>
        <div class="col-md-4">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_terapi')), '' , 'jenis_terapi', 'jenis_terapi', 'form-control', '', '');?>
        </div>
        <label class="control-label col-sm-1">Waktu</label>
        <div class="col-md-3">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'waktu_pemberian_obat')), '' , 'waktu_pemberian_obat', 'waktu_pemberian_obat', 'form-control', '', '');?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2">&nbsp;</label>
        <div class="col-md-10">
          <a href="#" class="btn btn-xs btn-primary" id="btn_save_pemberian_obat">Simpan</a>
        </div>
    </div>
    <hr>
    <center><span style="font-weight: bold">JADWAL PEMBERIAN OBAT</span></center>
    <table class="table">
      <tr style="background: #f3f3f3">
        <th width="30px" align="center">No</th>
        <th width="100px">Tanggal & Jam</th>
        <th width="200px" class="left">Nama Obat</th>
        <th class="center" width="100px">Jenis</th>
        <th class="center">Dosis</th>
        <th class="center">Frek</th>
        <th class="center">Rute</th>
        <th class="center">Waktu</th>
        <th class="center">Pwt</th>
        <th class="center">Klg</th>
        <th class="center"></th>
      </tr>
    <?php 
      if (count($obat) == 0) {
        echo "<tr><td colspan='9'><div class='alert alert-warning'>Tidak ada data ditemukan</div></td></tr>";
      }else{
        $no=0;
        foreach($obat as $row){
          $is_deleted = ($row->is_deleted == 1) ? 'style="text-decoration: line-through; color: red"' :'';
          if($row->is_deleted == 1){
            $btn = "<a href='#' onclick='set_line_through(".$row->id.", 0)'><i class='fa fa-refresh green bigger-120'></i></a>";
          }else{
            $btn = "<a href='#' onclick='set_line_through(".$row->id.", 1)'><i class='fa fa-times-circle red bigger-120'></i></a>";
          }
          $no++;
          echo "<tr id='tbl_dt_".$row->id."' ".$is_deleted.">";
          echo "<td align='center'>".$no."</td>";
          echo "<td>".$this->tanggal->formatDateDmy($row->tgl_obat)." ".$this->tanggal->formatTime($row->jam_obat)."</td>";
          echo "<td>".$row->nama_obat."</td>";
          echo "<td align='center'>".$row->jenis_terapi."</td>";
          echo "<td align='center'>".$row->dosis."</td>";
          echo "<td align='center'>".$row->frek."</td>";
          echo "<td align='center'>".$row->rute."</td>";
          echo "<td align='center'>".$row->waktu."</td>";
          echo "<td align='center'>
                  <label>
                  <input type='checkbox' class='ace' name='check_pwt' id='check_pwt_".$row->id."'  onclick='click_for_ttd(".$row->id.", "."'pwt'".")'>
                  <span class='lbl'>&nbsp;</span>
                  </label>
                </td>";
          echo "<td align='center'><label>
              <input type='checkbox' class='ace' name='check_klg' id='check_klg_".$row->id."'  onclick='click_for_ttd(".$row->id.", "."'klg'".")'>
              <span class='lbl'>&nbsp;</span>
          </label></td>";
          echo "<td align='center'><span id='btn_action_".$row->id."'>".$btn."</span></td>";
          echo "</tr>";
        }
      }
      
    ?>
    </table>
    <span style="font-style: italic; font-size: 11px"><b>Keterangan :</b> Jadwal pemberian obat tiap shift harus ditandatangani oleh perawat dan keluarga pasien.</span>
    

  </div>
</div>







