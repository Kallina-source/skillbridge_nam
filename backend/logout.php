<?php
session_start();
session_destroy();
header("Location: http://localhost/skillbridge_nam/frontend/pages/index.php");
exit();
?>