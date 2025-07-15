<?php
require_once __DIR__ . '/ConnexionManager.php';
class jetonDao
{
    private $connexionManager;

    public function __construct()
    {
        $this->connexionManager = new ConnexionManager();
    }

    // Méthode pour générer un jeton
    public function createJeton($utilisateur_id)
    {
        $jeton = bin2hex(random_bytes(16));
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('INSERT INTO jeton (token, utilisateur_id) VALUES (:jeton, :utilisateur_id)');
        $stmt->execute(['jeton' => $jeton, 'utilisateur_id' => $utilisateur_id]);
        $this->connexionManager->disconnect();
        return $jeton;
    }

    // Méthode pour récupérer un jeton pour un utilisateur
    public function getJetonByUserId($utilisateur_id)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT * FROM jeton WHERE utilisateur_id = :utilisateur_id');
        $stmt->execute(['utilisateur_id' => $utilisateur_id]);
        $jeton = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->connexionManager->disconnect();
        return $jeton;
    }

    // Méthode pour vérifier si un jeton est valide
    public function validateJeton($jeton)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT * FROM jeton WHERE token = :jeton');
        $stmt->execute(['jeton' => $jeton]);
        $jetonData = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->connexionManager->disconnect();

        return $jetonData ? true : false;
    }

    // Méthode pour supprimer un jeton
    public function deleteJeton($id)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('DELETE FROM jeton WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $this->connexionManager->disconnect();
    }
}
