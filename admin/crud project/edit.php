<?php
$user = 'root';
$pass = '';
try {

    $db = new PDO('mysql:host=localhost;dbname=gt_emp;charset=utf8', $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Affiche les erreurs
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

$client = $_GET['client'];
if (!isset($client)) {
    header("location: index.php");
    exit;
}


$query = $db->prepare("SELECT e.*, r.libelle FROM employe e JOIN role r ON e.id_role = r.id_role WHERE e.id_emp = :client");
$query->execute(['client' => $client]);
$fetch = $query->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {

    $cin = trim($_POST['cin']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date = trim($_POST['date']);
    $genre = trim($_POST['gender']);
    $adresse = trim($_POST['adr']);
    $gsm = trim($_POST['tel']);
    $email = trim($_POST['email']);
    $mdp = trim($_POST['mdp']);
    $role = (int)trim($_POST['role']);

    try {
       
        $update = $db->prepare("UPDATE employe SET 
                                cin = :cin, 
                                nom = :nom, 
                                prenom = :prenom, 
                                dt_naiss = :dt_naiss, 
                                genre = :genre, 
                                adresse = :adresse, 
                                tel = :tel, 
                                email = :email, 
                                mdp = :mdp, 
                                id_role = :role 
                                WHERE id_emp = :id_emp");

        $update->execute([
            'cin' => $cin,
            'nom' => $nom,
            'prenom' => $prenom,
            'dt_naiss' => $date,
            'genre' => $genre,
            'adresse' => $adresse,
            'tel' => $gsm,
            'email' => $email,
            'mdp' => $mdp,
            'role' => $role,
            'id_emp' => $client
        ]);

  
        header("location: edit.php?client=$client&success=Client Edited Successfully");
        exit;
    } catch (PDOException $e) {

        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/bootstrap.css">
    <title>Crud Project</title>
</head>
<body>
    <div class="container my-5">
        <h2>Modifier Employé</h2>
        <br>
        <br>

        <form action="" method="post">
            <?php
            if (isset($_GET['success'])) {
                echo '<div class="w-25 alert alert-success" role="alert">' . $_GET['success'] . '</div>';
            }
            ?>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">CIN</label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo htmlspecialchars($fetch['cin']); ?>" name="cin" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Nom</label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo htmlspecialchars($fetch['nom']); ?>" name="nom" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Prénom</label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo htmlspecialchars($fetch['prenom']); ?>" name="prenom" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Date de Naissance</label>
                <div class="col-sm-6">
                    <input type="date" value="<?php echo htmlspecialchars($fetch['dt_naiss']); ?>" name="date" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Genre</label>
                <div class="col-sm-6">
                    <select name="gender" class="form-control">
                        <option value="Male" <?php if ($fetch['genre'] == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($fetch['genre'] == 'Female') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Adresse</label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo htmlspecialchars($fetch['adresse']); ?>" name="adr" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
         
            <label class="col-sm-3 col-form-label">N° Téléphone</label>
                <div class="col-sm-6">
                    <input type="number" value="<?php echo htmlspecialchars($fetch['tel']); ?>" name="tel" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Adresse Email</label>
                <div class="col-sm-6">
                    <input type="email" value="<?php echo htmlspecialchars($fetch['email']); ?>" name="email" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Mot de passe</label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo htmlspecialchars($fetch['mdp']); ?>" name="mdp" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Rôle</label>
                <div class="col-sm-6">
                    <select name="role" class="form-control">
                        <option value="1" <?php if ($fetch['id_role'] == 1) echo 'selected'; ?>>Admin</option>
                        <option value="2" <?php if ($fetch['id_role'] == 2) echo 'selected'; ?>>Employee</option>
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
