<?php
require 'db.php';
// DOIT PAS ETRE ACCCESSIBLE
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $noauteur = $_POST['noauteur'];
    $titre = htmlspecialchars($_POST['titre']);
    $isbn13 = htmlspecialchars($_POST['isbn13']);
    $anneeparution = (int)$_POST['anneeparution'];
    $detail = htmlspecialchars($_POST['detail']);
    
    $dateajout = date('Y-m-d');

    $photo = null;
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $uploadDir = '../images-couvertures/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = basename($_FILES['cover']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['cover']['tmp_name'], $targetPath)) {
            $photo = $fileName;
        }
    }

    try {
        $sql = "INSERT INTO livre (noauteur, titre, isbn13, anneeparution, detail, dateajout, photo) 
                VALUES (:noauteur, :titre, :isbn13, :anneeparution, :detail, :dateajout, :photo)";
        
        $stmt = $connexion->prepare($sql);
        $stmt->execute([
            ':noauteur' => $noauteur,
            ':titre' => $titre,
            ':isbn13' => $isbn13,
            ':anneeparution' => $anneeparution,
            ':detail' => $detail,
            ':dateajout' => $dateajout,
            ':photo' => $photo
        ]);

        $message = '<div class="alert alert-success">Livre ajouté avec succès !</div>';
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Erreur lors de l\'ajout : ' . $e->getMessage() . '</div>';
    }
}

$auteurs = [];
try {
    $stmtAuteurs = $connexion->query("SELECT noauteur, nom, prenom FROM auteur ORDER BY nom, prenom");
    $auteurs = $stmtAuteurs->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = '<div class="alert alert-danger">Erreur de chargement des auteurs.</div>';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un livre - BiblioDrive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
    <?php require 'header.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">Ajouter un nouveau livre</h2>
        
        <?= $message ?>

        <form method="post" enctype="multipart/form-data" class="row g-3">
            
            <div class="col-md-6">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" class="form-control" name="titre" id="titre" required>
            </div>

            <div class="col-md-6">
                <label for="noauteur" class="form-label">Auteur</label>
                <select class="form-select" name="noauteur" id="noauteur" required>
                    <option value="" selected disabled>Choisir un auteur...</option>
                    <?php foreach ($auteurs as $auteur): ?>
                        <option value="<?= $auteur['noauteur'] ?>">
                            <?= htmlspecialchars($auteur['nom'] . ' ' . $auteur['prenom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="isbn13" class="form-label">ISBN13</label>
                <input type="text" class="form-control" name="isbn13" id="isbn13" required>
            </div>

            <div class="col-md-6">
                <label for="anneeparution" class="form-label">Année de Parution</label>
                <input type="number" class="form-control" name="anneeparution" id="anneeparution" required>
            </div>

            <div class="col-12">
                <label for="detail" class="form-label">Résumé</label>
                <textarea class="form-control" name="detail" id="detail" rows="3"></textarea>
            </div>

            <div class="col-12">
                <label for="cover" class="form-label">Image de couverture</label>
                <input type="file" class="form-control" name="cover" id="cover">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Ajouter le livre</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>