<?php

require_once __DIR__ . '/db.php';

$database = require __DIR__ . '/db.php';

$currentYear = (int) date('Y');

$groupQuery = $database->prepare("SELECT DISTINCT number FROM groups WHERE graduation_year >= :currentYear ORDER BY number");
$groupQuery->execute(['currentYear' => $currentYear]);
$availableGroups = $groupQuery->fetchAll(PDO::FETCH_COLUMN);

$chosenGroup = $_GET['group'] ?? null;

if ($chosenGroup !== null && $chosenGroup !== '') {
    if (!in_array($chosenGroup, $availableGroups)) {
        http_response_code(400);
        die('Выбран некорректный номер группы.');
    }
}

$queryString = "
    SELECT 
        g.number AS group_number,
        g.program,
        s.full_name,
        s.gender,
        s.birth_date,
        s.student_card_number
    FROM students s
    JOIN groups g ON s.group_id = g.id
    WHERE g.graduation_year >= :currentYear
";

$queryParameters = ['currentYear' => $currentYear];

if ($chosenGroup !== null && $chosenGroup !== '') {
    $queryString .= " AND g.number = :groupNumber";
    $queryParameters['groupNumber'] = $chosenGroup;
}

$queryString .= " ORDER BY g.number, s.full_name";

$statement = $database->prepare($queryString);
$statement->execute($queryParameters);
$studentList = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список студентов</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        
        h1 {
            color: #333;
        }
        
        form {
            margin-bottom: 20px;
        }
        
        select, button {
            padding: 5px 10px;
            margin-right: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f0f0f0;
        }
        
        .student-count {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Список студентов</h1>
    
    <form method="GET">
        <label for="group">Группа:</label>
        <select name="group" id="group">
            <option value="">Все группы</option>
            <?php foreach ($availableGroups as $groupNum): ?>
                <option value="<?= htmlspecialchars($groupNum) ?>" <?= $chosenGroup === $groupNum ? 'selected' : '' ?>>
                    <?= htmlspecialchars($groupNum) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Показать</button>
        
        <?php if (!empty($studentList)): ?>
            <span class="student-count">
                <?php if ($chosenGroup && $chosenGroup !== ''): ?>
                    Студентов в группе: <?= count($studentList) ?>
                <?php else: ?>
                    Всего студентов: <?= count($studentList) ?>
                <?php endif; ?>
            </span>
        <?php endif; ?>
    </form>
    
    <?php if (!empty($studentList)): ?>
        <table>
            <thead>
                <tr>
                    <th>Группа</th>
                    <th>Направление</th>
                    <th>ФИО</th>
                    <th>Пол</th>
                    <th>Дата рождения</th>
                    <th>Номер билета</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($studentList as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['group_number']) ?></td>
                        <td><?= htmlspecialchars($student['program']) ?></td>
                        <td><?= htmlspecialchars($student['full_name']) ?></td>
                        <td><?= htmlspecialchars($student['gender']) ?></td>
                        <td><?= htmlspecialchars($student['birth_date']) ?></td>
                        <td><?= htmlspecialchars($student['student_card_number']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Студентов не найдено</p>
    <?php endif; ?>
</body>
</html>