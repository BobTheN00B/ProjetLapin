<?php
require_once __DIR__ . '/../db.php';

class LapinModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT l.*, u.Nom AS NomUtilisateur
             FROM Lapins l
             JOIN Utilisateurs u ON l.Id_Utilisateurs = u.Id_Utilisateurs
             ORDER BY l.Nom'
        )->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT l.*, u.Nom AS NomUtilisateur
             FROM Lapins l
             JOIN Utilisateurs u ON l.Id_Utilisateurs = u.Id_Utilisateurs
             WHERE l.Id_Lapins = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $nom, string $couleur, string $statut, int $idUtilisateur): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO Lapins (nom, couleur, statut, Id_Utilisateurs) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$nom, $couleur, $statut, $idUtilisateur]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $nom): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE Lapins SET Nom=? WHERE Id_Lapins=?'
        );
        return $stmt->execute([$nom, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Lapins WHERE Id_Lapins=?');
        return $stmt->execute([$id]);
    }

    public function getDisponibles(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM Lapins WHERE Statut='disponible' ORDER BY Nom");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Affectations collecte
    public function affecterCollecte(int $idLapin, int $idCollecte): void
    {
        $stmt = $this->db->prepare(
            'INSERT IGNORE INTO effectue (Id_Lapins, Id_Collecte) VALUES (?, ?)'
        );
        $stmt->execute([$idLapin, $idCollecte]);
    }

    public function getLapinsParCollecte(int $idCollecte): array
    {
        $stmt = $this->db->prepare(
            'SELECT l.* FROM Lapins l
             JOIN effectue e ON l.Id_Lapins = e.Id_Lapins
             WHERE e.Id_Collecte = ?'
        );
        $stmt->execute([$idCollecte]);
        return $stmt->fetchAll();
    }

    // Statistiques
    public function getNbLivraisons(int $id): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM Livraisons WHERE Id_Lapins=? AND Statut='livré'"
        );
        $stmt->execute([$id]);
        return (int)$stmt->fetchColumn();
    }
}