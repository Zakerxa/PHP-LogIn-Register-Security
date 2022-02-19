<?php

include "config/init.php";
// Usage
createCSRF();
$CSRF = hash_hmac('sha256', 'registerCSRF', $_SESSION['CSRF']);

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $formCSRF = $_POST['CSRF'];
    $result = $account->Register($username, $password, $email, $ygntime, $CSRF, $formCSRF);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn-Out</title>
</head>

<body>

    <?php if (isset($result['ErrorMsg'])) {
        echo $result['ErrorMsg'];
    } ?>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">

        <input type="hidden" name="CSRF" value="<?= $CSRF ?>">

        <input type="text" name="username" id="">

        <input type="email" name="email" id="">

        <input type="password" name="password" id="">

        <button name="register">Register</button>

    </form>

</body>

</html>