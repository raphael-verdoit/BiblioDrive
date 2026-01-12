<?php
session_start(); 
require_once 'php/db.php'; 

$erreur = null; 

if (isset($_POST['login_submit'])){
    $email = $_POST['email'];
    $mdp = $_POST['pass'];

    try {
        $sql = 'SELECT * FROM utilisateur WHERE mel = ?';
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user && $mdp === $user->motdepasse) {
            $_SESSION['mel'] = $user->mel; 
            $_SESSION['user_name'] = $user->prenom . ' ' . $user->nom;
            header('Location: accueuil.php'); 
            exit();
        } else {
            $erreur = "Email ou mot de passe incorrect.";
        }
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Biblio-Drive - Accueil</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-9"> 
                    <?php require "./php/header.php"; ?>
                    <form action="" method="get" class="mt-3">
                        <div class="input-group mb-3" style="max-width: 400px;">
                            <input type="text" class="form-control" name="recherche_auteur" placeholder="Nom de l'auteur..." value="<?php echo isset($_GET['recherche_auteur']) ? htmlspecialchars($_GET['recherche_auteur']) : ''; ?>">
                            <button class="btn btn-outline-secondary" type="submit">
                                <img src="./images/loupe.svg" alt="Rechercher" id="loupe">
                            </button>
                            <?php if(isset($_GET['recherche_auteur'])): ?>
                                <a href="accueuil.php" class="btn btn-outline-danger">X</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                <div class="col-3"><img src="./images/logo.gif" alt="logo bibli" class="img-fluid"></div> 
            </div>
        
            <div class="row">
                <div class="col-9"> 
                    <?php
                    // --- CAS 1 : RECHERCHE ACTIVÉE ---
                    if (isset($_GET['recherche_auteur']) && !empty($_GET['recherche_auteur'])) {
                        $terme = $_GET["recherche_auteur"];
                        $recherche_auteur = '%' . $terme . '%';

                        // Logique récupérée de search.php
                        $sql = "SELECT livre.nolivre, livre.titre, livre.anneeparution, livre.photo, auteur.nom, auteur.prenom
                                FROM livre
                                INNER JOIN auteur ON livre.noauteur = auteur.noauteur
                                WHERE auteur.nom LIKE :recherche OR auteur.prenom LIKE :recherche 
                                OR CONCAT(auteur.prenom, ' ', auteur.nom) LIKE :recherche 
                                OR CONCAT(auteur.nom, ' ', auteur.prenom) LIKE :recherche";
                        
                        $stmt = $connexion->prepare($sql);
                        $stmt->execute([':recherche' => $recherche_auteur]);
                        $resultats = $stmt->fetchAll(PDO::FETCH_OBJ);

                        echo "<h3>Résultats pour : " . htmlspecialchars($terme) . "</h3>";
                        
                        if (count($resultats) > 0) {
                            echo '<div class="list-group">';
                            foreach ($resultats as $livre) {
                                // Lien corrigé vers php/details.php
                                echo '<a href="php/details.php?id_livre=' . $livre->nolivre . '" class="list-group-item list-group-item-action">';
                                echo '<strong>' . htmlspecialchars($livre->titre) . '</strong> - ' . htmlspecialchars($livre->prenom . ' ' . $livre->nom) . ' (' . $livre->anneeparution . ')';
                                echo '</a>';
                            }
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-warning">Aucun livre trouvé pour cet auteur.</div>';
                        }

                    // --- CAS 2 : AFFICHAGE PAR DÉFAUT (CARROUSEL) ---
                    } else {
                        $sql = "SELECT nolivre, titre, photo FROM livre WHERE photo != '' ORDER BY RAND() LIMIT 3";
                        $resultat = $connexion->query($sql);
                        $livres = $resultat->fetchAll();
                        ?>

                        <div id="monCarrousel" class="carousel slide" data-bs-ride="carousel" style="max-width: 300px; margin: 20px auto;">
                            <div class="carousel-inner">
                                <?php foreach ($livres as $index => $livre): ?>
                                    <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                                        <a href="php/details.php?id_livre=<?php echo $livre['nolivre']; ?>">
                                            <img src="images-couvertures/<?php echo $livre['photo']; ?>" class="d-block w-100" alt="<?php echo $livre['titre']; ?>">
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#monCarrousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#monCarrousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        </div>
                    <?php } ?>
                </div> 

                <div class="col-3"> 
                    <?php require_once "./php/login.php"; ?>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>