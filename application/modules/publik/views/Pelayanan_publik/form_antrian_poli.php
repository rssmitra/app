<script type="text/javascript">

  $(document).ready(function(){

    window.filter = function(element)
    {
      var value = $(element).val().toUpperCase();

      $(".itemdiv").each(function() 
      {
        if ($(this).text().toUpperCase().search(value) > -1){
          $(this).show();
        }
        else {
          $(this).hide();
        }
      });
    }
  });

  $('select[name="poliklinik"]').change(function () {      


    $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val(), '', function (data) {              

        $('#select_dokter option').remove();                

        $('<option value="">-Pilih Dokter-</option>').appendTo($('#select_dokter'));                         

        $.each(data, function (i, o) {                  

            $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#select_dokter'));                    
              
        });      


    });    

  }); 
  
  function showQuee(){

    $('#result-data').show();
    $('#list_antrian_existing .itemdiv').remove();

    $.getJSON("publik/Pelayanan_publik/get_data_antrian_pasien?kode_bagian=" + $('#poliklinik').val()+"&kode_dokter="+$('#select_dokter').val()+"", '', function (data) {   
      var arr = [];
      var arr_cancel = [];
      var no = 0;
      console.log(data.length);

      if(data.length == 0){
        $('<span class="itemdiv" style="color: red; font-weight: bold">Tidak ada pasien berobat untuk hari ini</span>').appendTo('#list_antrian_existing');
      }

      $.each(data, function (i, o) {   
        
          var penjamin = (o.kode_perusahaan==120)? '<span style="background: #f998878c; padding: 3px">('+o.nama_perusahaan+')</span>' : '<span style="background: #6fb3e0; padding: 3px">(UMUM)</span>' ;

          var style = ( o.status_batal == 1 ) ? 'style="background-color: red; color: white"' : (o.tgl_keluar_poli == null) ? '' : 'style="background-color: lightgrey; color: black"' ;

        no++;
          if(o.status_batal == 1){

            html_cancel = '';
            html_cancel += '<div class="itemdiv commentdiv">';
            html_cancel += '<div class="user">';
            html_cancel += '<h2 style="margin-top: 6px !important; text-align: center">'+no+'</h2>';
            html_cancel += '</div>';
            html_cancel += '<div class="body" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
            html_cancel += '<div class="name">';
            html_cancel += '<a href="#">'+o.no_mr+'</a>';
            html_cancel += '</div>';
            html_cancel += '<div class="time">';
            html_cancel += '<i class="ace-icon fa fa-times-circle red"></i>';
            html_cancel += '<span class="red"> batal kunjungan</span>';
            html_cancel += '</div>';
            html_cancel += '<div class="text">';
            html_cancel += '<span style="font-size: 14px">'+o.nama_pasien+'</span><br>';
            html_cancel += '<span style="font-size:10px">'+penjamin+'</span>';
            html_cancel += '</div>';
            html_cancel += '</div>';
            html_cancel += '</div>';
            
            $(html_cancel).appendTo($('#list_antrian_existing'));
          
          }else{

            if(o.tgl_keluar_poli == null){

              html_existing = '';
              html_existing += '<div class="itemdiv commentdiv" style="box-shadow: inset 0 0 10px #0000002e;">';
              html_existing += '<div class="user">';
              html_existing += '<h2 style="margin-top: 6px !important; text-align: center">'+no+'</h2>';
              html_existing += '</div>';
              html_existing += '<div class="body" style="cursor: pointer" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
              html_existing += '<div class="name">';
              html_existing += '<span style="font-size: 16px; font-weight: bold">'+o.no_mr+'</span>';
              html_existing += '</div>';
              html_existing += '<div class="text">';
              html_existing += '<span style="font-size: 14px">'+o.nama_pasien+'</span><br>';
              html_existing += '<span style="font-size:10px">'+penjamin+'</span>';
              html_existing += '</div>';
              html_existing += '</div>';
              html_existing += '</div>';

              $(html_existing).appendTo($('#list_antrian_existing'));

            }

            if(o.tgl_keluar_poli != null){

              html_done = '';
              html_done += '<div class="itemdiv commentdiv" style="background: linear-gradient(45deg, yellowgreen, transparent)">';
              html_done += '<div class="user" style="background: #a7d353">';
              html_done += '<h2 style="margin-top: 6px !important; text-align: center">'+no+'</h2>';
              html_done += '</div>';
              html_done += '<div class="body" style="cursor: pointer" onclick="click_selected_patient('+o.id_pl_tc_poli+','+o.no_kunjungan+','+"'"+o.no_mr+"'"+')">';
              html_done += '<div class="name">';
              html_done += '<span style="font-size: 16px; font-weight: bold">'+o.no_mr+'</span>';
              html_done += '</div>';
              html_done += '<div class="time">';
              html_done += '<i class="ace-icon fa fa-check-circle green"></i>';
              html_done += '<span class="green"> sudah diperiksa</span>';
              html_done += '</div>';
              html_done += '<div class="text">';
              html_done += '<span style="font-size: 14px">'+o.nama_pasien+'</span><br>';
              html_done += '<span style="font-size:10px">'+penjamin+'</span>';
              html_done += '</div>';
              html_done += '</div>';
              html_done += '</div>';
              
              $(html_done).appendTo($('#list_antrian_existing'));

            }
          }
          
          // sudah dilayani
          if (o.tgl_keluar_poli != null) {
              arr.push(o);
          }
          // batal
          if (o.status_batal == 1) {
            arr_cancel.push(o);
          }
      });   

      
    });

  }

</script>

<form class="form-search" autocomplete="off">
    <div class="pull-left">
      <a href="<?php echo base_url().'public'?>" class="btn btn-sm" style="background : green !important; border-color: green"> <i class="fa fa-home"></i> Home</a>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-12">
        
        <h3 class="header smaller lighter green" style="margin-bottom: 0px !important">Antrian Poliklinik</h3>
        <span style="margin-top: -5px; font-weight: bold; font-style: italic">Tanggal, <?php echo date('D, d/M/Y')?></span>

        <br>
        <br>
        <div>
            <label for="form-field-8">Silahkan pilih Poli/Klinik</label>
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1)), '' , 'poliklinik', 'poliklinik', 'form-control', '', '') ?>
        </div>

        <div style="padding-top: 8px">
            <label for="form-field-8">Dokter</label>
            <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'select_dokter', 'select_dokter', 'form-control', '', '') ?>
        </div>

        <div style="padding-top: 8px">
          <a href="#" class="btn btn-sm btn-primary" onclick="showQuee()" style="background : green !important; border-color: green; margin: 0px"> <i class="fa fa-search"></i> Tampilkan Antrian</a>
        </div>

        <div id="result-data" class="tab-pane active" style="display: none">

          <div class="row">
            <div class="col-xs-12 col-sm-12">
              <div class="center">
                <span class="pull-left" style="padding-top: 10px"><b>Cari nama anda :</b></span> <br>
                <input type="text" id="seacrh_ul_li" value="" placeholder="Masukan keyword..." class="form-control" onkeyup="filter(this);">
              </div>
              
              <div id="list_antrian_existing" style="margin-top: 10px"></div>
            </div>
          </div>
        </div>

      </div>
    </div>
</form>






