 
<?php
require_once __DIR__ . '/ConnexionManager.php';
class TokenDao
{
    private $connexionManager;

    public function __construct()
    {
        $this->connexionManager = new ConnexionManager();
    }

    // Méthode pour générer un jeton
    public function createToken($utilisateur_id)
    {
        $token = bin2hex(random_bytes(16));
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('INSERT INTO jeton (token, utilisateur_id) VALUES (:token, :utilisateur_id)');
        $stmt->execute(['token' => $token, 'utilisateur_id' => $utilisateur_id]);
        $this->connexionManager->disconnect();
        return $token;
    }

    // Méthode pour récupérer un jeton pour un utilisateur
    public function getTokenByUserId($utilisateur_id)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT * FROM jeton WHERE utilisateur_id = :utilisateur_id');
        $stmt->execute(['utilisateur_id' => $utilisateur_id]);
        $token = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->connexionManager->disconnect();
        return $token;
    }

    // Méthode pour vérifier si un jeton est valide
    public function validateToken($token)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('SELECT * FROM jeton WHERE token = :token');
        $stmt->execute(['token' => $token]);
        $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->connexionManager->disconnect();

        return $tokenData ? true : false;
    }

    // Méthode pour supprimer un jeton
    public function deleteToken($id)
    {
        $connexion = $this->connexionManager->connect();
        $stmt = $connexion->prepare('DELETE FROM jeton WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $this->connexionManager->disconnect();
    }
}
