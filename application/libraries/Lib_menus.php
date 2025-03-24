<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 Final Class Lib_menus {

    
    function get_menus_shortcut($user_id, $module_id) {
        
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        $getData = array();
        $shortcut_menu = array();
        $sess_menu = $this->get_menus($user_id, $module_id); 
        
        foreach ($sess_menu as $key => $value) {
            if($value['set_shortcut']=='Y' AND $value['link']!='#'){
                $shortcut_menu[] = $value;
            }
            foreach ($value['submenu'] as $ks => $vs) {
                if($vs['set_shortcut']=='Y' AND $vs['link']!='#'){
                    $shortcut_menu[] = $vs;
                }
            }
        }        

        if(count($shortcut_menu)>0){
            for ($i=0; $i < 4; $i++) { 
                foreach ($shortcut_menu as $ksm => $vsm) {
                    if($i==$ksm){
                        $getData[] = $shortcut_menu[$i];
                    }
                }
            }
        }

        return $getData;
        
    }

    function get_menu_template() {
        
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        $menu = $db->query("SELECT * FROM menu WHERE parent IN (SELECT menu_id FROM menu WHERE not_allowed='Y')")->result();
        return $menu;
        
    }

    function get_menus($user_id, $module_id) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		$getData = array();
		$sess_menu = $this->get_hak_akses_menu_role($user_id, $module_id); 

        if($sess_menu){
            foreach ($sess_menu as $key => $value) {
                # code...
                $getData[] = $value;
            }
        }        

		return $getData;
		
    }


    public function get_hak_akses_menu_role($user_id, $module_id){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        // get menu role
        $menu_role = $this->qry_main_menu($user_id, $module_id); 

        $getData = [];
        foreach ($menu_role->result_array() as $key => $value) {

            if( ($value['parent'] == 0) && ($value['link'] == '#') ){

                $value['submenu'] = $this->qry_submenu($user_id, $value['menu_id'], $module_id);

            }else{
                
                $code_action = $this->get_code_action(array('user_id'=>$user_id, 'menu_id'=>$value['menu_id']));

                $value['submenu'] = array();
                $value['action'] = $code_action;
            }

            $getData[] = $value;
        }

        return $getData;
    }

    public function get_code_action($where){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $arr_code = [];
        if($where['user_id'] != 0){
            $db->where('role_id IN (SELECT role_id FROM tmp_user_has_role WHERE user_id = '.$where['user_id'].' GROUP BY role_id)');
        }
        $db->select('action_code');
        $db->from('tmp_role_has_menu');
        $db->where('menu_id', $where['menu_id']);
        $code_action = $db->get()->result_array();
        foreach ($code_action as $key => $value) {
            # code...
            $arr_code[] = $value['action_code'];
        }

        return $arr_code;
    }

    public function qry_submenu($user_id, $menu_id, $module_id){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        $db->select('tmp_role_has_menu.menu_id,tmp_mst_menu.name,tmp_mst_menu.class,tmp_mst_menu.link,tmp_mst_menu.level,tmp_mst_menu.parent,tmp_mst_menu.icon,tmp_mst_menu.counter,tmp_mst_menu.set_shortcut, tmp_mst_menu.modul_id');
        $db->from('tmp_role_has_menu');
        $db->join('tmp_mst_menu', 'tmp_mst_menu.menu_id=tmp_role_has_menu.menu_id', 'left');
        $db->group_by('tmp_role_has_menu.menu_id,tmp_mst_menu.name,tmp_mst_menu.class,tmp_mst_menu.link,tmp_mst_menu.level,tmp_mst_menu.parent,tmp_mst_menu.icon,tmp_mst_menu.counter,tmp_mst_menu.set_shortcut, tmp_mst_menu.modul_id');
        $db->order_by('tmp_mst_menu.counter','ASC');
        if($user_id != 0){
            $db->where('role_id IN (SELECT role_id FROM tmp_user_has_role WHERE user_id = '.$user_id.' GROUP BY role_id)');
        }
        $db->where(array('parent'=>$menu_id, 'tmp_mst_menu.is_active'=>'Y'));
        $db->where('tmp_mst_menu.modul_id', $module_id);
        $submenu = $db->get()->result_array();
        // print_r($db->last_query());die;
        $arr_submenu = array();
        if( count($submenu) > 0 ){
            foreach ($submenu as $row_submenu) {

                $code_action = $this->get_code_action(array('user_id'=>$user_id, 'menu_id'=>$row_submenu['menu_id']));

                $row_submenu['action'] = $code_action;
                $arr_submenu[] = $row_submenu;

            }
        }
        

        return $arr_submenu;
    }

    public function qry_main_menu($user_id, $module_id){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        $db->select("tmp_role_has_menu.menu_id,tbl_menu.modul_id,tbl_menu.name,tbl_menu.class,tbl_menu.link,tbl_menu.level,tbl_menu.parent,tbl_menu.icon,tbl_menu.counter,tbl_menu.menu_id,tbl_menu.set_shortcut");
        $db->from('tmp_role_has_menu');
        $db->join("(SELECT * FROM tmp_mst_menu WHERE parent = 0 AND is_active = 'Y') AS tbl_menu","tbl_menu.menu_id=tmp_role_has_menu.menu_id","left");
        if($user_id != 0){
            $db->where('tmp_role_has_menu.role_id IN (SELECT role_id FROM tmp_user_has_role WHERE user_id = '.$user_id.' GROUP BY role_id)');
        }
        $db->where('tbl_menu.modul_id', $module_id);
        $db->group_by('tmp_role_has_menu.menu_id,tbl_menu.modul_id,tbl_menu.name,tbl_menu.class,tbl_menu.link,tbl_menu.level,tbl_menu.parent,tbl_menu.icon,tbl_menu.counter,tbl_menu.menu_id, tbl_menu.set_shortcut');
        $db->order_by('tbl_menu.modul_id', 'DESC');
        $db->order_by('tbl_menu.counter', 'ASC');

        $menu_role = $db->get();

        return $menu_role;
    }

    function get_sub_menu($class){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $sess = $CI->load->library('session');

        $query = "SELECT * FROM role_has_menu 
                    LEFT JOIN menu ON menu.menu_id=role_has_menu.menu_id
                    WHERE role_has_menu.role_id='".$sess->userdata('data_user')->role_id."' AND menu.is_active='Y' AND menu.parent IN (SELECT menu_id FROM menu WHERE class='".$class."')
                    GROUP BY role_has_menu.menu_id ORDER BY menu.counter ASC ";
        $menu_role = $db->query($query);

        return $menu_role;
    }

    function get_master_menus($id) {
        
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        $getData = array();
        $sess_menu = $this->get_all_menus($id);

        if($sess_menu){
            foreach ($sess_menu as $key => $value) {
                # code...
                $getData[] = $value;
            }
        }        

        return $getData;
        
    }

    public function get_all_menus($id){

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        // get menu role
        $menu = $db->select('tmp_mst_menu.menu_id,tmp_mst_menu.name,tmp_mst_menu.modul_id,tmp_mst_menu.parent,tmp_mst_menu.link,tmp_mst_menu.class,tmp_mst_menu.level,tmp_mst_menu.icon,tmp_mst_menu.class,tmp_mst_modul.name as modul_name')->join('tmp_mst_modul','tmp_mst_modul.modul_id=tmp_mst_menu.modul_id','left')->get_where('tmp_mst_menu', array('tmp_mst_menu.is_active'=>'Y', 'tmp_mst_menu.is_deleted'=>'N', 'parent' => 0));

        foreach ($menu->result_array() as $key => $value) {

            if( ($value['parent'] == 0) && ($value['link'] == '#') ){

                $value['submenu'] = $db->select('menu_id,modul_id, name, class, link, level, parent, icon, set_shortcut, counter')->group_by('menu_id,modul_id, name, class, link, level, parent, icon, set_shortcut, counter')->get_where('tmp_mst_menu',array('parent'=>$value['menu_id']))->result_array();

            }else{
                $value['submenu'] = array();
            }

            
            $getData[] = $value;
        }

        return $getData;
    }

	public function get_module_by_class($class)
    {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $db->from('tmp_mst_menu');
        $db->where("menu_module = (SELECT menu_id FROM menu WHERE class = '$class')");
        return $db->get()->result();
    }

    public function get_menu_by_class($class)
    {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $db->from('tmp_mst_menu');
        $db->where("class = '$class'");
        return $db->get()->row();
    }

    public function get_modules_by_user_id($user_id)
    {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $qry = "SELECT tmp_mst_modul.modul_id,tmp_mst_modul.name,tmp_mst_modul.title,tmp_mst_modul.description,tmp_mst_modul.icon,tmp_mst_modul.is_new_tab,tmp_mst_modul.group_modul_id, tmp_mst_group_modul.group_modul_name, tmp_mst_group_modul.group_modul_description, tmp_mst_group_modul.group_modul_icon  FROM tmp_role_has_menu
                LEFT JOIN tmp_mst_menu ON tmp_mst_menu.menu_id=tmp_role_has_menu.menu_id
                LEFT JOIN tmp_mst_modul ON tmp_mst_modul.modul_id=tmp_mst_menu.modul_id
                LEFT JOIN tmp_mst_group_modul ON tmp_mst_group_modul.group_modul_id=tmp_mst_modul.group_modul_id
                WHERE tmp_mst_modul.is_active !='N' AND tmp_mst_group_modul.is_active != 'N' AND tmp_mst_menu.is_active !='N' AND tmp_role_has_menu.role_id IN (SELECT role_id FROM tmp_user_has_role WHERE user_id=".$user_id.")
                GROUP BY tmp_mst_modul.modul_id,tmp_mst_modul.name,tmp_mst_modul.title,tmp_mst_modul.description,tmp_mst_modul.icon,tmp_mst_modul.is_new_tab,tmp_mst_modul.group_modul_id, tmp_mst_group_modul.group_modul_name,tmp_mst_group_modul.group_modul_description, tmp_mst_group_modul.group_modul_icon";
        $array = $db->query($qry)->result_array();

        foreach ($array as $key => $value) {
            $id = $value['group_modul_id'];
            if(!isset($result[$id])) $result[$id] = array();
            $result[$id] = $value['group_modul_name'].' <small><i class="fa fa-angle-double-right"></i> '.$value['group_modul_description'].'</small>'; 
        }

        foreach ($result as $k => $v) {
            $modul = $this->search_modul_by_group($k, $user_id);
            $arr = array(
                'group_modul_id' => $k,
                'group_modul_name' => $v,
                'modul' => $modul,
                );
            $getData[] = $arr;
        }

        return $getData;
         
    }

    public function search_modul_by_group($group_modul_id, $user_id){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $sess = $CI->load->library('session');
        $qry = "SELECT tmp_mst_modul.modul_id,tmp_mst_modul.name,tmp_mst_modul.title,tmp_mst_modul.description,tmp_mst_modul.icon,tmp_mst_modul.is_new_tab,tmp_mst_modul.link_on_new_tab,tmp_mst_modul.group_modul_id FROM tmp_role_has_menu
                LEFT JOIN tmp_mst_menu ON tmp_mst_menu.menu_id=tmp_role_has_menu.menu_id
                LEFT JOIN tmp_mst_modul ON tmp_mst_modul.modul_id=tmp_mst_menu.modul_id WHERE role_id IN (SELECT role_id FROM tmp_user_has_role WHERE user_id=".$user_id.") AND tmp_mst_modul.group_modul_id=".$group_modul_id." AND tmp_mst_modul.is_active='Y' GROUP by tmp_mst_modul.modul_id,tmp_mst_modul.name,tmp_mst_modul.title,tmp_mst_modul.description,tmp_mst_modul.icon,tmp_mst_modul.is_new_tab,tmp_mst_modul.group_modul_id,tmp_mst_modul.link_on_new_tab";
        return $db->query($qry)->result();
    }

}

?> 