<?php
// MadaaQ API Bridge (PHP to Node.js)
$node_url = "http://localhost:3000" . $_SERVER['REQUEST_URI'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $node_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $_SERVER['REQUEST_METHOD']);
curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents('php://input'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

http_response_code($http_code);
header('Content-Type: application/json');
echo $response;

curl_close($ch);
?>
