<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
</head>
<body>
    <h1>Connexion</h1>

    <?php if (isset($success)): ?>
        <div class="alert alert-success">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <?php if (isset($errors['auth'])): ?>
        <div class="alert alert-danger">
            <?= $errors['auth'] ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
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

        <button type="submit">Se connecter</button>
        <p>Pas encore de compte ? <a href="/register">S'inscrire</a></p>
    </form>
</body>
</html>