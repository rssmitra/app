<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
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

  kode_bagian = '<?php echo $kode_bagian ?>';
  url = (kode_bagian!=0)?'pelayanan/Pl_rekap_kunjungan/get_data?bagian_tujuan='+kode_bagian+' ':'pelayanan/Pl_rekap_kunjungan/get_data';
  oTable = $('#dynamic-table').DataTable({ 
          
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": false,
    "bPaginate": false,
    "bInfo": true,
    "pageLength": 25,
    "ajax": {
        "url": url,
        "type": "POST"
    },
    "drawCallback": function (response) { 
      // Here the response
        var objData = response.json;
        console.log(objData);
        $('#rekap_data tbody').remove();        
        var no = 0;
        $.each(objData.resume, function (i, o) {       
          no++;
            $('<tr><td align="center">'+no+'</td><td>'+o.unit+'</td><td align="center">'+o.total+'</td></tr>').appendTo($('#rekap_data'));   
        });
        $('.total_rekap').html(objData.recordsTotal);
        $('#rekap_batal').html(objData.rekap_batal);
        var total_berkunjung = parseInt(objData.recordsTotal) - parseInt(objData.rekap_batal);
        $('#total_berkunjung').html(total_berkunjung);

        $('#resume_rekap_data tbody').remove();  
        var noa = 0;      
        $.each(objData.rekap, function (k, v) {       
          noa++;
            $('<tr><td align="center">'+noa+'</td><td>'+v.unit+'</td><td align="center">'+v.total+'</td></tr>').appendTo($('#resume_rekap_data'));   
        });

        $('#resume_rekap_data_dr tbody').remove();  
        var nob = 0;      
        $.each(objData.rekap_dr, function (k, v) {       
          nob++;
            $('<tr><td align="center">'+nob+'</td><td>'+v.nama_dr+'</td><td align="center">'+v.total+'</td></tr>').appendTo($('#resume_rekap_data_dr'));   
        });

    },

  });

  $('#btn_search_data').click(function (e) {
      e.preventDefault();
      $.ajax({
      url: 'pelayanan/Pl_rekap_kunjungan/find_data',
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      beforeSend: function() {
        achtungShowLoader();  
      },
      success: function(data) {
        achtungHideLoader();
        find_data_reload(data,'pelayanan/Pl_rekap_kunjungan');
      }
    });
  });

  $('#btn_reset_data').click(function (e) {
        e.preventDefault();
        $('#form_search')[0].reset();
        reset_data();
    });

  $('#inputDokter').typeahead({
      source: function (query, result) {
              $.ajax({
                  url: "templates/references/getDokterByBagian",
                  data: 'keyword=' + query + '&bag=' + $('#bagian_asal').val(),         
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
          console.log(val_item);
          $('#dokter').val(val_item);
          
      }
  });

});

$('select[name="bagian_asal"]').change(function () {      

    if ($(this).val()) {          

        $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val() , function (data) {              

            $('#dokter option').remove();                

            $('<option value="">-Pilih Dokter-</option>').appendTo($('#dokter'));                

            $.each(data, function (i, o) {                  

                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#dokter'));                    

            });                

        });            

    } else {          

        $('#dokter option').remove()            

    }        

}); 

$( ".form-control" )  
  .keypress(function(event) {  
    var keycode =(event.keyCode?event.keyCode:event.which);  
    if(keycode ==13){    
      event.preventDefault();     
      if($(this).valid()){  
        $('#btn_search_data').click();  
      }    
      return false;   
    }  
}); 

function find_data_reload(result){

  oTable.ajax.url('pelayanan/Pl_rekap_kunjungan/get_data?'+result.data).load();
  $("html, body").animate({ scrollTop: "400px" });

}

function reset_data(){

oTable.ajax.url('pelayanan/Pl_rekap_kunjungan/get_data').load();
$("html, body").animate({ scrollTop: "400px" });

}

function cetak_surat_kematian(no_registrasi,no_kunjungan,umur) {
  
  url = 'pelayanan/Pl_pelayanan_igd/surat_kematian?no_kunjungan='+no_kunjungan+'&no_registrasi='+no_registrasi+'&umur='+umur;
  title = 'Cetak Surat Kematian';
  width = 850;
  height = 500;
  PopupCenter(url, title, width, height); 

}

function cetak_surat_keracunan(no_kunjungan,no_mr) {

  url = 'pelayanan/Pl_pelayanan_igd/surat_keracunan?no_kunjungan='+no_kunjungan+'&no_mr='+no_mr;
  title = 'Cetak Surat Keracunan';
  width = 1200;
  height = 1200;
  PopupCenter(url, title, width, height); 

}

function cetak_hasil(kode_mcu,id_pl_tc_poli) {
  
  url = 'pelayanan/Pl_pelayanan_mcu/cetak_hasil?kode_mcu='+kode_mcu+'&id_pl_tc_poli='+id_pl_tc_poli;
  title = 'Hasil MCU';
  width = 950;
  height = 650;
  PopupCenter(url, title, width, height); 

}

function rollback(no_registrasi, no_kunjungan){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan/rollback",
      data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan, kode_bag: $('#kode_bagian_val').val() },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          oTable.ajax.reload();
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
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

    <form class="form-horizontal" method="post" id="form_search" action="#">

    <div class="col-md-12 no-padding">

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Kunjungan</label>
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
          <div class="col-md-3 no-padding">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
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
    <br>
    <hr>
    <div class="tabbable" style="margin-top: 20px !important">
      <ul class="nav nav-tabs" id="myTab">
        <li class="active">
          <a data-toggle="tab" href="#datatable_tab">
            Data Table
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#rekap_data_tab">
            Rekap Data
          </a>
        </li>

      </ul>

      <div class="tab-content">
        <div id="datatable_tab" class="tab-pane fade in active">
          <p>Raw denim you probably haven't heard of them jean shorts Austin.</p>
          <table id="dynamic-table" base-url="pelayanan/Pl_rekap_kunjungan" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th width="30px" class="center">No</th>
                <th>No RM</th>
                <th>Nama Pasien</th>
                <th>Poliklinik</th>
                <th>Dokter</th>
                <th width="150px">Tanggal</th>
                <th>Status</th>
                
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

        <div id="rekap_data_tab" class="tab-pane fade">
          <div class="row">
            <div class="col-md-4">
              <p style="font-size: 14px; font-weight: bold;">REKAP DATA BERDASARKAN POLI</p>
              <table class="table" id="rekap_data">
                <thead>
                  <tr>
                    <th class="center" width="30px">No</th>
                    <th>Tujuan Kunjungan</th>
                    <th class="center" width="100px">Total</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr><td colspan="2" align="right"><b>TOTAL PASIEN</b></td><td align="center"><span class="total_rekap"></span></td></tr>
                </tfoot>
              </table>
            </div>

            <div class="col-md-4">
              <p style="font-size: 14px; font-weight: bold;">REKAP DATA BERDASARKAN DOKTER</p>
              <table class="table" id="resume_rekap_data_dr">
                <thead>
                  <tr>
                    <th class="center" width="30px">No</th>
                    <th>Nama Dokter</th>
                    <th class="center" width="100px">Total</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr><td colspan="2" align="right"><b>TOTAL PASIEN</b></td><td align="center"><span class="total_rekap"></span></td></tr>
                </tfoot>
              </table>
            </div>

            <div class="col-md-3">
              <p style="font-size: 14px; font-weight: bold;">REKAP KUJUNGAN PASIEN</p>
              <table class="table" id="resume_rekap_batal">
                  <tr>
                    <th class="center" width="30px">No</th>
                    <th>Deskripsi</th>
                    <th class="center" width="100px">Total</th>
                  </tr>
                  <tr>
                    <td class="center" width="30px">1</td>
                    <td>PASIEN TERDAFTAR</td>
                    <td class="center" width="100px"><span class="total_rekap"></span></td>
                  </tr>
                  <tr>
                    <td class="center" width="30px">2</td>
                    <td>PASIEN BATAL</td>
                    <td class="center" width="100px"><span id="rekap_batal"></span></td>
                  </tr>
                  <tr>
                    <td class="center" width="30px">&nbsp;</td>
                    <td align="right"><b>TOTAL PASIEN DATANG</b></td>
                    <td class="center" width="100px"><span id="total_berkunjung"></span></td>
                  </tr>
              </table>

            </div>

          </div>
        </div>

      </div>
    </div>
    

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<!-- <script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script> -->



