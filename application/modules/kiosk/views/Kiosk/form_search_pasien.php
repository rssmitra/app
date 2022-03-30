<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script type="text/javascript">

  function searchItem(){
    var keyword = $('#keyword_ID').val();

    if(keyword == ''){
      alert('Masukan Keyword!'); return false;
    }

    $.getJSON("<?php echo site_url('Templates/References/search_pasien') ?>?keyword=" + keyword, '', function (data) {      

      // jika data ditemukan
      
      if( data.count == 1 )     {
        
        $('#result-find-pasien').show();
        $('#no-data-found').hide();
        var obj = data.result[0];

        // for default breadcrumb
        $('#breadcrumb_nama_pasien').text(obj.nama_pasien+' ('+obj.jen_kelamin+')');
        $('#breadcrumb_description').text(obj.no_mr+' | '+obj.almt_ttp_pasien+' | '+getFormattedDate(obj.tgl_lhr)+'');

        // value
        $('#nama_pasien').val(obj.nama_pasien);
        $('#no_mr_val').val(obj.no_mr);

        var umur_pasien = hitung_usia(obj.tgl_lhr);
        $('#umur_saat_pelayanan_hidden').val(umur_pasien);

        // text
        $('#no_mr').text(obj.no_mr);
        $('#no_ktp').text(obj.no_ktp);
        $('#nama_pasien_txt').text(obj.nama_pasien+' ('+obj.jen_kelamin+')');
        $('#jk').text(obj.jen_kelamin);
        $('#alamat').text(obj.almt_ttp_pasien);
        $('#hp').text(obj.no_hp);
        $('#no_telp').text(obj.tlp_almt_ttp);
        $('#ttd_pasien').attr('src', obj.ttd);
        $('#tgl_lhr').text(getFormattedDate(obj.tgl_lhr));
        $('#noKartuBpjs').val(obj.no_kartu_bpjs);

        if( obj.url_foto_pasien ){
          $('#avatar').attr('src', '<?php echo base_url()?>uploaded/images/photo/'+obj.url_foto_pasien+'');
        }else{
          if( obj.jen_kelamin == 'L' ){
            $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');
          }else{
            $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');
          }
        }

        penjamin = (obj.nama_perusahaan==null)?obj.nama_kelompok:obj.nama_perusahaan;
        kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;

        $('#penjamin').text(penjamin);
        $('#kode_kelompok_hidden').val(obj.kode_kelompok);
        $('#kode_perusahaan_hidden').val(obj.kode_perusahaan);
        
       

      }else{              

        $('#no-data-found').show();
        $('#no-data-found').html('<div class="alert alert-danger"><strong>Data tidak ditemukan!</strong><br>Silahkan masukan No Rekam Medis/NIK anda dengan benar<div>'); 

      }           

    }); 

  }

  $( "#keyword_ID" )    
    .keypress(function(event) {        
      var keycode =(event.keyCode?event.keyCode:event.which);         
      if(keycode ==13){          
        event.preventDefault();          
        if($(this).valid()){            
          $('#btn-search-data').click();            
        }          
        return false;                 
      }        
  });

</script>

<style type="text/css">
    table{
      width: 100% !important;
      font-size: 12px;
    }
    .table-custom thead {
      background-color: #14506b;
      color: white;
    }

    .table-custom th, td {
      padding: 10px;
      border: 1px solid #c5d0dc;
    }
    .table-custom tbody tr:hover {background-color: #e6e6e6e0;}
    .td_custom{font-size: 20px;font-weight: bold;color: black;border: 5px solid white; cursor: pointer}
    .typeahead{min-width: 87%}
    .dropdown-menu > li > a {font-size: 18px !important}
    .dropdown-menu > li > a {
        padding-bottom: 10px;
        margin-bottom: 3px;
        margin-top: 3px;
    }
    .profile-info-name{
      text-align: left !important;
    }
</style>

<div class="space-8"></div>

<form class="form-search" autocomplete="off">
    <div class="row">
      <div class="col-xs-12 col-sm-12">
        
        <label style="font-size: 16px; font-weight: bold">Masukan No Rekam Medis/NIK : </label>
        <div class="input-group input-group-lg">
          <span class="input-group-addon">
            <i class="ace-icon fa fa-check"></i>
          </span>

          <input type="text" class="form-control" id="keyword_ID" placeholder="" style="height: 55px !important; font-size: 24px !important; text-transform: uppercase;">
          <span class="input-group-btn">
            <button type="button" class="btn btn-lg" id="btn-search-data" onclick="searchItem()" style="height: 55px !important; background: green !important; border-color: green">
              <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
              Search
            </button>
          </span>
        </div>
        <span style="font-size: 12px;font-style: italic;">Masukan keyword, lalu klik "enter"</span>

        <div class="hr"></div>
        
        <div id="no-data-found">
          <div class="center" style="padding-top: 120px !important">
            <img src="<?php echo base_url()?>assets/images/no-data-found.png">
          </div>
        </div>

        <div id="result-find-pasien" class="tab-pane active" style="display: none">
          <div class="row">
            <div class="col-xs-12 col-sm-3 center">
              <span class="profile-picture">
                <img class="editable img-responsive" id="avatar" style="min-width: 150px" src="<?php echo base_url()?>assets/images/no_pict.jpg">
              </span>

              <div class="space space-4"></div>

              <a href="#" class="btn btn-lg btn-success" style="background : green !important; border-color: green">
                <i class="ace-icon fa fa-pencil bigger-120"></i>
                <span class="bigger-110">Update Data</span>
              </a>

            </div>

            <div class="col-xs-12 col-sm-9">
              <h4 class="blue">
                <span class="middle" id="nama_pasien_txt">-</span>
              </h4>

              <div class="profile-user-info">

                <div class="profile-info-row">
                  <div class="profile-info-name"> No Rekam Medis </div>
                  <div class="profile-info-value">
                    <span id="no_mr">-</span>
                  </div>
                </div>

                <div class="profile-info-row">
                  <div class="profile-info-name"> NIK </div>
                  <div class="profile-info-value">
                    <span id="no_ktp"></span>
                  </div>
                </div>

                <div class="profile-info-row">
                  <div class="profile-info-name"> Alamat </div>
                  <div class="profile-info-value">
                    <span id="alamat">-</span>
                  </div>
                </div>

                <div class="profile-info-row">
                  <div class="profile-info-name"> Tgl Lahir </div>
                  <div class="profile-info-value">
                    <span id="tgl_lhr">-</span>
                  </div>
                </div>

                <div class="profile-info-row">
                  <div class="profile-info-name"> Penjamin </div>
                  <div class="profile-info-value">
                    <span id="">-</span>
                  </div>
                </div>

              </div>

              <div class="hr hr-8 dotted"></div>

            </div><!-- /.col -->
          </div><!-- /.row -->
          <div class="center">
            <a href="#" class="btn btn-lg" style="background : green !important; border-color: green" onclick="getMenu('kiosk/Kiosk/main')">Lanjutkan ke Menu Utama <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>

      </div>
    </div>
  </form>


<!-- <div style="background: white; padding: 10px">
  <?php 
    foreach ($spesialis as $key => $val) : 
      $mod = fmod($key, 2);
      if($mod == 0){
        $genap[] = $val;
      }else{
        $ganjil[] = $val;
      }
    endforeach;
  ?>

  <div class="col-md-6">
    <table class="table-custom">
      <tbody>
        <?php foreach ($genap as $value) :?>
        <tr>
          <td align="left" class="td_custom"><span onclick="getMenu('Kiosk/jadwal_dokter?kode=<?php echo $value->kode_bagian?>')"><?php echo strtoupper($value->nama_bagian)?></span></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>

  <div class="col-md-6">
    <table class="table-custom">
      <tbody>
        <?php foreach ($ganjil as $value) :?>
          <tr>
            <td align="left" class="td_custom"><span onclick="getMenu('Kiosk/jadwal_dokter?kode=<?php echo $value->kode_bagian?>')"><?php echo strtoupper($value->nama_bagian)?></span></td>
          </tr>
          <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div> -->






