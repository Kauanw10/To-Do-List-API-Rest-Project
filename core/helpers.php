<?php 
function getJson() {
    $json = file_get_contents('php://input');
    return json_decode($json, true) ?? [];
}

function response($data, $codigoStatus = 200) {
    header('Content-Type: application/json');
    http_response_code($codigoStatus);
    echo json_encode($data);
    exit;
}
?>