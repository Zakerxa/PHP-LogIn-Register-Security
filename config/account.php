<?php

class Account
{
    protected $name;
    protected $pass;
    protected $mail;
    protected $date;
    protected $csrf;
    protected $pdo;
    protected $hash_pass;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function Register($name, $pass, $mail, $date, $CSRF, $formCSRF)
    {
        try {
            $this->name = trim($name);
            $this->pass = trim($pass);
            $this->mail = trim($mail);
            $this->date = trim($date);

            // Check User Information Not To Empty
            if ((empty($this->name)) || (empty($this->pass)) || (empty($this->mail))) {
                return ['ErrorMsg' => '<p style="color:yellow;"> Fill your informations...</p>'];
            }

            // Check User Name is valid or Not
            if (!preg_match("/^([A-Za-z]+ )+[A-Za-z]+$|^[A-Za-z]+$/", $this->name)) {
                return ['ErrorMsg' => '<p style="color:#f00;"> Only alphabets and whitespace are allowed.</p>']; //Username is valid 
            }

            // Check CSRF Token If Developer Use
            if (isset($CSRF)) {
                if (!hash_equals($CSRF, $formCSRF)) {
                    return ['ErrorMsg' => '<p style="color:#f00;margin-bottom:4px;">Invalid CSRF </p><small class="text-muted"> The CSRF token is invalid or missing.Please try to resubmit the form.</small>'];
                }
            }

            // Check User Mail is Valid or Not
            if (filter_var($this->mail, FILTER_VALIDATE_EMAIL)) {
                $checkMail = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
                $checkMail->execute([$this->mail]);
                if ($checkMail->rowCount() >= 1) {
                    return ['ErrorMsg' => '<p style="color:#f00;"> Your mail is already registered.Please Try another.</p>'];
                }
            } else {
                return ['ErrorMsg' => '<p style="color:#f00;"> Invalid Email format.</p>'];
            }

            $this->hash_pass = password_hash($this->pass, PASSWORD_DEFAULT);

            $stmtRegister = $this->pdo->prepare("INSERT INTO users (username,password,email,confirm,created_date) VALUES(:name,:pass,:mail,0,:date)");
            //BIND VALUES
            $stmtRegister->bindValue(':name', htmlspecialchars($this->name), PDO::PARAM_STR);
            $stmtRegister->bindValue(':mail', strtolower($this->mail));
            $stmtRegister->bindValue(':pass', $this->hash_pass, PDO::PARAM_STR);
            $stmtRegister->bindValue(':date', htmlspecialchars($this->date), PDO::PARAM_STR);
            // Execute
            $insertUser = $stmtRegister->execute();
            if ($insertUser) {
                unset($_SESSION['CSRF']);
                header("location:success.php");
            } else {
                return ['ErrorMsg' => '<p style="color:#f00;"> Something Went Wrong!</p>'];
            }
        } catch (\Throwable $th) {
            echo $th;
        }
    }
}
