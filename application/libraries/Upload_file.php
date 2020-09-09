<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

final Class upload_file {

    function process($params)
    {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        $vdir_upload = $params['path'];
        $tipe_file   = $_FILES['file']['type'];
        $vfile_upload = $vdir_upload . $params['name'];

        if(move_uploaded_file($_FILES[$params['inputname']]["tmp_name"], $vfile_upload)){
            return true;
        }else{
          return false;
        }

    } 

    function doUpload($inputname, $path)
    {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('regex');

        $random = rand(1,99999);
        $unique_filename = $CI->regex->_genRegex(str_replace(' ','_', $random.preg_replace('/\s+/', '', $_FILES[''.$inputname.'']['name'])), 'RGXFILENAME');

        $vfile_upload = $path . $unique_filename;
        $type_file = $_FILES[''.$inputname.'']['type'];
        if(move_uploaded_file($_FILES[$inputname]["tmp_name"], $vfile_upload)){
            return $unique_filename;
        }else{
            return false;
        }

    }

    /*function doUpload($inputname, $path, $width='', $height='', $wdt_thumb='', $hgt_thumb='')
    {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('regex');
        $CI->load->library('upload');
        $CI->load->library('image_lib');

        $original_path = $path;
        $resized_path = $path.'resized';
        $thumbs_path = $path.'thumb';

        $random = rand(1,99);
        $unique_filename = $CI->regex->_genRegex(str_replace(' ','_', $random.preg_replace('/\s+/', '', $_FILES[''.$inputname.'']['name'])), 'RGXFILENAME');

        $vfile_upload = $path . $unique_filename;

        $config['upload_path'] = $original_path; //path folder
        //$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
        $config['encrypt_name'] = FALSE; //Enkripsi nama yang terupload
        $config['max_size'] = 61440; //Enkripsi nama yang terupload
 
        $CI->upload->initialize($config);
        if(!empty($_FILES[$inputname]['name'])){

            if ($CI->upload->do_upload($inputname)){
                $data_upl = $CI->upload->data(); 
                print_r($data_upl);die;
                //Compress Image

                //your desired config for the resize() function
                if (in_array($_FILES[$inputname]['type'], array('jpg','jpeg','png'))) {
                    # code...
                    $config1 = array(
                      'source_image' => $data_upl['full_path'], //path to the uploaded image
                      'new_image' => $resized_path,
                      'maintain_ratio' => false,
                      'width' => ($width!='')?$width:$data_upl['image_width'],
                      'height' => ($height!='')?$height:$data_upl['image_height'],
                    );
                    $CI->image_lib->initialize($config1);
                    $CI->image_lib->resize();

                   // for the Thumbnail image
                   $config2 = array(
                    'source_image' => $data_upl['full_path'],
                    'new_image' => $thumbs_path,
                    'maintain_ratio' => true,
                    'width' => ($wdt_thumb!='')?$wdt_thumb:85,
                    'height' => ($hgt_thumb!='')?$hgt_thumb:85,
                   );
                   //here is the second thumbnail, notice the call for the initialize() function again
                   $CI->image_lib->initialize($config2);
                   $CI->image_lib->resize();
                }
            

           return $data_upl['file_name'];

           }

        }else{
            return false;
        }

    }*/


    function getUploadedFile($wp_id){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        $html = '';
        $db->where('wp_id', $wp_id);
        $files = $db->get('web_attachment')->result();

        $html = '<h3><b><i class="fa fa-file"></i> Attachment Files</b></h3> <br>';
        $html .= '<table id="attc_table_id" class="table table-striped table-bordered">';
        $html .= '<tr style="background-color:darkcyan; color:white">';
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
        if(count($files) > 0){
            foreach ($files as $key => $row_list) {
                # code...
                $html .= '<tr id="tr_id_'.$row_list->wa_id.'">';
                    $html .= '<td align="center">'.$no.'</td>';
                    $html .= '<td align="left">'.$row_list->wa_name.'</td>';
                    $html .= '<td align="left">'.$row_list->wa_owner.'</td>';
                    $html .= '<td align="left">'.$row_list->wa_filename.'</td>';
                    $size_to_kb = $row_list->wa_size / 1024;
                    $html .= '<td align="center">'.number_format($size_to_kb).' KB</td>';
                    $html .= '<td align="center">'.$row_list->wa_type.'</td>';
                    $html .= '<td align="center">'.$row_list->created_date.'</td>';
                    $html .= '<td align="center"><a href="Templates/Attachment/download_attachment?fname='.$row_list->wa_fullpath.'" style="color:red">Download</a></td>';
                    //$html .= '<td align="center"><a href="#" class="delete_attachment" data-id="'.$row_list->wa_id.'"><i class="fa fa-times-circle red"></i></a></td>';
                    $html .= '<td align="center"><a href="#" class="delete_attachment" onclick="delete_attachment('.$row_list->wa_id.')"><i class="fa fa-times-circle red"></i></a></td>';
                $html .= '</tr>';
            $no++;
            }
        }else{
            $html .=  '<tr><td colspan="9">- File not found -</td></tr>';
        }
        
        $html .= '</table>';





        return $html;

    }

    function CsmdoUploadMultiple($params)
    {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('upload');
        //$CI->load->library('image_lib'); 
        $getData = array();
        foreach ($_FILES[''.$params['name'].'']['name'] as $i=>$values) {

              $_FILES['userfile']['name']     = $_FILES[''.$params['name'].'']['name'][$i];
              $_FILES['userfile']['type']     = $_FILES[''.$params['name'].'']['type'][$i];
              $_FILES['userfile']['tmp_name'] = $_FILES[''.$params['name'].'']['tmp_name'][$i];
              $_FILES['userfile']['error']    = $_FILES[''.$params['name'].'']['error'][$i];
              $_FILES['userfile']['size']     = $_FILES[''.$params['name'].'']['size'][$i];

              $random = rand(1,99);
              $custom_dok_name = isset($_POST['pf_file_name']) ? preg_replace('/\s+/','-', $_POST['pf_file_name'][$i]).'-'.$_POST['csm_rp_no_sep'] : 'Lampiran-'.$random ;
              $nama_file_unik = $custom_dok_name.'-'.preg_replace('/\s+/','', $_FILES[''.$params['name'].'']['name'][$i]);
              //$nama_file_unik = preg_replace('/\s+/', '-', $custom_dok_name).'-'.$_POST['csm_rp_no_sep'];

              $type_file = $_FILES[''.$params['name'].'']['type'][$i];

              $config = array(
                'allowed_types' => '*',
                'file_name'     => $nama_file_unik,
                'max_size'      => '999999',
                'overwrite'     => TRUE,
                'remove_spaces' => TRUE,
                'upload_path'   => $params['path']
              );

              $CI->upload->initialize($config);

              if ($_FILES['userfile']['tmp_name'][$i]) {

                  if ( ! $CI->upload->do_upload()) :
                    $error = array('error' => $CI->upload->display_errors());
                  else :

                    $data = array( 'upload_data' => $CI->upload->data() );
                    /*cek attchment exist*/
                    
                    $doc_save = array(
                        'no_registrasi' => $params['ref_id'],
                        'csm_dex_nama_dok' => $nama_file_unik,
                        'csm_dex_jenis_dok' => $type_file,
                        'csm_dex_fullpath' => $params['path'].$nama_file_unik,
                        'is_adjusment' => 'Y',
                    );
                    $doc_save['created_date'] = date('Y-m-d H:i:s');
                    $doc_save['created_by'] = $CI->session->userdata('user')->fullname;
                    $db->insert('csm_dokumen_export', $doc_save);


                    $getData[] = $doc_save;

                  endif;

              }
                
            }

            return $getData;
    }

    function CsmgetUploadedFile($no_registrasi){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        $html = '';
        $db->where('no_registrasi', $no_registrasi);
        $files = $db->get('csm_dokumen_export')->result();

        $html .= '<table id="attc_table_id" class="table table-striped table-bordered">';
        $html .= '<tr style="background-color:darkcyan; color:white">';
            $html .= '<th width="30px" class="center">No</th>';
            $html .= '<th width="100px">File Name</th>';
            $html .= '<th width="100px">Created Date</th>';
            $html .= '<th width="60px" class="center">Download</th>';
            $html .= '<th width="60px" class="center">Delete</th>';
        $html .= '</tr>';
        $no=1;
        if(count($files) > 0){
            foreach ($files as $key => $row_list) {
                # code...
                $html .= '<tr id="tr_id_'.$row_list->csm_dex_id.'">';
                    $html .= '<td align="center">'.$no.'</td>';
                    $html .= '<td align="left">'.$row_list->csm_dex_nama_dok.'</td>';
                    $html .= '<td align="center">'.$CI->tanggal->formatDateTime($row_list->created_date).'</td>';
                    $html .= '<td align="center"><a href="'.base_url().$row_list->csm_dex_fullpath.'" style="color:red" target="_blank">View File</a></td>';
                    $html .= '<td align="center"><a href="#" class="delete_attachment" onclick="delete_attachment_csm('.$row_list->csm_dex_id.')"><i class="fa fa-times-circle red"></i></a></td>';
                $html .= '</tr>';
            $no++;
            }
        }else{
            $html .=  '<tr><td colspan="9">- File not found -</td></tr>';
        }
        
        $html .= '</table>';





        return $html;

    }

    function doUploadMultiple($params)
    {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('upload');
        //$CI->load->library('image_lib'); 
        $getData = array();
        $arr_name = explode(',', $_POST['pf_file_name']);

        foreach ($_FILES[''.$params['name'].'']['name'] as $i=>$values) {

              $_FILES['userfile']['name']     = $_FILES[''.$params['name'].'']['name'][$i];
              $_FILES['userfile']['type']     = $_FILES[''.$params['name'].'']['type'][$i];
              $_FILES['userfile']['tmp_name'] = $_FILES[''.$params['name'].'']['tmp_name'][$i];
              $_FILES['userfile']['error']    = $_FILES[''.$params['name'].'']['error'][$i];
              $_FILES['userfile']['size']     = $_FILES[''.$params['name'].'']['size'][$i];

              $random = rand(1,99);
              $nama_file_unik = $CI->regex->_genRegex(str_replace(' ','_', $random.$_FILES[''.$params['name'].'']['name'][$i]), 'RGXFILENAME');
              
              $type_file = $_FILES[''.$params['name'].'']['type'][$i];

              $config = array(
                'allowed_types' => '*',
                'file_name'     => $nama_file_unik,
                'max_size'      => '999999',
                'overwrite'     => TRUE,
                'remove_spaces' => TRUE,
                'upload_path'   => $params['path']
              );

              $CI->upload->initialize($config);

              if ($_FILES['userfile']['tmp_name']) {

                  if ( ! $CI->upload->do_upload()) :
                    $error = array('error' => $CI->upload->display_errors());
                  else :

                    $data = array( 'upload_data' => $CI->upload->data() );
                    /*cek attchment exist*/
                    
                    $datainsertattc = array(
                        'wp_id' => $params['id'],
                        'wa_name' => ($arr_name[$i]!='')?$arr_name[$i]:'Lampiran File',
                        'wa_filename' => $_FILES[''.$params['name'].'']['name'][$i],
                        'wa_type' => $type_file,
                        'wa_size' => $_FILES[''.$params['name'].'']['size'][$i],
                        'wa_fullpath' => $params['path'].$nama_file_unik,
                        'wa_owner' => $CI->session->userdata('user')->fullname,
                        //'path' => $nama_file_unik,
                        'created_date' => date('Y-m-d H:i:s'),
                    );
                    $CI->db->insert('web_attachment', $datainsertattc);

                    $getData[] = $datainsertattc;

                  endif;

              }
                
            }

            return $getData;
    } 

    function check_existing($params){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        $files = $this->getUploadedFile($params, 'data');
        /*if exist file*/
        if(count($files) > 0 ){
           foreach ($files as $key => $value) {
                if(file_exists($value->attc_fullpath)){
                    unlink($value->attc_fullpath);
                }
                $CI->db->delete('tr_attachment', array('wa_id' => $value->wa_id));
            }
        }

        return true;

    }

	   
}

?>
