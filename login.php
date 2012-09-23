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
	if(isset($_GET['registration'])) {
		$pw1 = $_POST['password'];
		$pw2 = $_POST['password2'];

		if($pw1 != $pw2) {
			$registrationError = "Hesla se neshodují";
		}
		else {
			if(!preg_match('/^[a-zA-Z.-_0-9]+@[a-zA-Z.-_0-9]+\.[a-z]{2,5}$/', $_POST['email'])) {
				$registrationError = 'Nejedná se o validní email.';
			}
			else if(strlen($pw1) < 3) {
				$registrationError = 'Zadejte delší heslo než 3 znaky.';
			}
			else {
				$query = $pdo->prepare('INSERT INTO users(email, password) VALUES(?, ?)');
				$query->bindValue(1, $_POST['email']);
				$query->bindValue(2, hashPassword($_POST['email'], $_POST['password']));

				try {
					$query->execute();

					$id = flashMessage('Byl jste úspěšně zaregistrován.', 'success');
					$_SESSION['email'] = $_POST['email'];

					header('Location: /?_fid=' . $id);
				}
				catch(PDOException $e) {
					if($e->getCode() == 23000) {
						$registrationError = 'Uživatel s tímto emailem už existuje.';
					}
					else {
						throw $e;
					}
				}
			}
		}
	}
	else {
		$query = $pdo->prepare('SELECT email FROM users WHERE email = ? AND password = ?');
		$query->bindValue(1, $_POST['email']);
		$query->bindValue(2, hashPassword($_POST['email'], $_POST['password']));

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
}
?>
<div class="row-fluid">
	<div class="span6">
		<form action="login.php" method="post">
			<legend>Přihlášení</legend>
			<input type="email" name="email" placeholder="Váš e-mail" value="<?php echo isset($_POST['email']) && !isset($_GET['registration']) ? $_POST['email'] : '' ?>"><br>
			<input type="password" name="password" placeholder="Vaše heslo" required><br>
			<input type="submit" value="Přihlásit se" class="btn btn-primary">
		</form>
	</div>

	<div class="span6">
		<form action="login.php?registration" method="post">
			<legend>Registrace</legend>
			<?php if(isset($registrationError)) {
				echo "<div class='alert alert-error'>{$registrationError}</div>";
			} ?>
			<input type="email" name="email" placeholder="Váš e-mail" required value="<?php echo isset($_POST['email']) && isset($_GET['registration']) ? $_POST['email'] : '' ?>"><br>
			<input type="password" name="password" placeholder="Vaše heslo" required><br>
			<input type="password" name="password2" placeholder="Vaše heslo pro kontrolu" required><br>
			<input type="submit" value="Registrovat" class="btn btn-primary">
		</form>
	</div>
</div>
</div>

<?php
	include __DIR__ . '/inc/footer.php';
