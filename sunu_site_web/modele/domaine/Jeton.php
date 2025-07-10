<?php
class jeton
{
    private $id;
    private $jeton;
    private $dateCreation;
    private $utilisateur_id;

    public function __construct($id, $jeton, $dateCreation, $utilisateur_id)
    {
        $this->id = $id;
        $this->jeton = $jeton;
        $this->dateCreation = $dateCreation;
        $this->utilisateur_id = $utilisateur_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getjeton()
    {
        return $this->jeton;
    }

    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    public function getIdUser()
    {
        return $this->utilisateur_id;
    }
}
