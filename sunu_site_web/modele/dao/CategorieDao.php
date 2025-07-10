 
<?php
class CategorieDao
{
    private $connexionManager;

    public function __construct()
    {
        $this->connexionManager = new ConnexionManager();
    }
    //
    public function getCategorieById($id)
    {
        $stmt = $this->connexionManager->connect()->prepare("SELECT * FROM categorie WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllCategories()
    {
        $stmt = $this->connexionManager->connect()->prepare("SELECT * FROM categorie");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
