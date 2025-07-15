<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunu Site</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style/entete.css">
</head>
<body>
    <header>
        <nav class="navbar custom-nav" aria-label="Navigation principale">
            <div class="nav-container">
                <a class="nav-brand" href="<?= BASE_URL ?>/index.php">
                    <img src="./assets/logo.png" alt="Logo ESP Dakar" class="logo">
                    <span class="brand-text">Sunu Site</span>
                </a>
                
                <button class="navbar-toggler" type="button" aria-controls="navContent" 
                        aria-expanded="false" aria-label="Toggle navigation" onclick="toggleNav()">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="nav-content" id="navContent">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/index.php" 
                               aria-current="<?= ($current_page ?? '') === 'home' ? 'page' : '' ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                                </svg>
                                Accueil
                            </a>
                        </li>
                        
                        <li class="nav-item categories-dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" 
                               aria-haspopup="true" aria-expanded="false" id="categoriesDropdown">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>
                                </svg>
                                Catégories
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M7 10l5 5 5-5z"/>
                                </svg>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/index.php">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        Tous
                                    </a>
                                </li>
                                <?php if (isset($categories) && is_array($categories)) : ?>
                                    <?php foreach ($categories as $categorie) : ?>
                                        <li>
                                            <a class="dropdown-item" 
                                               href="<?= BASE_URL ?>/index.php?action=categorie&categorie=<?= urlencode($categorie->id ?? '') ?>">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                </svg>
                                                <?= htmlspecialchars($categorie->libelle ?? '') ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                        
                        <?php if (isset($_SESSION['utilisateur_id']) && $_SESSION['role'] === 'admin') : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>/index.php?action=utilisateurs">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.54 8H17c-.8 0-1.54.37-2.01.99l-2.54 7.63H14.5V22h2zm-7.5 0v-10.5c0-1.1-.9-2-2-2s-2 .9-2 2V22h4z"/>
                                    </svg>
                                    Utilisateurs
                                </a>
                            </li>
                        <?php endif; ?>
                        
                    </ul>
                    
                   
                    
                    <!-- User Authentication -->
                    <?php if (isset($_SESSION['utilisateur_id'])) : ?>
                        <div class="nav-item user-dropdown">
                            <button class="nav-link dropdown-toggle" 
                                    id="userDropdown" 
                                    aria-haspopup="true" 
                                    aria-expanded="false">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                </svg>
                                <?= htmlspecialchars($_SESSION['role'] ?? 'Utilisateur') ?>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M7 10l5 5 5-5z"/>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/index.php?action=profil">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                        </svg>
                                        Mon Profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/index.php?action=logout">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                                        </svg>
                                        Déconnexion
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <div class="nav-item">
                            <a href="<?= BASE_URL ?>/index.php?action=connexion" 
                               class="nav-link btn btn-success">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zM20 19h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z"/>
                                </svg>
                                Se connecter
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    
    <script>
        function toggleNav() {
            const navContent = document.getElementById('navContent');
            const toggler = document.querySelector('.navbar-toggler');
            
            if (navContent.classList.contains('active')) {
                navContent.classList.remove('active');
                toggler.setAttribute('aria-expanded', 'false');
            } else {
                navContent.classList.add('active');
                toggler.setAttribute('aria-expanded', 'true');
            }
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const navContent = document.getElementById('navContent');
            const toggler = document.querySelector('.navbar-toggler');
            
            if (!event.target.closest('.nav-container') && navContent.classList.contains('active')) {
                navContent.classList.remove('active');
                toggler.setAttribute('aria-expanded', 'false');
            }
        });
        
        // Handle search functionality
        document.querySelector('.search-btn').addEventListener('click', function() {
            const searchInput = document.querySelector('.search-input');
            const searchTerm = searchInput.value.trim();
            
            if (searchTerm) {
                window.location.href = `<?= BASE_URL ?>/index.php?action=recherche&q=${encodeURIComponent(searchTerm)}`;
            }
        });
        
        document.querySelector('.search-input').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                document.querySelector('.search-btn').click();
            }
        });
    </script>
</body>
</html>