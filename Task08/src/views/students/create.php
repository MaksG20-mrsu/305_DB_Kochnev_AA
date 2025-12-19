<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить студента</title>
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
            margin: 12px 0;
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
            margin-top: 10px;
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
    <h1>Добавить нового студента</h1>

    <form method="POST" action="index.php?action=student_store">
        <div class="form-group">
            <label>Группа:</label>
            <select name="group_id" required>
                <?php foreach (Student::getGroupsForSelect() as $groupOption): ?>
                    <option value="<?= (int)$groupOption['id'] ?>">
                        <?= htmlspecialchars($groupOption['number']) ?> (<?= htmlspecialchars($groupOption['program']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Фамилия Имя Отчество:</label>
            <input type="text" name="full_name" required>
        </div>

        <div class="form-group">
            <label>Номер зачетной книжки:</label>
            <input type="text" name="student_id" required>
        </div>

        <div class="form-group">
            <label>Пол:</label>
            <span class="radio-group">
                <label><input type="radio" name="gender" value="М" checked> Мужской</label>
                <label><input type="radio" name="gender" value="Ж"> Женский</label>
            </span>
        </div>

        <div class="actions">
            <button type="submit">Сохранить</button>
            <a href="index.php">Отмена</a>
        </div>
    </form>
</body>
</html>