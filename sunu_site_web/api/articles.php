<?php
// Définir le type de contenu par défaut
header('Content-Type: application/json');

require_once dirname(__DIR__) . '/modele/dao/ArticleDao.php';
require_once dirname(__DIR__) . '/service/ArticleService.php';

// Traitement des requêtes
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Initialisation du service
    $articleService = new ArticleService();

    // Récupérer les paramètres
    $format = isset($_GET['format']) ? $_GET['format'] : 'json';
    $categorie = isset($_GET['categorie']) ? $_GET['categorie'] : null;
    $groupByCategories = isset($_GET['groupByCategories']) ? $_GET['groupByCategories'] : false;

    // Gestion des actions
    if ($groupByCategories) {
        // Récupérer les articles groupés par catégories
        echo $articleService->getArticlesGroupedByCategories($format);
    } elseif (!empty($categorie)) {
        // Récupérer les articles par catégorie spécifique
        echo $articleService->getArticlesByCategory($format, $categorie);
    } else {
        // Récupérer tous les articles
        echo $articleService->getAllArticles($format);
    }
} else {
    // Gérer les autres méthodes HTTP (POST, PUT, DELETE) si nécessaire
    http_response_code(405); // Méthode non autorisée
    echo json_encode(array('message' => 'Méthode non autorisée'));
}
