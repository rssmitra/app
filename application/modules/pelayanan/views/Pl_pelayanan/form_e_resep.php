<div class="row" id="form_input_resep" <?php echo ($checked_resep == '')?'style="display: none"':''; ?>>
    <div class="col-md-12" style="margin-top: 6px">

        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                    <a data-toggle="tab" href="#resep_non_racikan_tab">
                        <i class="green ace-icon fa fa-home bigger-120"></i>
                        Non Racikan
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#resep_racikan_tab">
                    <i class="green ace-icon fa fa-flask bigger-120"></i>
                    Racikan
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#template">
                    <i class="green ace-icon fa fa-list bigger-120"></i>
                    Template Resep
                    </a>
                </li>
            </ul>

            <div class="tab-content">                
                <input type="hidden" name="id_pesan_resep_detail" id="id_pesan_resep_detail" class="form-control">
                <div id="resep_non_racikan_tab" class="tab-pane fade in active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <p style="font-weight: bold">Obat Non Racikan</p>
                            </div>
                            <div class="pull-right">
                                <a href="#" class="btn btn-sm btn-danger" onclick="reset_form_resep()"><i class="fa fa-refresh"></i> Reset Form</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2">Cari Obat</label>            
                        <div class="col-md-8">            
                        <input type="text" name="obat" id="inputKeyObat" class="form-control" placeholder="Masukan Keyword Obat" value="">
                        <input type="hidden" name="kode_brg" id="kode_brg_obat" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Dosis</label>
                        <div class="col-md-5">
                            <span class="input-icon">
                                <input name="jml_dosis" id="jml_dosis" type="text" style="width: 50px;text-align: center" value="1" onchange="countJmlObat()"/>
                            </span>

                            <span class="input-icon" style="padding-left: 4px">
                                <i class="fa fa-times bigger-150"></i>
                            </span>

                            <span class="input-icon">
                            <input name="jml_dosis_obat" id="jml_dosis_obat" type="text" style="width: 50px; text-align: center" value="1" onchange="countJmlObat()"/>
                            </span>
                        
                        </div>
                    </div>  
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2">Satuan</label>

                        <div class="col-md-4">
                            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'satuan_obat')), 'Tab' , 'satuan_obat', 'satuan_obat', 'form-control', '', '');?>
                        </div>
                        <label class="control-label col-sm-2">Waktu</label>
                        <div class="col-md-4">
                            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anjuran_pakai_obat')), 'Sesudah Makan' , 'aturan_pakai', 'aturan_pakai', 'form-control', '', '');?>
                        </div>
                    </div>  
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2">Jml Hari</label>
                        <div class="col-md-2">
                            <input class="form-control" name="jml_hari" id="jml_hari" type="text" style="text-align:center;" value="<?php echo ($value->kode_perusahaan == 120) ? 30 : "" ?>" onchange="countJmlObat()"/>
                        </div>
                        <label class="control-label col-sm-2">Jml Obat</label>
                        <div class="col-md-2">
                            <input class="form-control" name="jml_pesan" id="jml_pesan" type="text" style="text-align:center;" placeholder="(Auto)"/>
                        </div>
                        <!-- <div class="col-md-4" style="margin-top: 4px; margin-left: -20px">
                            <label>
                                <input name="is_racikan" type="checkbox" value="1" class="ace">
                                <span class="lbl"> Racikan </span>
                            </label>
                        </div> -->
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Keterangan</label>
                        <div class="col-md-10">
                            <input class="form-control" name="keterangan_resep" id="keterangan_resep" type="text"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 no-padding">
                            <div class="pull-left">
                                <a href="#" class="btn btn-xs btn-primary" id="btn_add_resep_obat" onclick="add_resep_obat()">Tambahkan Obat</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="resep_racikan_tab" class="tab-pane fade">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <p style="font-weight: bold">Obat Racikan</p>
                            </div>
                            <div class="pull-right">
                                <a href="#" class="btn btn-sm btn-danger" onclick="reset_form_racikan()"><i class="fa fa-refresh"></i> Reset Form Racikan</a>
                            </div>
                        </div>
                    </div>


                    <!-- form racikan header -->
                    <div id="data_racikan_div">

                        <div class="form-group">
                            <label class="control-label col-sm-2">Nama Racikan</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="nama_racikan" id="nama_racikan" value="">  
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Jumlah Obat</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="jml_racikan" id="jml_racikan" style="text-align: center;">  
                            </div>
                            <label class="control-label col-sm-2">Satuan</label>
                            <div class="col-md-4">
                                <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'satuan_obat')), 'Bks' , 'satuan_racikan', 'satuan_racikan', 'form-control', '', '');?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">Dosis</label>
                            <div class="col-md-4">
                            <span class="inline">
                                <input name="dosis_start_r" id="dosis_start_r" type="text" style="width: 50px; text-align: center"/>
                            </span>
                            <span class="inline" style="padding-left: 4px;">
                                <i class="fa fa-times bigger-150"></i>
                            </span>
                            <span class="inline">
                                <input name="dosis_end_r" id="dosis_end_r" type="text" style="width: 50px; text-align: center"/>
                            </span>
                            
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">Penggunaan</label>
                            <div class="col-md-4">
                            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anjuran_pakai_obat')), 'Sesudah Makan' , 'anjuran_pakai_r', 'anjuran_pakai_r', 'form-control', '', '');?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-sm-2">Catatan</label>
                            <div class="col-md-1">
                                <input class="form-control" name="catatan_r" id="catatan_r" type="text" style="width: 400px" value=""/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 no-padding">
                            <button type="submit" id="btn_submit_racikan" name="submit" value="header" class="btn btn-xs btn-primary">
                                <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                Simpan Racikan
                            </button>
                            <button type="submit" id="btn_update_header_racikan" style="display:none" name="submit" value="header" class="btn btn-xs btn-success">
                                <i class="ace-icon fa fa-edit icon-on-right bigger-110"></i>
                                Update Resep Racikan
                            </button>
                            </div>
                        </div> 
                        
                        <hr>

                    </div>

                    <!-- form obat -->
                    <div id="data_obat_div" style="display: none">
                        <!-- Data Obat -->
                        <p><b>Komposisi Obat Racikan </b></p>

                        <!-- cari obat -->
                        <div class="form-group">
                            <label class="control-label col-sm-2">Cari Obat</label>  
                            <div class="col-md-8">   
                            <input type="text" name="obat" id="inputKeyObatRacikan" class="form-control" placeholder="Masukan Keyword Obat" value=""> 
                            <input type="hidden" name="obat" id="inputKeyObatRacikanHidden" class="form-control" placeholder="Masukan Keyword Obat" value=""> 
                            </div>
                        </div>

                        <!-- jumlah -->
                        <div class="form-group">
                            <label class="control-label col-sm-2">Jumlah Obat</label>
                            <div class="col-md-2">
                            <input type="text" class="form-control" name="jml_komposisi_obat" id="jml_komposisi_obat" style="text-align: center;">  
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-10 no-padding">
                                <button type="submit" id="add_komposisi_obat"  value="" name="submit" class="btn btn-xs btn-primary">
                                <i class="ace-icon fa fa-plus icon-on-right bigger-110"></i>
                                Tambahkan Obat
                            </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div id="template" class="tab-pane fade">
                    <p>
                        --
                    </p>
                </div>

            </div>
        </div>

        <hr>
        <span style="font-weight: bold; font-style: italic">RESEP DOKTER</span>
        <table class="table" id="dt_add_resep_obat">
            <thead>
            <tr>
                <th>Item Obat</th>
                <th style="width: 50px !important"></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="center">
            <a href="#" class="btn btn-xs btn-primary" onclick="save_template()"><i class="fa fa-save"></i> Simpan Sebagai Template Resep</a>
        </div>
        <br>
        <!-- <textarea name="pl_resep_farmasi" id="pl_resep_farmasi" class="form-control" style="height: 100px !important" placeholder="Keterangan lainnya"><?php echo isset($riwayat->resep_farmasi)?$this->master->br2nl($riwayat->resep_farmasi):''?></textarea> -->

    </div>
</div>
<hr>