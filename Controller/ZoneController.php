<?php
require_once __DIR__ . '/../Model/ZoneJardinModel.php';
require_once __DIR__ . '/../View/ZoneJardinView.php';
require_once __DIR__ . '/../Controller/UserController.php';
 
class ZoneController
{
    private ZoneModel $model;
    private UserController $auth;
 
    public function __construct()
    {
        $this->model = new ZoneModel();
        $this->auth  = new UserController();
    }
 
    public function handleList(): void
    {
        $this->auth->requireAuth();
        $zones = $this->model->getAll();
        foreach ($zones as &$z) {
            $z['jardins'] = $this->model->getJardins($z['Id_Zone']);
        }
        (new ZoneJardinView())->showZoneList($zones);
    }
 
    public function handleCreate(): void
    {
        $this->auth->requireAdmin();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom   = trim($_POST['nom'] ?? '');
            $ville = trim($_POST['adresse'] ?? '');
            if ($nom) {
                $this->model->create($nom, $adresse);
                header('Location: index.php?page=zones&success=1');
                exit;
            }
            $error = 'Le nom est requis.';
        }
        (new ZoneJardinView())->showZoneCreate($error);
    }
 
    public function handleEdit(): void
    {
        $this->auth->requireAdmin();
        $id   = (int)($_GET['id'] ?? 0);
        $zone = $this->model->getById($id);
        if (!$zone) { header('Location: index.php?page=zones'); exit; }
 
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom   = trim($_POST['nom'] ?? '');
            $ville = trim($_POST['adresse'] ?? '');
            if ($nom) {
                $this->model->update($id, $nom, $adresse);
                header('Location: index.php?page=zones&success=1');
                exit;
            }
            $error = 'Le nom est requis.';
        }
        (new ZoneJardinView())->showZoneEdit($zone, $error);
    }
 
    public function handleDelete(): void
    {
        $this->auth->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->model->delete($id);
        header('Location: index.php?page=zones');
        exit;
    }
}