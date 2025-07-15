<?php
// Démarrer le buffer de sortie pour éviter les caractères parasites
ob_start();

// Inclure le service utilisateur
require_once 'service/UserService.php';

// Si on demande le WSDL, le servir directement
if (isset($_GET['wsdl'])) {
    header('Content-Type: text/xml; charset=UTF-8');
    // S'assurer qu'il n'y a pas de caractères avant le XML
    ob_clean();
    readfile('server.wsdl');
    exit;
}

// Configuration du serveur SOAP
$wsdl = 'http://localhost/sunu_site/sunu_site_web/soap_server.php?wsdl';
$server = new SoapServer($wsdl);

// Définir la classe de service
$server->setClass('UserService');

// Traiter la requête SOAP
$server->handle();

// Vider le buffer et envoyer la réponse
ob_end_flush();
