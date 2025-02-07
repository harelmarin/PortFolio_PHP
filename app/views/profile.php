<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Mon Profil</h1>

        <div class="profile-info">
            <h2>Informations</h2>
            <p>Username: <?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            <p>Email: <?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            <p>Rôle : <?= htmlspecialchars($user['role']) ?></p>
        </div>

        <div class="skills">
            <h2>Mes Compétences</h2>
            <?php if (!empty($skills)): ?>
                <ul>
                <?php foreach ($skills as $skill): ?>
                    <li>
                        <?= htmlspecialchars($skill['name'], ENT_QUOTES, 'UTF-8') ?> - 
                        Niveau : <?= htmlspecialchars($skill['level'], ENT_QUOTES, 'UTF-8') ?>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune compétence ajoutée</p>
            <?php endif; ?>
        </div>

        <div class="projects-section">
            <h2>Mes Projets</h2>
            <?php if (!empty($projects)): ?>
                <div class="projects-grid">
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card">
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
                            <?php endif; ?>
                            
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
                <p class="empty-message">Aucun projet ajouté</p>
            <?php endif; ?>
        </div>

        <div class="actions">
            <a href="/dashboard" class="btn-manage">Gérer mes compétences</a>
            <a href="/dashboard/projects" class="btn-manage-projects">Gérer mes projets</a>
            <?php if ($user['role'] === 'admin'): ?>
                <a href="/admin" class="btn-admin">Administration</a>
            <?php endif; ?>
            <a href="/logout" class="btn-logout">Déconnexion</a>
        </div>
    </div>
</body>
</html>