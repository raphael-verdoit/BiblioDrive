<?php
require_once("db.php");

$limite = 7; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limite;

try {
    $total_query = $connexion->query("SELECT COUNT(*) FROM livre");
    $total_livres = $total_query->fetchColumn();
    $total_pages = ceil($total_livres / $limite);

    $sql = "SELECT * FROM livre LIMIT :limite OFFSET :offset";
    $stmt = $connexion->prepare($sql);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $livres = $stmt->fetchAll(PDO::FETCH_OBJ);

} catch (Exception $e) {
    die("Erreur lors de la récupération des livres : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Livres - BiblioDrive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php require 'header.php'?>
<div class="container mt-5">
    <h1 class="mb-4">Tous les livres</h1>
    
    <div class="table-responsive bg-white shadow-sm p-3 rounded">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Titre</th>
                    <th>Année</th>
                    <th>ISBN</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($livres) > 0): ?>
                    <?php foreach ($livres as $livre): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($livre->titre); ?></td>
                            <td><?php echo htmlspecialchars($livre->anneeparution); ?></td>
                            <td><?php echo htmlspecialchars($livre->isbn13); ?></td>
                            <td class="text-center">
                                <a href="./details.php?id_livre=<?php echo $livre->nolivre; ?>" class="btn btn-sm btn-primary">
                                    Détails
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Aucun livre trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($total_pages > 1): ?>
    <nav aria-label="Navigation des pages" class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>">Précédent</a>
            </li>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if($page == $i) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?php if($page >= $total_pages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Suivant</a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>

    <div class="text-center mt-3">
        <a href="../accueuil.php" class="btn btn-secondary">Retour à l'accueil</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>