<?php
 header("Access-Control-Allow-Origin: *");

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Web_api extends MX_Controller {

    function __construct(){

        date_default_timezone_set("Asia/Jakarta");
        parent::__construct();
        // Example API users for Basic Auth (replace with DB lookup in production)
        $this->api_users = array(
            'admin' => 'secret',
            'bpjs'  => 'bpjs123'
        );
        // Ensure JSON responses
        header('Content-Type: application/json');
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';
        $this->load->model('publik/Pelayanan_publik_model', 'Pelayanan_publik');
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        $this->load->model('ws/AntrianOnlineModel', 'AntrianOnline');
        $this->load->library('Daftar_pasien');
    }

    /**
     * Get Basic Auth credentials from request (supports PHP_AUTH_* and Authorization header)
     * @return array [username, password]
     */
    private function get_basic_auth_credentials() {
        // PHP running under CGI/FastCGI may not populate PHP_AUTH_USER
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            return array($_SERVER['PHP_AUTH_USER'], isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '');
        }

        // Try Authorization header
        $auth = null;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth = trim($_SERVER['HTTP_AUTHORIZATION']);
        } elseif (function_exists('apache_request_headers')) {
            $ar = apache_request_headers();
            if (isset($ar['Authorization'])) $auth = trim($ar['Authorization']);
        }

        if ($auth && preg_match('/Basic\s+(.*)$/i', $auth, $matches)) {
            $decoded = base64_decode($matches[1]);
            if ($decoded !== false) {
                $parts = explode(':', $decoded, 2);
                return array(isset($parts[0]) ? $parts[0] : null, isset($parts[1]) ? $parts[1] : null);
            }
        }
        return array(null, null);
    }

    /**
     * Validate username/password against configured API users
     */
    private function validate_credentials($username, $password) {
        if (empty($username)) return false;
        if (isset($this->api_users[$username]) && $this->api_users[$username] === $password) return true;
        return false;
    }

    /**
     * Require Basic Auth for an endpoint. Returns username on success or sends 401 JSON and exits.
     */
    private function require_basic_auth() {
        list($user, $pass) = $this->get_basic_auth_credentials();
        if (!$this->validate_credentials($user, $pass)) {
            header('WWW-Authenticate: Basic realm="API"');
            http_response_code(401);
            echo json_encode(array(
                'success' => false,
                'code' => '401',
                'message' => 'Unauthorized',
                'data' => array()
            ));
            exit;
        }
        return $user;
    }

    /**
     * Helper to send response in the requested format
     */
    private function respond($success, $code, $message, $data = array()) {
        $resp = array(
            'success' => $success ? true : false,
            'code' => (string)$code,
            'message' => $message,
            'data' => $data
        );
        echo json_encode($resp);
        exit;
    }

    /**
     * Sample endpoint: GET /index.php/api/web_api/api_sample
     * Protected by Basic Auth. Returns the example JSON structure.
     */
    public function api_sample() {
        // Require basic auth
        $this->require_basic_auth();

        $data = array(
            array('id' => 'admin', 'name' => 'secret')
        );

        $this->respond(true, '200', 'OK', $data);
    }
    
    /**
     * Patient criterias endpoint
     * GET /index.php/api/web_api/patient_criterias
     */
    public function patient_criterias() {
        // Require basic auth
        $this->require_basic_auth();

        $data = array(
            array('id' => 1, 'name' => 'BPJS'),
            array('id' => 2, 'name' => 'Umum'),
            array('id' => 3, 'name' => 'Asuransi')
        );

        $this->respond(true, '200', 'OK', $data);
    }

    /**
     * Service specialist endpoint
     * GET /index.php/api/web_api/service_specialist
     */
    public function specialists() {
        // Require basic auth
        $this->require_basic_auth();

        // buatkan query untuk mendapatkan data spesialis dari database
        $limit   = (int)$this->input->get('limit');
        $skip    = (int)$this->input->get('skip');
        $sortBy  = $this->input->get('sortBy');
        $keyword = trim((string)$this->input->get('keyword'));

        if ($limit <= 0) $limit = 10;
        if ($skip < 0) $skip = 0;

        // tentukan order by dari parameter sortBy
        $order_by  = 'nama_bagian';
        $order_dir = 'ASC';
        if ($sortBy) {
            switch ($sortBy) {
                case 'nameDesc': $order_by = 'nama_bagian'; $order_dir = 'DESC'; break;
                case 'oldest': $order_by = 'created_date'; $order_dir = 'ASC'; break;
                case 'latest': $order_by = 'created_date'; $order_dir = 'DESC'; break;
                default: $order_by = 'nama_bagian'; $order_dir = 'ASC'; break;
            }
        }

        // bangun query dengan Query Builder (sesuaikan nama tabel/kolom jika berbeda)
        $this->db->start_cache();
        $this->db->from('mt_bagian'); // ganti jika tabel-mu bernama lain
        if ($keyword !== '') {
            $this->db->like('nama_bagian', $keyword);
            $skip = 0;
        }
        $this->db->stop_cache();

        // total sebelum limit untuk metadata
        $this->db->where('validasi', 100);
        $total = (int)$this->db->count_all_results();

        // ambil data dengan limit/offset dan order
        $this->db->order_by($order_by, $order_dir);
        $query = $this->db->where('validasi', 100)->get('mt_bagian', $limit, $skip);
        $rows = $query->result();

        // echo $this->db->last_query(); die;

        $this->db->flush_cache();

        // format items sesuai kebutuhan API
        $items = array();
        foreach ($rows as $r) {
            $items[] = array(
            'xid'       => isset($r->kode_bagian) ? $r->kode_bagian : null,
            'name'      => isset($r->nama_bagian) ? $r->nama_bagian : '',
            'createdAt' => isset($r->created_date) ? date('c', strtotime($r->created_date)) : date('c', strtotime(date('Y-m-d H:i:s'))),
            'updatedAt' => isset($r->updated_date) ? date('c', strtotime($r->updated_date)) : date('c', strtotime(date('Y-m-d H:i:s'))),
            );
        }

        $data = array(
            'items' => $items,
            'metadata' => array(
                'total'  => $total,
                'count'  => count($items),
                'skip'   => $skip,
                'limit'  => $limit,
                'sortBy' => $sortBy ? $sortBy : 'nameAsc'
            )
        );

        $this->respond(true, '200', 'OK', $data);
    }

    /**
     * Service insurances endpoint
     * GET /index.php/api/web_api/insurances
     */
    public function insurances() {
        // Require basic auth
        $this->require_basic_auth();

        // buatkan query untuk mendapatkan data spesialis dari database
        $limit   = (int)$this->input->get('limit');
        $skip    = (int)$this->input->get('skip');
        $sortBy  = $this->input->get('sortBy');
        $keyword = trim((string)$this->input->get('keyword'));

        if ($limit <= 0) $limit = 10;
        if ($skip < 0) $skip = 0;

        // tentukan order by dari parameter sortBy
        $order_by  = 'nama_perusahaan';
        $order_dir = 'ASC';
        if ($sortBy) {
            switch ($sortBy) {
                case 'nameDesc': $order_by = 'nama_perusahaan'; $order_dir = 'DESC'; break;
                case 'oldest': $order_by = 'created_date'; $order_dir = 'ASC'; break;
                case 'latest': $order_by = 'created_date'; $order_dir = 'DESC'; break;
                default: $order_by = 'nama_perusahaan'; $order_dir = 'ASC'; break;
            }
        }

        // bangun query dengan Query Builder (sesuaikan nama tabel/kolom jika berbeda)
        $this->db->start_cache();
        $this->db->from('mt_perusahaan'); // ganti jika tabel-mu bernama lain
        if ($keyword !== '') {
            $this->db->like('nama_perusahaan', $keyword);
            $skip = 0;
        }
        $this->db->stop_cache();

        // total sebelum limit untuk metadata
        $total = (int)$this->db->count_all_results();

        // ambil data dengan limit/offset dan order
        $this->db->order_by($order_by, $order_dir);
        $this->db->where("kode_perusahaan !=", 120);
        $query = $this->db->get('mt_perusahaan', $limit, $skip);
        $rows = $query->result();

        // echo $this->db->last_query(); die;

        $this->db->flush_cache();

        // format items sesuai kebutuhan API
        $items = array();
        foreach ($rows as $r) {
            $items[] = array(
            'xid'       => isset($r->kode_perusahaan) ? (string)$r->kode_perusahaan : null,
            'name'      => isset($r->nama_perusahaan) ? $r->nama_perusahaan : '',
            'createdAt' => isset($r->created_date) ? date('c', strtotime($r->created_date)) : date('c', strtotime(date('Y-m-d H:i:s'))),
            'updatedAt' => isset($r->updated_date) ? date('c', strtotime($r->updated_date)) : date('c', strtotime(date('Y-m-d H:i:s'))),
            );
        }

        $data = array(
            'items' => $items,
            'metadata' => array(
                'total'  => $total,
                'count'  => count($items),
                'skip'   => $skip,
                'limit'  => $limit,
                'sortBy' => $sortBy ? $sortBy : 'nameAsc'
            )
        );

        $this->respond(true, '200', 'OK', $data);
    }

    /**
     * Service subdistricts endpoint
     * GET /index.php/api/web_api/subdistricts
     */
    public function subdistricts() {
        // Require basic auth
        $this->require_basic_auth();

        // buatkan query untuk mendapatkan data spesialis dari database
        $limit   = (int)$this->input->get('limit');
        $skip    = (int)$this->input->get('skip');
        $sortBy  = $this->input->get('sortBy');
        $keyword = trim((string)$this->input->get('keyword'));

        if ($limit <= 0) $limit = 10;
        if ($skip < 0) $skip = 0;

        // tentukan order by dari parameter sortBy
        $order_by  = 'name';
        $order_dir = 'ASC';
        if ($sortBy) {
            switch ($sortBy) {
                case 'nameDesc': $order_by = 'name'; $order_dir = 'DESC'; break;
                case 'oldest': $order_by = 'created_date'; $order_dir = 'ASC'; break;
                case 'latest': $order_by = 'created_date'; $order_dir = 'DESC'; break;
                default: $order_by = 'name'; $order_dir = 'ASC'; break;
            }
        }

        // bangun query dengan Query Builder (sesuaikan nama tabel/kolom jika berbeda)
        $this->db->start_cache();
        $this->db->from('districts'); // ganti jika tabel-mu bernama lain
        if ($keyword !== '') {
            $this->db->like('name', $keyword);
            $skip = 0;
        }
        $this->db->stop_cache();

        // total sebelum limit untuk metadata
        $total = (int)$this->db->count_all_results();
        

        // ambil data dengan limit/offset dan order
        $this->db->order_by($order_by, $order_dir);
        $query = $this->db->get('districts', $limit, $skip);
        $rows = $query->result();

        $this->db->flush_cache();

        // format items sesuai kebutuhan API
        $items = array();
        foreach ($rows as $r) {
            $items[] = array(
            'xid'       => isset($r->id) ? (string)$r->id : null,
            'name'      => isset($r->name) ? $r->name : '',
            'createdAt' => isset($r->created_date) ? date('c', strtotime($r->created_date)) : date('c', strtotime(date('Y-m-d H:i:s'))),
            'updatedAt' => isset($r->updated_date) ? date('c', strtotime($r->updated_date)) : date('c', strtotime(date('Y-m-d H:i:s'))),
            );
        }

        $data = array(
            'items' => $items,
            'metadata' => array(
                'total'  => $total,
                'count'  => count($items),
                'skip'   => $skip,
                'limit'  => $limit,
                'sortBy' => $sortBy ? $sortBy : 'nameAsc'
            )
        );

        $this->respond(true, '200', 'OK', $data);
    }

    /**
     * Service diseases endpoint
     * GET /index.php/api/web_api/diseases
     */
    public function diseases() {
        // Require basic auth
        $this->require_basic_auth();

        // buatkan query untuk mendapatkan data spesialis dari database
        $limit   = (int)$this->input->get('limit');
        $skip    = (int)$this->input->get('skip');
        $sortBy  = $this->input->get('sortBy');
        $keyword = trim((string)$this->input->get('keyword'));

        if ($limit <= 0) $limit = 10;
        if ($skip < 0) $skip = 0;

        // tentukan order by dari parameter sortBy
        $order_by  = 'label';
        $order_dir = 'ASC';
        if ($sortBy) {
            switch ($sortBy) {
                case 'nameDesc': $order_by = 'label'; $order_dir = 'DESC'; break;
                case 'oldest': $order_by = 'created_date'; $order_dir = 'ASC'; break;
                case 'latest': $order_by = 'created_date'; $order_dir = 'DESC'; break;
                default: $order_by = 'label'; $order_dir = 'ASC'; break;
            }
        }

        // bangun query dengan Query Builder (sesuaikan nama tabel/kolom jika berbeda)
        $this->db->start_cache();
        $this->db->from('global_parameter'); // ganti jika tabel-mu bernama lain
        $this->db->where('flag', 'diseases');
        if ($keyword !== '') {
            $this->db->like('label', $keyword);
            $skip = 0;
        }
        $this->db->stop_cache();

        // total sebelum limit untuk metadata
        $total = (int)$this->db->count_all_results();

        // ambil data dengan limit/offset dan order
        $this->db->order_by($order_by, $order_dir);
        $query = $this->db->where('flag', 'diseases')->get('global_parameter', $limit, $skip);
        $rows = $query->result();

        // echo $this->db->last_query(); die;

        $this->db->flush_cache();

        // format items sesuai kebutuhan API
        $items = array();
        foreach ($rows as $r) {
            $items[] = array(
            'xid'       => isset($r->value) ? $r->value : null,
            'name'      => isset($r->label) ? $r->label : '',
            'createdAt' => isset($r->created_date) ? date('c', strtotime($r->created_date)) : date('c', strtotime(date('Y-m-d H:i:s'))),
            'updatedAt' => isset($r->updated_date) ? date('c', strtotime($r->updated_date)) : date('c', strtotime(date('Y-m-d H:i:s'))),
            );
        }

        $data = array(
            'items' => $items,
            'metadata' => array(
                'total'  => $total,
                'count'  => count($items),
                'skip'   => $skip,
                'limit'  => $limit,
                'sortBy' => $sortBy ? $sortBy : 'nameAsc'
            )
        );

        $this->respond(true, '200', 'OK', $data);
    }

    /**
     * Service patient endpoint
     * GET /index.php/api/web_api/patient
     */
    public function patient() {
        // Require basic auth
        $this->require_basic_auth();


        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // get raw data from request body
            $rawData = file_get_contents('php://input');
            $postData = json_decode($rawData, true);
            // get necessary data from postData
            $post = (object)$postData;

            // echo "<pre>"; print_r($post);die;

            // valdiate NIK
            $patient_nik = $this->db->where('no_ktp', $post->nik)->get('mt_master_pasien')->row();
            if($patient_nik){
                $this->respond(false, 'E_PATIENT_NIK_EXISTS', 'A patient with this nik is already exists.', []);
            }

            // kode kelompok bpjs
            if($post->criteriaId == 1){
                $kode_kelompok = 3;
                $kode_perusahaan = 120;
                if($post->bpjsId != null){
                    $patient_bpjs = $this->db->where('no_kartu_bpjs', $post->bpjsId)->get('mt_master_pasien')->row();
                    if($patient_bpjs){
                        $this->respond(false, 'E_PATIENT_BPJS_EXISTS', 'A patient with this bpjs is already exists.', []);
                    }
                }else{
                    $this->respond(false, 'E_PATIENT_BPJS_REQUIRED', 'BPJS ID is required for BPJS criteria.', []);
                }
            }
            // asuransi
            if($post->criteriaId == 3){
                $kode_kelompok = 3;
                $kode_perusahaan = $post->insuranceXid;

                if($post->insuranceXid == null){
                    $this->respond(false, 'E_PATIENT_INSURANCE_REQUIRED', 'INSURANCE ID is required for INSURANCE criteria.', []);
                }
            }
            
            // umum
            if($post->criteriaId == 2){
                $kode_kelompok = 1;
                $kode_perusahaan = null;
            }

            // generate no mr
            /*get max no mr*/
            $cekMaxMr = $this->db->query("select TOP 1 no_mr from mt_master_pasien where LEN(no_mr)=8 order by no_mr desc ")->row();
            $mrID = $cekMaxMr->no_mr + 1;
            $panjang_mr=strlen($mrID);
            $sisa_panjang=8-$panjang_mr;
            $tambah_nol="";
            for ($i=1;$i<=$sisa_panjang;$i++){
                $tambah_nol=$tambah_nol."0";
            }
            $mrID = $tambah_nol.$mrID;

            $dataexc = [
                "no_mr" => $mrID, 
                "nama_pasien" => $post->name, 
                "no_ktp" => $post->nik, 
                "tlp_almt_ttp" => $post->phoneNumber, 
                "no_hp" => $post->phoneNumber, 
                "tgl_lhr" => $post->birthDate, 
                "tempat_lahir" => $post->birthPlace, 
                "jen_kelamin" => $post->gender, 
                "kode_kelompok" => $kode_kelompok, 
                "kode_perusahaan" => $kode_perusahaan, 
                "no_kartu_bpjs" => $post->bpjsId, 
                "id_dc_kecamatan" => $post->address['subDistrictXids'], 
                "almt_ttp_pasien" => $post->address['fullAddress'], 
                "kode_pos" => $post->address['postalCode'], 
                'is_active' => 1,
                'keterangan' => 'Register pasien via Online Web',
                "create_date" => date('Y-m-d H:i:s'),
                "tgl_input" => date('Y-m-d H:i:s'),
                "created_by" => "Register Online"
            ];
            // echo "<pre>"; print_r($dataexc);die;
            $this->db->insert('mt_master_pasien', $dataexc);     
            $newId = $this->db->insert_id();
            $medicalRecordId = $mrID;
            $nik = "";
            $bpjsId = "";
        }else{
            $medicalRecordId = trim((string)$this->input->get('medicalRecordId'));
            $nik = trim((string)$this->input->get('nik'));
            $bpjsId = trim((string)$this->input->get('bpjsId'));
        }

        if ($medicalRecordId === '' && $nik === '' && $bpjsId === '') {
            $this->respond(false, 'E_PATIENT_NOT_FOUND', 'Patient Not Found.', array());
        }

        if($medicalRecordId != '') {
            $this->db->where('no_mr', $medicalRecordId);
        } else if($nik != '') {
            $this->db->where('no_ktp', $nik);
        } else {
            $this->db->where('no_kartu_bpjs', $bpjsId);
        }
        
        // only need one patient (first match)
        $this->db->select('mt_master_pasien.*, districts.name as subDistrictName');
        $this->db->join('districts', 'districts.id = mt_master_pasien.id_dc_kecamatan', 'LEFT');
        $this->db->join('villages_new', 'villages_new.id = mt_master_pasien.id_dc_kelurahan', 'LEFT');
        $query = $this->db->get('mt_master_pasien', 1);
        $r = $query->row();

        if (!$r) {
            $this->respond(false, 'E_PATIENT_NOT_FOUND', 'Patient Not Found.', array());
        }

        // helper: gender mapping
        $gender = null;
        if (isset($r->jen_kelamin)) {
            $g = strtoupper(trim($r->jen_kelamin));
            if ($g === 'L' || stripos($g, 'laki') !== false) $gender = 'Laki-laki';
            elseif ($g === 'P' || stripos($g, 'perempuan') !== false) $gender = 'Perempuan';
            else $gender = $r->jen_kelamin;
        }

        // criteria mapping (fallback to static mapping if stored as numeric)
        $criteria = null;
        if($r->kode_perusahaan == 120){
            $criteria = array(
                'id' => 1,
                'name' => 'BPJS'
            );
        }elseif (isset($r->kode_kelompok) && $r->kode_kelompok !== null && $r->kode_kelompok !== '' && $r->kode_kelompok == 1){
            $criteria = array(
                'id' => 2,
                'name' => 'Umum'
            );
        } else{
            $criteria = array(
                'id' => 3,
                'name' => 'Asuransi'
            );
        }


        // try several possible BPJS field names
        $bpjsId = $r->no_kartu_bpjs;

        // insurance lookup if kode_perusahaan present
        $insurance = null;
        $insKey = $r->kode_perusahaan;
        if ($insKey) {
            $ins = $this->db->where('kode_perusahaan', $insKey)->get('mt_perusahaan', 1)->row();
            if ($ins) {
                $insurance[] = array(
                    'xid' => isset($ins->kode_perusahaan) ? (string)$ins->kode_perusahaan : null,
                    'name' => isset($ins->nama_perusahaan) ? $ins->nama_perusahaan : ''
                );
            }
        }
        

        // address fields (try several common column names)
        $subDistrict = null;
        if (!empty($r->id_dc_kecamatan)) $subDistrict = $r->subDistrictName;

        $fullAddress = null;
        if (!empty($r->almt_ttp_pasien)) $fullAddress = $r->almt_ttp_pasien;

        // echo "<pre>"; print_r($post);die;

        $postalCode = isset($post->address['postalCode']) ? $post->address['postalCode'] : $r->kode_pos;

        // build response object
        $patient = array(
            'xid'             => isset($r->id_mt_master_pasien) ? (string)$r->id_mt_master_pasien : null,
            'name'            => isset($r->nama_pasien) ? $r->nama_pasien : '',
            'nik'             => isset($r->no_ktp) ? $r->no_ktp : '',
            'medicalRecordId' => isset($r->no_mr) ? $r->no_mr : '',
            'phoneNumber'     => isset($r->no_hp) ? $r->no_hp : (isset($r->tlp_almt_ttp) ? $r->tlp_almt_ttp : ''),
            'birthDate'       => (isset($r->tgl_lhr) && $r->tgl_lhr) ? date('Y-m-d', strtotime($r->tgl_lhr)) : null,
            'birthPlace'      => isset($r->tempat_lahir) ? $r->tempat_lahir : '',
            'gender'          => $gender,
            'criteria'        => $criteria,
            'bpjsId'          => $bpjsId,
            'insurance'       => $insurance !== null ? $insurance : null,
            'address'         => array(
                'subDistrict' => $subDistrict,
                'fullAddress' => $fullAddress,
                'postalCode'  => (int)$postalCode
            ),
            'createdAt'       => isset($r->create_date) && $r->create_date ? date('c', strtotime($r->create_date)) : date('c', strtotime(date('Y-m-d H:i:s'))),
            'updatedAt'       => isset($r->create_date) && $r->create_date ? date('c', strtotime($r->create_date)) : date('c', strtotime(date('Y-m-d H:i:s')))
        );

        $this->respond(true, '200', 'OK', $patient);
    }

    /**
     * Service doctors endpoint
     * GET /index.php/api/web_api/doctors
     */
    public function doctors() {
        // Require basic auth
        $this->require_basic_auth();

        $this->load->helper('mssql');

        // paging / sort / filter params
        $limit   = (int)$this->input->get('limit');
        $skip    = (int)$this->input->get('skip');
        $sortBy  = $this->input->get('sortBy');
        $keyword = trim((string)$this->input->get('keyword'));

        // array filters (may come as ?specialistXids[]=1&specialistXids[]=2)
        $specialistXids = $this->input->get('specialistXids');
        if ($specialistXids !== null && !is_array($specialistXids)) {
            // support comma separated too
            $specialistXids = $specialistXids === '' ? array() : explode(',', $specialistXids);
        }

        $specialistXids = is_array($specialistXids) ? array_values(array_filter($specialistXids, function($v){ return $v !== '' && $v !== null; })) : array();

        $diseaseXids = $this->input->get('diseaseXids');
        if ($diseaseXids !== null && !is_array($diseaseXids)) {
            $diseaseXids = $diseaseXids === '' ? array() : explode(',', $diseaseXids);
        }

        $diseaseXids = is_array($diseaseXids) ? array_values(array_filter($diseaseXids, function($v){ return $v !== '' && $v !== null; })) : array();

        // normalize incoming dayIds which may be ints or names like ['Senin','Selasa']
        $dayIds = $this->input->get('dayIds');
        if ($dayIds !== null && !is_array($dayIds)) {
            $dayIds = $dayIds === '' ? array() : explode(',', $dayIds);
        }
        $dayIds = is_array($dayIds) ? array_values(array_filter($dayIds, function($v){ return $v !== '' && $v !== null; })) : array();

        // map day name (lowercase, varios spelling) => id
        $dayNameToId = array(
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis', 5 => "Jumat", 6 => 'Sabtu'
        );

        // convert any name values to numeric ids, keep numeric values
        $normalized = array();
        foreach ($dayIds as $v) {
            $v = trim((string)$v);
            if ($v === '') continue;
            $key = mb_strtolower($v, 'UTF-8');
            if (isset($dayNameToId[$key])) {
            $normalized[] = $dayNameToId[$key];
            }
        }
        $dayIds = array_values(array_unique($normalized));

        // print_r($dayIds); die;

        if ($limit <= 0) $limit = 10;
        if ($skip < 0) $skip = 0;

        // order by mapping
        $order_by  = 'nama_pegawai';
        $order_dir = 'ASC';

        if ($sortBy) {
            switch ($sortBy) {
                case 'nameDesc': $order_by = 'nama_pegawai'; $order_dir = 'DESC'; break;
                case 'oldest': $order_by = 'updated_date'; $order_dir = 'ASC'; break;
                case 'latest': $order_by = 'updated_date'; $order_dir = 'DESC'; break;
                case 'nameAsc': $order_by = 'nama_pegawai'; $order_dir = 'ASC'; break;
                default: $order_by = 'nama_pegawai'; $order_dir = 'ASC'; break;
            }
        }

        // build base cached conditions
        $this->db->start_cache();
        $this->db->from('view_jadwal_dokter j');
        // $this->db->where('j.is_active', 'Y');

        // keyword search on doctor name or specialist name
        if ($keyword !== '') {
            $this->db->group_start();
            $this->db->like('j.nama_pegawai', $keyword);
            // $this->db->or_like('j.nama_bagian', $keyword);
            $this->db->group_end();
            // reset skip when searching similar to other endpoints
            $skip = 0;
        }

        // filters
        if (!empty($specialistXids)) {
            $this->db->where_in('j.jd_kode_spesialis', $specialistXids);
            $skip = 0;
        }

        if (!empty($dayIds)) {
            $this->db->where_in('j.jd_hari', $dayIds);
            $skip = 0;
        }

        // diseaseXids filter: depends on your schema. Example placeholder:
        // if you have a table tr_dokter_disease (kode_dokter, disease_id) then uncomment and adjust:
        // if (!empty($diseaseXids)) {
        //     $this->db->join('tr_dokter_disease dd', 'dd.kode_dokter = d.kode_dokter', 'LEFT');
        //     $this->db->where_in('dd.disease_id', $diseaseXids);
        // }
        
        $this->db->stop_cache();

        // total distinct doctors matching filters
        // select distinct dokter ids then count rows
        $this->db->select('jd_kode_dokter');
        $this->db->where("jd_kode_spesialis NOT IN ('012601','012801','012901', '012101','012201','013601', '013801', '013101')");
        $this->db->group_by('jd_kode_dokter, nama_pegawai, url_foto_karyawan, pengalaman_tahun, jd_kode_spesialis, nama_bagian, is_eksekutif');
        $qDistinct = $this->db->get();
        $total = (int)$qDistinct->num_rows();

        // echo $this->db->last_query(); die;

        // fetch doctors (distinct) with pagination
        $this->db->select('
            j.jd_kode_dokter,
            j.nama_pegawai,
            j.url_foto_karyawan,
            j.pengalaman_tahun,
            MAX(j.updated_date) AS updated_date,
            j.specialist_xid,
            j.nama_bagian,
            j.is_eksekutif
        ', false);

        $this->db->from('view_jadwal_dokter');

        $this->db->where("j.jd_kode_spesialis NOT IN ('012601','012801','012901','012101','012201','013601','013801','013101')");

        $this->db->group_by('
            j.jd_kode_dokter,
            j.nama_pegawai,
            j.url_foto_karyawan,
            j.pengalaman_tahun,
            j.specialist_xid,
            j.nama_bagian,
            j.is_eksekutif
        ');

        $this->db->order_by($order_by, $order_dir);

        // Panggil helper row_number pagination

        $rows = mssql_qb_limit($this->db, $limit, $skip);
        
        // get schedules for returned doctors
        $doctorIds = array();
        foreach ($rows as $r) {
            if (isset($r->jd_kode_dokter)) $doctorIds[] = $r->jd_kode_dokter;
        }

        $schedulesByDoctor = array();

        // print_r($rows); die;
        if (!empty($doctorIds)) {
            $this->db->reset_query();
            // fetch schedule rows for these doctors, apply same optional day filter and specialist filter
            $this->db->from('view_jadwal_dokter j');
            $this->db->where_in('j.jd_kode_dokter', $doctorIds);
            
            // $this->db->where('j.is_active', 'Y');
            if (!empty($specialistXids)) $this->db->where_in('j.jd_kode_spesialis', $specialistXids);
            if (!empty($dayIds)) $this->db->where_in('j.jd_hari', $dayIds);
            $this->db->order_by('j.jd_kode_dokter, j.jd_hari, j.jd_jam_mulai');
            $schedQ = $this->db->get();
            $schedRows = $schedQ->result();

            // echo $this->db->last_query(); die;

            // helper day names
            $dayNames = array(
                0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
                4 => 'Kamis', 5 => "Jumat", 6 => 'Sabtu'
            );

            foreach ($schedRows as $s) {
                $docId = isset($s->jd_kode_dokter) ? $s->jd_kode_dokter : null;
                if ($docId === null) continue;

                // determine service type id (1 BPJS, 2 Umum) - fallback to is_eksekutif if available
                $typeId = ((int)$s->is_eksekutif == 1) ? 2 : 1;

                $typeName = ($typeId > 1) ? 'Umum & Asuransi' : 'BPJS, Umum & Asuransi';

                $dayId = isset($s->jd_hari) ? array_search($s->jd_hari, $dayNames) : 0;

                $dayName = isset($dayNames[$dayId]) ? $dayNames[$dayId] : ('Day '.$dayId);

                // time fields
                $start = isset($s->jd_jam_mulai) ? substr($s->jd_jam_mulai,0,5) : null;
                $end   = isset($s->jd_jam_selesai) ? substr($s->jd_jam_selesai,0,5) : null;
                $tpXid = isset($s->jd_id) ? $s->jd_id : null;

                // init
                if (!isset($schedulesByDoctor[$docId])) $schedulesByDoctor[$docId] = array();
                if (!isset($schedulesByDoctor[$docId][$typeId])) {
                    $schedulesByDoctor[$docId][$typeId] = array(
                        'type' => array('id' => $typeId, 'name' => $typeName),
                        'items' => array() // keyed by dayId for aggregation
                    );
                }
                if (!isset($schedulesByDoctor[$docId][$typeId]['items'][$dayId])) {
                    $schedulesByDoctor[$docId][$typeId]['items'][$dayId] = array(
                        'dayId' => (int)$dayId,
                        'dayName' => $dayName,
                        'timePeriods' => array()
                    );
                }

                // append time period
                $schedulesByDoctor[$docId][$typeId]['items'][$dayId]['timePeriods'][] = array(
                    'xid' => (string)$tpXid,
                    'startTime' => $start,
                    'endTime' => $end
                );
            }
        }

        // flush cached base query
        $this->db->flush_cache();
        $this->db->reset_query();

        // build items response
        $items = array();
        foreach ($rows as $r) {
            $docId = isset($r->jd_kode_dokter) ? $r->jd_kode_dokter : null;
            $name = isset($r->nama_pegawai) ? $r->nama_pegawai : (isset($r->nama_dokter) ? $r->nama_dokter : '');
            // simple slug
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($name)));
            $slug = trim($slug, '-');

            $experience = 0;
            if (isset($r->pengalaman_tahun)) $experience = (int)$r->pengalaman_tahun;
            elseif (isset($r->experience_year)) $experience = (int)$r->experience_year;

            // build schedules list
            $schedulesOut = array();
            if ($docId !== null && isset($schedulesByDoctor[$docId])) {
                foreach ($schedulesByDoctor[$docId] as $typeId => $typeData) {
                    // items was keyed by dayId; convert to list
                    $itemsByDay = array();
                    foreach ($typeData['items'] as $dayItem) {
                        $itemsByDay[] = $dayItem;
                    }
                    // sort itemsByDay by dayId
                    usort($itemsByDay, function($a,$b){ return $a['dayId'] - $b['dayId']; });
                    $schedulesOut[] = array(
                        'type' => $typeData['type'],
                        'items' => $itemsByDay
                    );
                }
            }

            // foto dokter mapping
            $base_url_foto = 'https://registrasi.rssetiamitra.co.id/uploaded/images/photo_karyawan/'.$r->jd_kode_dokter.'.png';

            $items[] = array(
                'xid' => (string)$docId,
                'name' => $name,
                'slug' => $slug,
                'experienceYear' => $experience,
                'specialist' => array(
                    'xid' => isset($r->specialist_xid) ? $r->specialist_xid : null,
                    'name' => isset($r->nama_bagian) ? $r->nama_bagian : ''
                ),
                'schedules' => $schedulesOut,
                'imageFile' => array(
                    'url' => $base_url_foto,
                    'fileName' => isset($r->jd_kode_dokter) ? basename($r->jd_kode_dokter).'.png' : null
                ),
                'createdAt' => isset($r->created_date) && $r->created_date ? date('c', strtotime($r->created_date)) : date('c', strtotime(date('Y-m-d H:i:s'))),
                'updatedAt' => isset($r->updated_date) && $r->updated_date ? date('c', strtotime($r->updated_date)) : (isset($r->created_date) && $r->created_date ? date('c', strtotime($r->created_date)) : null)
            );
        }

        $data = array(
            "items" => $items,
            "metadata" => array(
                "total"  => $total,
                "count"  => count($items),
                "skip"   => $skip,
                "limit"  => $limit,
                "sortBy" => $sortBy ? $sortBy : 'latest'
            )
        );

        $this->respond(true, '200', 'OK', $data);
    }

    /**
     * Service doctor_detail endpoint
     * GET /index.php/api/web_api/doctor_detail
     */
    public function doctor_detail($slug) {
        // Require basic auth
        $this->require_basic_auth();

        $slugParam = trim((string)$slug);
        if ($slugParam === '') {
            $this->respond(false, 'E_DOCTOR_NOT_FOUND', 'Doctor Not Found.', array());
        }

        // try to find doctor by kode_dokter or by name-derived slug
        $searchName = str_replace('-', ' ', $slugParam);

        $this->db->reset_query();
        $this->db->from('mt_karyawan d');
        $this->db->join('tr_jadwal_dokter j', 'd.kode_dokter = j.jd_kode_dokter', 'LEFT');
        $this->db->join('mt_bagian b', 'b.kode_bagian = j.jd_kode_spesialis', 'LEFT');
        $this->db->select('d.kode_dokter as kode_dokter, d.nama_pegawai, d.url_foto_karyawan as foto, d.pengalaman_tahun, d.created_date, d.updated_date, b.kode_bagian as specialist_xid, b.nama_bagian as specialist_name, j.is_eksekutif', false);
        $this->db->group_by('d.kode_dokter, d.nama_pegawai, d.url_foto_karyawan, d.pengalaman_tahun, d.created_date, d.updated_date, b.kode_bagian, b.nama_bagian, j.is_eksekutif');
        // $this->db->group_start();
        // normalize searchName in PHP: lower-case and strip non-letter/number chars
        $normalized = preg_replace('/[^\p{L}\p{N}]+/u', '', mb_strtolower($searchName, 'UTF-8'));
        // compare against a normalized DB value (remove spaces, dots, commas, hyphens and apostrophes)
        $this->db->where("LOWER(dbo.RemoveNonAlpha(d.nama_pegawai))", $normalized);

        // $this->db->group_end();
        $this->db->limit(1);
        $q = $this->db->get();
        $candidates = $q->row();

        // echo $this->db->last_query(); die;

        if (empty($candidates)) {
            $this->respond(false, 'E_DOCTOR_NOT_FOUND', 'Doctor Not Found.', array());
        }

        // helper to build slug same as listing
        $build_slug = function($name){
            $s = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($name)));
            return trim($s, '-');
        };

        $selected = null;
        
        $name = isset($candidates->nama_pegawai) ? $candidates->nama_pegawai : '';
        $computed = $build_slug($name);
        if ($computed === $slugParam || (isset($candidates->kode_dokter) && $candidates->kode_dokter === $slugParam)) {
            $selected = $candidates;
        }

        // fallback to first candidate
        if ($selected === null) $selected = $candidates[0];

        $docId = isset($selected->kode_dokter) ? $selected->kode_dokter : null;
        if ($docId === null) {
            $this->respond(false, 'E_DOCTOR_NOT_FOUND', 'Doctor Not Found.', array());
        }

        // fetch schedules for this doctor
        $this->db->reset_query();
        $this->db->from('tr_jadwal_dokter j');
        $this->db->where('j.jd_kode_dokter', $docId);
        $this->db->order_by('j.jd_hari, j.jd_jam_mulai');
        $schedQ = $this->db->get();
        $schedRows = $schedQ->result();

        
        $dayNames = array(
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis', 5 => "Jumat", 6 => 'Sabtu'
        );
        
        // echo "<pre>"; print_r($this->db->last_query()); die;

        $schedulesByType = array();
        foreach ($schedRows as $s) {

            $typeId = ((int)$s->is_eksekutif == 1) ? 2 : 1;
            $typeName = ($typeId > 1) ? 'Umum & Asuransi' : 'BPJS, Umum & Asuransi';

            $dayId = isset($s->jd_hari) ? $this->tanggal->getNumOfTheDay(strtolower($s->jd_hari)) : 0;
            $dayName = isset($s->jd_hari) ? $s->jd_hari : "";
            // $dayName = isset($dayNames[$dayId]) ? $dayNames[$dayId] : ('Day '.$dayId);
            $start = isset($s->jd_jam_mulai) ? substr($s->jd_jam_mulai,0,5) : null;
            $end   = isset($s->jd_jam_selesai) ? substr($s->jd_jam_selesai,0,5) : null;
            $tpXid = isset($s->jd_id) ? $s->jd_id : null;

            if (!isset($schedulesByType[$typeId])) {
                $schedulesByType[$typeId] = array('type' => array('id' => $typeId, 'name' => $typeName), 'items' => array());
            }
            if (!isset($schedulesByType[$typeId]['items'][$dayId])) {
                $schedulesByType[$typeId]['items'][$dayId] = array(
                    'dayId' => (int)$dayId,
                    'dayName' => $dayName,
                    'timePeriods' => array()
                );
            }
            $schedulesByType[$typeId]['items'][$dayId]['timePeriods'][] = array(
                'xid' => (string)$tpXid,
                'startTime' => $start,
                'endTime' => $end
            );
        }

        
        // echo "<pre>"; print_r($schedulesByType); die;

        // convert schedulesByType items to expected structure
        $schedulesOut = array();
        foreach ($schedulesByType as $typeData) {
            $itemsByDay = array();
            foreach ($typeData['items'] as $dayItem) $itemsByDay[] = $dayItem;
            usort($itemsByDay, function($a,$b){ return $a['dayId'] - $b['dayId']; });
            $schedulesOut[] = array('type' => $typeData['type'], 'items' => $itemsByDay);
        }

        // diseasesTreated & educationalHistory - return empty arrays (adapt to your schema if available)
        $diseases = array();
        $education = array();

        $image_url = (isset($selected->kode_dokter) ? 'https://registrasi.rssetiamitra.co.id/uploaded/images/photo_karyawan/'.$selected->kode_dokter.'.png' : null);
        $createdAt = isset($selected->created_date) && $selected->created_date ? date('c', strtotime($selected->created_date)) : date('c', strtotime(date('Y-m-d H:i:s')));
        $updatedAt = isset($selected->updated_date) && $selected->updated_date ? date('c', strtotime($selected->updated_date)) : $createdAt;

        $data = array(
            'xid' => $docId,
            'name' => isset($selected->nama_pegawai) ? $selected->nama_pegawai : '',
            'slug' => $build_slug(isset($selected->nama_pegawai) ? $selected->nama_pegawai : ''),
            'experienceYear' => isset($selected->pengalaman_tahun) ? (int)$selected->pengalaman_tahun : 0,
            'specialist' => array(
                'xid' => isset($selected->specialist_xid) ? $selected->specialist_xid : null,
                'name' => isset($selected->specialist_name) ? $selected->specialist_name : ''
            ),
            'schedules' => $schedulesOut,
            'diseasesTreated' => $diseases,
            'educationalHistory' => $education,
            'imageFile' => array(
                'url' => $image_url,
                'fileName' => $image_url ? basename($image_url) : null
            ),
            'createdAt' => $createdAt,
            'updatedAt' => $updatedAt
        );

        $this->respond(true, '200', 'OK', $data);
    }

    /**
     * Service doctor_detail endpoint
     * GET /index.php/api/web_api/doctor_detail
     */
    public function doctor_time_available($slug, $methode = null) {
        // Require basic auth
        $this->require_basic_auth();

        $slugParam = trim((string)$slug);

        if ($slugParam === '') {
            $this->respond(false, 'E_DOCTOR_NOT_FOUND', 'Doctor Not Found.', array());
        }

        // find doctor by kode_dokter or name-derived slug (reuse normalized matching)
        $searchName = str_replace('-', ' ', $slugParam);

        $this->db->reset_query();
        $this->db->from('mt_karyawan d');
        $this->db->join('tr_jadwal_dokter j', 'd.kode_dokter = j.jd_kode_dokter', 'LEFT');
        $this->db->select('d.kode_dokter as kode_dokter, d.nama_pegawai', false);
        $this->db->group_by('d.kode_dokter, d.nama_pegawai');

        $normalized = preg_replace('/[^\p{L}\p{N}]+/u', '', mb_strtolower($searchName, 'UTF-8'));
        // remove spaces/dots/commas/hyphens/apostrophes for comparison
        // $this->db->group_start();
        $this->db->where("LOWER(dbo.RemoveNonAlpha(d.nama_pegawai))", $normalized);
        $this->db->or_where('d.kode_dokter', $slugParam);
        // $this->db->group_end();

        $this->db->limit(1);
        $q = $this->db->get();
        $candidate = $q->row();

        if (empty($candidate)) {
            $this->respond(false, 'E_DOCTOR_NOT_FOUND', 'Doctor Not Found.', array());
        }

        $docId = isset($candidate->kode_dokter) ? $candidate->kode_dokter : null;
        $dayId = isset($candidate->jd_hari) ? $candidate->jd_hari : null;
        if ($docId === null) {
            $this->respond(false, 'E_DOCTOR_NOT_FOUND', 'Doctor Not Found.', array());
        }

        // query params
        $limit   = (int)$this->input->get('limit');
        $skip    = (int)$this->input->get('skip');
        $sortBy  = $this->input->get('sortBy');
        $dateStr = trim((string)$this->input->get('date'));

        if ($limit <= 0) $limit = 10;
        if ($skip < 0) $skip = 0;

        // parse date, default to today
        $dateTs = ($dateStr !== '') ? @strtotime($dateStr) : false;
        if ($dateTs === false) $dateTs = time();
        // PHP date('w') returns 0 (Sun) .. 6 (Sat) matching jd_hari mapping used elsewhere
        $dayId = (int)date('w', $dateTs);
        $dayNameToId = array(
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis', 5 => "Jumat", 6 => 'Sabtu'
        );
        $dayName = isset($dayNameToId[$dayId]) ? $dayNameToId[$dayId] : $dayId;

        // fetch all schedule rows for this doctor on that day (count before limit)
        $this->db->reset_query();
        $this->db->from('tr_jadwal_dokter j');
        $this->db->where('j.jd_kode_dokter', $docId);
        $this->db->where('j.jd_hari', $dayName);
        // count total matching
        $qAll = $this->db->get();
        $total = (int)$qAll->num_rows();

        // now apply ordering/limit/offset
        $this->db->reset_query();
        $this->db->from('tr_jadwal_dokter j');
        $this->db->where('j.jd_kode_dokter', $docId);
        $this->db->where('j.jd_hari', $dayName);

        // ordering
        if ($sortBy === 'startTimeAsc') {
            $this->db->order_by('j.jd_jam_mulai', 'ASC');
        } elseif ($sortBy === 'startTimeDesc') {
            $this->db->order_by('j.jd_jam_mulai', 'DESC');
        } else {
            $this->db->order_by('j.jd_jam_mulai', 'ASC');
        }

        if ($limit > 0) $this->db->limit($limit, $skip);
        $q = $this->db->get();
        $rows = $q->result();

        // echo $this->db->last_query(); die;

        // map schedule rows to API items
        $items = array();
        foreach ($rows as $s) {
            $xid = isset($s->jd_id) ? $s->jd_id : null;
            $start = isset($s->jd_jam_mulai) ? substr($s->jd_jam_mulai, 0, 5) : null;
            $end   = isset($s->jd_jam_selesai) ? substr($s->jd_jam_selesai, 0, 5) : null;

            // detect quota field if exists in row (common names)
            $quotaFields = array('jd_kuota');
            $quota = null;
            foreach ($quotaFields as $f) {
                if (isset($s->{$f}) && $s->{$f} !== null && $s->{$f} !== '') {
                    $quota = (int)$s->{$f};
                    break;
                }
            }
            if ($quota === null) {
                // default quota if not available in DB
                $quota = 10;
            }

            // map schedule type: if is_eksekutif truthy -> Umum (id 2), else BPJS (id 1)
            $isEksekutif = isset($s->is_eksekutif) ? (int)$s->is_eksekutif : 0;
            if ($isEksekutif === 1) {
                $scheduleType = array('id' => 2, 'name' => 'Umum');
            } else {
                $scheduleType = array('id' => 1, 'name' => 'BPJS');
            }

            $items[] = array(
                'xid' => (string)$xid,
                'startTime' => $start,
                'endTime' => $end,
                'quota' => $quota,
                'scheduleType' => $scheduleType
            );
        }

        // echo "<pre>"; print_r($items); die;

        // client side sorting fallback (in case developer requested different sortBy values)
        if ($sortBy === 'startTimeAsc') {
            usort($items, function($a,$b){ return strcmp($a['startTime'], $b['startTime']); });
        } elseif ($sortBy === 'startTimeDesc') {
            usort($items, function($a,$b){ return strcmp($b['startTime'], $a['startTime']); });
        }

        $data = array(
            'items' => $items,
            'metadata' => array(
                'total'  => $total,
                'count'  => count($items),
                'skip'   => $skip,
                'limit'  => $limit,
                'sortBy' => $sortBy ? $sortBy : 'latest'
            )
        );

        $this->respond(true, '200', 'OK', $data);
    }

    /**
     * Service polyclinic-queues endpoint
     * GET /index.php/api/web_api/polyclinic-queues
     */
    public function polyclinic_queues() {
        // Require basic auth
        $this->require_basic_auth();

        // query params
        $limit   = (int)$this->input->get('limit');
        $skip    = (int)$this->input->get('skip');
        $sortBy  = $this->input->get('sortBy');
        $doctorXid = $this->input->get('doctorXid');
        $dateStr = trim((string)$this->input->get('date'));
        $timeXid = $this->input->get('timeXid');

        if ($limit <= 0) $limit = 10;
        if ($skip < 0) $skip = 0;
        if (!$sortBy) $sortBy = 'queueNumberAsc';

        // Build real query from pl_tc_poli joined with mt_bagian and mt_karyawan
        // Adjust column names if your schema differs.

        // parse date filter (only date part)
        $filterDate = null;
        if ($dateStr !== '') {
            $ts = @strtotime($dateStr);
            if ($ts !== false) $filterDate = date('Y-m-d', $ts);
        }

        // base cached query
        $this->db->start_cache();
        $this->db->from('pl_tc_poli pl');
        $this->db->join('mt_bagian b', 'b.kode_bagian = pl.kode_bagian', 'LEFT');       // poli/department
        $this->db->join('mt_karyawan d', 'd.kode_dokter = pl.kode_dokter', 'LEFT');   // doctor/staff

        // optional filters
        if ($doctorXid) {
            // try common doctor column names used in different schemas
            $this->db->group_start();
            $this->db->where('pl.kode_dokter', $doctorXid);
            $this->db->group_end();
            $skip = 0;
        }

        if ($filterDate !== null) {
            // use DATE() to compare date part of tgl_jam_poli
            $this->db->where("CAST(pl.tgl_jam_poli as DATE) = ", $filterDate);
            $skip = 0;
        }

        if ($timeXid) {
            // if you store a schedule/time id in pl_tc_poli adjust column name below
            $this->db->group_start();
            $this->db->join('tc_kunjungan', 'tc_kunjungan.no_kunjungan = pl.no_kunjungan', 'LEFT');
            $this->db->join('tc_registrasi', 'tc_registrasi.no_registrasi = tc_kunjungan.no_registrasi', 'LEFT');
            $this->db->where('tc_registrasi.jd_id', $timeXid);
            $this->db->group_end();
            $skip = 0;
        }

        $this->db->stop_cache();

       

        // total before paging
        $total = (int)$this->db->count_all_results();

        // ordering
        switch ($sortBy) {
            case 'queueNumberAsc':
            $this->db->order_by('pl.no_antrian', 'ASC');
            break;
            case 'queueNumberDesc':
            $this->db->order_by('pl.no_antrian', 'DESC');
            break;
            case 'timeAsc':
            $this->db->order_by('pl.tgl_jam_poli', 'ASC');
            break;
            case 'timeDesc':
            $this->db->order_by('pl.tgl_jam_poli', 'DESC');
            break;
            default:
            $this->db->order_by('pl.no_antrian', 'ASC');
        }

        // fetch rows with limit/offset
        $query = $this->db->get(null, $limit, $skip);
        $rows = $query->result();
        //  echo $this->db->last_query(); die;

        // cleanup cache
        $this->db->flush_cache();
        $this->db->reset_query();

        // map DB rows to API items
        $items = array();
        foreach ($rows as $r) {
            $xid = isset($r->no_kunjungan) ? (string)$r->no_kunjungan : null;
            $queueNumber = isset($r->no_antrian) ? (int)$r->no_antrian : null;
            // time: try to extract time portion
            $time = null;
            if (isset($r->tgl_jam_poli) && $r->tgl_jam_poli) {
                $time = substr($r->tgl_jam_poli, 11, 5); // HH:MM
            }

            // determine type (BPJS / Umum) heuristics: prefer explicit fields if present
            $type = array('id' => 2, 'name' => 'Umum'); // default Umum
            if (isset($r->flag_antrian) && stripos($r->flag_antrian, 'bpjs') !== false) {
                $type = array('id' => 1, 'name' => 'BPJS');
            } 
            // determine status (map some common status values)
            $statusId = 1; 
            $statusName = 'Pending';

            if (isset($r->status_periksa)) {
                if($r->status_periksa == 3){
                    $statusId = 3; 
                    $statusName = 'Done';
                }elseif($r->status_periksa == 2){
                    $statusId = 2; 
                    $statusName = 'In Progress';
                }
            }

            $items[] = array(
            'xid' => $xid,
            'queueNumber' => $queueNumber,
            'type' => $type,
            'status' => array('id' => $statusId, 'name' => $statusName)
            );
        }

        $data = array(
            'items' => $items,
            'metadata' => array(
                'total'  => $total,
                // preserve requested limit as 'count' to match provided sample response
                'count'  => count($rows),
                'skip'   => $skip,
                'limit'  => $limit,
                'sortBy' => $sortBy
            )
        );

        $this->respond(true, '200', 'OK', $data);
    }

    /**
     * Service payout_available endpoint
     * GET /index.php/api/web_api/payout_available
     */
    public function payout_available() {
        // Require basic auth
        $this->require_basic_auth();

        // read query params
        $doctorXid = trim((string)$this->input->get('doctorXid'));
        $dateStr   = trim((string)$this->input->get('date'));
        $timeXid   = trim((string)$this->input->get('timeXid'));

        // simple validation
        if ($doctorXid === '' || $dateStr === '' || $timeXid === '') {
            $this->respond(false, 'E_INVALID_PARAMS', 'Missing required parameters doctorXid, date or timeXid.', array());
        }

        // validate date
        $ts = @strtotime($dateStr);
        if ($ts === false) {
            $this->respond(false, 'E_INVALID_DATE', 'Invalid date format.', array());
        }

        // For the requested example URL (and generally), return available payout types.
        // Adjust logic here to query DB/business rules if needed.
        $dayId = (int)date('w', $ts);
        $dayNames = array(
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis', 5 => "Jumat", 6 => 'Sabtu'
        );
        $dayName = isset($dayNames[$dayId]) ? $dayNames[$dayId] : null;

        // Query tr_jadwal_dokter filtered by doctor, day and jd_id (timeXid)
        $this->db->reset_query();
        $this->db->from('tr_jadwal_dokter j');
        $this->db->where('j.jd_kode_dokter', $doctorXid);
        if ($dayName !== null) {
            $this->db->where('j.jd_hari', $dayName);
        }
        if ($timeXid !== '') {
            $this->db->where('j.jd_id', $timeXid);
        }
        $this->db->limit(1);
        $q = $this->db->get();
        $schedule = $q->row();

        // if no matching schedule found, return empty result / error as appropriate
        if (empty($schedule)) {
            $this->respond(false, 'E_TIME_NOT_FOUND', 'Schedule not found for given doctor/date/time.', array());
        }

        // determine available payout types based on schedule is_eksekutif flag
        if (isset($schedule->is_eksekutif) && $schedule->is_eksekutif !== null && (int)$schedule->is_eksekutif === 1) {
            // eksekutif -> only Umum and Asuransi
            $data = array(
                array('id' => 2, 'name' => 'Umum'),
                array('id' => 3, 'name' => 'Asuransi')
            );
        } else {
            // non-eksekutif or unknown (null/other) -> BPJS, Umum and Asuransi
            $data = array(
                array('id' => 1, 'name' => 'BPJS'),
                array('id' => 2, 'name' => 'Umum'),
                array('id' => 3, 'name' => 'Asuransi')
            );
        }

        $this->respond(true, '200', 'OK', $data);
    }

    /**
     * Service payout_available endpoint
     * GET /index.php/api/web_api/payout_available
     */
    public function appointments($xid = '')
    {
        // Require basic auth
        $this->require_basic_auth();

        // params
        $limit   = (int)$this->input->get('limit');
        $skip    = (int)$this->input->get('skip');
        $sortBy  = $this->input->get('sortBy') ?: 'latest';
        $nik     = trim((string)$this->input->get('nik'));
        $bpjsId  = trim((string)$this->input->get('bpjsId'));
        $medicalRecordId = trim((string)$this->input->get('medicalRecordId'));
        $dateStr = trim((string)$this->input->get('date'));

        if ($limit <= 0) $limit = 10;
        if ($skip < 0) $skip = 0;

        // require identifier when not fetching single appointment by xid
        if($xid == '' && $_SERVER['REQUEST_METHOD'] == 'GET'){
            if ($medicalRecordId === '' && $nik === '' && $bpjsId === '') {
                $this->respond(false, 'E_INVALID_PARAMS', 'Missing at least one patient identifier: medicalRecordId, nik or bpjsId.', array());
            }
        }
        
        if($xid != '' && $_SERVER['REQUEST_METHOD'] == 'GET'){
            $xid = trim((string)$xid);
            if (!ctype_digit($xid)) {
                $this->respond(false, 'E_INVALID_PARAMS', 'Invalid appointment xid. It must be an integer.', array());
            }
            // optionally cast to int for later queries
            $xid = (int)$xid;
        }
        
        // helper: map a DB row to API item and compute estimated time
        $mapRow = function ($pr, $dateStr) {
            // determine date to use
            $sampleDateTs = time();
            if ($dateStr !== '') {
                $tmp = @strtotime($dateStr);
                if ($tmp !== false) $sampleDateTs = $tmp;
            }

            $dateOnly = date('Y-m-d', $sampleDateTs);

            // times & queue
            $startTimeRaw = isset($pr->jd_jam_mulai) ? trim($pr->jd_jam_mulai) : '';
            $endTimeRaw   = isset($pr->jd_jam_selesai) ? trim($pr->jd_jam_selesai) : '';
            $queueNumber  = isset($pr->no_antrian) ? (int)$pr->no_antrian : 0;

            $estimatedIso = null;
            if ($startTimeRaw !== '') {
                $startTs = @strtotime($dateOnly . ' ' . $startTimeRaw);
                if ($startTs !== false) {
                    $offsetSeconds = $queueNumber * 5 * 60;
                    $estimatedTs = $startTs + $offsetSeconds;
                    if ($endTimeRaw !== '') {
                        $endTs = @strtotime($dateOnly . ' ' . $endTimeRaw);
                        if ($endTs !== false && $estimatedTs > $endTs) {
                            $estimatedTs = max($startTs, $endTs - 30 * 60);
                        }
                    }
                    $estimatedIso = date('c', $estimatedTs);
                }
            }

            if ($estimatedIso === null) {
                $estimatedIso = date('c', strtotime($dateOnly . ' 16:15:00'));
            }

            if (isset($pr->kode_perusahaan) && $pr->kode_perusahaan == 120) {
                $payout = array('id' => 1, 'name' => 'BPJS');
            } else {
                $payout = (isset($pr->kode_kelompok) && $pr->kode_kelompok == 1)
                    ? array('id' => 2, 'name' => 'Umum')
                    : array('id' => 3, 'name' => 'Asuransi');
            }

            // insurance / payout heuristics
            if( in_array($payout['id'], [1,2]) ){
                $insurance = null;
            }else{
                $insurance = array(
                    'xid' => isset($pr->kode_perusahaan) ? (string)$pr->kode_perusahaan : null,
                    'name' => isset($pr->nama_perusahaan) ? $pr->nama_perusahaan : null
                );
            }
            

            $statusAppointment = (isset($pr->status_batal) && $pr->status_batal == 1)
                ? array('id' => 2, 'name' => 'Cancelled')
                : array('id' => 1, 'name' => 'Active');

            $doctorXid  = isset($pr->kode_dokter) ? $pr->kode_dokter : null;
            $doctorName = isset($pr->nama_dokter) ? $pr->nama_dokter : null;
            $doctorSlug = $doctorName ? strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($doctorName))) : null;
            

            return array(
                'xid' => isset($pr->no_kunjungan) ? (string)$pr->no_kunjungan : null,
                'appointmentCode' => isset($pr->kodebookingantrol) ? (string)$pr->kodebookingantrol : null,
                'queueNumber' => isset($pr->no_antrian) ? (int)$pr->no_antrian : null,
                'code' => isset($pr->no_registrasi) ? (string)$pr->no_registrasi : null,
                'date' => $dateOnly,
                'estimatedTurnTime' => (string)$estimatedIso,
                'time' => array(
                    'xid' => isset($pr->jd_id) ? (string)$pr->jd_id : null,
                    'startTime' => $startTimeRaw !== '' ? substr($startTimeRaw, 0, 5) : null,
                    'endTime' => $endTimeRaw !== '' ? substr($endTimeRaw, 0, 5) : null
                ),
                'payout' => $payout,
                'bpjs' => (!empty($pr->no_kartu_bpjs) ? array(
                    'id' => $pr->no_kartu_bpjs,
                    'referenceNumber' => isset($pr->norujukan) ? $pr->norujukan : null
                ) : null),
                'insurance' => $insurance,
                'status' => $statusAppointment,
                'patient' => array(
                    'xid' => isset($pr->id_mt_master_pasien) ? (string)$pr->id_mt_master_pasien : null,
                    'name' => isset($pr->nama_pasien) ? $pr->nama_pasien : '',
                    'nik' => isset($pr->no_ktp) ? $pr->no_ktp : '',
                    'medicalRecordId' => isset($pr->patient_no_mr) ? $pr->patient_no_mr : '',
                    'bjpsId' => isset($pr->no_kartu_bpjs) ? $pr->no_kartu_bpjs : null,
                    'insurance' => $insurance
                ),
                'doctor' => array(
                    'xid' => $doctorXid,
                    'name' => $doctorName,
                    'slug' => $doctorSlug,
                    'experienceYear' => isset($pr->pengalaman_tahun) ? (int)$pr->pengalaman_tahun : 0,
                    'specialist' => array(
                        'xid' => isset($pr->kode_bagian) ? $pr->kode_bagian : null,
                        'name' => isset($pr->nama_poli) ? $pr->nama_poli : ''
                    ),
                    'imageFile' => array(
                        'url' => $doctorXid ? 'https://registrasi.rssetiamitra.co.id/uploaded/images/photo_karyawan/' . $doctorXid . '.png' : null,
                        'fileName' => $doctorXid ? basename($doctorXid) . '.png' : null
                    )
                ),
                'createdAt' => isset($pr->created_date) && $pr->created_date ? date('c', strtotime($pr->created_date)) : date('c', strtotime(date('Y-m-d H:i:s'))),
                'updatedAt' => isset($pr->updated_date) && $pr->updated_date ? date('c', strtotime($pr->updated_date)) : date('c', strtotime(date('Y-m-d H:i:s')))
            );
        };

        // build base query (single query for both list and single)
        $this->db->reset_query();
        $this->db->select([
            'mp.id_mt_master_pasien',
            'pl.no_kunjungan',
            'pl.no_antrian',
            'pl.tgl_jam_poli',
            'pl.nama_pasien',
            'bg.nama_bagian as nama_poli',
            'pl.kode_dokter', 'kr.nama_pegawai as nama_dokter', 'kr.pengalaman_tahun',
            'p.no_mr AS patient_no_mr', 'pl.kode_bagian',
            'r.no_registrasi', 'r.norujukan',
            'r.kode_perusahaan', 'r.kode_kelompok', 'r.kodebookingantrol',
            'mp.no_ktp', 'mp.no_kartu_bpjs',
            'pr.nama_perusahaan',
            'jd.jd_id', 'jd.jd_jam_mulai', 'jd.jd_jam_selesai',
            'pl.created_date', 'pl.updated_date', 'pl.status_batal'
        ]);
        $this->db->from('pl_tc_poli pl');
        $this->db->join('mt_bagian bg', 'bg.kode_bagian = pl.kode_bagian', 'LEFT');
        $this->db->join('tc_kunjungan p', 'p.no_kunjungan = pl.no_kunjungan', 'LEFT');
        $this->db->join('mt_karyawan kr', 'kr.kode_dokter = pl.kode_dokter', 'LEFT');
        $this->db->join('tc_registrasi r', 'r.no_registrasi = p.no_registrasi', 'LEFT');
        $this->db->join('mt_master_pasien mp', 'mp.no_mr = p.no_mr', 'LEFT');
        $this->db->join('mt_perusahaan pr', 'pr.kode_perusahaan = r.kode_perusahaan', 'LEFT');
        $this->db->join('tr_jadwal_dokter jd', 'jd.jd_id = r.jd_id', 'LEFT');

        // filters
        if ($xid !== '') {
            $this->db->where('pl.no_kunjungan', $xid);
            $this->db->limit(1);
        } else {
            if ($medicalRecordId !== '') {
                $this->db->where('p.no_mr', $medicalRecordId);
            } elseif ($nik !== '') {
                $this->db->where('mp.no_ktp', $nik);
            } elseif ($bpjsId !== '') {
                $this->db->where('mp.no_kartu_bpjs', $bpjsId);
            }
            if ($dateStr !== '') {
                $dateOnly = date('Y-m-d', strtotime($dateStr));
                $this->db->where("DATE(pl.tgl_jam_poli) =", $dateOnly);
            }
        }

        // ordering
        if ($sortBy === 'oldest') {
            $this->db->order_by('pl.tgl_jam_poli', 'ASC');
        } else {
            $this->db->order_by('pl.tgl_jam_poli', 'DESC');
        }

        // pagination for list
        if ($xid === '') {
            $this->db->limit($limit, $skip);
        }

        $query = $this->db->get();
        $rows = $query->result();

        // echo "<pre>";print_r($rows); die;

        // if single requested return single object
        if ($xid !== '') {
            if (empty($rows)) {
                $this->respond(false, 'E_APPOINTMENT_NOT_FOUND', 'Appointment Not Found.', array());
            }
            
            // jika customrequest delete maka return empty object with success
            if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                // header X-timezone
                $timezone = $this->input->get_request_header('X-Timezone', TRUE);
                // convert timezon from request header to datetime
                $currentDateTime = new DateTime("now", new DateTimeZone($timezone ? $timezone : 'UTC'));
                $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
                
                // here you can implement actual deletion logic if needed
                $this->db->update('pl_tc_poli', array('status_batal' => 1, 'updated_date' => $formattedDateTime), array('no_kunjungan' => $xid));
                $this->db->update('tc_kunjungan', array('status_batal' => 1, 'updated_date' => $formattedDateTime), array('no_kunjungan' => $xid));

                $this->respond(true, '200', 'OK', (object)[]);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                // header X-timezone
                $timezone = $this->input->get_request_header('X-Timezone', TRUE);
                // convert timezon from request header to datetime
                $currentDateTime = new DateTime("now", new DateTimeZone($timezone ? $timezone : 'UTC'));
                $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');

                $rawData = file_get_contents('php://input');
                $postData = json_decode($rawData, true);
                // echo "<pre>";print_r($postData); die;
                $kode_perusahaan = null;
                if( $postData['payoutId'] == 1){
                    $kode_perusahaan = 120;
                }elseif ( $postData['payoutId'] == 3 ) {
                    $kode_perusahaan = $postData['insuranceXid'];
                }
                
                // here you can implement actual deletion logic if needed
                // pl_tc_poli
                $this->db->update('pl_tc_poli', array('status_batal' => null, 'updated_date' => $formattedDateTime, 'tgl_jam_poli' => $postData['date']), array('no_kunjungan' => $xid));
                // update tc_kunjungan
                $this->db->update('tc_kunjungan', array('status_batal' => null, 'updated_date' => $formattedDateTime, 'tgl_masuk' => $postData['date']), array('no_kunjungan' => $xid));
                // update tc_registrasi
                $no_registrasi = $this->db->get_where('tc_kunjungan', array('no_kunjungan' => $xid))->row()->no_registrasi;
                $this->db->update('tc_registrasi', array('status_batal' => null, 'updated_date' => $formattedDateTime, 'tgl_jam_masuk' => $postData['date'], 'kode_perusahaan' => $kode_perusahaan, 'norujukan' => $postData['bpjsReferenceNumber']), array('no_registrasi' => $no_registrasi));
            }

            $strDate = isset($postData['date']) ? $postData['date'] : date('Y-m-d', strtotime($rows[0]->tgl_jam_poli));
            $item = $mapRow($rows[0], $strDate);
            $this->respond(true, '200', 'OK', $item);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // get raw data from request body
            $rawData = file_get_contents('php://input');
            $postData = json_decode($rawData, true);
            // get necessary data from postData
            $post = (object)$postData;
            // echo "<pre>";print_r($post); die;
            $this->load->library('regex');
            $id_mt_master_pasien = $this->regex->_genRegex($post->patientXid, 'RGXINT');
            $kode_dokter        = $this->regex->_genRegex($post->doctorXid, 'RGXINT');
            $norujukan          = $this->regex->_genRegex($post->bpjsReferenceNumber,'RGXQSL');
            $tanggalperiksa     = $this->regex->_genRegex($post->date,'RGXQSL');
            $jd_id              = $this->regex->_genRegex($post->timeXid,'RGXQSL');
            $kode_perusahaan    = $this->regex->_genRegex($post->insuranceXid,'RGXQSL');
            $payoutId    = $this->regex->_genRegex($post->payoutId,'RGXINT');

            // get peserta data
            $dt_peserta = $this->db->get_where('mt_master_pasien', array('id_mt_master_pasien' => $id_mt_master_pasien))->row();
            if(!$dt_peserta){
                $this->respond(false, 'E_PATIENT_NOT_FOUND', 'Patient Not Found.', array());
            }

            if($payoutId == 1){
                // get data registrasi
                $dt_registrasi = $this->db->get_where('tc_registrasi', array('no_mr' => $dt_peserta->no_mr, 'CAST(tgl_jam_masuk as DATE) = ' => $tanggalperiksa, 'kode_dokter' => $kode_dokter))->row();

                if($dt_registrasi){
                    $this->respond(false, 'E_MAX_APPOINTMENTS_REACHED', 'Anda hanya bisa melakukan 1 kali registrasi per hari ke dokter dan spesialis yang sama. ', array());
                }

                $kode_perusahaan = 120;
            }


            // umur peserta
            $umur = $this->tanggal->GetAge($dt_peserta->tgl_lhr);
            // jenis pasien
            if($payoutId == 1){
                $jenis_pasien = 'bpjs';
            }elseif($payoutId == 2){
                $jenis_pasien = 'umum';
            }elseif($payoutId == 3){
                $jenis_pasien = 'asuransi'; 
            }

            // echo "<pre>";print_r($jenis_pasien); die;

            $params_dt = array(
                    'id_mt_master_pasien' => $id_mt_master_pasien,
                    'tgl_registrasi' => $tanggalperiksa,
                    'kode_dokter' => $kode_dokter,
                    'no_mr' => $this->regex->_genRegex($dt_peserta->no_mr, 'RGXQSL'),
                    'umur' => $umur,
                    'is_expired' => 2,
                    'no_telp' => $dt_peserta->tlp_almt_ttp,
                    'jenis_pasien' => $jenis_pasien,
                    'kode_perusahaan' => $kode_perusahaan,
                    'jd_id' => $jd_id,
                    'no_rujukan' => $norujukan,
                    'no_kartu_bpjs' => $dt_peserta->no_kartu_bpjs,
                    'nama_pasien' => $dt_peserta->nama_pasien,
                    'no_hp' => $dt_peserta->no_hp,
                    'no_ktp' => $dt_peserta->no_ktp,
                    'tipe_daftar' => 'online_web',
                    'payoutId' => $payoutId,
            );

            $this->appointments_process($params_dt);

            $this->respond(true, '200', 'OK', $item);
        }

        // build list response
        $items = [];
        foreach ($rows as $r) {
            $items[] = $mapRow($r, $dateStr);
        }

        $data = array(
            'items' => $items,
            'metadata' => array(
                'total' => count($items),
                'count' => count($items),
                'skip'  => $skip,
                'limit' => $limit,
                'sortBy'=> $sortBy
            )
        );

        $this->respond(true, '200', 'OK', $data);
    }

    public function appointments_process($params){
        
        // echo "<pre>";print_r($params); die;
        // get jadwal dokter
        $jadwal_dokter = $this->db->join('mt_karyawan','mt_karyawan.kode_dokter = tr_jadwal_dokter.jd_kode_dokter','LEFT')->join('mt_bagian','mt_bagian.kode_bagian = tr_jadwal_dokter.jd_kode_spesialis','LEFT')->get_where('tr_jadwal_dokter', array('jd_id' => $params['jd_id']))->row();

        if(!$jadwal_dokter){
            $this->respond(false, 'E_SCHEDULE_NOT_FOUND', 'Jadwal Dokter tidak ditemukan.', array());
        }

        // validasi dokter sesuai jadwal
        if($jadwal_dokter->jd_kode_dokter != $params['kode_dokter']){
            $this->respond(false, 'E_DOCTOR_MISMATCH', 'Jadwal Dokter tidak sesuai dengan dokter yang dipilih.', array());
        }

        // validasi khusus untuk payout BPJS
        if($params['jenis_pasien'] == 'bpjs'){
            // cek rujukan bpjs
            $rujukan = $this->Ws_index->getData('Rujukan/'.$params['no_rujukan'].'');
            // echo "<pre>"; print_r($rujukan); die;
            if(!$rujukan){
                $this->respond(false, 'E_INVALID_REFERRAL', 'Rujukan BPJS tidak valid.', array());
            }else{
                if(isset($rujukan['response']) && $rujukan['response']->metaData->code == 202){
                    $this->respond(false, 'E_INVALID_REFERRAL', $rujukan['response']->metaData->message, array());
                }else{
                    $rujukan_dt = $rujukan['data'];
                }
            }

            // valdiasi kesesuaian Poliklinik
            if($rujukan_dt->rujukan->poliRujukan->kode != $jadwal_dokter->kode_poli_bpjs){
                $this->respond(false, 'E_POLYCLINIC_MISMATCH', 'Poliklinik pada rujukan BPJS tidak sesuai dengan poliklinik yang dipilih. Untuk Rujukan Internal silahkan datang langsung ke Bagian Pendaftaran', array());
            }
        }

        // validasi date and day 
        $dayNames = array(
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis', 5 => "Jumat", 6 => 'Sabtu'
        );
        $dayId = (int)date('w', strtotime($params['tgl_registrasi']));
        $dayName = isset($dayNames[$dayId]) ? $dayNames[$dayId] : null;
        if($jadwal_dokter->jd_hari != $dayName){
            $this->respond(false, 'E_SCHEDULE_MISMATCH', 'Jadwal Dokter tidak tersedia pada hari '. $dayName .', silahkan pilih jadwal lain.', array());
        }

        // define variabel
        $datapoli = array();
        $title = $this->title;
        $no_mr = $this->regex->_genRegex($params['no_mr'],'RGXQSL');
        $kode_perusahaan = ($params['jenis_pasien'] == 'bpjs') ? 120 : $this->regex->_genRegex($params['kode_perusahaan'],'RGXINT');
        $kode_kelompok =  ($params['jenis_pasien'] == 'umum') ? 1 : 3;
        $kode_dokter = $this->regex->_genRegex($params['kode_dokter'],'RGXINT');
        $kode_bagian_masuk = $this->regex->_genRegex($jadwal_dokter->jd_kode_spesialis,'RGXQSL');
        $umur_saat_pelayanan = $this->regex->_genRegex($params['umur'],'RGXINT');
        $no_sep = '012R034Vxxxx';
        $jd_id =  $params['jd_id'];
        $kode_faskes =  isset($rujukan_dt->rujukan->provPerujuk->kode)?$rujukan_dt->rujukan->provPerujuk->kode:'';
        $tgl_registrasi = $params['tgl_registrasi'].' '.date('H:i:s');

        /*execution*/
        $this->db->trans_begin();

        // validasi tanggal kunjungan
        $max_date = date('Y-m-d', strtotime('+1 day'));
        if($max_date < $params['tgl_registrasi']){
            $this->respond(false, 'E_NOT_AVAILABLE', 'Anda belum bisa melakukan pendaftaran pada tanggal '.$params['tgl_registrasi'].'', array());
        }
        
        if($params['jenis_pasien'] == 'bpjs'){
            // cek kunjungan by date
            $cek_kunjungan_by_date = $this->Pelayanan_publik->cek_kunjungan_by_date($params['tgl_registrasi'], $no_mr);
            if($cek_kunjungan_by_date->num_rows() > 0){
                $obj = $cek_kunjungan_by_date->row();
                $this->respond(false, 'E_MAX_APPOINTMENTS_REACHED', 'Anda sudah pernah terdaftar pada tanggal yang sama, yaitu tanggal'.$obj->tgl_jam_poli.' dengan tujuan ke '.ucwords($obj->nama_bagian).'', array());
            }
        }

        /*save tc_registrasi*/
        $data_registrasi = $this->daftar_pasien->daftar_registrasi($title, $no_mr, $kode_perusahaan, $kode_kelompok, $kode_dokter, $kode_bagian_masuk, $umur_saat_pelayanan, $no_sep, $jd_id, $kode_faskes, $tgl_registrasi, $params['no_rujukan']);
        $no_registrasi = isset($data_registrasi['no_registrasi'])?$data_registrasi['no_registrasi']:'';
        $no_kunjungan = isset($data_registrasi['no_kunjungan'])?$data_registrasi['no_kunjungan']:'';
        
        /*insert pl tc poli*/
        $kode_poli = $this->master->get_max_number('pl_tc_poli', 'kode_poli');
        $tipe_antrian = ($kode_perusahaan != 120) ? 'umum' : 'bpjs';
        $no_antrian = $this->master->get_no_antrian_poli($kode_bagian_masuk, $kode_dokter, $tipe_antrian, $tgl_registrasi);
        
        $datapoli['kode_poli'] = $kode_poli;
        $datapoli['no_kunjungan'] = $no_kunjungan;
        $datapoli['kode_bagian'] = $this->regex->_genRegex($kode_bagian_masuk,'RGXQSL');
        $datapoli['tgl_jam_poli'] = $tgl_registrasi;
        $datapoli['kode_dokter'] = $this->regex->_genRegex($kode_dokter,'RGXINT');
        $datapoli['flag_antrian'] = $tipe_antrian;
        $datapoli['no_antrian'] = $no_antrian;
        $datapoli['nama_pasien'] = $params['nama_pasien'];
        $datapoli['tipe_daftar'] = 'online_web';
        
        /*save poli*/
        $this->db->insert('pl_tc_poli', $datapoli);

        /*jika terdapat id_tc_pesanan maka update tgl_masuk pada table tc_pesanan*/
        $get_data_perjanjian = $this->db->get_where('tc_pesanan', array('no_mr' => $no_mr, 'CAST(tgl_pesanan as DATE) = ' => $params['tgl_registrasi'], 'kode_dokter' => $kode_dokter, 'no_poli' => $kode_poli) )->row();

        if( isset($get_data_perjanjian->id_tc_pesanan) ){
            
            $this->db->update('tc_pesanan', array('tgl_masuk' => $tgl_registrasi, 'nopesertabpjs' => $_POST['no_kartu_bpjs'] ), array('id_tc_pesanan' => $get_data_perjanjian->id_tc_pesanan ) );

            // update kuota dokter used
            $this->logs->update_status_kuota(array('kode_dokter' => $datapoli['kode_dokter'], 'kode_spesialis' => $datapoli['kode_bagian'], 'tanggal' => date('Y-m-d'), 'keterangan' => null, 'flag' => 'perjanjian', 'status' => NULL ), 1);
            $kode_booking = $get_data_perjanjian->kode_perjanjian;

        }

        $kuota_dr = $jadwal_dokter->jd_kuota;
        // sisa kuota jkn dan non jkn
        $kuotajkn = (($kuota_dr / 2) * 20 / 100);
        $kuotanonjkn = (($kuota_dr/2) * 40 / 100);

        $post_antrol = array(
            "kodebooking" => $no_registrasi,
            "jenispasien" => "NON JKN",
            "nomorkartu" => $params['no_kartu_bpjs'],
            "nik" => $params['no_ktp'],
            "nohp" => $params['no_hp'],
            "kodepoli" => $jadwal_dokter->kode_poli_bpjs,
            "namapoli" => $jadwal_dokter->nama_bagian,
            "pasienbaru" => 0,
            "norm" => $no_mr,
            "tanggalperiksa" => $this->tanggal->formatDateBPJS($params['tgl_registrasi']),
            "kodedokter" => $jadwal_dokter->kode_dokter_bpjs,
            "namadokter" => $jadwal_dokter->nama_pegawai,
            "jampraktek" => $this->tanggal->formatTime($jadwal_dokter->jd_jam_mulai).'-'.$this->tanggal->formatTime($jadwal_dokter->jd_jam_selesai),
            "jam_praktek_mulai" => $this->tanggal->formatTime($jadwal_dokter->jd_jam_mulai),
            "jam_praktek_selesai" => $this->tanggal->formatTime($jadwal_dokter->jd_jam_selesai),
            "jeniskunjungan" => 3,
            "nomorreferensi" => $params['no_rujukan'],
            "nomorantrean" => $jadwal_dokter->kode_poli_bpjs.'-'.$no_antrian,
            "angkaantrean" => $no_antrian,
            "sisakuotajkn" => ceil($kuotajkn),
            "kuotajkn" => ceil($kuota_dr/2),
            "sisakuotanonjkn" => ceil($kuotanonjkn),
            "kuotanonjkn" => ceil($kuota_dr/2),
        );

        
        $insuranceName = null;
        if($params['payoutId'] == 1){
            $antrol = $this->processAntrol($post_antrol);
            $payout = array('id' => 1, 'name' => 'BPJS');
        }else{
            $payout = (isset($kode_perusahaan) && $kode_perusahaan == 0)
                ? array('id' => 2, 'name' => 'Umum')
                : array('id' => 3, 'name' => 'Asuransi');
            $insuranceName = $this->master->get_string_data('nama_perusahaan', 'mt_perusahaan', array('kode_perusahaan' => $kode_perusahaan));
        }

        $insuranceData = null;
        if(in_array($params['payoutId'], [1,2])){
            $insuranceData = null;
        }else{
            $insuranceData = array(
                'xid' => $kode_perusahaan,
                'name' => $insuranceName
            );
        }

        // estimasi dilayani
        // times & queue
        $startTimeRaw = $post_antrol['jam_praktek_mulai'];
        $endTimeRaw   = $post_antrol['jam_praktek_selesai'];
        $queueNumber  = $post_antrol['angkaantrean'];

        $estimatedIso = null;
        if ($startTimeRaw !== '') {
            $startTs = @strtotime($dateOnly . ' ' . $startTimeRaw);
            if ($startTs !== false) {
                $offsetSeconds = $queueNumber * 5 * 60;
                $estimatedTs = $startTs + $offsetSeconds;
                if ($endTimeRaw !== '') {
                    $endTs = @strtotime($dateOnly . ' ' . $endTimeRaw);
                    if ($endTs !== false && $estimatedTs > $endTs) {
                        $estimatedTs = max($startTs, $endTs - 30 * 60);
                    }
                }
                $estimatedIso = date('c', $estimatedTs);
            }
        }

        if ($estimatedIso === null) {
            $estimatedIso = date('c', strtotime($dateOnly . $startTimeRaw));
        }

        // return response
        $items = [
                    "xid" => (string)$no_kunjungan,
                    "appointmentCode" => (string)$no_registrasi,
                    "queueNumber" => $no_antrian,
                    "code" => (string)$no_registrasi, // for qr code => encrypt no registrasi
                    "date" => $params['tgl_registrasi'],
                    "estimatedTurnTime" => (string)$estimatedIso,
                    "time" => [
                        "xid" => (string)$jd_id,
                        "startTime" =>  $this->tanggal->formatTime($jadwal_dokter->jd_jam_mulai),
                        "endTime" =>  $this->tanggal->formatTime($jadwal_dokter->jd_jam_selesai)
                    ],
                    "payout" => $payout,
                    "bpjs" => [
                        "id" => $params['no_kartu_bpjs'],
                        "referenceNumber" => ($params['no_rujukan'])?$params['no_rujukan']:null
                    ], // nullable
                    "insurance" => $insuranceData, // nullable
                    "status" => [
                        "id" => 1,
                        "name" => "Active"
                    ],
                    "patient" => [
                        "xid" => $params['id_mt_master_pasien'],
                        "name" => $params['nama_pasien'],
                        "nik" => $params['no_ktp'],
                        "medicalRecordId" => $params['no_mr'],
                        "bjpsId" => $params['no_kartu_bpjs'], // nullable
                        "insurance" => null
                    ],
                    "doctor" => [
                        "xid" => $kode_dokter,
                        "name" => $jadwal_dokter->nama_pegawai,
                        "slug" => strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($jadwal_dokter->nama_pegawai))),
                        "experienceYear" => 0,
                        "specialist" => [
                            "xid" => $jadwal_dokter->kode_bagian,
                            "name" => $jadwal_dokter->nama_bagian
                        ],
                        'imageFile' => [
                            'url' => $kode_dokter ? 'https://registrasi.rssetiamitra.co.id/uploaded/images/photo_karyawan/' . $kode_dokter . '.png' : null,
                            'fileName' => $kode_dokter ? basename($kode_dokter) . '.png' : null
                        ]
                    ],
                    'createdAt' => date('c', strtotime(date('Y-m-d H:i:s'))),
                    'updatedAt' => date('c', strtotime(date('Y-m-d H:i:s')))
                ];


        // echo '<pre>'; print_r($antrol);die;

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->respond(false, '200', 'Proses gagal dilakukan', array());
        }
        else
        {
            $this->db->trans_commit();
            // get detail data
            $this->respond(true, '200', 'Proses berhasil!', $items);
        }
        
    }

    public function processAntrol($params){
        
        // estimasi dilayani
        $jam_mulai_praktek = $this->tanggal->formatFullTime($params['jam_praktek_mulai']);
        $jam_selesai_praktek = $this->tanggal->formatFullTime($params['jam_praktek_selesai']);
        $dateStr = $params['tanggalperiksa'];

        // determine date to use
        $sampleDateTs = time();
        if ($dateStr !== '') {
            $tmp = @strtotime($dateStr);
            if ($tmp !== false) $sampleDateTs = $tmp;
        }
        
        $dateOnly = date('Y-m-d', $sampleDateTs);

        // times & queue
        $startTimeRaw = $jam_mulai_praktek;
        $endTimeRaw   = $jam_selesai_praktek;
        $queueNumber  = $params['angkaantrean'];

        $estimatedIso = null;
        if ($startTimeRaw !== '') {
            $startTs = @strtotime($dateOnly . ' ' . $startTimeRaw);
            if ($startTs !== false) {
                $offsetSeconds = $queueNumber * 5 * 60;
                $estimatedTs = $startTs + $offsetSeconds;
                if ($endTimeRaw !== '') {
                    $endTs = @strtotime($dateOnly . ' ' . $endTimeRaw);
                    if ($endTs !== false && $estimatedTs > $endTs) {
                        $estimatedTs = max($startTs, $endTs - 30 * 60);
                    }
                }
                $estimatedIso = date('c', $estimatedTs);
            }
        }

        if ($estimatedIso === null) {
            $estimatedIso = date('c', strtotime($dateOnly . $startTimeRaw));
        }

        // convert esitmatedIso to millisecond
        $milisecond = strtotime($estimatedIso) * 1000;
        
        // add antrian
        $post_antrol = array(
            "kodebooking" => $params['kodebooking'],
            "jenispasien" => "NON JKN",
            "nomorkartu" => $params['nomorkartu'],
            "nik" => $params['nik'],
            "nohp" => $params['nohp'],
            "kodepoli" => $params['kodepoli'],
            "namapoli" => $params['namapoli'],
            "pasienbaru" => 0,
            "norm" => $params['norm'],
            "tanggalperiksa" => $this->tanggal->formatDateBPJS($params['tanggalperiksa']),
            "kodedokter" => $params['kodedokter'],
            "namadokter" => $params['namadokter'],
            "jampraktek" => $params['jampraktek'],
            "jeniskunjungan" => $params['jeniskunjungan'],
            "nomorreferensi" => $params['nomorreferensi'],
            "nomorantrean" => $params['nomorantrean'],
            "angkaantrean" => $params['angkaantrean'],
            "estimasidilayani" => $milisecond,
            "sisakuotajkn" => $params['sisakuotajkn'],
            "kuotajkn" => $params['kuotajkn'],
            "sisakuotanonjkn" => $params['sisakuotanonjkn'],
            "kuotanonjkn" => $params['kuotanonjkn'],
            "keterangan" => "Silahkan tensi dengan perawat"
        );
        // add antrian lainnya
        $this->AntrianOnline->addAntrianOnsite($post_antrol);

        // update kodebooking
        $this->db->where('no_registrasi', $params['kodebooking'])->update('tc_registrasi', array('kodebookingantrol' => $params['kodebooking']) );

        // update task antrian online
        $waktukirim = strtotime($params['tanggalperiksa'].' '.date('H:i:s')) * 1000;
        $exc_antrol = $this->AntrianOnline->postDataWs('antrean/updatewaktu', array('kodebooking' => $params['kodebooking'], 'taskid' => 3, 'waktu' => $waktukirim));

        return $post_antrol;
    }

    /**
     * Service doctors endpoint
     * GET /index.php/api/web_api/doctors
     */
    public function references($nik) {
        // Require basic auth
        $this->require_basic_auth();

        // paging / sort / filter params
        $limit   = (int)$this->input->get('limit');
        $skip    = (int)$this->input->get('skip');
        $sortBy  = $this->input->get('sortBy');
        $referenceType = trim((string)$this->input->get('referenceType'));
        if (!$referenceType) {
            # code...
            $this->respond(false, 'E_REFERENCE_TYPE_REQUIRED', 'Reference Type is Required.', array());
        }
        $referenceNumber = trim((string)$this->input->get('referenceNumber'));
        // get no kartu bpjs
        $patient = $this->db->get_where('mt_master_pasien', ['no_ktp' => trim((string)$nik)])->row();
        if (!$patient) {
            # code...
            $this->respond(false, 'E_PATIENT_NOT_FOUND', 'Patient Not Found.', array());
        }

        $noKartuBPJS = $patient->no_kartu_bpjs;

        // gert rujukan from API BPJS
        if($referenceType == 'firstLevel'){ // rujukan tingkat pertama puskesmas
            if($referenceNumber != ''){ // get rujukan by nomor rujukan
                $referenceData = $this->Ws_index->getData('Rujukan/'.$referenceNumber.'');
            }else{
                $referenceData = $this->Ws_index->getData('Rujukan/List/Peserta/'.$noKartuBPJS.'');
            }

        }else{ // rujukan dari rs
            if($referenceNumber != ''){ // get rujukan by nomor rujukan
                $referenceData = $this->Ws_index->getData('Rujukan/RS/'.$referenceNumber.'');
            }else{
                $referenceData = $this->Ws_index->getData('Rujukan/RS/List/Peserta/'.$noKartuBPJS.'');
            }

        }

        if(!$referenceData){
            $this->respond(false, 'E_REFERENCE_NOT_FOUND', 'Reference Not Found.', array());
        }else{
            if(isset($referenceData['response']) && $referenceData['response']->metaData->code == 202){
                $this->respond(false, 'E_REFERENCE_NOT_FOUND', $referenceData['response']->metaData->message, array());
            }
        }

        $count_items = count($referenceData['data']->rujukan); 
        $references = (array)$referenceData['data']->rujukan;

        $getData = [];
        foreach ($references as $key => $value) {
            # code...
            $date = $value->tglKunjungan;
            // if you want YYYY-MM-DD output
            $expired_date = date('Y-m-d', strtotime('+3 months', strtotime($value->tglKunjungan)));

            // Ambil tanggal hari ini
            $today = date('Y-m-d');

            // Bandingkan
            if ($today > $expired_date) {
                $is_available = false;
            } else {
                $is_available = true;
            }

            $items = [
                "nik" => $value->peserta->nik,
                "bpjsId" => $value->peserta->noKartu,
                "referenceNumber" => $value->noKunjungan,
                "referenceSource" => $value->provPerujuk->kode,
                "patientName" => $value->peserta->nama,
                "patientAge" => $value->peserta->umur->umurSekarang,
                "diagnosis" => $value->diagnosa->nama,
                "date" => $value->tglKunjungan,
                "specialist" => $value->poliRujukan->nama,
                "isAvailable" => $is_available,
                "createdAt" => date('c', strtotime(date('Y-m-d H:i:s'))),
                "updatedAt" => date('c', strtotime(date('Y-m-d H:i:s'))),
            ];

            $getData[] = $items;

        }
        // echo "<pre>"; print_r($getData);die;

        $data = array(
            "items" => $getData,
            'metadata' => array(
                'total'  => $count_items,
                'count'  => $count_items,
                'skip'   => $skip,
                'limit'  => $limit,
                'sortBy' => $sortBy ? $sortBy : 'tglKunjungan'
            )
        );

        $this->respond(true, '200', 'OK', $data);
    }


    // static endpoints
    public function payouts() {
        // Require basic auth
        $this->require_basic_auth();

        $data = array(
            array('id' => 1, 'name' => 'BPJS'),
            array('id' => 2, 'name' => 'Umum'),
            array('id' => 3, 'name' => 'Asuransi')
        );

        $this->respond(true, '200', 'OK', $data);
    }

    public function appointment_status() {
        // Require basic auth
        $this->require_basic_auth();

        $data = array(
            array('id' => 1, 'name' => 'Active'),
            array('id' => 2, 'name' => 'Cancelled')
        );

        $this->respond(true, '200', 'OK', $data);
    }

    public function policlinic_schedule_types() {
        // Require basic auth
        $this->require_basic_auth();

        $data = array(
            array('id' => 1, 'name' => 'BPJS'),
            array('id' => 2, 'name' => 'Umum')
        );

        $this->respond(true, '200', 'OK', $data);
    }

    public function policlinic_queue_status() {
        // Require basic auth
        $this->require_basic_auth();

        $data = array(
            array('id' => 1, 'name' => 'Pending'),
            array('id' => 2, 'name' => 'In Progress'),
            array('id' => 3, 'name' => 'Done'),
        );

        $this->respond(true, '200', 'OK', $data);
    }

    public function schedule_days() {
        // Require basic auth
        $this->require_basic_auth();

        $data = array(
            array('id' => 0, 'name' => 'Minggu'),
            array('id' => 1, 'name' => 'Senin'),
            array('id' => 2, 'name' => 'Selasa'),
            array('id' => 3, 'name' => 'Rabu'),
            array('id' => 4, 'name' => 'Kamis'),
            array('id' => 5, 'name' => 'Jumat'),
            array('id' => 6, 'name' => 'Sabtu')
        );

        $this->respond(true, '200', 'OK', $data);
    }


}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

