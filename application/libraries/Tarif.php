<?php
include "AvObjects.php";
/*
 * To change this template, choose Tools | templates
 * and open the template in the editor.
 */

final class Tarif extends AvObjects {

    public function __construct() {
        parent::__construct();

        
        // Default nasabah, dianggep nasabah umum
        $this->_prop["kode_kelompok"]="1";

        // Default jumlah, 1
        $this->_prop["jumlah"]="1";

        $this->_prop["kode_tarif"]="";

        $this->_prop["kode_klas"]="";

        $this->_prop["kode_bagian"]="";

        //$this->_prop["cito"]="";
        $this->tax = 0.01; //11%

    } // end of public function __construct()

    public function hitung() {
        $hasil=array();
        $inputnya=$this->getAllProp();
        $tarifAskes='';
        $tarifJatah='';

        // kalo non-askes langsung dikasi 0 buat bill askes & jatah..
        switch ($this->get("kode_kelompok")) {
            case "1":
            case "2" :
            case "3" :
            case "4" :
            case "5" :
                $tarifAskes=$this->_hitungTarifAskes();
                $tarifJatah=$this->_hitungTarifJatah();
            break;
            case "6" :
                $tarifAskes=array();
                $tarifJatah=array();

                $tarifAskes["bill_rs_askes"] = "0";
                $tarifAskes["bill_dr1_askes"] = "0";
                $tarifAskes["bill_dr2_askes"] = "0";
                //$tarifAskes["bill_dr3_askes"] = "0";

                $tarifJatah["bill_rs_jatah"] = "0";
                $tarifJatah["bill_dr1_jatah"] = "0";
                $tarifJatah["bill_dr2_jatah"] = "0";
                //$tarifJatah["bill_dr3_jatah"] = "0";
                $tarifJatah["kode_master_tarif_detail_jatah"] = "";

                //echo "di dalam yg non-askes<br>\n";
            break;
            default :
        }

        $tarifCurrent=$this->_hitungTarifCurrent();

        if (!is_array($inputnya)) $inputnya = array();
        if (!is_array($tarifCurrent)) $tarifCurrent = array();
        if (!is_array($tarifAskes)) $tarifAskes = array();
        if (!is_array($tarifJatah)) $tarifJatah = array();
        $hasil=array_merge($inputnya, $tarifCurrent, $tarifAskes, $tarifJatah);

        unset($this->_prop["cito"]);

        return $hasil;
    } // end of public function hitung()

    private function _hitungTarifAskes() {
        $hasil=array();
        //echo "dalam _hitungTarifAskes()<br>\n";

        $hasil["bill_rs_askes"] = "0";
        $hasil["bill_dr1_askes"] = "0";
        $hasil["bill_dr2_askes"] = "0";
        //$hasil["bill_dr3_askes"] = "0";

        return $hasil;
    } // end of private function _hitungTarifAskes()

    private function _hitungTarifJatah() {
        $hasil=array();

        $hasil["bill_rs_jatah"] = "0";
        $hasil["bill_dr1_jatah"] = "0";
        $hasil["bill_dr2_jatah"] = "0";
        //$hasil["bill_dr3_jatah"] = "0";
        $hasil["kode_master_tarif_detail_jatah"] = "";

        return $hasil;
    } // end of private function _hitungTarifJatah()

    private function _hitungTarifCurrent() {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        $hasil=array();

        $rTarif = $db->get_where('mt_tarif_v', array('kode_tarif' => $this->get("kode_tarif"), 'kode_klas' => $this->get("kode_klas"), 'status' => 1))->row();
        //print_r($db->last_query());die;
        //if (isset($this->_prop["cito"]) && ($this->get("cito")!="" || $this->get("cito")!="0")) {
        if (isset($this->_prop["cito"]) && ($this->get("cito")=="1")) {
            //$hasil=$this->_hitungTarifCito($rTarif->fields);
            $hasil_cito=$this->_hitungTarifCito($rTarif->bill_rs,$rTarif->bill_dr1,$rTarif->bill_dr2,$rTarif->bill_dr3,$rTarif->bill_perawat,$rTarif->kamar_tindakan,$rTarif->biaya_lain,$rTarif->obat,$rTarif->alkes,$rTarif->alat_rs,$rTarif->adm,$rTarif->overhead,$rTarif->bhp,$rTarif->pendapatan_rs);
            $hasil["bill_rs"] = $hasil_cito["bill_rs"];
            $hasil["bill_dr1"] = $hasil_cito["bill_dr1"];
            $hasil["bill_dr2"] = $hasil_cito["bill_dr2"];
            $hasil["bill_dr3"] = $hasil_cito["bill_dr3"];
            //$hasil["bill_perawat"] = $hasil_cito["bill_perawat"];
            $hasil["kamar_tindakan"] = $hasil_cito["kamar_tindakan"];
            $hasil["biaya_lain"] = $hasil_cito["biaya_lain"];
            $hasil["obat"] = $hasil_cito["obat"];
            $hasil["alkes"] = $hasil_cito["alkes"];
            $hasil["alat_rs"] = $hasil_cito["alat_rs"];
            $hasil["adm"] = $hasil_cito["adm"];
            $hasil["bhp"] = $hasil_cito["bhp"];
            $hasil["pendapatan_rs"] = $hasil_cito["pendapatan_rs"];
        } else {
            $hasil["bill_rs"] = $rTarif->bill_rs * $this->get("jumlah");
            $hasil["bill_dr1"] = $rTarif->bill_dr1 * $this->get("jumlah");
            $hasil["bill_dr2"] = $rTarif->bill_dr2 * $this->get("jumlah");
            $hasil["bill_dr3"] = $rTarif->bill_dr3 * $this->get("jumlah");
            //$hasil["bill_perawat"] = $rTarif->Fields("bill_perawat") * $this->get("jumlah");
            $hasil["kamar_tindakan"] = $rTarif->kamar_tindakan * $this->get("jumlah");
            $hasil["biaya_lain"] = $rTarif->biaya_lain * $this->get("jumlah");
            $hasil["obat"] = $rTarif->obat * $this->get("jumlah");
            $hasil["alkes"] = $rTarif->alkes * $this->get("jumlah");
            $hasil["alat_rs"] = $rTarif->alat_rs * $this->get("jumlah");
            $hasil["adm"] = $rTarif->adm * $this->get("jumlah");
            $hasil["overhead"] = $rTarif->overhead * $this->get("jumlah");
            $hasil["bhp"] = $rTarif->bhp * $this->get("jumlah");
            $hasil["pendapatan_rs"] = $rTarif->pendapatan_rs * $this->get("jumlah");

            if($this->get("kode_kelompok")=='10'){
                $cek01=$rTarif->bill_kjs;
                $cek02=$rTarif->bill_bs_rs;
                $cek03=$rTarif->bill_bs_dr;
                if($cek01!='' || $cek02!='' || $cek03!='' ){
                    $hasil["bill_kjs"] = $rTarif->bill_kjs * $this->get("jumlah");
                    $hasil["bill_bs_rs"] = $rTarif->bill_bs_rs * $this->get("jumlah");
                    $hasil["bill_bs_dr"] = $rTarif->bill_bs_dr * $this->get("jumlah");
                }else{
                    $hasil["bill_bs_rs"] = $rTarif->bill_rs * $this->get("jumlah");
                    $hasil["bill_bs_dr"] = $rTarif->bill_dr1 * $this->get("jumlah");
                }
                $hasil["status_nk"] = "1";
            }
        }

        $hasil["kode_master_tarif_detail"] = $rTarif->kode_master_tarif_detail;
        $hasil["nama_tindakan"] = $rTarif->nama_tarif;
        $hasil["jenis_tindakan"] = $rTarif->jenis_tindakan;

        return $hasil;
    } // end of private function _hitungTarifCurrent()

    private function _hitungTarifCito($params,$bill_dr1,$bill_dr2,$bill_dr3,$bill_perawat,$kamar_tindakan,$biaya_lain,$obat,$alkes,$alat_rs,$adm,$overhead,$bhp,$pendapatan_rs) {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        $hasil=array();

        $nilai_cito=$db->get_where('pm_mt_kenaikancito', array('kode_bagian' => $this->get("kode_bagian")))->row();        
        $kenaikan_cito = ($nilai_cito->prosentase * $this->tax) + 1;
        
        $bill_rs=$params * $kenaikan_cito;
        $bill_dr1=$bill_dr1 * $kenaikan_cito;
        $bill_dr2=$bill_dr2 * $kenaikan_cito;
        $bill_dr3=$bill_dr3 * $kenaikan_cito;
        $bill_perawat=$bill_perawat * $kenaikan_cito;
        $kamar_tindakan=$kamar_tindakan * $kenaikan_cito;
        $biaya_lain=$biaya_lain * $kenaikan_cito;
        $obat=$obat * $kenaikan_cito;
        $alkes=$alkes * $kenaikan_cito;
        $obat=$obat * $kenaikan_cito;
        $alat_rs=$alat_rs * $kenaikan_cito;
        $adm=$adm * $kenaikan_cito;
        $overhead=$overhead * $kenaikan_cito;
        $bhp=$bhp * $kenaikan_cito;
        $pendapatan_rs=$pendapatan_rs * $kenaikan_cito;
        
        $hasil["bill_rs"] = ceil($bill_rs) ;
        $hasil["bill_dr1"] = ceil($bill_dr1);
        $hasil["bill_dr2"] = ceil($bill_dr2);
        $hasil["bill_dr3"] = ceil($bill_dr3);
        $hasil["bill_perawat"] = ceil($bill_perawat);
        $hasil["kamar_tindakan"] = ceil($kamar_tindakan);
        $hasil["biaya_lain"] = ceil($biaya_lain);
        $hasil["obat"] = ceil($obat);
        $hasil["alkes"] = ceil($alkes);
        $hasil["alat_rs"] = ceil($alat_rs);
        $hasil["adm"] = ceil($adm);
        $hasil["overhead"] = ceil($overhead);
        $hasil["bhp"] = ceil($bhp);
        $hasil["pendapatan_rs"] = ceil($pendapatan_rs);

        return $hasil;
    } // end of private function _hitungTarifCito()


    /*Added by als*/
    public function _hitungBPAKOCurrent($harga_beli, $kode_kelompok, $flag_kjs, $kode_brg, $kode_profit, $jumlah=1) {

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        /*kategori obat/alkes*/
        $kategori=substr($kode_brg,0,1);
        $select_profit = ($kategori=='D')?'profit_obat':'profit_alkes';
        /*get nilai profit*/
        $db->select($select_profit);
        $db->from('fr_mt_profit_margin');
        $db->where('tingkat', 1);
        
        if( ($kode_kelompok==10) && ($flag_kjs==10) ){
            $db->where('kode_profit', '10007');            
        }else{
            $db->where('kode_profit', $kode_profit);
        }
        $profit = $db->get()->row();
        // print_r($profit);die;
        if($profit->$select_profit != ''){
            $nilai_profit = $profit->$select_profit;
        }else{
            $get_idp = $db->select('id_profit')->get_where('mt_rekap_stok', array('kode_brg' => $kode_brg) )->row();
            if($get_idp->id_profit != ''){
                $get_profit = $db->get_where('fr_mt_profit_margin', array('id_profit' => $get_idp->id_profit) )->row();
                $nilai_profit = $get_profit->$select_profit;
            }else{
                $nilai_profit = 0;
            }
        }

        $kenaikan_profit = ($nilai_profit * $this->tax) + 1;
        $total_harga_jual = ceil($harga_beli * $kenaikan_profit * $jumlah);
        // print_r($total_harga_jual);
        // print_r($harga_beli);
        // print_r($kenaikan_profit);
        // print_r($nilai_profit);
        // print_r($jumlah);die;
        return $total_harga_jual;

    }

    public function _hitungBPAKOCurrentCustom($harga_beli, $kode_kelompok, $flag_kjs, $kode_brg, $kode_profit, $jumlah=1) {

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        /*kategori obat/alkes*/
        $kategori=substr($kode_brg,0,1);
        $select_profit = ($kategori=='D')?'profit_obat':'profit_alkes';
        /*get nilai profit*/
        $db->select($select_profit);
        $db->from('fr_mt_profit_margin');
        $db->where('tingkat', 1);
        
        if( ($kode_kelompok==10) && ($flag_kjs==10) ){
            $db->where('kode_profit', '10007');            
        }else{
            $db->where('kode_profit', $kode_profit);
        }
        $profit = $db->get()->row();
        // print_r($profit);die;
        if($profit->$select_profit != ''){
            $nilai_profit = $profit->$select_profit;
        }else{
            $get_idp = $db->select('id_profit')->get_where('mt_rekap_stok', array('kode_brg' => $kode_brg) )->row();
            if($get_idp->id_profit != ''){
                $get_profit = $db->get_where('fr_mt_profit_margin', array('id_profit' => $get_idp->id_profit) )->row();
                $nilai_profit = $get_profit->$select_profit;
            }else{
                $nilai_profit = 0;
            }
        }

        $kenaikan_profit = ($nilai_profit * $this->tax) + 1;
        $total_harga_jual = ceil($harga_beli * $kenaikan_profit * $jumlah);
        // print_r($total_harga_jual);
        // print_r($harga_beli);
        // print_r($kenaikan_profit);
        // print_r($nilai_profit);
        // print_r($jumlah);die;
        return $total_harga_jual;

    }

    function getTarifAktif($kode_tarif, $kode_klas){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        /*get tarif aktif*/
        $tarif_aktif = $db->get_where('mt_tgl_tarif', array('status' => 1) )->row();
        
        $db->select('mt_master_tarif_detail.kode_master_tarif_detail,mt_master_tarif_detail.kode_tarif,mt_master_tarif_detail.kode_klas,mt_master_tarif_detail.bill_rs, mt_master_tarif_detail.bill_dr1, mt_master_tarif_detail.bill_dr2, mt_master_tarif_detail.bill_dr3, mt_master_tarif_detail.bhp, mt_master_tarif_detail.alkes, mt_master_tarif_detail.alat_rs, mt_master_tarif_detail.pendapatan_rs,mt_master_tarif_detail.kamar_tindakan, mt_master_tarif.nama_tarif, mt_klas.nama_klas, mt_jenis_tindakan.jenis_tindakan as nama_jenis_tindakan, mt_master_tarif_detail.revisi_ke, mt_master_tarif.jenis_tindakan, mt_master_tarif.kode_bagian, mt_master_tarif_detail.total, mt_master_tarif_detail.revisi_ke, b.nama_tarif as tingkat, c.nama_tarif as tipe_operasi, c.kode_tarif as kode_jenis_bedah');
        $db->from('mt_master_tarif_detail');
        $db->join('mt_master_tarif','mt_master_tarif.kode_tarif=mt_master_tarif_detail.kode_tarif','left');
        $db->join('mt_master_tarif b','b.kode_tarif=mt_master_tarif.referensi','left');
        $db->join('mt_master_tarif c','c.kode_tarif=b.referensi','left');
        $db->join('mt_klas','mt_klas.kode_klas=mt_master_tarif_detail.kode_klas','left');
        $db->join('mt_jenis_tindakan','mt_jenis_tindakan.kode_jenis_tindakan=mt_master_tarif.jenis_tindakan','left');
        $db->where('mt_master_tarif_detail.kode_tarif='.$kode_tarif.' and (mt_master_tarif_detail.kode_klas='.$kode_klas.' or mt_master_tarif_detail.kode_klas=0)');
        //$db->where( array('mt_master_tarif_detail.kode_tarif' => $kode_tarif, 'mt_master_tarif_detail.kode_klas' => $kode_klas, 'mt_master_tarif_detail.is_active' => 'Y') );

        $db->group_by('mt_master_tarif_detail.kode_master_tarif_detail,mt_master_tarif_detail.kode_tarif,mt_master_tarif_detail.kode_klas,mt_master_tarif_detail.bill_rs, mt_master_tarif_detail.bill_dr1, mt_master_tarif_detail.bill_dr2, mt_master_tarif_detail.bill_dr3, mt_master_tarif_detail.bhp, mt_master_tarif_detail.alkes, mt_master_tarif_detail.alat_rs, mt_master_tarif_detail.pendapatan_rs, mt_master_tarif.nama_tarif, mt_klas.nama_klas, mt_jenis_tindakan.jenis_tindakan, mt_master_tarif_detail.kamar_tindakan,mt_master_tarif_detail.revisi_ke, mt_master_tarif.jenis_tindakan, mt_master_tarif.kode_bagian, mt_master_tarif_detail.total, mt_master_tarif_detail.revisi_ke,b.nama_tarif, c.nama_tarif, c.kode_tarif');
        $db->having('mt_master_tarif_detail.revisi_ke = (SELECT MAX(t2.revisi_ke) FROM mt_master_tarif_detail t2 WHERE mt_master_tarif_detail.kode_master_tarif_detail=t2.kode_master_tarif_detail) ');

        $db->order_by('mt_master_tarif_detail.revisi_ke, mt_master_tarif_detail.kode_master_tarif_detail','DESC');
        $db->limit(1);
        $result = $db->get();
        // print_r($db->last_query());die;
        return $result;

    }

    function getTarifMultipleDokter($arrayKodeDokter){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        $dataexc = array();
        foreach ($arrayKodeDokter as $k => $v) {
            $countDr = $k + 1;
            $dataexc['bill_dr'.$countDr.''] = $CI->regex->_genRegex($_POST['bill_dr'.$countDr.''],'RGXINT');
            $dataexc['kode_dokter'.$countDr.''] = $CI->regex->_genRegex($v,'RGXINT');
        }

        return $dataexc;

    }

    function getTarifForinsert(){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        $data = array(
            'bill_rs' => $_POST['bill_rs'] * $_POST['pl_jumlah'],
            'bill_dr1' => $_POST['bill_dr1'] * $_POST['pl_jumlah'],
            'bill_dr2' => $_POST['bill_dr2'] * $_POST['pl_jumlah'],
            'bill_dr3' => $_POST['bill_dr3'] * $_POST['pl_jumlah'],
            'bhp' => $_POST['bhp'] * $_POST['pl_jumlah'],
            'pendapatan_rs' => $_POST['pendapatan_rs'] * $_POST['pl_jumlah'],
            'alat_rs' => $_POST['alat_rs'] * $_POST['pl_jumlah'],
            'kamar_tindakan' => $_POST['kamar_tindakan'] * $_POST['pl_jumlah'],
            );

        return $data;

    }

    public function insert_tarif_by_jenis_tindakan($data, $jenis_tindakan){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        /*get tarif*/
        $where_str = '';
        if($data['kode_perusahaan']==120){

            if($jenis_tindakan!=13){
                //$where_str = 'and nama_tarif like '."'%BPJS'".'';
                if ($data['kode_bagian']=='020101') {
                    $where_str = 'and kode_tarif='."'20101001'".'';
                }else{
                    $where_str = 'and kode_tarif=41';
                }
            }else{
                if ($data['kode_bagian']=='020101') {
                    $where_str = 'and kode_tarif='."'20101039'".'';
                }else{
                    $where_str = 'and kode_tarif=39';

                }
            }

        }else{

            $where_str = 'and nama_tarif not like '."'%BPJS'".' and nama_tarif not like '."'%Tindakan'".'';
           
        }

        $db->select('a.*, b.nama_tarif');
        $db->from('mt_master_tarif_detail a, mt_master_tarif b');
        $db->where('a.kode_tarif = (select top 1 kode_tarif from mt_master_tarif where (kode_bagian='."'".$data['kode_bagian']."'".' or kode_bagian=0) and jenis_tindakan='.$jenis_tindakan.' '.$where_str.' ORDER BY "is_old" ASC, nama_tarif desc) ');
        $db->where('a.kode_tarif=b.kode_tarif');
        $db->where('(a.kode_klas='.$data['kode_klas'].' or a.kode_klas=0)');
        $db->order_by('a.revisi_ke', 'DESC');
        $row_data = $db->get()->row();
        print_r($db->last_query());die;
        

        /*data for execute*/
        $kode_trans_pelayanan = $CI->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');
        $tarif_data = array();
        $tarif_data['kode_trans_pelayanan'] = $kode_trans_pelayanan;
        $tarif_data['kode_tarif'] = ($row_data->kode_tarif)?$row_data->kode_tarif:'';
        $tarif_data['jenis_tindakan'] = $jenis_tindakan;
        $tarif_data['nama_tindakan'] = ($row_data->nama_tarif)?$row_data->nama_tarif:'';
        $tarif_data['kode_master_tarif_detail'] = ($row_data->kode_master_tarif_detail)?$row_data->kode_master_tarif_detail:'';
        $tarif_data['bill_rs'] = ($row_data->bill_rs)?$row_data->bill_rs:'';
        $tarif_data['bill_dr1'] = ($row_data->bill_dr1)?$row_data->bill_dr1:'';
        $tarif_data['pendapatan_rs'] = ($row_data->pendapatan_rs)?$row_data->pendapatan_rs:'';

        $mergeData = array_merge($tarif_data, $data);
        
        $db->insert('tc_trans_pelayanan', $mergeData);


        return true;
    }

    public function insert_tarif_APD($data, $jenis_tindakan){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        /*get tarif*/
        
        $db->select('a.*, b.nama_tarif');
        $db->from('mt_master_tarif_detail a, mt_master_tarif b');
        $db->where('a.kode_tarif=b.kode_tarif');
        $db->where('a.kode_tarif=10101073');
        $db->order_by('a.revisi_ke', 'DESC');
        $db->limit(1);
        $row_data = $db->get()->row();
        // print_r($db->last_query());die;
        /*data for execute*/
        $kode_trans_pelayanan = $CI->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');
        $tarif_data = array();
        $tarif_data['kode_trans_pelayanan'] = $kode_trans_pelayanan;
        $tarif_data['kode_tarif'] = ($row_data->kode_tarif)?$row_data->kode_tarif:'';
        $tarif_data['jenis_tindakan'] = $jenis_tindakan;
        $tarif_data['nama_tindakan'] = ($row_data->nama_tarif)?$row_data->nama_tarif:'';
        $tarif_data['kode_master_tarif_detail'] = ($row_data->kode_master_tarif_detail)?$row_data->kode_master_tarif_detail:'';
        $tarif_data['bill_rs'] = ($row_data->bill_rs)?$row_data->bill_rs:'';
        $tarif_data['bill_dr1'] = ($row_data->bill_dr1)?$row_data->bill_dr1:'';
        $tarif_data['pendapatan_rs'] = ($row_data->pendapatan_rs)?$row_data->pendapatan_rs:'';
        $tarif_data['status_selesai'] = 2;

        $mergeData = array_merge($tarif_data, $data);
        // print_r($mergeData);die;
        $db->insert('tc_trans_pelayanan', $mergeData);
        return true;
    }

}

?>