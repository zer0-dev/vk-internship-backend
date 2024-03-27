<?php
use Task\Api\Api;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require 'vendor/autoload.php';

Api::init();
$data = ($_SERVER['REQUEST_METHOD'] == 'GET') ? $_GET : $_POST;
$response = Api::handleRequest(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $data);

if($response) echo json_encode($response);