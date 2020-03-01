<?php

header("Access-Control-Allow-Origin: http://localhost/restapitutorial/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

$data = json_decode(file_get_contents("php://input"));

$token = isset($data->token) ? $data->token : "";

if ($token) {
    try {
        $decoded = JWT::decode($token, $key, array('HS256'));
        http_response_code(200);
        echo json_encode(array(
            "message" => "Access granted.",
            "data" => $decoded->data
        ));
    } catch (Exception $exception) {
        http_response_code(401);
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $exception->getMessage()
        ));
    }
} else {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied."));
}

?>
