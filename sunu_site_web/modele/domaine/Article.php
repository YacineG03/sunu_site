<?php
class Article
{
    private $id;
    private $titre;
    private $description;
    private $contenu;
    private $dateCreation;
    private $dateModification;
    private $categorie;

    public function __construct($id, $titre, $description, $contenu,$categorie)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->contenu = $contenu;
        $this->categorie = $categorie;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitre()
    {
        return $this->titre;
    }
    public function getDescription()
    {
        return $this->description;
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function getDateCreation()
    {
        return $this->dateCreation;
    }
    public function getDateModification()
    {
        return $this->dateModification;
    }
    public function getCategorie()
    {
        return $this->categorie;
    }
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTitre($titre)
    {
        $this->titre = $titre;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    }

    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }
}
