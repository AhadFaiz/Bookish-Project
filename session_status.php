<?php
session_start();

$response = [
    'is_admin' => isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false
];

header('Content-Type: application/json');
echo json_encode($response);
?>
