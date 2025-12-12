<?php

require_once __DIR__ . '/db.php';

$database = require __DIR__ . '/db.php';

$currentYear = (int) date('Y');

$groupQuery = $database->prepare("SELECT DISTINCT number FROM groups WHERE graduation_year >= :currentYear ORDER BY number");
$groupQuery->execute(['currentYear' => $currentYear]);
$availableGroups = $groupQuery->fetchAll(PDO::FETCH_COLUMN);

$chosenGroup = $_GET['group'] ?? null;
if ($chosenGroup !== null && !in_array($chosenGroup, $availableGroups)) {
    http_response_code(400);
    die('Выбран некорректный номер группы.');
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

if ($chosenGroup !== null) {
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
            background-color: #f4f4f4;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #2c5282;
            margin-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }
        
        .filter {
            background-color: #edf2f7;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .filter label {
            font-weight: bold;
            margin-right: 10px;
        }
        
        select, button {
            padding: 8px 12px;
            margin-right: 10px;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            font-size: 14px;
        }
        
        button {
            background-color: #4299e1;
            color: white;
            border: none;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #3182ce;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th {
            background-color: #2c5282;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: normal;
        }
        
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        tr:nth-child(even) {
            background-color: #f7fafc;
        }
        
        tr:hover {
            background-color: #ebf8ff;
        }
        
        .group-badge {
            background-color: #bee3f8;
            color: #2c5282;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #718096;
            font-size: 16px;
        }
        
        .student-count {
            color: #2c5282;
            font-weight: bold;
            margin-left: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Список студентов</h1>
        
        <div class="filter">
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
                
                <?php if ($chosenGroup): ?>
                    <span class="student-count">Студентов: <?= count($studentList) ?></span>
                <?php endif; ?>
            </form>
        </div>
        
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
                            <td><span class="group-badge"><?= htmlspecialchars($student['group_number']) ?></span></td>
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
            <div class="no-data">
                <p>Студентов не найдено</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>