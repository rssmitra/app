<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<style>
    .control-label{
        font-size: 14px !important;
    }
    .small-box > .inner {
        padding: 5px;
        padding-top: 10px;
    }

    .small-box{
        margin-bottom: -5px;
    }
</style>
<form class="form-horizontal" method="post" id="form_booking" action="<?php echo site_url('registration/Reg_pasien/process_perjanjian')?>" enctype="multipart/form-data" autocomplete="off">   

<div class="row" id="search_nomr_div">
    <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-8">
            <div class="widget-box effect8">
                <div class="widget-header">
                    <h4 class="widget-title">PENCARIAN DATA PASIEN</h4>
                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <div>
                            <label for="form-field-mask-1" style="font-size: 16px">
                                <b>Silahkan Masukan Nomor Rekam Medis anda.</b>
                            </label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="keyword_mr" name="keyword_mr" style="font-size:40px;height: 55px !important; width: 100%; text-align: left !important">
                                <span class="input-group-btn">
                                    <button class="btn btn-sm btn-primary" type="button" id="btnSearchPasien" style="height: 55px !important; font-size: 16px;">
                                        <i class="ace-icon fa fa-search bigger-110"></i>
                                        Temukan!
                                    </button>   
                                </span>
                            </div>
                            <div style="width: 100%; text-align: left !important; padding-top: 20px">
                                <span> * Jika anda lupa atau belum memiliki Nomor Rekam Medis, silahkan ambil <a href="#" onclick="scrollSmooth('Self_service/antrian_poli')"><b>Nomor Antrian</b></a> untuk ke <b>Bagian Administrasi Pasien</b></span>
                            </div>
                        </div>
                        <!-- <div style="width: 100%; margin-top: 10px; text-align: center">
                            <button class="btn btn-sm btn-primary" type="button" id="btnSearchPasien" style="height: 35px !important; font-size: 14px">
                                <i class="ace-icon fa fa-search bigger-110"></i>
                                Cari data pasien
                            </button>
                        </div> -->
                        
                    </div>
                </div>
            </div>
        </div>
    <div class="col-xs-2">&nbsp;</div>
</div>

<div class="row" id="result_nomr_div">
    <div class="col-xs-12">
        <div id="message_result_pasien" style="text-align: left !important;"></div>
        <div class="row" style="display: none;" id="resultSearchPasien">
            <div class="row" style="margin-top:0px;">

                <div id="view_last_message"></div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">No. Rekam Medis</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" value="" id="no_mr" name="no_mr" readonly>
                    </div>
                    <label class="control-label col-sm-2">Nama Pasien</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" value="" id="nama_pasien" name="nama_pasien" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Jenis Pasien</label>
                    <div class="col-md-4">
                    <div class="radio" style="text-align: left">
                            <label>
                            <input name="jenis_penjamin" type="radio" class="ace" value="Jaminan Perusahaan" />
                            <span class="lbl"> Jaminan Perusahaan</span>
                            </label>
                            <label>
                            <input name="jenis_penjamin" type="radio" class="ace" value="Umum" />
                            <span class="lbl"> Umum</span>
                            </label>
                    </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Perusahaan Penjamin</label>
                    <div class="col-md-6">
                        <input id="InputKeyPenjamin" class="form-control" name="penjamin" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                        <input type="hidden" name="kode_perusahaan_hidden" value="" id="kode_perusahaan_hidden"> 
                    </div>
                </div>

                <hr>
                <div id="div_select_tujuan_kunjungan">
                    <table width="100%">
                        <tr class="center">
                            <td style="width: 33%; height: 55px; font-size: 20px;font-weight: bold text-align: center; background: red">POLI/KLINIK SPESIALIS</td>
                            <td style="width: 33%; height: 55px; font-size: 20px;font-weight: bold text-align: center; background: blue">PENUNJANG MEDIS</td>
                            <td style="width: 33%; height: 55px; font-size: 20px;font-weight: bold text-align: center; background: green">IGD</td>
                        </tr>
                    </table>
                </div>
                <div id="div_select_jadwal_dokter" style="display: none">
                    <p style="font-size:12px; font-style: italic">Jadwal Praktek Dokter Tanggal <?php echo date('d/M/Y')?>, Silahkan pilih Poli/Klinik Spesialis dibawah ini : </p>
                    
                    <?php 
                        $no=0; foreach($klinik as $key=>$row) : $no++; 
                        $arr_color = array('bg-red','bg-yellow','bg-aqua','bg-blue','bg-light-blue','bg-green','bg-navy','bg-teal','bg-olive','bg-lime','bg-orange','bg-fuchsia','bg-purple','bg-maroon','bg-black'); 
                        shuffle($arr_color);
                    ?>
                        <div class="col-lg-2 col-xs-3 no-padding" style="margin-top:5px; padding: 5px !important">
                        <!-- small box -->
                        <div class="small-box <?php echo array_shift($arr_color)?>" style="min-height: 115px; border-radius: 10px !important">
                            <div class="inner" style="line-height: 13px; min-height: 90px">
                            <h3 style="font-size:12px;word-wrap: break-word;"><?php echo strtoupper($row->nama_bagian)?></h3>
                            <p style="font-size:12px">
                                <?php echo $row->nama_pegawai?><br>
                                <?php echo $this->tanggal->formatTime($row->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($row->jd_jam_selesai);?>
                            </p>
                            </div>
                            <div class="icon" style="margin-top:-10px">
                            <i class="fa fa-stethoscope"></i>
                            </div>
                            <?php 
                            echo '<a href="'.base_url().'dashboard?mod='.$row->jd_id.'" class="small-box-footer"><b>PENDAFTARAN</b> <i class="fa fa-arrow-circle-right"></i></a>';
                            ?>
                        </div>
                        </div>
                    <?php endforeach; ?>
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

    var today = getDateToday();
    console.log(today);
	$('#btnSearchPasien').click(function (e) {
      e.preventDefault();
      findPasien();
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

function selected_item(){


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
                
                $('#no_mr').val(obj.no_mr);
                $('#nama_pasien').val(obj.nama_pasien);
                $('#message_result_pasien').html('<div class="alert alert-success"><strong>Selamat Datang, </strong>&nbsp; '+obj.nama_pasien+' ('+obj.no_mr+'), silahkan pilih tujuan kunjungan anda.</div>');

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
                $('#message_result_pasien').html('<div class="center"><img src="<?php echo base_url()?>assets/kiosk/alert.jpeg" style="width: 100px "><strong><h3>Pemberitahuan !</h3> </strong><span style="font-size: 14px">'+response.message+'</span></div>');

            }
            
        }
    });

}


</script>