<?php
require_once 'db_connect.php';

$success_message = '';
$error_message = '';
$session_id = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = trim($_POST['course_id']);
    $group_id = trim($_POST['group_id']);
    $opened_by = trim($_POST['opened_by']);
    $date = date('Y-m-d');
    
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO attendance_sessions (course_id, group_id, date, opened_by, status) VALUES (?, ?, ?, ?, 'open')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$course_id, $group_id, $date, $opened_by]);
        
        $session_id = $conn->lastInsertId();
        $success_message = "Session created successfully! Session ID: " . $session_id;
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Session - Attendance System</title>
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
            max-width: 500px;
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

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #111;
            font-weight: 500;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #f0d4dc;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.2s ease;
            background: #fff;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #ff85a2;
            box-shadow: 0 0 0 3px rgba(255, 133, 162, 0.1);
        }

        button {
            background: var(--accent-light);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
            box-shadow: 0 2px 8px rgba(255, 133, 162, 0.2);
            margin-top: 10px;
        }

        button:hover {
            background: var(--accent);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 133, 162, 0.3);
        }

        .success {
            color: #075f26;
            background: rgba(122, 240, 139, 0.3);
            padding: 10px;
            border-radius: 8px;
            border: 1px solid rgba(122, 240, 139, 0.6);
            margin-bottom: 15px;
            text-align: center;
            font-weight: 600;
        }

        .error {
            color: #7a0a0a;
            background: rgba(207, 68, 68, 0.2);
            padding: 10px;
            border-radius: 8px;
            border: 1px solid rgba(207, 68, 68, 0.4);
            margin-bottom: 15px;
            text-align: center;
        }

        .session-id {
            background: rgba(122, 240, 139, 0.2);
            border: 2px solid rgba(122, 240, 139, 0.6);
            color: #075f26;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Attendance System</h1>
        <nav>
           <a href="index.html">Home</a>        
           <a href="add_student_db.php">Add Student (db)</a>     
           <a href="add_student.php">Add Student (jk)</a>        
           <a href="list_students.php">List Students</a>
           <a href="take_attendance.php">Take Attendance</a>
           <a href="create_session.php">Create Session</a>
           <a href="close_session.php">Close Session</a>
        </nav>
    </header>

    <main>
        <h2>Create Attendance Session</h2>
        
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
            <?php if ($session_id): ?>
                <div class="session-id">Session ID: <?php echo $session_id; ?></div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="course_id">Course ID:</label>
                <input type="text" id="course_id" name="course_id" required>
            </div>
            <div class="form-group">
                <label for="group_id">Group ID:</label>
                <input type="text" id="group_id" name="group_id" required>
            </div>
            <div class="form-group">
                <label for="opened_by">Professor ID:</label>
                <input type="text" id="opened_by" name="opened_by" required>
            </div>
            <button type="submit">Create Session</button>
        </form>
    </main>
</body>
</html>