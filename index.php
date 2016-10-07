<!DOCTYPE html>

<html lang="en">

<head>
	<!--jQuery Load-->
	<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<meta charset="UTF-8">
	<title>service</title>
	<link rel="stylesheet" href="css/style.css">
	<style type="text/css">

	</style>
</head>

<body>
	<div id="header">
		<div class="title">
			<div class="input-group input-group-lg">
				<input type="text" class="form-control" placeholder="Search for...">
				<span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
      </span>
			</div>
		</div>

		<div class="menu" role="navigation">
			<ul class="nav nav-tabs">
				<li role="presentation" class="active"><a href="#">Home</a></li>
				<li role="presentation"><a href="#">Rank</a></li>
				<li role="presentation"><a href="#">App review</a></li>
			</ul>
		</div>
	</div>

	<div id="container">
		<div class="content">
			<?php include 'realtime_ranking.php';?>
		</div>
	</div>

	<div id="footer">

	</div>
	<script>
		$(".nav a").on("click", function () {
			$(".nav").find(".active").removeClass("active");
			$(this).parent().addClass("active");
		});
	</script>
</body>

</html>
