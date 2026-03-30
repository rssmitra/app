<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/sweetalert2.all.min.js"></script>

<style>
  .frm-card { border: 1px solid #d0dce8; border-radius: 5px; background: #fff; margin-bottom: 14px; box-shadow: 0 1px 4px rgba(44,111,173,.07); }
  .frm-card-header { background: #2c6fad; color: #fff; padding: 10px 16px; border-radius: 5px 5px 0 0; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; }
  .frm-card-body { padding: 18px 20px; }
  .frm-actions { display: flex; gap: 8px; justify-content: flex-end; padding: 10px 16px; background: #f0f5fb; border-top: 1px solid #d8e6f3; border-radius: 0 0 5px 5px; }

  .po-grid { display: flex; flex-wrap: wrap; }
  .po-cell { flex: 1 1 12.5%; padding: 10px 14px; border-right: 1px solid #e4eaf2; border-bottom: 1px solid #e4eaf2; min-width: 110px; }
  .po-cell:last-child { border-right: none; }
  .po-cell .pc-label { font-size: 10px; color: #8899aa; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 3px; }
  .po-cell .pc-value { font-size: 13px; font-weight: 700; color: #1e3a5f; }

  .sup-box { padding: 14px 16px; background: #f7fafd; border-radius: 0 0 5px 5px; font-size: 12px; color: #444; line-height: 1.7; min-height: 80px; }

  .frm-field-row { display: flex; align-items: center; margin-bottom: 10px; }
  .frm-field-label { min-width: 148px; text-align: right; padding-right: 14px; font-size: 12px; color: #555; font-weight: 600; flex-shrink: 0; }
  .frm-field-input { flex: 1; }

  .btn-area-top { display: flex; gap: 8px; justify-content: flex-end; margin-bottom: 10px; }

  .page-header-frm { border-bottom: 3px solid #2c6fad; padding-bottom: 10px; margin-bottom: 16px; }
  .page-header-frm h1 { font-size: 20px; margin: 0; color: #1a4f8a; font-weight: 700; }
  .page-header-frm h1 small { font-size: 13px; color: #888; font-weight: 400; }
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

$(document).ready(function(){

    // profile supplier
    get_profile_supplier($('#supplier_id_hidden').val());

    // get barang po
    get_barang_po();

    $('#form_penerimaan_brg').ajaxForm({
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

          if (jsonResponse.action=='header') {
            $('#section_form_penerimaan_barang').hide();
            $('#section_table_penerimaan_brg').show();
            // show result form
            show_penerimaan_brg_dt(jsonResponse.id);
          }else{
            var redirect = "purchasing/penerimaan/Penerimaan_brg/preview_penerimaan?ID="+jsonResponse.id+"&flag="+jsonResponse.flag+"";

            if (jsonResponse.harga_changes && jsonResponse.harga_changes.length > 0) {
              var fmt = function(n){ return parseFloat(n).toLocaleString('id-ID', {minimumFractionDigits:0, maximumFractionDigits:2}); };
              var tableRows = '';
              $.each(jsonResponse.harga_changes, function(i, item) {
                var selisih = item.selisih;
                var selisihStyle = selisih > 0 ? 'color:#1e7e34;font-weight:bold' : (selisih < 0 ? 'color:#c82333;font-weight:bold' : '');
                var selisihText = (selisih > 0 ? '+' : '') + fmt(selisih);
                tableRows +=
                  '<tr style="border-bottom:1px solid #dee2e6">' +
                  '<td style="text-align:left;padding:5px 8px">' + item.kode_brg + '<br><small style="color:#555">' + item.nama_brg + '</small></td>' +
                  '<td style="text-align:right;padding:5px 8px">' + fmt(item.hna) + '</td>' +
                  '<td style="text-align:right;padding:5px 8px">' + fmt(item.disc) + '%<br><small>Rp ' + fmt(item.disc_rp) + '</small></td>' +
                  '<td style="text-align:right;padding:5px 8px">' + fmt(item.ppn) + '%<br><small>Rp ' + fmt(item.ppn_rp) + '</small></td>' +
                  '<td style="text-align:right;padding:5px 8px">' + fmt(item.harga_satuan_kecil_sbl_ppn) + '</td>' +
                  '<td style="text-align:right;padding:5px 8px">' + fmt(item.harga_lama) + '</td>' +
                  '<td style="text-align:right;padding:5px 8px;font-weight:bold">' + fmt(item.harga_baru) + '</td>' +
                  '<td style="text-align:right;padding:5px 8px;' + selisihStyle + '">' + selisihText + '</td>' +
                  '</tr>';
              });

              Swal.fire({
                title: '<strong style="font-size:18px">Penerimaan Barang Selesai</strong>',
                html:
                  '<p style="margin-bottom:10px;color:#555">Berikut informasi perubahan harga dasar jual :</p>' +
                  '<div style="overflow-x:auto">' +
                  '<table style="width:100%;font-size:12px;border-collapse:collapse;text-align:center">' +
                  '<thead><tr style="background:#4e73df;color:#fff">' +
                  '<th style="padding:6px 8px;text-align:left">Barang</th>' +
                  '<th style="padding:6px 8px; text-align: right">HNA (Rp)</th>' +
                  '<th style="padding:6px 8px; text-align: right">Diskon</th>' +
                  '<th style="padding:6px 8px; text-align: right">PPN</th>' +
                  '<th style="padding:6px 8px; text-align: right">H. Beli Supplier</th>' +
                  '<th style="padding:6px 8px; text-align: right">H. Jual Lama</th>' +
                  '<th style="padding:6px 8px; text-align: right">H. Jual Baru</th>' +
                  '<th style="padding:6px 8px; text-align: right">Selisih</th>' +
                  '</tr></thead>' +
                  '<tbody>' + tableRows + '</tbody>' +
                  '</table></div>',
                icon: 'success',
                width: '90%',
                confirmButtonText: 'OK, Lihat Preview',
                confirmButtonColor: '#4e73df',
              }).then(function() {
                $('#page-area-content').load(redirect);
              });
            } else {
              $('#page-area-content').load(redirect);
            }
          }


          // id penerimaan brg
          $('#id').val(jsonResponse.id);
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    });

    var flag = ( $('#flag_string').val() ) ? $('#flag_string').val() : '' ;
    var search_by = $('select[name="search_by"]').val();
    var keyword = $('#inputKeyWord').val();

    $('#btn_search_brg').click(function (e) {

        if ( $('#inputKeyWord').val()=='' ) {
          alert('Silahkan Masukan Kata Kunci !'); return false;
        }

        search_selected_brg(flag, search_by, keyword);

        e.preventDefault();

    });

    $( "#inputKeyWord" ).keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
            search_selected_brg(flag, search_by, keyword);
          }
          return false;
        }
    });

})

function show_penerimaan_brg_dt(id){
  $('#section_table_penerimaan_brg').load('purchasing/penerimaan/Penerimaan_brg/show_penerimaan_brg/'+id+'?flag='+$('#flag_string').val()+'');
  // drop attr disabled
  $('#table_brg_penerimaan input[type=checkbox]').attr('disabled', false);
}

function get_profile_supplier(id){
  if (!id) return;
  $.getJSON("<?php echo site_url('Templates/References/getSupplierById') ?>/" + id, '', function (response) {
      // detail supplier
      $('#dikirim').val(response.namasupplier);
      $('#inputSupplierPenerimaan').val(response.namasupplier);
      $('#detail_supplier').html(
        '<div style="font-size:13px;font-weight:700;color:#1a4f8a;margin-bottom:4px">' + response.namasupplier + '</div>' +
        '<div style="font-size:11px;color:#666;line-height:1.7">' +
          '<i class="fa fa-map-marker" style="width:14px;color:#999"></i> ' + response.alamat + '<br>' +
          '<i class="fa fa-phone" style="width:14px;color:#999"></i> ' + response.telpon1 +
        '</div>'
      );
  });
}

function get_barang_po(){
  $('#section_barang_po').load('purchasing/penerimaan/Penerimaan_brg/get_barang_po_penerimaan?id='+$('#id_tc_po').val()+'&flag='+$('#flag_string').val()+'');

  if( $('#id').val() == '' ){
    $('#table_brg_penerimaan input[type=checkbox]').attr('disabled', true);
  }else{
    $('#table_brg_penerimaan input[type=checkbox]').attr('disabled', false);
  }

}

function search_selected_brg(flag, search_by, keyword){

  $.ajax({ //Process the form using $.ajax()
      type      : 'POST', //Method type
      url       : 'Templates/References/getRefBrg', //Your form processing file URL
      data      : {keyword: $('#inputKeyWord').val(), flag: flag, search_by: search_by}, //Forms name
      dataType  : 'json',
      success   : function(data) {
          $('#show_detail_selected_brg').html(data.html);
      }
  })

}

function updatePenerimaan(){
  preventDefault();
  $('#table_brg_penerimaan input[type=checkbox]').prop('checked', false);
  $('#table_brg_penerimaan input[type=checkbox]').attr('disabled', true);
  $('#section_form_penerimaan_barang').show();
  $('#section_table_penerimaan_brg').hide();
}

// Typeahead supplier
$('#inputSupplierPenerimaan').typeahead({
  source: function(query, result) {
    $.ajax({
      url: 'templates/references/getSupplier',
      data: 'keyword=' + query,
      dataType: 'json',
      type: 'POST',
      success: function(response) {
        result($.map(response, function(item) { return item; }));
      }
    });
  },
  afterSelect: function(item) {
    var kode = item.split(':')[0];
    $('#supplier_id_hidden').val(kode);
    get_profile_supplier(kode);
  }
});

</script>

<div class="page-header-frm">
  <h1><?php echo $title?> <small><i class="ace-icon fa fa-angle-double-right"></i> <?php echo ($flag=='medis') ? 'Gudang Medis' : 'Gudang Non Medis'; ?></small></h1>
</div>

<div class="row" style="margin-bottom:6px">

  <!-- Supplier Info -->
  <div class="col-xs-3">
    <div class="frm-card" style="height:100%">
      <div class="frm-card-header"><i class="fa fa-truck"></i> Informasi Supplier</div>
      <div class="sup-box"><div id="detail_supplier"><span style="color:#aaa;font-size:12px"><i class="fa fa-spinner fa-spin"></i> Memuat...</span></div></div>
    </div>
  </div>

  <!-- PO Info -->
  <div class="col-xs-9">
    <div class="frm-card">
      <div class="frm-card-header"><i class="fa fa-file-text-o"></i> Informasi Purchase Order</div>
      <div class="po-grid">
        <div class="po-cell">
          <div class="pc-label">ID PO</div>
          <div class="pc-value"><?php echo isset($value->id_tc_po)?$value->id_tc_po:'-'?></div>
        </div>
        <div class="po-cell">
          <div class="pc-label">Nomor PO</div>
          <div class="pc-value"><?php echo isset($value->no_po)?$value->no_po:'-'?></div>
        </div>
        <div class="po-cell">
          <div class="pc-label">Tanggal PO</div>
          <div class="pc-value"><?php echo isset($value->tgl_po)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_po): date('Y-m-d') ?></div>
        </div>
        <div class="po-cell">
          <div class="pc-label">Est. Kirim</div>
          <div class="pc-value"><?php echo isset($value->tgl_kirim)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_kirim): date('Y-m-d') ?></div>
        </div>
        <div class="po-cell">
          <div class="pc-label">SIK AA</div>
          <div class="pc-value"><?php echo isset($value->sipa)?$value->sipa:'-'?></div>
        </div>
        <div class="po-cell">
          <div class="pc-label">Diajukan Oleh</div>
          <div class="pc-value"><?php echo isset($value->diajukan_oleh)?$value->diajukan_oleh:'-'?></div>
        </div>
        <div class="po-cell">
          <div class="pc-label">Disetujui Oleh</div>
          <div class="pc-value"><?php echo isset($value->disetujui_oleh)?$value->disetujui_oleh:'-'?></div>
        </div>
        <div class="po-cell">
          <div class="pc-label">Pembayaran</div>
          <div class="pc-value"><?php echo isset($value->term_of_pay)?$value->term_of_pay:'-'?></div>
        </div>
      </div>
    </div>
  </div>

</div>

<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_penerimaan_brg" action="<?php echo site_url('purchasing/penerimaan/Penerimaan_brg/process')?>" enctype="multipart/form-data" autocomplete="off">

      <!-- hidden inputs -->
      <input name="supplier_id_hidden" id="supplier_id_hidden" value="<?php echo isset($value->kodesupplier)?$value->kodesupplier:''?>" type="hidden">
      <input name="id_tc_po" id="id_tc_po" value="<?php echo isset($value->id_tc_po)?$value->id_tc_po:''?>" type="hidden">
      <input name="no_po" id="no_po" value="<?php echo isset($value->no_po)?$value->no_po:''?>" type="hidden">
      <input type="hidden" name="flag" id="flag_string" value="<?php echo $flag?>">
      <input type="hidden" name="kode_bagian" id="kode_bagian" value="<?php echo ($flag=='non_medis') ? '070101' : '060201' ; ?>">

      <!-- Form Penerimaan Barang -->
      <div id="section_form_penerimaan_barang" style="<?php if(isset($existing->id_penerimaan)) { echo 'display:none'; } ?>">
        <div class="frm-card">
          <div class="frm-card-header"><i class="fa fa-pencil-square-o"></i> Form Penerimaan Barang</div>
          <div class="frm-card-body">
            <div class="row">
              <div class="col-xs-6">

                <div class="frm-field-row">
                  <span class="frm-field-label">ID</span>
                  <div class="frm-field-input">
                    <input name="id" id="id" value="<?php echo isset($existing->id_penerimaan)?$existing->id_penerimaan : ''?>" class="form-control input-sm" type="text" placeholder="Auto" readonly style="width:80px">
                  </div>
                </div>

                <div class="frm-field-row">
                  <span class="frm-field-label">Tanggal Penerimaan</span>
                  <div class="frm-field-input">
                    <div class="input-group" style="width:165px">
                      <input class="form-control input-sm date-picker" name="tgl_penerimaan" id="tgl_penerimaan" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($existing->tgl_penerimaan)?$this->tanggal->formatDateTimeToSqlDate($existing->tgl_penerimaan) : date('Y-m-d')?>"/>
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                  </div>
                </div>

                <div class="frm-field-row">
                  <span class="frm-field-label">No. Penerimaan</span>
                  <div class="frm-field-input">
                    <input name="kode_penerimaan" id="kode_penerimaan" value="<?php echo isset($existing->kode_penerimaan)?$existing->kode_penerimaan : $format_nomor_penerimaan; ?>" class="form-control input-sm" type="text" readonly style="width:210px">
                  </div>
                </div>

                <div class="frm-field-row">
                  <span class="frm-field-label">No. Faktur</span>
                  <div class="frm-field-input">
                    <input name="no_faktur" id="no_faktur" value="<?php echo isset($existing->no_faktur)?$existing->no_faktur : ''; ?>" class="form-control input-sm" type="text" style="width:210px">
                  </div>
                </div>

              </div>
              <div class="col-xs-6">

                <div class="frm-field-row">
                  <span class="frm-field-label">Penerima</span>
                  <div class="frm-field-input">
                    <input name="petugas" id="petugas" value="<?php echo isset($existing->petugas)?$existing->petugas:$this->session->userdata('user')->fullname?>" class="form-control input-sm" type="text">
                  </div>
                </div>

                <div class="frm-field-row">
                  <span class="frm-field-label">Pengirim (a.n)</span>
                  <div class="frm-field-input">
                    <input name="dikirim" id="dikirim" value="<?php echo isset($existing->pengirim)?$existing->pengirim:''?>" class="form-control input-sm" type="text">
                  </div>
                </div>

                <div class="frm-field-row">
                  <span class="frm-field-label">Diketahui</span>
                  <div class="frm-field-input">
                    <input name="diketahui" id="diketahui" value="<?php echo isset($existing->diketahui) ? $existing->diketahui : (($flag=='medis') ? $this->master->get_ttd_data('ttd_ka_gdg_m','label') : $this->master->get_ttd_data('ttd_ka_gdg_nm','label')) ?>" class="form-control input-sm" type="text">
                  </div>
                </div>

                <div class="frm-field-row">
                  <span class="frm-field-label">Disetujui</span>
                  <div class="frm-field-input">
                    <input name="disetujui" id="disetujui" value="<?php echo isset($existing->disetujui) ? $existing->disetujui : $this->master->get_ttd_data('ttd_kasubag_pengadaan','label') ?>" class="form-control input-sm" type="text">
                  </div>
                </div>

                <div class="frm-field-row">
                  <span class="frm-field-label">Keterangan</span>
                  <div class="frm-field-input">
                    <textarea class="form-control input-sm" style="height:55px;resize:vertical" name="keterangan"><?php echo isset($existing->keterangan)?$existing->keterangan:'Sesuai PO'?></textarea>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="frm-actions">
            <button type="submit" name="submit" value="header" class="btn btn-sm btn-primary">
              <i class="fa fa-check"></i> Simpan &amp; Lanjutkan
            </button>
          </div>
        </div>
      </div>

      <?php if(isset($existing->id_penerimaan)): ?>
      <script type="text/javascript">
        $(document).ready(function(){
          show_penerimaan_brg_dt(<?php echo $existing->id_penerimaan?>);
        })
      </script>
      <?php endif; ?>

      <!-- Section: penerimaan brg result table -->
      <div id="section_table_penerimaan_brg"></div>

      <!-- Section: barang po -->
      <div id="section_table_brg">
        <div class="btn-area-top">
          <a onclick="getMenu('purchasing/penerimaan/Penerimaan_brg/view_data?flag=<?php echo $flag?>', 'tabs_form_po')" href="#" class="btn btn-sm btn-default">
            <i class="fa fa-arrow-left"></i> Kembali ke Daftar
          </a>
          <button type="reset" id="btnReset" class="btn btn-sm btn-warning">
            <i class="fa fa-refresh"></i> Reset
          </button>
          <button type="submit" name="submit" class="btn btn-sm btn-success" value="penerimaan_selesai">
            <i class="fa fa-check-circle"></i> Penerimaan Barang Selesai
          </button>
        </div>

        <div id="section_barang_po"></div>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->
