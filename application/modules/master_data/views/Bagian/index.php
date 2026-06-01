<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <div class="clearfix" style="margin-bottom:-5px">
      <?php echo $this->authuser->show_button('master_data/C_bagian','C','',7)?>
      <?php echo $this->authuser->show_button('master_data/C_bagian','D','',5)?>
      <a href="#" id="btn_export_excel" class="btn btn-sm btn-success">
        <i class="fa fa-file-excel-o"></i> Export Excel
      </a>
      <div class="pull-right tableTools-container"></div>
    </div>
    <hr class="separator">

    <!-- Filter bar -->
    <div class="well well-sm" style="padding:8px 12px;margin-bottom:10px;">
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3" style="margin-bottom:4px;">
          <label style="font-size:12px;margin-bottom:3px;display:block;">Status Aktif</label>
          <select id="filter_is_active" class="form-control input-sm">
            <option value="">-- Semua Status --</option>
            <option value="Y">Aktif</option>
            <option value="N">Tidak Aktif</option>
          </select>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3" style="margin-bottom:4px;">
          <label style="font-size:12px;margin-bottom:3px;display:block;">Nama Group</label>
          <select id="filter_depo_group" class="form-control input-sm">
            <option value="">-- Semua Group --</option>
            <?php foreach ($group_list as $g): ?>
            <option value="<?php echo htmlspecialchars($g->kode_bagian, ENT_QUOTES) ?>">
              <?php echo htmlspecialchars($g->kode_bagian, ENT_QUOTES) ?> &ndash; <?php echo htmlspecialchars($g->nama_bagian, ENT_QUOTES) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3" style="padding-top:18px;">
          <button id="btn_reset_filter" class="btn btn-sm btn-default">
            <i class="fa fa-refresh"></i> Reset Filter
          </button>
        </div>
      </div>
    </div>

    <div style="margin-top:0">
      <table id="dynamic-table" base-url="master_data/C_bagian" data-id="" url-detail="master_data/C_bagian/get_detail" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th width="30px"  class="center"></th>              <!-- 0: checkbox -->
            <th width="40px"  class="center"></th>              <!-- 1: expand -->
            <th width="40px"  class="center"></th>              <!-- 2: hidden ID -->
            <th width="40px"></th>                              <!-- 3: actions -->
            <th width="40px"  class="center">No</th>            <!-- 4 -->
            <th width="90px"  class="center">Kode</th>          <!-- 5 -->
            <th>Nama Bagian</th>                                 <!-- 6 -->
            <th width="110px">Nama Singkat</th>                  <!-- 7 -->
            <th width="180px">Nama Group (Parent)</th>            <!-- 8 -->
            <th width="60px"  class="center">Depo?</th>         <!-- 9 -->
            <th width="100px" class="center">Depo Group</th>    <!-- 10 -->
            <th width="90px"  class="center">Publik?</th>         <!-- 11 -->
            <th width="100px" class="center">Pelayanan?</th>    <!-- 12 -->
            <th width="90px"  class="center">Status Aktif</th>  <!-- 13 -->
            <th width="180px">Last Update</th>                   <!-- 14 -->
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable_with_detail_custom_url.js'?>"></script>

<script type="text/javascript">
jQuery(function($) {

    // ── Export Excel ──────────────────────────────────────────────────────────
    $('#btn_export_excel').on('click', function(e) {
        e.preventDefault();
        var params = $.param({
            filter_is_active:  $('#filter_is_active').val(),
            filter_depo_group: $('#filter_depo_group').val()
        });
        window.open('<?php echo site_url("master_data/C_bagian/export_excel"); ?>?' + params, '_blank');
    });

    // Inject filter values into every DataTable AJAX POST request
    $('#dynamic-table').on('preXhr.dt', function(e, settings, data) {
        data.filter_is_active   = $('#filter_is_active').val();
        data.filter_depo_group  = $('#filter_depo_group').val();
    });

    // Redraw on filter change
    $('#filter_is_active, #filter_depo_group').on('change', function() {
        $('#dynamic-table').DataTable().draw();
    });

    // Reset all filters
    $('#btn_reset_filter').on('click', function() {
        $('#filter_is_active').val('');
        $('#filter_depo_group').val('');
        $('#dynamic-table').DataTable().draw();
    });

    // ── Inline field toggles (Depo, Kamar Obs, Pelayanan, Status Aktif) ──────
    $(document).on('click', '.tbl-toggle-opt', function() {
        var $span  = $(this);
        var $wrap  = $span.closest('.tbl-field-toggle');
        var id     = $wrap.data('id');
        var field  = $wrap.data('field');
        var newVal = String($span.data('val'));
        var curVal = String($wrap.data('current'));

        if (newVal === curVal) return; // already active

        $wrap.css({ opacity: '0.5', pointerEvents: 'none' });

        $.ajax({
            url:      '<?php echo site_url("master_data/C_bagian/update_field"); ?>',
            type:     'POST',
            dataType: 'JSON',
            data:     { id: id, field: field, value: newVal },
            complete: function(xhr) {
                $wrap.css({ opacity: '1', pointerEvents: '' });
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.status === 200) {
                        $wrap.data('current', newVal);
                        var colorMap = {
                            'is_depo':         { 'Y': '#8e44ad', 'N': '#95a5a6' },
                            'is_public':        { 'Y': '#27ae60', 'N': '#95a5a6' },
                            'pelayanan':        { '1': '#2980b9', '0': '#e67e22' },
                            'is_active':        { 'Y': '#27ae60', 'N': '#e74c3c' }
                        };
                        $wrap.find('.tbl-toggle-opt').each(function() {
                            var $s  = $(this);
                            var val = String($s.data('val'));
                            if (val === newVal) {
                                var bg = (colorMap[field] && colorMap[field][val]) ? colorMap[field][val] : '#555';
                                $s.css({ background: bg, color: '#fff', fontWeight: 'bold', cursor: 'default' });
                            } else {
                                $s.css({ background: '#f0f0f0', color: '#aaa', fontWeight: '', cursor: 'pointer' });
                            }
                        });
                    } else {
                        $.achtung({ message: res.message, timeout: 4 });
                    }
                } catch(e) {}
            }
        });
    });

    // ── Inline parent group select ────────────────────────────────────────────
    // Delegated event — handles selects in rows loaded by DataTable AJAX
    $(document).on('change', '.tbl-parent-select', function() {
        var $sel     = $(this);
        var id       = $sel.data('id');
        var kode     = $sel.val();
        var $td      = $sel.closest('td');

        $sel.prop('disabled', true);
        $td.css('opacity', '0.6');

        $.ajax({
            url:      '<?php echo site_url("master_data/C_bagian/update_parent"); ?>',
            type:     'POST',
            dataType: 'JSON',
            data:     { id: id, kode_group: kode },
            complete: function(xhr) {
                $sel.prop('disabled', false);
                $td.css('opacity', '1');
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.status === 200) {
                        $td.stop(true).css('background', '#dff0d8')
                           .animate({ backgroundColor: 'transparent' }, 1500);
                    } else {
                        $td.css('background', '#f2dede');
                        setTimeout(function(){ $td.css('background', ''); }, 2000);
                        $.achtung({ message: res.message, timeout: 5 });
                    }
                } catch(e) {}
            }
        });
    });

});
</script>
