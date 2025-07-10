<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
    <?php require_once 'inc/entete.php'; ?>
    <div class="container p-5">
        <h1 class="text-center">Articles de la catégorie <?= $categorie->libelle ?></h1>
        <!-- Si l'utilisateur est un éditeur ou un administrateur, il peut créer un article -->
        <?php
        if (isset($_SESSION['role']) && ($_SESSION['role'] == 'editeur' || $_SESSION['role'] == 'admin')) : ?>
            <a href="<?= BASE_URL ?>/index.php?action=createArticle" class="btn btn-primary">Créer un article</a>
        <?php endif; ?>

        <!-- Affichage des articles par categorie -->
        <?php
        require_once 'config.php';
        foreach ($articles as $article) : ?>
            <div class="card border-dark my-3">
                <div class="card-body">
                    <h2 class="card-title"><a href="<?= BASE_URL ?>/index.php?action=article&id=<?= $article['id'] ?>"><?= $article['titre'] ?></a></h2>
                    <p><?= date("d/m/Y", strtotime($article['dateCreation'])) ?></p>
                    <p class="card-text"><?= $article['description'] ?></p>

                    <!-- Si l'utilisateur est un éditeur ou un administrateur, il peut modifier ou supprimer un article -->
                    <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'editeur' || $_SESSION['role'] == 'admin')) : ?>
                        <h3>
                            <a href="<?= BASE_URL ?>/index.php?action=deletearticle&id=<?= $article['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');"><i class="bi bi-trash"></i></a>
                            <a href="<?= BASE_URL ?>/index.php?action=editarticle&id=<?= $article['id'] ?>"><i class="bi bi-pencil-square"></i></a>
                        </h3>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach ?>
        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item <?= ($numeroPage == 1) ? "disabled" : "" ?>">
                    <a href="<?= BASE_URL ?>/index.php?page=<?= $numeroPage - 1 ?>" class="page-link">Précédente</a>
                </li>
                <?php for ($page = 1; $page <= $pages; $page++) : ?>
                    <li class="page-item <?= ($numeroPage == $page) ? "active" : "" ?>">
                        <a href="<?= BASE_URL ?>/index.php?page=<?= $page ?>" class="page-link"><?= $page ?></a>
                    </li>
                <?php endfor ?>
                <li class="page-item <?= ($numeroPage == $pages) ? "disabled" : "" ?>">
                    <a href="<?= BASE_URL ?>/index.php?page=<?= $numeroPage + 1 ?>" class="page-link">Suivante</a>
                </li>
            </ul>
        </nav>

    </div>
</body>

</html>