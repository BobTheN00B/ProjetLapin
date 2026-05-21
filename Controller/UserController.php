<?php

require_once __DIR__ . '/../Model/UserModel.php';
require_once __DIR__ . '/../Model/LivraisonsModel.php';
require_once __DIR__ . '/../Model/LapinModel.php';
require_once __DIR__ . '/../Model/MagasinsModel.php';

class UserController
{
    private UserModel $model;
    private LivraisonsModel $livraisonsmodel;

    public function __construct()
    {
        $this->model = new UserModel();
        $this->model = new LivraisonsModel();
    }

    private function isConnected(): bool
    {
        return isset($_SESSION['user']['id']);
    }
}
?>