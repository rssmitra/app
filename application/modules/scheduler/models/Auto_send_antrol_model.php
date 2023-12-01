<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auto_send_antrol_model extends CI_Model {


	public function __construct()
	{
		parent::__construct();
	}

    public function get_data($month, $year){

		$query = "SELECT TOP 1 * FROM (SELECT kodebooking,
					MAX ( taskid ) AS taskid,
					MAX ( created_date ) AS created_date 
				FROM
					tr_log_antrian 
				WHERE
				response_code = 200
				GROUP BY
					kodebooking
				HAVING MAX(taskid) < 5
				) as tbl WHERE CAST(created_date as DATE) != '".date('Y-m-d')."' ORDER BY created_date DESC ";

		$execute = $this->db->query($query)->result();
		return $execute;
    }



	public function get_data_registrasi(){

		$query = "SELECT TOP 1 kodebookingantrol as kodebooking, tgl_jam_masuk as created_date, task_id as taskid FROM tc_registrasi WHERE task_id < 5 and DATEDIFF(day,tgl_jam_masuk,GETDATE()) < 7 ORDER BY tgl_jam_masuk ASC";

		$execute = $this->db->query($query)->row();
		return $execute;
    }



}
