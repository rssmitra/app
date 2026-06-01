<script style="text/javascript">

  function resepkan_ulang(id){
      preventDefault();
      // Show loading and open preview modal
      $('#ru-body-load').show();
      $('#ru-body-content').hide().empty();
      $('#btn-ru-soap, #btn-ru-eresep').prop('disabled', false);
      $('#btn-ru-soap').html('<i class="fa fa-file-text-o"></i> Masukan ke SOAP Dokter');
      $('#btn-ru-eresep').html('<i class="fa fa-medkit"></i> Order e-Resep');
      $('#modal-resepkan-ulang').modal('show');
      $('#modal-resepkan-ulang').data('kode_pesan_resep', id);

      $.ajax({
          url: 'pelayanan/Pl_pelayanan/get_resep_drugs/' + id,
          type: 'GET',
          dataType: 'json',
          cache: false,
          success: function(resp){
              $('#ru-body-load').hide();
              if(resp && resp.status === 200 && resp.data && resp.data.length > 0){
                  ruRenderDrugs(resp.data, resp.hdr || {});
                  $('#ru-body-content').show();
              } else {
                  $('#ru-body-content').html('<div style="padding:28px;text-align:center;color:#94a3b8;"><i class="fa fa-inbox" style="font-size:26px;display:block;margin-bottom:8px;"></i>Tidak ada data obat pada resep ini.</div>').show();
              }
          },
          error: function(){
              $('#ru-body-load').hide();
              $('#ru-body-content').html('<div style="padding:28px;text-align:center;color:#dc2626;"><i class="fa fa-exclamation-triangle"></i> Gagal memuat data resep.</div>').show();
          }
      });
  }

  function ruRenderDrugs(drugs, hdr){
      hdr = hdr || {};
      var sh = function(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); };

      // Only show parent items (parent = '0' or empty/null)
      var parentDrugs = [];
      $.each(drugs, function(i,d){
          if(!d.parent || d.parent === '0' || d.parent === 0){ parentDrugs.push(d); }
      });

      var hasRacikan = false;
      $.each(parentDrugs, function(i,d){ if(d.tipe_obat === 'racikan') hasRacikan = true; });

      var jenisResep = hdr.jenis_resep || 'non_prb';
      var resepIter  = String(hdr.resep_iter  || '0');
      var ketResep   = hdr.keterangan   || '';

      var html = '';

      // ── Resep header settings (mirrors the rsb-wrap section in modal-resep-dokter) ──
      html += '<div class="rsb-wrap" style="margin-bottom:12px;">';

      // Jenis Resep
      html += '<div class="rsb-group">';
      html += '<span class="rsb-label">Jenis Resep</span>';
      html += '<div class="rsb-radios">';
      html += '<label class="rsb-radio-opt"><input type="radio" name="ru_jenis_resep" value="non_prb"'+(jenisResep!=='prb'?' checked':'')+'>Non PRB</label>';
      html += '<label class="rsb-radio-opt"><input type="radio" name="ru_jenis_resep" value="prb"'+(jenisResep==='prb'?' checked':'')+'>PRB</label>';
      html += '</div></div>';

      // Resep Iter
      var isPrb      = (jenisResep === 'prb');
      var iterDis    = isPrb ? ' disabled' : '';
      var iterVal    = isPrb ? '0' : resepIter;
      html += '<div class="rsb-group">';
      html += '<span class="rsb-label">Resep Iter</span>';
      html += '<div class="rsb-radios">';
      html += '<label class="rsb-radio-opt"><input type="radio" name="ru_resep_iter" value="0"'+(iterVal==='0'?' checked':'')+iterDis+'>Tidak</label>';
      html += '<label class="rsb-radio-opt"><input type="radio" name="ru_resep_iter" value="1"'+(iterVal==='1'?' checked':'')+iterDis+'>1x</label>';
      html += '<label class="rsb-radio-opt"><input type="radio" name="ru_resep_iter" value="2"'+(iterVal==='2'?' checked':'')+iterDis+'>2x</label>';
      html += '</div></div>';

      // Keterangan
      html += '<div class="rsb-group rsb-ket">';
      html += '<span class="rsb-label">Keterangan Resep</span>';
      html += '<input type="text" id="ru-ket-resep" placeholder="Keterangan (opsional)" value="'+sh(ketResep)+'">';
      html += '</div>';

      html += '</div>'; // /.rsb-wrap

      // Info bar
      html += '<div class="ru-infobar">';
      html += '<i class="fa fa-pencil" style="margin-right:5px;"></i>Verifikasi atau edit data obat sebelum memproses.';
      if(hasRacikan){
          html += '<br><span style="color:#0369a1;"><i class="fa fa-info-circle" style="margin-right:3px;"></i>Obat <strong>racikan</strong> akan disertakan beserta seluruh komposisinya.</span>';
      }
      html += '</div>';

      if(parentDrugs.length === 0){
          html += '<tr><td colspan="4" class="resep-empty-msg">Tidak ada data obat</td></tr>';
      }

      // ── Drug table — mirroring #resep-drug-table from form_diagnosa_dr ──
      var inp = 'border:1px solid #d1d5db;border-radius:4px;padding:3px 5px;font-size:12px;background:#fff;outline:none;';

      html += '<table id="ru-preview-table">';
      html += '<thead><tr>';
      html += '<th>Nama Obat</th>';
      html += '<th>Signa &amp; Aturan Pakai</th>';
      html += '<th style="width:68px;text-align:center;">Jml Total</th>';
      html += '<th style="width:115px;">Keterangan</th>';
      html += '</tr></thead><tbody>';

      $.each(parentDrugs, function(i,d){
          var isRacikan = (d.tipe_obat === 'racikan');

          if(isRacikan){
              // ── RACIKAN ROW (readonly, same as racikan-hdr-row) ──
              html += '<tr class="racikan-hdr-row">';

              // Nama Obat + bahan wrap
              html += '<td>';
              html += '<strong style="font-size:12.5px;">'+sh(d.nama_brg)+'</strong>';
              html += ' <span style="background:#fef3c7;color:#b45309;font-size:10px;font-weight:700;padding:1px 5px;border-radius:3px;">RACIKAN</span>';
              html += '<div class="racikan-bahan-wrap">';
              html += '<div class="rb-label">bahan racik :</div>';
              if(d.children && d.children.length > 0){
                  $.each(d.children, function(j,c){
                      html += '<span class="rb-item"><i class="fa fa-circle" style="font-size:5px;vertical-align:middle;margin-right:4px;color:#94a3b8;"></i>'+sh(c.nama_brg)+'&nbsp;&nbsp;('+sh(c.jml_pesan)+' '+sh(c.satuan_obat)+')</span><br>';
                  });
              } else {
                  html += '<div class="racikan-bahan-empty">Belum ada bahan obat</div>';
              }
              html += '</div>';
              html += '<input type="hidden" class="ru-tipe-obat" value="racikan">';
              html += '</td>';

              // Signa (display only)
              html += '<td style="vertical-align:top;padding-top:9px;">';
              html += '<span style="font-size:12.5px;font-weight:600;color:#1e293b;">'+sh(d.jml_dosis)+'</span>';
              html += '<span style="color:#94a3b8;margin:0 3px;">&times;</span>';
              html += '<span style="font-size:12.5px;font-weight:600;color:#1e293b;">'+sh(d.jml_dosis_obat)+'</span>';
              html += ' <span style="color:#64748b;font-size:12px;">'+sh(d.satuan_obat)+'</span>';
              html += '<br><span style="color:#475569;font-size:11.5px;">'+sh(d.aturan_pakai)+'</span>';
              html += '</td>';

              // Jml (display only)
              html += '<td style="text-align:center;vertical-align:top;padding-top:9px;">';
              html += '<span style="font-size:13px;font-weight:700;color:#1e293b;">'+sh(d.jml_pesan)+'</span>';
              html += '<div style="font-size:10px;color:#94a3b8;">'+sh(d.satuan_obat)+'</div>';
              html += '</td>';

              // Keterangan (display only)
              html += '<td style="vertical-align:top;padding-top:9px;color:#64748b;font-size:12px;">'+(d.keterangan ? sh(d.keterangan) : '<span style="color:#cbd5e0;">—</span>')+'</td>';

              html += '</tr>';

          } else {
              // ── NON-RACIKAN ROW (editable) ──
              html += '<tr>';

              // Nama Obat
              html += '<td>';
              html += '<strong style="font-size:12.5px;">'+sh(d.nama_brg)+'</strong>';
              html += '<input type="hidden" class="ru-kode-brg"    value="'+sh(d.kode_brg)+'">';
              html += '<input type="hidden" class="ru-nama-brg"    value="'+sh(d.nama_brg)+'">';
              html += '<input type="hidden" class="ru-satuan-obat"  value="'+sh(d.satuan_obat)+'">';
              html += '<input type="hidden" class="ru-tipe-obat"   value="non_racikan">';
              html += '</td>';

              // Signa — editable inline (same feel as drug table signa column)
              html += '<td>';
              html += '<div style="display:flex;align-items:center;gap:4px;">';
              html += '<input type="text" class="ru-jml-dosis" value="'+sh(d.jml_dosis||'1')+'" title="Jumlah Dosis (DD)" style="'+inp+'width:40px;text-align:center;">';
              html += '<span style="color:#64748b;font-weight:600;font-size:13px;">&times;</span>';
              html += '<input type="text" class="ru-jml-dosis-obat" value="'+sh(d.jml_dosis_obat||'1')+'" title="Jumlah per Dosis" style="'+inp+'width:40px;text-align:center;">';
              html += '<span style="color:#475569;font-size:12px;">'+sh(d.satuan_obat)+'</span>';
              html += '</div>';
              html += '<div style="margin-top:5px;">';
              html += '<input type="text" class="ru-aturan-pakai" value="'+sh(d.aturan_pakai||'')+'" placeholder="Aturan pakai" title="Aturan Pakai" style="'+inp+'width:100%;">';
              html += '</div>';
              html += '</td>';

              // Jml Total
              html += '<td style="text-align:center;vertical-align:middle;">';
              html += '<input type="number" class="ru-jml-pesan" value="'+sh(d.jml_pesan||'1')+'" min="1" title="Jumlah Total" style="'+inp+'width:56px;text-align:center;">';
              html += '<div style="font-size:10px;color:#94a3b8;margin-top:2px;">'+sh(d.satuan_obat)+'</div>';
              html += '</td>';

              // Keterangan
              html += '<td style="vertical-align:middle;">';
              html += '<input type="text" class="ru-keterangan" value="'+sh(d.keterangan||'')+'" placeholder="Ket…" style="'+inp+'width:100%;">';
              html += '</td>';

              html += '</tr>';
          }
      });

      html += '</tbody></table>';
      $('#ru-body-content').html(html);

      // PRB → disable iter; Non PRB → enable iter
      $(document).off('change.ruJenisResep').on('change.ruJenisResep', 'input[name="ru_jenis_resep"]', function(){
          var $iterInputs = $('input[name="ru_resep_iter"]');
          if($(this).val() === 'prb'){
              $iterInputs.prop('disabled', true);
              $iterInputs.filter('[value="0"]').prop('checked', true);
          } else {
              $iterInputs.prop('disabled', false);
          }
      });

      $('#modal-resepkan-ulang').data('parent-drugs', parentDrugs);
  }

  // ── Path 1: Masukan ke SOAP Dokter ──────────────────────────────
  function doResepkanUlangSoap(){
      if(typeof ensureResepHeader !== 'function'){
          $.achtung({message:'<i class="fa fa-exclamation-triangle"></i> Fitur ini hanya tersedia pada halaman SOAP dokter.', timeout:5, className:'achtungFail'});
          return;
      }

      // Collect edited non-racikan drugs from table
      var editedDrugs = [];
      $('#ru-preview-table tbody tr').each(function(){
          var $r = $(this);
          if($r.find('.ru-tipe-obat').val() === 'racikan') return;
          editedDrugs.push({
              kode_brg:       $r.find('.ru-kode-brg').val(),
              nama_brg:       $r.find('.ru-nama-brg').val(),
              satuan_obat:    $r.find('.ru-satuan-obat').val(),
              jml_dosis:      $r.find('.ru-jml-dosis').val()       || '1',
              jml_dosis_obat: $r.find('.ru-jml-dosis-obat').val()  || '1',
              aturan_pakai:   $r.find('.ru-aturan-pakai').val()    || 'Sesudah Makan',
              jml_pesan:      $r.find('.ru-jml-pesan').val()       || '1',
              keterangan:     $r.find('.ru-keterangan').val()      || ''
          });
      });

      // Collect racikan drugs (with children) from stored parent-drugs data
      var allParentDrugs = $('#modal-resepkan-ulang').data('parent-drugs') || [];
      var racikanList = [];
      $.each(allParentDrugs, function(i, d){
          if(d.tipe_obat === 'racikan') racikanList.push(d);
      });

      if(editedDrugs.length === 0 && racikanList.length === 0){
          $.achtung({message:'<i class="fa fa-info-circle"></i> Tidak ada obat untuk diproses.', timeout:7});
          return;
      }

      $('#btn-ru-soap').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
      $('#btn-ru-eresep').prop('disabled', true);

      var noReg  = $('#no_registrasi').val()    || (typeof _resepNoReg   !== 'undefined' ? _resepNoReg   : '');
      var noKunj = (typeof _resepNoKunj !== 'undefined' ? _resepNoKunj : '') || $('#no_kunjungan').val();
      var noMr   = (typeof _resepNoMr   !== 'undefined' ? _resepNoMr   : '') || $('#noMrHidden').val();

      // Sync resep header settings from modal → SOAP form radios so ensureResepHeader picks them up
      var ruJenis = $('input[name="ru_jenis_resep"]:checked').val() || 'non_prb';
      var ruIter  = $('input[name="ru_resep_iter"]:checked').val()  || '0';
      var ruKet   = $('#ru-ket-resep').val() || '';
      $('input[name="resep_jenis_m"][value="'+ruJenis+'"]').prop('checked', true);
      $('input[name="resep_iter_m"][value="'+ruIter+'"]').prop('checked', true);
      $('#resep_ket_modal').val(ruKet);

      ensureResepHeader(function(kodePesan){
          if(!kodePesan){
              $.achtung({message:'Gagal membuat header resep', timeout:5, className:'achtungFail'});
              $('#btn-ru-soap').prop('disabled', false).html('<i class="fa fa-file-text-o"></i> Masukan ke SOAP Dokter');
              $('#btn-ru-eresep').prop('disabled', false);
              return;
          }

          var totalAdded = 0;

          // Step 1: insert non-racikan drugs one by one
          function addNonRacikanNext(idx){
              if(idx >= editedDrugs.length){
                  addRacikanNext(0);
                  return;
              }
              var d = editedDrugs[idx];
              $.ajax({
                  url: 'farmasi/E_resep/add_resep_obat',
                  type: 'POST',
                  dataType: 'json',
                  data:{
                      submit:                'non_racikan',
                      id_template:           0,
                      id_pesan_resep_detail: 0,
                      kode_pesan_resep:      kodePesan,
                      no_registrasi:         noReg,
                      no_kunjungan:          noKunj,
                      kode_brg:              d.kode_brg,
                      nama_brg:              d.nama_brg,
                      jml_dosis:             d.jml_dosis,
                      jml_dosis_obat:        d.jml_dosis_obat,
                      satuan_obat:           d.satuan_obat,
                      aturan_pakai:          d.aturan_pakai,
                      jml_hari:              '0',
                      jml_pesan:             d.jml_pesan,
                      no_mr:                 noMr,
                      keterangan:            d.keterangan,
                      tipe_obat:             'non_racikan',
                      parent:                '0'
                  },
                  success: function(res){ if(res && res.status===200) totalAdded++; addNonRacikanNext(idx+1); },
                  error:   function(){ addNonRacikanNext(idx+1); }
              });
          }

          // Step 2: insert racikan header, then its children
          function addRacikanNext(idx){
              if(idx >= racikanList.length){
                  // All done — refresh SOAP resep panel and close modal
                  if(typeof loadResepDrugsModal === 'function') loadResepDrugsModal();
                  $('#modal-resepkan-ulang').modal('hide');
                  var msg = totalAdded + ' obat berhasil ditambahkan ke SOAP dokter.';
                  if(typeof Swal !== 'undefined'){
                      Swal.fire({icon:'success', title:'Berhasil!', html: msg, timer:2500, showConfirmButton:false});
                  } else {
                      $.achtung({message: msg, timeout:5});
                  }
                  $('#btn-ru-soap').prop('disabled', false).html('<i class="fa fa-file-text-o"></i> Masukan ke SOAP Dokter');
                  $('#btn-ru-eresep').prop('disabled', false);
                  return;
              }

              var rp = racikanList[idx];
              // Insert racikan header first; backend generates a new R-code as kode_brg
              $.ajax({
                  url: 'farmasi/E_resep/add_resep_obat',
                  type: 'POST',
                  dataType: 'json',
                  data:{
                      submit:                'header',
                      id_template:           0,
                      id_pesan_resep_detail: 0,
                      kode_pesan_resep:      kodePesan,
                      no_registrasi:         noReg,
                      no_kunjungan:          noKunj,
                      kode_brg:              rp.kode_brg,
                      nama_brg:              rp.nama_brg,
                      jml_dosis:             rp.jml_dosis      || '1',
                      jml_dosis_obat:        rp.jml_dosis_obat || '1',
                      satuan_obat:           rp.satuan_obat    || '',
                      aturan_pakai:          rp.aturan_pakai   || '',
                      jml_hari:              rp.jml_hari       || '0',
                      jml_pesan:             rp.jml_pesan      || '1',
                      no_mr:                 noMr,
                      keterangan:            rp.keterangan     || '',
                      tipe_obat:             'racikan',
                      tipe_racik:            rp.tipe_racik     || '0',
                      parent:                '0'
                  },
                  success: function(res){
                      if(res && res.status === 200){
                          totalAdded++;
                          // res.parent contains the new R-code assigned to this racikan header
                          addRacikanChildren(idx, 0, res.parent, rp.children || []);
                      } else {
                          addRacikanNext(idx+1);
                      }
                  },
                  error: function(){ addRacikanNext(idx+1); }
              });
          }

          // Step 2b: insert each child of a racikan using the new parent kode_brg
          function addRacikanChildren(racikanIdx, childIdx, newParentKode, children){
              if(childIdx >= children.length){
                  addRacikanNext(racikanIdx+1);
                  return;
              }
              var c = children[childIdx];
              $.ajax({
                  url: 'farmasi/E_resep/add_resep_obat',
                  type: 'POST',
                  dataType: 'json',
                  data:{
                      submit:                'komposisi',
                      id_template:           0,
                      id_pesan_resep_detail: 0,
                      kode_pesan_resep:      kodePesan,
                      no_registrasi:         noReg,
                      no_kunjungan:          noKunj,
                      kode_brg:              c.kode_brg,
                      nama_brg:              c.nama_brg,
                      jml_dosis:             c.jml_dosis      || '0',
                      jml_dosis_obat:        c.jml_dosis_obat || '0',
                      satuan_obat:           c.satuan_obat    || '',
                      aturan_pakai:          c.aturan_pakai   || '',
                      jml_hari:              c.jml_hari       || '0',
                      jml_pesan:             c.jml_pesan      || '1',
                      no_mr:                 noMr,
                      keterangan:            c.keterangan     || '',
                      tipe_obat:             'racikan',
                      tipe_racik:            '0',
                      parent:                newParentKode
                  },
                  success: function(){ addRacikanChildren(racikanIdx, childIdx+1, newParentKode, children); },
                  error:   function(){ addRacikanChildren(racikanIdx, childIdx+1, newParentKode, children); }
              });
          }

          addNonRacikanNext(0);
      });
  }

  // ── Path 2: Order e-Resep ────────────────────────────────────────
  function doResepkanUlangEresep(){
      var kode_pesan_resep = $('#modal-resepkan-ulang').data('kode_pesan_resep');
      if(!kode_pesan_resep){ return; }

      $('#btn-ru-eresep').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
      $('#btn-ru-soap').prop('disabled', true);

      var formData = {
          kode_pesan_resep : kode_pesan_resep,
          no_registrasi    : $('#no_registrasi').val(),
          no_kunjungan     : $('#no_kunjungan').val(),
          no_mr            : $('#noMrHidden').val(),
          kode_kelompok    : $('#kode_kelompok').val(),
          kode_perusahaan  : $('#kode_perusahaan_val').val(),
          kode_klas        : $('#kode_klas').val(),
          kode_profit      : $('#kode_profit').val(),
          kode_bagian_asal : $('#kode_bagian_asal').val(),
          kode_dokter      : $('#kode_dokter_poli').val()
      };

      $.ajax({
          url: 'farmasi/E_resep/proses_resepkan_ulang',
          data: formData,
          dataType: 'json',
          type: 'POST',
          success: function(response){
              $('#modal-resepkan-ulang').modal('hide');
              $('.nav-list li').removeClass('active');
              $('li#li_tabs_farmasi').addClass('active');
              getMenuTabs(
                  'farmasi/Farmasi_pesan_resep/pesan_resep/'+$('#no_kunjungan').val()+'/'+$('#kode_klas').val()+'/'+$('#kode_profit').val(),
                  'tabs_form_pelayanan'
              );
          },
          error: function(){
              $.achtung({message:'<i class="fa fa-exclamation-triangle"></i> Gagal memproses e-Resep', timeout:5, className:'achtungFail'});
          },
          complete: function(){
              $('#btn-ru-eresep').prop('disabled', false).html('<i class="fa fa-medkit"></i> Order e-Resep');
              $('#btn-ru-soap').prop('disabled', false);
          }
      });
  }

  function copy_soap(id){
      preventDefault();
      if(confirm('Apakah anda yakin akan menyalin SOAP ini?')){
          var formData = { kode_riwayat: id };
          $.ajax({
              url: "pelayanan/Pl_pelayanan/copy_soap",
              data: formData,
              dataType: "json",
              type: "POST",
              success: function(response) {
                  var obj = response.result;
                  console.log(obj);

                  // ── S : Anamnesa ──
                  $('#pl_anamnesa').val(obj.anamnesa || '');

                  // ── S : Riwayat Penyakit Dahulu ──
                  var rpd = obj.riwayat_penyakit_dahulu || 'tidak';
                  $('input[name="riwayat_penyakit_dahulu"]').filter('[value="'+rpd+'"]').prop('checked', true);
                  $('#riwayat_penyakit_dahulu_ket').val(obj.riwayat_penyakit_dahulu_ket || '');
                  if (typeof toggleRiwayat === 'function') toggleRiwayat('riwayat_penyakit_dahulu_txt', rpd);

                  // ── S : Riwayat Operasi ──
                  var ro = obj.riwayat_operasi || 'tidak';
                  $('input[name="riwayat_operasi"]').filter('[value="'+ro+'"]').prop('checked', true);
                  $('#riwayat_operasi_ket').val(obj.riwayat_operasi_ket || '');
                  if (typeof toggleRiwayat === 'function') toggleRiwayat('riwayat_operasi_txt', ro);

                  // ── S : Riwayat Alergi ──
                  var ra = obj.riwayat_alergi || 'tidak';
                  $('input[name="riwayat_alergi"]').filter('[value="'+ra+'"]').prop('checked', true);
                  $('#riwayat_alergi_ket').val(obj.riwayat_alergi_ket || '');
                  if (typeof toggleRiwayat === 'function') toggleRiwayat('riwayat_alergi_txt', ra);

                  // ── O : Tanda Vital ──
                  // $('#pl_dr_tb').val(obj.tinggi_badan   || '');
                  // $('#pl_dr_bb').val(obj.berat_badan    || '');
                  // $('#pl_dr_td').val(obj.tekanan_darah  || '');
                  // $('#pl_dr_nadi').val(obj.nadi         || '');
                  // $('#pl_dr_suhu').val(obj.suhu         || '');

                  // ── O : Pemeriksaan Fisik ──
                  $('#pl_pemeriksaan').val(obj.pemeriksaan || '');

                  // ── A : Diagnosa Primer ──
                  $('#pl_diagnosa').val(obj.diagnosa_akhir   || '');
                  $('#pl_diagnosa_hidden').val(obj.kode_icd_diagnosa || '');

                  // ── A : Diagnosa Sekunder ──
                  var ds = obj.diagnosa_sekunder || '';
                  var diagnosa_sekunder = ds.split('|');
                  var string = '';
                  for (var i = 1; i < diagnosa_sekunder.length; i++) {
                    if (diagnosa_sekunder[i] !== '') {
                      string += '<span class="multi-typeahead" id="txt_icd_'+i+'">'
                        + '<a href="#" onclick="remove_icd(\''+i+'\')" style="padding:3px;text-align:center"><i class="fa fa-times black"></i> </a>'
                        + '<span style="display:none">|</span>'
                        + '<span class="text_icd_10"> '+diagnosa_sekunder[i]+' </span>'
                        + '</span>';
                    }
                  }
                  $('#pl_diagnosa_sekunder_hidden_txt').html(string);
                  $('#konten_diagnosa_sekunder').val(ds);

                  // ── A : Prosedur / Tindakan (ICD-9) ──
                  $('#pl_procedure').val(obj.text_icd9 || '');
                  $('#pl_procedure_hidden').val(obj.kode_icd9 || '');

                  // ── A : Catatan Assesmen ──
                  $('#pl_catatan_assesmen').val(obj.catatan_assesmen || '');

                  // ── P : Rencana Asuhan / Pengobatan ──
                  var pengobatan = (obj.pengobatan || '');
                  if (obj.resep_farmasi) pengobatan += '\n' + obj.resep_farmasi;
                  $('#pl_pengobatan').val(pengobatan.trim());

                  // ── P : Kontrol Kembali ──
                  $('#pl_tgl_kontrol_kembali').val(obj.tgl_kontrol_kembali      || '');
                  $('#pl_catatan_kontrol').val(obj.catatan_kontrol_kembali || '');
              }
          });
      } else {
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
  .rm-ttv-grid {
    display: flex;
    gap: 6px;
    margin-top: 6px;
    flex-wrap: wrap;
  }
  .rm-ttv-item {
    flex: 1 1 0;
    min-width: 60px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 8px 6px;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 2px;
  }
  .rm-ttv-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: #0891b2;
  }
  .rm-ttv-value {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.2;
  }
  .rm-ttv-unit {
    font-size: 9.5px;
    color: #94a3b8;
  }

  /* ── Riwayat info items (Subjective) ── */
  .rm-riwayat-list {
    margin-top: 8px;
    display: flex;
    flex-direction: column;
    gap: 5px;
  }
  .rm-riwayat-item {
    display: flex;
    align-items: flex-start;
    gap: 7px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 7px 10px;
    font-size: 12px;
    line-height: 1.45;
  }
  .rm-riwayat-item i {
    flex-shrink: 0;
    margin-top: 2px;
    font-size: 12px;
  }
  .rm-riwayat-item .rm-rw-label {
    font-weight: 700;
    color: #334155;
    white-space: nowrap;
  }
  .rm-riwayat-item .rm-rw-val {
    color: #1e293b;
  }
  .rm-riwayat-item .rm-rw-val.ada   { color: #dc2626; font-weight: 600; }
  .rm-riwayat-item .rm-rw-val.tidak { color: #16a34a; }
  .rm-riwayat-item .rm-rw-ket {
    color: #64748b;
    font-size: 11.5px;
    font-style: italic;
  }

  /* ── Status Lokalis icon ── */
  .rm-lokalis-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    background: linear-gradient(135deg, #7c3aed, #a78bfa);
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .15s, transform .15s;
    margin-top: 8px;
  }
  .rm-lokalis-btn:hover { opacity: .85; transform: translateY(-1px); color: #fff; text-decoration: none; }
  .rm-lokalis-btn i { font-size: 13px; }

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

  /* ── Status Lokalis marker animation ── */
  @keyframes rm-pulse {
    0%   { transform: scale(1);   opacity: .5; }
    70%  { transform: scale(2.2); opacity: 0; }
    100% { transform: scale(1);   opacity: 0; }
  }
  .rm-lokalis-pin {
    position: absolute;
    transform: translate(-50%, -50%);
    z-index: 6;
    cursor: pointer;
  }
  .rm-lokalis-pin .pin-ring {
    position: absolute;
    inset: -5px;
    border-radius: 50%;
    animation: rm-pulse 1.8s ease-out infinite;
  }
  .rm-lokalis-pin .pin-dot {
    position: relative;
    width: 22px;
    height: 22px;
    border: 2.5px solid #fff;
    border-radius: 50%;
    box-shadow: 0 2px 6px rgba(0,0,0,.45);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 10px;
    font-weight: 800;
    line-height: 1;
    font-family: 'Segoe UI', system-ui, Arial, sans-serif;
  }
  .rm-lokalis-legend-item {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    padding: 7px 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    margin-bottom: 5px;
    font-size: 12.5px;
    line-height: 1.45;
    cursor: default;
    transition: background .15s;
  }
  .rm-lokalis-legend-item:hover { background: #f1f5f9; }
  .rm-lokalis-legend-num {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    color: #fff;
    font-size: 10px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
    font-family: 'Segoe UI', system-ui, Arial, sans-serif;
  }
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

            <?php
              $_rpd     = isset($value->rp_penyakit_dahulu)     ? strtolower(trim($value->rp_penyakit_dahulu)) : '';
              $_rpd_ket = isset($value->rp_penyakit_dahulu_ket) ? trim($value->rp_penyakit_dahulu_ket) : '';
              $_ro      = isset($value->rp_operasi)             ? strtolower(trim($value->rp_operasi)) : '';
              $_ro_ket  = isset($value->rp_operasi_ket)         ? trim($value->rp_operasi_ket) : '';
              $_ra      = isset($value->rp_alergi)              ? strtolower(trim($value->rp_alergi)) : '';
              $_ra_ket  = isset($value->rp_alergi_ket)          ? trim($value->rp_alergi_ket) : '';
              $_has_riwayat = ($_rpd || $_ro || $_ra);
            ?>
            <?php if($_has_riwayat): ?>
            <span class="rm-flabel" style="margin-top:10px;">Riwayat Pasien</span>
            <div class="rm-riwayat-list">
              <?php if($_rpd): ?>
              <div class="rm-riwayat-item">
                <i class="fa fa-history" style="color:#0ea5e9;"></i>
                <div>
                  <span class="rm-rw-label">Riwayat Penyakit Dahulu:</span>
                  <span class="rm-rw-val <?php echo $_rpd === 'ada' ? 'ada' : 'tidak'; ?>"><?php echo ucfirst($_rpd); ?></span>
                  <?php if($_rpd === 'ada' && $_rpd_ket): ?>
                    <div class="rm-rw-ket"><?php echo nl2br(htmlspecialchars($_rpd_ket)); ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <?php endif; ?>

              <?php if($_ro): ?>
              <div class="rm-riwayat-item">
                <i class="fa fa-medkit" style="color:#d97706;"></i>
                <div>
                  <span class="rm-rw-label">Riwayat Operasi:</span>
                  <span class="rm-rw-val <?php echo $_ro === 'ada' ? 'ada' : 'tidak'; ?>"><?php echo ucfirst($_ro); ?></span>
                  <?php if($_ro === 'ada' && $_ro_ket): ?>
                    <div class="rm-rw-ket"><?php echo nl2br(htmlspecialchars($_ro_ket)); ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <?php endif; ?>

              <?php if($_ra): ?>
              <div class="rm-riwayat-item">
                <i class="fa fa-exclamation-triangle" style="color:#dc2626;"></i>
                <div>
                  <span class="rm-rw-label">Riwayat Alergi:</span>
                  <span class="rm-rw-val <?php echo $_ra === 'ada' ? 'ada' : 'tidak'; ?>"><?php echo ucfirst($_ra); ?></span>
                  <?php if($_ra === 'ada' && $_ra_ket): ?>
                    <div class="rm-rw-ket"><?php echo nl2br(htmlspecialchars($_ra_ket)); ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <?php endif; ?>
            </div>
            <?php endif; ?>
          </div>

          <!-- O: Objective -->
          <div class="rm-section rm-section-o">
            <div class="rm-section-title"><i class="fa fa-stethoscope"></i> O &mdash; Objective</div>
            <span class="rm-flabel">Vital Sign</span>
            <div class="rm-ttv-grid">
              <div class="rm-ttv-item">
                <span class="rm-ttv-label">TB</span>
                <span class="rm-ttv-value"><?php echo isset($value->tinggi_badan) && $value->tinggi_badan ? $value->tinggi_badan : '—'; ?></span>
                <span class="rm-ttv-unit">cm</span>
              </div>
              <div class="rm-ttv-item">
                <span class="rm-ttv-label">BB</span>
                <span class="rm-ttv-value"><?php echo isset($value->berat_badan) && $value->berat_badan ? $value->berat_badan : '—'; ?></span>
                <span class="rm-ttv-unit">kg</span>
              </div>
              <div class="rm-ttv-item">
                <span class="rm-ttv-label">TD</span>
                <span class="rm-ttv-value"><?php echo isset($value->tekanan_darah) && $value->tekanan_darah ? $value->tekanan_darah : '—'; ?></span>
                <span class="rm-ttv-unit">mmHg</span>
              </div>
              <div class="rm-ttv-item">
                <span class="rm-ttv-label">Nadi</span>
                <span class="rm-ttv-value"><?php echo isset($value->nadi) && $value->nadi ? $value->nadi : '—'; ?></span>
                <span class="rm-ttv-unit">bpm</span>
              </div>
              <div class="rm-ttv-item">
                <span class="rm-ttv-label">Suhu</span>
                <span class="rm-ttv-value"><?php echo isset($value->suhu) && $value->suhu ? $value->suhu : '—'; ?></span>
                <span class="rm-ttv-unit">&deg;C</span>
              </div>
            </div>
            <span class="rm-flabel" style="margin-top:10px;">Pemeriksaan Fisik</span>
            <div class="rm-fval"><?php echo isset($value->objective) ? nl2br($value->objective) : '<span style="color:#94a3b8">—</span>'; ?></div>

            <?php
              $_has_lokalis = isset($value->rp_anatomi_tagging) && $value->rp_anatomi_tagging && $value->rp_anatomi_tagging !== '[]';
              $_lokalis_img = isset($value->rp_anatomi_img) ? $value->rp_anatomi_img : 0;
            ?>
            <?php if($_has_lokalis): ?>
            <a href="#" class="rm-lokalis-btn" data-tagging="<?php echo htmlspecialchars($value->rp_anatomi_tagging, ENT_QUOTES); ?>" data-img="<?php echo $_lokalis_img; ?>" onclick="showStatusLokalisModal(this.getAttribute('data-tagging'), this.getAttribute('data-img')); return false;">
              <i class="fa fa-map-marker"></i> Status Lokalis
            </a>
            <?php endif; ?>
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

<style>
/* ── Resepkan Ulang preview table — mirrors #resep-drug-table from form_diagnosa_dr ── */
#ru-preview-table { width:100%; border-collapse:collapse; font-size:12.5px; }
#ru-preview-table thead th { background:#0f172a; color:#fff; padding:7px 10px; font-weight:600; font-size:11.5px; text-align:left; }
#ru-preview-table tbody td { padding:6px 10px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
#ru-preview-table tbody tr:hover td { background:#f8fafc; }
#ru-preview-table .racikan-hdr-row td { background:#f8fffe; vertical-align:top; }
#ru-preview-table input[type="text"],
#ru-preview-table input[type="number"] { transition:border-color .15s, box-shadow .15s; }
#ru-preview-table input[type="text"]:focus,
#ru-preview-table input[type="number"]:focus { border-color:#2563eb !important; box-shadow:0 0 0 2px rgba(37,99,235,.15); }
.ru-infobar { font-size:12px; color:#1e40af; margin-bottom:10px; padding:7px 11px; background:#eff6ff; border-radius:6px; border:1px solid #bfdbfe; line-height:1.6; }
.rsb-radio-opt:has(input:disabled) { opacity:.4; cursor:not-allowed; pointer-events:none; }
</style>

<!-- ── Modal Resepkan Ulang ── -->
<div id="modal-resepkan-ulang" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" style="width:740px; max-width:96vw;">
    <div class="modal-content" style="border-radius:10px; overflow:hidden; box-shadow:0 10px 40px rgba(0,0,0,.25);">

      <!-- Header -->
      <div class="modal-header" style="background:linear-gradient(135deg,#1d4ed8,#2563eb); padding:12px 18px; border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal" style="color:#fff; opacity:.85; font-size:22px; margin-top:-3px; text-shadow:none;">&times;</button>
        <h4 class="modal-title" style="color:#fff; font-size:14px; font-weight:700; margin:0;">
          <i class="fa fa-repeat" style="margin-right:7px;"></i> Verifikasi Resep &mdash; Resepkan Kembali
        </h4>
      </div>

      <!-- Body -->
      <div class="modal-body" style="padding:16px; max-height:65vh; overflow-y:auto;">
        <div id="ru-body-load" style="text-align:center; padding:32px; color:#94a3b8;">
          <i class="fa fa-spinner fa-spin" style="font-size:22px;"></i>
          <div style="margin-top:10px; font-size:13px;">Memuat data resep&hellip;</div>
        </div>
        <div id="ru-body-content" style="display:none;"></div>
      </div>

      <!-- Footer -->
      <div class="modal-footer" style="background:#f8fafc; border-top:1px solid #e2e8f0; padding:12px 16px;">
        <div style="display:flex; gap:8px; flex-wrap:wrap; justify-content:flex-end; align-items:center;">
          <button type="button" class="btn btn-danger" data-dismiss="modal" style="font-size:12.5px; border-radius:6px; padding: 0px !important">
            <i class="fa fa-times" style="margin-right:4px;"></i> Batal
          </button>
          <button type="button" id="btn-ru-soap" onclick="doResepkanUlangSoap()"
            style="display:inline-flex; align-items:center; gap:5px; padding:7px 15px; background:linear-gradient(135deg,#059669,#10b981); color:#fff; border:none; border-radius:6px; font-size:12.5px; font-weight:600; cursor:pointer;">
            <i class="fa fa-file-text-o"></i> Masukan ke SOAP Dokter
          </button>
          <button type="button" id="btn-ru-eresep" onclick="doResepkanUlangEresep()"
            style="display:inline-flex; align-items:center; gap:5px; padding:7px 15px; background:linear-gradient(135deg,#1d4ed8,#2563eb); color:#fff; border:none; border-radius:6px; font-size:12.5px; font-weight:600; cursor:pointer;">
            <i class="fa fa-medkit"></i> Order e-Resep
          </button>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Status Lokalis Modal -->
<div id="rm-lokalis-modal" style="display:none; position:fixed; inset:0; z-index:10500; background:rgba(0,0,0,.55); justify-content:center; align-items:center;">
  <div style="background:#fff; border-radius:12px; max-width:600px; width:95%; max-height:90vh; overflow-y:auto; box-shadow:0 8px 30px rgba(0,0,0,.25); position:relative;">
    <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 18px; border-bottom:1px solid #e2e8f0; background:linear-gradient(135deg,#7c3aed,#a78bfa); border-radius:12px 12px 0 0;">
      <span style="color:#fff; font-weight:700; font-size:14px;"><i class="fa fa-map-marker"></i> Status Lokalis</span>
      <button type="button" onclick="$('#rm-lokalis-modal').hide();" style="background:none; border:none; color:#fff; font-size:20px; cursor:pointer; line-height:1;">&times;</button>
    </div>
    <div id="rm-lokalis-body" style="padding:16px; text-align:center;"></div>
  </div>
</div>

<script>
function showStatusLokalisModal(taggingJson, imgId) {
  var tags = [];
  try { tags = JSON.parse(taggingJson); } catch(e) {}
  if (!tags.length) return;

  var imgSrc = '<?php echo base_url("assets/img-tagging/images/"); ?>anatomi_' + (imgId || '0') + '.png';
  var $body  = $('#rm-lokalis-body');

  $body.html('<div style="padding:32px; text-align:center; color:#64748b;"><i class="fa fa-spinner fa-spin" style="font-size:22px;"></i><br><small style="margin-top:8px; display:block;">Memuat gambar…</small></div>');
  $('#rm-lokalis-modal').css('display', 'flex');

  var img = new Image();

  img.onload = function() {
    var natW = img.naturalWidth  || 1;
    var natH = img.naturalHeight || 1;

    /*
     * Koordinat tersimpan relatif terhadap gambar yang ditampilkan pada lebar 500px.
     * renderH = tinggi gambar saat lebar = 500px (proporsional).
     * Konversi ke persen: leftPct = x/500*100, topPct = y/renderH*100
     * sehingga marker tetap tepat pada skala berapapun modal ditampilkan.
     */
    var RW = 500;
    var RH = Math.round(natH / natW * RW);

    // ── Wadah gambar + overlay ──
    var html = '<div style="position:relative; display:block; margin-bottom:14px; line-height:0;">';
    html += '<img src="' + imgSrc + '" style="max-width:100%; height:auto; border-radius:8px; display:block; border:1px solid #e2e8f0;">';

    // SVG overlay – area markers (rect / polygon / freehand)
    var svgInner = '';
    for (var i = 0; i < tags.length; i++) {
      var t = tags[i];
      if (!t.type || t.type === 'point') continue;
      var c = t.color || '#7c3aed';
      if (t.type === 'rect' && t.points && t.points.length >= 2) {
        var x1 = Math.min(t.points[0].x, t.points[1].x);
        var y1 = Math.min(t.points[0].y, t.points[1].y);
        var rw = Math.abs(t.points[1].x - t.points[0].x);
        var rh = Math.abs(t.points[1].y - t.points[0].y);
        svgInner += '<rect x="'+x1+'" y="'+y1+'" width="'+rw+'" height="'+rh+'" fill="'+c+'" fill-opacity="0.18" stroke="'+c+'" stroke-width="1.8" rx="3"/>';
        if (t.label) svgInner += '<text x="'+(x1+4)+'" y="'+(y1-5)+'" fill="'+c+'" font-size="11" font-weight="700">'+t.label+'</text>';
      } else if (t.type === 'polygon' && t.points && t.points.length >= 2) {
        var pts = t.points.map(function(p){ return p.x+','+p.y; }).join(' ');
        svgInner += '<polygon points="'+pts+'" fill="'+c+'" fill-opacity="0.18" stroke="'+c+'" stroke-width="1.8"/>';
        if (t.label) svgInner += '<text x="'+t.points[0].x+'" y="'+(t.points[0].y-5)+'" fill="'+c+'" font-size="11" font-weight="700">'+t.label+'</text>';
      } else if (t.type === 'freehand' && t.points && t.points.length >= 2) {
        var d = 'M '+t.points[0].x+' '+t.points[0].y;
        for (var pi = 1; pi < t.points.length; pi++) d += ' L '+t.points[pi].x+' '+t.points[pi].y;
        svgInner += '<path d="'+d+'" fill="'+c+'" fill-opacity="0.18" stroke="'+c+'" stroke-width="1.8"/>';
      }
    }
    if (svgInner) {
      html += '<svg viewBox="0 0 '+RW+' '+RH+'" preserveAspectRatio="none" '
            + 'style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;" '
            + 'xmlns="http://www.w3.org/2000/svg">' + svgInner + '</svg>';
    }

    // Point markers – posisi persen dihitung dari koordinat tagging (basis 500px)
    var ptNum = 0;
    for (var i = 0; i < tags.length; i++) {
      var t = tags[i];
      if (t.type && t.type !== 'point') continue;
      if (t.x === undefined || t.y === undefined) continue;
      ptNum++;
      var color   = t.color || '#dc2626';
      var leftPct = (t.x / RW * 100).toFixed(3);
      var topPct  = (t.y / RH * 100).toFixed(3);

      html += '<div class="rm-lokalis-pin" style="left:'+leftPct+'%;top:'+topPct+'%;" title="'+(t.label||'')+'">';
      html += '<div class="pin-ring" style="background:'+color+';"></div>';
      html += '<div class="pin-dot"  style="background:'+color+';">'+ptNum+'</div>';
      html += '</div>';
    }
    html += '</div>';

    // ── Legenda ──
    html += '<div style="text-align:left;">';
    html += '<div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748b;margin-bottom:7px;">'
          + '<i class="fa fa-list-ul" style="margin-right:5px;"></i>Daftar Lokasi yang Ditandai</div>';

    var legNum  = 0;
    var hasItem = false;
    for (var j = 0; j < tags.length; j++) {
      var tag      = tags[j];
      var tagColor = tag.color || (tag.type === 'point' ? '#dc2626' : '#7c3aed');
      var tagNote  = tag.note || tag.keterangan || '';
      var isPoint  = (!tag.type || tag.type === 'point');
      if (!tag.label) continue;
      hasItem = true;

      if (isPoint) {
        legNum++;
        html += '<div class="rm-lokalis-legend-item">';
        html += '<div class="rm-lokalis-legend-num" style="background:'+tagColor+';">'+legNum+'</div>';
      } else {
        var areaIcon = tag.type === 'rect' ? 'fa-square-o' : 'fa-draw-polygon';
        html += '<div class="rm-lokalis-legend-item">';
        html += '<div class="rm-lokalis-legend-num" style="background:'+tagColor+'; border-radius:3px;"><i class="fa fa-object-group" style="font-size:9px;"></i></div>';
      }

      html += '<div style="flex:1;min-width:0;">';
      html += '<div style="color:#1e293b;font-weight:600;">' + tag.label + '</div>';
      if (tagNote) html += '<div style="color:#64748b;font-size:11.5px;margin-top:2px;font-style:italic;">' + tagNote + '</div>';
      html += '</div></div>';
    }

    if (!hasItem) {
      html += '<div style="color:#94a3b8;font-size:12px;padding:6px 0;">Tidak ada keterangan lokasi.</div>';
    }

    html += '</div>';
    $body.html(html);
  };

  img.onerror = function() {
    $body.html('<div style="padding:24px;text-align:center;color:#dc2626;"><i class="fa fa-exclamation-triangle" style="font-size:22px;margin-bottom:8px;display:block;"></i>Gambar anatomi tidak dapat dimuat.</div>');
  };

  img.src = imgSrc;
}

// Tutup modal saat klik overlay
$(document).on('click', '#rm-lokalis-modal', function(e) {
  if (e.target === this) $(this).hide();
});
</script>