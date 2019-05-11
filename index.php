<?php
	session_start();

	//if user already connected, redirection to dashboard
	if(isset($_SESSION['id'])){
		header('Location: /workflow/dashboard.php');
		exit;
	}

	function loadClass($class)
  {
    require 'models/' . $class . '.php'; // On inclut la classe correspondante au paramètre passé.
  }

  spl_autoload_register('loadClass');
  $user_manager=new userManager();


	if (isset($_POST['email_co'])){
		$user_manager->connect(
			$_POST['email_co'],
			$_POST['password_co']
		);
	}
	if (isset($_POST['firstname'])){
		$user_manager->register(
			$_POST['firstname'],
			$_POST['lastname'],
			$_POST['email'],
			$_POST['password'],
			$_POST['avatar']
		);
	}

	if (isset($_GET['type'])){
		$type=$_GET['type'];
    switch ($type) {
			case "signin":
        $signin=true;
        break;
			case "signup":
        $signup=true;
        break;
		}
	}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
    <head>
      <title>Workflow</title>
		  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
			<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
			<script type="text/javascript" src="/workflow/js/main.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.js"></script>
  		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.css" />
      <link href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/icon.min.css" rel="stylesheet" type="text/css">
		  <link rel="stylesheet" type="text/css" href="/workflow/css/main.css" />
    </head>
    <body>
			<!-- Modal Connexion -->
			<?php if (isset($signin)){require("views/modalConnexion.php");} ?>

			<!-- Modal inscription -->
			<?php if (isset($signup)){require("views/modalRegistration.php");} ?>

			<!-- Page header -->
  		<?php require("views/header.php"); ?>

			<!-- Page body -->
  		<div class="main-content">
				<img src="/workflow/static/images/whiteboard-4054377_1920.jpg"/ style="width :100%;">
  		</div>
    </body>
</html>
