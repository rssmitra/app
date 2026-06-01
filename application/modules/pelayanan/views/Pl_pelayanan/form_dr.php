<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

jQuery(function($) {  

  $('.date-picker').datepicker({    

    autoclose: true,    

    todayHighlight: true    

  })  

  //show datepicker when clicking on the icon

  .next().on(ace.click_event, function(){    

    $(this).prev().focus();    

  });  

});

$(document).ready(function(){

    /*when page load find pasien by mr*/
    find_pasien_by_keyword('<?php echo $no_mr?>');
    get_riwayat_medis('<?php echo $no_mr?>');

    if($('#kode_perusahaan_val').val() == 120){
      show_icare();
    }

    $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveDiagnosaDr');

    // get data antrian pasien
    getDataAntrianPasien();

    /*focus on form input pasien*/
    $('#form_cari_pasien').focus();    

    $('#form_pelayanan').on('submit', function(){
               
        $('#konten').val($('#editor_konten').html());
        $('input[name=catatan_pengkajian]' , this).val($('#editor').html());
        $('#konten_diagnosa_sekunder').val($('#pl_diagnosa_sekunder_hidden_txt').html());


        var formData = new FormData($('#form_pelayanan')[0]);        
        i=0;
        url = $('#form_pelayanan').attr('action');

        // ajax adding data to database
        $.ajax({
            url : url,
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,            
            beforeSend: function() {
              // achtungShowLoader();   
              $(this).find("button[type='submit']").prop('disabled',true);
            },
            uploadProgress: function(event, position, total, percentComplete) {
            },
            complete: function(xhr) {     
              var data=xhr.responseText;    
              var jsonResponse = JSON.parse(data);  

              if( jsonResponse.status === 200 ){   

                if(jsonResponse.type_pelayanan == 'penunjang_medis')
                {
                  $('#table_order_penunjang').DataTable().ajax.reload(null, false);
                }

                if(jsonResponse.type_pelayanan == 'eresep')
                {
                  $('#table-pesan-resep').DataTable().ajax.reload(null, false);
                  $('#kode_pesan_resep').val('');
                  $('#keterangan_pesan_resep').val('');
                  $('#notif_status').html('');
        
                }

                if(jsonResponse.type_pelayanan == 'pasien_selesai')
                {
                  $('#tabs_form_pelayanan').html('<div class="alert alert-success"><strong>Terima Kasih..!</strong> Pasien sudah dilayani. </div>');
                  get_riwayat_medis(jsonResponse.no_mr);
                  pauseStopWatch();
                  resetStopWatch();
                }
                
                if(jsonResponse.type_pelayanan == 'catatan_pengkajian'){
                  oTableCppt.ajax.reload();
                }

                if(jsonResponse.type_pelayanan == 'update_soap'){
                  $('#tab_menu_erm_dokter li.active').removeClass('active');
                  $('#li_cppt').addClass('active');
                  $('#tabs_cppt').click();
                }

                $.achtung({message: jsonResponse.message, timeout:5});

              }else{
                $.achtung({message: jsonResponse.message, timeout:5, className:'achtungFail'});

              }    
              // achtungHideLoader();        
              
              $(this).find("button[type='submit']").prop('disabled',false);

            }   
        });
        return false;
    });
 
    
    /*on keypress or press enter = search pasien*/
    
    $( "#form_cari_pasien" )    

      .keypress(function(event) {        

        var keycode =(event.keyCode?event.keyCode:event.which);         

        if(keycode ==13){          

          event.preventDefault();          

          if($(this).valid()){            

            $('#btn_search_pasien').focus();            

          }          

          return false;                 

        }        

    });
    
    $('#btn_update_session_poli').click(function (e) {  
      if(confirm('Are you sure?')){
        $.ajax({
            url: "pelayanan/Pl_pelayanan/destroy_session_kode_bagian",
            data: { kode: $('#sess_kode_bagian').val()},            
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
              var data=xhr.responseText;  
              var jsonResponse = JSON.parse(data);  
              if(jsonResponse.status === 200){  
                $.achtung({message: jsonResponse.message, timeout:5}); 
                getMenu('pelayanan/Pl_pelayanan');
              }else{          
                $.achtung({message: jsonResponse.message, timeout:5});  
              } 
              achtungHideLoader();
            }
        });
      }else{
        return false;
      }
    });


    $('#btn_search_pasien').click(function (e) {      

      e.preventDefault();  

      if( $("#form_cari_pasien").val() == "" ){

        alert('Masukan keyword minimal 3 Karakter !');

        return $("#form_cari_pasien").focus();

      }else{

        achtungShowLoader();

        find_pasien_by_keyword( $("#form_cari_pasien").val() );

      }    

    }); 
    
    $('#tabs_diagnosa_dr').click(function (e) {   
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveDiagnosaDr');
    });

    $('#tabs_tindakan').click(function (e) {    
      e.preventDefault();  
      $('#form_kelas_tarif').show();
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/process');
      // backToDefaultForm();
    });

    // $('#tabs_pm_lab').click(function (e) {   
    //   e.preventDefault();  
    //   $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveOrderLab');
    // });

    // $('#tabs_pm_rad').click(function (e) {   
    //   e.preventDefault();  
    //   $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveOrderRad');
    // });

    // $('#tabs_pm_fisio').click(function (e) {   
    //   e.preventDefault();  
    //   $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveOrderFisio');
    // });

    $('#tabs_penunjang_medis').click(function (e) {     
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'registration/Reg_pm/process');
    });

    $('#tabs_resep').click(function (e) {   
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'farmasi/Farmasi_pesan_resep/process');
    });

    $('#tabs_cppt').click(function (e) {   
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_cppt');
    });

    $('#tabs_catatan').click(function (e) {   
      e.preventDefault();  
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveCatatanPengkajian');
    });

    
    $('select[name="status_antrian_rj"]').change(function () {      

      if ($(this).val() == 1) {        
          $('#div_antrian_existing').show('fast');
          $('#div_antrian_done').hide('fast');
          $('#div_antrian_cancel').hide('fast');
      }

      if ($(this).val() == 2) {        
          $('#div_antrian_existing').hide('fast');
          $('#div_antrian_done').show('fast');
          $('#div_antrian_cancel').hide('fast');
      }

      if ($(this).val() == 3) {        
          $('#div_antrian_existing').hide('fast');
          $('#div_antrian_done').hide('fast');
          $('#div_antrian_cancel').show('fast');
      }

    }); 

    $('#tgl_kunjungan, #status_pelayanan').change(function(){ 
      getDataAntrianPasien();
    })

})


function click_selected_patient(id_pl_tc_poli, no_kunjungan, no_mr){
  preventDefault();  
  getMenu('pelayanan/Pl_pelayanan/form/'+id_pl_tc_poli+'/'+no_kunjungan+'?no_mr='+no_mr+'&form=<?php echo $form_type?>');
}
/*format date to m/d/Y*/
function formatDate(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  return date.getMonth()+1 + "/" + date.getDate() + "/" + date.getFullYear();
}

/*function find pasien*/
function find_pasien_by_keyword(keyword){  

    $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien_by_mr') ?>?keyword=" + keyword, '', function (data) {      
          achtungHideLoader();          

          /*if cannot find data show alert*/
          if( data.count == 0){

            $('#div_load_after_selected_pasien').hide('fast');

            $('#div_riwayat_pasien').hide('fast');
            
            // $('#div_penangguhan_pasien').hide('fast');

            /*reset all field data*/
            $('#no_mr').text('-');$('#noMrHidden').val('');$('#no_ktp').text('-');$('#nama_pasien').text('-');$('#jk').text('-');$('#umur').text('-');$('#alamat').text('-');$('#noKartuBpjs').val('-');$('#kode_perusahaan').text('-');$('#total_kunjungan').text('-');

            alert('Data tidak ditemukan'); return $("#form_cari_pasien").focus();

          }

          // }      
          if( data.count == 1 )     {

            var obj = data.result[0];

            var pending_data_pasien = data.pending; 
            var umur_pasien = hitung_usia(obj.tgl_lhr);
            console.log(pending_data_pasien);
            console.log(hitung_usia(obj.tgl_lhr));

            $('#no_mr').text(obj.no_mr);

            $('#noMrHidden').val(obj.no_mr);

            $('#no_ktp').text(obj.no_ktp);

            $('#nama_pasien').text(obj.nama_pasien+' ('+obj.jen_kelamin+')');

            $('#nama_pasien_hidden').val(obj.nama_pasien);

            $('#jk').text(obj.jen_kelamin);

            $('#umur').text(umur_pasien+' Thn');

            $('#tgl_lhr').text(getFormattedDate(obj.tgl_lhr));
            
            $('#umur_saat_pelayanan_hidden').val(umur_pasien);

            $('#alamat').text(obj.almt_ttp_pasien);

            $('#hp').text(obj.no_hp);

            $('#no_telp').text(obj.tlp_almt_ttp);

            $('#catatan_pasien').text(obj.keterangan);

            $('#noKartuBpjs').val(obj.no_kartu_bpjs);

            if( obj.url_foto_pasien ){

              $('#avatar').attr('src', '<?php echo base_url()?>uploaded/images/photo/'+obj.url_foto_pasien+'');

            }else{

              if( obj.jen_kelamin == 'L' ){
            
                $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');
                var txt_jk = 'bapak';
              
              }else{
                
                $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');
                var txt_jk = 'ibu';

              }

            }

            
            
            if( obj.kode_perusahaan==120){

              $('#form_sep').show('fast'); 

              //showModalFormSep(obj.no_kartu_bpjs,obj.no_mr);

            }else{

              $('#form_sep').hide('fast'); 

            }

            penjamin = (obj.nama_perusahaan==null)?obj.nama_kelompok:obj.nama_perusahaan;
            kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;

            $('#kode_perusahaan').text(penjamin);
            
            $('#kode_perusahaan_hidden').val(obj.kode_perusahaan);
            /*penjamin pasien*/
            $('#kode_kelompok_hidden').val(obj.kode_kelompok);

            $('#InputKeyPenjamin').val(obj.nama_perusahaan);
            $('#InputKeyNasabah').val(obj.nama_kelompok);

            $('#total_kunjungan').text(obj.total_kunjungan);

            $("#myTab li").removeClass("active");
            
            // // txt_call_patient
            // txt_call = ''; 
            // txt_call += txt_jk +' '+ obj.nama_pasien; 
            // txt_call += ' silahkan masuk ke '; 
            // txt_call += '<?php echo ucwords($nama_bagian); ?>'; 
            // $('#txt_call_patient').val(txt_call);

          }      

    }); 

}

function get_riwayat_medis(no_mr){
  $('#cppt_data').html('Loading...'); 
  $('#cppt_data_on_tabs').html('Loading...'); 
  
  $.getJSON("templates/References/get_riwayat_medis/" +no_mr, '', function (data) { 
      $('#cppt_data').html(data.html); 
      $('#cppt_data_on_tabs').html(data.html); 
  });
}

function get_riwayat_pm(no_mr){
  $('#cppt_data').html('Loading...'); 
  $('#cppt_data_on_tabs').html('Loading...'); 
  
  $.getJSON("templates/References/get_riwayat_pm/" +no_mr, '', function (data) { 
      $('#cppt_data').html(data.html); 
      $('#cppt_data_on_tabs').html(data.html); 
  });
}

function get_riwayat_perjanjian(no_mr){
  $('#cppt_data').html('Loading...'); 
  $('#cppt_data_on_tabs').html('Loading...'); 
  
  $.getJSON("templates/References/get_riwayat_perjanjian/" +no_mr, '', function (data) { 
      $('#cppt_data').html(data.html); 
      $('#cppt_data_on_tabs').html(data.html); 
  });
}

function getDataAntrianPasien(){

  $.getJSON("pelayanan/Pl_pelayanan/get_data_antrian_pasien?bag=" + $('#kode_bagian_val').val()+'&tgl='+$('#tgl_kunjungan').val()+'&status='+$('#status_pelayanan').val()+'', '', function (data) {
    $('#no_mr_selected option').remove();
    $('#list_antrian_existing').empty();
    $('<option value="">-Pilih Pasien-</option>').appendTo($('#no_mr_selected'));
    var arr = [];
    var arr_cancel = [];

    if (!data || data.length === 0) {
      $('#list_antrian_existing').html('<tr><td style="padding:20px;text-align:center;color:#94a3b8;"><i class="fa fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>Belum ada antrian hari ini</td></tr>');
    }

    $.each(data, function (i, o) {
      var selected = (o.no_mr == $('#noMrHidden').val()) ? 'selected' : '';
      $('<option value="' + o.no_mr + '" ' + selected + '>' + o.no_mr + ' - ' + o.nama_pasien + '</option>').appendTo($('#no_mr_selected'));

      var penjaminBadge;
      if (o.kode_perusahaan == 0) {
        penjaminBadge = '<span class="aq-badge aq-badge-umum">Umum</span>';
      } else if (o.kode_perusahaan == 120) {
        penjaminBadge = '<span class="aq-badge aq-badge-lain">' + o.nama_perusahaan + '</span>';
      } else {
        penjaminBadge = '<span class="aq-badge aq-badge-bpjs">' + o.nama_perusahaan + '</span>';
      }

      var statusClass, statusBadge;
      if (o.status_batal == 1) {
        statusClass = 'aq-batal';
        statusBadge = '<span class="aq-badge aq-badge-batal">Batal</span>';
      } else if (o.tgl_keluar_poli != null) {
        statusClass = 'aq-selesai';
        statusBadge = '<span class="aq-badge aq-badge-selesai">Selesai</span>';
      } else {
        statusClass = 'aq-belum';
        statusBadge = '<span class="aq-badge aq-badge-belum">Menunggu</span>';
      }

      var clickAttr = "click_selected_patient(" + o.id_pl_tc_poli + "," + o.no_kunjungan + ",'" + o.no_mr + "')";

      var html = '<tr class="aq-item ' + statusClass + '" onclick="' + clickAttr + '">' +
        '<td><div class="aq-card">' +
        '<div class="aq-card-top">' +
        '<div class="aq-num-badge">' +
        '<span class="aq-num-lbl">No.&nbsp;</span>' +
        '<span class="aq-num-val">' + o.no_antrian + '</span>' +
        '</div>' +
        statusBadge +
        '</div>' +
        '<div class="aq-name">' + o.nama_pasien + '</div>' +
        '<div class="aq-meta">' +
        '<i class="fa fa-id-card-o"></i>&nbsp;' + o.no_mr +
        '&nbsp;&bull;&nbsp;' + o.jen_kelamin +
        '&nbsp;&bull;&nbsp;' + o.umur + ' thn' +
        '</div>' +
        '<div class="aq-card-footer">' + penjaminBadge + '</div>' +
        '</div></td></tr>';

      var $row = $(html);
      if (o.no_mr === $('#noMrHidden').val()) { $row.find('.aq-card').css('background','#eff6ff'); $row.addClass('aq-selected'); }
      $row.appendTo($('#list_antrian_existing'));

      if (o.tgl_keluar_poli != null) arr.push(o);
      if (o.status_batal == 1) arr_cancel.push(o);
    });

    $('#total_antrian').text(data.length);
    $('#sudah_dilayani').text(arr.length);
    $('#pasien_batal').text(arr_cancel.length);
  });

}

function cancel_visit_dr(no_registrasi, no_kunjungan){

  preventDefault();  
  achtungShowLoader();
  if(confirm('Are you sure?')){
    $.ajax({
        url: "pelayanan/Pl_pelayanan/cancel_visit",
        data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan, kode_bag: $('#kode_bagian_val').val() },            
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
          var data=xhr.responseText;  
          var jsonResponse = JSON.parse(data);  
          if(jsonResponse.status === 200){  
            $.achtung({message: jsonResponse.message, timeout:5}); 
            getMenu('pelayanan/Pl_pelayanan/form/'+$('#id_pl_tc_poli').val()+'/'+no_kunjungan+'?no_mr='+$('#no_mr_val').val()+'&form=form_rj');
          }else{          
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
          } 
          achtungHideLoader();
        }
    });
  }else{
    return false;
  }

}

function selesaikanKunjungan() {
  preventDefault();

  var no_kunjungan  = $('#no_kunjungan').val();
  var no_registrasi = $('#no_registrasi').val();

  if (!no_kunjungan || !no_registrasi) {
    $.achtung({message: 'Data kunjungan tidak ditemukan!', timeout: 5, className: 'achtungFail'});
    return false;
  }

  if (!confirm('Apakah Anda yakin ingin menyelesaikan kunjungan ini?')) {
    return false;
  }

  achtungShowLoader();

  $.ajax({
    url: 'pelayanan/Pl_pelayanan/processSelesaikanKunjungan',
    type: 'POST',
    dataType: 'json',
    data: {
      no_kunjungan  : no_kunjungan,
      no_registrasi : no_registrasi,
      cara_keluar   : $('#cara_keluar').val()  || '',
      pasca_pulang  : $('#pasca_pulang').val() || ''
    },
    complete: function(xhr) {
      achtungHideLoader();
      var jsonResponse = JSON.parse(xhr.responseText);
      if (jsonResponse.status === 200) {
        $.achtung({message: jsonResponse.message, timeout: 5});
        setTimeout(function() {
          getMenu('pelayanan/Pl_pelayanan/form/' + $('#id_pl_tc_poli').val() + '/' + no_kunjungan + '?no_mr=' + $('#no_mr_val').val() + '&form=form_rj');
        }, 1200);
      } else {
        $.achtung({message: jsonResponse.message, timeout: 8, className: 'achtungFail'});
      }
    }
  });
}

function searchTable() {
    var input, filter, found, table, tr, td, i, j;
    input = document.getElementById("seacrh_ul_li");
    filter = input.value.toUpperCase();
    table = document.getElementById("list_antrian_existing");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
                found = true;
            }
        }
        if (found) {
            tr[i].style.display = "";
            found = false;
        } else {
            tr[i].style.display = "none";
        }
    }
}

function show_icare() {
    var noka = $('#noKartuBpjs').val();
    var kode_dokter = $('#kode_dokter_bpjs').val();
    if($('#kode_perusahaan_val').val() == 120){
      $.getJSON("ws_bpjs/Ws_index/getIcare/"+noka+"/"+kode_dokter+"", '', function (ress) { 
        var obj = ress.response.metaData;
        console.log(obj);
        if(obj.code != 200){
          $('#tabs_form_pelayanan').html('<div class="alert alert-danger"><strong>'+obj.message+' !</strong><br>Kemungkinan data pada VClaim dan SIMRS tidak sesuai</div>');
        }else{
          $('#tabs_form_pelayanan').html('<iframe src='+ress.url+' style="width: 100%; min-height: 900px" frameborder="no" scrolling="yes"></iframe>');
        }
      });
    }else{
      $('#tabs_form_pelayanan').html('<div class="alert alert-danger"><strong>Pasien Non BPJS !</strong><br>Tidak dapat membuka I-Care JKN</div>');
    }
    
}

</script>

<style type="text/css">
  /* ── Scoped to #fd-wrap ──────────────────────────────────────── */
  #fd-wrap {
    font-family: 'Segoe UI', system-ui, Arial, sans-serif;
    font-size: 13px;
  }

  /* ── Stats Bar ───────────────────────────────────────────────── */
  .fd-stats-bar {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    background: #ffffff;
    border-top: 4px solid #0ea5e9;
    padding: 14px 20px;
    border-radius: 12px 12px 0 0;
    border-left: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
  }
  .fd-stats-bar::before {
    content: '';
    position: absolute;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(14,165,233,0.07) 0%, transparent 70%);
    right: -40px; top: -80px;
    pointer-events: none;
  }
  .fd-doctor-info {
    display: flex; align-items: center; gap: 12px;
    flex: 1; min-width: 220px; position: relative;
  }
  .fd-dr-avatar {
    width: 48px; height: 48px; border-radius: 11px;
    object-fit: cover;
    border: 2px solid #e0f2fe;
    background: #f0f9ff;
    flex-shrink: 0;
  }
  .fd-dr-name {
    font-size: 14.5px; font-weight: 700; color: #0f172a;
    display: block; line-height: 1.3;
  }
  .fd-dr-dept {
    font-size: 11.5px; color: #64748b;
    display: block; margin-top: 2px;
  }
  .fd-stats-group {
    display: flex; align-items: center; gap: 8px;
    flex-wrap: wrap; position: relative;
  }
  .fd-stat-pill {
    display: flex; flex-direction: column; align-items: center;
    padding: 6px 16px; border-radius: 9px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    min-width: 72px; cursor: default;
    transition: background .18s, box-shadow .18s;
  }
  .fd-stat-pill:hover { background: #f1f5f9; box-shadow: 0 2px 8px rgba(0,0,0,.07); }
  .fd-stat-pill.fd-blue  { background: #eff8ff;  border-color: #bae6fd; }
  .fd-stat-pill.fd-green { background: #f0fdf4;  border-color: #bbf7d0; }
  .fd-stat-pill.fd-orange{ background: #fff7ed;  border-color: #fed7aa; }
  .fd-stat-num {
    font-size: 22px; font-weight: 800; line-height: 1;
    background: linear-gradient(135deg, #0369a1, #0ea5e9);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .fd-stat-pill.fd-green .fd-stat-num {
    background: linear-gradient(135deg, #15803d, #22c55e);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .fd-stat-pill.fd-orange .fd-stat-num {
    background: linear-gradient(135deg, #c2410c, #f97316);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .fd-stat-lbl { font-size: 10px; color: #94a3b8; margin-top: 3px; text-transform: uppercase; letter-spacing: 0.5px; }
  .fd-btn-tutup-sesi {
    display: flex; align-items: center; gap: 7px;
    padding: 9px 16px; border-radius: 9px;
    background: #fef2f2;
    border: 1.5px solid #fecaca;
    color: #dc2626; font-size: 12.5px; font-weight: 600;
    cursor: pointer; font-family: inherit; white-space: nowrap;
    transition: background .18s, border-color .18s, box-shadow .18s;
    position: relative;
  }
  .fd-btn-tutup-sesi:hover {
    background: #fee2e2;
    border-color: #f87171;
    box-shadow: 0 2px 8px rgba(239,68,68,.2);
  }

  /* ── Body wrapper ────────────────────────────────────────────── */
  .fd-body {
    background: #f1f5f9;
    border: 1px solid #dde3ec;
    border-top: none;
    border-radius: 0 0 12px 12px;
    padding: 12px;
  }

  /* ── Generic panel ───────────────────────────────────────────── */
  .fd-panel {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 6px rgba(0,0,0,0.05);
    overflow: hidden;
  }
  .fd-panel-hdr {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    border-left: 3px solid #0ea5e9;
    padding: 9px 14px;
    display: flex; align-items: center; gap: 8px;
    color: #0f172a; font-size: 12px; font-weight: 700;
  }
  .fd-panel-hdr i { color: #0ea5e9; }
  .fd-panel-body { padding: 10px 12px; }
  .fd-flabel {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.8px;
    color: #64748b; margin: 8px 0 4px;
  }
  .fd-antrian-wrap {
    margin-top: 8px; border-radius: 6px;
    border: 1px solid #eef2f7;
    flex: 1; overflow-y: auto; min-height: 0;
  }
  .fd-antrian-wrap::-webkit-scrollbar { width: 4px; }
  .fd-antrian-wrap::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
  #fd-wrap #list_antrian_existing { width: 100%; border-collapse: collapse; }
  #fd-wrap #list_antrian_existing td { padding: 0 !important; border: none !important; }

  /* ── Antrian Item Cards ───────────────── */
  .aq-item + .aq-item .aq-card { border-top: 1px solid #f1f5f9; }
  .aq-item:hover .aq-card { background: #f8faff !important; }
  .aq-item.aq-selected .aq-card { background: #eff6ff !important; border-left-color: #3b82f6 !important; }
  .aq-card {
    display: block; padding: 8px 12px 8px 12px;
    cursor: pointer; transition: background .12s;
    border-left: 3px solid transparent; background: #fff;
  }
  .aq-item.aq-belum   .aq-card { border-left-color: #f59e0b; }
  .aq-item.aq-selesai .aq-card { border-left-color: #22c55e; background: #fdfffe; }
  .aq-item.aq-batal   .aq-card { border-left-color: #ef4444; background: #fff8f8; opacity: .85; }
  /* top row: no antrian + status badge */
  .aq-card-top {
    display: flex; align-items: center;
    justify-content: space-between; margin-bottom: 5px;
  }
  .aq-num-badge { display: flex; align-items: baseline; gap: 3px; }
  .aq-num-lbl {
    font-size: 9px; font-weight: 700; color: #94a3b8;
    text-transform: uppercase; letter-spacing: .6px;
  }
  .aq-num-val {
    font-size: 20px; font-weight: 900; line-height: 1;
  }
  .aq-item.aq-belum   .aq-num-val { color: #b45309; }
  .aq-item.aq-selesai .aq-num-val { color: #15803d; }
  .aq-item.aq-batal   .aq-num-val { color: #b91c1c; }
  /* patient info */
  .aq-name {
    font-size: 12px; font-weight: 700; color: #0f172a;
    word-break: break-word; line-height: 1.35; margin-bottom: 2px;
  }
  .aq-meta {
    font-size: 10.5px; color: #64748b; margin-bottom: 5px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .aq-card-footer { display: flex; flex-wrap: wrap; gap: 3px; }
  .aq-badge {
    font-size: 10px; font-weight: 700; padding: 2px 7px;
    border-radius: 4px; white-space: nowrap;
  }
  .aq-badge-umum    { background: #dbeafe; color: #1d4ed8; }
  .aq-badge-bpjs    { background: #dcfce7; color: #15803d; }
  .aq-badge-lain    { background: #fef3c7; color: #92400e; }
  .aq-badge-belum   { background: #fef9c3; color: #854d0e; }
  .aq-badge-selesai { background: #dcfce7; color: #15803d; }
  .aq-badge-batal   { background: #fee2e2; color: #991b1b; }

  /* ── Patient card ────────────────────────────────────────────── */
  .fd-patient-card {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 6px rgba(0,0,0,0.05);
    margin-bottom: 10px;
    overflow: hidden;
  }
  .fd-patient-hdr {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-bottom: 1px solid #bae6fd;
    padding: 12px 16px;
    display: flex; align-items: center; gap: 14px;
  }
  .fd-antrian-badge {
    display: flex; flex-direction: column; align-items: center;
    background: #fff;
    border-radius: 9px; padding: 5px 13px;
    min-width: 58px; flex-shrink: 0;
    border: 1.5px solid #bae6fd;
    box-shadow: 0 1px 4px rgba(14,165,233,.12);
  }
  .fd-antrian-badge .fd-lbl { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; }
  .fd-antrian-badge .fd-num {
    font-size: 28px; font-weight: 900; line-height: 1.1;
    background: linear-gradient(135deg, #0369a1, #0ea5e9);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .fd-patient-info { flex: 1; min-width: 0; }
  .fd-patient-name { font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
  .fd-patient-name a { color: #0369a1; }
  .fd-patient-name a:hover { color: #0ea5e9; text-decoration: none; }
  .fd-patient-sub { font-size: 12px; color: #475569; }
  .fd-patient-meta {
    display: flex; flex-wrap: wrap; gap: 6px 20px;
    padding: 9px 16px; background: #f8fafc;
    border-top: 1px solid #eef2f7; font-size: 12px; color: #4a5568;
  }
  .fd-meta-item { display: flex; align-items: center; gap: 5px; }
  .fd-meta-item i { color: #0ea5e9; width: 13px; text-align: center; font-size: 11px; }

  /* ── Action buttons ──────────────────────────────────────────── */
  .fd-actions { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 10px; }
  .fd-btn-green {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: 8px;
    background: linear-gradient(135deg, #43a047, #2e7d32);
    color: #fff; font-size: 12.5px; font-weight: 600;
    border: none; cursor: pointer; text-decoration: none;
    transition: opacity .18s, transform .18s;
  }
  .fd-btn-red {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: 8px;
    background: linear-gradient(135deg, #e53935, #b71c1c);
    color: #fff; font-size: 12.5px; font-weight: 600;
    border: none; cursor: pointer; text-decoration: none;
    transition: opacity .18s, transform .18s;
  }
  .fd-btn-green:hover, .fd-btn-red:hover {
    opacity: .88; transform: translateY(-1px);
    color: #fff; text-decoration: none;
  }

  /* ── Nav tabs ────────────────────────────────────────────────── */
  #fd-wrap #tab_menu_erm_dokter {
    border-bottom: 2px solid #dde3ec;
    background: #f8fafc;
    border-radius: 10px 10px 0 0;
    padding: 0 8px;
    display: flex; flex-wrap: wrap; gap: 2px;
    margin: 0; list-style: none;
  }
  #fd-wrap #tab_menu_erm_dokter > li > a {
    padding: 9px 12px;
    font-size: 12px; font-weight: 600; color: #64748b;
    border: none !important;
    border-radius: 8px 8px 0 0 !important;
    background: transparent !important;
    display: block;
    transition: color .15s, background .15s;
  }
  #fd-wrap #tab_menu_erm_dokter > li > a:hover {
    color: #0369a1;
    background: rgba(14,165,233,0.08) !important;
    text-decoration: none;
  }
  #fd-wrap #tab_menu_erm_dokter > li.active > a,
  #fd-wrap #tab_menu_erm_dokter > li.active > a:focus {
    color: #0369a1 !important;
    background: #fff !important;
    border-bottom: 3px solid #0ea5e9 !important;
  }
  #fd-wrap .tab-content {
    border: 1px solid #dde3ec; border-top: none;
    border-radius: 0 0 10px 10px;
    background: #fff; padding: 14px; min-height: 180px;
  }

  /* ── Riwayat panel ───────────────────────────────────────────── */
  #fd-wrap #myTab {
    border: none; padding: 0; margin: 0;
    display: flex; gap: 3px; background: transparent;
  }
  #fd-wrap #myTab > li > a {
    padding: 4px 9px; font-size: 12px; font-weight: 600;
    border-radius: 6px !important;
    border: 1px solid transparent !important;
    color: #475569 !important;
    background: rgba(14,165,233,0.07) !important;
    transition: background .15s, color .15s;
  }
  #fd-wrap #myTab > li > a:hover { background: rgba(14,165,233,0.15) !important; color: #0369a1 !important; }
  #fd-wrap #myTab > li.active > a {
    background: #0ea5e9 !important;
    color: #fff !important;
    border-color: #0284c7 !important;
    box-shadow: 0 1px 4px rgba(14,165,233,.3);
  }
  .fd-riwayat-scroll { flex: 1; overflow-y: auto; padding: 10px; min-height: 0; }
  .fd-riwayat-scroll::-webkit-scrollbar { width: 4px; }
  .fd-riwayat-scroll::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }

  /* ── Misc ────────────────────────────────────────────────────── */
  #fd-wrap .pagination { margin: 0 !important; }
  #fd-wrap .well { padding: 5px !important; }
  #fd-wrap select option { padding: 3px 4px 5px; }
  .blink_me { animation: blinker 1s linear infinite; }
  @keyframes blinker { 50% { opacity: 0; } }
  .ace-settings-box { max-height: 550px !important; overflow-y: scroll; background: white; }
  .ace-settings-box.open { width: 350px !important; }
  #ace-settings-container-rj::-webkit-scrollbar { width: 10px; }
  #ace-settings-container-rj::-webkit-scrollbar-track { box-shadow: inset 0 0 5px grey; border-radius: 10px; }
  #ace-settings-container-rj::-webkit-scrollbar-thumb { background: #8cc229; border-radius: 10px; }
  #ace-settings-container-rj::-webkit-scrollbar-thumb:hover { background: #b30000; }
  .antrian-no-kanan {
    float: right; font-size: 34px; font-weight: bold;
    color: #1976d2; opacity: 0.16; line-height: 1;
    pointer-events: none; text-shadow: 1px 2px 8px #fff;
  }

  /* ── Sticky sidebar columns ─────────────────────────────────── */
  .fd-sticky-col {
    position: sticky;
    top: 0;
    /* height: 100vh; */
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }
  .fd-sticky-col .fd-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    min-height: 0;
  }
  .fd-sticky-col .fd-panel-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    min-height: 0;
  }

  /* ── Responsive ──────────────────────────────────────────────── */
  @media (max-width: 768px) {
    .fd-stats-bar { border-radius: 8px; flex-direction: column; align-items: flex-start; }
    .fd-stats-group { flex-wrap: wrap; }
    .fd-body { padding: 8px; }
  }
</style>

<div id="fd-wrap">

  <!-- ── Stats Bar ─────────────────────────────────────────── -->
  <div class="fd-stats-bar">
    <div class="fd-doctor-info">
      <img src="<?php echo base_url().'uploaded/images/photo_karyawan/'.$value->kode_dokter.'.png'; ?>"
           class="fd-dr-avatar" alt="Foto Dokter"
           onerror="this.src='<?php echo base_url()?>assets/avatars/boy.jpg'">
      <div>
        <span class="fd-dr-name"><?php echo isset($nama_dokter) ? $nama_dokter : ''; ?></span>
        <span class="fd-dr-dept"><i class="fa fa-map-marker"></i> <?php echo ucwords($nama_bagian); ?></span>
      </div>
    </div>
    <div class="fd-stats-group">
      <div class="fd-stat-pill fd-blue">
        <span class="fd-stat-num" id="total_antrian">—</span>
        <span class="fd-stat-lbl">Total Pasien</span>
      </div>
      <div class="fd-stat-pill fd-green">
        <span class="fd-stat-num" id="sudah_dilayani">—</span>
        <span class="fd-stat-lbl">Dilayani</span>
      </div>
      <div class="fd-stat-pill fd-orange">
        <span class="fd-stat-num" id="pasien_batal">—</span>
        <span class="fd-stat-lbl">Batal</span>
      </div>
      <button type="button" class="fd-btn-tutup-sesi" id="btn_update_session_poli">
        <i class="fa fa-sign-out"></i> Tutup Sesi
      </button>
    </div>
  </div>

<form class="form-horizontal" method="post" id="form_pelayanan" action="#" enctype="multipart/form-data" autocomplete="off">

  <!-- hidden form -->
  <input type="hidden" name="noMrHidden" id="noMrHidden" value="<?php echo $no_mr?>">
  <input type="hidden" name="id_pl_tc_poli" id="id_pl_tc_poli" value="<?php echo ($id)?$id:''?>">
  <input type="hidden" name="nama_pasien_hidden" id="nama_pasien_hidden">
  <input type="hidden" name="dokter_pemeriksa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dokter_pemeriksa">
  <input type="hidden" name="no_registrasi" id="no_registrasi" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" readonly>
  <input type="hidden" name="no_kunjungan" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" id="no_kunjungan" readonly>
  <input type="hidden" name="noKartu" id="form_cari_pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr;}else{echo '';}?>" readonly>
  <input type="hidden" name="kode_perjanjian" value="<?php echo isset($value->kode_perjanjian)?$value->kode_perjanjian:''?>" id="kode_perjanjian" readonly>
  <input type="hidden" name="kodebookingantrol" value="<?php echo isset($value->kodebookingantrol)?$value->kodebookingantrol:''?>" id="kodebookingantrol" readonly>
  <input type="hidden" name="taskId" value="4" id="taskId" readonly>
  <input type="hidden" name="noKartuBpjs" id="noKartuBpjs" value="<?php echo $value->no_kartu_bpjs?>" readonly>
  <input type="hidden" name="jeniskunjunganbpjs" id="jeniskunjunganbpjs" value="<?php echo $value->jeniskunjunganbpjs?>" readonly>

  <div class="fd-body">
  <div class="row" style="margin:0">

    <!-- ── Left: Antrian Sidebar ─────────────────────── -->
    <div class="col-md-2 fd-sticky-col" style="padding:10px 6px 10px 0">
      <div class="fd-panel" id="antrian_tabs">
        <div class="fd-panel-hdr">
          <i class="fa fa-list-ol"></i> Worklist Pasien
        </div>
        <div class="fd-panel-body">
          <div class="fd-flabel">Tanggal Kunjungan</div>
          <div class="input-group">
            <input name="tgl_kunjungan" id="tgl_kunjungan"
                   placeholder="<?php echo date('Y-m-d')?>"
                   class="form-control date-picker"
                   data-date-format="yyyy-mm-dd" type="text"
                   value="<?php echo isset($this->cache->get('cache')['tgl'])?$this->cache->get('cache')['tgl']:date('Y-m-d')?>">
            <span class="input-group-addon"><i class="ace-icon fa fa-calendar"></i></span>
          </div>
          <div class="fd-flabel">Status Pelayanan</div>
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'status_pelayanan', 'is_active' => 'Y')), 'Tab', 'status_pelayanan', 'status_pelayanan', 'form-control', '', '');?>
          <div class="fd-flabel">Cari Pasien</div>
          <input type="text" id="seacrh_ul_li" value="" placeholder="Keyword..." class="form-control" onkeyup="searchTable()">
          <div class="fd-antrian-wrap">
            <table id="list_antrian_existing"><tbody></tbody></table>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Center: Patient + Tabs ──────────────────── -->
    <div class="col-md-7 no-padding" style="padding:10px 6px">

      <!-- Patient Info Card -->
      <div class="fd-patient-card">
        <div class="fd-patient-hdr">
          <div class="fd-antrian-badge">
            <span class="fd-lbl">Antrian</span>
            <span class="fd-num"><?php echo isset($value->no_antrian)?$value->no_antrian:0?></span>
          </div>
          <div class="fd-patient-info">
            <div class="fd-patient-name">
              <a href="#" onclick="show_modal('registration/reg_pasien/form_modal/<?php echo isset($value->no_mr)?$value->no_mr:'';?>', 'DATA PASIEN')" title="Lihat Data Pasien">
                <span id="no_mr"><?php echo isset($value->no_mr)?$value->no_mr:'';?></span>
              </a>
              &nbsp;&mdash;&nbsp;
              <span id="nama_pasien"><?php echo isset($value->nama_pasien)?$value->nama_pasien:'';?></span>
              <small>(<?php echo isset($value->jen_kelamin)?$value->jen_kelamin:'';?>)</small>
            </div>
            <div class="fd-patient-sub">
              <i class="fa fa-birthday-cake"></i>
              <span id="tgl_lhr"><?php echo isset($value->tgl_lhr)?$this->tanggal->formatDate($value->tgl_lhr):''?></span>
              &bull; <span id="umur">(<?php echo isset($value->umur_lengkap)?$value->umur_lengkap:'';?>)</span>
            </div>
          </div>
          <div style="text-align:right;flex-shrink:0">
            <?php if($value->jeniskunjunganbpjs == 2): ?>
              <span class="label label-warning" style="font-size:10px;display:block;margin-bottom:3px">Rujukan Internal</span>
            <?php endif; ?>
            <?php if($value->flag_ri==1): ?>
              <span class="label label-danger" style="font-size:10px;display:block;margin-bottom:3px">Rawat Inap</span>
            <?php endif; ?>
            <div style="display:flex;flex-direction:row;align-items:stretch;gap:6px;">
              <!-- Tgl/Jam Masuk -->
              <div style="display:flex;flex-direction:column;align-items:flex-start;gap:1px;background:linear-gradient(135deg,#1e40af,#2563eb);border-radius:7px;padding:5px 10px;box-shadow:0 1px 6px rgba(37,99,235,.2);">
                <span style="font-size:8.5px;font-weight:600;letter-spacing:.6px;text-transform:uppercase;color:rgba(255,255,255,.7);white-space:nowrap;">
                  <i class="fa fa-sign-in" style="margin-right:2px;"></i>Tgl/Jam Masuk
                </span>
                <span style="font-size:11.5px;font-weight:700;color:#fff;white-space:nowrap;">
                  <?php echo isset($value->tgl_jam_poli) ? $this->tanggal->formatDateTime($value->tgl_jam_poli) : '-'?>
                </span>
              </div>
              <!-- Tgl/Jam Keluar -->
              <div style="display:flex;flex-direction:column;align-items:flex-start;gap:1px;background:<?php echo !empty($value->tgl_keluar_poli) ? 'linear-gradient(135deg,#065f46,#059669)' : 'linear-gradient(135deg,#475569,#64748b)'?>;border-radius:7px;padding:5px 10px;box-shadow:0 1px 6px rgba(0,0,0,.12);">
                <span style="font-size:8.5px;font-weight:600;letter-spacing:.6px;text-transform:uppercase;color:rgba(255,255,255,.7);white-space:nowrap;">
                  <i class="fa fa-sign-out" style="margin-right:2px;"></i>Tgl/Jam Keluar
                </span>
                <span style="font-size:11.5px;font-weight:700;color:#fff;white-space:nowrap;">
                  <?php echo !empty($value->tgl_keluar_poli) ? $this->tanggal->formatDateTime($value->tgl_keluar_poli) : 'Belum Keluar'?>
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="fd-patient-meta">
          <div class="fd-meta-item">
            <i class="fa fa-user-md"></i>
            <span><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></span>
          </div>
          <div class="fd-meta-item">
            <i class="fa fa-building"></i>
            <span id="kode_perusahaan"><?php
              echo isset($value->nama_perusahaan) && $value->nama_perusahaan
                ? $value->nama_perusahaan
                : (isset($value->nama_kelompok) ? ucwords($value->nama_kelompok) : '-');
            ?></span>
          </div>
          <?php if(isset($value->diagnosa_rujukan) && $value->diagnosa_rujukan): ?>
          <div class="fd-meta-item">
            <i class="fa fa-stethoscope"></i>
            <span><?php echo $value->diagnosa_rujukan?></span>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <?php if(isset($value) AND $value->less_then_min_visit==1) :?>
        <div class="alert alert-danger" style="margin-bottom:8px;padding:8px 12px;font-size:12.5px">
          <strong><i class="fa fa-exclamation-triangle"></i> Peringatan!</strong> Pasien kurang dari 30 hari kunjungan Pelayanan BPJS.
        </div>
      <?php endif;?>

      <!-- hidden form -->
      <input type="hidden" name="kode_dokter_bpjs" id="kode_dokter_bpjs" value="<?php echo isset($kode_dokter_bpjs)?$kode_dokter_bpjs:''?>">
      <input type="hidden" name="no_kunjungan" value="<?php echo isset($value)?$value->no_kunjungan:''?>">
      <input type="hidden" name="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>">
      <input type="hidden" name="kode_kelompok" value="<?php echo isset($value)?$value->kode_kelompok:''?>">
      <input type="hidden" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>" id="kode_perusahaan_val">
      <input type="hidden" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>" id="no_mr_val">
      <input type="hidden" name="nama_pasien_layan" value="<?php echo isset($value)?$value->nama_pasien:''?>">
      <input type="hidden" name="kode_bagian_asal" value="<?php echo isset($value)?$value->kode_bagian_asal:''?>">
      <input type="hidden" name="kode_bagian" value="<?php echo isset($value)?$value->kode_bagian:''?>" id="kode_bagian_val">
      <input type="hidden" name="kode_klas" value="<?php echo isset($kode_klas)?$kode_klas:''?>" id="kode_klas_val">
      <input type="hidden" name="kode_dokter_poli" id="kode_dokter_poli" value="<?php echo isset($value->kode_dokter)?$value->kode_dokter:''?>">
      <input type="hidden" name="flag_mcu" id="flag_mcu" value="<?php echo isset($value->flag_mcu)?$value->flag_mcu:0?>">
      <input type="hidden" name="tgl_jam_poli" id="tgl_jam_poli" value="<?php echo isset($value->tgl_jam_poli)?$value->tgl_jam_poli:''?>">

      <!-- Action Buttons -->
      <div class="fd-actions">
        <?php if(empty($value->tgl_keluar_poli)) :?>
          <a href="#" class="fd-btn-green" onclick="selesaikanKunjungan()">
            <i class="fa fa-check-circle"></i> Selesaikan Kunjungan
          </a>
          <a href="#" class="fd-btn-red" onclick="cancel_visit_dr(<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>,<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>)">
            <i class="fa fa-times-circle"></i> Batalkan Kunjungan
          </a>
        <?php else: echo ''; endif;?>
      </div>

      <!-- form default pelayanan pasien -->
      <div id="form_default_pelayanan" style="background-color:rgba(195,220,119,0.56)"></div>

      <!-- Tabs -->
      <div class="tabbable">
        <ul class="nav nav-tabs" id="tab_menu_erm_dokter">
          <li class="active">
            <a data-toggle="tab" id="icare_tabs" href="#" onclick="show_icare()">
              <i class="fa fa-heartbeat"></i> I-Care JKN
            </a>
          </li>
          <li id="li_soap">
            <a data-toggle="tab" id="tabs_diagnosa_dr" href="#"
               data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>"
               data-url="pelayanan/Pl_pelayanan/diagnosa_dr/<?php echo $id?>"
               onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
              <i class="fa fa-stethoscope"></i> S O A P
            </a>
          </li>
          <li>
            <a data-toggle="tab" id="tabs_tindakan" href="#"
               data-id="<?php echo $no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($value)?$value->kode_bagian:''?>"
               data-url="pelayanan/Pl_pelayanan/tindakan_dr/<?php echo $id?>"
               onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
              <i class="fa fa-flask"></i> Pemeriksaan
            </a>
          </li>
          <li id="li_cppt">
            <a data-toggle="tab" id="tabs_cppt" href="#"
               data-id="<?php echo $no_kunjungan?>?type=Rajal&form=cppt"
               data-url="pelayanan/Pl_pelayanan/cppt/<?php echo $id?>"
               onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
              <i class="fa fa-history"></i> Riwayat Medis
            </a>
          </li>
          <li>
            <a data-toggle="tab" href="#"
               data-id="<?php echo $id?>"
               data-url="farmasi/Farmasi_pesan_resep/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>"
               id="tabs_resep"
               onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
              <i class="fa fa-medkit"></i> e-Resep
            </a>
          </li>
          <li>
            <a data-toggle="tab" href="#"
               data-id="<?php echo $id?>"
               data-url="registration/Reg_pm/rujuk_pm/<?php echo $value->no_registrasi?>/<?php echo $value->kode_bagian?>/<?php echo $kode_klas?>/rajal"
               id="tabs_penunjang_medis"
               onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')">
              <i class="fa fa-share-square-o"></i> Order Penunjang
            </a>
          </li>
          <li>
            <a data-toggle="tab" id="tabs_catatan" href="#"
               data-id="<?php echo $no_kunjungan?>?type=Rajal&no_mr=<?php echo $no_mr?>"
               data-url="pelayanan/Pl_pelayanan/catatan_lainnya/<?php echo $id?>"
               onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
              <i class="fa fa-file-text-o"></i> Form Rekam Medis
            </a>
          </li>
          <li>
            <a data-toggle="tab" href="#"
               data-id="" data-url="pelayanan/Pl_pelayanan/info_harga_obat"
               onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')">
              <i class="fa fa-tag"></i> Info Harga Obat
            </a>
          </li>
        </ul>
        <div class="tab-content">
          <div id="tabs_form_pelayanan" class="tab-pane fade in active">
            <div class="alert alert-block alert-success">
              <p>
                <strong><i class="ace-icon fa fa-check"></i> Selamat Datang!</strong>
                Untuk melihat Riwayat Kunjungan Pasien dan Transaksi Pasien, silahkan pilih pasien dari worklist di sebelah kiri.
              </p>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.col-md-7 -->

    <!-- ── Right: Riwayat Medis ────────────────────── -->
    <div class="col-md-3 fd-sticky-col" style="padding:10px 0 10px 6px">
      <div class="fd-panel" style="flex:1;display:flex;flex-direction:column;overflow:hidden;min-height:0">
        <div class="fd-panel-hdr" style="justify-content:space-between">
          <span><i class="fa fa-book"></i> Riwayat Medis</span>
          <ul class="nav nav-tabs" id="myTab">
            <li class="active">
              <a data-toggle="tab" href="#rm_tabs" onclick="get_riwayat_medis('<?php echo $no_mr?>')" title="Riwayat Medis">
                <i class="fa fa-book" style="color:#ef9a9a"></i>
              </a>
            </li>
            <li>
              <a data-toggle="tab" href="#rm_tabs" onclick="get_riwayat_pm('<?php echo $no_mr?>')" title="Riwayat Penunjang">
                <i class="fa fa-bookmark" style="color:#a5d6a7"></i>
              </a>
            </li>
            <li>
              <a data-toggle="tab" href="#rm_tabs" onclick="get_riwayat_perjanjian('<?php echo $no_mr?>')" title="Riwayat Perjanjian">
                <i class="fa fa-calendar" style="color:#a5d6a7"></i>
              </a>
            </li>
          </ul>
        </div>
        <div class="fd-riwayat-scroll">
          <div class="tab-content">
            <div id="rm_tabs" class="tab-pane fade in active">
              <div id="cppt_data_on_tabs"></div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.col-md-3 -->

  </div><!-- /.row -->
  </div><!-- /.fd-body -->

</form>

<!-- modal -->
<div id="modalIcare" class="modal fade" tabindex="-1">
  <div class="modal-dialog" style="overflow-y:scroll;max-height:85%;margin-top:50px;margin-bottom:50px;width:80%">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
          </button>
          <span>ICARE JKN BPJS Kesehatan</span>
        </div>
      </div>
      <div class="modal-body no-padding">
        <iframe src="" id="frame_icare" style="width:100%;min-height:500px"></iframe>
      </div>
      <div class="modal-footer no-margin-top">
        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
          <i class="ace-icon fa fa-times"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

</div><!-- /#fd-wrap -->
