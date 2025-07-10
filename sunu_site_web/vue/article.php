<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
    <?php require_once 'inc/entete.php'; ?>
    <div class="container p-5">
        <?php
        require_once 'config.php'; ?>
        <div class="card border-dark my-3">
            <div class="card-body">
                <h2 class="card-title"><?= $article['titre'] ?></h2>
                <p><?= date("d/m/Y", strtotime($article['dateCreation'])) ?></p>
                <p class="card-text"><?= $article['contenu'] ?></p>

            </div>
        </div>

    </div>
</body>

</html>