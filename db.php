<?php
define('DB_HOST', '192.168.56.10');
define('DB_NAME', 'Lapin');
define('DB_USER', 'bobthenoob');      
define('DB_PASS', 'Omgimdeadlol.8112');          
define('DB_CHARSET', 'utf8mb4');
 
/**
 * Retourne une connexion PDO unique (singleton)
 */
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die(json_encode(['error' => 'Connexion BDD impossible : ' . $e->getMessage()]));
        }
    }
    return $pdo;
}
?>