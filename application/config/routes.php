<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*custom routes*/
$route['verifyDocument'] = "Templates/Attachment/verifyDocument";

// routing for public
$route['public'] = "publik/Pelayanan_publik";
$route['Registrasi_RJ'] = "publik/Pelayanan_publik/registrasi_rj";

$route['lapi'] = "laporan/Lapi_report";
$route['lapi/form'] = "laporan/Lapi_report/form";
$route['lapi/auth'] = "laporan/Lapi_report/auth";
$route['lapi/logout'] = "laporan/Lapi_report/logout";
$route['lapi/showData'] = "laporan/Lapi_report/show_data";

// web service rs-odoo
$route['ws/getToken'] = "api/service/getToken";
$route['ws/getPatient/(:any)/(:any)'] = "api/service/getPatient/$1/$2";
$route['ws/getMedicalRecord'] = "api/service/getMedicalRecord";
$route['ws/getMedicalExam'] = "api/service/getMedicalExam";
$route['ws/getMedicalExamResult'] = "api/service/getMedicalExamResult";
$route['c3850if'] = "Display_antrian/farmasi_publik";


