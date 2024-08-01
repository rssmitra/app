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

  oTable = $('#dynamic-table-antrol').DataTable({ 
          
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "bInfo": false,
    "searching": false,
    "paging": false,
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": "dashboard/Antrol/get_data",
        "type": "POST"
    },
    "drawCallback": function (response) { 
      // Here the response
        var objData = response.json;
        // antrol success
        $('#antrol_success_1').text(objData.task_1);
        $('#antrol_success_2').text(objData.task_2);
        $('#antrol_success_3').text(objData.task_3);
        $('#antrol_success_4').text(objData.task_4);
        $('#antrol_success_5').text(objData.task_5);
        $('#antrol_success_6').text(objData.task_6);
        $('#antrol_success_7').text(objData.task_7);

        $('#antrol_fail_1').text(objData.task_fail_1);
        $('#antrol_fail_2').text(objData.task_fail_2);
        $('#antrol_fail_3').text(objData.task_fail_3);
        $('#antrol_fail_4').text(objData.task_fail_4);
        $('#antrol_fail_5').text(objData.task_fail_5);
        $('#antrol_fail_6').text(objData.task_fail_6);
        $('#antrol_fail_7').text(objData.task_fail_7);

        $('#antrol_total_1').text(parseInt(objData.task_fail_1) + parseInt(objData.task_1));
        $('#antrol_total_2').text(parseInt(objData.task_fail_2) + parseInt(objData.task_2));
        $('#antrol_total_3').text(parseInt(objData.task_fail_3) + parseInt(objData.task_3));
        $('#antrol_total_4').text(parseInt(objData.task_fail_4) + parseInt(objData.task_4));
        $('#antrol_total_5').text(parseInt(objData.task_fail_5) + parseInt(objData.task_5));
        $('#antrol_total_6').text(parseInt(objData.task_fail_6) + parseInt(objData.task_6));
        $('#antrol_total_7').text(parseInt(objData.task_fail_7) + parseInt(objData.task_7));

        var total_all = parseInt(objData.task_fail_1) + parseInt(objData.task_1);

        $('#antrol_persen_1').text(Math.ceil((parseInt(objData.task_1) / total_all) * 100));
        $('#antrol_persen_2').text(Math.ceil((parseInt(objData.task_2) / total_all) * 100));
        $('#antrol_persen_3').text(Math.ceil((parseInt(objData.task_3) / total_all) * 100));
        $('#antrol_persen_4').text(Math.ceil((parseInt(objData.task_4) / total_all) * 100));
        $('#antrol_persen_5').text(Math.ceil((parseInt(objData.task_5) / total_all) * 100));
        $('#antrol_persen_6').text(Math.ceil((parseInt(objData.task_6) / total_all) * 100));
        $('#antrol_persen_7').text(Math.ceil((parseInt(objData.task_7) / total_all) * 100));

        $('#rekap_msg tbody').remove();
        $.each(objData.rekap_msg, function (key, value) {     
            html = '<tr>\
                <td>'+key+'</td>\
                <td>'+value+'</td>\
              </tr>';
              $(html).appendTo($('#rekap_msg'));                    
        });  
    },

  });

  $('#dynamic-table-antrol tbody').on( 'click', 'tr', function () {
      if ( $(this).hasClass('selected') ) {
          $(this).removeClass('selected');
      }
      else {
          oTable.$('tr.selected').removeClass('selected');
          $(this).addClass('selected');
      }
  } );

  $('#btn_search_data').click(function (e) {
      e.preventDefault();
      $.ajax({
      url: 'Templates/References/find_data',
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      beforeSend: function() {
        achtungShowLoader();  
      },
      success: function(data) {
        achtungHideLoader();
        find_data_reload(data,'registration/Riwayat_kunjungan_pm');
      }
    });
  });

  function find_data_reload(result){

    oTable.ajax.url('dashboard/Antrol/get_data?'+result.data).load();

  }

  function resend_antrol(kode, taskid, flag=''){
    preventDefault();
    $('#span_'+kode+'_'+taskid+'').html('Loading..');
    $.getJSON("<?php echo site_url('dashboard/Antrol/resend_antrol') ?>/" + kode + '/'+ taskid+'/'+flag , function (response) {              
        if(response.code == 200){
          $('#span_'+kode+'_'+taskid+'').html('<span><i class="fa fa-check-circle green bigger-150"></i><br>'+response.time+'</span>');
        }else{
          $('#span_'+kode+'_'+taskid+'').html('<span><i class="fa fa-times-circle red bigger-150"></i><br>'+response.time+'<br>'+response.msg+'</span><br><a href="#" class="label label-xs label-default" onclick="resend_antrol('+"'"+kode+"'"+', '+taskid+')"><i class="fa fa-refresh"></i> Kirim ulang</a>');
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

        <center><h4>MONITORING DATA ANTRIAN ONLINE BPJS<br><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data per Hari ini, yaitu tanggal <?php echo date('d/m/Y')?> </small></h4></center>
        <br>

        <div class="form-group">
          <label class="control-label col-md-2">Tanggal Kunjungan</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>

            <label class="col-md-1">s/d Tanggal</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>
            <div class="col-md-5" style="margin-left: -1.5%">
              <button type="submit" id="btn_search_data" class="btn btn-xs btn-default">
                <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                Search
              </button>
              <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                Reset
              </a>
            </div>
            
        </div>
        <br>

      </div>

      <hr class="separator">

      <div class="col-md-8 no-padding">
        <p><b>Rekap Antrol per Task ID</b></p>
        <table class="table">
          <tr style="background:#428bca61">
            <td></td>
            <?php for($i=1; $i<8; $i++):?>
            <td width="150px" class="center">
              <b>Task <?php echo $i?></b>
            </td>
            <?php endfor; ?>
          </tr>
          <tr>
            <td style="background:#428bca61; font-weight: bold">Total Antrol Sukses</td>
            <?php for($i=1; $i<8; $i++):?>
            <td align="center">
              <span style="font-size: 12px; font-weight: bold; color: blue" id="antrol_success_<?php echo $i?>"></span>
            </td>
            <?php endfor; ?>
          </tr>
          <tr>
            <td style="background:#428bca61; font-weight: bold">Total Antrol Gagal</td>
            <?php for($i=1; $i<8; $i++):?>
            <td align="center">
            <span style="font-size: 12px; font-weight: bold; color: red" id="antrol_fail_<?php echo $i?>"></span>
            </td>
            <?php endfor; ?>
          </tr>
          <tr>
            <td style="background:#428bca61; font-weight: bold">Total Keselurahan per Task</td>
            <?php for($i=1; $i<8; $i++):?>
            <td align="center">
            <span style="font-size: 12px; font-weight: bold; color: black" id="antrol_total_<?php echo $i?>"></span>
            </td>
            <?php endfor; ?>
          </tr>
          <tr>
            <td style="background:#428bca61; font-weight: bold">Persentase Data Sukses(%)</td>
            <?php for($i=1; $i<8; $i++):?>
            <td align="center">
            <span style="font-size: 12px; font-weight: bold; color: black" id="antrol_persen_<?php echo $i?>"></span>
            </td>
            <?php endfor; ?>
          </tr>
        </table>
        
      </div>
      <div class="col-md-4">
        <p><b>Rekap Antrol per <i>Error Message seluruh Task ID</i></b></p>
        <table id="rekap_msg" class="table">
          <thead>
          <tr>
            <th>Response Message</th>
            <th>Total</th>
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <p><b>Data Antrol yang dikirim per Task ID</b></p>
        <table id="dynamic-table-antrol" base-url="dashboard/Antrol" class="table">
          <thead>
          <tr style="background: #c7cccb; border-right: 2px solid #61605f">  
            <th rowspan="2" width="30px" class="center">No</th>
            <th rowspan="2" width="80px">Kode Booking</th>
            <th width="120px" colspan="8" class="center">Task ID</th>
          </tr>
          <tr style="background: #c7cccb; border-right: 2px solid #61605f">
            <?php 
              for($i=1; $i<8; $i++) :
                switch ($i) {
                  case 1:
                    # code...
                    $txt = 'mulai waktu tunggu admisi'; break;
                    case 2:
                      # code...
                      $txt = 'akhir waktu tunggu admisi/mulai waktu layan admisi'; break;
                      case 3:
                        # code...
                        $txt = 'akhir waktu layan admisi/mulai waktu tunggu poli'; break;
                        case 4:
                          # code...
                          $txt = 'akhir waktu tunggu poli/mulai waktu layan poli'; break;
                          case 5:
                            # code...
                            $txt = 'akhir waktu layan poli'; break;
                            case 6:
                              # code...
                              $txt = 'mulai waktu layan farmasi'; break;
                              case 7:
                                # code...
                                $txt = 'akhir waktu obat selesai dibuat'; break;
                }
            ?>
            <th class="center" style="width: 120px"><?php echo $i?><br>(<?php echo $txt?>)</th>  
            <?php endfor;?>
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




