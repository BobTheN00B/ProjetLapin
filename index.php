<?php
include 'Controller/UserController.php';
include 'Controller/LapinController.php';
include 'Controller/LivraisonsController.php';
include 'Controller/MagasinsController.php';

if($page == 1){
        $mapage = new UserController();
        $mapage->afficherMessage(); 
    }
?>