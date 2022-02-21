# PHP LogIn-Register With Security

<p>Hello Everyone! My name is Zin Min Htet. </p>
<p>I will show you how to create php LogIn-Register with Security.</p>
<p>There is also simple Installation & Usage for beginner.</p>

<br>
<br>

# Installation

### Create New Database & import users_table.sql

```bash
CREATE TABLE users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(50),
    token VARCHAR(30),
    created_date VARCHAR(50) NOT NULL
)
```

Inside the Database Class
```bash
# Insert your localhost
$db_host = "localhost";
# Your database name
$db_name = "logInOut"; 
# Username is root in localhost
$db_username = "root";
# If you don't have a password no need to be set.
$db_password = "Pass@1234";
```

<br>

# Usage

Inside the init.php

```bash
# Initialize Class
$dbCon   = new Database();
$pdo     = $dbCon->connection();
$account = new Account($pdo);
```

Inside the register.php

```bash

# $account->Register('','','','','',''); At least 6 Param
$result = $account->Register($_POST['name'], $_POST['pass'], $_POST['mail'], $time, $CSRF, $_POST['CSRF']);

# If you don't wanna use CSRF, give value to null or ''.
$result = $account->Register($_POST['name'], $_POST['pass'], $_POST['mail'], $time, '', '');

# You can also catch back the error message like that.
if (isset($result['ErrorMsg'])) {
    $err =  $result['ErrorMsg'];
}
    
```

Inside the index.php or login.php

```bash

# $account->LogIn('','','','',''); At least 5 Param
$result = $account->LogIn($_POST['pass'], $_POST['mail'], $time, $CSRF, $_POST['CSRF']);

# If you don't wanna use CSRF, give value to null or ''.
$result = $account->LogIn($_POST['pass'], $_POST['mail'], $time, '', '');

# You can also catch back the error message like that.
if (isset($result['ErrorMsg'])) {
    $err =  $result['ErrorMsg'];
}

```

<br>

# Live Demo
URL : [http://account.zakerxa.com](http://account.zakerxa.com)

<br>

## Customize

If you wanna customize more features learn from [account.php](https://github.com/Zakerxa/PHP-LogIn-Register-Security/blob/main/config/account.php)
