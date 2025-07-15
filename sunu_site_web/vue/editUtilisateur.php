<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($utilisateur) ? 'Modifier' : 'Créer' ?> un utilisateur - ESP Dakar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style/entete.css">
    <link rel="stylesheet" href="./style/index.css">
    <style>
        /* Styles spécifiques pour le formulaire utilisateur */
        .user-form-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--card-background);
            border-radius: 12px;
            box-shadow: var(--shadow);
        }
        
        .form-header {
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .form-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            color: var(--primary-color);
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.95rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%236b7280'%3e%3cpath d='M7 10l5 5 5-5z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25rem;
            padding-right: 2.5rem;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--light-text);
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0a204d 100%);
            color: white;
            padding: 0.875rem 1.75rem;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
            width: 100%;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(12, 48, 102, 0.25);
        }
        
        .form-actions {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: var(--accent-color);
        }
        
        @media (max-width: 768px) {
            .user-form-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .form-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <?php require_once 'inc/entete.php'; ?>
    
    <main>
        <div class="user-form-container">
            <div class="form-header">
                <h1 class="form-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <?= isset($utilisateur) ? 'Modifier' : 'Créer' ?> un utilisateur
                </h1>
            </div>
            
            <form method="post" action="<?= BASE_URL ?>/index.php?action=<?= isset($utilisateur) ? 'editutilisateur&id=' . $utilisateur['id'] : 'createutilisateur' ?>" class="form-grid">
                <div class="form-group">
                    <label for="nomUtilisateur" class="form-label">Nom d'utilisateur</label>
                    <input type="text" id="nomUtilisateur" name="login" class="form-control" 
                           placeholder="Entrez un nom d'utilisateur unique" 
                           value="<?= isset($utilisateur) ? htmlspecialchars($utilisateur['login']) : '' ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Rôle</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="">Sélectionnez un rôle</option>
                        <option value="admin" <?= isset($utilisateur) && $utilisateur['role'] == 'admin' ? 'selected' : '' ?>>Administrateur</option>
                        <option value="editeur" <?= isset($utilisateur) && $utilisateur['role'] == 'editeur' ? 'selected' : '' ?>>Éditeur</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="motDePasse" class="form-label">Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password" id="motDePasse" name="mot_de_passe" class="form-control" 
                               placeholder="<?= isset($utilisateur) ? 'Laisser vide pour ne pas modifier' : 'Entrez un mot de passe sécurisé' ?>" 
                               <?= !isset($utilisateur) ? 'required' : '' ?>>
                        <span class="toggle-password" onclick="togglePasswordVisibility()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                        </svg>
                        <?= isset($utilisateur) ? 'Mettre à jour' : 'Créer' ?> l'utilisateur
                    </button>
                </div>
            </form>
            
            <div style="text-align: center;">
                <a href="<?= BASE_URL ?>/index.php?action=utilisateurs" class="back-link">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                    </svg>
                    Retour à la liste des utilisateurs
                </a>
            </div>
        </div>
    </main>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('motDePasse');
            const toggleIcon = document.querySelector('.toggle-password svg');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.innerHTML = '<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>';
            } else {
                passwordInput.type = 'password';
                toggleIcon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
            }
        }
    </script>
</body>

</html>