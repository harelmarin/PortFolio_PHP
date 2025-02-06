<!DOCTYPE html>
<html>
<head>
    <title>Gérer mes projets</title>
</head>
<body>
    <h1>Mes Projets</h1>

    <!-- Formulaire d'ajout -->
    <div class="add-project">
        <h2>Ajouter un projet</h2>
        <form action="/dashboard/projects/add" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div>
                <label for="title">Titre du projet</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div>
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div>
                <label for="image">Image du projet</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>

            <div>
                <label for="link">Lien externe (optionnel)</label>
                <input type="url" id="link" name="external_link">
            </div>

            <button type="submit">Ajouter le projet</button>
        </form>
    </div>

    <div class="projects-list">
        <h2>Mes projets existants</h2>
        <?php if (!empty($projects)): ?>
            <?php foreach ($projects as $project): ?>
                <div class="project-card">
                    <?php if (!empty($project['image_data'])): ?>
                        <img src="data:image/jpeg;base64,<?= $project['image_data'] ?>" 
                             alt="<?= htmlspecialchars($project['title'] ?? '') ?>"
                             style="max-width: 300px; height: auto;">
                    <?php endif; ?>
                    
                    <h3><?= htmlspecialchars($project['title'] ?? '') ?></h3>
                    <p><?= htmlspecialchars($project['description'] ?? '') ?></p>
                    
                    <?php if (!empty($project['external_link'] ?? null)): ?>
                        <a href="<?= htmlspecialchars($project['external_link']) ?>" 
                           target="_blank">Voir le projet</a>
                    <?php endif; ?>
                    
                    <form action="/dashboard/projects/delete/<?= $project['id'] ?? '' ?>" 
                          method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <button type="submit" class="delete">Supprimer</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun projet pour le moment.</p>
        <?php endif; ?>
    </div>

    <div class="actions">
        <a href="/dashboard">Gérer mes compétences</a>
        <a href="/profile">Retour au profil</a>
    </div>
</body>
</html>