<script type="text/javascript">

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

    $('select[name="cara_keluar"]').change(function () {      

        if ($(this).val() == 'Rujuk ke Poli Lain') {        
            $('#poli_dirujuk').show('fast');
            $('#pasca_pulang').val('');
            $('#form_meninggal').hide('fast');
        }else if ($(this).val() == 'Meninggal'){
            $('#pasca_pulang').val('Meninggal');
            $('#poli_dirujuk').hide('fast');
            $('#form_meninggal').show('fast');
        }else{
            $('#poli_dirujuk').hide('fast');
            $('#pasca_pulang').val('');
            $('#form_meninggal').hide('fast');
        }
    }); 

    $('#InputDokterPasienMeninggal').typeahead({
        source: function (query, result) {
                $.ajax({
                    url: "templates/references/getAllDokter",
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
            $('#dokter_pasien_meninggal').val(val_item);
            
        }
    });

    
</script>
<div class="row" style="padding:8px">
    <div class="col-sm-12">
        <p><b><i class="fa fa-edit"></i> SELESAIKAN KUNJUNGAN PASIEN </b></p>

        <table class="table table-bordered">
            <tr style="background-color:#22e231">
                <th>No Kunjungan</th>
                <th>Bagian</th>
                <th>Nama Dokter</th>
                <th>Status</th>
            </tr>

            <?php foreach($kunjungan as $val){
                //$status = ($val->status_keluar==3)?'<div class="center"><i class="fa fa-check-circle green"></i></div>':'<div class="center"><i class="fa fa-times-circle red"></i></div>';
                $bagian=substr($val->kode_bagian_tujuan,0,2);
				
				if($bagian=="01"){
					if(($val->status_daftar_poli=="")&&($val->status_isihasil_poli=="")){
						$status = '<div class="center"><i class="fa fa-times-circle red"></i></div>';
					}else if(($val->status_daftar_poli=="1")&&($val->status_isihasil_poli=="")){
						if($kode_bagian_tujuan!='010901'){
							$status = '<div class="center"><i class="fa fa-check-circle green"></i></div>';
						}else{
							$status = '<div class="center"><i class="fa fa-times-circle red"></i></div>';
						}
					}else if(($val->status_daftar_poli=="2")&&($val->status_isihasil_poli=="1")){
						$status = '<div class="center"><i class="fa fa-check-circle green"></i></div>';
					}else if(($val->status_daftar_poli=="2")&&($val->status_isihasil_poli=="")){
						$status = '<div class="center"><i class="fa fa-times-circle red"></i></div>';
					}

				}else{

					if(($val->status_daftar_pm=="")&&($val->status_isihasil_pm=="")){

						$status = '<div class="center"><i class="fa fa-times-circle red"></i></div>';

					}else if(($val->status_daftar_pm=="1")&&($val->status_isihasil_pm=="")){

                        $status = '<div class="center"><i class="fa fa-times-circle red"></i></div>';
                        
					}else if(($val->status_daftar_pm=="2")&&($val->status_isihasil_pm=="")){

						$status = '<div class="center"><i class="fa fa-times-circle red"></i></div>';

					}else if(($val->status_daftar_pm=="2")&&($val->status_isihasil_pm=="1")){

						$status = '<div class="center"><i class="fa fa-check-circle green"></i></div>';

					}

				}
                if($val->kode_bagian_tujuan!='010901'){
                    echo '
                        <tr>
                            <td>'.$val->no_kunjungan.'</td>
                            <td>'.$val->nama_bagian.'</td>
                            <td>'.$val->nama_pegawai.'</td>
                            <td>'.$status.'</td>
                        </tr>
                    ';
                }
            } ?>

        </table><br>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Cara Keluar Pasien</label>
            <div class="col-sm-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'cara_keluar')), 'Atas Persetujuan Dokter' , 'cara_keluar', 'cara_keluar', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="form-group" id="poli_dirujuk" style="display:none">
            <label class="control-label col-sm-2" for="">Rujuk ke Poli-</label>
            <div class="col-sm-5">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1)), '' , 'rujukan_tujuan', 'rujukan_tujuan', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Pasca Pulang</label>
            <div class="col-sm-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'pasca_pulang')), 'Dalam Masa Pengobatan' , 'pasca_pulang', 'pasca_pulang', 'form-control', '', '') ?>
            </div>
        </div>

        <div id="form_meninggal" style="display:none;background-color:#FFF !important;padding:5px;margin-bottom:5px;">
            <span class="center">
                <h3>SURAT PERNYATAAN PASIEN MENINGGAL </h3>
            </span>

            <p> Pada hari ini, tanggal <?php echo $this->tanggal->formatDate(date('Y-m-d')).', pukul '. date('H:i:s') ?>, kami :</p>
       
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Nama Dokter</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="InputDokterPasienMeninggal"name="nama_dokter_pasien_meninggal" placeholder="Masukan Keyword Nama Dokter" value="<?php echo isset($riwayat->nama_pegawai)?$riwayat->nama_pegawai:''?>">
                    <input type="hidden" class="form-control" id="dokter_pasien_meninggal" name="dokter_pasien_meninggal" value="<?php echo isset($riwayat->kode_dokter)?$riwayat->kode_dokter:''?>">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Bagian</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="bagian" id="bagian" value="<?php echo isset($riwayat->nama_bagian)?$riwayat->nama_bagian:''?>">
                    <input type="hidden" class="form-control" id="kode_bagian_pasien_meninggal" name="kode_bagian_pasien_meninggal" value="<?php echo isset($riwayat->kode_bagian_tujuan)?$riwayat->kode_bagian_tujuan:''?>">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Rumah Sakit</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="rs" id="rs" value="<?php echo COMP_FULL; ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Alamat</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="alamat_rs" id="alamat_rs" value="<?php echo COMP_ADDRESS_SORT; ?>">
                </div>
            </div>

            <p> Menyatakan bahwa pasien tersebut diatas <b>telah meninggal</b> pada : </p>

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Hari</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="hari_meninggal" id="hari_meninggal" value="<?php echo $this->tanggal->getHari(date('D')) ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Tanggal</label>
                <div class="col-md-3">
        
                    <div class="input-group">
                        
                        <input name="tgl_meninggal" id="tgl_meninggal" value="<?php echo date('m/d/Y') ?>" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text">
                        <span class="input-group-addon">
                        
                        <i class="ace-icon fa fa-calendar"></i>
                        
                        </span>
                    </div>
                
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Jam</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="jam_meninggal" id="jam_meninggal" value="<?php echo date('H:i:s') ?>">
                </div>
            </div>

            <div class="form-group">
                      
                <label class="control-label col-sm-2">Instruksi</label>
                
                <div class="col-md-3">
                
                <textarea name="instruksi_meninggal" id="instruksi_meninggal" cols="50" style="height:100px !important;"></textarea>
                
                </div>
            
            
            </div>

        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-4" style="margin-left:6px">
               <button type="button" class="btn btn-xs btn-danger" id="btn_hide_" onclick="backToDefaultForm()"> <i class="fa fa-angle-double-left"></i> Sembunyikan </button>
               <button type="submit" class="btn btn-xs btn-primary" id="btn_submit_selesai"> <i class="fa fa-save"></i> Submit </button>
            </div>
        </div>

    </div>

</div>




