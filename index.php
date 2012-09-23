<?php
include __DIR__ . '/inc/config.php';

if(isset($_GET['action'])) {
	if($_GET['action'] == 'delete') {
		$query = $pdo->prepare("DELETE FROM posts WHERE id = ?");
		$query->bindValue(1, $_GET['id']);
		$query->execute();
	}
}


if(!empty($_POST)) {
	if(!$_SESSION['email']) {
		$query = $pdo->prepare("INSERT INTO posts(`text`, `name`, `created`) VALUES(?, ?, NOW())");
		$query->bindValue(1, $_POST['name']);
		$query->bindValue(2, $_POST['text']);
		
		$query->execute();
	}
	else {
		$query = $pdo->prepare("INSERT INTO posts(`text`, `created`, `user_id`) VALUES(?, NOW(), ?)");
		$query->bindValue(1, $_POST['text']);
		$query->bindValue(2, $_SESSION['email']);
		$query->execute();
	}
}

if(isset($_SESSION['email'])) {
	echo "<a href='login.php?logout'>odhlaseni</a>";
}
else {
	echo "<a href='login.php'>Prihlaseni</a>";
}?>

<form action="/" method="post">
	<?php if(!isset($_SESSION['email'])): ?>
		<input type="text" name="name" placeholder="vase jmeno"> <br>
	<?php endif  ?>
	<textarea name="text" placeholder="text"></textarea>
	<input type=submit>
</form>

<?php

$count = $pdo->query('SELECT COUNT(id) FROM posts')->fetch()[0];
$itemsPerPage = 5;
$page = $_GET['page'];

$results = $pdo->query('SELECT * FROM posts ORDER BY created DESC LIMIT ' . $page * $itemsPerPage . ', ' . $itemsPerPage);

foreach($results as $row) {
	echo '<b>' . (isset($row['user_id']) ? $row['user_id'] : $row['name']) . '</b> ' . $row['created'];
	if(isset($_SESSION['email']) && $_SESSION['email'] == $row['user_id']) {
		echo '<a href="?action=delete&id=' . $row['id'] . '">x</a>';
	}
	echo '<br>';

	echo $row['text'];
	echo '<hr>';


}

for($i = 0; $i < $count / $itemsPerPage; $i++) {
	echo "<a href='/?page={$i}'>{$i}</a> ";
}
