<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Студенты</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 14px;
        }
        h1 {
            margin-top: 0;
            margin-bottom: 16px;
        }
        .filter-form {
            margin-bottom: 16px;
        }
        .filter-form label {
            margin-right: 10px;
        }
        .filter-form select,
        .filter-form button {
            padding: 4px 8px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .no-records {
            text-align: center;
            color: #666;
            padding: 12px;
        }
        .actions {
            margin-top: 16px;
        }
        .actions a {
            color: #0066cc;
            text-decoration: none;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        a.action-link {
            color: #0066cc;
            text-decoration: none;
            margin-right: 10px;
        }
        a.action-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Список обучающихся студентов</h1>

    <form method="GET" class="filter-form">
        <label>
            Фильтр по группе:
            <select name="group">
                <option value="">Все группы</option>
                <?php foreach ($allGroups as $groupNumber): ?>
                    <option value="<?= htmlspecialchars($groupNumber) ?>" <?= ($_GET['group'] ?? '') === $groupNumber ? 'selected' : '' ?>>
                        <?= htmlspecialchars($groupNumber) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit">Применить</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Группа</th>
                <th>Фамилия Имя Отчество</th>
                <th>Пол</th>
                <th>Номер билета</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($studentRecords)): ?>
                <tr>
                    <td colspan="5" class="no-records">Нет студентов</td>
                </tr>
            <?php else: ?>
                <?php foreach ($studentRecords as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['group_number']) ?></td>
                        <td><?= htmlspecialchars($student['full_name']) ?></td>
                        <td><?= $student['gender'] === 'М' ? 'Мужской' : 'Женский' ?></td>
                        <td><?= htmlspecialchars($student['student_id']) ?></td>
                        <td>
                            <a href="index.php?action=student_edit&id=<?= (int)$student['id'] ?>" class="action-link">Редактировать</a>
                            <a href="index.php?action=student_delete&id=<?= (int)$student['id'] ?>" class="action-link">Удалить</a>
                            <a href="index.php?action=exams_read&student_id=<?= (int)$student['id'] ?>" class="action-link">Посмотреть экзамены</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="actions">
        <a href="index.php?action=student_create">Добавить студента</a>
    </div>
</body>
</html>