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

$(document).ready(function(){

    get_profile_supplier($('#supplier_id_hidden').val());
    
    $('#load_brg_po').load('purchasing/po/Po_revisi/view_brg_po/<?php echo $flag?>/<?php echo isset($value->id_tc_po)?$value->id_tc_po:''?>');


    $('#form_revisi_po').ajaxForm({
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
          $('#page-area-content').load('purchasing/po/Po_revisi/view_data?flag=<?php echo $flag?>');
          // popup cetak po
          PopupCenter('purchasing/po/Po_penerbitan/print_preview?ID='+jsonResponse.id+'&flag='+jsonResponse.flag+'','Cetak PO',900,650);

        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

    var flag = ( $('#flag_string').val() ) ? $('#flag_string').val() : '' ;
    var search_by = $('select[name="search_by"]').val();
    var keyword = $('#inputKeyWord').val();

    $('#btn_search_brg').click(function (e) {   

        if ( $('#inputKeyWord').val()=='' ) {
          alert('Silahkan Masukan Kata Kunci !'); return false;
        }

        search_selected_brg(flag, search_by, keyword);

        e.preventDefault();

    });

    $( "#inputKeyWord" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
            search_selected_brg(flag, search_by, keyword);       
          }         
          return false;                
        }       
    });  

    $('#inputSupplier').typeahead({
        source: function (query, result) {
                $.ajax({
                    url: "templates/references/getSupplier",
                    data: 'keyword=' + query,         
                    dataType: "json",
                    type: "POST",
                    success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                    }
                });
            },
            afterSelect: function (item) {
            // do what is needed with item
            var val_item=item.split(':')[0];
            $('#detail_supplier').html('');
            console.log(val_item);
            $('#supplier_id_hidden').val(val_item);
            // get detail data supplier
            get_profile_supplier(val_item);
            
        }
    });


})

function get_profile_supplier(id){
  $.getJSON("<?php echo site_url('Templates/References/getSupplierById') ?>/" + id, '', function (response) {
      // detail supplier
      $('#inputSupplier').val(response.namasupplier+' : '+response.namasupplier);
      $('#detail_supplier').html('<address><strong>'+response.namasupplier+'</strong><br>'+response.alamat+'<br>No. Telp : '+response.telpon1+'</address>');
  });
}

function search_selected_brg(flag, search_by, keyword){

  $.ajax({ //Process the form using $.ajax()
      type      : 'POST', //Method type
      url       : 'Templates/References/getRefBrg', //Your form processing file URL
      data      : {keyword: $('#inputKeyWord').val(), flag: flag, search_by: search_by}, //Forms name
      dataType  : 'json',
      success   : function(data) {
          $('#show_detail_selected_brg').html(data.html);
      }
  })

}

$("#btnTakeOutBrg").click(function(event){
      event.preventDefault();
      var searchIDs = $("#table_brg_po input:checkbox:checked").map(function(){
        return $(this).val();
      }).toArray();

      // searchIDs.forEach(function(element) {
      //     console.log(element);
      //     $('#tr_'+element+'').hide('fast');
      // });

      $.ajax({ //Process the form using $.ajax()
          type      : 'POST', //Method type
          url       : 'purchasing/po/Po_revisi/takeOutBrg', //Your form processing file URL
          data      : {kode_brg: searchIDs, flag: $('#flag_string').val(), id_tc_po: $('#id').val() }, //Forms name
          dataType  : 'json',
          success   : function(data) {
              $('#load_brg_po').load('purchasing/po/Po_revisi/view_brg_po/<?php echo $flag?>/<?php echo isset($value->id_tc_po)?$value->id_tc_po:''?>');

          }
      })

});


</script>
<div class="page-header">
  <h1>
    <?php echo $title?>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <div class="widget-body">
        <div class="widget-main no-padding">

          <form class="form-horizontal" method="post" id="form_revisi_po" action="<?php echo site_url('purchasing/po/Po_penerbitan/process')?>" enctype="multipart/form-data" >
            <br>
            <!-- input form hidden -->
            <input name="id" id="id" value="<?php echo isset($value->id_tc_po)?$value->id_tc_po:''?>" class="form-control" type="hidden">
            <input type="hidden" name="flag" id="flag_string" value="<?php echo $flag?>">
            <input type="hidden" name="action" id="action" value="revisi">

            <div class="form-group">
              <label class="control-label col-md-2">Nomor Periodik</label>
              <div class="col-md-2">
                <input name="no_urut_periodik" id="no_urut_periodik" value="<?php echo isset($value->no_urut_periodik)?$value->no_urut_periodik:''?>" class="form-control" type="text" placeholder="Auto" readonly>
              </div>
              <label class="control-label col-md-1">ID</label>
              <div class="col-md-2">
                <input name="id_tc_po" id="id_tc_po" value="<?php echo isset($value->id_tc_po)?$value->id_tc_po:''?>" class="form-control" type="text" placeholder="Auto" readonly>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Nomor PO</label>
              <div class="col-md-2">
                <input name="no_po" id="no_po" value="<?php echo isset($value->no_po)?$value->no_po:''?>" class="form-control" type="text" placeholder="Auto" readonly>
              </div>
              <label class="control-label col-md-1">SIK AA</label>
              <div class="col-md-2">
                <input name="sipa" id="sipa" value="<?php echo isset($value->sipa)?$value->sipa:''?>" class="form-control" type="text" >
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Tanggal PO</label>
              <div class="col-md-2">
                <div class="input-group" style="width: 150px;">
                  <input class="form-control date-picker" name="tgl_po" id="tgl_po" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($value->tgl_po)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_po): date('Y-m-d') ?>"/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>
              <label class="control-label col-md-2">Estimasi Kirim</label>
              <div class="col-md-2">
                <div class="input-group" style="width: 150px;">
                  <input class="form-control date-picker" name="tgl_kirim" id="tgl_kirim" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($value->tgl_kirim)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_kirim):''?>"/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>
              <span style="color: red"><i>Maximal pengiriman 7 Hari</i></span>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Diajukan Oleh</label>
              <div class="col-md-3">
                <input name="diajukan_oleh" id="diajukan_oleh" value="<?php echo isset($value->diajukan_oleh)?$value->diajukan_oleh:''?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Disetujui Oleh</label>
              <div class="col-md-3">
                <input name="disetujui_oleh" id="disetujui_oleh" value="<?php echo isset($value->disetujui_oleh)?$value->disetujui_oleh:''?>" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">KARS</label>
              <div class="col-md-3">
                <input name="krs" id="krs" value="<?php echo isset($value->krs)?$value->krs:''?>" class="form-control" type="text">
              </div>
            </div>

            <p><b>PILIH SUPPLIER</b></p>
            <div class="form-group">
                <label class="control-label col-md-2">Supplier</label>
                <div class="col-sm-6">
                  <input id="inputSupplier" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />
                  <input type="hidden" name="kodesupplier" id="supplier_id_hidden" class="form-control" value="<?php echo isset($value->kodesupplier)?$value->kodesupplier:''?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2"></label>
                <div class="col-sm-6" style="margin-left: 10px">
                <div id="detail_supplier"></div>
                </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Syarat Pembayaran</label>
              <div class="col-md-4">
                <textarea class="form-control" style="height:50px !important" name="term_of_pay">30 hari setelah tukar faktur</textarea>
              </div>
            </div>
            <br>

            <div class="pull-right">
              <a onclick="getMenu('purchasing/po/Po_revisi/view_data?flag=<?php echo $flag?>', 'tabs_form_po')" href="#" class="btn btn-xs btn-success">
                <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                Kembali ke daftar
              </a>
              <button type="reset" id="btnTakeOutBrg" class="btn btn-xs btn-danger">
                <i class="ace-icon fa fa-sign-out icon-on-right bigger-110"></i>
                Keluarkan dari PO
              </button>
              <button type="submit" id="btnSave" name="submit" class="btn btn-xs btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
            </div>

            <div id="load_brg_po"></div>
            
          </form>

        </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


