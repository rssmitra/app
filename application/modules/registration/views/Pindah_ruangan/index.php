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


$(document).ready(function(){

  

});     

$('select[name="klinik"]').change(function () {      

    if ($(this).val()) {          

        $.getJSON("<?php echo site_url('Templates/References/getDokterByBagian') ?>/" + $(this).val() , function (data) {              

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


  function showModalPindahRuangan(kode_ri, no_mr)

  {  

    $('#result_text_daftar_perjanjian').text('PINDAH RUANGAN');

    $('#form_pindah_ruangan').load('registration/pindah_ruangan/form_pindah_ruangan/'+no_mr+'?ID='+kode_ri); 

    $("#modalPindahRuangan").modal();
      
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

    <form class="form-horizontal" method="post" id="form_search" action="registration/Pindah_ruangan/find_data">

    <div class="col-md-12">

      <center><h4>FORM PENCARIAN DATA PESANAN PINDAH RUANGAN<br><small style="font-size:12px">(Silahkan lakukan pencarian data berdasarkan parameter dibawah ini)</small></h4></center>
      <br>

      <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
            <select name="search_by">
              <option value="no_mr">No MR</option>
              <option value="nama">Nama Pasien</option>
            </select>
          </div>

          <label class="control-label col-md-1" style="margin-left:-3.7%">Keyword</label>
          <div class="col-md-2">
            <input type="text" class="form-control" name="keyword">
          </div>

      </div>

      <div class="form-group">

        <label class="control-label col-md-2">*Ruangan</label>

        <div class="col-md-4">

            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => '0300','status_aktif' => 1) ), '' , 'ruangan', 'ruangan', 'form-control', '', '') ?>

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

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="registration/Pindah_ruangan" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center"></th>
          <th></th>
          <th>No MR</th>
          <th>Nama Pasien</th>
          <th>Penjamin</th>
          <th>Tanggal Masuk</th>
          <th>Ruangan</th>
          <th>Kelas</th>
          
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


<div id="modalPindahRuangan" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:70%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_riwayat_medis">PINDAH RUANGAN</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="form_pindah_ruangan"></div>

      </div>

      </div> -->

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



