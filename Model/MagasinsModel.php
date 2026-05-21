<?php
require_once __DIR__ . '/../db.php';

class MagasinsModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT * FROM Magasins ORDER BY Nom'
        )->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM Magasins WHERE Id_Magasins=?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $nom, string $adresse): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO Magasins (Nom, Adresse) VALUES (?, ?)'
        );
        $stmt->execute([$nom, $adresse]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $nom, string $adresse): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE Magasins SET Nom=?, Adresse=? WHERE Id_Magasins=?'
        );
        return $stmt->execute([$nom, $adresse, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Magasins WHERE Id_Magasins=?');
        return $stmt->execute([$id]);
    }
}