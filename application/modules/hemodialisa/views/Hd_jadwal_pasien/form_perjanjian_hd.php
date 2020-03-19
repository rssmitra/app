<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

$(document).ready(function(){
 
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
          $('#klinik_rajal').focus();
        }

    });

    $('input[name=jenis_penjamin]').click(function(e){
        var field = $('input[name=jenis_penjamin]:checked').val();
        if ( field == 'Jaminan Perusahaan' ) {
          $('#showFormPerusahaan').show('fast');
          $('#perusahaan').focus();
        }else if (field == 'Umum') {
          $('#showFormPerusahaan').hide('fast');
        }
    });

})

$('select[name="klinik_rajal"]').change(function () {      

        $('#show_detail_praktek').hide('fast');
        $('#tgl_kunjungan_form').hide('fast');
        $('#view_last_message').hide('fast');

        if ($(this).val()) {          

            $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val(), '', function (data) {              

                $('#dokter_rajal option').remove();                

                $('<option value="">-Pilih Dokter-</option>').appendTo($('#dokter_rajal'));                

                $.each(data, function (i, o) {                  

                    $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#dokter_rajal'));                    

                });                

            });            

        } else {          

            $('#dokter_rajal option').remove()            

        }     
        $('#dokter_rajal').focus();  

  });

  $('select[name="dokter_rajal"]').change(function () {      

      if ($(this).val()) {          

          var kode_spesialis = $('#klinik_rajal').val();

      } else {          

          /*remove */       

      }      

  });

function formatDate(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  return date.getMonth()+1 + "/" + date.getDate() + "/" + date.getFullYear();
}

</script>

<div class="row">

  <div class="col-xs-12">  

    <!-- div.dataTables_borderWrap -->

    <div>    

    <div id="user-profile-1" class="user-profile row">
      
      <div class="col-xs-12 col-sm-12">
        

        <!-- hidden form  -->

        <!-- end hidden form  -->

        <div class="form-group">
          <label class="control-label col-sm-2">Penjamin Pasien</label>
          <div class="col-md-8">
            <div class="radio">
                  <label>
                    <input name="jenis_penjamin" type="radio" class="ace" value="Jaminan Perusahaan" />
                    <span class="lbl"> Jaminan Perusahaan</span>
                  </label>
                  <label>
                    <input name="jenis_penjamin" type="radio" class="ace" value="Umum" />
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

        <p><b><i class="fa fa-edit"></i> PERJANJIAN PASIEN HD  </b></p>

        <div class="form-group">

            <label class="control-label col-sm-2" for="Province">*Klinik</label>

            <div class="col-sm-5">

                <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1, 'kode_bagian' => '013101')), '' , 'klinik_rajal', 'klinik_rajal', 'form-control', '', '') ?>

            </div>

        </div>

        <div class="form-group">

            <label class="control-label col-sm-2" for="City">*Dokter</label>

            <div class="col-sm-4">

                <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array() ), '' , 'dokter_rajal', 'dokter_rajal', 'form-control', '', '') ?>

            </div>

        </div>

        <div class="form-group">

            <label class="control-label col-sm-2" for="City">*Pilih hari</label>

            <div class="col-sm-10" style="margin-top:3px">

                <?php
                  for ($i=1; $i < 8; $i++) { 
                    echo '<label style="padding-left:10px">
                            <input name="selected_days[]" type="checkbox" class="ace" value="'.$this->tanggal->getDayByNum($i).'">
                            <span class="lbl"> '.$this->tanggal->getDayByNum($i).'</span>
                          </label> &nbsp;&nbsp;';
                  }
                ?>

            </div>

        </div>

        <div class="form-group">

            <label class="control-label col-sm-2" for="City">Catatan</label>

            <div class="col-sm-4">

                <textarea class="form-control" name="catatan" style="height:50px !important"></textarea>

            </div>

        </div>

        <div class="form-group">

            <label class="control-label col-sm-2" for="City">&nbsp;</label>

            <div class="col-sm-4">

                <button type="submit" class="btn btn-xs btn-primary" style="margin-left:2%"><i class="fa fa-save"></i> Submit</button>

            </div>

        </div>

    </div>

    

    </div>

  </div><!-- /.col -->

</div><!-- /.row -->

