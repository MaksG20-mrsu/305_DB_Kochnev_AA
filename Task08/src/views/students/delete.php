<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Удалить студента</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 14px;
        }
        h1 {
            margin-bottom: 16px;
        }
        p {
            margin: 8px 0;
        }
        form {
            margin-top: 16px;
        }
        a {
            margin-left: 12px;
            color: #0066cc;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Подтверждение удаления</h1>
    <p>Вы уверены, что хотите удалить студента?</p>
    <p><strong><?= htmlspecialchars($studentRecord['full_name']) ?></strong></p>

    <form method="POST" action="index.php?action=student_destroy">
        <input type="hidden" name="id" value="<?= (int)$studentRecord['id'] ?>">
        <button type="submit">Да, удалить</button>
        <a href="index.php">Отмена</a>
    </form>
</body>
</html>