<?php

require_once __DIR__ . '/../db.php';

class JardinModel
{
    private PDO $db;
 
    public function __construct()
    {
        $this->db = getDB();
    }
 
    public function getAll(): array
    {
        return $this->db->query(
            'SELECT j.*, z.Nom AS NomZone
             FROM Jardins j JOIN Zone z ON j.Id_Zone=z.Id_Zone
             ORDER BY z.Nom, j.Nom'
        )->fetchAll();
    }
 
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT j.*, z.Nom AS NomZone
             FROM Jardins j JOIN Zone z ON j.Id_Zone=z.Id_Zone
             WHERE j.Id_Jardin=?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
 
    public function create(string $nom, string $adresse, int $idZone): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO Jardins (Nom, Adresse, Id_Zone) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$nom, $adresse, $idZone]);
        return (int)$this->db->lastInsertId();
    }
 
    public function update(int $id, string $nom, string $adresse, int $idZone): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE Jardins SET Nom=?, Adresse=?, Id_Zone=? WHERE Id_Jardin=?'
        );
        return $stmt->execute([$nom, $adresse, $idZone, $id]);
    }
 
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Jardins WHERE Id_Jardin=?');
        return $stmt->execute([$id]);
    }
}