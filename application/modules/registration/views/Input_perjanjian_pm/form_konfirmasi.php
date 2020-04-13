<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

jQuery(function($) {  

  
  $(".date-picker").datepicker({

    autoclose: true,    

    todayHighlight: true,

    onSelect: function(dateText) {
      $(this).change();
    }
  
  }).on("change", function() {
    
    var str_selected_date = this.value;
    var selected_date = str_selected_date.split("/").join("-");
    var spesialis = $('#klinik_rajal').val();
    var dokter = $('#dokter_rajal').val();
    var jd_id = $('#jd_id').val();
    /*check selected date */

    $.post('<?php echo site_url('Templates/References/CheckSelectedDate') ?>', {date:selected_date, kode_spesialis:spesialis, kode_dokter:dokter, jadwal_id:jd_id} , function(data) {
        // Do something with the request
        if(data.status=='expired'){
           var message = '<div class="alert alert-danger"><strong>Expired Date !</strong><br>Tanggal yang anda pilih sudah lewat atau sedang berjalan.</div>';
           $('#view_msg_kuota').hide('fast');
        }else{
          if(data.day!=$('#selected_day').val() ){
                var message = '<div class="alert alert-danger"><strong>Tidak Sesuai !</strong><br>Tanggal Kunjungan tidak sesuai dengan jadwal Praktek Dokter yang anda pilih !</div>';
                $('#view_msg_kuota').hide('fast');
          }else{
            var message = '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><p><strong><i class="ace-icon fa fa-check"></i> Selesai ! </strong>Apakah anda akan melanjutkan ke proses berikutnya ?</p><p><button type="submit" id="btnSave" class="btn btn-sm btn-success">Lanjutkan</button><a href="#" onclick="getMenu('+"'"+'booking/regon_booking'+"'"+')" class="btn btn-sm btn-danger">Batalkan</a></p></div>';

            if(data.sisa > 0 ){
              var msg_kuota = '*Kuota tersedia pada tanggal ini, '+data.sisa+' pasien';
            }else{
              var msg_kuota = '<span style="color:red"> *Kuota penuh, silahkan cari tanggal lain!</span>';
            }

            $('#view_msg_kuota').show('fast');
            $('#view_msg_kuota').html(msg_kuota);

          }

        }

        $('#view_last_message').show('fast');
        $('#view_last_message').html(message);
        $("html, body").animate({ scrollTop: "700px" }, "slow");  

    }, 'json');

  });
 
});

$(document).ready(function(){

    $('#form_booking').ajaxForm({      

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
      
    <form class="form-horizontal" method="post" id="form_booking" action="<?php echo site_url('registration/Reg_pasien/process_perjanjian')?>" enctype="multipart/form-data" autocomplete="off">   

      <!-- hidden form  -->
      <input type="hidden" name="arrr_ids" id="arr_ids" value="<?php echo $ids?>">
      
      <div class="form-group" id="tanggal_perjanjian" >
          <label class="control-label col-sm-2">Tanggal Kunjungan</label>  
          <div class="col-md-2">
              <div class="input-group">
                  <input name="tanggal_perjanjian_pm" id="tanggal_perjanjian_pm" value="" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text">
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

