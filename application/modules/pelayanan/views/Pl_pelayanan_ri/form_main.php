<style>
  /* ===== Patient info table — match fdd-section style ===== */
  .ri-info-table {
    width: 100%; border-collapse: separate; border-spacing: 0;
    font-size: 12px; margin-bottom: 14px;
    border-radius: 10px; overflow: hidden;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 5px rgba(0,0,0,.05);
  }
  .ri-info-table thead tr th {
    background: #f8fafc;
    color: #334155; font-size: 11.5px; font-weight: 700;
    padding: 9px 10px; border: none; border-bottom: 2px solid #e2e8f0;
    border-right: 1px solid #f1f5f9;
    white-space: nowrap; text-transform: uppercase; letter-spacing: .3px;
  }
  .ri-info-table thead tr th:last-child { border-right: none; }
  .ri-info-table tbody tr td {
    padding: 8px 10px; border-top: 1px solid #f1f5f9;
    color: #334155; vertical-align: middle; background: #fff;
  }
  .ri-info-table tbody tr:hover td { background: #f0f9ff !important; }

  /* ===== Toolbar wrapper ===== */
  .ri-toolbar {
    background: #f8fafc; border: 1px solid #e2e8f0;
    border-radius: 10px; padding: 10px 14px;
    margin-bottom: 14px;
  }
  .ri-toolbar-label {
    font-size: 10px; font-weight: 700; color: #94a3b8;
    text-transform: uppercase; letter-spacing: .5px;
    margin-bottom: 8px;
  }
  .ri-btn-bar {
    display: flex; flex-wrap: wrap;
    gap: 6px; align-items: center;
  }
  .ri-btn-sep {
    width: 1px; height: 24px; background: #e2e8f0;
    margin: 0 4px; flex-shrink: 0;
  }

  /* — Base button chip — */
  .ri-btn-bar .btn.btn-primary,
  .ri-btn-bar .btn-group .btn.btn-primary {
    background: #fff !important;
    border: 1px solid #e2e8f0 !important; color: #475569 !important;
    font-size: 11.5px; font-weight: 500; border-radius: 6px;
    padding: 5px 11px; cursor: pointer;
    transition: all .15s; box-shadow: 0 1px 2px rgba(0,0,0,.04);
  }
  .ri-btn-bar .btn.btn-primary:hover,
  .ri-btn-bar .btn-group .btn.btn-primary:hover {
    background: #eff6ff !important;
    border-color: #93c5fd !important; color: #1d4ed8 !important;
    box-shadow: 0 1px 4px rgba(29,78,216,.1);
  }
  .ri-btn-bar .btn.btn-primary i.fa { color: #94a3b8; margin-right: 2px; transition: color .15s; }
  .ri-btn-bar .btn.btn-primary:hover i.fa { color: #3b82f6; }

  /* — Pulangkan / action utama — */
  .ri-btn-bar .btn.ri-btn-action {
    background: #0369a1 !important;
    border: none !important; color: #fff !important;
    font-size: 11.5px; font-weight: 600; border-radius: 6px;
    padding: 5px 13px; cursor: pointer;
    box-shadow: 0 1px 4px rgba(3,105,161,.2);
    transition: all .15s;
  }
  .ri-btn-bar .btn.ri-btn-action:hover {
    background: #075985 !important;
    box-shadow: 0 2px 8px rgba(3,105,161,.3);
  }

  /* — Danger — */
  .ri-btn-bar .btn.btn-danger {
    background: #fff !important;
    border: 1px solid #fecaca !important; color: #dc2626 !important;
    font-size: 11.5px; font-weight: 500; border-radius: 6px;
    padding: 5px 11px; cursor: pointer;
    transition: all .15s; box-shadow: 0 1px 2px rgba(0,0,0,.04);
  }
  .ri-btn-bar .btn.btn-danger:hover {
    background: #fef2f2 !important;
    border-color: #f87171 !important;
    box-shadow: 0 1px 4px rgba(220,38,38,.1);
  }

  /* — Success — */
  .ri-btn-bar .btn.btn-success {
    background: #fff !important;
    border: 1px solid #bbf7d0 !important; color: #16a34a !important;
    font-size: 11.5px; font-weight: 500; border-radius: 6px;
    padding: 5px 11px; cursor: pointer;
    transition: all .15s; box-shadow: 0 1px 2px rgba(0,0,0,.04);
  }
  .ri-btn-bar .btn.btn-success:hover {
    background: #f0fdf4 !important;
    border-color: #4ade80 !important;
    box-shadow: 0 1px 4px rgba(22,163,74,.1);
  }

  /* Fix dropdown-toggle caret pairing */
  .ri-btn-bar .btn-group { display: inline-flex; }
  .ri-btn-bar .btn-group .dropdown-toggle {
    border-left: 1px solid #e2e8f0 !important;
    border-radius: 0 6px 6px 0;
    padding: 5px 8px;
  }
  .ri-btn-bar .btn-group .btn:first-child { border-radius: 6px 0 0 6px; }

  /* ===== EWS Score badge ===== */
  .ri-ews-wrap {
    margin-left: auto;
    display: inline-flex; align-items: center; gap: 6px;
    background: #fff; border: 1px solid #e2e8f0;
    border-radius: 8px; padding: 5px 12px;
    font-size: 11px; font-weight: 700; color: #64748b;
    box-shadow: 0 1px 2px rgba(0,0,0,.04);
  }
  .ri-ews-wrap .ri-ews-label { font-size: 9px; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; }

  /* ===== Nav tabs — match fdd sections ===== */
  #tabs_modules_pelayanan_ri.nav-tabs { border-bottom: 2px solid #e2e8f0; }
  #tabs_modules_pelayanan_ri.nav-tabs > li > a {
    font-size: 12px; font-weight: 600; color: #64748b;
    border-radius: 6px 6px 0 0; border: 1px solid transparent;
    padding: 7px 13px; transition: all .15s;
  }
  #tabs_modules_pelayanan_ri.nav-tabs > li > a:hover {
    background: #f8fafc; color: #334155; border-color: #e2e8f0 #e2e8f0 transparent;
  }
  #tabs_modules_pelayanan_ri.nav-tabs > li.active > a,
  #tabs_modules_pelayanan_ri.nav-tabs > li.active > a:focus,
  #tabs_modules_pelayanan_ri.nav-tabs > li.active > a:hover {
    background: #fff; color: #0f172a !important;
    border-color: #e2e8f0 #e2e8f0 #fff;
    border-bottom: 2px solid #0ea5e9;
    font-weight: 700;
  }
  /* Dropdown tab item */
  #tabs_modules_pelayanan_ri.nav-tabs .dropdown-menu { font-size: 12px; border-radius: 8px; }
  #tabs_modules_pelayanan_ri.nav-tabs .dropdown-menu > li > a:hover { background: #f8fafc; color: #334155; }

  /* ===== Tab content welcome area — match fdd-patient-hdr ===== */
  .ri-welcome-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0; border-radius: 10px;
    padding: 22px; text-align: center; margin: 6px 0;
  }
  .ri-welcome-box strong { font-size: 15px; color: #334155; display: block; margin-bottom: 6px; }
  .ri-welcome-box span { font-size: 12px; color: #94a3b8; }

  /* ===== Kelas Tarif area ===== */
  #form_kelas_tarif label { font-size: 12px; font-weight: 700; color: #374151; }
  #form_kelas_tarif .form-control { font-size: 12.5px; border-color: #d1d5db; border-radius: 6px; }

  /* ===== Divider ===== */
  .ri-divider { border: none; border-top: 1px solid #e2e8f0; margin: 12px 0; }
</style>

<script>
  $(document).ready(function(){

    // show ews indikator
    $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_ews_dt') ?>", {no_kunjungan: $('#no_kunjungan').val()} , function (response) {
        // show data
        var obj = response.result;
        // set value input
        var ews_ttl = response.ews_ttl;
        $('#score_ews_indikator').html('');
        $.each(ews_ttl, function(key, val) {
          if(val != ''){
            if(val == 0){
              clr_ind = 'success';
              color = 'green';
            }else if(val >=1 && val <=4){
              clr_ind = 'yellow';
              color = 'yellow';
            }else if(val >=5 && val <=6){
              clr_ind = 'warning';
              color = 'orange';
            }else{
              clr_ind = 'danger';
              color = 'red';
            }
            // append to
            $('<a class="btn btn-xs btn-'+clr_ind+'" style="font-weight: bold; "> '+val+' </a> &nbsp; &nbsp;').appendTo($('#score_ews_indikator'));

            $('#list_group_'+$('#no_mr').val()+'').css('background', color).css('font-weight', 'bold');
          }
      });

    });

    // DEFAULT
    $('#btn_observasi_harian_keperawatan').click();
    // getMenuTabsHtml("billing/Billing/getDetail/<?php echo $value->no_registrasi?>/RI", 'tabs_form_pelayanan');

    getMenuTabsHtml("templates/References/get_riwayat_medis/<?php echo $value->no_mr?>", 'tabs_form_pelayanan_rm');
    getBillingDetail(<?php echo $value->no_registrasi?>,'RI','bill_kamar_perawatan');

    window.filter = function(element)
    {
      var value = $(element).val().toUpperCase();
      $(".list-group > li").each(function()
      {
        if ($(this).text().toUpperCase().search(value) > -1){
          $(this).show();
        }
        else {
          $(this).hide();
        }
      });
    }

    /*submit form*/
    $('#form_pelayanan').ajaxForm({
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
          $('#table-pesan-resep').DataTable().ajax.reload(null, false);
          $('#jumlah_r').val('');
          $("#modalEditPesan").modal('hide');
          if(jsonResponse.type_pelayanan == 'penunjang_medis' || jsonResponse.type_pelayanan == 'rawat_jalan')
          {
            $('#riwayat-table').DataTable().ajax.reload(null, false);
            $('#table_order_penunjang').DataTable().ajax.reload(null, false);
          }
          if(jsonResponse.type_pelayanan == 'pulangkan_pasien' )
          {
            $('#div_main_form').load('pelayanan/Pl_pelayanan_ri/form_main/'+$('#kode_ri').val()+'/'+$('#no_kunjungan').val()+'');
          }
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }
        achtungHideLoader();
      }
    });


    oTablePesanDiagnosa = $('#table-riwayat-diagnosa').DataTable({

      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_riwayat_diagnosa?no_kunjungan=<?php echo $no_kunjungan?>&no_registrasi=<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>",
          "type": "POST"
      },

    });

    $('#pl_diagnosa').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getICD10",
                data: 'keyword=' + query,
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
          var label_item=item.split(':')[1];
          var val_item=item.split(':')[0];
          $('#pl_diagnosa').val(label_item);
          $('#pl_diagnosa_hidden').val(val_item);
        }

    });

    $('#pl_diagnosa_awal').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "ws_bpjs/Ws_index/getRef?ref=refDiagnosa",
                data: 'keyword=' + query,
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
          var label_item=item.split(':')[1];
          var val_item=item.split(':')[0];
          $('#pl_diagnosa_awal').val(label_item);
        }

    });

    $('#btn_add_diagnosa').click(function (e) {
      e.preventDefault();

      if( $('#pl_diagnosa_awal').val() == '' ){
        alert('Silahkan isi Diagnosa Awal !'); return false;
      }else{
        if( $('#pl_diagnosa').val() == '' ){
          alert('Silahkan isi Diagnosa Akhir !'); return false;
        }
      }

      /*process add pesan ok*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan_ri/process_add_diagnosa",
          data: $('#form_pelayanan').serialize(),
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
            $('#btn_submit_diagnosa').hide('fast');
            $('#pl_diagnosa').attr('readonly', true);
            $('#pl_diagnosa_awal').attr('readonly', true);
            oTablePesanDiagnosa.ajax.url('pelayanan/Pl_pelayanan_ri/get_riwayat_diagnosa?no_kunjungan=<?php echo $no_kunjungan?>&no_registrasi=<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>').load();
            }else{
              alert('Silahkan cari pasien !'); return false;
            }

          }
      });

    });

    /*onchange form module when click tabs*/
    $('#btn_monitoring_perkembangan_pasien, #btn_form_pengawasan_khusus, #btn_observasi_harian_keperawatan').click(function (e) {
      e.preventDefault();
      $("#tabs_modules_pelayanan_ri li").removeClass("active");
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_monitoring');
    });

    $('#btn_form_pemberian_obat').click(function (e) {
      e.preventDefault();
      $("#tabs_modules_pelayanan_ri li").removeClass("active");
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_pemberian_obat');
    });

    $('#btn_form_askep').click(function (e) {
      e.preventDefault();
      $("#tabs_modules_pelayanan_ri li").removeClass("active");
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_askep');
    });

    $('#btn_form_hand_over').click(function (e) {
      e.preventDefault();
      $("#tabs_modules_pelayanan_ri li").removeClass("active");
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_handover');
    });

    $('#btn_ews').click(function (e) {
      e.preventDefault();
      $("#tabs_modules_pelayanan_ri li").removeClass("active");
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_ews');
    });

    $('#btn_note').click(function (e) {
      e.preventDefault();
      $("#tabs_modules_pelayanan_ri li").removeClass("active");
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_note');
    });

    $('#tabs_cppt, #tabs_catatan, #btn_note, #btn_ews, #btn_form_askep, #btn_form_pemberian_obat, #btn_monitoring_perkembangan_pasien, #btn_form_pengawasan_khusus, #btn_observasi_harian_keperawatan ').click(function (e) {
      e.preventDefault();
      $('#form_kelas_tarif').hide();
    });

    $('#tabs_tindakan').click(function (e) {
      e.preventDefault();
      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/process');
      $('#form_kelas_tarif').show();
    });

    $('#tabs_cppt').click(function (e) {

      e.preventDefault();

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_cppt');

    });

    $('#tabs_catatan').click(function (e) {

      e.preventDefault();

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveCatatanPengkajian');

    });

    $('#tabs_pesan_resep').click(function (e) {

      e.preventDefault();

      $('#form_pelayanan').attr('action', 'farmasi/Farmasi_pesan_resep/process');

    });

    $('#tabs_penunjang_medis').click(function (e) {

      e.preventDefault();

      $('#form_pelayanan').attr('action', 'registration/Reg_pm/process');

    });

    $('#tabs_klinik').click(function (e) {

      e.preventDefault();

      $('#form_pelayanan').attr('action', 'registration/Reg_klinik/process');

    });

    $('#tabs_billing_pasien').click(function (e) {

      e.preventDefault();

      getBillingDetail(<?php echo $value->no_registrasi?>,'RI','bill_kamar_perawatan');

    });

  })

function edit_diagnosa() {
  $('#btn_submit_diagnosa').show('fast');
  $('#btn_hide_submit_diagnosa').show('fast');
  $('#pl_diagnosa').attr('readonly', false);
  $('#pl_diagnosa_awal').attr('readonly', false);
}

function UnEditDiagnosa() {
  $('#btn_submit_diagnosa').hide('fast');
  $('#btn_hide_submit_diagnosa').hide('fast');
  $('#pl_diagnosa').attr('readonly', true);
  $('#pl_diagnosa_awal').attr('readonly', true);
}

function selesaikanKunjungan(){

  noMr = $('#noMrHidden').val();
  preventDefault();
  $("#tabs_modules_pelayanan_ri li").removeClass("active");
  //$('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/processPelayananSelesai');
  $('#tabs_form_pelayanan').show('fast');
  $('#tabs_form_pelayanan').load('pelayanan/Pl_pelayanan_ri/form_end_visit?mr='+noMr+'&id='+$('#kode_ri').val()+'&no_kunjungan='+$('#no_kunjungan').val()+'');

}

function backToDefaultForm(){

  noMr = $('#noMrHidden').val();
  preventDefault();
  $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processPelayananSelesai');
  $('#form_default_pelayanan').hide('fast');
  $('#form_default_pelayanan').html('');

}

function rollback(no_registrasi, no_kunjungan){

  preventDefault();

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan_ri/rollback",
      data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan },
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        if(jsonResponse.status === 200){
          $.achtung({message: jsonResponse.message, timeout:5});
          $('#div_main_form').load('pelayanan/Pl_pelayanan_ri/form_main/'+$('#kode_ri').val()+'/'+$('#no_kunjungan').val()+'');
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }
        achtungHideLoader();
      }
  });

}

function delete_diagnosa(myid){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan/delete_diagnosa',
        type: "post",
        data: {ID:myid},
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
            oTablePesanDiagnosa.ajax.url('pelayanan/Pl_pelayanan_ri/get_riwayat_diagnosa?no_kunjungan=<?php echo $no_kunjungan?>&no_registrasi=<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>').load();
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

</script>
<!-- end action form -->

<!-- hidden form -->
  <input type="hidden" class="form-control" name="no_registrasi" id="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>">
  <input type="hidden" class="form-control" name="kode_kelompok" value="<?php echo isset($value)?$value->kode_kelompok:''?>">
  <input type="hidden" class="form-control" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>">
  <input type="hidden" class="form-control" id="no_mr" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
  <input type="hidden" class="form-control" name="nama_pasien_layan" value="<?php echo isset($value)?$value->nama_pasien:''?>">
  <input type="hidden" class="form-control" name="kode_bagian_asal" value="<?php echo isset($value)?$value->bag_pas:''?>">
  <input type="hidden" class="form-control" name="kode_bagian" value="<?php echo isset($value)?$value->bag_pas:''?>" id="kode_bagian_val">
  <input type="hidden" class="form-control" name="klas_titipan" value="<?php echo $klas_titipan ?>" id="klas_titipan">
  <input type="hidden" class="form-control" name="kode_dokter_poli" value="<?php echo isset($value->kode_dokter)?$value->kode_dokter:''?>">
  <input type="hidden" class="form-control" name="kode_ruangan" value="<?php echo isset($value->kode_ruangan)?$value->kode_ruangan:''?>">
  <input type="hidden" name="kode_ri" id="kode_ri" value="<?php echo ($id)?$id:''?>">
  <input type="hidden" name="dr_merawat" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dr_merawat">
  <input type="hidden" name="dokter_pemeriksa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dokter_pemeriksa">

  <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>

  <input type="hidden" name="no_kunjungan" id="no_kunjungan" class="form-control" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" readonly>
  <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:0?>">

  <!-- Patient Info Table -->
  <table class="ri-info-table table-bordered">
    <thead>
      <tr>
        <th width="100px">Status Pasien</th>
        <th>Kode/Tgl Masuk</th>
        <th>Dokter Merawat</th>
        <th>Ruangan/Kelas</th>
        <th>Penjamin</th>
        <th>Diagnosa Awal</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td align="center" style="vertical-align: middle"><?php echo $status_rawat; ?></td>
        <td>No. <?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?><br><?php echo isset($value->tgl_masuk)?$this->tanggal->formatDateTime($value->tgl_masuk):''?></td>
        <td><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></td>
        <td><?php echo isset($value->nama_bagian)?$value->nama_bagian:'';?> (<?php echo isset($value->klas)?$value->klas:'';?>) <br><?php echo isset($ruangan)?'Kamar: '.$ruangan->no_kamar.' / Bed: '.$ruangan->no_bed:'';?></td>
        <td><?php echo isset($value->nama_perusahaan)?$value->nama_perusahaan:'';?></td>
        <td><?php echo isset($riwayat->diagnosa_awal)?$riwayat->diagnosa_awal:'';?></td>
      </tr>
    </tbody>
  </table>

  <!-- Action Toolbar -->
  <div class="ri-toolbar">
    <div class="ri-toolbar-label">Aksi Pelayanan</div>
    <div class="ri-btn-bar">

      <!-- Monitoring Group -->
      <div class="btn-group dropdown">
        <button class="btn btn-xs btn-primary" type="button"><i class="fa fa-warning"></i> EWS</button>
        <button data-toggle="dropdown" class="btn btn-xs btn-primary dropdown-toggle">
          <span class="ace-icon fa fa-caret-down icon-only"></span>
        </button>
        <ul class="dropdown-menu dropdown-primary">
          <li><a href="#" id="btn_ews" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/ews/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Ranap&type_form=dewasa&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>', 'tabs_form_pelayanan')">Dewasa</a></li>
          <li><a href="#" id="btn_ews" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/ews/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Ranap&type_form=anak&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>', 'tabs_form_pelayanan')">Anak</a></li>
          <li><a href="#" id="btn_ews" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/ews/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Ranap&type_form=kebidanan&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>', 'tabs_form_pelayanan')">Kebidanan</a></li>
        </ul>
      </div>

      <a href="#" class="btn btn-xs btn-primary" id="btn_observasi_harian_keperawatan" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/observasi_harian_keperawatan/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>&tipe_monitoring=UMUM', 'tabs_form_pelayanan')"><i class="fa fa-heartbeat"></i> Observasi Pasien</a>

      <a href="#" class="btn btn-xs btn-primary" id="btn_form_pemberian_obat" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/pemberian_obat/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>', 'tabs_form_pelayanan')"><i class="fa fa-medkit"></i> Pemberian Obat</a>

      <a href="#" class="btn btn-xs btn-primary" id="btn_form_askep" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/askep/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>', 'tabs_form_pelayanan')"><i class="fa fa-stethoscope"></i> Asuhan Keperawatan</a>

      <a href="#" class="btn btn-xs btn-primary" id="btn_form_hand_over" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/hand_over/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>', 'tabs_form_pelayanan')"><i class="fa fa-stethoscope"></i> Hand Over</a>

      <a href="#" class="btn btn-xs btn-primary" id="btn_note" onclick="getMenuTabs('pelayanan/Pl_pelayanan_ri/note/<?php echo $id?>/<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>', 'tabs_form_pelayanan')"><i class="fa fa-pencil-square-o"></i> Visual Gambar</a>

      <span class="ri-btn-sep"></span>

      <!-- Status / Aksi Utama -->
      <?php if($value->status_pulang==0) :?>
        <a href="#" class="btn btn-xs ri-btn-action" onclick="selesaikanKunjungan()"><i class="fa fa-sign-out"></i> Pulangkan Pasien</a>
      <?php else: ?>
        <a href="#" class="btn btn-xs ri-btn-action" onclick="selesaikanKunjungan()"><i class="fa fa-file-text-o"></i> Resume Medis</a>
        <?php if($transaksi!=0):?>
          <a href="#" class="btn btn-xs btn-danger" onclick="rollback(<?php echo isset($value)?$value->no_registrasi:'' ?>,<?php echo isset($value)?$value->no_kunjungan:''?>)"><i class="fa fa-undo"></i> Kembalikan ke Rawat Inap</a>
        <?php else: ?>
          <a href="#" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Lunas</a>
        <?php endif ?>
      <?php endif;?>

      <!-- EWS Score -->
      <div class="ri-ews-wrap">
        <i class="fa fa-heartbeat" style="color:#ef4444"></i>
        <span class="ri-ews-label">Score EWS</span>
        <span id="score_ews_indikator">-</span>
      </div>

    </div>
  </div>

  <hr class="ri-divider">

  <div class="col-md-12 no-padding">
    <div class="tabbable">

      <ul class="nav nav-tabs" id="tabs_modules_pelayanan_ri">

        <li>
          <a data-toggle="tab" id="tabs_cppt" href="#" data-id="<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>" data-url="pelayanan/Pl_pelayanan_ri/cppt/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
            C P P T
          </a>
        </li>

        <li>
          <a data-toggle="tab" id="tabs_catatan" href="#" data-id="<?php echo $no_kunjungan?>?type=Ranap&no_mr=<?php echo $no_mr?>" data-url="pelayanan/Pl_pelayanan/catatan_lainnya/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
            <?php echo FRM_PENGKAJIAN?>
          </a>
        </li>

        <li>
          <a data-toggle="tab" id="tabs_cppt" href="#" data-id="<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>" data-url="pelayanan/Pl_pelayanan_ri/riwayat_medis/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
            <?php echo RIWAYAT_MEDIS?>
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#rm_tabs" data-url="templates/References/get_riwayat_pm/<?php echo $value->no_mr?>" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')" title="Riwayat Penunjang Medis">
            Hasil Penunjang
          </a>
        </li>

        <li>
          <a data-toggle="tab" data-id="<?php echo $id?>" data-url="farmasi/Farmasi_pesan_resep/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>" id="tabs_pesan_resep" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id')+'?kode_bag=<?php echo $value->bag_pas?>', 'tabs_form_pelayanan')">
            <?php echo ERESEP; ?>
          </a>
        </li>

        <li>
          <a data-toggle="tab" id="tab_obat_bhp" href="#" data-id="<?php echo $no_kunjungan?>?type=<?php echo $type?>&kode_bag=<?php echo isset($kode_bagian)?$kode_bagian:''?>" data-url="pelayanan/Pl_pelayanan_ri/obat_bhp/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
            Input BHP
          </a>
        </li>

        <li class="dropdown">
          <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="true">
            Rujuk Internal &nbsp;<i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
          </a>
          <ul class="dropdown-menu dropdown-info">
            <li>
              <a data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/Reg_klinik/rujuk_klinik/<?php echo $value->no_registrasi?>/<?php echo $value->bag_pas?>/ranap/<?php echo $kode_klas?>" id="tabs_klinik" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')">
                Rujuk ke Klinik
              </a>
            </li>
            <li>
              <a data-toggle="tab" data-id="<?php echo $id?>" data-url="pelayanan/Pl_pelayanan_ri/pesan/<?php echo $id?>/<?php echo $value->no_registrasi?>" id="tabs_pesan" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
                (Kamar Bedah/ VK/ Pindah Ruangan)
              </a>
            </li>
            <li>
              <a data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/Reg_pm/rujuk_pm/<?php echo $value->no_registrasi?>/<?php echo $value->bag_pas?>/<?php echo $kode_klas?>/ranap" id="tabs_penunjang_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')">
                <?php echo EORDER?>
              </a>
            </li>
          </ul>
        </li>

        <li class="dropdown">
          <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="true">
            Billing Pasien &nbsp;<i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
          </a>
          <ul class="dropdown-menu dropdown-info">
            <li>
              <a data-toggle="tab" id="tabs_tindakan" href="dropdown1" data-id="<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>" data-url="pelayanan/Pl_pelayanan_ri/tindakan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">Input Tarif Tindakan</a>
            </li>
            <li>
              <a data-toggle="tab" data-id="<?php echo $id?>" data-url="billing/Billing/getDetail/<?php echo $value->no_registrasi?>/RI" id="tabs_billing_pasien" href="#dropdown2" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')">
                Resume Billing Pasien
              </a>
            </li>
          </ul>
        </li>

      </ul>

      <div class="tab-content">

        <div class="row">

          <div class="col-md-12" style="padding-bottom: 5px !important; display: none" id="form_kelas_tarif">
            <label><b>Kelas Tarif :</b></label><br>
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_klas', 'id' => 'kode_klas', 'name' => 'nama_klas', 'where' => array('is_active' => 1)), isset($kode_klas)?$kode_klas:$klas_titipan , 'kode_klas', 'kode_klas_val', 'form-control', '', '') ?>
          </div>

          <div id="tabs_form_pelayanan" style="padding: 10px !important">
            <div class="ri-welcome-box">
              <strong><i class="fa fa-hospital-o" style="color:#0ea5e9;margin-right:8px"></i>LEMBAR KERJA PELAYANAN PASIEN RAWAT INAP</strong>
              <span>Silahkan klik pada Tab diatas untuk mengisi form yang sesuai.</span>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>

  <div id="form_default_pelayanan"></div>
