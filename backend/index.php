<?php
include 'database.php';

$db = new Database();
$connection = $db->getConnection();
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Povolíme CORS pro všechny domény, pro produkční nasazení je lepší specifikovat povolené domény
// echo json_encode($articles);
