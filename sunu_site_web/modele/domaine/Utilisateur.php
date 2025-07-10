<?php

class Utilisateur
{
    private $id;
    private $login;
    private $mot_de_passe;
    private $role;

    public function __construct($id, $login, $mot_de_passe, $role)
    {
        $this->id = $id;
        $this->login = $login;
        $this->mot_de_passe = $mot_de_passe;
        $this->role = $role;
    }
}
