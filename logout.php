 <?php
session_start();
session_destroy();
header("Location: logout_success.php");
exit;
?>