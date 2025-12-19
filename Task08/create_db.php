<?php
require_once __DIR__ . '/src/db.php';

$pdo = getDatabaseConnection();

$pdo->exec("
    CREATE TABLE IF NOT EXISTS groups (
        id INTEGER PRIMARY KEY,
        number TEXT NOT NULL,
        program TEXT NOT NULL,
        graduation_year INTEGER NOT NULL
    )
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        group_id INTEGER NOT NULL,
        full_name TEXT NOT NULL,
        gender TEXT NOT NULL CHECK(gender IN ('М', 'Ж')),
        birth_date TEXT NOT NULL,
        student_id TEXT NOT NULL UNIQUE,
        FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE
    )
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS disciplines (
        id INTEGER PRIMARY KEY,
        program TEXT NOT NULL,
        course INTEGER NOT NULL,
        name TEXT NOT NULL,
        UNIQUE(program, course, name)
    )
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS exams (
        id INTEGER PRIMARY KEY,
        student_id INTEGER NOT NULL,
        discipline_id INTEGER NOT NULL,
        exam_date TEXT NOT NULL,
        score INTEGER NOT NULL CHECK(score BETWEEN 2 AND 5),
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (discipline_id) REFERENCES disciplines(id) ON DELETE CASCADE
    )
");

$groupRecords = [
    ['1', 'Программная инженерия', 2028],
    ['2', 'Программная инженерия', 2028],
];
$stmtGroup = $pdo->prepare("INSERT OR IGNORE INTO groups (number, program, graduation_year) VALUES (?, ?, ?)");
foreach ($groupRecords as $group) {
    $stmtGroup->execute($group);
}

$disciplineRecords = [
    ['Программная инженерия', 1, 'Дискретная математика'],
    ['Программная инженерия', 1, 'Объекто ориентированное программирование'],
    ['Программная инженерия', 2, 'Алгоритмы и структуры данных'],
    ['Программная инженерия', 2, 'Машинное обучение'],
    ['Программная инженерия', 3, 'Базы данных'],
    ['Программная инженерия', 3, 'Математическая логика'],
];
$stmtDiscipline = $pdo->prepare("INSERT OR IGNORE INTO disciplines (program, course, name) VALUES (?, ?, ?)");
foreach ($disciplineRecords as $discipline) {
    $stmtDiscipline->execute($discipline);
}

$studentRecords = [
    [1, 'Зубков Роман Сергеевич', 'М', '2004-06-15', '87654321'],
    [1, 'Иванов Максим Александрович', 'М', '2003-12-10', '87654322'],
    [1, 'Ивенин Артём Андреевич', 'М', '2005-03-22', '87654323'],
    [2, 'Казейкин Иван Иванович', 'М', '2004-09-05', '87654324'],
    [2, 'Колыганов Александр Павлович', 'М', '2003-08-01', '87654325'],
    [1, 'Кочнев Артем Алексеевич', 'М', '2005-01-12', '87654326'],
    [1, 'Логунов Илья Сергеевич', 'М', '2005-02-28', '87654327'],
    [1, 'Макарова Юлия Сергеевна', 'Ж', '2004-10-14', '87654328'],
    [2, 'Маклаков Сергей Алексеевич', 'М', '2003-05-22', '87654329'],
    [1, 'Маскинская Наталья Сергеевна', 'Ж', '2005-07-18', '87654330'],
    [1, 'Мукасеев Дмитрий Александрович', 'М', '2004-04-11', '87654331'],
    [1, 'Наумкин Владислав Валерьевич', 'М', '2003-11-25', '87654332'],
    [2, 'Паркаев Василий Александрович', 'М', '2004-08-13', '87654333'],
    [2, 'Полковников Дмитрий Александрович', 'М', '2005-12-20', '87654334'],
    [2, 'Пузаков Дмитрий Александрович', 'М', '2004-02-17', '87654335'],
    [2, 'Пшеницына Полина Алексеевна', 'Ж', '2004-01-07', '87654336'],
    [2, 'Пяткин Игорь Алексеевич', 'М', '2004-05-09', '87654337'],
    [1, 'Рыбаков Евгений Геннадьевич', 'М', '2005-09-30', '87654338'],
    [2, 'Рыжкин Владислав Дмитриевич', 'М', '2004-11-19', '87654339'],
    [1, 'Рябченко Александра Станиславовна', 'Ж', '2003-06-14', '87654340'],
    [2, 'Снегирев Данил Александрович', 'М', '2004-07-22', '87654341'],
    [2, 'Тульсков Илья Андреевич', 'М', '2005-10-12', '87654342'],
    [2, 'Фирстов Артём Александрович', 'М', '2003-03-15', '87654343'],
    [2, 'Четайкин Владислав Александрович', 'М', '2004-12-08', '87654344'],
    [2, 'Шарунов Максим Игоревич', 'М', '2005-04-03', '87654345'],
    [1, 'Шушев Денис Сергеевич', 'М', '2004-08-19', '87654346'],
];

$stmtStudent = $pdo->prepare("INSERT OR IGNORE INTO students (group_id, full_name, gender, birth_date, student_id) VALUES (?, ?, ?, ?, ?)");
foreach ($studentRecords as $student) {
    $stmtStudent->execute($student);
}