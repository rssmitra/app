<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>

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

    $.getJSON("<?php echo site_url('Templates/References/getBayiRS') ?>/" + $(this).val(), '', function (data) {              

      $('#nama_pasien_input option').remove();            

      $('<option value="">-Pilih Bayi-</option>').appendTo($('#nama_pasien_input'));                

      $.each(data, function (i, o) {  

          dt = new Date(o.tgl_jam_lahir);
                
          formatDt = formatDate(dt);                    

          $('<option value="' + o.id_bayi + '">' + o.nama_bayi + ' ( Tgl Lahir : ' + formatDt + ')</option>').appendTo($('#nama_pasien_input'));                        

      });           

    })

  })

  $('#form_input_bayi').ajaxForm({      

    beforeSend: function() {     
      achtungShowFadeIn();      
      $("input[type=submit]").attr("disabled", "disabled");    
    },      

    uploadProgress: function(event, position, total, percentComplete) {},      

    complete: function(xhr) {             

      var data=xhr.responseText;        

      var jsonResponse = JSON.parse(data);        

      if(jsonResponse.status === 200){           

        /*show action after success submit form*/
        //$("#page-area-content").load("registration/Reg_klinik?mr="+jsonResponse.no_mr+"&is_new=Yes");
        $('#data_ibu').hide('fast');
        $('#noMrHidden').val(jsonResponse.no_mr); 
        var answer = confirm('Daftarkan Pasien?');
        if (answer){
          console.log('yes'); 
          $.achtung({message: 'Silahkan Isi Form Pendaftaran', timeout:5});       
          $('#btn_submit').hide('fast');
          $('#btn_registrasi').show('fast'); 
          $('#registrasi_bayi').load('registration/Reg_klinik/show_modul/2');  
          $('#data_registrasi').show('fast');
        }else{
          console.log('cancel');
          $.achtung({message: jsonResponse.message, timeout:5});       
          $("#page-area-content").load("registration/Input_pasien_baru");
        }
       

      }else{          

        $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});          

      }        

      //achtungHideLoader();        

    }      

  });     

  $('select[name="nama_pasien_input"]').change(function () {      

    if ($(this).val()) {       

        $.getJSON("<?php echo site_url('Templates/References/getBayiRSbyID') ?>/" + $(this).val(), '', function (data) {              

          $('#id_bayi').val(data.id_bayi); 
          $('#dob_pasien').val(data.tgl_jam_lahir); 
          $('#gender').val(data.jenis_kelamin); 
          $('#nama_pasien').val(data.nama_bayi); 
          $('#berat_badan').val(data.berat_badan); 
          $('#panjang_badan').val(data.panjang_badan); 


        });    

        $.getJSON("<?php echo site_url('Templates/References/getDataIbu') ?>/" + $(this).val(), '', function (data) {              

            $('#mr_ibu').val(data.no_mr);     
            $('#nama_ibu_pasien').val(data.nama_pasien);         
            $('#alamat_pasien').val(data.almt_ttp_pasien);   
            var telp = (data.no_hp !== "")?data.no_hp:"-";    
            $('#telp_pasien').val(telp);   
            var agama = (data.id_dc_agama !== null)?data.id_dc_agama:data.kode_agama;
            $('#religion').val(agama);   
            $('#kelompok_pasien').val(data.kode_kelompok);  
            $('#kode_kelompok_hidden').val(data.kode_kelompok);   
            if(data.kode_kelompok==3){
              $('#kode_perusahaan').val(data.kode_perusahaan);  
              $('#kode_perusahaan_hidden').val(data.kode_perusahaan);

              if(data.kode_perusahaan==120){
                $('#form_sep').show('fast');
              }
              $('#kode_perusahaan_').show('fast');
            }

            $('#provinsiHidden').val(data.id_dc_propinsi);  
            $('#kotaHidden').val(data.id_dc_kota); 
            $('#kecamatanHidden').val(data.id_dc_kecamatan); 
            $('#kelurahanHidden').val(data.id_dc_kelurahan); 
            $('#zipcode').val(data.kode_pos); 

        }); 

        $('#data_ibu').show('fast');

    }    

  });  

  $('select[name="kode_kelompok_hidden"]').change(function () {      

    if($(this).val()!==3){
      $('#kode_perusahaan_hidden').val('');
    }   

  });

  $('#kelompok_pasien').change(function () {
    if($('#kelompok_pasien').val()==3){
      $('#kode_perusahaan_').show('fast');
    }else{
      $('#kode_perusahaan_').hide('fast');
      $('#kode_perusahaan_hidden').val('');
    }   
  })

  $('#btn_registrasi').click(function (e) {
    e.preventDefault();
    $.ajax({
      url: 'registration/Reg_ranap/process',
      type: "post",
      data: $('#form_input_bayi').serialize(),
      dataType: "json",
      beforeSend: function() {
        achtungShowLoader();  
      },
      success: function(data) {
        //var jsonResponse = JSON.parse(data);  
        achtungHideLoader();
        console.log(data) 
        if(data.status === 200){     
          $.achtung({message: data.message+", Silahkan cek kembali di riwayat kunjungan", timeout:5});  
          $("#page-area-content").load("registration/Reg_klinik?mr="+data.no_mr+"&is_new=Yes");
        } else {
          $.achtung({message: data.message, timeout:5});   
        }
      }
    });
       
  });

  function showModalFormSep()

  {  

    noMr = $('#noMrHidden').val();

    noKartu = $('#noKartuBpjs').val();

    $('#result_text_create_sep').text('PEMBUATAN SURAT ELIGIBILITAS PASIEN (SEP)');

    $('#form_create_sep_content').load('registration/reg_klinik/form_sep/'+noMr+''); 

    $("#modalCreateSep").modal();  

  }


</script>

<div class="row">

  <div class="col-xs-12">  

    <div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

    <!-- div.dataTables_borderWrap -->

    <div style="margin-top:-10px">    
      <form class="form-horizontal" method="post" id="form_input_bayi" action="registration/Input_pasien_baru/process_bayi_rs" enctype="multipart/form-data" autocomplete="off">      
        
          <br>

          <!-- hidden form -->
          <input type="hidden" name="tipe_pasien_baru" id="tipe_pasien_baru" value="bayi">
          <input type="hidden" name="pob_pasien" id="pob_pasien" value="Jakarta Selatan">
          <input type="hidden" name="nik_pasien" id="nik_pasien" value="-">
          <input type="hidden" name="id_bayi" id="id_bayi">
          <input type="hidden" name="nama_pasien" id="nama_pasien">
          <input type="hidden" name="dob_pasien" id="dob_pasien">
          <input type="hidden" name="provinsiHidden" id="provinsiHidden">
          <input type="hidden" name="kotaHidden" id="kotaHidden">
          <input type="hidden" name="kecamatanHidden" id="kecamatanHidden">
          <input type="hidden" name="kelurahanHidden" id="kelurahanHidden">
          <input type="hidden" name="zipcode" id="zipcode">
          <input type="hidden" name="gender" id="gender">
          <input type="hidden" name="berat_badan" id="berat_badan">
          <input type="hidden" name="panjang_badan" id="panjang_badan">
               

          <div id="data_bayi">
            <p><b><i class="fa fa-user"></i> DATA BAYI(*) </b></p>

            <div class="form-group" >

              <label class="control-label col-md-2">Nama Bayi</label>    
              <div class="col-md-4">      
                <?php echo $this->master->get_change($params = array('table' => 'ri_bayi_lahir', 'id' => 'nama_bayi', 'name' => 'nama_bayi', 'where' => array()), '' , 'nama_pasien_input', 'nama_pasien_input', 'form-control', '', '') ?>
              </div>

              <div class="col-md-2">
                <label class="inline" style="margin-top: 4px;">
                  <input type="checkbox" class="ace" name="bayi_kembar" id="bayi_kembar" value="1">
                  <span class="lbl"> Bayi Lahir Kembar/ Anak ke 2 dst </span>
                </label>
              </div>

            </div>

            
            <div class="form-group">

              <label class="control-label col-md-2">Nama Ayah*</label>            

              <div class="col-md-2">            

                <div class="input-group">

                  <input type="text" name="nama_ayah_pasien" id="nama_ayah_pasien" class="form-control" value="<?php echo isset($value)?$value->nama_ayah:''?>">

                </div>

              </div>

            </div>

            <div class="form-group">

              <label class="control-label col-md-2">Pekerjaan Ayah*</label>

              <div class="col-md-4">

                <?php echo $this->master->custom_selection($params = array('table' => 'mst_job', 'id' => 'job_name', 'name' => 'job_name', 'where' => array()), isset($value)?$value->pekerjaan_ayah:'' , 'job', 'job', 'form-control', '', '') ?> 

              </div>

            </div>
     
          </div>
          <br>

          <div id="data_ibu" style="display:none">
            <p><b><i class="fa fa-user"></i> DATA IBU(*) </b></p>

            <div class="form-group">

              <label class="control-label col-md-2">No MR Ibu</label>            

              <div class="col-md-2">            

                <div class="input-group">

                  <input type="text" name="mr_ibu" id="mr_ibu" class="form-control" >

                </div>

              </div>

              <label class="control-label col-md-1">Nama Ibu</label>            

              <div class="col-md-3">            

                  <input type="text" name="nama_ibu_pasien" id="nama_ibu_pasien" class="form-control" >

              </div>

            </div>

            <div class="form-group">
                
              <label class="control-label col-md-2">Alamat</label>
              
              <div class="col-md-3">
                
                <textarea name="alamat_pasien" id="alamat_pasien" class="form-control" style="height:50px !important"></textarea>
              
              </div>    

              <label class="control-label col-md-1">Telp/HP</label>

              <div class="col-md-2">
                <input type="text" name="telp_pasien" id="telp_pasien" class="form-control" value="<?php echo isset($value)?($value->tlp_almt_ttp!=0 || $value->tlp_almt_ttp!='' )?$value->tlp_almt_ttp:$value->no_hp:'' ?>">
              </div>
            
            </div>

            <div class="form-group">

              <label class="control-label col-md-2">Agama</label>

              <div class="col-md-2">

                <?php echo $this->master->custom_selection($params = array('table' => 'mst_religion', 'id' => 'religion_id', 'name' => 'religion_name', 'where' => array()), '' , 'religion', 'religion', 'form-control', '', '') ?> 

              </div>

            </div>

            <div class="form-group">

              <label class="control-label col-md-2">Nasabah</label>

              <div class="col-md-2">

                <?php echo $this->master->custom_selection($params = array('table' => 'mt_nasabah', 'id' => 'kode_kelompok', 'name' => 'nama_kelompok', 'where' => array()), '' , 'kelompok_pasien', 'kelompok_pasien', 'form-control', '', '') ?> 

              </div>

              <div id="kode_perusahaan_" style="display:none;">

                <label class="control-label col-md-2" for="Province">Nama Perusahaan</label>

                <div class="col-sm-2">

                  <?php echo $this->master->custom_selection($params = array('table' => 'mt_perusahaan', 'id' => 'kode_perusahaan', 'name' => 'nama_perusahaan', 'where' => array()), '' , 'kode_perusahaan', 'kode_perusahaan', 'form-control', '', '') ?>
                    
                </div>

              </div>

            </div>
          </div>


        <div id="data_registrasi" style="display:none">

              <p><b><i class="fa fa-user"></i> DATA REGISTRASI(*) </b></p>

              <input type="hidden" value="" name="noMrHidden" id="noMrHidden">

              <div class="form-group">

                <label class="control-label col-sm-2">Nasabah</label>

                <div class="col-md-4">

                    <?php echo $this->master->custom_selection($params = array('table' => 'mt_nasabah', 'id' => 'kode_kelompok', 'name' => 'nama_kelompok', 'where' => array()), '' , 'kode_kelompok_hidden', 'kode_kelompok_hidden', 'form-control', '', '') ?> 

                </div>

                <label class="control-label col-sm-2">Perusahaan Penjamin</label>

                <div class="col-md-4">

                  <?php echo $this->master->custom_selection($params = array('table' => 'mt_perusahaan', 'id' => 'kode_perusahaan', 'name' => 'nama_perusahaan', 'where' => array()), '' , 'kode_perusahaan_hidden', 'kode_perusahaan_hidden', 'form-control', '', '') ?>

                </div>

              </div>

              <div class="form-group" id="form_sep" style="display:none">

                <label class="control-label col-sm-2">Nomor SEP</label>            

                  <div class="col-md-4">            

                    <div class="input-group">

                      <input name="noSep" id="noSep" class="form-control" type="text" placeholder="Masukan No SEP">

                      <span class="input-group-btn">

                        <button type="button" class="btn btn-primary btn-sm" onclick="showModalFormSep()">

                          <span class="ace-icon fa fa-file icon-on-right bigger-110"></span>

                          Buat SEP

                        </button>

                      </span>

                    </div>

                  </div>   


              </div>

              <div id="registrasi_bayi" style="margin-top:10px"> </div>
              
        </div>

        <br>

        <div class="form-group">

          <a onclick="getMenu('registration/Input_pasien_baru')" href="#" class="btn btn-sm btn-success">
            <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
            Kembali ke daftar
          </a>

          <a href="#" id="btn_registrasi" class="btn btn-xs btn-primary" style="display:none;">
            <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
            Daftarkan
          </a>

          <button type="submit" name="submit" id="btn_submit" class="btn btn-xs btn-primary" >

            <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>

            Submit

          </button>

        </div>

      </form>

<!-- MODAL CREATE SEP -->

<div id="modalCreateSep" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:85%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_create_sep">Pembuatan SEP (Surat Eligibilatas Peserta)</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="form_create_sep_content"></div>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>