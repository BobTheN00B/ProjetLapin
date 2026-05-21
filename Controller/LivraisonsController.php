<?php
require_once __DIR__ . '/../Model/LivraisonsModel.php';
require_once __DIR__ . '/../Model/LapinModel.php';
require_once __DIR__ . '/../Model/ZoneJardinModel.php';
require_once __DIR__ . '/../View/LivraisonsView.php';
require_once __DIR__ . '/../Controller/UserController.php';

class LivraisonsController
{
    private LivraisonsModel $model;
    private UserController $auth;

    public function __construct()
    {
        $this->model = new LivraisonsModel();
        $this->auth  = new UserController();
    }

    public function handleList(): void
    {
        $this->auth->requireAuth();
        $livraisons = $this->model->getAll();
        $stats      = $this->model->getStats();
        (new LivraisonsView())->showList($livraisons, $stats);
    }

    public function handleCreate(): void
    {
        $this->auth->requireAdmin();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datePrev = $_POST['date_prev'] ?? '';
            $idLapin  = (int)($_POST['id_lapin'] ?? 0);
            $idJardin = (int)($_POST['id_jardin'] ?? 0);
            if ($datePrev && $idLapin && $idJardin) {
                $this->model->create($datePrev, $idLapin, $idJardin);
                header('Location: index.php?page=livraisons&success=1');
                exit;
            }
            $error = 'Tous les champs sont requis.';
        }
        $lapins  = (new LapinModel())->getAll();
        $jardins = (new JardinModel())->getAll();
        (new LivraisonsView())->showCreate($error, $lapins, $jardins);
    }

    public function handleEdit(): void
    {
        $this->auth->requireAdmin();
        $id  = (int)($_GET['id'] ?? 0);
        $liv = $this->model->getById($id);
        if (!$liv) { header('Location: index.php?page=livraisons'); exit; }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datePrev = $_POST['date_prev'] ?? '';
            $dateLiv  = $_POST['date_livraison'] ?: null;
            $statut   = $_POST['statut'] ?? 'planifiée';
            $idLapin  = (int)($_POST['id_lapin'] ?? 0);
            $idJardin = (int)($_POST['id_jardin'] ?? 0);

            if ($datePrev && $idLapin && $idJardin) {
                // Si passage à "livrée", on utilise la méthode qui écrit le log
                if ($statut === 'livrée' && $liv['Statut'] !== 'livrée') {
                    $this->model->update($id, $datePrev, $dateLiv, $statut, $idLapin, $idJardin);
                    $this->model->marquerLivree($id);
                } else {
                    $this->model->update($id, $datePrev, $dateLiv, $statut, $idLapin, $idJardin);
                }
                header('Location: index.php?page=livraisons&success=1');
                exit;
            }
            $error = 'Tous les champs sont requis.';
        }
        $lapins  = (new LapinModel())->getAll();
        $jardins = (new JardinModel())->getAll();
        (new LivraisonsView())->showEdit($liv, $error, $lapins, $jardins);
    }

    public function handleMarkDone(): void
    {
        $this->auth->requireAuth();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->model->marquerLivree($id);
        header('Location: index.php?page=livraisons&success=1');
        exit;
    }

    public function handleDelete(): void
    {
        $this->auth->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->model->delete($id);
        header('Location: index.php?page=livraisons');
        exit;
    }

    public function handleLogs(): void
    {
        $this->auth->requireAuth();
        $logs = $this->model->getLogs(200);
        $parZone = $this->model->getLivreesParZone();
        (new LivraisonsView())->showLogs($logs, $parZone);
    }
}