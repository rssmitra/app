<style>

/*==================================================
 * Effect 8
 * ===============================================*/
.effect8
{
    /* position:relative; */
    -webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
       -moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
            box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
}


</style>
<div class="row">
    <div class="col-xs-2">
        &nbsp;
    </div>
    <div class="col-xs-8">
        <div class="widget-box effect8">
            <div class="widget-header">
                <h4 class="widget-title" style="padding-left: 10px; font-weight: bold">KODE PERJANJIAN/BOOKING</h4>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <div>
                        <label for="form-field-mask-1">
                            Silahkan masukan Kode Booking atau Kode Perjanjian anda.
                        </label>

                        <div>
                            <input class="form-control" type="text"id="kodeBooking" name="kodeBooking" style="font-size:40px;height: 55px !important; width: 100%; text-align: center !important">
                        </div>
                    </div>
                    <div style="width: 100%; margin-top: 10px; text-align: center">
                        
                        <button style="height: 35px !important; font-size: 14px" class="btn btn-sm btn-primary" type="button" id="btnSearchKodeBooking">
                            <i class="ace-icon fa fa-search bigger-110"></i>
                            Cek Kode Booking/Perjanjian
                        </button>
                    </div>

                    <div id="message_result_kode_booking" style="padding-top: 10px"></div>
                    
                </div>
            </div>
        </div>


        <div class="row" style="display: none; background: white; padding: 20px" id="resultSearchKodeBooking">

            <!-- hidden parameter -->
            <input type="hidden" name="pnomr" id="pnomr">
            <input type="hidden" name="noSuratSKDP" id="noSuratSKDP">
            <input type="hidden" name="kode_poli_bpjs" id="kode_poli_bpjs">

            <div class="col-xs-12 col-sm-9">

                <div class="profile-user-info">
                    <div class="profile-info-row">
                        <div class="profile-info-name"> No Rekam Medis </div>
                        <div class="profile-info-value">
                            <span id="kb_no_mr" style="float: left">No.MR</span>
                        </div>
                    </div>

                    <div class="profile-info-row">
                        <div class="profile-info-name"> Nama pasien </div>
                        <div class="profile-info-value">
                            <span id="kb_nama_pasien" style="float: left">Nama Pasien</span>
                        </div>
                    </div>

                    <div class="profile-info-row">
                        <div class="profile-info-name"> Tujuan Poli/Klinik </div>

                        <div class="profile-info-value">
                            <span id="kb_poli_tujuan" style="float: left">Poli/Klinik</span>
                        </div>
                    </div>

                    <div class="profile-info-row">
                        <div class="profile-info-name"> Dokter </div>

                        <div class="profile-info-value">
                        <span id="kb_dokter" style="float: left">Nama Dokter</span>
                        </div>
                    </div>

                    <div class="profile-info-row">
                        <div class="profile-info-name"> Tanggal/Jam Kunjungan </div>
                        <div class="profile-info-value">
                            <span id="kb_tgl_kunjungan" style="float: left"></span> <span id="kb_jam_praktek" style="float: left"></span> 
                        </div>
                    </div>

                    <div class="profile-info-row">
                        <div class="profile-info-name"> Status </div>
                        <div class="profile-info-value">
                            <span id="is_available" style="float: left"></span>
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-xs-12 col-sm-3 center">
                <a href="#" class="btn btn-success btn-app radius-4" onclick="nextProcess('Self_service/form_rujukan')" style="width: 100% !important; height: 100% !important; margin-top: 35px">
                    <i class="ace-icon fa fa-arrow-right bigger-350" style="padding: 20px"></i>
                </a>
            </div>


        </div>

        

        
    </div>
    <div class="col-xs-2">
        &nbsp;
    </div>
</div>

<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

var today = getDateToday();
console.log(today);
$('#btnSearchKodeBooking').click(function (e) {
    e.preventDefault();
    findKodeBooking();
});

function findKodeBooking(){
    var kodeBooking = $('#kodeBooking').val();
    var today = getDateToday();

    $.ajax({
        url: '../Templates/References/findKodeBooking',
        type: "post",
        data: {kode:kodeBooking},
        dataType: "json",
        beforeSend: function() {
        //   achtungShowLoader();  
        },
        success: function(response) {
        //   achtungHideLoader();
            if(response.status==200){
            var obj = response.data;
            var no_mr = obj.no_mr;

            /*show hidden*/
            $('#resultSearchKodeBooking').show('fast');
            $('#message_result_kode_booking').html('');
            
            $('html,body').animate({
                    scrollTop: $("#resultSearchKodeBooking").offset().top},
                    'slow');

            /*text*/
            $('#kb_no_mr').text(obj.no_mr);
            $('#pnomr').val(obj.no_mr);
            $('#kb_nama_pasien').text(obj.nama);
            $('#kb_tgl_kunjungan').text(obj.tgl_kunjungan);
            $('#kb_poli_tujuan').text(obj.poli);
            $('#kb_dokter').text(obj.nama_dr);
            $('#kb_jam_praktek').text(obj.jam_praktek);
            $('#noSuratSKDP').val(kodeBooking);
            $('#kode_poli_bpjs').val(obj.kode_poli_bpjs);
            console.log(today);
            console.log(obj.tgl_kunjungan);

            if(today == obj.tgl_kunjungan){
                $('#is_available').html('<span class="label label-success arrowed-in-right">available</span>');
            }else{
                $('#is_available').html('<span class="label label-danger arrowed-in-right">not available</span>');
            }
            //   $('#noMR').val(response.data.no_mr);

            }else{
            // $.achtung({message: data.message, timeout:5});
            $('#resultSearchKodeBooking').hide('fast');
            $('#message_result_kode_booking').html('<div class="center"><img src="<?php echo base_url()?>assets/kiosk/alert.jpeg" style="width: 100px "><strong><h3>Pemberitahuan !</h3> </strong><span style="font-size: 14px">'+response.message+'</span></div>');

            }
            
        }
    });

}

function nextProcess(link){
    $('#load-content-page').load(link+'?kode='+$('#kodeBooking').val());
}

</script>