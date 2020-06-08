<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_search" action="templates/References/find_data" autocomplete="off">

      <div class="col-md-12">
        <div class="form-group">
            <label class="control-label col-md-2">Pencarian berdasarkan</label>
            <div class="col-md-2">
              <select name="search_by" class="form-control">
                <option value="kode_trans_far">Kode Transaksi</option>
                <option value="nama_pasien">Nama Pasien</option>
              </select>
            </div>

            <label class="control-label col-md-1">Keyword</label>
            <div class="col-md-2">
              <input type="text" class="form-control" name="keyword" id="keyword">
            </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Tanggal</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>

            <label class="control-label col-md-1">s/d</label>
            <div class="col-md-2" style="margin-lef:-10px">
              <div class="input-group">
                <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2 ">&nbsp;</label>
          <div class="col-md-10" style="margin-left: 5px">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Search
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
              <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
              Reset
            </a>
          </div>
        </div>

      </div>

      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="farmasi/Etiket_obat/riwayat_resep?flag=<?php echo $flag?>&profit=<?php echo $profit?>" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th class="center" style="min-width:30px">No</th>
              <th>Kode</th>
              <th>Tgl Pesan</th>
              <th>Nama Pasien</th>
              <th>Nama Dokter</th>
              <th>Pelayanan</th>
              <th style="width:60px">Status</th>
              <th class="center" style="width:50px">Nota<br>Farmasi</th>
              <th class="center" style="min-width: 70px">Copy<br>Resep</th>
              <th class="center" style="width:50px">Etiket</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable_custom_url.js'?>"></script>




