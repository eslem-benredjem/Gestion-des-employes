<?php
$user = 'root';
$pass = '';
try {

    $db = new PDO('mysql:host=localhost;dbname=gt_emp;charset=utf8', $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Affiche les erreurs
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}


function generateRandomString($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomIndex = random_int(0, $charactersLength - 1);
        $randomString .= $characters[$randomIndex];
    }
    return $randomString;
}

$errors = []; 

if (isset($_POST['submit'])) {

    $cin = trim($_POST['cin']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date = trim($_POST['date']);
    $genre = trim($_POST['gender']);
    $adresse = trim($_POST['adr']);
    $tel = trim($_POST['tel']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format de l'email invalide.";
    }
    if (!preg_match('/^\d{8}$/', $tel)) {
        $errors[] = "Le numéro de téléphone doit contenir exactement 8 chiffres.";
    }
    if (empty($cin) || empty($nom) || empty($prenom) || empty($date) || empty($genre) || empty($adresse) || empty($tel) || empty($email) || empty($role)) {
        $errors[] = "Tous les champs sont requis.";
    }


    $role_id = ($role === 'admin') ? 1 : ($role === 'employee' ? 2 : null);
    if ($role_id === null) {
        $errors[] = "Rôle sélectionné invalide.";
    }


    if (empty($errors)) {

        $mdp = generateRandomString(10);
        $hashed_mdp = $mdp;


        $formatted_date = date('Y-m-d', strtotime($date));

 
        $sql = "INSERT INTO employe (cin, nom, prenom, dt_naiss, genre, adresse, tel, email, mdp, id_role) 
                VALUES (:cin, :nom, :prenom, :dt_naiss, :genre, :adresse, :tel, :email, :mdp, :id_role)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'cin' => $cin,
            'nom' => $nom,
            'prenom' => $prenom,
            'dt_naiss' => $formatted_date,
            'genre' => $genre,
            'adresse' => $adresse,
            'tel' => $tel,
            'email' => $email,
            'mdp' => $hashed_mdp,
            'id_role' => $role_id
        ]);


        if ($stmt->rowCount() > 0) {
            $success_message = "Employé ajouté avec succès. Mot de passe généré : $mdp";
        } else {
            $errors[] = "Une erreur s'est produite lors de la création de l'employé.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/bootstrap.css">
    <title>Ajouter un Employé</title>
</head>
<body>
    <div class="container my-5">
        <h2>Ajouter un Employé</h2>
        <br><br>

        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }
        if (isset($success_message)) {
            echo "<div class='alert alert-success'>$success_message</div>";
        }
        ?>

        <form action="" method="post">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">CIN</label>
                <div class="col-sm-6">
                    <input type="text" name="cin" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Nom</label>
                <div class="col-sm-6">
                    <input type="text" name="nom" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Prénom</label>
                <div class="col-sm-6">
                    <input type="text" name="prenom" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Date de Naissance</label>
                <div class="col-sm-6">
                    <input type="date" name="date" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Genre</label>
                <div class="col-sm-6">
                    <select name="gender" class="form-control" required>
                        <option value="Male">Homme</option>
                        <option value="Female">Femme</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Adresse</label>
                <div class="col-sm-6">
                    <input type="text" name="adr" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Téléphone</label>
                <div class="col-sm-6">
                    <input type="text" name="tel" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Rôle</label>
                <div class="col-sm-6">
                    <select name="role" class="form-control">
                        <option value="admin">Admin</option>
                        <option value="employee">Employé</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <button type="submit" name="submit" class="col-sm-3 btn btn-primary">Valider</button>
                <div class="col-sm-6">
                    <a href="index.php" class="btn btn-outline-primary">Retour</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
