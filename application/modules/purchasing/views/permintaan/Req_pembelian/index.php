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

  function proses_persetujuan(id){
    preventDefault();

    if( confirm('Apakah anda yakin?') ){
      $.ajax({
        url: 'purchasing/permintaan/Req_pembelian/process_persetujuan?flag=<?php echo $flag?>',
        type: "post",
        data: {ID: id},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        success: function(data) {
          achtungHideLoader();
          // response after process
          preventDefault();
          reload_table();
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
    </div><!-- /.page-header -->

    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan_ri/find_data">

        <center>
            <h4>PERMINTAAN PEMBELIAN GUDANG <?php echo($flag=='non_medis')?'UMUM':'MEDIS'?> <br><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data Permintaan Pembelian Gudang <?php echo($flag=='non_medis')?'Umum':'Medis'?> Tahun <?php echo date('Y')?> </small></h4>
        </center>
      
        <br>
        <!-- hidden form -->
        <input type="hidden" name="flag" value="<?php echo $flag;?>">

        <div class="form-group">
            <label class="control-label col-md-2">Pencarian berdasarkan</label>
            <div class="col-md-2">
              <select name="search_by" id="search_by" class="form-control">
                <option value="">-Silahkan Pilih-</option>
                <option value="kode_permohonan" selected>Kode Permintaan</option>
                <option value="nama_barang">Nama Barang</option>
              </select>
            </div>

            <label class="control-label col-md-1">Keyword</label>
            <div class="col-md-2">
              <input type="text" class="form-control" name="keyword" id="keyword_form">
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

            <label class="control-label col-md-1">s/d</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Status Permintaan</label>
            <div class="col-md-2">
                <select name="status_persetujuan" id="status_persetujuan">
                  <option value="" selected>- Silahkan Pilih -</option>
                  <option value="0">Sudah disetujui</option>
                  <option value="NULL">Belum disetujui</option>
                  <option value="1">Tidak disetujui</option>
                  <!-- <option value="belum lunas">Sudah Lunas</option> -->
                </select>
            </div>
            <div class="col-md-4" style="margin-left: -3%">
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

        <div class="clearfix" style="margin-bottom:-5px">
          <?php echo $this->authuser->show_button('purchasing/permintaan/Req_pembelian?flag='.$flag.'','C','',7)?>
          <?php echo $this->authuser->show_button('purchasing/permintaan/Req_pembelian?flag='.$flag.'','D','',5)?>
          <a href="" class="btn btn-xs btn-inverse" id="button_print_multiple"><i class="fa fa-print"></i> Print Selected</a>
        </div>

        <hr class="separator">

        <div style="margin-top:-27px">

          <table id="dynamic-table" base-url="purchasing/permintaan/Req_pembelian" data-id="flag=<?php echo $flag?>" url-detail="purchasing/permintaan/Req_pembelian/get_detail" class="table table-bordered table-hover">
            <thead>
            <tr>  
              <th width="30px" class="center"></th>
              <th width="40px" class="center"></th>
              <th width="40px" class="center"></th>
              <th width="40px"></th>
              <th width="50px">ID</th>
              <th width="100px">Kode Permintaan</th>
              <th width="120px">Unit/Bagian</th>
              <th width="100px">Petugas</th>
              <th width="100px">Jenis</th>
              <th width="200px">Keterangan</th>
              <th width="150px">Persetujuan</th>
              <th width="100px">Total Barang</th>
              <th width="150px">Status</th>
              
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



