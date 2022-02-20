<?php

include "config/init.php";

$CSRF = hash_hmac('sha256', 'registerCSRF', $_SESSION['CSRF']);
$err = '';
if (isset($_POST['register'])) {
    $result = $account->Register($_POST['username'], $_POST['password'], $_POST['email'], $ygntime, $CSRF, $_POST['CSRF']);
    if (isset($result['ErrorMsg'])) {
        $err =  $result['ErrorMsg'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Register</title>
</head>

<body>

    <div class="container">
        <?php if (isset($_COOKIE['auth'])) {
            header("location:../");
        } else { ?>
            <div class="row justify-content-center" style="min-height: 95vh;">
                <div class="col-10 col-sm-8 col-md-6 col-lg-4 card p-3 shadow align-self-center ">
                    <div class="row justify-content-center">

                        <div class="col-11 pb-3 text-center">
                            <h2>Register</h2>
                            <img src="photo/profile.png" width="50%" alt="">
                        </div>

                        <div class="col-11">
                            <?= $err ?>
                        </div>

                        <div class="col-11 text-center">

                            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" autocomplete="off">

                                <input class="d-block w-100 m-3 form-floating" type="hidden" name="CSRF" value="<?= $CSRF ?>">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" autocomplete="off" name="username" id="username" placeholder="Username">
                                    <label for="username">Username</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" autocomplete="off" name="email" id="email" placeholder="name@example.com">
                                    <label for="email">Email address</label>
                                </div>
                                <div class="form-floating">
                                    <input type="password" class="form-control" autocomplete="off" name="password" id="password" placeholder="Password">
                                    <label for="password">Password</label>
                                </div>
                                <div class="pt-4 pb-3">
                                    <button class="btn btn-dark w-100 p-2" name="register">Register</button>
                                </div>

                                <div class="text-end">Already have an acoount? <a href="index.php" style="text-decoration: none;">LogIn</a></div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <script>
        if (window.history.replaceState)  window.history.replaceState(null, null, window.location.href);
    </script>
</body>

</html>