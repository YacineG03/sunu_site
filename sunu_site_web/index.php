<?php
require __DIR__ . '/controleur/Controleur.php';
require __DIR__ . '/controleur/UtilisateurControleur.php';
require __DIR__ . '/controleur/ConnexionControleur.php';

//Initialisation des controleurs
$controleur = new Controleur();
$conControleur = new ConnexionControleur();
$userControleur = new UtilisateurControleur();

// Récupérer le numero de la page courante pour gérer la pagination
$pageCourante = isset($_GET['page']) && !empty($_GET['page']) ? (int) strip_tags($_GET['page']) : 1;

$action = isset($_GET['action']) ? strtolower($_GET['action']) : null;
switch ($action) {

        //Afficher un article
    case 'article':
        if (!empty($_GET['id'])) {
            $controleur->showArticle($_GET['id']);
        } else {
            echo "erreur : id non défini";
        }
        break;

        //Afficher les articles par catégorie
    case 'categorie':
        if (!empty($_GET['categorie'])) {
            $categorieId = intval($_GET['categorie']);
            $controleur->showArticleByCategorie($categorieId);
        } else {
            echo "erreur : categorie non défini";
        }
        break;

        //Afficher la page de connexion
    case 'connexion':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'];
            $mot_de_passe = $_POST['mot_de_passe'];
            $conControleur->login($login, $mot_de_passe);
        } else {
            $conControleur->showConnexion();
        }
        break;

        //
    case 'home':
        $controleur->showAccueil($pageCourante);
        break;
    case 'logout':
        $conControleur->logout();
        break;
    case 'createarticle':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controleur->createArticle($_POST);
        } else {
            $controleur->showCreateArticle();
        }
        break;
    case 'deletearticle':
        if (!empty($_GET['id'])) {
            $controleur->deleteArticle($_GET['id']);
        } else {
            echo "erreur : id non défini";
        }
        break;
    case 'editarticle':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article = [
                'id' => $_GET['id'],
                'titre' => $_POST['titre'],
                'contenu' => $_POST['contenu'],
                'categorie' => $_POST['categorie'],
                'description' => $_POST['description']
            ];
            $controleur->updateArticle($article);
        } else {
            if (!empty($_GET['id'])) {
                $controleur->showEditArticle($_GET['id']);
            } else {
                echo "erreur : id non défini";
            }
        }
        break;
    case 'utilisateurs':
        $userControleur->showUtilisateurs();
        break;
    case 'createutilisateur':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userControleur->createUtilisateur($_POST);
        } else {
            $userControleur->showCreateUtilisateur();
        }
        break;
    case 'editutilisateur':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $utilisateur = [
                'id' => $_GET['id'],
                'login' => $_POST['login'],
                'mot_de_passe' => $_POST['mot_de_passe'],
                'role' => $_POST['role']
            ];
            $userControleur->updateUtilisateur($utilisateur);
        } else {
            if (!empty($_GET['id'])) {
                $userControleur->showEditUtilisateur($_GET['id']);
            } else {
                echo "erreur : id non défini";
            }
        }
        break;
    case 'deleteutilisateur':
        if (!empty($_GET['id'])) {
            $userControleur->deleteUtilisateur($_GET['id']);
        } else {
            echo "erreur : id non défini";
        }
    case 'generatejeton':
        if (!empty($_GET['id'])) {
            $userControleur->generatejeton($_GET['id']);
        } else {
            echo "erreur : id non défini";
        }
        break;
    case 'soapclient':
        require_once __DIR__ . '\soap_client.php';
        break;
    case 'api':
        require __DIR__ . '\api\articles.php';
        break;
    default:
        $controleur->showAccueil($pageCourante);
        break;
}
