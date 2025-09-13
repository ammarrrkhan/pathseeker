<?php
include "db.php"; // includes the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $firstName = $conn->real_escape_string(trim($_POST['firstName']));
    $lastName  = $conn->real_escape_string(trim($_POST['lastName']));
    $email     = $conn->real_escape_string(trim($_POST['email']));
    $phone     = $conn->real_escape_string(trim($_POST['phone']));
    $job       = $conn->real_escape_string(trim($_POST['job']));

    // Handle file upload
    $resumeFile = "";
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate a unique file name to prevent overwrite
        $fileExtension = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
        $uniqueFileName = uniqid('resume_', true) . '.' . $fileExtension;
        $resumeFile = $uploadDir . $uniqueFileName;

        if (!move_uploaded_file($_FILES['resume']['tmp_name'], $resumeFile)) {
            die("Failed to upload resume.");
        }
    }

    // Use prepared statements for secure insertion
    $stmt = $conn->prepare("INSERT INTO applications (first_name, last_name, email, phone, job, resume) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("ssssss", $firstName, $lastName, $email, $phone, $job, $resumeFile);

        if ($stmt->execute()) {
            // Redirect to careers.php after success
            // Optional: You can pass a success flag or message via GET
            header("Location: careers.php?submitted=success");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
}
?>
