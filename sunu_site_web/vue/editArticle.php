<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>

<body>
    <?php require_once 'inc/entete.php'; ?>

    <div class="container p-5">
        <!-- Si aucun article n'a été fourni dans la requête(pour le modifier)
        on affiche simplement le formulaire d'ajout
        Sinon on affiche le formulaire de modification prerempli-->
        <h1 class="text-center"><?= isset($article) ? 'Modifier' : 'Créer' ?> un article</h1>

        <form method="post" action="<?= BASE_URL ?>/index.php?action=<?= isset($article) ? 'editarticle&id=' . $article['id'] : 'createarticle' ?>">
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre" value="<?= isset($article) ? $article['titre'] : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="contenu">Contenu</label>
                <textarea class="form-control" id="contenu" name="contenu" rows="5" required><?= isset($article) ? $article['contenu'] : '' ?></textarea>
            </div>
            <div class="form-group">
                <label for="categorie">Catégorie</label>
                <select class="form-control" id="categorie" name="categorie" required>
                    <?php foreach ($categories as $categorie) : ?>
                        <option value="<?= $categorie->id ?>" <?= isset($article) && $article['categorie'] == $categorie->id ? 'selected' : '' ?>><?= $categorie->libelle ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?= isset($article) ? $article['description'] : '' ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><?= isset($article) ? 'Modifier' : 'Créer' ?></button>
        </form>

    </div>
</body>

</html>