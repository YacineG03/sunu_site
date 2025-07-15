<?php
// Active l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifie la session
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: " . BASE_URL . "/index.php?action=connexion");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Entête inchangé -->
</head>
<body>
    <?php require_once dirname(__DIR__) . '/vue/inc/entete.php'; ?>
    
    <main class="main-container">
        <div class="profile-container">
            <h1>Profil de <?= htmlspecialchars($utilisateur->login ?? '') ?></h1>
            <div>
                <p><strong>Rôle:</strong> <?= htmlspecialchars($utilisateur->role ?? '') ?></p>
                <?php if (isset($utilisateur->date_inscription)): ?>
                    <p><strong>Membre depuis:</strong> <?= date('d/m/Y', strtotime($utilisateur->date_inscription)) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>