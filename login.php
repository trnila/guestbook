<?php
include __DIR__ . '/inc/config.php';
include __DIR__ . '/inc/header.php';

if(isset($_GET['logout'])) {
	unset($_SESSION['email']);
	$id = flashMessage("Byl jste odhlášen.", "success");
	header("Location: /?_fid=" . $id);
}

echo '<div class="well">';
if(!empty($_POST)) {
	$query = $pdo->prepare('SELECT email FROM users WHERE email = ? AND password = ?');
	$query->bindValue(1, $_POST['email']);
	$query->bindValue(2, $_POST['password']);

	$query->execute();

	$result = $query->fetch();

	if($result[0]) {
		$_SESSION['email'] = $_POST['email'];

		$id = flashMessage("Byl jste úspěšně přihlášen.", "success");
		header('Location: /?_fid=' . $id);
	}
	else {
		echo "<div class='alert alert-error'>Špatné jméno nebo heslo.</div>";
	}
}
?>
	<form action="" method="post">
		<input type="email" name="email" placeholder="Váš e-mail" required><br>
		<input type="password" name="password" placeholder="Vaše heslo" required><br>
		<input type="submit" value="Přihlásit se" class="btn btn-primary">
	</form>
</div>

<?php
	include __DIR__ . '/inc/footer.php';
