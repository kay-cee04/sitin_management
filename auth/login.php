<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Basic validation
if (empty($username) || empty($password)) {
    header('Location: ../index.php?error=' . urlencode('Please fill in all fields.') . '&type=login');
    exit();
}

// Query admin by username or email
$stmt = $conn->prepare("SELECT id, full_name, username, email, password, role, is_active FROM admins WHERE username = ? OR email = ? LIMIT 1");
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ../index.php?error=' . urlencode('Invalid username or password.') . '&type=login');
    exit();
}

$admin = $result->fetch_assoc();

if (!$admin['is_active']) {
    header('Location: ../index.php?error=' . urlencode('Your account has been deactivated. Contact the system administrator.') . '&type=login');
    exit();
}

if (!password_verify($password, $admin['password'])) {
    header('Location: ../index.php?error=' . urlencode('Invalid username or password.') . '&type=login');
    exit();
}

// Update last login
$update = $conn->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
$update->bind_param("i", $admin['id']);
$update->execute();

// Set session
$_SESSION['admin_id']       = $admin['id'];
$_SESSION['admin_name']     = $admin['full_name'];
$_SESSION['admin_username'] = $admin['username'];
$_SESSION['admin_email']    = $admin['email'];
$_SESSION['admin_role']     = $admin['role'];

session_regenerate_id(true);

header('Location: ../dashboard.php');
exit();
?>