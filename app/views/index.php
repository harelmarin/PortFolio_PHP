<?php 

session_start();
if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; ?>
        <?php unset($_SESSION['success']); ?>  
    </div>
<?php endif; ?>


<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Mon Portfolio' ?></title>
    <?php if (isset($success)): ?>
        <div class="alert alert-success">
            <?= $success ?>
        </div>
    <?php endif; ?>
</head>
<body>
    <h2> TEST </h2>
</body>
</html>