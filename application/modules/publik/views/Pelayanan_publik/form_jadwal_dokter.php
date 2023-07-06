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

  function showQuee(){

    $('#result-data').show();

    $.getJSON("publik/Pelayanan_publik/get_data_jadwal_dokter?kode=" + $('#poliklinik').val(), '', function (data) {   
      $('#list_jadwal_dokter').html(data.html);
      
      
    });

  }

</script>

<form class="form-search" autocomplete="off">
    <div class="pull-left">
      <a href="<?php echo base_url().'public'?>" class="btn btn-sm" style="background : green !important; border-color: green"> <i class="fa fa-home"></i> Home</a>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-12">
        
        <h3 class="header smaller lighter green">Jadwal Dokter</h3>

        <div>
            <label for="form-field-8">Silahkan pilih Poli/Klinik</label>
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1)), '' , 'poliklinik', 'poliklinik', 'form-control', '', '') ?>
        </div>

        <div style="padding-top: 8px">
          <a href="#" class="btn btn-sm btn-primary" onclick="showQuee()" style="background : green !important; border-color: green; margin: 0px"> <i class="fa fa-search"></i> Tampilkan Jadwal Dokter</a>
        </div>

        <div id="result-data" class="tab-pane active" style="display: none">

          <div class="row">
            <div class="col-xs-12 col-sm-12">
              <div id="list_jadwal_dokter" style="margin-top: 10px"></div>
            </div>
          </div>
        </div>

      </div>
    </div>
</form>






