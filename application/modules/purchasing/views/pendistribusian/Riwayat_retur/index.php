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
          url: 'purchasing/pendistribusian/Riwayat_retur/get_detail_brg_po',
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
            getMenuTabs('purchasing/pendistribusian/Riwayat_retur/create_po/'+$('#flag_string').val()+'?'+jsonResponse.params+'', 'tabs_form_po');
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

</script>
<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
    </small>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_search" action="purchasing/pendistribusian/Riwayat_retur/find_data?flag=<?php echo $flag?>">

      <center>
          <h4>
            PERMINTAAN BARANG UNIT ( GUDANG <?php echo($flag=='non_medis')?'UMUM':'MEDIS'?> )<br><small style="font-size:12px">Data yang ditampilkan saat ini adalah data permintaan barang <?php echo($flag=='non_medis')?'Non Medis':'Medis'?> seluruh unit Tahun <?php echo date('Y')?> </small>
          </h4>
      </center>
    
      <!-- hidden form -->
      <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">

      <div class="form-group">
          <label class="control-label col-md-2">Pilih Bagian/Unit</label>
          <div class="col-md-5">
          <?php 
              echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), '' , 'kode_bagian', 'kode_bagian', 'form-control', '', '') ?>
          </div>
      </div>

      <div class="form-group">
          <label class="control-label col-md-2">Tanggal Permintaan</label>
          <div class="col-md-2">
          <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
          <label class="control-label col-md-1">s/d</label>
          <div class="col-md-2">
          <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
          <div class="col-md-4">
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
      
      <hr class="separator">
      <div class="clearfix" style="margin-bottom:-5px">
          <!-- <?php echo $this->authuser->show_button('purchasing/pendistribusian/Riwayat_retur?flag='.$flag.'','C','',7)?> -->
          <?php echo $this->authuser->show_button('purchasing/pendistribusian/Riwayat_retur?flag='.$flag.'','D','',5)?>
          <a href="" class="btn btn-xs btn-inverse" id="button_print_multiple"><i class="fa fa-print"></i> Print Selected</a>
        </div>

      <hr class="separator">
      <div style="margin-top:-25px">
        <table id="dynamic-table" base-url="purchasing/pendistribusian/Riwayat_retur" data-id="flag=<?php echo $flag?>" url-detail="purchasing/pendistribusian/Riwayat_retur/get_detail/<?php echo $flag?>" class="table table-bordered table-hover">
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
              <th width="40px"></th>
              <th width="50px">ID</th>
              <th>No. Retur</th>
              <th>Tanggal</th>
              <th>Retur dari Bagian/Unit</th>
              <th>Petugas Unit</th>
              <th>Petugas Gudang</th>
              <th>Keterangan</th>
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



