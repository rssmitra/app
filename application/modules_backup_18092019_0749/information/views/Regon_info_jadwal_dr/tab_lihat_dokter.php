<script type="text/javascript">

function preventDefault(e) {
  e = e || window.event;
  if (e.preventDefault)
      e.preventDefault();
  e.returnValue = false;  
}

jQuery(function($) {  

  
  $("#tgl_kunjungan").datepicker({

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
                var message = '<div class="alert alert-danger"><strong>Tidak ada praktek dokter</strong><br>Silahkan pilih tanggal lain yang sesuai dengan jadwal dokter</div>';
                $('#view_msg_kuota').hide('fast');
          }else{
            var message = '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><p><strong><i class="ace-icon fa fa-check"></i> Berhasil ! </strong>Anda dapat melakukan pendaftaran untuk tanggal ini pada menu Booking </p></div>';

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


    }, 'json');

    
  
  });
 

});

$('select[name="klinik_rajal"]').change(function () {      

    /*hide first*/
    $('#show_detail_praktek').hide('fast');
    $('#tgl_kunjungan_form').hide('fast');
    $('#view_last_message').hide('fast');
    $('#show_jadwal_dokter').hide('fast');
    $('#tgl_kunjungan').val('');

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

$('select[name="dokter_rajal"]').change(function () {      

    if ($(this).val()) {          

        var kode_spesialis = $('#klinik_rajal').val();

        $.getJSON("<?php echo site_url('Templates/References/getJadwalPraktek') ?>/" + $(this).val() + '/' + kode_spesialis, '', function (data) {              

            /*here show data from jadwal praktek*/  
            $('#show_jadwal_dokter').show('fast');             
            $('#show_jadwal_dokter').html(data.html);
            $('#show_detail_praktek').hide('fast');
            $('#tgl_kunjungan_form').hide('fast');
            $('#view_last_message').hide('fast');
            $('#tgl_kunjungan').val('');
        });            

    } else {          

        /*remove */       

    }        

});  

function detailJadwalPraktek(jd_id){
    preventDefault();
    $.getJSON("<?php echo site_url('Templates/References/getDetailJadwalPraktek') ?>/" + jd_id, function (data) {
            /*here show data from jadwal praktek*/               
            $('#show_detail_praktek').html(data.html);
            $('#show_detail_praktek').show('fast');
            $('#tgl_kunjungan_form').show('fast');
            $('#selected_day').val(data.day);
            $('#selected_time').val(data.time);
            $('#last_counter').val(data.terisi);
            $('#jd_id').val(data.id);
            $('#view_last_message').hide('fast');
            $('#view_msg_kuota').hide('fast');
            $('#tgl_kunjungan').val('');
    });

}

</script>

<form class="form-horizontal" method="post" id="form_booking" action="<?php echo site_url('booking/regon_booking/process')?>" enctype="multipart/form-data">   

<p style="margin-top:5px"><b><i class="fa fa-ambulance"></i> PILIH KLINIK </b></p>

<div class="form-group">

  <label class="control-label col-sm-2" for="Province">*Klinik</label>

  <div class="col-sm-5">

      <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1)), '' , 'klinik_rajal', 'klinik_rajal', 'form-control', '', '') ?>

  </div>

</div>

<div class="form-group">

  <label class="control-label col-sm-2" for="City">*Dokter</label>

  <div class="col-sm-4">

      <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'dokter_rajal', 'dokter_rajal', 'form-control', '', '') ?>

  </div>

</div>

<div id="show_jadwal_dokter"></div>

<div id="show_detail_praktek"></div>
  
<p><b><i class="fa fa-calendar"></i> TANGGAL KUNJUNGAN </b></p>

<div class="form-group">
  
  <label class="control-label col-sm-2">Tanggal Kunjungan</label>
  
  <div class="col-md-4">
    
    <div class="input-group">
        
        <input name="tanggal_kunjungan" id="tgl_kunjungan" value="" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text">
        <span class="input-group-addon">
          
          <i class="ace-icon fa fa-calendar"></i>
        
        </span>
      </div>

      <small id="view_msg_kuota" style="margin-top:1px"></small>

  
  </div>

</div>

<div id="view_last_message" style="margin-top:5px"></div>

<!-- hidden form  -->
<input type="hidden" name="no_mr" value="00211762" id="no_mr">
<input type="hidden" name="jd_id" id="jd_id">
<input type="hidden" name="selected_day" id="selected_day">
<input type="hidden" name="selected_time" id="selected_time">
<input type="hidden" name="last_counter" id="last_counter">

    
</form>