<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
</head>
<body>
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

    <div class="projects">
        <h2>Mes Projets</h2>
        <?php if (!empty($projects)): ?>
            <div class="projects-grid">
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
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun projet pour le moment</p>
        <?php endif; ?>
    </div>

    <div class="actions">
        <a href="/dashboard">Gérer mes compétences</a>
        <a href="/dashboard/projects">Gérer mes projets</a>
        <a href="/logout">Déconnexion</a>
    </div>
</body>
</html>