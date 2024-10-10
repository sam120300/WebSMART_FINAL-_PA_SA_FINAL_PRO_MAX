<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $department = $_POST['department'];
    $curriculum = $_POST['curriculum'];
    $status = $_POST['status'];
    $password = $_POST['password'];

    // Sanitize inputs to prevent SQL injection
    $student_id = $conn->real_escape_string($student_id);
    $firstname = $conn->real_escape_string($firstname);
    $middlename = $conn->real_escape_string($middlename);
    $lastname = $conn->real_escape_string($lastname);
    $gender = $conn->real_escape_string($gender);
    $department = $conn->real_escape_string($department);
    $curriculum = $conn->real_escape_string($curriculum);
    $status = $conn->real_escape_string($status);
    $password = $conn->real_escape_string($password);

    // Encrypt the password using MD5
    $encrypted_password = md5($password);

    // Prepare the SQL query
    $qry = $conn->query("UPDATE student_list SET 
        firstname = '$firstname',
        middlename = '$middlename',
        lastname = '$lastname',
        gender = '$gender',
        department_id = (SELECT id FROM department_list WHERE id = '$department'),
        curriculum_id = (SELECT id FROM curriculum_list WHERE id = '$curriculum'),
        status = '$status'" . 
        ($password ? ", password = '$encrypted_password'" : "") . " 
        WHERE student_id = '$student_id'");

    if ($qry) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'error' => $conn->error]);
    }
}
?>
