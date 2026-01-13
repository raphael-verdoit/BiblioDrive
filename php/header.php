<?php
$est_dans_dossier_php = (basename(dirname($_SERVER['PHP_SELF'])) == 'php');

$prefixe_php = $est_dans_dossier_php ? "" : "php/";
$prefixe_racine = $est_dans_dossier_php ? "../" : "./";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<header>
    <div class="alert text-center">
        La bibliothèque de Moulinsart est fermée au public jusqu'à nouvel ordre. Retirez vos livres via le bibliodrive !
    </div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $prefixe_racine; ?>accueuil.php">BiblioDrive</a>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $prefixe_php; ?>lister_livres.php">Livres</a>
                    </li>
                    <?php if(isset($_SESSION['mel'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $prefixe_php;?>ajout_livre.php">Ajouter un livre</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $prefixe_php;?>ajout_membre.php">Ajouter un membre</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <div class="d-flex align-items-center">
                    <?php if(isset($_SESSION['mel'])): ?>
                        <span class="navbar-text me-3">
                            Bonjour, <a href="./dashboard.php"><strong><?php echo htmlspecialchars($_SESSION['mel']); ?></strong></a>
                        </span>
                        <a href="<?php echo $prefixe_php; ?>panier.php" class="btn btn-outline-primary me-2">Mon Panier</a>
                        <a href="<?php echo $prefixe_php; ?>logout.php" class="btn btn-outline-danger">Déconnexion</a>
                    <?php else: ?>
                        <a href="<?php echo $prefixe_php; ?>login.php" class="btn btn-outline-success">Connexion</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>