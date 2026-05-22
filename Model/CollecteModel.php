<?php
require_once __DIR__ . '/../db.php';

class CollecteModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT c.*, m.Nom AS NomMagasin
             FROM Collecte c
             JOIN Magasins m ON Id_Magasins=m.Id_Magasins
             ORDER BY c.datePrevisionnelle DESC'
        )->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT c.*, m.Nom AS NomMagasin, m.Adresse AS AdresseMagasin
             FROM Collecte c
             JOIN Magasins m ON c.Id_Magasins=m.Id_Magasins
             WHERE c.Id_Collecte=?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $datePrev, int $idMagasin): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO Collecte (datePrevisionnelle, Statut, Id_Magasins) VALUES (?, "planifiée", ?)'
        );
        $stmt->execute([$datePrev, $idMagasin]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $datePrev, ?string $dateCollecte, string $statut, int $idMagasin): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE Collecte SET datePrevisionnelle=?, dateCollecte=?, Statut=?, Id_Magasins=?
             WHERE Id_Collecte=?'
        );
        return $stmt->execute([$datePrev, $dateCollecte, $statut, $idMagasin, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Collecte WHERE Id_Collecte=?');
        return $stmt->execute([$id]);
    }

    public function affecterLapin(int $idCollecte, int $idLapin): void
    {
        $stmt = $this->db->prepare(
            'INSERT IGNORE INTO effectue (Id_Lapins, Id_Collecte) VALUES (?, ?)'
        );
        $stmt->execute([$idLapin, $idCollecte]);
    }

    public function getLapins(int $idCollecte): array
    {
        $stmt = $this->db->prepare(
            'SELECT l.* FROM Lapins l
             JOIN effectue e ON l.Id_Lapins=e.Id_Lapins
             WHERE e.Id_Collecte=?'
        );
        $stmt->execute([$idCollecte]);
        return $stmt->fetchAll();
    }

    public function retirerLapin(int $idCollecte, int $idLapin): void
    {
        $stmt = $this->db->prepare(
            'DELETE FROM effectue WHERE Id_Lapins=? AND Id_Collecte=?'
        );
        $stmt->execute([$idLapin, $idCollecte]);
    }
}