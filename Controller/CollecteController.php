<?php
require_once __DIR__ . '/../Model/CollecteModel.php';
require_once __DIR__ . '/../Model/LapinModel.php';
require_once __DIR__ . '/../Model/MagasinsModel.php';
require_once __DIR__ . '/../View/CollecteView.php';
require_once __DIR__ . '/../Controller/UserController.php';

class CollecteController
{
    private CollecteModel $model;
    private UserController $auth;

    public function __construct()
    {
        $this->model = new CollecteModel();
        $this->auth  = new UserController();
    }

    public function handleList(): void
    {
        $this->auth->requireAuth();
        $collectes = $this->model->getAll();
        foreach ($collectes as &$c) {
            $c['lapins'] = $this->model->getLapins($c['Id_Collecte']);
        }
        (new CollecteView())->showList($collectes);
    }

    public function handleCreate(): void
    {
        $this->auth->requireAdmin();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datePrev  = $_POST['date_prev']  ?? '';
            $idMagasin = (int)($_POST['id_magasins'] ?? 0);
            $idLapins  = array_map('intval', $_POST['lapins'] ?? []);
            if ($datePrev && $idMagasin) {
                $idCollecte = $this->model->create($datePrev, $idMagasin);
                foreach ($idLapins as $idL) {
                    if ($idL) $this->model->affecterLapin($idCollecte, $idL);
                }
                header('Location: index.php?page=collectes&success=1');
                exit;
            }
            $error = 'La date et le magasin sont requis.';
        }
        $magasins = (new MagasinsModel())->getAll();
        $lapins   = (new LapinModel())->getAll();
        (new CollecteView())->showCreate($error, $magasins, $lapins);
    }

    public function handleEdit(): void
    {
        $this->auth->requireAdmin();
        $id      = (int)($_GET['id'] ?? 0);
        $collecte = $this->model->getById($id);
        if (!$collecte) { header('Location: index.php?page=collectes'); exit; }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datePrev    = $_POST['date_prev']       ?? '';
            $dateCollecte= $_POST['date_collecte']   ?: null;
            $idMagasin   = (int)($_POST['id_magasins'] ?? 0);
            $idLapins    = array_map('intval', $_POST['lapins'] ?? []);
            if ($datePrev && $idMagasin) {
                $this->model->update($id, $datePrev, $dateCollecte, $idMagasin);
                // Resync lapins
                $anciens = array_column($this->model->getLapins($id), 'Id_Lapins');
                foreach (array_diff($anciens, $idLapins) as $rem) {
                    $this->model->retirerLapin($id, $rem);
                }
                foreach (array_diff($idLapins, $anciens) as $add) {
                    if ($add) $this->model->affecterLapin($id, $add);
                }
                header('Location: index.php?page=collectes&success=1');
                exit;
            }
            $error = 'La date et le magasin sont requis.';
        }
        $magasins       = (new MagasinsModel())->getAll();
        $lapins         = (new LapinModel())->getAll();
        $lapinsAffectes = array_column($this->model->getLapins($id), 'Id_Lapins');
        (new CollecteView())->showEdit($collecte, $error, $magasins, $lapins, $lapinsAffectes);
    }

    public function handleDelete(): void
    {
        $this->auth->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->model->delete($id);
        header('Location: index.php?page=collectes');
        exit;
    }

    public function handleMarkDone(): void
    {
        $this->auth->requireAuth();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->model->update($id, date('Y-m-d'), date('Y-m-d'), 'réalisée',
                (int)($this->model->getById($id)['Id_Magasins'] ?? 0));
        }
        header('Location: index.php?page=collectes&success=1');
        exit;
    }
}