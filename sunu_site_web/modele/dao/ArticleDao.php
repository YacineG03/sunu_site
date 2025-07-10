 
<?php
class ArticleDao
{
    private $connexionManager;

    public function __construct()
    {
        $this->connexionManager = new ConnexionManager();
    }
    // Méthode pour récupérer un article par son id
    public function getArticleById($id)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT * FROM article WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->connexionManager->disconnect();
        return $article;
    }

    // Méthode pour récupérer tous les articles
    public function getAllArticles()
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT * FROM article');
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // $nbArticles = (int) $articles['nb_articles'];
        $this->connexionManager->disconnect();
        return $articles;
    }

    // Méthode pour récupérer les articles par page
    public function getArticlesByPage($page, $nbArticlesParPage)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT * FROM article ORDER BY dateCreation DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $nbArticlesParPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * $nbArticlesParPage, PDO::PARAM_INT);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->connexionManager->disconnect();
        return $articles;
    }

    // Méthode pour récupérer le nombre total d'articles
    public function getTotalArticlesCount()
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT COUNT(*) as nb_articles FROM article');
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC); // Utilisez fetch() avec PDO::FETCH_ASSOC pour récupérer un tableau associatif
        $nbArticles = (int) $article['nb_articles'];
        $this->connexionManager->disconnect();
        return $nbArticles; // Retournez directement le nombre d'articles
    }

    // Méthode pour créer un article dans la base de données
    public function createArticle($article)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('INSERT INTO article (titre, contenu, categorie,description) VALUES (:titre,:contenu,:categorie,:description)');
        $stmt->execute([
            'titre' => $article['titre'],
            'contenu' => $article['contenu'],
            'categorie' => $article['categorie'],
            'description' => $article['description']
        ]);
        $this->connexionManager->disconnect();
    }

    // Méthode pour mettre à jour un article dans la base de données
    public function updateArticle($article)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('UPDATE article SET titre = :titre, contenu = :contenu, categorie = :categorie, description = :description WHERE id = :id');
        $stmt->execute([
            'id' => $article['id'],
            'titre' => $article['titre'],
            'contenu' => $article['contenu'],
            'categorie' => $article['categorie'],
            'description' => $article['description']
        ]);
        $this->connexionManager->disconnect();
    }

    // Méthode pour supprimer un article de la base de données
    public function deleteArticle($id)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('DELETE FROM article WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $this->connexionManager->disconnect();
    }

    // Méthode pour récupérer les articles par catégorie
    public function getArticlesByCategorie($categorie)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT * FROM article WHERE categorie = :categorie ORDER BY dateCreation DESC');
        $stmt->execute(['categorie' => $categorie]);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->connexionManager->disconnect();
        return $articles;
    }

    // Méthode pour récupérer les articles groupés par catégories
    public function getArticlesGroupedByCategories()
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT a.id, a.titre, a.description, a.contenu, a.dateCreation, a.dateModification, c.libelle as categorie FROM article a JOIN categorie c ON a.categorie_id = c.id');
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->connexionManager->disconnect();

        $articlesByCategories = [];
        foreach ($articles as $article) {
            $categorie = $article['categorie'];
            if (!isset($articlesByCategories[$categorie])) {
                $articlesByCategories[$categorie] = [];
            }
            $articlesByCategories[$categorie][] = $article;
        }

        return $articlesByCategories;
    }
}
