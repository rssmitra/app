<?php
$url = 'https://rest.nexmo.com/sms/json?' . http_build_query(
    [
      'api_key' =>  '497fe30a',
      'api_secret' => '6b596a4e3de436a9',
      'to' => '6285252494110',
      'from' => 'BPKAD SINTANG KAB',
      'text' => 'Selamat Siang, anda mendapatkan 1 (satu) pemberitahuan disposisi perihal undangan rapat di Kantor DPR'
    ]
);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

echo $response;