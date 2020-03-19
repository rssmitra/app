<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>
  $( document ).ready(function() {
        console.log( "document loaded" );

        $.getJSON("ws_bpjs/Ws_index/getRef?ref=<?php echo $mod?>", '', function (data) {
                $('#select_option option').remove();
                $('<option value="">-Silahkan Pilih-</option>').appendTo($('#select_option'));
                $.each(data, function (i, o) {
                    $('<option value="' + o.kode + '">' + o.nama + '</option>').appendTo($('#select_option'));
                });

            });
  });

</script>

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <div class="widget-body">
        <div class="widget-main no-padding">
          <form class="form-horizontal" method="post" id="formRefKelasRawat" action="" enctype="multipart/form-data" autocomplete="off">
            <br>
            <div class="col-md-8">
              <div class="box box-danger">
                  <div class="box-body">
                      <div class="form-group">
                          <label class="col-md-3 col-sm-3 col-xs-12 control-label">Select Option <label style="color:red;font-size:small">*</label></label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                              <select name="selected" id="select_option" class="form-control">
                                <option value="">- Silahkan Pilih -</option>
                              </select>
                          </div>
                      </div>

                      <h3>Keterangan : </h3>

                      Fungsi : Pencarian data kelas rawat <br>

                      Method : GET <br>

                      Format : Json <br>

                      Content-Type: application/json; charset=utf-8 <br>

                  </div>
              </div>

            </div>
            <div class="col-xs-4">
                
            </div>

        </form>
      </div>
    </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


