<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Gérer Mes Projets</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['message']['type'] ?>">
                <?= htmlspecialchars($_SESSION['message']['text'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="projects-container">
            <div class="project-form-section">
                <h2>Ajouter un nouveau projet</h2>
                <form action="/dashboard/projects/add" method="POST" enctype="multipart/form-data" class="project-form">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    
                    <div class="form-group">
                        <label for="title">Titre du projet :</label>
                        <input type="text" id="title" name="title" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description :</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="external_link">Lien externe (GitHub, site web...) :</label>
                        <input type="url" id="external_link" name="external_link" placeholder="https://...">
                    </div>

                    <div class="form-group">
                        <label for="image">Image du projet :</label>
                        <input type="file" id="image" name="image" accept="image/*" required class="file-input">
                    </div>

                    <button type="submit" class="btn-manage-projects">Ajouter le projet</button>
                </form>
            </div>

            <div class="projects-list">
                <h2>Mes Projets</h2>
                <?php if (!empty($projects)): ?>
                    <div class="projects-grid">
                        <?php foreach ($projects as $project): ?>
                            <div class="project-card">
                                <div class="project-image">
                                    <img src="data:image/jpeg;base64,<?= $project['image'] ?>" alt="<?= htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8') ?>">
                                </div>
                                <div class="project-info">
                                    <h3><?= htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <p><?= htmlspecialchars($project['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <?php if (!empty($project['external_link'])): ?>
                                        <a href="<?= htmlspecialchars($project['external_link'], ENT_QUOTES, 'UTF-8') ?>" 
                                           target="_blank" 
                                           class="btn-link">
                                            Voir le projet
                                        </a>
                                    <?php endif; ?>
                                    <form action="/dashboard/projects/delete/<?= $project['id'] ?>" method="POST" class="delete-form">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <button type="submit" class="btn-delete">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="empty-message">Aucun projet ajouté pour le moment</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="actions">
            <a href="/profile" class="btn-back">Retour au profil</a>
        </div>
    </div>
</body>
</html>