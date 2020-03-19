<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
              <form class="form-horizontal" method="post" id="form_add_new_pasien" action="<?php echo site_url('booking/regon_booking/process_add_new_pasien')?>" enctype="multipart/form-data">     

                  <!-- hidden form -->
                  <input type="hidden" name="mrOwner" value="<?php echo $this->session->userdata('user_profile')->no_mr?>">
                  <input type="hidden" value="" name="no_mr" id="no_mr">
                  <input type="hidden" value="" name="fullname" id="fullname">
                  <input type="hidden" value="" name="pob" id="pob">
                  <input type="hidden" value="" name="dob" id="dob">
                  <input type="hidden" value="" name="address" id="address">
                  <input type="hidden" value="" name="no_hp" id="no_hp">
                  <input type="hidden" value="" name="gender" id="gender">
                  <input type="hidden" value="" name="status_pasien" id="status_pasien">

                  <div class="form-group">

                    <label class="control-label col-md-3"><b>Masukan Nomor Medical Record (MR)</b></label>            

                    <div class="col-md-5">            

                      <div class="input-group">

                        <input type="text" name="no_mr" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan Nomor Medical Record (MR)">

                        <span class="input-group-btn">

                          <button type="button" id="btn_search_pasien" class="btn btn-default btn-sm">

                            <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>

                            Cari Pasien

                          </button> &nbsp;&nbsp;&nbsp;
                          

                        </span>

                      </div>

                    </div>

                  </div>

                  <p><i class="fa fa-user"></i> <b>DATA PASIEN</b> </p>

                  <table class="table table-bordered table-hover">

                    <thead>

                      <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">No MR</th>

                      <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Nama</th>

                      <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Alamat</th>

                      <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Status</th>

                    </thead>

                    <tbody>

                      <td><div id="no_mr_text">-</div></td>

                      <td><div id="nama_pasien_text">-</div></td>

                      <td><div id="alamat_text">-</div></td>

                      <td align="center"><div id="status_pasien_text">-</div></td>

                    </tbody>

                  </table>

                  <div class="form-group">

                    <label class="control-label col-md-3">Hubungan Pasien</label>            

                    <div class="col-md-3">            

                      <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'hubungan_keluarga')), '' , 'hubungan_relasi', 'klinik_rajal', 'form-control', '', '') ?>

                    </div>

                  </div>

                  <br>
                  <center>
                    <div class="form-group">  
                      <div class="col-md-12"> 
                          <button type="submit" id="btnProsesAdd" name="submit" class="btn btn-sm btn-success">
                              <i class="ace-icon fa fa-chevron-circle-down icon-on-right bigger-110"></i>
                              Tambahkan Pasien
                            </button>
                      </div>
                    </div>
                  </center>

              </form>
            </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


<!-- plugin for this page only -->

<script>

var token = "<?php echo $this->session->userdata('token')?>";

$(document).ready(function(){

    
    $('#form_cari_pasien').focus();    

    $('#form_add_new_pasien').ajaxForm({      

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
          setTimeout(function(){getMenu(jsonResponse.redirect)} , 1000);   

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

      /*session mr*/

      var session_mr = '<?php echo $this->session->userdata('user_profile')->no_mr?>';

      if( $("#form_cari_pasien").val() == "" ){

        alert('Masukan keyword minimal 3 Karakter !');

        return $("#form_cari_pasien").focus();

      }else{

        //achtungShowLoader();

        $.getJSON("<?php echo site_url('registration/reg_pasien/search_pasien') ?>?keyword=" + $("#form_cari_pasien").val()+'&token='+token, '', function (data) {              
          
          if ( data.status !== 200) { 

            $.achtung({message: data.message, timeout:5});

          }else{

            if( data.count == 0){

              alert('Data tidak ditemukan'); return $("#form_cari_pasien").focus();

            }

            var obj = data.result[0];

            if( obj.no_mr == session_mr ){

              alert('Pasien ini adalah Owner'); return $("#form_cari_pasien").focus();

            }

            /*text result*/

            $('#no_mr_text').text(obj.no_mr);

            $('#nama_pasien_text').text(obj.nama_pasien+' ('+obj.jen_kelamin+')');

            $('#alamat_text').text(obj.almt_ttp_pasien+', No Telp/Hp : '+obj.tlp_almt_ttp);

            penjamin = (obj.nama_perusahaan==null)?'-':obj.nama_perusahaan;

            $('#status_pasien_text').text(obj.nama_perusahaan);


            /*value result*/

            $('#no_mr').val(obj.no_mr);
            $('#fullname').val(obj.nama_pasien);
            $('#pob').val(obj.tempat_lahir);
            $('#dob').val(obj.tgl_lahir);
            $('#address').val(obj.almt_ttp_pasien);
            $('#no_hp').val(obj.tlp_almt_ttp);
            $('#gender').val(obj.jen_kelamin);
            $('#status_pasien').val(obj.nama_perusahaan);
            

          }

          achtungHideLoader();

        });             
        
      }    

    });    


})



</script>


