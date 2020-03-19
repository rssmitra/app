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

$('select[name="bagian"]').change(function () {      

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

    <center><h4>PERSETUJUAN JADWAL OPERASI<br><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data yang belum di ACC tanggal <?php echo $this->tanggal->formatDate(date('Y-m-d'))?> </small></h4></center>
      <br>

    <!-- div.dataTables_borderWrap -->
    <div class="clearfix">

      <?php if(count($result) > 0 ) : foreach($result as $row) :?>

        <div class="col-md-4" style="cursor: pointer; margin-bottom: 10px" onclick="getMenu('kamar_bedah/Ok_acc_jadwal_bedah/form/<?php echo $row->id_pesan_bedah?>/<?php echo $row->no_kunjungan?>');">

            <div style="width:30%;float:left;">
              <img alt="Bob Doe's avatar" src="<?php echo base_url();?>assets/images/folder.png" style="width:100%">
            </div>

            <div style="width:70%;float:left;margin-top:10px;margin-left:-1%">
              <table class="table" style="font-size:11px; font-family: sans-serif">
                <tr>
                  <td>No MR</td>
                  <td>: <?php echo $row->no_mr?></td>
                </tr>
                <tr>
                  <td>Nama</td>
                  <td>: <?php echo $row->nama_pasien?></td>
                </tr>
                <tr>
                  <td>Tgl Pesan</td>
                  <td>: <?php echo $this->tanggal->formatDateTime( $row->tgl_pesan )?></td>
                </tr>
                <tr>
                  <td>Penjamin</td>
                  <td>: <?php echo substr($row->nama_perusahaan, 0, 17)?> (<?php echo $row->nama_klas?>)</td>
                </tr>
              </table>
            </div>

        </div>
        
      <?php endforeach; else : echo '<div class="alert alert-info"><strong>Pemberitahuan !</strong> Tidak ada jadwal operasi yang harus di ACC hari ini</div>'; endif; ?>

    </div>

  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



