<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script type="text/javascript">
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

  $( "#keyword_form" ).keypress(function(event) {  
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){          
        event.preventDefault();         
        if($(this).valid()){           
          $('#btn_search_data').click();    
        }         
        return false;                
      }       
  });

  $("#btn_create_po").click(function(event){
        event.preventDefault();
        var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
          return $(this).val();
        }).toArray();
        get_detail_brg_po(''+searchIDs+'')
        console.log(searchIDs);
  });

  function checkAll(elm) {

    if($(elm).prop("checked") == true){
      $('.ace').each(function(){
          $(this).prop("checked", true);
      });
    }else{
      $('.ace').prop("checked", false);
    }

  }

  function get_detail_brg_po(myid){

    if(confirm('Are you sure?')){

      $.ajax({
          url: 'purchasing/po/Po_penerbitan/get_detail_brg_po',
          type: "post",
          data: {ID:myid},
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          uploadProgress: function(event, position, total, percentComplete) {
          },
          complete: function(xhr) {     
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);
            getMenu('purchasing/po/Po_penerbitan/create_po/'+$('#flag').val()+'?'+jsonResponse.params+'');
            achtungHideLoader();
          }

      });

  }else{

    return false;

  }
  
}

$('select[name="search_by"]').change(function () {      

    if( $(this).val() == 'month'){
      /*show form month*/
      $('#div_month').show();
      $('#div_keyword').hide();
    }

    if( $(this).val() == 'kode_permintaan'){
      /*show form month*/
      $('#div_month').hide();
      $('#div_keyword').show();
    }

});

function rollback(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'purchasing/po/Po_penerbitan/rollback',
        type: "post",
        data: {ID:myid, flag: $('#flag').val()},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            $.achtung({message: jsonResponse.message, timeout:5});
            reload_table();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
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
    </div>

    <form class="form-horizontal" method="post" id="form_search" action="purchasing/po/Po_penerbitan/find_data?flag=<?php echo $flag?>" autocomplete="off">

      <!-- hidden form -->
      <input type="hidden" name="flag" id="flag" value="<?php echo $flag;?>">

      <div class="form-group">

          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
            <select name="search_by" id="search_by" class="form-control">
              <option value="">-Silahkan Pilih-</option>
              <option value="kode_permohonan" selected>Kode Permintaan</option>
              <option value="month">Bulan</option>
            </select>
          </div>

          <div id="div_month" style="display:none">
            <label class="control-label col-md-1">Bulan</label>
            <div class="col-md-2" style="margin-left: -15px">
              <?php echo $this->master->get_bulan('','month','month','form-control','','')?>
            </div>
          </div>

          <div id="div_keyword">
            <label class="control-label col-md-1">Keyword</label>
            <div class="col-md-2" style="margin-left: -15px">
              <input type="text" class="form-control" name="keyword" id="keyword_form">
            </div>
          </div>

      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Permintaan</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d </label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <div class="col-md-4" style="margin-left:-1.1%">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Search
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
              <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
              Reset
            </a>
          </div>

      </div>
      
      <div class="clearfix" style="margin-bottom:-5px;">
        <a href="#" class="btn btn-xs btn-danger" id="btn_create_po"><i class="fa fa-file"></i> Purchase Order (PO)</a>
      </div>

      <hr class="separator">

      <div style="margin-top:-25px">

          <table id="dynamic-table" base-url="purchasing/po/Po_penerbitan" data-id="flag=<?php echo $flag?>" url-detail="purchasing/po/Po_penerbitan/get_detail" class="table table-bordered table-hover">

          <thead>
          <tr>  
            <th width="30px" class="center">
              <div class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace" name="" onClick="checkAll(this);" value="0"/>
                    <span class="lbl"></span>
                </label>
              </div>
            </th>
            <th width="40px" class="center"></th>
            <th width="40px"></th>
            <th width="50px">ID</th>
            <th>Kode Permintaan</th>
            <th>Tanggal Permintaan</th>
            <th>Petugas</th>
            <th>No Persetujuan</th>
            <th>Tanggal Persetujuan</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Rollback</th>
            
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </div>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->


<script src="<?php echo base_url().'assets/js/custom/als_datatable_with_detail_custom_url.js'?>"></script>



