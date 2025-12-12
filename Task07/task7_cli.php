<?php
require_once __DIR__ . '/db.php';

$database = require __DIR__ . '/db.php';

$currentYear = (int) date('Y');

$groupStatement = $database->prepare("
    SELECT DISTINCT number FROM groups 
    WHERE graduation_year >= :currentYear 
    ORDER BY number
");
$groupStatement->execute(['currentYear' => $currentYear]);
$availableGroups = $groupStatement->fetchAll(PDO::FETCH_COLUMN);

if (empty($availableGroups)) {
    echo "Нет действующих групп.\n";
    exit;
}

echo "Доступные номера групп:\n";
foreach ($availableGroups as $groupNumber) {
    echo "- $groupNumber\n";
}
echo "\nВведите номер группы (или нажмите Enter для всех): ";

$inputStream = fopen("php://stdin", "r");
$userInput = trim(fgets($inputStream));
fclose($inputStream);

if ($userInput !== '') {
    if (!in_array($userInput, $availableGroups)) {
        echo "Ошибка: группы с таким номером не существует.\n";
        exit(1);
    }
}

$query = "
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

$queryParams = ['currentYear' => $currentYear];

if ($userInput !== '') {
    $query .= " AND g.number = :groupNumber";
    $queryParams['groupNumber'] = $userInput;
}

$query .= " ORDER BY g.number, s.full_name";

$dataStatement = $database->prepare($query);
$dataStatement->execute($queryParams);
$studentData = $dataStatement->fetchAll();

if (empty($studentData)) {
    echo "Нет студентов.\n";
    exit;
}

if (!function_exists('mb_str_pad')) {
    function mb_str_pad($text, $padLength, $padString = ' ', $padType = STR_PAD_RIGHT, $encoding = 'UTF-8') {
        $textLength = mb_strlen($text, $encoding);
        $padStringLength = mb_strlen($padString, $encoding);

        if (!$textLength && ($padType == STR_PAD_RIGHT || $padType == STR_PAD_LEFT)) {
            $textLength = 1;
        }

        if ($padLength <= $textLength) {
            return $text;
        }

        $resultText = '';
        switch ($padType) {
            case STR_PAD_RIGHT:
                $resultText = $text . str_repeat($padString, ceil(($padLength - $textLength) / $padStringLength));
                break;
            case STR_PAD_LEFT:
                $resultText = str_repeat($padString, ceil(($padLength - $textLength) / $padStringLength)) . $text;
                break;
            case STR_PAD_BOTH:
                $leftPad = floor(($padLength - $textLength) / 2);
                $rightPad = ceil(($padLength - $textLength) / 2);
                $resultText = str_repeat($padString, $leftPad) . $text . str_repeat($padString, $rightPad);
                break;
        }

        return mb_substr($resultText, 0, $padLength, $encoding);
    }
}

function displayTable($tableData) {
    if (empty($tableData)) {
        echo "Нет данных для отображения.\n";
        return;
    }

    $columnHeaders = ['Группа', 'Напр. подготовки', 'ФИО', 'Пол', 'Дата рожд.', 'Студ. билет'];
    $columnCount = count($columnHeaders);

    $columnWidths = [];
    for ($columnIndex = 0; $columnIndex < $columnCount; $columnIndex++) {
        $maxWidth = mb_strlen($columnHeaders[$columnIndex], 'UTF-8');
        foreach ($tableData as $dataRow) {
            $cellValue = '';
            switch ($columnIndex) {
                case 0: $cellValue = $dataRow['group_number']; break;
                case 1: $cellValue = $dataRow['program']; break;
                case 2: $cellValue = $dataRow['full_name']; break;
                case 3: $cellValue = $dataRow['gender']; break;
                case 4: $cellValue = $dataRow['birth_date']; break;
                case 5: $cellValue = $dataRow['student_card_number']; break;
            }
            $maxWidth = max($maxWidth, mb_strlen($cellValue, 'UTF-8'));
        }
        $columnWidths[$columnIndex] = min($maxWidth, 40);
    }

    $columnWidths[1] = max($columnWidths[1], 22);

    $topBorder = '┌' . implode('┬', array_map(fn($width) => str_repeat('─', $width + 2), $columnWidths)) . '┐';
    $middleBorder = '├' . implode('┼', array_map(fn($width) => str_repeat('─', $width + 2), $columnWidths)) . '┤';
    $bottomBorder = '└' . implode('┴', array_map(fn($width) => str_repeat('─', $width + 2), $columnWidths)) . '┘';

    echo $topBorder . "\n";

    echo '│';
    for ($columnIndex = 0; $columnIndex < $columnCount; $columnIndex++) {
        $headerCell = mb_str_pad($columnHeaders[$columnIndex], $columnWidths[$columnIndex], ' ', STR_PAD_BOTH, 'UTF-8');
        echo " $headerCell │";
    }
    echo "\n$middleBorder\n";

    foreach ($tableData as $dataRow) {
        echo '│';
        for ($columnIndex = 0; $columnIndex < $columnCount; $columnIndex++) {
            $cellValue = '';
            switch ($columnIndex) {
                case 0: $cellValue = $dataRow['group_number']; break;
                case 1: $cellValue = $dataRow['program']; break;
                case 2: $cellValue = $dataRow['full_name']; break;
                case 3: $cellValue = $dataRow['gender']; break;
                case 4: $cellValue = $dataRow['birth_date']; break;
                case 5: $cellValue = $dataRow['student_card_number']; break;
            }

            if (mb_strlen($cellValue, 'UTF-8') > $columnWidths[$columnIndex]) {
                $cellValue = mb_substr($cellValue, 0, $columnWidths[$columnIndex] - 3, 'UTF-8') . '...';
            }

            $formattedCell = mb_str_pad($cellValue, $columnWidths[$columnIndex], ' ', STR_PAD_RIGHT, 'UTF-8');
            echo " $formattedCell │";
        }
        echo "\n";
    }

    echo $bottomBorder . "\n";
}

displayTable($studentData);