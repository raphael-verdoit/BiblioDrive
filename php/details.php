<?php
    session_start();
    require("db.php");

    if (isset($_GET['id_livre'])) {
        $id_livre = $_GET['id_livre'];

        $sql = "SELECT
                    livre.nolivre,
                    livre.titre,
                    livre.anneeparution,
                    livre.detail,
                    livre.photo,
                    livre.isbn13,
                    auteur.nom,
                    auteur.prenom
                FROM
                    livre
                INNER JOIN
                    auteur ON livre.noauteur = auteur.noauteur
                WHERE
                    livre.nolivre = :id_livre";

        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id_livre', $id_livre);
        $stmt->execute();
        
        $livre = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$livre) {
        die("Livre introuvable.");
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails - <?php echo htmlspecialchars($livre['titre']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <?php 
        // Inclusion du header si disponible
        if(file_exists("../html/header.html")) {
            include("../html/header.html"); 
        }
    ?>

    <div class="container my-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="display-4 mb-4"><?php echo htmlspecialchars($livre['titre']); ?></h1>
                        
                        <div class="mb-3">
                            <h5 class="text-muted mb-1">Auteur</h5>
                            <p class="fs-5"><?php echo htmlspecialchars($livre['prenom'] . " " . $livre['nom']); ?></p>
                        </div>

                        <div class="mb-3">
                            <h5 class="text-muted mb-1">ISBN</h5>
                            <p class="font-monospace"><?php echo htmlspecialchars($livre['isbn13']); ?></p>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-muted mb-1">Résumé</h5>
                            <p class="text-justify"><?php echo nl2br(htmlspecialchars($livre['detail'])); ?></p>
                        </div>

                        <div class="mb-4">
                            <?php if(isset($_SESSION['mel'])): ?>
                                <form action="ajouter_panier.php" method="POST">
                                    <input type="hidden" name="id_livre" value="<?php echo htmlspecialchars($livre['nolivre']); ?>">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        Ajouter au panier
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-warning d-inline-block">
                                    Veuillez vous <a href="./accueuil.php" class="alert-link">connecter</a> pour emprunter ce livre.
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mt-auto">
                            <p class="text-muted small">Paru en : <?php echo htmlspecialchars($livre['anneeparution']); ?></p>
                            <a href="../accueuil.php" class="btn btn-outline-secondary">
                                &larr; Retour
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4 text-center">
                        <img src="../images-couvertures/<?php echo htmlspecialchars($livre['photo']); ?>" 
                             alt="Couverture de <?php echo htmlspecialchars($livre['titre']); ?>" 
                             class="img-fluid rounded shadow-sm border">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>