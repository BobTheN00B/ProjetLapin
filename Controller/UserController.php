<?php
require_once __DIR__ . '/../Model/UserModel.php';

class UserController
{
    private UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function isConnected(): bool
    {
        return isset($_SESSION['user']['id']);
    }

    public function isAdmin(): bool
    {
        return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
    }

    public function requireAuth(): void
    {
        if (!$this->isConnected()) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public function requireAdmin(): void
    {
        $this->requireAuth();
        if (!$this->isAdmin()) {
            header('Location: index.php?page=dashboard&error=unauthorized');
            exit;
        }
    }

    // ---- Actions ----
    public function handleLogin(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $error    = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->model->findByEmail($email);
            if ($user && $this->model->verifyPassword($password, $user['MDP'])) {
                $_SESSION['user'] = [
                    'id'   => $user['Id_Utilisateurs'],
                    'nom'  => $user['Nom'],
                    'role' => $user['Role'],
                ];
                header('Location: index.php?page=dashboard');
                exit;
            }
            $error = 'Email ou mot de passe incorrect 🐰';
        }

        require __DIR__ . '/../View/UserView.php';
        (new UserView())->showLogin($error);
    }

    public function handleLogout(): void
    {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }

    public function handleList(): void
    {
        $this->requireAdmin();
        $users = $this->model->getAll();
        (new UserView())->showList($users);
    }

    public function handleCreate(): void
    {
        $this->requireAdmin();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom      = trim($_POST['nom'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role     = $_POST['role'] ?? 'lapin';
            if ($nom && $email && $password) {
                $this->model->create($nom, $email, $password, $role);
                header('Location: index.php?page=utilisateurs&success=1');
                exit;
            }
            $error = 'Tous les champs sont requis.';
        }
        (new UserView())->showCreate($error);
    }

    public function handleDelete(): void
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id && $id !== (int)$_SESSION['user']['id']) {
            $this->model->delete($id);
        }
        header('Location: index.php?page=utilisateurs');
        exit;
    }
}