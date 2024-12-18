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
  // set value of existing data
  $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_ews_dt') ?>", {no_kunjungan: $('#no_kunjungan').val(), kategori: $('#kategori_ews').val()} , function (response) {    
    // show data
    var obj = response.result;
    // console.log(response);
    // set value input
    var value_form = response.value_form;
    var ews_ttl = response.ews_ttl;

    $.each(value_form, function(i, item) {
      var text = item;
      value_int = text.replace(/\+/g, ' ');
      $('#'+i).val(value_int);
      $('#id_'+i).val(value_int);
      $('input:radio[id="'+i+'"]').filter('[value="'+value_int+'"]').attr('checked', true);

      if(value_int == 0){
        $clr_ind = '#7ebc18';
      }else if(value_int >=1 && value_int <=4){
        $clr_ind = '#f6f204';
      }else if(value_int >=5 && value_int <=6){
        $clr_ind = '#f6c004';
      }else{
        $clr_ind = '#f63904';
      }
      
      // $('.ttl_score, #td_'+i+'').css('background', $clr_ind).css('font-weight', 'bold');
      $('#td_'+i+'').css('background', $clr_ind).css('font-weight', 'bold');
      $('#id_'+i+'').css('background', $clr_ind).css('font-weight', 'bold');

    });

    $('#score_ews_indikator').html('');
    
    $.each(ews_ttl, function(key, val) {
        if(val != ''){
          if(val == 0){
            clr_ind = 'success';
          }else if(val >=1 && val <=4){
            clr_ind = 'yellow';
          }else if(val >=5 && val <=6){
            clr_ind = 'warning';
          }else{
            clr_ind = 'danger';
          }
          // append to 
          $('<a class="btn btn-xs btn-'+clr_ind+'">'+val+'</a>').appendTo($('#score_ews_indikator'));
      }
    });

  }); 

  // proses add cppt
  $('#btn_save_ews').click(function (e) {   
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
            $('#btn_ews').click();
            $.achtung({message: jsonResponse.message, timeout:5});  
          }else{           
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
          }        
          achtungHideLoader();        
        } 
    });

  });

});

function getTotalScoreEws(classname){

  // var val = $.trim( $(this).val() );
  var nfs = parseFloat( $("input[type='radio'][id='nfs_"+classname+"']:checked").val() );
  var so = parseFloat( $("input[type='radio'][id='so_"+classname+"']:checked").val() );
  var pob = parseFloat( $("input[type='radio'][id='pob_"+classname+"']:checked").val() );
  var suhu = parseFloat( $("input[type='radio'][id='suhu_"+classname+"']:checked").val() );
  var dj = parseFloat( $("input[type='radio'][id='dj_"+classname+"']:checked").val() );
  var tds = parseFloat( $("input[type='radio'][id='tds_"+classname+"']:checked").val() );
  var sadar = parseFloat( $("input[type='radio'][id='sadar_"+classname+"']:checked").val() );
  var rdd = parseFloat( $("input[type='radio'][id='rdd_"+classname+"']:checked").val() );
  var crt = parseFloat( $("input[type='radio'][id='crt_"+classname+"']:checked").val() );

  var int_nfs = !isNaN(nfs) ? nfs : 0;
  var int_so = !isNaN(so) ? so : 0;
  var int_pob = !isNaN(pob) ? pob : 0;
  var int_suhu = !isNaN(suhu) ? suhu : 0;
  var int_dj = !isNaN(dj) ? dj : 0;
  var int_tds = !isNaN(tds) ? tds : 0;
  var int_sadar = !isNaN(sadar) ? sadar : 0;
  var int_rdd = !isNaN(rdd) ? rdd : 0;
  var int_crt = !isNaN(crt) ? crt : 0;

  ttl = int_nfs + int_so + int_pob + int_suhu + int_dj + int_tds + int_sadar + int_rdd + + int_crt;
  console.log(ttl);
  var total = ttl;
  if(total == 0){
    $clr_ind = '#7ebc18';
  }else if(total >=1 && total <=4){
    $clr_ind = '#f6f204';
  }else if(total >=5 && total <=6){
    $clr_ind = '#f6c004';
  }else{
    $clr_ind = '#f63904';
  }

  $('#id_ttl_'+classname+'').val(total);
  $('#id_ttl_'+classname+'').css('background', $clr_ind).css('font-weight', 'bold');
  $('#td_ttl_'+classname+'').css('background', $clr_ind).css('font-weight', 'bold');


}

</script>
<style>
  .input_type{
    text-align: center !important;
  }
</style>
<div class="row">
  <div class="col-md-12">

    <div class="pull-left">
      <center><span style="font-weight: bold;">OBSERVASI <i>PEDIATRIC EARLY WARNING SYSTEM (PEWS)</i> USIA &lt; 16 TH</span></center>
    </div>
    <div class="pull-right">
      <a href="#" class="btn btn-xs btn-primary" id="btn_save_ews">Simpan</a>
    </div>

    <!-- hidden form -->
    <input type="hidden" name="kategori_ews" id="kategori_ews" value="anak">
    
    <table class="table">
      <tr>
        <th width="100px" rowspan="2">Tanda-tanda vital</th>
        <th width="30px" colspan="2" class="center">Tanggal</th>
        <?php 
          for($i=0; $i<3; $i++):
            $date = date('Y-m-d', strtotime($this->tanggal->formatDateTimeToSqlDate($tgl_masuk) . ' +'.$i.' day'));
        ?>
          <th width="30px" colspan="3" class="center"><input type="text" class="input_type" name="ews[tgl_<?php echo $i?>]" id="tgl_<?php echo $i?>" value="<?php echo $date?>" style="width: 100px"></th>
        <?php endfor;?>
      </tr>
      <tr>
        <th class="center" width="100px">Jam</th>
        <th class="center" width="100px">PK Awal</th>
        <?php for($i=0; $i<3; $i++):?>
          <th width="30px"><input type="text" class="input_type" name="ews[jam_p_<?php echo $i?>]" id="jam_p_<?php echo $i?>" value="07" style="width: 30px"></th>
          <th width="30px"><input type="text" class="input_type" name="ews[jam_s_<?php echo $i?>]" id="jam_s_<?php echo $i?>" value="15" style="width: 30px"></th>
          <th width="30px"><input type="text" class="input_type" name="ews[jam_m_<?php echo $i?>]" id="jam_m_<?php echo $i?>" value="23" style="width: 30px"></th>
        <?php endfor;?>
      </tr>
      <tr>
        <th colspan="2" class="center">Parameter</th>
        <th class="center">Score EWS</th>
        <?php for($i=0; $i<3; $i++):?>
          <th width="30px" class="center">P</th>
          <th width="30px" class="center">S</th>
          <th width="30px" class="center">M</th>
        <?php endfor;?>
      </tr>
      <tr>
        <td rowspan="4">Pernafasan</td>
        <td align="center">>= 50, <= 10</td>
        <td align="center">3</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_pagi_tgl_<?php echo $i?>]" id="nfs_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_siang_tgl_<?php echo $i?>]" id="nfs_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_mlm_tgl_<?php echo $i?>]" id="nfs_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">40-49</td>
        <td align="center">2</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_pagi_tgl_<?php echo $i?>]" id="nfs_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_siang_tgl_<?php echo $i?>]" id="nfs_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_mlm_tgl_<?php echo $i?>]" id="nfs_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">30-39, 11-15</td>
        <td align="center">1</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_pagi_tgl_<?php echo $i?>]" id="nfs_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_siang_tgl_<?php echo $i?>]" id="nfs_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_mlm_tgl_<?php echo $i?>]" id="nfs_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">16-29</td>
        <td align="center">0</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_pagi_tgl_<?php echo $i?>]" id="nfs_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_siang_tgl_<?php echo $i?>]" id="nfs_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_mlm_tgl_<?php echo $i?>]" id="nfs_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <!-- <tr>
        <td align="center">11-15</td>
        <td align="center">1</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_pagi_tgl_<?php echo $i?>]" id="nfs_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_siang_tgl_<?php echo $i?>]" id="nfs_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_mlm_tgl_<?php echo $i?>]" id="nfs_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr> -->
      <!-- <tr>
        <td align="center"><= 10</td>
        <td align="center">3</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_pagi_tgl_<?php echo $i?>]" id="nfs_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_siang_tgl_<?php echo $i?>]" id="nfs_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_nfs[nfs_mlm_tgl_<?php echo $i?>]" id="nfs_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr> -->
      <!-- retraksi dinding dada -->
      <tr>
        <td rowspan="4">Retraksi Dinding Dada</td>
        <td align="center">Normal</td>
        <td align="center">0</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_rdd[rdd_pagi_tgl_<?php echo $i?>]" id="rdd_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_rdd[rdd_siang_tgl_<?php echo $i?>]" id="rdd_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_rdd[rdd_mlm_tgl_<?php echo $i?>]" id="rdd_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">Ringan</td>
        <td align="center">1</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_rdd[rdd_pagi_tgl_<?php echo $i?>]" id="rdd_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_rdd[rdd_siang_tgl_<?php echo $i?>]" id="rdd_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_rdd[rdd_mlm_tgl_<?php echo $i?>]" id="rdd_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">Sedang</td>
        <td align="center">2</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_rdd[rdd_pagi_tgl_<?php echo $i?>]" id="rdd_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_rdd[rdd_siang_tgl_<?php echo $i?>]" id="rdd_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_rdd[rdd_mlm_tgl_<?php echo $i?>]" id="rdd_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">Parah</td>
        <td align="center">3</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_rdd[rdd_pagi_tgl_<?php echo $i?>]" id="rdd_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_rdd[rdd_siang_tgl_<?php echo $i?>]" id="rdd_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_rdd[rdd_mlm_tgl_<?php echo $i?>]" id="rdd_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>

      <!-- saturasi oksigen -->
      <tr>
        <td rowspan="4">Saturasi Oksigen</td>
        <td align="center">> 94</td>
        <td align="center">0</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_so[so_pagi_tgl_<?php echo $i?>]" id="so_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_so[so_siang_tgl_<?php echo $i?>]" id="so_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_so[so_mlm_tgl_<?php echo $i?>]" id="so_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">90-93</td>
        <td align="center">1</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_so[so_pagi_tgl_<?php echo $i?>]" id="so_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_so[so_siang_tgl_<?php echo $i?>]" id="so_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_so[so_mlm_tgl_<?php echo $i?>]" id="so_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">86-89</td>
        <td align="center">2</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_so[so_pagi_tgl_<?php echo $i?>]" id="so_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_so[so_siang_tgl_<?php echo $i?>]" id="so_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_so[so_mlm_tgl_<?php echo $i?>]" id="so_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">&lt;= 85</td>
        <td align="center">3</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_so[so_pagi_tgl_<?php echo $i?>]" id="so_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_so[so_siang_tgl_<?php echo $i?>]" id="so_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_so[so_mlm_tgl_<?php echo $i?>]" id="so_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <!-- CRT -->
      <tr>
        <td rowspan="2">CRT</td>
        <td align="center"><= 2</td>
        <td align="center">0</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_crt[crt_pagi_tgl_<?php echo $i?>]" id="crt_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_crt[crt_siang_tgl_<?php echo $i?>]" id="crt_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_crt[crt_mlm_tgl_<?php echo $i?>]" id="crt_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>

      <tr>
        <td align="center">> 2</td>
        <td align="center">3</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_crt[crt_pagi_tgl_<?php echo $i?>]" id="crt_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_crt[crt_siang_tgl_<?php echo $i?>]" id="crt_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_crt[crt_mlm_tgl_<?php echo $i?>]" id="crt_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>

      <!-- penggunaan alat bantu -->
      <tr>
        <td rowspan="3">Penggunaan Alat Bantu O2</td>
        <td align="center">> 2 L</td>
        <td align="center">2</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_pob[pob_pagi_tgl_<?php echo $i?>]" id="pob_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_pob[pob_siang_tgl_<?php echo $i?>]" id="pob_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_pob[pob_mlm_tgl_<?php echo $i?>]" id="pob_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center"><= 2 L</td>
        <td align="center">1</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_pob[pob_pagi_tgl_<?php echo $i?>]" id="pob_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_pob[pob_siang_tgl_<?php echo $i?>]" id="pob_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_pob[pob_mlm_tgl_<?php echo $i?>]" id="pob_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">Tidak</td>
        <td align="center">0</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_pob[pob_pagi_tgl_<?php echo $i?>]" id="pob_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_pob[pob_siang_tgl_<?php echo $i?>]" id="pob_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_pob[pob_mlm_tgl_<?php echo $i?>]" id="pob_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      
      <!-- suhu -->
      <tr>
        <td rowspan="2">Suhu</td>
        <td align="center">>= 38, <= 35</td>
        <td align="center">3</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_suhu[suhu_pagi_tgl_<?php echo $i?>]" id="suhu_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_suhu[suhu_siang_tgl_<?php echo $i?>]" id="suhu_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_suhu[suhu_mlm_tgl_<?php echo $i?>]" id="suhu_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">36-37</td>
        <td align="center">0</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_suhu[suhu_pagi_tgl_<?php echo $i?>]" id="suhu_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_suhu[suhu_siang_tgl_<?php echo $i?>]" id="suhu_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_suhu[suhu_mlm_tgl_<?php echo $i?>]" id="suhu_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <!-- <tr>
        <td align="center"><= 35</td>
        <td align="center">3</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_suhu[suhu_pagi_tgl_<?php echo $i?>]" id="suhu_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_suhu[suhu_siang_tgl_<?php echo $i?>]" id="suhu_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_suhu[suhu_mlm_tgl_<?php echo $i?>]" id="suhu_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr> -->
      <!-- denyut jantung -->
      <tr>
        <td rowspan="4">Denyut Jantung</td>
        <td align="center">>= 131, <=40</td>
        <td align="center">3</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_pagi_tgl_<?php echo $i?>]" id="dj_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_siang_tgl_<?php echo $i?>]" id="dj_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_mlm_tgl_<?php echo $i?>]" id="dj_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">111-130</td>
        <td align="center">2</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_pagi_tgl_<?php echo $i?>]" id="dj_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_siang_tgl_<?php echo $i?>]" id="dj_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_mlm_tgl_<?php echo $i?>]" id="dj_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">41-50, 91-110</td>
        <td align="center">1</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_pagi_tgl_<?php echo $i?>]" id="dj_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_siang_tgl_<?php echo $i?>]" id="dj_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_mlm_tgl_<?php echo $i?>]" id="dj_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">51-90</td>
        <td align="center">0</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_pagi_tgl_<?php echo $i?>]" id="dj_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_siang_tgl_<?php echo $i?>]" id="dj_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_mlm_tgl_<?php echo $i?>]" id="dj_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <!-- <tr>
        <td align="center">41-50</td>
        <td align="center">1</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_pagi_tgl_<?php echo $i?>]" id="dj_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_siang_tgl_<?php echo $i?>]" id="dj_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_mlm_tgl_<?php echo $i?>]" id="dj_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr> -->
      <!-- <tr>
        <td align="center"><=40</td>
        <td align="center">3</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_pagi_tgl_<?php echo $i?>]" id="dj_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_siang_tgl_<?php echo $i?>]" id="dj_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_dj[dj_mlm_tgl_<?php echo $i?>]" id="dj_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr> -->
      <!-- tekanan darah sistolik -->
      <tr>
        <td rowspan="4">Tekanan Darah Sistolik</td>
        <td align="center">130-139</td>
        <td align="center">3</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_pagi_tgl_<?php echo $i?>]" id="tds_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_siang_tgl_<?php echo $i?>]" id="tds_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_mlm_tgl_<?php echo $i?>]" id="tds_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">120-129</td>
        <td align="center">2</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_pagi_tgl_<?php echo $i?>]" id="tds_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_siang_tgl_<?php echo $i?>]" id="tds_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_mlm_tgl_<?php echo $i?>]" id="tds_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="2">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center"><=80, 90-119</td>
        <td align="center">1</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_pagi_tgl_<?php echo $i?>]" id="tds_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_siang_tgl_<?php echo $i?>]" id="tds_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_mlm_tgl_<?php echo $i?>]" id="tds_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">80-89</td>
        <td align="center">0</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_pagi_tgl_<?php echo $i?>]" id="tds_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_siang_tgl_<?php echo $i?>]" id="tds_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_mlm_tgl_<?php echo $i?>]" id="tds_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <!-- <tr>
        <td align="center"><=80</td>
        <td align="center">1</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_pagi_tgl_<?php echo $i?>]" id="tds_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_siang_tgl_<?php echo $i?>]" id="tds_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_tds[tds_mlm_tgl_<?php echo $i?>]" id="tds_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr> -->
      <!-- kesadaran -->
      <tr>
        <td rowspan="3">Kesadaran</td>
        <td align="center">A</td>
        <td align="center">0</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_sadar[sadar_pagi_tgl_<?php echo $i?>]" id="sadar_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_sadar[sadar_siang_tgl_<?php echo $i?>]" id="sadar_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_sadar[sadar_mlm_tgl_<?php echo $i?>]" id="sadar_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="0">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">V</td>
        <td align="center">1</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_sadar[sadar_pagi_tgl_<?php echo $i?>]" id="sadar_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_sadar[sadar_siang_tgl_<?php echo $i?>]" id="sadar_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_sadar[sadar_mlm_tgl_<?php echo $i?>]" id="sadar_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="1">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>
      <tr>
        <td align="center">P,U</td>
        <td align="center">3</td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px">
            <label>
              <input name="ews_sadar[sadar_pagi_tgl_<?php echo $i?>]" id="sadar_pagi_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('pagi_tgl_<?php echo $i?>')" class="ace pagi_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_sadar[sadar_siang_tgl_<?php echo $i?>]" id="sadar_siang_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('siang_tgl_<?php echo $i?>')" class="ace siang_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
          <td align="center" width="30px">
            <label>
              <input name="ews_sadar[sadar_mlm_tgl_<?php echo $i?>]" id="sadar_mlm_tgl_<?php echo $i?>" type="radio" onchange="getTotalScoreEws('mlm_tgl_<?php echo $i?>')" class="ace mlm_tgl_<?php echo $i?>" value="3">
              <span class="lbl"> &nbsp;</span>
            </label>
          </td>
        <?php endfor;?>
      </tr>

      <!-- total score -->
      <tr>
        <td align="center" colspan="3"><b>T O T A L &nbsp; S C O R E</b></td>
        <?php for($i=0; $i<3; $i++):?>
          <td align="center" width="30px" id="td_ttl_pagi_tgl_<?php echo $i?>">
            <input name="ews_ttl[ttl_pagi_tgl_<?php echo $i?>]" id="id_ttl_pagi_tgl_<?php echo $i?>" type="text" class="input_type ttl_score" style="width: 30px" value="">
          </td>
          <td align="center" width="30px" id="td_ttl_siang_tgl_<?php echo $i?>">
            <input name="ews_ttl[ttl_siang_tgl_<?php echo $i?>]" id="id_ttl_siang_tgl_<?php echo $i?>" type="text" class="input_type ttl_score" style="width: 30px" value="">
          </td>
          <td align="center" width="30px" id="td_ttl_mlm_tgl_<?php echo $i?>">
            <input name="ews_ttl[ttl_mlm_tgl_<?php echo $i?>]" id="id_ttl_mlm_tgl_<?php echo $i?>" type="text" class="input_type ttl_score" style="width: 30px" value="">
          </td>
        <?php endfor;?>
      </tr>
    </table>
    <p>
      <b>Keterangan Tingkat Kesadaran</b>
      <ul>
        <li>A (Alert) Sadar Penuh</li>
        <li>V (Verbal) Berespon dengan kata-kata</li>
        <li>P (Pain) Berespon dengan rangsangan nyeri</li>
        <li>U (Unresponsive) Tidak berespon</li>
      </ul>
    </p>
    <br>
    <span style="font-weight: bold">Respon Klinis terhadap <i>National Early Warning System (NEWS)</i></span>
    <table class="table">
      <tr>
        <th class="center" style="vertical-align: middle !important"  width="70px">Skor</th>
        <th class="center" style="vertical-align: middle !important"  width="100px">Klarifikasi</th>
        <th class="center" style="vertical-align: middle !important" >Respon Klinis</th>
        <th class="center" style="vertical-align: middle !important" >Tindakan</th>
        <th class="center" style="vertical-align: middle !important"  width="120">Frekuensi Monitoring</th>
      </tr>
      <tr style="background: #7ebc18">
        <td align="center" style="vertical-align: middle">0</td>
        <td align="center" style="vertical-align: middle">Sangat Rendah</td>
        <td>Dilakukan Monitoring</td>
        <td>Melanjutkan Monitoring</td>
        <td>Min 12 jam</td>
      </tr>
      <tr style="background: #f6f204">
        <td align="center" style="vertical-align: middle">1-4</td>
        <td align="center" style="vertical-align: middle">Rendah</td>
        <td>Harus segera dievaluasi oleh perawat terdaftar yang kompeten, harus memutuskan apakah perubahan frekuensi pemantauan klinis atau wajib eskalasi perawatan klinis</td>
        <td>Perawat mengassesment atau perawat meningkatkan frekuensi monitoring</td>
        <td>Min 4-6 jam</td>
      </tr>
      <tr style="background: #f6c004">
        <td align="center" style="vertical-align: middle">5-6</td>
        <td align="center" style="vertical-align: middle">Sedang</td>
        <td>Harus segera melakukan tinjauan mendesak oleh klinis yang terampil dengan kompetensi dalam penilaian penyakit akut di bangsal biasanya oleh dokter atau perawat dengan mempertimbangkan apakah eskalasi perawat ke tim perawat kritis diperlukan (yaitu tim penjangkauan perawat kritis)</td>
        <td>Perawat berkolaborasi dengan tim/ pemberian assesmen kegawatan/ meningkatkan perawatan dengan fasilitas monitor yang lengkap</td>
        <td>Min 1 jam</td>
      </tr>

      <tr style="background: #f63904">
        <td align="center" style="vertical-align: middle">>=6</td>
        <td align="center" style="vertical-align: middle">Tinggi</td>
        <td>Herus segera memberikan penilaian darurat secara klinis oleh tim <i>critical care outreach</i> atau code blue dengan kompetensi penanganan pasien kritis dan biasanya terjadi transfer pasien ke area perawatan dengan alat bantu</td>
        <td>Berkolaborasi dengan tim medis/ pemberian assesmen kegawatan/ pindah ruang HCU/ICU</td>
        <td><i>Bad set monitoring/ every time</i></td>
      </tr>
    </table>
    

  </div>
</div>







