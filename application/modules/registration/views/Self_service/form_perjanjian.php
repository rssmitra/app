<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />


<div class="row">
    <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-8">
            <div class="widget-box effect8">
                <div class="widget-header">
                    <h4 class="widget-title">FORM PERJANJIAN</h4>
                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <div>
                            <table class="table">
                                <tr>
                                    <th>Nama Dokter</th>
                                    <th>Poli/Klinik Spesialis</th>
                                    <th>Hari</th>
                                    <th>Jam Praktek</th>
                                    <th>Kuota</th>
                                    <th>Keterangan</th>
                                </tr>
                                <tbody>
                                    <tr>
                                        <td><?php echo $value->nama_pegawai?></td>
                                        <td><?php echo ucwords($value->nama_bagian)?></td>
                                        <td><?php echo $value->jd_hari?></td>
                                        <td><?php echo $this->tanggal->formatTime($value->jd_jam_mulai)?> s/d <?php echo $this->tanggal->formatTime($value->jd_jam_selesai)?></td>
                                        <td><?php echo $value->jd_kuota?></td>
                                        <td><?php echo $value->jd_keterangan?></td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                            

                        </div>
                        <div>
                            <label for="form-field-mask-1">
                                Silahkan masukan Nomor Rekam Medis anda.
                            </label>

                            <div>
                                <input class="form-control" type="text" id="keyword_mr" name="keyword_mr" style="font-size:40px;height: 55px !important; width: 100%; text-align: center !important">
                            </div>
                        </div>
                        <div style="width: 100%; margin-top: 10px; text-align: center">
                            <button class="btn btn-sm btn-primary" type="button" id="btnSearchPasien" style="height: 35px !important; font-size: 14px">
                                <i class="ace-icon fa fa-search bigger-110"></i>
                                Cari data pasien
                            </button>
                        </div>
                        
                    </div>
                </div>
            </div>

            <hr>
            <div id="message_result_pasien"></div>

            <div class="row" style="display: none; padding: 15px" id="resultSearchPasien">
                
                <div id="tgl_kunjungan_form">
                    <p><b><i class="fa fa-calendar"></i> TANGGAL KUNJUNGAN </b></p>
                    <!-- hidden form -->
                    <input type="hidden" name="kode_poli_app" id="kode_poli_app" value="<?php echo $value->jd_kode_spesialis?>">
                    <input type="hidden" name="jd_id_app" id="jd_id_app" value="<?php echo $value->jd_id?>">
                    <input type="hidden" name="kode_dokter_app" id="kode_dokter_app" value="<?php echo $value->jd_kode_dokter?>">
                    <input type="hidden" name="selected_day" id="selected_day" value="<?php echo $value->jd_hari?>">
                    
                    <div class="form-group">
                        
                        <label class="control-label col-sm-2">Tanggal Kunjungan</label>
                        
                        <div class="col-md-4">                
                            <div class="input-group">                    
                                <input name="tanggal_kunjungan" id="tgl_kunjungan" value="" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text">
                                <span class="input-group-addon">
                                    <i class="ace-icon fa fa-calendar"></i>
                                </span>
                            </div>
                                <small id="" style="margin-top:1px; padding-left: 8px">*) Hari Minggu & Tanggal Merah Libur</small>
                                <!-- <small id="view_msg_kuota" style="margin-top:1px"></small> -->
                        </div>

                    </div>
                </div>
                <div class="row" style="margin-top:55px; padding: 8px">
                    <div id="view_last_message"></div>
                </div>
            </div>

            

            
        </div>
    <div class="col-xs-2">&nbsp;</div>
</div>

<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

jQuery(function($) {  

  
    var disableDates = ["1-1-2021","12-2-2021", "11-3-2021","14-3-2021","2-4-2021","1-5-2021","12-5-2021","13-5-2021","14-5-2021","26-5-2021","1-6-2021","20-7-2021","11-8-2021","17-8-2021","20-10-2021","24-12-2021","25-12-2021"];
    $("#tgl_kunjungan").datepicker({

    autoclose: true,    
    todayHighlight: true,
    daysOfWeekDisabled: [0],
    format: 'yyyy-mm-dd',
    beforeShowDay: function(date){
        dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
        if(disableDates.indexOf(dmy) != -1){
            return false;
        }
        else{
            return true;
        }

    },
    onSelect: function(dateText) {
        $(this).change();
    }
    }).on("change", function() {
    
    var str_selected_date = this.value;
    console.log(str_selected_date);
    var selected_date = str_selected_date.split("/").join("-");
    var spesialis = $('#kode_poli_app').val();
    var dokter = $('#kode_dokter_app').val();
    var jd_id = $('#jd_id_app').val();
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
            var message = '<div class="alert alert-block center"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><p><strong><img src="<?php echo base_url()?>assets/kiosk/check-circle.jpeg" width="100px"><br> <h2>Available !</h2> </strong> <p><button type="submit" id="btnSave" class="btn btn-success" style="height: 50px !important"><i class="fa fa-print"></i> Cetak Bukti Perjanjian</button></p></div>';

            if(data.sisa > 0 ){
                var msg_kuota = '<p style="font-size: 12px">*Total Pasien Perjanjian '+data.terisi+' orang, Kuota tersedia pada tanggal ini, '+data.sisa+' pasien</p>';
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

    });

});

$(document).ready(function () {

    var today = getDateToday();
    console.log(today);
	$('#btnSearchPasien').click(function (e) {
      e.preventDefault();
      findPasien();
    });


});

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
            var obj = result[0];
            var no_mr = obj.no_mr;
            // console.log(obj[0]);

            /*show hidden*/
            $('#resultSearchPasien').show('fast');
            $('#message_result_pasien').html('');
			
			$('html,body').animate({
					scrollTop: $("#resultSearchPasien").offset().top},
					'slow');
            
            $('#message_result_pasien').html('<div class="alert alert-success"><strong>Data ditemukan. </strong><br> Selamat datang, '+obj.nama_pasien+' ('+obj.no_mr+')</div>');

            /*text*/
            // $('#kb_no_mr').text(obj.no_mr);
            // $('#pnomr').val(obj.no_mr);
            // $('#kb_nama_pasien').text(obj.nama);
            // $('#kb_tgl_kunjungan').text(obj.tgl_kunjungan);
            // $('#kb_poli_tujuan').text(obj.poli);
            // $('#kb_dokter').text(obj.nama_dr);
            // $('#kb_jam_praktek').text(obj.jam_praktek);
            // $('#noSuratSKDP').val(keyword_mr);
            // $('#kode_poli_bpjs').val(obj.kode_poli_bpjs);
            // console.log(today);
            // console.log(obj.tgl_kunjungan);

            if(today == obj.tgl_kunjungan){
                $('#is_available').html('<span class="label label-success arrowed-in-right">available</span>');
            }else{
                $('#is_available').html('<span class="label label-danger arrowed-in-right">not available</span>');
            }
          //   $('#noMR').val(response.data.no_mr);

          }else{
            // $.achtung({message: data.message, timeout:5});
            $('#resultSearchPasien').hide('fast');
            $('#message_result_pasien').html('<div class="alert alert-danger"><strong>Pemberitahuan ! </strong>'+response.message+'</div>');

          }
          
        }
      });

}


</script>