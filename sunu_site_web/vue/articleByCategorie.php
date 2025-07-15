<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Articles - <?= htmlspecialchars($categorie->libelle ?? '') ?></title>
    <link rel="stylesheet" href="./style/index.css">
    <link rel="stylesheet" href="./style/entete.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body>

<?php require_once 'inc/entete.php'; ?>

<div class="main-container">

    <!-- Section principale des articles -->
    <section class="content-section">
        <h1 class="page-title">Articles de la catégorie <?= htmlspecialchars($categorie->libelle ?? 'Catégorie inconnue') ?></h1>

        <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['editeur', 'admin'])) : ?>
            <a href="<?= BASE_URL ?>/index.php?action=createArticle" class="create-btn">
                <i class="bi bi-plus-circle"></i> Créer un article
            </a>
        <?php endif; ?>

        <!-- Affichage des articles -->
        <?php if (!empty($articles)) : ?>
            <?php foreach ($articles as $article) : ?>
                <div class="article-card">
                    <h2 class="article-title">
                        <a href="<?= BASE_URL ?>/index.php?action=article&id=<?= $article['id'] ?? '' ?>">
                            <?= htmlspecialchars($article['titre'] ?? 'Titre non disponible') ?>
                        </a>
                    </h2>
                    <div class="article-meta">
                        <i class="bi bi-calendar"></i>
                        <?= date("d/m/Y", strtotime($article['dateCreation'] ?? 'now')) ?>
                    </div>
                    <p class="article-description"><?= htmlspecialchars($article['description'] ?? 'Description non disponible') ?></p>

                    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['editeur', 'admin'])) : ?>
                        <div class="article-actions">
                            <a href="<?= BASE_URL ?>/index.php?action=editarticle&id=<?= $article['id'] ?? '' ?>" class="action-btn edit-btn" title="Modifier">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="<?= BASE_URL ?>/index.php?action=deletearticle&id=<?= $article['id'] ?? '' ?>"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');"
                               class="action-btn delete-btn" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="empty-state">
                <div class="empty-state-icon">😕</div>
                Aucun article trouvé dans cette catégorie.
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if (isset($pages) && $pages > 1) : ?>
            <div class="pagination">
                <a href="<?= BASE_URL ?>/index.php?action=categorie&categorie=<?= $categorie->id ?? '' ?>&page=<?= max(1, ($numeroPage ?? 1) - 1) ?>" 
                   class="page-link <?= (($numeroPage ?? 1) == 1) ? 'disabled' : '' ?>">Précédente</a>
                <?php for ($page = 1; $page <= $pages; $page++) : ?>
                    <a href="<?= BASE_URL ?>/index.php?action=categorie&categorie=<?= $categorie->id ?? '' ?>&page=<?= $page ?>"
                       class="page-link <?= (($numeroPage ?? 1) == $page) ? 'active' : '' ?>">
                        <?= $page ?>
                    </a>
                <?php endfor; ?>
                <a href="<?= BASE_URL ?>/index.php?action=categorie&categorie=<?= $categorie->id ?? '' ?>&page=<?= min($pages, ($numeroPage ?? 1) + 1) ?>" 
                   class="page-link <?= (($numeroPage ?? 1) == $pages) ? 'disabled' : '' ?>">Suivante</a>
            </div>
        <?php endif; ?>
    </section>

    <!-- Barre latérale des catégories -->
    <aside class="sidebar">
        <h2 class="sidebar-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            Catégories
        </h2>

        <ul class="category-list">
            <li class="category-item">
                <a href="<?= BASE_URL ?>/index.php" class="category-link <?= !isset($_GET['categorie']) ? 'active' : '' ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    Tous
                </a>
            </li>
            <?php if (isset($categories) && is_array($categories)) : ?>
                <?php foreach ($categories as $cat) : ?>
                    <li class="category-item">
                        <a href="<?= BASE_URL ?>/index.php?action=categorie&categorie=<?= urlencode($cat->id ?? '') ?>" 
                           class="category-link <?= (isset($_GET['categorie']) && $_GET['categorie'] == ($cat->id ?? '')) ? 'active' : '' ?>">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            <?= htmlspecialchars($cat->libelle ?? 'Catégorie sans nom') ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else : ?>
                <li class="category-item">
                    <p>Aucune catégorie disponible</p>
                </li>
            <?php endif; ?>
        </ul>
    </aside>
</div>

</body>
</html>