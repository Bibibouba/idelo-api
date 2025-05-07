<?php
declare(strict_types=1);

// 🔒 Important : ne rien mettre avant <?php (même pas un espace ou saut de ligne)

// Affichage des erreurs pour le debug
ini_set('display_errors', '1');
error_reporting(E_ALL);

// En‑têtes CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=UTF-8');

// Pré-vol OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Connexion à la base (si nécessaire)
require_once __DIR__ . '/db.php';

try {
    $pdo = get_db_connection();
    $pdo->query('SELECT 1'); // test requête simple

    echo json_encode([
        'success' => true,
        'message' => 'Connexion OK'
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
