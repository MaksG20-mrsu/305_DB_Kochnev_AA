<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!file_exists(__DIR__ . '/../data/university.db')) {
    require_once __DIR__ . '/../create_db.php';
}

require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/models/Student.php';
require_once __DIR__ . '/../src/models/Exam.php';

$currentAction = $_GET['action'] ?? 'students_read';

$allowedActions = [
    'students_read', 'student_create', 'student_store', 'student_edit', 'student_update',
    'student_delete', 'student_destroy',
    'exams_read', 'exam_create', 'exam_store', 'exam_edit', 'exam_update',
    'exam_delete', 'exam_destroy'
];

if (!in_array($currentAction, $allowedActions, true)) {
    $currentAction = 'students_read';
}

if ($_POST) {
    if ($currentAction === 'student_store') {
        Student::store($_POST);
        header("Location: index.php");
        exit;
    } elseif ($currentAction === 'student_update') {
        Student::update($_POST);
        header("Location: index.php");
        exit;
    } elseif ($currentAction === 'student_destroy') {
        Student::destroy($_POST['id']);
        header("Location: index.php");
        exit;
    } elseif ($currentAction === 'exam_store') {
        Exam::store($_POST);
        $redirectStudentId = $_POST['student_id'];
        header("Location: index.php?action=exams_read&student_id=" . urlencode($redirectStudentId));
        exit;
    } elseif ($currentAction === 'exam_destroy') {
        $examRecord = Exam::find($_POST['id']);
        $redirectStudentId = $examRecord['student_id'];
        Exam::destroy($_POST['id']);
        header("Location: index.php?action=exams_read&student_id=" . urlencode($redirectStudentId));
        exit;
    } elseif ($currentAction === 'exam_update') {
        Exam::update($_POST);
        $redirectStudentId = $_POST['student_id'];
        header("Location: index.php?action=exams_read&student_id=" . urlencode($redirectStudentId));
        exit;
    }
}

switch ($currentAction) {
    case 'student_create':
        require_once __DIR__ . '/../src/views/students/create.php';
        break;

    case 'student_edit':
        $studentId = $_GET['id'] ?? null;
        if (!$studentId || !is_numeric($studentId)) {
            die("Некорректный ID студента");
        }
        $studentRecord = Student::find((int)$studentId);
        if (!$studentRecord) {
            die("Студент не найден");
        }
        $groupOptions = Student::getGroupsForSelect();
        require_once __DIR__ . '/../src/views/students/edit.php';
        break;

    case 'student_delete':
        $studentId = $_GET['id'] ?? null;
        if (!$studentId || !is_numeric($studentId)) {
            die("Некорректный ID студента");
        }
        $studentRecord = Student::find((int)$studentId);
        if (!$studentRecord) {
            die("Студент не найден");
        }
        require_once __DIR__ . '/../src/views/students/delete.php';
        break;

    case 'exams_read':
        $studentId = $_GET['student_id'] ?? null;
        if (!$studentId || !is_numeric($studentId)) {
            die("Некорректный ID студента");
        }
        $studentRecord = Student::find((int)$studentId);
        if (!$studentRecord) {
            die("Студент не найден");
        }
        $examRecords = Exam::findByStudent((int)$studentId);
        require_once __DIR__ . '/../src/views/exams/read.php';
        break;

    case 'exam_create':
        $studentId = $_GET['student_id'] ?? null;
        if (!$studentId || !is_numeric($studentId)) {
            die("Некорректный ID студента");
        }
        $studentRecord = Student::find((int)$studentId);
        if (!$studentRecord) {
            die("Студент не найден");
        }
        $disciplineOptions = Exam::getDisciplinesForStudent((int)$studentId);
        require_once __DIR__ . '/../src/views/exams/create.php';
        break;

    case 'exam_delete':
        $examId = $_GET['id'] ?? null;
        if (!$examId || !is_numeric($examId)) {
            die("Некорректный ID экзамена");
        }
        $examRecord = Exam::find((int)$examId);
        if (!$examRecord) {
            die("Экзамен не найден");
        }
        $studentRecord = Student::find($examRecord['student_id']);
        if (!$studentRecord) {
            die("Студент не найден");
        }
        require_once __DIR__ . '/../src/views/exams/delete.php';
        break;

    case 'exam_edit':
        $examId = $_GET['id'] ?? null;
        if (!$examId || !is_numeric($examId)) {
            die("Некорректный ID экзамена");
        }
        $examRecord = Exam::find((int)$examId);
        if (!$examRecord) {
            die("Экзамен не найден");
        }
        $studentRecord = Student::find($examRecord['student_id']);
        if (!$studentRecord) {
            die("Студент не найден");
        }
        $disciplineOptions = Exam::getDisciplinesForStudent($studentRecord['id']);
        require_once __DIR__ . '/../src/views/exams/edit.php';
        break;

    default: 
        $filterGroup = $_GET['group'] ?? null;
        if ($filterGroup === '') {
            $filterGroup = null;
        }
        $studentRecords = Student::all($filterGroup);
        $allGroups = Student::getGroups();
        require_once __DIR__ . '/../src/views/students/read.php';
        break;
}