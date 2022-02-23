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

        $.getJSON("<?php echo site_url('Templates/References/getDokterByBagian_') ?>/" + $(this).val() , function (data) {              

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

            $('#btn_search_data').focus();            

          }          

          return false;                 

        }        

}); 


function showModalDaftarPerjanjian(booking_id, no_mr)

{  

  $('#result_text_daftar_perjanjian').text('DAFTAR PERJANJIAN PASIEN NO MR ('+no_mr+')');

  $('#form_daftar_perjanjian_pasien_modal').load('registration/reg_pasien/form_perjanjian_modal/'+no_mr+'?ID='+booking_id); 

  $("#modalDaftarPerjanjian").modal();
    
}

function cetak_surat_kontrol(ID, jd_id) {   
    var no_mr = $('#tabs_riwayat_perjanjian_id').attr('data-id');  
    if( no_mr == '' ){
      alert('Silahkan cari pasien terlebih dahulu !'); return false;
    }else{
      url = 'registration/Reg_pasien/surat_control?id_tc_pesanan='+ID+'&jd_id='+jd_id+'';
      getMenu(url);
    }

}

function delete_perjanjian(id_tc_pesanan){  

  if(confirm('Are you sure?')){
    preventDefault();
    $.ajax({
        url: 'registration/Input_perjanjian/delete',
        type: "post",
        data: {ID:id_tc_pesanan},
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

function saveRow(id_tc_pesanan){  

  preventDefault();
  $.ajax({
      url: 'registration/Perjanjian_rj/saveNoSuratKontrol',
      type: "post",
      data: {ID:id_tc_pesanan, no_surat_kontrol: $('#surat_kontrol_'+id_tc_pesanan+'').val()},
      dataType: "json",
      beforeSend: function() {
        // achtungShowLoader();  
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

    <form class="form-horizontal" method="post" id="form_search" action="registration/Perjanjian_rj/find_data">

    <div class="col-md-12">

      <center><h4>FORM PENCARIAN DATA PERJANJIAN RAWAT JALAN<br><small style="font-size:12px">(Silahkan lakukan pencarian data berdasarkan parameter dibawah ini)</small></h4></center>
      <br>

      <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2" style="margin-right: -40px;">
            <select name="search_by">
              <option value="no_mr">No MR</option>
              <option value="nama">Nama Pasien</option>
            </select>
          </div>
          <label class="control-label col-md-1">Keyword</label>
          <div class="col-md-2">
            <input type="text" class="form-control" name="keyword">
          </div>
      </div>

      <div class="form-group">

          <label class="control-label col-md-2">*Poli/Klinik</label>

          <div class="col-md-4">

              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100,) ), '' , 'klinik', 'klinik', 'form-control', '', '') ?>

          </div>

          <label class="control-label col-md-1">*Dokter</label>

          <div class="col-md-4">

              <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'dokter', 'dokter', 'form-control', '', '') ?>

          </div>

      </div>
      
      <div class="form-group">

          <label class="control-label col-md-2">Tanggal Input Perjanjian</label>

          <div class="col-md-2">

            <div class="input-group">
              <input class="form-control date-picker" name="tgl_input_prj" id="tgl_input_prj" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>

          </div>

      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Kontrol Pasien</label>
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
        <div class="col-md-10">
          &nbsp;
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
    <div class="clearfix"></div>
    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="registration/Perjanjian_rj" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center">No</th>
          <th></th>
          <!-- <th>No MR</th> -->
          <th>Nama Pasien</th>
          <!-- <th>Tujuan Poli</th> -->
          <th>Dokter/Poli/Klinik Tujuan</th>
          <th>Tgl Kontrol Pasien</th>
          <th>No. Telp / HP</th>
          <th>No. SEP</th>
          <th>No Kartu BPJS</th>
          <th>No Surat Kontrol</th>
          <th>Tgl Input</th>
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


<div id="modalDaftarPerjanjian" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:70%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_riwayat_medis">PERJANJIAN PASIEN</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="form_daftar_perjanjian_pasien_modal"></div>

      </div>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



