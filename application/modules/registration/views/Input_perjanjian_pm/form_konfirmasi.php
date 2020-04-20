<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});


$(document).ready(function(){

    $('#form_konfirmasi').ajaxForm({      

      beforeSend: function() {        

        achtungShowFadeIn();          

      },      

      uploadProgress: function(event, position, total, percentComplete) {        

      },      

      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          $.achtung({message: jsonResponse.message, timeout:5});          

          setTimeout(function(){
            // PopupCenter(jsonResponse.redirect, 'SURAT KONTROL PASIEN', 850, 500);
            $('#page-area-content').load('registration/Input_perjanjian_pm');

          },1800);


        }else{          

          $.achtung({message: jsonResponse.message, timeout:5});          

        }        

        achtungHideLoader();        

      }      

    });     

})

</script>

<div class="row">

  <div class="col-xs-12">  

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->
      
    <form class="form-horizontal" method="post" id="form_konfirmasi" action="<?php echo site_url('registration/Input_perjanjian_pm/process_konfirmasi_kedatangan')?>" enctype="multipart/form-data" autocomplete="off">   

      <!-- hidden form  -->
      <input type="hiddenxx" name="arrr_ids" id="arr_ids" value="<?php echo $ids?>">
      
      <div class="form-group" id="tanggal_perjanjian" >
          <label class="control-label col-sm-2">Tanggal Kunjungan</label>  
          <div class="col-md-2">
              <div class="input-group">
                  <input name="tgl_kunjungan" id="tgl_kunjungan" value="" placeholder="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text">
                  <span class="input-group-addon">
                  <i class="ace-icon fa fa-calendar"></i>
                  </span>
              </div>
          </div>
      </div>
      <div class="clearfix"></div>
      <hr>
      <p style="font-weight: bold">DAFTAR PASIEN TERKONFIRMASI</p>
      <table class="table table-bordered" width="80%">
        <thead>
          <tr>
            <th class="center">No</th>
            <th>Nama Pasien</th>
            <th>Penjamin</th>
            <th>Dokter</th>
            <th>Nama Tindakan</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; foreach ($value as $key => $val_dt) : $no++; ?>
          <tr>
            <td class="center"><?php echo $no?></td>
            <td><?php echo $val_dt->nama?></td>
            <td><?php echo ($val_dt->nama_perusahaan==NULL)?'<div class="left">PRIBADI/UMUM</div>':'<div class="left">'.$val_dt->nama_perusahaan.'</div>'?></td>
            <td><?php echo $val_dt->nama_pegawai?></td>
            <td><?php echo $val_dt->nama_tarif?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="clearfix"></div>
        <div class="form-actions center">
            <button type="button" onclick="getMenu('registration/Input_perjanjian_pm')" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Close</button>
            <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-primary">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
            </button>
        </div>
          
      </form>


  </div><!-- /.col -->

</div><!-- /.row -->

