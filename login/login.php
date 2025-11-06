<?php

session_start();

$user = 'root';
$pass = '';
$error_message = '';

try {
  
    $db = new PDO('mysql:host=localhost;dbname=gt_emp;charset=utf8', $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        try {
            
            $stmt = $db->prepare("SELECT * FROM employe WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

               
                if ($password === $user['mdp']) {
                    $_SESSION['user_id'] = $user['id_emp'];
                    $_SESSION['id_role'] = $user['id_role'];

                    if ($user['id_role'] == 1) {
                 
                        echo "<script>window.location.replace('/prjt/admin/layout/index.html');</script>";
                    } else {
                        echo "<script>window.location.replace('/prjt/employe/layout/index.html');</script>";
                    }
                    exit;
                } else {
                    $error_message = "Mot de passe incorrect.";
                }
            } else {
                $error_message = "Aucun utilisateur trouvé avec cet email.";
            }
        } catch (PDOException $e) {
            $error_message = "Erreur : " . $e->getMessage();
        }
    } else {
        $error_message = "Veuillez remplir tous les champs.";
    }
}


if (!empty($error_message)) {
    echo "<div class='alert alert-danger'>$error_message</div>";
}
?>