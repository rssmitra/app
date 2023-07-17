<script type="text/javascript">

  function searchKunjungan(){
    
    $('#spinner_loading').html('Loading...');
    
    var keyword = $('#keyword_ID').val();
    var search_by = $('input[name=search_by]').filter(':checked').val();

    if(keyword == ''){
      alert('Masukan Keyword!'); return false;
    }

    $.getJSON("<?php echo site_url('Templates/References/search_kunjungan_pasien_public') ?>?keyword=" + keyword + "&search_by=" + search_by, '', function (data) {      
      
      // jika data ditemukan
      if( data.count == 1 )     {
        
        $('#result-find-pasien').show();
        $('#no-data-found').hide();
        var obj = data.result[0];

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

        $('#spinner_loading').html('');
        
        

      }else{              
        $('#spinner_loading').html('');
        $('#result-find-pasien').hide();
      } 

      if( data.count_kunjungan > 0){
        $('#spinner_loading').html('');
        $('#result-find-pasien').show();

        html = '<p style="font-weight: bold">Riwayat Kunjungan Pasien</p><table class="table">';
        $.each(data.log_kunjungan, function(key,val) {
            dokter = (val.dokter != null) ? val.dokter : '';
            html += '<tr><td style="padding: 15px; background : #80808014" onclick="getMenu('+"'publik/Pelayanan_publik/konfirmasi_kunjungan/"+val.no_kunjungan+"'"+')"><b>'+val.no_registrasi+' - '+val.tgl_masuk+'</b><br> '+val.poli+'<br>'+dokter+'</td></tr>';
        });
        $('#riwayat_kunjungan').html(html);

        return false;
      }else{

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
    .dropdown-menu > li > a {font-size: 12px !important}
    .dropdown-menu > li > a {
        padding-bottom: 10px;
        margin-bottom: 3px;
        margin-top: 3px;
    }
    .profile-info-name{
      text-align: left !important;
    }
</style>

<form class="form-search" autocomplete="off">
    <div class="pull-left">
      <a href="<?php echo base_url().'public'?>" class="btn btn-sm" style="background : green !important; border-color: green"> <i class="fa fa-home"></i> Home</a>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-12">
        
        <h3 class="header smaller lighter green">Riwayat Kunjungan Pasien</h3>
        <label style="font-weight: bold">Pencarian berdasarkan : </label>
        <div class="radio" style="margin-top: 0px !important;margin-bottom: 0px !important;">
          <label>
            <input name="search_by" type="radio" class="ace" value="no_mr" checked="checked"  />
            <span class="lbl"> No. Rekam Medis</span>
          </label>
          <label>
            <input name="search_by" type="radio" class="ace" value="no_ktp"/>
            <span class="lbl"> NIK </span>
          </label>
          <label>
            <input name="search_by" type="radio" class="ace" value="no_kartu_bpjs"/>
            <span class="lbl"> No. Kartu BPJS </span>
          </label>
        </div>
        <br>
        <label style="font-weight: bold">Masukan Kata Kunci : </label>
        <div class="input-group">
          <span class="input-group-addon">
            <i class="ace-icon fa fa-check"></i>
          </span>

          <input type="text" class="form-control search-query" id="keyword_ID" placeholder="">
          <span class="input-group-btn">
            <button type="button" class="btn btn-purple btn-sm" id="btn-search-data" onclick="searchKunjungan()" style="background : green !important; border-color: green">
              <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
              Cari Data Pasien
            </button>
          </span>
        </div>
        
        <span style="font-size: 12px;font-style: italic;">Masukan keyword, lalu klik "enter"</span>

        <div class="hr"></div>

        <div id="spinner_loading"></div>

        
        <div id="no-data-found" style="display: none">
          <div class="center" style="padding-top: 30px !important">
            <img src="<?php echo base_url()?>assets/images/no-data-found.png" width="200px">
          </div>
        </div>

        <div id="result-find-pasien" class="tab-pane active" style="display: none">
          <!-- data pasien lainnya -->
          <input type="hidden" name="noKartuBpjs" id="noKartuBpjs">
          
          <div class="row">
            <div class="col-xs-12 col-sm-12">
            
            <p style="font-weight: bold">Informasi Data Pasien</p>
              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <small style="color: #669a06; font-weight: bold; font-size: 11px">No. Rekam Medis: </small><div id="no_mr"></div>
                </li>
                <li class="list-group-item">
                  <small style="color: #669a06; font-weight: bold; font-size: 11px">Nama Pasien: </small><div id="nama_pasien_txt"></div>
                </li>
                <li class="list-group-item">
                  <small style="color: #669a06; font-weight: bold; font-size: 11px">NIK: </small><div id="no_ktp"></div>
                </li>
                <li class="list-group-item">
                  <small style="color: #669a06; font-weight: bold; font-size: 11px">Alamat: </small><div id="alamat"></div>
                </li>
                <li class="list-group-item">
                  <small style="color: #669a06; font-weight: bold; font-size: 11px">Tgl Lahir: </small><div id="tgl_lhr"></div>
                </li>
              </ul>
              
              <div class="hr hr-8 dotted"></div>
              
              <div id="riwayat_kunjungan"></div>



            </div><!-- /.col -->
          </div><!-- /.row -->
        </div>

      </div>
    </div>
</form>






