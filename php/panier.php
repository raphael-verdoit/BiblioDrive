<?php
// 1. Démarrage de la session
session_start();

// 2. Vérification de la connexion
if (!isset($_SESSION['mel'])) {
    header('Location: ../accueuil.php'); // Redirection vers l'accueil si non connecté
    exit();
}

// 3. Inclusion de la base de données
require "db.php";

// 4. Récupération des détails des livres du panier
$livres_panier = [];
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    // On crée une chaîne de points d'interrogation pour la requête IN (?,?,?)
    $ids = array_keys($_SESSION['panier']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    
    $sql = "SELECT livre.*, auteur.nom as nom_auteur, auteur.prenom as prenom_auteur 
            FROM livre 
            JOIN auteur ON livre.noauteur = auteur.noauteur 
            WHERE livre.nolivre IN ($placeholders)";
            
    $stmt = $connexion->prepare($sql);
    $stmt->execute($ids);
    $livres_panier = $stmt->fetchAll(PDO::FETCH_OBJ);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier - BiblioDrive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "header.php"; ?>

<div class="container mt-5">
    <h2 class="mb-4">Votre sélection de livres</h2>

    <?php if (empty($livres_panier)): ?>
        <div class="alert alert-info">
            Votre panier est vide. <a href="./lister_livres.php" class="alert-link">Parcourir les livres</a>.
        </div>
    <?php else: ?>
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Livre</th>
                    <th>Auteur</th>
                    <th>Année</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livres_panier as $livre): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($livre->titre); ?></strong>
                        </td>
                        <td><?php echo htmlspecialchars($livre->prenom_auteur . " " . $livre->nom_auteur); ?></td>
                        <td><?php echo htmlspecialchars($livre->anneeparution); ?></td>
                        <td class="text-center">
                            <a href="supprimer_panier.php?id_livre=<?php echo $livre->nolivre; ?>" class="btn btn-sm btn-outline-danger">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-4">
            <a href="../accueuil.php" class="btn btn-secondary">Continuer mes recherches</a>
            <div>
                <a href="valider_emprunt.php" class="btn btn-success">Confirmer l'emprunt</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>