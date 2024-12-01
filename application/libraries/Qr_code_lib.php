<?php 

require_once('phpqrcode/qrlib.php');

final class Qr_code_lib {

    var $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
    // var $verify_url = 'https://shs.rssetiamitra.co.id';
    var $verify_url = 'http://10.10.11.5:88/sirs-dev/app';

	function generate($text) { 

        //set it to writable location, a place for temp generated PNG files
        // $PNG_TEMP_DIR = 'uploaded/temp_qrcode/';
        $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'phpqrcode'.DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
        $PNG_WEB_DIR = 'phpqrcode/temp/';

        if (!file_exists($PNG_TEMP_DIR))
            mkdir($PNG_TEMP_DIR);

        $filename = $PNG_TEMP_DIR.'QR_CODE.png';

        //processing form input
        //remember to sanitize user input in real-life solution !!!
        $errorCorrectionLevel = 'L';
        if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
            $errorCorrectionLevel = $_REQUEST['level'];    

        $matrixPointSize = 4;
        if (isset($_REQUEST['size']))
            $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);

        $filename = $PNG_TEMP_DIR.'QR_'.md5($text.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        QRcode::png($text, $filename, $errorCorrectionLevel, $matrixPointSize, 2);      
        // echo basename($filename);
        return '<img src="'.$PNG_TEMP_DIR.basename($filename).'" width="100px"/>';

    }

    function qr_url($params){
        
        $no_registrasi = isset($params['no_registrasi'])?$params['no_registrasi']:'';
        $kode = isset($params['kode'])?$params['kode']:'';
        $flag = isset($params['flag'])?$params['flag']:'';

        $format = $flag.'|'.$kode.'|'.$no_registrasi;
        $encrypt = $this->encryptIt($format);
        $qr_url = $this->verify_url.'/verifyDocument?flag='.$flag.'&code='.$kode.'&reg='.$no_registrasi.'&token='.$encrypt.'';
        return $qr_url;
        
    }

    function check_valid_qr($params){
        
        $no_registrasi = isset($params['reg'])?$params['reg']:'';
        $kode = isset($params['code'])?$params['code']:'';
        $flag = isset($params['flag'])?$params['flag']:'';

        $format = $flag.'|'.$kode.'|'.$no_registrasi;
        $encrypt = $this->encryptIt($format);

        // echo $encrypt;
        // echo '<br>';
        // echo $params['token'];
        // die;
        if($encrypt == $params['token']){
            return true;
        }else{
            return false;
        }
        
    }

    function encryptIt( $q ) {
        $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $this->cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $this->cryptKey ) ) ) );
        return( $qEncoded );
    }
    
    function decryptIt( $q ) {
        $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
        return( $qDecoded );
    }


}


?>