<?php

$user = 'root';
$pass = '';
try {
    $db = new PDO('mysql:host=localhost;dbname=gt_emp;charset=utf8', $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Affiche les erreurs
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}


$searchTerm = $_POST['searchTerm'];


$sql = $db->prepare("
    SELECT e.*, r.libelle
    FROM employe e
    JOIN role r ON e.id_role = r.id_role
    WHERE (
        e.id_emp LIKE :searchTerm OR
        e.cin LIKE :searchTerm OR
        e.nom LIKE :searchTerm OR
        e.prenom LIKE :searchTerm OR
        e.dt_naiss LIKE :searchTerm OR
        e.genre LIKE :searchTerm OR
        e.adresse LIKE :searchTerm OR
        e.tel LIKE :searchTerm OR
        e.email LIKE :searchTerm OR
        e.mdp LIKE :searchTerm OR
        r.libelle LIKE :searchTerm
    )
");
$sql->execute(['searchTerm' => "%{$searchTerm}%"]);

$output = "";
if ($sql->rowCount() > 0) {
    while ($fetch = $sql->fetch(PDO::FETCH_ASSOC)) {
        $output .= '<tr>
            <td>' . htmlspecialchars($fetch['id_emp']) . '</td>
            <td>' . htmlspecialchars($fetch['cin']) . '</td>
            <td>' . htmlspecialchars($fetch['nom']) . '</td>
            <td>' . htmlspecialchars($fetch['prenom']) . '</td>
            <td>' . htmlspecialchars($fetch['dt_naiss']) . '</td>
            <td>' . htmlspecialchars($fetch['genre']) . '</td>
            <td>' . htmlspecialchars($fetch['adresse']) . '</td>
            <td>' . htmlspecialchars($fetch['tel']) . '</td>
            <td>' . htmlspecialchars($fetch['email']) . '</td>
            <td>' . htmlspecialchars($fetch['mdp']) . '</td>
            <td>
                <a href="edit.php?client=' . urlencode($fetch['id_emp']) . '" class="btn btn-primary btn-sm">Edit</a>
                <a href="php/delete.php?client=' . urlencode($fetch['id_emp']) . '" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>';
    }
} else {
    $output .= 'No clients found for your search term.';
}

echo $output;

?>
