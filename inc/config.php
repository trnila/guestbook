<?php
session_start();

if(!getenv("production")) {
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
}

$conf = getenv("VCAP_SERVICES");
$conf = '{"mysql-5.1":[{"name":"guestbook","label":"mysql-5.1","plan":"free","tags":["mysql","mysql-5.1","relational"],"credentials":{"name":"d49114de5142e44f08e3cb13db29e61d1","hostname":"10.180.7.135","host":"10.180.7.135","port":3306,"user":"uqHyAhA23LpKj","username":"uqHyAhA23LpKj","password":"pF70dW6EyEMff"}}]}';
if(!empty($conf)) {
	$conf = json_decode($conf);
	$credentials = $conf->{'mysql-5.1'}[0]->credentials;

	$dsn = "mysql:dbname={$credentials->name};host={$credentials->host}";
	$username = $credentials->username;
	$password = $credentials->password;
}
else {
	$dsn = "mysql:dbname=guestbook;host=localhost";
	$username = "guestbook";
	$password = "guestbook";
}

$pdo = new PDO($dsn, $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function flashMessage($message, $type = 'info') {
	$id = mt_rand();
	$_SESSION['flash' . $id] = array(
		'message' => $message,
		'type' => $type
	);

	return $id;
}