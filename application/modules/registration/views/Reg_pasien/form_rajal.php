<script type="text/javascript">

$(document).ready(function(){

    // var kode_bag_hd = $('#kode_bagian_hidden').val() ? $('#kode_bagian_hidden').val() : $('#klinik_rajal').val()
    // var kode_dok_hd = $('#kode_bagian_hidden').val() ? $('#kode_bagian_hidden').val() : $('#dokter_rajal').val()
    // getJadwalPraktek(kode_bag_hd, kode_dok_hd);

    $('select[name="klinik_rajal"]').change(function () {      

        $('#show_detail_praktek').hide('fast');
        $('#tgl_kunjungan_form').hide('fast');
        $('#view_last_message').hide('fast');
        $('#show_jadwal_dokter_form').hide('fast');

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
        $('#dokter_rajal').focus();  

    });

    $('select[name="dokter_rajal"]').change(function () {      

        if ($(this).val()) {          

            var kode_spesialis = ($('#klinik_rajal').val()) ? $('#klinik_rajal').val() : '';

            getJadwalPraktek(kode_spesialis, $(this).val());
            // $.getJSON("<?php echo site_url('Templates/References/getJadwalPraktek') ?>/" + $(this).val() + '/' + kode_spesialis, '', function (data) {              

            //     /*here show data from jadwal praktek*/     
            //     $('#show_jadwal_dokter_form').show('fast');          
            //     $('#show_jadwal_dokter_form').html(data.html);
            //     $('#show_detail_praktek').hide('fast');
            //     $('#tgl_kunjungan_form').hide('fast');
            //     $('#view_last_message').hide('fast');
            // });            

        } else {          

            /*remove */       

        }      

    });  

})

function getJadwalPraktek(){
    $.getJSON("<?php echo site_url('Templates/References/getJadwalPraktek') ?>/" + $('#klinik_rajal').val() + '/' + $('#dokter_rajal').val(), '', function (data) {              
        /*here show data from jadwal praktek*/     
        $('#show_jadwal_dokter_form').show('fast');          
        $('#show_jadwal_dokter_form').html(data.html);
        $('#show_detail_praktek').hide('fast');
        $('#tgl_kunjungan_form').hide('fast');
        $('#view_last_message').hide('fast');
    }); 
}

function detailJadwalPraktek(jd_id){
    preventDefault();
    $.getJSON("<?php echo site_url('Templates/References/getDetailJadwalPraktek') ?>/" + jd_id, function (data) {
            /*here show data from jadwal praktek*/               
            $('#show_detail_praktek').html(data.html);
            $('#show_detail_praktek').show('fast');
            $('#tgl_kunjungan_form').show('fast');
            $('#selected_day').val(data.day);
            $('#selected_time').val(data.time);
            $('#time_start').val(data.time_start);
            $('#last_counter').val(data.terisi);
            $('#jd_id').val(data.id);
            $('#view_last_message').hide('fast');
            $('#view_msg_kuota').hide('fast');
            $('#tgl_kunjungan').val('');
            $("html, body").animate({ scrollTop: "700px" }, "slow"); 
    });
}

</script>

<p><b><i class="fa fa-edit"></i> PENDAFTARAN RAWAT JALAN </b></p>

<div class="form-group">

    <label class="control-label col-sm-2" for="Province">*Klinik</label>

    <div class="col-sm-5">

        <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1)), $kode_bagian , 'klinik_rajal', 'klinik_rajal', 'form-control', '', '') ?>

    </div>

</div>

<div class="form-group">

    <label class="control-label col-sm-2" for="City">*Dokter</label>

    <div class="col-sm-4">

        <?php echo $this->master->get_change($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), $kode_dokter , 'dokter_rajal', 'dokter_rajal', 'form-control', '', '') ?>

    </div>

</div>

<div id="show_jadwal_dokter_form"></div>

<div id="show_detail_praktek"></div>


