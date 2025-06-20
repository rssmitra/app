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

  $('#jam_obat').timepicker({
    minuteStep: 1,
    showSeconds: false,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#jam_obat').timepicker('showWidget');
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

  $('#nama_obat').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getObatByBagianAutoCompleteNoInfoStok",
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
  
  tbl_pemberian_obat_parenteral = $('#tbl_pemberian_obat_parenteral').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": true,
      "bInfo": false,
      "pageLength": 5,
      "dom": 'rtip',
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_row_data_pemberian_obat?no_kunjungan="+$('#no_kunjungan').val()+"&flag=parenteral",
          "type": "POST"
      },

  });

  tbl_pemberian_obat_enteral = $('#tbl_pemberian_obat_enteral').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": true,
      "bInfo": false,
      "pageLength": 5,
      "dom": 'rtip',
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_row_data_pemberian_obat?no_kunjungan="+$('#no_kunjungan').val()+"&flag=non_parenteral",
          "type": "POST"
      },

  });

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
              
              if(jsonResponse.jenis_terapi == 'non_parenteral'){
                tbl_pemberian_obat_enteral.ajax.reload();
              }else{
                tbl_pemberian_obat_parenteral.ajax.reload(); 
              }
              $('#form_pelayanan')[0].reset();
              $( 'input[type="checkbox"]' ).prop('checked', false);

              $.achtung({message: jsonResponse.message, timeout:5});  
            }else{           
              $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
            }        
            achtungHideLoader();        
          } 
      });

    });

});

function set_line_through(id, status, flag){
  preventDefault();
  $.getJSON('pelayanan/Pl_pelayanan_ri/update_status_dt_monitoring', {ID: id, table: 'th_monitor_pemberian_obat', deleted : status} , function(response_data) {
    if(response_data.status === 200){
      if(flag == 'non_parenteral'){
        tbl_pemberian_obat_enteral.ajax.reload();
      }else{
        tbl_pemberian_obat_parenteral.ajax.reload(); 
      }
    } 
  });
}

function update_pelaksanaan_pemberian_obat(id, waktu, value){
  // preventDefault();
  console.log(id, value);
  $.getJSON('pelayanan/Pl_pelayanan_ri/update_pelaksanaan_pemberian_obat', {ID: id, status : value, waktu : waktu} , function(response_data) {
    if(response_data.status === 200){
      if(response_data.jenis_terapi == 'non_parenteral'){
        tbl_pemberian_obat_enteral.ajax.reload();
      }else{
        tbl_pemberian_obat_parenteral.ajax.reload(); 
      }
    }
  });
}

function upadte_status_pemberian_obat(id, value){
  // preventDefault();
  console.log(id, value);
  $.getJSON('pelayanan/Pl_pelayanan_ri/update_status_pemberian_obat', {ID: id, val : value} , function(response_data) {
    
    if(response_data.status === 200){
      if(response_data.jenis_terapi == 'non_parenteral'){
        tbl_pemberian_obat_enteral.ajax.reload();
      }else{
        tbl_pemberian_obat_parenteral.ajax.reload(); 
      }
    }
  });
}

function showModalTTD(id, flag)
{  
  preventDefault();
  noMr = $('#noMrPasienPemberianObat').val();
  if (noMr == '') {
    alert('Silahkan cari pasien terlebih dahulu !'); return false;
  }else{
    $('#title_ttd_persetujuan').text('Tanda Tangan '+flag.toUpperCase()+' untuk Persetujuan Pemberian Obat');
    $('#id_pemberian_obat').val(id);
    $('#flag_ttd').val(flag);
    $('#form_pasien_modal_ttd').load('registration/reg_pasien/form_modal_ttd/'+noMr+''); 
    $("#modalTTDPersetujuanPemberianObat").modal();
  }
}

$('#save_ttd_pasien_form').click(function (e) {
    e.preventDefault();
    $.ajax({
    url: 'pelayanan/Pl_pelayanan_ri/process_save_ttd_pemberian_obat',
    type: "post",
    data: {id : $('#id_pemberian_obat').val(), flag: $('#flag_ttd').val(),signature: $('#paramsSignature').val()},
    dataType: "json",
    beforeSend: function() {
      achtungShowLoader();  
    },
    success: function(data) {
      achtungHideLoader();
      $('#modalTTDPersetujuanPemberianObat').modal('hide');
      if(data.status == 200){
        if($('#flag_ttd').val() == 'perawat'){
          $('#ttd_perawat_id_'+$('#id_pemberian_obat').val()+'').html('<img src="'+data.signature+'" style="width: 100% !important">');
        }else{
          $('#ttd_pasien_id_'+$('#id_pemberian_obat').val()+'').html('<img src="'+data.signature+'" style="width: 100% !important">');
        }
      }
    }
  });
});

function edit_row(id, flag){
  preventDefault();
  $.getJSON('pelayanan/Pl_pelayanan_ri/get_data_pemberian_obat_by_id', {ID: id} , function(response_data) {
    
    var obj = response_data.data;
    if(response_data.status == 200){
      // load data from response
      $('#id_pemberian_obat').val(obj.id);
      $('#tgl_obat').val(obj.tgl_obat);
      $('#jam_obat').val(obj.jam_obat);
      $('#nama_obat').val(obj.nama_obat);
      $('#kode_brg').val(obj.kode_brg);
      $('#dosis').val(obj.dosis);
      $('#frek').val(obj.frek);
      $('#rute').val(obj.rute);
      $('#jenis_terapi').val(obj.jenis_terapi);
      $('#catatan_obat').val(obj.catatan);
      // loop waktu pemberian obat
      var waktu = JSON.parse(obj.waktu);
      $.each(waktu, function (i, o) {
        $('#jam_'+i+'').val(o.jam);
        $('#catatan_'+i+'').val(o.catatan);
        $('#waktu_'+i+'').prop('checked', true);
      });
      
    }else{
      $.achtung({message: response_data.message, timeout:5, className: 'achtungFail'});
    }
  });
}

</script>


<div class="row">
  <div class="col-md-12">
    <h3 class="header smaller lighter blue" >
      RENCANA DAN PELAKSANAAN PEMBERIAN OBAT
    </h3>
    <input type="hidden" name="noMrPasienPemberianObat" id="noMrPasienPemberianObat" value="<?php echo $no_mr?>">

    <div class="form-group">
        <label class="control-label col-sm-1" for="">*Tanggal/Jam</label>
        <div class="col-md-3">
          <div class="input-group">
              <input name="tgl_obat" id="tgl_obat" placeholder="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>">
              <span class="input-group-addon">
                <i class="ace-icon fa fa-calendar"></i>
              </span>
              <input id="jam_obat" name="jam_obat"  type="text" class="form-control">
              <span class="input-group-addon">
                <i class="fa fa-clock-o bigger-110"></i>
              </span>
          </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-1">Nama Obat</label>
        <div class="col-md-6">
          <input type="text" class="form-control" name="nama_obat" id="nama_obat" value="">
          <input type="hidden" class="form-control" name="kode_brg" id="kode_brg" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-1">Dosis</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="dosis" id="dosis" value="">
        </div>
        <label class="control-label col-sm-1">Frek</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="frek" id="frek" value="">
        </div>
        <label class="control-label col-sm-1">Rute</label>
        <div class="col-md-1">
          <input type="text" class="form-control" name="rute" id="rute" value="">
        </div>
        <label class="control-label col-sm-1">Jenis Terapi</label>
        <div class="col-md-2">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_terapi')), '' , 'jenis_terapi', 'jenis_terapi', 'form-control', '', '');?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-1">Catatan</label>
        <div class="col-md-6">
          <textarea class="form-control" name="catatan_obat" id="catatan_obat" style="height: 50px !important"></textarea>
        </div>
    </div>
    <!-- Waktu Pemberian Obat -->
    <div style="font-size: 14px; font-weight: bold; text-decoration: underline; padding: 10px !important">WAKTU PEMBERIAN OBAT</div>
    
    <?php foreach($waktu as $row) : ?>
    <div class="form-group">
        <div class="col-md-1">
          <label>
              <input type="checkbox" class="ace" name="waktu[<?php echo $row->value?>]" id="<?php echo 'waktu_'.$row->value?>" value="<?php echo $row->value?>">
              <span class="lbl"> <?php echo $row->label?></span>
          </label>
        </div>
        <label class="control-label col-sm-1">Jam</label>
        <div class="col-md-2">
          <input type="time" name="jam[<?php echo $row->value?>]" class="form-control" id="jam_<?php echo $row->value?>"/>
        </div>
        <label class="control-label col-sm-1">Catatan</label>
        <div class="col-md-4">
          <input type="text" name="catatan[<?php echo $row->value?>]" class="form-control" placeholder="Catatan <?php echo $row->label?>" id="catatan_<?php echo $row->value?>" />
        </div>
    </div>
    <?php endforeach;?>


    <div class="form-group">
        <div class="col-md-12">
          <a href="#" class="btn btn-xs btn-primary" id="btn_save_pemberian_obat">Simpan</a>
        </div>
    </div>
    <hr>
    <h3 class="header smaller lighter blue" >
      OBAT PARENTERAL
    </h3>

    <table class="table" id="tbl_pemberian_obat_parenteral">
      <thead>
        <tr style="background: #f3f3f3">
          <th width="30px" align="center">No</th>
          <th width="100px">Waktu Input</th>
          <th width="200px" class="left">Nama Obat</th>
          <?php foreach($waktu as $row) : ?>
          <th class="center" width="150px"><?php echo $row->label?></th>
          <?php endforeach; ?>
          <th class="center" style="width: 80px !important">Ttd Perawat</th>
          <th class="center" style="width: 80px !important">Ttd Keluarga Pasien</th>
          <th class="center" width="120px">Catatan</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    
    <h3 class="header smaller lighter blue" >
      OBAT NON PARENTERAL
    </h3>
    <table class="table" id="tbl_pemberian_obat_enteral">
      <thead>
        <tr style="background: #f3f3f3">
          <th width="30px" align="center">No</th>
          <th width="100px">Waktu Input</th>
          <th width="200px" class="left">Nama Obat</th>
          <?php foreach($waktu as $row) : ?>
          <th class="center" width="150px"><?php echo $row->label?></th>
          <?php endforeach; ?>
          <th class="center">Ttd Perawat</th>
          <th class="center">Ttd Keluarga Pasien</th>
          <th class="center" width="120px">Catatan</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>

    <span style="font-style: italic; font-size: 11px"><b>Keterangan :</b> Jadwal pemberian obat tiap shift harus ditandatangani oleh perawat dan keluarga pasien.</span>
    

  </div>
</div>

<div id="modalTTDPersetujuanPemberianObat" class="modal fade" tabindex="-1">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:95%">
    <div class="modal-content">
      <div class="modal-header">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
          </button>
          <span id="title_ttd_persetujuan">TANDA TANGAN PASIEN (DIGITAL SIGNATURE)</span>
        </div>
      </div>

      <div class="modal-body">                                 
        <div id="form_pasien_modal_ttd"></div>
        <input type="hidden" name="id_pemberian_obat" id="id_pemberian_obat" value="">
        <input type="hidden" name="flag_ttd" id="flag_ttd" value="">
        <button type="button" id="save_ttd_pasien_form" name="submit" class="btn btn-xs btn-primary">
          <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
          Submit
        </button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>







