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

          <form class="form-horizontal" method="post" id="form-default" action="<?php echo site_url('master_data/C_bagian/process')?>" enctype="multipart/form-data">
              <br>

              <!-- ID Field -->
              <div class="form-group">
                <label class="control-label col-md-2">ID</label>
                <div class="col-md-1">
                    <input name="id" id="id" value="<?php echo isset($value) && $value->id_mt_bagian ? $value->id_mt_bagian : ''; ?>" placeholder="Auto" class="form-control" type="text" readonly>
                </div>
              </div>

              <!-- Nama Bagian -->
              <div class="form-group">
                <label class="control-label col-md-2">Nama Bagian <span style="color:red;">*</span></label>
                <div class="col-md-4">
                    <input name="nama_bagian" id="nama_bagian" value="<?php echo isset($value)?$value->nama_bagian:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                </div>
              </div>

              <!-- Nama Singkat -->
              <div class="form-group">
                <label class="control-label col-md-2">Nama Singkat</label>
                <div class="col-md-3">
                    <input name="short_name" id="short_name" value="<?php echo isset($value)?$value->short_name:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                </div>
              </div>

              <!-- Group Bag -->
              <div class="form-group">
                <label class="control-label col-md-2">Nama Group <span style="color:red;">*</span></label>
                <div class="col-md-4">
                  <label class="radio-inline">
                    <input name="group_bag" id="group_bag_group" type="radio" class="ace" value="Group" <?php echo (isset($value) && $value->group_bag == 'Group') ? 'checked="checked"' : (isset($value) ? '' : 'checked="checked"'); ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Group</span>
                  </label>
                  <label class="radio-inline">
                    <input name="group_bag" id="group_bag_detail" type="radio" class="ace" value="Detail" <?php echo isset($value) && $value->group_bag == 'Detail' ? 'checked="checked"' : ''; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Detail</span>
                  </label>
                </div>
              </div>

              <!-- Pilih Bagian Dropdown (visible only when Detail selected) -->
              <div class="form-group" id="pilih_bagian_group" style="display:none;">
                <label class="control-label col-md-2">Pilih Group Bagian</label>
                <div class="col-md-4">
                  <select name="pilih_bagian_selected" id="pilih_bagian" class="form-control" <?php echo ($flag=='read')?'disabled':''?>>
                    <option value="">-- Pilih Group Bagian --</option>
                  </select>
                  <small style="color:#999;">Pilih bagian parent untuk auto-fill field di bawah</small>
                </div>
              </div>

              <!-- Kode Bagian -->
              <div class="form-group" id="kode_bagian_group">
                <label class="control-label col-md-2">Kode Bagian</label>
                <div class="col-md-3">
                  <input name="kode_bagian" id="kode_bagian" value="<?php echo isset($value)?$value->kode_bagian:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?>>
                  <small id="kode_bagian_hint" style="color:#999;">Manual input (Group)</small>
                </div>
              </div>

              <!-- Validasi (auto-filled, readonly, Detail only) -->
              <div class="form-group" id="validasi_group" style="display:none;">
                <label class="control-label col-md-2">Validasi</label>
                <div class="col-md-3">
                  <input name="validasi" id="validasi" value="<?php echo isset($value)?$value->validasi:''?>" placeholder="" class="form-control" type="text" readonly>
                  <small style="color:#999;">Auto-fill dari Kode Bagian (4 digit pertama)</small>
                </div>
              </div>

              <!-- Depo? -->
              <div class="form-group">
                <label class="control-label col-md-2">Depo?</label>
                <div class="col-md-4">
                  <label class="radio-inline">
                    <input name="is_depo" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->is_depo == 'Y' ? 'checked="checked"' : '') : ''; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Ya</span>
                  </label>
                  <label class="radio-inline">
                    <input name="is_depo" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->is_depo == 'N' ? 'checked="checked"' : 'checked="checked"') : 'checked="checked"'; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Tidak</span>
                  </label>
                </div>
              </div>

              <!-- Depo Group (Detail only) -->
              <div class="form-group" id="depo_group_group" style="display:none;">
                <label class="control-label col-md-2">Depo Group</label>
                <div class="col-md-4">
                  <select name="depo_group" id="depo_group" class="form-control" <?php echo ($flag=='read')?'disabled':''?>>
                    <option value="">-- Pilih Depo Group --</option>
                  </select>
                  <small style="color:#999;">Pilih Group yang menjadi parent dari Detail ini</small>
                </div>
              </div>

              <!-- Kamar Observasi? -->
              <div class="form-group">
                <label class="control-label col-md-2">Kamar Observasi?</label>
                <div class="col-md-4">
                  <label class="radio-inline">
                    <input name="has_observe_room" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->has_observe_room == 'Y' ? 'checked="checked"' : '') : ''; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Ya</span>
                  </label>
                  <label class="radio-inline">
                    <input name="has_observe_room" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->has_observe_room == 'N' ? 'checked="checked"' : 'checked="checked"') : 'checked="checked"'; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Tidak</span>
                  </label>
                </div>
              </div>

              <!-- Pelayanan / Backoffice -->
              <div class="form-group">
                <label class="control-label col-md-2">Pelayanan?</label>
                <div class="col-md-4">
                  <label class="radio-inline">
                    <input name="pelayanan" type="radio" class="ace" value="1" <?php echo isset($value) ? ($value->pelayanan == '1' ? 'checked="checked"' : '') : 'checked="checked"'; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Ya (Pelayanan)</span>
                  </label>
                  <label class="radio-inline">
                    <input name="pelayanan" type="radio" class="ace" value="0" <?php echo isset($value) ? ($value->pelayanan == '0' ? 'checked="checked"' : '') : ''; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Tidak (Back Office)</span>
                  </label>
                </div>
              </div>

              <!-- Status Aktif (is_active Y/N) -->
              <div class="form-group">
                <label class="control-label col-md-2">Status Aktif</label>
                <div class="col-md-4">
                  <label class="radio-inline">
                    <input name="is_active" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->is_active == 'Y' ? 'checked="checked"' : '') : 'checked="checked"'; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Aktif</span>
                  </label>
                  <label class="radio-inline">
                    <input name="is_active" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->is_active == 'N' ? 'checked="checked"' : '') : ''; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Tidak Aktif</span>
                  </label>
                </div>
              </div>

              <hr>
              <p class="text-muted" style="margin-left:17%"><small>Data tambahan (opsional)</small></p>

              <!-- Kode Poli BPJS -->
              <div class="form-group">
                <label class="control-label col-md-2">Kode Poli BPJS</label>
                <div class="col-md-3">
                    <input name="kode_poli_bpjs" id="kode_poli_bpjs" value="<?php echo isset($value)?$value->kode_poli_bpjs:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                </div>
              </div>

              <!-- Is Public -->
              <div class="form-group">
                <label class="control-label col-md-2">Is Public</label>
                <div class="col-md-4">
                  <label class="radio-inline">
                    <input name="is_public" type="radio" class="ace" value="1" <?php echo isset($value) ? ($value->is_public == '1' ? 'checked="checked"' : '') : 'checked="checked"'; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Ya</span>
                  </label>
                  <label class="radio-inline">
                    <input name="is_public" type="radio" class="ace" value="0" <?php echo isset($value) ? ($value->is_public == '0' ? 'checked="checked"' : '') : ''; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Tidak</span>
                  </label>
                </div>
              </div>

              <!-- Status Aktif numerik (legacy) -->
              <div class="form-group">
                <label class="control-label col-md-2">Status Aktif (int)</label>
                <div class="col-md-4">
                  <label class="radio-inline">
                    <input name="status_aktif" type="radio" class="ace" value="1" <?php echo isset($value) ? ($value->status_aktif == '1' ? 'checked="checked"' : '') : 'checked="checked"'; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> 1 (Aktif)</span>
                  </label>
                  <label class="radio-inline">
                    <input name="status_aktif" type="radio" class="ace" value="0" <?php echo isset($value) ? ($value->status_aktif == '0' ? 'checked="checked"' : '') : ''; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> 0 (Tidak)</span>
                  </label>
                </div>
              </div>

              <!-- ID Satu Sehat -->
              <div class="form-group">
                <label class="control-label col-md-2">ID Satu Sehat</label>
                <div class="col-md-3">
                    <input name="id_satu_sehat" id="id_satu_sehat" value="<?php echo isset($value)?$value->id_satu_sehat:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                </div>
              </div>

              <!-- Location ID -->
              <div class="form-group">
                <label class="control-label col-md-2">Location ID</label>
                <div class="col-md-3">
                    <input name="location_id" id="location_id" value="<?php echo isset($value)?$value->location_id:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                </div>
              </div>

              <!-- Audit fields (hidden) -->
              <input type="hidden" name="created_date" value="<?php echo isset($value)?$value->created_date:''?>" />
              <input type="hidden" name="created_by"   value="<?php echo isset($value)?$value->created_by:''?>" />
              <input type="hidden" name="updated_date" value="<?php echo isset($value)?$value->updated_date:''?>" />
              <input type="hidden" name="updated_by"   value="<?php echo isset($value)?$value->updated_by:''?>" />

              <div class="form-actions center">
                <a onclick="getMenu('master_data/C_bagian')" href="#" class="btn btn-sm btn-success">
                    <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                    Kembali ke daftar
                </a>
                <?php if($flag != 'read'):?>
                <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                    <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                    Reset
                </button>
                <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Submit
                </button>
                <?php endif; ?>
              </div>

          </form>

        </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>
<script src="<?php echo base_url()?>assets/js/bootbox.js"></script>

<script type="text/javascript">
    jQuery(function($) {

      // ===== LOAD PILIH BAGIAN DROPDOWN =====
      function loadPilihBagianDropdown() {
        $.ajax({
          url: '<?php echo site_url('master_data/C_bagian/get_bagian_dropdown'); ?>',
          type: 'GET', dataType: 'JSON',
          success: function(data) {
            var options = '<option value="">-- Pilih Group Bagian --</option>';
            $.each(data, function(i, item) {
              options += '<option value="' + item.id + '" data-kode="' + item.kode_bagian + '">' + item.kode_bagian + ' - ' + item.nama_bagian + '</option>';
            });
            $('#pilih_bagian').html(options);
          }
        });
      }

      // ===== LOAD DEPO GROUP DROPDOWN =====
      function loadDepoGroupDropdown(selectedVal) {
        $.ajax({
          url: '<?php echo site_url('master_data/C_bagian/get_bagian_dropdown'); ?>',
          type: 'GET', dataType: 'JSON',
          success: function(data) {
            var options = '<option value="">-- Pilih Depo Group --</option>';
            $.each(data, function(i, item) {
              var sel = (selectedVal && item.kode_bagian == selectedVal) ? 'selected' : '';
              options += '<option value="' + item.kode_bagian + '" ' + sel + '>' + item.kode_bagian + ' - ' + item.nama_bagian + '</option>';
            });
            $('#depo_group').html(options);
          }
        });
      }

      // ===== AUTO-CALCULATE KODE GROUP =====
      function loadNextKodeGroup() {
        $.ajax({
          url: '<?php echo site_url('master_data/C_bagian/get_next_kode_group'); ?>',
          type: 'GET', dataType: 'JSON',
          success: function(data) {
            $('#kode_bagian').val(data.next_kode);
            $('#kode_bagian_hint').text('Auto-fill dari kode Group tertinggi');
          }
        });
      }

      // ===== AUTO-CALCULATE KODE BAGIAN =====
      function loadNextKodeBagian(selected_kode) {
        $.ajax({
          url: '<?php echo site_url('master_data/C_bagian/get_next_kode_bagian_by_validasi'); ?>',
          type: 'GET', data: { kode_bagian: selected_kode }, dataType: 'JSON',
          success: function(data) {
            $('#kode_bagian').val(data.next_kode);
            $('#validasi').val(data.validasi || selected_kode.substring(0, 4));
          }
        });
      }

      // ===== SHOW / HIDE based on Group / Detail =====
      function applyGroupMode(mode) {
        if (mode === 'Detail') {
          $('#pilih_bagian_group').show();
          $('#kode_bagian_group').show();
          $('#validasi_group').show();
          $('#depo_group_group').show();
          $('#kode_bagian').prop('readonly', true);
          $('#kode_bagian_hint').text('Auto-fill dari Pilih Bagian (MAX + 1)');
        } else {
          $('#pilih_bagian_group').hide();
          $('#kode_bagian_group').show();
          $('#validasi_group').hide();
          $('#depo_group_group').hide();
          $('#kode_bagian').prop('readonly', false);
          $('#kode_bagian_hint').text('Manual input (Group)');
          $('#pilih_bagian').val('');
          $('#validasi').val('');
          $('#depo_group').val('');
        }
      }

      // ===== GROUP BAG RADIO CHANGE =====
      $('input[name="group_bag"]').on('change', function() {
        var mode = $(this).val();
        applyGroupMode(mode);
        if (mode === 'Detail') {
          loadPilihBagianDropdown();
          loadDepoGroupDropdown('');
          $('#kode_bagian').val('');
        } else if (mode === 'Group') {
          if (flagMode === 'create') {
            loadNextKodeGroup();
          }
        }
      });

      // ===== PILIH BAGIAN CHANGE =====
      $('#pilih_bagian').on('change', function() {
        var kode = $(this).find('option:selected').data('kode');
        if (kode) loadNextKodeBagian(kode);
      });

      // ===== FORM SUBMIT =====
      $('#form-default').on('submit', function() {
        var formData = new FormData($('#form-default')[0]);
        $.ajax({
          url: $('#form-default').attr('action'),
          type: 'POST', data: formData, dataType: 'JSON',
          contentType: false, processData: false,
          beforeSend: function() { achtungShowLoader(); },
          complete: function(xhr) {
            achtungHideLoader();
            try {
              var res = JSON.parse(xhr.responseText);
              $.achtung({ message: res.message, timeout: 5 });
              if (res.status === 200) {
                $('#page-area-content').load('master_data/C_bagian');
              }
            } catch(e) {}
          }
        });
        return false;
      });

      // ===== INITIALIZE =====
      var flagMode  = '<?php echo $flag; ?>';
      var initMode  = $('input[name="group_bag"]:checked').val();
      applyGroupMode(initMode);

      if (initMode === 'Detail') {
        var savedDepoGroup  = '<?php echo isset($value) ? addslashes($value->depo_group) : '' ?>';
        loadPilihBagianDropdown();
        loadDepoGroupDropdown(savedDepoGroup);

        var savedKode = '<?php echo isset($value) ? addslashes($value->kode_bagian) : '' ?>';
        if (savedKode) {
          $('#kode_bagian').prop('readonly', true);
          $('#kode_bagian_hint').text('Readonly (mode update)');
        }
      } else if (initMode === 'Group' && flagMode === 'create') {
        loadNextKodeGroup();
      }

    });
</script>
