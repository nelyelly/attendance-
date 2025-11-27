<?php
// take_attendance.php - Exercise 2
date_default_timezone_set('UTC');
$today = date('Y-m-d');
$attendance_file = "attendance_$today.json";

// Check if attendance already taken for today
if (file_exists($attendance_file)) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Attendance Taken - Attendance System</title>
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
                text-align: center;
            }

            h2 {
                color: var(--accent);
                margin-bottom: 20px;
            }

            .error-message {
                color: #7a0a0a;
                background: rgba(207, 68, 68, 0.2);
                padding: 15px;
                border-radius: 8px;
                border: 1px solid rgba(207, 68, 68, 0.4);
                margin-bottom: 20px;
                font-weight: 600;
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
                box-shadow: 0 2px 8px rgba(255, 133, 162, 0.2);
                text-decoration: none;
                display: inline-block;
                margin-top: 10px;
            }

            button:hover {
                background: var(--accent);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(255, 133, 162, 0.3);
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Attendance System</h1>
            <nav>
                <a href="menu.php">Home</a>
                <a href="attendance_list.php">Attendance List</a>
                <a href="add_student.php">Add Student</a>
                <a href="reports.php">Reports</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>

        <main>
            <h2>Attendance Already Taken</h2>
            <div class="error-message">
                Attendance for today (<?php echo $today; ?>) has already been recorded.
            </div>
            <a href="add_student.php">
                <button type="button">Go to Add Students</button>
            </a>
        </main>
    </body>
    </html>
    <?php
    exit;
}

// Load students from students.json
$students = [];
if (file_exists('students.json')) {
    $json_data = file_get_contents('students.json');
    $students = json_decode($json_data, true) ?: [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendance = [];
    foreach ($_POST['attendance'] as $student_id => $status) {
        $attendance[] = [
            'student_id' => $student_id,
            'status' => $status
        ];
    }
    
    // Save attendance to today's file
    file_put_contents($attendance_file, json_encode($attendance, JSON_PRETTY_PRINT));
    $success_message = "Attendance saved for $today!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Take Attendance - Attendance System</title>
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

        .student-item {
            margin: 15px 0;
            padding: 15px;
            border: 1px solid #f0d4dc;
            border-radius: 8px;
            background: #f8f0f2;
            transition: all 0.2s ease;
        }

        .student-item:hover {
            border-color: #ff85a2;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 133, 162, 0.1);
        }

        .student-info {
            font-weight: 600;
            margin-bottom: 10px;
            color: #111;
        }

        .attendance-options {
            display: flex;
            gap: 20px;
        }

        .radio-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        input[type="radio"] {
            transform: scale(1.2);
            accent-color: var(--accent);
        }

        button {
            background: var(--accent-light);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 20px;
            width: 100%;
            box-shadow: 0 2px 8px rgba(255, 133, 162, 0.2);
        }

        button:hover {
            background: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 133, 162, 0.3);
        }

        .success {
            color: #075f26;
            background: rgba(122, 240, 139, 0.3);
            padding: 15px;
            border-radius: 8px;
            border: 1px solid rgba(122, 240, 139, 0.6);
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
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
        <h2>Take Attendance for <?php echo $today; ?></h2>
        
        <?php if (isset($success_message)): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (empty($students)): ?>
            <div class="empty-state">
                <p>No students found. Please add students first.</p>
                <a href="add_student.php">Go to Add Students</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <?php foreach ($students as $student): ?>
                    <div class="student-item">
                        <div class="student-info">
                            <?php echo htmlspecialchars($student['name']); ?> 
                            (ID: <?php echo htmlspecialchars($student['student_id']); ?>, 
                            Group: <?php echo htmlspecialchars($student['group']); ?>)
                        </div>
                        <div class="attendance-options">
                            <div class="radio-group">
                                <input type="radio" 
                                       id="present_<?php echo $student['student_id']; ?>" 
                                       name="attendance[<?php echo $student['student_id']; ?>]" 
                                       value="present" required>
                                <label for="present_<?php echo $student['student_id']; ?>">Present</label>
                            </div>
                            <div class="radio-group">
                                <input type="radio" 
                                       id="absent_<?php echo $student['student_id']; ?>" 
                                       name="attendance[<?php echo $student['student_id']; ?>]" 
                                       value="absent" required>
                                <label for="absent_<?php echo $student['student_id']; ?>">Absent</label>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <button type="submit">Submit Attendance</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>