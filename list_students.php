
<?php
require_once 'db_connect.php';

try {
    $conn = getDBConnection();
    $stmt = $conn->query("SELECT * FROM students ORDER BY id DESC");
    $students = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
    $students = [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>List Students - Attendance System</title>
    <style>
        :root {
            --green: #7ce38b;
            --yellow: #ffd966;
            --red: #ff8080;
            --accent: #2d5a4d;
            --accent-light: #4a9b82;
            --muted: #666;
            --bg: #fff9fa;
        }
        
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: #111;
        }

        header {
            background: var(--accent);
            color: #fff;
            padding: 14px 20px;
            border-bottom: 3px solid #ff85a2;
        }

        header h1 {
            margin: 0;
            font-size: 1.3rem;
        }

        nav a {
            color: #fff;
            margin-right: 12px;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        nav a:hover {
            color: #ffb6c1;
        }

        main {
            max-width: 1100px;
            margin: 18px auto;
            padding: 25px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(255, 133, 162, 0.12);
            border: 1px solid #ffe6ea;
        }

        h2 {
            color: var(--accent);
            border-bottom: 3px solid #ffb6c1;
            padding-bottom: 8px;
            margin-top: 0;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
            margin-bottom: 10px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
        }

        .students-table th,
        .students-table td {
            border: 1px solid #f0d4dc;
            padding: 8px;
            text-align: left;
            background: transparent;
        }

        .students-table thead th {
            background: #f8f0f2;
            color: var(--accent);
            font-weight: 600;
        }

        .students-table tbody tr:hover {
            outline: 2px solid rgba(248, 113, 145, 0.541);
            transform: translateY(-1px);
            transition: all 0.12s ease;
            background: rgba(151, 1, 23, 0.05) !important;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-edit {
            background: var(--accent-light);
            color: white;
        }

        .btn-delete {
            background: var(--red);
            color: white;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .btn-edit:hover {
            background: var(--accent);
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--muted);
            background: #f8f0f2;
            border-radius: 8px;
            border: 1px solid #f0d4dc;
        }

        .empty-state a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }

        .empty-state a:hover {
            color: var(--accent-light);
        }

        .error {
            color: #7a0a0a;
            background: rgba(207, 68, 68, 0.2);
            padding: 15px;
            border-radius: 8px;
            border: 1px solid rgba(207, 68, 68, 0.4);
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }

        /* Table header rounded corners */
        .students-table thead th:first-child {
            border-top-left-radius: 8px;
        }

        .students-table thead th:last-child {
            border-top-right-radius: 8px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Attendance System</h1>
        <nav>
           <a href="index.html">Home</a>        
           <a href="#add-student">Add Student</a>
           <a href="add_student_db.php">Add Student (db)</a>     
           <a href="add_student.php">Add Student (jk)</a>        
           <a href="list_students.php">List Students</a>
           <a href="take_attendance.php">Take Attendance</a>
           <a href="create_session.php">Create Session</a>
           <a href="close_session.php">Close Session</a>
        </nav>
    </header>

    <main>
        <h2>Students List</h2>
        
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if (empty($students)): ?>
            <div class="empty-state">
                <p>No students found in database.</p>
                <a href="add_student_db.php">Add First Student</a>
            </div>
        <?php else: ?>
            <table class="students-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Matricule</th>
                        <th>Group</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['id']; ?></td>
                        <td><?php echo htmlspecialchars($student['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($student['matricule']); ?></td>
                        <td><?php echo htmlspecialchars($student['group_id']); ?></td>
                        <td><?php echo $student['created_at']; ?></td>
                        <td class="action-buttons">
                            <a href="update_student.php?id=<?php echo $student['id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="delete_student.php?id=<?php echo $student['id']; ?>" class="btn btn-delete" onclick="return confirm('Delete this student?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>