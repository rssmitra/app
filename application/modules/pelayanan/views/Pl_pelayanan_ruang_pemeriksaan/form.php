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

      $('#form_default_pelayanan').load('pelayanan/Pl_pelayanan_ruang_pemeriksaan/form_input_hasil?mr=<?php echo $no_mr?>&id='+$('#id_tc_pesanan').val()+'&no_kunjungan='+$('#no_kunjungan').val()+'&bag=<?php echo $kode_bagian; ?>'); 

      // get data antrian pasien
      setInterval("getDataAntrianPasien();",30000); 

      /*focus on form input pasien*/
      $('#form_cari_pasien').focus();    

      $('#form_pelayanan').on('submit', function(){
                
          // $('#konten1').val($('#editor_konten_1').html());
          // $('#konten2').val($('#editor_konten_2').html());
          // $('#konten3').val($('#editor_konten_3').html());
          // $('#konten4').val($('#editor_konten_4').html());
          // $('#konten5').val($('#editor_konten_5').html());
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
                if( $('#form_pelayanan').attr('action')=='pelayanan/Pl_pelayanan_ruang_pemeriksaan/processPelayananSelesai' ){
                    achtungShowFadeIn();                      
                }  
              },
              uploadProgress: function(event, position, total, percentComplete) {
              },
              complete: function(xhr) {     

                var data=xhr.responseText;    
                var jsonResponse = JSON.parse(data);  

                if( jsonResponse.status === 200 ){    

                  $.achtung({message: jsonResponse.message, timeout:5});  
                  $('#table-pesan-resep').DataTable().ajax.reload(null, false);
                  $('#jumlah_r').val('');
                  $("#modalEditPesan").modal('hide');  

                  if(jsonResponse.type_pelayanan == 'Penunjang Medis' ){

                    getMenuTabs('registration/reg_pasien/riwayat_kunjungan/'+jsonResponse.no_mr+'/'+$('#kode_bagian_val').val()+'', 'tabs_riwayat_kunjungan');

                  }

                  if( jsonResponse.type_pelayanan == 'pasien_selesai' ){
                    // back after process
                    if( jsonResponse.next_id_tc_pesanan != '' ){
                      getMenu('pelayanan/Pl_pelayanan_ruang_pemeriksaan/form/'+jsonResponse.next_id_tc_pesanan+'/'+jsonResponse.next_no_kunjungan+'?no_mr='+jsonResponse.next_pasien+'');
                    }else{
                      getMenu('pelayanan/Pl_pelayanan');
                    }

                  }

                  if( jsonResponse.type_pelayanan == 'Expertise' ){
                    // back after process
                    $('#kode_expertise').val(jsonResponse.ID);

                  }

                  
                }else{          

                  $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
                  //focus tabs diagnosa
                  getMenuTabs('pelayanan/Pl_pelayanan_ruang_pemeriksaan/diagnosa/<?php echo $id?>/<?php echo $kunjungan->no_kunjungan?>?type=Rajal&kode_bag=<?php echo isset($kode_bagian)?$kode_bagian:''?>', 'tabs_form_pelayanan'); 

                }        

                achtungHideLoader();        

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
              url: "pelayanan/Pl_pelayanan_ruang_pemeriksaan/destroy_session_kode_bagian",
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

      /*onchange form module when click tabs*/   
      $('#no_mr_selected').change(function (e) {  
        e.preventDefault();  
        var element = $(this).find('option:selected'); 
        var params_id = element.attr("data-id");
        getMenu('pelayanan/Pl_pelayanan_ruang_pemeriksaan/form/'+params_id+'?no_mr='+$(this).val()+'');
      });

  })

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

              $('#umur').text(umur_pasien);

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
                
                }else{
                  
                  $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');

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
              

            }      

      }); 

  }

  function get_riwayat_medis(no_mr){

    $.getJSON("templates/References/get_riwayat_medis/" +no_mr, '', function (data) { 
        $('#cppt_data').html(data.html); 
        $('#cppt_data_on_tabs').html(data.html); 
    });

  }

  function getDataAntrianPasien(){

    // getTotalBilling();
    $.getJSON("pelayanan/Pl_pelayanan_ruang_pemeriksaan/get_data_antrian_pasien?bag=" + $('#kode_bagian_val').val(), '', function (data) {   
          $('#no_mr_selected option').remove();         
          $('#antrian_pasien_tbl_done tbody').remove();         
          $('<option value="">-Pilih Pasien-</option>').appendTo($('#no_mr_selected'));  
          var arr = [];
          var arr_cancel = [];
          var no = 0;
          $.each(data, function (i, o) { 
            no++; 
              var selected = (o.no_mr==$('#noMrHidden').val())?'selected':'';
              var penjamin = (o.kode_perusahaan==120)? '('+o.nama_perusahaan+')' : '' ;
              var style = ( o.status_batal == 1 ) ? 'style="background-color: #fef2f2; color: #dc2626"' : (o.tgl_keluar_poli == null) ? (o.kode_perusahaan == 120) ? '' : 'style="background-color: #eff6ff; color: #1e40af"' : 'style="background-color: #fef2f2; color: #94a3b8"';

              $('<tr><td style="font-weight:700;font-size:13px;color:#64748b;text-align:center;width:35px">'+no+'</td><td><span value="'+o.no_mr+'" data-id="'+o.id_tc_pesanan+'/'+o.referensi_no_kunjungan+'"><a href="#" style="color:#1d4ed8;font-weight:600;font-size:11.5px" onclick="click_selected_patient('+o.id_tc_pesanan+','+o.referensi_no_kunjungan+','+"'"+o.no_mr+"'"+', '+no+')">'+o.no_mr+' - ' + o.nama + '</a><br><span style="font-size:9.5px;color:#94a3b8">'+penjamin+'</span></span></td></tr>').appendTo($('#antrian_pasien_tbl_done'));


              $('<option value="'+o.no_mr+'" data-id="'+o.id_tc_pesanan+'/'+o.referensi_no_kunjungan+'" '+selected+' '+style+'>'+no+'. '+o.no_mr+' - ' + o.nama + ' '+penjamin+' </option>').appendTo($('#no_mr_selected'));  
              // sudah dilayani
              if (o.tgl_keluar_poli != null) {
                  arr.push(o);
              }
              // batal
              if (o.status_batal == 1) {
                arr_cancel.push(o);
              }
          });   
          // total antrian
          var total_antrian = data.length;
          $('#total_antrian').text(total_antrian);
          // dilayani
          $('#sudah_dilayani').text(arr.length);
          // batal
          $('#pasien_batal').text(arr_cancel.length);

          console.log(arr_cancel.length);
      });

  }

  function getTotalBilling(){

    $.getJSON("adm_pasien/pembayaran_dr/Pembentukan_saldo_dr/get_total_billing_dr_current_day?kode_dokter="+$('#kode_dokter_poli').val()+"&kode_bagian="+$('#kode_bagian_val').val()+"", '', function (data) {  
      $('#total_bill_dr_current').text(formatMoney(data.total_billing));
    });

  }

  function click_selected_patient(id_tc_pesanan, no_kunjungan, no_mr, no){
    preventDefault();  
    $('#no_antrian_tbl').text(no);
    getMenu('pelayanan/Pl_pelayanan_ruang_pemeriksaan/form/'+id_tc_pesanan+'/'+no_kunjungan+'?no_mr='+no_mr+'&bag='+$('#kode_bagian_val').val()+'');
  }
</script>

<style type="text/css">
/* ===== Ruang Pemeriksaan — Professional Redesign ===== */

/* — Header Stats Bar — */
.rp-header {
  display: flex; flex-wrap: wrap; align-items: center; gap: 10px;
  background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
  padding: 10px 14px; margin-bottom: 14px;
}
.rp-doctor-info {
  display: flex; align-items: center; gap: 10px;
  padding-right: 14px; border-right: 1px solid #e2e8f0; margin-right: 4px;
}
.rp-doctor-info .rp-doc-icon {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, #1d4ed8, #0ea5e9);
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 15px; flex-shrink: 0;
}
.rp-doctor-info .rp-doc-name { font-size: 12.5px; font-weight: 700; color: #0f172a; line-height: 1.3; }
.rp-doctor-info .rp-doc-unit { font-size: 10.5px; color: #64748b; font-weight: 500; }

.rp-stat-card {
  display: flex; align-items: center; gap: 8px;
  background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;
  padding: 8px 14px; border-left: 3px solid #94a3b8;
  min-width: 100px;
}
.rp-stat-card i { font-size: 16px; color: #94a3b8; }
.rp-stat-card .rp-stat-val { font-size: 18px; font-weight: 700; color: #0f172a; line-height: 1; }
.rp-stat-card .rp-stat-lbl { font-size: 10px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .3px; }
.rp-stat-blue { border-left-color: #3b82f6; }
.rp-stat-blue i { color: #3b82f6; }
.rp-stat-green { border-left-color: #22c55e; }
.rp-stat-green i { color: #22c55e; }
.rp-stat-red { border-left-color: #ef4444; }
.rp-stat-red i { color: #ef4444; }

.rp-btn-close-session {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px; border: none; border-radius: 7px;
  background: linear-gradient(135deg, #dc2626, #ef4444);
  color: #fff; font-size: 11.5px; font-weight: 600;
  cursor: pointer; box-shadow: 0 2px 6px rgba(220,38,38,.2);
  transition: all .18s; margin-left: auto; text-decoration: none;
}
.rp-btn-close-session:hover {
  background: linear-gradient(135deg, #b91c1c, #dc2626);
  box-shadow: 0 3px 10px rgba(220,38,38,.3);
  transform: translateY(-1px); color: #fff; text-decoration: none;
}

/* — Patient Profile Card — */
.rp-profile-card {
  background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;
  box-shadow: 0 1px 5px rgba(0,0,0,.05); overflow: hidden;
}
.rp-profile-card .rp-avatar-wrap {
  text-align: center; padding: 16px 12px 10px;
  background: linear-gradient(135deg, #eff6ff, #f8fafc);
}
.rp-profile-card .rp-avatar-wrap img {
  width: 90px; height: 90px; border-radius: 50%;
  border: 3px solid #bfdbfe; object-fit: cover;
  box-shadow: 0 2px 8px rgba(59,130,246,.15);
}
.rp-mr-title {
  font-size: 15px; font-weight: 700; color: #1d4ed8;
  text-align: center; padding: 8px 10px 4px;
  letter-spacing: .5px;
}
.rp-profile-list { list-style: none; margin: 0; padding: 0; }
.rp-profile-list li {
  padding: 7px 12px; border-bottom: 1px dashed #e2e8f0;
  font-size: 11.5px; color: #334155; line-height: 1.4;
}
.rp-profile-list li:last-child { border-bottom: none; }
.rp-profile-list .rp-label {
  display: block; font-size: 10px; font-weight: 700;
  color: #64748b; text-transform: uppercase; letter-spacing: .3px;
  margin-bottom: 1px;
}
.rp-btn-show-patient {
  display: block; width: calc(100% - 20px); margin: 8px 10px 10px;
  padding: 7px 14px; border: none; border-radius: 7px;
  background: linear-gradient(135deg, #1d4ed8, #0ea5e9);
  color: #fff; font-size: 12px; font-weight: 600; text-align: center;
  cursor: pointer; box-shadow: 0 2px 6px rgba(29,78,216,.2);
  transition: all .18s; text-decoration: none;
}
.rp-btn-show-patient:hover {
  background: linear-gradient(135deg, #1e40af, #0284c7);
  box-shadow: 0 3px 10px rgba(29,78,216,.3);
  transform: translateY(-1px); color: #fff; text-decoration: none;
}

/* — Registration Table — */
.rp-reg-table {
  width: 100%; font-size: 12px; border-collapse: separate;
  border-spacing: 0; border-radius: 8px; overflow: hidden;
  border: 1px solid #e2e8f0; margin-bottom: 14px;
}
.rp-reg-table th {
  background: #f8fafc; color: #334155;
  font-size: 10.5px; font-weight: 700; padding: 7px 10px;
  border-bottom: 2px solid #e2e8f0; border-right: 1px solid #f1f5f9;
  text-transform: uppercase; letter-spacing: .2px; text-align: center;
}
.rp-reg-table th:last-child { border-right: none; }
.rp-reg-table td {
  padding: 8px 10px; border-top: 1px solid #f1f5f9;
  color: #334155; background: #fff; font-size: 12px;
  text-align: center;
}
.rp-antrian-no {
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  background: linear-gradient(135deg, #1d4ed8, #0ea5e9);
  color: #fff; border-radius: 8px; padding: 8px 6px; min-height: 60px;
}
.rp-antrian-no .rp-an-label { font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; opacity: .85; }
.rp-antrian-no .rp-an-num { font-size: 28px; font-weight: 700; line-height: 1; }

/* — Sidebar Tabs — */
#rp-sidebar .nav-tabs {
  border-bottom: 2px solid #e2e8f0; background: #f8fafc;
  border-radius: 8px 8px 0 0; padding: 4px 4px 0;
}
#rp-sidebar .nav-tabs > li > a {
  font-size: 11px; font-weight: 600; color: #64748b;
  border: 1px solid transparent; border-radius: 6px 6px 0 0;
  padding: 8px 12px; transition: all .15s;
}
#rp-sidebar .nav-tabs > li > a:hover {
  background: #fff; color: #334155;
  border-color: #e2e8f0 #e2e8f0 transparent;
}
#rp-sidebar .nav-tabs > li.active > a,
#rp-sidebar .nav-tabs > li.active > a:hover,
#rp-sidebar .nav-tabs > li.active > a:focus {
  background: #fff; color: #0f172a; font-weight: 700;
  border-color: #e2e8f0 #e2e8f0 #fff;
  border-top: 2px solid #3b82f6;
}
#rp-sidebar .tab-content {
  border: 1px solid #e2e8f0; border-top: none;
  border-radius: 0 0 8px 8px; padding: 10px;
  background: #fff; min-height: 200px;
  max-height: 500px; overflow-y: auto;
}
.rp-queue-title {
  font-size: 11.5px; font-weight: 700; color: #334155;
  padding: 4px 0 8px; display: flex; align-items: center; gap: 6px;
}
.rp-queue-title i { color: #22c55e; }
#antrian_pasien_tbl_done {
  width: 100%; border-collapse: separate; border-spacing: 0;
  background: #fff !important;
}
#antrian_pasien_tbl_done td {
  padding: 6px 8px; border-bottom: 1px solid #f1f5f9;
  font-size: 11.5px; color: #334155; vertical-align: middle;
}
#antrian_pasien_tbl_done tr:hover td { background: #f0f9ff; }

/* — Scrollbar — */
#rp-sidebar .tab-content::-webkit-scrollbar { width: 6px; }
#rp-sidebar .tab-content::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 3px; }
#rp-sidebar .tab-content::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
#rp-sidebar .tab-content::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

/* — General overrides — */
.pagination { margin: 0 !important; }
.well { padding: 5px !important; }
select option, select.form-control option { padding: 3px 4px 5px; }
</style>

<div class="row">
  <div class="col-md-12" style="padding-bottom:4px">
    <div class="rp-header">
      <div class="rp-doctor-info">
        <div class="rp-doc-icon"><i class="fa fa-user-md"></i></div>
        <div>
          <div class="rp-doc-name"><?php echo isset($nama_dokter)?$nama_dokter:''?></div>
          <div class="rp-doc-unit"><?php echo ucwords($nama_bagian); ?></div>
        </div>
      </div>
      <div class="rp-stat-card rp-stat-blue">
        <i class="fa fa-users"></i>
        <div>
          <div class="rp-stat-val" id="total_antrian">0</div>
          <div class="rp-stat-lbl">Total Pasien</div>
        </div>
      </div>
      <div class="rp-stat-card rp-stat-green">
        <i class="fa fa-check-circle"></i>
        <div>
          <div class="rp-stat-val" id="sudah_dilayani">0</div>
          <div class="rp-stat-lbl">Dilayani</div>
        </div>
      </div>
      <div class="rp-stat-card rp-stat-red">
        <i class="fa fa-times-circle"></i>
        <div>
          <div class="rp-stat-val" id="pasien_batal">0</div>
          <div class="rp-stat-lbl">Batal</div>
        </div>
      </div>
      <a href="#" class="rp-btn-close-session" id="btn_update_session_poli">
        <i class="fa fa-sign-out"></i> Tutup Session
      </a>
    </div>
  </div>
<div>   

<form class="form-horizontal" method="post" id="form_pelayanan" action="pelayanan/Pl_pelayanan_ruang_pemeriksaan/process" enctype="multipart/form-data" autocomplete="off" >      
  
    <!-- hidden form -->
    <input type="hidden" name="noMrHidden" id="noMrHidden">
    <input type="hidden" name="id_tc_pesanan" id="id_tc_pesanan" value="<?php echo ($id)?$id:''?>">
    <input type="hidden" name="nama_pasien_hidden" id="nama_pasien_hidden">
    <input type="hidden" name="no_kunjungan" class="form-control" value="<?php echo isset($kunjungan->no_kunjungan)?$kunjungan->no_kunjungan:''?>" id="no_kunjungan" readonly>
    <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>  
    <!-- hidden form -->
    <input type="hidden" class="form-control" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
    <input type="hidden" class="form-control" name="kode_bagian" value="<?php echo isset($kode_bagian)?$kode_bagian:''?>" id="kode_bagian_val">


      <!-- profile Pasien -->
      <div class="col-md-2 no-padding">
        <div class="rp-profile-card" id="box_identity">
            <div class="rp-avatar-wrap">
              <img id="avatar" src="<?php echo base_url().'assets/img/avatar.png'?>" alt="Foto Pasien">
            </div>
            <div class="rp-mr-title"><div id="no_mr">No. MR</div></div>
            <ul class="rp-profile-list">
                <li><span class="rp-label">Nama Pasien</span><div id="nama_pasien"></div></li>
                <li><span class="rp-label">NIK</span><div id="no_ktp"></div></li>
                <li><span class="rp-label">Tgl Lahir</span><div id="tgl_lhr"></div></li>
                <li><span class="rp-label">Umur</span><div id="umur"></div></li>
                <li><span class="rp-label">Alamat</span><div id="alamat"></div></li>
                <li><span class="rp-label">No Telp/HP</span><div id="hp"></div><div id="no_telp"></div></li>
                <li><span class="rp-label">Penjamin</span><div id="kode_perusahaan"></div></li>
                <li><span class="rp-label">Catatan</span><div id="catatan_pasien"></div></li>
            </ul>
            <a href="#" id="btn_search_pasien" class="rp-btn-show-patient"><i class="fa fa-search"></i> Tampilkan Pasien</a>
        </div>
      </div>

      <!-- form pelayanan -->
      <div class="col-md-7">

        <table class="rp-reg-table">
          <tr>
            <td rowspan="2" width="90px" style="padding:0; border:none">
              <div class="rp-antrian-no">
                <span class="rp-an-label">Antrian</span>
                <span class="rp-an-num" id="no_antrian_tbl"></span>
              </div>
            </td>
            <th>Kode</th>
            <th>No Reg</th>
            <th>Tanggal Daftar</th>
            <th>Dokter</th>
            <th>Penjamin</th>
          </tr>
          <tr>
            <td><?php echo $value->nama;?></td>
            <td><?php echo $kunjungan->no_registrasi?></td>
            <td><?php echo $kunjungan->tgl_masuk?></td>
            <td><?php echo $nama_dokter?></td>
            <td><?php echo isset($kunjungan->nama_perusahaan)?$kunjungan->nama_perusahaan:'-'?></td>
          </tr>
        </table>

        <!-- <p><b><i class="fa fa-edit"></i> FORM PELAYANAN PASIEN </b></p> -->

        <!-- form default pelayanan pasien -->
        <div id="form_default_pelayanan"></div>
        
      </div>

      <div class="col-md-3 no-padding" id="rp-sidebar">
        <div class="tabbable">
          <ul class="nav nav-tabs" id="myTab">
              <li class="active">
                  <a data-toggle="tab" href="#antrian_tabs">
                      <i class="fa fa-user"></i> Antrian Pasien
                  </a>
              </li>
              <li>
                  <a data-toggle="tab" href="#rm_tabs">
                      <i class="fa fa-history"></i> Riwayat Medis
                  </a>
              </li>
          </ul>

          <div class="tab-content">
              <div id="antrian_tabs" class="tab-pane fade in active">
                  <div class="rp-queue-title"><i class="fa fa-check-square-o"></i> Daftar Antrian Pasien</div>
                  <table class="table" id="antrian_pasien_tbl_done">
                      <tbody>
                          <tr><td><span style="font-style: italic; color: #94a3b8; font-size: 11px">Memuat data antrian...</span></td></tr>
                      </tbody>
                  </table>
              </div>

              <div id="rm_tabs" class="tab-pane fade">
                  <div id="cppt_data_on_tabs"></div>
              </div>
          </div>
        </div>
      </div>

</form>


<!-- ace scripts -->
<script src="<?php echo base_url()?>assets/js/ace/ace.settings.js"></script>

