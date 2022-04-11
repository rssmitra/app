<style type="text/css">
    table{
      width: 100% !important;
      font-size: 12px;
    }
    .table-custom thead {
      background-color: #14506b;
      color: white;
    }

    .table-custom th, td {
      padding: 10px;
      border: 1px solid #c5d0dc;
    }
    .table-custom tbody tr:hover {background-color: #e6e6e6e0;}
    .td_custom{font-size: 20px;font-weight: bold; background: linear-gradient(348deg, #88b91000, #4c953f);;color: white;border: 5px solid white; cursor: pointer}
</style>

<div style="background: white; padding: 10px">
  <table class="table-custom">
      <tbody>
        <?php foreach ($spesialis as $key => $value) : ?>
          <tr>
            <td align="left" class="td_custom"><span onclick="scrollSmooth('Self_service/jadwal_dokter?kode=<?php echo $value->kode_bagian?>')"><?php echo strtoupper($value->nama_bagian)?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
</div>





