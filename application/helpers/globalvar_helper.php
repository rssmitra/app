<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


if (!function_exists('get_status_type')) {
	function get_status_type() {
		return array(1 => "Aktif", 0 => "Tidak Aktif");
	}
}

if (!function_exists('get_mailcreating_type')) {

	function get_mailcreating_type() {
		return array(
			"TIKET" => 3,
			"PUSH_NOTIF" => 4
		);
	}

}

if (!function_exists('get_daynational_type')) {

	function get_daynational_type() {
		return $DAY_TYPE = array("LIBUR_NASIONAL" => 0, "MUSIM_LIBURAN" => 1, "TIDAK_DAPAT_BOOKING" => 2, "PEKAN" => 4);
	}

}

if (!function_exists('get_mail_conf')) {

	function get_mail_conf() {
		return array(
            "MAIL_TYPE" => "html",
            "PROTOCOL" => "mail",
            "SMTP_HOST" => "mail.smtp2go.com",
            "SMTP_PORT" => 2525,
            "SMTP_SECURE" => "tls",
            "SMTP_USER" => 'aries.liburania@gmail.com',
            "MAIL_PATH" => "/usr/sbin/sendmail",
            "SMTP_PASS" => "liburania#7654321",
            "EMAIL_FROM" => 'resellerapss@liburania.com',
            "EMAIL_FROM_ALIAS" => "Liburania.com [Agent]" 
        );
	}

}

if (!function_exists('mail_finance_group')) {

	function mail_finance_group() {
		return array(
			'deny.arwinto@gmail.com',
		);
	}
}

//if (!function_exists('get_credential_sgo')) {
	
//	function get_credential_sgo() {
//		return array(
//			"API_KEY" => 'a3e87d0382b89b6c06cc060cc374e24a',
//			"PASSWORD" => '123098',
//			"COMM_CODE" => 'SGWLIBURANIA',
//			"KEY_SIGNATURE" => '6NAMwjgRTMW686Bz',
//			"EMBEDKIT_URL" => 'https://sandbox-kit.espay.id/public/signature/js',
//			"INVOICE_URL" => 'https://sandbox-api.espay.id/rest/merchantpg/sendinvoice'
//		);
//	}

//}

if (!function_exists('get_credential_sgo')) {
	
	function get_credential_sgo() {
		return array(
			"API_KEY" => '21a0c53c141168d845aece1d8cf9c296',
			"PASSWORD" => 'DPPSZQZW',
			"COMM_CODE" => 'SGWLIBURANIAPRAKTISINDO',
			"KEY_SIGNATURE" => 'u1dw29632rjzfqfi',
			"EMBEDKIT_URL" => 'https://kit.espay.id/public/signature/js',
			"INVOICE_URL" => 'https://api.espay.id/rest/merchantpg/sendinvoice'
		);
	}
}

if (!function_exists('get_credential_google')) {
	
	function get_credential_google() {
		return array(
			"AUTHORIZATION_KEY" => 'AAAAjkvRW3E:APA91bERGMAxn201JxY384yx1-wxtWOGOYGRb_ROvHS2dkQgAM3B4rVZ9vPPIIfLGvHoMxUL5eT3qPi9Mv56oeTvJtIIl2ZcCQBlm1J2-IG22hQB5S-bcxDjourV_R8KpPUJjrBmwleD',
			"PUSH_URL" => 'https://fcm.googleapis.com/fcm/send'
		);
	}

}

if (!function_exists('get_glob_var')) {

	function get_glob_var() {
		return $GLOB_VAR = array(
			"SUBJECT_EMAILREGISTRATION" => "Liburania Agent [Registration]",
			"SUBJECT_EMAILACCOUNTACTIVATION" => "Liburania Agent [Account Activation]"
		);
	}

}





