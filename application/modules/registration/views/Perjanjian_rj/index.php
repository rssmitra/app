<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<style>
/* ── Filter card ── */
.prj-filter-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px 20px 12px;
    margin-bottom: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.prj-filter-title {
    font-size: 11px;
    font-weight: 700;
    color: #0891b2;
    text-transform: uppercase;
    letter-spacing: .6px;
    margin: 0 0 12px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e0f2fe;
}
.prj-filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px 14px;
    margin-bottom: 10px;
    align-items: flex-end;
}
.prj-fg {
    display: flex;
    flex-direction: column;
    min-width: 140px;
}
.prj-fg.fg-sm  { min-width: 130px; max-width: 170px; }
.prj-fg.fg-md  { min-width: 170px; max-width: 260px; }
.prj-fg.fg-lg  { flex: 1; min-width: 200px; }
.prj-fg label {
    font-size: 11px;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 4px;
    white-space: nowrap;
}
/* Direct-child selectors avoid conflicting with Bootstrap's input-group table-cell layout */
.prj-fg > select,
.prj-fg > input[type="text"] {
    height: 30px;
    font-size: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 0 8px;
    color: #1e293b;
    background: #fff;
    width: 100%;
    box-sizing: border-box;
}
/* Bootstrap 3 uses display:table-cell for input-group — do NOT override display.
   Only set height/font; Bootstrap handles border-radius, border joins, and width. */
.prj-fg .input-group > .form-control {
    height: 30px;
    font-size: 12px;
    border-color: #cbd5e1;
}
.prj-fg .input-group-addon {
    height: 30px;
    padding: 4px 9px;
    font-size: 12px;
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    cursor: pointer;
}
.prj-date-sep {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    padding-bottom: 6px;
    align-self: flex-end;
}
.prj-btn-row {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* ── Table header ── */
#dynamic-table thead th {
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
#dynamic-table tbody tr:hover {
    background-color: #f0f9ff !important;
}

/* ── Modal ── */
.prj-modal-header {
    background: linear-gradient(135deg, #0369a1, #0891b2);
    padding: 12px 16px;
    border-radius: 4px 4px 0 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.prj-modal-header .prj-modal-title {
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .4px;
    text-transform: uppercase;
}
.prj-modal-header .close {
    color: #fff;
    opacity: .8;
    font-size: 18px;
    line-height: 1;
}
.prj-modal-header .close:hover { opacity: 1; }
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
});

$('select[name="klinik"]').change(function () {
    if ($(this).val()) {
        $.getJSON("<?php echo site_url('Templates/References/getDokterByBagian_') ?>/" + $(this).val(), function (data) {
            $('#dokter option').remove();
            $('<option value="">-Pilih Dokter-</option>').appendTo($('#dokter'));
            $.each(data, function (i, o) {
                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#dokter'));
            });
        });
    } else {
        $('#dokter option').remove();
    }
});

$(".form-control").keypress(function(event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == 13) {
        event.preventDefault();
        if ($(this).valid()) {
            $('#btn_search_data').focus();
        }
        return false;
    }
});

function showModalDaftarPerjanjian(booking_id, no_mr) {
    $('#result_text_daftar_perjanjian').text('DAFTAR PERJANJIAN PASIEN NO MR (' + no_mr + ')');
    $('#form_daftar_perjanjian_pasien_modal').load('registration/reg_pasien/form_perjanjian_modal/' + no_mr + '?ID=' + booking_id);
    $("#modalDaftarPerjanjian").modal();
}

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
                    reload_table();
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

function saveRow(id_tc_pesanan) {
    preventDefault();
    $.ajax({
        url: 'registration/Perjanjian_rj/saveNoSuratKontrol',
        type: "post",
        data: {ID: id_tc_pesanan, no_surat_kontrol: $('#surat_kontrol_' + id_tc_pesanan + '').val()},
        dataType: "json",
        complete: function(xhr) {
            var data = xhr.responseText;
            var jsonResponse = JSON.parse(data);
            if (jsonResponse.status === 200) {
                $.achtung({message: jsonResponse.message, timeout: 5});
                reload_table();
            } else {
                $.achtung({message: jsonResponse.message, timeout: 5, className: 'achtungFail'});
            }
            achtungHideLoader();
        }
    });
}
</script>

<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs) ? $breadcrumbs : ''?>
        </small>
      </h1>
    </div>

    <form method="post" id="form_search" action="registration/Perjanjian_rj/find_data">

      <!-- Filter Card -->
      <div class="prj-filter-card">
        <div class="prj-filter-title"><i class="fa fa-filter"></i> Filter Pencarian Data Perjanjian</div>

        <!-- Row 1: Search + Poli + Dokter -->
        <div class="prj-filter-row">
          <div class="prj-fg fg-sm">
            <label>Cari Berdasarkan</label>
            <select name="search_by" class="form-control">
              <option value="no_mr">No MR</option>
              <option value="nama">Nama Pasien</option>
            </select>
          </div>
          <div class="prj-fg fg-md">
            <label>Keyword</label>
            <input type="text" class="form-control" name="keyword" placeholder="Ketik kata kunci...">
          </div>
          <div class="prj-fg fg-lg">
            <label>Poli / Klinik</label>
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100)), '', 'klinik', 'klinik', 'form-control', '', '') ?>
          </div>
          <div class="prj-fg fg-lg">
            <label>Dokter</label>
            <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '', 'dokter', 'dokter', 'form-control', '', '') ?>
          </div>
        </div>

        <!-- Row 2: Tanggal + Buttons -->
        <div class="prj-filter-row">
          <div class="prj-fg fg-sm">
            <label>Tgl Input Perjanjian</label>
            <div class="input-group">
              <input class="form-control date-picker" name="tgl_input_prj" id="tgl_input_prj" type="text" data-date-format="yyyy-mm-dd" value="" placeholder="yyyy-mm-dd">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>

          <div class="prj-fg fg-sm">
            <label>Tgl Kontrol Dari</label>
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="" placeholder="yyyy-mm-dd">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>

          <div class="prj-date-sep">s/d</div>

          <div class="prj-fg fg-sm">
            <label>Tgl Kontrol Sampai</label>
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="" placeholder="yyyy-mm-dd">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>

          <div class="prj-fg" style="min-width:auto;">
            <label>&nbsp;</label>
            <div class="prj-btn-row">
              <a href="#" id="btn_search_data" class="btn btn-sm btn-primary">
                <i class="fa fa-search"></i> Cari
              </a>
              <a href="#" id="btn_reset_data" class="btn btn-sm btn-default">
                <i class="fa fa-refresh"></i> Reset
              </a>
              <a href="#" id="btn_export_excel" class="btn btn-sm btn-success">
                <i class="fa fa-file-excel-o"></i> Export Excel
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- DataTable -->
      <table id="dynamic-table"
             base-url="registration/Perjanjian_rj"
             class="table table-bordered table-hover table-condensed"
             style="font-size:12px; width:100%">
        <thead>
          <tr>
            <th width="30px"  class="center">No</th>
            <th width="60px"  class="center">Aksi</th>
            <th>Nama Pasien</th>
            <th width="200px">Dokter / Poli / Klinik</th>
            <th width="110px" class="center">Tgl Kontrol</th>
            <th width="110px">No Telp / HP</th>
            <th width="110px" class="center">Jenis Kunjungan</th>
            <th width="130px">No Kartu BPJS</th>
            <th width="130px">No Surat Kontrol</th>
            <th width="100px" class="center">Tgl Input</th>
            <th>Keterangan</th>
            <th width="90px"  class="center">Status</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>

    </form>

  </div>
</div>

<!-- Modal Daftar Perjanjian -->
<div id="modalDaftarPerjanjian" class="modal fade" tabindex="-1">
  <div class="modal-dialog" style="width:70%; margin-top:50px; margin-bottom:50px;">
    <div class="modal-content">
      <div class="prj-modal-header">
        <span class="prj-modal-title"><i class="fa fa-calendar"></i> <span id="result_text_daftar_perjanjian">Perjanjian Pasien</span></span>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body" style="max-height:80vh; overflow-y:auto;">
        <div id="form_daftar_perjanjian_pasien_modal"></div>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>
