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
            achtungShowFadeIn(); 
      },      

      uploadProgress: function(event, position, total, percentComplete) {        

      },      

      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          $('#id').val(jsonResponse.kode_tarif);

          $.achtung({message: jsonResponse.message, timeout:5});  

          getMenu('tarif/Mst_tarif');   

        }else{          

          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});          

        }        

        achtungHideLoader();        

      }      

    }); 

    if(!ace.vars['touch']) {
        $('.chosen-select').chosen({allow_single_deselect:true}); 
        //resize the chosen on window resize

        $(window)
        .off('resize.chosen')
        .on('resize.chosen', function() {
          $('.chosen-select').each(function() {
              var $this = $(this);
              $this.next().css({'width': $this.parent().width()});
          })
        }).trigger('resize.chosen');

  }

})

function changeTotal(){
  sum = sumClass('format_number');
  sumFormat = formatMoney(parseInt(sum));

  console.log(sum);
  console.log(sumFormat);
  $('#total').val( sum );
  $('#txt_total_tarif').text( sumFormat );
}


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

</div><!-- /.row -->

<div class="row">
  <div class="col-xs-12">
    <form class="form-horizontal" method="post" id="form-tarif" action="tarif/Mst_tarif/process" enctype="multipart/form-data" autocomplete="off" >      
        
        <br>

        <!-- hidden form -->
        <input type="hidden" value="<?php echo isset($value->kode_tarif)?$value->kode_tarif:''?>" name="id" id="id">
        <input type="hidden" value="<?php echo isset($value->kode_master_tarif_detail)?$value->kode_master_tarif_detail:''?>" name="kode_master_tarif_detail" id="kode_master_tarif_detail" id="kode_master_tarif_detail">

        <p style="font-weight: bold">DATA TARIF</p>
        <div class="form-group">
            <label class="control-label col-sm-2" for="">Nama Tarif</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="nama_tarif" value="<?php echo isset($value->nama_tarif)?$value->nama_tarif:''?>">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Jenis Tarif</label>
            <div class="col-sm-2">
              <?php echo $this->master->custom_selection(array('table'=>'mt_jenis_tindakan', 'where'=>array(), 'id'=>'kode_jenis_tindakan', 'name' => 'jenis_tindakan'),isset($value->jenis_tindakan)?$value->jenis_tindakan:'','jenis_tindakan','jenis_tindakan','chosen-select form-control','','');?>
            </div>
            <label class="control-label col-sm-1" for="">Unit/Bagian</label>
            <div class="col-sm-3">
              <?php echo $this->master->custom_selection(array('table'=>'mt_bagian', 'where'=>array('pelayanan' => 1), 'id'=>'kode_bagian', 'name' => 'nama_bagian'),isset($value->kode_bagian)?$value->kode_bagian:'','kode_bagian','kode_bagian','chosen-select form-control','','');?>
            </div>
            <div class="col-sm-3">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" style="font-style: italic"> Global Tarif</span>
                </label>
              </div>
            </div>
        </div>
        
        <p style="font-weight: bold; padding-top: 10px">RINCIAN KELAS TARIF</p>
        <table class="table">
          <thead>
            <th>No</th>
            <th>Kelas Tarif</th>
            <th>Group</th>
            <th>Margin (%)</th>
            <th>Jasa Dr1</th>
            <th>Jasa Dr2</th>
            <th>Kamar Operasi</th>
            <th>BHP</th>
            <th>Alat Medis</th>
            <th>Profit RS</th>
            <th>Total Tarif</th>
          </thead>
          <tbody>
            <?php 
              $no=0; foreach($klas as $row_klas) : $no++; 
              // group
              $klsg = '';
              if(isset($klas_group[$row_klas->kode_klas])){
                $klsg .= '(';
                foreach ($klas_group[$row_klas->kode_klas] as $k => $v) {
                  $klsg.= $v->nama_klas.'/ ';
                }
                $klsg .= ')';
              }
            ?>
              <tr>
                <td width="30px" align="center"><?php echo $no;?></td>
                <td><?php echo $row_klas->nama_klas?></td>
                <td width="150px"><?php echo $klsg?></td>
                <td style="width: 100px !important"><input type="text" class="form-control format_number"></td>
                <td style="width: 100px !important"><input type="text" class="form-control format_number"></td>
                <td style="width: 100px !important"><input type="text" class="form-control format_number"></td>
                <td style="width: 100px !important"><input type="text" class="form-control format_number"></td>
                <td style="width: 100px !important"><input type="text" class="form-control format_number"></td>
                <td style="width: 100px !important"><input type="text" class="form-control format_number"></td>
                <td style="width: 100px !important"><input type="text" class="form-control format_number"></td>
                <td style="width: 100px !important"><input type="text" class="form-control format_number"></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        
        <div class="form-actions center">

          <a onclick="getMenu('tarif/Mst_tarif')" href="#" class="btn btn-sm btn-success">
            <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
            Kembali ke daftar
          </a>
          <button type="submit" id="btnSave" name="submit" value="create_tarif" class="btn btn-sm btn-info">
            <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
            Submit
          </button>
        </div>

    </form>
  </div>
</div>




