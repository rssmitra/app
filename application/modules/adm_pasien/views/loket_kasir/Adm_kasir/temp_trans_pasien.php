<script type="text/javascript">
  
  $(document).ready(function(){

    oTable = $('#dt_search_result_pasien').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bLengthChange": false,
      "pageLength": 5,
      "bInfo": true,
      "paging": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#dt_search_result_pasien').attr('base-url'),
          "data": {
                    flag:$('#flag').val(),
                    search_by:$('#search_by').val(),
                    keyword:$('#keyword').val(),
                    is_with_date:$('#is_with_date').val(),
                    date:$('#date').val(),
                    month:$('#month').val(),
                    year:$('#year').val(),
                  },
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

    $('#dt_search_result_pasien tbody').on('click', 'td.details-control', function () {
        var url_detail = $('#dt_search_result_pasien').attr('url-detail');
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

    $('#dt_search_result_pasien tbody').on( 'click', 'tr', function () {
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
          var searchIDs = $("#dt_search_result_pasien input:checkbox:checked").map(function(){
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
<!-- input hidden -->
<form id="form-hidden-for-load-data" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="is_with_date" id="is_with_date" value="<?php echo $is_with_date?>">
<input type="hidden" name="flag_string" id="flag_string" value="<?php echo $flag?>">
<input type="hidden" name="search_by" id="search_by" value="<?php echo $search_by?>">
<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword?>">
<input type="hidden" name="date" id="date" value="<?php echo $date?>">
<input type="hidden" name="month" id="month" value="<?php echo $month?>">
<input type="hidden" name="year" id="year" value="<?php echo $year?>">
</form>

<div class="row">
  <div class="col-md-12">

  <?php if(count($dt_pasien) != 0) :?>
  <div class="row">
    <div class="col-md-2">
        <img id="avatar" class="editable img-responsive editable-click editable-empty" alt="Alex's Avatar" src="<?php echo base_url()?>assets/avatars/profile-pic.jpg" width="150px">
    </div>

    <div class="col-md-10">
      <table width="60%" style="font-size:12px">
        <tr>
          <td colspan="2"><b>Data Pasien</b></td>
        </tr>
        <tr>
          <td width="150px">No MR (Medical Record)</td>
          <td>: <?php echo $dt_pasien->no_mr?></td>
        </tr>
        <tr>
          <td>Nama Pasien</td>
          <td>: <?php echo $dt_pasien->nama_pasien?></td>
        </tr>
        <tr>
          <td>TTL</td>
          <td>: <?php echo $dt_pasien->tempat_lahir?>, <?php echo $this->tanggal->formatDate($dt_pasien->tgl_lhr)?></td>
        </tr>
        <tr>
          <td colspan="2"><b>Kunjungan Terakhir</b></td>
        </tr>
        <tr>
          <td>Kunjungan Terakhir</td>
          <td>: <?php echo $this->tanggal->formatDateTime($dt_pasien->tgl_jam_masuk)?></td>
        </tr>
        <tr>
          <td>Poli/Klinik</td>
          <td>: <?php echo ucwords($dt_pasien->nama_bagian)?></td>
        </tr>
        <tr>
          <td>Dokter</td>
          <td>: <?php echo $dt_pasien->nama_pegawai?></td>
        </tr>

      </table>
    </div>
  </div>
  <?php endif;?>
  
  <div class="row">
    
    <div class="col-md-12">
      <form action="#" method="POST" id="form_data_pasien" enctype="multipart/form-data">
        <div class="pull-right">
            <button class="btn btn-xs btn-primary" type="button" id="merge_registrasi"> <i class="fa fa-leaf"></i> Merge Data </button> 
            <button class="btn btn-xs btn-danger" type="button" id="delete_selected"> <i class="fa fa-trash"></i> Hapus </button>
        </div>
        <table id="dt_search_result_pasien" base-url="adm_pasien/loket_kasir/adm_kasir/get_data?flag=<?php echo $flag?>&pelayanan=<?php echo $pelayanan?>" url-detail="billing/Billing/getDetailBillingKasir" class="table table-bordered table-hover">
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
      </form>
    </div>
  </div>
    
  </div>
</div>

