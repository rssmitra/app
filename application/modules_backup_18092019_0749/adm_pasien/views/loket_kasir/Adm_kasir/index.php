<script type="text/javascript">
  
  $(document).ready(function(){

    oTable = $('#dt_pasien_kasir').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bLengthChange": true,
      "pageLength": 25,
      "bInfo": true,
      "paging": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#dt_pasien_kasir').attr('base-url'),
          "data": {flag:$('#flag').val(), date:$('#date').val(), month:$('#month').val(), year:$('#year').val()},
          "type": "POST"
      },

      "columnDefs": [
          { 
            "targets": [ 0 ], //last column
            "orderable": false, //set not orderable
          },
          { "aTargets" : [ 1 ], "mData" : 1, "sClass":  "details-control"}, 
          { "visible": true, "targets": [ 1 ] },
          { "targets": [ 2 ], "visible": false },
      ],

    });

    $('#dt_pasien_kasir tbody').on('click', 'td.details-control', function () {
        var url_detail = $('#dt_pasien_kasir').attr('url-detail');
        preventDefault();
        var tr = $(this).closest('tr');
        var row = oTable.row( tr );
        var data = oTable.row( $(this).parents('tr') ).data();
        var kode_primary = data[ 2 ];                  

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/            
            $.getJSON( url_detail + "/" + kode_primary +"/RJ", '' , function (data) {
                response_data = data;
                // Open this row
                row.child( format_html( response_data ) ).show();
                tr.addClass('shown');
            });
            
        }
    } );

    $('#dt_pasien_kasir tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    $("#merge_registrasi").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dt_pasien_kasir input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          merge_registrasi(''+searchIDs+'')
          console.log(searchIDs);
    });

    function merge_registrasi( arr ){
      $.ajax({
          url: 'adm_pasien/loket_kasir/Adm_kasir/merge_data_registrasi',
          type: "post",
          data: { value : arr },
          dataType: "json",
          beforeSend: function() {
          },
          success: function(data) {
            
          }
      });
    }

  })

  function format_html ( data ) {
    return data.html;
  }

</script>

<script type="text/javascript">

  $( "#keyword" ).keypress(function(event) {  
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){          
        event.preventDefault();         
        if($(this).valid()){           
          $('#btn_search_data').click();    
        }         
        return false;                
      }       
  });

  $('#btn_search_data').click(function (e) {   
        /*if ( $('#keyword').val()=='' ) {
          alert('Silahkan Cari Pasien !'); return false;
        }*/
        search_trans_pasien($('#flag').val(), $('select[name=search_by]').val(), $('#keyword').val());
        e.preventDefault();
    });

  $('#btn_reset_data').click(function (e) {  
      reset_trans_pasien();
      e.preventDefault();
  });

  $('#add_search_by_date').click(function() {
    if (!$(this).is(':checked')) {
      $('#form_tanggal').hide();
    }else{
      $('#form_tanggal').show();
    }
  });

  function search_trans_pasien(flag, search_by, keyword){

    $.ajax({ //Process the form using $.ajax()
        type      : 'POST', //Method type
        url       : $('#form_search').attr('action'), //Your form processing file URL
        data      : $('#form_search').serialize(), //Forms name
        dataType  : 'json',
        success   : function(data) {
            $('#show_detail_selected_brg').html(data.html);
            $('#showDataTables').hide();
        }
    })

  }

  function reset_trans_pasien(){

    $('#show_detail_selected_brg').hide();
    $('#showDataTables').show();

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

    <form class="form-horizontal" method="post" id="form_search" action="adm_pasien/loket_kasir/Adm_kasir/getTransPasien">
      <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">
      <center>
          <h4>TRANSAKSI PASIEN <?php echo($flag=='umum')?'UMUM/ASURANSI':'BPJS'?><br><small style="font-size:12px">Silahkan lakukan pencarian data pasien terlebih dahulu </small></h4>
      </center>
    
      <br>
      <!-- hidden form -->
      <div class="form-group">
        <label class="control-label col-md-1">Pencarian</label>
        <div class="col-md-2">
          <select name="search_by" id="search_by" class="form-control">
            <option value="">-Silahkan Pilih-</option>
            <option value="no_mr" selected>No MR</option>
            <option value="nama_pasien_layan">Nama Pasien</option>
          </select>
        </div>
        <label class="control-label col-md-1">Keyword</label>
        <div class="col-sm-6">
          <input type="text" class="col-xs-10 col-sm-3" name="keyword" id="keyword">
          <span class="help-inline col-xs-12 col-sm-7">
            <label class="middle">
              <input class="ace" type="checkbox" id="add_search_by_date" name="is_with_date" value="1">
              <span class="lbl"> Tambahkan pencarian tanggal</span>
            </label>
          </span>
        </div>
      </div>

      <div class="form-group" id="form_tanggal" style="display:none">
        <label class="control-label col-md-1">Tanggal</label>
        <div class="col-md-1">
          <select name="date" id="date" class="form-control">
            <option value="">-Tanggal-</option>
            <?php 
              for($i=1; $i<=31;$i++) : 
                $selected = ($i==date('d'))?'selected':'';
            ?>
            <option value="<?php echo $i?>" <?php echo $selected?> ><?php echo $i?></option>
            <?php endfor;?>
          </select>
        </div>
        <div class="col-md-2" style="margin-left: -20px">
          <select name="month" id="month" style="width: 100px !important">
            <option value="">-Bulan-</option>
            <?php 
              for($j=1; $j<=12;$j++) : 
                $selected = ($j==date('m'))?'selected':'';
            ?>
            <option value="<?php echo $j?>" <?php echo $selected?> ><?php echo $this->tanggal->getBulan($j)?></option>
            <?php endfor;?>
          </select>
        </div>
        <div class="col-md-1" style="margin-left: -65px">
          <?php echo $this->master->get_tahun(date('Y'),'year','year','form-control','','')?>
        </div>
      </div>    
      <div class="form-group">
        <label class="control-label col-md-1">&nbsp;</label>
        <div class="col-md-2" style="margin-left:6px">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Cari
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-danger">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
        </div>
      </div>   

      <hr class="separator">

      <div id="show_detail_selected_brg"></div>   

      <div id="showDataTables">
        <table id="dt_pasien_kasir" base-url="adm_pasien/loket_kasir/adm_kasir/get_data?flag=<?php echo $flag?>" url-detail="billing/Billing/getDetailBillingKasir" class="table table-bordered table-hover">
          <thead>
            <tr style="background-color:#428bca">
              <th width="50px"></th>
              <th width="50px"></th>
              <th></th>
              <th>No. Reg</th>
              <th>No. MR</th>
              <th>Nama Pasien</th>
              <th>Poli/Klinik Asal</th>
              <th>Penjamin</th>
              <th width="150px">Tanggal Masuk</th>
              <th>Total Billing</th>
            </tr>
          </thead>
        </table>
      </div>   

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




