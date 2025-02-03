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
                    <?= htmlspecialchars($skill['name'] ?? '', ENT_QUOTES, 'UTF-8') ?> - 
                    <?= htmlspecialchars($skill['level'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune compétence ajoutée</p>
        <?php endif; ?>
    </div>

    <div class="actions">
        <a href="/profile/edit">Modifier mon profil</a>
        <a href="/logout">Déconnexion</a>
    </div>
</body>
</html>