<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Csm_dokumen_klaim extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'casemix/Csm_dokumen_klaim');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Csm_dokumen_klaim_model', 'Csm_dokumen_klaim');
        /*enable profiler*/
        $this->output->enable_profiler(false);


    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => 'Dokumen Klaim',
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Csm_dokumen_klaim/index', $data);
    }

    public function show_data() { 
        /*define variable data*/
        $data = array(
            'title' => 'Dokumen Klaim',
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Csm_dokumen_klaim/index2', $data);
    }
    
    public function get_data()
    {
        /*get data from model*/
        if(isset($_GET['search'])){
            $list = $this->Csm_dokumen_klaim->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center">
                            <label class="pos-rel">
                                <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_registrasi.'"/>
                                <span class="lbl"></span>
                            </label>
                          </div>';
                $row[] = '<div class="left"><a href="'.base_url().$row_list->csm_dk_fullpath.'" target="_blank">'.$row_list->csm_rp_no_sep.'</a></div>';
                $row[] = $row_list->no_registrasi;
                $row[] = $row_list->csm_rp_no_mr;
                $row[] = strtoupper($row_list->csm_rp_nama_pasien);
                $row[] = '<i class="fa fa-angle-double-right green"></i> '.$this->tanggal->formatDate($row_list->csm_rp_tgl_masuk);
                $row[] = '<i class="fa fa-angle-double-left red"></i> '.$this->tanggal->formatDate($row_list->csm_rp_tgl_keluar);

                $row[] = '<div class="center">'.$row_list->csm_dk_tipe.'</div>';
                $row[] = '<div align="right">'.number_format($row_list->csm_dk_total_klaim).'</div>';
                $row[] = $this->tanggal->formatDate($row_list->created_date).'<br>by : '.$row_list->created_by;
                $data[] = $row;
            }
            $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Csm_dokumen_klaim->count_all(),
                        "recordsFiltered" => $this->Csm_dokumen_klaim->count_filtered(),
                        "data" => $data,
                );
        }else{
            $data = array();
            $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => 0,
                        "recordsFiltered" => 0,
                        "data" => $data,
                );
        }
        
        //output to json format
        echo json_encode($output);
    }

    public function find_data()
    {   
        $output = array(
                        "recordsTotal" => $this->Csm_dokumen_klaim->count_all(),
                        /*"recordsFiltered" => $this->Csm_dokumen_klaim->count_filtered(),*/
                        "data" => $_POST,
                );
        echo json_encode($output);
    }

    public function get_content_data()
    {
        /*get data from model*/
        $list = $this->Csm_dokumen_klaim->get_data();
        //output to json format
        return $list;
    }

    public function html_content($data)
    {
        /*get data from model*/
        $html = ' <table border="1">
                        <thead>
                          <tr>  
                            <th width="30px" align="center">No</th>
                            <th>No. SEP</th>
                            <th>No. MR</th>
                            <th width="150px">Nama Pasien</th>
                            <th width="150px">Tanggal Transaksi</th>
                            <th width="70px">Tipe (RI/RJ)</th>
                            <th width="70px" align="center">Total Klaim</th>
                          </tr>
                        </thead>
                        <tbody>';
        $no = 0;
        foreach ($data as $key => $value) { $no++;
            $html .= '<tr>  
                        <td width="30px" align="center">'.$no.'</td>
                        <td>'.$value->csm_rp_no_sep.'</td>
                        <td>'.$value->csm_rp_no_mr.'</td>
                        <td width="150px">'.$value->csm_rp_nama_pasien.'</td>
                        <td width="150px">'.$value->tgl_transaksi_kasir.'</td>
                        <td width="70px" align="center">'.$value->csm_dk_tipe.'</td>
                        <td width="70px" align="right">'.number_format($value->csm_dk_total_klaim).'</td>
                      </tr>';
                      $arr_subtotal[] = $value->csm_dk_total_klaim;
                      $tot_pasien_ri[] = ($value->csm_dk_tipe=='RI')?1:0;
                      $tot_pasien_rj[] = ($value->csm_dk_tipe=='RJ')?1:0;
                      $tot_pasien_ri_bill[] = ($value->csm_dk_tipe=='RI')?$value->csm_dk_total_klaim:0;
                      $tot_pasien_rj_bill[] = ($value->csm_dk_tipe=='RJ')?$value->csm_dk_total_klaim:0;
        }
        $html .= '<tr>  
                        <td colspan="6" align="center"></td>
                        <td width="70px" align="right">'.number_format(array_sum($arr_subtotal)).'</td>
                      </tr>';

        $html .= '</tbody>
                  </table>';

        $html .= '<br>';
        $html .= '<p style="font-size:12px"><b>Resume Klaim :</b></p>';
        $html .= '<table border="1">
                    <tr>  
                        <th colspan="3">Jenis Pasien</th>
                        <th>Total Pasien</th>
                        <th>Total Klaim</th>
                      </tr>

                      <tr>  
                        <td colspan="3">Pasien Rawat Inap (RI)</td>
                        <td>'.array_sum($tot_pasien_ri).'</td>
                        <td>'.number_format(array_sum($tot_pasien_ri_bill)).'</td>
                      </tr>
                      <tr>  
                        <td colspan="3">Pasien Rawat Jalan (RJ)</td>
                        <td>'.array_sum($tot_pasien_rj).'</td>
                        <td>'.number_format(array_sum($tot_pasien_rj_bill)).'</td>
                      </tr>

                <tbody>';

        $html .= '</tbody>
                  </table>';

        //output to json format
        return $html;
    }


}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
