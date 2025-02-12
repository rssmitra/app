<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>

<script type="text/javascript">
jQuery(function($) {  

  $('.date-picker').datepicker({    

    autoclose: true,    

    todayHighlight: true    

  })  

  //show datepicker when clicking on the icon

  .next().on(ace.click_event, function(){    

    $(this).prev().focus();    

  });  

  $('#timepicker1').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#timepicker1').timepicker('showWidget');
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
  
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


});

$(document).ready(function() {
  // load resume medis last
  getMenuTabsHtml('templates/References/get_riwayat_medis/'+$('#no_mr').val()+'?key=1', 'load_last_resume_medis');
  //initiate dataTables plugin
    oTableCppt = $('#table-cppt').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>&type=catatan_pengkajian",
          "type": "POST"
      },

    });

    $('#btn_add_catatan').click(function (e) {   
      e.preventDefault();

      $('input[name=catatan_pengkajian]').val($('#editor').html());
      var formData = new FormData($('#form_pelayanan')[0]);        
      i=0;
      url = $('#form_pelayanan').attr('action');

      // ajax adding data to database
      $.ajax({
          url : url,
          type: "POST",
          data: formData,
          dataType: "JSON",
          contentType: false,
          processData: false,            
          beforeSend: function() {
            achtungShowLoader();   
          },
          uploadProgress: function(event, position, total, percentComplete) {
          },
          complete: function(xhr) {     
            var data=xhr.responseText;    
            var jsonResponse = JSON.parse(data);  

            if( jsonResponse.status === 200 ){   
              $('#jenis_form_catatan').change();
              $.achtung({message: jsonResponse.message, timeout:5});
              oTableCppt.ajax.reload();

            }else{          
              $.achtung({message: jsonResponse.message, timeout:5, className:'achtungFail'});
            }        
            achtungHideLoader();        

          }   
      });

    });


    $('#btn_search_data_cppt').click(function (e) {
        
        e.preventDefault();
        $.ajax({
        url: $('#form_search').attr('action'),
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        success: function(data) {
          achtungHideLoader();
          find_data_reload(data);
        }
      });
    });

  $('#btn_reset_data_cppt').click(function (e) {
          e.preventDefault();
          reset_table();
  });

  // $('#btn_export_pdf_cppt').click(function (e) {
  //   var url_search = $('#form_search').attr('action');
  //   e.preventDefault();
  //   $.ajax({
  //     url: url_search,
  //     type: "post",
  //     data: $('#form_search').serialize(),
  //     dataType: "json",
  //     success: function(result) {
  //       window.open('pelayanan/Pl_pelayanan_ri/export_pdf_cppt?kode_ri=&'+result.data+'','_blank'); 
  //     }
  //   });
  // });

  $('#jenis_form_catatan').change(function () {
      if ($(this).val()) {
          $.getJSON("pelayanan/Pl_pelayanan/switch_template_form/" + $(this).val() + '/' + $('#no_kunjungan').val()+'/'+$('#no_registrasi').val(), '', function (data) {
            $('#editor').html(data.html);
          });
      } else {
        $('#editor').html('');
      }
  });


});

function delete_cppt(myid, flag){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan_ri/delete_cppt',
        type: "post",
        data: {ID:myid, flag: flag},
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
            oTableCppt.ajax.url("pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>&type=catatan_pengkajian").load();
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

function verif_dpjp(cppt_id, value){
    
    if( $('#is_verified_' + cppt_id).is(":checked") ){
      var status = 1;
    }else{
      var status = 0;
    }

    $.ajax({
        url: 'pelayanan/Pl_pelayanan_ri/verif_cppt',
        data: {ID : cppt_id, status_verif : status},            
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
          if(status != 0){
            $('#verif_id_'+cppt_id+'').html('<?php echo $this->session->userdata('user')->fullname?><br><?php echo $this->tanggal->formatDateTime(date('Y-m-d H:i:s'))?>');
          }else{
            $('#verif_id_'+cppt_id+'').html('');

          }
          return false;
        }
    });
    
}

function show_edit(myid){
  preventDefault();
  $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_cppt_dt') ?>", {id: myid} , function (response) {    
    // show data
    var obj = response.result;
    // console.log(response);
    $('#cppt_id').val(myid);
    $('#jenis_form_catatan').val(obj.jenis_form);
    $('#editor').html(obj.catatan_pengkajian);
    // set value input
    var value_form = response.value_form;
    $.each(value_form, function(i, item) {
      var text = item;
      text = text.replace(/\+/g, ' ');
      $('#'+i).val(text);
    });
  }); 
}


function show_modal_pengkajian(myid){
  preventDefault();
  show_modal_medium_return_json('pelayanan/Pl_pelayanan/switch_template_form/25/1718811', 'DETAIL PENGKAJIAN');
  $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_cppt_dt') ?>", {id: myid} , function (response) {    
    // show data
    // set value input
    var value_form = response.value_form;
    $.each(value_form, function(i, item) {
      var text = item;
      text = text.replace(/\+/g, ' ');
      $('#'+i).val(text);
    });
  }); 
  // show_modal_medium_return_json('pelayanan/Pl_pelayanan_ri/get_cppt_dt?id='+myid+'', 'DETAIL PENGKAJIAN');
}

function find_data_reload(result){
  
  var data = result.data;    
  oTableCppt.ajax.url("pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>&"+data).load();
  // $("html, body").animate({ scrollTop: "400px" });

}

function reset_table(){
  oTableCppt.ajax.url("pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>&type=catatan_pengkajian").load();
  // $("html, body").animate({ scrollTop: "400px" });

}

function reload_table(){
 oTableCppt.ajax.reload(); //reload datatable ajax 
}

function checkthis(id){
  if($('#'+id+'').is(':checked')) {
      $('#'+id+'').attr('checked', true);
  } else {
      $('#'+id+'').attr('checked', false);    
  }
}

function fillthis(id){
  
  var val_str = document.getElementById(id).value;
  $('#'+id+'').val(val_str);
}

</script>

<div class="row">

  <div class="page-header">
    <h1>
      <?php echo $title?>
      <small>
        <i class="ace-icon fa fa-angle-double-right"></i>
        <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
      </small>
    </h1>
  </div><!-- /.page-header -->

  <div class="col-md-7" style="border-right: 1px solid #e5e5e5">
    <!-- profil pasien -->
    <table class="table">
      <tr style="background-color: #edf3f4;">
        <td style="vertical-align: top; width: 180px">
          <span style="font-weight: bold !important">Nama Pasien :</span><br> 
          [<a href="#" onclick="getMenu('pelayanan/Pl_input_vital_sign/assesmen_rj/<?php echo $value->id_pl_tc_poli?>/<?php echo $value->no_kunjungan?>?type=Rajal&no_mr=<?php echo $no_mr?>')" style="font-weight: bold"><?php echo isset($value)?ucwords($value->no_mr):''?></a>]  <?php echo isset($value)?ucwords($value->nama_pasien):''?>        
        </td>
        <td style="vertical-align: top; width: 300px"> <span style="font-weight: bold !important">Penjamin :</span><br> <?php echo isset($value)?ucwords($value->nama_kelompok):''?><br><?php echo isset($value)?ucwords($value->nama_perusahaan):''?> <?php echo isset($value->kode_perusahaan) ? ($value->kode_perusahaan == 120) ?'('.$value->no_sep.')' : '' :'';?></td>
        <td style="vertical-align: top; width: 300px"> <span style="font-weight: bold !important">Dokter :</span><br> <?php echo isset($value)?$value->nama_pegawai:''?> </td>
        <td style="vertical-align: top; width: 300px"> <span style="font-weight: bold !important">Poli/Spesialis :</span><br> <?php echo isset($value)?ucwords($value->nama_bagian):''?> </td>
      </tr>
    </table>


    <form class="form-horizontal" method="post" id="form_pelayanan" action="pelayanan/Pl_pelayanan/processSaveCatatanPengkajian" enctype="multipart/form-data" autocomplete="off" >   

      <!-- form default pelayanan pasien -->
      <input type="hidden" name="cppt_id" value="" id="cppt_id">
      <input type="hidden" name="no_mr" value="<?php echo isset($no_mr)?$no_mr:''?>" id="no_mr">
      <input type="hidden" name="no_kunjungan" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" id="no_kunjungan">
      <input type="hidden" name="no_registrasi" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" id="no_registrasi">

      <div class="form-group">
          <label class="control-label col-sm-2" for="">*Tanggal/Jam</label>
            <div class="col-md-6">
                  
              <div class="input-group">
                  
                  <input name="cppt_tgl" id="cppt_tgl" placeholder="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>">
                  <span class="input-group-addon">
                    
                    <i class="ace-icon fa fa-calendar"></i>
                  
                  </span>

                  <input id="timepicker1" name="cppt_jam" id="cppt_jam" type="text" class="form-control">
                  <span class="input-group-addon">
                    <i class="fa fa-clock-o bigger-110"></i>
                  </span>
                  
              </div>

            </div>
      </div>

      <div class="form-group">
          <label class="control-label col-sm-2">Nama Dokter</label>
          <div class="col-md-6">
            <input type="text" class="form-control" name="nama_ppa" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:$this->session->userdata('user')->fullname; ?>">
          </div>
      </div>

      <div class="form-group">
          <label class="control-label col-sm-2">Jenis Form</label>
          <div class="col-md-8">
            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_form_catatan')), 25 , 'jenis_form_catatan', 'jenis_form_catatan', 'chosen-select form-control', '', '') ?>
          </div>
      </div>
      <hr>
      <div id="editor"><?php echo $template?></div>
      <input type="hidden" name="catatan_pengkajian" value="" />
            
      <div class="center">
          <div class="col-md-12" id="btn_submit_cppt" style="margin-top: 20px" >
              <div class="col-sm-12"><button type="button" class="btn btn-sm btn-primary" id="btn_add_catatan"><i class="fa fa-save"></i> Simpan</button> 
              </div>
          </div>
          <hr>
      </div>
      <br>
      <hr>
    </form>
  </div>

  <div class="col-md-5" style="margin-top: 10px">
    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan_ri/find_data" autocomplete="off">

      <!-- <div class="form-group">
        <label class="control-label col-md-2">Tanggal</label>
          <div class="col-md-7">
            <span class="input-icon input-icon-right">
              <input class="date-picker" style="width: 120px" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <i class="ace-icon fa fa-calendar"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input class="date-picker" style="width: 120px" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <i class="ace-icon fa fa-calendar"></i>
            </span>
          </div>

          <div class="col-md-2 no-padding">
            <a href="#" id="btn_search_data_cppt" class="btn btn-xs btn-default">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            </a>
            <a href="#" id="btn_reset_data_cppt" class="btn btn-xs btn-warning">
              <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            </a>
            <a href="#" id="btn_export_pdf_cppt" class="btn btn-xs btn-danger">
              <i class="fa fa-file-pdf-o bigger-110"></i>
            </a> 
          </div>
      </div> -->
      <center><span style="font-size: 14px"><b>CATATAN RIWAYAT PENYAKIT ATAU PENGKAJIAN PASIEN</b></span></center>
      <table id="table-cppt" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="30px">No</th>
            <th width="70px">Tanggal/Jam</th>
            <th>Nama Dokter</th>
            <th>Catatan Pengkajian</th>
            <th>Verifikasi Dokter</th>
            <th width="100px">Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>

    </form>
    <!-- resume medis terakhir -->
    <center><span style="font-size: 14px"><b>RESUME MEDIS PASIEN TERBARU</b></span></center>
    <div id="load_last_resume_medis"></div>
  </div>
  
</div>








