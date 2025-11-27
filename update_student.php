<?php
require_once 'db_connect.php';

$student = null;
$success_message = '';
$error_message = '';

// Get student data
if (isset($_GET['id'])) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $student = $stmt->fetch();
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Update student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $fullname = trim($_POST['fullname']);
    $matricule = trim($_POST['matricule']);
    $group_id = trim($_POST['group_id']);
    
    try {
        $conn = getDBConnection();
        $sql = "UPDATE students SET fullname = ?, matricule = ?, group_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$fullname, $matricule, $group_id, $id]);
        
        $success_message = "Student updated successfully!";
        // Refresh student data
        $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

if (!$student) {
    die("Student not found!");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Student - Attendance System</title>
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
        <h2>Update Student</h2>
        
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
            
            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($student['fullname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="matricule">Matricule:</label>
                <input type="text" id="matricule" name="matricule" value="<?php echo htmlspecialchars($student['matricule']); ?>" required>
            </div>
            <div class="form-group">
                <label for="group_id">Group ID:</label>
                <input type="text" id="group_id" name="group_id" value="<?php echo htmlspecialchars($student['group_id']); ?>" required>
            </div>
            <button type="submit">Update Student</button>
        </form>
    </main>
</body>
</html>