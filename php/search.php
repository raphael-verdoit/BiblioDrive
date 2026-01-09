<?php 
    require("db.php");

    $sql = "SELECT
                livre.nolivre,
                livre.titre,
                livre.anneeparution,
                auteur.nom,
                auteur.prenom
            FROM
                livre
            INNER JOIN
                auteur ON livre.noauteur = auteur.noauteur
            WHERE
                auteur.nom LIKE :recherche_auteur 
                OR auteur.prenom LIKE :recherche_auteur 
                OR CONCAT(auteur.prenom, ' ', auteur.nom) LIKE :recherche_auteur 
                OR CONCAT(auteur.nom, ' ', auteur.prenom) LIKE :recherche_auteur";

    $stmt = $connexion->prepare($sql);

    $terme = isset($_GET["recherche_auteur"]) ? $_GET["recherche_auteur"] : '';

    $recherche_auteur = '%' . $terme . '%';


    try {
        $stmt->execute([':recherche_auteur' => $recherche_auteur]);
    } catch (Exception $e) {
        echo "Erreur lors de l'insertion : " . $e->getMessage();
        die(); 
    }
    
    while($resultat = $stmt->fetch(PDO::FETCH_OBJ)){
        $id = $resultat->nolivre;
        echo '<a href="accueuil.php?id_livre=' . $id . '">' . htmlspecialchars($resultat->titre) . ' ' . htmlspecialchars($resultat->nom) . ' ' . htmlspecialchars($resultat->prenom) . ' - (' . htmlspecialchars($resultat->anneeparution) . ') </a> <br>';
    }
    
?>
