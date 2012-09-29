<?php
include __DIR__ . '/inc/config.php';
include __DIR__ . '/inc/header.php';

if(isset($_GET['action'])) {
	if($_GET['action'] == 'delete') {
		if(!$_SESSION['email']) {
			header('Status: 403 Forbidden');
			echo '<div class="alert alert-error">Nelze smazat tento příspěvek.</div>';
			exit;
		}

		$query = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
		$query->bindValue(1, $_GET['id']);
		$query->bindValue(2, $_SESSION['email']);
		$query->execute();

		if($query->rowCount() >= 1) {
			$id = flashMessage("Příspěvek byl smazán.", "success");
		}
		else {
			$id = flashMessage("Příspěvek se nepodařilo smazat.", "error");
		}

		header('Location: /?_fid=' . $id);
	}
}


if(!empty($_POST)) {
	if(!isset($_SESSION['email']) || !$_SESSION['email']) {
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

	$id = flashMessage('Příspěvěk byl přídán.', 'success');
	header('Location: /?_fid=' . $id);
}

if(isset($_SESSION['email'])) {
	echo '<b>' . $_SESSION['email'] .  "</b> - <a href='login.php?logout'>odhlásit se</a>";
}
else {
	echo "<a href='login.php'>přihlásit se</a>";
}?>

<form action="/" method="post">
	<?php if(!isset($_SESSION['email'])): ?>
		<input type="text" name="name" placeholder="vase jmeno" required> <br>
	<?php endif  ?>
	<textarea name="text" placeholder="text" style="width: 100%; height: 150px" required></textarea> <br>
	<input type="submit" class="btn btn-primary" value="Přidat">
</form>

<?php

$count = $pdo->query('SELECT COUNT(id) FROM posts')->fetch();
$count = $count[0];
$itemsPerPage = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 0;
$pages = $count / $itemsPerPage;

$results = $pdo->query('SELECT * FROM posts ORDER BY created DESC LIMIT ' . $page * $itemsPerPage . ', ' . $itemsPerPage);

$iterations = 0;
foreach($results as $row) {
	$iterations++;
	echo '<b>' . (isset($row['user_id']) ? $row['user_id'] : $row['name']) . '</b> <em>' . $row['created'] . '</em>';
	if(isset($_SESSION['email']) && $_SESSION['email'] == $row['user_id']) {
		echo ' <a href="?action=delete&id=' . $row['id'] . '" data-confirm="Opravdu smazat tento příspěvek?"><i class="icon-trash"></i></a>';
	}
	echo '<br>';

	echo $row['text'];
	echo '<hr>';
}

if(!$iterations) {
	echo '<div class="alert">Momentálně zde nejsou žádný příspěvky.</div>';
}

if($pages > 1) {
	echo '<div class="pagination"><ul>';
	for($i = 0; $i < $pages; $i++) {
		echo "<li" . ($page == $i ? " class='active'" : "") . "><a href='/?page={$i}'>" . ($i+1) . "</a></li>";
	}
	echo '</ul></div>';
}

include __DIR__ . '/inc/footer.php';