<script type="text/javascript">

    $(document).ready(function() {
    //initiate dataTables plugin
        oTable = $('#table-pesan-resep').DataTable({ 
            
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "ordering": false,
            "searching": false,
            "bPaginate": false,
            "bInfo": false,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "farmasi/Farmasi_pesan_resep/get_data_by_id?q="+<?php echo $value->no_kunjungan ?>,
                "type": "POST"
            },
            "columnDefs": [
                { 
                    "targets": [ 0 ], //last column
                    "orderable": false, //set not orderable
                },
                {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
                { "visible": true, "targets": [0] },
                { "visible": false, "targets": [4] },
                { "visible": false, "targets": [5] },
            ],

        });

        $('#table-pesan-resep tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = oTable.row( tr );
                var data = oTable.row( $(this).parents('tr') ).data();
                var kode_pesan_resep = data[ 4 ];
                var no_registrasi = data[ 5 ];
                        

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    /*data*/
                    
                    $.getJSON("farmasi/Farmasi_pesan_resep/getDetail/" + kode_pesan_resep + "/" + no_registrasi, '', function (data) {
                        response_data = data;
                        // Open this row
                        row.child( format_html( response_data ) ).show();
                        tr.addClass('shown');
                    });
                    
                }
        } );

        $('#table-pesan-resep tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                //achtungShowLoader();
                $(this).removeClass('selected');
                //achtungHideLoader();
            }
            else {
                //achtungShowLoader();
                oTable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                //achtungHideLoader();
            }
        } );

        // table riwayat
        oTableRiwayat = $('#table-riwayat-pesan-resep').DataTable({ 
            
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "ordering": false,
            "searching": false,
            "bPaginate": false,
            "bInfo": false,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "farmasi/Farmasi_pesan_resep/get_data_by_mr?no_mr="+$('#no_mr_pesan_resep').val()+"",
                "type": "POST"
            },
            "columnDefs": [
                { 
                    "targets": [ 0 ], //last column
                    "orderable": false, //set not orderable
                },
                {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
                { "visible": true, "targets": [0] },
                { "visible": false, "targets": [4] },
            ],

        });

        $('#table-riwayat-pesan-resep tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = oTableRiwayat.row( tr );
                var data = oTableRiwayat.row( $(this).parents('tr') ).data();
                var kode_pesan_resep = data[ 4 ];
                        

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    /*data*/
                    
                    $.getJSON("farmasi/Farmasi_pesan_resep/getDetail/" + kode_pesan_resep, '', function (data) {
                        response_data = data;
                        // Open this row
                        row.child( format_html( response_data ) ).show();
                        tr.addClass('shown');
                    });
                    
                }
        } );

        $('#table-riwayat-pesan-resep tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                //achtungShowLoader();
                $(this).removeClass('selected');
                //achtungHideLoader();
            }
            else {
                //achtungShowLoader();
                oTableRiwayat.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                //achtungHideLoader();
            }
        } );


        $('#kode_dokter_show').typeahead({
            source: function (query, result) {
                    $.ajax({
                        url: "templates/references/getAllDokter",
                        data: 'keyword=' + query + '&bag=' + $('#kode_bagian_tujuan').val(),         
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
                var label_item=item.split(':')[1];
                console.log(val_item);
                $('#kode_dokter_show').val(label_item);
                $('#kode_dokter').val(val_item);
                
            }
        });


        // ── PRB: disable iter ──
        var fprTtvIter = '<?php echo isset($_fpr_iter_ttv) ? $_fpr_iter_ttv : '0' ?>';
        $('input[name="jenis_resep"]').on('change', function(){
            var $iterInputs = $('input[name="resep_iter"]');
            if($(this).val() === 'prb'){
                $iterInputs.prop('disabled', true);
                $iterInputs.filter('[value="0"]').prop('checked', true);
            } else {
                $iterInputs.prop('disabled', false);
                $iterInputs.filter('[value="' + fprTtvIter + '"]').prop('checked', true);
            }
        });

        $('#inputDokterPesanResep').typeahead({
            source: function (query, result) {
                    $.ajax({
                        url: "templates/references/getAllDokter",
                        data: 'keyword=' + query + '&bag=' + $('#kode_bagian_tujuan').val(),         
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
                var label_item=item.split(':')[1];
                console.log(val_item);
                $('#inputDokterPesanResep').val(label_item);
                $('#kode_dokter_edit').val(val_item);
                
            }
        });
        
    });

    function format_html ( data ) {
    return data.html;
    }

    function preventDefault(e) {
    e = e || window.event;
    if (e.preventDefault)
        e.preventDefault();
    e.returnValue = false;  
    }

    function delete_pesan_resep(id) {
        var answer = confirm('Hapus Pesanan?');
        preventDefault();
        if (answer){
            console.log('yes'); 
            $.ajax({
                url: 'farmasi/Farmasi_pesan_resep/delete',
                type: "post",
                data: {ID:id},
                dataType: "json",
                beforeSend: function() {
                    achtungShowLoader();  
                },
                success: function(data) {
                    //var jsonResponse = JSON.parse(data);  
                    achtungHideLoader();
                    console.log(data) 
                    $('#table-pesan-resep').DataTable().ajax.reload(null, false);
                }
            });
        }else{
            console.log('cancel');      
        }
    }

    function showModalEdit(id) {
        preventDefault();
        $.ajax({
            url: 'farmasi/Farmasi_pesan_resep/get_pesan_resep_by_id',
            type: "post",
            data: {id:id},
            dataType: "json",
            beforeSend: function() {
                $('#notif_status').html('<span class="red"><b>[Session Update]</b></span>');        
            },
            success: function(data) {
                //var jsonResponse = JSON.parse(data);  
                achtungHideLoader();
                if(data.status === 200){     
                    var resep = data.data
                    console.log(resep.tgl_pesan);
                    $('#kode_pesan_resep').val(resep.kode_pesan_resep);
                    $('#tgl_pesan').val(resep.tgl_pesan);
                    $('#jumlah_r').val(resep.jumlah_r);
                    $('#status_tebus').val(resep.status_tebus);
                    $('#lokasi_tebus').val(resep.lokasi_tebus);
                    $('#kode_dokter').val(resep.kode_dokter);
                    $('#kode_dokter_show').val(resep.nama_pegawai);
                    $('#keterangan_pesan_resep').val(resep.keterangan);
                    $('#kode_bagian_asal').val(resep.kode_bagian_asal);
                    $("input[name=jenis_resep][value='"+resep.jenis_resep+"']").prop("checked",true);
                    var $iterInputs = $('input[name="resep_iter"]');
                    if(resep.jenis_resep === 'prb'){
                        $iterInputs.prop('disabled', true);
                        $iterInputs.filter('[value="0"]').prop('checked', true);
                    } else {
                        $iterInputs.prop('disabled', false);
                        $iterInputs.filter('[value="'+(resep.resep_iter||'0')+'"]').prop('checked', true);
                    }
                } else {
                    $.achtung({message: data.message, timeout:5});   
                }
            }
        });
         
    }

    function back_to_previous(){
        getMenuTabs('farmasi/Farmasi_pesan_resep/pesan_resep/'+$('#no_kunjungan').val()+'/'+$('#kode_klas').val()+'/'+$('#kode_profit').val()+'', 'tabs_form_pelayanan');
    }

    function form_eresep(kode_pesan_resep){
        getMenuTabs('farmasi/E_resep/form/'+$('#no_registrasi').val()+'/'+kode_pesan_resep+'?no_mr='+$('#no_mr_pesan_resep').val()+'&no_kunjungan='+$('#no_kunjungan').val()+'', 'form_pesan_resep');
    }


</script>

<style>
/* ── Wrapper ── */
#fpr-wrap {
  font-family: 'Segoe UI', system-ui, Arial, sans-serif;
  font-size: 13px;
}

/* ── Back nav ── */
.fpr-back {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  font-weight: 700;
  color: #1e40af;
  text-decoration: none;
  text-transform: uppercase;
  letter-spacing: .4px;
  margin-bottom: 14px;
  padding: 5px 10px;
  border-radius: 6px;
  background: #eff6ff;
  border: 1px solid #bfdbfe;
  transition: background .15s;
}
.fpr-back:hover { background: #dbeafe; color: #1e3a8a; text-decoration: none; }
.fpr-back i { font-size: 14px; }

/* ── Notification ── */
#notif_status { display: block; margin-bottom: 6px; font-size: 12px; }

/* ── Card ── */
.fpr-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,.06);
  overflow: hidden;
  margin-bottom: 18px;
}
.fpr-card-hdr {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 16px;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: #fff !important;
  border-bottom: none;
}
.fpr-card-hdr.blue  { background: linear-gradient(135deg, #1e40af, #2563eb); }
.fpr-card-hdr.green { background: linear-gradient(135deg, #065f46, #059669); }
.fpr-card-hdr i { font-size: 14px; opacity: .9; }
.fpr-card-body { padding: 16px; }

/* ── Form grid ── */
.fpr-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 12px 16px;
  margin-bottom: 12px;
}
.fpr-grid-wide {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 10px;
  align-items: end;
  margin-top: 10px;
}
.fpr-field { display: flex; flex-direction: column; gap: 4px; }
.fpr-field label {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .4px;
  color: #64748b;
  margin: 0;
}
.fpr-field input[type="text"],
.fpr-field input[type="number"],
.fpr-field select {
  border: 1px solid #d1d5db;
  border-radius: 6px;
  padding: 6px 10px;
  font-size: 12.5px;
  color: #1e293b;
  background: #fff;
  transition: border-color .15s, box-shadow .15s;
  width: 100%;
}
.fpr-field input:focus,
.fpr-field select:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37,99,235,.12);
}
.fpr-field input[readonly] { background: #f8fafc; color: #64748b; cursor: default; }

/* ── Inline field row ── */
.fpr-inline {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
  align-items: flex-start;
  margin-bottom: 10px;
}
.fpr-inline .fpr-field { flex: 1; min-width: 160px; }

/* ── Radio groups ── */
.fpr-radio-row {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
  margin-bottom: 12px;
}
.fpr-radio-block { display: flex; flex-direction: column; gap: 4px; }
.fpr-radio-block-label {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .4px;
  color: #64748b;
}
.fpr-radio-opts { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
.fpr-radio-opt {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 12px;
  border: 1.5px solid #e2e8f0;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
  color: #475569;
  cursor: pointer;
  transition: all .15s;
  user-select: none;
  margin: 0;
}
.fpr-radio-opt input[type="radio"] { display: none; }
.fpr-radio-opt:has(input:checked) {
  border-color: #2563eb;
  background: #eff6ff;
  color: #1d4ed8;
  font-weight: 700;
}
.fpr-radio-opt:hover { border-color: #93c5fd; background: #f0f9ff; }
.fpr-radio-opt:has(input:disabled) { opacity: .4; cursor: not-allowed; pointer-events: none; }

/* ── Submit button ── */
.fpr-btn-submit {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 20px;
  background: linear-gradient(135deg, #059669, #10b981);
  color: #fff;
  border: none;
  border-radius: 7px;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: opacity .15s, transform .15s;
  white-space: nowrap;
}
.fpr-btn-submit:hover { opacity: .88; transform: translateY(-1px); }

/* ── Divider label ── */
.fpr-section-sep {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 4px 0 14px;
  color: #94a3b8;
  font-size: 11px;
}
.fpr-section-sep::before,
.fpr-section-sep::after { content:''; flex:1; height:1px; background:#e2e8f0; }

/* ── Iter alert ── */
@keyframes fprIterPulse {
  0%, 100% { box-shadow: 0 0 0 0 rgba(246,92,92,0); }
  50%       { box-shadow: 0 0 0 5px rgba(246,92,92,.12); }
}
.fpr-iter-alert {
  display: none;
  align-items: center;
  gap: 14px;
  background: linear-gradient(135deg, #fff5f5, #ffe8e8);
  border: 1.5px solid #feb9b4;
  border-left: 4px solid #f6615c;
  border-radius: 10px;
  padding: 12px 18px;
  margin-bottom: 12px;
  animation: fprIterPulse 2s ease-in-out infinite;
}
.fpr-iter-alert.show { display: flex; }
.fpr-iter-icon {
  width: 40px; height: 40px; border-radius: 10px;
  background: linear-gradient(135deg, #f65c5c, #d92828);
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 18px; flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(246,92,92,.3);
}
.fpr-iter-body { flex: 1; min-width: 0; }
.fpr-iter-title { font-size: 13px; font-weight: 700; color: #b62121; margin-bottom: 2px; }
.fpr-iter-desc  { font-size: 12px; color: #6b7280; line-height: 1.5; }
.fpr-iter-desc strong { color: #b62121; font-size: 14px; }

/* ── Notice ── */
.fpr-notice {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  padding: 10px 14px;
  background: #fffbeb;
  border: 1px solid #fde68a;
  border-radius: 7px;
  font-size: 12px;
  color: #92400e;
  margin-top: 10px;
}
.fpr-notice i { color: #d97706; margin-top: 1px; flex-shrink: 0; }

/* ── Table overrides ── */
#fpr-wrap .dataTables_wrapper { margin-top: 0; }
#fpr-wrap table.dataTable thead th {
  background: #0f172a;
  color: #fff;
  font-size: 11.5px;
  font-weight: 600;
  padding: 8px 10px;
  border-color: #1e293b;
}
#fpr-wrap table.dataTable tbody td {
  font-size: 12.5px;
  padding: 7px 10px;
  vertical-align: middle;
  border-color: #f1f5f9;
}
/* #fpr-wrap table.dataTable tbody tr:hover td { background: #f8fafc; } */
</style>

<!-- ── Hidden inputs ── -->
<input type="hidden" value="<?php echo $value->no_registrasi?>" name="no_registrasi" id="no_registrasi">
<input type="hidden" value="<?php echo $value->no_kunjungan?>" name="no_kunjungan" id="no_kunjungan">
<input type="hidden" value="<?php echo $value->no_mr?>" name="no_mr_pesan_resep" id="no_mr_pesan_resep">
<input type="hidden" value="<?php echo $value->kode_perusahaan?>" name="kode_perusahaan" id="kode_perusahaan">
<input type="hidden" value="<?php echo $value->kode_kelompok?>" name="kode_kelompok" id="kode_kelompok">
<input type="hidden" value="<?php echo $value->kode_bagian_tujuan?>" name="kode_bagian_tujuan" id="kode_bagian_tujuan">
<input type="hidden" value="<?php echo $kode_bagian_asal?>" name="kode_bagian_asal" id="kode_bagian_asal">
<input type="hidden" value="<?php echo $kode_klas?>" name="kode_klas" id="kode_klas">
<input type="hidden" value="<?php echo $kode_profit?>" name="kode_profit" id="kode_profit">
<input type="hidden" value="" name="kode_pesan_resep" id="kode_pesan_resep">

<div id="fpr-wrap">

  <!-- ── Back nav ── -->
  <a href="#" class="fpr-back" onclick="back_to_previous(); return false;">
    <i class="fa fa-angle-double-left"></i> Form Pesan Resep
  </a>

  <span id="notif_status"></span>

  <!-- ══ CARD 1: Order Form ══ -->
  <div class="fpr-card" id="form_pesan_resep">
    <div class="fpr-card-hdr blue">
      <i class="fa fa-medkit"></i> Order Resep Dokter
    </div>
    <div class="fpr-card-body">

      <!-- Row 1: Tanggal + Dokter -->
      <div class="fpr-inline">
        <div class="fpr-field" style="max-width:220px;">
          <label><i class="fa fa-calendar" style="margin-right:3px;"></i>Tanggal Resep</label>
          <input class="datetime" name="tgl_pesan" id="tgl_pesan" type="text"
                 value="<?php echo date('Y-m-d h:i:s') ?>" readonly/>
        </div>
        <div class="fpr-field" style="flex:2;">
          <label><i class="fa fa-user-md" style="margin-right:3px;"></i>Nama Dokter</label>
          <input id="kode_dokter_show" name="kode_dokter_show" type="text"
                 value="<?php echo $value->nama_pegawai ?>" placeholder="Cari dokter..."/>
          <input id="kode_dokter" name="kode_dokter" type="hidden"
                 value="<?php echo $value->kode_dokter ?>"/>
        </div>
        <div class="fpr-field" style="max-width:90px;">
          <label>Jumlah R/</label>
          <input name="jumlah_r" id="jumlah_r" type="text" value="1"/>
        </div>
        <div class="fpr-field" style="max-width:160px;">
          <label><i class="fa fa-map-marker" style="margin-right:3px;"></i>Lokasi Tebus</label>
          <select name="lokasi_tebus" id="lokasi_tebus">
            <option value="1">Dalam RS</option>
            <option value="2">Luar RS</option>
          </select>
        </div>
      </div>

      <div class="fpr-section-sep">Pengaturan Resep</div>
      <!-- Iter notification (TTV-recorded by nurse) -->
      <?php if(isset($value->resep_iter) && $value->resep_iter == 'Y') :?>
      <div class="fpr-iter-alert show">
        <div class="fpr-iter-body">
          <div class="fpr-iter-title">Pasien Memiliki Resep Iter</div>
          <div class="fpr-iter-desc">
            Resep iterasi sebanyak <strong><?php echo isset($value->jumlah_iter) ? $value->jumlah_iter : '-'?>x</strong>
            pengulangan telah dicatat oleh perawat pada saat input TTV.
          </div>
        </div>
      </div>
      <?php endif; ?>
      <!-- Row 2: Radios -->
      <div class="fpr-radio-row">
        <div class="fpr-radio-block">
          <span class="fpr-radio-block-label">Jenis Resep</span>
          <div class="fpr-radio-opts">
            <label class="fpr-radio-opt">
              <input name="jenis_resep" value="non_prb" type="radio" checked> Non PRB
            </label>
            <label class="fpr-radio-opt">
              <input name="jenis_resep" value="prb" type="radio"> PRB
            </label>
          </div>
        </div>
        <?php
          $_fpr_iter_ttv = (isset($value->resep_iter) && $value->resep_iter == 'Y' && isset($value->jumlah_iter) && $value->jumlah_iter > 0) ? (string)$value->jumlah_iter : '0';
        ?>
        <div class="fpr-radio-block">
          <span class="fpr-radio-block-label">Resep Iter</span>
          <div class="fpr-radio-opts">
            <label class="fpr-radio-opt">
              <input name="resep_iter" id="resep_iter" value="0" type="radio" <?php echo $_fpr_iter_ttv === '0' ? 'checked' : '' ?>> Tidak
            </label>
            <label class="fpr-radio-opt">
              <input name="resep_iter" value="1" type="radio" <?php echo $_fpr_iter_ttv === '1' ? 'checked' : '' ?>> 1x
            </label>
            <label class="fpr-radio-opt">
              <input name="resep_iter" value="2" type="radio" <?php echo $_fpr_iter_ttv === '2' ? 'checked' : '' ?>> 2x
            </label>
          </div>
        </div>
      </div>

      

      <!-- Row 3: Keterangan + Submit -->
      <div class="fpr-grid-wide">
        <div class="fpr-field">
          <label><i class="fa fa-comment-o" style="margin-right:3px;"></i>Keterangan</label>
          <input type="text" name="keterangan_pesan_resep" id="keterangan_pesan_resep"
                 value="Mohon diproses sesuai resep" placeholder="Keterangan resep..."/>
        </div>
        <button type="submit" class="fpr-btn-submit">
          <i class="fa fa-save"></i> Submit Resep
        </button>
      </div>

    </div>
  </div>

  <!-- ══ CARD 2: Daftar Resep Kunjungan ══ -->
  <div class="fpr-card">
    <div class="fpr-card-hdr blue">
      <i class="fa fa-list-alt"></i> Daftar Resep Kunjungan Ini
    </div>
    <div class="fpr-card-body" style="padding:14px;">
      <table id="table-pesan-resep" base-url="farmasi/Farmasi_pesan_resep"
             class="table table-bordered table-hover" style="width:100%;">
        <thead>
          <tr>
            <th width="40px"></th>
            <th width="40px"></th>
            <th width="150px">Tgl Resep</th>
            <th>Asal Unit / Dokter</th>
            <th></th>
            <th></th>
            <th width="100px">Keterangan</th>
            <th width="90px">Status</th>
            <th>e-Resep</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <div class="fpr-notice">
        <i class="fa fa-exclamation-triangle"></i>
        <span><strong>Pemberitahuan:</strong> Resep yang sudah diproses oleh farmasi tidak dapat diubah lagi. Silahkan hubungi farmasi untuk mengubah resep yang sudah diproses.</span>
      </div>
    </div>
  </div>

  <!-- ══ CARD 3: Riwayat Resep ══ -->
  <div class="fpr-card">
    <div class="fpr-card-hdr green">
      <i class="fa fa-history"></i> 10 Riwayat Resep Sebelumnya
    </div>
    <div class="fpr-card-body" style="padding:14px;">
      <table id="table-riwayat-pesan-resep" base-url="farmasi/Farmasi_pesan_resep"
             class="table table-bordered table-hover" style="width:100%;">
        <thead>
          <tr>
            <th width="40px"></th>
            <th width="40px"></th>
            <th width="150px">Tgl Resep</th>
            <th>Asal Unit / Dokter</th>
            <th></th>
            <th width="100px">Keterangan</th>
            <th width="90px">Status</th>
            <th>e-Resep</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

</div><!-- /#fpr-wrap -->






