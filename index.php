<?php
	session_start();

	$url='';
	if (isset($_GET['url']) && !empty($_GET['url'])){
		$url=explode('/',$_GET['url']);
		$page=$url[0];
	}


	if ($url !='' && $page=='api'){
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
			if ($url !=''){
		    switch ($page) {
					case "dashboard":
		        $controller="dashboard";
		        break;

					case "history":
						$controller="history";
						break;

					default:
						$controller="dashboard";
						break;
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
