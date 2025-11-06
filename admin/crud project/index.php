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
        <h2>List des Employees</h2>
        <div class="mb-3">
            <div class="col-sm-6">
                <input type="text" name="search" placeholder="Search Client.." id="search_bar" class="form-control">
            </div>
        </div>
        <br>
        <br>

        <table class="table">
            <thead>
                <tr>
                <th>ID Employe</th>
                <th>CIN</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Date de Naissance</th>
                <th>Genre</th>
                <th>Adresse</th>
                <th>N°Telephone</th>
                <th>Adresse Email</th>
                <th>Mot de Passe </th>
                <th>Role</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody id="tbody">
                <?php include "php/client_record.php"; ?>
            </tbody>
        </table>

    </div>


    <script src="js/search.js"></script>
</body>
</html>