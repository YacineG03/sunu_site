# Application Client Java - Gestion des Utilisateurs

## Description

Cette application Java est un client pour le service web SOAP de gestion des utilisateurs. Elle permet aux administrateurs de gérer les utilisateurs du site d'actualités via une interface graphique.

## Fonctionnalités

### Authentification
- Connexion avec login et mot de passe
- Vérification des droits d'administration via le service SOAP
- Gestion des tokens d'authentification

### Gestion des Utilisateurs (CRUD)
- **Lister** : Afficher tous les utilisateurs du système
- **Ajouter** : Créer un nouvel utilisateur avec nom d'utilisateur, mot de passe et rôle
- **Modifier** : Mettre à jour les informations d'un utilisateur existant
- **Supprimer** : Supprimer un utilisateur du système

### Rôles Supportés
- **visiteur** : Accès en lecture seule aux articles
- **editeur** : Peut gérer le contenu (articles et catégories)
- **administrateur** : Accès complet à toutes les fonctionnalités

## Prérequis

- Java 11 ou supérieur
- Maven 3.6+
- Serveur web avec le service SOAP actif (XAMPP avec le projet PHP)

## Installation et Exécution

### 1. Compilation
```bash
mvn clean compile
```

### 2. Exécution
```bash
mvn exec:java -Dexec.mainClass="com.example.soapclient.UserManagementApp"
```

Ou directement avec Java :
```bash
java -cp target/classes com.example.soapclient.UserManagementApp
```

## Configuration

L'application se connecte automatiquement au service SOAP configuré dans `UserService.java` :
- URL : `http://localhost/sunu_site/sunu_site_web/server.wsdl`
- Namespace : `http://localhost/sunu_site/sunu_site_web/soap_server.php`

## Structure du Projet

```
client_java-main/
├── src/main/java/
│   ├── com/soapclient/
│   │   ├── Main.java                    # Point d'entrée principal
│   │   ├── ui/
│   │   │   ├── LoginFrame.java          # Interface de connexion
│   │   │   └── DashboardFrame.java      # Tableau de bord
│   │   └── services/                    # Classes générées par wsimport
│   │       ├── UserService.java
│   │       ├── User.java
│   │       ├── AddUserRequest.java
│   │       ├── UpdateUserRequest.java
│   │       └── ...
│   └── com/example/soapclient/
│       └── UserManagementApp.java       # Application principale
├── pom.xml                              # Configuration Maven
└── README.md                            # Documentation
```

## Utilisation

1. **Lancement** : Exécutez l'application
2. **Connexion** : Saisissez les identifiants d'un administrateur
3. **Gestion** : Utilisez les boutons pour gérer les utilisateurs
4. **Déconnexion** : Cliquez sur "Déconnexion" pour fermer la session

## Dépannage

### Erreurs de Connexion
- Vérifiez que le serveur web (XAMPP) est démarré
- Vérifiez que le service SOAP est accessible
- Vérifiez les identifiants d'administration

### Erreurs de Compilation
- Vérifiez la version Java (Java 11+ requis)
- Vérifiez que Maven est installé
- Exécutez `mvn clean compile` pour recompiler

## Dépendances

- **Apache CXF** : Client SOAP
- **JAX-WS API** : API pour les services web
- **Swing** : Interface graphique (inclus dans le JDK)

## Auteur

Projet d'Architecture Logicielle - Application Client Java 