<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>

var interval;
var table;
var base_url = $('#table_antrian').attr('base-url'); 

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
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

    $('#btn_search_data').click(function (e) {
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
          $("#result_data tr").remove();
          $.each(data, function (i, o) {   
            tgl = formatDate(o.tanggal)

            $.each(o.data, function (e, a) {
              $('<tr><td>'+tgl+'</td><td>'+a.nomor+'</td><td>'+a.klinik+'</td><td>'+a.dokter+'</td><td>'+a.jam_praktek+'</td><td>'+a.type+'</td></tr>').appendTo($('#table_antrian'));                    
            });

           
          }); 

          $('#table_antrian_').show('fast');    
        }
      });  
    });

    $('#btn_reset_data').click(function (e) {
            e.preventDefault();
            reset_table();
    });

    $('#btn_export_detail').click(function (e) {
      
      clearInterval(interval);
      e.preventDefault();
      $.ajax({
        url: base_url+'/export_detail',
        type: "post",
        data: 'search_by=' + $('#search_by').val() + '&keyword=' + $('#keyword').val() + '&from_tgl=' + $('#from_tgl').val() + '&to_tgl=' + $('#to_tgl').val() + '&bagian=' + $('#bagian').val() + '&dokterHidden=' + $('#dokterHidden').val() + '$export_by=' + $('#export_by').val(),
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        success: function(data) {
          achtungHideLoader();
          console.log(data)
          $(window).load("laporan/Antrian_pasien/export_detail_view",data);
        }
      });
        
    });

       
})

$('#inputDokter').typeahead({
    source: function (query, result) {
        $.ajax({
            url: "Templates/References/getAllDokter",
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
      var val_item=item.split(':')[1];
      console.log(val_item);
      $('#dokterHidden').val(val_item);
    }
});

function formatDate(date) {
  var d = new Date(date);
  var monthNames = [
    "January", "February", "March",
    "April", "May", "June", "July",
    "August", "September", "October",
    "November", "December"
  ];

  var day = d.getDate();
  var monthIndex = d.getMonth();
  var year = d.getFullYear();

  return day + ' ' + monthNames[monthIndex] + ' ' + year;
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

    <form class="form-horizontal" method="post" id="form_search" action="laporan/Antrian_pasien/export_detail" target="blank">

    <div class="col-md-12">

    <center><h4>FORM PENCARIAN DATA ANTRIAN PASIEN</h4></center>
      <br>

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
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'nama_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1,'status_aktif' => 1), 'where_in' => array('col' => 'validasi', 'val' => array('0100','0300','0500')) ), '' , 'bagian', 'bagian', 'form-control', '', '') ?>
        </div>
        <label class="control-label col-md-1">Dokter</label>
          <div class="col-md-3">
              <input id="inputDokter" class="form-control" name="dokter" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" />
              <input type="hidden" name="dokterHidden" value="" id="dokterHidden">
          </div>
      </div>

      <div class="form-group">
          <label class="control-label col-md-2">Export type</label>
          <div class="col-md-2">
            <select name="export_by" id="export_by" class="form-control">
              <option value="detail" selected>Export Data Detail</option>
              <option value="general">Export Data General</option>
            </select>
          </div>

          <button type="submit" class="btn btn-xs btn-success" >
            
              <i class="fa fa-file-word-o bigger-110"></i>
              Export Excel
            
          </button>
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
          
        </div>
      </div>

    </div>

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px; display:none;" id="table_antrian_">
      <table id="table_antrian" base-url="laporan/Antrian_pasien" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th class="center">Tanggal</th>
            <th width="80px">No. Antrian</th>
            <th>Klinik</th>
            <th>Dokter</th>
            <th>Jam Praktek</th>
            <th>Type</th>       
          </tr>
        </thead>
        <tbody id="result_data">

        </tbody>
      </table>
    </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->




