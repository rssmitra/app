<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Monitoring Intra Sedasi / Anestesi</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
  table { border-collapse: collapse; width: 100%; margin-bottom: 15px; }
  th, td { border: 1px solid #000; padding: 4px; text-align: center; }
  input { width: 100%; box-sizing: border-box; }
  h3 { text-align: center; }
</style>
</head>
<body>

<h3>MONITORING INTRA SEDASI / ANESTESI</h3>

<!-- Bagian OBAT -->
<table>
  <tr><th>OBAT</th><th>UNIT</th><th>DOSIS</th><th>TOTAL</th></tr>
  <?php for($i=1;$i<=6;$i++): ?>
  <tr>
    <td><input type="text" name="form_obat[<?= $i ?>][nama]"></td>
    <td><input type="text" name="form_obat[<?= $i ?>][unit]"></td>
    <td><input type="text" name="form_obat[<?= $i ?>][dosis]"></td>
    <td><input type="text" name="form_obat[<?= $i ?>][total]"></td>
  </tr>
  <?php endfor; ?>
</table>

<!-- Jenis Gas -->
<div>
  <label><input type="checkbox" name="gas[]" value="Sevoflurane"> Sevoflurane</label>
  <label><input type="checkbox" name="gas[]" value="Halothane"> Halothane</label>
  <label><input type="checkbox" name="gas[]" value="Isoflurane"> Isoflurane</label>
  <label><input type="checkbox" name="gas[]" value="O2"> O₂</label>
  <label><input type="checkbox" name="gas[]" value="N2O"> N₂O</label>
</div>

<hr>

<!-- Input Vital Sign -->
<h4>Monitoring Vital Sign</h4>
<table id="monitorTable">
  <tr>
    <th>Jam</th><th>Nadi</th><th>TD (mmHg)</th><th>SpO₂</th><th>ETCO₂</th><th>Dosis</th>
  </tr>
</table>
<button type="button" onclick="addRow()">+ Tambah Waktu</button>

<canvas id="chartVital" height="120"></canvas>

<script>
let dataNadi = [];
let dataTD = [];
let labels = [];

function addRow() {
  const tbl = document.getElementById("monitorTable");
  const row = tbl.insertRow();
  const jam = row.insertCell(0);
  const nadi = row.insertCell(1);
  const td = row.insertCell(2);
  const spo2 = row.insertCell(3);
  const etco2 = row.insertCell(4);
  const dosis = row.insertCell(5);
  
  jam.innerHTML = `<input type="time" onchange="updateChart()">`;
  nadi.innerHTML = `<input type="number" min="0" max="200" onchange="updateChart()">`;
  td.innerHTML = `<input type="text" placeholder="120/80" onchange="updateChart()">`;
  spo2.innerHTML = `<input type="number" min="0" max="100" onchange="updateChart()">`;
  etco2.innerHTML = `<input type="number" min="0" max="100" onchange="updateChart()">`;
  dosis.innerHTML = `<input type="text" onchange="updateChart()">`;
}

let ctx = document.getElementById('chartVital').getContext('2d');
let chart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: labels,
    datasets: [{
      label: 'Nadi',
      data: dataNadi,
      borderColor: 'red',
      fill: false
    },{
      label: 'Tekanan Darah (TD, Sistolik)',
      data: dataTD,
      borderColor: 'blue',
      fill: false
    }]
  },
  options: {
    scales: {
      y: { beginAtZero: true },
      x: { title: { display: true, text: 'Jam' } }
    }
  }
});

function updateChart() {
  const rows = document.querySelectorAll("#monitorTable tr:not(:first-child)");
  labels.length = 0; dataNadi.length = 0; dataTD.length = 0;
  rows.forEach(row => {
    const jam = row.cells[0].querySelector('input').value;
    const nadi = parseInt(row.cells[1].querySelector('input').value) || null;
    const td = row.cells[2].querySelector('input').value.split('/')[0] || null;
    if (jam && nadi && td) {
      labels.push(jam);
      dataNadi.push(nadi);
      dataTD.push(parseInt(td));
    }
  });
  chart.update();
}
</script>

<hr>

<!-- Ventilasi dan Cairan -->
<table>
  <tr><th colspan="4">VENTILASI</th></tr>
  <tr><td>Spontan</td><td>Bantu</td><td>Kendali</td><td>SpO₂</td></tr>
  <tr>
    <td><input type="checkbox" name="ventilasi[spontan]"></td>
    <td><input type="checkbox" name="ventilasi[bantu]"></td>
    <td><input type="checkbox" name="ventilasi[kendali]"></td>
    <td><input type="number" name="ventilasi[spo2]" min="0" max="100"></td>
  </tr>
</table>

<table>
  <tr><th>INFUS</th><th>PERDARAHAN</th><th>URINE</th><th>BALANS CAIRAN</th></tr>
  <tr>
    <td><input type="text" name="infus"></td>
    <td><input type="text" name="perdarahan"></td>
    <td><input type="text" name="urine"></td>
    <td><input type="text" name="balans"></td>
  </tr>
</table>

<hr>

<!-- Tanda tangan -->
<table style="text-align:center;">
  <tr>
    <td>
      Jakarta, <input type="text" name="tanggal_ttd" style="width:100px;">
      <br><br>Penanggung Jawab Kamar Bedah
      <br><br><span class="ttd-btn" id="ttd_penanggung"><i class="fa fa-pencil blue"></i></span>
      <br>(Tanda Tangan & Nama Jelas)
    </td>
    <td>
      <br><br>Perawat Sirkuler
      <br><br><span class="ttd-btn" id="ttd_perawat"><i class="fa fa-pencil blue"></i></span>
      <br>(Tanda Tangan & Nama Jelas)
    </td>
  </tr>
</table>

</body>
</html>
