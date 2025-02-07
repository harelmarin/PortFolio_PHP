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
                
                <div class="skills-grid">
                    <?php 
                    $userSkillsMap = [];
                    foreach ($skills as $skill) {
                        $userSkillsMap[$skill['name']] = [
                            'id' => $skill['id'],
                            'level' => $skill['level']
                        ];
                    }
                    
                    $availableSkills = [
                        'HTML', 'CSS', 'JavaScript', 'PHP', 
                        'MySQL', 'React', 'Node.js', 'Python'
                    ];
                    
                    foreach ($availableSkills as $skillName): 
                        $isChecked = isset($userSkillsMap[$skillName]);
                        $currentLevel = $isChecked ? $userSkillsMap[$skillName]['level'] : '';
                    ?>
                        <div class="skill-card">
                            <div class="skill-header">
                                <input type="checkbox" 
                                       name="skills[<?= htmlspecialchars($skillName) ?>]" 
                                       id="skill_<?= htmlspecialchars($skillName) ?>" 
                                       value="<?= htmlspecialchars($skillName) ?>"
                                       <?= $isChecked ? 'checked' : '' ?>>
                                <label for="skill_<?= htmlspecialchars($skillName) ?>">
                                    <?= htmlspecialchars($skillName) ?>
                                </label>
                            </div>
                            
                            <div class="skill-level">
                                <select name="skill_levels[<?= htmlspecialchars($skillName) ?>]" 
                                        class="level-select <?= $isChecked ? 'active' : '' ?>">
                                    <option value="Débutant" <?= $currentLevel === 'Débutant' ? 'selected' : '' ?>>Débutant</option>
                                    <option value="Intermédiaire" <?= $currentLevel === 'Intermédiaire' ? 'selected' : '' ?>>Intermédiaire</option>
                                    <option value="Avancé" <?= $currentLevel === 'Avancé' ? 'selected' : '' ?>>Avancé</option>
                                    <option value="Expert" <?= $currentLevel === 'Expert' ? 'selected' : '' ?>>Expert</option>
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