<?php
	session_start();

	$page='';
	if (isset($_GET['url']) && !empty($_GET['url'])){
		$url=explode('/',$_GET['url']);
		$page=$url[0];
	}


	if ($page=='api'){
		//workflow/api/tasks/gettasks/equipe
		//route to api manager
		$object= (!empty($url[1])) ? $url[1] : '';
		$service= (!empty($url[2])) ? $url[2] : '';
		$param1= (!empty($url[3])) ? $url[3] : '';
		require('api/config/controller.php');
	}else{

		//route to frontend managers
		if (!empty($url[1])){$type=$url[1];}
		if (!empty($url[2])){$id=$url[2];}

		if (isset($_SESSION['id'])) {
			if ($page !=''){
				$controllers=array('dashboard','history','members');
				if (in_array($page,$controllers)){
					$controller=$page;
				}else{
					$controller='pagenotfound';
				}
			}else{
				$controller="dashboard";
			}
		}else {
		  $controller="landing";
		}

		//call appropriate controller
		require('controllers/'. $controller .'.php');
	}
