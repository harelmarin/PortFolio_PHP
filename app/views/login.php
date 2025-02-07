<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
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

            <form action="/login" method="POST" class="auth-form">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required value="<?= $old['email'] ?? '' ?>">
                    <?php if (isset($errors['email'])): ?>
                        <span class="error"><?= $errors['email'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                    <?php if (isset($errors['password'])): ?>
                        <span class="error"><?= $errors['password'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="auth-actions">
                    <button type="submit" class="btn-auth">Se connecter</button>
                </div>
            </form>

            <div class="auth-links">
                <p>Pas encore de compte ? <a href="/register">S'inscrire</a></p>
            </div>
        </div>
    </div>
</body>
</html>