<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

final Class Authuser {

    function filtering_data_by_level_user($table, $user_id){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('session');

        $query = "SELECT level_id FROM tmp_mst_role WHERE role_id IN (SELECT role_id FROM tmp_user_has_role WHERE user_id=".$user_id.")";
        $level_id = $db->query($query)->result();
        
        if (in_array($level_id, array(3))) {
            return $db->where($table.'.created_by', $CI->session->userdata('user')->user_id);
        }else{
            return false;
        }
    }

    function show_button($link, $code, $id='', $style=''){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('session');

        /*check existing*/
        $query = "SELECT action_code
                    FROM tmp_role_has_menu
                    WHERE menu_id = (SELECT menu_id FROM tmp_mst_menu WHERE link='$link') AND role_id IN (SELECT role_id FROM tmp_user_has_role WHERE user_id=".$CI->session->userdata('user')->user_id.")"; 
        $result = $db->query($query);
        if($result->num_rows() > 0){
            $action_code = $result->row()->action_code;
            $str_to_array = explode(',', $action_code); 
            /*ubah link menjad*/
            $exp_code = explode('/', $style);
            $ori_code = (string)$exp_code[0];
            $flag = isset($exp_code[1])?$exp_code[1]:'';
            //print_r($flag);die;
            // $Query_String  = isset($_SERVER['REQUEST_URI']) ? explode("&", explode("?", $_SERVER['REQUEST_URI'])[1] ) : '' ;
            // $repl_link = str_replace("?flag=$flag",'',$link);

            /*switch code to get button*/
            return $this->switch_to_get_btn($str_to_array, $link, $code, $id, $style);
        }else{
            return false;
        }
    }

    function switch_to_get_btn($array, $link, $code, $id, $style=''){

        if(in_array($code, $array)){
            /*get button action*/
            switch ($code) {

                case 'C':
                    $btn = $this->create_button($link, $id, $code.$style);
                    break;
                case 'R':
                    $btn = $this->read_button($link, $id, $code.$style);
                    break;
                case 'U':
                    $btn = $this->update_button($link, $id, $code.$style);
                    break;
                case 'D':
                    $btn = $this->delete_button($link, $id, $code.$style);
                    break;
                
                default:
                    # code...
                    $btn = '';
                    break;
            }
            return $btn;
        }
    }

    function remove_url_query($url, $key) {
        // $url = preg_replace('/(?:&|(\?))' . $key . '=[^&]*(?(1)&|)?/i', "$1", $url);
        // $url = rtrim($url, '?');
        // $url = rtrim($url, '&');

        $url_arr = parse_url($url);
        $query = isset($url_arr['query'])?$url_arr['query']:'';
        $url = str_replace(array($query,'?'), '', $url);

        return $url;
    }

    function create_button($url, $id, $code_style){

        $exp_code = explode('/', $code_style);
        $code = (string)$exp_code[0];
        $flag = isset($exp_code[1])?$exp_code[1]:'';
        $link = $this->remove_url_query($url, 'flag');
        

        switch ($code) {

            /*style for create*/
            case 'C':
                # code...
                $btn = '<button type="button" class="btn btn-white btn-xs btn-info btn-bold" onclick="getMenu('."'".$link.'/form'."'".')"><i class="ace-icon glyphicon glyphicon-plus bigger-50 blue"></i>Create New</button>';
                break;

            case 'C1':
                # code...
                $btn = '<a href="#" class="btn btn-xs btn-primary" onclick="getMenu('."'".$link.'/form'."'".')"><i class="ace-icon glyphicon glyphicon-plus bigger-50"></i>Create New</a>';
                break;

            case 'C11':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-primary" onclick="getMenuTabs('."'".$link.'/form?pgd_id='.$id.''."'".')"><i class="ace-icon glyphicon glyphicon-plus bigger-50"></i>Create New</button>';
                break;

            case 'C2':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-primary" onclick="getMenu('."'".$link.'/form'."'".')"><i class="ace-icon glyphicon glyphicon-plus bigger-50"></i></button>';
                break;
            case 'C3':
                # code...
                $btn = '<button type="button" class="btn btn-white btn-xs btn-info btn-bold" onclick="getMenu('."'".$link.'/form'."'".')"><i class="ace-icon glyphicon glyphicon-plus bigger-50 blue"></i></button>';
                break;

            case 'C4':
                # code...
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/form'."'".')" class="tooltip-success" data-rel="tooltip" title="Add">
                                        <span class="blue">
                                            <i class="ace-icon glyphicon glyphicon-plus bigger-120"></i>
                                        </span>
                                    </a>';
                break;

            case 'C6':
                # code...
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/form'."'".')"title="Add">Add</a>';
                break;

            case 'C7':
                # code...
                $Query_String  = isset($_SERVER['REQUEST_URI']) ? explode("&", explode("?", $_SERVER['REQUEST_URI'])[1] ) : '' ;
                $param_string = http_build_query($_GET);

                $btn = '<a href="#" class="btn btn-xs btn-primary" onclick="getMenu('."'".$link.'/form?'.$param_string.''."'".')"><i class="ace-icon glyphicon glyphicon-plus bigger-50"></i>Create New</a>';
                break;

            case 'CC1':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-primary" onclick="getMenu('."'".$link.''."'".')"><i class="ace-icon glyphicon glyphicon-plus bigger-50"></i></button>';
                break;

            default:
                # code...
                $btn = '';
                break;
        }
        return $btn;
    }

    function read_button($url, $id, $code_style){
        $exp_code = explode('/', $code_style);
        $code = (string)$exp_code[0];
        $flag = isset($exp_code[1])?$exp_code[1]:'';
        $link = $this->remove_url_query($url, 'flag');
        

        //print_r($code_style);die;
        switch ($code) {

            /*style button for read action*/
            case 'R':
                # code...
                $btn = '<button type="button" class="btn btn-white btn-xs btn-info btn-bold" onclick="getMenu('."'".$link.'/show/'.$id.''."'".')"><i class="ace-icon fa fa-eye bigger-50 blue"></i>View</button>';
                break;

            case 'R1':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-info" onclick="getMenu('."'".$link.'/show/'.$id.''."'".')"><i class="ace-icon fa fa-eye bigger-50"></i>View</button>';
                break;
            case 'R2':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-info" onclick="getMenu('."'".$link.'/show/'.$id.''."'".')"><i class="ace-icon fa fa-eye bigger-50"></i></button>';
                break;
            case 'R21':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-info" onclick="getMenuTabs('."'".$link.'/show/'.$id.''."'".')"><i class="ace-icon fa fa-eye bigger-50"></i></button>';
                break;
            case 'R3':
                # code...
                $btn = '<button type="button" class="btn btn-white btn-xs btn-info btn-bold" onclick="getMenu('."'".$link.'/show/'.$id.''."'".')"><i class="ace-icon fa fa-eye bigger-50 blue"></i></button>';
                break;

            case 'R4':
                # code...
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')" class="tooltip-success" data-rel="tooltip" title="View">
                                        <span class="info">
                                            <i class="ace-icon fa fa-eye bigger-120"></i>
                                        </span>
                                    </a>';
                break;

            case 'R6':
                # code...
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/show/'.$id.''."'".')">Read</a>';
                break;

            case 'R67':
                # code...
                $Query_String  = explode("&", explode("?", $_SERVER['REQUEST_URI'])[1] );
                $param_string = isset($Query_String[0])?$Query_String[0]:'';
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/show/'.$id.'?'.$param_string.''."'".')">Read</a>';
                break;

            case 'RC1':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-info" onclick="getMenu('."'".$link.'/show?id='.$id.'&flag='.$flag.''."'".')"><i class="ace-icon fa fa-eye bigger-50"></i></button>';
                break;

            default:
                # code...
                $btn = '';
                break;
        }
        return $btn;
    }

    function update_button($url, $id, $code_style){
        $exp_code = explode('/', $code_style);
        $code = (string)$exp_code[0];
        $flag = isset($exp_code[1])?$exp_code[1]:'';
        $link = $this->remove_url_query($url, 'flag');
        
        //print_r($code_style);die;
        switch ($code) {

            /*style button for read action*/
            case 'U':
                # code...
                $btn = '<button type="button" class="btn btn-white btn-xs btn-success btn-bold" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')"><i class="ace-icon fa fa-pencil bigger-50 blue"></i>Edit</button>';
                break;

            case 'U1':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-success" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')"><i class="ace-icon fa fa-pencil bigger-50"></i>Edit</button>';
                break;
            case 'U2':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-success" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')"><i class="ace-icon fa fa-edit bigger-50"></i></button>';
                break;
            case 'U21':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-success" onclick="getMenuTabs('."'".$link.'/form/'.$id.''."'".')"><i class="ace-icon fa fa-pencil bigger-50"></i></button>';
                break;
            case 'U3':
                # code...
                $btn = '<button type="button" class="btn btn-white btn-xs btn-success btn-bold" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')"><i class="ace-icon fa fa-pencil bigger-50 blue"></i></button>';
                break;

             case 'U4':
                # code...
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')" class="tooltip-success" data-rel="tooltip" title="Update">
                                        <span class="green">
                                            <i class="ace-icon fa fa-pencil bigger-120"></i>
                                        </span>
                                    </a>';
                break;

            case 'U6':
                # code...
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/form/'.$id.''."'".')" title="Update">Update</a>';
                break;

            case 'U67':
                # code...
                $Query_String  = explode("&", explode("?", $_SERVER['REQUEST_URI'])[1] );
                // $param_string = isset($Query_String[0])?$Query_String[0]:'';
                $param_string = http_build_query($_GET);
                $btn = '<a href="#" onclick="getMenu('."'".$link.'/form/'.$id.'?'.$param_string.''."'".')" title="Update">Update</a>';
                break;

            case 'UC1':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-success" onclick="getMenu('."'".$link.'/form?id='.$id.'&flag='.$flag."'".')"><i class="ace-icon fa fa-edit bigger-50"></i></button>';
                break;

            default:
                # code...
                $btn = '';
                break;
        }
        return $btn;
    }

    function delete_button($url, $id, $code_style){
        $exp_code = explode('/', $code_style);
        $code = (string)$exp_code[0];
        $flag = isset($exp_code[1])?$exp_code[1]:'';
        $link = $this->remove_url_query($url, 'flag');
        
        //print_r($code_style);die;
        switch ($code) {

            /*style button for delete action*/
            case 'D':
                # code...
                $btn = '<a href="#" class="btn btn-white btn-xs btn-danger btn-bold" onclick="delete_data('."'".$id."'".')"><i class="ace-icon fa fa-trash-o bigger-50 blue"></i>Delete</a>';
                break;

            case 'D1':
                # code...
                $btn = '<a href="#" class="btn btn-xs btn-danger" onclick="delete_data('."'".$id."'".')"><i class="ace-icon fa fa-trash-o bigger-50"></i>Delete</a>';
                break;
            case 'D2':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-danger" onclick="delete_data('."'".$id."'".')"><i class="ace-icon fa fa-times bigger-50"></i></button>';
                break;

            case 'D3':
                # code...
                $btn = '<a href="#" class="btn btn-white btn-xs btn-danger btn-bold" onclick="delete_data('."'".$id."'".')"><i class="ace-icon fa fa-trash-o bigger-50 blue"></i></a>';
                break;
            
            case 'D4':
                # code...
                $btn = '<a href="#" onclick="delete_data('."'".$id."'".')" class="tooltip-success" data-rel="tooltip" title="Delete">
                                        <span class="red">
                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                        </span>
                                    </a>';
                break;

            case 'D5':
                # code...
                $btn = '<a href="#" class="btn btn-xs btn-danger" id="button_delete"><i class="ace-icon fa fa-trash-o bigger-50"></i>Delete selected items</a>';
                break;


            case 'D6':
                # code...
                $btn = '<a href="#" onclick="delete_data('."'".$id."'".')">Delete</a>';
                break;

            case 'DC1':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-danger" onclick="delete_data('."'".$id."'".')"><i class="ace-icon fa fa-times bigger-50"></i></button>';
                break;

            case 'D7':
                # code...
                $btn = '<button type="button" class="btn btn-xs btn-danger" onclick="delete_data_without_paging('."'".$id."'".')"><i class="ace-icon fa fa-times bigger-50"></i></button>';
                break;

            case 'D8':
                # code...
                $btn = '<a href="#" class="btn btn-xs btn-danger" id="button_delete_wp"><i class="ace-icon fa fa-trash-o bigger-50"></i>Delete selected items</a>';
                break;

            default:
                # code...
                $btn = '';
                break;
        }
        return $btn;
    }

    public function is_administrator($user_id){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        // cek is administrator
        $result = $db->get_where('tmp_user_has_role', array('user_id' => $user_id, 'role_id' => 1));
        if($result->num_rows() == 1){
            return true;
        }else{
            return false;
        }
    }

	

}

?> 