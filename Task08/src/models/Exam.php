<?php

class Exam
{
    public static function findByStudent($studentId)
    {
        $pdo = getDatabaseConnection();
        $sql = "
            SELECT e.*, d.name AS discipline_name, d.course
            FROM exams e
            JOIN disciplines d ON e.discipline_id = d.id
            WHERE e.student_id = ?
            ORDER BY e.exam_date
        ";
        $statement = $pdo->prepare($sql);
        $statement->execute([$studentId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getDisciplinesForStudent($studentId)
    {
        $pdo = getDatabaseConnection();
        $currentYear = (int) date('Y');

        $statement = $pdo->prepare("
            SELECT g.program, g.graduation_year
            FROM students s
            JOIN groups g ON s.group_id = g.id
            WHERE s.id = ?
        ");
        $statement->execute([$studentId]);
        $groupInfo = $statement->fetch();

        if (!$groupInfo) {
            return [];
        }

        $programName = $groupInfo['program'];
        $graduationYear = (int) $groupInfo['graduation_year'];
        $currentCourse = $graduationYear - $currentYear + 1;
        $currentCourse = max(1, min(4, $currentCourse));

        $statement = $pdo->prepare("
            SELECT id, name, course
            FROM disciplines
            WHERE program = ? AND course <= ?
            ORDER BY course, name
        ");
        $statement->execute([$programName, $currentCourse]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function store($requestData)
    {
        $pdo = getDatabaseConnection();
        $statement = $pdo->prepare("
            INSERT INTO exams (student_id, discipline_id, exam_date, score)
            VALUES (?, ?, ?, ?)
        ");
        $statement->execute([
            $requestData['student_id'],
            $requestData['discipline_id'],
            $requestData['exam_date'],
            $requestData['score']
        ]);
    }

    public static function destroy($examId)
    {
        $pdo = getDatabaseConnection();
        $pdo->prepare("DELETE FROM exams WHERE id = ?")->execute([$examId]);
    }

    public static function find($examId)
    {
        $pdo = getDatabaseConnection();
        $statement = $pdo->prepare("SELECT * FROM exams WHERE id = ?");
        $statement->execute([$examId]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($requestData)
    {
        $pdo = getDatabaseConnection();
        $statement = $pdo->prepare("
            UPDATE exams
            SET discipline_id = ?, exam_date = ?, score = ?
            WHERE id = ?
        ");

        try {
            $statement->execute([
                $requestData['discipline_id'],
                $requestData['exam_date'],
                $requestData['score'],
                (int) $requestData['id']
            ]);
        } catch (PDOException $exception) {
            die("Ошибка при обновлении экзамена: " . $exception->getMessage());
        }
    }
}