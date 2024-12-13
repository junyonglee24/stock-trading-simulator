<?php
session_start();
session_destroy(); 
header("Location: /stock-trading-simulator/php/auth/login.php"); 
exit;
?>