<?php
require_once dirname(__DIR__) . '\modele\dao\ConnexionManager.php';
require_once dirname(__DIR__) . '\modele\dao\ArticleDao.php';
require_once dirname(__DIR__) . '\modele\dao\CategorieDao.php';

class Controleur
{
    private $articleDao;
    private $categorieDao;

    public function __construct()
    {
        $connexionManager = new ConnexionManager();

        $this->articleDao = new ArticleDao($connexionManager);
        $this->categorieDao = new CategorieDao($connexionManager);
    }
    // Méthode pour afficher la page d'accueil
    public function showAccueil($pageCourante)
    {
        $numeroPage = $pageCourante;
        $nombreArticlesParPage = 2; // Définissez le nombre d'articles que vous souhaitez par page
        $totalArticles = $this->articleDao->getTotalArticlesCount(); // Obtenez le nombre total d'articles
        $pages = ceil($totalArticles / $nombreArticlesParPage); // Calculez le nombre total de pages

        $articles = $this->articleDao->getArticlesByPage($numeroPage, $nombreArticlesParPage);
        $categories = $this->categorieDao->getAllCategories();
        require_once dirname(__DIR__) . '\vue\accueil.php';
    }

    // Méthode pour recuperer les articles pour une catégorie donnée
    public function getArticlesByCategorie($categorie)
    {
        return $this->articleDao->getArticlesByCategorie($categorie);
    }

    // Méthode pour afficher un article
    public function showArticle($id)
    {
        $article = $this->articleDao->getArticleById($id);
        $categories = $this->categorieDao->getAllCategories();
        require_once dirname(__DIR__) . '\vue\article.php';
    }

    // Méthode pour afficher les articles pour une catégorie donnée
    public function showArticleByCategorie($id)
    {
        $categorie = $this->categorieDao->getCategorieById($id);
        $articles = $this->articleDao->getArticlesByCategorie($id);
        $categories = $this->categorieDao->getAllCategories();
        require_once dirname(__DIR__) . '\vue\articleByCategorie.php';
    }

    // Méthode pour afficher le formulaire de création d'article
    public function showCreateArticle()
    {
        if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'editeur' && $_SESSION['role'] != 'admin')) {
            header('Location: index.php?action=showConnexion&error=Unauthorized');
            exit();
        }
        $categories = $this->categorieDao->getAllCategories();
        require_once dirname(__DIR__) . '\vue\editArticle.php';
    }

    // Méthode pour afficher le formulaire de modification d'article
    public function showEditArticle($id)
    {
        if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'editeur' && $_SESSION['role'] != 'admin')) {
            header('Location: index.php?action=showConnexion&error=Unauthorized');
            exit();
        }
        $article = $this->articleDao->getArticleById($id);
        $categories = $this->categorieDao->getAllCategories();
        require_once dirname(__DIR__) . '\vue\editArticle.php';
    }

    // Méthode pour créer un article
    public function createArticle($article)
    {
        if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'editeur' && $_SESSION['role'] != 'admin')) {
            header('Location: index.php?action=showConnexion&error=Unauthorized');
            exit();
        }
        $this->articleDao->createArticle($article);
        header('Location: index.php');
    }

    // Méthode pour modifier un article
    public function updateArticle($article)
    {
        if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'editeur' && $_SESSION['role'] != 'admin')) {
            header('Location: index.php?action=showConnexion&error=Unauthorized');
            exit();
        }
        $this->articleDao->updateArticle($article);
        header('Location: index.php');
        exit();
    }

    // Méthode pour supprimer un article
    public function deleteArticle($id)
    {
        if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'editeur' && $_SESSION['role'] != 'admin')) {
            header('Location: index.php?action=showConnexion&error=Unauthorized');
            exit();
        }
        $this->articleDao->deleteArticle($id);
        header('Location: index.php');
        exit();
    }
}
