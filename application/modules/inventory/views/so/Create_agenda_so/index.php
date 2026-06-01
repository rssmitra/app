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
      <?php echo $this->authuser->show_button('inventory/so/Create_agenda_so','C','',1)?>
      <?php echo $this->authuser->show_button('inventory/so/Create_agenda_so','D','',5)?>

    </div>
    <hr class="separator">
    <!-- div.table-responsive -->

    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="inventory/so/Create_agenda_so" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center"></th>
          <th width="130px">&nbsp;</th>
          <th width="50px">ID</th>
          <th>Nama Kegiatan</th>
          <th>Tanggal Pelaksanaan</th>
          <th><i>Cut off stokc</i></th>
          <th>Penanggung Jawab</th>
          <th width="300px">Keterangan</th>
          <th>Status</th>
          <th width="100px">Last Update</th>
          <th width="100px">Freeze Stok</th>
          
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>
<script src="<?php echo base_url().'assets/js/sweetalert2.all.min.js'?>"></script>

<script type="text/javascript">
var _soBaseUrl = '<?php echo site_url('inventory/so/Create_agenda_so') ?>';

$(document).off('click.sofreeze').on('click.sofreeze', '.btn-so-toggle-freeze', function () {
    var $btn      = $(this);
    var id        = $btn.data('id');
    var curFrozen = $btn.data('frozen');
    var newFrozen = (curFrozen === 'Y') ? 'N' : 'Y';

    Swal.fire({
        title:             (newFrozen === 'Y') ? 'Freeze Stok Agenda?' : 'Lepas Freeze Stok?',
        html:              (newFrozen === 'Y')
            ? 'Stok agenda ini akan <strong>dikunci</strong>.<br>Lanjutkan?'
            : 'Freeze stok agenda ini akan <strong>dilepas</strong>.<br>Lanjutkan?',
        icon:              (newFrozen === 'Y') ? 'warning' : 'question',
        showCancelButton:  true,
        confirmButtonText: (newFrozen === 'Y') ? '<i class="fa fa-lock"></i> Ya, Freeze' : '<i class="fa fa-unlock"></i> Ya, Lepas Freeze',
        cancelButtonText:  'Batal',
        confirmButtonColor: (newFrozen === 'Y') ? '#e6a817' : '#d9534f',
        cancelButtonColor:  '#6c757d',
        reverseButtons:    true,
    }).then(function (result) {
        if (!result.isConfirmed) return;

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.post(_soBaseUrl + '/set_frozen', { id: id, is_frozen: newFrozen }, function (res) {
            if (res && res.status === 200) {
                Swal.fire({
                    icon:  'success',
                    title: 'Berhasil',
                    text:  res.message,
                    timer: 2000,
                    showConfirmButton: false,
                });
                $('#dynamic-table').DataTable().ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon:  'error',
                    title: 'Gagal',
                    text:  (res && res.message) ? res.message : 'Proses gagal.',
                });
                $btn.prop('disabled', false).html(
                    curFrozen === 'Y'
                        ? '<i class="fa fa-unlock"></i> Lepas Freeze'
                        : '<i class="fa fa-lock"></i> Freeze Stok'
                );
            }
        }, 'json').fail(function () {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Koneksi gagal. Silahkan coba lagi.' });
            $btn.prop('disabled', false).html(
                curFrozen === 'Y'
                    ? '<i class="fa fa-unlock"></i> Lepas Freeze'
                    : '<i class="fa fa-lock"></i> Freeze Stok'
            );
        });
    });
});
</script>



