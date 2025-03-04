<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>
  document.getElementById("formRefDokter").reset();
  $(document).ready(function () {

      // reff spesialis
      $.getJSON("ws_bpjs/Ws_index/getRef?ref=RefSpesialistik", '', function (data) {
                $('#spesialis option').remove();
                $('<option value="">-Silahkan Pilih-</option>').appendTo($('#spesialis'));
                $.each(data, function (i, o) {
                    $('<option value="' + o.kode + '">' + o.nama + '</option>').appendTo($('#spesialis'));
                });

      });

      $('#inputKey').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "ws_bpjs/Ws_index/getRef?ref=<?php echo $mod?>",
        data: 'keyword=' + query,            
                  dataType: "json",
                  data: $('#formRefDokter').serialize(),
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
          <form class="form-horizontal" method="post" id="formRefDokter" action="" enctype="multipart/form-data" autocomplete="off">
            <br>
            <div class="col-md-8">
              <div class="box box-danger">
                  <div class="box-body">
                      <div class="form-group">
                        <label class="control-label col-md-3">Tanggal Pelayanan</label>
                        <div class="col-md-3">
                          <div class="input-group">
                              <input class="form-control date-picker" name="tgl" id="tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                               <span class="input-group-addon">
                                <i class="ace-icon fa fa-calendar"></i>
                              </span>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3">Jenis Pelayanan</label>
                        <div class="col-md-6">
                          <div class="radio">
                                <label>
                                  <input name="jp" type="radio" class="ace" value="2" checked/>
                                  <span class="lbl"> Rawat Jalan</span>
                                </label>
                                <label>
                                  <input name="jp" type="radio" class="ace" value="1" />
                                  <span class="lbl"> Rawat Inap </span>
                                </label>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-3 col-sm-3 col-xs-12 control-label">Spesialis</label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                              <select name="spesialis" id="spesialis" class="form-control">
                                <option value="">- Silahkan Pilih -</option>
                              </select>
                          </div>
                      </div>

                      <div class="form-group">
                          <label class="col-md-3 col-sm-3 col-xs-12 control-label">Masukan Keyword <label style="color:red;font-size:small">*</label></label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                              <input id="inputKey" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />
                          </div>
                      </div>

                      <h3>Keterangan : </h3>

                      Fungsi : Pencarian data dokter DPJP <br>

                      Method : GET <br>

                      Format : Json <br>

                      Content-Type: application/json; charset=utf-8 <br>

                      Parameter : Nama Dokter / DPJP <br>

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


