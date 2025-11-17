<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $student_id = trim($_POST['student_id']);
    $last_name = trim($_POST['last_name']);
    $first_name = trim($_POST['first_name']);
    $email = trim($_POST['email']);
    
    $errors = [];
    
    // Validate Student ID (not empty and only numbers)
    if (empty($student_id)) {
        $errors['student_id'] = "Student ID is required";
    } elseif (!preg_match('/^\d+$/', $student_id)) {
        $errors['student_id'] = "Student ID must contain only numbers";
    }
    
    // Validate Last Name (not empty and only letters)
    if (empty($last_name)) {
        $errors['last_name'] = "Last Name is required";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $last_name)) {
        $errors['last_name'] = "Last Name must contain only letters";
    }
    
    // Validate First Name (not empty and only letters)
    if (empty($first_name)) {
        $errors['first_name'] = "First Name is required";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $first_name)) {
        $errors['first_name'] = "First Name must contain only letters";
    }
    
    // Validate Email (not empty and valid format)
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }
    
    // Check if there are any errors
    if (empty($errors)) {
        // All validation passed - success!
        $response = [
            'status' => 'success',
            'message' => 'Student added successfully!',
            'data' => [
                'student_id' => $student_id,
                'last_name' => $last_name,
                'first_name' => $first_name,
                'email' => $email
            ]
        ];
    } else {
        // There are validation errors
        $response = [
            'status' => 'error',
            'message' => 'Please fix the following errors:',
            'errors' => $errors
        ];
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
