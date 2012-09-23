<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 'on;');

$pdo = new PDO("mysql:dbname=guestbook;host=localhost", "guestbook", "guestbook");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);