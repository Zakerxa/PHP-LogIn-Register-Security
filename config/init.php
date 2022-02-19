<?php
header('Content-Type: text/html; charset=utf-8');

include "dataBase.php";
include "account.php";

session_start();
// Set To LocalTimeZone
date_default_timezone_set("Asia/Yangon");

// Initialize Class
$dbCon   = new Database();
$account = new Account($dbCon->connection());

// Create Default Time
$diffWithGMT = 6 * 60 * 60 + 30 * 60; //converting time difference to seconds.
$ygntime = gmdate("Y-m-d H:i:s", time() + $diffWithGMT);
$ygndate = gmdate("Y-F-d", time() + $diffWithGMT);

// Escape Function
function escape($html){
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

// Create CSRF TOKEN
function createCSRF(){
    if (empty($_SESSION['CSRF'])) {
        if (function_exists('random_bytes')) {
            $_SESSION['CSRF'] = bin2hex(random_bytes(32));
        } else {
            $_SESSION['CSRF'] = bin2hex(openssl_random_pseudo_bytes(32));
        }
    }
}
