<?php
session_start();
session_destroy();
header('Location: ../index.php?success=' . urlencode('You have been successfully logged out.') . '&type=login');
exit();
?>