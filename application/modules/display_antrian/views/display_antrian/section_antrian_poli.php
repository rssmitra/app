<div class="row" >
  <p style="text-align: center; font-size: 3.5em; font-weight: bold; color: white ">ANTRIAN POLIKLINIK</p>
  <div class="col-md-12">
    <?php foreach($data_loket as $row) :?>
    <div class="col-md-4">
      <span style="background: #df1e8e; color: white; padding: 10px; text-align: center; font-size: 2.2em; font-weight: bold; padding-bottom: 5px"><?php echo $row->kode_poli_bpjs; ?></span>
      <div style="padding: 3px;">
        <table class="table sedang_dilayani_poli" id="data_sedang_dilayani_poli">
          <tbody style="background:rgb(15, 53, 78)">
            <tr>
              <td align="center">1</td>
              <td>-Tidak ada data-</td>
              <td>-</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <?php endforeach; ?>


  </div>
</div>