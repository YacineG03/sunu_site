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
    <title>Sunu Site - Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style/entete.css">
    <link rel="stylesheet" href="./style/index.css">
    <style>
        /* Styles spécifiques pour la page de connexion */
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--background-color);
            padding: 2rem;
        }
        
        .auth-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 1200px;
            width: 100%;
            background: var(--card-background);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }
        
        .auth-form-section {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .auth-image-section {
            background: linear-gradient(135deg, var(--hover-color) 0%, #0a204d 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .auth-image {
            max-width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
        }
        
        .auth-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .auth-form {
            display: grid;
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
        
        .input-wrapper {
            display: flex;
            align-items: center;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            background: white;
            transition: all 0.3s ease;
            padding: 0.75rem;
        }
        
        .input-wrapper:focus-within {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.1);
        }
        
        .input-icon {
            margin-right: 0.75rem;
            color: var(--light-text);
        }
        
        .form-input {
            flex: 1;
            border: none;
            background: transparent;
            font-family: 'Inter', sans-serif;
            color: var(--text-color);
            font-size: 1rem;
        }
        
        .form-input::placeholder {
            color: var(--light-text);
        }
        
        .form-input:focus {
            outline: none;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .remember-me input {
            width: 1.25rem;
            height: 1.25rem;
            accent-color: var(--primary-color);
        }
        
        .remember-me label {
            font-size: 0.95rem;
            color: var(--text-color);
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0a204d 100%);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(12, 48, 102, 0.25);
        }
        
        .error-message {
            color: #dc2626;
            background: #fee2e2;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .error-message svg {
            flex-shrink: 0;
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 1rem;
        }
        
        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .forgot-password a:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .auth-container {
                grid-template-columns: 1fr;
            }
            
            .auth-image-section {
                display: none;
            }
            
            .auth-form-section {
                padding: 2rem;
            }
        }
    </style>
</head>

<body>
    <?php require_once 'inc/entete.php'; ?>
    
    <div class="auth-page">
        <div class="auth-container">
            <div class="auth-form-section">
                <h1 class="auth-title">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zM20 19h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z"/>
                    </svg>
                    Connexion à votre compte
                </h1>

                <?php if (isset($_GET['error'])) : ?>
                    <div class="error-message" role="alert">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                        </svg>
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= BASE_URL ?>/index.php?action=connexion" class="auth-form">
                    <div class="form-group">
                        <label for="login" class="form-label">Nom d'utilisateur</label>
                        <div class="input-wrapper">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="input-icon">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                            </svg>
                            <input type="text" id="login" class="form-input" placeholder="Entrez votre nom d'utilisateur" name="login" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="input-wrapper">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="input-icon">
                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                            </svg>
                            <input type="password" id="password" class="form-input" placeholder="Entrez votre mot de passe" name="mot_de_passe" required>
                        </div>
                    </div>
                    
                    <div class="remember-me">
                        <input type="checkbox" id="rememberMe" name="remember">
                        <label for="rememberMe">Se souvenir de moi</label>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zM20 19h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z"/>
                        </svg>
                        Se connecter
                    </button>
                    
                    <div class="forgot-password">
                        <a href="<?= BASE_URL ?>/index.php?action=forgot-password">Mot de passe oublié ?</a>
                    </div>
                </form>
            </div>
            
            <div class="auth-image-section">
                <img src="./assets/Connexion.png" alt="Illustration de connexion" class="auth-image">
            </div>
        </div>
    </div>
</body>

</html>