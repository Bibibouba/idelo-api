<?php
/**
 * add_devis.php
 *
 * Crée un devis dans la table `devis`.
 * Champs obligatoires : numero_devis, client_id, date_sortie, horaire_embquement,
 *                       duree, nombre_passagers, prix_ttc
 * Réponse JSON.
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
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=UTF-8');

/* Pré‑vol OPTIONS */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

/* -------------------------------------------------------------------------
   Vérification méthode
   ----------------------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée. Utilisez POST.'
    ]);
    exit;
}

/* -------------------------------------------------------------------------
   Lecture & validation JSON
   ----------------------------------------------------------------------- */
$raw   = file_get_contents('php://input');
$input = json_decode($raw, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        'success'    => false,
        'message'    => 'JSON invalide ou vide',
        'json_error' => json_last_error_msg()
    ]);
    exit;
}

/* Champs requis */
$required = [
    'numero_devis', 'client_id', 'date_sortie', 'horaire_embarquement',
    'duree', 'nombre_passagers', 'prix_ttc'
];
foreach ($required as $field) {
    if (empty($input[$field])) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => "Champ obligatoire manquant : $field"
        ]);
        exit;
    }
}

/* -------------------------------------------------------------------------
   Extraction & typage
   ----------------------------------------------------------------------- */
$numero_devis         = trim($input['numero_devis']);
$client_id            = trim($input['client_id']);
$date_sortie          = trim($input['date_sortie']);
$horaire_embarquement = trim($input['horaire_embarquement']);
$duree                = trim($input['duree']);
$nombre_passagers     = (int) $input['nombre_passagers'];
$prix_ttc             = (float) $input['prix_ttc'];

$apporteur_id   = isset($input['apporteur_id'])   ? trim($input['apporteur_id'])   : null;
$numero_facture = isset($input['numero_facture']) ? trim($input['numero_facture']) : null;
$statut         = isset($input['statut'])         ? trim($input['statut'])         : 'en_cours';
$sortie_comptee = isset($input['sortie_comptee']) ? (int) $input['sortie_comptee'] : 1;
$notes          = isset($input['notes'])          ? trim($input['notes'])          : null;

/* -------------------------------------------------------------------------
   Connexion BD
   ----------------------------------------------------------------------- */
require_once __DIR__ . '/db.php';
$pdo = get_db_connection();

/* -------------------------------------------------------------------------
   Génération ID
   ----------------------------------------------------------------------- */
$id = bin2hex(random_bytes(12));   // 24 caractères hex.

/* -------------------------------------------------------------------------
   Insertion
   ----------------------------------------------------------------------- */
try {
    $sql = <<<'SQL'
        INSERT INTO devis (
            id, numero_devis, client_id, date_sortie, horaire_embarquement, duree,
            nombre_passagers, prix_ttc, apporteur_id, numero_facture,
            statut, sortie_comptee, notes
        ) VALUES (
            :id, :numero_devis, :client_id, :date_sortie, :horaire_embarquement, :duree,
            :nombre_passagers, :prix_ttc, :apporteur_id, :numero_facture,
            :statut, :sortie_comptee, :notes
        )
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id'                 => $id,
        ':numero_devis'       => $numero_devis,
        ':client_id'          => $client_id,
        ':date_sortie'        => $date_sortie,
        ':horaire_embarquement'=> $horaire_embarquement,
        ':duree'              => $duree,
        ':nombre_passagers'   => $nombre_passagers,
        ':prix_ttc'           => $prix_ttc,
        ':apporteur_id'       => $apporteur_id ?: null,
        ':numero_facture'     => $numero_facture ?: null,
        ':statut'             => $statut,
        ':sortie_comptee'     => $sortie_comptee,
        ':notes'              => $notes ?: null
    ]);

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Devis ajouté avec succès',
        'data'    => ['id' => $id]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur d\'insertion',
        'error'   => $e->getMessage()
    ]);
}
