<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Csm_billing_pasien.php");
class Csm_reg_not_merge extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'casemix/Csm_reg_not_merge');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Csm_reg_not_merge_model', 'Csm_reg_not_merge');
        $this->load->model('Csm_billing_pasien_model', 'Csm_billing_pasien');

        /*load module*/
        $this->load->module('Templates/Templates.php');
        $this->load->module('Templates/Export_data.php');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        $this->cbpModule = new Csm_billing_pasien;

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
        $this->load->view('Csm_reg_not_merge/index', $data);
    }

    
    public function get_data()
    {
        //print_r($_GET['num']);die;
        /*get data from model*/
        if( isset($_GET['search']) ){
            $list = $this->Csm_reg_not_merge->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $str_type = $row_list->csm_rp_tipe;
                $det_data = $this->Csm_billing_pasien->getDetailData($row_list->no_registrasi);
                $decode_data = json_decode($det_data);
                $no++;
                $row = array();
                $link = 'casemix/Migration';

                $row[] = '<div class="center">
                            <label class="pos-rel">
                                <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_registrasi.'"/>
                                <span class="lbl"></span>
                            </label>
                          </div>';
                $row[] = '<a href="#" onclick="getMenu('."'".$link.'/editBilling/'.$row_list->no_registrasi.''."/".$str_type."'".')">'.$row_list->no_registrasi.'</a>';
                $row[] = '<div class="center"><input type="hidden" id="'.$row_list->no_registrasi.'" class="form-control" name="no_sep['.$row_list->no_registrasi.']" value="'.$row_list->csm_rp_no_sep.'"> '.$row_list->csm_rp_no_sep.'  </div>';
                
                $row[] = $row_list->csm_rp_no_mr;
                $row[] = strtoupper($row_list->csm_rp_nama_pasien);
                $row[] = '<i class="fa fa-angle-double-right green"></i> '.$this->tanggal->formatDate($row_list->csm_rp_tgl_masuk).'<br><i class="fa fa-angle-double-left red"></i> '.$this->tanggal->formatDate($row_list->csm_rp_tgl_keluar);
                $row[] = $row_list->csm_rp_nama_dokter.'<br><span style="font-size:11px"><b>('.$row_list->csm_rp_nama_dokter.')</b></span>';
                
                $row[] = '<div class="center"><input type="hidden" id="type_'.$row_list->no_registrasi.'" class="form-control" name="form_type['.$row_list->no_registrasi.']" value="'.$str_type.'">'.$str_type.'</div>';
                
                $row[] = '<div class="center" ><i class="fa fa-check bigger-200 green"></i><br><span style="font-size:10px">By : '.$row_list->created_by.'<br>'.$this->tanggal->formatDateTime($row_list->created_date).'</span></div>';

                $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-primary" onclick="submit('.$row_list->no_registrasi.')"><i class="fa fa-arrow-to-bottom bigger-50"></i> Submit</a></div>';

                       
                $data[] = $row;
            }
            $recordsTotal = $this->Csm_reg_not_merge->count_all();
            $recordsFiltered = $this->Csm_reg_not_merge->count_filtered();
        }else{
            $data = array();
            $recordsTotal = 0;
            $recordsFiltered = 0;
        }
            
            
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $recordsTotal,
                        "recordsFiltered" => $recordsFiltered,
                        "data" => $data,
                );
        //print_r($output);die;
        //output to json format
        echo json_encode($output);
    }
    
    public function find_data()
    {   
        $output = array(
                        "recordsTotal" => $this->Csm_reg_not_merge->count_all(),
                        //"recordsFiltered" => $this->registrasi_adm->count_filtered_data(),
                        "data" => $_POST,
                );
        echo json_encode($output);
    }

    public function get_content_data()
    {
        /*get data from model*/
        $list = $this->Csm_reg_not_merge->get_data();
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

    public function submit_multiple()
    {
        $data = array();
        if ( isset($_POST['ID']) ) {
            # code...
            $arr_id = explode(',', $_POST['ID']);
            foreach ($arr_id as $key => $value) {
                /*find original data*/
                $original = $this->db->get_where('csm_reg_pasien', array('no_registrasi'=>$value))->row();
                $this->db->delete('csm_dokumen_export', array('no_registrasi' => $value));
                /*created document name*/
                $createDocument = $this->Csm_billing_pasien->createDocument($value, $original->csm_rp_tipe);
                // print_r($createDocument);die;
                foreach ($createDocument as $k_cd => $v_cd) {
                    
                    # code...
                    $explode = explode('-', $v_cd);
                    /*explode result*/
                    $named = str_replace('BILL','',$explode[0]);
                    $no_mr = $explode[1];
                    $exp_no_registrasi = $explode[2];
                    $unique_code = $explode[3];

                    /*create and save download file pdf*/
                    //$cbpModule = new Csm_billing_pasien;
                    if( $this->cbpModule->getContentPDF($exp_no_registrasi, $named, $unique_code, 'F') ) :
                    /*save document to database*/
                    /*csm_reg_pasien*/
                    $filename = $named.'-'.$no_mr.$exp_no_registrasi.$unique_code.'.pdf';
                    
                    $doc_save = array(
                        'no_registrasi' => $this->regex->_genRegex($exp_no_registrasi, 'RGXQSL'),
                        'csm_dex_nama_dok' => $this->regex->_genRegex($filename, 'RGXQSL'),
                        'csm_dex_jenis_dok' => $this->regex->_genRegex($v_cd, 'RGXQSL'),
                        'csm_dex_fullpath' => $this->regex->_genRegex('uploaded/casemix/log/'.$filename.'', 'RGXQSL'),
                    );
                    $doc_save['created_date'] = date('Y-m-d H:i:s');
                    $doc_save['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                    /*check if exist*/
                    if ( $this->Csm_billing_pasien->checkIfDokExist($exp_no_registrasi, $filename) == FALSE ) {
                        $this->db->insert('csm_dokumen_export', $doc_save);
                    }
                    endif;
                    /*insert database*/
                }

                $data[] = base_url().'casemix/Csm_billing_pasien/mergePDFFiles/'.$value.'/'.$original->csm_rp_tipe.'';
            }

            
        }
        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'redirect_data' =>$data));

    }

}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
