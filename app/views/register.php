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

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>