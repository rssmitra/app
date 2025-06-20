<p>
  <div class="gcs-info">
    <table class="table">
      <thead>
        <tr>
          <td style="width: 30px;">No</td>
          <td style="width: 180px;">Tingkat Kesadaran</td>
          <td>Deskripsi</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="center">1.</td>
          <td><b>KOMPOSMENTIS</b></td>
          <td>Bereaksi segera dengan orientasi sempurna.</td>
        </tr>
        <tr>
          <td class="center">2.</td>
          <td><b>APATIS</b></td>
          <td>Terlihat mengantuk tetapi mudah dibangunkan dan reaksi penglihatan, pendengaran dan perabaan normal.</td>
        </tr>
        <tr>
          <td class="center">3.</td>
          <td><b>SOMNOLENT</b></td>
          <td>Dapat dibangunkan bila dirangsang, dapat disuruh dan menjawab pertanyaan. Bila rangsangan berhenti penderita tidur lagi.</td>
        </tr>
        <tr>
          <td class="center">4.</td>
          <td><b>SOPOR</b></td>
          <td>Dapat dibangunkan bila dirangsangkan dengan kasar dan terus menerus.</td>
        </tr>
        <tr>
          <td class="center">5.</td>
          <td><b>SOPORKOMA</b></td>
          <td>Refleks motoris terjadi hanya bila dirangsang dengan rangsangan nyeri.</td>
        </tr>
        <tr>
          <td class="center">6.</td>
          <td><b>KOMA</b></td>
          <td>Tidak ada refleks motoris sekalipun dengan perangsang nyeri.</td>
        </tr>
      </tbody>
    </table>
  </div>

  <form id="gcsForm">
    <div class="form-group">
      <label for="tingkat_kesadaran">TINGKAT KESADARAN</label>
      <select id="tingkat_kesadaran" name="tingkat_kesadaran" class="form-control">
        <option value="Komposmentis">Komposmentis</option>
        <option value="Apatis">Apatis</option>
        <option value="Somnolent">Somnolent</option>
        <option value="Sopor">Sopor</option>
        <option value="Soporkoma">Soporkoma</option>
        <option value="Koma">Koma</option>
      </select>
    </div>
    <div class="form-group" style="padding-top: 10px">
      <label for="gcs_eye">BUKA MATA (EYE)</label>
      <select id="gcs_eye" name="gcs_eye" class="form-control">
        <option value="1">Tidak</option>
        <option value="2">Pada nyeri</option>
        <option value="3">Pada bicara</option>
        <option value="4">Spontan</option>
      </select>
    </div>
    <div class="form-group" style="padding-top: 10px">
      <label for="gcs_motor">RESPONS MOTORIK (MOVEMENT)</label>
      <select id="gcs_motor" name="gcs_motor" class="form-control">
        <option value="1">Tidak Ada</option>
        <option value="2">Eksistensi</option>
        <option value="3">Fleksi Raba</option>
        <option value="4">Menarik</option>
        <option value="5">Tunjuk Nyeri</option>
        <option value="6">Menurut Perintah</option>
      </select>
    </div>
    <div class="form-group" style="padding-top: 10px">
      <label for="gcs_verbal">RESPONS KOMUNIKASI (VERBAL)</label>
      <select id="gcs_verbal" name="gcs_verbal" class="form-control">
        <option value="1">Tidak Ada</option>
        <option value="2">Tanpa Arti</option>
        <option value="3">Kata tak benar</option>
        <option value="4">Bicara mengacau</option>
        <option value="5">Orientasi baik</option>
      </select>
    </div>
    <div class="form-group" style="padding-top: 10px">
      <label>TOTAL SKOR GCS</label>
      <input type="text" id="gcs_total" name="gcs_total" class="form-control" readonly value="15">
    </div>
    <div class="form-group" style="padding-top: 10px">
      <label>BESAR PUPIL (mm)</label><br>
      <div id="pupil_size_group">
        <label class="radio-inline"><input type="radio" name="pupil_size" value="2"> 2</label>
        <label class="radio-inline"><input type="radio" name="pupil_size" value="3"> 3</label>
        <label class="radio-inline"><input type="radio" name="pupil_size" value="4"> 4</label>
        <label class="radio-inline"><input type="radio" name="pupil_size" value="5"> 5</label>
        <label class="radio-inline"><input type="radio" name="pupil_size" value="6"> 6</label>
        <label class="radio-inline"><input type="radio" name="pupil_size" value="7"> 7</label>
        <label class="radio-inline"><input type="radio" name="pupil_size" value="8"> 8</label>
      </div>
    </div>
    <hr>
    
    <button type="button" class="btn btn-xs btn-primary" data-dismiss="modal" aria-hidden="true" onclick="close_modal()">Simpan dan Tutup</button>

  </form>

  <script>
  function updateGCSTotal() {
    var eye = parseInt(document.getElementById('gcs_eye').value);
    var verbal = parseInt(document.getElementById('gcs_verbal').value);
    var motor = parseInt(document.getElementById('gcs_motor').value);
    document.getElementById('gcs_total').value = eye + verbal + motor;
  }

  function close_modal(){

    var gcs_total = parseInt(document.getElementById('gcs_total').value);
    document.getElementById('gcs').value = gcs_total;
  }

  document.getElementById('gcs_eye').addEventListener('change', updateGCSTotal);
  document.getElementById('gcs_verbal').addEventListener('change', updateGCSTotal);
  document.getElementById('gcs_motor').addEventListener('change', updateGCSTotal);

  </script>
</p>