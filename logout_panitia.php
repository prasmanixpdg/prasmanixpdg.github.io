<?php
session_start();
session_destroy();
header('Location: panitia.php');
exit;
?>