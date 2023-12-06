<?php
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
   header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
   header('Access-Control-Allow-Headers: Content-Type');
   exit();
}

if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
   $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
   $clientIP = $_SERVER['REMOTE_ADDR'];
}

$ipInfo = json_decode(file_get_contents("https://ipinfo.io/$clientIP?token=66f1654f0055ae&nocache=" . time()));
$ipAddress = $ipInfo->ip;

$proxyCheckResponse = file_get_contents("https://proxycheck.io/v2/$ipAddress?key=public-35w8n2-878lub-12468p&asn=1&timestamp=" . time());
$proxyCheckData = json_decode($proxyCheckResponse, true);

if (isset($proxyCheckData[$ipAddress]['proxy']) && $proxyCheckData[$ipAddress]['proxy'] == 'yes') {
   echo json_encode(['isUsingVPN' => true, 'geo' => $ipInfo, 'info' => $proxyCheckData[$ipAddress]]);
} else {
   echo json_encode(['isUsingVPN' => false, 'geo' => $ipInfo, 'info' => $proxyCheckData[$ipAddress]]);
}
