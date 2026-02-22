<?php
define('DB_HOST', '127.0.0.1');  // Use IP instead of 'localhost'
define('DB_USER', 'root');
define('DB_PASS', '');           // Leave empty if no password set in XAMPP
define('DB_NAME', 'sitin_management');
define('DB_PORT', 3308);         // Your XAMPP MySQL port

mysqli_report(MYSQLI_REPORT_OFF); // Disable exceptions, handle manually

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($conn->connect_error) {
    die('
    <!DOCTYPE html>
    <html>
    <head>
        <title>Database Error</title>
        <style>
            body { font-family: Arial, sans-serif; background: #0A0E1A; color: #fff; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            .box { background: #1a1a2e; border: 1px solid #F5A623; border-radius: 12px; padding: 40px; max-width: 500px; text-align: center; }
            h2 { color: #F5A623; margin-bottom: 16px; }
            p { color: rgba(255,255,255,0.6); font-size: 14px; line-height: 1.6; }
            code { background: rgba(255,255,255,0.1); padding: 2px 8px; border-radius: 4px; color: #F5A623; }
            .err { color: #F87171; font-size: 13px; margin-top: 12px; background: rgba(239,68,68,0.1); padding: 10px; border-radius: 8px; }
        </style>
    </head>ss
    <body>
        <div class="box">
            <h2>⚠️ Database Connection Failed</h2>
            <p>Could not connect to MySQL. Please make sure:</p>
            <p>✅ MySQL is <strong>running</strong> in XAMPP<br>
               ✅ Database <code>sitin_management</code> exists<br>
               ✅ You imported <code>setup.sql</code> in phpMyAdmin</p>
            <div class="err">Error: ' . $conn->connect_error . '</div>
        </div>
    </body>
    </html>');
}

$conn->set_charset("utf8mb4");
?>