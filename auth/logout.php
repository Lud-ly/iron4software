<?php
require_once '../config/security.php';
Security::logout();
header('Location: /iron4software/');
exit;
?>
