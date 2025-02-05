<!DOCTYPE html>
<html>
<head>
    <title>Tableau de bord - Modification du profil</title>
</head>
<body>
    <h1>Gérer Mes Compétences</h1>

    <?php if (isset($message)): ?>
        <div class="alert <?= $message['type'] ?>">
            <?= htmlspecialchars($message['text'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="skills-form">
        <h2>Mes Compétences</h2>
        <form action="/dashboard/skills/update" method="POST">
            <div>
                <h3>Compétences</h3>
                <?php 
                // Récupérer les compétences actuelles de l'utilisateur
                $userSkillsMap = [];
                foreach ($skills as $skill) {
                    $userSkillsMap[$skill['name']] = [
                        'id' => $skill['id'],
                        'level' => $skill['level']
                    ];
                }
                
                // Liste des compétences disponibles
                $availableSkills = [
                    'HTML', 'CSS', 'JavaScript', 'PHP', 
                    'MySQL', 'React', 'Node.js', 'Python'
                ];
                
                foreach ($availableSkills as $skillName): 
                    $isChecked = isset($userSkillsMap[$skillName]);
                    $currentLevel = $isChecked ? $userSkillsMap[$skillName]['level'] : '';
                ?>
                    <div class="skill-item">
                        <input type="checkbox" 
                               name="skills[<?= htmlspecialchars($skillName) ?>]" 
                               id="skill_<?= htmlspecialchars($skillName) ?>" 
                               value="<?= htmlspecialchars($skillName) ?>"
                               <?= $isChecked ? 'checked' : '' ?>>
                        <label for="skill_<?= htmlspecialchars($skillName) ?>">
                            <?= htmlspecialchars($skillName) ?>
                        </label>
                        
                        <select name="skill_levels[<?= htmlspecialchars($skillName) ?>]">
                            <option value="Débutant" <?= $currentLevel === 'Débutant' ? 'selected' : '' ?>>Débutant</option>
                            <option value="Intermédiaire" <?= $currentLevel === 'Intermédiaire' ? 'selected' : '' ?>>Intermédiaire</option>
                            <option value="Avancé" <?= $currentLevel === 'Avancé' ? 'selected' : '' ?>>Avancé</option>
                            <option value="Expert" <?= $currentLevel === 'Expert' ? 'selected' : '' ?>>Expert</option>
                        </select>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit">Mettre à jour mes compétences</button>
        </form>
    </div>

    <div class="actions">
        <a href="/profile" class="button">Retour au profil</a>
    </div>
</body>
</html> 