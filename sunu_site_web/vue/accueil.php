<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunu Site - Actualités</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style/entete.css">
    <link rel="stylesheet" href="./style/index.css">
</head>

<body>
    <?php require_once 'inc/entete.php'; ?>
    
    <main class="main-container">
        <section class="content-section">
            <h1 class="page-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
                Actualités
            </h1>

            <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'editeur' || $_SESSION['role'] == 'admin')) : ?>
                <a href="<?= BASE_URL ?>/index.php?action=createArticle" class="create-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                    Créer un article
                </a>
            <?php endif; ?>

            <?php if (isset($articles) && !empty($articles)) : ?>
                <?php foreach ($articles as $article) : ?>
                    <article class="article-card animate-fadeIn">
                        <h2 class="article-title">
                            <a href="<?= BASE_URL ?>/index.php?action=article&id=<?= $article['id'] ?>">
                                <?= htmlspecialchars($article['titre'] ?? '') ?>
                            </a>
                        </h2>
                        
                        <div class="article-meta">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                            </svg>
                            Publié le <?= date("d/m/Y", strtotime($article['dateCreation'] ?? 'now')) ?>
                        </div>
                        
                        <p class="article-description">
                            <?= htmlspecialchars($article['description'] ?? '') ?>
                        </p>

                        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'editeur' || $_SESSION['role'] == 'admin')) : ?>
                            <div class="article-actions">
                                <a href="<?= BASE_URL ?>/index.php?action=editarticle&id=<?= $article['id'] ?>" 
                                   class="action-btn edit-btn" 
                                   title="Modifier l'article">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                    </svg>
                                </a>
                                <a href="<?= BASE_URL ?>/index.php?action=deletearticle&id=<?= $article['id'] ?>" 
                                   class="action-btn delete-btn" 
                                   title="Supprimer l'article"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📰</div>
                    <h3>Aucun article disponible</h3>
                    <p>Il n'y a pas encore d'articles publiés.</p>
                </div>
            <?php endif; ?>

            <?php if (isset($pages) && $pages > 1) : ?>
                <nav class="pagination" aria-label="Navigation des pages">
                    <a href="<?= BASE_URL ?>/index.php?page=<?= max(1, ($numeroPage ?? 1) - 1) ?>" 
                       class="page-link <?= ($numeroPage ?? 1) == 1 ? 'disabled' : '' ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                        </svg>
                        Précédente
                    </a>
                    
                    <?php for ($page = 1; $page <= $pages; $page++) : ?>
                        <a href="<?= BASE_URL ?>/index.php?page=<?= $page ?>" 
                           class="page-link <?= ($numeroPage ?? 1) == $page ? 'active' : '' ?>">
                            <?= $page ?>
                        </a>
                    <?php endfor; ?>
                    
                    <a href="<?= BASE_URL ?>/index.php?page=<?= min($pages, ($numeroPage ?? 1) + 1) ?>" 
                       class="page-link <?= ($numeroPage ?? 1) == $pages ? 'disabled' : '' ?>">
                        Suivante
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                        </svg>
                    </a>
                </nav>
            <?php endif; ?>
        </section>

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
                    <?php foreach ($categories as $categorie) : ?>
                        <li class="category-item">
                            <a href="<?= BASE_URL ?>/index.php?action=categorie&categorie=<?= urlencode($categorie->id ?? '') ?>" 
                               class="category-link <?= (isset($_GET['categorie']) && $_GET['categorie'] == ($categorie->id ?? '')) ? 'active' : '' ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                                <?= htmlspecialchars($categorie->libelle ?? '') ?>
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
    </main>
</body>
</html>