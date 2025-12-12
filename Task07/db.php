<?php
$databasePath = __DIR__ . '/university.db';
$connection = new PDO('sqlite:' . $databasePath, null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
$connection->exec('PRAGMA foreign_keys = ON;');

$tableCheck = $connection->query("SELECT 1 FROM sqlite_master WHERE type='table' AND name='groups'")->fetch();
if (!$tableCheck) {
    $schema = "
        PRAGMA foreign_keys = ON;
        DROP TABLE IF EXISTS students;
        DROP TABLE IF EXISTS groups;
        CREATE TABLE groups (
            id INTEGER PRIMARY KEY,
            number TEXT NOT NULL,
            program TEXT NOT NULL,
            graduation_year INTEGER NOT NULL
        );
        CREATE TABLE students (
            id INTEGER PRIMARY KEY,
            full_name TEXT NOT NULL,
            gender TEXT NOT NULL CHECK (gender IN ('М', 'Ж')),
            birth_date TEXT NOT NULL,
            student_card_number TEXT NOT NULL,
            group_id INTEGER NOT NULL,
            FOREIGN KEY (group_id) REFERENCES groups(id)
        );
        INSERT INTO groups (number, program, graduation_year) VALUES
        ('1', 'Программная инженерия', 2025),
        ('2', 'Программная инженерия', 2026);
        INSERT INTO students (full_name, gender, birth_date, student_card_number, group_id) VALUES
        ('Зубков Роман Сергеевич', 'М', '2005-10-03', '09', 1),
        ('Иванов Максим Александрович', 'М', '2005-11-21', '12', 1),
        ('Ивенин Артём Андреевич', 'М', '2006-01-06', '13', 1),
        ('Казейкин Иван Иванович', 'М', '2006-03-24', '14', 2),
        ('Колыганов Александр Павлович', 'М', '2005-05-01', '15', 2),
        ('Кочнев Артем Алексеевич', 'М', '2005-27-07', '16', 1),
        ('Логунов Илья Сергеевич', 'М', '2005-02-13', '17', 1),
        ('Макарова Юлия Сергеевна', 'Ж', '2005-05-19', '18', 1),
        ('Маклаков Сергей Александрович', 'М', '2005-03-25', '19', 2),
        ('Маскинскова Наталья Сергеевна', 'Ж', '2005-11-09', '20', 1),
        ('Мукасеев Дмитрий Александрович', 'М', '2005-12-01', '21', 1),
        ('Наумкин Владислав Валерьевич', 'М', '2005-12-24', '22', 1),
        ('Паркаев Василий Александрович', 'М', '2005-10-28', '23', 2),
        ('Полковников Дмитрий Александрович', 'М', '2006-01-22', '24', 2),
        ('Пузаков Дмитрий Александрович', 'М', '2005-08-01', '25', 2),
        ('Пшеницына Полина Алексеевна', 'Ж', '2005-10-05', '26', 2),
        ('Пяткин Игорь Алексеевич', 'М', '2005-02-21', '27', 2),
        ('Рыбаков Евгений Геннадьевич', 'М', '2005-03-15', '28', 1),
        ('Рыжкин Владислав Дмитриевич', 'М', '2005-08-19', '29', 2),
        ('Рябченко Александра Станиславовна', 'Ж', '2005-10-12', '30', 1),
        ('Снегирев Данил Александрович', 'М', '2005-12-08', '31', 2),
        ('Тульсков Илья Андреевич', 'М', '2005-11-01', '32', 2),
        ('Фирстов Артём Александрович', 'М', '2005-12-22', '33', 2),
        ('Четайкин Владислав Александрович', 'М', '2005-09-07', '34', 2),
        ('Шарунов Максим Игоревич', 'М', '2005-05-06', '35', 2),
        ('Шушев Денис Сергеевич', 'М', '2005-08-27', '36', 1);
    ";
    
    $statements = explode(';', $schema);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        $statement = preg_replace('/--.*$/', '', $statement);
        $statement = trim($statement);
        if ($statement !== '') {
            $connection->exec($statement);
        }
    }
}

return $connection;