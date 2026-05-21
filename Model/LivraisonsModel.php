<?php
require_once __DIR__ . '/../db.php';

class LivraisonsModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT lv.*, la.Nom AS NomLapin, j.Adresse AS AdresseJardin,
                    j.Nom AS NomJardin, j.Ville AS VilleJardin, z.Nom AS NomZone
             FROM Livraisons lv
             JOIN Lapins la ON lv.Id_Lapins = la.Id_Lapins
             JOIN Jardins j  ON lv.Id_Jardin = j.Id_Jardin
             JOIN Zone z     ON j.Id_Zone    = z.Id_Zone
             ORDER BY lv.datePrevisionnelle DESC'
        )->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT lv.*, la.Nom AS NomLapin, j.Adresse AS AdresseJardin,
                    j.Nom AS NomJardin, j.Ville AS VilleJardin, z.Nom AS NomZone
             FROM Livraisons lv
             JOIN Lapins la ON lv.Id_Lapins = la.Id_Lapins
             JOIN Jardins j  ON lv.Id_Jardin = j.Id_Jardin
             JOIN Zone z     ON j.Id_Zone    = z.Id_Zone
             WHERE lv.Id_Livraison=?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $datePrev, int $idLapin, int $idJardin): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO Livraisons (datePrevisionnelle, Statut, Id_Lapins, Id_Jardin)
             VALUES (?, "planifiée", ?, ?)'
        );
        $stmt->execute([$datePrev, $idLapin, $idJardin]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $datePrev, ?string $dateLiv, string $statut, int $idLapin, int $idJardin): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE Livraisons
             SET datePrevisionnelle=?, dateLivraison=?, Statut=?, Id_Lapins=?, Id_Jardin=?
             WHERE Id_Livraison=?'
        );
        return $stmt->execute([$datePrev, $dateLiv, $statut, $idLapin, $idJardin, $id]);
    }

    public function marquerLivree(int $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE Livraisons SET Statut='livrée', dateLivraison=CURDATE() WHERE Id_Livraison=?"
        );
        $ok = $stmt->execute([$id]);
        if ($ok) {
            $this->ecrireLog($id);
        }
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Livraisons WHERE Id_Livraison=?');
        return $stmt->execute([$id]);
    }

    // ---- Logs ----
    private function ecrireLog(int $idLivraison): void
    {
        $stmt = $this->db->prepare(
            'SELECT lv.Id_Livraison, la.Nom AS NomLapin, j.Adresse, j.Nom AS NomJardin
             FROM Livraisons lv
             JOIN Lapins la ON lv.Id_Lapins=la.Id_Lapins
             JOIN Jardins j ON lv.Id_Jardin=j.Id_Jardin
             WHERE lv.Id_Livraison=?'
        );
        $stmt->execute([$idLivraison]);
        $data = $stmt->fetch();
        if (!$data) return;

        $ins = $this->db->prepare(
            'INSERT INTO Logs (Adresse_Jardin, Nom_Jardin, Nom_Lapin, Id_Livraison)
             VALUES (?, ?, ?, ?)'
        );
        $ins->execute([$data['Adresse'], $data['NomJardin'], $data['NomLapin'], $idLivraison]);
    }

    public function getLogs(int $limit = 100): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM Logs ORDER BY Date_Heure DESC LIMIT ?'
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Stats dashboard
    public function getStats(): array
    {
        $row = $this->db->query(
            "SELECT
               COUNT(*) AS total,
               SUM(Statut='livrée') AS livrees,
               SUM(Statut='planifiée') AS planifiees,
               SUM(Statut='en cours') AS en_cours
             FROM Livraisons"
        )->fetch();
        return $row ?: [];
    }

    public function getLivreesParZone(): array
    {
        return $this->db->query(
            "SELECT z.Nom AS Zone, COUNT(*) AS nb
             FROM Livraisons lv
             JOIN Jardins j ON lv.Id_Jardin=j.Id_Jardin
             JOIN Zone z    ON j.Id_Zone=z.Id_Zone
             WHERE lv.Statut='livrée'
             GROUP BY z.Nom
             ORDER BY nb DESC"
        )->fetchAll();
    }
}