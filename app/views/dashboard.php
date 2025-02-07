<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Gérer Mes Compétences</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['message']['type'] ?>">
                <?= htmlspecialchars($_SESSION['message']['text'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="skills-manager">
            <form action="/dashboard/skills/update" method="POST" class="skills-form">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <?php
                /**
                 * Initialisation du tableau des compétences actuelles de l'utilisateur
                 * @var array $currentSkills Tableau associatif [skill_id => level]
                 */
                $currentSkills = [];
                foreach ($skills as $skill) {
                    if (isset($skill['id'])) {
                        $currentSkills[$skill['id']] = $skill['level'];
                    } elseif (isset($skill['skill_id'])) {
                        $currentSkills[$skill['skill_id']] = $skill['level'];
                    }
                }
                ?>

                <div class="skills-grid">
                    <?php foreach($availableSkills as $skill): ?>
                        <div class="skill-card">
                            <div class="skill-header">
                                <input type="checkbox" 
                                       name="skills[<?= $skill['id'] ?>]" 
                                       value="<?= $skill['id'] ?>" 
                                       id="skill_<?= $skill['id'] ?>"
                                       <?= isset($currentSkills[$skill['id']]) ? 'checked' : '' ?>>
                                <label for="skill_<?= $skill['id'] ?>">
                                    <?= htmlspecialchars($skill['name']) ?>
                                </label>
                            </div>
                            
                            <div class="skill-level">
                                <select name="skill_levels[<?= $skill['id'] ?>]">
                                    <?php 
                                    /**
                                     * Définition des niveaux de compétence disponibles
                                     * @var array $levels Liste des niveaux possibles
                                     */
                                    $levels = ['Débutant', 'Avancé', 'Expert'];
                                    foreach ($levels as $level): ?>
                                        <option value="<?= $level ?>" 
                                                <?= (isset($currentSkills[$skill['id']]) && 
                                                    strtolower($currentSkills[$skill['id']]) === strtolower($level)) ? 'selected' : '' ?>>
                                            <?= $level ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="actions">
                    <button type="submit" class="btn-manage">Mettre à jour mes compétences</button>
                    <a href="/profile" class="btn-back">Retour au profil</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 