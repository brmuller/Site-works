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

	$current_page=array(
    "id" => "index",
    "name" => "Outil de gestion de Process"
  );

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

	//define items to include in the page view
	if (isset($signin)){$modals[]='modalConnexion.php';}
	if (isset($signup)){$modals[]='modalRegistration.php';}

	//call template and display above items in the page
	require('views/template.php');
