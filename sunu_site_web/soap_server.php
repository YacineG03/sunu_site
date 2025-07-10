<?php

// Inclure le fichier contenant la classe UserService
require_once 'service/UserService.php';

// Définir le chemin vers le fichier WSDL
$wsdl = 'http://localhost/sunu_site/sunu_site_web/server.wsdl';
// Créer un serveur SOAP
$server = new SoapServer($wsdl);

// Ajouter la classe UserService au serveur
$server->setClass('UserService');

// Démarrer le serveur
$server->handle();
