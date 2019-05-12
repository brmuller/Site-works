<?php
	session_start();

	//if user already connected, redirection to dashboard
	if(isset($_SESSION['id'])){
		header('Location: /workflow/dashboard.php');
		exit;
	}

	spl_autoload_register(function ($class) {
    include 'models/' . $class . '.php';
  });

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

	require('views/template.php');
?>
