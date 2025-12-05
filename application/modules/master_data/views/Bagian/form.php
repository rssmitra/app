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

              <!-- Nama Bagian Field -->
              <div class="form-group">
                <label class="control-label col-md-2">Nama Bagian <span style="color:red;">*</span></label>
                <div class="col-md-4">
                    <input name="nama_bagian" id="nama_bagian" value="<?php echo isset($value)?$value->nama_bagian:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                </div>
              </div>

              <!-- Group Bag Radio Buttons (inline) -->
              <div class="form-group">
                <label class="control-label col-md-2">Group Bag <span style="color:red;">*</span></label>
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

              <!-- Kode Bagian Field -->
              <div class="form-group" id="kode_bagian_group">
                <label class="control-label col-md-2">Kode Bagian</label>
                <div class="col-md-4">
                  <input name="kode_bagian" id="kode_bagian" value="<?php echo isset($value)?$value->kode_bagian:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?>>
                  <small id="kode_bagian_hint" style="color:#999;">Manual input (Group)</small>
                </div>
              </div>

              <!-- Validasi Field (auto-filled, readonly) -->
              <div class="form-group" id="validasi_group" style="display:none;">
                <label class="control-label col-md-2">Validasi</label>
                <div class="col-md-4">
                  <input name="validasi" id="validasi" value="<?php echo isset($value)?$value->validasi:''?>" placeholder="" class="form-control" type="text" readonly>
                  <small style="color:#999;">Auto-fill dari Kode Bagian (4 digit pertama)</small>
                </div>
              </div>

              <!-- Depo Group Field (dropdown select) -->
              <div class="form-group" id="depo_group_group" style="display:none;">
                <label class="control-label col-md-2">Depo Group</label>
                <div class="col-md-4">
                  <select name="depo_group" id="depo_group" class="form-control" <?php echo ($flag=='read')?'disabled':''?>>
                    <option value="">-- Pilih Depo Group --</option>
                  </select>
                  <small style="color:#999;">Pilih Group yang menjadi parent dari Detail ini</small>
                </div>
              </div>

              <!-- Pelayanan Field (radio inline) -->
              <div class="form-group">
                <label class="control-label col-md-2">Pelayanan</label>
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

              <!-- Status Aktif (0/1) - inline -->
              <div class="form-group">
                <label class="control-label col-md-2">Status Aktif</label>
                <div class="col-md-4">
                  <label class="radio-inline">
                    <input name="status_aktif" type="radio" class="ace" value="1" <?php echo isset($value) ? ($value->status_aktif == '1' ? 'checked="checked"' : '') : 'checked="checked"'; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> 1</span>
                  </label>
                  <label class="radio-inline">
                    <input name="status_aktif" type="radio" class="ace" value="0" <?php echo isset($value) ? ($value->status_aktif == '0' ? 'checked="checked"' : '') : ''; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> 0</span>
                  </label>
                </div>
              </div>

              <!-- Kode Poli BPJS -->
              <div class="form-group" id="kode_poli_bpjs_group">
                <label class="control-label col-md-2">Kode Poli BPJS</label>
                <div class="col-md-3">
                    <input name="kode_poli_bpjs" id="kode_poli_bpjs" value="<?php echo isset($value)?$value->kode_poli_bpjs:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                </div>
              </div>

              <!-- Has Observe Room (Y/N) - inline -->
              <div class="form-group" id="has_observe_room_group">
                <label class="control-label col-md-2">Has Observe Room</label>
                <div class="col-md-4">
                  <label class="radio-inline">
                    <input name="has_observe_room" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->has_observe_room == 'Y' ? 'checked="checked"' : '') : 'checked="checked"'; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Ya</span>
                  </label>
                  <label class="radio-inline">
                    <input name="has_observe_room" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->has_observe_room == 'N' ? 'checked="checked"' : '') : ''; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Tidak</span>
                  </label>
                </div>
              </div>

              <!-- Is Public (0/1) - inline -->
              <div class="form-group" id="is_public_group">
                <label class="control-label col-md-2">Is Public</label>
                <div class="col-md-4">
                  <label class="radio-inline">
                    <input name="is_public" type="radio" class="ace" value="1" <?php echo isset($value) ? ($value->is_public == '1' ? 'checked="checked"' : '') : 'checked="checked"'; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> 1</span>
                  </label>
                  <label class="radio-inline">
                    <input name="is_public" type="radio" class="ace" value="0" <?php echo isset($value) ? ($value->is_public == '0' ? 'checked="checked"' : '') : ''; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> 0</span>
                  </label>
                </div>
              </div>

              <!-- Short Name -->
              <div class="form-group" id="short_name_group">
                <label class="control-label col-md-2">Short Name</label>
                <div class="col-md-3">
                    <input name="short_name" id="short_name" value="<?php echo isset($value)?$value->short_name:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                </div>
              </div>

              <!-- ID Satu Sehat -->
              <div class="form-group" id="id_satu_sehat_group">
                <label class="control-label col-md-2">ID Satu Sehat</label>
                <div class="col-md-3">
                    <input name="id_satu_sehat" id="id_satu_sehat" value="<?php echo isset($value)?$value->id_satu_sehat:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                </div>
              </div>

              <!-- Location ID -->
              <div class="form-group" id="location_id_group">
                <label class="control-label col-md-2">Location ID</label>
                <div class="col-md-3">
                    <input name="location_id" id="location_id" value="<?php echo isset($value)?$value->location_id:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                </div>
              </div>

              <!-- Created/Updated audit (hidden) -->
              <input type="hidden" name="created_date" id="created_date" value="<?php echo isset($value)?$value->created_date:''?>" />
              <input type="hidden" name="created_by" id="created_by" value="<?php echo isset($value)?$value->created_by:''?>" />
              <input type="hidden" name="updated_date" id="updated_date" value="<?php echo isset($value)?$value->updated_date:''?>" />
              <input type="hidden" name="updated_by" id="updated_by" value="<?php echo isset($value)?$value->updated_by:''?>" />

              <!-- Is Active Radio Buttons - inline -->
              <div class="form-group">
                <label class="control-label col-md-2">Is Active?</label>
                <div class="col-md-4">
                  <label class="radio-inline">
                    <input name="is_active" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->is_active == 'Y') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Ya</span>
                  </label>
                  <label class="radio-inline">
                    <input name="is_active" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->is_active == 'N') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'disabled':''?> />
                    <span class="lbl"> Tidak</span>
                  </label>
                </div>
              </div>
              
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
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/bootstrap-tag.js"></script>
<script>

    jQuery(function($) {  

        $('.date-picker').datepicker({  
        autoclose: true,   
        todayHighlight: true,
        dateFormat: 'yyyy-mm-dd'
        })  
        //show datepicker when clicking on the icon
        .next().on(ace.click_event, function(){  
        $(this).prev().focus();    
        });  

        var tag_input = $('#form-field-tags');
        try{
        tag_input.tag(
            {
            placeholder:tag_input.attr('placeholder'),
            }
        )
        }
        catch(e) {
        tag_input.after('<textarea id="'+tag_input.attr('id')+'" name="'+tag_input.attr('name')+'" rows="3">'+tag_input.val()+'</textarea>').remove();
        }
        
    });
  
</script>

<script src="<?php echo base_url()?>/assets/js/jquery-ui.custom.js"></script>
<script src="<?php echo base_url()?>/assets/js/jquery.ui.touch-punch.js"></script>
<script src="<?php echo base_url()?>/assets/js/markdown/markdown.js"></script>
<script src="<?php echo base_url()?>/assets/js/markdown/bootstrap-markdown.js"></script>
<script src="<?php echo base_url()?>/assets/js/jquery.hotkeys.js"></script>
<script src="<?php echo base_url()?>/assets/js/bootstrap-wysiwyg.js"></script>
<script src="<?php echo base_url()?>/assets/js/bootbox.js"></script>

<script type="text/javascript">
    jQuery(function($) {
      
      var dropdown_active = false;

      // ===== LOAD PILIH BAGIAN DROPDOWN =====
      function loadPilihBagianDropdown() {
        console.log('✓ Loading Pilih Bagian Dropdown...');
        $.ajax({
          url: '<?php echo site_url('master_data/C_bagian/get_bagian_dropdown'); ?>',
          type: 'GET',
          dataType: 'JSON',
          success: function(data) {
            console.log('✓ Dropdown data received:', data);
            var options = '<option value="">-- Pilih Group Bagian --</option>';
            $.each(data, function(index, item) {
              options += '<option value="' + item.id + '" data-kode="' + item.kode_bagian + '">' + item.kode_bagian + ' - ' + item.nama_bagian + '</option>';
            });
            $('#pilih_bagian').html(options);
            console.log('✓ Dropdown populated');
          },
          error: function() {
            console.log('✗ Error loading dropdown');
            $.achtung({message: 'Error loading dropdown', timeout:5});
          }
        });
      }

      // ===== LOAD DEPO GROUP DROPDOWN =====
      function loadDepoGroupDropdown() {
        console.log('✓ Loading Depo Group Dropdown...');
        $.ajax({
          url: '<?php echo site_url('master_data/C_bagian/get_bagian_dropdown'); ?>',
          type: 'GET',
          dataType: 'JSON',
          success: function(data) {
            console.log('✓ Depo Group data received:', data);
            var options = '<option value="">-- Pilih Depo Group --</option>';
            $.each(data, function(index, item) {
              options += '<option value="' + item.kode_bagian + '">' + item.kode_bagian + ' - ' + item.nama_bagian + '</option>';
            });
            $('#depo_group').html(options);
            console.log('✓ Depo Group dropdown populated');
          },
          error: function() {
            console.log('✗ Error loading depo group dropdown');
            $.achtung({message: 'Error loading depo group dropdown', timeout:5});
          }
        });
      }

      // ===== AUTO-CALCULATE KODE BAGIAN + 1 =====
      function loadNextKodeBagian(selected_kode_bagian) {
        console.log('✓ Loading next Kode Bagian (MAX + 1)...');
        console.log('  - Selected Kode Bagian:', selected_kode_bagian);
        
        $.ajax({
          url: '<?php echo site_url('master_data/C_bagian/get_next_kode_bagian_by_validasi'); ?>',
          type: 'GET',
          data: { kode_bagian: selected_kode_bagian },
          dataType: 'JSON',
          success: function(data) {
            console.log('✓ Next Kode response:', data);
            $('#kode_bagian').val(data.next_kode);
            
            // Validasi = first 4 digits
            var validasi = data.validasi || selected_kode_bagian.substring(0, 4);
            $('#validasi').val(validasi);
            
            console.log('✓ Kode Bagian set to:', data.next_kode);
            console.log('✓ Validasi set to:', validasi);
          },
          error: function() {
            console.log('✗ Error loading next Kode');
            $.achtung({message: 'Error calculating next Kode', timeout:5});
          }
        });
      }

      // ===== GROUP BAG RADIO CHANGE EVENT =====
      $('input[name="group_bag"]').on('change', function() {
        var selected = $(this).val();
        console.log('✓ Group Bag changed to:', selected);
        
        if(selected === 'Detail') {
          console.log('✓ Detail selected - showing dropdown fields');
          dropdown_active = true;
          $('#pilih_bagian_group').show();
          $('#kode_bagian_group').show();
          $('#validasi_group').show();
          $('#depo_group_group').show();
          // Show additional fields required for Detail
          $('#kode_poli_bpjs_group').show();
          $('#has_observe_room_group').show();
          $('#is_public_group').show();
          $('#short_name_group').show();
          $('#id_satu_sehat_group').show();
          $('#location_id_group').show();
          
          // Make kode_bagian readonly when Detail
          $('#kode_bagian').prop('readonly', true);
          $('#kode_bagian_hint').text('Auto-fill dari Pilih Bagian (MAX + 1)');
          
          loadPilihBagianDropdown();
          loadDepoGroupDropdown();
        } else {
          console.log('✓ Group selected - hiding dropdown fields');
          dropdown_active = false;
          $('#pilih_bagian_group').hide();
          $('#kode_bagian_group').show();
          $('#validasi_group').hide();
          $('#depo_group_group').hide();
          // Hide additional fields not needed for Group
          $('#kode_poli_bpjs_group').hide();
          $('#has_observe_room_group').hide();
          $('#is_public_group').hide();
          $('#short_name_group').hide();
          $('#id_satu_sehat_group').hide();
          $('#location_id_group').hide();
          
          // Make kode_bagian editable when Group
          $('#kode_bagian').prop('readonly', false);
          $('#kode_bagian_hint').text('Manual input (Group)');
          
          // Clear dropdown related fields
          $('#pilih_bagian').val('');
          $('#validasi').val('');
          $('#depo_group').val('');
        }
      });

      // ===== PILIH BAGIAN DROPDOWN CHANGE EVENT =====
      $('#pilih_bagian').on('change', function() {
        var selected = $(this).find('option:selected');
        var kode_bagian = selected.data('kode');
        
        console.log('✓ Pilih Bagian changed');
        console.log('  - Kode Bagian:', kode_bagian);
        
        if(kode_bagian && kode_bagian !== 'null' && kode_bagian !== '') {
          // Only calculate MAX+1 when user manually selects from dropdown
          // This triggers the AJAX call to get next kode
          loadNextKodeBagian(kode_bagian);
          console.log('✓ Calculating next Kode Bagian (MAX + 1)');
        } else {
          console.log('✗ Invalid kode_bagian value or no selection');
        }
      });

      // ===== FORM SUBMIT EVENT =====
      $('#form-default').on('submit', function(){
        console.log('✓ Form submitted');
        
        var formData = new FormData($('#form-default')[0]);
        var url = $('#form-default').attr('action');

        $.ajax({
          url: url,
          type: "POST",
          data: formData,
          dataType: "JSON",
          contentType: false,
          processData: false,            
          beforeSend: function() {
            console.log('✓ Sending form data...');
            achtungShowLoader();  
          },
          complete: function(xhr) {     
            var data = xhr.responseText;
            console.log('✓ Response received:', data);
            
            try {
              var jsonResponse = JSON.parse(data);
              console.log('✓ Response status:', jsonResponse.status);
              
              if(jsonResponse.status === 200){
                console.log('✓ Success! Message:', jsonResponse.message);
                $.achtung({message: jsonResponse.message, timeout:5});
                $('#page-area-content').load('master_data/C_bagian');
              } else {
                console.log('✗ Error! Message:', jsonResponse.message);
                $.achtung({message: jsonResponse.message, timeout:5});
              }
            } catch(e) {
              console.log('✗ JSON parse error:', e);
            }
            achtungHideLoader();
          }
        });

        return false;
      });

      // ===== INITIALIZE ON PAGE LOAD =====
      console.log('✓ Page initialized');
      var initial_group = $('input[name="group_bag"]:checked').val();
      console.log('✓ Initial Group Bag value:', initial_group);
      
      if(initial_group === 'Detail') {
        console.log('✓ Initial state: Detail selected, showing dropdown');
        dropdown_active = true;
        $('#pilih_bagian_group').show();
        $('#kode_bagian_group').show();
        $('#validasi_group').show();
        $('#depo_group_group').show();
        // Show additional fields required for Detail on init
        $('#kode_poli_bpjs_group').show();
        $('#has_observe_room_group').show();
        $('#is_public_group').show();
        $('#short_name_group').show();
        $('#id_satu_sehat_group').show();
        $('#location_id_group').show();
        
        // Load dropdowns
        loadPilihBagianDropdown();
        loadDepoGroupDropdown();
        
        // If UPDATE mode, auto-select based on saved values
        var saved_depo_group = $('#depo_group').val();
        var saved_kode_bagian = $('#kode_bagian').val();
        console.log('✓ Saved depo_group:', saved_depo_group);
        console.log('✓ Saved kode_bagian:', saved_kode_bagian);
        
        if(saved_depo_group && saved_kode_bagian) {
          console.log('✓ UPDATE mode detected, keeping existing kode_bagian');
          // UPDATE mode - make kode_bagian readonly and keep existing value
          $('#kode_bagian').prop('readonly', true);
          $('#kode_bagian_hint').text('Readonly (UPDATE mode)');
          
          // Wait for dropdowns to populate, then select saved values
          setTimeout(function() {
            // Select saved depo_group
            $('#depo_group').val(saved_depo_group);
            console.log('✓ Depo Group set to saved value:', saved_depo_group);
          }, 600);
        } else {
          console.log('✓ CREATE mode - ready for new selection');
          // CREATE mode - make editable for dropdown selection
          $('#kode_bagian').prop('readonly', false);
          $('#kode_bagian_hint').text('Auto-fill dari Pilih Bagian (MAX + 1) - ketika dipilih');
        }
      } else {
        console.log('✓ Initial state: Group selected');
        $('#pilih_bagian_group').hide();
        $('#kode_bagian_group').show();
        $('#validasi_group').hide();
        $('#depo_group_group').hide();
        $('#kode_bagian').prop('readonly', false);
        $('#kode_bagian_hint').text('Manual input (Group)');
        // Hide additional fields not needed for Group on init
        $('#kode_poli_bpjs_group').hide();
        $('#has_observe_room_group').hide();
        $('#is_public_group').hide();
        $('#short_name_group').hide();
        $('#id_satu_sehat_group').hide();
        $('#location_id_group').hide();
      }

    });

</script>