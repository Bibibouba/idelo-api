<?php
// db.php – Connexion PDO avec DATABASE_URL Railway
declare(strict_types=1);

/**
 * Fonction de connexion unique à la base de données Railway via PDO
 *
 * @return PDO Instance de connexion
 * @throws RuntimeException si la configuration est invalide
 */
function get_db_connection(): PDO
{
    static $pdo = null;
    if ($pdo) {
        return $pdo; // Singleton
    }

    // Récupère l'URL de connexion depuis l'environnement Railway
    $url = getenv('DATABASE_URL');

    if (!$url) {
        // ⚠️ Pour debug : active la ligne ci-dessous si besoin de tester en HTTP
        // echo json_encode(['error' => 'DATABASE_URL non définie dans l’environnement']);
        throw new RuntimeException('DATABASE_URL non définie dans l’environnement');
    }

    $p = parse_url($url);
    if (!$p || !isset($p['host'], $p['user'], $p['path'])) {
        throw new RuntimeException('DATABASE_URL malformée');
    }

    // Construction du DSN MySQL
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $p['host'],
        $p['port'] ?? 3306,
        ltrim($p['path'], '/')
    );

    $username = $p['user'];
    $password = $p['pass'] ?? '';

    // Options PDO sécurisées
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    return $pdo = new PDO($dsn, $username, $password, $options);
}
