<?php
declare(strict_types=1);

// 🌍 En‑têtes CORS dynamiques : autorise uniquement les origines connues
$allowed_origins = [
    'https://idelo.creacodeal.store',
    'https://bee-book-voyage-manager-production.up.railway.app',
    'http://localhost:3000',
    'http://localhost:8080',
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Credentials: true');
} else {
    header('Access-Control-Allow-Origin: https://idelo.creacodeal.store'); // fallback sûr
}

header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

// 🔁 Pré‑vol OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ❌ Refuse toute autre méthode que GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'message' => 'Méthode non autorisée. Utilisez GET.'
    ]);
    exit;
}

// ✅ Réponse "API OK"
echo json_encode([
    'ok' => true,
    'message' => 'Endpoint opérationnel'
]);
