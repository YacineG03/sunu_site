<?php
require_once __DIR__ . '/ConnexionManager.php';


class UtilisateurDao
{
    private $connexionManager;

    public function __construct()
    {
        $this->connexionManager = new ConnexionManager();
    }

    // Méthode pour récupérer tous les utilisateurs de la base de données
    public function getAllUtilisateurs()
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT utilisateur.id, utilisateur.login, utilisateur.role, jeton.token FROM utilisateur LEFT JOIN jeton ON utilisateur.id = jeton.utilisateur_id');
        $stmt->execute();
        $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->connexionManager->disconnect();
        return $utilisateurs;
    }

    // Méthode pour récupérer un utilisateur par son identifiant
    public function getUtilisateurById($id)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT * FROM utilisateur WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->connexionManager->disconnect();
        return $utilisateur;
    }

    // Méthode pour récupérer un utilisateur par son nom d'utilisateur
    public function getUtilisateurBylogin($login)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT * FROM utilisateur WHERE login = :login');
        $stmt->execute(['login' => $login]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->connexionManager->disconnect();
        return $utilisateur;
    }

    // Méthode pour vérifier si un utilisateur existe dans la base de données
    public function verifyUser($login, $mot_de_passe)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT utilisateur.*, jeton.token FROM utilisateur LEFT JOIN jeton ON utilisateur.id = jeton.utilisateur_id WHERE utilisateur.login = :login');
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            $this->connexionManager->disconnect();
            return false;
        }
        
        $isValid = password_verify($mot_de_passe, $user['mot_de_passe']);
        
        $this->connexionManager->disconnect();
        
        if ($isValid) {
            return $user;
        }
        
        return false;
    }
    public function createUtilisateur($utilisateur)
    {
        $connexion = $this->connexionManager->connect();
        // Hachage du mot de passe
        $hashedPassword = password_hash($utilisateur['mot_de_passe'], PASSWORD_DEFAULT);
        $stmt = $connexion->prepare('INSERT INTO utilisateur (login, mot_de_passe, role) VALUES (:login, :mot_de_passe, :role)');
        $stmt->execute([
            'login' => $utilisateur['login'],
            'mot_de_passe' => $hashedPassword,
            'role' => $utilisateur['role']
        ]);
        $this->connexionManager->disconnect();
    }

    // Méthode pour mettre à jour un utilisateur dans la base de données
    public function updateUtilisateur($utilisateur)
    {
        $connexion = $this->connexionManager->connect();
        // Hachage du mot de passe seulement si un nouveau mot de passe est fourni
        if (!empty($utilisateur['mot_de_passe'])) {
            $hashedPassword = password_hash($utilisateur['mot_de_passe'], PASSWORD_DEFAULT);
        } else {
            // Récupérez le mot de passe actuel de la base de données si aucun nouveau mot de passe n'est fourni
            $stmt = $connexion->prepare('SELECT mot_de_passe FROM utilisateur WHERE id = :id');
            $stmt->execute(['id' => $utilisateur['id']]);
            $hashedPassword = $stmt->fetchColumn();
        }
        $stmt = $connexion->prepare('UPDATE utilisateur SET login = :login, mot_de_passe = :mot_de_passe, role = :role WHERE id = :id');
        $stmt->execute([
            'id' => $utilisateur['id'],
            'login' => $utilisateur['login'],
            'mot_de_passe' => $hashedPassword,
            'role' => $utilisateur['role']
        ]);
        $this->connexionManager->disconnect();
    }

    // Méthode pour supprimer un utilisateur de la base de données
    public function deleteUtilisateur($id)
    {
        $connexion = $this->connexionManager->connect();
        
        try {
            // Démarrer une transaction pour garantir l'intégrité
            $connexion->beginTransaction();

            // Supprimer d'abord les jetons liés à cet utilisateur
            $stmtJeton = $connexion->prepare('DELETE FROM jeton WHERE utilisateur_id = :id');
            $stmtJeton->execute(['id' => $id]);

            // Puis supprimer l'utilisateur
            $stmtUtilisateur = $connexion->prepare('DELETE FROM utilisateur WHERE id = :id');
            $stmtUtilisateur->execute(['id' => $id]);

            // Valider la transaction
            $connexion->commit();
        } catch (PDOException $e) {
            // Annuler en cas d'erreur
            $connexion->rollBack();
            throw $e;
        }

        $this->connexionManager->disconnect();
    }

}
