<?php
declare(strict_types=1);

// 🔐 Debug uniquement en développement
ini_set('display_errors', '1');
error_reporting(E_ALL);

// 🌍 CORS – autorise uniquement ton frontend de production
header("Access-Control-Allow-Origin: https://idelo.creacodeal.store");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// 🔁 Pré‑vol OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// 🔌 Connexion à la base de données
require_once __DIR__ . '/db.php';

try {
    $pdo = get_db_connection();
    $pdo->query('SELECT 1'); // Test simple

    echo json_encode([
        'success' => true,
        'message' => 'Connexion à la base OK'
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine()
    ]);
}
