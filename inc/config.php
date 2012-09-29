<?php
session_start();

date_default_timezone_set('Europe/Prague');

error_reporting(E_ALL);
if(getenv("development")) {
	ini_set('display_errors', 'on');
}
else {
	ini_set('display_errors', 'off');
	ini_set('error_log', __DIR__ . '/errors.log');
}

$conf = getenv("VCAP_SERVICES");
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
$pdo->query('SET time_zone = "' . date('P') . '"');

function flashMessage($message, $type = 'info') {
	$id = mt_rand();
	$_SESSION['flash' . $id] = array(
		'message' => $message,
		'type' => $type
	);

	return $id;
}

function hashPassword($email, $password)
{
	return sha1(md5($password . 'guestbook') . $email);
}