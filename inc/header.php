<!DOCTYPE html public>
<html>
	<head>
		<title>Guestbook</title>
		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.1.1/css/bootstrap-combined.min.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script>
			$(document).on("click", "[data-confirm]", function() {
				if(confirm(this.getAttribute('data-confirm'))) {
					document.location.href = this.getAttribute('href');
				}

				return false;
			});
		</script>
	</head>
	<body>
		<div class="container">
			<h1><a href="/">Guestbook</a></h1>

			<?php
				$_flash = $_SESSION['flash' . $_GET['_fid']];
				if($_flash) {
					echo "<div class='alert alert-{$_flash['type']}'>{$_flash['message']}</div>";
				}
			?>
