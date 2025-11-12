<!-- Modal Verifikasi PIN -->
<div class="modal fade" id="modal-verifikasi-pin" tabindex="-1" role="dialog" aria-labelledby="modalVerifPinLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalVerifPinLabel">Verifikasi PIN</h5>
      </div>
      <div class="modal-body">
        <div class="form-group" style="position: relative; margin-bottom: 10px;">
          <label for="input_pin_verif">Masukan PIN : </label>
          <div class="input-group">
            <input type="password" class="form-control" id="input_pin_verif" placeholder="Masukkan PIN" autocomplete="off">
            <span class="input-group-addon" id="toggle-pin-verif" style="cursor: pointer; background: transparent; border-left: none;">
              <i class="fa fa-eye" id="icon-eye-pin"></i>
            </span>
          </div>
          <span id="pinVerifErrorMsg" style="color:red;display:none;"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-xs btn-primary" id="btnSubmitPinVerif">Submit</button>
      </div>
    </div>
  </div>
</div>

<script>
// Toggle show/hide PIN
$(document).on('mousedown', '#toggle-pin-verif', function() {
  $('#input_pin_verif').attr('type', 'text');
  $('#icon-eye-pin').removeClass('fa-eye').addClass('fa-eye-slash');
});

$(document).on('mouseup mouseleave', '#toggle-pin-verif', function() {
  $('#input_pin_verif').attr('type', 'password');
  $('#icon-eye-pin').removeClass('fa-eye-slash').addClass('fa-eye');
});

// Handler submit PIN
$(document).off('click', '#btnSubmitPinVerif').on('click', '#btnSubmitPinVerif', function() {
  var pin = $('#input_pin_verif').val();
  if(!pin) {
    $('#pinVerifErrorMsg').text('PIN harus diisi!').show();
    return;
  }
  // Verifikasi PIN ke server
  $.ajax({
    url: 'templates/References/verify_code', // Ganti dengan URL endpoint verifikasi PIN yang benar
    type: "post",
    data: {pin: pin, type: 'cppt'},
    dataType: "json",
    beforeSend: function() {
      $('#btnSubmitPinVerif').prop('disabled', true);
    },
    success: function(response) {
      
      if(response.status === 200) {
        $('#modal-verifikasi-pin').modal('hide');
        // Lanjutkan proses hapus
        var myid = window._deleteCpptParams.myid;
        var flag = window._deleteCpptParams.flag;
        $.ajax({
          url: 'pelayanan/Pl_pelayanan_ri/delete_cppt',
          type: "post",
          data: {ID:myid, flag: flag},
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          complete: function(xhr) {     
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);
            if(jsonResponse.status === 200){
              $.achtung({message: jsonResponse.message, timeout:5});
              oTableCppt.ajax.url("pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>&type=catatan_pengkajian").load();
              $('#cppt_id').val('');
            }else{
              $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFailed'});
            }
            achtungHideLoader();
          }
        });
      } else {
        $('#pinVerifErrorMsg').text(response.message || 'PIN salah!').show();
      }

    },
    error: function() {
      $('#pinVerifErrorMsg').text('Terjadi kesalahan saat verifikasi PIN.').show();
    },
    complete: function() {
      $('#btnSubmitPinVerif').prop('disabled', false);
    }
  });
});
</script>

<style>
  /* Custom modal for better appearance */
  #modal-verifikasi-pin .modal-dialog {
    margin-top: 10vh;
    max-width: 300px;
  }
  #modal-verifikasi-pin .modal-content {
    border-radius: 10px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    border: none;
  }
  #modal-verifikasi-pin .modal-header {
    border-bottom: 1px solid #eee;
    background: #f7f7f7;
    border-radius: 10px 10px 0 0;
    padding: 16px 24px 12px 24px;
  }
  #modal-verifikasi-pin .modal-title {
    font-weight: bold;
    font-size: 18px;
  }
  #modal-verifikasi-pin .modal-body {
    padding: 20px 24px 10px 24px;
  }
  #modal-verifikasi-pin .form-group label {
    font-weight: 500;
    margin-bottom: 8px;
  }
  #modal-verifikasi-pin .form-control {
    border-radius: 6px;
    font-size: 12px;
  }
  #modal-verifikasi-pin .modal-footer {
    border-top: 1px solid #eee;
    padding: 12px 24px 16px 24px;
    border-radius: 0 0 10px 10px;
    background: #f7f7f7;
  }
</style>
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

      $('input[name=catatan_pengkajian]').val($('#editor_html_pengkajian').html());
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

    $('#search_nama_ppa').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getAllDokter",
              data: { keyword:query },            
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
      var label_item=item.split(':')[1];
      $('#search_nama_ppa').val(label_item);
      $('#txt_nama_dr_profile_form_pengkajian').text(label_item);
      $('#dokter_bedah_1').val(label_item);
      // get ttd and stamp dokter
      var kode_dokter = val_item.replace(/\s/g, '');
      get_ttd_and_stamp_dr(kode_dokter);
    }

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
          find_data_reload(data,base_url);
        }
      });
    });

  $('#btn_reset_data_cppt').click(function (e) {
          e.preventDefault();
          reset_table();
  });

   $('#btn_export_pdf_cppt').click(function (e) {
     var url_search = $('#form_search').attr('action');
     e.preventDefault();
     $.ajax({
       url: url_search,
       type: "post",
       data: $('#form_search').serialize(),
       dataType: "json",
       success: function(result) {
         window.open('pelayanan/Pl_pelayanan_ri/export_pdf_cppt?id='+$('#cppt_id').val()+'','_blank'); 
       }
     });
   });

  $('#jenis_form_catatan').change(function () {
      if ($(this).val()) {
          // set text jenis form
          $('#txt_jenis_form').html('Form No. '+$(this).val());
          $.getJSON("pelayanan/Pl_pelayanan/switch_template_form/" + $(this).val() + '/' + $('#no_kunjungan').val() + '/'+$('#no_registrasi').val(), '', function (data) {
            $('#editor_html_pengkajian').html(data.html);
            $('#cppt_id').val('');
            
          });
      } else {
        $('#editor_html_pengkajian').html('');
      }
  });


});


function delete_cppt(myid, flag){
  // Simpan parameter untuk digunakan setelah verifikasi PIN
  window._deleteCpptParams = {myid: myid, flag: flag};
  $('#inputPinVerifikasi').val('');
  $('#pinErrorMsg').hide();
  $('#modal-verifikasi-pin').modal('show');
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
    console.log(response);
    $('#cppt_id').val(myid);
    $('#jenis_form_catatan').val(obj.jenis_form);
    $('#editor_html_pengkajian').html(obj.catatan_pengkajian);
    // set value input
    var value_form = response.value_form;
    $.each(value_form, function(i, item) {
      var text = item;
      text = text.replace(/\+/g, ' ');
      $('#'+i).val(text);
    });
    $('#anatomi_tagging_28').val(response.anatomi_tagging);

  }); 
}



function show_modal_pengkajian(myid){
  preventDefault();
  // show_modal_medium_return_json('pelayanan/Pl_pelayanan/switch_template_form/25/1718811', 'DETAIL PENGKAJIAN');
  $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_cppt_dt') ?>", {id: myid} , function (response) {    
    // show data
    // set value input
    var value_form = response.value_form;
    $.each(value_form, function(i, item) {
      var text = item;
      text = text.replace(/\+/g, ' ');
      $('#'+i).val(text);
      $('select#'+i).val(text).change();
    });
  }); 
  // show_modal_medium_return_json('pelayanan/Pl_pelayanan_ri/get_cppt_dt?id='+myid+'', 'DETAIL PENGKAJIAN');
}

function find_data_reload(result, base_url){
  
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

function printDivHtml(divId) {
     preventDefault();
     var printContents = document.getElementById(divId).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}

function get_ttd_and_stamp_dr(kode_dokter){
  $.getJSON("<?php echo site_url('Templates/Templates/get_credential_dr') ?>", {id: kode_dokter} , function (response) {    
    // show data
    console.log(response);
    $('#ttd_digital_dr').html(response.ttd+'<br>');
    $('#stamp_digital_dr').html(response.stamp);
  }); 
}

$('#save_ttd_pasien_form').click(function (e) {
    e.preventDefault();
    $.ajax({
    url: 'pelayanan/Pl_pelayanan_ri/process_note',
    type: "post",
    data: $('#form_pelayanan').serialize(),
    dataType: "json",
    beforeSend: function() {
      achtungShowLoader();  
    },
    success: function(data) {
      achtungHideLoader();
      $('#modalTTDPasien').modal('hide');
      if(data.status == 200){
        $('#ttd_lainnya').html('<img src="'+data.ttd+'" style="width: 100% !important">');
        $('#ttd_nama').text(data.nama_ttd);
        $('#ttd_tgl').html('Jakarta, '+data.tgl_ttd+'<br>Pasien/Keluarga Pasien<br>');
      }
    }
  });
});

function showModalTTD()
{  
  noMr = $('#noMrHidden').val();
  if (noMr == '') {
    alert('Silahkan cari pasien terlebih dahulu !'); return false;
  }else{
    $('#result_text_edit_pasien').text('TANDA TANGAN PASIEN');
    $('#form_pasien_modal_ttd').load('registration/reg_pasien/form_modal_ttd/'+noMr+''); 
    $("#modalTTDPasien").modal();
  }
}




</script>

<style>
  @media print {
    body {
      visibility: hidden;
    }
    #section-to-print {
      visibility: visible;
      position: absolute;
      left: 0;
      top: 0;
    }
  }
</style>
<div class="row">

  <div class="col-md-12">

    <div class="center"><span style="font-size: 14px"><b>FORM REKAM MEDIS</b></span></div>
    <br>

    <!-- form default pelayanan pasien -->
    <input type="hidden" name="cppt_id" value="" id="cppt_id">

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
          <input type="text" class="form-control" name="nama_ppa" id="search_nama_ppa" value="<?php echo $this->session->userdata('sess_nama_dokter');?>">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2">Jenis Form</label>
        <div class="col-md-8">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_form_catatan', 'is_active' => 'Y')), isset($_GET['form_no'])?$_GET['form_no']:'' , 'jenis_form_catatan', 'jenis_form_catatan', 'chosen-select form-control', '', '') ?>
        </div>
    </div>


    <hr>
    <span id="txt_jenis_form" style="float: right">Form No. </span>
    <div id="editor_html_pengkajian"><?php echo $template?></div>

    <input type="hidden" name="catatan_pengkajian" value="" />
					
    <div class="center">
        
        <div class="col-md-12" id="btn_submit_cppt" style="margin-top: 20px" >
            <div class="col-sm-12">
              <button type="button" class="btn btn-sm btn-primary" id="btn_add_catatan"><i class="fa fa-save"></i> Simpan</button> 
              <a href="#" class="btn btn-primary btn-sm" onclick="showModalTTD()" id="ttd_digital_pasien_btn"><i class="fa fa-pencil"></i> Tanda Tangan Digital Pasien</a>
            </div>
        </div>
        <hr>
    </div>
    <br>
    <hr>

    <div class="col-md-12" style="margin-top: 10px">
      <center><span style="font-size: 18px"><b>CATATAN RIWAYAT PENYAKIT ATAU PENGKAJIAN PASIEN</b></span></center><br>
      <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan_ri/find_data" autocomplete="off">

          <div class="form-group">
            <label class="control-label col-md-2">Tanggal</label>
              <div class="col-md-3">
                <div class="input-group">
                  <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>

              <label class="control-label col-md-1">s/d</label>
              <div class="col-md-3">
                <div class="input-group">
                  <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>

              <div class="col-md-3">
              <a href="#" id="btn_search_data_cppt" class="btn btn-xs btn-default">
                <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              </a>
              <a href="#" id="btn_reset_data_cppt" class="btn btn-xs btn-warning">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
              </a>
              <a href="#" id="btn_export_pdf_cpptxx" class="btn btn-xs btn-danger">
                <i class="fa fa-file-pdf-o bigger-110"></i>
              </a>
            </div>

          </div>
          
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
    </div>

  </div>
     
</div>


<div id="modalTTDPasien" class="modal fade" tabindex="-1">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:95%">
    <div class="modal-content">
      <div class="modal-header">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
          </button>
          <span>TANDA TANGAN PASIEN (DIGITAL SIGNATURE)</span>
        </div>
      </div>

      <div class="modal-body">                                 
        <div id="form_pasien_modal_ttd"></div>
        <input type="hidden" name="note_type" id="note_type" value="ttd_pasien">
        <input type="hidden" name="created_by" id="created_by" value="pasien">
        <input type="hidden" name="created_name" id="created_name" value="pasien">
        <button type="button" id="save_ttd_pasien_form" name="submit" class="btn btn-xs btn-primary">
          <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
          Submit
        </button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>








