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
            $this->name = escape(trim($name));
            $this->mail = escape(trim($mail));
            $this->pass = trim($pass);
            $this->date = trim($date);

            // Check User Information Not To Empty
            if ((empty($this->name)) || (empty($this->pass)) || (empty($this->mail))) {
                return ['ErrorMsg' => '<p style="color:#f00;"> Fill your informations . . .</p>'];
            }

            // Check User Name is valid or Not
            if (!preg_match("/^([A-Za-z]+ )+[A-Za-z]+$|^[A-Za-z]+$/", $this->name)) {
                return ['ErrorMsg' => '<p style="color:#f00;"> Only alphabets and whitespace are allowed.</p>']; //Username is valid 
            }

            // Check CSRF Token If Developer Use
            if (isset($CSRF)) {
                if (!hash_equals($CSRF, $formCSRF)) {
                    return ['ErrorMsg' => '<p style="color:#f00;margin-bottom:3px;">Invalid CSRF 
                    <small style="opacity:0.6;margin-bottom:2px;color:#222;display:block;"> Please try to resubmit the form.</small>
                   </p>'];
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

            
            $token    = 'QWERTYUIOPASDFGHJKLZXCVBNM123456789';
            $token    = str_shuffle($token);
            $token    = substr($token, 0, 25);
            $salt     = substr(str_shuffle($token), 0, 10);
            $this->hash_pass = password_hash($salt.$this->pass, PASSWORD_DEFAULT);

            $stmtRegister = $this->pdo->prepare("INSERT INTO users (username,password,salt,email,token,created_date) VALUES(:name,:pass,'$salt',:mail,'$token',:date)");
            //BIND VALUES
            $stmtRegister->bindValue(':name', htmlspecialchars($this->name), PDO::PARAM_STR);
            $stmtRegister->bindValue(':mail', strtolower($this->mail));
            $stmtRegister->bindValue(':pass', $this->hash_pass, PDO::PARAM_STR);
            $stmtRegister->bindValue(':date', htmlspecialchars($this->date), PDO::PARAM_STR);
            // Execute
            $insertUser = $stmtRegister->execute();
            if ($insertUser) {
                unset($_SESSION['CSRF']);
                header("location:redirect/success.html");
            } else {
                return ['ErrorMsg' => '<p style="color:#f00;"> Something Went Wrong!</p>'];
            }
        } catch (\Throwable $th) {
            echo $th;
        }
    }

    public function LogIn($pass, $mail, $date, $CSRF, $formCSRF)
    {
        try {
            $this->pass = trim($pass);
            $this->mail = trim($mail);
            $this->date = trim($date);

            // Check User Information Not To Empty
            if ((empty($this->pass)) || (empty($this->mail))) {
                return ['ErrorMsg' => '<p style="color:#f00;"> Fill your informations . . .</p>'];
            }

            // Check CSRF Token If Developer Use
            if (isset($CSRF)) {
                if (!hash_equals($CSRF, $formCSRF)) {
                    return ['ErrorMsg' => '<p style="color:#f00;margin-bottom:3px;">Invalid CSRF 
                     <small style="opacity:0.6;margin-bottom:2px;color:#222;display:block;"> Please try to resubmit the form.</small>
                    </p>'];
                }
            }

            // Check User Mail is Valid or Not
            if (filter_var($this->mail, FILTER_VALIDATE_EMAIL)) {
                $checkMail = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
                $checkMail->execute([$this->mail]);
                if ($checkMail->rowCount() === 1) {
                    // Get user row if email is exist.
                    $row = $checkMail->fetch(PDO::FETCH_ASSOC);
                    // Check password is ture or not
                    $match_pass = password_verify($row['salt'].$this->pass, $row['password']);
                    
                    if($match_pass){
                      setcookie('auth',$row['username'],time()+3600,'/');
                      setcookie('token',$row['token'],time()+3600,'/');
                      unset($_SESSION['CSRF']);
                      header("location:index.php");
                    }else{
                        return ['ErrorMsg' => '<p style="color:#f00;"> Incorrect Password !</p>'];
                    }
                }else{
                    return ['ErrorMsg' => '<p style="color:#f00;"> Your mail doesn\'t register.</p>'];
                }
            } else {
                return ['ErrorMsg' => '<p style="color:#f00;"> Invalid Email format.</p>'];
            }
        } catch (\Throwable $th) {
            echo $th;
        }
    }
}
