<script style="text/javascript">

  function resepkan_ulang(id){
      preventDefault();
      if(confirm('Apakah anda yakin akan meresepkan kembali obat-obat ini?')){
          var formData = {
            kode_pesan_resep : id,
            no_registrasi : $('#no_registrasi').val(),
            no_kunjungan : $('#no_kunjungan').val(),
            no_mr : $('#noMrHidden').val(),
            kode_kelompok : $('#kode_kelompok').val(),
            kode_perusahaan : $('#kode_perusahaan_val').val(),
            kode_klas : $('#kode_klas').val(),
            kode_profit : $('#kode_profit').val(),
            kode_bagian_asal : $('#kode_bagian_asal').val(),
            kode_dokter : $('#kode_dokter_poli').val(),
          };
          $.ajax({
              url: "farmasi/E_resep/proses_resepkan_ulang",
              data: formData,            
              dataType: "json",
              type: "POST",
              success: function (response) {
                  // load form pesan resep
                  $('.nav-list li').removeClass('active');
                  $('li#li_tabs_farmasi').addClass('active');
                  getMenuTabs('farmasi/Farmasi_pesan_resep/pesan_resep/'+$('#no_kunjungan').val()+'/'+$('#kode_klas').val()+'/'+$('#kode_profit').val(), 'tabs_form_pelayanan')
              }
          });
      }else{
          return false;
      }
      
  }

  function copy_soap(id){
      preventDefault();
      if(confirm('Apakah anda yakin akan menyalin SOAP ini?')){
          var formData = {
            kode_riwayat : id,
          };
          $.ajax({
              url: "pelayanan/Pl_pelayanan/copy_soap",
              data: formData,            
              dataType: "json",
              type: "POST",
              success: function (response) {
                  // load form pesan resep
                  var obj = response.result;
                  console.log(obj);
                  $('#pl_anamnesa').val(obj.anamnesa);
                  $('#pl_pemeriksaan').val(obj.pemeriksaan);
                  $('#pl_diagnosa').val(obj.diagnosa_akhir);
                  $('#pl_diagnosa_hidden').val(obj.kode_icd_diagnosa);
                  // split diagnosa sekunder
                  var ds = obj.diagnosa_sekunder;
                  var diagnosa_sekunder = ds.split('|');
                  console.log(diagnosa_sekunder);
                  var string = '';
                  for (i = 1; i < diagnosa_sekunder.length; ++i) {
                    if(diagnosa_sekunder[i] != ''){
                      string += '<span class="multi-typeahead" id="txt_icd_'+i+'"><a href="#" onclick="remove_icd('+"'"+i+"'"+')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span> <span class="text_icd_10"> '+diagnosa_sekunder[i]+' </span> </span>';
                    }
                  }
                  console.log(string);
                  $('#pl_diagnosa_sekunder_hidden_txt').html(string);
                  $('#konten_diagnosa_sekunder').val(obj.diagnosa_sekunder);
                  $('#pl_pengobatan').val(obj.pengobatan+'\n'+obj.resep_farmasi);
              }
          });
      }else{
          return false;
      }
      
  }
</script>

<script>
// Fitur filter dokter pada accordion dengan select option menggunakan jQuery
$(document).ready(function() {
  $('#filterDokterSelect').on('change', function() {
    var filter = $(this).val().toLowerCase();
    var $panels = $('#accordion .rm-card');
    // Tutup semua panel saat filter berubah
    $panels.find('.panel-collapse').removeClass('in');
    $panels.each(function() {
      var dokter = ($(this).data('dokter') || '').toLowerCase();
      if(filter === '' || dokter === filter) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
    // Buka panel pertama yang tampil jika ada
    var $visiblePanels = $panels.filter(':visible');
    if($visiblePanels.length > 0) {
      $visiblePanels.first().find('.panel-collapse').addClass('in');
    }
  });
});
</script>

<style>
  #rm-wrap {
    font-family: 'Segoe UI', system-ui, Arial, sans-serif;
    font-size: 13px;
  }

  /* ── Filter bar ── */
  .rm-filter-bar {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-left: 3px solid #0ea5e9;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }
  .rm-filter-bar label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #0369a1;
    margin: 0;
    white-space: nowrap;
  }
  .rm-filter-bar select {
    flex: 1;
    min-width: 160px;
    font-size: 12.5px;
  }

  /* ── Card ── */
  .rm-card {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 6px rgba(0,0,0,.05);
    margin-bottom: 10px;
    overflow: hidden;
  }

  /* ── Card header ── */
  .rm-card-hdr {
    background: linear-gradient(135deg, #f0f9ff 0%, #e8f4fd 100%);
    border-bottom: 1px solid #bae6fd;
    padding: 10px 14px;
    cursor: pointer;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    text-decoration: none !important;
  }
  .rm-card-hdr:hover { background: linear-gradient(135deg, #e0f2fe, #dbeafe); }
  .rm-card-hdr.cancelled {
    background: linear-gradient(135deg, #fff5f5, #fee2e2);
    border-bottom-color: #fecaca;
  }
  .rm-card-chevron {
    margin-top: 2px;
    color: #0ea5e9;
    font-size: 14px;
    flex-shrink: 0;
  }
  .rm-card-hdr-date {
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
  }
  .rm-card-hdr-dokter {
    font-size: 11.5px;
    color: #475569;
    margin-top: 2px;
  }
  .rm-card-hdr-bagian {
    font-size: 11px;
    color: #64748b;
    margin-top: 1px;
  }
  .rm-badge {
    display: inline-block;
    border-radius: 4px;
    padding: 2px 8px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .3px;
    color: #fff;
  }
  .rm-badge-red    { background: #dc2626; }
  .rm-badge-blue   { background: #0369a1; }
  .rm-badge-green  { background: #15803d; }
  .rm-badge-amber  { background: #b45309; }
  .rm-badge-slate  { background: #475569; }

  /* ── Card body ── */
  .rm-card-body {
    padding: 12px 14px;
    background: #f8fafc;
  }

  /* ── Action buttons ── */
  .rm-actions {
    display: flex;
    gap: 7px;
    flex-wrap: wrap;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e2e8f0;
  }
  .rm-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    color: #fff;
    transition: opacity .15s, transform .15s;
  }
  .rm-btn:hover { opacity: .85; transform: translateY(-1px); color: #fff; text-decoration: none; }
  .rm-btn-green { background: linear-gradient(135deg, #15803d, #22c55e); }
  .rm-btn-blue  { background: linear-gradient(135deg, #0369a1, #0ea5e9); }

  /* ── SOAP sections ── */
  .rm-section {
    border-radius: 0 7px 7px 0;
    padding: 9px 12px;
    margin-bottom: 10px;
  }
  .rm-section-s { border-left: 3px solid #0ea5e9; background: #f0f9ff; }
  .rm-section-o { border-left: 3px solid #0891b2; background: #f0fdff; }
  .rm-section-a { border-left: 3px solid #7c3aed; background: #faf5ff; }
  .rm-section-p { border-left: 3px solid #059669; background: #f0fdf4; }
  .rm-section-r { border-left: 3px solid #d97706; background: #fffbeb; }
  .rm-section-f { border-left: 3px solid #64748b; background: #f8fafc; }

  .rm-section-title {
    font-size: 10.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .7px;
    margin-bottom: 7px;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .rm-section-s .rm-section-title { color: #0369a1; }
  .rm-section-o .rm-section-title { color: #0891b2; }
  .rm-section-a .rm-section-title { color: #6d28d9; }
  .rm-section-p .rm-section-title { color: #065f46; }
  .rm-section-r .rm-section-title { color: #92400e; }
  .rm-section-f .rm-section-title { color: #334155; }

  .rm-flabel {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748b;
    display: block;
    margin: 8px 0 3px;
  }
  .rm-fval {
    font-size: 12.5px;
    color: #1e293b;
    line-height: 1.55;
  }

  /* ── Vital signs table ── */
  .rm-vitals {
    width: 100%;
    border-collapse: collapse;
    margin-top: 6px;
    background: #fff;
    border-radius: 6px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
  }
  .rm-vitals th {
    background: #e0f2fe;
    color: #0369a1;
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 7px 8px;
    text-align: center;
    border: none;
  }
  .rm-vitals td {
    padding: 5px 6px;
    text-align: center;
    border: 1px solid #f1f5f9;
  }
  .rm-vitals td input.form-control {
    text-align: center;
    font-size: 12.5px;
    height: 30px;
    padding: 3px 6px;
    border-color: #e2e8f0;
  }

  /* ── e-Resep table ── */
  .rm-resep-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 6px;
    font-size: 12px;
  }
  .rm-resep-table th {
    background: #fef3c7;
    color: #92400e;
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 7px 10px;
    border-bottom: 2px solid #fde68a;
  }
  .rm-resep-table td {
    padding: 8px 10px;
    border-bottom: 1px solid #fef3c7;
    vertical-align: top;
  }
  .rm-resep-table tr:last-child td { border-bottom: none; }

  /* ── Empty state ── */
  .rm-empty {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-left: 3px solid #f59e0b;
    border-radius: 8px;
    padding: 14px 16px;
    color: #92400e;
    font-size: 13px;
    font-weight: 600;
  }

  /* keep accordion animation */
  #rm-wrap .panel-collapse { transition: height .2s ease; }
  #rm-wrap hr { margin: 6px 0; border-top: 1px solid #e2e8f0; }
</style>

<div id="rm-wrap">

  <!-- ── Filter Dokter ── -->
  <div class="rm-filter-bar">
    <label><i class="fa fa-filter" style="margin-right:4px;"></i>Filter Dokter</label>
    <select id="filterDokterSelect" class="form-control input-sm">
      <option value="">-- Semua Dokter --</option>
      <?php
        $dokterList = array();
        foreach($result as $val) {
          $dokter = strtolower(trim($val->dokter_pemeriksa));
          if($dokter && !in_array($dokter, $dokterList)) {
            $dokterList[] = $dokter;
          }
        }
        foreach($dokterList as $dokter) {
          echo '<option value="'.$dokter.'">'.ucwords($dokter).'</option>';
        }
      ?>
    </select>
  </div>

  <!-- ── Accordion ── -->
  <div id="accordion" class="panel-group" style="position:relative;">
    <?php
      if(count($result) > 0):
      foreach ($result as $key => $value) :
        $default_toogle = (in_array($key, array(0))) ? 'in' : '' ;
        $lembar_konsul  = 0;
        $files   = isset($file_pkj[$value->no_registrasi][$value->no_kunjungan]) ? $file_pkj[$value->no_registrasi][$value->no_kunjungan] : array();
        $file_rm = isset($file[$value->no_registrasi][$value->no_kunjungan])     ? $file[$value->no_registrasi][$value->no_kunjungan]     : array();

        $html_file = '';
        if(count($files) > 0){
          $html_file .= '<ol style="margin:6px 0 0; padding-left:18px;">';
          foreach ($files as $kpkj => $vpkj) {
            $html_file .= '<li style="margin-bottom:4px;"><a href="#" onclick="show_modal_medium_return_json(\'pelayanan/Pl_pelayanan_ri/show_catatan_pengkajian/'.$vpkj->id.'\', \''.$vpkj->jenis_pengkajian.'\')">'.$vpkj->jenis_pengkajian.'</a></li>';
            $lembar_konsul = ($vpkj->jenis_form == 29) ? 1 : 0;
          }
          $html_file .= '</ol>';
        }else{
          $html_file .= '<span style="color:#94a3b8; font-size:12px;">Tidak ada file ditemukan</span>';
        }

        $html_file_rm = '';
        if(count($file_rm) > 0){
          $html_file_rm .= '<ol style="margin:6px 0 0; padding-left:18px;">';
          foreach ($file_rm as $kprm => $vprm) {
            if($vprm->is_adjusment == 'Y'){
              $fnme = explode('-', $vprm->csm_dex_nama_dok);
              $html_file_rm .= '<li style="margin-bottom:4px;"><a href="#" onclick="PopupCenter(\''.$vprm->base_url_dok.$vprm->csm_dex_fullpath.'\', 1200, 750)">'.$fnme[0].'</a></li>';
            }
          }
          $html_file_rm .= '</ol>';
        }else{
          $html_file_rm .= '<span style="color:#94a3b8; font-size:12px;">Tidak ada file ditemukan</span>';
        }

        $is_batal   = ($value->status_batal == 1);
        $hdrClass   = $is_batal ? 'rm-card-hdr cancelled' : 'rm-card-hdr';
        $cara_keluar = (!in_array($value->cara_keluar_pasien, [null, 'Atas Persetujuan Dokter', 'Atas Permintaan Sendiri'])) ? $value->cara_keluar_pasien : '';
    ?>
    <div class="rm-card" data-dokter="<?php echo strtolower(trim($value->dokter_pemeriksa)); ?>">

      <!-- Card Header (toggle) -->
      <a class="<?php echo $hdrClass; ?>" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $value->kode_riwayat; ?>">
        <i class="fa fa-angle-down rm-card-chevron" data-icon-hide="fa fa-angle-down" data-icon-show="fa fa-angle-right"></i>
        <div style="flex:1; min-width:0;">
          <div class="rm-card-hdr-date">
            <i class="fa fa-calendar-o" style="color:#0ea5e9; margin-right:5px; font-size:11px;"></i>
            <?php echo $this->tanggal->formatDateTime($value->tgl_periksa); ?>
            <?php if($is_batal): ?>
              &nbsp;<span class="rm-badge rm-badge-red">Batal</span>
            <?php endif; ?>
            <?php if($cara_keluar): ?>
              &nbsp;<span class="rm-badge rm-badge-blue"><?php echo $cara_keluar; ?></span>
            <?php endif; ?>
            <?php if($lembar_konsul == 1): ?>
              &nbsp;<span class="rm-badge rm-badge-amber">Rujukan Internal</span>
            <?php endif; ?>
          </div>
          <div class="rm-card-hdr-dokter">
            <i class="fa fa-user-md" style="color:#64748b; margin-right:4px; font-size:11px;"></i>
            <?php echo $value->dokter_pemeriksa; ?>
          </div>
          <div class="rm-card-hdr-bagian">
            <i class="fa fa-building-o" style="color:#94a3b8; margin-right:4px; font-size:11px;"></i>
            <?php echo ucwords($value->nama_bagian); ?> &mdash; <?php echo $value->tipe; ?>
          </div>
        </div>
      </a>

      <!-- Card Body (collapsible) -->
      <div class="panel-collapse collapse <?php echo $default_toogle; ?>" id="collapse<?php echo $value->kode_riwayat; ?>">
        <div class="rm-card-body">

          <!-- Action buttons -->
          <div class="rm-actions">
            <a href="#" class="rm-btn rm-btn-green" onclick="copy_soap(<?php echo $value->kode_riwayat; ?>)">
              <i class="fa fa-copy"></i> Copy SOAP
            </a>
            <a href="#" class="rm-btn rm-btn-blue" onclick="show_modal('registration/reg_pasien/view_detail_resume_medis/<?php echo $value->no_registrasi; ?>', 'RESUME MEDIS PASIEN')">
              <i class="fa fa-file-text-o"></i> Resume Medis
            </a>
          </div>

          <!-- S: Subjective -->
          <div class="rm-section rm-section-s">
            <div class="rm-section-title"><i class="fa fa-comment-o"></i> S &mdash; Subjective</div>
            <span class="rm-flabel">Anamnesa / Keluhan Pasien</span>
            <div class="rm-fval"><?php echo isset($value->subjective) ? nl2br($value->subjective) : '<span style="color:#94a3b8">—</span>'; ?></div>
          </div>

          <!-- O: Objective -->
          <div class="rm-section rm-section-o">
            <div class="rm-section-title"><i class="fa fa-stethoscope"></i> O &mdash; Objective</div>
            <span class="rm-flabel">Vital Sign</span>
            <table class="rm-vitals">
              <thead>
                <tr>
                  <th>TB (cm)</th>
                  <th>BB (kg)</th>
                  <th>TD (mmHg)</th>
                  <th>Nadi (bpm)</th>
                  <th>Suhu (&deg;C)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input type="text" class="form-control" name="pl_tb"   value="<?php echo isset($value->tinggi_badan)  ? $value->tinggi_badan  : ''; ?>"></td>
                  <td><input type="text" class="form-control" name="pl_bb"   value="<?php echo isset($value->berat_badan)   ? $value->berat_badan   : ''; ?>"></td>
                  <td><input type="text" class="form-control" name="pl_td"   value="<?php echo isset($value->tekanan_darah) ? $value->tekanan_darah : ''; ?>"></td>
                  <td><input type="text" class="form-control" name="pl_nadi" value="<?php echo isset($value->nadi)          ? $value->nadi          : ''; ?>"></td>
                  <td><input type="text" class="form-control" name="pl_suhu" value="<?php echo isset($value->suhu)          ? $value->suhu          : ''; ?>"></td>
                </tr>
              </tbody>
            </table>
            <span class="rm-flabel" style="margin-top:10px;">Pemeriksaan Fisik</span>
            <div class="rm-fval"><?php echo isset($value->objective) ? nl2br($value->objective) : '<span style="color:#94a3b8">—</span>'; ?></div>
          </div>

          <!-- A: Assessment -->
          <div class="rm-section rm-section-a">
            <div class="rm-section-title"><i class="fa fa-flask"></i> A &mdash; Assessment</div>
            <span class="rm-flabel">Diagnosa Primer (ICD-10)</span>
            <div class="rm-fval">
              <?php
                $kode_icd = isset($value->kode_icd_diagnosa) ? $value->kode_icd_diagnosa : '';
                $diagnosa  = isset($value->assesment) ? $value->assesment : '';
                echo $kode_icd ? '<strong>'.$kode_icd.'</strong> &mdash; '.$diagnosa : '<span style="color:#94a3b8">—</span>';
              ?>
            </div>

            <span class="rm-flabel" style="margin-top:8px;">Diagnosa Sekunder</span>
            <div style="background:#fff; border:1px solid #ddd8fe; border-radius:5px; padding:6px 8px; min-height:28px; line-height:24px;">
              <?php
                $arr_text = isset($value->diagnosa_sekunder) ? explode('|', $value->diagnosa_sekunder) : [];
                $no_ds = 1;
                $has_ds = false;
                foreach ($arr_text as $k => $v) {
                  if(strlen(trim($v)) > 0){
                    $no_ds++; $has_ds = true;
                    $split = explode(':', $v);
                    $icd_id = (count($split) > 1) ? trim(str_replace('.','_',$split[0])) : $no_ds;
                    echo '<span class="multi-typeahead" id="txt_icd_'.$icd_id.'"><a href="#" style="padding:3px;text-align:center"><i class="fa fa-times black"></i></a><span style="display:none">|</span><span class="text_icd_10"> '.$v.' </span></span>';
                  }
                }
                if(!$has_ds) echo '<span style="color:#94a3b8; font-size:12px;">Tidak ada diagnosa sekunder</span>';
              ?>
            </div>

            <span class="rm-flabel" style="margin-top:8px;">Prosedur / Tindakan (ICD-9)</span>
            <div class="rm-fval">
              <?php
                $kode9 = isset($value->kode_icd9) ? $value->kode_icd9 : '';
                $text9 = isset($value->text_icd9) ? $value->text_icd9 : '';
                echo $kode9 ? '<strong>'.$kode9.'</strong> &mdash; '.$text9 : '<span style="color:#94a3b8">—</span>';
              ?>
            </div>
          </div>

          <!-- P: Planning -->
          <div class="rm-section rm-section-p">
            <div class="rm-section-title"><i class="fa fa-list-ul"></i> P &mdash; Planning</div>
            <span class="rm-flabel">Rencana Asuhan / Anjuran Dokter</span>
            <div class="rm-fval"><?php echo isset($value->planning) ? nl2br($value->planning) : '<span style="color:#94a3b8">—</span>'; ?></div>
            <span class="rm-flabel" style="margin-top:8px;">Resep Dokter</span>
            <div class="rm-fval"><?php echo isset($value->resep_farmasi) ? nl2br($value->resep_farmasi) : '<span style="color:#94a3b8">—</span>'; ?></div>
            <span class="rm-flabel" style="margin-top:8px;">Tanggal Kontrol Kembali</span>
            <div class="rm-fval">
              <?php
                $tgl_kontrol = isset($value->tgl_kontrol_kembali) ? $this->tanggal->formatDate($value->tgl_kontrol_kembali) : '';
                $cat_kontrol = isset($value->catatan_kontrol_kembali) ? $value->catatan_kontrol_kembali : '';
                echo $tgl_kontrol ? $tgl_kontrol.($cat_kontrol ? ' &mdash; '.$cat_kontrol : '') : '<span style="color:#94a3b8">—</span>';
              ?>
            </div>
          </div>

          <!-- e-Resep -->
          <div class="rm-section rm-section-r">
            <div class="rm-section-title"><i class="fa fa-pills" style="display:none"></i><i class="fa fa-medkit"></i> e-Resep &mdash; Obat yang Diresepkan</div>
            <?php
              $eresep_result = isset($eresep[$value->no_registrasi][$value->no_kunjungan]) ? $eresep[$value->no_registrasi][$value->no_kunjungan] : array();
              if(count($eresep_result) > 0):
                foreach($eresep_result as $key_er => $val_er):
            ?>
              <div style="font-size:11px; color:#92400e; margin-bottom:4px;">
                <i class="fa fa-clock-o"></i> Tanggal resep: <em><?php echo $this->tanggal->formatDateTime($val_er[0]->created_date); ?></em>
              </div>
              <table class="rm-resep-table">
                <thead>
                  <tr>
                    <th width="32px">No</th>
                    <th>Nama Obat &amp; Aturan Pakai</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $no = 0;
                    foreach ($val_er as $ker => $ver):
                      $no++;
                      $child_racikan = $this->master->get_child_racikan_data($ver->kode_pesan_resep, $ver->kode_brg);
                      $html_racikan  = ($child_racikan != '') ? '<div style="padding:6px 10px; margin-top:4px; background:#fff8e7; border-radius:4px; font-size:11px; font-style:italic; color:#92400e;">Bahan racik:<br>'.$child_racikan.'</div>' : '';
                  ?>
                    <tr>
                      <td align="center" valign="top" style="color:#94a3b8;"><?php echo $no; ?></td>
                      <td>
                        <strong style="font-size:12.5px;"><?php echo strtoupper($ver->nama_brg); ?></strong>
                        <?php echo $html_racikan; ?>
                        <div style="color:#475569; margin-top:3px; font-size:11.5px;">
                          <?php echo $ver->jml_dosis; ?> &times; <?php echo $ver->jml_dosis_obat; ?> <?php echo $ver->satuan_obat; ?> &mdash; <?php echo $ver->aturan_pakai; ?>
                        </div>
                        <div style="color:#64748b; font-size:11px;">Qty: <?php echo $ver->jml_pesan; ?> <?php echo $ver->satuan_obat; ?><?php echo $ver->keterangan ? ' &mdash; '.$ver->keterangan : ''; ?></div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  <tr>
                    <td colspan="2" style="padding:8px; text-align:center; background:#fffbeb;">
                      <a href="#" class="rm-btn rm-btn-blue" onclick="resepkan_ulang(<?php echo $ver->kode_pesan_resep; ?>)">
                        <i class="fa fa-repeat"></i> Resepkan Kembali
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            <?php endforeach; else: ?>
              <span style="color:#94a3b8; font-size:12px;">Belum ada e-Resep pada kunjungan ini</span>
            <?php endif; ?>
          </div>

          <!-- File Pengkajian -->
          <div class="rm-section rm-section-f">
            <div class="rm-section-title"><i class="fa fa-folder-open-o"></i> File Pengkajian Pasien</div>
            <?php echo $html_file; ?>
          </div>

          <!-- File Upload -->
          <div class="rm-section rm-section-f" style="margin-bottom:0;">
            <div class="rm-section-title"><i class="fa fa-upload"></i> File Rekam Medis Upload</div>
            <?php echo $html_file_rm; ?>
          </div>

        </div><!-- /.rm-card-body -->
      </div><!-- /.panel-collapse -->

    </div><!-- /.rm-card -->
    <?php endforeach;
    else: ?>
      <div class="rm-empty">
        <i class="fa fa-info-circle" style="margin-right:6px;"></i>
        <strong>Pasien Baru</strong> &mdash; Belum ada riwayat medis sebelumnya.
      </div>
    <?php endif; ?>
  </div><!-- /#accordion -->

</div><!-- /#rm-wrap -->