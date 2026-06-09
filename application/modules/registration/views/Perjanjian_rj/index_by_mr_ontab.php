<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<style>
/* ── Section title ── */
.prjtab-title {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}
.prjtab-title .prjtab-badge {
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
  <div class="col-md-12">

    <!-- Section title -->
    <div class="prjtab-title">
      <span class="prjtab-badge"><i class="fa fa-calendar-check-o"></i> Riwayat Perjanjian Pasien</span>
    </div>

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
