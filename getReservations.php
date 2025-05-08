<?php
declare(strict_types=1);

/**
 * getReservations.php
 * Renvoie la liste des devis (ou réservations) triés par date_sortie décroissante.
 */

// 🌍 CORS dynamique : liste blanche d’origines autorisées
$allowed_origins = [
    'https://idelo.creacodeal.store',
    'https://bee-book-voyage-manager-production.up.railway.app',
    'http://localhost:3000',
    'http://localhost:8080',
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins, true)) {
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
}

header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// 🔁 OPTIONS pré-vol
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ❌ Refuse toute autre méthode que GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée. Utilisez GET.'
    ]);
    exit;
}

// 🔌 Connexion BDD
require_once __DIR__ . '/db.php';
$pdo = get_db_connection();

// 📥 Requête SQL + retour
try {
    $sql   = 'SELECT * FROM devis ORDER BY date_sortie DESC';
    $stmt  = $pdo->query($sql);
    $devis = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'data'    => $devis
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la récupération des devis',
        'error'   => $e->getMessage()
    ]);
}
