<?php

$user = 'root';
$pass = '';
try {
    $db = new PDO('mysql:host=localhost;dbname=gt_emp;charset=utf8', $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}


$sql = $db->prepare("SELECT e.id_emp, e.cin, e.nom, e.prenom, e.dt_naiss, e.genre, e.tel, e.adresse, e.email, e.mdp, r.libelle 
    FROM employe e 
    JOIN role r ON e.id_role = r.id_role");
$sql->execute();


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
            <td>' . htmlspecialchars($fetch['libelle']) . '</td>
            <td>
                <a href="edit.php?client=' . urlencode($fetch['id_emp']) . '" class="btn btn-primary btn-sm">Edit</a>
                <a href="php/delete.php?client=' . urlencode($fetch['id_emp']) . '" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>';
    }
} else {
    $output .= 'Aucun employé trouvé';
}

echo $output;

?>
