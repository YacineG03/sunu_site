<?php
class Token
{
    private $id;
    private $token;
    private $dateCreation;
    private $utilisateur_id;

    public function __construct($id, $token, $dateCreation, $utilisateur_id)
    {
        $this->id = $id;
        $this->token = $token;
        $this->dateCreation = $dateCreation;
        $this->utilisateur_id = $utilisateur_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getToken()
    {
        return $this->token;
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
