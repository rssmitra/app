<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Antrian Farmasi - Monitoring Pasien</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:400,600,700&display=swap" />
  <style>
    body {
      font-family: 'Inter', Arial, sans-serif;
      background: #f6f8fa;
      margin: 0;
      color: #222;
    }
    .header {
      background: #00669f;
      color: #fff;
      padding: 1rem 0.5rem;
      text-align: center;
      font-size: 1.5rem;
      font-weight: 700;
      letter-spacing: 1px;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .date-time {
      font-size: 1rem;
      font-weight: 400;
      margin-top: 0.25rem;
      color: #e0e0e0;
    }
    .queue-container {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      justify-content: center;
      padding: 1rem 0.5rem 5rem 0.5rem;
      max-width: 1200px;
      margin: 0 auto;
    }
    .queue-section {
      background: #fff;
      border-radius: 1rem;
      box-shadow: 0 2px 12px rgba(0,102,159,0.08);
      /* flex: 1 1 320px; */
      /* min-width: 280px;
      max-width: 350px; */
      display: flex;
      flex-direction: column;
      align-items: stretch;
      margin-bottom: 1rem;
    }
    .queue-title {
      background: #00669f;
      color: #fff;
      border-top-left-radius: 1rem;
      border-top-right-radius: 1rem;
      padding: 1rem;
      font-size: 1.2rem;
      font-weight: 600;
      text-align: center;
      letter-spacing: 1px;
    }
    .queue-list {
      flex: 1 1 auto;
      overflow-y: auto;
      max-height: 22vh;
      padding: 0rem 0.5rem 0.5rem 0.5rem;
    }
    .queue-list table {
      width: 100%;
      border-collapse: collapse;
    }
    .queue-list th, .queue-list td {
      padding: 0.5rem 0.3rem;
      text-align: left;
      font-size: 1.05rem;
    }
    .queue-list th {
      background: #e3f1fa;
      color: #00669f;
      font-weight: 600;
      position: sticky;
      top: 0;
      z-index: 1;
    }
    .queue-list tr {
      border-bottom: 1px solid #f0f0f0;
    }
    .queue-list td {
      color: #222;
      font-weight: 500;
    }
    .queue-list td.time {
      text-align: center;
      color: #00669f;
      font-weight: 600;
    }
    .queue-total {
      background: #f6f8fa;
      color: #00669f;
      text-align: center;
      font-size: 1.1rem;
      font-weight: 600;
      padding: 0.7rem 0;
      border-bottom-left-radius: 1rem;
      border-bottom-right-radius: 1rem;
    }
    .footer {
      background: #00669f;
      color: #fff;
      text-align: center;
      font-size: 1rem;
      font-weight: 500;
      padding: 1rem 0.5rem 1.5rem 0.5rem;
      position: fixed;
      left: 0;
      bottom: 0;
      width: 100vw;
      z-index: 100;
      box-shadow: 0 -2px 12px rgba(13,82,128,0.12);
    }
    .avg-waktu {
      color: #ffeb3b;
      font-size: 1.5rem;
      font-weight: 700;
      margin-left: 0.5rem;
    }
    @media (max-width: 900px) {
      .queue-container {
        flex-direction: column;
        gap: 0.5rem;
        padding: 0.5rem 0.2rem 5rem 0.2rem;
      }
      .queue-section {
        min-width: 0;
        max-width: 100vw;
      }
    }
    @media (max-width: 600px) {
      .header {
        font-size: 1.1rem;
        padding: 0.7rem 0.2rem;
      }
      .queue-title {
        font-size: 1rem;
        padding: 0.7rem 0.2rem;
      }
      .queue-list th, .queue-list td {
        font-size: 0.95rem;
        padding: 0.4rem 0.2rem;
      }
      .footer {
        font-size: 0.95rem;
        padding: 0.7rem 0.2rem 1.2rem 0.2rem;
      }
      .avg-waktu {
        font-size: 1.1rem;
      }
    }

    .search-bar-wrapper {
      width: 100%;
      /* max-width: 1200px; */
      margin: 0 auto 1rem auto;
      /* display: flex; */
      /* justify-content: flex-end; */
      /* padding: 0 0.5rem; */
    }
    .search-bar {
      width: 100%;
      /* max-width: 350px; */
      /* padding: 0.7rem 1rem; */
      border-radius: 2rem;
      border: 1px solid #bcdff7;
      font-size: 1rem;
      outline: none;
      box-shadow: 0 1px 4px rgba(0,102,159,0.04);
      margin-bottom: 0.5rem;
      transition: border 0.2s;
    }
    .search-bar:focus {
      border: 1.5px solid #00669f;
    }
    .queue-table {
      width: 100%;
      border-collapse: collapse;
    }
    @media (max-width: 600px) {
      .search-bar-wrapper {
        padding: 0 0.1rem;
      }
      .search-bar {
        font-size: 0.95rem;
        padding: 0.5rem 0.8rem;
      }
    }
  </style>
</head>
<body>
  <div class="header">
    ANTRIAN RESEP OBAT FARMASI
    <div class="date-time">
      <i class="fa fa-calendar"></i> <?php date_default_timezone_set("Asia/Jakarta"); echo date('l, d F Y') ?> &nbsp; <i class="fa fa-clock"></i> <span id="time"><?php echo date('H:i') ?></span> WIB
    </div>
  </div>
  
  <div class="search-bar-wrapper" style="padding-top: 14px; width: 100vw; max-width: 100vw; box-sizing: border-box;">
    <input
      type="text"
      id="searchInput"
      class="search-bar"
      placeholder="Cari nama pasien..."
      autocomplete="off"
      style="margin: 0; width: 100vw; max-width: 100vw; box-sizing: border-box; height: 46px; font-size: 16px;"
    >
  </div>

  <div class="queue-container">
    <!-- Resep Diterima -->
    <section class="queue-section">
      <div class="queue-title"><i class="fa fa-inbox"></i> Resep Diterima</div>
      <div class="queue-list">
        <table class="queue-table" data-queue="resep-diterima">
          <thead>
            <tr><th>No</th><th>Nama Pasien</th><th class="time" style="text-align: center !important"><i class="fa fa-clock"></i></th></tr>
          </thead>
          <tbody>
            <?php 
              $no=0; $arr_resep_diterima = [];
              foreach($resep_diterima as $row) :
                $no++;
                $arr_resep_diterima[] = $row;
            ?>
            <tr>
              <td align="center"><?php echo $no?></td>
              <td class="nama-pasien">
                <?php
                  $nama = str_replace($text_hide,'', $row->nama_pasien);
                  $nama = trim(preg_replace('/\s+/', ' ', $nama));
                  $parts = explode(' ', $nama);
                  if(count($parts) <= 2) {
                    echo strtoupper(implode(' ', $parts));
                  } else {
                    $output = array_slice($parts, 0, 2);
                    for($i=2; $i<count($parts); $i++) {
                      $output[] = strtoupper(substr($parts[$i],0,1)).'';
                    }
                    echo strtoupper(implode(' ', $output));
                  }
                ?>
              </td>
              <td class="time" style="text-align: center !important"><?php echo date('H:i', strtotime($row->tgl_trans))?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
      <div class="queue-total">Total: <span style="font-size:1.3em;font-weight:700;"><?php echo count($arr_resep_diterima)?></span></div>
    </section>
    <!-- Proses Racikan -->
    <section class="queue-section">
      <div class="queue-title"><i class="fa fa-flask"></i> Proses Racikan</div>
      <div class="queue-list">
        <table class="queue-table" data-queue="racikan">
          <thead>
            <tr><th>No</th><th>Nama Pasien</th><th class="time" style="text-align: center !important"><i class="fa fa-clock"></i></th></tr>
          </thead>
          <tbody>
            <?php 
              $no=0; $arr_racikan = [];
              foreach($resep as $row) : 
                if($row->log_time_3 != null && $row->log_time_4 == null) : 
                  $no++;
                  $arr_racikan[] = $row;
            ?>
            <tr>
              <td align="center"><?php echo $no?></td>
              <td class="nama-pasien">
                <?php
                  $nama = str_replace($text_hide,'', $row->nama_pasien);
                  $nama = trim(preg_replace('/\s+/', ' ', $nama));
                  $parts = explode(' ', $nama);
                  if(count($parts) <= 2) {
                    echo strtoupper(implode(' ', $parts));
                  } else {
                    $output = array_slice($parts, 0, 2);
                    for($i=2; $i<count($parts); $i++) {
                      $output[] = strtoupper(substr($parts[$i],0,1)).'';
                    }
                    echo strtoupper(implode(' ', $output));
                  }
                ?>
              </td>
              <td class="time" style="text-align: center !important"><?php echo date('H:i', strtotime($row->tgl_trans))?></td>
            </tr>
            <?php endif; endforeach;?>
          </tbody>
        </table>
      </div>
      <div class="queue-total">Total: <span style="font-size:1.3em;font-weight:700;"><?php echo count($arr_racikan)?></span></div>
    </section>
    <!-- Proses Etiket -->
    <section class="queue-section">
      <div class="queue-title"><i class="fa fa-tags"></i> Proses Etiket</div>
      <div class="queue-list">
        <table class="queue-table" data-queue="etiket">
          <thead>
            <tr><th>No</th><th>Nama Pasien</th><th class="time" style="text-align: center !important"><i class="fa fa-clock"></i></th></tr>
          </thead>
          <tbody>
            <?php 
              $no=0; $arr_etiket = [];
              foreach($resep as $row) : 
                if($row->log_time_4 != null && $row->log_time_5 == null) : 
                  $no++;
                  $arr_etiket[] = $row;
            ?>
            <tr>
              <td align="center"><?php echo $no?></td>
              <td class="nama-pasien">
                <?php
                  $nama = str_replace($text_hide,'', $row->nama_pasien);
                  $nama = trim(preg_replace('/\s+/', ' ', $nama));
                  $parts = explode(' ', $nama);
                  if(count($parts) <= 2) {
                    echo strtoupper(implode(' ', $parts));
                  } else {
                    $output = array_slice($parts, 0, 2);
                    for($i=2; $i<count($parts); $i++) {
                      $output[] = strtoupper(substr($parts[$i],0,1)).'';
                    }
                    echo strtoupper(implode(' ', $output));
                  }
                ?>
              </td>
              <td class="time" style="text-align: center !important"><?php echo date('H:i', strtotime($row->tgl_trans))?></td>
            </tr>
            <?php endif; endforeach;?>
          </tbody>
        </table>
      </div>
      <div class="queue-total">Total: <span style="font-size:1.3em;font-weight:700;"><?php echo count($arr_etiket)?></span></div>
    </section>
    <!-- Siap Diambil -->
    <section class="queue-section">
      <div class="queue-title"><i class="fa fa-check-circle"></i> Siap Diambil</div>
      <div class="queue-list">
        <table class="queue-table" data-queue="siap-diambil">
          <thead>
            <tr><th>No</th><th>Nama Pasien</th><th class="time" style="text-align: center !important"><i class="fa fa-clock"></i></th></tr>
          </thead>
          <tbody>
            <?php 
              $no=0; $arr_siap_diambil = [];
              foreach($resep as $row) : 
                if($row->log_time_5 != null && $row->log_time_6 == null) : 
                  if($no <= 30) :
                    $no++;
                    $arr_siap_diambil[] = $row;
            ?>
            <tr>
              <td align="center"><?php echo $no?></td>
              <td class="nama-pasien">
                <?php
                  $nama = str_replace($text_hide,'', $row->nama_pasien);
                  $nama = trim(preg_replace('/\s+/', ' ', $nama));
                  $parts = explode(' ', $nama);
                  if(count($parts) <= 2) {
                    echo strtoupper(implode(' ', $parts));
                  } else {
                    $output = array_slice($parts, 0, 2);
                    for($i=2; $i<count($parts); $i++) {
                      $output[] = strtoupper(substr($parts[$i],0,1)).'';
                    }
                    echo strtoupper(implode(' ', $output));
                  }
                ?>
              </td>
              <td class="time" style="text-align: center !important"><?php echo date('H:i', strtotime($row->tgl_trans))?></td>
            </tr>
            <?php endif; endif; endforeach;?>
          </tbody>
        </table>
      </div>
    
      <div class="queue-total">Total: <span style="font-size:1.3em;font-weight:700;"><?php echo count($arr_siap_diambil)?></span></div>
    </section>
  </div>
  <div class="footer">
    <span>RS Setia Mitra | Smart Hospital System 4.0 &copy; 2018-<?php echo date('Y')?> <br>
      <span style="font-weight:400;">Rata-rata Waktu Tunggu Obat:</span>
      <span class="avg-waktu" id="avg-waktu-tunggu">
        <?php
          $total_selesai = 0;
          $total_detik = 0;
          if (isset($resep) && is_array($resep)) {
            foreach($resep as $row) {
              if($row->log_time_5 != null && $row->log_time_1 != null) {
                $total_selesai++;
                $start = strtotime($row->log_time_1);
                $end = strtotime($row->log_time_5);
                $row_total_detik = ($end - $start);
                $total_detik += ($row_total_detik > 3600) ? 3600 : $row_total_detik;
              }
            }
          }
          $rata2 = '-';
          if($total_selesai > 0 && $total_detik > 0) {
            $avg = $total_detik / $total_selesai;
            $jam = floor($avg / 3600);
            $menit = floor(($avg % 3600) / 60);
            $detik = $avg % 60;
            $rata2 = sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
          }
          echo $rata2;
        ?>
      </span>
    </span>
  </div>
  <script>
    // Update time every 10 seconds
    setInterval(function(){
      var now = new Date();
      var h = now.getHours().toString().padStart(2,'0');
      var m = now.getMinutes().toString().padStart(2,'0');
      document.getElementById('time').textContent = h+":"+m;
    }, 10000);
    // Live search filter for all queue tables
    document.addEventListener('DOMContentLoaded', function() {
      var searchInput = document.getElementById('searchInput');
      if (!searchInput) return;
      searchInput.addEventListener('input', function() {
        var filter = this.value.trim().toLowerCase();
        document.querySelectorAll('.queue-table').forEach(function(table) {
          var rows = table.querySelectorAll('tbody tr');
          rows.forEach(function(row) {
            var namaCell = row.querySelector('.nama-pasien');
            if (!namaCell) return;
            var nama = namaCell.textContent.trim().toLowerCase();
            if (filter === '' || nama.indexOf(filter) !== -1) {
              row.style.display = '';
            } else {
              row.style.display = 'none';
            }
          });
        });
      });
    });
  </script>
</body>
</html>

