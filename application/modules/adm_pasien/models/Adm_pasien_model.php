<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adm_pasien_model extends CI_Model {

	var $table = 'tc_trans_pelayanan';
	var $column = array('a.no_registrasi');
	var $select = 'a.no_registrasi, a.no_mr, e.nama_pasien, b.tgl_jam_masuk, c.nama_bagian, b.kode_perusahaan, d.nama_perusahaan';
	var $order = array('a.no_registrasi' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('billing.total_billing');
		$this->db->from('tc_trans_pelayanan a');
		$this->db->join('tc_registrasi b', 'b.no_registrasi=a.no_registrasi', 'left');
		$this->db->join('mt_bagian c', 'c.kode_bagian=b.kode_bagian_masuk', 'left');
		$this->db->join('mt_perusahaan d', 'd.kode_perusahaan=b.kode_perusahaan', 'left');
		$this->db->join('mt_master_pasien e', 'e.no_mr=a.no_mr', 'left');
		$this->db->join('(SELECT z.no_registrasi, CAST((SUM(z.bill_rs) + SUM(z.bill_dr1) + SUM(z.bill_dr2) + SUM(z.bill_dr3) + SUM(z.lain_lain)) AS INT) as total_billing FROM tc_trans_pelayanan z WHERE z.kode_tc_trans_kasir IS NULL GROUP BY z.no_registrasi) as billing', 'billing.no_registrasi=a.no_registrasi', 'left');

		if ($_POST['is_with_date']==1) {
			$this->db->where('DAY(b.tgl_jam_masuk)', $_POST['date']);
			$this->db->where('MONTH(b.tgl_jam_masuk)', $_POST['month']);
			$this->db->where('YEAR(b.tgl_jam_masuk)', $_POST['year']);
		}else{
			$this->db->where('a.no_registrasi in (select no_registrasi from tc_kunjungan where YEAR(tgl_masuk)='."'".date('Y')."'".' AND MONTH(tgl_masuk)='."'".date('m')."'".')');
		}

		if(isset($_POST['keyword']) AND $_POST['keyword'] != ''){
			$this->db->where('a.'.$_POST['search_by'], $_POST['keyword']);		
		}
		
		$this->db->group_by($this->select);
		$this->db->group_by('billing.total_billing');

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
		// echo '<pre>';print_r($this->db->last_query());die;
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

	public function merge_transaksi( $array ){
		
		/*delete tc_registrasi*/
		$this->db->query('delete from tc_registrasi where no_registrasi='.$array['second'].'');

		/*delete kode_tc_trans_kasir*/
		$this->db->query('delete from tc_trans_kasir where no_registrasi='.$array['second'].'');

		/*update tc_trans_kasir_bagian*/
		$this->db->query('update tc_trans_kasir_bagian
							set kode_tc_trans_kasir=
								(select kode_tc_trans_kasir 
									from tc_trans_kasir 
									where no_registrasi='.$array['first'].'
								) 
							where kode_tc_trans_kasir in 
								(select kode_tc_trans_kasir 
									from tc_trans_kasir 
									where no_registrasi in ('.$array['first'].','.$array['second'].')
								)'
						);


		$this->db->query('update ak_tc_transaksi 
							set kode_tc_trans_kasir=
								(select kode_tc_trans_kasir 
									from tc_trans_kasir 
									where no_registrasi='.$array['first'].') 
							where kode_tc_trans_kasir in 
								(select kode_tc_trans_kasir 
									from tc_trans_kasir 
									where no_registrasi in ('.$array['first'].','.$array['second'].')
								)'
						);

		$this->db->query('update ks_tc_trans_um
							set kode_tc_trans_kasir=
								(select kode_tc_trans_kasir 
									from tc_trans_kasir 
									where no_registrasi='.$array['first'].'
								) 
							where kode_tc_trans_kasir in 
								(select kode_tc_trans_kasir 
									from tc_trans_kasir 
									where no_registrasi in ('.$array['first'].','.$array['second'].')
								)'
						);

		$this->db->query('update tc_trans_pelayanan 
							set no_registrasi='.$array['first'].' 
							where no_registrasi = '.$array['second'].''
						);

		$this->db->query('update tc_kunjungan 
							set no_registrasi='.$array['first'].' 
							where no_registrasi = '.$array['second'].''
						);

		$this->db->query('update mt_kunjungan_detail 
							set no_registrasi='.$array['first'].' 
							where no_registrasi = '.$array['second'].''
						);
		$this->db->query('update tc_trans_pelayanan 
							set kode_tc_trans_kasir=
								(select kode_tc_trans_kasir 
									from tc_trans_kasir 
									where no_registrasi='.$array['first'].') 
							where no_registrasi='.$array['first'].''
						);
		return true;
	}

	public function get_first_registrasi($val){
		$first = $this->db->select('SUBSTRING(kode_bagian_masuk, 1,2) as substr')->get_where('tc_registrasi', array('no_registrasi' => $val[0]) )->row();
        $second = $this->db->select('SUBSTRING(kode_bagian_masuk, 1,2) as substr')->get_where('tc_registrasi', array('no_registrasi' => $val[1]) )->row();
        if ($first->substr == $second->substr) {
            $kode['first'] = $val[0];
            $kode['second'] = $val[1];
        }else{
            if( in_array($first->substr, array('01','03') ) ){
                $kode['first'] = $val[0];
            }else{
                $kode['second'] = $val[0];
            }

            if( in_array($second->substr, array('01','03') ) ){
                $kode['first'] = $val[1];
            }else{
                $kode['second'] = $val[1];
            }

        }

        return $kode;
	}

}
