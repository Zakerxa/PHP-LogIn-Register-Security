<?php

include "config/init.php";

$CSRF = hash_hmac('sha256', 'registerCSRF', $_SESSION['CSRF']);
$err = '';
if (isset($_POST['login'])) {
    $result = $account->LogIn($_POST['password'], $_POST['email'], $ygntime, $CSRF, $_POST['CSRF']);
    if (isset($result['ErrorMsg'])) {
        $err =  $result['ErrorMsg'];
    }
}

if (isset($_COOKIE['auth'])) {
    $users = $pdo->prepare("SELECT * FROM users WHERE token = ?");
    $user  = $users->execute([$_COOKIE['token']]);
    $user  = $users->fetch(PDO::FETCH_OBJ);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="PHP Login-Register With Security" />
    <meta property="og:description" content="This Login-Register form is prevented CSRF,XSS Attack & SQL Injection." />
    <meta property="og:image" content="photo/1_zNRoyQ92EUrW8EbaNXT6vQ.png" />
    <meta property="og:image:type" content="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>LogIn-Register</title>
</head>

<body>

    <div class="container">
        <?php if (isset($_COOKIE['auth'])) { ?>
            <div class="row justify-content-center" style="min-height: 95vh;">
                <div class="col-11 col-sm-10 col-md-9 col-lg-8 card shadow p-3 align-self-center">
                    <h1 class="p-1">Congratulation!</h1>
                    <p class="p-2">Now you are Login User.Here is your Informations</p>
                    <div class="p-1">

                        <div class="fw-bold text-muted bg-light p-2 pt-3">
                            <p class="p-1">Name : <?= $user->username ?></p>
                            <p class="p-1">Email : <?= $user->email ?></p>
                            <p class="p-1">Token : <?= $user->token ?></p>
                            <p class="p-1">Date : <?= $user->created_date ?></p>

                        </div>
                        <span class="text-muted p-2 d-block mt-3">Login user only can see this message.</span>
                        <div class="text-end pt-3">
                            <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');" class="text-end"><button class="btn btn-danger">LogOut</button></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row justify-content-center" style="min-height: 95vh;">
                <div class="col-10 col-sm-8 col-md-6 col-lg-4 card p-3 shadow align-self-center ">
                    <div class="row justify-content-center">

                        <div class="col-11 pb-3 text-center">
                            <h2>LogIn</h2>
                            <img src="photo/profile.png" width="50%" alt="">
                        </div>

                        <div class="col-11">
                            <?= $err ?>
                        </div>

                        <div class="col-11 text-center">

                            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" autocomplete="off">

                                <input class="d-block w-100 m-3 form-floating" type="hidden" name="CSRF" value="<?= $CSRF ?>">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" autocomplete="off" name="email" id="email" placeholder="name@example.com">
                                    <label for="email">Email address</label>
                                </div>
                                <div class="form-floating">
                                    <input type="password" class="form-control" autocomplete="off" name="password" id="password" placeholder="Password">
                                    <label for="password">Password</label>
                                </div>
                                <div class="pt-4 pb-3">
                                    <button class="btn btn-dark w-100 p-2" name="login">LogIn</button>
                                </div>

                                <div class="text-end">Don't have an acoount? <a href="register.php" style="text-decoration: none;">Register</a></div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <script>
        if (window.history.replaceState) window.history.replaceState(null, null, window.location.href);
    </script>
</body>

</html>