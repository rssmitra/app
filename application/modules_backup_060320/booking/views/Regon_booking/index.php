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

    <div class="widget-box transparent">
        <div class="widget-header widget-header-small">
            <button class="btn btn-xs btn-primary" id="btnAddNewPasien">  <i class="fa fa-plus"></i> Tambahkan Pasien Baru  </button>
        </div>

        <div class="widget-body">
          <div class="widget-main padding-8">
            

            <!-- #section:pages/profile.feed -->
            <div id="profile-feed-1" class="profile-feed" style="position: relative;"><div class="scroll-track scroll-active" style="display: block;"><div class="scroll-bar" style="top: 0px;"></div></div>
              <div class="scroll-content">

              <?php 
                foreach($value->result as $row_pasien): 
                  /*decode detail pasien*/
                  $profile_owner = json_decode($row_pasien->log_det_no_mr);
                  //print_r($profile_owner);die;
              ?>
                <div class="profile-activity clearfix">
                  <div>
                    <img class="pull-left" alt="<?php echo $profile_owner->fullname?>'s photo" src="<?php echo isset($profile_owner->path_foto) ? base_url().PATH_PHOTO_PROFILE_DEFAULT.$profile_owner->path_foto:base_url().'assets/avatars/user.jpg'?>">
                    <a class="user" href="#"> <?php echo $profile_owner->fullname?> ( <?php echo $profile_owner->no_mr?> ) </a><br>
                    Status : <?php echo $row_pasien->regon_rp_status_relasi?>

                    <div class="time">
                      <i class="ace-icon fa fa-clock-o bigger-110"></i>
                      Last activities 13/02/2018 12:42
                    </div>
                  </div>

                  <div class="action-buttons">
                    <button onclick="booking('<?php echo $row_pasien->regon_rp_no_mr?>','<?php echo $row_pasien->regon_rp_id?>')" class="btnBookingPasien btn btn-xs btn-success">
                      Booking
                    </button>
                  </div>
                </div>
              <?php endforeach;?>

              </div>
            </div>

            <!-- /section:pages/profile.feed -->
          </div>
        </div>
      </div>

  </div><!-- /.col -->
</div><!-- /.row -->

<!-- script for this page only -->
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
  
        $('#form_tmp_user').ajaxForm({
          beforeSend: function() {
            achtungShowLoader();  
          },
          uploadProgress: function(event, position, total, percentComplete) {
          },
          complete: function(xhr) {     
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);

            if(jsonResponse.status === 200){
              $.achtung({message: jsonResponse.message, timeout:3});
              $('#message_success').show({
                  speed: 'slow',
                  timeout: 5000,
              });
            }else{
              $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
          }
        });

        $('#form_update_profile').ajaxForm({
          beforeSend: function() {
            achtungShowLoader();  
          },
          uploadProgress: function(event, position, total, percentComplete) {
          },
          complete: function(xhr) {     
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);

            if(jsonResponse.status === 200){
              $.achtung({message: jsonResponse.message, timeout:3});
              $('#message_success').show({
                  speed: 'slow',
                  timeout: 1000,
              });
            }else{
              $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
          }
        });


      })

      function exc_my_account() {
        $('#form_tmp_user').submit();
        return false;
      }

      function exc_update_profile() {
        $('#form_update_profile').submit();
        return false;
      }

      
    </script>

    <!-- plugin for this page only -->
    <!-- form profile -->

    <script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

    <script>

    function booking(no_mr,relasi_id){
    
          getMenu('booking/regon_booking/formBookingPasien/'+no_mr+'/'+relasi_id);
    
    }

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

        
        $('#form_cari_pasien').focus();    

        $('#form_verifikasi_data_pasien').ajaxForm({      

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

              /*show massage success*/
              setTimeout(function(){location.href=jsonResponse.redirect} , 2000);   

            }else{          

              $.achtung({message: jsonResponse.message, timeout:5});          

            }        

            achtungHideLoader();        

          }      

        });     

          
        $( "#form_cari_pasien" )    

          .keypress(function(event) {        

            var keycode =(event.keyCode?event.keyCode:event.which);         

            if(keycode ==13){          

              event.preventDefault();          

              if($(this).valid()){            

                $('#btn_search_pasien').focus();            

              }          

              return false;                 

            }        

        });      


        $('#btn_search_pasien').click(function (e) {      

          e.preventDefault();      

          if( $("#form_cari_pasien").val() == "" ){

            alert('Masukan keyword minimal 3 Karakter !');

            return $("#form_cari_pasien").focus();

          }else{

            achtungShowLoader();

            $.getJSON("<?php echo site_url('registration/reg_pasien/search_pasien') ?>?keyword=" + $("#form_cari_pasien").val(), '', function (data) {              
              
              achtungHideLoader();

              if( data.count == 0){

                alert('Data tidak ditemukan'); return $("#form_cari_pasien").focus();

              }

              var obj = data.result[0];

              $('#no_mr').val(obj.no_mr);

              $('#noMrHidden').val(obj.no_mr);

              $('#no_ktp').val(obj.no_ktp);

              $('#fullname').val(obj.nama_pasien);

              $('#gender').val(obj.jen_kelamin);

              if( obj.jen_kelamin == 'L' ){
              
                $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');
              
              }else{
                
                $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');

              }
              
              $('#no_telp').val(obj.tlp_almt_ttp);

              $('#pob').val(obj.tempat_lahir);

              $('#dob').val(obj.tgl_lhr);

              $('#address').val(obj.almt_ttp_pasien);
              
              penjamin = (obj.nama_perusahaan==null)?'-':obj.nama_perusahaan;

              $('#kode_perusahaan').val(obj.nama_perusahaan);
           

            });             
            
          }    

        });    

        $('#btnAddNewPasien').click(function (e) { 
          getMenu('booking/regon_booking/addNewPasien');
        });

    })

    </script>



