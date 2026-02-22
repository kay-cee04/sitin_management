<?php
session_start();
require_once '../config/database.php';

// ── CHANGE THIS SECRET KEY TO YOUR OWN ──
define('ADMIN_SECRET_KEY', 'UC_SITIN_ADMIN_2024');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit();
}

$first_name      = trim($_POST['first_name'] ?? '');
$last_name       = trim($_POST['last_name'] ?? '');
$email           = trim($_POST['email'] ?? '');
$username        = trim($_POST['username'] ?? '');
$password        = $_POST['password'] ?? '';
$confirm_pass    = $_POST['confirm_password'] ?? '';
$secret_key      = $_POST['secret_key'] ?? '';

// Validation
if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password) || empty($secret_key)) {
    header('Location: ../index.php?error=' . urlencode('All fields are required.') . '&type=register');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../index.php?error=' . urlencode('Please enter a valid email address.') . '&type=register');
    exit();
}

if (strlen($username) < 4) {
    header('Location: ../index.php?error=' . urlencode('Username must be at least 4 characters.') . '&type=register');
    exit();
}

if (strlen($password) < 8) {
    header('Location: ../index.php?error=' . urlencode('Password must be at least 8 characters.') . '&type=register');
    exit();
}

if ($password !== $confirm_pass) {
    header('Location: ../index.php?error=' . urlencode('Passwords do not match.') . '&type=register');
    exit();
}

if ($secret_key !== ADMIN_SECRET_KEY) {
    header('Location: ../index.php?error=' . urlencode('Invalid admin secret key. Contact the system administrator.') . '&type=register');
    exit();
}

// Check if email or username already exists
$check = $conn->prepare("SELECT id FROM admins WHERE email = ? OR username = ?");
$check->bind_param("ss", $email, $username);
$check->execute();
$checkResult = $check->get_result();

if ($checkResult->num_rows > 0) {
    header('Location: ../index.php?error=' . urlencode('An account with that email or username already exists.') . '&type=register');
    exit();
}

// Hash password and insert
$full_name     = htmlspecialchars($first_name . ' ' . $last_name);
$hashed_pass   = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

$stmt = $conn->prepare("INSERT INTO admins (full_name, email, username, password, role) VALUES (?, ?, ?, ?, 'admin')");
$stmt->bind_param("ssss", $full_name, $email, $username, $hashed_pass);

if ($stmt->execute()) {
    header('Location: ../index.php?success=' . urlencode('Account created successfully! You can now log in.') . '&type=login');
} else {
    header('Location: ../index.php?error=' . urlencode('Registration failed. Please try again.') . '&type=register');
}
exit();
?>