<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Удалить экзамен</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 14px; }
        h1 { margin-bottom: 16px; }
        p { margin: 8px 0; }
        form { margin-top: 16px; }
        a { margin-left: 12px; color: #0066cc; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Удалить экзамен</h1>
    <p>Вы действительно хотите удалить экзамен?</p>
    <p><strong><?= htmlspecialchars($examRecord['discipline_name']) ?></strong></p>

    <form method="POST" action="index.php?action=exam_destroy">
        <input type="hidden" name="id" value="<?= (int)$examRecord['id'] ?>">
        <button type="submit">Да, удалить</button>
        <a href="index.php?action=exams_read&student_id=<?= (int)$studentRecord['id'] ?>">Отмена</a>
    </form>
</body>
</html>