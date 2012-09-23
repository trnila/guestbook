<?php
include __DIR__ . '/inc/config.php';

if(isset($_GET['logout'])) {
	unset($_SESSION['email']);
	echo 'Byl jste odhlasen';
}

if(!empty($_POST)) {
	$query = $pdo->prepare('SELECT email FROM users WHERE email = ? AND password = ?');
	$query->bindValue(1, $_POST['email']);
	$query->bindValue(2, $_POST['password']);

	$query->execute();

	if($query->fetch()[0]) {
		$_SESSION['email'] = $_POST['email'];
	}
	else {
		echo "Spatne jmeno/heslo<br>";
	}
}
?>

<form action="" method="post">
	<input type="text" name="email" placeholder="Vase email"><br>
	<input type="password" name="password" placeholder="Vase heslo"><br>
	<input type="submit" value="Prihlasit">
</form>