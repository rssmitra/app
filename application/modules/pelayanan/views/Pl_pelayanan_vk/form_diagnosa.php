<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script type="text/javascript">
  
  jQuery(function($) {  

    $('.date-picker').datepicker({    
      autoclose: true,    
      todayHighlight: true,
      format: 'yyyy-mm-dd'
    })  

    //show datepicker when clicking on the icon

    .next().on(ace.click_event, function(){    

      $(this).prev().focus();    

    });  

  });

  $('#pl_diagnosa').typeahead({
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
        $('#pl_diagnosa').val(label_item);
        $('#pl_diagnosa_hidden').val(val_item);
      }

  });

  oTablePesanDiagnosa = $('#table-riwayat-diagnosa').DataTable({ 
          
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": false,
    "bPaginate": false,
    "bInfo": false,
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": "pelayanan/Pl_pelayanan_ri/get_riwayat_diagnosa?no_kunjungan=<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>&no_registrasi=<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>",
        "type": "POST"
    },

  });

  function delete_diagnosa(myid){
    preventDefault();
    if(confirm('Are you sure?')){
      $.ajax({
          url: 'pelayanan/Pl_pelayanan/delete_diagnosa',
          type: "post",
          data: {ID:myid},
          dataType: "json",
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
              oTablePesanDiagnosa.ajax.url('pelayanan/Pl_pelayanan_ri/get_riwayat_diagnosa?no_kunjungan=<?php echo $no_kunjungan?>&no_registrasi=<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>').load();
            }else{
              $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
          }

        });

    }else{
      return false;
    }
  }

  function edit_diagnosa() {
    $('#btn_submit_diagnosa').show('fast');
  }

  

</script>

<p><b><i class="fa fa-edit"></i> DIAGNOSA DAN PEMERIKSAAN </b></p>

<!-- <div class="form-group">
    <label class="control-label col-sm-2" for="">Kategori Triase</label>
    <div class="col-sm-2">
      <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'kategori_tindakan')), 3 , 'kategori_tindakan', 'kategori_tindakan', 'form-control', '', '') ?>
    </div>

    <label class="control-label col-sm-2" for="">Jenis Kasus</label>
    <div class="col-sm-4">
      <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_kasus_igd')), '' , 'jenis_kasus_igd', 'jenis_kasus_igd', 'form-control', '', '') ?>
    </div>
</div> -->

<input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">
<input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">

<div class="form-group">
    <label class="control-label col-sm-2" for="">Tgl Pemeriksaan</label>
    <div class="col-sm-2">
      <div class="input-group"> 
          <input name="tgl_periksa" id="tgl_periksa" value="<?php echo isset($riwayat->tgl_periksa)?$this->tanggal->sqlDateTimeToDate($riwayat->tgl_periksa):''?>"  class="form-control date-picker" type="text">
          <span class="input-group-addon">
          <i class="ace-icon fa fa-calendar"></i>
          </span>
      </div>
    </div>
</div>


<div class="form-group">
    <label class="control-label col-sm-2" for="">Diagnosa <span style="color:red">(*)</span></label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Anamnesa</label>
    <div class="col-sm-10">
       <input type="text" class="form-control" name="pl_anamnesa" value="<?php echo isset($riwayat->anamnesa)?$riwayat->anamnesa:''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Pemeriksaan</label>
    <div class="col-sm-10">
        <textarea name="pl_pemeriksaan" id="pl_pemeriksaan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pemeriksaan)?$riwayat->pemeriksaan:''?></textarea>
    </div>
</div>

<div class="form-group" style="margin-top: 3px">
    <label class="control-label col-sm-2" for="">Anjuran Dokter</label>
    <div class="col-sm-10">
      <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pengobatan)?$riwayat->pengobatan:''?></textarea>
    </div>
</div>

<div class="form-group" style="padding-top: 3px;" id="btn_submit_diagnosa">
    <label class="col-sm-2" for="">&nbsp;</label>
    <div class="col-sm-4" style="margin-left:6px">
       <button type="submit" class="btn btn-xs btn-primary" id="btn_save_data"> <i class="fa fa-save"></i> Simpan Data </button>
    </div>
</div>

<hr>

<!-- data table -->
<div class="row">
  <div class="col-sm-12">
    <p><b><i class="fa fa-history"></i> RIWAYAT DIAGNOSA DAN PEMERIKSAAN PASIEN</b></p>
    <table id="table-riwayat-diagnosa" class="table table-bordered table-hover">
      <thead>
        <tr>  
          <th width="80px"></th>
          <th>Tanggal</th>
          <th>Bagian</th>
          <th width="180px">Anamnesa</th>
          <th width="180px">Diagnosa Awal/Akhir</th>
          <th width="180px">Pemeriksaan</th>
          <th width="180px">Pengobatan</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>