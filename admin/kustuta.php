<?php include('../config.php'); ?>
<?php
session_start();
if (!isset($_SESSION['kasutajanimi'])) {
    header('Location: login.php');
    exit;
}

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    
}