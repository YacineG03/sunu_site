<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>

<body>
    <?php require_once 'inc/entete.php'; ?>
    <div class="container p-5">
        <h1 class="text-center">Gestion des utilisateurs</h1>
        <!-- Si l'utilisateur est un administrateur, il peut ajouter, lister, modifier et supprimmer
          un utilisateur -->
        <?php if ($_SESSION['role'] === 'admin') : ?>
            <div class="d-flex justify-content-end">
                <a href="index.php?action=createutilisateur" class="btn btn-primary my-3">Ajouter un utilisateur</a>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                        <th>jeton</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs as $utilisateur) : ?>
                        <tr>
                            <td><?= htmlspecialchars($utilisateur['id']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['login']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['role']) ?></td>
                            <td>
                                <a href="index.php?action=editutilisateur&id=<?= $utilisateur['id'] ?>" class="btn btn-sm btn-info">Modifier</a>
                                <a href="index.php?action=deleteutilisateur&id=<?= $utilisateur['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?');">Supprimer</a>
                                <a href="index.php?action=generatejeton&id=<?= $utilisateur['id'] ?>" class="btn btn-sm btn-secondary">Générer jeton</a>
                            </td>
                            <td><?= htmlspecialchars($utilisateur['token'] ?? 'Non généré') ?></td> <!-- Affiche le jeton ou 'Non généré' si null -->

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div class="alert alert-warning" role="alert">
                Vous n'avez pas la permission d'accéder à cette page.
            </div>
        <?php endif; ?>
    </div>
</body>

</html>