<?php
include "NotORM.php";
// $user = "id16436519_marcelo";
// $pass="vn^e<(|HM{WXN5@l";
// $connection = new PDO('mysql:host=localhost;dbname=id16436519_reports_db;charset=utf8', $user, $pass);
$user = "marcelo";
$pass="TMtz29jVoe(_goqK";
$connection = new PDO('mysql:host=localhost;dbname=reports_db;charset=utf8', $user, $pass);

$db = new NotORM($connection);
?> 