<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>
  $(document).ready(function () {
      $('#inputKey').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "ws_bpjs/Ws_index/getRef?ref=<?php echo $mod?>",
                  data: { keyword:query,jf:$('input[name=jenis_faskes]:checked').val() },            
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                  }
              });
          }
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
          <form class="form-horizontal" method="post" id="formRefFaskes" action="" enctype="multipart/form-data" autocomplete="off">
            <br>
            <div class="col-md-8">
              <div class="box box-danger">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="control-label col-md-3">Jenis Faskes</label>
                      <div class="col-md-6">
                        <div class="radio">
                              <label>
                                <input name="jenis_faskes" type="radio" class="ace" value="1" />
                                <span class="lbl"> Faskes 1 / Puskesmas</span>
                              </label>
                              <label>
                                <input name="jenis_faskes" type="radio" class="ace" value="2" />
                                <span class="lbl"> Faskes 2 / RS </span>
                              </label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Masukan Keyword <label style="color:red;font-size:small">*</label></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input id="inputKey" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />
                        </div>
                    </div>

                      <h3>Keterangan : </h3>

                      Fungsi : Pencarian data fasilitas kesehatan <br>

                      Method : GET <br>

                      Format : Json <br>

                      Content-Type: application/json; charset=utf-8 <br>

                      Parameter 1 : nama atau kode faskes <br>

                      Parameter 2 : Jenis Faskes (1. Faskes 1, 2. Faskes 2/RS) <br>

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


