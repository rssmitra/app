<script type="text/javascript">
  $(document).ready(function(){

    $( ".form-control" )    
        .keypress(function(event) {  
          var keycode =(event.keyCode?event.keyCode:event.which);  
          if(keycode ==13){   
            event.preventDefault();  
            $('#btn_search_data').click();  
            return false;  
          }  
    });

    $('#btn_search_data').click(function (e) {
      var url_search = $('#form_search').attr('action');
      e.preventDefault();
      $.ajax({
      url: url_search,
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      success: function(response) {
        console.log(response.data);
        $('#rekap_lembur_pegawai').load('kepegawaian/Kepeg_rekap_lembur_unit/show_lembur_pegawai?'+response.data+'');
      }
    });
   });

  })

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
    <form class="form-horizontal" method="post" id="form_search" action="kepegawaian/Kepeg_rekap_lembur_unit/find_data">

      <div class="form-group" style="margin-bottom: 3px">
        <div class="control-label col-md-2">
          <div class="checkbox" style="margin-top: -5px">
            <label>
              <input name="checked_unit" id="checked_unit" type="checkbox" class="ace" value="1">
              <span class="lbl"> Unit/Bagian</span>
            </label>
          </div>
        </div>
        <div class="col-md-3" style="margin-left: -15px">
          <?php echo $this->master->custom_selection(array('table'=>'kepeg_mt_unit', 'where'=>array(), 'id'=>'kepeg_unit_id', 'name' => 'kepeg_unit_nama'),'','unit','unit','chosen-slect form-control','','');?>
        </div>

        <div class="control-label col-md-1">
          <div class="checkbox" style="margin-top: -5px">
            <label>
              <input name="checked_unit" id="checked_unit" type="checkbox" class="ace" value="1">
              <span class="lbl"> Periode </span>
            </label>
          </div>
        </div>
        <div class="col-md-2" style="margin-left: -15px">
          <?php echo $this->master->get_bulan(isset($value->periode_lembur_bln)?$value->periode_lembur_bln:'' , 'periode_lembur_bln', 'periode_lembur_bln', 'form-control', '', '') ?> 
        </div>
        <div class="col-md-2" style="margin-left: -15px">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Tampilkan Data
          </a>
        </div>

      </div>

      <hr class="separator">
      
      <div id="rekap_lembur_pegawai"></div>
      
    </form>

  </div><!-- /.col -->
</div><!-- /.row -->




