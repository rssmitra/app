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

    oTable = $('#dynamic-table').DataTable({ 
            
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "ordering": false,
        "searching": false,
        "bPaginate": true,
        "bInfo": false,
        "pageLength": 25,
        "ajax": {
            "url": "pelayanan/Pl_pelayanan/get_data_entry_billing?bag=0&form=billing_entry",
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },
            {"aTargets" : [0], "mData" : 1, "sClass":  "details-control"}, 
            { "visible": false, "targets": [1,2,3] },
            ],

    });

    $('#dynamic-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTable.row( tr );
        var data = oTable.row( $(this).parents('tr') ).data();
        var no_kunjungan = data[ 2 ];
        var no_registrasi = data[ 3 ];
        

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/
            
            $.getJSON("pelayanan/Pl_pelayanan/view_detail_resume_medis/" + no_registrasi+"/"+no_kunjungan , '', function (data) {
                response_data = data;
                // Open this row
                row.child( format( response_data ) ).show();
                tr.addClass('shown');
            });
            
        }
    });

  
    $('#btn_search_data').click(function (e) {
        e.preventDefault();
        $.ajax({
            url: 'tarif/Mst_tarif/find_data',
            type: "post",
            data: $('#form_search').serialize(),
            dataType: "json",
            beforeSend: function() {
            achtungShowLoader();  
            },
            success: function(data) {
            achtungHideLoader();
            find_data_reload(data,'tarif/Mst_tarif');
            }
        });
    });

    $('#btn_reset_data').click(function (e) {
        e.preventDefault();
        find_data_reload();
    });

    $( ".form-control" )    
      .keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);  
        if(keycode ==13){   
          event.preventDefault();  
          $('#btn_search_data').click();  
          return false;  
        }  
    });


    $('#btn_update_session_poli').click(function (e) {  

        achtungShowLoader();

        $.ajax({
            url: "pelayanan/Pl_pelayanan/destroy_session_kode_bagian",
            data: { kode: $('#sess_kode_bagian').val()},            
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
                var data=xhr.responseText;  
                var jsonResponse = JSON.parse(data);  
                if(jsonResponse.status === 200){  
                $.achtung({message: jsonResponse.message, timeout:5}); 
                getMenu('pelayanan/Pl_pelayanan');
                }else{          
                $.achtung({message: jsonResponse.message, timeout:5});  
                } 
                achtungHideLoader();
            }
        });

    });

    $('select[name="poliklinik"]').change(function () {      

        $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val(), '', function (data) {              

            $('#select_dokter option').remove();                

            $('<option value="">-Pilih Dokter-</option>').appendTo($('#select_dokter'));                         

            $.each(data, function (i, o) {                  

                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#select_dokter'));                    
                    
            });      


        });    

    });

})

function format ( data ) {
  return data.html;
}

function find_data_reload(result=''){
    oTable.ajax.url('pelayanan/Pl_pelayanan/get_data_entry_billing?bag=0&form=billing_entry&'+result.data).load();
}

function reload_data(){
    oTable.ajax.url('pelayanan/Pl_pelayanan/get_data_entry_billing?bag=0&form=billing_entry').load();
}

function cancel_visit(no_registrasi, no_kunjungan){

    preventDefault();  

    achtungShowLoader();

    $.ajax({
        url: "pelayanan/Pl_pelayanan/cancel_visit",
        data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan, kode_bag: $('#sess_kode_bagian').val() },            
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
            var data=xhr.responseText;  
            var jsonResponse = JSON.parse(data);  
            if(jsonResponse.status === 200){  
            $.achtung({message: jsonResponse.message, timeout:5}); 
            getMenu('pelayanan/Pl_pelayanan');
            }else{          
            $.achtung({message: jsonResponse.message, timeout:5});  
            } 
            achtungHideLoader();
        }
    });

}

function rollback(no_registrasi, no_kunjungan, flag){

    preventDefault();  

    achtungShowLoader();

    $.ajax({
        url: "pelayanan/Pl_pelayanan/rollback",
        data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan, kode_bag: $('#kode_bagian_val').val(), flag: flag },            
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
            var data=xhr.responseText;  
            var jsonResponse = JSON.parse(data);  
            if(jsonResponse.status === 200){  
            $.achtung({message: jsonResponse.message, timeout:5}); 
            reload_table();
            //getMenu('pelayanan/Pl_pelayanan');
            }else{          
            $.achtung({message: jsonResponse.message, timeout:5});  
            } 
            achtungHideLoader();
        }
    });

}

function selesaikanKunjungan(no_registrasi, no_kunjungan){

  preventDefault(); 
  achtungShowLoader();
  $.ajax({
      url: "pelayanan/Pl_pelayanan/processSelesaikanKunjungan",
      data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan},            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
          var data=xhr.responseText;  
          var jsonResponse = JSON.parse(data);  
          if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          reload_data();
          }else{          
            $.achtung({message: jsonResponse.message, timeout:5});  
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
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan/find_data">

    <div class="col-md-12">

      <!-- <center><h4><?php echo strtoupper($nama_bagian); ?> <br> <small style="font-size:12px"><b><?php echo isset($nama_dokter)?'('.strtoupper($nama_dokter).')<br>':''?></b> </small><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data per Hari ini yaitu tanggal <?php echo $this->tanggal->formatDate(date('Y-m-d'))?> </small></h4></center>
      <br> -->

      <!-- hidden form -->
      <!-- <input type="hidden" name="sess_kode_bagian" value="<?php echo ($this->session->userdata('kode_bagian'))?$this->session->userdata('kode_bagian'):''?>" id="sess_kode_bagian"> -->
      <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
            <select name="search_by" class="form-control">
              <option value="">-Silahkan Pilih-</option>
              <option value="tc_kunjungan.no_mr" selected>No MR</option>
              <option value="pl_tc_poli.nama_pasien">Nama Pasien</option>
            </select>
          </div>

          <label class="control-label col-md-1">Keyword</label>
          <div class="col-md-2">
            <input type="text" class="form-control" name="keyword" id="keyword_form">
          </div>

      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Poli/Klinik</label>
        <div class="col-md-4">
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1)), '' , 'poliklinik', 'poliklinik', 'form-control', '', '') ?>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Dokter</label>
        <div class="col-md-4">
          <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'select_dokter', 'select_dokter', 'form-control', '', '') ?>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Registrasi</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2 ">&nbsp;</label>
        <div class="col-md-10" style="margin-left:6px">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-primary" action="pelayanan/Pl_pelayanan/find_data">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
        </div>
      </div>

    </div>

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="pelayanan/Pl_pelayanan/get_data_entry_billing?bag=0&form=billing_entry" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center"></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th>Kode</th>
          <th>No MR</th>
          <th>Nama Pasien</th>
          <th>Penjamin</th>
          <th width="150px">Tanggal Kunjungan</th>
          <th>Dokter</th>
          <th>Petugas</th>
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

<!-- <script src="<?php echo base_url().'assets/js/custom/als_datatable_custom_url.js'?>"></script> -->



