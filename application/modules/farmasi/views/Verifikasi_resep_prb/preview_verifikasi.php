<script>

$(document).ready(function(){
    
    $('#form_verifikasi_dok_klaim').ajaxForm({
      beforeSend: function() {
        achtungShowLoader();  
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status === 200){
          $.achtung({message: jsonResponse.message, timeout:5});
          $('#page-area-content').load('farmasi/Verifikasi_resep_prb/preview_verifikasi/'+$('#kode_trans_far').val()+'?flag=RJ');
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    }); 

})

function hapus_file(a, b)

{

  if(b != 0){
    $.getJSON("<?php echo base_url('posting/delete_file') ?>/" + b, '', function(data) {
        document.getElementById("file"+a).innerHTML = "";
        greatComplate(data);
    });
  }else{
    y = a ;
    document.getElementById("file"+a).innerHTML = "";
  }

}

counterfile = <?php $j=1;echo $j.";";?>

function tambah_file()

{

  counternextfile = counterfile + 1;

  counterIdfile = counterfile + 1;

  document.getElementById("input_file"+counterfile).innerHTML = "<div id=\"file"+counternextfile+"\"><div class='form-group'><label class='col-md-2'>&nbsp;</label><div class='col-md-2'><input type='text' name='pf_file_name[]' id='pf_file_name' class='form-control'></div><label class='control-label col-md-1'>Pilih File</label><div class='col-md-3'><input type='file' id='pf_file' name='pf_file[]' class='upload_file form-control' /></div><div class='col-md-1' style='margin-left:-2.5%'><input type='button' onclick='hapus_file("+counternextfile+",0)' value='x' class='btn btn-sm btn-danger'/></div></div></div><div id=\"input_file"+counternextfile+"\"></div>";

  counterfile++;

}


</script>
<div class="row">

  <div class="col-xs-12">
    <form class="form-horizontal" method="post" id="form_verifikasi_dok_klaim" action="<?php echo site_url('farmasi/Verifikasi_resep_prb/process_upload')?>" enctype="multipart/form-data">
      <!-- breadcrumbs -->
      <div class="page-header">  
        <h1>
          <?php echo $title?>        
          <small> <i class="ace-icon fa fa-angle-double-right"></i> <?php echo isset($breadcrumbs)?$breadcrumbs:''?></small>        
        </h1>
      </div> 

      <center><span style="font-size: 12px;"><strong><u>TRANSAKSI FARMASI</u></strong><br>
      No. PRB-<?php echo $value->kode_trans_far?> - <?php echo $value->no_resep?>
      </span></center>

      <table>
        <tr>
          <td width="100px">Tanggal</td>
          <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($value->tgl_trans) ?></td>
        </tr>
        <tr>
          <td width="100px">Nama Pasien</td>
          <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($value->nama_pasien)?></td>
        </tr>
        <tr>
          <td width="100px">No. MR</td>
          <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $value->no_mr?></td>
        </tr>
        <tr>
          <td width="100px">Dokter</td>
          <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($value->dokter_pengirim)?></td>
        </tr>
        <tr>
          <td width="100px">Unit/Bagian</td>
          <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $value->kode_bagian_asal) )?></td>
        </tr>
      </table>

      <table class="table-utama" style="width: 100% !important;">
        <thead>
            <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">
              <td style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">No</td>
              <td style="border-bottom: 1px solid black; border-collapse: collapse">Nama Obat</td>
              <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Jumlah Tebus</td>
              <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Satuan</td>
              <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Harga Satuan</td>
              <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Subtotal</td>
            </tr>
        </thead>
            <?php 
              $no=0; 
              foreach($resep as $key_dt=>$row_dt) : $no++; 
              $arr_total[] = $row_dt->sub_total;
            ?>

              <tr>
                <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
                <td style="border-collapse: collapse"><?php echo $row_dt->nama_brg?></td>
                <td style="text-align:center; border-collapse: collapse"><?php echo number_format($row_dt->jumlah);?></td>
                <td style="text-align: center; border-collapse: collapse"><?php echo $row_dt->satuan_kecil; ?></td>
                <td style="text-align:right; border-collapse: collapse"><?php echo number_format($row_dt->harga_satuan);?></td>
                <td style="text-align:right; border-collapse: collapse"><?php echo number_format((int)$row_dt->sub_total)?></td>
              </tr>

            <?php endforeach;?>

              <tr>
                <td colspan="5" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Total </td>
                <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_total))?></td>
              </tr>
              <tr>
                <td colspan="7" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
                <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_total)))?> Rupiah"</i></b>
                </td>
              </tr>

      </table>
      <span style="font-size: 14px">Petugas</span> : 
      <?php $decode = json_decode($resep[0]->created_by); echo isset($decode->fullname)?$decode->fullname:$this->session->userdata('user')->fullname;?>

      <hr>
      <b><h4>DOKUMEN KLAIM TAMBAHAN</h4></b>
      <div class="form-group">
        <label class="control-label col-md-2">Nama Dokumen</label>
        <div class="col-md-2">
          <input name="pf_file_name[]" id="pf_file_name" class="form-control" type="text">
        </div>
        <label class="control-label col-md-1">Pilih File</label>
        <div class="col-md-3">
          <input type="file" id="pf_file" name="pf_file[]" class="upload_file form-control"/>
        </div>
        <div class ="col-md-1" style="margin-left:-2.5%">
          <input onClick="tambah_file()" value="+" type="button" class="btn btn-sm btn-info" />
        </div>
      </div>

      <div id="input_file<?php echo $j;?>"></div>
      
      <b><h4>DOKUMEN UPLOAD</h4></b>
      <?php echo $attachment; ?>
      
      <div class="form-group">
          <label class="control-label col-md-2" for="">Catatan restriksi</label>
          <div class="col-sm-4">
            <textarea class="form-control" style="height: 70px !important" name="restriksi"></textarea>
          </div>
      </div>
    
      <!-- input hidden -->
      <input type="hidden" name="no_resep" id="no_resep" value="<?php echo $value->kode_pesan_resep?>">
      <input type="hidden" name="kode_trans_far" id="kode_trans_far" value="<?php echo $value->kode_trans_far?>">
      <input type="hidden" name="no_mr" id="no_mr" value="<?php echo $value->no_mr?>">

      <div class="form-group" style="padding-top: 5px">
          <label class="col-md-2" for="">&nbsp;</label>
          <div class="col-sm-4" style="margin-left: 5px;">
            <a href="<?php echo base_url().'farmasi/Verifikasi_resep_prb/mergePDFFiles/'.$value->kode_trans_far.'/'.$value->no_sep.''?>" target="_blank" id="btn_merge_pdf_files" class="btn btn-xs btn-danger" title="Merge PDF File">
              <i class="fa fa-file dark"></i> Merge PDF Files
            </a>

            <button type="submit" class="btn btn-xs btn-success" title="Nota Farmasi">
                <i class="fa fa-print dark"></i> Proses Upload
            </button>
          </div>
      </div>

      

    </form>
  </div>

</div>
