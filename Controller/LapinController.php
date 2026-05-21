<?php
require_once __DIR__ . '/../Model/LapinModel.php';
require_once __DIR__ . '/../View/LapinView.php';
require_once __DIR__ . '/../Controller/UserController.php';

class LapinController
{
    private LapinModel $model;
    private UserController $auth;

    public function __construct()
    {
        $this->model = new LapinModel();
        $this->auth  = new UserController();
    }

    public function handleList(): void
    {
        $this->auth->requireAuth();
        $lapins = $this->model->getAll();
        (new LapinView())->showList($lapins);
    }

    public function handleCreate(): void
    {
        $this->auth->requireAdmin();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom    = trim($_POST['nom'] ?? '');
            $couleur = trim($_POST['couleur'] ?? '');
            $statut = $_POST['statut'] ?? 'disponible';
            $idUser = (int)($_POST['id_utilisateur'] ?? $_SESSION['user']['id']);
            if ($nom) {
                $this->model->create($nom, $couleur, $statut, $idUser);
                header('Location: index.php?page=lapins&success=1');
                exit;
            }
            $error = 'Le nom est requis.';
        }
        require_once __DIR__ . '/../Model/UserModel.php';
        $users = (new UserModel())->getAll();
        (new LapinView())->showCreate($error, $users);
    }

    public function handleEdit(): void
    {
        $this->auth->requireAdmin();
        $id    = (int)($_GET['id'] ?? 0);
        $lapin = $this->model->getById($id);
        if (!$lapin) { header('Location: index.php?page=lapins'); exit; }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom    = trim($_POST['nom'] ?? '');
            $couleur = trim($_POST['couleur'] ?? '');
            $statut = $_POST['statut'] ?? 'disponible';
            if ($nom) {
                $this->model->update($id, $nom, $couleur, $statut);
                header('Location: index.php?page=lapins&success=1');
                exit;
            }
            $error = 'Le nom est requis.';
        }
        (new LapinView())->showEdit($lapin, $error);
    }

    public function handleDelete(): void
    {
        $this->auth->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->model->delete($id);
        header('Location: index.php?page=lapins');
        exit;
    }
}