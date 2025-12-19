<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать студента</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 14px;
        }
        h1 {
            margin-bottom: 16px;
        }
        .form-group {
            margin: 10px 0;
        }
        label {
            display: inline-block;
            width: 200px;
            vertical-align: top;
        }
        input[type="text"],
        select {
            padding: 4px 6px;
            width: 220px;
        }
        .radio-group label {
            display: inline-block;
            margin-right: 15px;
        }
        .actions {
            margin-left: 200px;
            margin-top: 12px;
        }
        .actions a {
            margin-left: 12px;
            color: #0066cc;
            text-decoration: none;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Редактировать данные студента</h1>

    <form method="POST" action="index.php?action=student_update">
        <input type="hidden" name="id" value="<?= (int)$studentRecord['id'] ?>">

        <div class="form-group">
            <label>Группа:</label>
            <select name="group_id" required>
                <?php foreach ($groupOptions as $group): ?>
                    <option value="<?= (int)$group['id'] ?>" <?= $group['id'] == $studentRecord['group_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($group['number']) ?> (<?= htmlspecialchars($group['program']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Фамилия Имя Отчество:</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($studentRecord['full_name']) ?>" required>
        </div>

        <div class="form-group">
            <label>Номер студенческого билета:</label>
            <input type="text" name="student_id" value="<?= htmlspecialchars($studentRecord['student_id']) ?>" required>
        </div>

        <div class="form-group">
            <label>Пол:</label>
            <span class="radio-group">
                <label><input type="radio" name="gender" value="М" <?= $studentRecord['gender'] === 'М' ? 'checked' : '' ?>> Мужской</label>
                <label><input type="radio" name="gender" value="Ж" <?= $studentRecord['gender'] === 'Ж' ? 'checked' : '' ?>> Женский</label>
            </span>
        </div>

        <div class="actions">
            <button type="submit">Сохранить</button>
            <a href="index.php">Отмена</a>
        </div>
    </form>
</body>
</html>