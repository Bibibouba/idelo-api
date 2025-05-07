<?php
/**
 * add_client.php
 *
 * Endpoint de santé : renvoie { "ok": true } si l’API répond.
 */

/* -------------------------------------------------------------------------
   CORS dynamique : liste blanche d’origines autorisées
   ----------------------------------------------------------------------- */
$allowed_origins = [
    'https://idelo.creacodeal.store',
    'https://bee-book-voyage-manager-production.up.railway.app',
    'http://localhost:3000',
    'http://localhost:8080',
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins, true)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Credentials: true');
}
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=UTF-8');

/* Pré‑vol OPTIONS --------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

/* Vérification méthode ---------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'ok'      => false,
        'message' => 'Méthode non autorisée. Utilisez GET.'
    ]);
    exit;
}

/* -------------------------------------------------------------------------
   Réponse OK
   ----------------------------------------------------------------------- */
echo json_encode(['ok' => true]);
