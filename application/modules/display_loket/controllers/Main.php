<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends MX_Controller {

    function __construct() {
        parent::__construct();
 
        $this->load->model('main_model','Main'); 

    }

    public function index() {
        $data = array();
        $data['app'] = $this->db->get_where('tmp_profile_app', array('id' => 1))->row();
        $this->load->view('Main/main_view', $data);
    }

    public function antrian_pendaftaran_dt_tbl() {
        $data = array();
        $data['app'] = $this->db->get_where('tmp_profile_app', array('id' => 1))->row();
        $this->load->view('Main/antrian_pendaftaran_dt_tbl', $data);
    }

    public function poli() {
        $data = array();
        $data['app'] = $this->db->get_where('tmp_profile_app', array('id' => 1))->row();
        $this->load->view('Main/main_poli_view', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Main->get_datatables_display();
       // $data = array();
        $no = 0;;
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $sisa_kuota = $row_list->jd_kuota - $row_list->kuota_terpenuhi;
            $clr_loket = ($row_list->status_jadwal == 'Loket dibuka') ? 'background: linear-gradient(357deg, #13d634, #9edc9a);color: white;' : 'background: linear-gradient(357deg, #ecd212, #dcd79a);color: white;';
            $status_jadwal = '';
            if(!in_array($row_list->status_jadwal, array('Loket dibuka','Loket ditutup') )){
                $status_jadwal = $row_list->status_jadwal.'<br>';
            }

            $row[] = '<div style="'.$clr_loket.'">
                        <table width="100%">
                            <tr style="background-color: green">
                                <td colspan="3" style="font-size: 3em; padding-left: 10px; font-weight: bold">'.strtoupper($row_list->nama_bagian).'</td>
                            </tr>
                            <tr style="background-color: white !important">
                                <td rowspan="6" style="width: 100px; vertical-align: top">
                                    <img src="'.base_url().'assets/img/avatar.png" style="width: 250px;" >
                                </td>
                            </tr>
                            <tr style="color: black !important; font-weight: bold; line-height: 1.2">
                                <td><span style="padding-left: 10px; font-size: 1.5em;">Nama Dokter</span><br><span style="padding-left: 10px; font-size: 2em">'.strtoupper($row_list->nama_pegawai).'</span></td>
                            </tr>
                            <tr style="color: black !important; font-weight: bold; line-height: 1.2">
                                <td><span style="padding-left: 10px; font-size: 1.5em;">Jam Praktek</span><br><span style="padding-left: 10px; font-size: 2em">'.$this->tanggal->formatTime($row_list->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($row_list->jd_jam_selesai).'</span></td>
                            </tr>
                            <tr style="color: black !important; font-weight: bold; line-height: 1.2">
                                <td><span style="padding-left: 10px; font-size: 1.5em;">Sisa Kuota</span><br><span style="padding-left: 10px; font-size: 2em">'.$sisa_kuota.'</span></td>
                            </tr>
                            <tr style="color: black !important; font-weight: bold; line-height: 1.2">
                                <td><span style="padding-left: 10px; font-size: 1.5em;">Keterangan</span><br><span style="padding-left: 10px; font-size: 2em">'.strtoupper($status_jadwal).''.strtoupper($row_list->jd_keterangan).'<br>'.strtoupper($row_list->keterangan).'</span></td>
                            </tr>
                        </table>
            </div>';
            

            $data[] = $row;
        }

        $output = array(
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

