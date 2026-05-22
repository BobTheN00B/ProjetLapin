<?php
require_once __DIR__ . '/../db.php';

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT Id_Utilisateurs, Nom, Email, Role
             FROM Utilisateurs ORDER BY Nom'
        )->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT Id_Utilisateurs, Nom, Email, Role, CreatedAt
             FROM Utilisateurs WHERE Id_Utilisateurs=?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM Utilisateurs WHERE Email=?'
        );
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public function create(string $nom, string $email, string $password, string $role): int
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            'INSERT INTO Utilisateurs (Nom, Email, MDP, Role) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$nom, $email, $hash, $role]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Utilisateurs WHERE Id_Utilisateurs=?');
        return $stmt->execute([$id]);
    }
}