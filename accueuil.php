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
                <div class="col-9"> <!-- Header -->
                    <?php require("./php/header.php"); ?>
                    <form action="" method="get">
                        <input type="text" name="recherche_auteur" placeholder="Nom de l'auteur : ">
                        <button type="submit"><img src="./images/loupe.svg" alt="loupe.svg" id="loupe"></button>
                    </form>
                </div>
                <div class="col-3">LOGO</div> <!-- Logo -->
            </div>
        
            <div class="row">
                <div class="col-9"> <!-- Contenu -->
                    <?php
                        if (isset($_GET['id_livre'])){
                            require('./php/details.php');
                        } 
                        elseif (isset($_GET['recherche_auteur']) && !empty(trim($_GET['recherche_auteur']))){ 
                            require('./php/search.php');
                        } # Au lieu de isset buton pour eviter une url sur charger
                    ?>
                </div> 

            <div class="col-3"> 
                <?php require_once('./php/login.php')?>
            </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>