<?php
require_once 'db_connect.php';

$success_message = '';
$error_message = '';

// Get open sessions
try {
    $conn = getDBConnection();
    $stmt = $conn->query("SELECT * FROM attendance_sessions WHERE status = 'open' ORDER BY id DESC");
    $open_sessions = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
    $open_sessions = [];
}

// Close session
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['session_id'])) {
    $session_id = $_POST['session_id'];
    
    try {
        $conn = getDBConnection();
        $sql = "UPDATE attendance_sessions SET status = 'closed' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$session_id]);
        
        $success_message = "Session closed successfully!";
        // Refresh open sessions
        $stmt = $conn->query("SELECT * FROM attendance_sessions WHERE status = 'open' ORDER BY id DESC");
        $open_sessions = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Close Session - Attendance System</title>
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
            max-width: 800px;
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

        .session-item {
            margin: 15px 0;
            padding: 15px;
            border: 1px solid #f0d4dc;
            border-radius: 8px;
            background: #f8f0f2;
            transition: all 0.2s ease;
        }

        .session-item:hover {
            border-color: #ff85a2;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 133, 162, 0.1);
        }

        .session-info {
            margin-bottom: 10px;
        }

        .session-info strong {
            color: var(--accent);
        }

        button {
            background: var(--red);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        button:hover {
            background: #e53e3e;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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

        form {
            margin: 0;
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
        <h2>Close Attendance Session</h2>
        
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if (empty($open_sessions)): ?>
            <div class="empty-state">
                <p>No open sessions found.</p>
                <a href="create_session.php">Create New Session</a>
            </div>
        <?php else: ?>
            <?php foreach ($open_sessions as $session): ?>
                <div class="session-item">
                    <div class="session-info">
                        <strong>Session ID:</strong> <?php echo $session['id']; ?><br>
                        <strong>Course:</strong> <?php echo htmlspecialchars($session['course_id']); ?><br>
                        <strong>Group:</strong> <?php echo htmlspecialchars($session['group_id']); ?><br>
                        <strong>Date:</strong> <?php echo $session['date']; ?><br>
                        <strong>Opened by:</strong> <?php echo $session['opened_by']; ?>
                    </div>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="session_id" value="<?php echo $session['id']; ?>">
                        <button type="submit" onclick="return confirm('Close this session?')">Close Session</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>