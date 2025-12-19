<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать экзамен</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 14px; }
        .form-group { margin: 10px 0; }
        label { display: inline-block; width: 150px; }
        input, select { padding: 4px 6px; }
        a { margin-left: 12px; color: #0066cc; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Редактировать экзамен</h1>

    <form method="POST" action="index.php?action=exam_update">
        <input type="hidden" name="id" value="<?= (int)$examRecord['id'] ?>">
        <input type="hidden" name="student_id" value="<?= (int)$studentRecord['id'] ?>">

        <div class="form-group">
            <label>Дисциплина:</label>
            <select name="discipline_id" required>
                <?php foreach ($disciplineOptions as $option): ?>
                    <option value="<?= (int)$option['id'] ?>" <?= $option['id'] == $examRecord['discipline_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($option['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Дата экзамена:</label>
            <input type="date" name="exam_date" value="<?= htmlspecialchars($examRecord['exam_date']) ?>" required>
        </div>

        <div class="form-group">
            <label>Оценка:</label>
            <select name="score" required>
                <option value="2" <?= $examRecord['score'] == 2 ? 'selected' : '' ?>>2</option>
                <option value="3" <?= $examRecord['score'] == 3 ? 'selected' : '' ?>>3</option>
                <option value="4" <?= $examRecord['score'] == 4 ? 'selected' : '' ?>>4</option>
                <option value="5" <?= $examRecord['score'] == 5 ? 'selected' : '' ?>>5</option>
            </select>
        </div>

        <button type="submit">Сохранить</button>
        <a href="index.php?action=exams_read&student_id=<?= (int)$studentRecord['id'] ?>">Отмена</a>
    </form>
</body>
</html>