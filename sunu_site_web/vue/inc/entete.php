 
<?php
// session_start();
 // echo password_hash('passer', PASSWORD_DEFAULT);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}                 
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Sunu Site</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Si l'utilisateur est connecté, on affiche un dropdown avec les options de l'utilisateur
         Sinon on affiche le bouton de connexion -->
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <?php
                require_once 'config.php';
                ?>
                <a class="nav-item nav-link active" href="<?= BASE_URL ?>/index.php">Accueil</a>
                <?php foreach ($categories as $categorie) : ?>
                    <a class="nav-item nav-link" href="<?= BASE_URL ?>/index.php?action=categorie&categorie=<?= $categorie->id ?>"><?= $categorie->libelle ?></a>
                <?php endforeach ?>
                <?php if (isset($_SESSION['utilisateur_id']) && $_SESSION['role'] == 'admin') : ?>
                    <a class="nav-item nav-link" href="<?= BASE_URL ?>/index.php?action=utilisateurs">Utilisateurs</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['utilisateur_id'])) : ?>
                    <div class="dropdown nav-item">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $_SESSION['role'] ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?action=logout">Déconnexion</a></li>
                        </ul>
                    </div>
                <?php else : ?>
                    <a href="<?= BASE_URL ?>/index.php?action=connexion" class="btn btn-success nav-item">Se connecter</a>
                <?php endif; ?>
            </div>

        </div>
    </nav>
</body>

</html>