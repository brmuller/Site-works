<?php
	session_start();

	$url='';
	if (isset($_GET['url']) && !empty($_GET['url'])){
		$url=explode('/',$_GET['url']);
		$page=$url[0];
		if (!empty($url[1])){$type=$url[1];}
		if (!empty($url[2])){$id=$url[2];}
	}

	if (isset($_SESSION['id'])) {
		if ($url !=''){
	    switch ($page) {
				case "dashboard":
	        $controller="dashboard";
	        break;

				case "history":
					$controller="history";
					break;

				default:
					$controller="error";
					break;
			}
		}else{
			header('Location: /workflow/dashboard/');
	  	exit;
		}
	}else {
	  $controller="landing";
	}

	//call appropriate controller
	require('controllers/'. $controller .'.php');
