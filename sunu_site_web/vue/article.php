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
    <title><?= htmlspecialchars($article['titre']) ?> - ESP Dakar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style/entete.css">
    <link rel="stylesheet" href="./style/index.css">
</head>

<body>
    <?php require_once 'inc/entete.php'; ?>
    
    <main class="main-container">
        <article class="content-section">
            <div class="article-navigation">
                <a href="<?= BASE_URL ?>/index.php" class="create-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                    </svg>
                    Retour aux actualités
                </a>
            </div>
            
            <div class="article-card">
                <h1 class="article-title">
                    <?= htmlspecialchars($article['titre']) ?>
                </h1>
                
                <div class="article-meta">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7V7z"/>
                    </svg>
                    Publié le <?= date("d/m/Y", strtotime($article['dateCreation'])) ?>
                    
                    <?php if (!empty($article['auteur'])) : ?>
                        <span class="article-author">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            <?= htmlspecialchars($article['auteur']) ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="article-content">
                    <?= $article['contenu'] ?>
                </div>

                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'editeur' || $_SESSION['role'] == 'admin')) : ?>
                    <div class="article-actions">
                        <a href="<?= BASE_URL ?>/index.php?action=editarticle&id=<?= $article['id'] ?>" 
                           class="action-btn edit-btn"
                           title="Modifier l'article">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                            Modifier
                        </a>
                        <a href="<?= BASE_URL ?>/index.php?action=deletearticle&id=<?= $article['id'] ?>" 
                           class="action-btn delete-btn"
                           title="Supprimer l'article"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                            </svg>
                            Supprimer
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </article>

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
                       class="category-link <?= (isset($_GET['categorie']) && $_GET['categorie'] == $categorie->id) ? 'active' : '' ?>">
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