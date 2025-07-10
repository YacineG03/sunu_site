 
<?php
require_once dirname(__DIR__) . '\modele\dao\UtilisateurDao.php';
require_once dirname(__DIR__) . '\modele\dao\jetonDao.php';

class UserService
{
    private $utilisateurDao;
    private $jetonDao;

    public function __construct()
    {
        $this->utilisateurDao = new UtilisateurDao();
        $this->jetonDao = new jetonDao();
    }

    // Service pour récupérer la liste des utilisateurs en vérifiant le jeton
    public function listUsers($jeton)
    {
        $this->authenticate($jeton);
        $users = $this->utilisateurDao->getAllUtilisateurs();
        return $users;
    }

    // Service pour ajouter un utilisateur en vérifiant le jeton
    public function addUser($jeton, $login, $mot_de_passe, $role)
    {
        $this->authenticate($jeton);
        return $this->utilisateurDao->createUtilisateur($login, $mot_de_passe, $role);
    }

    // Service pour supprimer un utilisateur en vérifiant le jeton
    public function deleteUser($jeton, $id)
    {
        $this->authenticate($jeton);
        return $this->utilisateurDao->deleteUtilisateur($id);
    }

    // Service pour mettre à jour un utilisateur en vérifiant le jeton
    public function updateUser($jeton, $id, $login, $mot_de_passe, $role)
    {
        $this->authenticate($jeton);
        return $this->utilisateurDao->updateUtilisateur($id, $login, $mot_de_passe, $role);
    }

    // Service pour authentifier un utilisateur
    public function authenticateUser($login, $mot_de_passe)
    {
        return $this->utilisateurDao->verifyUser($login, $mot_de_passe);
    }

    // Service pour verifier le jeton pour un utilisateur pour qu'il accèdé aux ressources protégées
    private function authenticate($jeton)
    {
        if (!$this->jetonDao->validatejeton($jeton)) {
            throw new Exception('jeton invalide ou expiré');
        }
    }
}
