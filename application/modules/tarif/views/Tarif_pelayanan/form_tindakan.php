<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script type="text/javascript">

$(document).ready(function() {

    $('#InputKeyTindakan').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getTindakanByBagianAutoComplete",
                data: { keyword:query, kode_klas: $('#klas').val(), kode_bag : $('#kode_bagian_selected').val(), kode_perusahaan : $('#kodePerusahaanHidden').val() },            
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
          console.log(val_item);
          $('#pl_kode_tindakan_hidden').val(val_item);

          /*get detail tarif by kode tarif and kode klas*/
          getDetailTarifByKodeTarifAndKlas(val_item, $('#klas').val());
        }

    });

    $('#InputKeyBagian').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getBagian",
                data: { keyword:query },            
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
          console.log(val_item);
          $('#kode_bagian_selected').val(val_item);

        }

    });

    $('#perusahaan').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/References/getPerusahaan",
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
          console.log(val_item);
          $('#kodePerusahaanHidden').val(val_item);
        }

    });

    $('input[name=jenis_penjamin]').click(function(e){
        var field = $('input[name=jenis_penjamin]:checked').val();
        if ( field == 'Jaminan Perusahaan' ) {
          $('#showFormPerusahaan').show('fast');
        }else if (field == 'Umum') {
          $('#kodePerusahaanHidden').val(0);
          $('#showFormPerusahaan').hide('fast');
        }
    });


  });

  function getDetailTarifByKodeTarifAndKlas(kode_tarif, kode_klas){

    $.getJSON("<?php echo site_url('templates/references/getDetailTarif') ?>?kode="+kode_tarif+"&klas="+kode_klas+"&type=html", '' , function (data) {

      /*show detail tarif html*/
      $('#formDetailTarif').show('fast');
      $('#detailTarifHtml').html(data.html);

    })

  }


</script>

<div class="row">
    <div class="col-sm-12">
      <div class="page-header">
        <h1>
          <?php echo $title?>
          <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
          </small>
        </h1>
      </div><!-- /.page-header -->

        <p><b> TINDAKAN PASIEN <i class="fa fa-angle-double-right bigger-120"></i></b></p>

        <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan/find_data">

        <div class="form-group">
          <label class="control-label col-sm-2">Jenis Penjamin</label>
          <div class="col-md-8">
            <div class="radio">
                  <label>
                    <input name="jenis_penjamin" type="radio" class="ace" value="Jaminan Perusahaan" />
                    <span class="lbl"> Jaminan Perusahaan</span>
                  </label>
                  <label>
                    <input name="jenis_penjamin" type="radio" class="ace" value="Umum" checked />
                    <span class="lbl"> Umum</span>
                  </label>
            </div>
          </div>
        </div>

        <div class="form-group" id="showFormPerusahaan" style="display:none">
            <label class="control-label col-sm-2">Perusahaan</label>
            <div class="col-sm-6">
                <input id="perusahaan" name="perusahaan" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />
                <input id="kodePerusahaanHidden" name="kode_perusahaan" class="form-control"  type="hidden" />
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Pilih Bagian/Poli</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="InputKeyBagian" name="nama_bagian" placeholder="Masukan Keyword Tindakan">
                  <input type="hidden" class="form-control" id="kode_bagian_selected" name="kode_bagian_selected" >
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Klas Pasien</label>
            <div class="col-sm-3">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_klas', 'id' => 'kode_klas', 'name' => 'nama_klas', 'where' => array()), '' , 'klas', 'klas', 'form-control', '', '') ?>
            </div>
        </div>

          <div class="form-group">
              <label class="control-label col-sm-2" for="">Nama Tindakan</label>
              <div class="col-sm-6">
                  <input type="text" class="form-control" id="InputKeyTindakan" name="pl_nama_tindakan" placeholder="Masukan Keyword Tindakan">
                  <input type="hidden" class="form-control" id="pl_kode_tindakan_hidden" name="pl_kode_tindakan_hidden" >
              </div>

          </div>
          <div class="form-group" id="formDetailTarif" style="display:none">
              <label class="control-label col-sm-2" for="">&nbsp;</label>
              <div class="col-sm-10" style="margin-left:6px">
                 <div id="detailTarifHtml"></div>
              </div>
          </div>

        </form>
    </div>
</div>







