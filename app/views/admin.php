<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Panel d'Administration</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['message']['type'] ?>">
                <?= htmlspecialchars($_SESSION['message']['text'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="admin-grid">
            <div class="admin-section">
                <h2>Ajouter une nouvelle compétence</h2>
                <form action="/admin/skills/add" method="POST" class="form-card">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    
                    <div class="form-group">
                        <label for="skill_name">Nom de la compétence :</label>
                        <input type="text" id="skill_name" name="skill_name" required>
                    </div>

                    <button type="submit" class="btn-primary">Ajouter</button>
                </form>
            </div>

            <div class="admin-section">
                <h2>Liste des compétences</h2>
                <div class="skills-list">
                    <?php if (!empty($skills)): ?>
                        <ul>
                            <?php foreach ($skills as $skill): ?>
                                <li class="skill-card">
                                    <?= htmlspecialchars($skill['name'], ENT_QUOTES, 'UTF-8') ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="empty-message">Aucune compétence disponible</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="admin-section users-section">
                <h2>Gestion des Utilisateurs</h2>
                
                <form action="/admin" method="GET" class="filter-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="search">Rechercher :</label>
                            <input type="text" id="search" name="search" 
                                   value="<?= htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                   placeholder="Nom d'utilisateur ou email">
                        </div>
                        
                        <div class="form-group">
                            <label for="role">Rôle :</label>
                            <select name="role" id="role">
                                <option value="">Tous les rôles</option>
                                <option value="user" <?= (isset($_GET['role']) && $_GET['role'] === 'user') ? 'selected' : '' ?>>Utilisateur</option>
                                <option value="admin" <?= (isset($_GET['role']) && $_GET['role'] === 'admin') ? 'selected' : '' ?>>Administrateur</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-secondary">Filtrer</button>
                    </div>
                </form>

                <?php if (!empty($users)): ?>
                    <div class="table-responsive">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Date d'inscription</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><span class="role-badge <?= $user['role'] ?>"><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                        <td><?= htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="empty-message">Aucun utilisateur trouvé</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="actions">
            <a href="/profile" class="btn-back">Retour au profil</a>
        </div>
    </div>
</body>
</html> 