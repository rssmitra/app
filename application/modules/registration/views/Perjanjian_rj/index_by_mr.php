<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<style>
/* ── Filter bar ── */
.prjmr-filter-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 10px 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 12px 16px;
    margin-bottom: 14px;
}
.prjmr-fg {
    display: flex;
    flex-direction: column;
}
.prjmr-fg label {
    font-size: 11px;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 4px;
    white-space: nowrap;
}
/* Bootstrap 3 uses display:table-cell for input-group — do NOT override display.
   Only set height/font; Bootstrap handles border-radius, border joins, and width. */
.prjmr-fg .input-group > .form-control {
    height: 30px;
    font-size: 12px;
    border-color: #cbd5e1;
}
.prjmr-fg .input-group-addon {
    height: 30px;
    padding: 4px 9px;
    font-size: 12px;
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    cursor: pointer;
}

/* Pelayanan radios */
.prjmr-radio-group {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    padding: 4px 0;
}
.prjmr-radio-item {
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    margin: 0;
    font-size: 12px;
    font-weight: 500;
    color: #334155;
    white-space: nowrap;
}

/* ── Section title ── */
.prjmr-section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}
.prjmr-section-title .prjmr-badge {
    background: linear-gradient(135deg, #0369a1, #0891b2);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 4px;
}

/* ── Table header ── */
#dynamic-table-perjanjian-bymr thead th {
    background: linear-gradient(135deg, #0369a1, #0891b2);
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
    border-color: #0284c7 !important;
    white-space: nowrap;
    vertical-align: middle !important;
}
#dynamic-table-perjanjian-bymr tbody tr:hover {
    background-color: #f0f9ff !important;
}
</style>

<script>
jQuery(function($) {
    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true
    })
    .next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

    var oTablePerjanjianByMR;
    var base_url = $('#dynamic-table-perjanjian-bymr').attr('base-url');

    oTablePerjanjianByMR = $('#dynamic-table-perjanjian-bymr').DataTable({
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "bPaginate": false,
        "searching": false,
        "bInfo": false,
        "ajax": {
            "url": base_url,
            "type": "POST"
        },
        "columnDefs": [
            { "targets": [0], "orderable": false }
        ],
        "language": {
            "processing":  '<i class="fa fa-spinner fa-spin"></i> Memuat data...',
            "zeroRecords": 'Tidak ada perjanjian ditemukan',
            "emptyTable":  'Tidak ada data tersedia'
        }
    });
});

$('input[name="flag"]').click(function (e) {
    var value = $(this).val();
    var no_mr = $('#no_mr_pasien_perjanjian').val();
    oTablePerjanjianByMR.ajax.url('registration/Perjanjian_rj/get_data?no_mr=' + no_mr + '&flag=' + value).load();
});

function cetak_surat_kontrol(ID, jd_id) {
    var no_mr = $('#tabs_riwayat_perjanjian_id').attr('data-id');
    if (no_mr == '') {
        alert('Silahkan cari pasien terlebih dahulu !'); return false;
    } else {
        url = 'registration/Reg_pasien/surat_control?id_tc_pesanan=' + ID + '&jd_id=' + jd_id + '';
        getMenu(url);
    }
}

function delete_perjanjian(id_tc_pesanan) {
    if (confirm('Yakin ingin menghapus data perjanjian ini?')) {
        preventDefault();
        $.ajax({
            url: 'registration/Input_perjanjian/delete',
            type: "post",
            data: {ID: id_tc_pesanan},
            dataType: "json",
            beforeSend: function() { achtungShowLoader(); },
            complete: function(xhr) {
                var data = xhr.responseText;
                var jsonResponse = JSON.parse(data);
                if (jsonResponse.status === 200) {
                    $.achtung({message: jsonResponse.message, timeout: 5});
                    var no_mr = $('#no_mr_pasien_perjanjian').val();
                    oTablePerjanjianByMR.ajax.url('registration/Perjanjian_rj/get_data?no_mr=' + no_mr + '&flag=RJ').load();
                } else {
                    $.achtung({message: jsonResponse.message, timeout: 5});
                }
                achtungHideLoader();
            }
        });
    } else {
        return false;
    }
}
</script>

<div class="row">
  <div class="col-xs-12">

    <!-- Section title -->
    <div class="prjmr-section-title">
      <span class="prjmr-badge"><i class="fa fa-calendar"></i> Perjanjian Pasien</span>
    </div>

    <form class="form-horizontal" method="post" id="form_search" action="registration/Perjanjian_rj/find_data">
      <input type="hidden" name="no_mr_pasien_perjanjian" id="no_mr_pasien_perjanjian" value="<?php echo $no_mr?>">

      <!-- Filter bar -->
      <div class="prjmr-filter-bar">

        <!-- Pelayanan radios -->
        <div class="prjmr-fg">
          <label>Jenis Pelayanan</label>
          <div class="prjmr-radio-group">
            <label class="prjmr-radio-item">
              <input name="flag" type="radio" class="ace" value="NULL" checked>
              <span>Rawat Jalan</span>
            </label>
            <label class="prjmr-radio-item">
              <input name="flag" type="radio" class="ace" value="bedah">
              <span>Bedah</span>
            </label>
            <label class="prjmr-radio-item">
              <input name="flag" type="radio" class="ace" value="HD">
              <span>Hemodialisa</span>
            </label>
          </div>
        </div>

        <!-- Tanggal -->
        <div class="prjmr-fg">
          <label>Tanggal</label>
          <div class="input-group">
            <input name="tanggal" id="tanggal" placeholder="<?php echo date('Y-m-d')?>"
                   data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
          </div>
        </div>

        <!-- Buttons -->
        <div class="prjmr-fg">
          <label>&nbsp;</label>
          <div style="display:flex; gap:6px;">
            <a href="#" id="btn_search_data" class="btn btn-sm btn-primary">
              <i class="fa fa-search"></i> Cari
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-sm btn-default">
              <i class="fa fa-refresh"></i> Reset
            </a>
          </div>
        </div>

      </div>
    </form>

    <!-- DataTable -->
    <table id="dynamic-table-perjanjian-bymr"
           base-url="registration/Perjanjian_rj/get_data?no_mr=<?php echo $no_mr?>"
           class="table table-bordered table-hover table-condensed"
           style="font-size:12px; width:100%">
      <thead>
        <tr>
          <th width="60px" class="center">Aksi</th>
          <th width="60px" class="center" style="display:none;"></th>
          <th>Tujuan Poli / Dokter</th>
          <th width="110px" class="center">Tgl Kontrol</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

  </div>
</div>
