<?php
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header('Location: list_students.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        die("Error deleting student: " . $e->getMessage());
    }
} else {
    header('Location: list_students.php');
    exit;
}
?>