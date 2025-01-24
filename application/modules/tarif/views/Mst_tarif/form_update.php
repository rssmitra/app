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


    /*submit form*/
    $('#form-tarif').ajaxForm({      

      beforeSend: function() {        
            achtungShowLoader(); 
      },      

      uploadProgress: function(event, position, total, percentComplete) {        

      },      

      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          $('#id').val(jsonResponse.kode_tarif);
          $('#page-area-content').load('tarif/Mst_tarif');
          $.achtung({message: jsonResponse.message, timeout:5});     

        }else{          

          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});          

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
          <i class="ace-icon fa fa-angle-double-right"></i>    
          Unit/Bagian <?php echo isset($value->nama_bagian)?$value->nama_bagian:''?>          
        </small>        

      </h1>      

    </div>  

    <!-- div.dataTables_borderWrap -->

    <div style="margin-top:-10px">   

      <form class="form-horizontal" method="post" id="form-tarif" action="tarif/Mst_tarif/process" enctype="multipart/form-data" autocomplete="off" >      
        
          <br>

          <!-- hidden form -->
          <input type="hidden" value="<?php echo isset($value->kode_tarif)?$value->kode_tarif:''?>" name="id" id="id">

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Nama Tarif</label>
                <div class="col-sm-4">
                   <input type="text" class="form-control" name="nama_tarif" value="<?php echo isset($value->nama_tarif)?$value->nama_tarif:''?>">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Tarif Unit/Bagian</label>
                <div class="col-sm-3">
                  <?php echo $this->master->custom_selection(array('table'=>'view_unit_tarif', 'where'=>array(), 'id'=>'kode_bagian', 'name' => 'nama_bagian'),isset($value->kode_bagian)?$value->kode_bagian:'','kode_bagian','kode_bagian','chosen-slect form-control','','');?>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Jenis</label>
                <div class="col-sm-2">
                  <?php echo $this->master->custom_selection(array('table'=>'mt_jenis_tindakan', 'where'=>array(), 'id'=>'kode_jenis_tindakan', 'name' => 'jenis_tindakan'),isset($value->jenis_tindakan)?$value->jenis_tindakan:'','jenis_tindakan','jenis_tindakan','chosen-slect form-control','','');?>
                </div>
            </div>
            
            <div class="form-actions center">

              <a onclick="getMenu('tarif/Mst_tarif')" href="#" class="btn btn-sm btn-success">
                <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                Kembali ke daftar
              </a>
              <button type="submit" id="btnSave" name="submit"value="update_tarif" class="btn btn-sm btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
            </div>


          </div>

        </form>
    </div>

</div><!-- /.row -->




