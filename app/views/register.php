<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1>Inscription</h1>

            <?php if (isset($error)): ?>
                <div class="alert error">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="/register" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe :</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <div class="auth-actions">
                    <button type="submit" class="btn-auth">S'inscrire</button>
                </div>
            </form>

            <div class="auth-links">
                <p>Déjà un compte ? <a href="/">Se connecter</a></p>
            </div>
        </div>
    </div>
</body>
</html>