 
<?php
require_once dirname(__DIR__) . '\modele\dao\UtilisateurDao.php';
require_once dirname(__DIR__) . '\modele\dao\jetonDao.php';
require_once dirname(__DIR__) . '\modele\dao\CategorieDao.php';

class UtilisateurControleur
{
    private $utilisateurDao;
    private $categorieDao;
    private $jetonDao;

    public function __construct()
    {
        $this->utilisateurDao = new UtilisateurDao();
        $this->categorieDao = new CategorieDao();
        $this->jetonDao = new jetonDao();
    }

    // Méthode pour afficher la liste des utilisateurs
    public function showUtilisateurs()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?action=connexion&error=Unauthorized');
            exit();
        }
        $categories = $this->categorieDao->getAllCategories();
        $utilisateurs = $this->utilisateurDao->getAllUtilisateurs();
        require_once dirname(__DIR__) . '\vue\utilisateurs.php';
    }

    // Méthode pour afficher le formulaire de création d'un utilisateur
    public function showCreateUtilisateur()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?action=connexion&error=Unauthorized');
            exit();
        }
        $categories = $this->categorieDao->getAllCategories();
        require_once dirname(__DIR__) . '\vue\editUtilisateur.php';
    }

    // Méthode pour afficher le formulaire de modification d'un utilisateur
    public function showEditUtilisateur($id)
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?action=connexion&error=Unauthorized');
            exit();
        }
        $utilisateur = $this->utilisateurDao->getUtilisateurById($id);
        require_once dirname(__DIR__) . '\vue\editUtilisateur.php';
    }

    //Méthode pour creer un utilisateur
    public function createUtilisateur($utilisateur)
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?action=connexion&error=Unauthorized');
            exit();
        }
        $this->utilisateurDao->createUtilisateur($utilisateur);
        header('Location: index.php?action=utilisateurs');
    }

    //Méthode pour modifier un utilisateur
    public function updateUtilisateur($utilisateur)
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?action=connexion&error=Unauthorized');
            exit();
        }
        $this->utilisateurDao->updateUtilisateur($utilisateur);
        header('Location: index.php?action=utilisateurs');
    }

    //Méthode pour supprimer un utilisateur
    public function deleteUtilisateur($id)
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?action=connexion&error=Unauthorized');
            exit();
        }
        $this->utilisateurDao->deleteUtilisateur($id);
        header('Location: index.php?action=utilisateurs');
    }

    // Méthode pour générer un jeton pour un utilisateur 
    public function generatejeton($user_id)
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?action=connexion&error=Unauthorized');
            exit();
        }
        $jeton = $this->jetonDao->createJeton($user_id);
        header('Location: index.php?action=utilisateurs');
    }

    // Méthode pour gérer la connexion
    public function login($login, $mot_de_passe)
    {
        $user = $this->utilisateurDao->verifyUser($login, $mot_de_passe);
        if ($user) {
            $_SESSION['utilisateur_id'] = $user['id'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php?action=home');
        } else {
            header('Location: index.php?action=connexion&error=Invalid credentials');
        }
    }

    // Méthode pour gérer la déconnexion
    public function logout()
    {
        session_destroy();
        header('Location: index.php');
    }
}
