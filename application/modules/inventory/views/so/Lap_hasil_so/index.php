<style>
.lhs-thead th {
    background: linear-gradient(135deg, #0369a1, #0891b2);
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
    border-color: #0284c7 !important;
    white-space: nowrap;
}
</style>

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
    </div>

    <div style="margin-top:0">
      <table id="dynamic-table" base-url="inventory/so/Lap_hasil_so"
             class="table table-bordered table-hover table-condensed" style="font-size:12px">
        <thead>
          <tr class="lhs-thead">
            <th width="30px"  class="center"></th>
            <th width="50px"  class="center">ID</th>
            <th>Nama Kegiatan</th>
            <th width="120px">Tanggal</th>
            <th width="180px">Penanggung Jawab</th>
            <th>Keterangan</th>
            <th width="90px"  class="center">Status</th>
            <th width="130px">Last Update</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

  </div>
</div>

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>
