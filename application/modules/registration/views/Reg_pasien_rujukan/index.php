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

$('select[name="klinik"]').change(function () {      

    if ($(this).val()) {          

        $.getJSON("<?php echo site_url('Templates/References/getDokterByBagian') ?>/" + $(this).val() , function (data) {              

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

var oTable;
var base_url = $('#dynamic-table').attr('base-url'); 
var params = $('#dynamic-table').attr('data-id'); 

$(document).ready(function() {
  
  //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "paging": false,
      "searching": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": base_url+"/get_data",
          "type": "POST"
      },

    });

    $('#dynamic-table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
      
    $("#button_delete").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          delete_data(''+searchIDs+'')
          console.log(searchIDs);
    });

    // $('#btn_search_data').click(function (e) {
    //       e.preventDefault();
    //       $.ajax({
    //       url: base_url+'/find_data',
    //       type: "post",
    //       data: $('#form_search').serialize(),
    //       dataType: "json",
    //       beforeSend: function() {
    //         achtungShowLoader();  
    //       },
    //       success: function(data) {
    //         achtungHideLoader();
    //         find_data_reload(data,base_url);
    //       }
    //     });
    // });

    $('#btn_export_excel').click(function (e) {
        e.preventDefault();
        $.ajax({
        url: base_url+'/find_data',
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        success: function(data) {
          achtungHideLoader();
          export_excel(data);
        }
    });
         
  });

    $('#btn_reset_data').click(function (e) {
        e.preventDefault();
        reset_table();
        $('#form_search')[0].reset();
    });


});

$('#btn_search_data').click(function (e) {
    var url_search = $('#form_search').attr('action');
    e.preventDefault();
    $.ajax({
    url: url_search,
    type: "post",
    data: $('#form_search').serialize(),
    dataType: "json",
    success: function(data) {
      console.log(data.data);
      find_data_reload(data);
    }
  });
 });

function find_data_reload(result){

    oTable.ajax.url(base_url+'/get_data?'+result.data).load();
    // $("html, body").animate({ scrollTop: "400px" });

}

function export_excel(result){

  window.open(base_url+'/export_excel?'+result.data+'','_blank'); 

}

function reset_table(){
    oTable.ajax.url(base_url+'/get_data').load();
    // $("html, body").animate({ scrollDown: "400px" });

}

function reload_table(){
   oTable.ajax.reload(); //reload datatable ajax 
}
  

function delete_data(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: base_url+'/delete',
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
            reload_table();
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

    <form class="form-horizontal" method="post" id="form_search" action="registration/Riwayat_kunjungan_poli/find_data">

    <div class="col-md-12 no-padding">

      <!-- <center><h4>FORM PENCARIAN DATA PASIEN RUJUKAN<br><small style="font-size:12px">Data yang ditampilkan saat ini adalah data Tanggal <?php echo $this->tanggal->formatDate( date('Y-m-d') ) ?> </small></h4></center>
      <br> -->

      <!-- <div class="form-group">
        <label class="control-label col-md-2">Bulan</label>
          <div class="col-md-2">
            <?php echo $this->master->get_bulan('' , 'bulan', 'bulan', 'form-control', '','') ?>
          </div>
          <label class="control-label col-md-1">Tahun</label>
          <div class="col-md-2">
            <?php echo $this->master->get_tahun('' , 'tahun', 'tahun', 'form-control', '', '') ?>
          </div>
      </div> -->

      <!-- <div class="form-group">
          <label class="control-label col-md-2">Bagian</label>
          <div class="col-md-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1, 'validasi' => '0300', 'status_aktif' => 1) ), '' , 'bagian_asal', 'bagian_asal', 'form-control', '', '') ?>
          </div>
      </div> -->

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Dirujuk</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <div class="col-md-4">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Search
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
              <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
              Reset
            </a>
            <!-- <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
              <i class="fa fa-file-word-o bigger-110"></i>
              Export Excel
            </a> -->
          </div>

      </div>


    </div>

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="registration/Reg_pasien_rujukan" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center">No</th>
          <th width="50px">No MR</th>
          <th>Nama Pasien</th>
          <th>Tanggal Dirujuk</th>
          <th>Asal Rujukan</th>
          <th>Dokter Perujuk</th>
          <th>Kamar</th>
          <th>Pengantar Rawat</th>
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




