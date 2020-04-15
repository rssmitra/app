<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<style>

body, table, p{
  font-family: calibri;
  font-size: 12px;
  background-color: white;
}
.table-utama{
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 2px;
  text-align: left;
}
@media print{ #barPrint{
    display:none;
  }
}


</style>

<body>
  <div style="float: right">
    <button class="tular" onClick="window.close()">Tutup</button>
    <button class="tular" onClick="print()">Cetak</button>
  </div>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr >
				<td width="10%"><IMG SRC="<?php echo base_url()?>uploaded/bakti_husada.gif"  BORDER="0" ALT=""></td>
				<td class="header" style="font: 11px/normal tahoma,verdana;padding:5px;font-weight:bold;">
					<div class="judul1">RL 1.1<BR>DATA DASAR RUMAH SAKIT</div>
				</td>
				<td width="30%" style="padding:5px">
					<IMG SRC="<?php echo base_url()?>uploaded/ditjen_kes.gif"  BORDER="0" ALT="">
				</td>
			</tr>

			
			
		</table>
		<BR>
		<table border="0" cellpadding="0" cellspacing="0">
			<tbody>
				
				<tr class="contentTable">
					<td width="10px" class="border-trbl">1&nbsp;</td>
					<td width="200px" class="border-tb">Nomor Kode RS&nbsp;</td>
					<td width="5px" class="border-tb">:&nbsp;</td>
					<td class="border-trb"><?php echo $result->kode_rs ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">2&nbsp;</td>
					<td class="border-b">Tanggal Registrasi&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->tgl_registrasi ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">3&nbsp;</td>
					<td class="border-b">Nama <?php echo COMP_FLAG; ?> (Huruf Kapital)&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo strtoupper($result->nama_perusahaan) ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">4&nbsp;</td>
					<td class="border-b">Jenis <?php echo COMP_FLAG; ?>&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->jenis_rumah_sakit ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">5&nbsp;</td>
					<td class="border-b">Kelas <?php echo COMP_FLAG; ?>&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->kelas_rumah_sakit ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">6&nbsp;</td>
					<td class="border-b">Nama Direktur RS&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->nama_pimpinan ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">7&nbsp;</td>
					<td class="border-b">Nama Penyelenggara RS&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->penyelenggara_rumah_sakit ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">8&nbsp;</td>
					<td class="border-b">Alamat/Lokasi RS&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->alamat ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">8.1    Kab/Kota &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->kota ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">8.2    Kode Pos &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->kode_pos ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">8.3    Telepon &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->telpon ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">8.4    Fax &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->fax ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">8.5    Email&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->email ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">8.6    Nomor Telp Bag. Umum/Humas RS&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->notelp_humas ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">8.7    Website &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->website ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">9&nbsp;</td>
					<td class="border-b">Luas <?php echo COMP_FLAG; ?> &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb">&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">8.1    Tanah    &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->luas_tanah ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">8.2    Bangunan     &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->luas_bangunan ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td  class="border-rbl">10&nbsp;</td>
					<td  class="border-b">Surat Izin/Penetapan &nbsp;</td>
					<td  class="border-b">:&nbsp;</td>
					<td  class="border-rb"><?php echo $result->surat_izin ?>&nbsp;</td>
				</tr>
				<tr class="contentTable" >
					<td  class="border-rbl">&nbsp;</td>
					<td  class="border-b">10.1   Nomor    &nbsp;</td>
					<td  class="border-b">:&nbsp;</td>
					<td  class="border-rb"><?php echo $result->nomor_izin ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td  class="border-rbl">&nbsp;</td>
					<td  class="border-b">10.2   Tanggal      &nbsp;</td>
					<td   class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->tanggal_izin ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">10.3   Oleh        &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->oleh_izin ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">10.4   Sifat         &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->sifat_izin ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">10.5   Masa Berlaku s/d thn          &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->masa_berlaku ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">11&nbsp;</td>
					<td class="border-b">Status Penyelenggara Swasta *   &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->status_penyelenggara ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">12&nbsp;</td>
					<td class="border-b">Akreditasi RS * &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->akreditas_rs ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">12.1   Pentahapan *&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->pentahapan_akreditas ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">12.2   Status *&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->status_akreditas ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">12.3   Tanggal Akreditasi
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->tanggal_akreditas ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">13&nbsp;</td>
					<td class="border-b">Jumlah Tempat Tidur&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->jumlah_tt ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.1   Perinatalogi&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->perinatologi ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.2   Kelas VVIP &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->kelas_vvip ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.3   Kelas VIP &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->kelas_vip ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.4   Kelas I&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->kelas_i ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.5   Kelas II &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->kelas_ii ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.6   Kelas III &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->kelas_iii ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.7   ICU &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->icu ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.8   PICU  &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->picu ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.9   NICU&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->nicu ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.10  HCU &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->hcu ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.11  ICCU &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->iccu ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.12  Ruang Isolasi &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->ruang_isolasi ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.13  Ruang UGD &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->ruang_ugd ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.14  Ruang Bersalin&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->ruang_bersalin ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">13.15  Ruang Operasi &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $result->ruang_operasi ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">14&nbsp;</td>
					<td class="border-b">Jumlah Tenaga Medis&nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb">&nbsp;</td>
				</tr>
				<?php $no=0; foreach($jumlah_spesialis AS $rowjs) : $no++; if( $rowjs->kode_spesialisasi != NULL ) :?>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">14.<?php echo $no?>   <?php echo $rowjs->nama_spesialisasi ?>   &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $rowjs->total ?> &nbsp;</td>
				</tr>
				<?php endif; endforeach; ?>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">14.21  Perawat &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $jumlah_spesialispwt->total ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">14.22  Bidan &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $jumlah_spesialisbdn->total ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">14.23  Farmasi  &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $jumlah_spesialisfrm->total ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">&nbsp;</td>
					<td class="border-b">14.24  Tenaga Kesehatan Lainnya &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $jumlah_spesialistk->total ?>&nbsp;</td>
				</tr>
				<tr class="contentTable">
					<td class="border-rbl">15&nbsp;</td>
					<td class="border-b">Jumlah Tenaga Non Kesehatan &nbsp;</td>
					<td class="border-b">:&nbsp;</td>
					<td class="border-rb"><?php echo $jumlah_spesialisntk->total ?>&nbsp;</td>
				</tr>
			</tbody>

		</table>
   
</body>
</html>