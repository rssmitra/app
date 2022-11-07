<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Req_pembelian_detail extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/permintaan/Req_pembelian_detail');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/permintaan/Req_pembelian_detail_model', 'Req_pembelian_detail');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';
    }

    public function index() { 
        // echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => 'Permintaan Pembelian',
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('permintaan/Req_pembelian_detail/index', $data);
    }


    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Req_pembelian_detail->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $flag = ($_GET['flag']=='medis')?'m':'nm';
            
            if ( $row_list->status_batal == "1" ) {
                    $status = '<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>';
                    $text = 'Tidak disetujui';
            }else{

                if( $row_list->tgl_acc == NULL ){

                    if ( $row_list->status_kirim == NULL ) {
                        $status = '<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>';
                        $text = '<a href="#" target="_blank" class="btn btn-xs btn-white btn-warning" onclick="proses_persetujuan('.$row_list->id_tc_permohonan.')">Kirim Pengadaan</a>';
                    }else{
                        if($row_list->tgl_pemeriksa == NULL){
                            $status = '<div class="left"><i class="fa fa-exclamation-triangle bigger-150 orange"></i></div>';
                            $text = 'Menunggu Persetujuan<br>'.$this->master->get_ttd_data('verifikator_'.$flag.'_1','value');
                        }
            
                        if($row_list->tgl_pemeriksa != NULL AND $row_list->tgl_penyetuju == NULL){
                            $status = '<div class="left"><i class="fa fa-exclamation-triangle bigger-150 orange"></i></div>';
                            $text = 'Menunggu Persetujuan<br>'.$this->master->get_ttd_data('verifikator_'.$flag.'_2','value');
                        }
                    }
                    
                }else{
                    if ( $row_list->status_kirim == NULL ) {
                        $status = '<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>';
                        $text = 'Persetujuan';
                    }else{
                        $status = '<div class="center"><i class="fa fa-check-circle bigger-150 green"></i></div>';
                        $text = 'Disetujui';
                    }
                }
                
            }


            

            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_permohonan.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->id_tc_permohonan;
            if( $row_list->status_kirim != 1 ){
                $row[] = '<div class="center">
                            <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-inverse">
                                <li>'.$this->authuser->show_button('purchasing/permintaan/Req_pembelian_detail?flag='.$_GET['flag'].'','R',$row_list->id_tc_permohonan,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/permintaan/Req_pembelian_detail?flag='.$_GET['flag'].'','U',$row_list->id_tc_permohonan,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/permintaan/Req_pembelian_detail?flag='.$_GET['flag'].'','D',$row_list->id_tc_permohonan,6).'</li>
                            </ul>
                            </div>
                        </div>';
            }else{
                $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'".base_url().'purchasing/permintaan/Req_pembelian_detail/print_preview/'.$row_list->id_tc_permohonan.'?flag='.$_GET['flag']."'".', '."'PERMINTAAN PEMBELIAN'".', 1000, 550)" ><i class="fa fa-print bigger-150 inverse"></a></div>';
            }
            
            $row[] = '<div class="center">'.$row_list->id_tc_permohonan.'</div>';
            $row[] = $row_list->kode_permohonan;
            $row[] = $this->tanggal->formatDate($row_list->tgl_permohonan);
            // log
            $log = json_decode($row_list->created_by);
            $petugas = isset($log->fullname)?$log->fullname:$row_list->username;
            $row[] = '<div class="center">'.ucwords($petugas).'</div>';
            
            $row[] = '<div class="center">'.$row_list->jenis_permohonan_name.'</div>';
            $row[] = '<div class="left">'.$row_list->no_acc.'<br>'.$this->tanggal->formatDate($row_list->tgl_acc).'</div>';
            $row[] = '<div class="center">'.$row_list->total_brg.'</div>';
            
            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = '<div class="left">'.$text.'</div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Req_pembelian_detail->count_all(),
                        "recordsFiltered" => $this->Req_pembelian_detail->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_detail($id){
        $result = $this->Req_pembelian_detail->get_detail_brg_permintaan($_GET['flag'], $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $_GET['flag'],
            );
        // echo '<pre>'; print_r($data);
        $temp_view = $this->load->view('permintaan/Req_pembelian_detail/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
