<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Guestbook</title>
		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.1.1/css/bootstrap-combined.min.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style>
			.post .author {
				font-weight: bold;
				margin-right: 5px;
			}

			.post time {
				color: #999;
			}
		</style>
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
				if(isset($_GET['_fid'])) {
					$_flash = isset($_SESSION['flash' . $_GET['_fid']]) ? $_SESSION['flash' . $_GET['_fid']] : NULL;
					if($_flash) {
						echo "<div class='alert alert-{$_flash['type']}'>{$_flash['message']}</div>";
					}
				}
			?>
