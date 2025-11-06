<?php
$user = 'root';
$pass = '';
try {

    $db = new PDO('mysql:host=localhost;dbname=gt_emp;charset=utf8', $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

$client = $_GET['client'];
if (!isset($client)) {
    header("location: ../index.php");
    exit;
}

$delete = $db->prepare("DELETE FROM employe WHERE id_emp = :id_emp");
$delete->execute(['id_emp' => $client]);

header("location: ../index.php");
exit;
?>
