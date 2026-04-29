<style>
  /* ===== Page Header ===== */
  .page-header { border-bottom: 2px solid #0ea5e9 !important; margin-bottom: 18px; padding-bottom: 10px; }
  .page-header h1 { font-size: 20px; font-weight: 700; color: #1e3a5f; }
  .page-header h1 small { font-size: 13px; color: #64748b; }

  /* ===== Search Card ===== */
  .ri-search-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 18px 22px 14px;
    -webkit-box-shadow: 0 1px 6px rgba(0,0,0,.07);
    box-shadow: 0 1px 6px rgba(0,0,0,.07);
    margin-bottom: 18px;
  }
  .ri-form-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #0369a1;
    border-left: 3px solid #0ea5e9;
    padding-left: 10px;
    margin: 0 0 14px;
    line-height: 1.5;
  }
  .ri-form-title small {
    display: block;
    font-size: 11px;
    font-weight: 400;
    color: #64748b;
    margin-top: 3px;
  }

  /* ===== Form controls ===== */
  .ri-search-card .control-label { font-size: 12px; font-weight: 600; color: #374151; padding-top: 7px; }
  .ri-search-card .form-control,
  .ri-search-card select.form-control {
    font-size: 12.5px; border-color: #d1d5db; border-radius: 6px;
  }
  .ri-search-card .form-control:focus {
    border-color: #0ea5e9;
    -webkit-box-shadow: 0 0 0 3px rgba(14,165,233,.12);
    box-shadow: 0 0 0 3px rgba(14,165,233,.12);
  }
  .ri-search-card select:not(.form-control) {
    display: inline-block; height: 32px; padding: 4px 8px;
    font-size: 12.5px; border: 1px solid #d1d5db; border-radius: 6px;
    color: #374151; background: #fff;
  }

  /* ===== Action row ===== */
  .ri-action-row {
    padding-top: 10px; margin-top: 4px;
    border-top: 1px solid #f1f5f9;
    display: -webkit-flex; display: flex;
    -webkit-flex-wrap: wrap; flex-wrap: wrap;
    gap: 6px;
  }
  .ri-btn-search {
    background: -webkit-linear-gradient(135deg, #0369a1, #0ea5e9);
    background: linear-gradient(135deg, #0369a1, #0ea5e9);
    border: none; color: #fff !important;
    font-size: 12.5px; font-weight: 600; border-radius: 6px;
    padding: 6px 16px; cursor: pointer; text-decoration: none;
    display: inline-block;
    -webkit-transition: opacity .18s; transition: opacity .18s;
  }
  .ri-btn-search:hover { opacity: .88; color: #fff !important; text-decoration: none; }
  .ri-btn-reset {
    background: #f1f5f9; border: 1px solid #e2e8f0;
    color: #475569 !important; font-size: 12.5px; font-weight: 600;
    border-radius: 6px; padding: 6px 14px; cursor: pointer;
    text-decoration: none; display: inline-block;
    -webkit-transition: background .15s; transition: background .15s;
  }
  .ri-btn-reset:hover { background: #e2e8f0; color: #1e3a5f !important; text-decoration: none; }

  /* ===== Separator ===== */
  .ri-search-card hr { border-color: #e2e8f0; margin: 12px 0; }

  /* ===== DataTable override ===== */
  #dynamic-table thead tr th {
    background: -webkit-linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%);
    background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%);
    color: #fff; font-size: 11.5px; font-weight: 700;
    border-color: #1d4ed8 !important;
    white-space: nowrap;
  }
  #dynamic-table tbody tr td { font-size: 12px; color: #334155; vertical-align: middle; }
  #dynamic-table tbody tr:hover { background: #eff6ff !important; }
</style>

<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
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

  $( "#form_search" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){
          event.preventDefault();
          $('#btn_search_data').click();
          return false;
        }
  });

  oTable = $('#dynamic-table').DataTable({

    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": false,
    "bPaginate": true,
    "bInfo": true,
    "pageLength": 25,
    // Load data for the table's content from an Ajax source
    /*"ajax": {
        "url": "pelayanan/Pl_pelayanan_ri/get_data?search_by="+$("#search_by").val()+"&keyword="+$("#keyword_form").val()+"&from_tgl="+$("#from_tgl").val()+"&to_tgl="+$("#to_tgl").val()+"",
        "type": "POST"
    },*/
     "ajax": {
        "url": "pelayanan/Pl_pelayanan_ri/get_data?search_by="+$("#search_by").val()+"&keyword="+$("#keyword_form").val()+"&is_icu="+$("#is_icu").val()+"",
        "type": "POST"
    },
    "columnDefs": [
        {
          "targets": [ -1 ], //last column
          "orderable": false, //set not orderable
        },
        {"aTargets" : [0], "mData" : 2, "sClass":  "details-control"},
        { "visible": false, "targets": [1,2] },
      ],

  });

  $('#dynamic-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );
            var data = oTable.row( $(this).parents('tr') ).data();
            var no_registrasi = data[ 0 ];
            var tipe = data[ 1 ];


            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/

                $.getJSON("billing/Billing/getDetail/" + no_registrasi + "/" + tipe, '', function (data) {
                    response_data = data;
                     // Open this row
                    row.child( format( response_data ) ).show();
                    tr.addClass('shown');
                });

            }
    } );


      $('#btn_search_data').click(function (e) {
          e.preventDefault();
          $.ajax({
          url: 'pelayanan/Pl_pelayanan_ri/find_data',
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();
          },
          success: function(data) {
            achtungHideLoader();
            find_data_reload(data,'pelayanan/Pl_pelayanan_ri');
          }
        });
      });

      $('#btn_reset_data').click(function (e) {
          e.preventDefault();
          find_data_reload();
      });

})

function format ( data ) {
    return data.html;
}

function getBillingDetail(noreg, type, field){
  preventDefault();
  $.getJSON("billing/Billing/getRincianBilling/" + noreg + "/" + type + "/" +field, '', function (data) {
      response_data = data;
      html = '';
      html += '<div class="center"><p><b>RINCIAN BIAYA '+field+'</b></p></div>';
      //alert(response_data.html); return false;
      $('#detail_item_billing_'+noreg+'').html(data.html);
  });

}

function find_data_reload(result=''){

  oTable.ajax.url('pelayanan/Pl_pelayanan_ri/get_data?'+result.data).load();
  $("html, body").animate({ scrollTop: "400px" });

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
          getMenu('pelayanan/Pl_pelayanan_ri');
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }
        achtungHideLoader();
      }
  });

}

if(!ace.vars['touch']) {
        $('.chosen-select').chosen({allow_single_deselect:true});
    //resize the chosen on window resize

    $(window)
    .off('resize.chosen')
    .on('resize.chosen', function() {
      $('.chosen-select').each(function() {
          var $this = $(this);
          $this.next().css({'width': $this.parent().width()});
      })
    }).trigger('resize.chosen');

  }

</script>
<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan_ri/find_data">

      <!-- Search Card -->
      <div class="ri-search-card">

        <div class="ri-form-title">
          <i class="fa fa-search" style="margin-right:6px;color:#0ea5e9"></i>
          Form Pencarian Data Pasien <?php echo ($is_icu=='N')?'Rawat Inap':'ICU'; ?>
          <small>Data Pasien yang masih dirawat sampai hari ini, <?php echo $this->tanggal->formatDate(date('Y-m-d'))?></small>
        </div>

        <!-- hidden form -->
        <input type="hidden" name="is_icu" value="<?php echo $is_icu ?>" id="is_icu">

        <div class="form-group">
            <label class="control-label col-md-2">Pencarian berdasarkan</label>
            <div class="col-md-2">
              <select name="search_by" id="search_by" class="form-control">
                <option value="">-Silahkan Pilih-</option>
                <option value="no_mr" selected>No MR</option>
                <option value="nama_pasien">Nama Pasien</option>
              </select>
            </div>

            <label class="control-label col-md-1">Keyword</label>
            <div class="col-md-2">
              <input type="text" class="form-control" name="keyword" id="keyword_form">
            </div>

            <label class="control-label col-md-1">Status</label>
            <div class="col-md-2">
                <select name="status_ranap" id="status_ranap" class="form-control">
                  <option value="" selected>- Silahkan Pilih -</option>
                  <option value="masih dirawat">Masih dirawat</option>
                  <option value="sudah pulang">Sudah Pulang</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2">Pilih Penjamin</label>
            <div class="col-md-3">
              <select name="penjamin" id="penjamin" class="form-control">
                  <option value="" selected>- Semua -</option>
                  <option value="120">BPJS Kesehatan</option>
                  <option value="229">BPJS Ketenagakerjaan</option>
                  <option value="1">Asuransi</option>
                  <option value="0">Umum</option>
                </select>
            </div>
            <label class="control-label col-md-1">Dokter</label>
            <div class="col-md-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array() ), '' , 'kode_dokter', 'kode_dokter', 'chosen-select form-control', '', '') ?>
            </div>
        </div>

        <div class="ri-action-row">
          <a href="#" id="btn_search_data" class="ri-btn-search">
            <i class="fa fa-search"></i>&nbsp;Cari Data
          </a>
          <a href="#" id="btn_reset_data" class="ri-btn-reset">
            <i class="fa fa-refresh"></i>&nbsp;Reset
          </a>
        </div>

      </div><!-- /.ri-search-card -->

      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="pelayanan/Pl_pelayanan_ri" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="50px">&nbsp;</th>
              <th width="50px">&nbsp;</th>
              <th></th>
              <th></th>
              <th>No MR</th>
              <th>Nama Pasien</th>
              <th>Tanggal Masuk</th>
              <th width="150px">Ruangan</th>
              <th>Penjamin</th>
              <th>Dokter yang merawat</th>
              <!-- <th>Kelas</th> -->
              <!-- <th>Hak Kelas</th> -->
              <th class="center"> InaCBG</th>
              <th class="center"> Billing</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->
