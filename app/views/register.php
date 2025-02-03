<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
</head>
<body>
    <h1>Inscription</h1>

    <?php if (isset($errors['general'])): ?>
        <div class="alert alert-danger">
            <?= $errors['general'] ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/register">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <div>
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" 
                   value="<?= $old['username'] ?? '' ?>">
            <?php if (isset($errors['username'])): ?>
                <span class="error"><?= $errors['username'] ?></span>
            <?php endif; ?>
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" 
                   value="<?= $old['email'] ?? '' ?>">
            <?php if (isset($errors['email'])): ?>
                <span class="error"><?= $errors['email'] ?></span>
            <?php endif; ?>
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password">
            <?php if (isset($errors['password'])): ?>
                <span class="error"><?= $errors['password'] ?></span>
            <?php endif; ?>
        </div>

        <div>
            <h3>Compétences</h3>
            <?php foreach ($skills as $skill): ?>
                <div class="skill-item">
                    <input type="checkbox" 
                           name="skills[<?= $skill['id'] ?>]" 
                           id="skill_<?= $skill['id'] ?>" 
                           value="<?= $skill['id'] ?>">
                    <label for="skill_<?= $skill['id'] ?>"><?= $skill['name'] ?></label>
                    
                    <select name="skill_levels[<?= $skill['id'] ?>]">
                        <option value="Débutant">Débutant</option>
                        <option value="Intermédiaire">Intermédiaire</option>
                        <option value="Avancé">Avancé</option>
                        <option value="Expert">Expert</option>
                    </select>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit">S'inscrire</button>
        <p>Déjà un compte ? <a href="/">Se connecter</a></p>
    </form>
</body>
</html>