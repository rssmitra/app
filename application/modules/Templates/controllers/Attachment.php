<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attachment extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*load model*/
        $this->load->model('attachment_model');
        $this->load->library('lib_menus');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

        // load library
        $this->load->library('qr_code_lib');

    }

    public function index() {
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('attachment/index', $data);
    }

    public function verifyDocument() {
        /*define variable data*/
        // cek dokumen token
        $valid_dok = $this->qr_code_lib->check_valid_qr($_GET);
        // get data from qr
        $docdt = $this->attachment_model->get_detail_doc($_GET);

        // echo $_GET['str']; die;
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('attachment/verify_doc_view', $data);
    }

    public function prosesValidasiDok($kode){
        
        $query = $this->db->get_where('pm_pasienpm_v', array('CAST(kode_penunjang as NVARCHAR(55)) = ' => $kode));

        if($query->num_rows() > 0){
            $response = array(
                'code' => 200,
                'message' => 'Data ditemukan',
                'data' => $query->row(),
            );
        }else{
            $response = array(
                'code' => 300,
                'message' => 'Data tidak ditemukan'
            );
        }

        echo json_encode($response);

    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->attachment_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center"><label class="pos-rel">
                        <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id.'"/>
                        <span class="lbl"></span>
                    </label></div>';
            $row[] = $row_list->id;
            $row[] = '<div class="left">'.$row_list->attc_name.'</div>';
            $row[] = '<div class="center">'.$row_list->owner.'</div>';
            $row[] = '<div class="left">'.$row_list->name.'</div>';
            $row[] = '<div class="center">'.number_format($row_list->size).'</div>';
            $row[] = '<div class="center">'.$row_list->type.'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateForm($row_list->created_date).'</div>';
            $row[] = '<div class="center">Download</div>';
            $row[] = '<div class="center"><div class="hidden-sm hidden-xs action-buttons">
                        '.$this->authuser->show_button('templates/attachment','D',$row_list->id,2).'
                      </div>
                      <div class="hidden-md hidden-lg">
                        <div class="inline pos-rel">
                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto"><i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                <li>'.$this->authuser->show_button('templates/attachment','D','',4).'</li>
                            </ul>
                        </div>
                    </div></div>';        
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->attachment_model->count_all(),
                        "recordsFiltered" => $this->attachment_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_attachment($params){
        $list_attachment = $this->attachment_model->get_attachment_by_params($params);
        $html = '<h3><b><i class="fa fa-file"></i> Attachment Files</b></h3> <br>';
        $html .= '<table id="attc_table_id" class="table table-striped table-bordered">';
        $html .= '<tr style="background-color:#ec2028; color:white">';
            $html .= '<th width="30px" class="center">No</th>';
            $html .= '<th width="100px">Title</th>';
            $html .= '<th width="100px">Owner</th>';
            $html .= '<th width="100px">Filename</th>';
            $html .= '<th width="70px" class="center">Size</th>';
            $html .= '<th width="100px" class="center">Type</th>';
            $html .= '<th width="100px">Created Date</th>';
            $html .= '<th width="60px" class="center">Download</th>';
            $html .= '<th width="60px" class="center">Delete</th>';
        $html .= '</tr>';
        $no=1;
        if(count($list_attachment) > 0){
            foreach ($list_attachment as $key => $row_list) {
                # code...
                $html .= '<tr id="tr_id_'.$row_list->attc_id.'">';
                    $html .= '<td align="center">'.$no.'</td>';
                    $html .= '<td align="left">'.$row_list->attc_filename.'</td>';
                    $html .= '<td align="left">'.$row_list->attc_owner.'</td>';
                    $html .= '<td align="left">'.$row_list->attc_name.'</td>';
                    $size_to_kb = $row_list->attc_size / 1024;
                    $html .= '<td align="center">'.number_format($size_to_kb).' KB</td>';
                    $html .= '<td align="center">'.$row_list->attc_type.'</td>';
                    $html .= '<td align="center">'.$row_list->created_date.'</td>';
                    $html .= '<td align="center"><a href="Templates/attachment/download_attachment?fname='.$row_list->attc_fullpath.'" style="color:red">Download</a></td>';
                    //$html .= '<td align="center"><a href="#" class="delete_attachment" data-id="'.$row_list->attc_id.'"><i class="fa fa-times-circle red"></i></a></td>';
                    
                    $html .= '<td align="center"><a href="#" class="delete_attachment" onclick="delete_attachment('.$row_list->attc_id.')"><i class="fa fa-times-circle red"></i></a></td>';
                $html .= '</tr>';
            $no++;
            }
        }else{
            $html .=  '<tr><td colspan="9">- File not found -</td></tr>';
        }
        
        $html .= '</table>';
        return $html;
    }


    public function download_attachment(){
        $this->load->helper('download');
        $path = ($this->input->get('fname')) ? $this->input->get('fname') : NULL;  
        if(force_download(''.$path.'',NULL)){
            echo 'Download success';
        }else{
            echo 'File doesnt exist';
        }
    }

    public function upload_attachment($params){
        return $this->upload_file->doUploadMultiple($params);
    }

    public function delete_attachment()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->attachment_model->delete_attachment_by_id($id)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function delete_attachment_csm()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->attachment_model->delete_attachment_csm_by_id($id)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function delete_attachment_fr()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->attachment_model->delete_attachment_fr_by_id($id)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }
  

}


/* End of file templates.php */
/* Location: ./application/modules/templates/controllers/templates.php */
