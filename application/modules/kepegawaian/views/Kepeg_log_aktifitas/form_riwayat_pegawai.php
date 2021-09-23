<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
    dateFormat: 'yyyy-mm-dd',
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

});

  $(document).ready(function(){

    // type ahead input kota sekolah
    $('#kepeg_rpd_kota').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "Templates/References/getRegenciesPob",
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
        var val_label=item.split(':')[0];

        $('#kepeg_rpd_kota').val(val_label);
            
      }
    });
    
    // datatable riwayat pekerjaan

    oTable = $('#table-riwayat-pekerjaan').DataTable({ 
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
        "ajax": {
          "url": "kepegawaian/Kepeg_riwayat_pekerjaan/get_data?kepeg_id="+$('#kepeg_id').val()+"",
          "type": "POST"
        },
    });

    // datatable riwayat pendidikan
    oTablePendidikan = $('#table-riwayat-pendidikan').DataTable({       
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
        "ajax": {
          "url": "kepegawaian/Kepeg_riwayat_pendidikan/get_data?kepeg_id="+$('#kepeg_id_frm_rpd').val()+"",
          "type": "POST"
        },  
    });



    
    // Reload table riwayat pekerjaan after process success
      $('#form_kepeg_riwayat_pekerjaan').ajaxForm({
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
            // jika berhasil maka reload table
            oTable.ajax.url("kepegawaian/Kepeg_riwayat_pekerjaan/get_data?kepeg_id="+$('#kepeg_id').val()+"").load();
            // reset form
            $('#form_kepeg_riwayat_pekerjaan')[0].reset()
          }else{
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
          }
          achtungHideLoader();
          }
        });

    // Reload table riwayat pendidikan after process succes
      $('#form_kepeg_riwayat_pendidikan').ajaxForm({
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
            // jika berhasil maka reload table
            oTablePendidikan.ajax.url("kepegawaian/Kepeg_riwayat_pendidikan/get_data?kepeg_id="+$('#kepeg_id').val()+"").load();
            // reset form
            $('#form_kepeg_riwayat_pendidikan')[0].reset()
          }else{
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
          }
          achtungHideLoader();
          }
      });

});

// end of document ready



  // Update riwayat pekerjaan pegawai
  function update_row_pekerjaan(kepeg_rpj_id){
    preventDefault();
    // get data by id
    $.getJSON("<?php echo site_url('kepegawaian/Kepeg_riwayat_pekerjaan/get_data_by_id') ?>/" + kepeg_rpj_id, '', function (response) {                        
      console.log(response);
      $('#kepeg_id').val(response.kepeg_id);
      $('#kepeg_rpj_id').val(response.kepeg_rpj_id);
      $('#kepeg_rpj_nama_perusahaan').val(response.kepeg_rpj_nama_perusahaan);
      $('#kepeg_rpj_jabatan').val(response.kepeg_rpj_jabatan);
      $('#kepeg_rpj_dari_tahun').val(response.kepeg_rpj_dari_tahun);
      $('#kepeg_rpj_sd_tahun').val(response.kepeg_rpj_sd_tahun);
      $('#kepeg_rpj_deskripsi_pekerjaan').val(response.kepeg_rpj_deskripsi_pekerjaan);

    }); 
  }

  // Update riwayat pendidikan pegawai
  function update_row_pendidikan(kepeg_rpd_id){
    preventDefault();
    // get data by id
    $.getJSON("<?php echo site_url('kepegawaian/Kepeg_riwayat_pendidikan/get_data_by_id') ?>/" + kepeg_rpd_id, '', function (response) {                        
      console.log(response);
      $('#kepeg_id_frm_rpd').val(response.kepeg_id);
      $('#kepeg_rpd_id').val(response.kepeg_rpd_id);
      $('#kepeg_rpd_nama_sekolah').val(response.kepeg_rpd_nama_sekolah);
      $('#kepeg_rpd_kota').val(response.kepeg_rpd_kota);
      $('#kepeg_rpd_jenjang_pendidikan').val(response.kepeg_rpd_jenjang_pendidikan);
      $('#kepeg_rpd_nilai_akhir').val(response.kepeg_rpd_nilai_akhir);
      $('#kepeg_rpd_tahun_lulus').val(response.kepeg_rpd_tahun_lulus);

    }); 
  }

  // Delete riwayat pekerjaan pegawai
  function delete_row(myid){
    if(confirm('Are you sure?')){
      preventDefault();
      $.ajax({
          url: 'kepegawaian/Kepeg_riwayat_pekerjaan/delete',
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
              oTable.ajax.url("kepegawaian/Kepeg_riwayat_pekerjaan/get_data?kepeg_id="+$('#kepeg_id').val()+"").load();
              $('#form_kepeg_riwayat_pekerjaan')[0].reset();
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

  // Delete riwayat pendidikan pegawai
  function delete_row_pendidikan(myid){
    if(confirm('Are you sure?')){
      preventDefault();
      $.ajax({
          url: 'kepegawaian/Kepeg_riwayat_pendidikan/delete',
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
              oTablePendidikan.ajax.url("kepegawaian/Kepeg_riwayat_pendidikan/get_data?kepeg_id="+$('#kepeg_id').val()+"").load();
              $('#form_kepeg_riwayat_pendidikan')[0].reset();
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

</script>


<style>
  .style_data tr:nth-child(odd) {
    background: #edf3f4;
  } 
  .style_data tr {
    line-height: 2.0;
    padding-left: 6px;
  }
  .style_data tbody tr td {
    padding-left : 6px !important;
  }
</style>


<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    
    <!-- PAGE CONTENT BEGINS -->

    <div class="widget-body">
      <div class="widget-main no-padding">
          <!-- profile pegawai -->
            <div class="col-md-12">
            <p><b>DATA PEGAWAI</b></p>
            </div>  
            <div class="col-xs-2">
                <img id="" class="" style="width: 100%" alt="Foto Pegawai" src="<?php echo isset($value->pas_foto)?base_url().PATH_PHOTO_PEGAWAI.$value->pas_foto:'' ?>" />
            </div>

            <div class="col-xs-5">
                <table class="style_data td_field">
                <tbody>
                  <tr>
                    <td class="">NIK</td>
                    <td> : <?php echo isset($value->nik)?$value->nik:'' ?></td>
                  </tr>

                  <tr>
                  <td class="">Nama Pegawai</td>
                    <td> : <?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'' ?></td>
                  </tr>
                  
                  <tr>
                    <td width="200px">TTL</td>
                    <td> : <?php echo isset($value->tmp_lahir, $value->tgl_lahir)?$value->tmp_lahir.', '.$value->tgl_lahir:'' ?></td>
                  </tr>
                  
                  <tr>
                    <td>Agama</td>
                    <td> : <?php echo isset($value->religion)?$value->religion:'' ?></td>
                  </tr>
                  
                  <tr>
                    <td>No Telpon / HP</td>
                    <td> : <?php echo isset($value->kepeg_no_telp)?$value->kepeg_no_telp:'' ?></td>
                  </tr>
                  
                  <tr>
                    <td>E-mail</td>
                    <td> : <?php echo isset($value->kepeg_email)?$value->kepeg_email:'' ?></td>
                  </tr>

                  <tr>
                    <td>Alamat</td>
                    <td width="500px"> : <?php echo isset($value->alamat, $value->rt, $value->rw, $value->nama_kelurahan, $value->nama_kecamatan, $value->nama_kota, $value->nama_provinsi, $value->kode_pos)?$value->alamat .' RT '.$value->rt.' RW '.$value->rw.', Kel. '.$value->nama_kelurahan.', Kec. '.$value->nama_kecamatan.', '.$value->nama_kota.', Prov. '.$value->nama_provinsi.', '.$value->kode_pos :''?></td>
                  </tr>
                </tbody>
                </table>
            </div>
            
          <!-- data kepegawaian -->
            <div class="col-xs-5">
                <table class="style_data">
                  <tr>
                    <td>NIP</td>
                    <td> : <?php echo isset($value->kepeg_nip)?$value->kepeg_nip:'' ?></td>
                  </tr>

                  <tr>
                    <td>Unit / Bagian</td>
                    <td> : <?php echo isset($value->nama_unit)?$value->nama_unit:'' ?></td>
                  </tr>

                  <tr>
                    <td>Jabatan</td>
                    <td> : <?php echo isset($value->nama_level)?$value->nama_level:'' ?></td>
                  </tr>

                  <tr>
                    <td>Golongan</td>
                    <td> : <?php echo isset($value->kepeg_gol)?$value->kepeg_gol:'' ?></td>
                  </tr>

                  <tr>
                    <td>Jenis Pegawai</td>
                    <td width="200px"> : <?php echo isset($value->kepeg_tenaga_medis)?($value->kepeg_tenaga_medis!='non medis')?'Tenaga Medis':'Tenaga Non Medis':'' ?></td>
                  </tr>

                  <tr>
                    <td width="150px">Status Kepegawaian</td>
                    <td> : <?php echo isset($value->kepeg_status_kerja)?($value->kepeg_status_kerja==='211')?'Pegawai Tetap':'Pegawai KKWT':'' ?></td>
                  </tr>

                  <tr>
                    <td>Status Aktif</td>
                    <td> : <?php echo isset($value->kepeg_status_aktif)?($value->kepeg_status_aktif==='Y')?'Aktif':'Tidak Aktif':'' ?></td>
                  </tr>

                </table>
            </div>

          <!-- form riwayat pekerjaan -->
          <form class="form-horizontal" method="post" id="form_kepeg_riwayat_pekerjaan" action="<?php echo site_url('kepegawaian/Kepeg_riwayat_pekerjaan/process')?>" enctype="multipart/form-data" autocomplete="off">
            <br>
            <!-- hidden form -->
            <input type="hidden" name="kepeg_id" id="kepeg_id" value="<?php echo $value->kepeg_id?>">
            
            <input type="text" name="kepeg_rpj_id" id="kepeg_rpj_id" style="display:none" value="<?php echo isset($value->kepeg_rpj_id)?$value->kepeg_rpj_id:''?>">
            
            <div class="col-md-12">
              <p style="padding-top: 30px"><b>FORM RIWAYAT PEKERJAAN PEGAWAI</b></p>
              <div class="form-group">
                <label class="control-label col-md-2">Nama Perusahaan</label>
                <div class="col-md-5">
                  <input name="kepeg_rpj_nama_perusahaan" id="kepeg_rpj_nama_perusahaan" value="<?php echo isset($value->kepeg_rpj_nama_perusahaan)?$value->kepeg_rpj_nama_perusahaan:''?>" class="form-control" type="text">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2">Jabatan</label>
                  <div class="col-md-2">
                    <input name="kepeg_rpj_jabatan" id="kepeg_rpj_jabatan" value="<?php echo isset($value->kepeg_rpj_jabatan)?$value->kepeg_rpj_jabatan:''?>" class="form-control" type="text">
                  </div>
                  <label class="control-label col-md-1">Tahun Kerja</label>
                  <div class="col-md-1">
                    <input name="kepeg_rpj_dari_tahun" id="kepeg_rpj_dari_tahun" value="<?php echo isset($value->kepeg_rpj_dari_tahun)?$value->kepeg_rpj_dari_tahun:''?>" class="form-control" type="text">
                  </div>
                  <label class="control-label col-md-1">s.d Tahun</label>
                  <div class="col-md-1">
                    <input name="kepeg_rpj_sd_tahun" id="kepeg_rpj_sd_tahun" value="<?php echo isset($value->kepeg_rpj_sd_tahun)?$value->kepeg_rpj_sd_tahun:''?>" class="form-control" type="text">
                  </div>
              </div>               
              <div class="form-group">
                <label class="control-label col-md-2">Deskripsi Pekerjaan</label>
                  <div class="col-md-5">
                    <textarea name="kepeg_rpj_deskripsi_pekerjaan" id="kepeg_rpj_deskripsi_pekerjaan" value="" class="form-control" style="height: 50px !important;"><?php echo isset($value->kepeg_rpj_deskripsi_pekerjaan)?$value->kepeg_rpj_deskripsi_pekerjaan:''?></textarea>
                  </div>              
              </div> 
              <div class="form-group">
                <label class="col-md-2"></label>
                  <div class="col-md-5" style="padding-left: 18px; padding-top: 3px;">
                  <button type="submit" id="btnSave" name="submit" class="btn btn-xs btn-info">
                  <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                  Submit
                  </button>
                  <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                  <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                  Reset
                </button>
                  </div>              
              </div> 
            </div>
          </form> 

          <hr class="separator">
          
          <div class="col-xs-12" style="margin-top: 8px;">
            <table id="table-riwayat-pekerjaan" base-url="kepegawaian/Kepeg_riwayat_pekerjaan" class="table table-bordered table-hover" >
              <thead>
                <tr style="background-color: #c7cccb;">  
                  <th width="30px" height="32px" class="center" >No</th>
                  <th>Nama Perusahaan</th>
                  <th width="200px" class="center">Jabatan</th>
                  <th width="200px" class="center">Tahun Bekerja</th>
                  <th width="200px" class="center">Deskripsi Pekerjaan</th>
                  <th width="80px" class="center">Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          
          <!-- form riwayat pendidikan -->
          <form class="form-horizontal" method="post" id="form_kepeg_riwayat_pendidikan" action="<?php echo site_url('kepegawaian/Kepeg_riwayat_pendidikan/process')?>" enctype="multipart/form-data" autocomplete="off">
            
            <!-- hidden form -->
            <input type="hidden" name="kepeg_id_frm_rpd" id="kepeg_id_frm_rpd" value="<?php echo $value->kepeg_id?>">
            <input type="text" name="kepeg_rpd_id" id="kepeg_rpd_id" style="display:none" value="<?php echo isset($value->kepeg_rpd_id)?$value->kepeg_rpd_id:''?>">
            
            <div class="col-md-12">
              <p style="padding-top: 10px; padding-bottom:3px;"><b>FORM RIWAYAT PENDIDIKAN PEGAWAI</b></p>
              <div class="form-group">
                <label class="control-label col-md-2">Nama Sekolah</label>
                <div class="col-md-4">
                  <input name="kepeg_rpd_nama_sekolah" id="kepeg_rpd_nama_sekolah" value="<?php echo isset($value->kepeg_rpd_nama_sekolah)?$value->kepeg_rpd_nama_sekolah:''?>" class="form-control" type="text">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2">Kota</label>
                  <div class="col-md-2">
                    <input name="kepeg_rpd_kota" id="kepeg_rpd_kota" value="<?php echo isset($value->kepeg_rpd_kota)?$value->kepeg_rpd_kota:''?>" class="form-control" type="text">
                  </div>
                  <label class="control-label col-md-2">Jenjang Pendidikan</label>
                  <div class="col-md-2">
                    <?php echo $this->master->custom_selection($params = array('table' => 'mst_education', 'id' => 'education_name', 'name' => 'education_name', 'where' => array('is_active' => 'Y')), isset($value->kepeg_rpd_jenjang_pendidikan)?$value->kepeg_rpd_jenjang_pendidikan:'' , 'kepeg_rpd_jenjang_pendidikan', 'kepeg_rpd_jenjang_pendidikan', 'form-control', '', '') ?>
                  </div>
              </div>               
              <div class="form-group">
                <label class="control-label col-md-2">Nilai Akhir</label>
                  <div class="col-md-2">
                    <input name="kepeg_rpd_nilai_akhir" id="kepeg_rpd_nilai_akhir" value="<?php echo isset($value->kepeg_rpd_nilai_akhir)?$value->kepeg_rpd_nilai_akhir:''?>" class="form-control" type="text">
                  </div>
                  <label class="control-label col-md-2">Tahun Lulus</label>
                  <div class="col-md-2">
                    <input name="kepeg_rpd_tahun_lulus" id="kepeg_rpd_tahun_lulus" value="<?php echo isset($value->kepeg_rpd_tahun_lulus)?$value->kepeg_rpd_tahun_lulus:''?>" class="form-control" type="text">
                  </div>
              </div> 
              <div class="form-group">
                <label class="col-md-2"></label>
                  <div class="col-md-5" style="padding-left: 18px; padding-top: 2px;">
                  <button type="submit" id="btnSave" name="submit" class="btn btn-xs btn-info">
                  <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                  Submit
                  </button>
                  <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                  <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                  Reset
                </button>
                  </div>              
              </div> 
            </div>
          </form> 

          <hr class="separator">
          
          <div class="col-xs-12" style="margin-top: 8px;">
            <table id="table-riwayat-pendidikan" base-url="kepegawaian/Kepeg_riwayat_pendidikan" class="table table-bordered table-hover" >
              <thead>
                <tr style="background-color: #c7cccb;">  
                  <th width="30px" height="32px" class="center" >No</th>
                  <th>Nama Sekolah</th>
                  <th width="200px" class="center">Kota</th>
                  <th width="200px" class="center">Jenjang Pendidikan</th>
                  <th width="200px" class="center">Nilai Akhir</th>
                  <th width="200px" class="center">Tahun Lulus</th>
                  <th width="80px" class="center">Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
      </div>
    </div>
    
    <!-- PAGE CONTENT ENDS -->

  </div><!-- /.col -->
</div><!-- /.row -->


