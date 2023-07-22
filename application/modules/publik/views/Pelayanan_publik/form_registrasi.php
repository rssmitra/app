<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script type="text/javascript">

  jQuery(function($) {  

    var disableDates = getLiburNasional(<?php echo date('Y')?>);
    var today = new Date();
    var tomorrow = today.setDate(today.getDate() + 1);

    $("#tgl_registrasi").datepicker({

      autoclose: true,    
      todayHighlight: true,
      daysOfWeekDisabled: [0],
      format: 'yyyy-mm-dd',
      startDate: new Date(),
      endDate: '+1d', 
      minDate: '0',
      numberOfMonths: 1,
      beforeShowDay: function(date){

          dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
          if(disableDates.indexOf(dmy) != -1){
              return false;
          }
          else{
            return true;
          }

      }

    }).on("change", function() {
        
        $('#pilih_kunjungan').show();
        // hidden
        $('#reg_klinik_rajal_txt').val('');
        $('#reg_dokter_rajal_txt').val('');
        $('#jam_praktek_mulai').val('');
        $('#jam_praktek_selesai').val('');
        $('#sisa_kuota').val('');
        $('#kuotadr').val('');
        $('#jd_id').val('');
        $('#message_for_kuota').html('');
        getKlinikByJadwalDefault();

    });

  });

  $(document).ready(function(){

    getKlinikByJadwalDefault(); 
    if($('#keyword_ID').val() != ''){
      searchItem();
    }

    $('#form-registrasi').ajaxForm({
      beforeSend: function() {
        achtungShowFadeIn();  
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status === 200){
          $.achtung({message: jsonResponse.message, timeout:5});
          getMenu('publik/Pelayanan_publik/konfirmasi_kunjungan/'+jsonResponse.no_kunjungan+'?noKartu='+$('#noKartuBpjs').val()+'&tgl='+jsonResponse.tgl_registrasi+'&jam_praktek='+jsonResponse.jam_praktek+'');
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
          // status sudah pernah terdaftar
          if(jsonResponse.status === 202){
            getMenu('publik/Pelayanan_publik/konfirmasi_kunjungan/'+jsonResponse.no_kunjungan+'');
          }
        }
        achtungHideLoader();
      }
    });          

  })

  function searchItem(){
    
    $('#spinner_loading').html('Loading...');
    $('#msg_ress_rujukan').hide();
    $('#result_rujukan').hide();
    $('#message_for_kuota').hide();
    
    var keyword = $('#keyword_ID').val();
    var search_by = $('input[name=search_by]').filter(':checked').val();

    if(keyword == ''){
      alert('Masukan Keyword!'); return false;
    }

    $.getJSON("<?php echo site_url('publik/Pelayanan_publik/search_pasien_public') ?>?keyword=" + keyword + "&search_by=" + search_by, '', function (data) {      
      
      // jika data ditemukan
      if( data.count_kunjungan > 0){
        var obj_kunj = data.log_kunjungan[0];
        $('#spinner_loading').html('');
        $('#no-data-found').show();
        $('#result-find-pasien').hide();
        $('#no-data-found').html('<div class="alert alert-danger"><strong>Anda sudah terdaftar pada hari ini!</strong><br>Pendaftaran online hanya bisa dilakukan satu kali per hari, untuk selanjutnya silahkan datang langsung ke pendaftaran pasien.</div> <br> <b>Riwayat pendaftaran hari ini.</b><br><table class="table" style="background: #e9f5ff; width: 100%"><tr><td style="padding: 2px; background : #80808014; cursor: pointer" onclick="getMenu('+"'publik/Pelayanan_publik/konfirmasi_kunjungan/"+obj_kunj.no_kunjungan+"'"+')" ><table style="background: azure; width: 100%"><tr><td style="text-align: center; width: 70px;"><span style="font-size: 3em; font-weight: bold">'+obj_kunj.no_antrian+'</span><br><small>(no urut)</small></td><td><b>Tgl.'+obj_kunj.tgl_masuk+'</b><br>'+obj_kunj.poli+'<br>'+obj_kunj.dokter+'<br>'+obj_kunj.status+'</td></tr></table></td></tr></table>');
        return false;
      }

      if( data.count == 1 )     {
        
        $('#result-find-pasien').show();
        $('#btn-proses-registrasi').show();
        $('#no-data-found').hide();
        var obj = data.result[0];

        // for default breadcrumb
        $('#breadcrumb_nama_pasien').text(obj.nama_pasien+' ('+obj.jen_kelamin+')');
        $('#breadcrumb_description').text(obj.no_mr+' | '+obj.almt_ttp_pasien+' | '+getFormattedDate(obj.tgl_lhr)+'');

        // text
        $('#no_mr').text(obj.no_mr);
        $('#no_ktp').text(obj.no_ktp);
        $('#nama_pasien_txt').text(obj.nama_pasien+' ('+obj.jen_kelamin+')');
        $('#jk').text(obj.jen_kelamin);
        $('#alamat').text(obj.almt_ttp_pasien);
        $('#hp').text(obj.no_hp);
        $('#no_telp').text(obj.tlp_almt_ttp);
        $('#ttd_pasien').attr('src', obj.ttd);
        $('#tgl_lhr').text(getFormattedDate(obj.tgl_lhr));
        $('#noKartuBpjs').val(obj.no_kartu_bpjs);
        $('#nama_pasien_hidden').val(obj.nama_pasien);
        $('#hpPasien').val(obj.hp);
        $('#noMrHidden').val(obj.no_mr);
        $('#nikPasien').val(obj.no_ktp);

        if( obj.url_foto_pasien ){
          $('#avatar').attr('src', '<?php echo base_url()?>uploaded/images/photo/'+obj.url_foto_pasien+'');
        }else{
          if( obj.jen_kelamin == 'L' ){
            $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');
          }else{
            $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');
          }
        }

        penjamin = (obj.nama_perusahaan==null)?obj.nama_kelompok:obj.nama_perusahaan;
        kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;

         // value

        $('#spinner_loading').html('');

        $('#nama_pasien').val(obj.nama_pasien);
        $('#no_mr_val').val(obj.no_mr);
        var umur_pasien = hitung_usia(obj.tgl_lhr);
        $('#umur_saat_pelayanan_hidden').val(umur_pasien);
        $('#penjamin').text(penjamin);
        $('#kode_kelompok_hidden').val(obj.kode_kelompok);
        $('#kode_perusahaan_hidden').val(obj.kode_perusahaan);
        
        

      }else{              
        $('#spinner_loading').html('');
        $('#btn-proses-registrasi').hide();
        $('#result-find-pasien').hide();
        $('#no-data-found').show();
        $('#no-data-found').html('<div class="alert alert-danger"><strong>Data tidak ditemukan!</strong><br>Silahkan masukan No Rekam Medis/NIK anda dengan benar, atau silahkan klik <a href="#" onclick="getMenu('+"'publik/Pelayanan_publik/pasien_baru'"+')" style="font-style: italic; font-weight: bold">disini</a> untuk daftar sebagai pasien baru.<div>'); 

      }           

    }); 

  }

  $( "#keyword_ID" )    
    .keypress(function(event) {        
      var keycode =(event.keyCode?event.keyCode:event.which);         
      if(keycode ==13){          
        event.preventDefault();          
        if($(this).valid()){            
          $('#btn-search-data').click();            
        }          
        return false;                 
      }        
  });

  $( "#noRujukan" )    
    .keypress(function(event) {        
      var keycode =(event.keyCode?event.keyCode:event.which);         
      if(keycode ==13){          
        event.preventDefault();          
        if($(this).valid()){            
          $('#btnSearchNoRujukan').click();            
        }          
        return false;                 
      }        
  });

  $('input[name=jenis_pasien]').on('change',function () {
    var val_radio = $(this).filter(':checked').val();
    if(val_radio == 'bpjs'){
      // show no rujukan
      $('#search_rujukan').show();
      $('#div_asuransi').hide();
      $('#btn-proses-registrasi').hide();
    }else if(val_radio == 'asuransi'){
      $('#search_rujukan').hide();
      $('#div_asuransi').show();
      $('#btn-proses-registrasi').show();
      $('#is_expired').val(0);
    }else{
      $('#search_rujukan').hide();
      $('#div_asuransi').hide();
      $('#btn-proses-registrasi').show();
      $('#is_expired').val(0);
    }
  });

  $('select[name="reg_klinik_rajal"]').change(function () {  

    /*current day*/
    current_day = $('#current_day').val();

    var url_get_dokter = '<?php echo site_url('publik/Pelayanan_publik/getJadwalDokter/')?>'+$(this).val()+'/'+current_day+'/'+$('#tgl_registrasi').val()+'';

    
    if ($(this).val() != '012801') {     
        $('#reg_dokter_rajal').attr('name', 'reg_dokter_rajal');
        $('#reg_dokter_rajal_dinamis').attr('name', 'reg_dokter_rajal_');
        $('#dokter_dinamis_klinik').hide('fast')
        $('#dokter_by_klinik').show('fast')
        $.getJSON(url_get_dokter, '', function (data) {   
            $('#reg_dokter_rajal option').remove();         
            $('<option value="">-Pilih Dokter-</option>').appendTo($('#reg_dokter_rajal'));  
            $.each(data, function (i, o) {   
                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + ' - ( <span class="pull-right"> '+o.jam_mulai+' - '+o.jam_selesai+'</span>)</option>').appendTo($('#reg_dokter_rajal'));  
            });   
        });   
    } else {    
        $('#reg_dokter_rajal option').remove()  
        $('#reg_dokter_rajal_dinamis').attr('name', 'reg_dokter_rajal');
        $('#reg_dokter_rajal').attr('name', 'reg_dokter_rajal_');
        $('#dokter_by_klinik').hide('fast')
        $('#dokter_dinamis_klinik').show('fast')
    } 

    // title
    $('#title-select-klinik').text( $('#reg_klinik_rajal option:selected').text().toUpperCase() );
    $('#reg_klinik_rajal_txt').val( $('#reg_klinik_rajal option:selected').text().toUpperCase() );
    }); 

    $('select[id="reg_dokter_rajal"]').change(function () {      

    if ($(this).val()) {          

        if (($('#show_all_poli').is(':checked'))) {
            return false;
        }else{
            $.getJSON("<?php echo site_url('publik/Pelayanan_publik/getKuotaDokter') ?>/" + $(this).val() + '/' +$('select[name="reg_klinik_rajal"]').val()+'/'+$('#tgl_registrasi').val() , '', function (data) {  

                var objData = data.data;
                $('#kuotadr').val(objData.kuota); 
                $('#sisa_kuota').val(data.sisa_kuota); 
                $('#kode_dokter_bpjs').val(objData.kode_dokter_bpjs); 
                $('#reg_dokter_rajal_txt').val( $('#reg_dokter_rajal option:selected').text().toUpperCase() );
                $('#kode_poli_bpjs').val(objData.kode_poli_bpjs); 
                $('#jam_praktek_mulai').val(objData.jam_praktek_mulai); 
                $('#jam_praktek_selesai').val(objData.jam_praktek_selesai); 
                $('#jd_id').val(objData.jd_id); 
                $('#message_for_kuota').show();
                $('#message_for_kuota').html(data.message);              
                if(data.sisa_kuota > 0){
                    $('#btn-proses-registrasi').show('fast');
                    $('#message_for_kuota').html('<div class="alert alert-success" style="padding: 5px !important"><b>Available ! </b>Sisa kuota dokter <b>'+data.sisa_kuota+'</b> pasien.<br>'+objData.keterangan+'</div>');
                }else{
                    $('#btn-proses-registrasi').hide('fast');
                    $('#message_for_kuota').html('<span style="color: red; font-weight: bold; font-style:italic">- Kuota dokter penuh, silahkan ganti tanggal lain !</span>');
                }
                $('#jd_id').val(data.jd_id); 
            });            

            $('#title-select-dokter').text( $('#reg_dokter_rajal option:selected').text() );
        }
    }    
  }); 

  function getKlinikByJadwalDefault(){

    date = ($('#tgl_registrasi').val() != '') ? $('#tgl_registrasi').val() : '<?php echo date('Y-m-d')?>';
    days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    var d = new Date(date);
    current_day = days[d.getDay()]; 
    // change day
    $('#current_day').val(current_day);
    
    var url = 'getKlinikFromJadwal';
    $('#reg_dokter_rajal option').remove();  

    $.getJSON("<?php echo site_url('publik/Pelayanan_publik/') ?>"+url+"/" +current_day+'/'+date, '', function (data) {              
        $('#reg_klinik_rajal option').remove();  
        $('<option value="">-Pilih Klinik-</option>').appendTo($('#reg_klinik_rajal'));
        $.each(data, function (i, o) {                  
            $('<option value="' + o.kode_bagian + '">' + o.nama_bagian + '</option>').appendTo($('#reg_klinik_rajal'));                    
        });     
    });  
  }

  $('#InputKeyPenjamin').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getPerusahaan",
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
        if( val_item == 120 ){
          $('#form_sep').show();
        }else{
          $('#form_sep').hide();
        }
      }
  });

  $('#btnSearchNoRujukan').click(function (e) {
    e.preventDefault();
    var field = 'noRujukan';
    var jenis_faskes_pasien = 'pcare';
    var flag = 'noRujukan';
    var noRujukan = $('#noRujukan').val();
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
            var dueDate = data.result.masaBerlakuRujukan;
            
            var var_a = peserta.noKartu;
            var noKartuBpjsRujukan = var_a.replace(/\s/g, '');
            var var_b = $('#noKartuBpjs').val();
            var noKartuBpjsRs = var_b.replace(/\s/g, '');

            if(noKartuBpjsRujukan != noKartuBpjsRs){
              $('#msg_ress_rujukan').show();
              $('#msg_ress_rujukan').html('<div class="alert alert-danger"><strong>Pemberitahuan !</strong><br>Nomor Kartu BPJS anda belum terdaftar di RS Setia Mitra.<div>');
              $('#result_rujukan').hide();
              return false;
            }

            if(peserta.statusPeserta.keterangan != 'AKTIF'){
              $('#msg_ress_rujukan').show();
              $('#msg_ress_rujukan').html('<div class="alert alert-danger"><strong>Pemberitahuan !</strong><br>Status Peserta anda <b>'+peserta.statusPeserta.keterangan+'</b><div>');
              $('#result_rujukan').hide();
              $('#btn-proses-registrasi').hide();
              return false;
            }else{
              $('#msg_ress_rujukan').show();
              var fit_start_time  = new Date();
              var fit_end_time    = dueDate; //2013-09-10
              if(Date.parse(fit_start_time) <= Date.parse(fit_end_time)){
                $('#msg_ress_rujukan').html('<div class="alert alert-success"><strong>PESERTA '+peserta.statusPeserta.keterangan+' !</strong><br>Masa berlaku rujukan anda s.d tanggal <b><i>'+dueDate+'</i></b> dengan tujuan poli spesialis <b><i>'+poliRujukan.nama+'</i></b> <div>');
                $('#btn-proses-registrasi').show();
              }else{
                $('#msg_ress_rujukan').html('<div class="alert alert-danger"><strong>RUJUKAN EXPIRED !</strong><br>Masa berlaku rujukan anda expired s.d tanggal <b><i>'+dueDate+'</i></b><div>');
                $('#btn-proses-registrasi').hide();
              }

              
            }

            $('#result_rujukan').show();
            console.log(provPerujuk);
            $('#faskes_perujuk').text(provPerujuk.nama);
            $('#masa_berlaku').text(rujukan.tglKunjungan);
            $('#status_peserta').text(peserta.statusPeserta.keterangan);
            $('#kelas_peserta').text(peserta.hakKelas.keterangan);
            $('#poli_rujukan').text(poliRujukan.nama);
            
            $('#result_rujukan').show();

            /*text*/
            $('#noSuratSKDP').val($('#noSuratKontrol').val());
            $('#noKartuFromNik').text(peserta.noKartu);
            $('#nama').text(peserta.nama);
            $('#user').val(peserta.nama);
            $('#nik').text(peserta.nik);
            $('#tglLahir').text(peserta.tglLahir);
            $('#umur_p_bpjs').text(peserta.umur.umurSekarang);
            $('#jenisPeserta').text(peserta.jenisPeserta.keterangan);
            $('#hakKelas').text(peserta.hakKelas.keterangan);
            $('#statusPeserta').text(peserta.statusPeserta.keterangan);

            /*form*/
            $('#noKartuBpjs').val(peserta.noKartu);
            $('#noMR').val(peserta.mr.noMR);
            $('#noKartuReadonly').val(peserta.noKartu);
            $('#namaPasienReadonly').val(peserta.nama);
            $('#inputKeyPoliTujuan').val(poliRujukan.nama);
            $('#kodePoliHiddenTujuan').val(poliRujukan.kode);
            $('#inputKeyFaskes').val(provPerujuk.nama);
            $('#noRujukanView').val(rujukan.noKunjungan);
            $('#tglKunjungan').val(rujukan.tglKunjungan);
            $('#inputKeyDiagnosa').val(diagnosa.nama);
            $('#kodeDiagnosaHidden').val(diagnosa.kode);
            $('#hpPasien').val(peserta.mr.noTelepon);
            $('#catatan').val(rujukan.keluhan);
            $('#kode_faskes_hidden').val(provPerujuk.kode);
            $('#is_expired').val(0);

        }else{
            $('#btn-proses-registrasi').hide();
            $('#is_expired').val(1);
            if(data.status == 202){
              $('#msg_ress_rujukan').show();
              $('#msg_ress_rujukan').html('<div class="alert alert-danger"><strong>RUJUKAN EXPIRED !</strong><br>'+data.message+'<div>');
            }
            $.achtung({message: data.message, timeout:5, className: 'achtungFail'});
        }
        
      }
    });
  });


</script>

<style type="text/css">
    .datepicker table tr td.disabled, .datepicker table tr td.disabled:hover {
      color: #070707 !important;
      background: #da0a0a73 !important;
    }

</style>

<form id="form-registrasi" autocomplete="off" method="POST" action="publik/Pelayanan_publik/proses_registrasi">

    <div class="pull-left">
      <a href="<?php echo base_url().'public'?>" class="btn btn-sm" style="background : green !important; border-color: green"> <i class="fa fa-home"></i> Home</a>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-12">
        
        <h3 class="header smaller lighter green">Pendaftaran Pasien</h3>
        <label style="font-weight: bold">Pencarian berdasarkan : </label>
        <div class="radio" style="margin-top: 0px !important;margin-bottom: 0px !important;">
          <label>
            <input name="search_by" type="radio" class="ace" value="no_mr" checked="checked"  />
            <span class="lbl"> No. Rekam Medis</span>
          </label>
          <label>
            <input name="search_by" type="radio" class="ace" value="no_ktp"/>
            <span class="lbl"> NIK </span>
          </label>
          <label>
            <input name="search_by" type="radio" class="ace" value="no_kartu_bpjs"/>
            <span class="lbl"> No. Kartu BPJS </span>
          </label>
        </div>
        <br>
        <label style="font-weight: bold">Masukan Kata Kunci : </label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="ace-icon fa fa-check"></i>
          </span>

          <input type="text" class="form-control search-query" id="keyword_ID" placeholder="" value="<?php echo isset($_GET['mr'])?$_GET['mr']:''?>">
          <span class="input-group-btn">
            <button type="button" class="btn btn-purple btn-sm" id="btn-search-data" onclick="searchItem()" style="background: green !important; border-color: green !important">
              <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
              Cari Data Pasien
            </button>
          </span>
        </div>
        
        <span style="font-size: 12px;font-style: italic;">Masukan keyword, lalu klik "enter"</span>

        <div class="hr"></div>

        <div id="spinner_loading"></div>

        
        <div id="no-data-found" style="display: none">
          <div class="center" style="padding-top: 30px !important">
            <img src="<?php echo base_url()?>assets/images/no-data-found.png" width="200px">
          </div>
        </div>

        <div id="result-find-pasien" class="tab-pane active" style="display: none">
          <!-- data pasien lainnya -->
          <input type="hidden" name="noKartuBpjs" id="noKartuBpjs">
          <input type="hidden" name="noMrHidden" id="noMrHidden">
          <input type="hidden" name="hpPasien" id="hpPasien">
          <input type="hidden" name="nikPasien" id="nikPasien">
          <input type="hidden" name="nama_pasien_hidden" id="nama_pasien_hidden">
          <input type="hidden" name="umur_saat_pelayanan_hidden" id="umur_saat_pelayanan_hidden">
          
          <div class="row">
            <div class="col-xs-12 col-sm-12">
            
              <span class="middle" style="font-weight: bold; padding-bottom: 10px">Informasi Data Pasien</span>
              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <small style="color: #669a06; font-weight: bold; font-size: 11px">No. Rekam Medis: </small><div id="no_mr"></div>
                </li>
                <li class="list-group-item">
                  <small style="color: #669a06; font-weight: bold; font-size: 11px">Nama Pasien: </small><div id="nama_pasien_txt"></div>
                </li>
                <li class="list-group-item">
                  <small style="color: #669a06; font-weight: bold; font-size: 11px">NIK: </small><div id="no_ktp"></div>
                </li>
                <li class="list-group-item">
                  <small style="color: #669a06; font-weight: bold; font-size: 11px">Alamat: </small><div id="alamat"></div>
                </li>
                <li class="list-group-item">
                  <small style="color: #669a06; font-weight: bold; font-size: 11px">Tgl Lahir: </small><div id="tgl_lhr"></div>
                </li>
              </ul>
              
              <div class="hr hr-8 dotted"></div>

              
              <label style="font-weight: bold"><span class="red">*</span> Kriteria Pasien : </label>
              <div class="radio" style="margin-top: 0px !important;margin-bottom: 0px !important;">
                <label>
                  <input name="jenis_pasien" type="radio" class="ace" value="bpjs" checked="checked"  />
                  <span class="lbl"> BPJS Kesehatan</span>
                </label>
                <label>
                  <input name="jenis_pasien" type="radio" class="ace" value="umum"/>
                  <span class="lbl"> Umum </span>
                </label>
                <label>
                  <input name="jenis_pasien" type="radio" class="ace" value="asuransi"/>
                  <span class="lbl"> Asuransi </span>
                </label>
              </div>
              <div id="search_rujukan" style="display: block">
                <br>
                <label style="font-weight: bold"><span class="red">*</span> Cari Nomor Rujukan Faskes: </label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="ace-icon fa fa-check"></i>
                  </span>
                  <input type="text" class="form-control search-query" id="noRujukan" name="noRujukan" placeholder="">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-purple btn-sm" id="btnSearchNoRujukan" style="background: green !important; border-color: green !important">
                      <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                      Cari Rujukan
                    </button>
                  </span>
                  <input type="hidden" name="kode_faskes_hidden" id="kode_faskes_hidden">
                  <input type="hidden" name="is_expired" id="is_expired" value="0">
                </div>

                <div id="msg_ress_rujukan"></div>

                <div id="result_rujukanxx" style="display: none">
                  <span class="middle" style="font-weight: bold; padding-bottom: 10px">Informasi Data Rujukan</span>
                  <table>
                    <tr><td><small style="font-weight: bold; color: #669a06">Faskes perujuk :</small><br><span id="faskes_perujuk"></span></td><tr>
                    <tr><td><small style="font-weight: bold; color: #669a06">Masa berlaku rujukan :</small><br><span id="masa_berlaku"></span></td><tr>
                    <tr><td><small style="font-weight: bold; color: #669a06">Status peserta :</small><br><span id="status_peserta"></span></td><tr>
                    <tr><td><small style="font-weight: bold; color: #669a06">Kelas :</small><br><span id="kelas_peserta"></span></td><tr>
                    <tr><td><small style="font-weight: bold; color: #669a06">Poli Rujukan :</small><br><span id="poli_rujukan"></span></td><tr>
                  </table>
                </div>
              </div>
              

              <div id="div_asuransi" style="display: none">
                <br>
                <label style="font-weight: bold"><span class="red">*</span> Pilih Asuransi : </label>
                <input id="InputKeyPenjamin" class="form-control" name="penjamin" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                <input type="hidden" name="kode_perusahaan_hidden" value="" id="kode_perusahaan_hidden">
              </div>
              <br>
              <label style="font-weight: bold"><span class="red">*</span> Tanggal Kunjungan : </label>
              <div class="input-group">
                  <input name="tgl_registrasi" id="tgl_registrasi" data-date-format="yyyy-mm-dd" value=""  class="form-control date-picker" type="text">
                  <span class="input-group-addon">
                  <i class="ace-icon fa fa-calendar"></i>
                  </span>
              </div>
              <br>
              <div id="pilih_kunjungan" style="display: none">
                <label style="font-weight: bold"><span class="red">*</span> Pilih Poli Spesialis :</label>
                <select class="form-control" name="reg_klinik_rajal" id="reg_klinik_rajal">
                    <option value="">-Pilih-</option>
                </select>
                <br>
                <label style="font-weight: bold"><span class="red">*</span> Pilih Dokter :</label>
                <select class="form-control" name="reg_dokter_rajal" id="reg_dokter_rajal">
                    <option value="">-Pilih-</option>
                </select>
                <input type="hidden" name="reg_klinik_rajal_txt" id="reg_klinik_rajal_txt" class="form-control">
                <input type="hidden" name="reg_dokter_rajal_txt" id="reg_dokter_rajal_txt" class="form-control">
                <input type="hidden" name="jam_praktek_mulai" id="jam_praktek_mulai" class="form-control">
                <input type="hidden" name="jam_praktek_selesai" id="jam_praktek_selesai" class="form-control">
                <input type="hidden" name="sisa_kuota" id="sisa_kuota" readonly>
                <input type="hidden" name="kuotadr" id="kuotadr" readonly>
                <input type="hidden" name="jd_id" id="jd_id" readonly>
                <input type="hidden" name="kode_poli_bpjs" id="kode_poli_bpjs" readonly>
                <input type="hidden" name="kode_dokter_bpjs" id="kode_dokter_bpjs" readonly>
                <input name="current_day" id="current_day" class="form-control" type="hidden" value="<?php echo $this->tanggal->gethari(date('D'))?>">
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div>
        <div id="message_for_kuota"></div>

      </div>
    </div>
    <hr>
    <div class="center" id="btn-proses-registrasi" style="display: none">
      <button class="btn btn-block" type="submit" style="background : green !important; border-color: green; height: 43px !important;text-transform: uppercase;font-weight: bold;">Proses Pendaftaran</button>
    </div>
</form>