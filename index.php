<?php
session_start();

// ── Autoload simple ──────────────────────────────────────────
require_once __DIR__ . '/db.php';

// Models
require_once __DIR__ . '/Model/UserModel.php';
require_once __DIR__ . '/Model/LapinModel.php';
require_once __DIR__ . '/Model/MagasinsModel.php';
require_once __DIR__ . '/Model/ZoneModel.php';
require_once __DIR__ . '/Model/JardinModel.php';
require_once __DIR__ . '/Model/CollecteModel.php';
require_once __DIR__ . '/Model/LivraisonsModel.php';

// Views
require_once __DIR__ . '/View/layout.php';
require_once __DIR__ . '/View/UserView.php';
require_once __DIR__ . '/View/LapinView.php';
require_once __DIR__ . '/View/MagasinsView.php';
require_once __DIR__ . '/View/ZoneJardinView.php';
require_once __DIR__ . '/View/CollecteView.php';
require_once __DIR__ . '/View/LivraisonsView.php';

// Controllers
require_once __DIR__ . '/Controller/UserController.php';
require_once __DIR__ . '/Controller/LapinController.php';
require_once __DIR__ . '/Controller/MagasinsController.php';
require_once __DIR__ . '/Controller/ZoneController.php';
require_once __DIR__ . '/Controller/JardinController.php';
require_once __DIR__ . '/Controller/CollecteController.php';
require_once __DIR__ . '/Controller/LivraisonsController.php';

// ── Routeur ──────────────────────────────────────────────────
$page = $_GET['page'] ?? 'register';

$auth = new UserController();

switch ($page) {

    // ── Auth ────────────────────────────────────────────────
    case 'register':
        $auth->handleRegister();
        break;
    case 'login':
        $auth->handleLogin();
        break;
    case 'logout':
        $auth->handleLogout();
        break;

    // ── Dashboard ───────────────────────────────────────────
    case 'dashboard':
        $auth->requireAuth();
        $livrModel  = new LivraisonsModel();
        $lapinModel = new LapinModel();
        $magModel   = new MagasinsModel();
        $jardinModel= new JardinModel();
        $stats      = $livrModel->getStats();
        $parZone    = $livrModel->getLivreesParZone();
        $lapins     = $lapinModel->getAll();
        $nbMagasins = count($magModel->getAll());
        $nbJardins  = count($jardinModel->getAll());
        (new UserView())->showDashboard($stats, $parZone, $lapins, $nbMagasins, $nbJardins);
        break;

    // ── Utilisateurs ────────────────────────────────────────
    case 'utilisateurs':        $auth->handleList();   break;
    case 'utilisateurs_create': $auth->handleCreate(); break;
    case 'utilisateurs_delete': $auth->handleDelete(); break;

    // ── Lapins ──────────────────────────────────────────────
    case 'lapins':
        (new LapinController())->handleList();   break;
    case 'lapins_create':
        (new LapinController())->handleCreate(); break;
    case 'lapins_edit':
        (new LapinController())->handleEdit();   break;
    case 'lapins_delete':
        (new LapinController())->handleDelete(); break;

    // ── Magasins ─────────────────────────────────────────────
    // ── Magasins Donateurs (À insérer dans le switch de index.php) ──
    case 'magasins':
        (new MagasinsController())->handleList();   break;
    case 'magasins_create':
        (new MagasinsController())->handleCreate(); break;
    case 'magasins_edit':
        (new MagasinsController())->handleEdit();   break;
    case 'magasins_delete':
        (new MagasinsController())->handleDelete(); break;

    // ── Zones ────────────────────────────────────────────────
    case 'zones':
        (new ZoneController())->handleList();   break;
    case 'zones_create':
        (new ZoneController())->handleCreate(); break;
    case 'zones_edit':
        (new ZoneController())->handleEdit();   break;
    case 'zones_delete':
        (new ZoneController())->handleDelete(); break;

    // ── Jardins ──────────────────────────────────────────────
    case 'jardins':
        (new JardinController())->handleList();   break;
    case 'jardins_create':
        (new JardinController())->handleCreate(); break;
    case 'jardins_edit':
        (new JardinController())->handleEdit();   break;
    case 'jardins_delete':
        (new JardinController())->handleDelete(); break;

    // ── Collectes ────────────────────────────────────────────
    case 'collectes':
        (new CollecteController())->handleList();   break;
    case 'collectes_create':
        (new CollecteController())->handleCreate(); break;
    case 'collectes_edit':
        (new CollecteController())->handleEdit();   break;
    case 'collectes_delete':
        (new CollecteController())->handleDelete(); break;
    case 'collectes_done':
        (new CollecteController())->handleMarkDone(); break;

    // ── Livraisons ───────────────────────────────────────────
    case 'livraisons':
        (new LivraisonsController())->handleList();   break;
    case 'livraisons_create':
        (new LivraisonsController())->handleCreate(); break;
    case 'livraisons_edit':
        (new LivraisonsController())->handleEdit();   break;
    case 'livraisons_delete':
        (new LivraisonsController())->handleDelete(); break;
    case 'livraisons_done':
        (new LivraisonsController())->handleMarkDone(); break;

    // ── Logs ─────────────────────────────────────────────────
    case 'logs':
        (new LivraisonsController())->handleLogs(); break;

    default:
        header('Location: index.php?page=login');
        exit;
}