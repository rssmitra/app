<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>

var interval;
var table;
var base_url = $('#dynamic-table').attr('base-url'); 

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
  
    $('#form_Tmp_mst_function').ajaxForm({
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
          $('#page-area-content').load('registration/Reg_on_dashboard?_=' + (new Date()).getTime());
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    }); 

    table = $('#dynamic-table').DataTable( {
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "scrollX": false,
        "ordering": false,
        "bProcessing": false,
        "animate": true,
        "searching":false,
        "pageLength":25,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"/get_data",
            "type": "POST"
        },

    });

    $('#btn_search_data').click(function (e) {
      if($('#from_tgl').val()!=''){
        if($('#to_tgl').val()!=''){
          clearInterval(interval);
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
              find_data_reload(data,base_url);
            }
          });
        } else{
          $.achtung({message: "Silahkan isi kolom s/d tanggal", timeout:5});
        }
      }else{
        clearInterval(interval);
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
            find_data_reload(data,base_url);
          }
        });
      }
            
    });

    $('#btn_reset_data').click(function (e) {
            e.preventDefault();
            reset_table();
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

    interval = setInterval( function () {
        
        table.ajax.reload( null, false ); // user paging is not reset on reload
        
    }, 15000 );
    
})

$('select[name="bagian"]').change(function () {      

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

$('#inputDokter').typeahead({
    source: function (query, result) {
        $.ajax({
            url: "Templates/References/getDokter",
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
      var val_item=item.split(':')[0];
      console.log(val_item);
      $('#dokterHidden').val(val_item);
    }
});

// $('#btn_search_data').click(function (e) {
  
//   var url_search = $('#form_search').attr('action');
//   e.preventDefault();
//   $.ajax({
//     url: url_search,
//     type: "post",
//     data: $('#form_search').serialize(),
//     dataType: "json",
//     success: function(data) {
//       console.log(data.data);
//       find_data_reload(data);
//     }
//   });
// });

function find_data_reload(result){

  table.ajax.url(base_url+'/get_data?'+result.data).load();
  $("html, body").animate({ scrollTop: "400px" });

}

function reset_table(){
  table.ajax.url(base_url+'/get_data').load();
  $("html, body").animate({ scrollDown: "400px" });
  interval = setInterval( function () {
      
      table.ajax.reload( null, false ); // user paging is not reset on reload
      
  }, 3000 );
}

function popUnder(node) {
    var newWindow = window.open("about:blank", node.target, "width=700,height=500"); 
    window.focus();
    newWindow.location.href = node.href;
    return false;
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

    <form class="form-horizontal" method="post" id="form_search" action="rekam_medis/Tracer/find_data">

    <div class="col-md-12">

    <center><h4>FORM PENCARIAN DATA TRACER PASIEN<br><small style="font-size:12px">Data yang ditampilkan saat ini adalah data per Hari ini yaitu tanggal <?php echo date('d/m/Y')?> </small></h4></center>
      <br>

      <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
            <select name="search_by" class="form-control">
              <option value="no_mr">No MR</option>
              <option value="nama_pasien">Nama Pasien</option>
            </select>
          </div>

          <label class="control-label col-md-1">Keyword</label>
          <div class="col-md-2">
            <input type="text" class="form-control" name="keyword">
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d</label>
          <div class="col-md-2" style="margin-lef:-10px">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
      </div>


      <div class="form-group">
        <label class="control-label col-md-2">Poli/Klinik</label>
        <div class="col-md-3">
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1,'status_aktif' => 1), 'where_in' => array('col' => 'validasi', 'val' => array('0100','0300','0500')) ), '' , 'bagian', 'bagian', 'form-control', '', '') ?>
        </div>
        <label class="control-label col-md-1">Dokter</label>
          <div class="col-md-3">
              <input id="inputDokter" class="form-control" name="dokter" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" />
              <input type="hidden" name="dokterHidden" value="" id="dokterHidden">
          </div>
      </div>

      
      <div class="form-group">
        <label class="control-label col-md-2 ">&nbsp;</label>
        <div class="col-md-10" style="margin-left: 5px">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
          <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
            <i class="fa fa-file-word-o bigger-110"></i>
            Export Excel
          </a>
        </div>
      </div>

    </div>

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="rekam_medis/Tracer" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th class="center" width="110px"></th>
          <th width="80px">No. Reg</th>
          <th>No MR</th>
          <th>Nama Pasien</th>
          <th>Penjamin</th>
          <th>Tanggal Registrasi</th>
          <th>Tujuan Bagian</th>
          <th>Nama Dokter</th>  
          <th>Petugas</th>         
          <th>Status Pasien</th>      
          <th style="min-width: 70px">Tracer</th>      
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->




