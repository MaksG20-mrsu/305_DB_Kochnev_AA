<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить экзамен</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 14px;
        }
        h1 {
            margin-bottom: 16px;
        }
        label {
            display: inline-block;
            width: 140px;
            margin-right: 10px;
            vertical-align: top;
        }
        input, select {
            padding: 4px;
            margin-bottom: 10px;
        }
        .buttons {
            margin-left: 150px;
            margin-top: 10px;
        }
        .buttons a {
            text-decoration: none;
            margin-left: 10px;
            color: #0066cc;
        }
    </style>
</head>
<body>
    <h1>Добавить экзамен для: <?= htmlspecialchars($studentRecord['full_name']) ?></h1>

    <form method="POST" action="index.php?action=exam_store">
        <input type="hidden" name="student_id" value="<?= (int)$studentRecord['id'] ?>">

        <p>
            <label>Дисциплина:</label>
            <select name="discipline_id" required>
                <?php if (empty($disciplineOptions)): ?>
                    <option value="">Нет доступных дисциплин</option>
                <?php else: ?>
                    <?php foreach ($disciplineOptions as $option): ?>
                        <option value="<?= (int)$option['id'] ?>">
                            Курс <?= (int)$option['course'] ?>: <?= htmlspecialchars($option['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </p>

        <p>
            <label>Дата экзамена:</label>
            <input type="date" name="exam_date" value="<?= date('Y-m-d') ?>" required>
        </p>

        <p>
            <label>Оценка:</label>
            <select name="score" required>
                <option value="2">2 (неудовл.)</option>
                <option value="3">3 (удовл.)</option>
                <option value="4">4 (хорошо)</option>
                <option value="5" selected>5 (отлично)</option>
            </select>
        </p>

        <div class="buttons">
            <button type="submit">Сохранить</button>
            <a href="index.php?action=exams_read&student_id=<?= (int)$studentRecord['id'] ?>">Отмена</a>
        </div>
    </form>
</body>
</html>