<?php
class JardinController
{
    private JardinModel $model;
    private ZoneModel $zoneModel;
    private UserController $auth;
 
    public function __construct()
    {
        $this->model     = new JardinModel();
        $this->zoneModel = new ZoneModel();
        $this->auth      = new UserController();
    }
 
    public function handleList(): void
    {
        $this->auth->requireAuth();
        $jardins = $this->model->getAll();
        (new ZoneJardinView())->showJardinList($jardins);
    }
 
    public function handleCreate(): void
    {
        $this->auth->requireAdmin();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom     = trim($_POST['nom'] ?? '');
            $adresse = trim($_POST['adresse'] ?? '');
            $ville   = trim($_POST['ville'] ?? '');
            $idZone  = (int)($_POST['id_zone'] ?? 0);
            if ($adresse && $idZone) {
                $this->model->create($nom, $adresse, $ville, $idZone);
                header('Location: index.php?page=jardins&success=1');
                exit;
            }
            $error = 'Adresse et zone sont requis.';
        }
        $zones = $this->zoneModel->getAll();
        (new ZoneJardinView())->showJardinCreate($error, $zones);
    }
 
    public function handleEdit(): void
    {
        $this->auth->requireAdmin();
        $id     = (int)($_GET['id'] ?? 0);
        $jardin = $this->model->getById($id);
        if (!$jardin) { header('Location: index.php?page=jardins'); exit; }
 
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom     = trim($_POST['nom'] ?? '');
            $adresse = trim($_POST['adresse'] ?? '');
            $ville   = trim($_POST['ville'] ?? '');
            $idZone  = (int)($_POST['id_zone'] ?? 0);
            if ($adresse && $idZone) {
                $this->model->update($id, $nom, $adresse, $ville, $idZone);
                header('Location: index.php?page=jardins&success=1');
                exit;
            }
            $error = 'Adresse et zone sont requis.';
        }
        $zones = $this->zoneModel->getAll();
        (new ZoneJardinView())->showJardinEdit($jardin, $error, $zones);
    }
 
    public function handleDelete(): void
    {
        $this->auth->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->model->delete($id);
        header('Location: index.php?page=jardins');
        exit;
    }
}