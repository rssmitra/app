<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<style>
    .control-label{
        font-size: 14px !important;
    }
</style>
<form class="form-horizontal" method="post" id="form_booking" action="<?php echo site_url('registration/Reg_pasien/process_perjanjian')?>" enctype="multipart/form-data" autocomplete="off">   
<div class="row">
    <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-8">
            <div class="widget-box effect8">
                <div class="widget-header">
                    <h4 class="widget-title">PENCARIAN DATA PASIEN</h4>
                </div>

                <div class="widget-body">
                    <div class="widget-main">
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
                <div class="row" style="margin-top:0px; padding: 8px">
                    <div id="view_last_message"></div>

                    <div class="form-group">
                        <label class="control-label col-sm-3">Jenis Pasien</label>
                        <div class="col-md-8">
                        <div class="radio">
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
                            <?php echo $this->master->custom_selection($params = array('table' => 'mt_perusahaan', 'id' => 'kode_perusahaan', 'name' => 'nama_perusahaan', 'where' => array()), '' , 'kode_perusahaan', 'kode_perusahaan', 'form-control', '', '') ?> 
                        </div>
                    </div>

                    <hr>

                    <p style="font-size:12px; font-style: italic">Jadwal Praktek Dokter Tanggal <?php echo date('d/M/Y')?>, Silahkan pilih Poli/Klinik Spesialis dibawah ini : </p>
                    <table class="table table-bordered table-hovered">
                        <thead>
                        <tr>
                            <th class="center">No</th>
                            <th>Nama Dokter</th>
                            <th>Poli/Klinik</th>
                            <th>Jam Praktek</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no=0; foreach($klinik as $key=>$row) : $no++; ?>
                        <tr style="font-size: 14px">
                            <td style="vertical-align: middle" class="center"><?php echo $no;?></td>
                            <td style="vertical-align: middle"><?php echo $row->nama_pegawai;?></td>
                            <td style="vertical-align: middle"><?php echo ucwords($row->nama_bagian);?></td>
                            <td style="vertical-align: middle"><?php echo $this->tanggal->formatTime($row->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($row->jd_jam_selesai);?></td>
                            <td style="vertical-align: middle" class="center"><a href="#" class="btn btn-success">Pilih</a></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
   
        </div>
    <div class="col-xs-2">&nbsp;</div>
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


});

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
        $('#message_result_pasien').html('<div class="center"><img src="<?php echo base_url()?>assets/kiosk/alert.jpeg" style="width: 100px "><strong><h3>Pemberitahuan !</h3> </strong><span style="font-size: 14px">'+response.message+'</span></div>');

        }
        
    }
    });

}


</script>