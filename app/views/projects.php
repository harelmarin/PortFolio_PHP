<!DOCTYPE html>
<html>
<head>
    <title>Gérer mes projets</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Mes Projets</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['message']['type'] ?>">
                <?= htmlspecialchars($_SESSION['message']['text'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- Formulaire d'ajout -->
        <div class="add-project">
            <h2>Ajouter un projet</h2>
            <form action="/dashboard/projects/add" method="POST" enctype="multipart/form-data" class="project-form">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="form-group">
                    <label for="title">Titre du projet</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Image du projet</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="link">Lien externe (optionnel)</label>
                    <input type="url" id="link" name="external_link">
                </div>

                <button type="submit" class="btn-manage">Ajouter le projet</button>
            </form>
        </div>

        <!-- Liste des projets -->
        <div class="projects-section">
            <h2>Mes projets existants</h2>
            <?php if (!empty($projects)): ?>
                <div class="projects-grid">
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card">
                            <div class="project-header">
                                <?php if (!empty($project['image_data'])): ?>
                                    <?php 
                                    // Si l'image est déjà en base64, pas besoin de l'encoder à nouveau
                                    $imageData = $project['image_data'];
                                    if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $imageData)) {
                                        // Si ce n'est pas du base64, on encode
                                        $imageData = base64_encode($project['image_data']);
                                    }
                                    ?>
                                    <img src="data:image/jpeg;base64,<?= $imageData ?>" 
                                         alt="<?= htmlspecialchars($project['title']) ?>"
                                         class="project-image">
                                <?php else: ?>
                                    <div>No image data available</div>
                                <?php endif; ?>
                                
                                <form action="/dashboard/projects/delete/<?= $project['id'] ?>" 
                                      method="POST" 
                                      class="delete-project-form"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="project-info">
                                <h3><?= htmlspecialchars($project['title'] ?? '') ?></h3>
                                <p><?= htmlspecialchars($project['description'] ?? '') ?></p>
                                
                                <?php if (!empty($project['external_link'] ?? null)): ?>
                                    <a href="<?= htmlspecialchars($project['external_link']) ?>" 
                                       target="_blank" 
                                       class="project-external-link">
                                        <i class="fas fa-external-link-alt"></i> 
                                        Voir le projet
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="empty-message">Aucun projet pour le moment.</p>
            <?php endif; ?>
        </div>

        <div class="actions">
            <a href="/dashboard" class="btn-manage">Gérer mes compétences</a>
            <a href="/profile" class="btn-back">Retour au profil</a>
        </div>
    </div>
</body>
</html>