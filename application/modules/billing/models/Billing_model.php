<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_model extends CI_Model {

	var $table = 'ri_tc_rawatinap';
	var $column = array('ri_tc_rawatinap.nama_pasien','mt_karyawan.nama_pegawai');
    var $select = 'ri_tc_rawatinap.bag_pas,ri_tc_rawatinap.no_kunjungan,mt_master_pasien.nama_pasien, kode_ri, ri_tc_rawatinap.status_pulang, tc_kunjungan.no_mr, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, ri_tc_rawatinap.tgl_masuk, mt_karyawan.nama_pegawai,tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tc_registrasi.kode_perusahaan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.status_keluar, mt_bagian.nama_bagian, ri_tc_rawatinap.dr_merawat, ri_tc_rawatinap.kelas_pas,ri_tc_rawatinap.kelas_titipan, ri_tc_rawatinap.kode_ruangan, mt_klas.nama_klas';
    var $fields = array('bill_kamar_perawatan','bill_kamar_icu','bill_tindakan_inap','bill_tindakan_oksigen','bill_tindakan_bedah','bill_tindakan_vk','bill_obat','bill_ambulance','bill_dokter','bill_apotik','bill_lain_lain','bill_adm','bill_ugd','bill_rad','bill_lab','bill_fisio','bill_klinik','bill_pemakaian_alat', 'bill_tindakan_luar_rs', 'bill_bpako', 'bill_sarana_rs');
	

	var $order = array('ri_tc_rawatinap.no_kunjungan' => 'DESC');

    /*define*/
    var $biaya_materai = 0;

	public function __construct()
	{
		parent::__construct();
	}

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=ri_tc_rawatinap.dr_merawat','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->join('mt_bagian',''.$this->table.'.bag_pas=mt_bagian.kode_bagian','left');
		$this->db->join('mt_master_pasien','tc_kunjungan.no_mr=mt_master_pasien.no_mr','left');
		$this->db->join('mt_klas','mt_klas.kode_klas='.$this->table.'.kelas_pas','left');
		$this->db->where('(ri_tc_rawatinap.status_pulang=0 or ri_tc_rawatinap.status_pulang IS NULL)');

		if( isset($_GET['search_by']) AND $_GET['search_by']=='no_mr' ){
			$this->db->where('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
		}

		if( isset($_GET['search_by']) AND $_GET['search_by']=='nama_pasien'  ){
			$this->db->like('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
		}
		
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,ri_tc_rawatinap.tgl_masuk,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
		}

	
		/*check level user*/
		$this->authuser->filtering_data_by_level_user($this->table, $this->session->userdata('user')->user_id);

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
		//print_r($this->db->last_query());die;
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
			$this->db->where_in(''.$this->table.'.kode_ri',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_ri',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
    }
    
    public function get_by_reg($id)
	{
		$this->db->select('tc_registrasi.no_mr,tc_registrasi.no_registrasi,tc_registrasi.kode_perusahaan,tc_registrasi.kode_dokter,tc_registrasi.tgl_jam_masuk,tc_registrasi.tgl_jam_keluar,tc_registrasi.kode_bagian_masuk,tc_registrasi.kode_bagian_keluar,tc_registrasi.no_sep,tc_registrasi.umur, mt_master_pasien.nama_pasien, mt_master_pasien.jen_kelamin as jk, mt_master_pasien.tgl_lhr, mt_master_pasien.almt_ttp_pasien, mt_master_pasien.tempat_lahir, bagian_masuk.nama_bagian as bagian_masuk_field, bagian_keluar.nama_bagian as bagian_keluar_field, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, mt_master_pasien.title, mt_nasabah.nama_kelompok, tc_registrasi.kode_kelompok');
		$this->db->from('tc_registrasi');
        $this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_registrasi.no_mr', 'left');
		$this->db->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan', 'left');
		$this->db->join('mt_nasabah', 'mt_nasabah.kode_kelompok=tc_registrasi.kode_kelompok', 'left');
        $this->db->join('mt_bagian as bagian_masuk', 'bagian_masuk.kode_bagian=tc_registrasi.kode_bagian_masuk', 'left');
		$this->db->join('mt_bagian as bagian_keluar', 'bagian_keluar.kode_bagian=tc_registrasi.kode_bagian_keluar', 'left');
		$this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter=tc_registrasi.kode_dokter', 'left');
		if(is_array($id)){
			$this->db->where_in('tc_registrasi.no_registrasi',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('tc_registrasi.no_registrasi',$id);
			$query = $this->db->get();
			//echo '<pre>';print_r($this->sqlsrv->last_query());die;
			return $query->row();
		}
	}


	public function update($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function save_pm($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	
	public function delete_by_id($table,$key,$id)
	{
		$this->db->get_where($table, array(''.$key.'' => $id));
		return $this->db->delete($table, array(''.$key.'' => $id));
	}

	public function cek_transaksi_minimal($no_kunjungan){
		$transaksi_min = $this->db->get_where('tc_trans_pelayanan', array('no_kunjungan' => $no_kunjungan) )->num_rows();
		if( $transaksi_min > 0 ){
			return true;
		}else{
			return false;
		}
	}

	public function get_riwayat_pasien_by_id($no_kunjungan){
		return $this->db->get_where('th_riwayat_pasien', array('no_kunjungan' => $no_kunjungan) )->row();
	}

	public function getDetailData($no_registrasi){
        /*get data registrasi*/
        $reg_data = $this->get_by_reg($no_registrasi);
        /*get kasir data*/
        $kasir_data = $this->getKasirData($no_registrasi);
        /*get data trans pelayanan by no registrasi*/
        $trans_data = $this->getTransData($no_registrasi);
        //echo '<pre>';print_r($this->db->last_query());die;
        $group = array();
        foreach ($trans_data as $value) {
            $group[$value->nama_jenis_tindakan][] = $value;
        }
        $result = json_encode(array('group' => $group, 'kasir_data' => $kasir_data, 'no_registrasi' => $no_registrasi, 'trans_data' => $trans_data, 'reg_data' => $reg_data));

        return $result;
    }

    public function getDetailDataa($no_registrasi,$no_mr){
        $kasir_data = $this->getKasirDataa($no_registrasi,$no_mr);
        /*get data trans pelayanan by no registrasi*/
        $group = array();
        foreach ($trans_data as $value) {
            $group[$value->nama_jenis_tindakan][] = $value;
        }
        $result = json_encode(array('group' => $group, 'kasir_data' => $kasir_data, 'no_registrasi' => $no_registrasi, 'trans_data' => $trans_data, 'reg_data' => $reg_data));

        return $result;
    }

	public function checkExistingData($no_registrasi)
    {
        $this->db->from('csm_reg_pasien');
        $this->db->where('no_registrasi', $no_registrasi);
        return $this->db->get()->num_rows();
	}

	public function getDetailBillingRJ($no_registrasi, $tipe, $data){
        /*html data untuk tampilan*/
        // print_r($data);
        $html = '';
        if( count($data->group) > 0 ) :

        $html .= '<b><h3>Rawat Jalan</h3></b>';
        $html .= '<div class="row">';
        $html .= '<div class="col-sm-7">';
        $html .= '<div><h4>Billing Pasien</h4></div>';
        //print_r($data->group);die;
        $html .= '<table class="table table-hover">';
        $html .= '<tr>';
            $html .= '<th width="30px" class="center">No</th>';
            $html .= '<th>Uraian</th>';
            $html .= '<th width="100px" class="center">Subtotal (Rp.)</th>';
        $html .= '</tr>'; 
        $no=1;
        foreach ($data->group as $k => $val) {
            $html .= '<tr>';
            $html .= '<td width="30px" class="center">'.$no.'</td>';
            $html .= '<td width="100px"><b>'.$k.'</b></td>';
            $html .= '<td width="100px" align="right"></td>';
            $html .= '</tr>';
            $no++; 
            foreach ($val as $value_data) {
                $subtotal = (double)$value_data->bill_rs + (double)$value_data->bill_dr1 + (double)$value_data->bill_dr2 + (double)$value_data->lain_lain;
                $html .= '<tr>';
                $html .= '<td width="30px" align="center">'.$value_data->kode_trans_pelayanan.'</td>';
                $html .= '<td width="100px">'.$value_data->nama_tindakan.'</td>';
                $html .= '<td width="100px" align="right">Rp. '.number_format($subtotal).',-</td>';
                $html .= '</tr>';
                /*total*/
                $sum_subtotal[] = $subtotal;
                /*resume billing*/
                $resume_billing[] = $this->Billing->resumeBillingRJ($value_data->jenis_tindakan, $value_data->kode_bagian, $subtotal);
            }        
        }
        $html .= '<tr>';
            $html .= '<td colspan="2" align="right"><b>Total</b></td>';
            $html .= '<td width="100px" align="right"><b>Rp. '.number_format(array_sum($sum_subtotal)).',-</b></td>';
        $html .= '</tr>';   
        $html .= '</table>';

        $html .= '<br>';
        $html .= '<h4>Resume Billing</h4>';
        $html .= '<table class="table table-hover">';
        $html .= '<tr>';
            $html .= '<th align="right">Dokter</th>';
            $html .= '<th align="right">Administrasi</th>';
            $html .= '<th align="right">Obat/Farmasi</th>';
            $html .= '<th align="right">Penunjang Medis</th>';
            $html .= '<th align="right">Tindakan</th>';
            $html .= '<th align="right">BPAKO</th>';
        $html .= '</tr>';
         /*split resume billing*/
        $split_billing = $this->splitResumeBilling($resume_billing);

        $bill_dr    = isset($split_billing['bill_dr'])?$split_billing['bill_dr']:0;
        $bill_adm_rs    = isset($split_billing['bill_adm_rs'])?$split_billing['bill_adm_rs']:0;
        $bill_farm  = isset($split_billing['bill_farm'])?$split_billing['bill_farm']:0;
        $bill_pm    = isset($split_billing['bill_pm'])?$split_billing['bill_pm']:0;
        $bill_tindakan  = isset($split_billing['bill_tindakan'])?$split_billing['bill_tindakan']:0;
        $bill_bpako     = isset($split_billing['bill_bpako'])?$split_billing['bill_bpako']:0;
        
        $html .= '<tr>';
            $html .= '<td align="right">Rp. '.number_format($bill_dr).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($bill_adm_rs).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($bill_farm).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($bill_pm).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($bill_tindakan).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($bill_bpako).',-</td>';
        $html .= '</tr>'; 
        $html .= '<tr>';
            $html .= '<td align="right" colspan="5"><b>Total</b></td>';
            $total_billing = (double)$bill_dr + (double)$bill_adm_rs + (double)$bill_farm + (double)$bill_pm + (double)$bill_tindakan+ (double)$bill_bpako; 
            $html .= '<td align="right"><b>Rp. '.number_format($total_billing).',-</b></td>';
        $html .= '</tr>';
        $html .= '</table>'; 
        $html .= '</div>';

        $html .= '<div class="col-sm-5">';
            $html .= '<div><h4>Resume Pasien</h4></div>';
                $html .= '<table class="table" style="background-color: white">';  
                $html .= '<tr>';
                    $html .= '<th width="30px">No</th>';
                    $html .= '<th width="100px">Kode</th>';
                    $html .= '<th>Deskripsi Resume</th>';
                    $html .= '<th>Jenis</th>';
                $html .= '</tr>';
                /*Billing RS*/
                foreach ($data->kasir_data as $key_kasir_data => $val_kasir_data) {
                    $no=1;
                    $html .= '<tr>';
                    $html .= '<td>'.$no.'</td>';
                    $html .= '<td>'.$val_kasir_data->seri_kuitansi.'-'.$val_kasir_data->kode_tc_trans_kasir.'</td>';
                    $html .= '<td><a href="'.base_url().'Templates/Export_data/export?type=pdf&flag=RJ&noreg='.$no_registrasi.'" target="_blank" >Billing Pasien Rawat Jalan</a></td>';
                    $html .= '<td>Billing</td>';
                    $html .= '</tr>';
                    $no++;
                }
        $cont_no = $no;
        /*Hasil penunjang medis*/
        /*grouping document pm*/
        $grouping_doc = $this->groupingDocumentPM($data->group);
        foreach ($grouping_doc['grouping_dokumen'] as $key_group => $val_group) {
            $explode_key = explode('-',$key_group);
            $offset_kode_penunjang = $explode_key[0];
            $offset_kode_bagian = $explode_key[1];
            $offset_nama_pm = $explode_key[2];
            /*convert arr tindakan to string*/
            $convert_to_string_tindakan = implode(' / ', $grouping_doc['grouping_tindakan'][$offset_kode_penunjang]);
            if($offset_kode_bagian == '050101'){
                $flag = 'LAB';
            }elseif ($offset_kode_bagian == '050201') {
                $flag = 'RAD';
            }elseif ($offset_kode_bagian == '050301') {
                $flag = 'FSO';
            }else{
                $flag = 0;
            }
            $html .= '<tr>';
            $html .= '<td>'.$cont_no.'</td>';
            $html .= '<td width="50px"><a href="'.base_url().'Templates/Export_data/export?type=pdf&flag='.$flag.'&noreg='.$no_registrasi.'&pm='.$offset_kode_penunjang.'&kode_pm='.$offset_kode_bagian.'" target="blank" >'.$offset_kode_penunjang.'</a></td>';
            $html .= '<td>'.$offset_nama_pm.' ( '.$convert_to_string_tindakan.' ) </td>';
            $html .= '<td>Hasil Penunjang Medis</td>';
            $html .= '</tr>';
            
            $cont_no++;
        }

        $html .= '</table>';
        $html .= '<p class="center">';

        $html .= '<b>Klik Tombol Dibawah Ini !</b><br><blink><i class="fa fa-angle-double-down bigger-300"></i></blink><br><br>';
            $html .= '<a href="#" onclick="update_status_nk_kode_perusahaan('.$no_registrasi.')" class="btn btn-sm btn-primary">Update Status NK dan Kode Perusahaan</a>';

            /*$html .= '<a href="#" onclick="submit_kasir('.$no_registrasi.')" class="btn btn-sm btn-primary">Submit Kasir</a>';*/

            $html .= '<br><br> Silahkan klik tombol diatas jika terdapat item tindakan yang tidak muncul';
            $html .= '</p>';

        /*$html .= '<br>';
        $link = 'casemix/Billing';
        $html .= '<a href="#" onclick="getMenu('."'".$link.'/editBilling/'.$no_registrasi.''."/RJ'".')" class="btn btn-xs btn-success"><i class="fa fa-edit bigger-50"></i> Edit Billing</a> ';
        $html .= '<a href="#" class="btn btn-xs btn-primary"><i class="fa fa-send bigger-50"></i> Submit</a> ';
        $html .= '<a href="#" class="btn btn-xs btn-danger"><i class="fa fa-file-pdf-o bigger-50"></i> Merge PDF Files</a> ';*/
        $html .= '</div>';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';

        else:
            $trans_data_original = json_decode($this->getOriginalTransData($no_registrasi));
            $html .= '<div class="center"><br><p style="color:red;font-weight:bold"><b> PASIEN BELUM DIPULANGKAN DAN ATAU BELUM DISUBMIT KASIR</b></p></div>';
            $html .= '<div class="row">';
            $html .= '<div class="col-md-12">';
            $html .= '<table class="table" style="background-color: white">';
            $html .= '<tr>';
                $html .= '<th width="30px" class="center">No</th>';
                $html .= '<th>Uraian</th>';
                $html .= '<th width="100px" class="center">Submit Kasir</th>';
                $html .= '<th width="120px" class="center">Status NK</th>';
                $html .= '<th width="120px" class="center">Kode Perusahaan</th>';
                $html .= '<th width="120px" class="right">Subtotal (Rp.)</th>';
            $html .= '</tr>'; 
            $no=1;
            foreach ($trans_data_original->group as $k => $val) {
                $html .= '<tr>';
                $html .= '<td width="30px" class="center">'.$no.'</td>';
                $html .= '<td width="100px" colspan="4"><b>'.$k.'</b></td>';
                $html .= '<td width="100px" align="right"></td>';
                $html .= '</tr>';
                $no++; 
                foreach ($val as $value_data) {
                    $subtotal = (double)$value_data->bill_rs + (double)$value_data->bill_dr1 + (double)$value_data->bill_dr2 + (double)$value_data->lain_lain;
                    $html .= '<tr>';
                    $html .= '<td width="30px" align="center">'.$value_data->kode_trans_pelayanan.'</td>';
                    $html .= '<td>'.$value_data->nama_tindakan.'</td>';
                    $sign_submit_kasir = ($value_data->kode_tc_trans_kasir)?'<i class="fa fa-check-circle green"> </i>':'<span style="color:red"><i class="fa fa-times-circle red"></i></span>';
                    $html .= '<td width="100px" class="center">'.$sign_submit_kasir.'</td>';
                    $sign_status_nk = ($value_data->status_nk)?'<i class="fa fa-check-circle green"> </i>':'<span style="color:red"><i class="fa fa-times-circle red"></i></span>';
                    $html .= '<td width="100px" class="center">'.$sign_status_nk.'</td>';
                    $sign_penjamin = ($value_data->kode_perusahaan==120)?'<i class="fa fa-check-circle green"></i>':'<span style="color:red"><i class="fa fa-times-circle red"></i></span>';
                    $html .= '<td width="100px" class="center">'.$sign_penjamin.'</td>';
                    $html .= '<td width="100px" align="right">Rp. '.number_format($subtotal).',-</td>';
                    $html .= '</tr>';
                    /*total*/
                    $sum_subtotal[] = $subtotal;
                    /*resume billing*/
                    $resume_billing[] = $this->Billing->resumeBillingRJ($value_data->jenis_tindakan, $value_data->kode_bagian, $subtotal);
                }        
            }   
            $html .= '</table>';
            $html .= '</div>';
            $html .= '<div class="col-md-9">';
             $html .= '<table class="table" style="background-color: white">';
            $html .= '<tr>';
                $html .= '<th width="100px">&nbsp;</th>';
                $html .= '<th>Submit Kasir</th>';
                $html .= '<th>Status NK</th>';
                $html .= '<th>Kode Perusahaan</th>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<td> <i class="fa fa-times-circle red"></i> Permasalahan</td>';
                $html .= '<td>Kasir belum melakukan submit pada aplikasi '.APPS_NAME_SORT.'</td>';
                $html .= '<td>Nota Kredit pasien tidak tercata disistem, karena masih ada kendala pada aplikasi</td>';
                $html .= '<td>Pasien terdaftar bukan sebagai pasien BPJS karena kesalahan input pada petugas registrasi</td>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<td><i class="fa fa-check-circle green"></i> Solusi</td>';
                $html .= '<td> Kasir harus melakukan submit terlebih dahulu sebelum melanjutkan ke proses Submit dan Merge PDF Files</td>';
                $html .= '<td>Silahkan klik tombol disamping untuk mengupdate Status NK</td>';
                $html .= '<td>Pasien harus diubah data penjaminnya pada aplikasi '.APPS_NAME_SORT.' atau Klik Button disamping</td>';
            $html .= '</tr>';
            $html .= '</table>';
            $html .= '</div>';
            $reg_data = $data->reg_data;
            if( $reg_data->kode_perusahaan == 120 ) :
                $html .= '<div class="col-md-3 center">';
                $html .= '<b>Silahkan Klik Tombol Dibawah Ini !</b><br><blink><i class="fa fa-angle-double-down bigger-300"></i></blink><br><br>';
                $html .= '<a href="#" onclick="update_status_nk_kode_perusahaan('.$no_registrasi.')" class="btn btn-sm btn-primary">Update NK & Kode Perusahaan</a>';
                $html .= '</div>';
            endif;

            $html .= '</div>';
            $html .= '</div>';

        endif;
        return $html;
    }
	
    public function getDetailBillingRJLess($no_registrasi, $tipe, $data){
        /*html data untuk tampilan*/
        // print_r($data);
        $html = '';
        if( count($data->group) > 0 ) :

        $html .= '<center><b>RESUME BILLING PASIEN RAWAT JALAN</b></center>';
        $html .= '<div class="row">';
        $html .= '<div class="col-sm-12">';
        $no=1;
        foreach ($data->group as $k => $val) {
            $no++; 
            foreach ($val as $value_data) {
                if( empty($value_data->kode_tc_trans_kasir) ){
                    $subtotal = (double)$value_data->bill_rs + (double)$value_data->bill_dr1 + (double)$value_data->bill_dr2 + (double)$value_data->lain_lain;
                    /*total*/
                    $sum_subtotal[] = $subtotal;
                    /*resume billing*/
                    $resume_billing[] = $this->Billing->resumeBillingRJ($value_data->jenis_tindakan, $value_data->kode_bagian, $subtotal);
                }
            }        
        }

        $html .= '<table class="table" style="background-color: white">';
        $html .= '<tr>';
            $html .= '<th align="right">Dokter</th>';
            $html .= '<th align="right">Adm</th>';
            $html .= '<th align="right">Obat/Farmasi</th>';
            $html .= '<th align="right">Penunjang</th>';
            $html .= '<th align="right">Tindakan</th>';
            $html .= '<th align="right">BPAKO</th>';
        $html .= '</tr>';
         /*split resume billing*/
        $split_billing = $this->splitResumeBilling($resume_billing);

        $bill_dr    = isset($split_billing['bill_dr'])?$split_billing['bill_dr']:0;
        $bill_adm_rs    = isset($split_billing['bill_adm_rs'])?$split_billing['bill_adm_rs']:0;
        $bill_farm  = isset($split_billing['bill_farm'])?$split_billing['bill_farm']:0;
        $bill_pm    = isset($split_billing['bill_pm'])?$split_billing['bill_pm']:0;
        $bill_tindakan  = isset($split_billing['bill_tindakan'])?$split_billing['bill_tindakan']:0;
        $bill_bpako     = isset($split_billing['bill_bpako'])?$split_billing['bill_bpako']:0;
        
        $html .= '<tr>';
            $html .= '<td align="right">Rp. '.number_format($bill_dr).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($bill_adm_rs).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($bill_farm).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($bill_pm).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($bill_tindakan).',-</td>';
            $html .= '<td align="right">Rp. '.number_format($bill_bpako).',-</td>';
        $html .= '</tr>'; 
        $html .= '<tr>';
            $html .= '<td align="left" colspan="4"><b>Total Biaya Keseluruhan</b></td>';
            $total_billing = (double)$bill_dr + (double)$bill_adm_rs + (double)$bill_farm + (double)$bill_pm + (double)$bill_tindakan+ (double)$bill_bpako; 
            $html .= '<td align="right" style="font-size: 14px; font-weight: bold" colspan="2"><b>Rp. '.number_format($total_billing).',-</b></td>';
        $html .= '</tr>';
        $html .= '</table>'; 
        $html .= '</div>';


        else:
            $trans_data_original = json_decode($this->getOriginalTransData($no_registrasi));
            $html .= '<div class="center"><br><p style="color:red;font-weight:bold"><b> PASIEN BELUM DIPULANGKAN DAN ATAU BELUM DISUBMIT KASIR</b></p></div>';
            $html .= '<div class="row">';
            $html .= '<div class="col-md-12">';
            $html .= '<table class="table" style="background-color: white">';
            $html .= '<tr>';
                $html .= '<th width="30px" class="center">No</th>';
                $html .= '<th>Uraian</th>';
                $html .= '<th width="100px" class="center">Submit Kasir</th>';
                $html .= '<th width="120px" class="center">Status NK</th>';
                $html .= '<th width="120px" class="center">Kode Perusahaan</th>';
                $html .= '<th width="120px" class="right">Subtotal (Rp.)</th>';
            $html .= '</tr>'; 
            $no=1;
            foreach ($trans_data_original->group as $k => $val) {
                $html .= '<tr>';
                $html .= '<td width="30px" class="center">'.$no.'</td>';
                $html .= '<td width="100px" colspan="4"><b>'.$k.'</b></td>';
                $html .= '<td width="100px" align="right"></td>';
                $html .= '</tr>';
                $no++; 
                foreach ($val as $value_data) {
                    $subtotal = $this->get_total_tagihan($value_data);
                    $html .= '<tr>';
                    $html .= '<td width="30px" align="center">'.$value_data->kode_trans_pelayanan.'</td>';
                    $html .= '<td>'.$value_data->nama_tindakan.'</td>';
                    $sign_submit_kasir = ($value_data->kode_tc_trans_kasir)?'<i class="fa fa-check-circle green"> </i>':'<span style="color:red"><i class="fa fa-times-circle red"></i></span>';
                    $html .= '<td width="100px" class="center">'.$sign_submit_kasir.'</td>';
                    $sign_status_nk = ($value_data->status_nk)?'<i class="fa fa-check-circle green"> </i>':'<span style="color:red"><i class="fa fa-times-circle red"></i></span>';
                    $html .= '<td width="100px" class="center">'.$sign_status_nk.'</td>';
                    $sign_penjamin = ($value_data->kode_perusahaan==120)?'<i class="fa fa-check-circle green"></i>':'<span style="color:red"><i class="fa fa-times-circle red"></i></span>';
                    $html .= '<td width="100px" class="center">'.$sign_penjamin.'</td>';
                    $html .= '<td width="100px" align="right">Rp. '.number_format($subtotal).',-</td>';
                    $html .= '</tr>';
                    /*total*/
                    $sum_subtotal[] = $subtotal;
                    /*resume billing*/
                    $resume_billing[] = $this->Billing->resumeBillingRJ($value_data->jenis_tindakan, $value_data->kode_bagian, $subtotal);
                }        
            }   
            $html .= '</table>';
            $html .= '</div>';
            $html .= '<div class="col-md-9">';
             $html .= '<table class="table" style="background-color: white">';
            $html .= '<tr>';
                $html .= '<th width="100px">&nbsp;</th>';
                $html .= '<th>Submit Kasir</th>';
                $html .= '<th>Status NK</th>';
                $html .= '<th>Kode Perusahaan</th>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<td> <i class="fa fa-times-circle red"></i> Permasalahan</td>';
                $html .= '<td>Kasir belum melakukan submit pada aplikasi '.APPS_NAME_SORT.'</td>';
                $html .= '<td>Nota Kredit pasien tidak tercata disistem, karena masih ada kendala pada aplikasi</td>';
                $html .= '<td>Pasien terdaftar bukan sebagai pasien BPJS karena kesalahan input pada petugas registrasi</td>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<td><i class="fa fa-check-circle green"></i> Solusi</td>';
                $html .= '<td> Kasir harus melakukan submit terlebih dahulu sebelum melanjutkan ke proses Submit dan Merge PDF Files</td>';
                $html .= '<td>Silahkan klik tombol disamping untuk mengupdate Status NK</td>';
                $html .= '<td>Pasien harus diubah data penjaminnya pada aplikasi '.APPS_NAME_SORT.' atau Klik Button disamping</td>';
            $html .= '</tr>';
            $html .= '</table>';
            $html .= '</div>';
            $reg_data = $data->reg_data;
            if( $reg_data->kode_perusahaan == 120 ) :
                $html .= '<div class="col-md-3 center">';
                $html .= '<b>Silahkan Klik Tombol Dibawah Ini !</b><br><blink><i class="fa fa-angle-double-down bigger-300"></i></blink><br><br>';
                $html .= '<a href="#" onclick="update_status_nk_kode_perusahaan('.$no_registrasi.')" class="btn btn-sm btn-primary">Update NK & Kode Perusahaan</a>';
                $html .= '</div>';
            endif;

            $html .= '</div>';
            $html .= '</div>';

        endif;
        return $html;
    }

	public function getDetailBillingRI($no_registrasi, $tipe, $data){
        /*html data untuk tampilan*/
        $dataRI = $this->getDataRI($no_registrasi);
        //print_r($this->db->last_query());die;
        $no=1;
        //print_r($data->group);die;
        foreach ($data->group as $k => $val) {
            foreach ($val as $value_data) {
                $subtotal = $this->Billing->get_total_tagihan($value_data);
                $resume_billing[] = $this->Billing->resumeBillingRI($value_data);
            }        
        }
        

        $html = '<div class="row">';
        $html .= '<div class="col-md-12">';

        $html .= '<b><h3>Rawat Inap</h3></b>';
        $html .= '<table class="table" style="background-color: white">';
        $html .= '<tr>';
            $html .= '<th>Umur</th>';
            $html .= '<th>Ruangan</th>';
            $html .= '<th>Kelas</th>';
            $html .= '<th>Nasabah</th>';
            $html .= '<th>Perusahaan</th>';
        $html .= '</tr>';
        $html .= '<tr>';
            $html .= '<td>'.$data->reg_data->umur.' Tahun</td>';
            $html .= '<td>'.$dataRI->nama_bagian.'</td>';
            $html .= '<td>'.$dataRI->nama_klas.'</td>';
            $html .= '<td>Jaminan Perusahaan</td>';
            $html .= '<td>BPJS Kesehatan</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</br>';
       
        //print_r($resume_billing);die;

        if( isset($resume_billing) ) :
        /*split resume billing*/
        $split_billing = $this->splitResumeBillingRI($resume_billing);
        
         /*lampiran dokumen*/
        $html .= '<div class="col-sm-12">';
        $html .= '<div class="center"><p><b>DOKUMEN LAMPIRAN HASIL PENUNJANG MEDIS DAN BILLING PASIEN</b></p></div>';
        $html .= '<table class="table" style="background-color: white" width="60%">';
        $html .= '<tr>';
            $html .= '<th width="30px">No</th>';
            $html .= '<th width="50px">Kode</th>';
            $html .= '<th width="120px">Nama Dokumen Lampiran</th>';
            $html .= '<th width="100px">Jenis</th>';
        $html .= '</tr>';

        /*Billing RS*/
        foreach ($data->kasir_data as $key_kasir_data => $val_kasir_data) {
            $no=1;
            $html .= '<tr>';
            $html .= '<td width="30px">'.$no.'</td>';
            $html .= '<td width="50px">'.$val_kasir_data->seri_kuitansi.'-'.$val_kasir_data->kode_tc_trans_kasir.'</td>';
            $html .= '<td><a href="'.base_url().'Templates/Export_data/export?type=pdf&flag=RI&noreg='.$no_registrasi.'&no_kunjungan='.$dataRI->no_kunjungan.'" target="_blank" >Rincian Biaya Keseluruhan Pasien Rawat Inap </a></td>';
            $html .= '<td>Billing Kasir</td>';
            $html .= '</tr>';
            $no++;
        }
        $cont_no = $no;
        /*Hasil penunjang medis*/
        /*grouping document pm*/
        $grouping_doc = $this->groupingDocumentPM($data->group);
        // print_r($grouping_doc);die;
        foreach ($grouping_doc['grouping_dokumen'] as $key_group => $val_group) {
            $explode_key = explode('-',$key_group);
            $offset_kode_penunjang = $explode_key[0];
            $offset_kode_bagian = $explode_key[1];
            $offset_nama_pm = $explode_key[2];
            /*convert arr tindakan to string*/
            $convert_to_string_tindakan = implode(' / ', $grouping_doc['grouping_tindakan'][$offset_kode_penunjang]);
            if($offset_kode_bagian == '050101'){
                $flag = 'LAB';
            }elseif ($offset_kode_bagian == '050201') {
                $flag = 'RAD';
            }elseif ($offset_kode_bagian == '050301') {
                $flag = 'FSO';
            }else{
                $flag = 0;
            }

            $html .= '<tr>';
            $html .= '<td>'.$cont_no.'</td>';
            $html .= '<td width="50px"><a href="'.base_url().'Templates/Export_data/export?type=pdf&flag='.$flag.'&noreg='.$no_registrasi.'&pm='.$offset_kode_penunjang.'&kode_pm='.$offset_kode_bagian.'&no_kunjungan='.$val_group[0]['no_kunjungan'].'" target="blank" >'.$offset_kode_penunjang.'</a></td>';
            $html .= '<td>'.$offset_nama_pm.' ( '.$convert_to_string_tindakan.' ) </td>';
            $html .= '<td>Hasil Penunjang Medis</td>';
            $html .= '</tr>';
            
            $cont_no++;
        }
        $html .= '</table>';
        $html .= '</div>';
        /*rincian billing*/
        $html .= '<div class="col-sm-4">';
        $html .= '<br>';
        $html .= '<div class="center" style="background-color: white"><p><b>RINCIAN BIAYA KESELURUHAN PASIEN RAWAT INAP</b></p></div>';
        $html .= '<table class="table" style="background-color: white">';
        $html .= '<tr>';
            $html .= '<th width="30px" class="center">No</th>';
            $html .= '<th>Uraian</th>';
            $html .= '<th width="100px" class="center">Subtotal (Rp.)</th>';
        $html .= '</tr>'; 
        $no=1;
        //echo '<pre>';print_r($split_billing);die;
        foreach ($split_billing as $k => $val) {
            /*total*/
            if((int)$val['subtotal'] > 0){
                $sum_subtotal[] = $val['subtotal'];
                $html .= '<tr>';
                $html .= '<td width="30px" class="center">'.$no.'</td>';
                $html .= '<td width="100px"><a href="#" onclick="getBillingDetail('.$no_registrasi.','."'".$tipe."'".','."'".$val['field']."'".')">'.$val['title'].'</a></td>';
                $html .= '<td width="100px" align="right">'.number_format($val['subtotal']).'</td>';
                $html .= '</tr>';
                $no++;
            }
                 
        }
            /*biaya Administrasi*/
            // $adm = array_sum($sum_subtotal) * 0.05;
            // $total_plus_adm = array_sum($sum_subtotal) + (array_sum($sum_subtotal) * 0.05);
            // $html .= '<tr>';
            //         $html .= '<td width="30px" class="center">'.$no.'</td>';
            //         $html .= '<td width="100px">Administrasi</td>';
            //         $html .= '<td width="100px" align="right">'.number_format($adm).',-</td>';
            //         $html .= '</tr>';
            // $html .= '<tr>';
            // $no++;
            /*biaya materai*/
             /*$html .= '<tr>';
                    $html .= '<td width="30px" class="center">'.$no.'</td>';
                    $html .= '<td width="100px">Materai</td>';
                    $html .= '<td width="100px" align="right">'.number_format($this->biaya_materai).'</td>';
            $html .= '</tr>';*/
            $html .= '<tr>';
            /*total plus materai*/
            $total_plus_materai = array_sum($sum_subtotal) + $this->biaya_materai;
            $html .= '<td align="right"><b>Total</b></td>';
            $html .= '<td colspan="2" width="100px" align="right"><b>Rp. '.number_format($total_plus_materai).',-</b></td>';
        $html .= '</tr>';   
        $html .= '</table>';
        $html .= '<br>';
            
        $html .= '</div>';

        $html .= '<div class="col-sm-8">';
            $html .= '<div id="detail_item_billing_'.$no_registrasi.'">';
            $html .= '</div>';
        $html .= '</div>';

        else :
            $html .= '<div class="center"><p style="color:red;font-weight:bold"><b>PASIEN BELUM DIPROSES OLEH ADM PASIEN</b></p></div>';
            $html .= '<p class="center">';

            $html .= '<b>Klik Tombol Dibawah Ini !</b><br><blink><i class="fa fa-angle-double-down bigger-300"></i></blink><br><br>';
            $html .= '<a href="#" onclick="update_status_nk_kode_perusahaan('.$no_registrasi.')" class="btn btn-sm btn-primary">Update Status NK dan Kode Perusahaan</a>';
            $html .= '<br><br> Silahkan klik tombol diatas jika terdapat item tindakan yang tidak muncul';
            $html .= '</p>';

        endif;

        $html .= '</div>';
        $html .= '</div>';

        return $html;
	}

    public function getDetailBillingRILess($no_registrasi, $tipe, $data){
        /*html data untuk tampilan*/
        $dataRI = $this->getDataRI($no_registrasi);
        //print_r($this->db->last_query());die;
        $no=1;
        //print_r($data->group);die;
        foreach ($data->group as $k => $val) {
            foreach ($val as $value_data) {
                $subtotal = $this->Billing->get_total_tagihan($value_data);
                $resume_billing[] = $this->Billing->resumeBillingRI($value_data);
            }        
        }
        

        $html = '<div class="col-md-12 no-padding">';
       
        //print_r($resume_billing);die;

        if( isset($resume_billing) ) :
        /*split resume billing*/
        $split_billing = $this->splitResumeBillingRI($resume_billing);
        
        $cont_no = $no;
        
        /*rincian billing*/
        $html .= '<div class="col-sm-12">';
        $html .= '<center><b>RINCIAN BIAYA KESELURUHAN PASIEN RAWAT INAP</b></center>';
        $html .= '<table style="background-color: white; width: 100%; padding-top: 2px">';
        $html .= '<tr>';
            // $html .= '<th width="10px" class="center">No</th>';
            $html .= '<th>Uraian</th>';
            $html .= '<th width="100px" class="right">Subtotal (Rp.)</th>';
        $html .= '</tr>'; 
        $no=1;
        //echo '<pre>';print_r($split_billing);die;
        foreach ($split_billing as $k => $val) {
            /*total*/
            if((int)$val['subtotal'] > 0){
                $sum_subtotal[] = $val['subtotal'];
                $html .= '<tr>';
                // $html .= '<td width="10px" class="center">'.$no.'</td>';
                $html .= '<td width="80%"><a href="#" onclick="show_modal_medium_return_json('."'billing/Billing/getRincianBilling/".$no_registrasi."/".$tipe."/".$val['field']."'".', '."'".$val['title']."'".')">'.$val['title'].'</a></td>';
                $html .= '<td width="20%" align="right">'.number_format($val['subtotal']).'</td>';
                $html .= '</tr>';
                $no++;
            }
                 
        }
          
        $html .= '<tr>';
            /*total plus materai*/
            $total_plus_materai = array_sum($sum_subtotal) + $this->biaya_materai;
            $html .= '<td align="left" width="80%"><b>Total Biaya Keseluruhan</b></td>';
            $html .= '<td colspan="2" width="20%" align="right"><b>Rp. '.number_format($total_plus_materai).',-</b></td>';
        $html .= '</tr>';   
        $html .= '</table>';
        $html .= '<br>';
            
        $html .= '</div>';

        $html .= '<div class="col-sm-8">';
            $html .= '<div id="detail_item_billing_'.$no_registrasi.'">';
            $html .= '</div>';
        $html .= '</div>';

        else :
            $html .= '<div class="center"><p style="color:red;font-weight:bold"><b>PASIEN BELUM DIPROSES OLEH ADM PASIEN</b></p></div>';
            $html .= '<p class="center">';

            $html .= '<b>Klik Tombol Dibawah Ini !</b><br><blink><i class="fa fa-angle-double-down bigger-300"></i></blink><br><br>';
            $html .= '<a href="#" onclick="update_status_nk_kode_perusahaan('.$no_registrasi.')" class="btn btn-sm btn-primary">Update Status NK dan Kode Perusahaan</a>';
            $html .= '<br><br> Silahkan klik tombol diatas jika terdapat item tindakan yang tidak muncul';
            $html .= '</p>';

        endif;

        $html .= '</div>';

        return $html;
    }
	
	public function getDataRI($no_registrasi){
        $this->db->from('ri_tc_rawatinap');
        $this->db->join('mt_ruangan','mt_ruangan.kode_ruangan=ri_tc_rawatinap.kode_ruangan','left');
        $this->db->join('mt_klas','mt_klas.kode_klas=ri_tc_rawatinap.kelas_pas','left');
        $this->db->join('mt_bagian','mt_bagian.kode_bagian=mt_ruangan.kode_bagian','left');
        $this->db->where("ri_tc_rawatinap.no_kunjungan IN (SELECT no_kunjungan FROM tc_kunjungan WHERE no_registrasi = ".$no_registrasi." and kode_bagian_tujuan like '03%' and kode_bagian_tujuan != '030001')");
        
        return $this->db->get()->row();
	}
	
	public function resumeBillingRI($data){
        // echo '<pre>';print_r($data);die;
        /*subtotal*/
        $subtotal = $this->Billing->get_total_tagihan($data);
        /*kode str tarif*/
        $str_type = substr((string)$data->kode_bagian, 0,2);
        /*fields billing*/
        $fields = $this->fields;

        /*kamar perawatan / ruangan / ICU all*/
        if (in_array($data->jenis_tindakan, array(1))) {
            if (strpos($data->nama_tindakan, 'Ruangan') !== false) {
                if (strpos($data->nama_tindakan, 'Ruangan ICU') !== false) {
                    $bill['bill_kamar_icu'] = $subtotal;
                    $kode_trans_pelayanan['bill_kamar_icu'] = $data->kode_trans_pelayanan;
                }else{
                    $bill['bill_kamar_perawatan'] = $subtotal;
                    $kode_trans_pelayanan['bill_kamar_perawatan'] = $data->kode_trans_pelayanan;
                }
            }
        }

        /*biaya administrasi all*/
        if ( in_array($data->jenis_tindakan, array(2)) ) {
            $bill['bill_adm'] = $subtotal;
            $kode_trans_pelayanan['bill_adm'] = $data->kode_trans_pelayanan;
        }

        /*tindakan dan konsultasi inap / HD / Poliklinik / UGD*/
        if ( in_array($data->jenis_tindakan, array(3, 12)) ) {
            if( $str_type == '03'){
                if(!in_array($data->kode_bagian, array('030501','030901'))){
                    $bill['bill_tindakan_inap'] = $subtotal;
                    $kode_trans_pelayanan['bill_tindakan_inap'] = $data->kode_trans_pelayanan;
                }
            }elseif( $str_type == '01'){
                if(strpos($data->nama_tindakan, 'Hemodialis') !== false){
                    $bill['bill_tindakan_hd'] = $subtotal;
                    $kode_trans_pelayanan['bill_tindakan_hd'] = $data->kode_trans_pelayanan;
                }else{
                    $bill['bill_klinik'] = $subtotal;
                    $kode_trans_pelayanan['bill_klinik'] = $data->kode_trans_pelayanan;
                }
            }elseif( $str_type == '02'){
                $bill['bill_ugd'] = $subtotal;
                $kode_trans_pelayanan['bill_ugd'] = $data->kode_trans_pelayanan;
            }
        }

        /*jasa dokter/bidan hanya untuk rawat inap*/
        if ( in_array($data->jenis_tindakan, array(4)) ) {
            if( $str_type == '03' ){
                $bill['bill_dokter'] = $subtotal;
                $kode_trans_pelayanan['bill_dokter'] = $data->kode_trans_pelayanan;
            }
        }

        /*biaya pemakaian alat all*/
        if ( in_array($data->jenis_tindakan, array(5)) ) {
            $bill['bill_pemakaian_alat'] = $subtotal;
            $kode_trans_pelayanan['bill_pemakaian_alat'] = $data->kode_trans_pelayanan;
        }

        /*ambulance all*/
        if ( in_array($data->jenis_tindakan, array(6)) ) {
            $bill['bill_ambulance'] = $subtotal;
            $kode_trans_pelayanan['bill_ambulance'] = $data->kode_trans_pelayanan;
        }
        
        /*tindakan oksigen utk semua unit selain VK dan OK*/
        if ( in_array($data->jenis_tindakan, array(7)) ) {
            if( $str_type == '03' ){
                if(!in_array($data->kode_bagian, array('030501','030901'))){
                    $bill['bill_tindakan_oksigen'] = $subtotal;
                    $kode_trans_pelayanan['bill_tindakan_oksigen'] = $data->kode_trans_pelayanan;
                }
            }else{
                $bill['bill_tindakan_oksigen'] = $subtotal;
                $kode_trans_pelayanan['bill_tindakan_oksigen'] = $data->kode_trans_pelayanan;
            }
        }

        /*biaya lain-lain all*/
        if ( in_array($data->jenis_tindakan, array(8)) ) {
            $bill['bill_lain_lain'] = $subtotal;
            $kode_trans_pelayanan['bill_lain_lain'] = $data->kode_trans_pelayanan;
        }

        // obat alkes / BPAKO
        if ( in_array($data->jenis_tindakan, array(9)) ) {
            if( $str_type == '03' ){
                if(!in_array($data->kode_bagian, array('030501','030901'))){
                    $bill['bill_obat'] = $subtotal;
                    $kode_trans_pelayanan['bill_obat'] = $data->kode_trans_pelayanan;
                }
            }elseif( $str_type == '02' ){
                $bill['bill_ugd'] = $subtotal;
                $kode_trans_pelayanan['bill_ugd'] = $data->kode_trans_pelayanan;
            }elseif( $str_type == '01' ){
                $bill['bill_klinik'] = $subtotal;
                $kode_trans_pelayanan['bill_klinik'] = $data->kode_trans_pelayanan;
            }
        }

        /*biaya tindakan luar rs all*/
        if ( in_array($data->jenis_tindakan, array(10)) ) {
            $bill['bill_tindakan_luar_rs'] = $subtotal;
            $kode_trans_pelayanan['bill_tindakan_luar_rs'] = $data->kode_trans_pelayanan;
        }

        /*biaya apotik hanya untuk farmasi dan RI*/
        if ( in_array($data->jenis_tindakan, array(11)) ) {
            if( in_array($str_type, array('06','03') ) ){
                if(!in_array($data->kode_bagian, array('030501','030901'))){
                    $bill['bill_apotik'] = $subtotal;
                    $kode_trans_pelayanan['bill_apotik'] = $data->kode_trans_pelayanan;
                }
            }
        }

        // sarana rs all
        if ( in_array($data->jenis_tindakan, array(13)) ) {
            $bill['bill_sarana_rs'] = $subtotal;
            $kode_trans_pelayanan['bill_sarana_rs'] = $data->kode_trans_pelayanan;
        }

        /*tindakan vk*/
        if ( in_array($data->kode_bagian, array('030501')) ) {
            if( !in_array($data->jenis_tindakan, array(1, 2, 5, 6, 8, 10, 13) ) ){
                $bill['bill_tindakan_vk'] = $subtotal;
                $kode_trans_pelayanan['bill_tindakan_vk'] = $data->kode_trans_pelayanan;
            }
        }

        /*tindakan bedah*/
        if ( in_array($data->kode_bagian, array('030901')) ) {
            if( !in_array($data->jenis_tindakan, array(1, 2, 5, 6, 8, 10, 13) ) ){
                $bill['bill_tindakan_bedah'] = $subtotal;
                $kode_trans_pelayanan['bill_tindakan_bedah'] = $data->kode_trans_pelayanan;
            }
        }

        /*biaya ugd*/
        if ( in_array($data->kode_bagian, array('020101')) ) {
            if( !in_array($data->jenis_tindakan, array(1, 2, 5, 6, 8, 10, 13) ) ){
                $bill['bill_ugd'] = $subtotal;
                $kode_trans_pelayanan['bill_ugd'] = $data->kode_trans_pelayanan;
            }
        }

        /*penunjang medis*/
        /*biaya lab*/
        if ( in_array($data->kode_bagian, array('050101')) ) {
            if( !in_array($data->jenis_tindakan, array(1, 2, 5, 6, 8, 10, 13) ) ){
                $bill['bill_lab'] = $subtotal;
                $kode_trans_pelayanan['bill_lab'] = $data->kode_trans_pelayanan;
            }
        }

        /*biaya radiologi*/
        if ( in_array($data->kode_bagian, array('050201')) ) {
            if( !in_array($data->jenis_tindakan, array(1, 2, 5, 6, 8, 10, 13) ) ){
                $bill['bill_rad'] = $subtotal;
                $kode_trans_pelayanan['bill_rad'] = $data->kode_trans_pelayanan;
            }
        }
        /*biaya fisio*/
        if ( in_array($data->kode_bagian, array('050301')) ) {
            if( !in_array($data->jenis_tindakan, array(1, 2, 5, 6, 8, 10, 13) ) ){
                $bill['bill_fisio'] = $subtotal;
                $kode_trans_pelayanan['bill_fisio'] = $data->kode_trans_pelayanan;
            }
        }

        /*return bill data by fields billing*/
        foreach ($fields as $key => $value) {
            $getData[$value] = isset($bill[$value])?$bill[$value]:0;
            $getKodeTrans[$value] = isset($kode_trans_pelayanan[$value])?$kode_trans_pelayanan[$value]:0;
        }

        $array = array(
            'billing' => $getData,
            'kode_trans_pelayanan' => $getKodeTrans,
            );
        return $array;
    }
    
    public function resumeBillingRJ($jenis_tindakan, $kode_bagian, $subtotal){

        // define
        $bill_dr = 0;
        $bill_farm = 0;
        $bill_adm_rs = 0;
        $bill_pm = 0;
        $bill_tindakan = 0;
        $bill_bpako = 0;
        
        /*dokter*/
        if (in_array($jenis_tindakan, array(12))) {
            $bill_dr = $subtotal;
        }
        /*bpako*/
        if (in_array($jenis_tindakan, array(9))) {
            $bill_bpako = $subtotal;
        }
        /*obat farmasi*/
        if (in_array($jenis_tindakan, array(11))) {
            $bill_farm = $subtotal;
        }

        /*adm dan sarana rs*/
        if (in_array($jenis_tindakan, array(2,13))) {
            $bill_adm_rs = $subtotal;
        }

        /*penunjang medis*/
        $str_pm = substr((string)$kode_bagian, 0,2);
        if($str_pm == '05'){
            if (in_array($jenis_tindakan, array(3))) {
                $bill_pm = $subtotal;
            }
            $bill_tindakan = 0;
        }else{
            $bill_pm = 0;
            /*tindakan*/
            if (in_array($jenis_tindakan, array(3))) {
                $bill_tindakan = $subtotal;
            }
        }

        $data = array(
            'bill_dr' => $bill_dr,
            'bill_farm' => $bill_farm,
            'bill_adm_rs' => $bill_adm_rs,
            'bill_pm' => $bill_pm,
            'bill_tindakan' => $bill_tindakan,
            'bill_bpako' => $bill_bpako,
            );

        return $data;
    }

    public function splitResumeBilling($arrays){
        foreach ($arrays as $key => $value) {
            $bill_dr[] = $value['bill_dr'];
            $bill_farm[] = $value['bill_farm'];
            $bill_adm_rs[] = $value['bill_adm_rs'];
            $bill_pm[] = $value['bill_pm'];
            $bill_tindakan[] = $value['bill_tindakan'];
            $bill_bpako[] = $value['bill_bpako'];
        }
        $data = array(
            'bill_dr' => array_sum($bill_dr),
            'bill_farm' => array_sum($bill_farm),
            'bill_adm_rs' => array_sum($bill_adm_rs),
            'bill_pm' => array_sum($bill_pm),
            'bill_tindakan' => array_sum($bill_tindakan),
            'bill_bpako' => array_sum($bill_bpako),
            );

        return $data;
    }
	
	public function splitResumeBillingRI($arrays){
        $sumArray = [];
        foreach ($arrays as $k=>$subArray) {
          foreach ($subArray['billing'] as $keys=>$value) {
            $sumArray[$keys][] = $value;
            if($subArray['kode_trans_pelayanan'][$keys]!=0){
                $kodeTransPe[$keys][] = $subArray['kode_trans_pelayanan'][$keys];
            }
          }
        }
        foreach ($sumArray as $ky=>$vl) {
            $getData[$ky] = array_sum($vl);
        }
        foreach($getData as $kys=>$vls){
            $count = isset($kodeTransPe[$kys])?count($kodeTransPe[$kys]):0;
            $filter_count = ($count==1)?$kodeTransPe[$kys][0]:'';
            $data[] = array('title' => $this->getTitleNameBilling($kys,$filter_count), 'subtotal' => $vls, 'field' => $kys);
        }
        return $data;
	}
	
	public function groupingDocumentPM($array_data){
            
        $grouping_tindakan = array();
        $grouping = array();
        foreach ( $array_data as $k_pm=>$value ) {
            if(in_array($k_pm, array('Tindakan'))){
                foreach ($value as $kval_pm => $vvval_pm) {
                    /*grouping document pm*/
                    $str_pm_resume = substr((string)$vvval_pm->kode_bagian, 0,2);
                    if ($str_pm_resume=='05') {
                        /*get tindakan by kode penunjang*/
                        $grouping_tindakan[$vvval_pm->kode_penunjang][] = $vvval_pm->nama_tindakan; 
                        $grouping[$vvval_pm->kode_penunjang.'-'.$vvval_pm->kode_bagian.'-'.$vvval_pm->nama_bagian][] = array('kode_bagian' => $vvval_pm->kode_bagian,'kode_penunjang' => $vvval_pm->kode_penunjang, 'pm_name' => $vvval_pm->nama_bagian, 'no_kunjungan' => $vvval_pm->no_kunjungan);
                    }
                }
            }
        }
        $data = array('grouping_tindakan' => $grouping_tindakan, 'grouping_dokumen' => $grouping);
        //echo '<pre>';print_r($grouping_tindakan);die;
        return $data;

    }

    public function getKasirData($no_registrasi)
	{
		$this->db->from('tc_trans_kasir');
		$this->db->where('no_registrasi', $no_registrasi);
		return $this->db->get()->result();
    }
     public function getKasirDataa($no_registrasi,$no_mr)
    {
        $this->db->from('tc_trans_kasir');
        $this->db->where('no_registrasi', $no_registrasi);
        $this->db->where('no_mr', $no_mr);
        return $this->db->get()->result();
    }
    
    public function getTransData($no_registrasi){
		$this->db->select('tc_trans_pelayanan.*, CAST(bill_rs as INT) as bill_rs_int, CAST(bill_dr1 as INT) as bill_dr1_int, CAST(bill_dr2 as INT) as bill_dr2_int, CAST(bill_dr3 as INT) as bill_dr3_int ,mt_jenis_tindakan.jenis_tindakan as nama_jenis_tindakan, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai as nama_dokter, mt_perusahaan.nama_perusahaan, tc_kunjungan.tgl_masuk, tc_kunjungan.tgl_keluar');
		$this->db->from('tc_trans_pelayanan');
        $this->db->join('mt_jenis_tindakan','mt_jenis_tindakan.kode_jenis_tindakan=tc_trans_pelayanan.jenis_tindakan','left');
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_trans_pelayanan.kode_perusahaan','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_trans_pelayanan.kode_bagian','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_trans_pelayanan.kode_dokter1','left');
		$this->db->join('tc_kunjungan','tc_kunjungan.no_kunjungan=tc_trans_pelayanan.no_kunjungan','left');
		$this->db->where('tc_trans_pelayanan.no_registrasi', $no_registrasi);
        $this->db->where('nama_tindakan IS NOT NULL');

        if(isset($_GET['status_nk']) AND $_GET['status_nk'] == 1){
            $this->db->where('status_nk', 1);
        }elseif (isset($_GET['status_nk']) AND $_GET['status_nk'] == 0) {
            $this->db->where('(status_nk is null or status_nk = 0)');
        }

		$this->db->order_by('tc_trans_pelayanan.tgl_transaksi', 'ASC');
        $this->db->order_by('tc_trans_pelayanan.jenis_tindakan', 'ASC');
        $query = $this->db->get()->result();
        // print_r($this->db->last_query());die;
		return $query;
    }
    
    public function getTitleNameBilling($field){
    	
    	switch ($field) {
    		case 'bill_kamar_perawatan':
    			$title_name = 'Kamar Perawatan';
    			break;
    		case 'bill_kamar_icu':
    			$title_name = 'Ruangan ICU';
    			break;
    		case 'bill_tindakan_inap':
    			$title_name = 'Tindakan Rawat Inap';
    			break;
    		case 'bill_tindakan_oksigen':
    			$title_name = 'Tindakan Oksigen';
    			break;
    		case 'bill_tindakan_bedah':
    			$title_name = 'Tindakan Bedah';
    			break;
    		case 'bill_tindakan_vk':
    			$title_name = 'Tindakan VK';
    			break;
    		case 'bill_obat':
    			$title_name = 'Biaya Obat/Alkes';
    			break;
    		case 'bill_ambulance':
    			$title_name = 'Sewa Ambulance';
    			break;
    		case 'bill_dokter':
    			$title_name = 'Jasa Dokter/Bidan';
    			break;
    		case 'bill_apotik':
    			$title_name = 'Biaya Apotik';
    			break;
    		case 'bill_lain_lain':
    			$title_name = 'Lain-lain';
    			break;
    		case 'bill_adm':
    			$title_name = 'Administrasi';
    			break;
    		case 'bill_ugd':
    			$title_name = 'Gawat Darurat';
    			break;
    		case 'bill_rad':
    			$title_name = 'Radiologi';
    			break;
    		case 'bill_lab':
    			$title_name = 'Laboratorium';
    			break;
    		case 'bill_fisio':
    			$title_name = 'Fisioterapi';
    			break;
    		case 'bill_klinik':
    			$title_name = 'Poliklinik';
    			break;
    		case 'bill_pemakaian_alat':
    			$title_name = 'Pemakaian Alat';
    			break;
            case 'bill_tindakan_luar_rs':
                $title_name = 'Tindakan Luar';
                break;
            case 'bill_bpako':
                $title_name = 'BPAKO';
                break;
            case 'bill_sarana_rs':
                $title_name = 'Sarana '.COMP_FLAG.'';
                break;
    		default:
    		$title_name = '';
    			break;

    	}
    	return $title_name;
    }

    public function getKodeTransPelayanan($array, $field){
        foreach ($array as $value_data) {
            $resume_billing[] = $this->Billing->resumeBillingRI($value_data);
        }  
        foreach ($resume_billing as $key => $value) {
            $getData[] = $value['kode_trans_pelayanan'];
        }
        return $getData;
    }

    public function arraySearchResume($array, $field){
        $filtered = array();
        foreach($array as $index => $columns) {
            foreach($columns as $key => $value) {
                if ($key == $field && $value != 0) {
                    $filtered[] = $value;
                }
            }
        }
        return $filtered;
    }

    public function getHasilLab($params, $kode_penunjang, $flag_mcu){
        //print_r($params);die;
        if($flag_mcu==''){
            $table = 'pm_hasilpasien_v as a';
            $where = 'a.kode_trans_pelayanan IN (SELECT kode_trans_pelayanan FROM tc_trans_pelayanan WHERE kode_penunjang='.$kode_penunjang.')';
        }else{
            $table = 'mcu_hasilpasien_pm_v as a';
            $where = 'a.kode_trans_pelayanan IN (SELECT kode_trans_pelayanan_paket_mcu FROM tc_trans_pelayanan_paket_mcu WHERE kode_penunjang='.$kode_penunjang.')';
        }
        $this->db->select("a.kode_trans_pelayanan, a.kode_tarif, a.nama_pemeriksaan, REPLACE(a.nama_tindakan, 'BPJS' , '') as nama_tindakan, a.hasil, a.standar_hasil_pria, a.standar_hasil_wanita, a.satuan, a.keterangan, a.detail_item_1, a.detail_item_2,b.referensi,d.urutan");
        $this->db->from($table);
        $this->db->join('mt_master_tarif b', 'a.kode_tarif=b.kode_tarif', 'left');
        $this->db->join('pm_mt_standarhasil d', 'a.kode_mt_hasilpm=d.kode_mt_hasilpm', 'left');
        $this->db->where($where);
        $this->db->where(' a.hasil != '."''".' ');
        $this->db->group_by('a.kode_tarif, a.nama_pemeriksaan,a.nama_tindakan, a.hasil, a.standar_hasil_pria, a.standar_hasil_wanita, a.satuan, a.keterangan, a.detail_item_1, a.detail_item_2,b.referensi,d.urutan, a.kode_trans_pelayanan');
        $this->db->order_by('d.urutan', 'ASC');
        // $this->db->order_by('a.nama_pemeriksaan', 'ASC');
        // $this->db->order_by('a.kode_trans_pelayanan', 'ASC');
        //$this->db->order_by('a.detail_item_2', 'ASC');
        return $this->db->get()->result();

    }

    public function getRefLab($params, $kode_penunjang,$flag_mcu){
        //print_r($params);die;
        if($flag_mcu==''){
            $table = 'pm_hasilpasien_v as a';
            $where = 'a.kode_trans_pelayanan IN (SELECT kode_trans_pelayanan FROM tc_trans_pelayanan WHERE kode_penunjang='.$kode_penunjang.')';
        }else{
            $table = 'mcu_hasilpasien_pm_v as a';
            $where = 'a.kode_trans_pelayanan IN (SELECT kode_trans_pelayanan_paket_mcu FROM tc_trans_pelayanan_paket_mcu WHERE kode_penunjang='.$kode_penunjang.')';
        }
        $this->db->select("c.kode_tarif,c.nama_tarif as referensi,b.nama_tarif,a.nama_pemeriksaan,MAX(d.urutan) as urutan,MAX(a.kode_trans_pelayanan) AS kode_trans_pelayanan");
        $this->db->from($table);
        $this->db->join('mt_master_tarif b', 'a.kode_tarif=b.kode_tarif', 'left');
        $this->db->join('mt_master_tarif c', 'c.kode_tarif=b.referensi', 'left');
        $this->db->join('pm_isihasil_v d', 'a.kode_mt_hasilpm=d.kode_mt_hasilpm', 'left');
        $this->db->where($where);
        $this->db->where("a.hasil != ''");
        $this->db->group_by('c.kode_tarif,c.nama_tarif,b.nama_tarif,a.nama_pemeriksaan');
        $this->db->order_by('kode_trans_pelayanan', 'ASC');
        $this->db->order_by('urutan', 'ASC');
        return $this->db->get()->result();
    }

    public function getNamaDokter($flag, $kode_pm){
        $this->db->from('pm_tc_penunjang');
        $this->db->join('tc_kunjungan', 'tc_kunjungan.no_kunjungan=pm_tc_penunjang.no_kunjungan', 'left');
        $this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter=tc_kunjungan.kode_dokter', 'left');
        $this->db->where('pm_tc_penunjang.kode_penunjang', $kode_pm);
        $exc = $this->db->get()->row();
        //print_r($this->db->last_query());die;
        if( $exc && isset($exc->nama_pegawai) || $exc->nama_pegawai!=''){
            return $exc->nama_pegawai;
        }else{
            return 'Administrator';
        }

    }

    public function getNamaDokter_($flag, $kode_pm){
        $this->db->from('tc_trans_pelayanan');
        $this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter=tc_trans_pelayanan.kode_dokter1', 'left');
        $this->db->where('kode_penunjang', $kode_pm);
        $exc = $this->db->get()->row();
        //print_r($this->db->last_query());die;
        if( $exc && isset($exc->nama_pegawai) || $exc->nama_pegawai!=''){
            return $exc->nama_pegawai;
        }else{
            return 'Administrator';
        }

    }

    public function getRiwayatKunjungan($no_registrasi){
        $this->db->select('a.no_kunjungan, b.nama_bagian, a.tgl_masuk, a.tgl_keluar, c.nama_pegawai as nama_dokter');
        $this->db->from('tc_kunjungan a');
        $this->db->join('mt_bagian b','b.kode_bagian=a.kode_bagian_tujuan','left');
        $this->db->join('mt_dokter_v c','c.kode_dokter=a.kode_dokter','left');
        $this->db->where('a.no_registrasi', $no_registrasi);
        $this->db->order_by('a.tgl_masuk', 'ASC');
        $this->db->group_by('a.no_kunjungan, b.nama_bagian, a.tgl_masuk, a.tgl_keluar, c.nama_pegawai');
        $result = $this->db->get()->result();
        foreach ($result as $key => $value) {
            $group[$this->tanggal->formatDate($value->tgl_masuk)][] = $value;
        }
        return $group;
    }

    public function get_total_tagihan($obj){

        $total =  (int)$obj->bill_rs + (int)$obj->bill_dr1 + (int)$obj->bill_dr2 + (int)$obj->bill_dr3 + (int)$obj->lain_lain;
        return $total;

    }
    public function get_total_tagihanall($obj){

        $total =  (int)$obj->bill_rs + (int)$obj->bill_dr1 + (int)$obj->bill_dr2 + + (int)$obj->bill_dr3 + (int)$obj->lain_lain;
        //$subtotal = $subtotal + $total;
        return $total;

    }

    // public function get_total_tagihan($obj){

    //     $total =  (double)$obj->bill_rs_int + (double)$obj->bill_dr1_int + (double)$obj->bill_dr2_int + (double)$obj->bill_dr3_int + (double)$obj->lain_lain;
    //     return $total;

    // }
    // public function get_total_tagihanall($obj){

    //     $total =  (double)$obj->bill_rs_int + (double)$obj->bill_dr1_int + (double)$obj->bill_dr2_int + + (double)$obj->bill_dr3_int + (double)$obj->lain_lain;
    //     //$subtotal = $subtotal + $total;
    //     return $total;

    // }

    public function cek_tipe_pasien($no_registrasi){

        $query = $this->db->where('no_kunjungan in (SELECT no_kunjungan FROM tc_kunjungan WHERE no_registrasi='.$no_registrasi.')')->get('ri_tc_rawatinap')->num_rows();
        return ( $query == 0 ) ? 'RJ' : 'RI' ;
    }

    public function rollback_kasir(){

        /*delete tc trans kasir*/
        // $this->db->where(' kode_tc_trans_kasir IN 
        //                     (select kode_tc_trans_kasir from tc_trans_pelayanan where no_registrasi in ('.$_POST['no_reg'].')
        //                     ) ')->delete('tc_trans_kasir');
       
        /*delete detail akuntig*/
        $this->db->where(' id_ak_tc_transaksi IN 
                            (select id_ak_tc_transaksi from ak_tc_transaksi where kode_tc_trans_kasir in 
                                (select kode_tc_trans_kasir from tc_trans_kasir where no_registrasi in ('.$_POST['no_reg'].')
                                )
                            ) ')->delete('ak_tc_transaksi_det');

        /*delete akunting*/
        $this->db->where(' id_ak_tc_transaksi IN 
                            (select id_ak_tc_transaksi from ak_tc_transaksi where kode_tc_trans_kasir in 
                                (select kode_tc_trans_kasir from tc_trans_kasir where no_registrasi in 
                                    ('.$_POST['no_reg'].')
                                )
                            ) ')->delete('ak_tc_transaksi');

        // delete tc_trans_kasir
        $this->db->where(' no_registrasi', $_POST['no_reg'])->delete('tc_trans_kasir');

        /*update trans pelayanan*/
        $this->db->update('tc_trans_pelayanan', array('status_selesai' => 2, 'status_nk' => NULL, 'kode_tc_trans_kasir' => NULL), array('no_registrasi' => $_POST['no_reg']) );

        return true;
    }

    public function getOriginalTransData($no_registrasi){
        $this->db->select('tc_trans_pelayanan.*,mt_jenis_tindakan.jenis_tindakan as nama_jenis_tindakan, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai as nama_dokter, mt_klas.nama_klas, kode_perusahaan, status_nk, kode_tc_trans_kasir');
        $this->db->from('tc_trans_pelayanan');
        $this->db->join('mt_jenis_tindakan','mt_jenis_tindakan.kode_jenis_tindakan=tc_trans_pelayanan.jenis_tindakan','left');
        $this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_trans_pelayanan.kode_bagian','left');
        $this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_trans_pelayanan.kode_dokter1','left');
        $this->db->join('mt_klas','mt_klas.kode_klas=tc_trans_pelayanan.kode_klas','left');
        $this->db->where('no_registrasi', $no_registrasi);
        $this->db->where('nama_tindakan IS NOT NULL');
        //$this->db->where('tc_trans_pelayanan.kode_tc_trans_kasir IN (SELECT kode_tc_trans_kasir FROM tc_trans_kasir WHERE no_registrasi='.$no_registrasi.'');
        $this->db->order_by('tc_trans_pelayanan.jenis_tindakan', 'ASC');
        //print_r($this->db->last_query());die;
        $trans_data = $this->db->get()->result();

        $group = array();
        foreach ($trans_data as $value) {
            $group[$value->nama_jenis_tindakan][] = $value;
        }

        $result = json_encode(array('group' => $group, 'no_registrasi' => $no_registrasi, 'trans_data' => $trans_data));

        return $result;

    }

    public function groupingTransaksiByDate($array){
        $groupDate = array();
        $groupBag = array();
        $getData = array();
        // loop data
        foreach($array as $key=>$row){
            // group by date
            $groupDate[$this->tanggal->formatDate($row->tgl_transaksi)][] = $row;
        }
        // group by bagian
        foreach($groupDate as $key_gr=>$row_gr){
            foreach($row_gr as $sub_rw){
                $groupBag[$key_gr][$sub_rw->nama_bagian][] = $sub_rw;
            }
            $getData = $groupBag;
        }

        // group by bagian tindakan
        // foreach($getData as $key_dt=>$row_dt){
        //     foreach($row_dt as $key_val=>$val_dt){
        //         foreach($val_dt as $sub_dt){
        //             $groupTindakan[$sub_dt->nama_jenis_tindakan][] = $sub_dt;
        //         }
        //         $bagDt[$key_val] = $groupTindakan;
        //     }
        //     $resultData[$key_dt] = $bagDt;
        // }
        // echo '<pre>';print_r($getData);die;
        return $getData;
    }

    

}
