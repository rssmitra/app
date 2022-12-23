<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script type="text/javascript">

    $('#form_update_klas').ajaxForm({
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
          load_billing_data();
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }
        achtungHideLoader();
      }
    }); 

    $('select[name="ri_ruangan"]').change(function () {      

        $('#ri_no_bed_hidden').val('');
        $('#ri_no_ruangan').val('');

        if ($(this).val()) {          

            $.getJSON("<?php echo site_url('Templates/References/getDeposit') ?>/" + $(this).val() + "/" +$("#ri_klas_ruangan").val(), '', function (data) {                       

                $.each(data, function (i, o) {                  

                    console.log(o.deposit)
                    deposit = (o.deposit!==null)?addPeriod(o.deposit):0;

                    harga_ruangan = addPeriod(o.harga_r);

                    harga_ruangan_bpjs = addPeriod(o.harga_bpjs);

                    $('#ri_deposit').val(o.deposit);

                    $('#ri_deposit_show').val(deposit);

                    $('#ri_harga_ruangan_hidden').val(o.harga_r);

                    $('#ri_harga_ruangan_bpjs_hidden').val(o.harga_bpjs);

                });                

            }); 

        } else {          

            $('#ri_klas_ruangan option').remove()  

             $('#ri_dokter_ruangan option').remove()          

        }        

    });  

    $('select[name="ri_klas_ruangan"]').change(function () {      


        /*hide value*/
        $('#ri_no_bed_hidden').val('');
        $('#ri_no_ruangan').val('');
        $('#ri_deposit').val(0);
        $('#ri_harga_ruangan_hidden').val('');
        $('#ri_harga_ruangan_bpjs_hidden').val('');

        if ($(this).val()) {          

            /*get ruangan by klas*/
            $.getJSON("<?php echo site_url('Templates/References/getRuanganByKlas') ?>/" + $(this).val(), '', function (data) {              

                $('#ri_ruangan option').remove();                

                $('<option value="">-Pilih Ruangan-</option>').appendTo($('#ri_ruangan'));                

                $.each(data, function (i, o) {                  

                    $('<option value="' + o.kode_bagian + '">' + o.nama_bagian + '</option>').appendTo($('#ri_ruangan'));                    

                });                

            }); 

        }    

    });  

    function addPeriod(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return x1 + x2;
    }

    function showModalBed() {

        $.getJSON("<?php echo site_url('Templates/References/getBedByKlasWithView') ?>/" + $("#ri_ruangan").val() + "/" +  $("#ri_klas_ruangan").val(), '', function (data) {              
            
        //    achtungHideLoader();
            
            $('#result_bed').html(data.html);

            showModal_bed();  

        });       

    }

    function showModal_bed()

    {  

        $("#modalBed").modal();  

    }

    function select_bed_from_modal_bed(kode,bed,kamar){
        
        preventDefault();

        $("#modalBed").modal('hide');

        $('#div_load_after_selected_pasien').show('fast');

        $('#div_riwayat_pasien').show('fast');

        $('#ri_no_ruangan').val(kode);

        $('#ri_no_ruangan').text(kode);  

        $('#ri_no_bed_hidden').val(bed);

        $('#ri_no_ruangan').focus();

        kelas = $('#ri_klas_ruangan option:selected').text();
        ruangan = $('#ri_ruangan option:selected').text();

        $('#text_title_selected_ruangan_klas').text(ruangan+' '+kelas);

        /*Table Value*/
        $('#td_kode_ruangan').text(kode);  
        $('#td_nama_ruangan').text(ruangan);  
        $('#td_kelas').text(kelas);  
        $('#td_bed').text(bed);  
        $('#td_kamar').text(kamar);  

    }


</script>

<hr>

<form class="form-horizontal" method="post" id="form_update_klas" action="<?php echo site_url('billing/Billing/process_update_klas_tarif')?>" enctype="multipart/form-data" autocomplete="off">
  
    <!-- hidden -->
    <input type="hidden" id="no_registrasi" value="<?php echo $no_registrasi?>" name="no_registrasi">

    <div class="form-group">
        <label class="control-label col-sm-2">*Kelas Pasien</label>
        <div class="col-sm-3">
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_klas', 'id' => 'kode_klas', 'name' => 'nama_klas', 'where' => array()), '' , 'ri_klas_ruangan', 'ri_klas_ruangan', 'form-control', '', '') ?>
        </div>
    </div>

    <div  class="form-group">
        <label class="control-label col-sm-2">*Pilih Ruangan</label>
        <div class="col-sm-4">
            <?php echo $this->master->get_change($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => '0300', 'pelayanan' => 1)), '' , 'ri_ruangan', 'ri_ruangan', 'form-control', '', '') ?>
        </div>
    </div>

    <div  class="form-group">

        <label class="control-label col-sm-2">Uang Muka</label>

        <div class="col-sm-2">
        
            <input type="text" name="ri_deposit_show" id="ri_deposit_show" placeholder="0" class="form-control" readonly>
            <input type="hidden" name="ri_deposit" id="ri_deposit">
            <input type="hidden" name="ri_harga_ruangan_hidden" id="ri_harga_ruangan_hidden">
            <input type="hidden" name="ri_harga_ruangan_bpjs_hidden" id="ri_harga_ruangan_bpjs_hidden">
        
        </div>

        <label class="control-label col-sm-1">Kode</label>            

        <div class="col-md-3">            

          <div class="input-group">

            <input type="text" name="ri_no_ruangan" id="ri_no_ruangan" class="form-control" readonly>

            <span class="input-group-btn">

              <button type="button" class="btn btn-primary btn-sm" onclick="showModalBed()">

                <span class="ace-icon fa fa-bed icon-on-right bigger-110"></span>

                Pilih Bed

              </button>

            </span>

            </div>

            <input type="hidden" name="ri_no_bed_hidden" id="ri_no_bed_hidden">
            
        </div> 

    </div>

    <div class="form-group">
        <label class="col-sm-2">&nbsp;</label>   
        <div class="col-sm-8" style="padding-left: 18px">
          <table class="table">
                <thead>
              <tr>
                  <td>Kode</td>
                  <td>Ruangan</td>
                  <td>Kelas</td>
                  <td>Kamar</td>
                  <td>Bed</td>
              </tr>
              </thead>

              <tr>
                  <td id="td_kode_ruangan">-</td>
                  <td id="td_nama_ruangan">-</td>
                  <td id="td_kelas">-</td>
                  <td id="td_kamar">-</td>
                  <td id="td_bed">-</td>
              </tr>
          </table>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2">&nbsp;</label>   
        <div class="col-sm-8" style="padding-left: 18px">
          <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
            <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
            Submit
          </button>
        </div>
    </div>
    
  </form>


<!-- MODAL BED -->

<div id="modalBed" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;width:80%">

    <div class="modal-content">
    
      <div class="modal-header">

          <div class="table-header">

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

                <span class="white">&times;</span>

            </button>

            <span id="text_title_selected_ruangan_klas">RUANGAN KELAS</span>

          </div>

      </div>

      <div class="modal-body no-padding">

          <div id="result_bed"></div>
          
      </div>

      <div class="modal-footer no-margin-top">

        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

          <i class="ace-icon fa fa-times"></i>

          Close

        </button>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div><!-- /.modal-dialog -->


