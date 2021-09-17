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

function cetak_surat_kontrol(ID) {   
      var no_mr = $('#tabs_riwayat_perjanjian_id').attr('data-id');  
      if( no_mr == '' ){
        alert('Silahkan cari pasien terlebih dahulu !'); return false;
      }else{
        url = 'registration/Reg_pasien/surat_control?id_tc_pesanan='+ID;
        title = 'Cetak Barcode';
        width = 850;
        height = 500;
        PopupCenter(url, title, width, height);
      }

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
    <div class="clearfix" style="margin-bottom:-5px">
      <?php echo $this->authuser->show_button('registration/Input_perjanjian','C','',1)?>
      <?php echo $this->authuser->show_button('registration/Input_perjanjian','D','',5)?>
    </div>
    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="registration/Input_perjanjian" data-id="kode_bagian=<?php echo isset($_GET['kode_bagian'])?$_GET['kode_bagian']:''?>" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center"></th>
          <th></th>
          <th>No MR</th>
          <th>Nama Pasien</th>
          <th>Penjamin</th>
          <th>Tujuan Poli</th>
          <?php if(isset($_GET['kode_bagian']) AND in_array($_GET['kode_bagian'], array('050201') )) : ?>
          <th>Tindakan</th>
          <?php else: ?>
          <th>Nama Dokter</th>
          <?php endif; ?>
          <th>Jam Praktek</th>
          <th>No Telp</th>
          <th>Kd. Perjanjian</th>
          <th>Keterangan</th>
          <th>Status</th>
          
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>

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

      </div> -->

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>

<script src="<?php echo base_url().'assets/js/custom/als_datatable_with_detail_custom_url.js'?>"></script>



