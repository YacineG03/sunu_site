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
    <title>Gestion des utilisateurs - ESP Dakar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style/entete.css">
    <link rel="stylesheet" href="./style/index.css">
    <style>
        /* Styles spécifiques pour la gestion des utilisateurs */
        .users-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--card-background);
            border-radius: 12px;
            box-shadow: var(--shadow);
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
        }
        
        .page-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--primary-color);
            font-size: 1.75rem;
        }
        
        .add-user-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0a204d 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .add-user-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(12, 48, 102, 0.25);
        }
        
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }
        
        .users-table th, 
        .users-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        .users-table th {
            background: rgba(12, 48, 102, 0.05);
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .users-table tr:hover {
            background: rgba(12, 48, 102, 0.02);
        }
        
        .action-btn {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            margin-right: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .edit-btn {
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
            border: 1px solid rgba(37, 99, 235, 0.2);
        }
        
        .edit-btn:hover {
            background: rgba(37, 99, 235, 0.2);
        }
        
        .delete-btn {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
            border: 1px solid rgba(220, 38, 38, 0.2);
        }
        
        .delete-btn:hover {
            background: rgba(220, 38, 38, 0.2);
        }
        
        .token-btn {
            background: rgba(107, 114, 128, 0.1);
            color: var(--text-color);
            border: 1px solid rgba(107, 114, 128, 0.2);
        }
        
        .token-btn:hover {
            background: rgba(107, 114, 128, 0.2);
        }
        
        .token-value {
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            background: rgba(0, 0, 0, 0.05);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            word-break: break-all;
        }
        
        .access-denied {
            text-align: center;
            padding: 2rem;
            background: rgba(245, 158, 11, 0.1);
            border-radius: 8px;
            margin-top: 2rem;
        }
        
        .access-denied-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #d97706;
        }
        
        @media (max-width: 768px) {
            .users-container {
                padding: 1rem;
                margin: 1rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .users-table {
                display: block;
                overflow-x: auto;
            }
            
            .action-btns {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .action-btn {
                margin-right: 0;
            }
        }
    </style>
</head>

<body>
    <?php require_once 'inc/entete.php'; ?>
    
    <main>
        <div class="users-container">
            <?php if ($_SESSION['role'] === 'admin') : ?>
                <div class="page-header">
                    <h1 class="page-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        Gestion des utilisateurs
                    </h1>
                    <a href="index.php?action=createutilisateur" class="add-user-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                        </svg>
                        Ajouter un utilisateur
                    </a>
                </div>
                
                <div style="overflow-x: auto;">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom d'utilisateur</th>
                                <th>Rôle</th>
                                <th>Jeton API</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($utilisateurs as $utilisateur) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($utilisateur['id']) ?></td>
                                    <td><?= htmlspecialchars($utilisateur['login']) ?></td>
                                    <td>
                                        <span style="text-transform: capitalize;"><?= htmlspecialchars($utilisateur['role']) ?></span>
                                    </td>
                                    <td>
                                        <span class="token-value"><?= htmlspecialchars($utilisateur['token'] ?? 'Non généré') ?></span>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="index.php?action=editutilisateur&id=<?= $utilisateur['id'] ?>" class="action-btn edit-btn" title="Modifier">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                                </svg>
                                                Modifier
                                            </a>
                                            <a href="index.php?action=deleteutilisateur&id=<?= $utilisateur['id'] ?>" 
                                               class="action-btn delete-btn" 
                                               title="Supprimer"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                                </svg>
                                                Supprimer
                                            </a>
                                            <a href="index.php?action=generatejeton&id=<?= $utilisateur['id'] ?>" class="action-btn token-btn" title="Générer jeton">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11V11.99z"/>
                                                </svg>
                                                Jeton
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="access-denied">
                    <div class="access-denied-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                        </svg>
                    </div>
                    <h2>Accès refusé</h2>
                    <p>Vous n'avez pas les permissions nécessaires pour accéder à cette page.</p>
                    <a href="<?= BASE_URL ?>/index.php" class="back-btn" style="margin-top: 1rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                        </svg>
                        Retour à l'accueil
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>