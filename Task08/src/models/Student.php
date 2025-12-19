<?php

class Student
{
    public static function all($groupNumber = null)
    {
        $pdo = getDatabaseConnection();
        $currentYear = (int) date('Y');

        $sql = "
            SELECT s.*, g.number AS group_number, g.program
            FROM students s
            JOIN groups g ON s.group_id = g.id
            WHERE g.graduation_year >= ?
        ";
        $parameters = [$currentYear];

        if ($groupNumber !== null) {
            $sql .= " AND g.number = ?";
            $parameters[] = $groupNumber;
        }

        $sql .= " ORDER BY g.number, s.full_name";
        $statement = $pdo->prepare($sql);
        $statement->execute($parameters);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getGroups()
    {
        $pdo = getDatabaseConnection();
        $currentYear = (int) date('Y');

        $statement = $pdo->prepare("
            SELECT DISTINCT number
            FROM groups
            WHERE graduation_year >= ?
            ORDER BY number
        ");
        $statement->execute([$currentYear]);
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function getGroupsForSelect()
    {
        $pdo = getDatabaseConnection();
        $currentYear = (int) date('Y');

        $statement = $pdo->prepare("
            SELECT id, number, program
            FROM groups
            WHERE graduation_year >= ?
            ORDER BY number
        ");
        $statement->execute([$currentYear]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($studentId)
    {
        $pdo = getDatabaseConnection();
        $statement = $pdo->prepare("SELECT * FROM students WHERE id = ?");
        $statement->execute([$studentId]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function store($requestData)
    {
        $pdo = getDatabaseConnection();
        $birthDate = $requestData['birth_date'] ?? date('Y-m-d', strtotime('-20 years'));

        $statement = $pdo->prepare("
            INSERT INTO students (group_id, full_name, gender, birth_date, student_id)
            VALUES (?, ?, ?, ?, ?)
        ");
        $statement->execute([
            $requestData['group_id'],
            $requestData['full_name'],
            $requestData['gender'],
            $birthDate,
            $requestData['student_id']
        ]);
    }

    public static function update($requestData)
    {
        $pdo = getDatabaseConnection();
        $statement = $pdo->prepare("
            UPDATE students
            SET group_id = ?, full_name = ?, gender = ?, student_id = ?
            WHERE id = ?
        ");
        $statement->execute([
            $requestData['group_id'],
            $requestData['full_name'],
            $requestData['gender'],
            $requestData['student_id'],
            $requestData['id']
        ]);
    }

    public static function destroy($studentId)
    {
        $pdo = getDatabaseConnection();
        $pdo->prepare("DELETE FROM students WHERE id = ?")->execute([$studentId]);
    }
}