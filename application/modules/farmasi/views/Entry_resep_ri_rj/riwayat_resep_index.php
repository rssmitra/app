<form class="form-horizontal" method="post" id="form_search" action="templates/References/find_data" autocomplete="off">

      <div class="col-md-12">
        <h3 class="row header smaller lighter orange">
          <span class="col-sm-8">
            <i class="ace-icon fa fa-bell"></i>
            <?php echo strtoupper($title)?>
          </span><!-- /.col -->
        </h3>
        
        <div class="form-group">
            <label class="control-label col-sm-2">Pencarian berdasarkan</label>
            <div class="col-md-2">
              <select name="search_by" class="form-control">
                <option value="kode_trans_far">Kode Transaksi</option>
                <option value="nama_pasien">Nama Pasien</option>
              </select>
            </div>

            <label class="control-label col-sm-1">Keyword</label>
            <div class="col-md-2">
              <input type="text" class="form-control" name="keyword" id="keyword">
            </div>
            
            <div class="col-md-5" style="margin-left: -10px">
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
      <div class="clearfix"></div>
      <!-- div.dataTables_borderWrap -->
      <div class="col-md-12">
        <div>
          <table id="dynamic-table" base-url="farmasi/Etiket_obat/riwayat_resep?flag=<?php echo $flag?>&profit=<?php echo $profit?>" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th>&nbsp;</th>
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
      </div>

    </form>

<script src="<?php echo base_url().'assets/js/custom/als_datatable_custom_url.js'?>"></script>




