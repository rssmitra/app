<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script type="text/javascript">

  $(document).ready(function(){

    $('#form_edit_penjamin').ajaxForm({      

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

          $('#modalEditPenjamin').modal('hide');
          console.log(jsonResponse);
          /*show action after success submit form*/
          $("#tabs_detail_pasien").load("registration/reg_pasien/riwayat_kunjungan/"+jsonResponse.no_mr);

        }else{          

          $.achtung({message: jsonResponse.message, timeout:5});          

        }        

        achtungHideLoader();        

      }      

    }); 


  });


</script>

<script>

  $(document).ready(function(){

    $('#InputKeyNasabahEditPenjamin').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getKelompokNasabah",
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
          $('#kode_kelompok_hidden_edit_penjamin').val(val_item);

          if (val_item == 3) {

            $('#field_perusahaan').show('fast');

          }else {

            $('#field_perusahaan').hide('fast');

          }

          // if(val_item !== 3){
          //   $('#kode_perusahaan_hidden_edit_penjamin').val('');
          //   $('#InputKeyPenjaminEdit').val('');
          // }  
        }
    });

    $('#InputKeyPenjaminEdit').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getPerusahaan",
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
          $('#kode_perusahaan_hidden_edit_penjamin').val(val_item);
          if( val_item == 120 ){
            $('#form_sep_edit_penjamin').show();
          }else{
            $('#form_sep_edit_penjamin').hide();
          }
        }
    });

  })
  

</script>

<div class="row">

<form class="form-horizontal" method="post" id="form_edit_penjamin" action="registration/Reg_pasien/process_edit_transaksi_penjamin_pasien" enctype="multipart/form-data" autocomplete="off">

  <div class="col-sm-12 widget-container-col ui-sortable">
    <div class="widget-box transparent ui-sortable-handle">
      <div class="widget-header">
        <h4 class="widget-title lighter"><strong style="font-size:12px"><?php echo strtoupper($result['registrasi']->nama_pasien)?> (<?php echo $result['registrasi']->no_mr?>) </strong></h4>
      </div>
      <div class="widget-body">

        <div class="col-md-6">
          <table border="0" width="100%">
            <tr>
              <td>No. Registrasi</td>
              <td colspan="3"> : <?php echo $result['registrasi']->no_registrasi?></td>
            </tr>

             <tr>
              <td>Tanggal</td>
              <td colspan="3"> : <?php echo $this->tanggal->formatDateTime($result['registrasi']->tgl_jam_masuk)?></td>
            </tr>

            <tr>
              <td>Poli/Klinik</td>
              <td colspan="3"> : <?php echo ucwords($result['registrasi']->poli_tujuan_kunjungan)?></td>
            </tr>

            <tr>
              <td>Dokter</td>
              <td colspan="3"> : <?php echo $result['registrasi']->nama_pegawai?></td>
            </tr>

            <tr>
              <td>Penjamin</td>
              <td colspan="3"> : <?php echo $result['registrasi']->nama_perusahaan?></td>
            </tr>

            <tr>
              <td>Petugas</td>
              <td colspan="3"> : <?php echo $result['petugas']->fullname?> </td>
            </tr>
          </table>
          <br>
        </div>

        <div class="col-md-6">
          <?php if( count($result['tindakan']) > 0) :?>
            <div class="alert alert-danger">
              <strong>Perhatian !</strong><br>
              <p style="text-align:justify">Mengubah penjamin pasien, akan mengakibatkan perubahan pada tarif pelayanan pasien selanjutnya.</p>
            </div>
          <?php endif;?>
        </div>

        <div class="col-md-12">
          <p><b><i class="fa fa-user"></i> PENJAMIN PASIEN(*) </b></p>

          <!-- hidden form -->
          <input class="form-control" name="no_mr_hidden_edit_penjamin" type="hidden" value="<?php echo $result['registrasi']->no_mr?>" />
          <input class="form-control" name="no_registrasi_hidden_edit_penjamin" type="hidden" value="<?php echo $result['registrasi']->no_registrasi?>" />

          <div class="form-group">
            <label class="control-label col-md-2">Nasabah</label>
            <div class="col-md-4">
                <input id="InputKeyNasabahEditPenjamin" class="form-control" name="kelompok_nasabah" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                <input type="hidden" name="kode_kelompok_hidden_edit_penjamin" value="" id="kode_kelompok_hidden_edit_penjamin">
            </div>
          </div>

          <div class="form-group" id="field_perusahaan" style="display:none;">
            <label class="control-label col-md-2">Nama Perusahaan</label>
              <div class="col-md-4">
                  <input id="InputKeyPenjaminEdit" class="form-control" name="penjamin" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                  <input type="hidden" name="kode_perusahaan_hidden_edit_penjamin" value="" id="kode_perusahaan_hidden_edit_penjamin">
              </div>
          </div>

          <div class="form-group" id="form_sep_edit_penjamin" style="display:none">
            <label class="control-label col-md-2">Nomor SEP</label>   
             <div class="col-md-4">     
               <div class="input-group">
                 <input name="noSepEditPenjamin" id="noSepEditPenjamin" class="form-control" type="text" placeholder="Masukan No SEP">
               </div>
             </div>   
          </div>

          <div class="form-group">
            <label class="col-md-2">&nbsp;</label>   
            <div class="col-md-4">  
              <button type="submit" name="submit" class="btn btn-xs btn-primary">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
            </div>
          </div>

        </div>

        <div class="col-md-12">
          <br><br>
          <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active">
                <a data-toggle="tab" href="#kunjungan">
                  <i class="green ace-icon fa fa-home bigger-120"></i>
                  Kunjungan
                </a>
              </li>

              <li>
                <a data-toggle="tab" href="#item_transaksi">
                  Item Transaksi
                  <span class="badge badge-danger"><?php echo count($result['tindakan'])?></span>
                </a>
              </li>

            </ul>

            <div class="tab-content">

              <div id="kunjungan" class="tab-pane fade in active">
                <p><b><i class="fa fa-circle-o"></i> KUNJUNGAN PASIEN </b></p>

                <table class="table table-bordered table-hover">

                    <thead>

                      <th style="color:black">No Kunjungan</th>

                      <th style="color:black">Jam Masuk Poli</th>

                      <th style="color:black">Poli Asal</th>

                      <th style="color:black">Poli Tujuan</th>

                      <th style="color:black">Diagnosa Awal</th>

                      <th style="color:black">Anamnesa</th>

                      <th style="color:black">Tindakan/Pemeriksaan</th>

                      <th style="color:black">Diagnosa Akhir</th>

                    </thead>

                    <tbody>

                    <?php foreach($result['riwayat_medis'] as $row_rm) : if($row_rm->no_kunjungan==$no_kunjungan) :?>
                      <tr>
                        <td><?php echo $row_rm->no_kunjungan?></td>
                        <td><?php echo $this->tanggal->formatDateTime($row_rm->tgl_masuk)?></td>
                        <td><?php echo $row_rm->poli_asal_kunjungan?></td>
                        <td><?php echo $row_rm->poli_tujuan_kunjungan?></td>
                        <td><?php echo ucfirst($row_rm->diagnosa_awal)?></td>
                        <td><?php echo ucfirst($row_rm->anamnesa)?></td>
                        <td><?php echo ucfirst($row_rm->pemeriksaan)?></td>
                        <td><?php echo ucfirst($row_rm->diagnosa_akhir)?></td>
                      </tr>
                    <?php endif; endforeach;?>

                    </tbody>

                </table>
              </div>

              <div id="item_transaksi" class="tab-pane fade">

                <p><b><i class="fa fa-circle-o"></i> RIWAYAT TRANSAKSI PASIEN </b></p>

                <table class="table table-bordered table-hover">

                    <thead>

                      <th style="color:black">Kode</th>

                      <th style="color:black">Tanggal</th>

                      <th style="color:black">Dokter</th>

                      <th style="color:black">Deskripsi Item</th>

                      <th style="color:black">Jenis</th>

                      <th style="color:black">Penjamin</th>

                      <th style="color:black">Status Pembayaran</th>

                    </thead>

                    <tbody>

                    <?php foreach($result['tindakan'] as $row_t) : if($row_t->no_kunjungan==$no_kunjungan) :?>
                      <tr>
                        <td><?php echo $row_t->kode_trans_pelayanan?></td>
                        <td><?php echo $this->tanggal->formatDate($row_t->tgl_transaksi)?></td>
                        <td><?php echo $row_t->nama_pegawai?></td>
                        <td><?php echo $row_t->nama_tindakan?></td>
                        <td><?php echo $row_t->jenis_tindakan?></td>
                        <td><?php echo isset($row_t->nama_perusahaan)?$row_t->nama_perusahaan:'Umum'?></td>
                        <td align="center"><?php echo ($row_t->kode_tc_trans_kasir>0)?'<label class="label label-success">Lunas</label>':'<label class="label label-danger">Belum Dibayar</label>'?></td>
                      </tr>
                    <?php endif; endforeach;?>

                    </tbody>

                </table>
              </div>

            </div>

          </div>
        </div>

      </div>

      </div>
    </div>
  </div>

  </form>
  
</div>



<!-- end form create SEP