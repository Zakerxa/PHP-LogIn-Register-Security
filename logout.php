<?php
session_destroy();
setcookie("auth", '', time() - 1, '/');
setcookie("token", '', time() - 1, '/');
header("location:index.php");