<?php
require_once __DIR__ . '/../db.php';
 
class ZoneModel
{
    private PDO $db;
 
    public function __construct()
    {
        $this->db = getDB();
    }
 
    public function getAll(): array
    {
        return $this->db->query('SELECT * FROM Zone ORDER BY Nom')->fetchAll();
    }
 
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM Zone WHERE Id_Zone=?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
 
    public function create(string $nom, string $ville): int
    {
        $stmt = $this->db->prepare('INSERT INTO Zone (Nom, Ville) VALUES (?, ?)');
        $stmt->execute([$nom, $ville]);
        return (int)$this->db->lastInsertId();
    }
 
    public function update(int $id, string $nom, string $ville): bool
    {
        $stmt = $this->db->prepare('UPDATE Zone SET Nom=?, Ville=? WHERE Id_Zone=?');
        return $stmt->execute([$nom, $ville, $id]);
    }
 
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Zone WHERE Id_Zone=?');
        return $stmt->execute([$id]);
    }
 
    public function getJardins(int $idZone): array
    {
        $stmt = $this->db->prepare('SELECT * FROM Jardins WHERE Id_Zone=? ORDER BY Nom');
        $stmt->execute([$idZone]);
        return $stmt->fetchAll();
    }
}