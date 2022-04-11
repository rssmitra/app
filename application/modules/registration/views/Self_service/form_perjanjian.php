<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<style>
.table-condensed{
    width: 300px !important;
    height: 290px;
    border: 1px solid #e3dfdf;
}
</style>
<div class="row">
    <div class="col-xs-12 no-padding">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="widget-title" style="font-weight: bold; padding: 10px">PERJANJIAN PASIEN RAWAT JALAN</h4>
            </div>
            <form class="form-horizontal" method="post" id="form_booking" action="<?php echo site_url('registration/Reg_pasien/process_perjanjian')?>" enctype="multipart/form-data" autocomplete="off"> 

                <div class="widget-body">
                    <div class="widget-main">

                        <div style="padding: 0px;">
                            <div style="text-align: left !important; font-weight: bold;font-size: 12px;">JADWAL DOKTER</div>
                            <table class="table">
                                <tr style="background: #f9eb41;font-size: 12px;">
                                    <th>Nama Dokter</th>
                                    <?php for($i=1; $i<8;$i++) : ?>
                                        <th style="text-align: left !important; width: 90px"><?php echo $this->tanggal->getDayByNum($i);?></th>
                                    <?php endfor; ?>
                                    <th width="50px">Ubah</th>
                                </tr>
                                <tbody>
                                    <tr>
                                        <td  style="text-align: left !important">
                                            <span style="font-size: 12px; font-weight: bold"><?php echo $value->nama_pegawai?></span><br>
                                            <?php echo ucwords($value->nama_bagian)?>
                                        </td>
                                        <?php 
                                            for($i=1; $i<8;$i++) : 
                                                $day = $this->tanggal->getDayByNum($i);
                                        ?>

                                            <td>
                                                <?php 
                                                    if(isset($jadwal_dr_spesialis[$day])) :
                                                        echo '<a href="#" onclick="changeDay('.$jadwal_dr_spesialis[$day]['jd_id'].', '."'".$jadwal_dr_spesialis[$day]['jd_hari']."'".', '."'".$jadwal_dr_spesialis[$day]['jd_jam_mulai']."'".')" style="font-size:12px; font-weight: bold">'.$this->tanggal->formatTime($jadwal_dr_spesialis[$day]['jd_jam_mulai']).' s.d '. $this->tanggal->formatTime($jadwal_dr_spesialis[$day]['jd_jam_selesai']).'</a><br>Kuota : '.$jadwal_dr_spesialis[$day]['jd_kuota'].'<br><small>'.$jadwal_dr_spesialis[$day]['jd_keterangan'].'</small>';
                                                    else:
                                                        echo '-';
                                                    endif; 
                                                ?>
                                        <?php endfor; ?>
                                        <td style="vertical-align: middle; text-align: center">
                                            <a href="#" class="btn btn-sm btn-danger" style="font-size: 12px;font-weight: bold;" onclick="scrollSmooth('Self_service/jadwal_dokter?kode=<?php echo trim($value->jd_kode_spesialis)?>')"><i class="fa fa-refresh"></i></a>
                                        </td>

                                        
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div id="form-cari-pasien">
                            <div style="padding: 10px">
                                <label for="form-field-mask-1" style="font-size: 15px;">
                                    Silahkan masukan <b>Nomor Rekam Medis</b> atau <b>Nomor NIK</b> anda.
                                </label>

                                <div>
                                    <input class="form-control" type="text" id="keyword_mr" name="keyword_mr" style="font-size:40px;height: 55px !important; width: 100%; text-align: center !important">
                                </div>
                            </div>

                            <div style="width: 100%; margin-top: 10px; text-align: center">
                                <button class="btn btn-success" type="button" id="btnSearchPasien" style="height: 50px !important; font-size: 12px; font-weight: bold">
                                    <i class="ace-icon fa fa-search bigger-110"></i>
                                    CARI DATA PASIEN
                                </button>
                            </div>
                        </div>

                        <div class="widget-main" id="data_perjanjian_pasien" style="padding: 0px"></div>

                    </div>

                    <div class="row" style="display: none; padding: 0px" id="resultSearchPasien">
                        
                        <div id="tgl_kunjungan_form">

                            <div class="col-sm-4">
                                <div style="text-align: left !important; font-weight: bold;font-size: 12px;width: 100% !important">
                                    PILIH TANGGAL UNTUK HARI <span id="txt_day"><?php echo strtoupper($value->jd_hari)?></span>
                                </div>
                                
                                <!-- hidden form -->
                                <input type="hidden" name="id_tc_pesanan" id="id_tc_pesanan" value="">
                                <input type="hidden" name="no_surat_kontrol" id="no_surat_kontrol" value="">
                                <input type="hidden" name="klinik_rajal" id="kode_poli_app" value="<?php echo $value->jd_kode_spesialis?>">
                                <input type="hidden" name="jd_id" id="jd_id_app" value="<?php echo $value->jd_id?>">
                                <input type="hidden" name="dokter_rajal" id="kode_dokter_app" value="<?php echo $value->jd_kode_dokter?>">
                                <input type="hidden" name="selected_day" id="selected_day" value="<?php echo $value->jd_hari?>">
                                <input type="hidden" name="selected_time" id="selected_time" value="<?php echo $this->tanggal->formatTime($value->jd_jam_mulai)?>">
                                <input type="hidden" name="no_mr" id="no_mr" value="">
                                <input type="hidden" name="jenis_instalasi" id="jenis_instalasi" value="RJ">
                                <input type="hidden" name="jenis_penjamin" id="jenis_penjamin" value="Jaminan Perusahaan">
                                <input type="hidden" name="kode_perusahaan" id="kode_perusahaan" value="120">
                                <input type="hidden" name="kode_perusahaan" id="kode_perusahaan" value="120">
                                <input type="hidden" name="is_no_mr" id="is_no_mr" value="N">
                                <input type="hidden" name="nama_pasien" id="kb_nama_pasien" value="">
                                <input type="hidden" name="alamat" id="alamat" value="">
                                <input type="hidden" name="print_booking" id="print_booking" value="1">
                                
                                <div class="center">
                                    <div id="datepicker_inline"></div>
                                    <button type="button" class="btn btn-sm btn-danger" style="width: 300px;height: 40px !important; margin-left:4px; font-size: 12px; font-weight: bold" onClick="showTestDate()">PILIH TANGGAL</button>
                                    
                                    <input name="tanggal_kunjungan" id="tgl_kunjungan_input" value="" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>"  style="width: 450px" type="hidden">
                                </div>
                                <br><br>
                            </div>
                            <div class="col-sm-8 no-padding">
                                <div class="widget-main" id="message_result_pasien" style="margin-top: -5px"></div>

                                <div style="padding: 10px" id="div_no_sep_lama">
                                    <label for="form-field-8"><b>MASUKAN NO SEP TERAKHIR</b></label>
                                    <input type="text" class="form-control" name="no_sep_lama" id="no_sep_lama" id="form-field-8" placeholder="Masukan No SEP">
                                </div>
                                
                                <div id="view_msg_kuota" style="margin: 12px; margin-top: 5px"></div>
                                <div id="view_last_message" style="margin-top: -25px"></div>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>

<script>

jQuery(function($) {  

  
    var disableDates = getLiburNasional(<?php echo date('Y')?>);

    $("#datepicker_inline").datepicker({
        autoclose: true,    
        todayHighlight: true,
        daysOfWeekDisabled: getDisabledDay(),
        format: 'yyyy-mm-dd',
        beforeShowDay: function(date){
            dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
            if(disableDates.indexOf(dmy) != -1){
                return false;
            }
            else{
                return true;
            }

        }
    });

    $( "#keyword_mr" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which); 
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            $('#btnSearchPasien').click();
          }
          return false;       
        }
  });


});

function changeDay(jd_id, day, time){
    preventDefault();
    $('#jd_id_app').val(jd_id);
    $('#selected_day').val(day);
    $('#selected_time').val(time);
    $('#txt_day').text(day.toUpperCase());
}
function getDisabledDay(){
    return [0];
}

/* Need it in Format dd mm yyyy */
function showTestDate(){ 

    // var valueDate = $("#datepicker_inline").datepicker("getDate");
    var valueDate = $("#datepicker_inline").data('datepicker').getFormattedDate('yyyy-mm-dd');
    document.getElementById("tgl_kunjungan_input").value = valueDate;

    var str_selected_date = valueDate;
    console.log(str_selected_date);
    // var selected_date = str_selected_date.split("/").join("-");
    var selected_date = valueDate;
    var spesialis = $('#kode_poli_app').val();
    var dokter = $('#kode_dokter_app').val();
    var jd_id = $('#jd_id_app').val();
    /*check selected date */
    $.post('<?php echo site_url('Templates/References/CheckSelectedDate') ?>', {date:selected_date, kode_spesialis:spesialis, kode_dokter:dokter, jadwal_id:jd_id} , function(data) {
        // Do something with the request
        if(data.status=='expired'){
            var message = '<div class="alert alert-danger" style="margin: 10px;margin-top: 25px;"><strong>Expired Date !</strong><br>Tanggal yang anda pilih sudah lewat atau sedang berjalan.</div>';
            $('#view_msg_kuota').hide('fast');
        }else{
            if(data.day!=$('#selected_day').val() ){
                var message = '<div class="alert alert-danger" style="margin: 10px;margin-top: 25px;"><strong>Tidak Sesuai !</strong><br>Tanggal Kunjungan tidak sesuai dengan jadwal Praktek Dokter yang anda pilih !</div>';
                $('#view_msg_kuota').hide('fast');
            }else{
            var message = '<div class="alert alert-block center"><button type="submit" id="btnSave" class="btn btn-success" style="height: 50px !important;font-size: 12px;font-weight: bold;"><i class="fa fa-print"></i> Cetak Bukti Perjanjian</button></p></div>';

            if(data.sisa > 0 ){
                var msg_kuota = '<div class="alert alert-success"> <strong style="font-size: 12px"><i class="fa fa-check-circle bigger-200"></i><br>Kuota Tersedia</strong> <br> Kuota tersedia pada tanggal ini yaitu '+data.sisa+' pasien <br> Silahkan cetak dan simpan Bukti Perjanjian anda untuk melakukan pendaftaran.</div>';
            }else{
                var msg_kuota = '<span style="color:red"> <h4>*Kuota sudah penuh Cari tanggal lain!</h4></h4></span>';
            }

            $('#view_msg_kuota').show('fast');
            $('#view_msg_kuota').html(msg_kuota);

            }

        }

        $('#view_last_message').show('fast');
        $('#view_last_message').html(message);
        $("html, body").animate({ scrollTop: "700px" }, "slow");  

    }, 'json'); 
    
}


$(document).ready(function () {

    var today = getDateToday();
    // console.log(today);
	$('#btnSearchPasien').click(function (e) {
      e.preventDefault();
      findPasien();
    });

    $('#form_booking').ajaxForm({
      beforeSend: function() {
      achtungShowLoader();  
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
      var data=xhr.responseText;
      var jsonResponse = JSON.parse(data);
      if(jsonResponse.status == 200){
            $.achtung({message: jsonResponse.message, timeout:5});
            $('#view_last_message').html('<div class="alert alert-block center"><p><strong><br> <h3>Berhasil !</h3> </strong><img src="<?php echo base_url()?>assets/kiosk/print_success.png" width="80px"><br><br><a href="#" onclick="rePrintBooking('+jsonResponse.jd_id+', '+jsonResponse.id_tc_pesanan+')" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Cetak Ulang</a></div>');
            $('#view_msg_kuota').hide();
            $('#div_no_sep_lama').hide();
            //timeout redirect page
            setTimeout(function () {
                window.location.href = "Self_service"; //will redirect to your blog page (an ex: blog.html)
            }, 5000); //will call the function after 2 secs.

      }else{
          $.achtung({message: jsonResponse.message, timeout:5, className:'achtungFail'});
        //   $('#view_last_message').html('<div class="alert alert-danger">'+jsonResponse.message+'</div>');
      }

      achtungHideLoader();
      }
  }); 


});

function rePrintBooking(jd_id, id_tc_pesanan){
    preventDefault();
    $.ajax({
        url: 'Reg_pasien/print_booking/'+jd_id+'/'+id_tc_pesanan+'',
        type: "GET",
        dataType: "json",
        beforeSend: function() {
        //   achtungShowLoader();  
        },
        success: function(response) {
            scrollSmooth('Self_service/view_spesialis');
        }
    });
}

function findPasien(){
    var keyword_mr = $('#keyword_mr').val();
    var today = getDateToday();

      $.ajax({
        url: '../Templates/References/findPasien',
        type: "post",
        data: {no_mr: keyword_mr},
        dataType: "json",
        beforeSend: function() {
        //   achtungShowLoader();  
        },
        success: function(response) {
            console.log(response.status);
        //   achtungHideLoader();
          if(response.status==200){
            var result = response.data;
            var dtp = response.perjanjian;
            var obj = result[0];
            var no_mr = obj.no_mr;
            var sep = response.sep;
            $('#no_sep_lama').val(sep.no_sep);

            // jika sudah ada perjanjian sebelumnya maka tampilkan data perjanjiannya
            if(response.count_perjanjian > 0){
                $('#resultSearchPasien').hide('fast');
                $('#id_tc_pesanan').val(dtp.id_tc_pesanan);
                $('#no_surat_kontrol').val(dtp.kode_perjanjian);

                var html_p = '';
                html_p += '<div class="row">';
                html_p += '<div style="text-align: left !important; font-weight: bold;font-size: 12px; ">DATA PERJANJIAN PASIEN TERAKHIR</div>';
                html_p += '<table class="table">';
                html_p += '<tr style="background: #87b87f;font-size: 12px;font-weight: bold">';
                html_p += '<td>Kode Booking</td>';
                html_p += '<td>Nama Dokter</td>';
                html_p += '<td>Tanggal Dibuat</td>';
                html_p += '<td width="280px">#</td>';
                html_p += '</tr>';
                html_p += '<tr style="font-size: 12px;">';
                html_p += '<td style="text-align: left"><b>'+dtp.kode_perjanjian+'</b><br>'+dtp.tgl_pesanan+'</td>';
                html_p += '<td style="text-align: left">'+dtp.nama_dokter+'<br>'+dtp.nama_bagian+'</td>';
                var petugas = (dtp.petugas == 'null') ? dtp.petugas : 'KIOSK';
                html_p += '<td>'+dtp.input_tgl+'<br>'+petugas+'</td>';
                html_p += '<td><a href="#" class="btn btn-success" onclick="update_data_perjanjian()" style="    background-color: #669fc7 !important;border-color: #4f85ab;"><i class="fa fa-pencil"></i> Ubah Data</a> &nbsp; <a href="#" class="btn btn-primary" onclick="rePrintBooking('+dtp.jd_id+','+dtp.id_tc_pesanan+')"style="background: #e1aa2f !important; border-color: #d99704;" ><i class="fa fa-print"></i> Cetak Ulang</a></td>';
                html_p += '</tr>';
                html_p += '</table>';
                html_p += '</div>';
                $('#data_perjanjian_pasien').show();
                $('#data_perjanjian_pasien').html(html_p);

            }else{
                $('#data_perjanjian_pasien').hide();
                $('#resultSearchPasien').show('fast');
            }
            
            
            /*show hidden*/
            $('#form-cari-pasien').hide('fast');
            $('#view_last_message').hide('fast');
            $('#message_result_pasien').html('');
			

			// $('html,body').animate({
			// 		scrollTop: $("#resultSearchPasien").offset().top},
			// 		'slow');

            var html = '';
            html += '<div class="row" style="margin-top:-8px; text-align: left !important;">';
            html += '<div style="text-align: left !important; font-weight: bold;font-size: 12px; ">DATA PASIEN</div>';
            // html += '<table class="table">';
            // html += '<tr style="background: #f9eb41;font-size: 12px;font-weight: bold">';
            // html += '<td>No MR</td>';
            // html += '<td>No KTP</td>';
            // html += '<td>Nama Pasien</td>';
            // html += '<td>Tgl Lahir</td>';
            // html += '<td>Alamat</td>';
            // html += '<td>No. Telp</td>';
            // html += '</tr>';
            // html += '<tr style="font-size: 12px;">';
            // html += '<td>'+obj.no_mr+'</td>';
            // html += '<td>'+obj.no_ktp+'</td>';
            // html += '<td>'+obj.nama_pasien+'</td>';
            // html += '<td>'+obj.tgl_lahir+'</td>';
            // html += '<td>'+obj.almt_ttp_pasien+'</td>';
            // html += '<td>'+obj.tlp_almt_ttp+'</td>';
            // html += '</tr>';
            // html += '</table>';
            html += '<b>NIK. '+obj.no_ktp+'</b>';
            html += '<br>'+obj.no_mr+' - '+obj.nama_pasien+'&nbsp;&nbsp;&nbsp;TL. '+obj.tgl_lahir+'<br>';
            html += '<span>'+obj.almt_ttp_pasien+' - '+obj.tlp_almt_ttp+'<span>';

            html += '</div>';

            $('#message_result_pasien').html(html);

            /*text*/
            $('#no_mr').val(obj.no_mr);
            $('#kb_nama_pasien').val(obj.nama_pasien);
            $('#alamat').val(obj.almt_ttp_pasien);


            if(today == obj.tgl_kunjungan){
                $('#is_available').html('<span class="label label-success arrowed-in-right">available</span>');
            }else{
                $('#is_available').html('<span class="label label-danger arrowed-in-right">not available</span>');
            }
            

          }else{
              
            $('#resultSearchPasien').hide('fast');
            $('#message_result_pasien').html('<div class="alert alert-danger"><strong style="font-size:16px">Pemberitahuan ! </strong><br>'+response.message+' <br>Silahkan masukan No MR atau NIK dengan benar</div>');

          }
          
        }
      });

}

function update_data_perjanjian(){
    $('#resultSearchPasien').show('fast');
    $('html,body').animate({scrollTop: $("#resultSearchPasien").offset().top},'slow');
}


</script>
