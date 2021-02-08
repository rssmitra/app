<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>

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

  $('.format_number').number( true, 2 );

});

$(document).ready(function(){


    /*focus on form input pasien*/
    $('#form_cari_pasien').focus();    

    /*submit form*/
    $('#form_pelayanan').ajaxForm({      

      beforeSend: function() {        

          if( $('#form_pelayanan').attr('action')=='pelayanan/Pl_pelayanan/processPelayananSelesai' ){
            achtungShowFadeIn();                      
          }

      },      

      uploadProgress: function(event, position, total, percentComplete) {        

      },      

      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          $.achtung({message: jsonResponse.message, timeout:5});     

          $('#table-pesan-resep').DataTable().ajax.reload(null, false);

          $('#jumlah_r').val('')

          $("#modalEditPesan").modal('hide');  

          if(jsonResponse.type_pelayanan == 'Penunjang Medis' )
          {

            getMenuTabs('registration/reg_pasien/riwayat_kunjungan/'+jsonResponse.no_mr+'/'+$('#kode_bagian_val').val()+'', 'tabs_riwayat_kunjungan');

          }

          if(jsonResponse.type_pelayanan == 'Pasien Selesai' )
          {

            getMenu('pelayanan/Pl_pelayanan');

          }
          
        }else{          

          $.achtung({message: jsonResponse.message, timeout:5});          

        }        

        achtungHideLoader();        

      }      

    }); 

})

</script>

<style type="text/css">
  .pagination{
    margin: 0px 0px !important;
  }
  .well{
    padding: 5px !important;
  }
  .format_number{
    text-align: right
  }
</style>
<div class="row">

    <div class="page-header">    

      <h1>      

        <?php echo $title?>        

        <small>        

          <i class="ace-icon fa fa-angle-double-right"></i>          

          Klas - <?php echo isset($value->nama_klas)?$value->nama_klas:''?>          

        </small>        

      </h1>      

    </div>  

    <!-- div.dataTables_borderWrap -->

    <div style="margin-top:-10px">   

      <form class="form-horizontal" method="post" id="form_pelayanan" action="#" enctype="multipart/form-data" autocomplete="off" >      
        
          <br>

          <!-- hidden form -->
          <input type="hidden" value="<?php echo isset($value->kode_tarif)?$value->kode_tarif:''?>" name="kode_tarif" id="kode_tarif">
          <input type="hidden" value="<?php echo isset($value->kode_master_tarif_detail)?$value->kode_master_tarif_detail:''?>" name="kode_master_tarif_detail" id="kode_master_tarif_detail">

            
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Bill dr 1</label>
                <div class="col-sm-2">
                   <input type="text" class="form-control format_number" name="bill_dr1" value="<?php echo isset($value->bill_dr1)?$value->bill_dr1:''?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Bill dr 2</label>
                <div class="col-sm-2">
                   <input type="text" class="form-control format_number" name="bill_dr2" value="<?php echo isset($value->bill_dr2)?$value->bill_dr2:''?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Bill dr 3</label>
                <div class="col-sm-2">
                   <input type="text" class="form-control format_number" name="bill_dr3" value="<?php echo isset($value->bill_dr3)?$value->bill_dr3:''?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Kamar Tindakan</label>
                <div class="col-sm-2">
                   <input type="text" class="form-control format_number" name="kamar_tindakan" value="<?php echo isset($value->kamar_tindakan)?$value->kamar_tindakan:''?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="">BHP</label>
                <div class="col-sm-2">
                   <input type="text" class="form-control format_number" name="bhp" value="<?php echo isset($value->bhp)?$value->bhp:''?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Obat</label>
                <div class="col-sm-2">
                   <input type="text" class="form-control format_number" name="obat" value="<?php echo isset($value->obat)?$value->obat:''?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Alkes</label>
                <div class="col-sm-2">
                   <input type="text" class="form-control format_number" name="alkes" value="<?php echo isset($value->alkes)?$value->alkes:''?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Alat RS</label>
                <div class="col-sm-2">
                   <input type="text" class="form-control format_number" name="alat_rs" value="<?php echo isset($value->alat_rs)?$value->alat_rs:''?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Administrasi</label>
                <div class="col-sm-2">
                   <input type="text" class="form-control format_number" name="adm" value="<?php echo isset($value->adm)?$value->adm:''?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Pendapatan RS</label>
                <div class="col-sm-2">
                   <input type="text" class="form-control format_number" name="pendapatan_rs" value="<?php echo isset($value->pendapatan_rs)?$value->pendapatan_rs:''?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Total</label>
                <div class="col-sm-2">
                   <input type="text" class="form-control format_number" name="total" value="<?php echo isset($value->total)?$value->total:''?>">
                </div>
            </div>
            <div class="form-actions center">

              <a onclick="getMenu('tarif/Mst_tarif')" href="#" class="btn btn-sm btn-success">
                <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                Kembali ke daftar
              </a>
              <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
            </div>


          </div>

        </form>
    </div>

</div><!-- /.row -->




