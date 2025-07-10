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
        <!-- Si aucun utilisateur n'a été fourni dans la requête(pour le modifier)
        on affiche simplement le formulaire d'ajout
        Sinon on affiche le formulaire de modification prerempli-->
        <h1 class="text-center"><?= isset($utilisateur) ? 'Modifier' : 'Créer' ?> un utilisateur</h1>
        <form method="post" action="<?= BASE_URL ?>/index.php?action=<?= isset($utilisateur) ? 'editutilisateur&id=' . $utilisateur['id'] : 'createutilisateur' ?>">
            <div class="form-group">
                <label for="nomUtilisateur">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="nomUtilisateur" name="login" value="<?= isset($utilisateur) ? $utilisateur['login'] : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admininistrateur" <?= isset($utilisateur) && $utilisateur['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="editeur" <?= isset($utilisateur) && $utilisateur['role'] == 'editeur' ? 'selected' : '' ?>>Éditeur</option>
                </select>
            </div>
            <div class="form-group">
                <label for="motDePasse">Mot de passe</label>
                <input type="mot_de_passe" class="form-control" id="motDePasse" name="mot_de_passe" required>
            </div>
            <button type="submit" class="btn btn-primary"><?= isset($utilisateur) ? 'Modifier' : 'Créer' ?></button>
        </form>
    </div>
</body>

</html>