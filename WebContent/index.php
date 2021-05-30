<?php
session_start ();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Breizh Mahjong Recorder</title>

<!-- Bootstrap -->
<link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="lib/DataTables/datatables.min.css" />

<!-- Select2 -->
<link rel="stylesheet" type="text/css" href="lib/select2-4.0.2/css/select2.min.css" />
<link rel="stylesheet" type="text/css" href="lib/select2-4.0.2/css/select2-bootstrap.min.css" />

<!-- Custom CSS -->
<link href="css/style.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<?php
require_once ("db_php/query_common.php");
require_once ("db_php/query_login_logout.php");

if (isset ($_COOKIE [COOKIE_NAME_ID])) {
	decryptCookie($_COOKIE [COOKIE_NAME_ID]);
	$isLogin = isset ($_SESSION[SESSION_LOG_IN_ID]);
	if($isLogin) {
		$loginId = $_SESSION[SESSION_LOG_IN_ID];
		$isAdmin = $_SESSION[SESSION_IS_ADMIN];
	} else {
		$loginId = 0;
		$isAdmin = false;
	}
} else {
	$isLogin = false;
	$loginId = 0;
	$isAdmin = false;
}

if (isset ($_GET ["menu"])) {
	$menu = $_GET ["menu"];
	if (($menu == "manage" && !$isAdmin) or ($menu == "newgame" && !$isLogin)) {
		$menu = "ranking";
	}
} else {
	$menu = "ranking";
}
?>
	<script type="text/javascript">
		var isAdmin = "<?php echo $isAdmin; ?>";
	</script>

	<div id="parent">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/bmjc"> <img src="images/logo_render_small.png" />
					</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li <?php if (!$isAdmin) { echo "class=\"disabled\""; } ?> <?php if ($menu === "manage") { echo "class=\"active\""; }?>><a href="?menu=manage">Admin</a></li>
						<li <?php if (!$isLogin) { echo "class=\"disabled\""; } ?> <?php if ($menu === "newgame") { echo "class=\"active\""; }?>><a href="?menu=newgame">Nouvelle Partie</a></li>
						<li <?php if ($menu === "ranking") { echo "class=\"active\""; }?>><a href="?menu=ranking">Classements</a></li>
						<li <?php if ($menu === "personal_analyze") { echo "class=\"active\""; }?>><a href="?menu=personal_analyze">Analyse joueur</a></li>
						<li <?php if ($menu === "score_analyze") { echo "class=\"active\""; }?>><a href="?menu=score_analyze">Analyse score</a></li>
						<li <?php if ($menu === "history") { echo "class=\"active\""; }?>><a href="?menu=history">Historique</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<?php if($isLogin) { ?>
						<li>
						<?php
							$avatarPath1 = '../wp-content/uploads/ultimatemember/' . $loginId . '/profile_photo-40x40.jpg';
							$avatarPath2 = '../wp-content/uploads/ultimatemember/' . $loginId . '/profile_photo-40.jpg';
							$avatarPath3 = '../wp-content/uploads/ultimatemember/' . $loginId . '/profile_photo-40x40.png';
							$avatarPath4 = '../wp-content/uploads/ultimatemember/' . $loginId . '/profile_photo-40.png';
							if(file_exists($avatarPath1)) {
								echo "<img class=\"navbar-brand\" src=\"" . $avatarPath1 . "\"/>";
							} else if(file_exists($avatarPath2)) {
								echo "<img class=\"navbar-brand\" src=\"" . $avatarPath2 . "\"/>";
							} else if(file_exists($avatarPath3)) {
								echo "<img class=\"navbar-brand\" src=\"" . $avatarPath3 . "\"/>";
							} else if(file_exists($avatarPath4)) {
								echo "<img class=\"navbar-brand\" src=\"" . $avatarPath4 . "\"/>";
							} else {
								echo "<img class=\"navbar-brand\" src=\"../wp-content/uploads/2018/07/ouest-riichi.png\"/>";
							}
							?>
						</li>
						<li><button id="logoutButton" type="button" class="btn btn-default navbar-btn" onclick="logoutEvent()">
								<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Déconnexion
							</button></li>
						<?php } else { ?>
						<li><button id="loginButton" href="#modal" type="button" class="btn btn-success navbar-btn">
								<span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Connexion
							</button></li>
						<?php } ?>
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container-fluid -->
		</nav>

		<div id="content">
			<?php include ("page_php/" . $menu . ".php"); ?>
		</div>
	</div>

	<footer>
		<p align="center">
			Authors : Pierric Willemet, Yulong Zhao @ <a href="https://breizhmahjong.fr/">Breizh Mahjong</a>
	</footer>

	<div id="modal" class="popupContainer">
		<header class="popupHeader">
			<span class="header_title">Authentification</span> <span class="modal_close"><span class="glyphicon glyphicon-remove"></span></span>
		</header>
		<section class="popupBody">
			<form id="loginForm">
				<p id="loginError"></p>
				<label for="loginInput">Identifiant : </label>
				<div class="input-group">
					<input id="loginInput" type="text" class="form-control" aria-describedby="basic-addon2" />
				</div>
				<br /> <label for="passwordInput">Mot de passe : </label>
				<div class="input-group">
					<input id="passwordInput" type="password" class="form-control" aria-describedby="basic-addon2" />
				</div>
				<br />
				<button id="validateLoginButton" type="button" class="btn btn-primary" onclick="loginEvent()" aria-label="Left Align">Se connecter</button>
			</form>
		</section>
	</div>

	<script src="lib/jquery-1.12.3.min.js"></script>
	<script src="lib/jquery.leanModal.min.js"></script>
	<script src="lib/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="lib/DataTables/datatables.min.js"></script>
	<script type="text/javascript" src="lib/select2-4.0.2/js/select2.min.js"></script>
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="page_js/main.js"></script>
	<script src="page_js/<?php echo $menu; ?>.js"></script>
</body>
</html>