<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import_tarif_2025_model extends CI_Model {


	public function __construct()
	{
		parent::__construct();
	}

    public function update_detail_tarif($table, $data){

        echo nl2br("").PHP_EOL.'<br>';
        echo nl2br("Waiting Execution For Detail Tarif..").PHP_EOL.'<br>';
        echo nl2br("").PHP_EOL.'<br>';

        /*loop first array*/
        foreach($data as $k=>$value){
            /*cek terlebih dahulu apakah sudah ada data nya atau tidak*/
            $is_exist = $this->db->get_where($table, array('kode_tarif' => $value['kode_tarif'], 'kode_klas' => $value['kode_klas']) );

            $revisi_ke = ( $is_exist->num_rows() > 0 ) ? $is_exist->row()->revisi_ke + 1 : 1 ;
            $array_merge = array_merge($value, array('revisi_ke' => $revisi_ke) );
            // echo '<pre>';print_r($array_merge);die;
            /*loop detail tarif*/
            if($this->db->insert($table, $array_merge)){
                echo nl2br($value['kode_tarif'].' => '.$value['bill_rs'].' | '.$value['bill_dr1'].' | '.$value['bill_dr2'].' | '.$value['bhp'].' | '.$value['pendapatan_rs'].' row insert successfull').PHP_EOL.'<br>'; 
            }else{
                echo nl2br($value['kode_tarif'].' row insert failed').PHP_EOL.'<br>';
            }
            
        }
       
        return true;
    }

    public function update_tarif_ori($table, $data){

        if(!in_array($data['kode_tarif'], array('#N/A'))){
            /*cek terlebih dahulu apakah sudah ada data nya atau tidak*/
            $is_exist = $this->db->get_where($table, array('kode_tarif' => $data['kode_tarif']) );
            if($is_exist->num_rows() > 0){
                /*add field revisi*/
                $row_data = $is_exist->row();
                $revisi = $row_data->revisi_ke + 1;
                $field_revisi = array('revisi_ke' => $revisi, 'kode_tindakan' => $row_data->kode_tindakan, 'jenis_tindakan' => $row_data->jenis_tindakan);
                $merge_array = array_merge($data, $field_revisi);
                //echo '<pre>'; print_r($merge_array);die;
                /*update data*/
                if($this->db->update($table, $merge_array, array('kode_tarif' => $data['kode_tarif']) )){
                    echo nl2br($data['kode_tarif'].' ['.$data['nama_tarif'].'] row updated successfull').PHP_EOL.'<br>'; 
                }else{
                    echo nl2br($data['kode_tarif'].' row updated failed').PHP_EOL.'<br>';
                }  
                $kode_tarif = $data['kode_tarif'];
            }else{
                die;
                $insert = $this->insert_new_data($data['kode_bagian'], $data, $table);

                if($insert['kode_tarif']){
                    echo nl2br($insert['kode_tarif'].' row insert successfull').PHP_EOL.'<br>';
                }else{
                    echo nl2br('row insert failed').PHP_EOL.'<br>';
                }
                $kode_tarif = $insert['kode_tarif'];
            }
        }else{
            $insert = $this->insert_new_data($data['kode_bagian'], $data, $table);
            if($insert['kode_tarif']){
                echo nl2br($insert['kode_tarif'].' row insert successfull').PHP_EOL.'<br>';
            }else{
                echo nl2br('row insert failed').PHP_EOL.'<br>';
            }
            $kode_tarif = $insert['kode_tarif'];
        }
        
        return $kode_tarif;
    }

    public function update_tarif($table, $data){

        $is_exist = $this->db->where('kode_tarif', trim($data['kode_tarif']))
                             ->from($table)->order_by('revisi_ke', 'DESC')->get();
        // echo '<pre>'; print_r($is_exist->row());die;
        if($is_exist->num_rows() > 0){
            /*add field revisi*/
            $row_data = $is_exist->row();
            $revisi = $row_data->revisi_ke + 1;
            // generate kode tindakan
            $length = strlen(trim($row_data->kode_tindakan));
            $substr = substr($row_data->kode_tarif, 5);
            $kode_tindakan = ($length > 0)?$row_data->kode_tindakan:'NT'.$substr.'';

            // echo '<pre>'; print_r($row_data);
            // echo '<pre>'; print_r($kode_tindakan);
            // echo '<pre>'; print_r($substr);die;
            $field_revisi = array('kode_tarif' => $row_data->kode_tarif, 'revisi_ke' => $revisi, 'kode_tindakan' => $kode_tindakan, 'jenis_tindakan' => $row_data->jenis_tindakan);
            $merge_array = array_merge($data, $field_revisi);
            // echo '<pre>'; print_r($merge_array);die;
            /*update data*/
            if($this->db->update($table, $merge_array, array('kode_tarif' => $row_data->kode_tarif) )){
                echo nl2br($row_data->kode_tarif.' ['.$data['nama_tarif'].'] row updated successfull').PHP_EOL.'<br>'; 
            }else{
                echo nl2br($row_data->kode_tarif.' row updated failed').PHP_EOL.'<br>';
            }  
            $kode_tarif = $row_data->kode_tarif;

        }else{

            $insert = $this->insert_new_data($data['kode_bagian'], $data, $table);

            if($insert['kode_tarif']){
                echo nl2br($insert['kode_tarif'].' row insert successfull').PHP_EOL.'<br>';
            }else{
                echo nl2br('row insert failed').PHP_EOL.'<br>';
            }
            $kode_tarif = $insert['kode_tarif'];

        }
        
        return $kode_tarif;
    }

    public function insert_new_data($kode_bagian, $data, $table){
        $new_kode_tarif = $this->generate_kode_tarif($kode_bagian);
        $new_kode_tindakan = substr($new_kode_tarif, 5);
        $data_for_insert = array(
            'kode_tarif' => $new_kode_tarif,
            'kode_tindakan' => 'NT'.$new_kode_tindakan,
            'nama_tarif' => $data['nama_tarif'],
            'tingkatan' => 5,
            'referensi' => $data['referensi'],
            'kode_bagian' => (string)$kode_bagian,
            'jenis_tindakan' => 3,
            'is_old' => 'N',
            'is_active' => 'Y',
            'revisi_ke' => 1,
            'ins_nw' => 'Y',
            );

        $this->db->insert($table, $data_for_insert);

        return $data_for_insert;
    }

    public function generate_kode_tarif($kode_bagian){
        /*get max kode tarif by kode_bagian*/
        $max_kode = $this->db->query("select MAX(kode_tarif)as max_tarif from mt_master_tarif where kode_bagian=".$kode_bagian."")->row();
        $new_kode_plus_one = $max_kode->max_tarif + 1;
        return $new_kode_plus_one;
    }

    public function get_detail_tarif($kode_tarif, $kode_klas){
        //print_r($kode_tarif);die;
        $query = $this->db->get_where('mt_master_tarif_detail_dev', array('kode_tarif' => $kode_tarif, 'kode_klas' => $kode_klas));
        return $query->row();
    }

    public function get_tarif_by_kode_bagian($kode_bagian, $reff){
        /*execute query*/
        $qry = $this->db->query("SELECT mt_master_tarif.kode_tarif, mt_master_tarif.nama_tarif, mt_master_tarif_detail.kode_klas, mt_klas.nama_klas, bill_rs, alat_rs, bill_dr1, bill_dr2, 
            kamar_tindakan, bhp, pendapatan_rs, obat, alkes, total 
            FROM mt_master_tarif_detail 
            left join mt_master_tarif on mt_master_tarif.kode_tarif=mt_master_tarif_detail.kode_tarif
            left join mt_klas on mt_klas.kode_klas=mt_master_tarif_detail.kode_klas
            where mt_master_tarif_detail.kode_tarif in (select kode_tarif from mt_master_tarif where kode_bagian='".$kode_bagian."') and mt_master_tarif_detail.kode_klas != 2 and mt_master_tarif.kode_tarif like '".$reff."%' and mt_master_tarif_detail.is_old='N'
            order by mt_master_tarif_detail.kode_klas ASC")->result();

        /*get klas*/
        $klas = $this->db->query("select * from mt_klas where kode_klas=16 order by kode_klas")->result();
        $getData = array();
        foreach ($qry as $key => $value) {
            $getData[$value->kode_tarif][] = $value;
        }

        $html = '<table border="1" cellspacing="2">';
        $html .= '<tr>';
        $html .= '<td rowspan="2">No</td>';
        $html .= '<td rowspan="2">Kode Tarif</td>';
        $html .= '<td rowspan="2">Nama Tarif</td>';
        foreach ($klas as $key_klas => $val_klas) {
            $html .= '<td colspan="7">'.strtoupper($val_klas->nama_klas).'</td>';
        }
        $html .= '</tr>';
        $html .= '<tr>';
        for ($i=0; $i < 1; $i++) { 
            $html .= '<td>ALAT RS</td>';
            $html .= '<td>BMHP</td>';
            $html .= '<td>JS</td>';
            $html .= '<td>JP</td>';
            $html .= '<td>JN</td>';
            $html .= '<td>SEWA KAMAR</td>';
            $html .= '<td>TOTAL TARIF</td>';
        }
        $html .= '</tr>';
       
        $html .= '</tr>';
        $no = 0;
        foreach ($getData as $k => $v) {
            $no++;
            $nama_tarif = $this->get_nama_tarif($v[0]);
            $html .= '<tr>';
            $html .= '<td>'.$no.'</td>';
            $html .= '<td>'.$k.'</td>';
            $html .= '<td>'.$nama_tarif.'</td>';
            foreach($v as $r){
                $html .= '<td>'.(int)$r->alat_rs.'</td>';
                $html .= '<td>'.(int)$r->bhp.'</td>';
                $html .= '<td>'.(int)$r->pendapatan_rs.'</td>';
                $html .= '<td>'.(int)$r->bill_dr1.'</td>';
                $html .= '<td>'.(int)$r->bill_dr2.'</td>';
                $html .= '<td>'.(int)$r->kamar_tindakan.'</td>';
                $html .= '<td>'.(int)$r->total.'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        echo $html;
        //echo '<pre>';print_r($getData);die;

    }

    function get_nama_tarif($array){
        return $array->nama_tarif;
    }




}
