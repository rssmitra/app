<script type="text/javascript">
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
          url: 'purchasing/penerimaan_brg/Pb_riwayat/get_detail_brg_po',
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
            getMenuTabs('purchasing/penerimaan_brg/Pb_riwayat/create_po/'+$('#flag_string').val()+'?'+jsonResponse.params+'', 'tabs_form_po');
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
<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_search" action="permintaan/Req_riwayat_permintaan_pemb">

      <center>
          <h4>
            PENERIMAAN BARANG DARI SUPPLIER KE ( GUDANG <?php echo($flag=='non_medis')?'UMUM':'MEDIS'?> )<br><small style="font-size:12px">Data yang ditampilkan saat ini adalah PO (Purchase Order) Data Gudang <?php echo($flag=='non_medis')?'Umum':'Medis'?> Yang sudah disetujui Tahun <?php echo date('Y')?> </small>
          </h4>
      </center>
    
      <!-- hidden form -->
      <input type="hidden" name="flag_string" id="flag_string" value="<?php echo $flag?>">

      <div class="form-group">

          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2" style="margin-left:-2%">
            <select name="search_by" id="search_by" class="form-control">
              <option value="">-Silahkan Pilih-</option>
              <option value="kode_permintaan" selected>Kode Permintaan</option>
              <option value="month">Bulan</option>
            </select>
          </div>

          <div id="div_month" style="display:none">
            <label class="control-label col-md-1">Bulan</label>
            <div class="col-md-2" style="margin-left:-2%">
              <?php echo $this->master->get_bulan('','month','month','form-control','','')?>
            </div>
          </div>

          <div id="div_keyword">
            <label class="control-label col-md-1">Keyword</label>
            <div class="col-md-2" style="margin-left:-2%">
              <input type="text" class="form-control" name="keyword" id="keyword_form">
            </div>
          </div>

          <div class="col-md-2" style="margin-left:-1%">
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

      <div style="margin-top:-25px">

        <table id="dynamic-table" base-url="purchasing/penerimaan_brg/Pb_riwayat" url-detail="purchasing/penerimaan_brg/Pb_riwayat/get_detail/<?php echo $flag?>" data-id="flag=<?php echo $flag?>" class="table table-bordered table-hover">
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
            <th>Kode Penerimaan</th>
            <th>Tanggal</th>
            <th>No. PO</th>
            <th>Nama Supplier</th>
            <th>No Faktur</th>
            <th>Petugas</th>
            <th>Pengirim</th>
            
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



