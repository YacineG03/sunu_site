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
    <title><?= isset($article) ? 'Modifier' : 'Créer' ?> un article - ESP Dakar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style/entete.css">
    <link rel="stylesheet" href="./style/index.css">
    <style>
        /* Styles spécifiques pour le formulaire */
        .form-page-container {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .form-container {
            background: var(--card-background);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 2rem;
        }
        
        .form-header {
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
        }
        
        .form-title {
            display: flex;
            align-items: center;
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
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%236b7280'%3e%3cpath d='M7 10l5 5 5-5z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25rem;
            padding-right: 2.5rem;
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
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(12, 48, 102, 0.25);
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 2rem;
        }
        
        /* Sidebar instructions */
        .instructions-sidebar {
            background: var(--card-background);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 1.5rem;
            position: sticky;
            top: 100px;
        }
        
        .instructions-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .instruction-item {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: rgba(12, 48, 102, 0.05);
            border-radius: 8px;
        }
        
        .instruction-item h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        
        .instruction-item p {
            color: var(--text-color);
            font-size: 0.9rem;
            line-height: 1.5;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            color: var(--accent-color);
        }
        
        @media (max-width: 768px) {
            .form-page-container {
                grid-template-columns: 1fr;
                padding: 1rem;
                gap: 1.5rem;
            }
            
            .form-container,
            .instructions-sidebar {
                padding: 1.25rem;
            }
            
            .form-title {
                font-size: 1.5rem;
            }
            
            .instructions-sidebar {
                position: static;
            }
        }
    </style>
</head>

<body>
    <?php require_once 'inc/entete.php'; ?>
    
    <main class="form-page-container">
        <div class="form-container">
            <div class="form-header">
                <h1 class="form-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                    <?= isset($article) ? 'Modifier' : 'Créer' ?> un article
                </h1>
                <a href="<?= BASE_URL ?>/index.php" class="back-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                    </svg>
                    Retour aux actualités
                </a>
            </div>
            
            <form method="post" action="<?= BASE_URL ?>/index.php?action=<?= isset($article) ? 'editarticle&id=' . $article['id'] : 'createarticle' ?>" class="form-grid">
                <div class="form-group">
                    <label for="titre" class="form-label">Titre de l'article</label>
                    <input type="text" id="titre" name="titre" class="form-control" 
                           placeholder="Entrez un titre accrocheur (50-60 caractères max)" 
                           value="<?= isset($article) ? htmlspecialchars($article['titre']) : '' ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="categorie" class="form-label">Catégorie</label>
                    <select id="categorie" name="categorie" class="form-control" required>
                        <option value="">Sélectionnez une catégorie</option>
                        <?php foreach ($categories as $categorie) : ?>
                            <option value="<?= $categorie->id ?>" <?= isset($article) && $article['categorie'] == $categorie->id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categorie->libelle) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description courte</label>
                    <textarea id="description" name="description" class="form-control" 
                              placeholder="Résumé en 1-2 phrases (max 200 caractères)" 
                              required><?= isset($article) ? htmlspecialchars($article['description']) : '' ?></textarea>
                </div>

                <div class="form-group">
                    <label for="contenu" class="form-label">Contenu détaillé</label>
                    <textarea id="contenu" name="contenu" class="form-control" 
                              placeholder="Développez votre article ici... Utilisez des paragraphes courts et des sous-titres pour une meilleure lisibilité." 
                              required><?= isset($article) ? htmlspecialchars($article['contenu']) : '' ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                        </svg>
                        <?= isset($article) ? 'Mettre à jour' : 'Publier' ?> l'article
                    </button>
                </div>
            </form>
        </div>

        <aside class="instructions-sidebar">
            <h2 class="instructions-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                </svg>
                Instructions de rédaction
            </h2>
            
            <div class="instruction-item">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z"/>
                    </svg>
                    Titre percutant
                </h3>
                <p>Le titre doit être clair, informatif et accrocheur. Essayez de le limiter à 50-60 caractères maximum. Il doit donner envie de lire l'article tout en reflétant fidèlement son contenu.</p>
            </div>
            
            <div class="instruction-item">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z"/>
                    </svg>
                    Description concise
                </h3>
                <p>La description doit résumer l'article en 1-2 phrases maximum (environ 200 caractères). C'est ce qui apparaîtra dans les aperçus, alors soyez précis et accrocheur.</p>
            </div>
            
            <div class="instruction-item">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z"/>
                    </svg>
                    Structure du contenu
                </h3>
                <p>Organisez votre article avec des paragraphes courts (3-4 lignes max), des sous-titres et des listes à puces si nécessaire. Utilisez des phrases simples et évitez les blocs de texte trop denses.</p>
            </div>
            
            <div class="instruction-item">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z"/>
                    </svg>
                    Catégorie appropriée
                </h3>
                <p>Sélectionnez la catégorie qui correspond le mieux au sujet de votre article. Cela aide les lecteurs à trouver votre contenu et améliore l'organisation du site.</p>
            </div>
        </aside>
    </main>
</body>

</html>