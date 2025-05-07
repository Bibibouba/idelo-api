<?php
// db.php – Connexion PDO pour Railway
declare(strict_types=1);

function get_db_connection(): PDO
{
    static $pdo = null;
    if ($pdo) {
        return $pdo; // Singleton
    }

    // Récupération sécurisée de l’URL de BDD
    $url = getenv('DATABASE_URL');
    if (!$url) {
        throw new RuntimeException('DATABASE_U non définie dans l’environnement');
    }

    $p = parse_url($url);
    if (!$p || !isset($p['host'], $p['user'], $p['path'])) {
        throw new RuntimeException('DATABASE_URL malformée');
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $p['host'],
        $p['port'] ?? 3306,
        ltrim($p['path'], '/')
    );

    $username = $p['user'];
    $password = $p['pass'] ?? '';

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    return $pdo = new PDO($dsn, $username, $password, $options);
}
