<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<style>
    .control-label{
        font-size: 13px !important;
    }
    .small-box > .inner {
        padding: 5px;
        padding-top: 10px;
    }

    .small-box{
        margin-bottom: -5px;
    }
    .alert-warning {
        background-color: #fcf8e3 !important;
        border-color: #faebcc !important;
        color: #8a6d3b !important;
    }
</style>

<form class="form-horizontal" method="post" id="form_registrasi" action="<?php echo site_url('registration/Self_service/processRegistrasi')?>" enctype="multipart/form-data" autocomplete="off">   
    <!-- hidden -->
    <input type="hidden" class="form-control" value="" id="noMRBooking" name="noMRBooking">
    <input type="hidden" class="form-control" value="" id="nama_pasien_hidden" name="nama_pasien_hidden">
    <input type="hidden" class="form-control" value="" id="umur_saat_pelayanan_hidden" name="umur_saat_pelayanan_hidden">
    <input type="hidden" class="form-control" value="" id="kode_kelompok_hidden" name="kode_kelompok_hidden">
    <input type="hidden" class="form-control" value="" id="jenis_pendaftaran" name="jenis_pendaftaran" value="1">
    <input type="hidden" class="form-control" value="" id="no_mr" name="no_mr" readonly>
    <input type="hidden" class="form-control" value="" id="nama_pasien" name="nama_pasien" readonly>
    <input type="hidden" class="form-control" value="<?php echo date('Y-m-d')?>" id="tgl_registrasi" name="tgl_registrasi" readonly>
    <input type="hidden" name="pm_tujuan" value="" id="pm_tujuan">
    <input type="hidden" name="asal_pasien_pm" value="" id="asal_pasien_pm">

<div class="row" id="search_nomr_div" style="padding-top: 150px">
    <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-8">
            <div class="widget-box effect8">
                <div class="widget-header">
                    <h4 class="widget-title">PENCARIAN DATA PASIEN</h4>
                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <div>
                            <label for="form-field-mask-1" style="font-size: 16px;">
                                <b>Masukan Nomor Rekam Medis/NIK anda.</b>
                            </label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="keyword_mr" name="keyword_mr" style="font-size:40px;height: 55px !important; width: 100%; text-align: left !important">
                                <span class="input-group-btn">
                                    <button class="btn btn-sm btn-primary" type="button" id="btnSearchPasien" style="height: 55px !important; font-size: 16px;">
                                        <i class="ace-icon fa fa-search bigger-110"></i>
                                        Cari Data !
                                    </button>   
                                </span>
                            </div>
                            <div style="width: 100%; text-align: left !important; padding-top: 20px">
                                <ul>
                                    <li><span>Jika anda lupa atau belum memiliki Nomor Rekam Medis atau NIK, silahkan ambil <a href="#" onclick="scrollSmooth('Self_service/antrian_poli')" style="font-size: 14px; background: green; padding: 5px; color: white"><b>NOMOR ANTRIAN</b></a> sesuai dengan poli/klinik tujuan anda untuk didaftarkan sebagai pasien oleh <b>Bagian Administrasi Pasien</b></span></li>
                                    <li>Jika anda mengalami kesulitan, silahkan tanyakan ke petugas kami.</li>
                                </ul>
                                
                            </div>
                        </div>
                        <!-- <div style="width: 100%; margin-top: 10px; text-align: center">
                            <button class="btn btn-sm btn-primary" type="button" id="btnSearchPasien" style="height: 35px !important; font-size: 14px">
                                <i class="ace-icon fa fa-search bigger-110"></i>
                                Cari data pasien
                            </button>
                        </div> -->
                        
                    </div>
                    <div id="message_result_pasien" style="text-align: left !important; background: white;"></div>
                </div>
            </div>
        </div>
    <div class="col-xs-2">&nbsp;</div>
</div>

<div class="row" id="result_nomr_div">
    <div class="col-xs-12 no-padding">
        
        <div class="row" style="display: none; background: white; padding: 30px" id="resultSearchPasien">
            <div class="row" style="margin-top:0px;">

                <div id="view_last_message"></div>
                <table style="width: 70%">
                    <tr>
                        <td style="font-weight: bold; font-size: 16px; text-align: left">DATA PASIEN</td>
                    </tr>
                    <tr>
                        <td align="left">NIK</td><td align="left"><span id="txt_nik"></span></td>
                    </tr>
                    <tr>
                        <td align="left">No. Rekam Medis</td><td align="left"><span id="txt_no_mr"></span></td>
                        <td align="left">Nama Pasien</td><td align="left"><span id="txt_nama_pasien"></span></td>
                    </tr>
                    <tr>
                        <td align="left">Tgl Lahir</td><td align="left"><span id="txt_tgl_lhr"></span></td>
                        <td align="left">Umur</td><td align="left"><span id="txt_umur"></span></td>
                    </tr>
                    <tr>
                        <td align="left">Alamat</td><td align="left"><span id="txt_alamat"></span></td>
                    </tr>
                </table>
                <br>

                <div class="form-group">
                    <label class="col-sm-12" style="font-weight: bold; font-size: 16px; text-align: left !important; margin-left: -10px">PILIH JENIS/PENJAMIN PASIEN</label>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2">Jenis Pasien</label>
                    <div class="col-md-4">
                    <div class="radio" style="text-align: left">
                            <label>
                            <input name="jenis_penjamin" type="radio" class="ace" value="Jaminan Perusahaan" />
                            <span class="lbl"> Jaminan Perusahaan</span>
                            </label>
                            <label>
                            <input name="jenis_penjamin" type="radio" class="ace" value="Umum" checked/>
                            <span class="lbl"> Umum</span>
                            </label>
                    </div>
                    </div>
                </div>

                <div class="form-group" id="div_penjamin_perusahaan" style="display: none">
                    <label class="control-label col-sm-2">Perusahaan Penjamin</label>
                    <div class="col-md-4">
                        <input id="InputKeyPenjamin" class="form-control" name="penjamin" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                        <input type="hidden" name="kode_perusahaan_hidden" value="" id="kode_perusahaan_hidden"> 
                    </div>
                </div>

                <hr>
                <div id="div_select_tujuan_kunjungan">
                    <div class="tabbable">
                        <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
                            <li class="active">
                                <a data-toggle="tab" id="tab-poli" href="#tab-poli" onclick="getMenuTabs('Self_service/tab_poli', 'tab_jenis_kunjungan')">POLI/KLINIK SPESIALIS</a>
                            </li>

                            <li>
                                <a data-toggle="tab" id="tab-penunjang" href="#tab-penunjang" onclick="getMenuTabs('Self_service/tab_PM', 'tab_jenis_kunjungan')">PENUNJANG MEDIS</a>
                            </li>

                        </ul>

                        <div class="tab-content">
                            <div id="tab_jenis_kunjungan" class="tab-pane in active">
                                Loading...
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="div_selected_poli">
                    <!-- input type hidden -->
                    <input type="hidden" name="reg_klinik_rajal" id="reg_klinik_rajal">
                    <input type="hidden" name="reg_dokter_rajal" id="reg_dokter_rajal">
                    <input type="hidden" name="jd_id" id="jd_id">
                    <div id="konfirmasi_kunjungan"></div>
                </div>

            </div>
        </div>
    </div>
</div>



</form>

<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

    $(document).ready(function () {

        // focus input
        $('#keyword_mr').focus();

        getMenuTabs('Self_service/tab_poli', 'tab_jenis_kunjungan');
        $('#jenis_pendaftaran').val(1);
        
        var today = getDateToday();
        console.log(today);
        $('#btnSearchPasien').click(function (e) {
        e.preventDefault();
        findPasien();
        });

        $('#tab-penunjang').click(function (e) {
            $('#form_registrasi').attr('action', 'Self_service/processRegistrasiPenunjang');
        })

        $('#tab-poli').click(function (e) {
            $('#form_registrasi').attr('action', 'Self_service/processRegistrasi');
        })

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
        
        $('#form_registrasi').ajaxForm({
            beforeSend: function() {
                achtungShowFadeIn();  
            },
            uploadProgress: function(event, position, total, percentComplete) {
            },
            complete: function(xhr) {     
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);

            if(jsonResponse.status === 200){
                $.achtung({message: jsonResponse.message, timeout:3});
                // message success
                $('#konfirmasi_kunjungan').html('<div class="alert alert-block center"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><p><strong><img src="<?php echo base_url()?>assets/kiosk/check-circle.jpeg" width="100px"><br> <h2>Sukses !</h2> </strong> <p>Silahkan langsung menunggu di Ruang Tunggu Poli/Klinik.<br>Terima Kasih.</p></div>');
                setTimeout(function () {
                        location.reload();
                }, 5000);
            }else{
                $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
            }
        });
        

        $('input[name="jenis_penjamin"]').click(function (e) {
            var value = $(this).val();
            if(value == 'Umum'){
                $('#div_penjamin_perusahaan').hide();
            }else{
                $('#div_penjamin_perusahaan').show();
            }
            
        });

        $('#InputKeyPenjamin').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "../templates/references/getPerusahaan",
                    data: { keyword:query },            
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
            console.log(val_item);
            $('#kode_perusahaan_hidden').val(val_item);
            }
        });


    });

    function select_dokter_poli(kode_dokter, kode_spesialis, jd_id){
        $('#div_select_tujuan_kunjungan').hide();
        $('#div_selected_poli').show();
        $('#reg_dokter_rajal').val(kode_dokter);
        $('#reg_klinik_rajal').val(kode_spesialis);
        $('#jd_id').val(jd_id);

        $.getJSON("../Templates/References/getDetailJadwalPraktek/"+jd_id+"", '', function (response) {
            console.log(response.data);
            var obj = response.data;
            var poli = obj.nama_bagian;
            var dokter = obj.nama_pegawai;
            $('#konfirmasi_kunjungan').html('<div class="center" style="padding-bottom: 100px"><strong><h3>Konfirmasi !</h3> </strong><span style="font-size: 14px">Anda akan berkunjung ke <b>'+poli.toUpperCase()+'</b></span><br><span style="font-size: 14px; "> dengan Dokter <b>'+dokter.toUpperCase()+'</b><br><br><button type="button" class="btn btn-xs btn-primary" style="height: 45px !important;font-size: 16px;min-width: 320px; background: #147995 !important; border-color: #066681" onclick="updateTujuanPoli()"><i class="fa fa-arrow-left bigger-150"></i> Ganti Tujuan Kunjungan Poli/Klinik </button> <button type="submit" class="btn btn-xs btn-primary" style="height: 45px !important;font-size: 16px;min-width: 320px"><i class="fa fa-print bigger-150"></i> Proses dan Cetak Bukti Pendaftaran</button></span></div>');
        });

    }

    function select_penunjang(kode_bagian){
        $('#div_select_tujuan_kunjungan').hide();
        $('#div_selected_poli').show();
        $('#pm_tujuan').val(kode_bagian);
        $('#asal_pasien_pm').val(kode_bagian);
        $.getJSON("../Templates/References/getRefPm/"+kode_bagian+"", '', function (response) {
            console.log(response.data);
            var poli = response.nama_bag;
            $('#konfirmasi_kunjungan').html('<div class="center" style="padding-bottom: 100px"><strong><h3>Konfirmasi !</h3> </strong><span style="font-size: 14px">Anda akan berkunjung ke <b>'+poli.toUpperCase()+'</b></span><br><span style="font-size: 14px; "><br><br><button type="button" class="btn btn-xs btn-primary" style="height: 45px !important;font-size: 16px;min-width: 320px; background: #147995 !important; border-color: #066681" onclick="updateTujuanPoli()"><i class="fa fa-arrow-left bigger-150"></i> Ganti Tujuan Kunjungan Poli/Klinik </button> <button type="submit" class="btn btn-xs btn-primary" style="height: 45px !important;font-size: 16px;min-width: 320px"><i class="fa fa-print bigger-150"></i> Proses dan Cetak Bukti Pendaftaran</button></span></div>');
        });

    }

    function findPasien(){
        var keyword_mr = $('#keyword_mr').val();
        var today = getDateToday();

        if(keyword_mr == ''){
            alert('Silahkan masukan Nomor Rekam Medis anda..!');
            return false;
        }

        $.ajax({
            url: '../Templates/References/findPasien',
            type: "post",
            data: {no_mr: keyword_mr},
            dataType: "json",
            beforeSend: function() {
                /*show hidden*/
                $('#message_result_pasien').html('Loading...');
            },
            success: function(response) {
                console.log(response.status);
            //   achtungHideLoader();
                if(response.status==200){
                    $('#search_nomr_div').hide('fast');
                    $('#resultSearchPasien').show('fast');
                    var result = response.data;
                    var obj = result[0];
                    var no_mr = obj.no_mr;
                    // console.log(obj[0]);
                    $('#message_result_pasien').html('');
                    
                    // $('html,body').animate({
                    //         scrollTop: $("#result_nomr_div").offset().top},
                    //         'slow');
                    var umur_pasien = hitung_usia(obj.tgl_lhr);

                    // txt data
                    $('#txt_no_mr').text(obj.no_mr);
                    $('#txt_nik').text(obj.no_ktp);
                    $('#txt_nama_pasien').text(obj.nama_pasien+' ( '+obj.jen_kelamin+' )');
                    $('#txt_tgl_lhr').text(getFormattedDate(obj.tgl_lhr));
                    $('#txt_umur').text(umur_pasien+' thn');
                    $('#txt_alamat').text(obj.almt_ttp_pasien);


                    $('#no_mr').val(obj.no_mr);
                    $('#nama_pasien').val(obj.nama_pasien);
                    $('#noMRBooking').val(obj.no_mr);
                    $('#nama_pasien_hidden').val(obj.nama_pasien);
                    $('#umur_saat_pelayanan_hidden').val(umur_pasien);
                    
                    
                    $('#message_result_pasien').html('<div class="alert alert-success"><strong>Selamat Datang, </strong>&nbsp; '+obj.nama_pasien+' ('+obj.no_mr+'), silahkan pilih tujuan kunjungan anda.</div>');
                    setTimeout(function () {
                        $('#message_result_pasien').html('');
                    }, 5000);
                    
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
                    $('#search_nomr_div').show('fast');
                    $('#message_result_pasien').html('<div class="center" style="padding: 50px"><img src="<?php echo base_url()?>assets/kiosk/alert.jpeg" style="width: 100px "><strong><h3>Pemberitahuan !</h3> </strong><span style="font-size: 14px">'+response.message+'</span></div>');

                }
                
            }
        });

    }

    function updateTujuanPoli(){
        $('#div_select_tujuan_kunjungan').show();
        getMenuTabs('Self_service/tab_poli', 'tab_jenis_kunjungan');
        $('#konfirmasi_kunjungan').html('');
    }


</script>