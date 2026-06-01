<link rel="stylesheet" href="<?php echo base_url().'assets/css/bootstrap-timepicker.css'?>" />
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
  function openSlidePanel(html) {
    preventDefault();
    document.getElementById('slidePanelContent').innerHTML = html;
    document.getElementById('slidePanel').classList.add('open');
    document.getElementById('slidePanelBg').classList.add('active');
  }
  function closeSlidePanel() {
    document.getElementById('slidePanel').classList.remove('open');
    document.getElementById('slidePanelBg').classList.remove('active');
  }
  // Optional: close panel if background clicked
  document.getElementById('slidePanelBg').onclick = closeSlidePanel;
</script>

<style>
  .slide-panel {
    position: fixed;
    top: 0;
    right: -500px;
    width: 400px;
    height: 100vh;
    background: #fff;
    box-shadow: -2px 0 10px rgba(0,0,0,0.2);
    z-index: 9999;
    transition: right 0.4s cubic-bezier(.4,0,.2,1);
    overflow-y: auto;
    padding: 24px 20px 20px 20px;
  }
  .slide-panel.open {
    right: 0;
  }
  .slide-panel .close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 2em;
    color: #888;
    background: none;
    border: none;
    cursor: pointer;
    z-index: 10001;
  }
  .slide-panel-content {
    margin-top: 40px;
  }
  .slide-panel-bg {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.2);
    z-index: 9998;
  }
  .slide-panel-bg.active {
    display: block;
  }
</style>


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
          "url": "pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>",
          "type": "POST"
      },

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

  $('#btn_export_pdf_cppt').click(function (e) {
    var url_search = $('#form_search').attr('action');
    e.preventDefault();
    $.ajax({
      url: url_search,
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      success: function(result) {
        window.open('pelayanan/Pl_pelayanan_ri/export_pdf_cppt?kode_ri=<?php echo $kode_ri?>&no_mr=<?php echo $no_mr?>&'+result.data+'','_blank');
      }
    });
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
      var parts = item.split(':');
      var nama  = parts.length > 1 ? $.trim(parts[1]) : $.trim(item);
      $('#filter_nama_ppa').val(nama);
    }
  });

});

function delete_cppt(myid, flag){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan_ri/delete_cppt',
        type: "post",
        data: {ID:myid, flag: flag },
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
            oTableCppt.ajax.url('pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>').load();
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

function show_edit(myid){
  preventDefault();
  $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_cppt_dt') ?>", {id: myid} , function (response) {    
    // show data
    $('#section_form_cppt').show('fast');
    $('#section_history_cppt').show('fast');
    $('#cppt_id').val(response.cppt_id);
    var subjective = response.cppt_subjective;
    $('#subjective').val(subjective.replace(/<br ?\/?>/g, "\n"));
    var objective = response.cppt_objective;
    $('#objective').val(objective.replace(/<br ?\/?>/g, "\n"));
    var assesment = response.cppt_assesment;
    $('#assesment').val(assesment.replace(/<br ?\/?>/g, "\n"));
    var plan = response.cppt_plan;
    $('#plan').val(plan.replace(/<br ?\/?>/g, "\n"));
  }); 
}

function find_data_reload(result, base_url){
  var data = result.data;    
  oTableCppt.ajax.url("pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>&"+data).load();
  // $("html, body").animate({ scrollTop: "400px" });
}

function reset_table(){
  $('#filter_nama_ppa').val('');
  oTableCppt.ajax.url("pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>").load();
  // $("html, body").animate({ scrollTop: "400px" });
}

function reload_table(){
 oTableCppt.ajax.reload(); //reload datatable ajax 
}

function view_data_soap(myid, flag, no_kunjungan, reff_id){
  preventDefault();
  if(flag == 'RJ'){

    $.getJSON('pelayanan/Pl_pelayanan/diagnosa_dr_view_only/'+reff_id+'/'+no_kunjungan+'?type=Rajal&kode_riwayat='+myid+'&kode_bag=<?php echo $sess_kode_bag;?>&response=json', '' , function (response) {    
      console.log(response);
      openSlidePanel(response.html);
    }); 

    // $('#tab_menu_erm_dokter li.active').removeClass('active');
    // $('#li_soap').addClass('active');
    // $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processUpdateDiagnosaDr');

    // show_modal('pelayanan/Pl_pelayanan/diagnosa_dr_view_only/'+reff_id+'/'+no_kunjungan+'?type=Rajal&kode_riwayat='+myid+'&kode_bag=<?php echo $sess_kode_bag;?>', 'SOAP DOKTER');
  }

}

function print_resume(no_registrasi){
  preventDefault();
  show_modal('registration/reg_pasien/view_detail_resume_medis/'+no_registrasi, 'RESUME MEDIS PASIEN');

}

</script>


<div class="slide-panel-bg" id="slidePanelBg"></div>

<div class="slide-panel" id="slidePanel">
  <button class="close-btn" type="button" onclick="closeSlidePanel()">&times;</button>
  <div class="slide-panel-content" id="slidePanelContent">
    <!-- Konten detail akan dimuat di sini -->
  </div>
</div>

<div class="row">
  <div class="col-md-12" id="section_history_cppt">

    <!-- Page header -->
    <div style="text-align:center; margin-bottom:16px; padding-bottom:12px; border-bottom:2px solid #e2e8f0;">
      <p style="margin:0 0 3px 0;">
        <b><span style="font-size:20px; color:#0f172a;">
          <i class="fa fa-list-alt" style="color:#0369a1; margin-right:6px;"></i>
          Riwayat Catatan Medis
        </span></b>
      </p>
      <small style="color:#64748b; font-size:12px;"><i>Catatan Perkembangan Pasien Terintegrasi (RJ / RI)</i></small>
    </div>

    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan_ri/find_data" autocomplete="off">

      <!-- Filter card -->
      <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px 16px; margin-bottom:14px;">

        <div class="form-group" style="margin-bottom:8px;">

            <label class="control-label col-md-1">Tanggal</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>

            <label class="control-label col-md-1" style="margin-left:-10px">s/d</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>

            <label class="control-label col-md-1">Tipe</label>
            <div class="col-md-2">
              <div class="radio">
                    <label>
                      <input name="tipe_layan" type="radio" class="ace" value="RI"/>
                      <span class="lbl"> RI</span>
                    </label>
                    <label>
                      <input name="tipe_layan" type="radio" class="ace" value="RJ"/>
                      <span class="lbl"> RJ</span>
                    </label>
              </div>
            </div>

        </div>

        <div class="form-group" style="margin-bottom:0;">

            <label class="control-label col-md-1">Dokter / PPA</label>
            <div class="col-md-3">
              <div class="input-group">
                <input type="text" class="form-control typeahead" id="filter_nama_ppa" name="nama_ppa"
                       placeholder="Ketik nama dokter..." autocomplete="off">
                <span class="input-group-addon" style="cursor:pointer;" onclick="$('#filter_nama_ppa').val('').focus();">
                  <i class="fa fa-times"></i>
                </span>
              </div>
            </div>

            <div class="col-md-5 no-padding" style="padding-top:3px;">
              <a href="#" id="btn_search_data_cppt" class="btn btn-xs btn-primary">
                <i class="fa fa-search"></i> Cari
              </a>
              <a href="#" id="btn_reset_data_cppt" class="btn btn-xs btn-default">
                <i class="fa fa-refresh"></i> Reset
              </a>
              <a href="#" id="btn_export_pdf_cppt" class="btn btn-xs btn-danger">
                <i class="fa fa-file-pdf-o"></i> Export PDF
              </a>
            </div>

        </div>

      </div><!-- /filter card -->

      <table id="table-cppt" class="table table-bordered table-hover table-condensed">
        <thead style="background:linear-gradient(135deg,#f0f9ff,#e8f4fd); border-bottom:2px solid #bae6fd;">
          <tr>
            <th width="30px"  style="color:#0369a1;">#</th>
            <th width="160px" style="color:#0369a1;">Tanggal / Jam / PPA</th>
            <th               style="color:#0369a1;">SOAP / Pengkajian Pasien</th>
            <th width="130px" style="color:#0369a1;">Verifikasi DPJP</th>
            <th width="100px" style="color:#0369a1;">Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>

    </form>
  </div>
  
</div>







