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

</style>

<div id="main_view_rj">
    
    <p style="text-align: center; font-size: 2em; font-weight: bold; color: green">JADWAL PRAKTEK DOKTER <br> TANGGAL <?php echo date('d/M/Y')?></p>

    <div class="row">
        <?php 
            $no=0; foreach($klinik as $key=>$row) : $no++; 
            $arr_color = array('#197715c2'); 
            shuffle($arr_color);
        ?>
        <div class="col-lg-3 col-xs-4 no-padding" style="margin-top:5px; padding: 5px !important">
            <div class="small-box <?php echo array_shift($arr_color)?>" style="min-height: 115px; border-radius: 0px 54px !important; border: 1px solid #bdbdbd">
                <div class="inner" style="line-height: 13px; min-height: 125px" onclick="select_dokter_poli(<?php echo $row->jd_kode_dokter?>, '<?php echo $row->jd_kode_spesialis?>', <?php echo $row->jd_id?>)">
                    <h3 style="font-size:18px; word-wrap: break-word;"><?php echo strtoupper($row->nama_bagian)?></h3>
                    <p style="font-size:14px; line-height: 17px">
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
        <a href="#" class="btn btn-lg" style="background : green !important; border-color: green;" onclick="getMenu('kiosk/Kiosk/umum_asuransi')"> <i class="fa fa-arrow-left"></i> Kembali ke Menu Sebelumnya</a>
    </div>

</div>

<div id="konfirmasi_kunjungan_rj" style="display: none">
    <div class="center" style="padding-top: 100px">
        <i class="fa fa-question-circle bigger-250 green" style="font-size: 80px !important"></i><br>
        <h2 class="green" style="font-weight: bold; font-size: 36px"><span id="txt-nama-poli">-</span><br><span id="txt-nama-dr">-</span></h2>
        <span style="font-size: 24px">Apakah anda yakin akan berkunjung ? </span>
        <br><br><br><br>
        <button type="button" class="btn btn-xs btn-primary" style="height: 45px !important;font-size: 20px;min-width: 320px; background: green !important; border-color: green" onclick="getMenu('kiosk/Kiosk/asum_mod_poli')"><i class="fa fa-arrow-left bigger-150"></i> Ganti Tujuan Kunjungan </button>
        <button type="button" onclick="submitForm()" class="btn btn-xs btn-primary" style="height: 45px !important;font-size: 20px;min-width: 320px; background: green !important; border-color: green"><i class="fa fa-print bigger-150"></i> Proses dan Cetak Bukti Pendaftaran</button>
    </div>
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
                $('#konfirmasi_kunjungan').html('<div class="center" style="padding-top: 20px"><span style="font-size: 36px; font-weight: bold; color: green"><i class="fa fa-check-circle green bigger-250"></i><br>PENDAFTARAN BERHASIL DILAKUKAN!</span><br><span style="font-size: 20px">Silahkan menunggu diruang tunggu pelayanan untuk dipanggil.</span></div><br><div class="center"><a href="#" class="btn btn-lg" style="background : green !important; border-color: green;" onclick="getMenu('+"'kiosk/Kiosk/umum_asuransi'"+')"> <i class="fa fa-arrow-left"></i> Kembali ke Menu Sebelumnya</a> <a href="#" class="btn btn-lg" style="background : green !important; border-color: green"><i class="fa fa-print"></i> Cetak Ulang Bukti Pendaftaran</a></div>');

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



</script>

