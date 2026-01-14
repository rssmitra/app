<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attachment_model extends CI_Model {

	var $table = 'web_attachment';
	var $column = array('web_attachment.wa_id','web_attachment.wa_name','web_attachment.wa_owner','web_attachment.wa_name','web_attachment.wa_fullpath','web_attachment.wa_size','web_attachment.wa_type','web_attachment.created_date');
	var $select = 'web_attachment.*';
	var $order = array('web_attachment.created_date' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		$i = 0;
	
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	
	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->_main_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.wa_id',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.wa_id',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function get_attachment_by_params($params)
	{
		$this->db->from('web_attachment');
		$this->db->where('web_attachment.ref_table', $params['ref_table']);
		$this->db->where_in('web_attachment.ref_id', $params['ref_id']);
		return $this->db->get()->result();		
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$get_data = $this->get_by_id($id);
		/*if file images exist*/
		if (file_exists($get_data->wa_fullpath)) {
			$this->delete_attachment_by_id($id);
			/*delete first images file*/
            unlink($get_data->wa_fullpath);			

        }else{
        	return false;
        }
		
	}

	public function delete_attachment_by_id($id)
	{
		$get_data = $this->db->get_where('web_attachment', array('wa_id'=>$id))->row();
		//print_r($get_data->fullpath);die;
		if (file_exists($get_data->wa_fullpath)) {
			unlink($get_data->wa_fullpath);
		}
		$this->db->where('web_attachment.wa_id', $id);
		return $this->db->delete('web_attachment');

	}

	public function delete_attachment_csm_by_id($id)
	{
		$get_data = $this->db->get_where('csm_dokumen_export', array('csm_dex_id'=>$id))->row();
		//print_r($get_data->fullpath);die;
		if (file_exists($get_data->csm_dex_fullpath)) {
			unlink($get_data->csm_dex_fullpath);
		}
		$this->db->where('csm_dokumen_export.csm_dex_id', $id);
		return $this->db->delete('csm_dokumen_export');

	}

	public function delete_attachment_fr_by_id($id)
	{
		$get_data = $this->db->get_where('fr_tc_far_dokumen_klaim_prb', array('dok_prb_id'=>$id))->row();
		//print_r($get_data->fullpath);die;
		if (file_exists($get_data->dok_prb_fullpath)) {
			unlink($get_data->dok_prb_fullpath);
		}
		$this->db->where('fr_tc_far_dokumen_klaim_prb.dok_prb_id', $id);
		return $this->db->delete('fr_tc_far_dokumen_klaim_prb');

	}

	public function get_detail_doc(){

		// get data transaksi kasir
		$result = $this->db->join('tmp_user', 'tmp_user.user_id = tc_trans_kasir.no_induk', 'left')->get_where('tc_trans_kasir', ['no_registrasi' => $_GET['code']])->row();

		$id = isset($result->kode_tc_trans_kasir)?$result->kode_tc_trans_kasir:'';
		$tgl = isset($result->tgl_jam)?$this->tanggal->formatDateTimeFormDmy($result->tgl_jam):'';
		$user = isset($result->fullname)?$result->fullname:'';
		$signed = $result->fullname;
		$status = isset($result->kode_tc_trans_kasir)?'Published':'Deleted';
		$signTitle = 'Petugas Kasir';

		switch ($_GET['flag']) {

			case 'BILL_RJ':
				$title = 'Billing Pasien RJ';
				$noted = isset($result->kode_tc_trans_kasir)?'Billing Pasien Rawat Jalan a.n '.$result->nama_pasien.' ':'Dokumen ini telah dihapus';
			break;

			case 'RESUME_MEDIS':
				$title = 'Resume Medis Pasien';
				$noted = isset($result->kode_tc_trans_kasir)?'Resume Medis Pasien a.n '.$result->nama_pasien.' ':'Dokumen ini telah dihapus';
			break;

			case 'PENGANTAR_FISIOTERAPI':
				$title = 'Surat Pengantar Fisioterapi';
				$noted = isset($result->kode_tc_trans_kasir)?'Surat Pengantar Fisioterapi Pasien a.n '.$result->nama_pasien.' ':'Dokumen ini telah dihapus';
			break;

			case 'FRM_BAST':
				$trans =  $this->db->select('ttd, fr_tc_far.nama_pasien, fr_tc_far.no_mr, ttd, fr_tc_far.created_date, fr_tc_far.created_by, kode_trans_far')->join('mt_master_pasien', 'mt_master_pasien.no_mr = fr_tc_far.no_mr', 'left')->get_where('fr_tc_far', ['kode_trans_far' => $_GET['code']])->row();
				$signed = $trans->nama_pasien;
				$ttd = $trans->ttd;
				$img_base64_encoded = $trans->ttd;
				$imageContent = file_get_contents($img_base64_encoded);
				$path = tempnam(sys_get_temp_dir(), 'prefix');
				file_put_contents ($path, $imageContent);
				$img_ttd = '<img src="' . $ttd . '" width="300px">';

				$tgl = $this->tanggal->formatDateTime($trans->created_date);
				$created_by = json_decode($trans->created_by);
				// print_r($created_by);die;
				$user = $created_by->fullname.' [Petugas Farmasi] ';
				$signTitle = 'Pasien';
				$title = 'Berita Acara Serah Terima Obat';
				$status = isset($trans->kode_trans_far)?'Published':'Deleted';
				$noted = isset($trans->kode_trans_far)?'Berita Acara Serah Terima Obat Pasien a.n '.$trans->nama_pasien.' ':'Dokumen ini telah dihapus';
			break;

			case 'MEMO_INHIBITOR':
				$trans =  $this->db->select('ttd, fr_tc_far.nama_pasien, fr_tc_far.no_mr, fr_tc_far.created_date, fr_tc_far.created_by, kode_trans_far, dokter_pengirim, no_sip')->join('mt_dokter_v', 'mt_dokter_v.kode_dokter = fr_tc_far.kode_dokter', 'left')->get_where('fr_tc_far', ['kode_trans_far' => $_GET['code']])->row();
				$signed = $trans->dokter_pengirim.'<br>SIP. '.$trans->no_sip;
				$ttd = $trans->ttd;
				$img_ttd = '<img src="'.BASE_FILE_RM.'uploaded/ttd/'.$ttd.'" width="50%"><br>';
				// $img_ttd .= '<img src="'.BASE_FILE_RM.'uploaded/ttd/'.$stamp_dr.'" width="700px" style="position: absolute !important">';

				// print_r($img_ttd);die;
				$tgl = $this->tanggal->formatDateTime($trans->created_date);
				$created_by = $trans->dokter_pengirim;
				$user = $trans->dokter_pengirim.' [Dokter DPJP] ';
				$signTitle = 'Dokter DPJP';
				$title = 'MEMO HT & ACE INHIBITOR';
				$status = isset($trans->kode_trans_far)?'Published':'Deleted';
				$noted = isset($trans->kode_trans_far)?'Memo HT & ACE Inhibitor untuk pengambilan Resep Obat Candesartan Pasien a.n '.$trans->nama_pasien.' ':'Dokumen ini telah dihapus';
			break;

			case 'NOTA':
				$trans =  $this->db->select('ttd, fr_tc_far.nama_pasien, fr_tc_far.no_mr, fr_tc_far.created_date, fr_tc_far.created_by, kode_trans_far, dokter_pengirim, no_sip')->join('mt_dokter_v', 'mt_dokter_v.kode_dokter = fr_tc_far.kode_dokter', 'left')->get_where('fr_tc_far', ['kode_trans_far' => $_GET['code']])->row();

				$created_by = json_decode($trans->created_by);
				$signed = $created_by->fullname;
				
				$img_ttd = '';
				// $img_ttd = '<img src="'.BASE_FILE_RM.'uploaded/ttd/'.$ttd.'" width="50%"><br>';
				// print_r($img_ttd);die;
				$tgl = $this->tanggal->formatDateTime($trans->created_date);
				$created_by = json_decode($trans->created_by);
				// print_r($created_by);die;
				$user = $created_by->fullname.' [Petugas Farmasi] ';
				$signTitle = 'Petugas Farmasi';
				$title = 'NOTA FARMASI';
				$status = isset($trans->kode_trans_far)?'Published':'Deleted';
				$noted = isset($trans->kode_trans_far)?'Nota Farmasi Pasien a.n '.$trans->nama_pasien.' ':'Dokumen ini telah dihapus';
			break;

			case 'COPY_RESEP':
				$trans =  $this->db->select('ttd, fr_tc_far.nama_pasien, fr_tc_far.no_mr, fr_tc_far.created_date, fr_tc_far.created_by, kode_trans_far, dokter_pengirim, no_sip, nama_bagian')->join('mt_dokter_v', 'mt_dokter_v.kode_dokter = fr_tc_far.kode_dokter', 'left')->get_where('fr_tc_far', ['kode_trans_far' => $_GET['code']])->row();
				$signed = $trans->dokter_pengirim.'<br>SIP. '.$trans->no_sip;
				$ttd = $trans->ttd;
				
				$img_ttd = '<img src="'.BASE_FILE_RM.'uploaded/ttd/'.$ttd.'" width="50%"><br>';
				// $img_ttd .= '<img src="'.BASE_FILE_RM.'uploaded/ttd/'.$stamp_dr.'" width="700px" style="position: absolute !important">';

				// print_r($img_ttd);die;
				$tgl = $this->tanggal->formatDateTime($trans->created_date);
				$created_by = $trans->dokter_pengirim;
				$user = $trans->dokter_pengirim.'<br>[Dokter DPJP] ';
				$signTitle = 'Dokter DPJP';
				$title = 'COPY RESEP';
				$status = isset($trans->kode_trans_far)?'Published':'Deleted';
				$noted = isset($trans->kode_trans_far)?'Resep Dokter <i>'.$trans->dokter_pengirim.'</i> ('.$trans->nama_bagian.') Pasien a.n '.$trans->nama_pasien.' ':'Dokumen ini telah dihapus';
			break;

			case 'LAB':
				// code = kode penunjang@pm_tc_penunjang
				$trans = $this->Pl_pelayanan_pm->get_by_kode_penunjang($_GET['code'], '050101');
				// echo "<pre>"; print_r($trans);die;
				$signed = $this->master->get_ttd_data('ka_inst_lab', 'label');
				$img_ttd = '';
				$tgl = $this->tanggal->formatDateTime($trans->tgl_masuk);
				$user = $trans->fullname.' [Dokter Pengirim] ';
				$signTitle = 'Ka Inst Laboratorium';
				$title = 'HASIL PEMERIKSAAN LABORATORIUM';
				$status = isset($trans->kode_penunjang)?'Published':'Deleted';
				$noted = isset($trans->kode_penunjang)?'Hasil Pemeriksaan Laboratorium Pasien a.n '.$trans->nama_pasien.' ':'Dokumen ini telah dihapus';
			break;
			
			case 'MCU':
   			 	$trans = $this->db->select('tr.no_registrasi, tr.kode_dokter, tr.created_date, md.nama_pegawai AS dokter_pengirim, md.no_sip, md.ttd, mp.nama_pasien')
        		->from('tc_registrasi tr')
        		->join('mt_karyawan md', 'md.kode_dokter = tr.kode_dokter', 'left')
        		->join('mt_master_pasien mp', 'mp.no_mr = tr.no_mr', 'left')
        		->where('tr.no_registrasi', $_GET['reg'])
        		->get()
        		->row();
				// echo "<pre>"; print_r($trans);die;
    			
				$signed = $trans->dokter_pengirim . '<br><span style="font-size: 9px">SIP. ' . $trans->no_sip.'</span>';
    			$ttd = $trans->ttd;

   				$img_ttd = '<img src="' . BASE_FILE_RM . 'uploaded/ttd/' . $ttd . '" width="35%"><br>';

    			$tgl = $this->tanggal->formatDateTime($trans->created_date);
    			$created_by = $trans->dokter_pengirim;

    			$user = $trans->dokter_pengirim . '<br>[Dokter Pemeriksa] ';
    			$signTitle = 'Dokter Penanggung Jawab';
    			$title = 'HASIL PEMERIKSAAN MCU';

    			$status = isset($trans->no_registrasi) ? 'Published' : 'Deleted';

    			$noted = isset($trans->no_registrasi)
       			? 'Hasil Pemeriksaan MCU oleh ' . $trans->dokter_pengirim . ' untuk Pasien dengan No. Registrasi ' . $trans->no_registrasi . ' a.n. '.$trans->nama_pasien
        		: 'Dokumen ini telah dihapus';
			break;

			case 'RAD':
				$trans = $this->db->select('tr.no_registrasi, tr.kode_dokter, tr.created_date, md.nama_pegawai AS dokter_pengirim, md.no_sip, md.ttd, mp.nama_pasien')
			->from('tc_registrasi tr')
			->join('mt_karyawan md', 'md.kode_dokter = tr.kode_dokter', 'left')
			->join('mt_master_pasien mp', 'mp.no_mr = tr.no_mr', 'left')
			->where('tr.no_registrasi', $_GET['reg'])
			->get()
			->row();
			
				$signed = $trans->dokter_pengirim . '<br>SIP. ' . $trans->no_sip;
				$ttd = $trans->ttd;
			
				$img_ttd = '<img src="' . BASE_FILE_RM . 'uploaded/ttd/' . $ttd . '" width="50%"><br>';
			
				$tgl = $this->tanggal->formatDateTime($trans->created_date);
				$created_by = $trans->dokter_pengirim;
			
				$user = $trans->dokter_pengirim . '<br>[Dokter Pemeriksa] ';
				$signTitle = 'Dokter Penanggung Jawab';
				$title = 'HASIL PEMERIKSAAN RADIOLOGI';
			
				$status = isset($trans->no_registrasi) ? 'Published' : 'Deleted';
			
				$noted = isset($trans->no_registrasi)
					? 'Hasil Pemeriksaan Radiologi oleh <i>' . $trans->dokter_pengirim . '</i> (' . $trans->nama_bagian . ')'
					: 'Dokumen ini telah dihapus';
			
			break;

		}

		$response = [
			'documentName' => $title,
			'ID' => $_GET['code'],
			'createdBy' => $user,
			'createdDate' => $tgl,
			'statusDocument' => $status,
			'noted' => $noted,
			'signedBy' => $signed,
			'signedDate' => $tgl,
			'signTitle' => $signTitle,
			'img_ttd' => $img_ttd,
		];

		return $response;
		
	}

	
}
