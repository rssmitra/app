<link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" />
<style>
    .small-box {
        min-height: 150px !important;
    }
    small-box > .small-box-footer {
        padding: 0px 0 !important;
        border-radius: 0px 82px 0px 92px !important;
        margin-left: 25px !important;
        height: 25px !important;
        font-size: 15px !important;
    }

    .input-custom{
        width: 58% !important;
        height: 50px !important;
        text-align: center;
        font-size: 24px !important;
    }
    .control-label{
        font-size: 20px !important;
    }
    .profile-info-value{
        text-align: left !important;
    }
</style>

<div id="main_view_rj">
    
    <p style="text-align: center; font-size: 2em; font-weight: bold; color: green">JADWAL PRAKTEK DOKTER PASIEN BPJS<br> TANGGAL <?php echo date('d/M/Y')?></p>

    <div class="row">
        <?php 
            $no=0; foreach($klinik as $key=>$row) : $no++; 
            $arr_color = array('#197715c2'); 
            shuffle($arr_color);
        ?>
        <div class="col-lg-3 col-xs-4 no-padding" style="margin-top:5px; padding: 5px !important">
            <div class="small-box <?php echo array_shift($arr_color)?>" style="min-height: 115px; border-radius: 0px 54px !important; border: 1px solid #bdbdbd">
                <div class="inner" style="line-height: 13px; min-height: 125px" onclick="select_dokter_poli(<?php echo $row->jd_kode_dokter?>, '<?php echo $row->jd_kode_spesialis?>', <?php echo $row->jd_id?>)">
                    <h3 style="font-size:18px; word-wrap: break-word;min-height: 50px"><?php echo strtoupper($row->nama_bagian)?></h3>
                    <p style="font-size:14px; line-height: 17px; min-height: 55px !important;">
                        <?php echo $row->nama_pegawai?><br>
                        <?php echo $this->tanggal->formatTime($row->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($row->jd_jam_selesai);?>
                    </p>
                </div>
                <?php 
                    echo '<a href="#" style="background: #078000;border-radius: 0px 82px 0px 92px !important;margin-left: 25px !important;height: 30px !important;font-size: 15px !important; color: white" onclick="select_dokter_poli('.$row->jd_kode_dokter.', '."'".$row->jd_kode_spesialis."'".', '.$row->jd_id.')" class="small-box-footer"><b>DAFTAR POLI/KLINIK</b> <i class="fa fa-arrow-circle-right"></i></a>';
                ?>
            </div>
        </div>

        <?php endforeach; ?>
    </div>
    <div class="hr"></div>
    <div class="center" style="left: 50%;" >
        <a href="#" class="btn btn-lg" style="background : green !important; border-color: green;" onclick="getMenu('kiosk/Kiosk/bpjs')"> <i class="fa fa-arrow-left"></i> Kembali ke Menu Sebelumnya</a>
    </div>

</div>

<div id="konfirmasi_kunjungan_rj" style="display: none;">
    <div class="col-md-2">&nbsp;</div>
    <div class="col-md-8">
        <div class="center" style="padding-top: 30px;">
            <i class="fa fa-question-circle bigger-250 green" style="font-size: 60px !important"></i><br>
            <h2 class="green" style="font-weight: bold; font-size: 24px"><span id="txt-nama-poli">-</span><br><span id="txt-nama-dr">-</span></h2>
            <!-- <span style="font-size: 24px">Apakah anda yakin akan berkunjung ? </span> -->
            <br>
            
            <!-- form -->
            
            <div>
                <label class="" style="font-size: 18px">Masukan Nomor Rujukan</label><br>         
                <div class="input-group input-group-lg">
                <span class="input-group-addon">
                    <i class="ace-icon fa fa-check"></i>
                </span>
                <input type="text" class="form-control" id="noRujukan" placeholder="" style="height: 55px !important;max-width: font-size: 24px !important; text-transform: uppercase;" autocomplete="off">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-lg" id="btnSearchNoRujukan" style="height: 55px !important; background: green !important; border-color: green">
                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                    Search
                    </button>
                </span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="profile-user-info profile-user-info-striped">
                    <div class="profile-info-row">
                        <div class="profile-info-name"> No Kartu BPJS </div>
                        <div class="profile-info-value">
                            <span class="editable editable-click" id="no_kartu_bpjs_txt">-</span>
                        </div>
                    </div>
                    <div class="profile-info-row">
                        <div class="profile-info-name"> Nama Pasien </div>
                        <div class="profile-info-value">
                            <span class="editable editable-click" id="nama_pasien_txt">-</span>
                        </div>
                    </div>
                    <div class="profile-info-row">
                        <div class="profile-info-name"> PPK Asal Rujukan </div>
                        <div class="profile-info-value">
                            <span class="editable editable-click" id="ppk_asal_rujukan_txt">-</span>
                        </div>
                    </div>
                    <div class="profile-info-row">
                        <div class="profile-info-name"> Diagnosa </div>
                        <div class="profile-info-value">
                            <span class="editable editable-click" id="diagnosa_txt">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <br><br><br>
            <button type="button" class="btn btn-xs btn-primary" style="height: 45px !important;font-size: 20px;min-width: 320px; background: green !important; border-color: green" onclick="getMenu('kiosk/Kiosk/bpjs_mod_poli')"><i class="fa fa-arrow-left bigger-150"></i> Ganti Tujuan Kunjungan </button>
            <button type="button" onclick="submitForm()" class="btn btn-xs btn-primary" style="height: 45px !important;font-size: 20px;min-width: 320px; background: green !important; border-color: green"><i class="fa fa-print bigger-150"></i> Proses dan Cetak Bukti Pendaftaran</button>
            
        </div>
    </div>

    <div class="col-md-2">&nbsp;</div>
    <!-- hidden -->
    <input type="hidden" name="reg_klinik_rajal" id="reg_klinik_rajal">
    <input type="hidden" name="reg_dokter_rajal" id="reg_dokter_rajal">
    <input type="hidden" name="jd_id" id="jd_id">
    
</div>

<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

    function select_dokter_poli(kode_dokter, kode_spesialis, jd_id){

        // show hide form
        $('#main_view_rj').hide();
        $('#konfirmasi_kunjungan_rj').show();

        $('#reg_dokter_rajal').val(kode_dokter);
        $('#reg_klinik_rajal').val(kode_spesialis);
        $('#jd_id').val(jd_id);

        $.getJSON("Templates/References/getDetailJadwalPraktek/"+jd_id+"", '', function (response) {
            console.log(response.data);
            var obj = response.data;
            var poli = obj.nama_bagian;
            var dokter = obj.nama_pegawai;
            $('#txt-nama-poli').text(poli.toUpperCase());
            $('#txt-nama-dr').text(dokter.toUpperCase());
        });

    }

    function submitForm(){

        var post_data = {
            no_mr : $('#no_mr_val').val(),
            nama_pasien : $('#nama_pasien').val(),
            umur_saat_pelayanan_hidden : $('#umur_saat_pelayanan_hidden').val(),
            kode_kelompok_hidden : $('#kode_kelompok_hidden').val(),
            jenis_pendaftaran : $('#jenis_pendaftaran').val(),
            pm_tujuan : $('#pm_tujuan').val(),
            asal_pasien_pm : $('#asal_pasien_pm').val(),
        }

        $.ajax({
            url: 'kiosk/Kiosk/process_register_rj',
            type: "post",
            data: post_data,
            dataType: "json",
            beforeSend: function() {
                achtungShowFadeIn();  
            },
            success: function(response) {

                if(response.status==200){
                // show success
                $('#konfirmasi_kunjungan').html('<div class="center" style="padding-top: 20px"><span style="font-size: 36px; font-weight: bold; color: green"><i class="fa fa-check-circle green bigger-250"></i><br>PENDAFTARAN BERHASIL DILAKUKAN!</span><br><span style="font-size: 20px">Silahkan menunggu diruang tunggu pelayanan untuk dipanggil.</span></div><br><div class="center"><a href="#" class="btn btn-lg" style="background : green !important; border-color: green;" onclick="getMenu('+"'kiosk/Kiosk/bpjs'"+')"> <i class="fa fa-arrow-left"></i> Kembali ke Menu Sebelumnya</a> <a href="#" class="btn btn-lg" style="background : green !important; border-color: green"><i class="fa fa-print"></i> Cetak Ulang Bukti Pendaftaran</a></div>');

                // setTimeout(function () {
                //         location.reload();
                // }, 5000);
                    
                }else{
                    return false;
                }
                // hide loader
                achtungHideLoader();
                
            }
        });

    }

    $('#btnSearchNoRujukan').click(function (e) {
        e.preventDefault();

        var field = 'noRujukan';
        var jenis_faskes_pasien = 'pcare';
        var flag = 'noRujukan';
        var noRujukan = $('#noRujukan').val();
        var idTcPesanan = $('#id_tc_pesanan').val();

        // $('#change_modul_view_perjanjian').load('registration/Reg_klinik/show_modul/8/'+idTcPesanan+'') ;
        // $('#form_registration').attr('action', 'registration/Reg_klinik/processRegisterNSEP');

        e.preventDefault();
        $.ajax({
        url: 'ws_bpjs/ws_index/searchRujukan',
        type: "post",
        data: {flag:flag, keyvalue:noRujukan, jenis_faskes:jenis_faskes_pasien, noKartuBPJS: $('#noKartuBpjs').val() },
        dataType: "json",
        beforeSend: function() {
            achtungShowLoader();  
        },
        success: function(data) {
            achtungHideLoader();
            if(data.status==200){

                var rujukan = data.result.rujukan;
                var peserta = data.result.peserta;
                var diagnosa = data.result.diagnosa;
                var pelayanan = data.result.pelayanan;
                var poliRujukan = data.result.poliRujukan;
                var provPerujuk = data.result.provPerujuk;
                console.log(provPerujuk.kode);

                /*show hidden*/
                // $('#result-dt-rujukan').show('fast');
                // $('#showFormPenjaminKLL').hide('fast');
                // $('#showResultData').show('fast');

                // jika no_mr tidak sama dengan no mr rujukan show warning
                // if(peserta.mr.noMR != $('#noMrHidden').val() ){
                //     find_pasien_by_keyword(peserta.mr.noMR);
                //     $('#perjanjian_result_view_div').html('<div class="center red" style="font-weight: bold; font-style: italic; padding-top: 25px; font-size: 16px">Silahkan pilih kode booking kembali!</div>');
                //     $('#search_kode_perjanjian_result').hide();
                // }

                /*text*/
                $('#no_kartu_bpjs_txt').text(peserta.noKartu);
                $('#nama_pasien_txt').text(peserta.nama);
                $('#diagnosa_txt').text(diagnosa.nama);
                $('#ppk_asal_rujukan_txt').text(provPerujuk.nama);



                $('#noSuratSKDP').val($('#noSuratKontrol').val());
                $('#user').val(peserta.nama);
                $('#nik').text(peserta.nik);
                $('#tglLahir').text(peserta.tglLahir);
                $('#umur_p_bpjs').text(peserta.umur.umurSekarang);
                $('#jenisPeserta').text(peserta.jenisPeserta.keterangan);
                $('#hakKelas').text(peserta.hakKelas.keterangan);
                $('#statusPeserta').text(peserta.statusPeserta.keterangan);

                /*form*/
                $('#noKartuHidden').val(peserta.noKartu);
                $('#noMR').val(peserta.mr.noMR);
                $('#noKartuReadonly').val(peserta.noKartu);
                $('#namaPasienReadonly').val(peserta.nama);
                $('#inputKeyPoliTujuan').val(poliRujukan.nama);
                $('#kodePoliHiddenTujuan').val(poliRujukan.kode);
                $('#inputKeyFaskes').val(provPerujuk.nama);
                $('#kodeFaskesHidden').val(provPerujuk.kode);
                $('#noRujukanView').val(rujukan.noKunjungan);
                $('#tglKunjungan').val(rujukan.tglKunjungan);
                $('#inputKeyDiagnosa').val(diagnosa.nama);
                $('#kodeDiagnosaHidden').val(diagnosa.kode);
                $('#noTelp').val(peserta.mr.noTelepon);
                $('#catatan').val(rujukan.keluhan);

                /*show dokter DPJP*/
                // $.getJSON("ws_bpjs/Ws_index/getRef?ref=GetRefDokterDPJPRandom", { spesialis:$('#kodePoliHidden').val(),jp:$('input[name=jnsPelayanan]:checked').val(),tgl:$('#tglSEP').val(), dokterDPJP:$().val() }, function (row) {
                //       $('#KodedokterDPJP').val(row.kode);
                //       $('#InputKeydokterDPJP').val(row.nama.toUpperCase());    
                //       $('#show_dpjp').val(row.nama.toUpperCase());    
                // });

                // default from perjanjian
                $('#KodedokterDPJP').val($('#kodeDokterDPJPPerjanjianBPJS').val());
                $('#InputKeydokterDPJP').val($('#namaDokterDPJPPerjanjianBPJS').val());    
                $('#show_dpjp').val($('#namaDokterDPJPPerjanjianBPJS').val());

                // set value default
                $('#kode_perusahaan_hidden').val(120);
                $('#kode_kelompok_hidden').val(3);
                $('#noKartuBpjs').val(peserta.noKartu);
                $("input[name=jnsPelayanan][value="+pelayanan.kode+"]").attr('checked', true);

            }else{
                $.achtung({message: data.message, timeout:5, className: 'achtungFail'});
            }
            
        }
        });

    });




</script>

<script src="<?php echo base_url()?>assets/jSignature/js/jquery.signature.custom.js"></script>
<script>
$(function() {

  var sig = $('#sig').signature({thickness: 4});

	$('#clear').click(function() {
		sig.signature('clear');
    $('#paramsSignature').val('');

  });
  
  $('#jpg').click(function() {
    $('#paramsSignature').val(sig.signature('toDataURL', 'image/png', 1));
    $('#btnPrintSEP').show('fast');
  });
  
});


</script>

