<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function check_account($usr, $pass) {
        /*get hash password*/
        $data = $this->get_hash_password($usr);
        // print_r($data);die;
        /*validate account*/
        if($data){
            if($this->bcrypt->check_password($pass,$data->password)){
                return $data;
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }

    public function get_hash_password($usr){
        $query = $this->db->select('tmp_user.user_id, tmp_user.email, tmp_user.fullname, tmp_user.username, tmp_user.flag_user, tmp_user.password, tmp_user.last_logon, tmp_user_profile.path_foto')
                            ->select("STUFF((
                                  SELECT '/' + tmp_mst_role.name
                                  FROM tmp_user_has_role tuhr
                                  LEFT JOIN tmp_mst_role ON tmp_mst_role.role_id=tuhr.role_id
                                  WHERE user_id = tmp_user.user_id
                                  FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, '') as role")
                            ->join('tmp_user_profile','tmp_user_profile.user_id=tmp_user.user_id','left')
                            ->get_where('tmp_user', array('username' => $usr, 'tmp_user.is_active' => 'Y'))->row();
                          // print_r($query);die;
        if($query){
            return $query;
        }else{
            return false;
        }
    }

    public function get_sess_menus($user_id){

        /*get session menu by role*/
        $getData = [];
        $find_menus = $this->find_menus($user_id, 0);  
        foreach($find_menus as $row){
            /*find sub menus*/
            $find_sub_menus = $this->find_menus($user_id, $row->menu_id); 
            $row->sub_menus = $find_sub_menus;
            $getData[] = $row;
        }
        return $getData;
    }

    function find_menus($user_id, $parent) {

        $this->db->select('tmp_role_has_menu.menu_id,tmp_mst_menu.name,tmp_mst_menu.class,tmp_mst_menu.link,tmp_mst_menu.level,tmp_mst_menu.parent,tmp_mst_menu.icon,tmp_mst_menu.counter,tmp_role_has_menu.role_id');
        $this->db->from('tmp_role_has_menu');
        $this->db->join('tmp_mst_menu','tmp_mst_menu.menu_id=tmp_role_has_menu.menu_id');
        $this->db->where('tmp_role_has_menu.role_id IN (SELECT role_id FROM tmp_user_has_role WHERE user_id='.$user_id.') ');
        $this->db->where(array('tmp_mst_menu.is_active' => 'Y', 'tmp_mst_menu.parent' => $parent));
        $this->db->group_by('tmp_role_has_menu.menu_id,tmp_mst_menu.name,tmp_mst_menu.class,tmp_mst_menu.link,tmp_mst_menu.level,tmp_mst_menu.parent,tmp_mst_menu.icon,tmp_mst_menu.counter,tmp_role_has_menu.role_id');
        $this->db->order_by('tmp_mst_menu.counter', 'ASC');
        return $this->db->get()->result();
    }

    public function generate_token($user_id){

        $static_str='Login';
        $currenttimeseconds = date("mdY_His");
        $token_id=$static_str.$user_id.$currenttimeseconds;
        $data = array(
                 'token' => md5($token_id),
                 'type' => $static_str,
                 'created_date' => date('Y-m-d H:i:s'),
                 'user_id' => $user_id,
                 );
        $this->db->insert('token', $data);
        return md5($token_id);
    }

    public function clear_token($user_id){
        return $this->db->delete('token', array('user_id' => $user_id));
    }

    public function get_user_profile($user_id){
        $profile = $this->db->join('view_dt_pegawai', 'view_dt_pegawai.kepeg_nik=tmp_user_profile.no_ktp')->get_where('tmp_user_profile', array('user_id' => $user_id))->row();

        // print_r($profile);die;
        if(!empty($profile)){
            return $profile;
        }else{
            return false;
        }
    }

}
