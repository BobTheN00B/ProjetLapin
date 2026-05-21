<?php
require_once __DIR__ . '/../Model/MagasinsModel.php';
require_once __DIR__ . '/../View/MagasinsView.php';
require_once __DIR__ . '/../Controller/UserController.php';

class MagasinsController
{
    private MagasinsModel $model;
    private UserController $auth;

    public function __construct()
    {
        $this->model = new MagasinsModel();
        $this->auth  = new UserController();
    }

    public function handleList(): void
    {
        $this->auth->requireAuth();
        $magasins = $this->model->getAll();
        (new MagasinsView())->showList($magasins);
    }

    public function handleCreate(): void
    {
        $this->auth->requireAdmin();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom     = trim($_POST['nom'] ?? '');
            $adresse = trim($_POST['adresse'] ?? '');
            $ville   = trim($_POST['ville'] ?? '');
            $contact = trim($_POST['contact'] ?? '');
            if ($nom) {
                $this->model->create($nom, $adresse, $ville, $contact);
                header('Location: index.php?page=magasins&success=1');
                exit;
            }
            $error = 'Le nom est requis.';
        }
        (new MagasinsView())->showCreate($error);
    }

    public function handleEdit(): void
    {
        $this->auth->requireAdmin();
        $id  = (int)($_GET['id'] ?? 0);
        $mag = $this->model->getById($id);
        if (!$mag) { header('Location: index.php?page=magasins'); exit; }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom     = trim($_POST['nom'] ?? '');
            $adresse = trim($_POST['adresse'] ?? '');
            $ville   = trim($_POST['ville'] ?? '');
            $contact = trim($_POST['contact'] ?? '');
            if ($nom) {
                $this->model->update($id, $nom, $adresse, $ville, $contact);
                header('Location: index.php?page=magasins&success=1');
                exit;
            }
            $error = 'Le nom est requis.';
        }
        (new MagasinsView())->showEdit($mag, $error);
    }

    public function handleDelete(): void
    {
        $this->auth->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->model->delete($id);
        header('Location: index.php?page=magasins');
        exit;
    }
}