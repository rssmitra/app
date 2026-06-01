<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>

<style>
/* ── CPPT SOAP Styled Sections ── */
.cppt-section {
  border-radius: 0 6px 6px 0;
  padding: 7px 10px;
  margin-bottom: 6px;
  font-size: 12.5px;
  line-height: 1.5;
}
.cppt-s { border-left: 3px solid #0ea5e9; background: #f0f9ff; }
.cppt-o { border-left: 3px solid #0891b2; background: #f0fdff; }
.cppt-a { border-left: 3px solid #7c3aed; background: #faf5ff; }
.cppt-p { border-left: 3px solid #059669; background: #f0fdf4; }
.cppt-title {
  font-size: 10px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: .6px;
  margin-bottom: 5px;
  display: flex;
  align-items: center;
  gap: 5px;
}
.cppt-s .cppt-title { color: #0369a1; }
.cppt-o .cppt-title { color: #0891b2; }
.cppt-a .cppt-title { color: #6d28d9; }
.cppt-p .cppt-title { color: #065f46; }
.cppt-flabel {
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .4px;
  color: #64748b;
  display: block;
  margin: 5px 0 2px;
}
.cppt-fval {
  font-size: 12.5px;
  color: #1e293b;
  line-height: 1.5;
}
/* Vital signs grid */
.cppt-ttv {
  display: flex;
  gap: 5px;
  flex-wrap: wrap;
  margin: 4px 0;
}
.cppt-ttv-item {
  flex: 1 1 0;
  min-width: 52px;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 5px;
  padding: 5px 4px;
  text-align: center;
  display: flex;
  flex-direction: column;
  gap: 1px;
}
.cppt-ttv-lbl  { font-size: 9px; font-weight: 700; text-transform: uppercase; color: #0891b2; }
.cppt-ttv-val  { font-size: 13px; font-weight: 700; color: #0f172a; line-height: 1.2; }
.cppt-ttv-unit { font-size: 9px; color: #94a3b8; }
/* Badge tipe */
.cppt-badge {
  display: inline-block;
  border-radius: 4px;
  padding: 1px 7px;
  font-size: 10px;
  font-weight: 700;
  color: #fff;
}
.cppt-badge-blue  { background: #0369a1; }
.cppt-badge-green { background: #15803d; }
/* Header kartu */
.cppt-card-hdr {
  background: linear-gradient(135deg,#f0f9ff,#e8f4fd);
  border-bottom: 1px solid #bae6fd;
  padding: 7px 10px;
  border-radius: 6px 6px 0 0;
  font-size: 12px;
}
.cppt-card-hdr .ppa-name  { font-weight: 700; color: #0f172a; }
.cppt-card-hdr .ppa-role  { color: #475569; font-size: 11px; }
.cppt-card-wrap {
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  overflow: hidden;
  background: #f8fafc;
}
.cppt-card-body { padding: 8px; }
/* e-Resep section */
.cppt-r { border-left: 3px solid #d97706; background: #fffbeb; }
/* Riwayat pasien list */
.cppt-riwayat-list { display:flex; flex-direction:column; gap:4px; margin-top:5px; }
.cppt-riwayat-item {
  display: flex;
  align-items: flex-start;
  gap: 7px;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 5px;
  padding: 5px 8px;
  font-size: 12px;
  line-height: 1.45;
}
.cppt-riwayat-item i { flex-shrink:0; margin-top:2px; font-size:12px; }
.cppt-rw-label { font-weight:700; color:#334155; }
.cppt-rw-val       { color:#1e293b; }
.cppt-rw-val.ada   { color:#dc2626; font-weight:600; }
.cppt-rw-val.tidak { color:#16a34a; }
.cppt-rw-ket { color:#64748b; font-size:11px; font-style:italic; margin-top:2px; }
/* e-Resep table */
.cppt-resep-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 5px;
  font-size: 12px;
}
.cppt-resep-table th {
  background: #fef3c7;
  color: #92400e;
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .4px;
  padding: 5px 8px;
  border-bottom: 2px solid #fde68a;
}
.cppt-resep-table td {
  padding: 6px 8px;
  border-bottom: 1px solid #fef3c7;
  vertical-align: top;
}
.cppt-resep-table tr:last-child td { border-bottom: none; }
</style>
<script type="text/javascript">

jQuery(function($) {  

  $('.date-picker').datepicker({    

    autoclose: true,    

    todayHighlight: true    

  })  

  //show datepicker when clicking on the icon

  .next().on(ace.click_event, function(){    

    $(this).prev().focus();    

  });  

  $('#timepicker1').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#timepicker1').timepicker('showWidget');
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
    

});

$(document).ready(function() {
  //initiate dataTables plugin
    oTableCppt = $('#table-cppt').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>&module=RJ",
          "type": "POST"
      },

    });

    // proses add cppt
    $('#btn_add_cppt').click(function (e) {   
      e.preventDefault();
      $.ajax({
          url: $('#form_pelayanan').attr('action'),
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          complete: function(xhr) {             
            var data=xhr.responseText;        
            var jsonResponse = JSON.parse(data);        
            if(jsonResponse.status === 200){          
              $.achtung({message: jsonResponse.message, timeout:5});     
              $('#section_form_cppt').hide('fast');
              $('#section_history_cppt').show('fast');

              oTableCppt.ajax.url('pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>&no_registrasi=<?php echo isset($no_registrasi)?$no_registrasi:''?>&module=RJ').load();
              // reset form
              $('#cppt_id').val('');
              $('#subjective').val('');
              $('#objective').val('');
              $('#assesment').val('');
              $('#plan').val('');
            }else{           
              $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
            }        
            achtungHideLoader();        
          } 
      });

    });

    $('#btn_search_data_cppt').click(function (e) {
        
        e.preventDefault();
        $.ajax({
        url: $('#form_search').attr('action'),
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        success: function(data) {
          achtungHideLoader();
          find_data_reload(data,base_url);
        }
      });
    });

  $('#btn_reset_data_cppt').click(function (e) {
          e.preventDefault();
          reset_table();
  });

  // Typeahead pencarian dokter / PPA
  $('#filter_nama_ppa').typeahead({
    source: function (query, result) {
      $.ajax({
        url: 'templates/references/getAllDokter',
        data: { keyword: query },
        dataType: 'json',
        type: 'POST',
        success: function (response) {
          result($.map(response, function (item) { return item; }));
        }
      });
    },
    afterSelect: function (item) {
      // Format respons: "kode_dokter : nama_pegawai" – ambil nama saja
      var parts = item.split(':');
      var nama  = parts.length > 1 ? $.trim(parts[1]) : $.trim(item);
      $('#filter_nama_ppa').val(nama);
    }
  });

  $('#btn_export_pdf_cppt').click(function (e) {
    var url_search = $('#form_search').attr('action');
    e.preventDefault();
    $.ajax({
      url: url_search,
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      success: function(result) {
        window.open('pelayanan/Pl_pelayanan_ri/export_pdf_cppt?kode_ri=<?php echo isset($kode_ri)?$kode_ri:''?>&'+result.data+'','_blank'); 
      }
    });
  });


});

function delete_cppt(myid, flag){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan_ri/delete_cppt',
        type: "post",
        data: {ID:myid, flag: flag},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            $.achtung({message: jsonResponse.message, timeout:5});
            oTableCppt.ajax.url('pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>&no_registrasi=<?php echo isset($no_registrasi)?$no_registrasi:''?>&module=RJ').load();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }
  
}

function verif_dpjp(cppt_id, value){
    
    if( $('#is_verified_' + cppt_id).is(":checked") ){
      var status = 1;
    }else{
      var status = 0;
    }

    $.ajax({
        url: 'pelayanan/Pl_pelayanan_ri/verif_cppt',
        data: {ID : cppt_id, status_verif : status},            
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
          if(status != 0){
            $('#verif_id_'+cppt_id+'').html('<?php echo $this->session->userdata('user')->fullname?><br><?php echo $this->tanggal->formatDateTime(date('Y-m-d H:i:s'))?>');
          }else{
            $('#verif_id_'+cppt_id+'').html('');

          }
          return false;
        }
    });
    
}

function add_cppt(){
  preventDefault();
  $('#section_form_cppt').show('fast');
  $('#section_history_cppt').hide('fast');
}

function show_edit(myid, flag, no_kunjungan, reff_id){
  preventDefault();
  if(flag == 'RJ'){
    $('#tab_menu_erm_dokter li.active').removeClass('active');
    $('#li_soap').addClass('active');
    $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processUpdateDiagnosaDr');
    getMenuTabs('pelayanan/Pl_pelayanan/diagnosa_dr/'+reff_id+'/'+no_kunjungan+'?type=Rajal&kode_riwayat='+myid+'&kode_bag=<?php echo $kode_bagian;?>', 'tabs_form_pelayanan');
  }

}

function view_data_soap(myid, flag, no_kunjungan, reff_id){
  preventDefault();
  if(flag == 'RJ'){
    $('#tab_menu_erm_dokter li.active').removeClass('active');
    $('#li_soap').addClass('active');
    $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processUpdateDiagnosaDr');
    show_modal('pelayanan/Pl_pelayanan/diagnosa_dr_view_only/'+reff_id+'/'+no_kunjungan+'?type=Rajal&kode_riwayat='+myid+'&kode_bag=<?php echo $kode_bagian;?>', 'SOAP DOKTER');
  }

}

function print_resume(no_registrasi){
  preventDefault();
  show_modal('registration/reg_pasien/view_detail_resume_medis/'+no_registrasi, 'RESUME MEDIS PASIEN');

}

function find_data_reload(result, base_url){
  var data = result.data;
  oTableCppt.ajax.url("pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>&no_registrasi=<?php echo isset($no_registrasi)?$no_registrasi:''?>&module=RJ&"+data).load();
  // $("html, body").animate({ scrollTop: "400px" });
}

function reset_table(){
  $('#filter_nama_ppa').val('');
  oTableCppt.ajax.url("pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>&no_registrasi=<?php echo isset($no_registrasi)?$no_registrasi:''?>&module=RJ").load();
  // $("html, body").animate({ scrollTop: "400px" });
}

function reload_table(){
 oTableCppt.ajax.reload(); //reload datatable ajax 
}

function show_form_rekam_medis(myid){
    preventDefault();
    $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_cppt_dt') ?>", {id: myid} , function (response) {    
        // show data
        var obj = response.result;
        $('#cppt_id').val(myid);
        $('#jenis_form').val(obj.jenis_form);
        // $('#anatomi_tagging_28').val(response.anatomi_tagging);
        $('#form_rekam_medis_special_case_'+myid+'').html(obj.catatan_pengkajian);
        $('#header_form').css('display', 'none');
        $('#footer_form').css('display', 'none');
        // set value input
        var value_form = response.value_form;
        console.log(value_form);
        $.each(value_form, function(i, item) {
            var text = item;
            text = text.replace(/\+/g, ' ');
            key = i.replace(/\+/g, ' ');
            $('#'+key).val(text);
        });
        

    }); 
}

</script>

<div class="row">

  <div class="col-md-12" id="section_form_cppt" style="display: none">

    <div class="center"><span style="font-size: 14px"><b>FORM CPPT</b></span><br><small>(Dilengkapi setelah PPA melakukan Assesment)</small></div>
    <br>
    <div class="form-group">
        <label class="control-label col-sm-2" for="">*Tanggal/Jam</label>
          <div class="col-md-6">
                
            <div class="input-group">
                
                <input name="cppt_tgl" id="cppt_tgl" placeholder="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>">
                <span class="input-group-addon">
                  
                  <i class="ace-icon fa fa-calendar"></i>
                
                </span>

                <input id="timepicker1" name="cppt_jam" id="cppt_jam" type="text" class="form-control">
                <span class="input-group-addon">
                  <i class="fa fa-clock-o bigger-110"></i>
                </span>
                
            </div>

          </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2">PPA</label>
        <div class="col-md-10">
          <div class="radio">
              <label>
                <input name="ppa" type="radio" class="ace" value="perawat" checked="checked"  />
                <span class="lbl"> Perawat</span>
              </label>

              <label>
                <input name="ppa" type="radio" class="ace" value="dokter"/>
                <span class="lbl"> Dokter</span>
              </label>

              <label>
                <input name="ppa" type="radio" class="ace" value="fisioterapist"/>
                <span class="lbl"> Fisioterapist</span>
              </label>

              <label>
                <input name="ppa" type="radio" class="ace" value="dietizen"/>
                <span class="lbl"> Dietizen</span>
              </label>

              <label>
                <input name="ppa" type="radio" class="ace" value="farmasi klinis"/>
                <span class="lbl"> Farmasi Klinis</span>
              </label>
          </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2">Nama PPA</label>
        <div class="col-md-3">
          <input type="text" class="form-control" name="nama_ppa" value="<?php echo $this->session->userdata('user')->fullname?>">
        </div>
    </div>

    <!-- form default pelayanan pasien -->
    <input type="hidden" name="cppt_id" value="" id="cppt_id">

    
    <p style="text-align: center"><b><span style="font-size: 36px;font-family: 'Glyphicons Halflings';">S O A P</span> <br>(<i>Subjective, Objective, Assesment, Planning</i>) </b></p>

    <span style="font-weight: bold; font-style: italic; color: blue">(Subjective)</span>
    <div style="margin-top: 6px">
        <label for="form-field-8"> <b>Anamnesa / Keluhan Pasien</b> <span style="color:red">* </span> <br><span style="font-size: 11px; font-style: italic">(Masukan anamnesa minimal 8 karakter)</span> </label>
        <textarea class="form-control" name="pl_anamnesa" style="height: 100px !important"><?php echo isset($riwayat->anamnesa)?$this->master->br2nl($riwayat->anamnesa):''?></textarea>
        <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">
    </div>
    <br>

    <span style="font-weight: bold; font-style: italic; color: blue">(Objective)</span>

    <div style="margin-top: 6px">
        <label for="form-field-8"> <i><b>Vital Sign</b></i><br><span style="font-size: 11px; font-style: italic">(Masukan tanda-tanda vital)</span></label>
        <table class="table">
            <tr style="font-size: 11px; background: beige;">
                <th>Tinggi Badan (Cm)</th>
                <th>Berat Badan (Kg)</th>
                <th>Tekanan Darah (mmHg)</th>
                <th>Suhu Tubuh (C&deg;)</th>
                <th>Nadi (bpm)</th>
            </tr>
            <tbody>
            <tr style="background: aliceblue;">
                <td>
                    <input type="text" style="text-align: center" class="form-control" name="pl_tb" value="<?php echo isset($riwayat->tinggi_badan)?$riwayat->tinggi_badan:''?>">
                </td>
                <td>
                    <input type="text" style="text-align: center" class="form-control" name="pl_bb" value="<?php echo isset($riwayat->berat_badan)?$riwayat->berat_badan:''?>">
                </td>
                <td>
                    <input type="text" style="text-align: center" class="form-control" name="pl_td" value="<?php echo isset($riwayat->tekanan_darah)?$riwayat->tekanan_darah:''?>">
                </td>
                <td>
                    <input type="text" style="text-align: center" class="form-control" name="pl_suhu" value="<?php echo isset($riwayat->suhu)?$riwayat->suhu:''?>">
                </td>
                <td>
                    <input type="text" style="text-align: center" class="form-control" name="pl_nadi" value="<?php echo isset($riwayat->nadi)?$riwayat->nadi:''?>">
                </td>
            </tr>
            </tbody>
        </table>

        <label for="form-field-8"> <b>Pemeriksaan Fisik</b><br><span style="font-size: 11px; font-style: italic">(Mohon dijelaskan kondisi fisik pasien)</span></label>
        <textarea name="pl_pemeriksaan" id="pl_pemeriksaan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pemeriksaan)?$this->master->br2nl($riwayat->pemeriksaan):''?></textarea>
        <input type="hidden" name="flag_form_pelayanan" value="<?php echo ($this->session->userdata('flag_form_pelayanan')) ? $this->session->userdata('flag_form_pelayanan') : 'perawat'?>"><br>
        
    </div>

    <span style="font-weight: bold; font-style: italic; color: blue">(Assesment)</span>

    <div style="margin-top: 6px">
        <label for="form-field-8"><b>Diagnosa Primer(ICD10)</b> <span style="color:red">* </span><br><i style="font-size: 11px">(Wajib mengisi menggunakan ICD10)</i></label>
        <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
        <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
    </div>

    <div style="margin-top: 6px">
        <label for="form-field-8"><b>Diagnosa Sekunder</b> <br><i style="font-size: 11px">(Klik <b>"enter"</b> untuk menambahkan Diagnosa Sekunder dan dapat diisi lebih dari satu )</i></label>
        <input type="text" class="form-control" name="pl_diagnosa_sekunder" id="pl_diagnosa_sekunder" placeholder="Masukan keyword ICD 10" value="">
        <div id="pl_diagnosa_sekunder_hidden_txt" style="padding: 2px; line-height: 23px; border: 1px solid #d5d5d5; min-height: 25px; margin-top: 2px">
            <?php
                $arr_text = isset($riwayat->diagnosa_sekunder) ? explode('|',$riwayat->diagnosa_sekunder) : [];
                // echo "<pre>";print_r($arr_text);
                foreach ($arr_text as $k => $v) {
                    $split = explode(':',$v);
                    if(count($split) > 1){
                        echo '<span class="multi-typeahead" id="txt_icd_'.trim(str_replace('.','_',$split[0])).'"><a href="#" onclick="remove_icd('."'".trim(str_replace('.','_',$split[0]))."'".')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span> <span class="text_icd_10"> '.$v.' </span> </span>';
                    }
                }
            ?>
        </div>
        <input type="hidden" class="form-control" name="konten_diagnosa_sekunder" id="konten_diagnosa_sekunder" value="<?php echo isset($riwayat->diagnosa_sekunder)?$riwayat->diagnosa_sekunder:''?>">
    </div>
    <br>
    <span style="font-weight: bold; font-style: italic; color: blue">(Planning)</span>
    <div style="margin-top: 6px">
        <label for="form-field-8"><b>Rencana Asuhan / Anjuran Dokter</b><br><i style="font-size: 11px">(Mohon dijelaskan Rencana Asuhan Pasien dan Tindak Lanjutnya)</i></label>
        <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pengobatan)?$this->master->br2nl($riwayat->pengobatan):''?></textarea>
    </div>




<!-- 
    <div>
        <label class="padding-20" style="padding-top: 10px"><b style="font-size: 18px; color: blue">S</b> (<i>Subjective</i>) <span style="color:red">(*)</span>:</label><br>
        <div class="col-sm-12 no-padding">
          <textarea name="subjective" id="subjective" class="form-control" style="height:120px !important"  placeholder="" ><?php echo isset($riwayat->subjective)?$riwayat->subjective:''?></textarea>  
        </div>
    </div>

    <div>
        <label class="padding-20" style="padding-top: 10px"><b style="font-size: 18px; color: blue">O</b> (<i>Objective</i>) <span style="color:red">(*)</span>:</label><br>
        <div class="col-sm-12 no-padding">
          <textarea name="objective" id="objective" class="form-control" style="height:120px !important"  placeholder="" ><?php echo isset($riwayat->objective)?$riwayat->objective:''?></textarea>  
        </div>
    </div>

    <div>
        <label class="padding-20" style="padding-top: 10px"><b style="font-size: 18px; color: blue">A</b> (<i>Assesment</i>) <span style="color:red">(*)</span>:</label><br>
        <div class="col-sm-12 no-padding">
          <textarea name="assesment" id="assesment" class="form-control" style="height:120px !important"  placeholder="" ><?php echo isset($riwayat->assesment)?$riwayat->assesment:''?></textarea>  
        </div>
    </div>

    <div>
        <label class="padding-20" style="padding-top: 10px"><b style="font-size: 18px; color: blue">P</b> (<i>Plan</i>) <span style="color:red">(*)</span>:</label><br>
        <div class="col-sm-12 no-padding">
          <textarea name="plan" id="plan" class="form-control" style="height:120px !important"  placeholder="" ><?php echo isset($riwayat->plan)?$riwayat->plan:''?></textarea>  
        </div>
    </div> -->

    <div class="col-md-12" id="btn_submit_cppt" style="margin-top: 20px" >
        <div class="col-sm-12"><a href="#" class="btn btn-sm btn-primary" id="btn_add_cppt"><i class="fa fa-save"></i> Simpan CPPT</a> 
        </div>
    </div>

    <br>
    <hr>
  </div>

  <div class="col-md-12" id="section_history_cppt">
    <!-- add form -->
    <!-- <div style="">
      <a href="#" class="btn btn-xs btn-primary" onclick="add_cppt()"><i class="fa fa-plus"></i> Input CPPT</a>
    </div> -->
    <!-- <center><span style="font-size: 14px"><b>CATATAN PERKEMBANGAN PASIEN <br>TERINTEGRASI (CPPT)</b></span></center><br> -->
    <div class="col-md-12">
        <p style="text-align: center; margin-top: 10px"><b><span style="font-size: 20px;">Riwayat Catatan Medis</span> <br>(<i>Catatan Perkembangan Pasien Terintegrasi</i>) </b></p>
    </div>
    <br>

      <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan_ri/find_data" autocomplete="off">

        <!-- pencarian berdasarkan tanggal dan dokter/PPA -->

        <div class="form-group">

            <label class="control-label col-md-2">Tanggal CPPT</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>

            <label class="control-label col-md-1" style="margin-left: 40px;padding-left: 19px">s/d</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>

        </div>

        <div class="form-group">

            <label class="control-label col-md-2">Dokter / PPA</label>
            <div class="col-md-4">
              <div class="input-group">
                <input type="text" class="form-control typeahead" id="filter_nama_ppa" name="nama_ppa"
                       placeholder="Ketik nama dokter..." autocomplete="off">
                <span class="input-group-addon" style="cursor:pointer;" onclick="$('#filter_nama_ppa').val('').focus();">
                  <i class="fa fa-times"></i>
                </span>
              </div>
            </div>

            <div class="col-md-6 no-padding" style="padding-top:3px;">
              <a href="#" id="btn_search_data_cppt" class="btn btn-xs btn-default">
                <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                Search
              </a>
              <a href="#" id="btn_reset_data_cppt" class="btn btn-xs btn-warning">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                Reset
              </a>
              <a href="#" id="btn_export_pdf_cppt" class="btn btn-xs btn-danger">
                <i class="fa fa-file-pdf-o bigger-110"></i>
                Export PDF
              </a>
              <a href="#" id="btn_export_excel_cppt" class="btn btn-xs btn-success">
                <i class="fa fa-file-excel-o bigger-110"></i>
                Export Excel
              </a>
            </div>

        </div>
          
        <table id="table-cppt" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th width="30px">No</th>
              <th width="150px">Tanggal/Jam/PPA</th>
              <th>SOAP/Pengkajian Pasien</th>
              <th width="120px">Verifikasi DPJP</th>
              <th width="100px">Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </form>
  </div>
  
</div>







