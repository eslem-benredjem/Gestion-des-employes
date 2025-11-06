<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.html'); 
    exit();
}

$user_id = $_SESSION['user_id'];


$user = 'root';
$pass = '';
try {
    $db = new PDO('mysql:host=localhost;dbname=gt_emp;charset=utf8', $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

try {
    $stmt = $db->prepare("SELECT * FROM employe WHERE id_emp = :id_emp");
    $stmt->bindParam(':id_emp', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        die('Utilisateur non trouvé.');
    }
} catch (PDOException $e) {
    die('Erreur lors de la récupération des données : ' . $e->getMessage());
}


$errors = [];
$success_message = "";

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


    if (empty($cin) || empty($nom) || empty($prenom) || empty($date) || empty($adresse) || empty($gsm) || empty($email) || empty($mdp)) {
        $errors[] = 'Veuillez remplir tous les champs obligatoires.';
    }

    if (empty($errors)) {
        try {

            $updateStmt = $db->prepare("UPDATE employe 
                                        SET cin = :cin, nom = :nom, prenom = :prenom, dt_naiss = :dt_naiss, 
                                            genre = :genre,adresse = :adresse, tel = :tel, email = :email,  id_role = :role
                                        WHERE id_emp = :id_emp");
            $updateStmt->execute([
                'cin' => $cin,
                'nom' => $nom,
                'prenom' => $prenom,
                'dt_naiss' => $date,
                'genre' => $genre,
                'adresse' => $adresse,
                'tel' => $gsm,
                'email' => $email,
                'role' => $role,
                'id_emp' => $user_id
            ]);

            $success_message = "Profil mis à jour avec succès.";
        } catch (PDOException $e) {
            $errors[] = 'Erreur lors de la mise à jour : ' . $e->getMessage();
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
    <title>Modifier Employé</title>
</head>
<body>
    <div class="container my-5">
        <h2>Modifier Employé</h2>
        <br><br>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars(implode('<br>', $errors)) ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">CIN</label>
                <div class="col-sm-6">
                    <input type="text" value="<?= htmlspecialchars($employee['cin']); ?>" name="cin" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Nom</label>
                <div class="col-sm-6">
                    <input type="text" value="<?= htmlspecialchars($employee['nom']); ?>" name="nom" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Prénom</label>
                <div class="col-sm-6">
                    <input type="text" value="<?= htmlspecialchars($employee['prenom']); ?>" name="prenom" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Date de Naissance</label>
                <div class="col-sm-6">
                    <input type="date" value="<?= htmlspecialchars($employee['dt_naiss']); ?>" name="date" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Genre</label>
                <div class="col-sm-6">
                    <select name="gender" class="form-control">
                        <option value="Male" <?= $employee['genre'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?= $employee['genre'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Adresse</label>
                <div class="col-sm-6">
                    <input type="text" value="<?= htmlspecialchars($employee['adresse']); ?>" name="adr" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">N° Téléphone</label>
                <div class="col-sm-6">
                    <input type="number" value="<?= htmlspecialchars($employee['tel']); ?>" name="tel" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Adresse Email</label>
                <div class="col-sm-6">
                    <input type="email" value="<?= htmlspecialchars($employee['email']); ?>" name="email" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Mot de passe</label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo htmlspecialchars($employee['mdp']); ?>" name="mdp" class="form-control" required>
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
