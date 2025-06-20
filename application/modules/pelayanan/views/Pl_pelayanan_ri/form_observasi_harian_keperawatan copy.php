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

  $('#jam_monitor, #jam_monitor2').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#jam_monitor, #jam_monitor2').timepicker('showWidget');
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

});

$(document).ready(function() {
  
  // load grafik hemodinamik
  load_graph();
  
  // proses add cppt
  $('#btn_save_perkembangan_pasien, #btn_save_work_day, #btn_hemodinamik, #btn_monitor_perkembangan_pasien').click(function (e) {   
    e.preventDefault();
    var btn_value = $(this).val();
    $.ajax({
        url: $('#form_pelayanan').attr('action'),
        data: $('#form_pelayanan').serialize()+ '&submit='+btn_value+'',            
        dataType: "json",
        type: "POST",
        complete: function(xhr) {             
          var data=xhr.responseText;        
          var jsonResponse = JSON.parse(data);        
          if(jsonResponse.status === 200){      

            $('#btn_observasi_harian_keperawatan').click();

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
    load_graph();

  });
}

function edit_row(id, flag){
  preventDefault();
  $.getJSON('pelayanan/Pl_pelayanan_ri/get_row_data_observasi', {ID: id} , function(response_data) {
    if(response_data.status == 200){

      if(flag == 'tbl_observasi_harian_keperawatan'){
        
        $('#id').val(response_data.data.id);
        $('#tgl_monitor').val(response_data.data.tgl_monitor);
        $('#intake_enteral').val(response_data.data.intake_enteral);
        $('#intake_parenteral').val(response_data.data.intake_parenteral);
        $('#obat_enteral').val(response_data.data.obat_enteral);
        $('#obat_parenteral').val(response_data.data.obat_parenteral);
        $('#polavent').val(response_data.data.polavent);
        $('#lain_alergi').val(response_data.data.lain_alergi);
        $('#catatan').val(response_data.data.catatan);

      }

      if(flag == 'dt_hemodinamik'){
        
        $('#id').val(response_data.data.id);
        $('#jam_monitor').val(response_data.data.jam_monitor);
        $('#sistolik').val(response_data.data.sistolik);
        $('#diastolik').val(response_data.data.diastolik);
        $('#nd').val(response_data.data.nd);
        $('#sh').val(response_data.data.sh);

      }

      if(flag == 'dt_montoring_perkembangan_pasien'){
        
        $('#id').val(response_data.data.id);
        $('#jam_monitor2').val(response_data.data.jam_monitor);
        $('#kesadaran').val(response_data.data.kesadaran);
        $('#pupil').val(response_data.data.pupil);
        $('#ref').val(response_data.data.ref);
        $('#gcs').val(response_data.data.gcs);
        $('#sup').val(response_data.data.sup);
        $('#inf').val(response_data.data.inf);
        $('#cm_enteral').val(response_data.data.cm_enteral);
        $('#cm_parenteral').val(response_data.data.cm_parenteral);
        $('#cm_train').val(response_data.data.cm_train);
        $('#ck_urin').val(response_data.data.ck_urin);
        $('#ck_ngt').val(response_data.data.ck_ngt);
        $('#ck_bab').val(response_data.data.ck_bab);
        $('#resp_pola').val(response_data.data.resp_pola);
        $('#resp_tv').val(response_data.data.resp_tv);
        $('#resp_rr').val(response_data.data.resp_rr);
        $('#resp_fo2').val(response_data.data.resp_fo2);
        $('#resp_peep').val(response_data.data.resp_peep);
        $('#tbpt').val(response_data.data.tbpt);

      }

      // Show the modal or form for editing
      // For example, you can use a modal dialog to show the data
    }else{
      $.achtung({message: response_data.message, timeout:5, className: 'achtungFail'});
    }
  });
}

function deleterow(a, b, idname)
{
  preventDefault();
  if(b != 0){
    $.getJSON("<?php echo base_url('Pl_pelayanan_ri/update_status_dt_monitoring') ?>?ID="+b+"&table=&deleted=1", '', function(data) {
        document.getElementById(idname+a).innerHTML = "";
    });
  }else{
    y = a ;
    x = a + 1;
    document.getElementById(idname+a).innerHTML = "";
  }
}

counterfile = <?php $j=0;echo $j.";";?>

function add_row_dt(tablename)
{
  preventDefault();
  
  if(tablename == 'dt_montoring_perkembangan_pasien'){
    counternextfile = counterfile + 1;
    counterIdfile = counterfile + 1;
    html = '<tr id="row_id_'+counternextfile+'">\
              <td class="center" style="font-size: 11px"><a href="#" onclick="deleterow('+counternextfile+', 0, '+"'row_id_'"+')"><i class="fa fa-times-circle red bigger-120"></a></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 80px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 40px; text-align: center" name="kes" id="kes"></td>\
            </tr>';
            
    counterfile++;
  }

  if(tablename == 'dt_cairan_masuk_keluar'){
    counternextfile = counterfile + 1;
    counterIdfile = counterfile + 1;
    html = '<tr id="row_id_dt_cmck'+counternextfile+'">\
              <td class="center" style="font-size: 11px"><a href="#" onclick="deleterow('+counternextfile+', 0, '+"'row_id_dt_cmck'"+')"><i class="fa fa-times-circle red bigger-120"></a></td>\
              <td class="center" style="font-size: 11px"><input type="text" style="width: 80px; text-align: center" name="kes" id="kes"></td>\
              <td class="center" style="font-size: 11px"><input type="text" class="form-control" style="width: 100%; text-align: left" name="kes" id="kes"></td>\
            </tr>';
            
    counterfile++;
  }

  $('#'+tablename+' tbody').append(html);

}

function load_graph(){
  $('#grafik_content').html('Loading...');
  $.getJSON('pelayanan/Pl_pelayanan_ri/get_content_chart_monitoring', {no_kunjungan : $('#no_kunjungan').val()}, function(response_data) {
    html = '';
    $.each(response_data, function (i, o) {
      html += '<div class="col-sm-'+o.col_size+'"><div id="'+o.nameid+'"></div></div>';
      if(o.style=='line'){
        GraphLineStyle(o.mod, o.nameid, o.url);
      }
    });
    
    $('#grafik_content').html(html);
  });
}

</script>
<div class="row">
  <div class="col-md-12">

    <center><span style="font-weight: bold; font-size: 20px !important">OBSERVASI HARIAN KEPERAWATAN PASIEN</span></center>
    <br>
    <!-- hidden form -->
    <input type="hidden" name="tipe_monitoring" id="tipe_monitoring" value="UMUM">
    <input type="hidden" name="id" id="id" >

    <!-- TANGGAL -->
    <div class="form-group">
        <label class="control-label col-sm-1" for="">*Tanggal</label>
        <div class="col-md-2">
          <div class="input-group">
              <input name="tgl_monitor" id="tgl_monitor" placeholder="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>">
              <span class="input-group-addon">
                <i class="ace-icon fa fa-calendar"></i>
              </span>
          </div>
        </div>
    </div>
    <hr>
    <!-- END TANGGAL -->

    <!-- FORM OBSERVASI HARIAN KEPERAWATAN -->
    <div class="row">
      <div class="col-md-12">

        <div class="col-md-3 no-padding">
          <b><u>INTAKE</u></b><br>
              <div style="width: 100%">
                  Enteral<br>
                  <textarea class="form-control" style="height: 50px !important;" name="intake_enteral" id="intake_enteral"></textarea>
              </div>
              <br>
              <div style="width: 100%">
                  Parenteral<br>
                  <textarea class="form-control" style="height: 50px !important;" name="intake_parenteral" id="intake_parenteral"></textarea>
              </div>
        </div>

        <div class="col-md-3 ">
          <b><u>PEMBERIAN OBAT</u></b><br>
            <div style="width: 100%">
                Enteral/Lain-lain<br>
                <textarea class="form-control" style="height: 50px !important;" name="obat_enteral" id="obat_enteral"></textarea>
            </div>
            <br>
            <div style="width: 100%">
                Parenteral<br>
                <textarea class="form-control" style="height: 50px !important;" name="obat_parenteral" id="obat_parenteral"></textarea>
            </div>
        </div>

        <div class="col-md-3">
          <b>&nbsp;</b><br>
            <div style="width: 100%">
                Polavent<br>
                <textarea class="form-control" style="height: 50px !important;" name="polavent" id="polavent"></textarea>
            </div>
            <br>
            <div style="width: 100%">
                Lain-lain (Alergi)<br>
                <textarea class="form-control" style="height: 50px !important;" name="lain_alergi" id="lain_alergi"></textarea>
            </div>
        </div>

        <div class="col-md-3 no-padding">
          <b>&nbsp;</b><br>
            <div style="width: 100%">
                Catatan Dokter<br>
                <textarea class="form-control" style="height: 120px !important;" name="catatan" id="catatan"></textarea>
            </div>
        </div>

        <div class="col-md-12 no-padding" style="padding-top: 3px !important">
          <button type="submit" name="btn_work_day" value="btn_work_day" class="btn btn-xs btn-primary" id="btn_save_work_day"><i class="fa fa-save"></i> Simpan</button>
        </div>
        <br>

        <div class="col-md-12 no-padding">
          <table class="table" style="margin-top: 10px" id="tbl_observasi_harian_keperawatan">
            <tr style="background:#428bca; color: #fff">
              <th rowspan="2" width="50px">#</th>
              <th rowspan="2" class="center">Tanggal</th>
              <th colspan="2" class="center">Intake</th>
              <th rowspan="2" class="center" style="vertical-align: middle; width: 200px">Polavent</th>
              <th colspan="2" class="center">Obat</th>
              <th rowspan="2" class="center" style="vertical-align: middle; width: 200px">Lain-lain (Alergi)</th>
              <th rowspan="2" class="center" style="vertical-align: middle; width: 200px">Catatan Dokter</th>
            </tr>
            <tr style="background:#428bca; color: #fff">
              <th class="center" style="width: 200px">enteral</th>
              <th class="center" style="width: 200px">parenteral</th>
              <th class="center" style="width: 200px">enteral/lain-lain</th>
              <th class="center" style="width: 200px">parenteral</th>
            </tr>
            <?php foreach($perkembangan as $key=>$list) : ?>
              <?php 
                foreach($list as $row) :
                  $is_deleted = ($row->is_deleted == 1) ? 'style="text-decoration: line-through; color: red"' :'';
                  if($row->is_deleted == 1){
                    $btn = "<a href='#' onclick='set_line_through(".$row->id.", 0)'><i class='fa fa-refresh green bigger-120'></i></a>";
                  }else{
                    $btn = "<a href='#' onclick='set_line_through(".$row->id.", 1)'><i class='fa fa-times-circle red bigger-120'></i></a>";
                  }
              ?>
                <tr id="tbl_dt_<?php echo $row->id?>" <?php echo $is_deleted?>>
                  <td align="center"><span id="btn_action_<?php echo $row->id?>"><?php echo $btn?></span> </td>
                  <td><b><a href="#" onclick="edit_row(<?php echo $row->id?>, 'tbl_observasi_harian_keperawatan')"><?php echo $this->tanggal->formatDateDmy($key)?></a></b><br>(<?php echo $row->created_by?>)</td>
                  <td><?php echo $row->intake_enteral?></td>
                  <td><?php echo $row->intake_parenteral?></td>
                  <td><?php echo $row->polavent?></td>
                  <td><?php echo $row->obat_enteral?></td>
                  <td><?php echo $row->obat_parenteral?></td>
                  <td><?php echo $row->lain_alergi?></td>
                  <td><?php echo $row->catatan?></td>
                </tr>
              <?php endforeach;?>
            <?php endforeach;?>
            
          </table>
        </div>

      </div>
    </div>
    <!-- END FORM OBSERVASI HARIAN KEPERAWATAN -->

    <hr>

    <div id="accordion" class="accordion-style1 panel-group">
      
      <!-- COLLAPSE HEMODINAMIK -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseHemodinamik" aria-expanded="false">
              <i class="bigger-110 ace-icon fa fa-angle-right" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
              &nbsp;  HEMODINAMIK
            </a>
          </h4>
        </div>

        <div class="panel-collapse collapse" id="collapseHemodinamik" aria-expanded="false" style="height: 0px;">
          <div class="panel-body">
            <!-- HEMODINAMIK -->
            <div class="row" style="padding: 10px !important">
              <div class="col-md-12">
                <h3 class="header smaller lighter blue padding-10">
                    HEMODINAMIK
                  </h3>
                <div class="col-md-3">
                  <div class="form-group">
                      <label class="control-label col-sm-6" for="">*Jam Input</label>
                      <div class="col-md-6">
                        <div class="input-group">
                            <input id="jam_monitor" name="jam_monitor"  type="text" class="form-control">
                            <span class="input-group-addon">
                              <i class="fa fa-clock-o bigger-110"></i>
                            </span>
                        </div>
                      </div>
                  </div>
                  <br>
                  <span style="font-weight: bold; padding: 10px">1. TD (Tekanan Darah)</span>
                  <br>
                  <div class="form-group">
                      <label class="control-label col-sm-6">Sistolik (mmHg)</label>
                      <div class="col-md-6">
                        <input type="text" class="form-control" name="sistolik" id="sistolik" value="">
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="control-label col-sm-6">Diastolik (mmHg)</label>
                      <div class="col-md-6">
                        <input type="text" class="form-control" name="diastolik" id="diastolik" value="">
                      </div>
                  </div>
                  <br>

                  <span style="font-weight: bold; padding: 10px; color: red">2. FN (Frekuensi Nadi)</span>
                  <br>
                  <div class="form-group no-padding">
                      <label class="control-label col-sm-6"> Nadi (bpm)</label>
                      <div class="col-md-6">
                        <input type="text" class="form-control" name="nd" id="nd" value="">
                      </div>
                  </div>
                  <br>
                  <span style="font-weight: bold; padding: 10px; color: blue">3. SH (Suhu)</span>
                  <br>
                  <div class="form-group no-padding">
                      <label class="control-label col-sm-6">Suhu (&#x2103;)</label>
                      <div class="col-md-6">
                        <input type="text" class="form-control" name="sh" id="sh" value="">
                      </div>
                  </div>
                  <br>
                  <div class="col-md-12 no-padding" style="padding-top: 3px !important">
                    <button type="submit" name="btn_hemodinamik" value="btn_hemodinamik" class="btn btn-xs btn-primary" id="btn_hemodinamik"><i class="fa fa-save"></i> Simpan</button>
                  </div>
                  

                </div>
                <div class="col-md-9" style="padding-left: 30px !important">

                  <div class="tabbable">
                    <ul class="nav nav-tabs" id="myTab2">
                      <li class="active">
                        <a data-toggle="tab" href="#grafik_monitoring_tab">
                          <i class="green ace-icon fa fa-bar-chart-o bigger-120"></i>
                          Grafik Monitoring
                        </a>
                      </li>

                      <li>
                        <a data-toggle="tab" href="#tabel_monitoring_tab">
                          <i class="green ace-icon fa fa-list bigger-120"></i> Data Tabel
                        </a>
                      </li>
                    </ul>

                    <div class="tab-content">

                      <div id="grafik_monitoring_tab" class="tab-pane fade in active">
                        <div class="row no-padding" id="grafik_content">
                          <!-- Content will be loaded here via AJAX -->
                           
                        </div>
                        
                      </div>

                      <div id="tabel_monitoring_tab" class="tab-pane fade">
                        <p style="font-size: 14px; font-weight: bold">H E M O D I N A M I K</p>
                        <table class="table" id="dt_hemodinamik">
                          <thead>
                            <tr style="background:#428bca; color: #fff">
                              <th width="30px">#</th>
                              <th width="50px" class="center">Jam</th>
                              <th width="80px" class="center">Petugas</th>
                              <th class="center" width="50px">Sistolik (mmHg)</th>
                              <th class="center" width="50px">Diastolik (mmHg)</th>
                              <th class="center" width="50px">Nadi (bpm)</th>
                              <th class="center" width="50px">Suhu (&#x2103;)</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach($hemodinamik as $key_hd=>$list_hd) : ?>
                              <tr>
                                <td colspan="7"><b>Tanggal. <?php echo $this->tanggal->formatDateDmy($key_hd)?></b></td>
                              </tr>
                              <?php 
                                foreach($list_hd as $row) :
                                  $is_deleted = ($row->is_deleted == 1) ? 'style="text-decoration: line-through; color: red"' :'';
                                  if($row->is_deleted == 1){
                                    $btn = "<a href='#' onclick='set_line_through(".$row->id.", 0)'><i class='fa fa-refresh green bigger-120'></i></a>";
                                  }else{
                                    $btn = "<a href='#' onclick='set_line_through(".$row->id.", 1)'><i class='fa fa-times-circle red bigger-120'></i></a>";
                                  }
                              ?>
                                <tr id="tbl_dt_<?php echo $row->id?>" <?php echo $is_deleted?>>
                                  <td align="center"><span id="btn_action_<?php echo $row->id?>"><?php echo $btn?></span> </td>
                                  <td><b><a href="#" onclick="edit_row(<?php echo $row->id?>, 'dt_hemodinamik')"><?php echo $this->tanggal->formatTime($row->jam_monitor)?></a></b></td>
                                  <td><?php echo $row->created_by?></td>
                                  <td align="center"><?php echo $row->sistolik?></td>
                                  <td align="center"><?php echo $row->diastolik?></td>
                                  <td align="center"><?php echo $row->nd?></td>
                                  <td align="center"><?php echo $row->sh?></td>
                                </tr>
                              <?php endforeach;?>
                            <?php endforeach;?>
                            
                          </tbody>
                      </table>

                      </div>
                      
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
            <!-- END HEMODINAMIK -->
          </div>
        </div>
      </div>
      <!-- END COLLAPSE HEMODINAMIK -->

      <!-- COLLAPSE DATA MONITORING PERKEMBANGAN PASIEN -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseDataMonitoringPerkembanganPasien" aria-expanded="false">
              <i class="ace-icon fa fa-angle-right bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
              &nbsp;DATA MONITORING PERKEMBANGAN PASIEN
            </a>
          </h4>
        </div>

        <div class="panel-collapse collapse" id="collapseDataMonitoringPerkembanganPasien" aria-expanded="false">
          <div class="panel-body">
            <!-- DATA MONITORING PERKEMBANGAN PASIEN -->
            <div class="row" style="padding: 10px !important">
              <div class="col-md-12">
                <h3 class="header smaller lighter blue padding-10">
                  DATA MONITORING PERKEMBANGAN PASIEN
                </h3>
                <div class="col-md-12 no-padding">
                  <div class="form-group">
                      <label class="control-label col-sm-1" for="">*Jam Input</label>
                      <div class="col-md-2">
                        <div class="input-group">
                            <input id="jam_monitor2" name="jam_monitor2"  type="text" class="form-control">
                            <span class="input-group-addon">
                              <i class="fa fa-clock-o bigger-110"></i>
                            </span>
                        </div>
                      </div>
                  </div>
                  <div style="padding: 5px; font-weight: bold">SSP (Sistem Saraf Pusat)</div>
                  <div class="form-group">
                      <label class="control-label col-sm-1">Kes.</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="kesadaran" id="kesadaran" value="">
                      </div>
                      <label class="control-label col-sm-1">Pupil</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="pupil" id="pupil" value="">
                      </div>
                      <label class="control-label col-sm-1">Ref.</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="ref" id="ref" value="">
                      </div>
                      <label class="control-label col-sm-1">GCS</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="gcs" id="gcs" value="">
                      </div>
                  </div>

                  <div style="padding: 5px; font-weight: bold">Motorik</div>
                  <div class="form-group">
                      <label class="control-label col-sm-1">Sup.</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="sup" id="sup" value="">
                      </div>
                      <label class="control-label col-sm-1">Inf.</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="inf" id="inf" value="">
                      </div>
                  </div>

                  <div style="padding: 5px; font-weight: bold">CM (Cairan Masuk)</div>
                  <div class="form-group">
                      <label class="control-label col-sm-1">Ent.</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="cm_enteral" id="cm_enteral" value="">
                      </div>
                      <label class="control-label col-sm-1">Par.</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="cm_parenteral" id="cm_parenteral" value="">
                      </div>
                      <label class="control-label col-sm-1">Train.</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="cm_train" id="cm_train" value="">
                      </div>
                  </div>

                  <div style="padding: 5px; font-weight: bold">CK (Cairan Keluar)</div>
                  <div class="form-group">
                      <label class="control-label col-sm-1">Urin</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="ck_urin" id="ck_urin" value="">
                      </div>
                      <label class="control-label col-sm-1">NGT</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="ck_ngt" id="ck_ngt" value="">
                      </div>
                      <label class="control-label col-sm-1">BAB</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="ck_bab" id="ck_bab" value="">
                      </div>
                  </div>

                  <div style="padding: 5px; font-weight: bold">Respirasi</div>
                  <div class="form-group">
                      <label class="control-label col-sm-1">Pola</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="resp_pola" id="resp_pola" value="">
                      </div>
                      <label class="control-label col-sm-1">TV</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="resp_tv" id="resp_tv" value="">
                      </div>
                      <label class="control-label col-sm-1">RR</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="resp_rr" id="resp_rr" value="">
                      </div>
                      <label class="control-label col-sm-1">Fo2%</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="resp_fo2" id="resp_fo2" value="">
                      </div>
                      <label class="control-label col-sm-1">Peep</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="resp_peep" id="resp_peep" value="">
                      </div>
                  </div>

                  <div style="padding: 5px; font-weight: bold">TBPT</div>
                  <div class="form-group">
                      <label class="control-label col-sm-1">Tbpt</label>
                      <div class="col-md-1">
                        <input type="text" class="form-control" name="tbpt" id="tbpt" value="">
                      </div>
                  </div>

                </div>
                <hr>
                <div class="col-md-12 no-padding" style="padding-top: 3px !important; padding-bottom: 10px !important">
                  <button type="submit" name="btn_monitor_perkembangan_pasien" value="btn_monitor_perkembangan_pasien" class="btn btn-xs btn-primary" id="btn_monitor_perkembangan_pasien"><i class="fa fa-save"></i> Simpan</button>
                </div>

                <!-- <div style="padding-top: 10px"><a href="#" class="btn btn-xs btn-primary" onclick="add_row_dt('dt_montoring_perkembangan_pasien')"><i class="fa fa-plus"></i> Tambah Data</a></div> -->

                <table class="table" id="dt_montoring_perkembangan_pasien">
                  <thead>
                  <tr style="background:#428bca; color: #fff">
                    <th rowspan="2" width="30px">#</th>
                    <th rowspan="2" width="80px" class="center">JAM</th>
                    <th colspan="4" class="center">SSP</th>
                    <th colspan="2" class="center">MOTORIK</th>
                    <th colspan="3" class="center">CAIRAN MASUK</th>
                    <th colspan="3" class="center">CAIRAN KELUAR</th>
                    <th colspan="5" class="center">RESPIRASI</th>
                    <!-- <th colspan="6" class="center">AGD</th> -->
                    <th rowspan="2" class="center">TBPT</th>
                  </tr>
                  <tr style="background:#428bca; color: #fff">
                    <th class="center" style="font-size: 10px">Kes.</th>
                    <th class="center" style="font-size: 10px">Pupil</th>
                    <th class="center" style="font-size: 10px">Ref.</th>
                    <th class="center" style="font-size: 10px">GCS</th>
                    <th class="center" style="font-size: 10px">Sup.</th>
                    <th class="center" style="font-size: 10px">Inf.</th>
                    <th class="center" style="font-size: 10px">Ent.</th>
                    <th class="center" style="font-size: 10px">Par.</th>
                    <th class="center" style="font-size: 10px">Train.</th>
                    <th class="center" style="font-size: 10px">Urin</th>
                    <th class="center" style="font-size: 10px">NGT</th>
                    <th class="center" style="font-size: 10px">BAB</th>
                    <th class="center" style="font-size: 10px">Pola</th>
                    <th class="center" style="font-size: 10px">TV</th>
                    <th class="center" style="font-size: 10px">RR</th>
                    <th class="center" style="font-size: 10px">FO2%</th>
                    <th class="center" style="font-size: 10px">Peep</th>
                    <!-- <th class="center" style="font-size: 10px">pH</th>
                    <th class="center" style="font-size: 10px">pCO2</th>
                    <th class="center" style="font-size: 10px">pO2</th>
                    <th class="center" style="font-size: 10px">BE</th>
                    <th class="center" style="font-size: 10px">HCO2</th>
                    <th class="center" style="font-size: 10px">Sat</th> -->
                  </tr>
                  </thead>
                  <tbody>
                      <?php foreach($monitoring as $key_mtr=>$list_mtr) : ?>
                         <tr>
                            <td colspan="18"><b>Tanggal. <?php echo $this->tanggal->formatDateDmy($key_mtr)?></b></td>
                          </tr>
                          <?php 
                            foreach($list_mtr as $row_mtr) :
                            $is_deleted = ($row_mtr->is_deleted == 1) ? 'style="text-decoration: line-through; color: red"' :'';
                                  if($row_mtr->is_deleted == 1){
                                    $btn = "<a href='#' onclick='set_line_through(".$row_mtr->id.", 0)'><i class='fa fa-refresh green bigger-120'></i></a>";
                                  }else{
                                    $btn = "<a href='#' onclick='set_line_through(".$row_mtr->id.", 1)'><i class='fa fa-times-circle red bigger-120'></i></a>";
                                  }
                            ?>
                            <tr>
                              <tr id="tbl_dt_<?php echo $row_mtr->id?>" <?php echo $is_deleted?>>
                                  <td align="center"><span id="btn_action_<?php echo $row_mtr->id?>"><?php echo $btn?></span> </td>
                                  <td><b><a href="#" onclick="edit_row(<?php echo $row_mtr->id?>, 'dt_montoring_perkembangan_pasien')"><?php echo $this->tanggal->formatTime($row_mtr->jam_monitor)?></a></b></td>

                              <td align="center"><?php echo $row_mtr->kesadaran?></td>
                              <td align="center"><?php echo $row_mtr->pupil?></td>
                              <td align="center"><?php echo $row_mtr->ref?></td>
                              <td align="center"><?php echo $row_mtr->gcs?></td>
                              <td align="center"><?php echo $row_mtr->sup?></td>
                              <td align="center"><?php echo $row_mtr->inf?></td>
                              <td align="center"><?php echo $row_mtr->cm_enteral?></td>
                              <td align="center"><?php echo $row_mtr->cm_parenteral?></td>
                              <td align="center"><?php echo $row_mtr->cm_train?></td>
                              <td align="center"><?php echo $row_mtr->ck_urin?></td>
                              <td align="center"><?php echo $row_mtr->ck_ngt?></td>
                              <td align="center"><?php echo $row_mtr->ck_bab?></td>
                              <td align="center"><?php echo $row_mtr->resp_pola?></td>
                              <td align="center"><?php echo $row_mtr->resp_tv?></td>
                              <td align="center"><?php echo $row_mtr->resp_rr?></td>
                              <td align="center"><?php echo $row_mtr->resp_fo2?></td>
                              <td align="center"><?php echo $row_mtr->resp_peep?></td>
                              <td align="center"><?php echo $row_mtr->tbpt?></td>
                            </tr>
                          <?php endforeach;?>
                      <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- DATA MONITORING PERKEMBANGAN PASIEN -->
          </div>
        </div>
      </div>
      <!-- END COLLAPSE DATA MONITORING PERKEMBANGAN PASIEN -->

      <!-- COLLAPSE DESKRIPSI/JENIS CAIRAN/CATATAN KHUSUS/DLL -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false">
              <i class="ace-icon fa fa-angle-right bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
              &nbsp;DESKRIPSI/JENIS CAIRAN/CATATAN KHUSUS/DLL
            </a>
          </h4>
        </div>

        <div class="panel-collapse collapse" id="collapseThree" aria-expanded="false">
          <div class="panel-body">
            <div class="row" style="padding: 10px !important">
              <div class="col-md-12">
                <h3 class="header smaller lighter blue padding-10">
                  DESKRIPSI/JENIS CAIRAN/CATATAN KHUSUS/DLL
                </h3>
                <div class="col-md-8 no-padding">
                  <div style="padding-bottom: 10px"><a href="#" class="btn btn-xs btn-primary" onclick="add_row_dt('dt_cairan_masuk_keluar')"><i class="fa fa-plus"></i> Tambah Data</a></div>
                  <table class="table" id="dt_cairan_masuk_keluar">
                    <thead>
                      <tr style="background:#428bca; color: #fff">
                        <th width="30px">#</th>
                        <th width="80px" class="center">JAM</th>
                        <th class="center"> DESKRIPSI/JENIS CAIRAN/CATATAN KHUSUS/DLL </th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>     
                </div>

                <div class="col-md-4">
                  <span style="font-weight: bold; padding-bottom: 10px">KESEIMBANGAN CAIRAN</span> 
                  <br>
                  <div class="form-group">
                      <label class="control-label col-sm-4">Masuk</label>
                      <div class="col-md-6">
                        <input type="text" class="form-control" name="cairan_masuk" id="cairan_masuk" value="">
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <label class="control-label col-sm-4">Keluar</label>
                      <div class="col-md-6">
                        <input type="text" class="form-control" name="cairan_keluar" id="cairan_keluar" value="">
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="control-label col-sm-4">IWL</label>
                      <div class="col-md-6">
                        <input type="text" class="form-control" name="iwl" id="iwl" value="">
                      </div>
                  </div>
                  <hr>
                  <div class="form-group">
                      <label class="control-label col-sm-4">Balans</label>
                      <div class="col-md-6">
                        <input type="text" class="form-control" name="balans" id="balans" value="">
                      </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END COLLAPSE DESKRIPSI/JENIS CAIRAN/CATATAN KHUSUS/DLL -->

      <!-- COLLAPSE RENCANA PEMERIKSAAN DAN KEGIATAN HARIAN -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false">
              <i class="ace-icon fa fa-angle-right bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
              &nbsp;DESKRIPSI/JENIS CAIRAN/CATATAN KHUSUS/DLL
            </a>
          </h4>
        </div>

        <div class="panel-collapse collapse" id="collapseFour" aria-expanded="false">
          <div class="panel-body">
            <div class="row" style="padding: 10px !important">
              <div class="col-md-12">
                <h3 class="header smaller lighter blue padding-10">
                  RENCANA PEMERIKSAAN DAN KEGIATAN HARIAN
                </h3>
                <div>
                  <b style="font-style: italic">Deskripsi atau Penjelasan Kegiatan dan Pemeriksaan Harian Pasien</b><br>
                  <textarea class="form-control" style="height: 100px !important;" name="kes" id="kes"></textarea>
                </div>
                <br>
                <div>
                  <b style="font-style: italic">Jenis Kegiatan/Pemeriksaan</b><br>
                  <label>
                      <input name="jenis_kegiatan" id="jenis_kegiatan" type="radio" class="ace" value="1">
                      <span class="lbl"> Rencana Pemeriksaan</span>
                    </label>
                    <label>
                      <input name="jenis_kegiatan" id="jenis_kegiatan" type="radio" class="ace" value="2">
                      <span class="lbl"> Rencana Kegiatan Harian</span>
                    </label>
                </div>
                <div class="col-md-12 no-padding" style="padding-top: 10px; padding-bottom: 10px">
                  <a href="#" class="btn btn-xs btn-primary" id="btn_save_perkembangan_pasien">Simpan</a>
                </div>
                <hr>

                <table class="table" id="tbl_rencana_pemeriksaan_harian">
                    <tr style="background: #f3f3f3">
                      <th width="30px">#</th>
                      <th class="left"> Rencana Pemeriksaan atau Kegiatan Harian  </th>
                      <th class="left"> Jenis Rencana  </th>
                    </tr>
                  </table>  

              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END COLLAPSE RENCANA PEMERIKSAAN DAN KEGIATAN HARIAN -->

    </div>
    
    

    

    
    
    
  </div>
</div>







