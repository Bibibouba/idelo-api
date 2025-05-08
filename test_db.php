<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

header('Content-Type: text/plain');

try {
    $pdo = get_db_connection();
    echo "✅ Connexion réussie à Railway !";
} catch (Throwable $e) {
    echo "❌ Connexion échouée : " . $e->getMessage();
}
