<?php
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
   header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
   header('Access-Control-Allow-Headers: Content-Type');
   exit();
}

$ipInfo = json_decode(file_get_contents('https://ipinfo.io/json'));
$ipAddress = $ipInfo->ip;

$proxyCheckResponse = file_get_contents("https://proxycheck.io/v2/$ipAddress?key=public-35w8n2-878lub-12468p&vpn=1&asn=1");
$proxyCheckData = json_decode($proxyCheckResponse, true);

if (isset($proxyCheckData[$ipAddress]['proxy']) === 'yes') {
   echo json_encode(['isUsingVPN' => true, 'info' => $proxyCheckData[$ipAddress]]);
} else {
   echo json_encode(['isUsingVPN' => false, 'info' => $proxyCheckData[$ipAddress]]);
}
