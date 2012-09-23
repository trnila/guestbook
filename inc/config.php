<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 'on;');

$pdo = new PDO("mysql:dbname=guestbook;host=localhost", "guestbook", "guestbook");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function flashMessage($message, $type = 'info') {
	$id = mt_rand();
	$_SESSION['flash' . $id] = array(
		'message' => $message,
		'type' => $type
	);

	return $id;
}

function getflashMessages($id) {
	return $_SESSION['flash' . $id];
}