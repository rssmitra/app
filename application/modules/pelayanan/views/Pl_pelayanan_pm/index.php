<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_/style_wizard.css" />
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

  var step = '<?php echo $step ?>';

  if(step=='to_pemeriksaan'){
    var current_active_step = $('#btn_input_tindakan').parents();
    var progress_line = $('#btn_input_tindakan').parents('.ft').find('.f1-progress-line');
  
    current_active_step.removeClass('active').addClass('activated').next().addClass('active');
    // progress bar
    bar_progress(progress_line, 'right');
    
    $('#status_pasien').val('belum_diperiksa');

    $.ajax({
      url: 'pelayanan/Pl_pelayanan_pm/find_data',
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      beforeSend: function() {
        //achtungShowLoader();  
      },
      success: function(data) {
        //achtungHideLoader();
        find_data_reload(data,'pelayanan/Pl_pelayanan_pm?type_tujuan='+$("#sess_kode_bagian").val()+'');
      }
    });
  }

  if(step=='to_isihasil'){
    var current_active_step = $('#btn_input_tindakan').parents();
    var progress_line = $('#btn_isi_hasil').parents('.f1-steps').find('.f1-progress-line');
    var active = $('#btn_isi_hasil').parent();
    var pemeriksaan =  $('#btn_pemeriksaan').parents();
  
    current_active_step.removeClass('active').addClass('activated');
    pemeriksaan.addClass('activated');
    active.removeClass('activated').addClass('active');

    bar_forward(progress_line);
    
    $('#status_pasien').val('belum_isi_hasil');

    $.ajax({
      url: 'pelayanan/Pl_pelayanan_pm/find_data',
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      beforeSend: function() {
        achtungShowLoader();  
      },
      success: function(data) {
        achtungHideLoader();
        find_data_reload(data,'pelayanan/Pl_pelayanan_pm?type_tujuan='+$("#sess_kode_bagian").val()+'');
      }
    });
  }

  oTable = $('#dynamic-table').DataTable({ 
          
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": false,
    "bPaginate": true,
    "pageLength": 50,
    "bLengthChange": false,
    "bInfo": true,
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": "pelayanan/Pl_pelayanan_pm/get_data?sess_kode_bagian="+$("#sess_kode_bagian").val()+"&search_by="+$("#search_by").val()+"&keyword="+$("#keyword_form").val()+"&from_tgl="+$("#from_tgl").val()+"&to_tgl="+$("#to_tgl").val()+"",
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

  $('#dynamic-table tbody').on( 'click', 'tr', function () {
      if ( $(this).hasClass('selected') ) {
          //achtungShowLoader();
          $(this).removeClass('selected');
          //achtungHideLoader();
      }
      else {
          //achtungShowLoader();
          oTable.$('tr.selected').removeClass('selected');
          $(this).addClass('selected');
          //achtungHideLoader();
      }
  } );

      $('#btn_search_data').click(function (e) {
          e.preventDefault();
          $.ajax({
          url: 'pelayanan/Pl_pelayanan_pm/find_data',
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          success: function(data) {
            achtungHideLoader();
            find_data_reload(data,'pelayanan/Pl_pelayanan_pm?type_tujuan='+$("#sess_kode_bagian").val()+'');
          }
        });
      });

      $('#btn_input_tindakan').on('click', function() {
        var active = $(this).parent();
        var next_step = true;
        // navigation steps / progress steps
        var current_active_step = $(this).parents('.f1-steps').find('.f1-step.active');
        var progress_line = $(this).parents('.f1-steps').find('.f1-progress-line');
        var btn = current_active_step.children().attr("id")
        //console.log(current_active_step.children().attr("id"))
        
        
        if( btn == 'btn_pemeriksaan' ) {
         
          // change icons
          current_active_step.removeClass('active');
          active.removeClass('activated').addClass('active');
          // progress bar
          bar_progress(progress_line, 'left');
          
        }

        if( btn == 'btn_isi_hasil' ) {
         
         // change icons
         current_active_step.removeClass('active');
         active.removeClass('activated').addClass('active');

         var activated = $('#btn_pemeriksaan').parent();
         activated.removeClass('activated');

         bar_reset(progress_line);
         
       }

       $('#status_pasien').val('belum_ditindak');

       $.ajax({
          url: 'pelayanan/Pl_pelayanan_pm/find_data',
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          success: function(data) {
            achtungHideLoader();
            find_data_reload(data,'pelayanan/Pl_pelayanan_pm?type_tujuan='+$("#sess_kode_bagian").val()+'');
          }
        });
        
      });

      $('#btn_pemeriksaan').on('click', function() {
        $('#keyword_form').val('');
        $('#from_tgl').val('');
        $('#to_tgl').val('');
        var active = $(this).parent();
        var next_step = true;
        // navigation steps / progress steps
        var current_active_step = $(this).parents('.f1-steps').find('.f1-step.active');
        var progress_line = $(this).parents('.f1-steps').find('.f1-progress-line');
        var btn = current_active_step.children().attr("id")
        
        if( btn == 'btn_input_tindakan' ) {
         
          // change icons
          current_active_step.removeClass('active').addClass('activated');
          active.addClass('active');
          // progress bar
          bar_progress(progress_line, 'right');
          
          
        }

        if( btn == 'btn_isi_hasil' ) {
         
         // change icons
         current_active_step.removeClass('active');
         active.removeClass('activated').addClass('active');

         bar_progress(progress_line, 'left');
         
       }

       $('#status_pasien').val('belum_diperiksa');

       $.ajax({
          url: 'pelayanan/Pl_pelayanan_pm/find_data',
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          success: function(data) {
            achtungHideLoader();
            find_data_reload(data,'pelayanan/Pl_pelayanan_pm?type_tujuan='+$("#sess_kode_bagian").val()+'');
          }
        });
        
      });

      $('#btn_isi_hasil').on('click', function() {
        $('#keyword_form').val('');
        $('#from_tgl').val('');
        $('#to_tgl').val('');
        var active = $(this).parent();
        var next_step = true;
        // navigation steps / progress steps
        var current_active_step = $(this).parents('.f1-steps').find('.f1-step.active');
        var progress_line = $(this).parents('.f1-steps').find('.f1-progress-line');
        var btn = current_active_step.children().attr("id")
        var pemeriksaan =  $('#btn_pemeriksaan').parents();

        
        
        if( btn == 'btn_pemeriksaan' ) {
         
          // change icons
          current_active_step.removeClass('active').addClass('activated');
          active.addClass('active');
          // progress bar
          bar_progress(progress_line, 'right');
               
        }

        if( btn == 'btn_input_tindakan' ) {
         
          // change icons
          current_active_step.removeClass('active').addClass('activated');
          pemeriksaan.addClass('activated');
          active.removeClass('activated').addClass('active');

          bar_forward(progress_line);
          
        }

       $('#status_pasien').val('belum_isi_hasil');

        $.ajax({
          url: 'pelayanan/Pl_pelayanan_pm/find_data',
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          success: function(data) {
            achtungHideLoader();
            find_data_reload(data,'pelayanan/Pl_pelayanan_pm?type_tujuan='+$("#sess_kode_bagian").val()+'');
          }
        });
      });

      $( "#keyword_form" ).keypress(function(event) {        
       var keycode =(event.keyCode?event.keyCode:event.which);         
       if(keycode ==13){          
         event.preventDefault();          
         if($(this).valid()){            
           $('#btn_search_data').focus();            
         }          
         return false;                 
       }        
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

function find_data_reload(result){

  oTable.ajax.url('pelayanan/Pl_pelayanan_pm/get_data?'+result.data).load();
  // $("html, body").animate({ scrollTop: "400px" });

}

function rollback(kode_penunjang){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan_pm/rollback",
      data: { kode_penunjang: kode_penunjang},            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          getMenu('pelayanan/Pl_pelayanan_pm?type_tujuan='+$("#sess_kode_bagian").val()+'');
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });

}

function bar_progress(progress_line_object, direction) {
	var number_of_steps = progress_line_object.data('number-of-steps');
	var now_value = progress_line_object.data('now-value');
	var new_value = 0;
	if(direction == 'right') {
		new_value = now_value + ( 100 / number_of_steps );
	}
	else if(direction == 'left') {
		new_value = now_value - ( 100 / number_of_steps );
	}
	progress_line_object.attr('style', 'width: ' + new_value + '%;').data('now-value', new_value);
}

function bar_reset(progress_line_object) {
  var new_value = 16.66;
  progress_line_object.attr('style', 'width: ' + new_value + '%;').data('now-value', new_value);
}

function bar_forward(progress_line_object) {
  var new_value = 83.326;
  progress_line_object.attr('style', 'width: ' + new_value + '%;').data('now-value', new_value);
}

function periksa(kode_penunjang) {
  
  $.ajax({
      url: "pelayanan/Pl_pelayanan_pm/periksa_pm",
      data: { kode_penunjang: kode_penunjang },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          // navigation steps / progress steps
          var current_active_step = $('#btn_pemeriksaan').parents();
          var progress_line = $('#btn_pemeriksaan').parents('.ft').find('.f1-progress-line');
       
          current_active_step.removeClass('active').addClass('activated').next().addClass('active');
    			// progress bar
    			bar_progress(progress_line, 'right');
          
          $('#status_pasien').val('belum_isi_hasil');

          $.ajax({
            url: 'pelayanan/Pl_pelayanan_pm/find_data',
            type: "post",
            data: $('#form_search').serialize(),
            dataType: "json",
            beforeSend: function() {
              achtungShowLoader();  
            },
            success: function(data) {
              achtungHideLoader();
              find_data_reload(data,'pelayanan/Pl_pelayanan_pm');
            }
          });
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });
}

function cetak_slip(kode_penunjang) {
  
  noMr = $('#noMrHidden').val();
  url = 'pelayanan/Pl_pelayanan_pm/slip?kode_penunjang='+kode_penunjang+'';
  title = 'Cetak Slip';
  width = 500;
  height = 600;
  PopupCenter(url, title, width, height); 

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

    <form class="form-horizontal ft" method="post" id="form_search" action="pelayanan/Pl_pelayanan_pm/find_data" autocomplete="off">

    <div class="col-md-12">

      <center><h4>DATA PASIEN <?php echo strtoupper($nama_bag) ?><br><small>Data yang ditampilkan adalah data pasien 1 bulan ke terakhir.</small></h4></center>
      <br>

      <div class="f1-steps" style="margin-top:0px !important">
        <div class="f1-progress">
          <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3" style="width: 16.66%;"></div>
        </div>
        <div class="f1-step active">
          <a href="#" id="btn_input_tindakan"><div class="f1-step-icon"><i class="fa fa-user"></i></div></a>
          <p>Input Tindakan</p>
        </div>
        <div class="f1-step">
          <a href="#" id="btn_pemeriksaan"><div class="f1-step-icon"><i class="fa fa-stethoscope"></i></div></a>
          <p>Pemeriksaan</p>
        </div>
        <div class="f1-step">
          <a href="#" id="btn_isi_hasil"><div class="f1-step-icon"><i class="fa fa-file"></i></div></a>
          <p>Hasil Pemeriksaan</p>
        </div>
        <input type="hidden" name="status_pasien" value="" id="status_pasien">
      </div>

      <!-- hidden form -->
      <input type="hidden" name="sess_kode_bagian" value="<?php echo $bag_tujuan ?>" id="sess_kode_bagian">
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
          <label class="control-label col-md-1">Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
          <div class="col-md-2" style="margin-left: -2%">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
          <div class="col-md-1" style="margin-left:-1%">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
            Tampilkan data
            <i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
          </a>
        </div>
          
      </div>

    </div>

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="pelayanan/Pl_pelayanan_pm" class="table table-bordered table-hover"> 
        <thead>
          <tr>  
            <th width="40px"></th>
            <th width="30px"></th>
            <th></th>
            <th></th>
            <th>Kode</th>
            <th>No MR</th>
            <th>Nama Pasien</th>
            <th>Urutan</th>
            <th>Penjamin</th>
            <th width="150px">No SEP</th>
            <th>Tgl Daftar</th>
            <th>Asal Pasien</th>
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





