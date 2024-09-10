<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script type="text/javascript">

jQuery(function($) {  

  $('.date-picker').datepicker({  
    autoclose: true,    
    todayHighlight: true    
  })  
  .next().on(ace.click_event, function(){   
    $(this).prev().focus();    
  });  

});

$(document).ready(function(){
  
  $('#form_cuti_dr').ajaxForm({
    beforeSend: function() {
      achtungShowLoader();  
    },
    uploadProgress: function(event, position, total, percentComplete) {
    },
    complete: function(xhr) {     
      var data=xhr.responseText;
      var jsonResponse = JSON.parse(data);

      if(jsonResponse.status === 200){
        $.achtung({message: jsonResponse.message, timeout:5});
        oTableCutiDr.ajax.reload();
        // reset form
        $('#form_cuti_dr')[0].reset();
      }else{
        $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
      }
      achtungHideLoader();
    }
  }); 

  oTableCutiDr = $('#datatable-cuti-dr').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bLengthChange": false,
      // "pageLength": 25,
      "bInfo": false,
      "paging": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#datatable-cuti-dr').attr('base-url'),
          "type": "POST"
      },
  });
  

})

$('select[name="klinik_rajal"]').change(function () {      
    if ($(this).val()) {   
      $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val(), '', function (data) {              
          $('#dokter_rajal option').remove();                
          $('<option value="">-Pilih Dokter-</option>').appendTo($('#dokter_rajal'));                
          $.each(data, function (i, o) {                  
              $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#dokter_rajal'));                    
          });                
      });   
    } else {    
        $('#dokter_rajal option').remove()      
    }        
});

function show_data(id){
  preventDefault();
  $.getJSON("<?php echo site_url('information/Regon_info_jadwal_dr/show_data_cuti') ?>/"+id, '' , function (response) {
      $('#cuti_id').val(response.cuti_id);
      $('#from_tgl').val(response.from_tgl);
      $('#to_tgl').val(response.to_tgl);
      $('#klinik_rajal').val(response.kode_bag);
      $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + response.kode_bag, '', function (data) {              
          $('#dokter_rajal option').remove();                
          $('<option value="">-Pilih Dokter-</option>').appendTo($('#dokter_rajal'));                
          $.each(data, function (i, o) {      
              var selected = (o.kode_dokter == response.kode_dr) ? 'selected' : '';
              $('<option value="' + o.kode_dokter + '" '+selected+'>' + o.nama_pegawai + '</option>').appendTo($('#dokter_rajal'));                    
          });                
      }); 
      $('#dokter_rajal').val(response.kode_dr);
      $('#keterangan_cuti').val(response.keterangan_cuti);
  })
}

function delete_data_cuti(id){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'information/Regon_info_jadwal_dr/delete_data_cuti',
        type: "post",
        data: { ID : id },
        dataType: "json",
        beforeSend: function() { 
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            $.achtung({message: jsonResponse.message, timeout:5});
            oTableCutiDr.ajax.reload();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
        }

      });

  }else{
    return false;
  }
}


</script>

<form class="form-horizontal" method="post" id="form_cuti_dr" action="<?php echo site_url('information/Regon_info_jadwal_dr/process_cuti_dr')?>" enctype="multipart/form-data">   

<p style="margin-top:5px"><b>Form Cuti Dokter </b></p>

<!-- hidden -->
<input type="hidden" name="cuti_id" id="cuti_id" value="">

<div class="form-group">
  <label class="control-label col-sm-1" for="">*Klinik</label>
  <div class="col-sm-3">
      <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1, 'is_public' => 1)), '' , 'klinik_rajal', 'klinik_rajal', 'form-control', '', '') ?>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-sm-1" for="City">*Dokter</label>
  <div class="col-sm-3">
      <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'dokter_rajal', 'dokter_rajal', 'form-control', '', '') ?>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-sm-1">Tanggal</label>
  <div class="col-md-2">
    <div class="input-group">
        <input name="from_tgl" id="from_tgl" value="" placeholder="<?php echo date('Y-m-d')?>" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd">
        <span class="input-group-addon">
          <i class="ace-icon fa fa-calendar"></i>
        </span>
      </div>
  </div>
  <label class="control-label col-sm-1">s.d Tanggal</label>
  <div class="col-md-2">
    <div class="input-group">
        <input name="to_tgl" id="to_tgl" value="" placeholder="<?php echo date('Y-m-d')?>" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd">
        <span class="input-group-addon">
          <i class="ace-icon fa fa-calendar"></i>
        </span>
      </div>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-sm-1" for="City">Keterangan</label>
  <div class="col-sm-6">
      <textarea name="keterangan_cuti" id="keterangan_cuti" class="form-control" style="height: 50px !important"></textarea>
  </div>
</div>

<button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
  <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
  Submit
</button>
<hr>
<div>
  <table id="datatable-cuti-dr" base-url="information/Regon_info_jadwal_dr/get_data_cuti_dr" class="table table-bordered table-hover">
    <thead>
    <tr>  
      <th width="30px" class="center">No</th>
      <th class="center" width="100px">#</th>
      <th width="250px">Nama Dokter</th>
      <th width="250px">Poliklinik</th>
      <th width="120px">Tanggal Cuti</th>
      <th width="120px">s.d Tanggal</th>
      <th>Keterangan Cuti</th> 
      <th width="100px">Status</th> 
    </tr>
  </thead>
  <tbody>
  </tbody>
  </table>
</div>


    
</form>