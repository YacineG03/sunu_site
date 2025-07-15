<?php

require_once dirname(__DIR__) . '/modele/dao/UtilisateurDao.php';
require_once dirname(__DIR__) . '/modele/dao/jetonDao.php';

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
    public function listUsers($params)
    {
        $this->authenticate($params->jeton);
        $users = $this->utilisateurDao->getAllUtilisateurs();
        // Ajoute le champ mot_de_passe vide pour chaque utilisateur (nécessaire pour le SOAP)
        foreach ($users as &$u) {
            if (!isset($u['mot_de_passe'])) {
                $u['mot_de_passe'] = '';
            }
        }
        return ['users' => $users];
    }

    // Service pour ajouter un utilisateur en vérifiant le jeton
    public function addUser($params)
    {
        $this->authenticate($params->jeton);
        try {
            $utilisateur = [
                'login' => $params->login,
                'mot_de_passe' => $params->mot_de_passe,
                'role' => $params->role
            ];
            $this->utilisateurDao->createUtilisateur($utilisateur);
            return ['status' => true];
        } catch (Exception $e) {
            return ['status' => false];
        }
    }

    // Service pour supprimer un utilisateur en vérifiant le jeton
    public function deleteUser($params)
    {
        $this->authenticate($params->jeton);
        try {
            $this->utilisateurDao->deleteUtilisateur($params->id);
            return ['status' => true];
        } catch (Exception $e) {
            return ['status' => false];
        }
    }

    // Service pour mettre à jour un utilisateur en vérifiant le jeton
    public function updateUser($params)
    {
        $this->authenticate($params->jeton);
        try {
            $utilisateur = [
                'id' => $params->id,
                'login' => $params->login,
                'mot_de_passe' => $params->mot_de_passe,
                'role' => $params->role
            ];
            $this->utilisateurDao->updateUtilisateur($utilisateur);
            return ['status' => true];
        } catch (Exception $e) {
            return ['status' => false];
        }
    }

    // Service pour authentifier un utilisateur
    public function authenticateUser($params)
    {
        // Log pour debug
        file_put_contents(__DIR__ . '/../soap_output.log', print_r($params, true), FILE_APPEND);
        // Correction pour structure imbriquée
        if (isset($params->parameters)) {
            $params = $params->parameters;
        }
        $login = $params->login;
        $mot_de_passe = $params->mot_de_passe;
        $user = $this->utilisateurDao->verifyUser($login, $mot_de_passe);
        
        if ($user) {
            // Vérifier si l'utilisateur a déjà un token
            $existingJeton = $this->jetonDao->getJetonByUserId($user['id']);
            if ($existingJeton) {
                $jeton = $existingJeton['token'];
            } else {
                // Ne pas créer de nouveau jeton, retourner vide
                $jeton = '';
            }
            return ['status' => true, 'jeton' => $jeton];
        } else {
            return ['status' => false, 'jeton' => ''];
        }
    }

    // Service pour verifier le jeton pour un utilisateur pour qu'il accèdé aux ressources protégées
    private function authenticate($jeton)
    {
        if (!$this->jetonDao->validateJeton($jeton)) {
            throw new Exception('jeton invalide ou expiré');
        }
    }
}
